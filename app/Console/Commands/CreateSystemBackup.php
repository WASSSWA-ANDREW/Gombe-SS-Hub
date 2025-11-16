<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class CreateSystemBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create {--type=all : Type of backup (database, files, all)} {--retention=12 : Number of months to keep backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a comprehensive system backup including database and uploaded files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting system backup...');

        $type = $this->option('type');
        $retentionMonths = (int) $this->option('retention');

        try {
            // Create backups directory if it doesn't exist
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
                $this->info('📁 Created backups directory');
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $backupFileName = "gombe_ss_backup_{$timestamp}.zip";
            $backupFilePath = $backupPath . DIRECTORY_SEPARATOR . $backupFileName;

            $zip = new ZipArchive();
            if ($zip->open($backupFilePath, ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create backup archive');
            }

            $backupCreated = false;

            // Backup database if requested
            if ($type === 'all' || $type === 'database') {
                $this->info('💾 Creating database backup...');
                $dbBackupPath = $this->createDatabaseBackup($timestamp);
                if ($dbBackupPath) {
                    $zip->addFile($dbBackupPath, 'database/' . basename($dbBackupPath));
                    $backupCreated = true;
                    $this->info('✅ Database backup added to archive');
                }
            }

            // Backup files if requested
            if ($type === 'all' || $type === 'files') {
                $this->info('📁 Adding uploaded files to backup...');
                $filesAdded = $this->addFilesToBackup($zip);
                if ($filesAdded > 0) {
                    $backupCreated = true;
                    $this->info("✅ Added {$filesAdded} files to backup");
                }
            }

            // Add metadata
            $this->addBackupMetadata($zip, $type, $timestamp);

            $zip->close();

            if ($backupCreated) {
                // Clean up old backups
                $this->cleanupOldBackups($retentionMonths);

                $fileSize = filesize($backupFilePath);
                $fileSizeMB = round($fileSize / 1024 / 1024, 2);

                $this->info("🎉 Backup completed successfully!");
                $this->info("📦 Backup file: {$backupFileName}");
                $this->info("📏 File size: {$fileSizeMB} MB");
                $this->info("📍 Location: storage/app/backups/");

                Log::info('System backup created', [
                    'file' => $backupFileName,
                    'size' => $fileSize,
                    'type' => $type
                ]);

                return 0;
            } else {
                $this->error('❌ No backup data was created');
                if (file_exists($backupFilePath)) {
                    unlink($backupFilePath);
                }
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Backup failed: ' . $e->getMessage());
            Log::error('System backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Create database backup
     */
    private function createDatabaseBackup($timestamp)
    {
        try {
            $dbConfig = config('database.connections.mysql');
            $dbName = $dbConfig['database'];
            $dbUser = $dbConfig['username'];
            $dbPass = $dbConfig['password'];
            $dbHost = $dbConfig['host'];

            $backupFile = storage_path("app/backups/temp_db_backup_{$timestamp}.sql");

            // Use mysqldump command (works on Windows with MySQL)
            $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > \"{$backupFile}\" 2>nul";

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($backupFile)) {
                return $backupFile;
            } else {
                // Fallback: try alternative mysqldump path
                $command = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\" --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > \"{$backupFile}\"";
                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($backupFile)) {
                    return $backupFile;
                }

                throw new \Exception('Database backup failed - mysqldump not found or access denied');
            }
        } catch (\Exception $e) {
            $this->error('Database backup failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Add uploaded files to backup
     */
    private function addFilesToBackup(ZipArchive $zip)
    {
        $filesAdded = 0;

        // Backup pass slips and other uploaded files
        $uploadDirectories = [
            'storage/app/public/pass_slips' => 'uploads/pass_slips',
            'storage/app/public' => 'uploads/public',
            'storage/app/private' => 'uploads/private'
        ];

        foreach ($uploadDirectories as $sourceDir => $zipPath) {
            $fullSourcePath = base_path($sourceDir);

            if (file_exists($fullSourcePath)) {
                $this->addDirectoryToZip($zip, $fullSourcePath, $zipPath);
                $filesAdded += $this->countFilesInDirectory($fullSourcePath);
            }
        }

        return $filesAdded;
    }

    /**
     * Add directory contents to ZIP
     */
    private function addDirectoryToZip(ZipArchive $zip, $sourceDir, $zipPath)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sourceDir) + 1);
                $zip->addFile($filePath, $zipPath . '/' . $relativePath);
            }
        }
    }

    /**
     * Count files in directory
     */
    private function countFilesInDirectory($directory)
    {
        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Add backup metadata
     */
    private function addBackupMetadata(ZipArchive $zip, $type, $timestamp)
    {
        $metadata = [
            'backup_type' => $type,
            'created_at' => now()->toISOString(),
            'timestamp' => $timestamp,
            'system_info' => [
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'server_os' => PHP_OS
            ],
            'database_info' => [
                'connection' => config('database.default'),
                'name' => config('database.connections.mysql.database')
            ]
        ];

        $zip->addFromString('backup_metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
    }

    /**
     * Clean up old backups
     */
    private function cleanupOldBackups($retentionMonths)
    {
        $backupPath = storage_path('app/backups');
        $cutoffDate = now()->subMonths($retentionMonths);

        if (file_exists($backupPath)) {
            $files = glob($backupPath . DIRECTORY_SEPARATOR . 'gombe_ss_backup_*.zip');

            foreach ($files as $file) {
                $fileDate = $this->extractDateFromBackupFile($file);
                if ($fileDate && $fileDate->lt($cutoffDate)) {
                    unlink($file);
                    $this->info("🗑️  Cleaned up old backup: " . basename($file));
                }
            }
        }

        // Clean up temporary database files
        $tempFiles = glob($backupPath . DIRECTORY_SEPARATOR . 'temp_db_backup_*.sql');
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    /**
     * Extract date from backup filename
     */
    private function extractDateFromBackupFile($filePath)
    {
        $filename = basename($filePath);
        if (preg_match('/gombe_ss_backup_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})\.zip/', $filename, $matches)) {
            try {
                return Carbon::createFromFormat('Y-m-d_H-i-s', $matches[1]);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
