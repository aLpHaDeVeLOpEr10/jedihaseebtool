@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@if($tool->seo_keywords)
@section('seo_keywords_meta')
<meta name="keywords" content="{{ $tool->seo_keywords }}">
@endsection
@endif

@section('canonical', $tool->canonical_url ?: route('tools.show', $tool))

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "{{ $tool->name }}",
    "description": "{{ $tool->seo_description }}",
    "url": "{{ route('tools.show', $tool) }}",
    "applicationCategory": "UtilityApplication",
    "operatingSystem": "Web Browser",
    "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" }
    @if($tool->faqs->count() > 0)
    ,"mainEntity": {
        "@type": "FAQPage",
        "mainEntity": [
            @foreach($tool->faqs->where('is_visible', true) as $faq)
            {
                "@type": "Question",
                "name": "{{ addslashes($faq->question) }}",
                "acceptedAnswer": { "@type": "Answer", "text": "{{ addslashes($faq->answer) }}" }
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
    }
    @endif
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Tool Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
                <span>›</span>
                <a href="{{ route('categories.show', $tool->category) }}" class="hover:text-brand-600 transition-colors">
                    {{ $tool->category->name }}
                </a>
                <span>›</span>
                <span class="text-gray-900">{{ $tool->name }}</span>
            </nav>

            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0"
                     style="background: {{ $tool->color }}22; color: {{ $tool->color }}">
                    {{ $tool->icon }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
                        @if($tool->is_featured)
                        <span class="badge badge-warning">⭐ Featured</span>
                        @endif
                        <span class="badge badge-primary capitalize">{{ $tool->tool_type }}</span>
                    </div>
                    @if($tool->short_description)
                    <p class="text-gray-500 text-lg">{{ $tool->short_description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Tool Engine --}}
                <div class="card p-6"
                     x-data="toolRunner('{{ $tool->slug }}', '{{ route('tools.process', $tool->slug) }}')"
                     x-init="init()">

                    <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Use the Tool
                    </h2>

                    @if($tool->tool_type === 'productivity' && (str_contains($tool->slug, 'todo') || str_contains($tool->slug, 'notes')))
                        {{-- Productivity tools are client-side only --}}
                        <div class="alert alert-info">
                            This tool runs entirely in your browser. Your data is saved locally and never sent to our servers.
                        </div>
                    @else
                    <form @submit.prevent="submit()" class="space-y-4">
                        @if($tool->inputs->count() > 0)
                            @foreach($tool->inputs as $input)
                            <div>
                                <label class="form-label">
                                    {{ $input->field_label }}
                                    @if($input->required)<span class="text-red-500">*</span>@endif
                                </label>

                                @if($input->field_type === 'textarea')
                                <textarea name="{{ $input->field_name }}"
                                          placeholder="{{ $input->placeholder }}"
                                          x-model="formData.{{ $input->field_name }}"
                                          {{ $input->required ? 'required' : '' }}
                                          rows="4"
                                          class="form-input">{{ $input->default_value }}</textarea>

                                @elseif($input->field_type === 'select')
                                <select name="{{ $input->field_name }}"
                                        x-model="formData.{{ $input->field_name }}"
                                        class="form-input">
                                    @foreach((is_string($input->options) ? json_decode($input->options, true) : ($input->options ?? [])) as $opt)
                                    <option value="{{ is_array($opt) ? $opt['value'] : $opt }}">{{ is_array($opt) ? $opt['label'] : $opt }}</option>
                                    @endforeach
                                </select>

                                @elseif($input->field_type === 'checkbox')
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                           name="{{ $input->field_name }}"
                                           x-model="formData.{{ $input->field_name }}"
                                           class="rounded text-brand-600"
                                           {{ $input->default_value ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-600">{{ $input->placeholder ?: $input->field_label }}</span>
                                </label>

                                @elseif($input->field_type === 'range')
                                <div>
                                    <input type="range"
                                           name="{{ $input->field_name }}"
                                           x-model="formData.{{ $input->field_name }}"
                                           min="{{ $input->validation['min'] ?? 0 }}"
                                           max="{{ $input->validation['max'] ?? 100 }}"
                                           class="w-full accent-brand-600">
                                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                                        <span>{{ $input->validation['min'] ?? 0 }}</span>
                                        <span x-text="formData.{{ $input->field_name }} ?? {{ $input->default_value ?? 50 }}"></span>
                                        <span>{{ $input->validation['max'] ?? 100 }}</span>
                                    </div>
                                </div>

                                @elseif($input->field_type === 'color')
                                <div class="flex items-center gap-3">
                                    <input type="color"
                                           name="{{ $input->field_name }}"
                                           x-model="formData.{{ $input->field_name }}"
                                           value="{{ $input->default_value ?? '#6366f1' }}"
                                           class="w-12 h-10 rounded-lg border border-gray-300 cursor-pointer">
                                    <span x-text="formData.{{ $input->field_name }} ?? '{{ $input->default_value ?? '#6366f1' }}'" class="text-sm text-gray-600 font-mono"></span>
                                </div>

                                @else
                                <input type="{{ $input->field_type }}"
                                       name="{{ $input->field_name }}"
                                       placeholder="{{ $input->placeholder }}"
                                       x-model="formData.{{ $input->field_name }}"
                                       value="{{ $input->default_value }}"
                                       {{ $input->required ? 'required' : '' }}
                                       @if(!empty($input->validation['min'])) min="{{ $input->validation['min'] }}" @endif
                                       @if(!empty($input->validation['max'])) max="{{ $input->validation['max'] }}" @endif
                                       @if(!empty($input->validation['step'])) step="{{ $input->validation['step'] }}" @endif
                                       class="form-input">
                                @endif

                                @if($input->help_text)
                                <p class="form-help">{{ $input->help_text }}</p>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                This tool's input form is being configured. Please check back soon.
                            </div>
                        @endif

                        @if($tool->inputs->count() > 0)
                        <button type="submit" class="btn btn-primary w-full btn-lg" :disabled="loading">
                            <span x-show="!loading">⚡ Calculate / Process</span>
                            <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                                <span class="spinner"></span> Processing...
                            </span>
                        </button>
                        @endif
                    </form>
                    @endif

                    {{-- Results --}}
                    <div x-show="result" x-cloak class="mt-6 result-animate">
                        {{-- QR Code special case --}}
                        <template x-if="result && result.qr_url">
                            <div class="result-box text-center">
                                <h3 class="text-sm font-semibold text-gray-700 mb-4">Your QR Code</h3>
                                <img :src="result.qr_url" :alt="'QR Code'" class="mx-auto rounded-xl shadow-md max-w-[250px]">
                                <a :href="result.download" download="qrcode.png" class="btn btn-primary mt-4 mx-auto">
                                    ⬇ Download PNG
                                </a>
                            </div>
                        </template>

                        {{-- Color Palette special case --}}
                        <template x-if="result && result.palette">
                            <div class="result-box">
                                <h3 class="text-sm font-semibold text-gray-700 mb-4">Generated Palette</h3>
                                <div class="flex rounded-xl overflow-hidden h-20 mb-4">
                                    <template x-for="color in result.palette" :key="color.hex">
                                        <div class="flex-1 cursor-pointer hover:flex-[2] transition-all duration-300"
                                             :style="`background: ${color.hex}`"
                                             @click="JST.copyToClipboard(color.hex)"
                                             :title="color.hex"></div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    <template x-for="color in result.palette" :key="color.hex">
                                        <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 cursor-pointer hover:border-brand-300"
                                             @click="JST.copyToClipboard(color.hex)">
                                            <div class="w-8 h-8 rounded-md flex-shrink-0" :style="`background: ${color.hex}`"></div>
                                            <span class="text-xs font-mono text-gray-600" x-text="color.hex"></span>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-400 mt-3 text-center">Click any color to copy hex code</p>
                            </div>
                        </template>

                        {{-- Password list special case --}}
                        <template x-if="result && result.passwords && result.passwords.length > 1">
                            <div class="result-box space-y-2">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Generated Passwords</h3>
                                <template x-for="(pw, i) in result.passwords" :key="i">
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200">
                                        <code class="flex-1 text-sm font-mono text-gray-800 break-all" x-text="pw"></code>
                                        <button @click="JST.copyToClipboard(pw, $el)" class="btn btn-secondary btn-sm flex-shrink-0">Copy</button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- JSON formatter output --}}
                        <template x-if="result && result.output !== undefined">
                            <div class="result-box">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-700">Formatted Output</h3>
                                    <button @click="JST.copyToClipboard(result.output, $el)" class="btn btn-secondary btn-sm">Copy</button>
                                </div>
                                <pre class="code-block text-xs max-h-80 overflow-y-auto" x-text="result.output"></pre>
                            </div>
                        </template>

                        {{-- Text output (summary, etc.) --}}
                        <template x-if="result && result.summary">
                            <div class="result-box">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-700">Summary</h3>
                                    <button @click="JST.copyToClipboard(result.summary, $el)" class="btn btn-secondary btn-sm">Copy</button>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed" x-text="result.summary"></p>
                            </div>
                        </template>

                        {{-- General results --}}
                        <template x-if="result && result.results">
                            <div class="space-y-4">
                                {{-- Highlighted result --}}
                                <template x-if="result.results.some(r => r.highlight)">
                                    <div class="result-box text-center">
                                        <template x-for="r in result.results.filter(r => r.highlight)" :key="r.label">
                                            <div>
                                                <p class="text-sm text-gray-500 mb-1" x-text="r.label"></p>
                                                <p class="text-4xl font-bold text-brand-600" x-text="r.value"></p>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- Other results grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="r in result.results.filter(r => !r.highlight)" :key="r.label">
                                        <div class="bg-white rounded-xl border border-gray-100 p-4">
                                            <p class="text-xs text-gray-500 mb-1" x-text="r.label"></p>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900 text-sm break-all" x-text="r.value"></p>
                                                <button x-show="r.copyable" @click="JST.copyToClipboard(r.value, $el)"
                                                        class="text-gray-400 hover:text-brand-600 flex-shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Quick tips (tip calculator) --}}
                                <template x-if="result.quick_tips">
                                    <div class="card p-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Quick Tip Reference</h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="border-b border-gray-100">
                                                        <th class="text-left pb-2 text-gray-500 font-medium">Tip %</th>
                                                        <th class="text-right pb-2 text-gray-500 font-medium">Tip Amount</th>
                                                        <th class="text-right pb-2 text-gray-500 font-medium">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50">
                                                    <template x-for="row in result.quick_tips" :key="row.percent">
                                                        <tr>
                                                            <td class="py-2 font-medium" x-text="row.percent + '%'"></td>
                                                            <td class="py-2 text-right text-gray-600" x-text="'$' + row.tip"></td>
                                                            <td class="py-2 text-right font-medium text-brand-600" x-text="'$' + row.total"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>

                                {{-- Loan schedule preview --}}
                                <template x-if="result.schedule_preview">
                                    <div class="card p-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Amortization Schedule (first 12 months)</h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="border-b border-gray-100">
                                                        <th class="text-left pb-2 text-gray-500">Month</th>
                                                        <th class="text-right pb-2 text-gray-500">Payment</th>
                                                        <th class="text-right pb-2 text-gray-500">Principal</th>
                                                        <th class="text-right pb-2 text-gray-500">Interest</th>
                                                        <th class="text-right pb-2 text-gray-500">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50">
                                                    <template x-for="row in result.schedule_preview" :key="row.month">
                                                        <tr>
                                                            <td class="py-1.5" x-text="row.month"></td>
                                                            <td class="py-1.5 text-right" x-text="'$' + row.payment.toLocaleString()"></td>
                                                            <td class="py-1.5 text-right text-green-600" x-text="'$' + row.principal.toLocaleString()"></td>
                                                            <td class="py-1.5 text-right text-red-500" x-text="'$' + row.interest.toLocaleString()"></td>
                                                            <td class="py-1.5 text-right font-medium" x-text="'$' + row.balance.toLocaleString()"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>

                                {{-- All unit conversions --}}
                                <template x-if="result.all_conversions">
                                    <div class="card p-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">All Conversions</h4>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            <template x-for="conv in result.all_conversions" :key="conv.unit">
                                                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 text-xs">
                                                    <span class="font-medium text-gray-600" x-text="conv.unit"></span>
                                                    <span class="text-gray-900 font-mono" x-text="conv.value"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Note (e.g. currency disclaimer) --}}
                                <template x-if="result.note">
                                    <p class="text-xs text-gray-400 italic" x-text="result.note"></p>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Error --}}
                    <div x-show="error" x-cloak class="mt-4">
                        <div class="alert alert-error" x-text="error"></div>
                    </div>
                </div>

                {{-- Long description --}}
                @if($tool->long_description)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
                    <div class="tool-prose">
                        {!! nl2br(e($tool->long_description)) !!}
                    </div>
                </div>
                @endif

                {{-- Content sections --}}
                @foreach($tool->contents->where('is_visible', true) as $content)
                <div class="card p-6">
                    @if($content->title)
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $content->title }}</h2>
                    @endif
                    <div class="tool-prose">{!! nl2br(e($content->content)) !!}</div>
                </div>
                @endforeach

                {{-- FAQs --}}
                @if($tool->faqs->where('is_visible', true)->count() > 0)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
                    <div class="space-y-3" x-data="{ open: null }">
                        @foreach($tool->faqs->where('is_visible', true) as $i => $faq)
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                                     :class="open === {{ $i }} ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open === {{ $i }}" x-cloak class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">
                                {{ $faq->answer }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
                    <a href="{{ route('categories.show', $tool->category) }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-brand-50 transition-colors group">
                        <span class="text-2xl">{{ $tool->category->icon }}</span>
                        <div>
                            <p class="font-medium text-gray-800 group-hover:text-brand-600 text-sm transition-colors">
                                {{ $tool->category->name }}
                            </p>
                            <p class="text-xs text-gray-400">Browse category</p>
                        </div>
                    </a>
                </div>

                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Statistics</h3>
                    <div class="space-y-2 mt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Views</span>
                            <span class="font-medium">{{ number_format($tool->view_count) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Uses</span>
                            <span class="font-medium">{{ number_format($tool->use_count) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Type</span>
                            <span class="font-medium capitalize">{{ $tool->tool_type }}</span>
                        </div>
                    </div>
                </div>

                @if($relatedTools->count() > 0)
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
                    <div class="space-y-1">
                        @foreach($relatedTools as $related)
                        <a href="{{ route('tools.show', $related) }}"
                           class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                            <span class="text-lg flex-shrink-0">{{ $related->icon }}</span>
                            <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors leading-tight">
                                {{ $related->name }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toolRunner(slug, processUrl) {
    return {
        formData: {},
        result: null,
        error: null,
        loading: false,

        init() {
            // Initialize form data with default values from inputs
            document.querySelectorAll('[name]').forEach(input => {
                this.formData[input.name] = input.value || input.defaultValue || '';
            });
        },

        async submit() {
            this.loading = true;
            this.result = null;
            this.error = null;

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.formData),
                });

                const data = await response.json();

                if (data.success) {
                    this.result = data;
                } else {
                    this.error = data.error || 'An error occurred. Please try again.';
                }
            } catch (err) {
                this.error = 'Network error. Please check your connection and try again.';
                console.error(err);
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
