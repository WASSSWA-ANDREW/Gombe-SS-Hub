<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\SmartNotificationService;
use Illuminate\Http\Request;

class NotificationsAPIController extends Controller
{
    protected SmartNotificationService $notificationService;

    public function __construct(SmartNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getUserNotifications(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'unread_only' => 'nullable|boolean',
                'limit' => 'nullable|integer|min:1|max:100',
            ]);

            $userId = $request->input('user_id');
            $user = User::findOrFail($userId);
            $limit = $request->input('limit', 50);
            $unreadOnly = $request->input('unread_only', false);

            $query = Notification::byRole($user->role ?? 'user');
            
            if ($unreadOnly) {
                $query = $query->unread();
            }

            $notifications = $query->orderBy('priority')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            $notificationsData = $this->notificationService->getNotificationsForUser($user);

            return response()->json([
                'success' => true,
                'data' => $notificationsData,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|integer|exists:notifications,id',
            ]);

            $notificationId = $request->input('notification_id');
            $result = $this->notificationService->markNotificationAsRead($notificationId);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Notification marked as read' : 'Failed to mark notification as read',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function markMultipleAsRead(Request $request)
    {
        try {
            $request->validate([
                'notification_ids' => 'required|array',
                'notification_ids.*' => 'integer|exists:notifications,id',
            ]);

            $notificationIds = $request->input('notification_ids');
            $updated = Notification::whereIn('id', $notificationIds)->update(['read' => true]);

            return response()->json([
                'success' => true,
                'message' => "Marked {$updated} notifications as read",
                'updated_count' => $updated,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function deleteNotification(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|integer|exists:notifications,id',
            ]);

            $notification = Notification::findOrFail($request->input('notification_id'));
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getNotificationStats(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $userId = $request->input('user_id');
            $user = User::findOrFail($userId);

            $totalUnread = Notification::byRole($user->role ?? 'user')->unread()->count();
            $highPriority = Notification::byRole($user->role ?? 'user')->byPriority('high')->unread()->count();
            $mediumPriority = Notification::byRole($user->role ?? 'user')->byPriority('medium')->unread()->count();
            $lowPriority = Notification::byRole($user->role ?? 'user')->byPriority('low')->unread()->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_unread' => $totalUnread,
                    'high_priority' => $highPriority,
                    'medium_priority' => $mediumPriority,
                    'low_priority' => $lowPriority,
                    'breakdown' => [
                        'high' => $highPriority,
                        'medium' => $mediumPriority,
                        'low' => $lowPriority,
                    ],
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function generateAllNotifications()
    {
        try {
            $riskNotifications = $this->notificationService->generateStudentRiskNotifications();
            $trendNotifications = $this->notificationService->generatePerformanceTrendNotifications();
            $anomalyAlerts = $this->notificationService->generateAnomalyAlerts();
            $recommendationNotifications = $this->notificationService->generateRecommendationNotifications();

            return response()->json([
                'success' => true,
                'message' => 'All notifications generated successfully',
                'data' => [
                    'risk_notifications' => $riskNotifications,
                    'trend_notifications' => $trendNotifications,
                    'anomaly_alerts' => $anomalyAlerts,
                    'recommendation_notifications' => $recommendationNotifications,
                    'total_generated' => $riskNotifications + $trendNotifications + $anomalyAlerts + $recommendationNotifications,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function clearOldNotifications(Request $request)
    {
        try {
            $request->validate([
                'days_old' => 'nullable|integer|min:1',
            ]);

            $daysOld = $request->input('days_old', 30);
            $deletedCount = $this->notificationService->clearOldNotifications($daysOld);

            return response()->json([
                'success' => true,
                'message' => "Cleared {$deletedCount} old notifications",
                'deleted_count' => $deletedCount,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
