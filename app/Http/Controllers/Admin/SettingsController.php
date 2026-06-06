<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        $currencyRates = Setting::get('currency_rates', '{}');
        if (is_string($currencyRates)) {
            $currencyRates = json_decode($currencyRates, true) ?? [];
        }

        return view('admin.settings.index', compact('settings', 'currencyRates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'        => 'required|string|max:100',
            'site_tagline'     => 'nullable|string|max:200',
            'site_description' => 'nullable|string|max:500',
            'contact_email'    => 'nullable|email|max:200',
            'site_logo'        => 'nullable|string|max:500',
            'footer_text'      => 'nullable|string|max:500',
            'google_analytics' => 'nullable|string|max:50',
            'tools_per_page'   => 'nullable|integer|min:6|max:100',
            'enable_search'    => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
            'home_hero_title'  => 'nullable|string|max:200',
            'home_hero_subtitle' => 'nullable|string|max:500',
            'seo_title_suffix' => 'nullable|string|max:100',
            'seo_default_description' => 'nullable|string|max:500',
            'currency_rates'   => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if ($value === null) continue;

            $type = match (true) {
                in_array($key, ['enable_search', 'maintenance_mode']) => 'boolean',
                in_array($key, ['tools_per_page']) => 'integer',
                $key === 'currency_rates' => 'json',
                default => 'string',
            };

            // Validate JSON for currency rates
            if ($key === 'currency_rates') {
                json_decode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return back()->withErrors(['currency_rates' => 'Invalid JSON format for currency rates.']);
                }
            }

            Setting::set($key, $value, $type);
        }

        // Handle boolean fields that might not be in request
        foreach (['enable_search', 'maintenance_mode'] as $boolKey) {
            Setting::set($boolKey, $request->boolean($boolKey), 'boolean');
        }

        Setting::flushCache();

        return back()->with('success', 'Settings saved successfully!');
    }

    public function updateCurrencyRates(Request $request)
    {
        $request->validate([
            'rates' => 'required|string',
        ]);

        json_decode($request->rates);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['rates' => 'Invalid JSON.']);
        }

        Setting::set('currency_rates', $request->rates, 'json');
        Setting::flushCache();

        return back()->with('success', 'Currency rates updated!');
    }
}
