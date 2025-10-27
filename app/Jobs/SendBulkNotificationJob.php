<?php

namespace App\Jobs;

use App\Jobs\CleanupFailedTokensAfterNotificationJob;
use App\Models\FcmToken;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SendBulkNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;
    protected $data;
    protected $platform;
    protected $batchSize;

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
    public function __construct(array $notification, array $data = [], string $platform = null, int $batchSize = 100)
    {
        $this->notification = $notification;
        $this->data = $data;
        $this->platform = $platform;
        $this->batchSize = $batchSize;
    }

    /**
     * Execute the job.
     */
    public function handle(FcmService $fcmService): void
    {
        try {
            Log::info('Processing bulk notification job', [
                'platform' => $this->platform,
                'batch_size' => $this->batchSize,
                'notification' => $this->notification
            ]);

            // Get active tokens
            $query = FcmToken::active();

            if ($this->platform) {
                $query->platform($this->platform);
            }

            $totalTokens = $query->count();

            if ($totalTokens === 0) {
                Log::info('No active tokens found for bulk notification');
                return;
            }

            Log::info("Found {$totalTokens} active tokens for bulk notification");

            $successCount = 0;
            $failureCount = 0;
            $processedCount = 0;
            $allFailedTokens = [];

            // Process tokens in batches
            $query->chunk($this->batchSize, function ($tokens) use ($fcmService, &$successCount, &$failureCount, &$processedCount, &$allFailedTokens) {
                $tokenArray = $tokens->pluck('token')->toArray();

                Log::info("Processing batch of " . count($tokenArray) . " tokens");

                $result = $fcmService->sendToTokens($tokenArray, $this->notification, $this->data);

                if ($result['success']) {
                    $successCount += $result['success_count'];
                    $failureCount += $result['failure_count'];
                } else {
                    $failureCount += count($tokenArray);
                    Log::error('Batch notification failed', [
                        'message' => $result['message'],
                        'batch_size' => count($tokenArray)
                    ]);
                }

                // Collect failed tokens from this batch
                if (isset($result['failed_tokens']) && !empty($result['failed_tokens'])) {
                    $allFailedTokens = array_merge($allFailedTokens, $result['failed_tokens']);

                    Log::info('Failed tokens in batch', [
                        'batch_size' => count($tokenArray),
                        'failed_count' => count($result['failed_tokens']),
                        'failed_tokens' => $result['failed_tokens']
                    ]);
                }

                $processedCount += count($tokenArray);

                // Small delay between batches to avoid rate limiting
                usleep(100000); // 0.1 second delay
            });

            Log::info('Bulk notification job completed', [
                'total_tokens' => $totalTokens,
                'processed_count' => $processedCount,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'total_failed_tokens' => count($allFailedTokens)
            ]);

            // Dispatch cleanup job for all failed tokens
            if (!empty($allFailedTokens)) {

                Artisan::call('queue:work --once');
                CleanupFailedTokensAfterNotificationJob::dispatch($allFailedTokens, 'bulk');
                Log::info('Dispatched cleanup job for failed tokens', [
                    'failed_count' => count($allFailedTokens)
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Bulk notification job exception', [
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
        Log::error('Bulk notification job failed permanently', [
            'error' => $exception->getMessage(),
            'notification' => $this->notification
        ]);
    }
}
