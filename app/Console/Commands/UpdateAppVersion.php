<?php

namespace App\Console\Commands;

use App\Models\AppConfig;
use Illuminate\Console\Command;

class UpdateAppVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-version
                            {version : The new version number (e.g., 1.2.0)}
                            {--platform= : Platform (android, ios, or both)}
                            {--min-version= : Set minimum required version}
                            {--force-update : Force users to update}
                            {--update-url= : URL for app update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update app version configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->argument('version');
        $platform = $this->option('platform') ?: 'both';
        $minVersion = $this->option('min-version');
        $forceUpdate = $this->option('force-update');
        $updateUrl = $this->option('update-url');

        $platforms = $platform === 'both' ? ['android', 'ios'] : [$platform];

        foreach ($platforms as $platformName) {
            $this->info("Updating {$platformName} version to {$version}...");

            // Update latest version
            AppConfig::setValue(
                "app_latest_version_{$platformName}",
                $version,
                'string',
                "Latest version for {$platformName}",
                true
            );

            // Update minimum version if provided
            if ($minVersion) {
                AppConfig::setValue(
                    "app_min_version_{$platformName}",
                    $minVersion,
                    'string',
                    "Minimum required version for {$platformName}",
                    true
                );
            }

            // Update force update flag if provided
            if ($forceUpdate !== null) {
                AppConfig::setValue(
                    "app_force_update_{$platformName}",
                    $forceUpdate,
                    'boolean',
                    "Force update for {$platformName}",
                    true
                );
            }

            // Update URL if provided
            if ($updateUrl) {
                AppConfig::setValue(
                    "app_update_url_{$platformName}",
                    $updateUrl,
                    'string',
                    "Update URL for {$platformName}",
                    true
                );
            }

            $this->info("✓ {$platformName} version updated successfully");
        }

        // Also update the general version if both platforms
        if ($platform === 'both') {
            AppConfig::setValue(
                'app_latest_version',
                $version,
                'string',
                'Latest version of the mobile app',
                true
            );

            if ($minVersion) {
                AppConfig::setValue(
                    'app_min_version',
                    $minVersion,
                    'string',
                    'Minimum required version of the mobile app',
                    true
                );
            }
        }

        $this->info('App version update completed!');
    }
}
