<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // App Version Management
            [
                'key' => 'app_latest_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Latest version of the mobile app',
                'is_public' => true,
            ],
            [
                'key' => 'app_min_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Minimum required version of the mobile app',
                'is_public' => true,
            ],
            [
                'key' => 'app_force_update',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Whether to force users to update the app',
                'is_public' => true,
            ],
            [
                'key' => 'app_update_url',
                'value' => 'https://play.google.com/store/apps/details?id=com.example.app',
                'type' => 'string',
                'description' => 'URL where users can download the latest app version',
                'is_public' => true,
            ],

            // App Settings
            [
                'key' => 'app_name',
                'value' => 'Blog App',
                'type' => 'string',
                'description' => 'Name of the application',
                'is_public' => true,
            ],
            [
                'key' => 'app_description',
                'value' => 'A modern blog application',
                'type' => 'string',
                'description' => 'Description of the application',
                'is_public' => true,
            ],
            [
                'key' => 'app_logo_url',
                'value' => '/images/logo.png',
                'type' => 'string',
                'description' => 'URL to the application logo',
                'is_public' => true,
            ],

            // Feature Flags
            [
                'key' => 'feature_dark_mode',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable dark mode feature',
                'is_public' => true,
            ],
            [
                'key' => 'feature_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable push notifications',
                'is_public' => true,
            ],
            [
                'key' => 'feature_offline_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable offline mode',
                'is_public' => true,
            ],
            [
                'key' => 'feature_social_login',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable social media login',
                'is_public' => true,
            ],

            // API Settings
            [
                'key' => 'api_rate_limit',
                'value' => '100',
                'type' => 'integer',
                'description' => 'API rate limit per minute',
                'is_public' => false,
            ],
            [
                'key' => 'api_timeout',
                'value' => '30',
                'type' => 'integer',
                'description' => 'API timeout in seconds',
                'is_public' => false,
            ],

            // Maintenance Mode
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'We are currently performing maintenance. Please try again later.',
                'type' => 'string',
                'description' => 'Message to show during maintenance',
                'is_public' => true,
            ],

            // Social Media Links
            [
                'key' => 'social_links',
                'value' => json_encode([
                    'facebook' => 'https://facebook.com/yourapp',
                    'twitter' => 'https://twitter.com/yourapp',
                    'instagram' => 'https://instagram.com/yourapp',
                    'linkedin' => 'https://linkedin.com/company/yourapp'
                ]),
                'type' => 'json',
                'description' => 'Social media links',
                'is_public' => true,
            ],

            // Contact Information
            [
                'key' => 'contact_email',
                'value' => 'support@yourapp.com',
                'type' => 'string',
                'description' => 'Support email address',
                'is_public' => true,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1-555-0123',
                'type' => 'string',
                'description' => 'Support phone number',
                'is_public' => true,
            ],

            // Privacy and Terms
            [
                'key' => 'privacy_policy_url',
                'value' => 'https://yourapp.com/privacy',
                'type' => 'string',
                'description' => 'URL to privacy policy',
                'is_public' => true,
            ],
            [
                'key' => 'terms_of_service_url',
                'value' => 'https://yourapp.com/terms',
                'type' => 'string',
                'description' => 'URL to terms of service',
                'is_public' => true,
            ],
        ];

        foreach ($configs as $config) {
            AppConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}
