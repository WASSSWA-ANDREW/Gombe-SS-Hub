<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display FAQ page
     */
    public function index()
    {
        $faqs = $this->getFaqData();
        return view('faq.index', compact('faqs'));
    }

    /**
     * Get FAQ data organized by categories
     */
    public function getFaqData()
    {
        return [
            'General' => [
                [
                    'question' => 'What is Gombe SS Hub?',
                    'answer' => 'Gombe SS Hub is a comprehensive school management system designed to collect, store, and manage data for students and teachers. It provides features for data entry, search, reporting, and analysis.'
                ],
                [
                    'question' => 'Who can use this system?',
                    'answer' => 'The system is designed for school administrators, teachers, and authorized staff members. Different user roles have different levels of access to system features.'
                ],
                [
                    'question' => 'Is my data secure?',
                    'answer' => 'Yes, the system implements multiple security measures including encryption, secure authentication, multi-factor verification, and regular backups to protect your data.'
                ]
            ],
            'Login & Authentication' => [
                [
                    'question' => 'How do I login to the system?',
                    'answer' => 'Go to the login page and enter your email and password provided by the administrator. If you\'re an admin, use the admin login page.'
                ],
                [
                    'question' => 'I forgot my password, what should I do?',
                    'answer' => 'Contact your system administrator to reset your password. For security reasons, only administrators can reset passwords.'
                ],
                [
                    'question' => 'Why can\'t I access certain features?',
                    'answer' => 'Access to features depends on your user role. Contact your administrator if you need additional permissions.'
                ]
            ],
            'Student Management' => [
                [
                    'question' => 'How do I add a new student?',
                    'answer' => 'Navigate to Admin > Students > Add New Student. Choose between O\'Level or A\'Level students and fill in all required information including personal details, academic information, and location data.'
                ],
                [
                    'question' => 'Can I edit student information after adding it?',
                    'answer' => 'Yes, you can edit student information by going to the student list, finding the student, and clicking the edit button.'
                ],
                [
                    'question' => 'How do I delete a student record?',
                    'answer' => 'Find the student in the list and click the delete button. Note that this action cannot be undone, so use it carefully.'
                ],
                [
                    'question' => 'What\'s the difference between O\'Level and A\'Level students?',
                    'answer' => 'O\'Level and A\'Level students have different forms and requirements. O\'Level is for junior secondary students, while A\'Level is for senior secondary students.'
                ]
            ],
            'Staff Management' => [
                [
                    'question' => 'How do I add a new staff member?',
                    'answer' => 'Go to Admin > Staff > Add New Staff. Choose between regular staff and government staff, then fill in all required information.'
                ],
                [
                    'question' => 'What\'s the difference between regular and government staff?',
                    'answer' => 'Government staff have additional fields like IPPS numbers and different management requirements compared to regular staff.'
                ],
                [
                    'question' => 'Can I export staff information?',
                    'answer' => 'Yes, you can export staff data to Excel or PDF format using the export buttons in the staff section.'
                ]
            ],
            'Search & Reports' => [
                [
                    'question' => 'How do I search for students or teachers?',
                    'answer' => 'Use the search bar at the top of the page to search by name. You can also use the advanced search feature for more specific criteria.'
                ],
                [
                    'question' => 'What types of reports can I generate?',
                    'answer' => 'You can generate various reports including students per class, teachers per department, gender statistics, age groups, district statistics, and custom reports.'
                ],
                [
                    'question' => 'Can I export reports?',
                    'answer' => 'Yes, reports can be exported to Excel, PDF, or CSV formats. You can also print them or share via email and social media.'
                ]
            ],
            'Map & Location Features' => [
                [
                    'question' => 'How does the map feature work?',
                    'answer' => 'The map displays the geographical distribution of students and staff across Uganda. It shows locations by district and provides statistical information.'
                ],
                [
                    'question' => 'Why is location data important?',
                    'answer' => 'Location data helps in understanding geographical distribution, planning resources, and generating location-based reports and statistics.'
                ]
            ],
            'Export & Sharing' => [
                [
                    'question' => 'What export formats are available?',
                    'answer' => 'You can export data in Excel (.xlsx), PDF, and CSV formats. There are also options to print or share via email and social media.'
                ],
                [
                    'question' => 'Can I share data on social media?',
                    'answer' => 'Yes, the system supports sharing on various platforms including WhatsApp, Facebook, Twitter, LinkedIn, and others.'
                ],
                [
                    'question' => 'How do I email reports?',
                    'answer' => 'Use the email export option to send reports directly to specified email addresses.'
                ]
            ],
            'System Features' => [
                [
                    'question' => 'How do I change the theme?',
                    'answer' => 'Go to Settings and use the dark mode toggle to switch between light and dark themes.'
                ],
                [
                    'question' => 'Can I change the language?',
                    'answer' => 'Yes, the system supports multiple languages. Change your language preference in the settings section.'
                ],
                [
                    'question' => 'How do notifications work?',
                    'answer' => 'The system sends notifications for important updates, changes, and alerts. You can manage notification preferences in settings.'
                ],
                [
                    'question' => 'What are the emergency contact buttons?',
                    'answer' => 'These are floating buttons that provide quick access to contact administrators via phone call or WhatsApp in case of emergencies.'
                ]
            ],
            'Troubleshooting' => [
                [
                    'question' => 'The system is running slowly, what should I do?',
                    'answer' => 'Try refreshing the page, clearing your browser cache, or check your internet connection. If the problem persists, contact technical support.'
                ],
                [
                    'question' => 'I\'m getting error messages, what should I do?',
                    'answer' => 'Note the exact error message and contact technical support with the details. Include what you were trying to do when the error occurred.'
                ],
                [
                    'question' => 'Some features are not working, what should I do?',
                    'answer' => 'Try refreshing the page or logging out and back in. If the problem continues, contact your administrator.'
                ]
            ],
            'Mobile & Accessibility' => [
                [
                    'question' => 'Can I use this system on my mobile phone?',
                    'answer' => 'Yes, the system has a responsive design that works on smartphones, tablets, and other mobile devices.'
                ],
                [
                    'question' => 'Are there accessibility features?',
                    'answer' => 'Yes, the system includes high contrast themes, screen reader compatibility, keyboard navigation support, and alt text for images.'
                ]
            ],
            'Contact & Support' => [
                [
                    'question' => 'How do I contact technical support?',
                    'answer' => 'Use the support section, contact form, or emergency contact buttons. You can also reach out through the helpdesk feature.'
                ],
                [
                    'question' => 'How do I provide feedback?',
                    'answer' => 'Use the feedback form available in the system to share your suggestions and comments.'
                ],
                [
                    'question' => 'Where can I find more help?',
                    'answer' => 'You can use the chatbot, browse this FAQ section, contact support, or reach out to your system administrator.'
                ]
            ]
        ];
    }

    /**
     * Search FAQs
     */
    public function search(Request $request)
    {
        $query = strtolower($request->get('q', ''));
        $faqs = $this->getFaqData();
        $results = [];

        foreach ($faqs as $category => $categoryFaqs) {
            foreach ($categoryFaqs as $faq) {
                if (strpos(strtolower($faq['question']), $query) !== false || 
                    strpos(strtolower($faq['answer']), $query) !== false) {
                    $results[] = [
                        'category' => $category,
                        'question' => $faq['question'],
                        'answer' => $faq['answer']
                    ];
                }
            }
        }

        return response()->json([
            'results' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * Get popular FAQs
     */
    public function getPopular()
    {
        $popularFaqs = [
            [
                'question' => 'How do I login to the system?',
                'answer' => 'Go to the login page and enter your email and password provided by the administrator.',
                'category' => 'Login & Authentication'
            ],
            [
                'question' => 'How do I add a new student?',
                'answer' => 'Navigate to Admin > Students > Add New Student and fill in all required information.',
                'category' => 'Student Management'
            ],
            [
                'question' => 'How do I export data?',
                'answer' => 'Use the export buttons available in each section to download data in various formats.',
                'category' => 'Export & Sharing'
            ],
            [
                'question' => 'How do I generate reports?',
                'answer' => 'Go to the Reports section and select the type of report you want to generate.',
                'category' => 'Search & Reports'
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'Use the support section, contact form, or emergency contact buttons.',
                'category' => 'Contact & Support'
            ]
        ];

        return response()->json([
            'popular_faqs' => $popularFaqs
        ]);
    }
}