<?php

namespace App\Console\Commands;

use App\Models\FcmToken;
use Illuminate\Console\Command;

class RemoveFailedTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:remove-failed-tokens
                            {--max-age=7 : Maximum age in days for failed tokens to remove}
                            {--force : Force removal without confirmation}
                            {--dry-run : Show what would be removed without actually removing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old failed FCM tokens permanently';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $maxAge = $this->option('max-age');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $this->info('FCM Failed Token Removal');
        $this->info('========================');

        // Find tokens to remove
        $oldInactiveTokens = FcmToken::where('is_active', false)
            ->where('updated_at', '<', now()->subDays($maxAge))
            ->get();

        $longFailedTokens = FcmToken::where('is_active', false)
            ->where('created_at', '<', now()->subDays($maxAge * 2))
            ->get();

        $tokensToRemove = $oldInactiveTokens->merge($longFailedTokens)->unique('id');

        if ($tokensToRemove->isEmpty()) {
            $this->info('No failed tokens found to remove.');
            return;
        }

        $this->info("Found {$tokensToRemove->count()} tokens to remove:");
        $this->table(
            ['ID', 'Platform', 'Created', 'Last Updated', 'Reason'],
            $tokensToRemove->map(function ($token) use ($maxAge) {
                $reason = 'Old inactive';
                if ($token->created_at < now()->subDays($maxAge * 2)) {
                    $reason = 'Long failed';
                }
                return [
                    $token->id,
                    $token->platform ?? 'Unknown',
                    $token->created_at->format('Y-m-d H:i:s'),
                    $token->updated_at->format('Y-m-d H:i:s'),
                    $reason
                ];
            })->toArray()
        );

        if ($dryRun) {
            $this->info('Dry run completed. No tokens were actually removed.');
            return;
        }

        if (!$force) {
            if (!$this->confirm("Are you sure you want to remove {$tokensToRemove->count()} failed tokens?")) {
                $this->info('Removal cancelled.');
                return;
            }
        }

        // Remove tokens
        $removedCount = 0;
        foreach ($tokensToRemove as $token) {
            $token->delete();
            $removedCount++;
        }

        $this->info("✅ Successfully removed {$removedCount} failed tokens removed.");
    }
}
