<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Services\AppVersionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class AppConfigController extends Controller
{
    /**
     * Get all public app configurations
     */
    public function apiIndex(): JsonResponse
    {
        $configs = AppConfig::getPublicConfigs();

        return response()->json([
            'success' => true,
            'data' => $configs
        ]);
    }

    /**
     * Get client app configuration
     */
    public function apiClientConfig(): JsonResponse
    {
        $config = AppVersionService::getClientConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Get a specific public app configuration
     */
    public function apiShow(string $key): JsonResponse
    {
        $config = AppConfig::where('key', $key)
            ->where('is_public', true)
            ->first();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $config->key,
                'value' => $config->typed_value,
                'type' => $config->type,
                'description' => $config->description
            ]
        ]);
    }

    /**
     * Check app version and return update information
     */
    public function apiCheckVersion(Request $request): JsonResponse
    {
        $request->validate([
            'version' => 'required|string',
            'platform' => 'nullable|string|in:android,ios'
        ]);

        $currentVersion = $request->input('version');
        $platform = $request->input('platform', 'android');

        $versionInfo = AppVersionService::checkVersionUpdate($currentVersion, $platform);
        $versionInfo['message'] = AppVersionService::getUpdateMessage($versionInfo);

        return response()->json([
            'success' => true,
            'data' => $versionInfo
        ]);
    }

    /**
     * Get all app configurations (admin only)
     */
    public function index(): JsonResponse
    {
        $configs = AppConfig::all()->map(function ($config) {
            return [
                'id' => $config->id,
                'key' => $config->key,
                'value' => $config->typed_value,
                'type' => $config->type,
                'description' => $config->description,
                'is_public' => $config->is_public,
                'created_at' => $config->created_at,
                'updated_at' => $config->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $configs
        ]);
    }

    /**
     * Store a new app configuration (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string|unique:app_configs,key|max:255',
            'value' => 'required',
            'type' => ['required', Rule::in(['string', 'boolean', 'integer', 'json'])],
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $config = AppConfig::create([
            'key' => $request->key,
            'value' => is_array($request->value) ? json_encode($request->value) : (string) $request->value,
            'type' => $request->type,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Configuration created successfully',
            'data' => [
                'id' => $config->id,
                'key' => $config->key,
                'value' => $config->typed_value,
                'type' => $config->type,
                'description' => $config->description,
                'is_public' => $config->is_public,
            ]
        ], 201);
    }

    /**
     * Show a specific app configuration (admin only)
     */
    public function show(AppConfig $appConfig): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $appConfig->id,
                'key' => $appConfig->key,
                'value' => $appConfig->typed_value,
                'type' => $appConfig->type,
                'description' => $appConfig->description,
                'is_public' => $appConfig->is_public,
                'created_at' => $appConfig->created_at,
                'updated_at' => $appConfig->updated_at,
            ]
        ]);
    }

    /**
     * Update an app configuration (admin only)
     */
    public function update(Request $request, AppConfig $appConfig): JsonResponse
    {
        $request->validate([
            'key' => ['required', 'string', Rule::unique('app_configs', 'key')->ignore($appConfig->id), 'max:255'],
            'value' => 'required',
            'type' => ['required', Rule::in(['string', 'boolean', 'integer', 'json'])],
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

        return response()->json([
            'success' => true,
            'message' => 'Configuration updated successfully',
            'data' => [
                'id' => $appConfig->id,
                'key' => $appConfig->key,
                'value' => $appConfig->typed_value,
                'type' => $appConfig->type,
                'description' => $appConfig->description,
                'is_public' => $appConfig->is_public,
            ]
        ]);
    }

    /**
     * Delete an app configuration (admin only)
     */
    public function destroy(AppConfig $appConfig): JsonResponse
    {
        $appConfig->delete();

        return response()->json([
            'success' => true,
            'message' => 'Configuration deleted successfully'
        ]);
    }

    /**
     * Bulk update configurations (admin only)
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'configs' => 'required|array',
            'configs.*.key' => 'required|string',
            'configs.*.value' => 'required',
            'configs.*.type' => ['required', Rule::in(['string', 'boolean', 'integer', 'json'])],
            'configs.*.description' => 'nullable|string',
            'configs.*.is_public' => 'boolean'
        ]);

        $updatedConfigs = [];

        foreach ($request->configs as $configData) {
            $config = AppConfig::updateOrCreate(
                ['key' => $configData['key']],
                [
                    'value' => is_array($configData['value']) ? json_encode($configData['value']) : (string) $configData['value'],
                    'type' => $configData['type'],
                    'description' => $configData['description'] ?? null,
                    'is_public' => $configData['is_public'] ?? false
                ]
            );

            $updatedConfigs[] = [
                'id' => $config->id,
                'key' => $config->key,
                'value' => $config->typed_value,
                'type' => $config->type,
                'description' => $config->description,
                'is_public' => $config->is_public,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Configurations updated successfully',
            'data' => $updatedConfigs
        ]);
    }
}
