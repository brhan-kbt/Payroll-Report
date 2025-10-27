<?php

namespace App\Services;

use App\Jobs\SendBulkNotificationJob;
use App\Jobs\SendNewPostNotificationJob;
use App\Jobs\SendNotificationJob;
use App\Models\FcmToken;
use App\Models\Post;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to all active tokens
     */
    public function sendToAll(array $notification, array $data = [], ?string $platform = null): array
    {
        try {
            // Get active tokens
            $query = FcmToken::active();

            if ($platform) {
                $query->platform($platform);
            }

            $tokens = $query->pluck('token')->toArray();

            if (empty($tokens)) {
                return [
                    'success' => false,
                    'message' => 'No active tokens found',
                ];
            }

            // Dispatch job for bulk notification
            SendBulkNotificationJob::dispatch($notification, $data, $platform);

            Log::info('Bulk notification job dispatched', [
                'token_count' => count($tokens),
                'platform' => $platform,
            ]);

            // Log::info('Running queue:work --once');
            // Artisan::call('queue:work --once');
            // Log::info('Finished queue:work --once');

            return [
                'success' => true,
                'message' => 'Notification job dispatched successfully',
                'token_count' => count($tokens),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to dispatch bulk notification', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to dispatch notification: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Send notification to specific tokens
     */
    public function sendToTokens(array $tokens, array $notification, array $data = []): array
    {
        try {
            if (empty($tokens)) {
                return [
                    'success' => false,
                    'message' => 'No tokens provided',
                ];
            }

            // Dispatch job for specific tokens
            SendNotificationJob::dispatch($tokens, $notification, $data);

            Log::info('Notification job dispatched for specific tokens', [
                'token_count' => count($tokens),
            ]);

            // Log::info('Running queue:work --once');
            // Artisan::call('queue:work --once');
            // Log::info('Finished queue:work --once');

            return [
                'success' => true,
                'message' => 'Notification job dispatched successfully',
                'token_count' => count($tokens),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to dispatch notification for tokens', [
                'error' => $e->getMessage(),
                'token_count' => count($tokens),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to dispatch notification: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Send new post notification
     */
    public function sendNewPostNotification(Post $post, ?string $platform = null): array
    {
        try {
            // Dispatch job for new post notification
            SendNewPostNotificationJob::dispatch($post, $platform);

            Log::info('New post notification job dispatched', [
                'post_id' => $post->id,
                'post_title' => $post->title,
                'platform' => $platform,
            ]);

            // Log::info('Running queue:work --once');
            // Artisan::call('queue:work --once');
            // Log::info('Finished queue:work --once');

            return [
                'success' => true,
                'message' => 'New post notification job dispatched successfully',
                'post_id' => $post->id,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to dispatch new post notification', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to dispatch new post notification: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Send notification to specific platform
     */
    public function sendToPlatform(string $platform, array $notification, array $data = []): array
    {
        return $this->sendToAll($notification, $data, $platform);
    }

    /**
     * Get notification statistics
     */
    public function getStats(): array
    {
        return [
            'total_tokens' => FcmToken::count(),
            'active_tokens' => FcmToken::active()->count(),
            'platforms' => FcmToken::active()
                ->selectRaw('platform, COUNT(*) as count')
                ->groupBy('platform')
                ->get()
                ->pluck('count', 'platform'),
        ];
    }

    /**
     * Send test notification to validate setup
     */
    public function sendTestNotification(?string $token = null): array
    {
        try {
            $testNotification = [
                'title' => 'Test Notification',
                'body' => 'This is a test notification from your blog API',
            ];

            $testData = [
                'type' => 'test',
                'timestamp' => now()->toISOString(),
            ];

            if ($token) {
                // Send to specific token
                return $this->sendToTokens([$token], $testNotification, $testData);
            } else {
                // Send to all active tokens
                return $this->sendToAll($testNotification, $testData);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send test notification', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send test notification: '.$e->getMessage(),
            ];
        }
    }
}
