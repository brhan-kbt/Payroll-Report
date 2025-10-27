<?php

namespace App\Jobs;

use App\Models\FcmToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupFailedTokensAfterNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $failedTokens;
    protected $notificationType;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 1;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(array $failedTokens, string $notificationType = 'general')
    {
        $this->failedTokens = $failedTokens;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (empty($this->failedTokens)) {
                Log::info('No failed tokens to cleanup');
                return;
            }

            Log::info('Starting failed token cleanup after notification', [
                'failed_count' => count($this->failedTokens),
                'notification_type' => $this->notificationType
            ]);

            $deactivatedCount = 0;
            $notFoundCount = 0;

            foreach ($this->failedTokens as $token) {
                try {
                    $fcmToken = FcmToken::where('token', $token)->first();

                    if ($fcmToken) {
                        // Deactivate the failed token
                        $fcmToken->delete();
                        $deactivatedCount++;

                        Log::debug('Deactivated failed token', [
                            'token_id' => $fcmToken->id,
                            'platform' => $fcmToken->platform,
                            'token' => substr($token, 0, 20) . '...'
                        ]);
                    } else {
                        $notFoundCount++;
                        Log::debug('Failed token not found in database', [
                            'token' => substr($token, 0, 20) . '...'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing failed token', [
                        'token' => substr($token, 0, 20) . '...',
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Failed token cleanup completed', [
                'total_failed' => count($this->failedTokens),
                'deactivated_count' => $deactivatedCount,
                'not_found_count' => $notFoundCount,
                'notification_type' => $this->notificationType
            ]);

        } catch (\Exception $e) {
            Log::error('Failed token cleanup job exception', [
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
        Log::error('Failed token cleanup job failed permanently', [
            'error' => $exception->getMessage(),
            'failed_tokens_count' => count($this->failedTokens)
        ]);
    }
}
