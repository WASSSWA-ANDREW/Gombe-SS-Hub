<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the current user
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
                                      ->latest()
                                      ->paginate(20);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        $notifications = auth()->user()->notifications()
                                      ->latest()
                                      ->limit(10)
                                      ->get();
        
        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Create system notification (admin only)
     */
    public function create()
    {
        // Check if user is admin
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        return view('admin.notifications.create');
    }

    /**
     * Store system notification
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|string|in:info,success,warning,error,alert',
            'target_users' => 'required|string|in:all,admins,users',
            'send_email' => 'boolean',
            'expires_at' => 'nullable|date|after:now'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.notifications.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();
        
        // Get target users
        $users = $this->getTargetUsers($data['target_users']);
        
        // Create notifications for each user
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\SystemNotification([
                'title' => $data['title'],
                'message' => $data['message'],
                'type' => $data['type'],
                'expires_at' => $data['expires_at'] ?? null,
                'created_by' => auth()->id()
            ]));
        }

        return redirect()->route('admin.notifications.index')
                         ->with('success', 'Notification sent to ' . $users->count() . ' users!');
    }

    /**
     * Get target users based on selection
     */
    private function getTargetUsers($target)
    {
        switch ($target) {
            case 'all':
                return \App\Models\User::where('status', 'active')->get();
                
            case 'admins':
                return \App\Models\User::whereIn('role', ['admin', 'super_admin'])
                                     ->where('status', 'active')
                                     ->get();
                
            case 'users':
                return \App\Models\User::where('role', 'user')
                                     ->where('status', 'active')
                                     ->get();
                
            default:
                return collect();
        }
    }

    /**
     * Send emergency notification
     */
    public function sendEmergency(Request $request)
    {
        // Check if user is admin
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        
        // Send to all active users
        $users = \App\Models\User::where('status', 'active')->get();
        
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\SystemNotification([
                'title' => '🚨 EMERGENCY: ' . $data['title'],
                'message' => $data['message'],
                'type' => 'error',
                'is_emergency' => true,
                'created_by' => auth()->id()
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Emergency notification sent to all users!'
        ]);
    }

    /**
     * Get notification settings for user
     */
    public function getSettings()
    {
        $settings = auth()->user()->notification_settings ?? [
            'email_notifications' => true,
            'push_notifications' => true,
            'sms_notifications' => false,
            'emergency_notifications' => true,
            'system_updates' => true,
            'user_activities' => false
        ];

        return view('admin.notifications.settings', compact('settings'));
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'emergency_notifications' => 'boolean',
            'system_updates' => 'boolean',
            'user_activities' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settings = $validator->validated();
        
        auth()->user()->update([
            'notification_settings' => $settings
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully!'
        ]);
    }

    /**
     * Test notification system
     */
    public function test()
    {
        auth()->user()->notify(new \App\Notifications\SystemNotification([
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the system is working correctly.',
            'type' => 'info',
            'created_by' => auth()->id()
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Test notification sent!'
        ]);
    }
}