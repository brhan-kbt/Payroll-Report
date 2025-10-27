<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FirebaseServiceAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:service-account {action} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Firebase service account JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $path = $this->option('path') ?: config('services.firebase.service_account_path');

        switch ($action) {
            case 'setup':
                $this->setupServiceAccount($path);
                break;
            case 'validate':
                $this->validateServiceAccount($path);
                break;
            case 'info':
                $this->showServiceAccountInfo($path);
                break;
            default:
                $this->error('Invalid action. Use: setup, validate, or info');
                return 1;
        }

        return 0;
    }

    /**
     * Setup service account file
     */
    private function setupServiceAccount($path)
    {
        $this->info('Setting up Firebase service account...');

        // Create directory if it doesn't exist
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->info("Created directory: {$directory}");
        }

        if (File::exists($path)) {
            if (!$this->confirm("File already exists at {$path}. Overwrite?")) {
                $this->info('Setup cancelled.');
                return;
            }
        }

        $this->info('Please follow these steps:');
        $this->line('1. Go to Firebase Console > Project Settings > Service Accounts');
        $this->line('2. Click "Generate new private key"');
        $this->line('3. Download the JSON file');
        $this->line('4. Save it as: ' . $path);
        $this->line('5. Run: php artisan firebase:service-account validate');

        // Create a template file
        $template = [
            'type' => 'service_account',
            'project_id' => 'your-project-id',
            'private_key_id' => 'your-private-key-id',
            'private_key' => '-----BEGIN PRIVATE KEY-----\nYOUR_PRIVATE_KEY_HERE\n-----END PRIVATE KEY-----\n',
            'client_email' => 'your-service-account@your-project.iam.gserviceaccount.com',
            'client_id' => 'your-client-id',
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/your-service-account%40your-project.iam.gserviceaccount.com'
        ];

        File::put($path, json_encode($template, JSON_PRETTY_PRINT));
        $this->info("Template created at: {$path}");
        $this->warn('Please replace the template values with your actual service account data!');
    }

    /**
     * Validate service account file
     */
    private function validateServiceAccount($path)
    {
        $this->info("Validating service account file: {$path}");

        if (!File::exists($path)) {
            $this->error("Service account file not found: {$path}");
            return;
        }

        try {
            $content = File::get($path);
            $serviceAccount = json_decode($content, true);

            if (!$serviceAccount) {
                $this->error('Invalid JSON format');
                return;
            }

            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (!isset($serviceAccount[$field]) || empty($serviceAccount[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                $this->error('Missing required fields: ' . implode(', ', $missingFields));
                return;
            }

            // Check for template values
            $templateValues = ['your-project-id', 'your-private-key-id', 'YOUR_PRIVATE_KEY_HERE', 'your-service-account@your-project.iam.gserviceaccount.com'];
            $hasTemplateValues = false;

            foreach ($templateValues as $templateValue) {
                if (strpos($content, $templateValue) !== false) {
                    $hasTemplateValues = true;
                    break;
                }
            }

            if ($hasTemplateValues) {
                $this->warn('Service account file contains template values. Please replace with actual values.');
                return;
            }

            $this->info('✅ Service account file is valid!');
            $this->line("Project ID: {$serviceAccount['project_id']}");
            $this->line("Client Email: {$serviceAccount['client_email']}");

        } catch (\Exception $e) {
            $this->error('Error validating service account: ' . $e->getMessage());
        }
    }

    /**
     * Show service account info
     */
    private function showServiceAccountInfo($path)
    {
        $this->info("Service account file: {$path}");

        if (!File::exists($path)) {
            $this->error("Service account file not found: {$path}");
            return;
        }

        try {
            $content = File::get($path);
            $serviceAccount = json_decode($content, true);

            if (!$serviceAccount) {
                $this->error('Invalid JSON format');
                return;
            }

            $this->table(
                ['Field', 'Value'],
                [
                    ['Type', $serviceAccount['type'] ?? 'N/A'],
                    ['Project ID', $serviceAccount['project_id'] ?? 'N/A'],
                    ['Client Email', $serviceAccount['client_email'] ?? 'N/A'],
                    ['Client ID', $serviceAccount['client_id'] ?? 'N/A'],
                    ['Auth URI', $serviceAccount['auth_uri'] ?? 'N/A'],
                    ['Token URI', $serviceAccount['token_uri'] ?? 'N/A'],
                ]
            );

        } catch (\Exception $e) {
            $this->error('Error reading service account: ' . $e->getMessage());
        }
    }
}
