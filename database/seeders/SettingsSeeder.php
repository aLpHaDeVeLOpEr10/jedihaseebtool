<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_name',        'value' => 'JEDISEBITOOL',  'type' => 'string',  'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline',     'value' => 'Free Online Tools for Everyone', 'type' => 'string', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'site_description', 'value' => 'JEDISEBITOOL is your all-in-one platform for free online calculators, converters, generators, and productivity tools.', 'type' => 'string', 'group' => 'general', 'label' => 'Description'],
            ['key' => 'contact_email',    'value' => 'hello@jedisebitool.com', 'type' => 'string', 'group' => 'general', 'label' => 'Contact Email'],
            ['key' => 'footer_text',      'value' => '© ' . date('Y') . ' JEDISEBITOOL. All rights reserved. Free tools for everyone.', 'type' => 'string', 'group' => 'general', 'label' => 'Footer Text'],
            ['key' => 'home_hero_title',  'value' => 'Your All-in-One', 'type' => 'string', 'group' => 'general', 'label' => 'Hero Title'],
            ['key' => 'home_hero_subtitle', 'value' => 'Calculators, converters, generators, and productivity tools — all free, no signup required.', 'type' => 'string', 'group' => 'general', 'label' => 'Hero Subtitle'],
            ['key' => 'enable_search',    'value' => '1',  'type' => 'boolean', 'group' => 'general', 'label' => 'Enable Search'],
            ['key' => 'maintenance_mode', 'value' => '0',  'type' => 'boolean', 'group' => 'general', 'label' => 'Maintenance Mode'],
            ['key' => 'tools_per_page',   'value' => '24', 'type' => 'integer', 'group' => 'general', 'label' => 'Tools Per Page'],
            ['key' => 'google_analytics', 'value' => '',   'type' => 'string',  'group' => 'seo', 'label' => 'Google Analytics ID'],
            ['key' => 'seo_title_suffix', 'value' => ' - JEDISEBITOOL', 'type' => 'string', 'group' => 'seo', 'label' => 'SEO Title Suffix'],
            ['key' => 'seo_default_description', 'value' => 'Free online tools for calculations, conversions, text processing, and more. No signup required.', 'type' => 'string', 'group' => 'seo', 'label' => 'Default Meta Description'],
            ['key' => 'currency_rates',   'value' => json_encode([
                'USD' => 1, 'EUR' => 0.92, 'GBP' => 0.79, 'JPY' => 148.5,
                'AUD' => 1.53, 'CAD' => 1.36, 'CHF' => 0.88, 'CNY' => 7.24,
                'INR' => 83.1, 'MXN' => 17.05, 'BRL' => 4.97, 'KRW' => 1325,
                'SGD' => 1.34, 'HKD' => 7.82, 'NOK' => 10.56, 'SEK' => 10.38,
                'DKK' => 6.88, 'NZD' => 1.63, 'ZAR' => 18.62, 'TRY' => 30.85,
                'AED' => 3.67, 'SAR' => 3.75, 'THB' => 35.02, 'IDR' => 15630,
                'MYR' => 4.72, 'PHP' => 56.7, 'NGN' => 795, 'EGP' => 30.9,
            ]), 'type' => 'json', 'group' => 'tools', 'label' => 'Currency Exchange Rates'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
