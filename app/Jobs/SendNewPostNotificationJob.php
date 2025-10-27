<?php

namespace App\Jobs;

use App\Jobs\CleanupFailedTokensAfterNotificationJob;
use App\Models\FcmToken;
use App\Models\Post;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SendNewPostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $platform;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post, string $platform = null)
    {
        $this->post = $post;
        $this->platform = $platform;
    }

    /**
     * Execute the job.
     */
    public function handle(FcmService $fcmService): void
    {
        try {
            Log::info('Processing new post notification job', [
                'post_id' => $this->post->id,
                'post_title' => $this->post->title,
                'platform' => $this->platform
            ]);

            // Get active tokens
            $query = FcmToken::active();

            if ($this->platform) {
                $query->platform($this->platform);
            }

            $tokens = $query->pluck('token')->toArray();

            if (empty($tokens)) {
                Log::info('No active tokens found for new post notification');
                return;
            }

            // Prepare notification
            $notification = [
                'title' => 'New Post: ' . $this->post->title,
                'body' => $this->post->subtitle ?: substr(strip_tags($this->post->body), 0, 100) . '...',
            ];

            // Add image if post has one
            if ($this->post->image) {
                $notification['image'] = $this->post->image;
            }

            // Prepare data payload
            $data = [
                'type' => 'new_post',
                'post_id' => (string) $this->post->id,
                'post_slug' => $this->post->slug,
                'category_id' => (string) $this->post->category_id,
                'author' => $this->post->user->name,
                'created_at' => $this->post->created_at->toISOString(),
            ];

            // Send notification
            $result = $fcmService->sendToTokens($tokens, $notification, $data);

            if ($result['success']) {
                Log::info('New post notification sent successfully', [
                    'post_id' => $this->post->id,
                    'success_count' => $result['success_count'],
                    'failure_count' => $result['failure_count']
                ]);
            } else {
                Log::error('New post notification failed', [
                    'post_id' => $this->post->id,
                    'message' => $result['message']
                ]);
            }

            // Handle failed tokens
            if (isset($result['failed_tokens']) && !empty($result['failed_tokens'])) {
                Log::info('Failed tokens detected in new post notification', [
                    'post_id' => $this->post->id,
                    'failed_count' => count($result['failed_tokens']),
                    'failed_tokens' => $result['failed_tokens']
                ]);

                // Dispatch cleanup job for failed tokens
                Log::info('Running queue:work --once');
                Artisan::call('queue:work --once');
                CleanupFailedTokensAfterNotificationJob::dispatch($result['failed_tokens'], 'new_post');
                Log::info('Finished queue:work --once');
            }

        } catch (\Exception $e) {
            Log::error('New post notification job exception', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('New post notification job failed permanently', [
            'post_id' => $this->post->id,
            'error' => $exception->getMessage()
        ]);
    }
}
