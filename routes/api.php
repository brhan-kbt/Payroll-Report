<?php

use App\Http\Controllers\AppConfigController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'apiIndex']);
    Route::get('/categories/{category}', [CategoryController::class, 'apiShow']);

    Route::get('/posts/search', [PostController::class, 'apiSearch']);
    Route::get('/posts', [PostController::class, 'apiIndex']);
    Route::get('/posts/{post}', [PostController::class, 'apiShow']);
    Route::post('/posts/{post}/like', [PostController::class, 'apiToggleLike']);
    Route::post('/posts/{post}/view', [PostController::class, 'apiView']);
    Route::get('/settings', [SettingController::class, 'apiIndex']);
    Route::get('/settings/{key}', [SettingController::class, 'apiShow']);

    // App Config API routes
    Route::get('/app-config', [AppConfigController::class, 'apiIndex']);
    Route::get('/app-config/client', [AppConfigController::class, 'apiClientConfig']);
    Route::get('/app-config/{key}', [AppConfigController::class, 'apiShow']);
    Route::post('/app-config/check-version', [AppConfigController::class, 'apiCheckVersion']);

    // FCM Token Management (Public - for app registration)
    Route::post('/fcm/register', [FcmController::class, 'registerToken']);
    Route::post('/fcm/unregister', [FcmController::class, 'unregisterToken']);
});

// Protected API routes (authentication required)
Route::middleware('auth:sanctum')->prefix('v1/admin')->group(function () {
    // Categories API
    Route::apiResource('categories', CategoryController::class);

    // Posts API
    Route::apiResource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'apiToggleLike']);

    // App Config API (Admin only)
    Route::apiResource('app-config', AppConfigController::class);
    Route::post('/app-config/bulk-update', [AppConfigController::class, 'bulkUpdate']);

    // FCM Notification Management (Admin only)
    Route::post('/fcm/send', [FcmController::class, 'sendNotification']);
    Route::post('/fcm/send-to-platform', [FcmController::class, 'sendToPlatform']);
    Route::post('/fcm/test', [FcmController::class, 'sendTestNotification']);
    Route::get('/fcm/stats', [FcmController::class, 'getStats']);
    Route::get('/fcm/token-stats', [FcmController::class, 'getTokenStats']);
    Route::get('/fcm/tokens', [FcmController::class, 'getTokens']);
    Route::post('/fcm/cleanup', [FcmController::class, 'cleanupFailedTokens']);
    Route::post('/fcm/remove-failed', [FcmController::class, 'removeFailedTokens']);
    Route::post('/fcm/deactivate-token', [FcmController::class, 'deactivateToken']);
});
