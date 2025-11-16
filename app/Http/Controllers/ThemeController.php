<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\UserPreference;

class ThemeController extends Controller
{
    /**
     * Get current theme settings
     */
    public function getCurrentTheme()
    {
        $userId = Auth::id();
        $theme = 'green'; // default theme
        
        if ($userId) {
            // Get from user preferences
            $preference = UserPreference::where('user_id', $userId)
                ->where('key', 'theme')
                ->first();
            
            if ($preference) {
                $theme = $preference->value;
            }
        } else {
            // Get from cookie for non-authenticated users
            $theme = request()->cookie('theme', 'green');
        }

        return response()->json([
            'theme' => $theme,
            'available_themes' => $this->getAvailableThemes()
        ]);
    }

    /**
     * Toggle between green and cream theme
     */
    public function toggleTheme(Request $request)
    {
        $currentTheme = $request->get('current_theme', 'green');
        $newTheme = $currentTheme === 'green' ? 'cream' : 'green';

        return $this->setTheme($newTheme);
    }

    /**
     * Set specific theme
     */
    public function setTheme($theme = null)
    {
        if (!$theme) {
            $theme = request()->get('theme', 'green');
        }

        // Validate theme
        $availableThemes = array_keys($this->getAvailableThemes());
        if (!in_array($theme, $availableThemes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid theme selected.'
            ], 400);
        }

        $userId = Auth::id();

        if ($userId) {
            // Save to user preferences
            UserPreference::updateOrCreate(
                ['user_id' => $userId, 'key' => 'theme'],
                ['value' => $theme]
            );
        }

        // Set cookie for all users (including non-authenticated)
        $cookie = cookie('theme', $theme, 60 * 24 * 365); // 1 year

        return response()->json([
            'success' => true,
            'theme' => $theme,
            'message' => 'Theme updated successfully.'
        ])->cookie($cookie);
    }

    /**
     * Get available themes
     */
    public function getAvailableThemes()
    {
        return [
            'green' => [
                'name' => 'Green Theme',
                'description' => 'Nature-inspired green interface',
                'primary_color' => '#059669',
                'background_color' => '#f0fdf4',
                'text_color' => '#1f2937',
                'preview_image' => '/images/themes/green-preview.png'
            ],
            'cream' => [
                'name' => 'Cream Theme',
                'description' => 'Warm and elegant cream interface',
                'primary_color' => '#b45309',
                'background_color' => '#fffbeb',
                'text_color' => '#78350f',
                'preview_image' => '/images/themes/cream-preview.png'
            ],

        ];
    }

    /**
     * Get theme customization options
     */
    public function getCustomizationOptions()
    {
        return response()->json([
            'font_sizes' => [
                'small' => '14px',
                'medium' => '16px',
                'large' => '18px',
                'extra-large' => '20px'
            ],
            'font_families' => [
                'system' => 'System Default',
                'inter' => 'Inter',
                'roboto' => 'Roboto',
                'open-sans' => 'Open Sans',
                'lato' => 'Lato'
            ],
            'sidebar_positions' => [
                'left' => 'Left Side',
                'right' => 'Right Side'
            ],
            'layout_options' => [
                'boxed' => 'Boxed Layout',
                'full-width' => 'Full Width',
                'fluid' => 'Fluid Layout'
            ],
            'animation_speeds' => [
                'none' => 'No Animations',
                'slow' => 'Slow',
                'normal' => 'Normal',
                'fast' => 'Fast'
            ]
        ]);
    }

    /**
     * Update theme customization
     */
    public function updateCustomization(Request $request)
    {
        $request->validate([
            'font_size' => 'nullable|string|in:small,medium,large,extra-large',
            'font_family' => 'nullable|string|in:system,inter,roboto,open-sans,lato',
            'sidebar_position' => 'nullable|string|in:left,right',
            'layout' => 'nullable|string|in:boxed,full-width,fluid',
            'animation_speed' => 'nullable|string|in:none,slow,normal,fast',
            'reduce_motion' => 'nullable|boolean',
            'high_contrast' => 'nullable|boolean'
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required for customization.'
            ], 401);
        }

        $customizations = $request->only([
            'font_size', 'font_family', 'sidebar_position', 
            'layout', 'animation_speed', 'reduce_motion', 'high_contrast'
        ]);

        // Remove null values
        $customizations = array_filter($customizations, function($value) {
            return $value !== null;
        });

        // Save each customization
        foreach ($customizations as $key => $value) {
            UserPreference::updateOrCreate(
                ['user_id' => $userId, 'key' => "theme_{$key}"],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Theme customization updated successfully.',
            'customizations' => $customizations
        ]);
    }

    /**
     * Get user's theme customizations
     */
    public function getCustomizations()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'customizations' => []
            ]);
        }

        $preferences = UserPreference::where('user_id', $userId)
            ->where('key', 'like', 'theme_%')
            ->get()
            ->pluck('value', 'key');

        $customizations = [];
        foreach ($preferences as $key => $value) {
            $customizationKey = str_replace('theme_', '', $key);
            $customizations[$customizationKey] = $value;
        }

        return response()->json([
            'customizations' => $customizations
        ]);
    }

    /**
     * Reset theme to default
     */
    public function resetToDefault()
    {
        $userId = Auth::id();
        
        if ($userId) {
            // Remove all theme preferences
            UserPreference::where('user_id', $userId)
                ->where('key', 'like', 'theme%')
                ->delete();
        }

        // Clear theme cookie
        $cookie = cookie('theme', null, -1);

        return response()->json([
            'success' => true,
            'message' => 'Theme reset to default successfully.',
            'theme' => 'green'
        ])->cookie($cookie);
    }

    /**
     * Get theme statistics (for admin)
     */
    public function getThemeStatistics()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $themeStats = UserPreference::where('key', 'theme')
            ->selectRaw('value as theme, COUNT(*) as count')
            ->groupBy('value')
            ->get();

        $totalUsers = UserPreference::where('key', 'theme')->count();

        $statistics = [
            'total_users_with_theme_preference' => $totalUsers,
            'theme_distribution' => $themeStats->mapWithKeys(function ($item) use ($totalUsers) {
                return [
                    $item->theme => [
                        'count' => $item->count,
                        'percentage' => $totalUsers > 0 ? round(($item->count / $totalUsers) * 100, 2) : 0
                    ]
                ];
            }),
            'most_popular_theme' => $themeStats->sortByDesc('count')->first()?->theme ?? 'green'
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Export theme settings
     */
    public function exportSettings()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401);
        }

        $preferences = UserPreference::where('user_id', $userId)
            ->where('key', 'like', 'theme%')
            ->get()
            ->pluck('value', 'key');

        $settings = [
            'theme_settings' => $preferences,
            'exported_at' => now()->toISOString(),
            'user_id' => $userId
        ];

        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="theme-settings.json"');
    }

    /**
     * Import theme settings
     */
    public function importSettings(Request $request)
    {
        $request->validate([
            'theme_settings' => 'required|array'
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401);
        }

        $imported = 0;
        foreach ($request->theme_settings as $key => $value) {
            if (strpos($key, 'theme') === 0) {
                UserPreference::updateOrCreate(
                    ['user_id' => $userId, 'key' => $key],
                    ['value' => $value]
                );
                $imported++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$imported} theme settings.",
            'imported_count' => $imported
        ]);
    }
}
