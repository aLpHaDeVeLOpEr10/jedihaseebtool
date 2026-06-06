@extends('layouts.admin')
@section('title', 'Website Settings')

@section('content')
<div x-data="{ tab: 'general' }">
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach([['general','General'],['seo','SEO'],['currency','Currency Rates']] as [$t,$l])
        <button @click="tab='{{ $t }}'" :class="tab==='{{ $t }}' ? 'border-brand-500 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors">{{ $l }}</button>
        @endforeach
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        {{-- General --}}
        <div x-show="tab === 'general'" class="grid lg:grid-cols-2 gap-6">
            <div class="card p-6 space-y-4">
                <h2 class="font-semibold text-gray-900">Site Identity</h2>
                <div>
                    <label class="form-label">Site Name *</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name']->value ?? 'JEDISEBITOOL' }}" class="form-input" required>
                    <p class="form-help">This name appears in the header, footer, and page titles.</p>
                </div>
                <div>
                    <label class="form-label">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline']->value ?? '' }}" class="form-input" placeholder="Free online tools for everyone">
                </div>
                <div>
                    <label class="form-label">Site Description</label>
                    <textarea name="site_description" rows="3" class="form-input" placeholder="Shown in footer and About page">{{ $settings['site_description']->value ?? '' }}</textarea>
                </div>
                <div>
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email']->value ?? '' }}" class="form-input" placeholder="hello@yourdomain.com">
                </div>
                <div>
                    <label class="form-label">Footer Text</label>
                    <input type="text" name="footer_text" value="{{ $settings['footer_text']->value ?? '' }}" class="form-input" placeholder="© 2024 JEDISEBITOOL. All rights reserved.">
                </div>
            </div>
            <div class="card p-6 space-y-4">
                <h2 class="font-semibold text-gray-900">Homepage</h2>
                <div>
                    <label class="form-label">Hero Title</label>
                    <input type="text" name="home_hero_title" value="{{ $settings['home_hero_title']->value ?? '' }}" class="form-input" placeholder="Your All-in-One">
                </div>
                <div>
                    <label class="form-label">Hero Subtitle</label>
                    <textarea name="home_hero_subtitle" rows="2" class="form-input">{{ $settings['home_hero_subtitle']->value ?? '' }}</textarea>
                </div>
                <h2 class="font-semibold text-gray-900 pt-4">Analytics & Features</h2>
                <div>
                    <label class="form-label">Google Analytics ID</label>
                    <input type="text" name="google_analytics" value="{{ $settings['google_analytics']->value ?? '' }}" class="form-input font-mono" placeholder="G-XXXXXXXXXX">
                </div>
                <div>
                    <label class="form-label">Tools Per Page</label>
                    <input type="number" name="tools_per_page" value="{{ $settings['tools_per_page']->value ?? 24 }}" class="form-input" min="6" max="100">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="enable_search" id="enable_search" value="1" class="rounded text-brand-600" {{ ($settings['enable_search']->value ?? '1') ? 'checked' : '' }}>
                    <label for="enable_search" class="text-sm text-gray-700">Enable search</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" class="rounded text-brand-600" {{ ($settings['maintenance_mode']->value ?? '') ? 'checked' : '' }}>
                    <label for="maintenance_mode" class="text-sm text-gray-700">⚠️ Maintenance Mode</label>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div x-show="tab === 'seo'" x-cloak class="card p-6 max-w-2xl space-y-4">
            <h2 class="font-semibold text-gray-900">Default SEO Settings</h2>
            <div>
                <label class="form-label">SEO Title Suffix</label>
                <input type="text" name="seo_title_suffix" value="{{ $settings['seo_title_suffix']->value ?? '' }}" class="form-input" placeholder="- JEDISEBITOOL">
                <p class="form-help">Appended to all page titles. E.g. "BMI Calculator - JEDISEBITOOL"</p>
            </div>
            <div>
                <label class="form-label">Default Meta Description</label>
                <textarea name="seo_default_description" rows="3" class="form-input">{{ $settings['seo_default_description']->value ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary btn-lg">Save Settings</button>
        </div>
    </form>

    {{-- Currency Rates --}}
    <div x-show="tab === 'currency'" x-cloak class="card p-6 max-w-3xl">
        <h2 class="font-semibold text-gray-900 mb-2">Currency Exchange Rates</h2>
        <p class="text-sm text-gray-500 mb-5">Enter rates relative to USD (1 USD = X). Update regularly for accuracy.</p>

        <form action="{{ route('admin.settings.currency') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-5" x-data="currencyEditor({{ json_encode($currencyRates) }})">
                <input type="hidden" name="rates" :value="JSON.stringify(rates)">

                <template x-for="(rate, currency) in rates" :key="currency">
                    <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-3">
                        <span class="text-sm font-medium text-gray-700 w-10 flex-shrink-0" x-text="currency"></span>
                        <input type="number" x-model="rates[currency]" step="0.0001" min="0"
                               class="form-input text-sm py-1">
                    </div>
                </template>
            </div>
            <button type="submit" class="btn btn-primary">Update Exchange Rates</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function currencyEditor(initialRates) {
    return {
        rates: initialRates && Object.keys(initialRates).length > 0 ? initialRates : {
            "USD":1,"EUR":0.92,"GBP":0.79,"JPY":148.5,"AUD":1.53,"CAD":1.36,
            "CHF":0.88,"CNY":7.24,"INR":83.1,"MXN":17.05,"BRL":4.97,"KRW":1325,
            "SGD":1.34,"HKD":7.82,"NOK":10.56,"SEK":10.38,"DKK":6.88,"NZD":1.63,
            "ZAR":18.62,"TRY":30.85,"AED":3.67,"SAR":3.75,"THB":35.02,"IDR":15630,
            "MYR":4.72,"PHP":56.7,"NGN":795,"EGP":30.9
        }
    };
}
</script>
@endpush
@endsection
