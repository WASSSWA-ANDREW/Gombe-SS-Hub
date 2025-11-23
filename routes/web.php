<?php

use App\Http\Controllers\Admin\AcademicsController;
// Import all controllers
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('landing');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile Routes (protected for authenticated admins and super_admins)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
});

// Admin Login (separate from main login)
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Admin Welcome Page (after login)
Route::get('/admin/welcome', [AuthController::class, 'showWelcome'])->name('admin.welcome')->middleware('auth');

// Teacher Login Routes
Route::get('/teacher/login', [TeacherAuthController::class, 'showLoginForm'])->name('teacher.login.form');
Route::post('/teacher/login', [TeacherAuthController::class, 'login'])->name('teacher.login');
Route::post('/teacher/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');

// Teacher Dashboard and Academic Routes (protected by teacher middleware)
Route::middleware(['teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherAuthController::class, 'dashboard'])->name('teacher.dashboard');
});

// Icon Test Page (temporary for debugging)
Route::get('/admin/icon-test', function () {
    return view('admin.icon-test');
})->name('admin.icon-test')->middleware('auth');

// Legal/Public Routes (accessible without authentication)
Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-service', [LegalController::class, 'termsOfService'])->name('terms.service');
Route::get('/cookies-policy', [LegalController::class, 'cookiesPolicy'])->name('cookies.policy');
Route::get('/disclaimer', [LegalController::class, 'disclaimer'])->name('disclaimer');
Route::get('/about-us', [LegalController::class, 'aboutUs'])->name('about.us');
Route::get('/legal/{type}', [LegalController::class, 'getLegalDocument'])->name('legal.document');
Route::post('/legal/accept-terms', [LegalController::class, 'acceptTerms'])->name('legal.accept');
Route::get('/legal/acceptance-status', [LegalController::class, 'getLegalAcceptanceStatus'])->name('legal.status');

// Public Theme and Language Routes (accessible without authentication)
Route::get('/theme/current', [ThemeController::class, 'getCurrentTheme'])->name('public.theme.current');
Route::post('/theme/set', [ThemeController::class, 'setTheme'])->name('public.theme.set');
Route::get('/language/current', [LanguageController::class, 'getCurrentLanguage'])->name('public.language.current');
Route::post('/language/set', [LanguageController::class, 'setLanguage'])->name('public.language.set');
Route::get('/language/translations/{language?}', [LanguageController::class, 'getTranslations'])->name('public.language.translations');

// Staff Management Routes
Route::prefix('admin/staff')->name('admin.staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/create', [StaffController::class, 'create'])->name('create');
    Route::post('/', [StaffController::class, 'store'])->name('store');
    Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
    Route::put('/{staff}', [StaffController::class, 'update'])->name('update');
    Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
    Route::get('/export/excel', [StaffController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [StaffController::class, 'exportPdf'])->name('export.pdf');
    Route::post('/export/selected', [StaffController::class, 'exportSelected'])->name('export.selected');
    Route::get('/{staff}/export-form-pdf', [StaffController::class, 'exportFormPdf'])->name('export.form_pdf');
    Route::get('/{staff}/view-pdf', [StaffController::class, 'viewStaffPdf'])->name('view.pdf');
    Route::get('/{staff}/view', [StaffController::class, 'show'])->name('show');
    Route::post('/delete-selected', [StaffController::class, 'deleteSelected'])->name('delete.selected');
    Route::get('/import', [StaffController::class, 'importForm'])->name('import');
    Route::post('/import', [StaffController::class, 'import'])->name('import.store');
    Route::get('/search', [StaffController::class, 'search'])->name('search');

    // Government Staff Routes
    Route::get('/govt', [StaffController::class, 'indexGovt'])->name('index_govt');
    Route::get('/govt/create', [StaffController::class, 'createGovt'])->name('create_govt');
    Route::post('/govt', [StaffController::class, 'storeGovt'])->name('store_govt');
    Route::get('/govt/{staff}/edit', [StaffController::class, 'editGovt'])->name('edit_govt');
    Route::put('/govt/{staff}', [StaffController::class, 'updateGovt'])->name('update_govt');
    Route::delete('/govt/{staff}', [StaffController::class, 'destroyGovt'])->name('destroy_govt');
    Route::get('/govt/export/excel', [StaffController::class, 'exportExcelGovt'])->name('export.excel_govt');
    Route::get('/govt/export/pdf', [StaffController::class, 'exportPdfGovt'])->name('export.pdf_govt');
    Route::get('/govt/{staff}/view-pdf', [StaffController::class, 'viewStaffGovtPdf'])->name('view.pdf_govt');
    Route::get('/govt/{staff}/view', [StaffController::class, 'showGovt'])->name('show_govt');
    Route::post('/govt/delete-selected', [StaffController::class, 'deleteSelectedGovt'])->name('delete.selected_govt');
    Route::get('/govt/import', [StaffController::class, 'importGovtForm'])->name('import_govt');
    Route::post('/govt/import', [StaffController::class, 'importGovt'])->name('import.store_govt');
    Route::get('/govt/search', [StaffController::class, 'searchGovt'])->name('govt.search');
    // Add any specific export routes for government staff if needed
});

