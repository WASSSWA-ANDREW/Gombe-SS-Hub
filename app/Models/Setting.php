<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'category',
        'type',
        'description',
        'editable_by',
        'viewable_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    const CACHE_DURATION = 1440;
    
    const CATEGORIES = [
        'general', 'appearance', 'notifications', 'security', 
        'backup', 'email', 'sms', 'intelligence', 'academics',
        'students', 'staff', 'attendance', 'integrations'
    ];

    const TYPES = [
        'string', 'integer', 'boolean', 'array', 'json',
        'decimal', 'date', 'time', 'datetime'
    ];

    /**
     * Get setting by key with caching
     */
    public static function get($key, $default = null)
    {
        $cacheKey = "setting.{$key}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set setting value with cache invalidation
     */
    public static function set($key, $value, $category = 'general', $type = 'string', $description = null)
    {
        $cacheKey = "setting.{$key}";
        Cache::forget($cacheKey);
        
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'category' => $category,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Get all settings by category with caching
     */
    public static function getByCategory($category)
    {
        $cacheKey = "settings.category.{$category}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category) {
            return self::where('category', $category)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => self::castValue($setting->value, $setting->type)];
                })
                ->toArray();
        });
    }

    /**
     * Get all settings as key-value pairs with caching
     */
    public static function getAllSettings()
    {
        $cacheKey = 'settings.all';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            return self::all()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => self::castValue($setting->value, $setting->type)];
                })
                ->toArray();
        });
    }

    /**
     * Get intelligence system settings
     */
    public static function getIntelligenceSettings()
    {
        return self::getByCategory('intelligence');
    }

    /**
     * Get academic settings
     */
    public static function getAcademicSettings()
    {
        return self::getByCategory('academics');
    }

    /**
     * Get student settings
     */
    public static function getStudentSettings()
    {
        return self::getByCategory('students');
    }

    /**
     * Get staff settings
     */
    public static function getStaffSettings()
    {
        return self::getByCategory('staff');
    }

    /**
     * Get attendance settings
     */
    public static function getAttendanceSettings()
    {
        return self::getByCategory('attendance');
    }

    /**
     * Get notification settings
     */
    public static function getNotificationSettings()
    {
        return self::getByCategory('notifications');
    }

    /**
     * Get security settings
     */
    public static function getSecuritySettings()
    {
        return self::getByCategory('security');
    }

    /**
     * Update multiple settings at once
     */
    public static function updateMany($settings, $category = 'general')
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value, $category);
        }
        
        Cache::forget("settings.category.{$category}");
        Cache::forget('settings.all');
        
        return true;
    }

    /**
     * Check if setting exists
     */
    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            case 'array':
            case 'json':
                return is_array($value) ? $value : json_decode($value, true);
            case 'date':
            case 'time':
            case 'datetime':
                return $value;
            case 'string':
            default:
                return (string) $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('settings.all');
        
        foreach (self::CATEGORIES as $category) {
            Cache::forget("settings.category.{$category}");
        }
        
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
        
        return true;
    }

    /**
     * Get setting with metadata
     */
    public static function getWithMeta($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return [
                'key' => $key,
                'value' => $default,
                'category' => null,
                'type' => null,
                'description' => null,
                'exists' => false
            ];
        }

        return [
            'key' => $setting->key,
            'value' => self::castValue($setting->value, $setting->type),
            'category' => $setting->category,
            'type' => $setting->type,
            'description' => $setting->description,
            'exists' => true,
            'updated_at' => $setting->updated_at
        ];
    }

    /**
     * Batch get multiple settings
     */
    public static function getMultiple($keys, $defaults = [])
    {
        $results = [];
        
        foreach ($keys as $key) {
            $results[$key] = self::get($key, $defaults[$key] ?? null);
        }
        
        return $results;
    }

    /**
     * Get system configuration for intelligent features
     */
    public static function getIntelligenceConfig()
    {
        return [
            'high_risk_threshold' => self::get('intelligence_high_risk_threshold', 0.7),
            'prediction_confidence_min' => self::get('intelligence_confidence_min', 0.5),
            'attendance_critical_threshold' => self::get('attendance_critical', 50),
            'attendance_high_threshold' => self::get('attendance_high', 75),
            'enable_anomaly_detection' => self::get('intelligence_anomaly_detection', true),
            'enable_recommendations' => self::get('intelligence_recommendations', true),
            'enable_smart_notifications' => self::get('intelligence_notifications', true),
            'anomaly_zscore_threshold' => self::get('anomaly_zscore_threshold', 2.5),
            'auto_generate_notifications' => self::get('auto_generate_notifications', false),
        ];
    }

    /**
     * Get academic configuration
     */
    public static function getAcademicConfig()
    {
        return [
            'enable_olevel' => self::get('academic_olevel_enabled', true),
            'enable_alevel' => self::get('academic_alevel_enabled', true),
            'grading_scale' => self::get('academic_grading_scale', 'A-F'),
            'passing_grade' => self::get('academic_passing_grade', 'E'),
            'minimum_mark' => self::get('academic_minimum_mark', 0),
            'maximum_mark' => self::get('academic_maximum_mark', 100),
            'enable_health_tracking' => self::get('academic_health_tracking', true),
            'enable_attendance_tracking' => self::get('academic_attendance_tracking', true),
        ];
    }

    /**
     * Get current academic year
     */
    public static function getCurrentAcademicYear()
    {
        return self::get('current_academic_year', now()->year);
    }

    /**
     * Set current academic year
     */
    public static function setCurrentAcademicYear($year)
    {
        return self::set('current_academic_year', $year, 'academics', 'integer', 'Current academic year');
    }
}