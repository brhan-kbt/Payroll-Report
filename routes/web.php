<?php

use App\Http\Controllers\Admin\AppConfigController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/composer', function () {
    Artisan::call('composer install');
});

Route::get('/privacy-policy', [SettingController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/theme-test', function () {
    return view('theme-test');
})
    ->middleware(['auth', 'verified'])
    ->name('theme-test');

Route::middleware('auth')->group(function () {
    Route::middleware('isAdmin')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('payrolls', PayrollController::class);
        Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
        // afro/test-sms
        Route::get('sms', [SMSController::class, 'index'])->name('sms.index');
        Route::post('/send-sms', [SmsController::class, 'sendSms'])->name('sms.send');
        Route::get('/search-employees', [SmsController::class, 'searchEmployees'])->name('sms.search-employees');
        Route::post('/afro/test-sms', [SMSController::class, 'testSms'])->name('afro.test-sms');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
        // settings.update.afro
        Route::PUT('/settings/update/afro', [SettingController::class, 'updateAfro'])->name('settings.update.afro');

        Route::resource('posts', PostController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('settings', SettingController::class);
        Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');

        // App Config Management
        // Route::prefix('admin')
        //     ->name('admin.')
        //     ->group(function () {
        //         Route::resource('app-config', AppConfigController::class);
        //         Route::get('app-config/version-management', [AppConfigController::class, 'versionManagement'])->name('app-config.version-management');
        //         Route::post('app-config/update-version-management', [AppConfigController::class, 'updateVersionManagement'])->name('app-config.update-version-management');
        //         Route::get('app-config/maintenance-mode', [AppConfigController::class, 'maintenanceMode'])->name('app-config.maintenance-mode');
        //         Route::post('app-config/update-maintenance-mode', [AppConfigController::class, 'updateMaintenanceMode'])->name('app-config.update-maintenance-mode');
        //         Route::post('app-config/bulk-update', [AppConfigController::class, 'bulkUpdate'])->name('app-config.bulk-update');
        //     });

        // FCM Management
        // Route::prefix('fcm')
        //     ->name('fcm.')
        //     ->group(function () {
        //         Route::get('/', [FcmController::class, 'index'])->name('index');
        //         Route::get('/send', [FcmController::class, 'sendForm'])->name('send');
        //         Route::get('/history', [FcmController::class, 'history'])->name('history');
        //         Route::get('/service-account', [FcmController::class, 'serviceAccount'])->name('service-account');
        //         Route::get('/service-account/status', [FcmController::class, 'serviceAccountStatus'])->name('service-account.status');
        //         Route::post('/service-account/upload', [FcmController::class, 'uploadServiceAccount'])->name('service-account.upload');
        //         // /fcm/service-account/info
        //         Route::get('/service-account/info', [FcmController::class, 'serviceAccountInfo'])->name('service-account.info');
        //         Route::get('/service-account/test', [FcmController::class, 'testServiceAccount'])->name('service-account.test');
        //         Route::post('/tokens/{token}/toggle', [FcmController::class, 'toggleToken'])->name('tokens.toggle');
        //     });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
