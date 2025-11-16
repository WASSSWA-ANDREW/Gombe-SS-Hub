<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display feedback form
     */
    public function index()
    {
        return view('feedback.index');
    }

    /**
     * Store feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:bug,feature,improvement,complaint,compliment,other',
            'category' => 'required|string|in:system,ui,performance,data,security,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'page_url' => 'nullable|url|max:500',
            'browser_info' => 'nullable|string|max:500',
            'screenshot' => 'nullable|image|max:5120', // 5MB max
            'contact_email' => 'nullable|email|max:255',
            'allow_contact' => 'nullable|boolean'
        ]);

        $user = Auth::user();
        $screenshotPath = null;

        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('feedback/screenshots', 'public');
        }

        $feedbackData = [
            'user_id' => $user ? $user->id : null,
            'type' => $request->type,
            'category' => $request->category,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority ?? 'normal',
            'page_url' => $request->page_url,
            'browser_info' => $request->browser_info ?? $this->getBrowserInfo(),
            'screenshot_path' => $screenshotPath,
            'contact_email' => $request->contact_email ?? ($user ? $user->email : null),
            'allow_contact' => $request->allow_contact ?? false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'open',
            'created_at' => now()
        ];

        // In a real application, save to database
        // $feedback = Feedback::create($feedbackData);

        // Send notification to admin
        $this->notifyAdmin($feedbackData);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback! We\'ll review it and get back to you if needed.',
            'feedback_id' => 'FB' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)
        ]);
    }

    /**
     * Get feedback categories
     */
    public function getCategories()
    {
        return response()->json([
            'types' => [
                'bug' => [
                    'label' => 'Bug Report',
                    'description' => 'Report a problem or error in the system',
                    'icon' => 'fas fa-bug',
                    'color' => 'red'
                ],
                'feature' => [
                    'label' => 'Feature Request',
                    'description' => 'Suggest a new feature or functionality',
                    'icon' => 'fas fa-lightbulb',
                    'color' => 'blue'
                ],
                'improvement' => [
                    'label' => 'Improvement',
                    'description' => 'Suggest improvements to existing features',
                    'icon' => 'fas fa-arrow-up',
                    'color' => 'green'
                ],
                'complaint' => [
                    'label' => 'Complaint',
                    'description' => 'Report an issue or concern',
                    'icon' => 'fas fa-exclamation-triangle',
                    'color' => 'orange'
                ],
                'compliment' => [
                    'label' => 'Compliment',
                    'description' => 'Share positive feedback',
                    'icon' => 'fas fa-heart',
                    'color' => 'pink'
                ],
                'other' => [
                    'label' => 'Other',
                    'description' => 'General feedback or questions',
                    'icon' => 'fas fa-comment',
                    'color' => 'gray'
                ]
            ],
            'categories' => [
                'system' => [
                    'label' => 'System Functionality',
                    'description' => 'Core system features and operations'
                ],
                'ui' => [
                    'label' => 'User Interface',
                    'description' => 'Design, layout, and user experience'
                ],
                'performance' => [
                    'label' => 'Performance',
                    'description' => 'Speed, loading times, and responsiveness'
                ],
                'data' => [
                    'label' => 'Data Management',
                    'description' => 'Data entry, export, and accuracy'
                ],
                'security' => [
                    'label' => 'Security',
                    'description' => 'Login, permissions, and data protection'
                ],
                'other' => [
                    'label' => 'Other',
                    'description' => 'General feedback not covered above'
                ]
            ],
            'priorities' => [
                'low' => [
                    'label' => 'Low',
                    'description' => 'Minor issue, no urgency',
                    'color' => 'gray'
                ],
                'normal' => [
                    'label' => 'Normal',
                    'description' => 'Standard feedback',
                    'color' => 'blue'
                ],
                'high' => [
                    'label' => 'High',
                    'description' => 'Important issue affecting work',
                    'color' => 'orange'
                ],
                'urgent' => [
                    'label' => 'Urgent',
                    'description' => 'Critical issue requiring immediate attention',
                    'color' => 'red'
                ]
            ]
        ]);
    }

    /**
     * Get user's feedback history
     */
    public function getUserFeedback()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'feedback' => [],
                'message' => 'Authentication required to view feedback history.'
            ]);
        }

        // Mock data - in real app, fetch from database
        $feedback = [
            [
                'id' => 'FB0001',
                'type' => 'bug',
                'category' => 'system',
                'subject' => 'Login page not loading',
                'status' => 'resolved',
                'priority' => 'high',
                'created_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'admin_response' => 'Issue has been fixed in the latest update.'
            ],
            [
                'id' => 'FB0002',
                'type' => 'feature',
                'category' => 'ui',
                'subject' => 'Add dark mode toggle',
                'status' => 'in_progress',
                'priority' => 'normal',
                'created_at' => now()->subDays(10)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'admin_response' => 'Dark mode feature is being developed and will be available soon.'
            ]
        ];

        return response()->json([
            'feedback' => $feedback,
            'total_count' => count($feedback)
        ]);
    }

    /**
     * Get all feedback (admin only)
     */
    public function getAllFeedback(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $priority = $request->get('priority', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        // Mock data - in real app, fetch from database with filters
        $allFeedback = [
            [
                'id' => 'FB0001',
                'user_name' => 'John Doe',
                'user_email' => 'john@example.com',
                'type' => 'bug',
                'category' => 'system',
                'subject' => 'Login page not loading',
                'message' => 'The login page takes too long to load and sometimes doesn\'t load at all.',
                'status' => 'resolved',
                'priority' => 'high',
                'created_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'admin_response' => 'Issue has been fixed in the latest update.',
                'page_url' => '/login'
            ],
            [
                'id' => 'FB0002',
                'user_name' => 'Jane Smith',
                'user_email' => 'jane@example.com',
                'type' => 'feature',
                'category' => 'ui',
                'subject' => 'Add dark mode toggle',
                'message' => 'It would be great to have a dark mode option for better viewing in low light.',
                'status' => 'in_progress',
                'priority' => 'normal',
                'created_at' => now()->subDays(10)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'admin_response' => 'Dark mode feature is being developed.',
                'page_url' => '/admin/dashboard'
            ],
            [
                'id' => 'FB0003',
                'user_name' => 'Anonymous User',
                'user_email' => null,
                'type' => 'improvement',
                'category' => 'performance',
                'subject' => 'Slow report generation',
                'message' => 'Reports take a very long time to generate, especially for large datasets.',
                'status' => 'open',
                'priority' => 'high',
                'created_at' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(1)->format('Y-m-d H:i:s'),
                'admin_response' => null,
                'page_url' => '/admin/reports'
            ]
        ];

        // Apply filters (simplified for demo)
        $filteredFeedback = collect($allFeedback);
        
        if ($status !== 'all') {
            $filteredFeedback = $filteredFeedback->where('status', $status);
        }
        
        if ($type !== 'all') {
            $filteredFeedback = $filteredFeedback->where('type', $type);
        }
        
        if ($priority !== 'all') {
            $filteredFeedback = $filteredFeedback->where('priority', $priority);
        }

        $total = $filteredFeedback->count();
        $feedback = $filteredFeedback->forPage($page, $perPage)->values();

        return response()->json([
            'feedback' => $feedback,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage)
            ],
            'filters' => [
                'status' => $status,
                'type' => $type,
                'priority' => $priority
            ]
        ]);
    }

    /**
     * Update feedback status (admin only)
     */
    public function updateStatus(Request $request, $feedbackId)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'admin_response' => 'nullable|string|max:1000'
        ]);

        // In a real application, update the database
        // $feedback = Feedback::findOrFail($feedbackId);
        // $feedback->update([
        //     'status' => $request->status,
        //     'admin_response' => $request->admin_response,
        //     'updated_by' => Auth::id()
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback status updated successfully.',
            'feedback_id' => $feedbackId,
            'new_status' => $request->status
        ]);
    }

    /**
     * Get feedback statistics (admin only)
     */
    public function getStatistics()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $statistics = [
            'total_feedback' => 156,
            'feedback_today' => 5,
            'feedback_this_week' => 23,
            'feedback_this_month' => 89,
            'status_distribution' => [
                'open' => 45,
                'in_progress' => 23,
                'resolved' => 78,
                'closed' => 10
            ],
            'type_distribution' => [
                'bug' => 67,
                'feature' => 34,
                'improvement' => 28,
                'complaint' => 15,
                'compliment' => 8,
                'other' => 4
            ],
            'priority_distribution' => [
                'low' => 23,
                'normal' => 89,
                'high' => 34,
                'urgent' => 10
            ],
            'category_distribution' => [
                'system' => 45,
                'ui' => 32,
                'performance' => 28,
                'data' => 21,
                'security' => 18,
                'other' => 12
            ],
            'average_response_time' => '2.5 days',
            'resolution_rate' => 85.5,
            'user_satisfaction' => 4.2
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Export feedback data (admin only)
     */
    public function exportFeedback(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $format = $request->get('format', 'csv');
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // In a real application, you would generate the actual export file
        return response()->json([
            'success' => true,
            'message' => 'Feedback export generated successfully.',
            'download_url' => "/admin/feedback/download/feedback-export-{$format}-" . now()->format('Y-m-d') . ".{$format}",
            'format' => $format,
            'filters_applied' => [
                'status' => $status,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]
        ]);
    }

    /**
     * Get feedback templates
     */
    public function getTemplates()
    {
        return response()->json([
            'templates' => [
                'bug_report' => [
                    'name' => 'Bug Report Template',
                    'type' => 'bug',
                    'category' => 'system',
                    'subject' => 'Bug: [Brief description]',
                    'message' => "**Steps to reproduce:**\n1. \n2. \n3. \n\n**Expected behavior:**\n\n**Actual behavior:**\n\n**Additional information:**\n"
                ],
                'feature_request' => [
                    'name' => 'Feature Request Template',
                    'type' => 'feature',
                    'category' => 'system',
                    'subject' => 'Feature Request: [Feature name]',
                    'message' => "**Feature description:**\n\n**Use case:**\n\n**Benefits:**\n\n**Additional notes:**\n"
                ],
                'ui_improvement' => [
                    'name' => 'UI Improvement Template',
                    'type' => 'improvement',
                    'category' => 'ui',
                    'subject' => 'UI Improvement: [Area to improve]',
                    'message' => "**Current situation:**\n\n**Suggested improvement:**\n\n**Expected outcome:**\n\n**Screenshots (if applicable):**\n"
                ]
            ]
        ]);
    }

    /**
     * Get browser information
     */
    private function getBrowserInfo()
    {
        $userAgent = request()->userAgent();
        return [
            'user_agent' => $userAgent,
            'ip_address' => request()->ip(),
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Notify admin about new feedback
     */
    private function notifyAdmin($feedbackData)
    {
        // In a real application, you would send email notification to admin
        // For now, just log it
        \Log::info('New feedback received', [
            'type' => $feedbackData['type'],
            'priority' => $feedbackData['priority'],
            'subject' => $feedbackData['subject'],
            'user_id' => $feedbackData['user_id']
        ]);
    }
}