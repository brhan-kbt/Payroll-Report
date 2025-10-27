<?php

namespace App\Console\Commands;

use App\Models\AppConfig;
use Illuminate\Console\Command;

class ToggleMaintenanceMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:maintenance
                            {--enable : Enable maintenance mode}
                            {--disable : Disable maintenance mode}
                            {--message= : Custom maintenance message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle maintenance mode for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enable = $this->option('enable');
        $disable = $this->option('disable');
        $message = $this->option('message');

        if ($enable && $disable) {
            $this->error('Cannot use both --enable and --disable options');
            return 1;
        }

        if (!$enable && !$disable) {
            // Toggle current state
            $currentState = AppConfig::getValue('maintenance_mode', false);
            $newState = !$currentState;
        } else {
            $newState = $enable;
        }

        // Update maintenance mode
        AppConfig::setValue(
            'maintenance_mode',
            $newState,
            'boolean',
            'Enable maintenance mode',
            true
        );

        // Update message if provided
        if ($message) {
            AppConfig::setValue(
                'maintenance_message',
                $message,
                'string',
                'Message to show during maintenance',
                true
            );
        }

        if ($newState) {
            $this->info('Maintenance mode enabled');
            if ($message) {
                $this->info("Maintenance message: {$message}");
            }
        } else {
            $this->info('Maintenance mode disabled');
        }

        return 0;
    }
}