// Student Management Routes
Route::prefix('admin/students')->name('admin.students.')->group(function () {
    // O'Level Students
    Route::get('/olevel/create', [StudentController::class, 'createOlevel'])->name('olevel.create');
    Route::post('/olevel', [StudentController::class, 'storeOlevel'])->name('olevel.store');
    // O'Level Students Routes
    Route::get('/olevel', [StudentController::class, 'indexOlevel'])->name('olevel.index');
    Route::get('/olevel/{student}/edit', [StudentController::class, 'editOlevel'])->name('olevel.edit');
    Route::put('/olevel/{student}', [StudentController::class, 'updateOlevel'])->name('olevel.update');
    Route::delete('/olevel/{student}', [StudentController::class, 'destroyOlevel'])->name('olevel.destroy');
    Route::get('/olevel/export/excel', [StudentController::class, 'exportOlevelExcel'])->name('olevel.export.excel');
    Route::get('/olevel/export/pdf', [StudentController::class, 'exportOlevelPdf'])->name('olevel.export.pdf');
    Route::post('/olevel/export/selected', [StudentController::class, 'exportSelectedOlevel'])->name('olevel.export.selected');
    Route::get('/olevel/{student}/view-pdf', [StudentController::class, 'viewOlevelPdf'])->name('olevel.view.pdf');
    Route::get('/olevel/{student}/view', [StudentController::class, 'showOlevel'])->name('olevel.show');
    Route::post('/olevel/delete-selected', [StudentController::class, 'deleteSelectedOlevel'])->name('olevel.delete.selected');
    Route::get('/olevel/import', [StudentController::class, 'importOlevelForm'])->name('olevel.import');
    Route::post('/olevel/import', [StudentController::class, 'importOlevel'])->name('olevel.import.store');
    Route::get('/olevel/search', [StudentController::class, 'searchOlevel'])->name('olevel.search');

    // A'Level Students
    Route::get('/alevel', [StudentController::class, 'indexAlevel'])->name('alevel.index');
    Route::get('/alevel/create', [StudentController::class, 'createAlevel'])->name('alevel.create');
    Route::post('/alevel', [StudentController::class, 'storeAlevel'])->name('alevel.store');
    Route::get('/alevel/{student}/edit', [StudentController::class, 'editAlevel'])->name('alevel.edit');
    Route::put('/alevel/{student}', [StudentController::class, 'updateAlevel'])->name('alevel.update');
    Route::delete('/alevel/{student}', [StudentController::class, 'destroyAlevel'])->name('alevel.destroy');
    Route::get('/alevel/export/excel', [StudentController::class, 'exportAlevelExcel'])->name('alevel.export.excel');
    Route::get('/alevel/export/pdf', [StudentController::class, 'exportAlevelPdf'])->name('alevel.export.pdf');
    Route::post('/alevel/export/selected', [StudentController::class, 'exportSelectedAlevel'])->name('alevel.export.selected');
    Route::get('/alevel/{student}/view-pdf', [StudentController::class, 'viewAlevelPdf'])->name('alevel.view.pdf');
    Route::post('/alevel/delete-selected', [StudentController::class, 'deleteSelectedAlevel'])->name('alevel.delete.selected');
    Route::get('/alevel/{student}/view', [StudentController::class, 'showAlevel'])->name('alevel.show');
    Route::get('/alevel/import', [StudentController::class, 'importAlevelForm'])->name('alevel.import');
    Route::post('/alevel/import', [StudentController::class, 'importAlevel'])->name('alevel.import.store');
    Route::get('/alevel/search', [StudentController::class, 'searchAlevel'])->name('alevel.search');

    // Student Promotion Routes
    Route::prefix('promotion')->name('promotion.')->group(function () {
        Route::get('/', [StudentController::class, 'promotionForm'])->name('form');
        Route::post('/', [StudentController::class, 'promoteStudents'])->name('store');
    });
});

