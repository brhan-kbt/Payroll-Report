<?php

namespace App\Console\Commands;

use App\Jobs\CleanupFailedTokensJob;
use App\Jobs\RemoveFailedTokensJob;
use App\Models\FcmToken;
use Illuminate\Console\Command;

class CleanupFailedTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:cleanup-tokens
                            {--batch-size=50 : Number of tokens to process in each batch}
                            {--max-age=30 : Maximum age in days for tokens to validate}
                            {--remove-old : Remove old failed tokens permanently}
                            {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up failed and invalid FCM tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchSize = $this->option('batch-size');
        $maxAge = $this->option('max-age');
        $removeOld = $this->option('remove-old');
        $force = $this->option('force');

        $this->info('FCM Token Cleanup');
        $this->info('================');

        // Show current token statistics
        $this->showTokenStats();

        if (!$force) {
            if (!$this->confirm('Do you want to proceed with token cleanup?')) {
                $this->info('Cleanup cancelled.');
                return;
            }
        }

        // Dispatch cleanup job
        $this->info("Dispatching token cleanup job...");
        CleanupFailedTokensJob::dispatch($batchSize, $maxAge);
        $this->info("✅ Cleanup job dispatched successfully!");

        if ($removeOld) {
            $this->info("Dispatching old token removal job...");
            RemoveFailedTokensJob::dispatch();
            $this->info("✅ Removal job dispatched successfully!");
        }

        $this->info("\nJobs have been dispatched to the queue.");
        $this->info("Make sure your queue worker is running: php artisan queue:work");
    }

    /**
     * Show current token statistics
     */
    private function showTokenStats()
    {
        $totalTokens = FcmToken::count();
        $activeTokens = FcmToken::where('is_active', true)->count();
        $inactiveTokens = FcmToken::where('is_active', false)->count();
        $oldInactiveTokens = FcmToken::where('is_active', false)
            ->where('updated_at', '<', now()->subDays(7))
            ->count();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Tokens', $totalTokens],
                ['Active Tokens', $activeTokens],
                ['Inactive Tokens', $inactiveTokens],
                ['Old Inactive Tokens (>7 days)', $oldInactiveTokens],
            ]
        );

        // Show platform breakdown
        $platforms = FcmToken::where('is_active', true)
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->get();

        if ($platforms->isNotEmpty()) {
            $this->info("\nActive Tokens by Platform:");
            foreach ($platforms as $platform) {
                $this->line("  {$platform->platform}: {$platform->count}");
            }
        }
    }
}
