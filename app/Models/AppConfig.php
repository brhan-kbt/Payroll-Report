<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AppConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the typed value based on the type field
     */
    protected function typedValue(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->type) {
                    'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $this->value,
                    'json' => json_decode($this->value, true),
                    default => $this->value,
                };
            }
        );
    }

    /**
     * Get a config value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();

        if (!$config) {
            return $default;
        }

        return $config->typed_value;
    }

    /**
     * Set a config value
     */
    public static function setValue(string $key, $value, string $type = 'string', string $description = null, bool $isPublic = false)
    {
        $config = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'description' => $description,
                'is_public' => $isPublic,
            ]
        );

        return $config;
    }

    /**
     * Get all public configs
     */
    public static function getPublicConfigs()
    {
        return static::where('is_public', true)->get()->mapWithKeys(function ($config) {
            return [$config->key => $config->typed_value];
        });
    }

    /**
     * Check if app version requires update
     */
    public static function checkVersionUpdate(string $currentVersion)
    {
        $minVersion = static::getValue('app_min_version', '1.0.0');
        $latestVersion = static::getValue('app_latest_version', '1.0.0');
        $forceUpdate = static::getValue('app_force_update', false);

        $currentVersionParts = explode('.', $currentVersion);
        $minVersionParts = explode('.', $minVersion);

        $needsUpdate = false;
        $forceUpdateRequired = false;

        // Compare versions
        for ($i = 0; $i < max(count($currentVersionParts), count($minVersionParts)); $i++) {
            $current = (int) ($currentVersionParts[$i] ?? 0);
            $min = (int) ($minVersionParts[$i] ?? 0);

            if ($current < $min) {
                $needsUpdate = true;
                $forceUpdateRequired = $forceUpdate;
                break;
            } elseif ($current > $min) {
                break;
            }
        }

        return [
            'needs_update' => $needsUpdate,
            'force_update' => $forceUpdateRequired,
            'current_version' => $currentVersion,
            'min_version' => $minVersion,
            'latest_version' => $latestVersion,
            'update_url' => static::getValue('app_update_url', null),
        ];
    }
}