// Alumni Management Routes
Route::prefix('admin/alumni')->name('admin.alumni.')->group(function () {
    Route::get('/', [AlumniController::class, 'index'])->name('index');
    Route::get('/create', [AlumniController::class, 'create'])->name('create');
    Route::post('/', [AlumniController::class, 'store'])->name('store');
    Route::get('/{alumnus}/edit', [AlumniController::class, 'edit'])->name('edit');
    Route::put('/{alumnus}', [AlumniController::class, 'update'])->name('update');
    Route::delete('/{alumnus}', [AlumniController::class, 'destroy'])->name('destroy');
    Route::get('/{alumnus}', [AlumniController::class, 'show'])->name('show');
    Route::get('/export/excel', [AlumniController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [AlumniController::class, 'exportPdf'])->name('export.pdf');
    Route::post('/export/selected', [AlumniController::class, 'exportSelected'])->name('export.selected');
    Route::post('/delete-selected', [AlumniController::class, 'deleteSelected'])->name('delete.selected');
    Route::get('/stats', [AlumniController::class, 'getStats'])->name('stats');
    Route::get('/search', [AlumniController::class, 'search'])->name('search');
});

// Discipline Records Routes
Route::prefix('admin/discipline')->name('admin.discipline.')->group(function () {
    Route::get('/', [DisciplineController::class, 'index'])->name('index');

    // Records routes (for layout navigation)
    Route::prefix('records')->name('records.')->group(function () {
        Route::get('/', [DisciplineController::class, 'disciplineTracks'])->name('index');
        Route::get('/create', [DisciplineController::class, 'createDisciplineTrack'])->name('create');
        Route::post('/', [DisciplineController::class, 'storeDisciplineTrack'])->name('store');
    });

    // Tracks routes
    Route::prefix('tracks')->name('tracks.')->group(function () {
        Route::get('/', [DisciplineController::class, 'disciplineTracks'])->name('index');
        Route::get('/create', [DisciplineController::class, 'createDisciplineTrack'])->name('create');
        Route::post('/', [DisciplineController::class, 'storeDisciplineTrack'])->name('store');
    });

    // Legacy routes (kept for backward compatibility)
    Route::get('/discipline-tracks', [DisciplineController::class, 'disciplineTracks'])->name('discipline-tracks');
    Route::get('/create-discipline-track', [DisciplineController::class, 'createDisciplineTrack'])->name('create-discipline-track');
    Route::post('/store-discipline-track', [DisciplineController::class, 'storeDisciplineTrack'])->name('store-discipline-track');

    Route::get('/student/{studentId}/records', [DisciplineController::class, 'studentRecords'])->name('student-records');
});

// Counselling Tracks routes (at admin level, not nested under discipline)
Route::prefix('admin/counselling')->name('admin.counselling.')->group(function () {
    Route::prefix('tracks')->name('tracks.')->group(function () {
        Route::get('/', [DisciplineController::class, 'counsellingTracks'])->name('index');
        Route::get('/create', [DisciplineController::class, 'createCounsellingTrack'])->name('create');
        Route::post('/', [DisciplineController::class, 'storeCounsellingTrack'])->name('store');
    });

    // Legacy routes (kept for backward compatibility)
    Route::get('/counselling-tracks', [DisciplineController::class, 'counsellingTracks'])->name('counselling-tracks');
    Route::get('/create-counselling-track', [DisciplineController::class, 'createCounsellingTrack'])->name('create-counselling-track');
    Route::post('/store-counselling-track', [DisciplineController::class, 'storeCounsellingTrack'])->name('store-counselling-track');
});

// Protected Admin Routes (require authentication)
Route::middleware(['auth'])->group(function () {

    // Dashboard Routes
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/stats', [DashboardController::class, 'getStats'])->name('admin.dashboard.stats');
    Route::get('/admin/dashboard/charts', [DashboardController::class, 'getChartsData'])->name('admin.dashboard.charts');
    Route::get('/admin/dashboard/recent-activities', [DashboardController::class, 'getRecentActivities'])->name('admin.dashboard.activities');

    // File Upload Routes
    Route::prefix('admin/files')->name('admin.files.')->group(function () {
        Route::get('/', [FileUploadController::class, 'index'])->name('index');
        Route::get('/download/{id}/{type}', [FileUploadController::class, 'download'])->name('download');
        Route::delete('/delete/{id}/{type}', [FileUploadController::class, 'delete'])->name('delete');
    });

    // Academics Management Routes
    Route::prefix('admin/academics')->name('admin.academics.')->group(function () {
        Route::get('/dashboard', [AcademicsController::class, 'dashboard'])->name('dashboard');

        Route::prefix('olevel')->name('olevel.')->group(function () {
            Route::get('/subjects', [AcademicsController::class, 'olevelSubjects'])->name('subjects');
            Route::post('/subjects/general', [AcademicsController::class, 'storeOlevelGeneralSubject'])->name('subjects.general.store');
            Route::put('/subjects/general/{id}', [AcademicsController::class, 'updateOlevelGeneralSubject'])->name('subjects.general.update');
            Route::delete('/subjects/general/{id}', [AcademicsController::class, 'destroyOlevelGeneralSubject'])->name('subjects.general.destroy');
            Route::post('/subjects/optional', [AcademicsController::class, 'storeOlevelOptionalSubject'])->name('subjects.optional.store');
            Route::put('/subjects/optional/{id}', [AcademicsController::class, 'updateOlevelOptionalSubject'])->name('subjects.optional.update');
            Route::delete('/subjects/optional/{id}', [AcademicsController::class, 'destroyOlevelOptionalSubject'])->name('subjects.optional.destroy');
            Route::get('/marks', [AcademicsController::class, 'olevelMarks'])->name('marks');
            Route::get('/performance', [AcademicsController::class, 'olevelPerformance'])->name('performance');
        });

        Route::prefix('alevel')->name('alevel.')->group(function () {
            Route::get('/subjects', [AcademicsController::class, 'alevelSubjects'])->name('subjects');
            Route::post('/subjects/arts', [AcademicsController::class, 'storeAlevelArtsSubject'])->name('subjects.arts.store');
            Route::put('/subjects/arts/{id}', [AcademicsController::class, 'updateAlevelArtsSubject'])->name('subjects.arts.update');
            Route::delete('/subjects/arts/{id}', [AcademicsController::class, 'destroyAlevelArtsSubject'])->name('subjects.arts.destroy');
            Route::post('/subjects/science', [AcademicsController::class, 'storeAlevelScienceSubject'])->name('subjects.science.store');
            Route::put('/subjects/science/{id}', [AcademicsController::class, 'updateAlevelScienceSubject'])->name('subjects.science.update');
            Route::delete('/subjects/science/{id}', [AcademicsController::class, 'destroyAlevelScienceSubject'])->name('subjects.science.destroy');
            Route::post('/subjects/subsidiary', [AcademicsController::class, 'storeAlevelSubsidiarySubject'])->name('subjects.subsidiary.store');
            Route::put('/subjects/subsidiary/{id}', [AcademicsController::class, 'updateAlevelSubsidiarySubject'])->name('subjects.subsidiary.update');
            Route::delete('/subjects/subsidiary/{id}', [AcademicsController::class, 'destroyAlevelSubsidiarySubject'])->name('subjects.subsidiary.destroy');
            Route::get('/marks', [AcademicsController::class, 'alevelMarks'])->name('marks');
            Route::get('/performance', [AcademicsController::class, 'alevelPerformance'])->name('performance');
        });

        Route::get('/student-performance', [AcademicsController::class, 'studentPerformance'])->name('student-performance');
        Route::get('/student-performance/{studentId}/data', [AcademicsController::class, 'getStudentPerformanceData'])->name('student-performance.data');

        Route::get('/teachers', [AcademicsController::class, 'teacherAssignments'])->name('teachers');
        Route::post('/teachers/assign', [AcademicsController::class, 'assignTeacherSubjects'])->name('teachers.assign');
        Route::get('/teachers/{id}/edit', [AcademicsController::class, 'editTeacherSubject'])->name('teachers.edit');
        Route::put('/teachers/{id}', [AcademicsController::class, 'updateTeacherSubject'])->name('teachers.update');
        Route::delete('/teachers/{id}', [AcademicsController::class, 'destroyTeacherSubject'])->name('teachers.destroy');
    });

    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/search', [SearchController::class, 'search'])->name('search.submit');
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestions');
    Route::get('/search/history', [SearchController::class, 'getSearchHistory'])->name('search.history');
    Route::delete('/search/history/{id}', [SearchController::class, 'deleteSearchHistory'])->name('search.history.delete');
    Route::delete('/search/history', [SearchController::class, 'clearSearchHistory'])->name('search.history.clear');
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch'])->name('search.advanced');

    // Reports Routes
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        // Legacy Reports Controller (if needed)
        Route::get('/legacy', [ReportsController::class, 'index'])->name('legacy.index');
        Route::get('/legacy/students-per-class', [ReportsController::class, 'studentsPerClass'])->name('legacy.students.class');
        Route::get('/legacy/teachers-per-department', [ReportsController::class, 'teachersPerDepartment'])->name('legacy.teachers.department');
        Route::get('/legacy/gender-statistics', [ReportsController::class, 'genderStatistics'])->name('legacy.gender.stats');
        Route::get('/legacy/age-groups', [ReportsController::class, 'ageGroups'])->name('legacy.age.groups');
        Route::get('/legacy/districts', [ReportsController::class, 'districtStatistics'])->name('legacy.districts');
        Route::get('/legacy/religions', [ReportsController::class, 'religionStatistics'])->name('legacy.religions');
        Route::get('/legacy/custom', [ReportsController::class, 'customReport'])->name('legacy.custom');
        Route::post('/legacy/generate', [ReportsController::class, 'generateReport'])->name('legacy.generate');
        Route::get('/legacy/export/{type}', [ReportsController::class, 'exportReport'])->name('legacy.export');

        // New Reports Controller
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/student-distribution', [ReportController::class, 'studentDistribution'])->name('student-distribution');
        Route::get('/demographics', [ReportController::class, 'demographics'])->name('demographics');
        Route::get('/staff', [ReportController::class, 'staffReports'])->name('staff');
        Route::get('/academic-performance', [ReportController::class, 'academicPerformance'])->name('academic-performance');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        Route::post('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate-pdf');
        Route::post('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });

    // User Management Routes (Admin only)
    Route::prefix('admin/users')->name('admin.users.')->middleware(['role:admin'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk.action');
        Route::put('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle.status');
        Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset.password');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/create', [NotificationController::class, 'create'])->name('create');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark.all.read');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear.all');
        Route::get('/emergency/create', [NotificationController::class, 'createEmergency'])->name('emergency.create');
        Route::post('/emergency', [NotificationController::class, 'sendEmergency'])->name('emergency.send');
        Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [NotificationController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Export Routes
    Route::prefix('export')->name('export.')->group(function () {
        Route::post('/excel', [ExportController::class, 'exportExcel'])->name('excel');
        Route::post('/pdf', [ExportController::class, 'exportPdf'])->name('pdf');
        Route::post('/csv', [ExportController::class, 'exportCsv'])->name('csv');
        Route::post('/print', [ExportController::class, 'print'])->name('print');
        Route::post('/email', [ExportController::class, 'sendEmail'])->name('email');
        Route::post('/whatsapp', [ExportController::class, 'shareViaWhatsApp'])->name('whatsapp');
        Route::post('/social', [ExportController::class, 'shareViaSocial'])->name('social');
        Route::get('/templates', [ExportController::class, 'getTemplates'])->name('templates');
        Route::post('/bulk', [ExportController::class, 'bulkExport'])->name('bulk');
    });

    // Settings Routes (Admin only)
    Route::prefix('admin/settings')->name('admin.settings.')->middleware(['role:admin'])->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::put('/theme', [SettingsController::class, 'updateTheme'])->name('theme.update');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::put('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
        Route::put('/backup', [SettingsController::class, 'updateBackup'])->name('backup.update');
        Route::put('/email', [SettingsController::class, 'updateEmail'])->name('email.update');
        Route::put('/sms', [SettingsController::class, 'updateSms'])->name('sms.update');
        Route::post('/test-email', [SettingsController::class, 'testEmail'])->name('test.email');
        Route::post('/test-sms', [SettingsController::class, 'testSms'])->name('test.sms');
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear.cache');
        Route::post('/optimize', [SettingsController::class, 'optimizeSystem'])->name('optimize');
        Route::get('/system-info', [SettingsController::class, 'getSystemInfo'])->name('system.info');
        Route::post('/backup', [SettingsController::class, 'createBackup'])->name('backup.create');
    });

    // Backup Management Routes (Admin only)
    Route::prefix('admin/backups')->name('admin.backups.')->middleware(['role:admin'])->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])->name('destroy');
        Route::get('/metadata/{filename}', [BackupController::class, 'metadata'])->name('metadata');
    });

    // Map Routes
    Route::prefix('map')->name('map.')->group(function () {
        Route::get('/', [MapController::class, 'index'])->name('index');
        Route::get('/students-data', [MapController::class, 'getStudentsLocationData'])->name('students.data');
        Route::get('/staff-data', [MapController::class, 'getStaffLocationData'])->name('staff.data');
        Route::get('/combined-data', [MapController::class, 'getCombinedLocationData'])->name('combined.data');
        Route::get('/stats-by-district', [MapController::class, 'getLocationStatsByDistrict'])->name('stats.district');
        Route::get('/heatmap-data', [MapController::class, 'getHeatmapData'])->name('heatmap.data');
        Route::get('/nearby', [MapController::class, 'getNearbyLocations'])->name('nearby');
        Route::get('/config', [MapController::class, 'getMapConfig'])->name('config');
    });

    // Chatbot and FAQ Routes removed

    // Bookmark Routes
    Route::prefix('bookmarks')->name('bookmarks.')->group(function () {
        Route::get('/', [BookmarkController::class, 'index'])->name('index');
        Route::post('/', [BookmarkController::class, 'store'])->name('store');
        Route::put('/{bookmark}', [BookmarkController::class, 'update'])->name('update');
        Route::delete('/{bookmark}', [BookmarkController::class, 'destroy'])->name('destroy');
        Route::get('/category/{category?}', [BookmarkController::class, 'getByCategory'])->name('category');
        Route::get('/categories', [BookmarkController::class, 'getCategories'])->name('categories');
        Route::get('/search', [BookmarkController::class, 'search'])->name('search');
        Route::get('/check', [BookmarkController::class, 'checkBookmark'])->name('check');
        Route::get('/suggestions', [BookmarkController::class, 'getSuggestions'])->name('suggestions');
        Route::get('/export', [BookmarkController::class, 'export'])->name('export');
        Route::post('/import', [BookmarkController::class, 'import'])->name('import');
    });

    // Theme Routes
    Route::prefix('theme')->name('theme.')->group(function () {
        Route::get('/current', [ThemeController::class, 'getCurrentTheme'])->name('current');
        Route::post('/toggle', [ThemeController::class, 'toggleTheme'])->name('toggle');
        Route::post('/set', [ThemeController::class, 'setTheme'])->name('set');
        Route::get('/available', [ThemeController::class, 'getAvailableThemes'])->name('available');
        Route::get('/customization-options', [ThemeController::class, 'getCustomizationOptions'])->name('customization.options');
        Route::post('/customization', [ThemeController::class, 'updateCustomization'])->name('customization.update');
        Route::get('/customizations', [ThemeController::class, 'getCustomizations'])->name('customizations');
        Route::post('/reset', [ThemeController::class, 'resetToDefault'])->name('reset');
        Route::get('/statistics', [ThemeController::class, 'getThemeStatistics'])->name('statistics');
        Route::get('/export', [ThemeController::class, 'exportSettings'])->name('export');
        Route::post('/import', [ThemeController::class, 'importSettings'])->name('import');
    });

    // Language Routes
    Route::prefix('language')->name('language.')->group(function () {
        Route::get('/available', [LanguageController::class, 'getAvailableLanguages'])->name('available');
        Route::get('/current', [LanguageController::class, 'getCurrentLanguage'])->name('current');
        Route::post('/set', [LanguageController::class, 'setLanguage'])->name('set');
        Route::get('/translations/{language?}', [LanguageController::class, 'getTranslations'])->name('translations');
        Route::get('/statistics', [LanguageController::class, 'getLanguageStatistics'])->name('statistics');
        Route::post('/translation/update', [LanguageController::class, 'updateTranslation'])->name('translation.update');
        Route::get('/missing/{language}', [LanguageController::class, 'getMissingTranslations'])->name('missing');
    });

    // Emergency Contact Routes
    Route::prefix('emergency')->name('emergency.')->group(function () {
        Route::get('/contacts', [EmergencyContactController::class, 'getContacts'])->name('contacts');
        Route::post('/call', [EmergencyContactController::class, 'initiateCall'])->name('call');
        Route::post('/whatsapp', [EmergencyContactController::class, 'initiateWhatsApp'])->name('whatsapp');
        Route::post('/email', [EmergencyContactController::class, 'sendEmergencyEmail'])->name('email');
        Route::get('/history', [EmergencyContactController::class, 'getContactHistory'])->name('history');
        Route::get('/statistics', [EmergencyContactController::class, 'getStatistics'])->name('statistics');
        Route::post('/settings', [EmergencyContactController::class, 'updateContactSettings'])->name('settings.update');
        Route::post('/test', [EmergencyContactController::class, 'testSystem'])->name('test');
        Route::get('/widget', [EmergencyContactController::class, 'getWidgetData'])->name('widget');
    });

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::post('/', [FeedbackController::class, 'store'])->name('store');
        Route::get('/categories', [FeedbackController::class, 'getCategories'])->name('categories');
        Route::get('/user', [FeedbackController::class, 'getUserFeedback'])->name('user');
        Route::get('/all', [FeedbackController::class, 'getAllFeedback'])->name('all');
        Route::put('/{feedbackId}/status', [FeedbackController::class, 'updateStatus'])->name('status.update');
        Route::get('/statistics', [FeedbackController::class, 'getStatistics'])->name('statistics');
        Route::get('/export', [FeedbackController::class, 'exportFeedback'])->name('export');
        Route::get('/templates', [FeedbackController::class, 'getTemplates'])->name('templates');
    });

    // Support Routes removed
});

