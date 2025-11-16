<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;

class SupportController extends Controller
{
    /**
     * Display support center
     */
    public function index()
    {
        return view('support.index');
    }

    /**
     * Create support ticket
     */
    public function createTicket(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:technical,account,data,training,other',
            'priority' => 'required|string|in:low,normal,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
            'contact_method' => 'nullable|string|in:email,phone,whatsapp'
        ]);

        $user = Auth::user();
        $ticketId = 'TKT' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/attachments', 'public');
                $attachmentPaths[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }
        }

        $ticketData = [
            'ticket_id' => $ticketId,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'Anonymous User',
            'user_email' => $user ? $user->email : null,
            'category' => $request->category,
            'priority' => $request->priority,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachments' => $attachmentPaths,
            'contact_method' => $request->contact_method ?? 'email',
            'status' => 'open',
            'created_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];

        // In a real application, save to database
        // SupportTicket::create($ticketData);

        // Send notification to support team
        $this->notifySupportTeam($ticketData);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully. You will receive updates via email.',
            'ticket_id' => $ticketId,
            'estimated_response_time' => $this->getEstimatedResponseTime($request->priority)
        ]);
    }

    /**
     * Get user's support tickets
     */
    public function getUserTickets()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'tickets' => [],
                'message' => 'Authentication required to view tickets.'
            ]);
        }

        // Mock data - in real app, fetch from database
        $tickets = [
            [
                'ticket_id' => 'TKT00001',
                'category' => 'technical',
                'priority' => 'high',
                'subject' => 'Cannot export student data',
                'status' => 'in_progress',
                'created_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subHours(6)->format('Y-m-d H:i:s'),
                'last_response' => 'We are investigating the export issue and will update you soon.',
                'assigned_to' => 'Technical Support Team'
            ],
            [
                'ticket_id' => 'TKT00002',
                'category' => 'account',
                'priority' => 'normal',
                'subject' => 'Password reset request',
                'status' => 'resolved',
                'created_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(4)->format('Y-m-d H:i:s'),
                'last_response' => 'Password has been reset successfully. Please check your email.',
                'assigned_to' => 'Account Support'
            ]
        ];

        return response()->json([
            'tickets' => $tickets,
            'total_count' => count($tickets),
            'open_count' => collect($tickets)->where('status', '!=', 'resolved')->count()
        ]);
    }

    /**
     * Get ticket details
     */
    public function getTicketDetails($ticketId)
    {
        $user = Auth::user();
        
        // Mock ticket data - in real app, fetch from database and verify ownership
        $ticket = [
            'ticket_id' => $ticketId,
            'category' => 'technical',
            'priority' => 'high',
            'subject' => 'Cannot export student data',
            'description' => 'When I try to export student data to Excel, the system shows an error message and the download fails.',
            'status' => 'in_progress',
            'created_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
            'updated_at' => now()->subHours(6)->format('Y-m-d H:i:s'),
            'assigned_to' => 'Technical Support Team',
            'attachments' => [
                [
                    'name' => 'error_screenshot.png',
                    'size' => '245 KB',
                    'url' => '/storage/support/attachments/error_screenshot.png'
                ]
            ],
            'conversation' => [
                [
                    'type' => 'user',
                    'author' => $user ? $user->name : 'User',
                    'message' => 'When I try to export student data to Excel, the system shows an error message and the download fails.',
                    'timestamp' => now()->subDays(2)->format('Y-m-d H:i:s'),
                    'attachments' => ['error_screenshot.png']
                ],
                [
                    'type' => 'support',
                    'author' => 'Technical Support',
                    'message' => 'Thank you for reporting this issue. We have received your ticket and are investigating the export functionality. We will update you within 24 hours.',
                    'timestamp' => now()->subDays(2)->addHours(2)->format('Y-m-d H:i:s')
                ],
                [
                    'type' => 'support',
                    'author' => 'Technical Support',
                    'message' => 'We have identified the issue and are working on a fix. The problem appears to be related to large datasets. As a temporary workaround, try exporting smaller date ranges.',
                    'timestamp' => now()->subHours(6)->format('Y-m-d H:i:s')
                ]
            ]
        ];

        return response()->json([
            'ticket' => $ticket
        ]);
    }

    /**
     * Add response to ticket
     */
    public function addResponse(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401);
        }

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support/attachments', 'public');
                $attachmentPaths[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }

        // In a real application, add response to database
        // and notify support team

        return response()->json([
            'success' => true,
            'message' => 'Response added successfully. Support team will be notified.',
            'response' => [
                'type' => 'user',
                'author' => $user->name,
                'message' => $request->message,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'attachments' => array_column($attachmentPaths, 'original_name')
            ]
        ]);
    }

    /**
     * Get support categories
     */
    public function getCategories()
    {
        return response()->json([
            'categories' => [
                'technical' => [
                    'label' => 'Technical Issues',
                    'description' => 'System errors, bugs, and technical problems',
                    'icon' => 'fas fa-cog',
                    'color' => 'blue',
                    'estimated_response' => '4-8 hours'
                ],
                'account' => [
                    'label' => 'Account & Access',
                    'description' => 'Login issues, password resets, permissions',
                    'icon' => 'fas fa-user',
                    'color' => 'green',
                    'estimated_response' => '2-4 hours'
                ],
                'data' => [
                    'label' => 'Data Management',
                    'description' => 'Data entry, export, import, and accuracy issues',
                    'icon' => 'fas fa-database',
                    'color' => 'purple',
                    'estimated_response' => '6-12 hours'
                ],
                'training' => [
                    'label' => 'Training & How-to',
                    'description' => 'Help with using system features and functions',
                    'icon' => 'fas fa-graduation-cap',
                    'color' => 'orange',
                    'estimated_response' => '1-2 business days'
                ],
                'other' => [
                    'label' => 'Other',
                    'description' => 'General questions and other support needs',
                    'icon' => 'fas fa-question-circle',
                    'color' => 'gray',
                    'estimated_response' => '1-2 business days'
                ]
            ],
            'priorities' => [
                'low' => [
                    'label' => 'Low',
                    'description' => 'General questions, minor issues',
                    'color' => 'gray',
                    'response_time' => '2-3 business days'
                ],
                'normal' => [
                    'label' => 'Normal',
                    'description' => 'Standard support requests',
                    'color' => 'blue',
                    'response_time' => '1-2 business days'
                ],
                'high' => [
                    'label' => 'High',
                    'description' => 'Issues affecting daily work',
                    'color' => 'orange',
                    'response_time' => '4-8 hours'
                ],
                'urgent' => [
                    'label' => 'Urgent',
                    'description' => 'Critical issues preventing work',
                    'color' => 'red',
                    'response_time' => '1-2 hours'
                ]
            ]
        ]);
    }

    /**
     * Get all support tickets (admin only)
     */
    public function getAllTickets(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $status = $request->get('status', 'all');
        $category = $request->get('category', 'all');
        $priority = $request->get('priority', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        // Mock data - in real app, fetch from database with filters
        $allTickets = [
            [
                'ticket_id' => 'TKT00001',
                'user_name' => 'John Doe',
                'user_email' => 'john@example.com',
                'category' => 'technical',
                'priority' => 'high',
                'subject' => 'Cannot export student data',
                'status' => 'in_progress',
                'created_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subHours(6)->format('Y-m-d H:i:s'),
                'assigned_to' => 'Technical Support Team',
                'response_count' => 3
            ],
            [
                'ticket_id' => 'TKT00002',
                'user_name' => 'Jane Smith',
                'user_email' => 'jane@example.com',
                'category' => 'account',
                'priority' => 'normal',
                'subject' => 'Password reset request',
                'status' => 'resolved',
                'created_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays(4)->format('Y-m-d H:i:s'),
                'assigned_to' => 'Account Support',
                'response_count' => 2
            ],
            [
                'ticket_id' => 'TKT00003',
                'user_name' => 'Bob Johnson',
                'user_email' => 'bob@example.com',
                'category' => 'data',
                'priority' => 'urgent',
                'subject' => 'Student records missing',
                'status' => 'open',
                'created_at' => now()->subHours(3)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subHours(3)->format('Y-m-d H:i:s'),
                'assigned_to' => null,
                'response_count' => 0
            ]
        ];

        // Apply filters (simplified for demo)
        $filteredTickets = collect($allTickets);
        
        if ($status !== 'all') {
            $filteredTickets = $filteredTickets->where('status', $status);
        }
        
        if ($category !== 'all') {
            $filteredTickets = $filteredTickets->where('category', $category);
        }
        
        if ($priority !== 'all') {
            $filteredTickets = $filteredTickets->where('priority', $priority);
        }

        $total = $filteredTickets->count();
        $tickets = $filteredTickets->forPage($page, $perPage)->values();

        return response()->json([
            'tickets' => $tickets,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage)
            ],
            'filters' => [
                'status' => $status,
                'category' => $category,
                'priority' => $priority
            ]
        ]);
    }

    /**
     * Update ticket status (admin only)
     */
    public function updateTicketStatus(Request $request, $ticketId)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|string|max:255',
            'internal_notes' => 'nullable|string|max:1000'
        ]);

        // In a real application, update the database
        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully.',
            'ticket_id' => $ticketId,
            'new_status' => $request->status
        ]);
    }

    /**
     * Get support statistics (admin only)
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
            'total_tickets' => 234,
            'tickets_today' => 8,
            'tickets_this_week' => 45,
            'tickets_this_month' => 156,
            'status_distribution' => [
                'open' => 23,
                'in_progress' => 34,
                'resolved' => 167,
                'closed' => 10
            ],
            'category_distribution' => [
                'technical' => 89,
                'account' => 67,
                'data' => 45,
                'training' => 23,
                'other' => 10
            ],
            'priority_distribution' => [
                'low' => 45,
                'normal' => 123,
                'high' => 56,
                'urgent' => 10
            ],
            'average_response_time' => '4.2 hours',
            'average_resolution_time' => '1.8 days',
            'customer_satisfaction' => 4.6,
            'first_response_rate' => 94.5,
            'resolution_rate' => 89.2
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Get knowledge base articles
     */
    public function getKnowledgeBase()
    {
        return response()->json([
            'categories' => [
                'getting_started' => [
                    'name' => 'Getting Started',
                    'icon' => 'fas fa-play-circle',
                    'articles' => [
                        [
                            'title' => 'How to Login to the System',
                            'summary' => 'Step-by-step guide to accessing your account',
                            'url' => '/support/kb/how-to-login',
                            'views' => 1234,
                            'helpful_votes' => 89
                        ],
                        [
                            'title' => 'Dashboard Overview',
                            'summary' => 'Understanding the main dashboard features',
                            'url' => '/support/kb/dashboard-overview',
                            'views' => 987,
                            'helpful_votes' => 76
                        ]
                    ]
                ],
                'student_management' => [
                    'name' => 'Student Management',
                    'icon' => 'fas fa-user-graduate',
                    'articles' => [
                        [
                            'title' => 'Adding New Students',
                            'summary' => 'Complete guide to student registration',
                            'url' => '/support/kb/adding-students',
                            'views' => 856,
                            'helpful_votes' => 67
                        ],
                        [
                            'title' => 'Editing Student Information',
                            'summary' => 'How to update student records',
                            'url' => '/support/kb/editing-students',
                            'views' => 654,
                            'helpful_votes' => 54
                        ]
                    ]
                ],
                'reports' => [
                    'name' => 'Reports & Analytics',
                    'icon' => 'fas fa-chart-bar',
                    'articles' => [
                        [
                            'title' => 'Generating Reports',
                            'summary' => 'How to create and customize reports',
                            'url' => '/support/kb/generating-reports',
                            'views' => 743,
                            'helpful_votes' => 62
                        ],
                        [
                            'title' => 'Exporting Data',
                            'summary' => 'Export options and formats available',
                            'url' => '/support/kb/exporting-data',
                            'views' => 532,
                            'helpful_votes' => 45
                        ]
                    ]
                ]
            ],
            'popular_articles' => [
                [
                    'title' => 'How to Login to the System',
                    'category' => 'Getting Started',
                    'views' => 1234,
                    'url' => '/support/kb/how-to-login'
                ],
                [
                    'title' => 'Dashboard Overview',
                    'category' => 'Getting Started',
                    'views' => 987,
                    'url' => '/support/kb/dashboard-overview'
                ],
                [
                    'title' => 'Adding New Students',
                    'category' => 'Student Management',
                    'views' => 856,
                    'url' => '/support/kb/adding-students'
                ]
            ]
        ]);
    }

    /**
     * Search knowledge base
     */
    public function searchKnowledgeBase(Request $request)
    {
        $query = $request->get('q', '');
        
        // Mock search results
        $results = [
            [
                'title' => 'How to Login to the System',
                'summary' => 'Step-by-step guide to accessing your account',
                'category' => 'Getting Started',
                'url' => '/support/kb/how-to-login',
                'relevance' => 95
            ],
            [
                'title' => 'Password Reset Instructions',
                'summary' => 'What to do if you forgot your password',
                'category' => 'Account Management',
                'url' => '/support/kb/password-reset',
                'relevance' => 87
            ]
        ];

        return response()->json([
            'query' => $query,
            'results' => $results,
            'total_results' => count($results)
        ]);
    }

    /**
     * Get estimated response time based on priority
     */
    private function getEstimatedResponseTime($priority)
    {
        $responseTimes = [
            'low' => '2-3 business days',
            'normal' => '1-2 business days',
            'high' => '4-8 hours',
            'urgent' => '1-2 hours'
        ];

        return $responseTimes[$priority] ?? '1-2 business days';
    }

    /**
     * Notify support team about new ticket
     */
    private function notifySupportTeam($ticketData)
    {
        // In a real application, send email/notification to support team
        \Log::info('New support ticket created', [
            'ticket_id' => $ticketData['ticket_id'],
            'category' => $ticketData['category'],
            'priority' => $ticketData['priority'],
            'subject' => $ticketData['subject']
        ]);
    }
}