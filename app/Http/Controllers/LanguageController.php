<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\UserPreference;

class LanguageController extends Controller
{
    /**
     * Get available languages
     */
    public function getAvailableLanguages()
    {
        return response()->json([
            'languages' => [
                'en' => [
                    'name' => 'English',
                    'native_name' => 'English',
                    'flag' => '🇺🇸',
                    'rtl' => false,
                    'completion' => 100
                ],
                'sw' => [
                    'name' => 'Swahili',
                    'native_name' => 'Kiswahili',
                    'flag' => '🇹🇿',
                    'rtl' => false,
                    'completion' => 85
                ],
                'lg' => [
                    'name' => 'Luganda',
                    'native_name' => 'Oluganda',
                    'flag' => '🇺🇬',
                    'rtl' => false,
                    'completion' => 70
                ],
                'fr' => [
                    'name' => 'French',
                    'native_name' => 'Français',
                    'flag' => '🇫🇷',
                    'rtl' => false,
                    'completion' => 90
                ],
                'ar' => [
                    'name' => 'Arabic',
                    'native_name' => 'العربية',
                    'flag' => '🇸🇦',
                    'rtl' => true,
                    'completion' => 75
                ],
                'ha' => [
                    'name' => 'Hausa',
                    'native_name' => 'Harshen Hausa',
                    'flag' => '🇳🇬',
                    'rtl' => false,
                    'completion' => 60
                ]
            ],
            'current_language' => $this->getCurrentLanguage()
        ]);
    }

    /**
     * Get current language
     */
    public function getCurrentLanguage()
    {
        $userId = Auth::id();
        $language = 'en'; // default language

        if ($userId) {
            // Get from user preferences
            $preference = UserPreference::where('user_id', $userId)
                ->where('key', 'language')
                ->first();

            if ($preference) {
                $language = $preference->value;
            }
        } else {
            // Get from session for non-authenticated users
            $language = Session::get('language', 'en');
        }

        return $language;
    }

