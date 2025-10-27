<?php

namespace App\Jobs;

use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tokens;

    protected $notification;

    protected $data;

    protected $platform;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(array $tokens, array $notification, array $data = [], ?string $platform = null)
    {
        $this->tokens = $tokens;
        $this->notification = $notification;
        $this->data = $data;
        $this->platform = $platform;
    }

    /**
     * Execute the job.
     */
    public function handle(FcmService $fcmService): void
    {
        try {
            Log::info('Processing notification job', [
                'token_count' => count($this->tokens),
                'platform' => $this->platform,
                'notification' => $this->notification,
            ]);

            $result = $fcmService->sendToTokens($this->tokens, $this->notification, $this->data);

            if ($result['success']) {
                Log::info('Notification job completed successfully', [
                    'success_count' => $result['success_count'],
                    'failure_count' => $result['failure_count'],
                ]);
            } else {
                Log::error('Notification job failed', [
                    'message' => $result['message'],
                    'result' => $result,
                ]);

                // If it's a critical failure, fail the job
                if (str_contains($result['message'], 'Invalid') ||
                    str_contains($result['message'], 'Unauthorized')) {
                    $this->fail(new \Exception($result['message']));
                }
            }

            // Log failed tokens for monitoring
            if (isset($result['failed_tokens']) && ! empty($result['failed_tokens'])) {
                Log::info('Failed tokens detected in notification job', [
                    'failed_count' => count($result['failed_tokens']),
                    'failed_tokens' => $result['failed_tokens'],
                ]);

                // Dispatch cleanup job for failed tokens
                Artisan::call('queue:work --once');

                CleanupFailedTokensAfterNotificationJob::dispatch($result['failed_tokens'], 'targeted');
            }

        } catch (\Exception $e) {
            Log::error('Notification job exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Notification job failed permanently', [
            'error' => $exception->getMessage(),
            'tokens' => $this->tokens,
            'notification' => $this->notification,
        ]);
    }
}
