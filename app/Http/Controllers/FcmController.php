<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceAccountUploadRequest;
use App\Jobs\CleanupFailedTokensJob;
use App\Jobs\RemoveFailedTokensJob;
use App\Models\Category;
use App\Models\FcmToken;
use App\Models\Post;
use App\Services\FcmService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FcmController extends Controller
{
    protected $fcmService;
    protected $notificationService;

    public function __construct(FcmService $fcmService, NotificationService $notificationService)
    {
        $this->fcmService = $fcmService;
        $this->notificationService = $notificationService;
    }

    /**
     * Register FCM token
     */
    public function registerToken(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
            'platform' => 'nullable|string|in:android,ios,web',
            'app_version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // prevToken
            $prevToken = FcmToken::where('token', $request->prevToken)->first();

            if ($prevToken) {
                $prevToken->delete();
            }
            // Check if token already exists
            $existingToken = FcmToken::where('token', $request->token)->first();

            if ($existingToken) {
                // Update existing token
                $existingToken->update([
                    'device_id' => $request->device_id,
                    'platform' => $request->platform,
                    'app_version' => $request->app_version,
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Token updated successfully',
                    'data' => $existingToken
                ]);
            } else {
                // Create new token
                $fcmToken = FcmToken::create([
                    'token' => $request->token,
                    'device_id' => $request->device_id,
                    'platform' => $request->platform,
                    'app_version' => $request->app_version,
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Token registered successfully',
                    'data' => $fcmToken
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-register FCM token on app open
     */
    public function autoRegisterToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
            'platform' => 'nullable|string|in:android,ios,web',
            'app_version' => 'nullable|string|max:50',
            'device_info' => 'nullable|array',
            'app_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Deactivate old tokens for the same device
            if ($request->device_id) {
                FcmToken::where('device_id', $request->device_id)
                    ->where('token', '!=', $request->token)
                    ->update(['is_active' => false]);
            }

            // Register or update the current token
            $fcmToken = FcmToken::updateOrCreate(
                ['token' => $request->token],
                [
                    'device_id' => $request->device_id,
                    'platform' => $request->platform,
                    'app_version' => $request->app_version,
                    'is_active' => true,
                    'last_used_at' => now(),
                ]
            );

            // Log the registration for analytics
            Log::info('FCM Token Auto-Registered', [
                'token_id' => $fcmToken->id,
                'device_id' => $request->device_id,
                'platform' => $request->platform,
                'app_version' => $request->app_version,
                'device_info' => $request->device_info,
                'app_info' => $request->app_info,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token auto-registered successfully',
                'token_id' => $fcmToken->id,
                'is_new_token' => $fcmToken->wasRecentlyCreated,
                'total_active_tokens' => FcmToken::where('is_active', true)->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('FCM Token Auto-Registration Failed', [
                'error' => $e->getMessage(),
                'device_id' => $request->device_id,
                'platform' => $request->platform,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to auto-register token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unregister FCM token
     */
    public function unregisterToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fcmToken = FcmToken::where('token', $request->token)->first();

            if (!$fcmToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found'
                ], 404);
            }

            $fcmToken->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Token unregistered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unregister token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to all users (Admin only)
     */
    public function sendNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array',
            'platform' => 'nullable|string|in:android,ios,web',
            'image' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notification = [
                'title' => $request->title,
                'body' => $request->body,
            ];

            if ($request->image) {
                $notification['image'] = $request->image;
            }

            $data = $request->data ?? [];

            // Use NotificationService to dispatch job
            $result = $this->notificationService->sendToAll($notification, $data, $request->platform);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to specific platform (Admin only)
     */
    public function sendToPlatform(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|in:android,ios,web',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'nullable|array',
            'image' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notification = [
                'title' => $request->title,
                'body' => $request->body,
            ];

            if ($request->image) {
                $notification['image'] = $request->image;
            }

            $data = $request->data ?? [];

            // Use NotificationService to dispatch job
            $result = $this->notificationService->sendToPlatform($request->platform, $notification, $data);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FCM statistics (Admin only)
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = $this->notificationService->getStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all FCM tokens (Admin only)
     */
    public function getTokens(Request $request): JsonResponse
    {
        try {
            $query = FcmToken::query();

            if ($request->has('platform')) {
                $query->platform($request->platform);
            }

            if ($request->has('active')) {
                $query->where('is_active', $request->boolean('active'));
            }

            $tokens = $query->orderBy('created_at', 'desc')->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $tokens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get tokens: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show FCM management dashboard
     */
    public function index()
    {
        return view('fcm.index');
    }

    /**
     * Show FCM send form
     */
    public function sendForm()
    {

         $posts = Post::with('user')->paginate(50);
        $categories = Category::all();
        return view('fcm.send', compact('posts', 'categories'));
    }

    /**
     * Toggle FCM token status
     */
    public function toggleToken(Request $request, $tokenId)
    {
        try {
            $fcmToken = FcmToken::findOrFail($tokenId);
            $fcmToken->update(['is_active' => !$fcmToken->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Token status updated successfully',
                'is_active' => $fcmToken->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show notification history
     */
    public function history()
    {
        return view('fcm.history');
    }

    /**
     * Show service account management
     */
    public function serviceAccount()
    {
        return view('fcm.service-account');
    }

    /**
     * Get service account status
     */
    public function serviceAccountStatus()
    {
        try {
            $serviceAccountPath = config('services.firebase.service_account_path');

            if (!file_exists($serviceAccountPath)) {
                return response()->json([
                    'exists' => false,
                    'file_path' => $serviceAccountPath
                ]);
            }

            $content = file_get_contents($serviceAccountPath);
            $serviceAccount = json_decode($content, true);

            if (!$serviceAccount) {
                return response()->json([
                    'exists' => false,
                    'file_path' => $serviceAccountPath,
                    'error' => 'Invalid JSON format'
                ]);
            }

            return response()->json([
                'exists' => true,
                'file_path' => $serviceAccountPath,
                'project_id' => $serviceAccount['project_id'] ?? 'N/A',
                'client_email' => $serviceAccount['client_email'] ?? 'N/A',
                'last_modified' => date('Y-m-d H:i:s', filemtime($serviceAccountPath))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload service account file
     */
    public function uploadServiceAccount(ServiceAccountUploadRequest $request)
    {
        try {
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->file('service_account_file');

            // Get service account data (already validated by request class)
            $content = file_get_contents($file->getPathname());
            $serviceAccount = json_decode($content, true);

            // Save file to configured path
            $serviceAccountPath = config('services.firebase.service_account_path');
            $directory = dirname($serviceAccountPath);

            // Log the paths for debugging
            Log::info('Firebase Service Account Upload', [
                'target_path' => $serviceAccountPath,
                'directory' => $directory,
                'file_exists_before' => file_exists($serviceAccountPath)
            ]);

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                Log::info('Created directory: ' . $directory);
            }

            // Remove existing file if it exists
            if (file_exists($serviceAccountPath)) {
                unlink($serviceAccountPath);
                Log::info('Removed existing file: ' . $serviceAccountPath);
            }

            // Move uploaded file to target location
            $result = $file->move($directory, basename($serviceAccountPath));

            Log::info('File moved successfully', [
                'result' => $result,
                'file_exists_after' => file_exists($serviceAccountPath)
            ]);

            // Set proper permissions (only on Unix-like systems)
            if (function_exists('chmod')) {
                chmod($serviceAccountPath, 0600);
            }

            return response()->json([
                'success' => true,
                'message' => 'Service account file uploaded successfully',
                'project_id' => $serviceAccount['project_id'],
                'client_email' => $serviceAccount['client_email'],
                'file_path' => $serviceAccountPath,
                'debug' => [
                    'target_path' => $serviceAccountPath,
                    'file_exists' => file_exists($serviceAccountPath),
                    'file_size' => file_exists($serviceAccountPath) ? filesize($serviceAccountPath) : 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Firebase Service Account Upload Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get service account information
     */
    public function serviceAccountInfo()
    {
        try {
            $serviceAccountPath = config('services.firebase.service_account_path');

            $info = [
                'configured_path' => $serviceAccountPath,
                'file_exists' => file_exists($serviceAccountPath),
                'directory_exists' => is_dir(dirname($serviceAccountPath)),
                'directory_writable' => is_writable(dirname($serviceAccountPath)),
                'file_readable' => file_exists($serviceAccountPath) ? is_readable($serviceAccountPath) : false,
                'file_size' => file_exists($serviceAccountPath) ? filesize($serviceAccountPath) : 0,
                'last_modified' => file_exists($serviceAccountPath) ? date('Y-m-d H:i:s', filemtime($serviceAccountPath)) : null,
            ];

            if (file_exists($serviceAccountPath)) {
                $content = file_get_contents($serviceAccountPath);
                $serviceAccount = json_decode($content, true);

                if ($serviceAccount) {
                    $info['json_valid'] = true;
                    $info['project_id'] = $serviceAccount['project_id'] ?? 'N/A';
                    $info['client_email'] = $serviceAccount['client_email'] ?? 'N/A';
                } else {
                    $info['json_valid'] = false;
                    $info['json_error'] = json_last_error_msg();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Service account information retrieved',
                'info' => $info
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get service account info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test service account functionality
     */
    public function testServiceAccount()
    {
        try {
            $serviceAccountPath = config('services.firebase.service_account_path');

            $info = [
                'configured_path' => $serviceAccountPath,
                'file_exists' => file_exists($serviceAccountPath),
                'directory_exists' => is_dir(dirname($serviceAccountPath)),
                'directory_writable' => is_writable(dirname($serviceAccountPath)),
                'file_readable' => file_exists($serviceAccountPath) ? is_readable($serviceAccountPath) : false,
                'file_size' => file_exists($serviceAccountPath) ? filesize($serviceAccountPath) : 0,
                'last_modified' => file_exists($serviceAccountPath) ? date('Y-m-d H:i:s', filemtime($serviceAccountPath)) : null,
            ];

            if (file_exists($serviceAccountPath)) {
                $content = file_get_contents($serviceAccountPath);
                $serviceAccount = json_decode($content, true);

                if ($serviceAccount) {
                    $info['json_valid'] = true;
                    $info['project_id'] = $serviceAccount['project_id'] ?? 'N/A';
                    $info['client_email'] = $serviceAccount['client_email'] ?? 'N/A';
                } else {
                    $info['json_valid'] = false;
                    $info['json_error'] = json_last_error_msg();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Service account test completed',
                'info' => $info
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send test notification
     */
    public function sendTestNotification(Request $request): JsonResponse
    {
        try {
            $token = $request->input('token');
            $result = $this->notificationService->sendTestNotification($token);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up failed/invalid tokens
     */
    public function cleanupFailedTokens(Request $request): JsonResponse
    {
        try {
            $batchSize = $request->input('batch_size', 50);
            $maxAge = $request->input('max_age_days', 30);

            // Dispatch cleanup job
            CleanupFailedTokensJob::dispatch($batchSize, $maxAge);

            Log::info('FCM token cleanup job dispatched', [
                'batch_size' => $batchSize,
                'max_age_days' => $maxAge
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token cleanup job dispatched successfully',
                'batch_size' => $batchSize,
                'max_age_days' => $maxAge
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to dispatch token cleanup job', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to dispatch cleanup job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove old failed tokens permanently
     */
    public function removeFailedTokens(Request $request): JsonResponse
    {
        try {
            $failureThreshold = $request->input('failure_threshold', 3);
            $maxAge = $request->input('max_age_days', 7);

            // Dispatch removal job
            RemoveFailedTokensJob::dispatch($failureThreshold, $maxAge);

            Log::info('Failed token removal job dispatched', [
                'failure_threshold' => $failureThreshold,
                'max_age_days' => $maxAge
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Failed token removal job dispatched successfully',
                'failure_threshold' => $failureThreshold,
                'max_age_days' => $maxAge
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to dispatch token removal job', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to dispatch removal job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get token statistics including failed tokens
     */
    public function getTokenStats(): JsonResponse
    {
        try {
            $stats = [
                'total_tokens' => FcmToken::count(),
                'active_tokens' => FcmToken::where('is_active', true)->count(),
                'inactive_tokens' => FcmToken::where('is_active', false)->count(),
                'platforms' => FcmToken::where('is_active', true)
                    ->selectRaw('platform, COUNT(*) as count')
                    ->groupBy('platform')
                    ->get()
                    ->pluck('count', 'platform'),
                'old_inactive_tokens' => FcmToken::where('is_active', false)
                    ->where('updated_at', '<', now()->subDays(7))
                    ->count(),
                'tokens_by_age' => [
                    'last_24h' => FcmToken::where('created_at', '>=', now()->subDay())->count(),
                    'last_7d' => FcmToken::where('created_at', '>=', now()->subDays(7))->count(),
                    'last_30d' => FcmToken::where('created_at', '>=', now()->subDays(30))->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get token statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manually deactivate a specific token
     */
    public function deactivateToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fcmToken = FcmToken::where('token', $request->token)->first();

            if (!$fcmToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found'
                ], 404);
            }

            $fcmToken->update(['is_active' => false]);

            Log::info('Token manually deactivated', [
                'token_id' => $fcmToken->id,
                'platform' => $fcmToken->platform
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token deactivated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate token: ' . $e->getMessage()
            ], 500);
        }
    }
}
