<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot interface
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Process chatbot messages
     */
    public function processMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $message = strtolower(trim($request->message));
        $response = $this->generateResponse($message);

        // Log the conversation for improvement
        Log::info('Chatbot conversation', [
            'user_message' => $request->message,
            'bot_response' => $response,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'response' => $response,
            'timestamp' => now()->format('H:i')
        ]);
    }

    /**
     * Generate chatbot response based on user message
     */
    private function generateResponse($message)
    {
        $responses = [
            // Greetings
            'hello' => 'Hello! How can I help you with the Gombe SS Hub system today?',
            'hi' => 'Hi there! I\'m here to help you navigate the system. What do you need assistance with?',
            'good morning' => 'Good morning! How can I assist you today?',
            'good afternoon' => 'Good afternoon! What can I help you with?',
            'good evening' => 'Good evening! How may I help you?',

            // System Navigation
            'how to login' => 'To login, go to the login page and enter your email and password provided by the admin. If you forgot your password, contact the administrator.',
            'dashboard' => 'The dashboard shows system statistics, recent activities, and quick access to main features. You can view total users, students, teachers, and various charts.',
            'search' => 'Use the search bar at the top to find students or teachers by name. You can also use advanced search with filters.',
            'reports' => 'Reports section allows you to generate various statistics like students per class, gender distribution, age groups, and location data.',

            // Student Management
            'add student' => 'To add a student, go to Admin > Students > Add New Student. Fill in all required information including personal details and location.',
            'student records' => 'Student records can be viewed, edited, and exported from the Students section. You can filter by class, gender, or other criteria.',
            'olevel students' => 'O\'Level students are managed separately from A\'Level students. Each has its own section with specific forms and requirements.',
            'alevel students' => 'A\'Level students have different requirements and subjects compared to O\'Level students.',

            // Staff Management
            'add teacher' => 'To add a teacher, go to Admin > Staff > Add New Staff. Choose between regular staff and government staff types.',
            'staff records' => 'Staff records include personal information, qualifications, and department assignments. You can export staff data to Excel or PDF.',
            'government staff' => 'Government staff have additional fields like IPPS number and different management requirements.',

            // Export Features
            'export data' => 'You can export data to Excel, PDF, or CSV formats. There are also options to print, email, or share via social media.',
            'print' => 'Use the print function to generate printable versions of reports and records.',
            'email data' => 'You can email reports and data directly from the system to specified recipients.',

            // Map Features
            'map' => 'The map shows student and staff locations across Uganda. It helps visualize geographical distribution and statistics.',
            'location data' => 'Location data includes districts, LGAs, and specific coordinates when available.',

            // User Management
            'user roles' => 'The system has different user roles: Super Admin (full access), Admin (most features), and regular users (limited access).',
            'permissions' => 'Permissions are role-based. Contact your administrator if you need additional access.',

            // Notifications
            'notifications' => 'Notifications keep you updated about system changes, new additions, and important alerts.',
            'emergency' => 'Emergency notifications can be sent to all users for urgent communications.',

            // Settings
            'settings' => 'System settings include theme preferences, notification settings, backup configurations, and security options.',
            'dark mode' => 'You can toggle between light and dark themes in the settings section.',
            'language' => 'Multiple languages are supported. Change your language preference in settings.',

            // Troubleshooting
            'not working' => 'If something isn\'t working, try refreshing the page, clearing your browser cache, or contact the administrator.',
            'error' => 'If you encounter errors, note the error message and contact technical support with details.',
            'slow' => 'If the system is slow, it might be due to network issues or high server load. Try again later.',

            // Contact Information
            'contact admin' => 'You can contact the administrator through the emergency contact buttons or the contact form.',
            'support' => 'For technical support, use the support section or contact the helpdesk.',
            'help' => 'Help is available through this chatbot, the FAQ section, or by contacting support.',
            'feedback' => 'You can send feedback directly to the Super Admin via WhatsApp by clicking the "Feedback" button below the chat. Your feedback helps us improve the system!',
            'send feedback' => 'Click the green "Feedback" button below to send your feedback directly to the Super Admin via WhatsApp (0779201801).',
            'whatsapp' => 'You can contact the Super Admin directly via WhatsApp at 0779201801. Use the Feedback button to send a message.',

            // Default responses
            'default' => [
                'I\'m not sure I understand that. Could you please rephrase your question?',
                'That\'s an interesting question. For specific technical issues, please contact the administrator.',
                'I\'m here to help with system-related questions. Could you be more specific?',
                'For detailed assistance with that topic, please check the FAQ section or contact support.',
            ]
        ];

        // Check for exact matches first
        if (isset($responses[$message])) {
            return $responses[$message];
        }

        // Check for partial matches
        foreach ($responses as $key => $response) {
            if ($key !== 'default' && (strpos($message, $key) !== false || strpos($key, $message) !== false)) {
                return $response;
            }
        }

        // Check for keywords
        $keywords = [
            'login' => $responses['how to login'],
            'student' => $responses['student records'],
            'teacher' => $responses['add teacher'],
            'staff' => $responses['staff records'],
            'export' => $responses['export data'],
            'map' => $responses['map'],
            'report' => $responses['reports'],
            'search' => $responses['search'],
            'dashboard' => $responses['dashboard'],
            'notification' => $responses['notifications'],
            'setting' => $responses['settings'],
            'help' => $responses['help'],
            'contact' => $responses['contact admin'],
            'support' => $responses['support'],
            'error' => $responses['error'],
            'problem' => $responses['not working'],
            'feedback' => $responses['feedback'],
            'whatsapp' => $responses['whatsapp'],
        ];

        foreach ($keywords as $keyword => $response) {
            if (strpos($message, $keyword) !== false) {
                return $response;
            }
        }

        // Return random default response
        $defaultResponses = $responses['default'];
        return $defaultResponses[array_rand($defaultResponses)];
    }

    /**
     * Get chatbot suggestions
     */
    public function getSuggestions()
    {
        $suggestions = [
            'How to login?',
            'How to add a student?',
            'How to export data?',
            'How to use the map?',
            'How to generate reports?',
            'How to search for records?',
            'How to contact admin?',
            'How to change settings?',
            'What are user roles?',
            'How to use notifications?'
        ];

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Get frequently asked questions
     */
    public function getFAQ()
    {
        $faqs = [
            [
                'question' => 'How do I login to the system?',
                'answer' => 'Use the email and password provided by your administrator. Go to the login page and enter your credentials.'
            ],
            [
                'question' => 'How do I add a new student?',
                'answer' => 'Navigate to Admin > Students > Add New Student and fill in all required information.'
            ],
            [
                'question' => 'How can I export data?',
                'answer' => 'Use the export buttons available in each section to download data in Excel, PDF, or CSV format.'
            ],
            [
                'question' => 'How do I generate reports?',
                'answer' => 'Go to the Reports section and select the type of report you want to generate.'
            ],
            [
                'question' => 'How do I search for records?',
                'answer' => 'Use the search bar at the top of the page or use the advanced search feature for more specific results.'
            ],
            [
                'question' => 'How do I contact the administrator?',
                'answer' => 'Use the emergency contact buttons or the contact form available in the system.'
            ]
        ];

        return response()->json([
            'faqs' => $faqs
        ]);
    }
}