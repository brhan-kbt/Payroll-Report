<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
      public function index()
    {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    // Show create form
    public function create()
    {
        return view('settings.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:settings,key',
            'value' => 'required',
        ]);


        Setting::create($request->all());

        return redirect()->route('settings.index')
                         ->with('success', 'Setting created successfully.');
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

        return redirect()->route('settings.index')
                         ->with('success', 'Setting updated successfully.');
    }

    // Delete record
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('settings.index')
                         ->with('success', 'Setting deleted successfully.');
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
            'key'   => $setting->key,
            'value' => $setting->value
        ]);
    }
}
