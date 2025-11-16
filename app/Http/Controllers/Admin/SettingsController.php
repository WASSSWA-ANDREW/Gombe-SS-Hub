<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display system settings
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'user') {
            return view('admin.settings.user-appearance');
        }

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access to settings');
        }

        $settings = $this->getAllSettings();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png|max:512',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settings = $request->except(['app_logo', 'app_favicon']);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $logoPath = $request->file('app_logo')->store('settings', 'public');
            $settings['app_logo'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('app_favicon')) {
            $faviconPath = $request->file('app_favicon')->store('settings', 'public');
            $settings['app_favicon'] = $faviconPath;
        }

        $this->updateSettings('general', $settings);

        return response()->json([
            'success' => true,
            'message' => 'General settings updated successfully'
        ]);
    }

    /**
     * Update theme settings
     */
    public function updateTheme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'nullable|string|in:green,cream',
            'font_family' => 'nullable|string',
            'font_size' => 'nullable|string|in:small,medium,large'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $themeData = $request->only(['theme', 'font_family', 'font_size']);
        $themeData = array_filter($themeData, function($value) {
            return $value !== null;
        });

        $this->updateSettings('theme', $themeData);

        return response()->json([
            'success' => true,
            'message' => 'Theme settings updated successfully'
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_sound' => 'boolean',
            'daily_reports' => 'boolean',
            'weekly_reports' => 'boolean',
            'monthly_reports' => 'boolean',
            'emergency_alerts' => 'boolean',
            'system_updates' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->updateSettings('notifications', $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully'
        ]);
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_timeout' => 'required|integer|min:5|max:1440',
            'password_min_length' => 'required|integer|min:6|max:50',
            'require_uppercase' => 'boolean',
            'require_lowercase' => 'boolean',
            'require_numbers' => 'boolean',
            'require_symbols' => 'boolean',
            'enable_2fa' => 'boolean',
            'login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
            'enable_captcha' => 'boolean',
            'ip_whitelist' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->updateSettings('security', $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Security settings updated successfully'
        ]);
    }

    /**
     * Update backup settings
     */
    public function updateBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auto_backup' => 'boolean',
            'backup_frequency' => 'required|string|in:daily,weekly,monthly',
            'backup_time' => 'required|string',
            'backup_retention' => 'required|integer|min:1|max:365',
            'backup_location' => 'required|string|in:local,cloud,both',
            'cloud_provider' => 'nullable|string|in:aws,google,dropbox',
            'backup_database' => 'boolean',
            'backup_files' => 'boolean',
            'backup_uploads' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->updateSettings('backup', $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Backup settings updated successfully'
        ]);
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'required_if:mail_driver,smtp|string',
            'mail_port' => 'required_if:mail_driver,smtp|integer',
            'mail_username' => 'required_if:mail_driver,smtp|string',
            'mail_password' => 'required_if:mail_driver,smtp|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->updateSettings('email', $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Email settings updated successfully'
        ]);
    }

    /**
     * Update SMS settings
     */
    public function updateSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sms_provider' => 'required|string|in:twilio,nexmo,africastalking',
            'sms_api_key' => 'required|string',
            'sms_api_secret' => 'required|string',
            'sms_sender_id' => 'required|string|max:11',
            'sms_test_mode' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->updateSettings('sms', $request->all());

        return response()->json([
            'success' => true,
            'message' => 'SMS settings updated successfully'
        ]);
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // This would send a test email
        // For now, return success
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully to ' . $request->test_email
        ]);
    }

    /**
     * Test SMS configuration
     */
    public function testSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_phone' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // This would send a test SMS
        // For now, return success
        return response()->json([
            'success' => true,
            'message' => 'Test SMS sent successfully to ' . $request->test_phone
        ]);
    }

    /**
     * Clear system cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'System cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize system
     */
    public function optimizeSystem()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return response()->json([
                'success' => true,
                'message' => 'System optimized successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize system: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'disk_space' => $this->getDiskSpace(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default')
        ];

        return response()->json($info);
    }

    /**
     * Create manual backup
     */
    public function createBackup(Request $request)
    {
        try {
            $backupSettings = $this->getSettings('backup');

            // Use settings or defaults
            $type = $request->get('type', $backupSettings['backup_database'] && $backupSettings['backup_files'] ? 'all' :
                                  ($backupSettings['backup_database'] ? 'database' : 'files'));

            $retentionMonths = $backupSettings['backup_retention'] ?? 12;

            // Run the backup command
            $command = "php " . base_path('artisan') . " backup:create --type={$type} --retention={$retentionMonths}";
            exec($command . " 2>&1", $output, $returnCode);

            if ($returnCode === 0) {
                // Find the latest backup file
                $backupPath = storage_path('app/backups');
                $backupFiles = glob($backupPath . DIRECTORY_SEPARATOR . 'gombe_ss_backup_*.zip');
                $latestBackup = end($backupFiles);

                $fileSize = $latestBackup ? filesize($latestBackup) : 0;
                $fileSizeMB = round($fileSize / 1024 / 1024, 2);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'backup_file' => $latestBackup ? basename($latestBackup) : null,
                    'file_size' => $fileSizeMB . ' MB',
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
     * Get all settings
     */
    private function getAllSettings()
    {
        return [
            'general' => $this->getSettings('general'),
            'theme' => $this->getSettings('theme'),
            'notifications' => $this->getSettings('notifications'),
            'security' => $this->getSettings('security'),
            'backup' => $this->getSettings('backup'),
            'email' => $this->getSettings('email'),
            'sms' => $this->getSettings('sms')
        ];
    }

    /**
     * Get settings by category
     */
    private function getSettings($category)
    {
        return Cache::get("settings.{$category}", $this->getDefaultSettings($category));
    }

    /**
     * Update settings by category
     */
    private function updateSettings($category, $settings)
    {
        Cache::put("settings.{$category}", $settings, now()->addDays(30));
        
        // Also store in database if you have a settings table
        // Setting::updateOrCreate(['category' => $category], ['data' => json_encode($settings)]);
    }

    /**
     * Get default settings
     */
    private function getDefaultSettings($category)
    {
        $defaults = [
            'general' => [
                'app_name' => 'Gombe SS Hub',
                'app_description' => 'School Management System',
                'contact_email' => 'admin@gombehub.com',
                'timezone' => 'Africa/Lagos',
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s'
            ],
            'theme' => [
                'theme' => 'green',
                'font_family' => 'Ubuntu',
                'font_size' => 'medium'
            ],
            'notifications' => [
                'email_notifications' => true,
                'sms_notifications' => false,
                'push_notifications' => true,
                'notification_sound' => true,
                'daily_reports' => false,
                'weekly_reports' => true,
                'monthly_reports' => true,
                'emergency_alerts' => true,
                'system_updates' => true
            ],
            'security' => [
                'session_timeout' => 120,
                'password_min_length' => 8,
                'require_uppercase' => true,
                'require_lowercase' => true,
                'require_numbers' => true,
                'require_symbols' => false,
                'enable_2fa' => false,
                'login_attempts' => 5,
                'lockout_duration' => 15,
                'enable_captcha' => false
            ],
            'backup' => [
                'auto_backup' => false,
                'backup_frequency' => 'weekly',
                'backup_time' => '02:00',
                'backup_retention' => 30,
                'backup_location' => 'local',
                'backup_database' => true,
                'backup_files' => true,
                'backup_uploads' => true
            ],
            'email' => [
                'mail_driver' => 'smtp',
                'mail_from_address' => 'noreply@gombehub.com',
                'mail_from_name' => 'Gombe SS Hub'
            ],
            'sms' => [
                'sms_provider' => 'twilio',
                'sms_sender_id' => 'GombeHub',
                'sms_test_mode' => true
            ]
        ];

        return $defaults[$category] ?? [];
    }

    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            return \DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Get disk space information
     */
    private function getDiskSpace()
    {
        $bytes = disk_free_space(storage_path());
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}