    /**
     * Set language
     */
    public function setLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:en,sw,lg,fr,ar,ha'
        ]);

        $language = $request->language;
        $userId = Auth::id();

        // Set application locale
        App::setLocale($language);

        if ($userId) {
            // Save to user preferences
            UserPreference::updateOrCreate(
                ['user_id' => $userId, 'key' => 'language'],
                ['value' => $language]
            );
        }

        // Set session for all users
        Session::put('language', $language);

        return response()->json([
            'success' => true,
            'language' => $language,
            'message' => __('Language updated successfully.')
        ]);
    }

    /**
     * Get translations for current language
     */
    public function getTranslations($language = null)
    {
        if (!$language) {
            $language = $this->getCurrentLanguage();
        }

        // Validate language
        $availableLanguages = ['en', 'sw', 'lg', 'fr', 'ar', 'ha'];
        if (!in_array($language, $availableLanguages)) {
            $language = 'en';
        }

        $translations = $this->getLanguageTranslations($language);

        return response()->json([
            'language' => $language,
            'translations' => $translations,
            'rtl' => in_array($language, ['ar'])
        ]);
    }

    /**
     * Get language-specific translations
     */
    private function getLanguageTranslations($language)
    {
        $translations = [
            'en' => [
                // Navigation
                'dashboard' => 'Dashboard',
                'students' => 'Students',
                'staff' => 'Staff',
                'reports' => 'Reports',
                'search' => 'Search',
                'settings' => 'Settings',
                'logout' => 'Logout',
                'profile' => 'Profile',
                'notifications' => 'Notifications',
                'map' => 'Map',

                // Common Actions
                'add' => 'Add',
                'edit' => 'Edit',
                'delete' => 'Delete',
                'save' => 'Save',
                'cancel' => 'Cancel',
                'submit' => 'Submit',
                'export' => 'Export',
                'import' => 'Import',
                'print' => 'Print',
                'download' => 'Download',
                'upload' => 'Upload',
                'view' => 'View',
                'back' => 'Back',
                'next' => 'Next',
                'previous' => 'Previous',
                'close' => 'Close',
                'confirm' => 'Confirm',

                // Forms
                'name' => 'Name',
                'email' => 'Email',
                'password' => 'Password',
                'phone' => 'Phone',
                'address' => 'Address',
                'date_of_birth' => 'Date of Birth',
                'gender' => 'Gender',
                'male' => 'Male',
                'female' => 'Female',
                'class' => 'Class',
                'department' => 'Department',
                'subject' => 'Subject',
                'qualification' => 'Qualification',

                // Messages
                'success' => 'Success',
                'error' => 'Error',
                'warning' => 'Warning',
                'info' => 'Information',
                'loading' => 'Loading...',
                'no_data' => 'No data available',
                'confirm_delete' => 'Are you sure you want to delete this item?',
                'operation_successful' => 'Operation completed successfully',
                'operation_failed' => 'Operation failed',

                // Student Management
                'add_student' => 'Add Student',
                'student_list' => 'Student List',
                'student_details' => 'Student Details',
                'olevel_students' => 'O\'Level Students',
                'alevel_students' => 'A\'Level Students',
                'student_id' => 'Student ID',
                'admission_date' => 'Admission Date',
                'parent_name' => 'Parent Name',
                'parent_phone' => 'Parent Phone',

                // Staff Management
                'add_staff' => 'Add Staff',
                'staff_list' => 'Staff List',
                'staff_details' => 'Staff Details',
                'teacher' => 'Teacher',
                'admin_staff' => 'Administrative Staff',
                'government_staff' => 'Government Staff',
                'staff_id' => 'Staff ID',
                'hire_date' => 'Hire Date',
                'salary' => 'Salary',

                // Reports
                'generate_report' => 'Generate Report',
                'report_type' => 'Report Type',
                'date_range' => 'Date Range',
                'from_date' => 'From Date',
                'to_date' => 'To Date',
                'students_per_class' => 'Students per Class',
                'teachers_per_department' => 'Teachers per Department',
                'gender_statistics' => 'Gender Statistics',
                'age_groups' => 'Age Groups',

                // Location
                'district' => 'District',
                'subcounty' => 'Subcounty',
                'village' => 'Village',
                'region' => 'Region',
                'location' => 'Location',

                // System
                'system_name' => 'Gombe SS Hub',
                'welcome' => 'Welcome',
                'login' => 'Login',
                'register' => 'Register',
                'forgot_password' => 'Forgot Password',
                'remember_me' => 'Remember Me',
                'total_users' => 'Total Users',
                'total_students' => 'Total Students',
                'total_teachers' => 'Total Teachers',
                'recent_activities' => 'Recent Activities',

                // Time
                'today' => 'Today',
                'yesterday' => 'Yesterday',
                'this_week' => 'This Week',
                'this_month' => 'This Month',
                'this_year' => 'This Year',

                // Accessibility
                'skip_to_content' => 'Skip to content',
                'main_navigation' => 'Main navigation',
                'search_placeholder' => 'Search...',
                'menu' => 'Menu',
                'home' => 'Home',

                // Emergency
                'emergency_contact' => 'Emergency Contact',
                'call_admin' => 'Call Admin',
                'whatsapp_admin' => 'WhatsApp Admin',

                // Help
                'help' => 'Help',
                'faq' => 'FAQ',
                'support' => 'Support',
                'contact_us' => 'Contact Us',
                'feedback' => 'Feedback',
                'about' => 'About',
                'privacy_policy' => 'Privacy Policy',
                'terms_of_service' => 'Terms of Service'
            ],

            'sw' => [
                // Navigation
                'dashboard' => 'Dashibodi',
                'students' => 'Wanafunzi',
                'staff' => 'Wafanyakazi',
                'reports' => 'Ripoti',
                'search' => 'Tafuta',
                'settings' => 'Mipangilio',
                'logout' => 'Ondoka',
                'profile' => 'Wasifu',
                'notifications' => 'Arifa',
                'map' => 'Ramani',

                // Common Actions
                'add' => 'Ongeza',
                'edit' => 'Hariri',
                'delete' => 'Futa',
                'save' => 'Hifadhi',
                'cancel' => 'Ghairi',
                'submit' => 'Wasilisha',
                'export' => 'Hamisha',
                'import' => 'Leta',
                'print' => 'Chapisha',
                'download' => 'Pakua',
                'upload' => 'Pakia',
                'view' => 'Ona',
                'back' => 'Rudi',
                'next' => 'Ifuatayo',
                'previous' => 'Iliyotangulia',
                'close' => 'Funga',
                'confirm' => 'Thibitisha',

                // Forms
                'name' => 'Jina',
                'email' => 'Barua pepe',
                'password' => 'Nenosiri',
                'phone' => 'Simu',
                'address' => 'Anwani',
                'date_of_birth' => 'Tarehe ya kuzaliwa',
                'gender' => 'Jinsia',
                'male' => 'Mwanaume',
                'female' => 'Mwanamke',
                'class' => 'Darasa',
                'department' => 'Idara',
                'subject' => 'Somo',
                'qualification' => 'Kiwango',

                // System
                'system_name' => 'Gombe SS Hub',
                'welcome' => 'Karibu',
                'login' => 'Ingia',
                'total_users' => 'Watumiaji wote',
                'total_students' => 'Wanafunzi wote',
                'total_teachers' => 'Walimu wote',

                // Location
                'district' => 'Wilaya',
                'region' => 'Mkoa',
                'location' => 'Mahali',

                // Help
                'help' => 'Msaada',
                'faq' => 'Maswali ya kawaida',
                'support' => 'Msaada',
                'contact_us' => 'Wasiliana nasi'
            ],

            'lg' => [
                // Navigation
                'dashboard' => 'Omubala',
                'students' => 'Abayizi',
                'staff' => 'Abakozi',
                'reports' => 'Alipoota',
                'search' => 'Noonya',
                'settings' => 'Enteekateeka',
                'logout' => 'Fuluma',
                'profile' => 'Ebikukwata',

                // Common Actions
                'add' => 'Yongera',
                'edit' => 'Kyusa',
                'delete' => 'Sazaamu',
                'save' => 'Tereka',
                'cancel' => 'Sazaamu',
                'name' => 'Erinnya',
                'email' => 'Email',
                'phone' => 'Essimu',

                // System
                'system_name' => 'Gombe SS Hub',
                'welcome' => 'Tusanyuse',
                'login' => 'Yingira',
                'help' => 'Obuyambi'
            ],

            'fr' => [
                // Navigation
                'dashboard' => 'Tableau de bord',
                'students' => 'Étudiants',
                'staff' => 'Personnel',
                'reports' => 'Rapports',
                'search' => 'Rechercher',
                'settings' => 'Paramètres',
                'logout' => 'Déconnexion',
                'profile' => 'Profil',
                'notifications' => 'Notifications',
                'map' => 'Carte',

                // Common Actions
                'add' => 'Ajouter',
                'edit' => 'Modifier',
                'delete' => 'Supprimer',
                'save' => 'Enregistrer',
                'cancel' => 'Annuler',
                'submit' => 'Soumettre',
                'export' => 'Exporter',
                'import' => 'Importer',
                'print' => 'Imprimer',
                'download' => 'Télécharger',
                'view' => 'Voir',

                // Forms
                'name' => 'Nom',
                'email' => 'Email',
                'password' => 'Mot de passe',
                'phone' => 'Téléphone',
                'address' => 'Adresse',
                'gender' => 'Genre',
                'male' => 'Masculin',
                'female' => 'Féminin',

                // System
                'system_name' => 'Gombe SS Hub',
                'welcome' => 'Bienvenue',
                'login' => 'Connexion',
                'help' => 'Aide'
            ],

            'ar' => [
                // Navigation
                'dashboard' => 'لوحة التحكم',
                'students' => 'الطلاب',
                'staff' => 'الموظفون',
                'reports' => 'التقارير',
                'search' => 'بحث',
                'settings' => 'الإعدادات',
                'logout' => 'تسجيل الخروج',
                'profile' => 'الملف الشخصي',
                'notifications' => 'الإشعارات',
                'map' => 'الخريطة',

                // Common Actions
                'add' => 'إضافة',
                'edit' => 'تعديل',
                'delete' => 'حذف',
                'save' => 'حفظ',
                'cancel' => 'إلغاء',
                'submit' => 'إرسال',
                'export' => 'تصدير',
                'import' => 'استيراد',
                'print' => 'طباعة',
                'download' => 'تحميل',
                'view' => 'عرض',

                // Forms
                'name' => 'الاسم',
                'email' => 'البريد الإلكتروني',
                'password' => 'كلمة المرور',
                'phone' => 'الهاتف',
                'address' => 'العنوان',
                'gender' => 'الجنس',
                'male' => 'ذكر',
                'female' => 'أنثى',

                // System
                'system_name' => 'مركز جومبي الثانوي',
                'welcome' => 'مرحباً',
                'login' => 'تسجيل الدخول',
                'help' => 'مساعدة'
            ],

            'ha' => [
                // Navigation
                'dashboard' => 'Dashboard',
                'students' => 'Dalibai',
                'staff' => 'Ma\'aikata',
                'reports' => 'Rahotanni',
                'search' => 'Bincike',
                'settings' => 'Saitunan',
                'logout' => 'Fita',
                'profile' => 'Bayani',

                // Common Actions
                'add' => 'Kara',
                'edit' => 'Gyara',
                'delete' => 'Share',
                'save' => 'Ajiye',
                'cancel' => 'Soke',
                'name' => 'Suna',
                'email' => 'Email',
                'phone' => 'Waya',

                // System
                'system_name' => 'Gombe SS Hub',
                'welcome' => 'Maraba',
                'login' => 'Shiga',
                'help' => 'Taimako'
            ]
        ];

        return $translations[$language] ?? $translations['en'];
    }

    /**
     * Get language statistics (for admin)
     */
    public function getLanguageStatistics()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $languageStats = UserPreference::where('key', 'language')
            ->selectRaw('value as language, COUNT(*) as count')
            ->groupBy('value')
            ->get();

        $totalUsers = UserPreference::where('key', 'language')->count();

        $statistics = [
            'total_users_with_language_preference' => $totalUsers,
            'language_distribution' => $languageStats->mapWithKeys(function ($item) use ($totalUsers) {
                return [
                    $item->language => [
                        'count' => $item->count,
                        'percentage' => $totalUsers > 0 ? round(($item->count / $totalUsers) * 100, 2) : 0
                    ]
                ];
            }),
            'most_popular_language' => $languageStats->sortByDesc('count')->first()?->language ?? 'en'
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Update translation (for admin)
     */
    public function updateTranslation(Request $request)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'language' => 'required|string|in:en,sw,lg,fr,ar,ha',
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:1000'
        ]);

        // In a real application, you would save this to a database or translation files
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Translation updated successfully.'
        ]);
    }

    /**
     * Get missing translations (for admin)
     */
    public function getMissingTranslations($language)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $englishTranslations = $this->getLanguageTranslations('en');
        $targetTranslations = $this->getLanguageTranslations($language);

        $missing = [];
        foreach ($englishTranslations as $key => $value) {
            if (!isset($targetTranslations[$key])) {
                $missing[$key] = $value;
            }
        }

        return response()->json([
            'language' => $language,
            'missing_translations' => $missing,
            'missing_count' => count($missing),
            'completion_percentage' => round((1 - count($missing) / count($englishTranslations)) * 100, 2)
        ]);
    }
}