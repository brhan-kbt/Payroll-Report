<?php

namespace App\Services;

use App\Models\AppConfig;

class AppVersionService
{
    /**
     * Check if the current app version needs an update
     */
    public static function checkVersionUpdate(string $currentVersion, string $platform = 'android'): array
    {
        $minVersion = AppConfig::getValue("app_min_version_{$platform}", AppConfig::getValue('app_min_version', '1.0.0'));
        $latestVersion = AppConfig::getValue("app_latest_version_{$platform}", AppConfig::getValue('app_latest_version', '1.0.0'));
        $forceUpdate = AppConfig::getValue("app_force_update_{$platform}", AppConfig::getValue('app_force_update', false));
        $updateUrl = AppConfig::getValue("app_update_url_{$platform}", AppConfig::getValue('app_update_url', null));

        $versionComparison = self::compareVersions($currentVersion, $minVersion);

        return [
            'needs_update' => $versionComparison < 0,
            'force_update' => $versionComparison < 0 && $forceUpdate,
            'current_version' => $currentVersion,
            'min_version' => $minVersion,
            'latest_version' => $latestVersion,
            'update_url' => $updateUrl,
            'platform' => $platform,
            'update_available' => self::compareVersions($currentVersion, $latestVersion) < 0,
        ];
    }

    /**
     * Compare two version strings
     * Returns: -1 if version1 < version2, 0 if equal, 1 if version1 > version2
     */
    public static function compareVersions(string $version1, string $version2): int
    {
        $v1Parts = array_map('intval', explode('.', $version1));
        $v2Parts = array_map('intval', explode('.', $version2));

        $maxLength = max(count($v1Parts), count($v2Parts));

        // Pad arrays with zeros to make them the same length
        $v1Parts = array_pad($v1Parts, $maxLength, 0);
        $v2Parts = array_pad($v2Parts, $maxLength, 0);

        for ($i = 0; $i < $maxLength; $i++) {
            if ($v1Parts[$i] < $v2Parts[$i]) {
                return -1;
            } elseif ($v1Parts[$i] > $v2Parts[$i]) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * Get version update message
     */
    public static function getUpdateMessage(array $versionInfo): string
    {
        if ($versionInfo['force_update']) {
            return "A critical update is required. Please update to version {$versionInfo['latest_version']} to continue using the app.";
        } elseif ($versionInfo['needs_update']) {
            return "An update is available. Please update to version {$versionInfo['latest_version']} for the best experience.";
        } elseif ($versionInfo['update_available']) {
            return "A new version {$versionInfo['latest_version']} is available. Update when convenient.";
        }

        return "You are using the latest version.";
    }

    /**
     * Check if app is in maintenance mode
     */
    public static function isMaintenanceMode(): bool
    {
        return AppConfig::getValue('maintenance_mode', false);
    }

    /**
     * Get maintenance message
     */
    public static function getMaintenanceMessage(): string
    {
        return AppConfig::getValue('maintenance_message', 'We are currently performing maintenance. Please try again later.');
    }

    /**
     * Get app configuration for client
     */
    public static function getClientConfig(): array
    {
        $publicConfigs = AppConfig::getPublicConfigs();

        return [
            'app_info' => [
                'name' => $publicConfigs['app_name'] ?? 'Blog App',
                'description' => $publicConfigs['app_description'] ?? 'A modern blog application',
                'logo_url' => $publicConfigs['app_logo_url'] ?? '/images/logo.png',
            ],
            'features' => [
                'dark_mode' => $publicConfigs['feature_dark_mode'] ?? true,
                'notifications' => $publicConfigs['feature_notifications'] ?? true,
                'offline_mode' => $publicConfigs['feature_offline_mode'] ?? false,
                'social_login' => $publicConfigs['feature_social_login'] ?? true,
            ],
            'contact' => [
                'email' => $publicConfigs['contact_email'] ?? 'support@yourapp.com',
                'phone' => $publicConfigs['contact_phone'] ?? '+1-555-0123',
            ],
            'links' => [
                'privacy_policy' => $publicConfigs['privacy_policy_url'] ?? null,
                'terms_of_service' => $publicConfigs['terms_of_service_url'] ?? null,
                'social' => $publicConfigs['social_links'] ?? [],
            ],
            'maintenance' => [
                'enabled' => self::isMaintenanceMode(),
                'message' => self::getMaintenanceMessage(),
            ],
        ];
    }
}
