# Settings Model - Comprehensive Usage Guide

## Overview
The updated `Setting` model provides a comprehensive settings management system for the Gombe SS Hub Pro intelligent school management platform. It includes support for multiple categories, type casting, caching, and specialized configuration retrieval methods.

## Features

### 1. **Categories Supported**
- `general` - General application settings
- `appearance` - Theme and UI settings
- `notifications` - Notification preferences
- `security` - Security configurations
- `backup` - Backup settings
- `email` - Email configuration
- `sms` - SMS settings
- `intelligence` - AI/ML system settings
- `academics` - Academic configuration
- `students` - Student-related settings
- `staff` - Staff-related settings
- `attendance` - Attendance policy settings
- `integrations` - Third-party integrations

### 2. **Supported Data Types**
- `string` - Text values
- `integer` - Whole numbers
- `decimal` - Floating-point numbers
- `boolean` - True/False values
- `array` - Array data
- `json` - JSON data
- `date` - Date values
- `time` - Time values
- `datetime` - DateTime values

### 3. **Built-in Caching**
All settings are cached for 1440 minutes (24 hours) for optimal performance. Cache is automatically invalidated on updates.

---

## Basic Usage

### Get a Setting
```php
use App\Models\Setting;

// Get setting with default value
$value = Setting::get('app_name', 'My App');

// Value will be automatically cast based on type
$timeout = Setting::get('session_timeout'); // Returns integer
$enabled = Setting::get('email_notifications'); // Returns boolean
```

### Set a Setting
```php
// Simple set
Setting::set('app_name', 'New App Name');

// Set with category and type
Setting::set(
    'session_timeout',
    120,
    category: 'security',
    type: 'integer',
    description: 'Session timeout in minutes'
);
```

### Get Settings by Category
```php
// Get all settings in a category as key-value array
$generalSettings = Setting::getByCategory('general');

// Example output:
// [
//     'app_name' => 'Gombe SS Hub',
//     'contact_email' => 'admin@gombehub.com',
//     ...
// ]
```

### Get All Settings
```php
$allSettings = Setting::getAllSettings();
// Returns all settings across all categories as key-value pairs
```

---

## Category-Specific Methods

### Intelligence Settings
```php
// Get all intelligence system settings
$intelligenceSettings = Setting::getIntelligenceSettings();

// Get complete intelligence configuration
$config = Setting::getIntelligenceConfig();
// Returns:
// [
//     'high_risk_threshold' => 0.7,
//     'prediction_confidence_min' => 0.5,
//     'attendance_critical_threshold' => 50,
//     'attendance_high_threshold' => 75,
//     'enable_anomaly_detection' => true,
//     'enable_recommendations' => true,
//     'enable_smart_notifications' => true,
//     'anomaly_zscore_threshold' => 2.5,
//     'auto_generate_notifications' => false,
// ]
```

### Academic Settings
```php
// Get all academic settings
$academicSettings = Setting::getAcademicSettings();

// Get complete academic configuration
$config = Setting::getAcademicConfig();
// Returns:
// [
//     'enable_olevel' => true,
//     'enable_alevel' => true,
//     'grading_scale' => 'A-F',
//     'passing_grade' => 'E',
//     'minimum_mark' => 0,
//     'maximum_mark' => 100,
//     'enable_health_tracking' => true,
//     'enable_attendance_tracking' => true,
// ]
```

### Student Settings
```php
$studentSettings = Setting::getStudentSettings();
```

### Staff Settings
```php
$staffSettings = Setting::getStaffSettings();
```

### Attendance Settings
```php
$attendanceSettings = Setting::getAttendanceSettings();
```

### Notification Settings
```php
$notificationSettings = Setting::getNotificationSettings();
```

### Security Settings
```php
$securitySettings = Setting::getSecuritySettings();
```

---

## Advanced Usage

### Update Multiple Settings at Once
```php
Setting::updateMany([
    'app_name' => 'New App Name',
    'contact_email' => 'newemail@example.com',
    'timezone' => 'Africa/Lagos'
], category: 'general');
```

### Check if Setting Exists
```php
if (Setting::has('app_name')) {
    // Setting exists
}
```

### Get Setting with Metadata
```php
$meta = Setting::getWithMeta('app_name');
// Returns:
// [
//     'key' => 'app_name',
//     'value' => 'Gombe SS Hub',
//     'category' => 'general',
//     'type' => 'string',
//     'description' => 'Application name',
//     'exists' => true,
//     'updated_at' => DateTime
// ]
```

### Batch Get Multiple Settings
```php
$settings = Setting::getMultiple(
    ['app_name', 'contact_email', 'timezone'],
    defaults: [
        'app_name' => 'My App',
        'contact_email' => 'admin@example.com',
        'timezone' => 'UTC'
    ]
);
// Returns:
// [
//     'app_name' => 'Gombe SS Hub',
//     'contact_email' => 'admin@gombehub.com',
//     'timezone' => 'Africa/Lagos'
// ]
```

### Clear Settings Cache
```php
// Clear all settings cache
Setting::clearCache();

// Cache will be regenerated on next access
```

### Academic Year Management
```php
// Get current academic year
$year = Setting::getCurrentAcademicYear();

// Set current academic year
Setting::setCurrentAcademicYear(2025);
```

---

## Intelligence System Configuration

### Risk Thresholds
```php
$config = Setting::getIntelligenceConfig();

// High risk threshold (0-1 scale)
$highRiskThreshold = $config['high_risk_threshold']; // 0.7

// Minimum prediction confidence
$minConfidence = $config['prediction_confidence_min']; // 0.5
```

