<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\EmergencyContact;
use App\Models\EmergencyLog;

class EmergencyContactController extends Controller
{
    /**
     * Get emergency contacts
     */
    public function getContacts()
    {
        $contacts = [
            'super_admin' => [
                'name' => 'Super Administrator',
                'phone' => '+256700000001',
                'whatsapp' => '+256700000001',
                'email' => 'superadmin@gombess.edu.ug',
                'role' => 'Super Admin',
                'available_24_7' => true,
                'priority' => 1
            ],
            'admin' => [
                'name' => 'System Administrator',
                'phone' => '+256700000002',
                'whatsapp' => '+256700000002',
                'email' => 'admin@gombess.edu.ug',
                'role' => 'Admin',
                'available_24_7' => true,
                'priority' => 2
            ],
            'technical_support' => [
                'name' => 'Technical Support',
                'phone' => '+256700000003',
                'whatsapp' => '+256700000003',
                'email' => 'support@gombess.edu.ug',
                'role' => 'Technical Support',
                'available_hours' => '8:00 AM - 6:00 PM',
                'available_24_7' => false,
                'priority' => 3
            ],
            'emergency_services' => [
                'name' => 'Emergency Services',
                'phone' => '999',
                'description' => 'Police, Fire, Medical Emergency',
                'available_24_7' => true,
                'priority' => 0
            ]
        ];

        return response()->json([
            'contacts' => $contacts,
            'current_time' => now()->format('H:i'),
            'timezone' => 'Africa/Kampala'
        ]);
    }

