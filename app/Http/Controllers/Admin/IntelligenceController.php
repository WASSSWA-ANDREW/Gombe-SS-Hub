<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PredictiveAnalyticsService;
use App\Services\AnomalyDetectionService;
use App\Services\RecommendationService;
use App\Services\SmartNotificationService;
use Illuminate\Http\Request;

class IntelligenceController extends Controller
{
    protected PredictiveAnalyticsService $analyticsService;
    protected AnomalyDetectionService $anomalyService;
    protected RecommendationService $recommendationService;
    protected SmartNotificationService $notificationService;

    public function __construct(
        PredictiveAnalyticsService $analyticsService,
        AnomalyDetectionService $anomalyService,
        RecommendationService $recommendationService,
        SmartNotificationService $notificationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->anomalyService = $anomalyService;
        $this->recommendationService = $recommendationService;
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        return view('admin.intelligence-dashboard');
    }

    public function studentPredictions(Request $request)
    {
        $level = $request->input('level', 'olevel');
        $class = $request->input('class', 'S3');

        $predictions = $this->analyticsService->predictClassPerformance($level, $class);

        return view('admin.intelligence.student-predictions', [
            'predictions' => $predictions,
            'level' => $level,
            'class' => $class,
        ]);
    }

    public function anomalyReport()
    {
        $gradingAnomalies = $this->anomalyService->detectGradingAnomalies();
        $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies();

        return view('admin.intelligence.anomaly-report', [
            'gradingAnomalies' => $gradingAnomalies,
            'dataAnomalies' => $dataAnomalies,
        ]);
    }

    public function recommendations()
    {
        $systemRecommendations = $this->recommendationService->getSystemWideRecommendations();

        return view('admin.intelligence.recommendations', [
            'recommendations' => $systemRecommendations,
        ]);
    }

    public function notifications()
    {
        $notifications = \App\Models\Notification::orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.intelligence.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