### Attendance Thresholds
```php
$config = Setting::getIntelligenceConfig();

// Critical attendance (below this triggers intervention)
$criticalAttendance = $config['attendance_critical_threshold']; // 50%

// High attendance warning
$highAttendance = $config['attendance_high_threshold']; // 75%
```

### Feature Toggles
```php
$config = Setting::getIntelligenceConfig();

// Enable/disable specific features
$anomalyDetection = $config['enable_anomaly_detection']; // true
$recommendations = $config['enable_recommendations']; // true
$notifications = $config['enable_smart_notifications']; // true
$autoNotifications = $config['auto_generate_notifications']; // false
```

---

## Using Settings in Services

### In Intelligent Services
```php
namespace App\Services;

use App\Models\Setting;

class PredictiveAnalyticsService
{
    public function predictStudentGradeTrend($student)
    {
        $config = Setting::getIntelligenceConfig();
        
        // Use configuration
        $riskThreshold = $config['high_risk_threshold'];
        $confidenceMin = $config['prediction_confidence_min'];
        
        // ... prediction logic
    }
}
```

### In Controllers
```php
use App\Models\Setting;

class IntelligenceController extends Controller
{
    public function dashboard()
    {
        $academicConfig = Setting::getAcademicConfig();
        $intelligenceConfig = Setting::getIntelligenceConfig();
        
        return view('dashboard', [
            'academicConfig' => $academicConfig,
            'intelligenceConfig' => $intelligenceConfig,
        ]);
    }
}
```

---

## Database Migration

The migration file `2025_11_24_000000_add_intelligence_settings.php` will automatically populate 23 new settings when run:

### Run Migration
```bash
php artisan migrate
```

### Included Settings
- **Intelligence (7 settings)**: Risk thresholds, confidence scores, feature toggles, anomaly detection
- **Attendance (3 settings)**: Critical/high thresholds, tracking toggle
- **Academics (9 settings)**: Level toggles, grading configuration, health/attendance tracking
- **Students (2 settings)**: Health field requirement, medical tracking
- **Staff (2 settings)**: Health tracking, performance monitoring

---

## Settings Categories and Keys

### Intelligence Category
| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `intelligence_high_risk_threshold` | decimal | 0.7 | Risk classification threshold |
| `intelligence_confidence_min` | decimal | 0.5 | Minimum prediction confidence |
| `intelligence_anomaly_detection` | boolean | true | Enable anomaly detection |
| `intelligence_recommendations` | boolean | true | Enable recommendations |
| `intelligence_notifications` | boolean | true | Enable notifications |
| `anomaly_zscore_threshold` | decimal | 2.5 | Z-score anomaly threshold |
| `auto_generate_notifications` | boolean | false | Auto-generate notifications |

### Attendance Category
| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `attendance_critical` | integer | 50 | Critical threshold % |
| `attendance_high` | integer | 75 | High warning threshold % |
| `attendance_tracking_enabled` | boolean | true | Enable tracking |

### Academics Category
| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `academic_olevel_enabled` | boolean | true | Enable O-Level |
| `academic_alevel_enabled` | boolean | true | Enable A-Level |
| `academic_grading_scale` | string | A-F | Grading scale |
| `academic_passing_grade` | string | E | Passing grade |
| `academic_minimum_mark` | integer | 0 | Minimum mark |
| `academic_maximum_mark` | integer | 100 | Maximum mark |
| `academic_health_tracking` | boolean | true | Enable health tracking |
| `academic_attendance_tracking` | boolean | true | Enable attendance tracking |
| `current_academic_year` | integer | 2025 | Current year |

---

## Best Practices

1. **Use Specific Getters**: Use category-specific methods when possible (`getIntelligenceSettings()`) instead of generic retrieval.

2. **Cache Awareness**: Settings are cached - if you need real-time updates, clear cache with `Setting::clearCache()`.

3. **Type Consistency**: Always specify the correct type when setting values to ensure proper casting.

4. **Default Values**: Always provide default values when retrieving settings to handle missing configurations.

5. **Configuration Objects**: Use `getIntelligenceConfig()` and `getAcademicConfig()` instead of individual gets for bulk configuration.

6. **Category Organization**: Keep related settings in the same category for better organization and retrieval.

---

## Backward Compatibility

The updated Setting model maintains full backward compatibility with existing code:
- All existing methods work unchanged
- New methods are additions only
- Existing cache logic is preserved
- Database schema is unchanged

---

## Examples

### Complete Intelligence System Setup
```php
// Get all intelligence configuration
$intelligence = Setting::getIntelligenceConfig();
$academics = Setting::getAcademicConfig();

// Use in predictive analytics
$highRisk = $intelligence['high_risk_threshold'];
$maxMark = $academics['academic_maximum_mark'];

// Generate predictions with configuration
$predictions = $service->predictWithConfig($intelligence, $academics);
```

### Update Attendance Policy
```php
Setting::updateMany([
    'attendance_critical' => 40,
    'attendance_high' => 70,
    'attendance_tracking_enabled' => true
], category: 'attendance');
```

### Check System Capabilities
```php
$config = Setting::getIntelligenceConfig();

if ($config['enable_anomaly_detection'] && $config['enable_smart_notifications']) {
    // Run full intelligent system
    $service->runFullAnalysis();
}
```

---

## Troubleshooting

### Settings Not Updating
- Clear cache: `Setting::clearCache()`
- Check database for duplicates
- Verify cache driver is working

### Type Casting Issues
- Ensure correct type is specified when setting value
- Use `getWithMeta()` to verify type information
- Check database for corrupted values

### Performance Issues
- Cache duration is 1440 minutes (24 hours)
- Use `clearCache()` sparingly
- Consider batch operations with `updateMany()`

---

## Support

For issues or questions about the Settings model, refer to the complete documentation or contact the development team.
