<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display backup management interface
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        $backupSettings = $this->getSettings('backup');

        return view('admin.backups.index', compact('backups', 'backupSettings'));
    }

    /**
     * Download a specific backup file
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (!file_exists($backupPath)) {
            return response()->json(['error' => 'Backup file not found'], 404);
        }

        // Validate filename format
        if (!preg_match('/^gombe_ss_backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/', $filename)) {
            return response()->json(['error' => 'Invalid backup filename'], 400);
        }

        return response()->download($backupPath, $filename, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Delete a backup file
     */
    public function destroy($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (!file_exists($backupPath)) {
            return response()->json(['error' => 'Backup file not found'], 404);
        }

        // Validate filename format
        if (!preg_match('/^gombe_ss_backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/', $filename)) {
            return response()->json(['error' => 'Invalid backup filename'], 400);
        }

        if (unlink($backupPath)) {
            return response()->json(['success' => true, 'message' => 'Backup deleted successfully']);
        } else {
            return response()->json(['error' => 'Failed to delete backup'], 500);
        }
    }

    /**
     * Create a new backup
     */
    public function create(Request $request)
    {
        try {
            $backupSettings = $this->getSettings('backup');

            $type = $request->get('type', $backupSettings['backup_database'] && $backupSettings['backup_files'] ? 'all' :
                                  ($backupSettings['backup_database'] ? 'database' : 'files'));

            $retentionMonths = $backupSettings['backup_retention'] ?? 12;

            // Run the backup command
            $command = "php " . base_path('artisan') . " backup:create --type={$type} --retention={$retentionMonths}";
            exec($command . " 2>&1", $output, $returnCode);

            if ($returnCode === 0) {
                // Get updated backup list
                $backups = $this->getBackupFiles();
                $latestBackup = collect($backups)->first();

                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'backup' => $latestBackup,
                    'output' => implode("\n", $output)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed: ' . implode("\n", $output)
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup metadata
     */
    public function metadata($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (!file_exists($backupPath)) {
            return response()->json(['error' => 'Backup file not found'], 404);
        }

        $zip = new ZipArchive();
        if ($zip->open($backupPath) === TRUE) {
            $metadataContent = $zip->getFromName('backup_metadata.json');
            $zip->close();

            if ($metadataContent) {
                $metadata = json_decode($metadataContent, true);
                return response()->json($metadata);
            }
        }

        return response()->json(['error' => 'Metadata not found in backup'], 404);
    }

    /**
     * Get all backup files with metadata
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (file_exists($backupPath)) {
            $files = glob($backupPath . DIRECTORY_SEPARATOR . 'gombe_ss_backup_*.zip');

            foreach ($files as $file) {
                $filename = basename($file);
                $fileSize = filesize($file);
                $fileSizeMB = round($fileSize / 1024 / 1024, 2);

                // Extract date from filename
                $createdAt = null;
                if (preg_match('/gombe_ss_backup_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})\.zip/', $filename, $matches)) {
                    try {
                        $createdAt = Carbon::createFromFormat('Y-m-d_H-i-s', $matches[1]);
                    } catch (\Exception $e) {
                        $createdAt = Carbon::createFromTimestamp(filemtime($file));
                    }
                } else {
                    $createdAt = Carbon::createFromTimestamp(filemtime($file));
                }

                // Try to get metadata from ZIP file
                $metadata = $this->getBackupMetadata($file);

                $backups[] = [
                    'filename' => $filename,
                    'size' => $fileSize,
                    'size_mb' => $fileSizeMB,
                    'created_at' => $createdAt,
                    'type' => $metadata['backup_type'] ?? 'unknown',
                    'laravel_version' => $metadata['system_info']['laravel_version'] ?? null,
                    'php_version' => $metadata['system_info']['php_version'] ?? null,
                    'download_url' => route('admin.backups.download', $filename),
                    'metadata_url' => route('admin.backups.metadata', $filename),
                    'delete_url' => route('admin.backups.destroy', $filename)
                ];
            }

            // Sort by creation date (newest first)
            usort($backups, function($a, $b) {
                return $b['created_at']->gt($a['created_at']) ? 1 : -1;
            });
        }

        return $backups;
    }

    /**
     * Get backup metadata from ZIP file
     */
    private function getBackupMetadata($filePath)
    {
        $zip = new ZipArchive();
        $metadata = [];

        if ($zip->open($filePath) === TRUE) {
            $metadataContent = $zip->getFromName('backup_metadata.json');
            if ($metadataContent) {
                $metadata = json_decode($metadataContent, true);
            }
            $zip->close();
        }

        return $metadata;
    }

    /**
     * Get settings (duplicate from SettingsController for now)
     */
    private function getSettings($category)
    {
        return \Cache::get("settings.{$category}", $this->getDefaultSettings($category));
    }

    /**
     * Get default settings
     */
    private function getDefaultSettings($category)
    {
        $defaults = [
            'backup' => [
                'auto_backup' => false,
                'backup_frequency' => 'monthly',
                'backup_time' => '02:00',
                'backup_retention' => 12,
                'backup_location' => 'local',
                'backup_database' => true,
                'backup_files' => true,
                'backup_uploads' => true
            ]
        ];

        return $defaults[$category] ?? [];
    }
}
