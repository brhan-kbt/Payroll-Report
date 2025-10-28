<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'AFRO_API_KEY' => env('AFRO_API_KEY', ''),
            'AFRO_IDENTIFIER_ID' => env('AFRO_IDENTIFIER_ID', ''),
            'AFRO_SENDER_NAME' => env('AFRO_SENDER_NAME', ''),
            'AFRO_BASE_URL' => env('AFRO_BASE_URL', ''),
            'AFRO_OTP_EXPIRES_IN_SECONDS' => env('AFRO_OTP_EXPIRES_IN_SECONDS', 300),
            'AFRO_OPT_LENGTH' => env('AFRO_OPT_LENGTH', 6),
            'SHORT_CODE' => env('SHORT_CODE', '0000'),
        ];
        return view('settings.index', compact('settings'));
    }

    // Show create form
    public function create()
    {
        return view('settings.create');
    }
    public function updateAfro(Request $request)
    {
        // Validate input
        $request->validate([
            'AFRO_API_KEY' => 'required|string',
            'AFRO_IDENTIFIER_ID' => 'required|string',
            'AFRO_SENDER_NAME' => 'required|string',
            'AFRO_BASE_URL' => 'required|url',
            'AFRO_OTP_EXPIRES_IN_SECONDS' => 'required|integer',
            'AFRO_OPT_LENGTH' => 'required|integer',
            'SHORT_CODE' => 'required|string',
        ]);

        // Path to .env
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return back()->with('error', '.env file not found');
        }

        // Keys to update
        $keys = ['AFRO_API_KEY', 'AFRO_IDENTIFIER_ID', 'AFRO_SENDER_NAME', 'AFRO_BASE_URL', 'AFRO_OTP_EXPIRES_IN_SECONDS', 'AFRO_OPT_LENGTH', 'SHORT_CODE'];

        // Read current .env content
        $envContent = File::get($envPath);

        foreach ($keys as $key) {
            $value = $request->input($key);

            // Prepare value with quotes if it contains spaces
            if (str_contains($value, ' ')) {
                $value = '"' . $value . '"';
            }

            // If key exists, replace it; otherwise, append it
            if (preg_match("/^{$key}=.*$/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        // Save updated .env
        File::put($envPath, $envContent);

        // Clear config cache to reflect changes immediately
        if (app()->configurationIsCached()) {
            Artisan::call('config:clear');
        }

        return back()->with('success', 'AFRO SMS settings updated successfully.');
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:settings,key',
            'value' => 'required',
        ]);

        Setting::create($request->all());

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    public function show(Setting $setting)
    {
        return view('settings.show', compact('setting'));
    }

    // Show edit form
    public function edit(Setting $setting)
    {
        return view('settings.edit', compact('setting'));
    }

    // Update record
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'key' => 'required|unique:settings,key,' . $setting->id,
            'value' => 'required',
        ]);

        $setting->update($request->all());

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    // Delete record
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }

    public function privacyPolicy()
    {
        $privacyPolicy = Setting::where('key', 'privacy_policy')->first();

        return view('privacy-policy', compact('privacyPolicy'));
    }

    public function apiIndex()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    // Return a single setting by key
    public function apiShow($key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        return response()->json([
            'key' => $setting->key,
            'value' => $setting->value,
        ]);
    }
}
