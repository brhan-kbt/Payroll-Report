<?php

namespace App\Jobs;

use App\Models\FcmToken;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupFailedTokensJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batchSize;
    protected $maxAge;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(int $batchSize = 50, int $maxAge = 30)
    {
        $this->batchSize = $batchSize;
        $this->maxAge = $maxAge; // days
    }

    /**
     * Execute the job.
     */
    public function handle(FcmService $fcmService): void
    {
        try {
            Log::info('Starting FCM token cleanup job', [
                'batch_size' => $this->batchSize,
                'max_age_days' => $this->maxAge
            ]);

            $totalProcessed = 0;
            $totalRemoved = 0;
            $totalFailed = 0;

            // Get tokens that haven't been used recently or are old
            $oldTokens = FcmToken::where('is_active', true)
                ->where(function ($query) {
                    $query->where('last_used_at', '<', now()->subDays($this->maxAge))
                          ->orWhereNull('last_used_at')
                          ->where('created_at', '<', now()->subDays($this->maxAge));
                })
                ->limit($this->batchSize)
                ->get();

            if ($oldTokens->isEmpty()) {
                Log::info('No old tokens found for cleanup');
                return;
            }

            Log::info("Found {$oldTokens->count()} old tokens to validate");

            foreach ($oldTokens as $token) {
                $totalProcessed++;

                try {
                    // Test the token by sending a validation notification
                    $isValid = $this->validateToken($fcmService, $token->token);

                    if (!$isValid) {
                        // Token is invalid, mark as inactive
                        $token->update(['is_active' => false]);
                        $totalRemoved++;

                        Log::info('Deactivated invalid token', [
                            'token_id' => $token->id,
                            'platform' => $token->platform,
                            'last_used' => $token->last_used_at
                        ]);
                    } else {
                        // Token is valid, update last used timestamp
                        $token->update(['last_used_at' => now()]);

                        Log::debug('Token validated successfully', [
                            'token_id' => $token->id
                        ]);
                    }

                } catch (\Exception $e) {
                    $totalFailed++;
                    Log::error('Error validating token', [
                        'token_id' => $token->id,
                        'error' => $e->getMessage()
                    ]);
                }

                // Small delay to avoid overwhelming FCM
                usleep(100000); // 0.1 second
            }

            Log::info('FCM token cleanup completed', [
                'total_processed' => $totalProcessed,
                'total_removed' => $totalRemoved,
                'total_failed' => $totalFailed
            ]);

        } catch (\Exception $e) {
            Log::error('FCM token cleanup job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Validate a single token
     */
    private function validateToken(FcmService $fcmService, string $token): bool
    {
        try {
            // Send a minimal test notification
            $testNotification = [
                'title' => 'Token Validation',
                'body' => 'Validating token...'
            ];

            $result = $fcmService->sendToToken($token, $testNotification);

            return $result['success'];
        } catch (\Exception $e) {
            Log::debug('Token validation failed', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('FCM token cleanup job failed permanently', [
            'error' => $exception->getMessage()
        ]);
    }
}
