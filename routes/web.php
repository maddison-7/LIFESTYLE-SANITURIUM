<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HealthArticleController as AdminHealthArticleController;
use App\Http\Controllers\Admin\HealthcareStaffController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MedicineController as AdminMedicineController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReceiptController as AdminReceiptController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Portal\AppointmentController as PortalAppointmentController;
use App\Http\Controllers\Portal\Auth\AuthenticatedPatientSessionController;
use App\Http\Controllers\Portal\Auth\RegisteredPatientController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\PaymentController as PortalPaymentController;
use App\Http\Controllers\Portal\ProfileController as PortalProfileController;
use App\Http\Controllers\Portal\ReceiptController as PortalReceiptController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', LocaleController::class)
    ->middleware('throttle:30,1')
    ->name('locale.switch');

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/privacy-policy', PrivacyPolicyController::class)->name('privacy-policy');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/healthcare-team', [TeamController::class, 'index'])->name('team.index');
Route::get('/health-education', [EducationController::class, 'index'])->name('education.index');
Route::get('/health-education/{article}', [EducationController::class, 'show'])->name('education.show');

Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/book-appointment', [AppointmentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('appointments.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login.store');
});

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'active'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('users', UserController::class)
        ->except('show')
        ->middleware('can:manage-users');

    Route::get('/settings', [WebsiteSettingController::class, 'edit'])
        ->middleware('can:manage-settings')
        ->name('settings.edit');
    Route::put('/settings', [WebsiteSettingController::class, 'update'])
        ->middleware('can:manage-settings')
        ->name('settings.update');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::post('/appointments/{appointment}/payments', [AdminPaymentController::class, 'store'])->name('appointments.payments.store');
    Route::post('/appointments/{appointment}/remind', [AdminAppointmentController::class, 'sendReminder'])->name('appointments.remind');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirm'])->name('payments.confirm');
    Route::patch('/payments/{payment}/fail', [AdminPaymentController::class, 'fail'])->name('payments.fail');
    Route::get('/receipts/{payment}', AdminReceiptController::class)->name('receipts.show');

    Route::resource('services', AdminServiceController::class)
        ->except('show')
        ->middleware('can:manage-settings');

    Route::resource('branches', AdminBranchController::class)
        ->except('show')
        ->middleware('can:manage-settings');

    Route::resource('team', HealthcareStaffController::class)
        ->except('show')
        ->parameters(['team' => 'member'])
        ->middleware('can:manage-settings');

    Route::resource('articles', AdminHealthArticleController::class)
        ->except('show')
        ->middleware('can:manage-settings');

    Route::resource('patients', AdminPatientController::class);

    Route::resource('medicines', AdminMedicineController::class)
        ->middleware('can:manage-settings');

    Route::prefix('inventory')->name('inventory.')->middleware('can:manage-settings')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/history', [InventoryController::class, 'history'])->name('history');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
    });

    Route::get('/analytics', AnalyticsController::class)->middleware('can:manage-settings')->name('analytics');

    Route::prefix('reports')->name('reports.')->middleware('can:manage-settings')->group(function () {
        Route::get('/', [ReportController::class, 'appointments'])->name('appointments');
        Route::get('/services', [ReportController::class, 'services'])->name('services');
        Route::get('/branches', [ReportController::class, 'branches'])->name('branches');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/patients', [ReportController::class, 'patientDemographics'])->name('patients');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('markRead');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
    });
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware('guest:patient')->group(function () {
        Route::get('/register', [RegisteredPatientController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredPatientController::class, 'store'])->middleware('throttle:5,1')->name('register.store');
        Route::get('/login', [AuthenticatedPatientSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedPatientSessionController::class, 'store'])->name('login.store');
    });

    Route::post('/logout', [AuthenticatedPatientSessionController::class, 'destroy'])
        ->middleware('auth:patient')
        ->name('logout');

    Route::middleware('auth:patient')->group(function () {
        Route::get('/', PortalDashboardController::class)->name('dashboard');

        Route::get('/appointments', [PortalAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [PortalAppointmentController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{appointment}/payments', [PortalPaymentController::class, 'store'])->name('appointments.payments.store');

        Route::get('/receipts/{payment}', PortalReceiptController::class)->name('receipts.show');

        Route::get('/profile', [PortalProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [PortalProfileController::class, 'update'])->name('profile.update');
    });
});
