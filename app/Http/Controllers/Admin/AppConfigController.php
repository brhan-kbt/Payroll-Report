<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Services\AppVersionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AppConfigController extends Controller
{
    /**
     * Display a listing of app configurations
     */
    public function index(): View
    {
        $configs = AppConfig::orderBy('key')->get();

        return view('admin.app-config.index', compact('configs'));
    }

    /**
     * Show the form for creating a new app configuration
     */
    public function create(): View
    {
        return view('admin.app-config.create');
    }

    /**
     * Store a newly created app configuration
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => 'required|string|unique:app_configs,key|max:255',
            'value' => 'required',
            'type' => 'required|in:string,boolean,integer,json',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        AppConfig::create([
            'key' => $request->key,
            'value' => is_array($request->value) ? json_encode($request->value) : (string) $request->value,
            'type' => $request->type,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);

        return redirect()->route('admin.app-config.index')
            ->with('success', 'App configuration created successfully.');
    }

    /**
     * Display the specified app configuration
     */
    public function show(AppConfig $appConfig): View
    {
        return view('admin.app-config.show', compact('appConfig'));
    }

    /**
     * Show the form for editing the specified app configuration
     */
    public function edit(AppConfig $appConfig): View
    {
        return view('admin.app-config.edit', compact('appConfig'));
    }

    /**
     * Update the specified app configuration
     */
    public function update(Request $request, AppConfig $appConfig): RedirectResponse
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:app_configs,key,' . $appConfig->id,
            'value' => 'required',
            'type' => 'required|in:string,boolean,integer,json',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $appConfig->update([
            'key' => $request->key,
            'value' => is_array($request->value) ? json_encode($request->value) : (string) $request->value,
            'type' => $request->type,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);

        return redirect()->route('admin.app-config.index')
            ->with('success', 'App configuration updated successfully.');
    }

    /**
     * Remove the specified app configuration
     */
    public function destroy(AppConfig $appConfig): RedirectResponse
    {
        $appConfig->delete();

        return redirect()->route('admin.app-config.index')
            ->with('success', 'App configuration deleted successfully.');
    }

    /**
     * Show version management page
     */
    public function versionManagement(): View
    {
        $versionConfigs = AppConfig::whereIn('key', [
            'app_latest_version',
            'app_min_version',
            'app_force_update',
            'app_update_url',
            'app_latest_version_android',
            'app_min_version_android',
            'app_force_update_android',
            'app_update_url_android',
            'app_latest_version_ios',
            'app_min_version_ios',
            'app_force_update_ios',
            'app_update_url_ios',
        ])->get()->keyBy('key');

        return view('admin.app-config.version-management', compact('versionConfigs'));
    }

    /**
     * Update version configurations
     */
    public function updateVersionManagement(Request $request): RedirectResponse
    {
        $request->validate([
            'app_latest_version' => 'required|string',
            'app_min_version' => 'required|string',
            'app_force_update' => 'boolean',
            'app_update_url' => 'nullable|url',
            'app_latest_version_android' => 'required|string',
            'app_min_version_android' => 'required|string',
            'app_force_update_android' => 'boolean',
            'app_update_url_android' => 'nullable|url',
            'app_latest_version_ios' => 'required|string',
            'app_min_version_ios' => 'required|string',
            'app_force_update_ios' => 'boolean',
            'app_update_url_ios' => 'nullable|url',
        ]);

        $configs = [
            'app_latest_version' => ['value' => $request->app_latest_version, 'description' => 'Latest version of the mobile app'],
            'app_min_version' => ['value' => $request->app_min_version, 'description' => 'Minimum required version of the mobile app'],
            'app_force_update' => ['value' => $request->boolean('app_force_update') ? 'true' : 'false', 'description' => 'Whether to force users to update the app'],
            'app_update_url' => ['value' => $request->app_update_url, 'description' => 'URL where users can download the latest app version'],
            'app_latest_version_android' => ['value' => $request->app_latest_version_android, 'description' => 'Latest version for Android'],
            'app_min_version_android' => ['value' => $request->app_min_version_android, 'description' => 'Minimum required version for Android'],
            'app_force_update_android' => ['value' => $request->boolean('app_force_update_android') ? 'true' : 'false', 'description' => 'Force update for Android'],
            'app_update_url_android' => ['value' => $request->app_update_url_android, 'description' => 'Update URL for Android'],
            'app_latest_version_ios' => ['value' => $request->app_latest_version_ios, 'description' => 'Latest version for iOS'],
            'app_min_version_ios' => ['value' => $request->app_min_version_ios, 'description' => 'Minimum required version for iOS'],
            'app_force_update_ios' => ['value' => $request->boolean('app_force_update_ios') ? 'true' : 'false', 'description' => 'Force update for iOS'],
            'app_update_url_ios' => ['value' => $request->app_update_url_ios, 'description' => 'Update URL for iOS'],
        ];

        foreach ($configs as $key => $data) {
            AppConfig::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'type' => in_array($key, ['app_force_update', 'app_force_update_android', 'app_force_update_ios']) ? 'boolean' : 'string',
                    'description' => $data['description'],
                    'is_public' => true
                ]
            );
        }

        return redirect()->route('admin.app-config.version-management')
            ->with('success', 'Version configurations updated successfully.');
    }

    /**
     * Show maintenance mode management page
     */
    public function maintenanceMode(): View
    {
        $maintenanceConfig = AppConfig::where('key', 'maintenance_mode')->first();
        $maintenanceMessage = AppConfig::where('key', 'maintenance_message')->first();

        return view('admin.app-config.maintenance-mode', compact('maintenanceConfig', 'maintenanceMessage'));
    }

    /**
     * Update maintenance mode
     */
    public function updateMaintenanceMode(Request $request): RedirectResponse
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'required|string'
        ]);

        AppConfig::updateOrCreate(
            ['key' => 'maintenance_mode'],
            [
                'value' => $request->boolean('maintenance_mode') ? 'true' : 'false',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode',
                'is_public' => true
            ]
        );

        AppConfig::updateOrCreate(
            ['key' => 'maintenance_message'],
            [
                'value' => $request->maintenance_message,
                'type' => 'string',
                'description' => 'Message to show during maintenance',
                'is_public' => true
            ]
        );

        $status = $request->boolean('maintenance_mode') ? 'enabled' : 'disabled';

        return redirect()->route('admin.app-config.maintenance-mode')
            ->with('success', "Maintenance mode {$status} successfully.");
    }

    /**
     * Bulk update configurations
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'configs' => 'required|array',
            'configs.*.id' => 'required|exists:app_configs,id',
            'configs.*.value' => 'required',
        ]);

        foreach ($request->configs as $configData) {
            $config = AppConfig::find($configData['id']);
            if ($config) {
                $config->update([
                    'value' => is_array($configData['value']) ? json_encode($configData['value']) : (string) $configData['value']
                ]);
            }
        }

        return redirect()->route('admin.app-config.index')
            ->with('success', 'Configurations updated successfully.');
    }
}