// API Routes
Route::prefix('api/v1')->name('api.')->group(function () {
    // Public API routes
    Route::post('/login', [ApiController::class, 'login'])->name('login');
    Route::get('/app-info', [ApiController::class, 'getAppInfo'])->name('app.info');

    // Protected API routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [ApiController::class, 'logout'])->name('logout');
        Route::get('/profile', [ApiController::class, 'profile'])->name('profile');
        Route::put('/profile', [ApiController::class, 'updateProfile'])->name('profile.update');
        Route::put('/change-password', [ApiController::class, 'changePassword'])->name('password.change');
        Route::get('/dashboard-stats', [ApiController::class, 'dashboardStats'])->name('dashboard.stats');
        Route::get('/search/students', [ApiController::class, 'searchStudents'])->name('search.students');
        Route::get('/search/staff', [ApiController::class, 'searchStaff'])->name('search.staff');
        Route::get('/students', [ApiController::class, 'getStudents'])->name('students.list');
        Route::get('/students/{id}', [ApiController::class, 'getStudent'])->name('students.show');
        Route::get('/staff', [ApiController::class, 'getStaffList'])->name('staff.list');
        Route::get('/staff/{id}', [ApiController::class, 'getStaff'])->name('staff.show');
        Route::get('/system-stats', [ApiController::class, 'getSystemStats'])->name('system.stats');
    });
});
