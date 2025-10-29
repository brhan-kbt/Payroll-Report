<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
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
        Route::get('sms', [SMSController::class, 'index'])->name('sms.index');
        Route::post('/send-sms', [SmsController::class, 'sendSms'])->name('sms.send');
        Route::get('/search-employees', [SmsController::class, 'searchEmployees'])->name('sms.search-employees');
        Route::post('/afro/test-sms', [SMSController::class, 'testSms'])->name('afro.test-sms');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
        Route::PUT('/settings/update/afro', [SettingController::class, 'updateAfro'])->name('settings.update.afro');
    });

            Route::resource('settings', SettingController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
