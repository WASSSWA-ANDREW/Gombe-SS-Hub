<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'intelligence_high_risk_threshold', 'value' => '0.7', 'category' => 'intelligence', 'type' => 'decimal', 'description' => 'Risk score threshold for high-risk classification'],
            ['key' => 'intelligence_confidence_min', 'value' => '0.5', 'category' => 'intelligence', 'type' => 'decimal', 'description' => 'Minimum confidence score for predictions'],
            ['key' => 'intelligence_anomaly_detection', 'value' => '1', 'category' => 'intelligence', 'type' => 'boolean', 'description' => 'Enable anomaly detection'],
            ['key' => 'intelligence_recommendations', 'value' => '1', 'category' => 'intelligence', 'type' => 'boolean', 'description' => 'Enable automatic recommendations'],
            ['key' => 'intelligence_notifications', 'value' => '1', 'category' => 'intelligence', 'type' => 'boolean', 'description' => 'Enable smart notifications'],
            ['key' => 'anomaly_zscore_threshold', 'value' => '2.5', 'category' => 'intelligence', 'type' => 'decimal', 'description' => 'Z-score threshold for anomalies'],
            ['key' => 'auto_generate_notifications', 'value' => '0', 'category' => 'intelligence', 'type' => 'boolean', 'description' => 'Auto-generate notifications'],
            ['key' => 'attendance_critical', 'value' => '50', 'category' => 'attendance', 'type' => 'integer', 'description' => 'Critical attendance threshold'],
            ['key' => 'attendance_high', 'value' => '75', 'category' => 'attendance', 'type' => 'integer', 'description' => 'High attendance threshold'],
            ['key' => 'attendance_tracking_enabled', 'value' => '1', 'category' => 'attendance', 'type' => 'boolean', 'description' => 'Enable attendance tracking'],
            ['key' => 'academic_olevel_enabled', 'value' => '1', 'category' => 'academics', 'type' => 'boolean', 'description' => 'Enable O-Level'],
            ['key' => 'academic_alevel_enabled', 'value' => '1', 'category' => 'academics', 'type' => 'boolean', 'description' => 'Enable A-Level'],
            ['key' => 'academic_grading_scale', 'value' => 'A-F', 'category' => 'academics', 'type' => 'string', 'description' => 'Grading scale'],
            ['key' => 'academic_passing_grade', 'value' => 'E', 'category' => 'academics', 'type' => 'string', 'description' => 'Passing grade'],
            ['key' => 'academic_minimum_mark', 'value' => '0', 'category' => 'academics', 'type' => 'integer', 'description' => 'Minimum mark'],
            ['key' => 'academic_maximum_mark', 'value' => '100', 'category' => 'academics', 'type' => 'integer', 'description' => 'Maximum mark'],
            ['key' => 'academic_health_tracking', 'value' => '1', 'category' => 'academics', 'type' => 'boolean', 'description' => 'Enable health tracking'],
            ['key' => 'academic_attendance_tracking', 'value' => '1', 'category' => 'academics', 'type' => 'boolean', 'description' => 'Enable attendance in academics'],
            ['key' => 'current_academic_year', 'value' => '2025', 'category' => 'academics', 'type' => 'integer', 'description' => 'Current academic year'],
            ['key' => 'students_health_field_required', 'value' => '1', 'category' => 'students', 'type' => 'boolean', 'description' => 'Require health field'],
            ['key' => 'students_enable_medical_tracking', 'value' => '1', 'category' => 'students', 'type' => 'boolean', 'description' => 'Enable medical tracking'],
            ['key' => 'staff_health_tracking', 'value' => '1', 'category' => 'staff', 'type' => 'boolean', 'description' => 'Enable staff health tracking'],
            ['key' => 'staff_performance_monitoring', 'value' => '1', 'category' => 'staff', 'type' => 'boolean', 'description' => 'Enable staff performance monitoring'],
        ];

        foreach ($settings as $setting) {
            if (!DB::table('settings')->where('key', $setting['key'])->exists()) {
                $setting['created_at'] = now();
                $setting['updated_at'] = now();
                DB::table('settings')->insert($setting);
            }
        }
    }

    public function down(): void
    {
        $keys = [
            'intelligence_high_risk_threshold', 'intelligence_confidence_min', 'intelligence_anomaly_detection',
            'intelligence_recommendations', 'intelligence_notifications', 'anomaly_zscore_threshold',
            'auto_generate_notifications', 'attendance_critical', 'attendance_high', 'attendance_tracking_enabled',
            'academic_olevel_enabled', 'academic_alevel_enabled', 'academic_grading_scale', 'academic_passing_grade',
            'academic_minimum_mark', 'academic_maximum_mark', 'academic_health_tracking', 'academic_attendance_tracking',
            'current_academic_year', 'students_health_field_required', 'students_enable_medical_tracking',
            'staff_health_tracking', 'staff_performance_monitoring',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
