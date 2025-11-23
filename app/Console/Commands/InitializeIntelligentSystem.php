<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\SmartNotificationService;

class InitializeIntelligentSystem extends Command
{
    protected $signature = 'intelligence:init';
    protected $description = 'Initialize the intelligent system with database migrations and initial notifications';

    public function handle()
    {
        $this->info('🚀 Initializing Intelligent System...');

        $this->info('📊 Running migrations...');
        $this->call('migrate', ['--force' => true]);

        $this->info('📝 Creating initial notifications...');
        try {
            $notificationService = app(SmartNotificationService::class);
            
            $riskNotifications = $notificationService->generateStudentRiskNotifications();
            $trendNotifications = $notificationService->generatePerformanceTrendNotifications();
            $anomalyAlerts = $notificationService->generateAnomalyAlerts();
            $recommendationNotifications = $notificationService->generateRecommendationNotifications();

            $total = $riskNotifications + $trendNotifications + $anomalyAlerts + $recommendationNotifications;

            $this->info("✅ Generated {$total} notifications:");
            $this->info("   • Risk Notifications: {$riskNotifications}");
            $this->info("   • Trend Notifications: {$trendNotifications}");
            $this->info("   • Anomaly Alerts: {$anomalyAlerts}");
            $this->info("   • Recommendation Notifications: {$recommendationNotifications}");
        } catch (\Exception $e) {
            $this->warn('⚠️  Could not generate initial notifications: ' . $e->getMessage());
        }

        $this->info('✨ Intelligent System initialized successfully!');
        $this->info('Access the dashboard at: /admin/intelligence');
    }
}