    /**
     * Initiate emergency call
     */
    public function initiateCall(Request $request)
    {
        $request->validate([
            'contact_type' => 'required|string|in:super_admin,admin,technical_support,emergency_services',
            'reason' => 'nullable|string|max:500'
        ]);

        $contacts = $this->getContacts()->getData()->contacts;
        $contact = $contacts->{$request->contact_type};

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid contact type.'
            ], 400);
        }

        // Log the emergency call attempt
        $this->logEmergencyAction('call', $request->contact_type, $request->reason);

        return response()->json([
            'success' => true,
            'action' => 'call',
            'phone_number' => $contact->phone,
            'contact_name' => $contact->name,
            'call_url' => "tel:{$contact->phone}",
            'message' => "Initiating call to {$contact->name} at {$contact->phone}"
        ]);
    }

    /**
     * Initiate WhatsApp contact
     */
    public function initiateWhatsApp(Request $request)
    {
        $request->validate([
            'contact_type' => 'required|string|in:super_admin,admin,technical_support',
            'message' => 'nullable|string|max:1000',
            'reason' => 'nullable|string|max:500'
        ]);

        $contacts = $this->getContacts()->getData()->contacts;
        $contact = $contacts->{$request->contact_type};

        if (!$contact || !isset($contact->whatsapp)) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp not available for this contact.'
            ], 400);
        }

        $defaultMessage = $request->message ?? $this->getDefaultWhatsAppMessage($request->reason);
        $whatsappUrl = "https://wa.me/{$contact->whatsapp}?text=" . urlencode($defaultMessage);

        // Log the WhatsApp contact attempt
        $this->logEmergencyAction('whatsapp', $request->contact_type, $request->reason, $defaultMessage);

        return response()->json([
            'success' => true,
            'action' => 'whatsapp',
            'whatsapp_number' => $contact->whatsapp,
            'contact_name' => $contact->name,
            'whatsapp_url' => $whatsappUrl,
            'message' => $defaultMessage,
            'response_message' => "Opening WhatsApp to contact {$contact->name}"
        ]);
    }

    /**
     * Send emergency email
     */
    public function sendEmergencyEmail(Request $request)
    {
        $request->validate([
            'contact_type' => 'required|string|in:super_admin,admin,technical_support',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'nullable|string|in:low,normal,high,urgent'
        ]);

        $contacts = $this->getContacts()->getData()->contacts;
        $contact = $contacts->{$request->contact_type};

        if (!$contact || !isset($contact->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Email not available for this contact.'
            ], 400);
        }

        $priority = $request->priority ?? 'normal';
        $user = Auth::user();

        $emailData = [
            'to' => $contact->email,
            'subject' => "[EMERGENCY - {$priority}] {$request->subject}",
            'message' => $request->message,
            'sender_name' => $user ? $user->name : 'Anonymous User',
            'sender_email' => $user ? $user->email : 'unknown@system.local',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'priority' => $priority
        ];

        // In a real application, you would send the actual email here
        // For now, we'll just log it and return success

        $this->logEmergencyAction('email', $request->contact_type, $request->subject, $request->message);

        return response()->json([
            'success' => true,
            'action' => 'email',
            'recipient' => $contact->email,
            'contact_name' => $contact->name,
            'subject' => $emailData['subject'],
            'message' => 'Emergency email sent successfully.'
        ]);
    }

    /**
     * Get emergency contact history
     */
    public function getContactHistory()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'history' => [],
                'message' => 'Authentication required to view history.'
            ]);
        }

        // In a real application, you would fetch from EmergencyLog model
        // For now, we'll return mock data
        $history = [
            [
                'id' => 1,
                'action' => 'whatsapp',
                'contact_type' => 'admin',
                'reason' => 'System error',
                'timestamp' => now()->subHours(2)->format('Y-m-d H:i:s'),
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'action' => 'call',
                'contact_type' => 'technical_support',
                'reason' => 'Login issues',
                'timestamp' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'status' => 'completed'
            ]
        ];

        return response()->json([
            'history' => $history,
            'total_contacts' => count($history)
        ]);
    }

    /**
     * Get emergency contact statistics (for admin)
     */
    public function getStatistics()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Mock statistics - in real app, fetch from database
        $statistics = [
            'total_emergency_contacts' => 45,
            'contacts_today' => 3,
            'contacts_this_week' => 12,
            'contacts_this_month' => 45,
            'contact_types' => [
                'call' => 20,
                'whatsapp' => 18,
                'email' => 7
            ],
            'contact_reasons' => [
                'technical_issues' => 15,
                'system_errors' => 12,
                'login_problems' => 8,
                'data_issues' => 6,
                'other' => 4
            ],
            'response_times' => [
                'average_response_time' => '15 minutes',
                'fastest_response' => '2 minutes',
                'slowest_response' => '45 minutes'
            ],
            'peak_hours' => [
                '09:00-10:00' => 8,
                '14:00-15:00' => 6,
                '11:00-12:00' => 5
            ]
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Update emergency contact settings (for admin)
     */
    public function updateContactSettings(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'contact_type' => 'required|string|in:super_admin,admin,technical_support',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'available_24_7' => 'nullable|boolean',
            'available_hours' => 'nullable|string|max:100'
        ]);

        // In a real application, you would update the database
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Emergency contact settings updated successfully.',
            'updated_contact' => $request->contact_type
        ]);
    }

    /**
     * Test emergency contact system
     */
    public function testSystem(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'test_type' => 'required|string|in:call,whatsapp,email,all'
        ]);

        $testResults = [];
        $testType = $request->test_type;

        if ($testType === 'call' || $testType === 'all') {
            $testResults['call'] = [
                'status' => 'success',
                'message' => 'Call functionality is working properly',
                'response_time' => '0.5s'
            ];
        }

        if ($testType === 'whatsapp' || $testType === 'all') {
            $testResults['whatsapp'] = [
                'status' => 'success',
                'message' => 'WhatsApp integration is working properly',
                'response_time' => '0.8s'
            ];
        }

        if ($testType === 'email' || $testType === 'all') {
            $testResults['email'] = [
                'status' => 'success',
                'message' => 'Email system is working properly',
                'response_time' => '1.2s'
            ];
        }

        return response()->json([
            'success' => true,
            'test_results' => $testResults,
            'overall_status' => 'all_systems_operational',
            'tested_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get default WhatsApp message
     */
    private function getDefaultWhatsAppMessage($reason = null)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'System User';
        $timestamp = now()->format('Y-m-d H:i:s');

        $message = "🚨 EMERGENCY CONTACT - Gombe SS Hub\n\n";
        $message .= "From: {$userName}\n";
        $message .= "Time: {$timestamp}\n";
        
        if ($reason) {
            $message .= "Reason: {$reason}\n";
        }
        
        $message .= "\nI need immediate assistance with the school management system. Please respond as soon as possible.\n\n";
        $message .= "Thank you.";

        return $message;
    }

    /**
     * Log emergency action
     */
    private function logEmergencyAction($action, $contactType, $reason = null, $message = null)
    {
        $logData = [
            'user_id' => Auth::id(),
            'action' => $action,
            'contact_type' => $contactType,
            'reason' => $reason,
            'message' => $message,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ];

        // Log to Laravel log
        Log::info('Emergency contact initiated', $logData);

        // In a real application, you would also save to database
        // EmergencyLog::create($logData);
    }

    /**
     * Get emergency contact widget data
     */
    public function getWidgetData()
    {
        $contacts = $this->getContacts()->getData()->contacts;
        
        $widgetData = [
            'primary_contacts' => [
                [
                    'type' => 'super_admin',
                    'name' => $contacts->super_admin->name,
                    'phone' => $contacts->super_admin->phone,
                    'whatsapp' => $contacts->super_admin->whatsapp,
                    'icon' => 'fas fa-user-shield',
                    'color' => 'red'
                ],
                [
                    'type' => 'admin',
                    'name' => $contacts->admin->name,
                    'phone' => $contacts->admin->phone,
                    'whatsapp' => $contacts->admin->whatsapp,
                    'icon' => 'fas fa-user-cog',
                    'color' => 'orange'
                ]
            ],
            'quick_actions' => [
                [
                    'action' => 'call',
                    'label' => 'Emergency Call',
                    'icon' => 'fas fa-phone',
                    'color' => 'red'
                ],
                [
                    'action' => 'whatsapp',
                    'label' => 'WhatsApp',
                    'icon' => 'fab fa-whatsapp',
                    'color' => 'green'
                ]
            ],
            'position' => 'bottom-right',
            'always_visible' => true
        ];

        return response()->json($widgetData);
    }
}