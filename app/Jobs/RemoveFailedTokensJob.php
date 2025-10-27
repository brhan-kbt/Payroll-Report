<?php

namespace App\Jobs;

use App\Models\FcmToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemoveFailedTokensJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $failureThreshold;
    protected $maxAge;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 1;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(int $failureThreshold = 3, int $maxAge = 7)
    {
        $this->failureThreshold = $failureThreshold;
        $this->maxAge = $maxAge; // days
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting failed FCM token removal job', [
                'failure_threshold' => $this->failureThreshold,
                'max_age_days' => $this->maxAge
            ]);

            // Remove tokens that are inactive and old
            $removedTokens = FcmToken::where('is_active', false)
                ->where('updated_at', '<', now()->subDays($this->maxAge))
                ->delete();

            Log::info('Removed old inactive tokens', [
                'count' => $removedTokens
            ]);

            // Remove tokens that have been failing for a long time
            $failedTokens = FcmToken::where('is_active', false)
                ->where('created_at', '<', now()->subDays($this->maxAge * 2))
                ->delete();

            Log::info('Removed long-failed tokens', [
                'count' => $failedTokens
            ]);

            $totalRemoved = $removedTokens + $failedTokens;

            Log::info('Failed FCM token removal completed', [
                'total_removed' => $totalRemoved,
                'old_inactive' => $removedTokens,
                'long_failed' => $failedTokens
            ]);

        } catch (\Exception $e) {
            Log::error('Failed FCM token removal job failed', [
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
        Log::error('Failed FCM token removal job failed permanently', [
            'error' => $exception->getMessage()
        ]);
    }
}
