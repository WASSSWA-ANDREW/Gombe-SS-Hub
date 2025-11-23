<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Staff;
use App\Services\PredictiveAnalyticsService;
use App\Services\AnomalyDetectionService;
use App\Services\RecommendationService;
use App\Services\SmartNotificationService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
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

    public function studentPerformancePrediction($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $prediction = $this->analyticsService->predictStudentGradeTrend($student);

            return response()->json([
                'success' => true,
                'data' => $prediction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function classPerformancePrediction($level, $class)
    {
        try {
            $prediction = $this->analyticsService->predictClassPerformance($level, $class);

            return response()->json([
                'success' => true,
                'data' => $prediction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function studentRiskAssessment($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $risk = $this->analyticsService->predictStudentDropoutRisk($student);

            return response()->json([
                'success' => true,
                'data' => $risk,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function staffPerformancePrediction($staffId)
    {
        try {
            $staff = Staff::findOrFail($staffId);
            $prediction = $this->analyticsService->predictStaffPerformance($staff);

            return response()->json([
                'success' => true,
                'data' => $prediction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function resourceAllocation($level, $class)
    {
        try {
            $allocation = $this->analyticsService->predictResourceAllocation($level, $class);

            return response()->json([
                'success' => true,
                'data' => $allocation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function studentAnomalies($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $anomalies = $this->anomalyService->detectStudentPerformanceAnomalies($student);

            return response()->json([
                'success' => true,
                'data' => $anomalies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function gradingAnomalies()
    {
        try {
            $anomalies = $this->anomalyService->detectGradingAnomalies();

            return response()->json([
                'success' => true,
                'data' => $anomalies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function dataEntryAnomalies()
    {
        try {
            $anomalies = $this->anomalyService->detectDataEntryAnomalies();

            return response()->json([
                'success' => true,
                'data' => $anomalies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function staffAnomalies($staffId)
    {
        try {
            $staff = Staff::findOrFail($staffId);
            $anomalies = $this->anomalyService->detectStaffBehaviorAnomalies($staff);

            return response()->json([
                'success' => true,
                'data' => $anomalies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function studentRecommendations($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $recommendations = $this->recommendationService->getStudentRecommendations($student);

            return response()->json([
                'success' => true,
                'data' => $recommendations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function classRecommendations($level, $class)
    {
        try {
            $recommendations = $this->recommendationService->getClassRecommendations($level, $class);

            return response()->json([
                'success' => true,
                'data' => $recommendations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function staffRecommendations($staffId)
    {
        try {
            $staff = Staff::findOrFail($staffId);
            $recommendations = $this->recommendationService->getStaffRecommendations($staff);

            return response()->json([
                'success' => true,
                'data' => $recommendations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function systemRecommendations()
    {
        try {
            $recommendations = $this->recommendationService->getSystemWideRecommendations();

            return response()->json([
                'success' => true,
                'data' => $recommendations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function generateNotifications()
    {
        try {
            $riskNotifications = $this->notificationService->generateStudentRiskNotifications();
            $trendNotifications = $this->notificationService->generatePerformanceTrendNotifications();
            $anomalyAlerts = $this->notificationService->generateAnomalyAlerts();
            $recommendationNotifications = $this->notificationService->generateRecommendationNotifications();

            return response()->json([
                'success' => true,
                'message' => 'Notifications generated successfully',
                'data' => [
                    'risk_notifications' => $riskNotifications,
                    'trend_notifications' => $trendNotifications,
                    'anomaly_alerts' => $anomalyAlerts,
                    'recommendation_notifications' => $recommendationNotifications,
                    'total' => $riskNotifications + $trendNotifications + $anomalyAlerts + $recommendationNotifications,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function dashboard()
    {
        try {
            $systemWideRecommendations = $this->recommendationService->getSystemWideRecommendations();
            $gradingAnomalies = $this->anomalyService->detectGradingAnomalies();
            $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies();

            $studentsAtRisk = Student::all()->filter(fn ($s) => 
                $this->analyticsService->predictStudentDropoutRisk($s)['risk_level'] === 'high'
            )->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'system_recommendations' => $systemWideRecommendations,
                    'grading_anomalies' => $gradingAnomalies,
                    'data_anomalies' => $dataAnomalies,
                    'students_at_risk_count' => $studentsAtRisk,
                    'timestamp' => now(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
