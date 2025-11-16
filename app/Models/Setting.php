<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get setting by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $category = 'general', $type = 'string', $description = null)
    {
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
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        return self::where('category', $category)->get()->pluck('value', 'key');
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return self::all()->pluck('value', 'key');
    }
}