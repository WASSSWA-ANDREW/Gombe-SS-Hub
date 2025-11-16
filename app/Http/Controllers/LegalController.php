<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegalController extends Controller
{
    /**
     * Display privacy policy
     */
    public function privacyPolicy()
    {
        $privacyPolicy = $this->getPrivacyPolicyContent();
        return view('legal.privacy-policy', compact('privacyPolicy'));
    }

    /**
     * Display terms of service
     */
    public function termsOfService()
    {
        $termsOfService = $this->getTermsOfServiceContent();
        return view('legal.terms-of-service', compact('termsOfService'));
    }

    /**
     * Display cookies policy
     */
    public function cookiesPolicy()
    {
        $cookiesPolicy = $this->getCookiesPolicyContent();
        return view('legal.cookies-policy', compact('cookiesPolicy'));
    }

    /**
     * Display disclaimer
     */
    public function disclaimer()
    {
        $disclaimer = $this->getDisclaimerContent();
        return view('legal.disclaimer', compact('disclaimer'));
    }

    /**
     * Display about us page
     */
    public function aboutUs()
    {
        $aboutUs = $this->getAboutUsContent();
        return view('legal.about-us', compact('aboutUs'));
    }

    /**
     * Get privacy policy content
     */
    public function getPrivacyPolicyContent()
    {
        return [
            'title' => 'Privacy Policy',
            'last_updated' => '2024-01-15',
            'effective_date' => '2024-01-15',
            'sections' => [
                [
                    'title' => 'Introduction',
                    'content' => 'Gombe Secondary School Hub ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our school management system. Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the application.'
                ],
                [
                    'title' => 'Information We Collect',
                    'content' => 'We may collect information about you in a variety of ways. The information we may collect includes:',
                    'subsections' => [
                        [
                            'title' => 'Personal Data',
                            'content' => 'Personally identifiable information, such as your name, email address, phone number, and demographic information that you voluntarily give to us when you register with the application or when you choose to participate in various activities related to the application.'
                        ],
                        [
                            'title' => 'Student and Staff Data',
                            'content' => 'Information related to students and staff including academic records, attendance data, personal details, contact information, and location data for administrative and educational purposes.'
                        ],
                        [
                            'title' => 'Usage Data',
                            'content' => 'Information automatically collected when you access the application, including your IP address, browser type, operating system, access times, and the pages you have viewed directly before and after accessing the application.'
                        ]
                    ]
                ],
                [
                    'title' => 'How We Use Your Information',
                    'content' => 'We use the information we collect to:',
                    'list' => [
                        'Provide, operate, and maintain our school management system',
                        'Improve, personalize, and expand our services',
                        'Understand and analyze how you use our application',
                        'Develop new products, services, features, and functionality',
                        'Communicate with you for customer service and support',
                        'Send you administrative information and updates',
                        'Generate reports and analytics for educational administration',
                        'Ensure the security and integrity of our system'
                    ]
                ],
                [
                    'title' => 'Data Security',
                    'content' => 'We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse.'
                ],
                [
                    'title' => 'Data Retention',
                    'content' => 'We will retain your personal information only for as long as is necessary for the purposes set out in this privacy policy. We will retain and use your information to the extent necessary to comply with our legal obligations, resolve disputes, and enforce our policies.'
                ],
                [
                    'title' => 'Your Rights',
                    'content' => 'Depending on your location, you may have the following rights regarding your personal information:',
                    'list' => [
                        'The right to access – You have the right to request copies of your personal data',
                        'The right to rectification – You have the right to request that we correct any information you believe is inaccurate',
                        'The right to erasure – You have the right to request that we erase your personal data, under certain conditions',
                        'The right to restrict processing – You have the right to request that we restrict the processing of your personal data',
                        'The right to data portability – You have the right to request that we transfer the data that we have collected to another organization'
                    ]
                ],
                [
                    'title' => 'Contact Information',
                    'content' => 'If you have questions or comments about this Privacy Policy, please contact us at:',
                    'contact' => [
                        'email' => 'privacy@gombess.edu.ug',
                        'phone' => '+256-700-000-001',
                        'address' => 'Gombe Secondary School, Gombe, Uganda'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get terms of service content
     */
    public function getTermsOfServiceContent()
    {
        return [
            'title' => 'Terms of Service',
            'last_updated' => '2024-01-15',
            'effective_date' => '2024-01-15',
            'sections' => [
                [
                    'title' => 'Agreement to Terms',
                    'content' => 'These Terms of Service ("Terms") govern your use of the Gombe Secondary School Hub application ("Service") operated by Gombe Secondary School ("us", "we", or "our"). By accessing or using our Service, you agree to be bound by these Terms.'
                ],
                [
                    'title' => 'Description of Service',
                    'content' => 'Gombe SS Hub is a comprehensive school management system designed to collect, store, and manage data for students and teachers. The system provides features for data entry, search, reporting, analytics, and administrative functions for educational institutions.'
                ],
                [
                    'title' => 'User Accounts',
                    'content' => 'When you create an account with us, you must provide information that is accurate, complete, and current at all times. You are responsible for safeguarding the password and for all activities that occur under your account.'
                ],
                [
                    'title' => 'Acceptable Use',
                    'content' => 'You may use our Service only for lawful purposes and in accordance with these Terms. You agree not to use the Service:',
                    'list' => [
                        'In any way that violates any applicable federal, state, local, or international law or regulation',
                        'To transmit, or procure the sending of, any advertising or promotional material, or any other form of similar solicitation',
                        'To impersonate or attempt to impersonate the School, a School employee, another user, or any other person or entity',
                        'To engage in any other conduct that restricts or inhibits anyone\'s use or enjoyment of the Service'
                    ]
                ],
                [
                    'title' => 'Data and Privacy',
                    'content' => 'Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Service, to understand our practices regarding the collection and use of your information.'
                ],
                [
                    'title' => 'Intellectual Property Rights',
                    'content' => 'The Service and its original content, features, and functionality are and will remain the exclusive property of Gombe Secondary School and its licensors. The Service is protected by copyright, trademark, and other laws.'
                ],
                [
                    'title' => 'User Content',
                    'content' => 'Our Service may allow you to post, link, store, share and otherwise make available certain information, text, graphics, or other material ("Content"). You are responsible for the Content that you post to the Service.'
                ],
                [
                    'title' => 'Prohibited Uses',
                    'content' => 'You may not use our Service:',
                    'list' => [
                        'For any unlawful purpose or to solicit others to perform unlawful acts',
                        'To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances',
                        'To infringe upon or violate our intellectual property rights or the intellectual property rights of others',
                        'To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate',
                        'To submit false or misleading information',
                        'To upload or transmit viruses or any other type of malicious code'
                    ]
                ],
                [
                    'title' => 'Termination',
                    'content' => 'We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.'
                ],
                [
                    'title' => 'Limitation of Liability',
                    'content' => 'In no event shall Gombe Secondary School, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.'
                ],
                [
                    'title' => 'Changes to Terms',
                    'content' => 'We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days notice prior to any new terms taking effect.'
                ],
                [
                    'title' => 'Contact Information',
                    'content' => 'If you have any questions about these Terms of Service, please contact us at:',
                    'contact' => [
                        'email' => 'legal@gombess.edu.ug',
                        'phone' => '+256-700-000-001',
                        'address' => 'Gombe Secondary School, Gombe, Uganda'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get cookies policy content
     */
    public function getCookiesPolicyContent()
    {
        return [
            'title' => 'Cookies Policy',
            'last_updated' => '2024-01-15',
            'effective_date' => '2024-01-15',
            'sections' => [
                [
                    'title' => 'What Are Cookies',
                    'content' => 'Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and to provide information to website owners.'
                ],
                [
                    'title' => 'How We Use Cookies',
                    'content' => 'We use cookies for several reasons:',
                    'list' => [
                        'To remember your login information and preferences',
                        'To analyze how our website is used and improve performance',
                        'To provide personalized content and features',
                        'To ensure the security of our application',
                        'To remember your theme and language preferences'
                    ]
                ],
                [
                    'title' => 'Types of Cookies We Use',
                    'content' => 'We use the following types of cookies:',
                    'subsections' => [
                        [
                            'title' => 'Essential Cookies',
                            'content' => 'These cookies are necessary for the website to function properly. They enable basic functions like page navigation and access to secure areas of the website.'
                        ],
                        [
                            'title' => 'Performance Cookies',
                            'content' => 'These cookies collect information about how visitors use our website, such as which pages are visited most often and if they get error messages from web pages.'
                        ],
                        [
                            'title' => 'Functionality Cookies',
                            'content' => 'These cookies allow the website to remember choices you make and provide enhanced, more personal features.'
                        ]
                    ]
                ],
                [
                    'title' => 'Managing Cookies',
                    'content' => 'You can control and/or delete cookies as you wish. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. However, if you do this, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.'
                ],
                [
                    'title' => 'Contact Us',
                    'content' => 'If you have any questions about our use of cookies, please contact us at cookies@gombess.edu.ug'
                ]
            ]
        ];
    }

    /**
     * Get disclaimer content
     */
    public function getDisclaimerContent()
    {
        return [
            'title' => 'Disclaimer',
            'last_updated' => '2024-01-15',
            'sections' => [
                [
                    'title' => 'General Information',
                    'content' => 'The information on this website is provided on an "as is" basis. To the fullest extent permitted by law, Gombe Secondary School excludes all representations, warranties, obligations, and liabilities arising out of or in connection with this website and its contents.'
                ],
                [
                    'title' => 'Accuracy of Information',
                    'content' => 'While we strive to ensure that the information on this website is accurate and up-to-date, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability of the website or the information contained on the website.'
                ],
                [
                    'title' => 'External Links',
                    'content' => 'This website may contain links to external websites that are not provided or maintained by or in any way affiliated with Gombe Secondary School. We do not guarantee the accuracy, relevance, timeliness, or completeness of any information on these external websites.'
                ],
                [
                    'title' => 'Technical Issues',
                    'content' => 'We do not warrant that the website will be constantly available, or available at all, or that the website will be free of viruses or other harmful components. We will not be liable for any loss or damage that may arise from the use of this website.'
                ],
                [
                    'title' => 'Educational Purpose',
                    'content' => 'This system is designed for educational and administrative purposes within Gombe Secondary School. The data and information contained within should be used solely for these intended purposes.'
                ]
            ]
        ];
    }

    /**
     * Get about us content
     */
    public function getAboutUsContent()
    {
        return [
            'title' => 'About Gombe Secondary School Hub',
            'sections' => [
                [
                    'title' => 'Our Mission',
                    'content' => 'To provide a comprehensive, secure, and user-friendly school management system that enhances educational administration and supports the academic success of students and staff at Gombe Secondary School.'
                ],
                [
                    'title' => 'About the System',
                    'content' => 'Gombe SS Hub is a state-of-the-art school management system designed specifically for educational institutions. Our platform provides comprehensive tools for student and staff management, data collection, reporting, and analytics.'
                ],
                [
                    'title' => 'Key Features',
                    'content' => 'Our system offers a wide range of features designed to streamline school administration:',
                    'list' => [
                        'Student and staff data management',
                        'Comprehensive reporting and analytics',
                        'Advanced search and filtering capabilities',
                        'Data export in multiple formats (Excel, PDF, CSV)',
                        'Interactive maps showing geographical distribution',
                        'Multi-language support',
                        'Dark/light theme options',
                        'Mobile-responsive design',
                        'Accessibility features',
                        'Secure data handling and backup systems'
                    ]
                ],
                [
                    'title' => 'Our Commitment',
                    'content' => 'We are committed to:',
                    'list' => [
                        'Protecting the privacy and security of all user data',
                        'Providing reliable and efficient system performance',
                        'Offering comprehensive support and training',
                        'Continuously improving our features and functionality',
                        'Ensuring accessibility for all users',
                        'Maintaining the highest standards of data integrity'
                    ]
                ],
                [
                    'title' => 'Technology',
                    'content' => 'Built using modern web technologies including Laravel PHP framework, our system ensures scalability, security, and performance. We employ industry-standard security measures including data encryption, secure authentication, and regular backups.'
                ],
                [
                    'title' => 'Support',
                    'content' => 'Our dedicated support team is available to assist with any questions or issues. We provide multiple channels of support including email, phone, WhatsApp, and an integrated help system with chatbot assistance.'
                ],
                [
                    'title' => 'Contact Information',
                    'content' => 'For more information about Gombe SS Hub, please contact us:',
                    'contact' => [
                        'school_name' => 'Gombe Secondary School',
                        'address' => 'Gombe, Uganda',
                        'email' => 'info@gombess.edu.ug',
                        'phone' => '+256-700-000-001',
                        'website' => 'https://gombess.edu.ug',
                        'support_email' => 'support@gombess.edu.ug',
                        'emergency_contact' => '+256-700-000-002'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get legal document as JSON (API endpoint)
     */
    public function getLegalDocument(Request $request, $type)
    {
        $validTypes = ['privacy-policy', 'terms-of-service', 'cookies-policy', 'disclaimer', 'about-us'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid document type.'
            ], 404);
        }

        $methodName = 'get' . str_replace('-', '', ucwords($type, '-')) . 'Content';
        
        if (method_exists($this, $methodName)) {
            $content = $this->$methodName();
            return response()->json([
                'success' => true,
                'document' => $content,
                'type' => $type
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Document not found.'
        ], 404);
    }

    /**
     * Accept terms and conditions
     */
    public function acceptTerms(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401);
        }

        $request->validate([
            'terms_version' => 'required|string',
            'privacy_version' => 'required|string'
        ]);

        // In a real application, you would save this to the database
        // UserLegalAcceptance::updateOrCreate([
        //     'user_id' => $user->id
        // ], [
        //     'terms_version' => $request->terms_version,
        //     'privacy_version' => $request->privacy_version,
        //     'accepted_at' => now(),
        //     'ip_address' => $request->ip()
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Terms and conditions accepted successfully.',
            'accepted_at' => now()->toISOString()
        ]);
    }

    /**
     * Get user's legal acceptance status
     */
    public function getLegalAcceptanceStatus()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'accepted' => false,
                'message' => 'Authentication required.'
            ]);
        }

        // In a real application, check database for user's acceptance
        // $acceptance = UserLegalAcceptance::where('user_id', $user->id)->first();

        return response()->json([
            'accepted' => true, // Mock data
            'terms_version' => '2024-01-15',
            'privacy_version' => '2024-01-15',
            'accepted_at' => '2024-01-15T10:30:00Z',
            'requires_update' => false
        ]);
    }
}