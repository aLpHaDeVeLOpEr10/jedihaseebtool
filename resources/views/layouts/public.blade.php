<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', config('app.name', 'Toolsearch'))</title>
    @php
        // Meta description: child section > tool model > global default
        $_seoDesc = $__env->hasSection('description')
            ? $__env->yieldContent('description')
            : ($__env->hasSection('meta_description')
                ? $__env->yieldContent('meta_description')
                : (isset($tool) && $tool->getRawOriginal('seo_description')
                    ? $tool->seo_description  // accessor adds short_description fallback
                    : \App\Models\Setting::get('seo_default_description', 'Free online tools for everyone.')));

        // Open Graph
        $_ogTitle = $__env->hasSection('og_title')
            ? $__env->yieldContent('og_title')
            : (isset($tool) && $tool->getRawOriginal('og_title')
                ? $tool->getRawOriginal('og_title')
                : $__env->yieldContent('title', config('app.name')));

        $_ogDesc = $__env->hasSection('og_description')
            ? $__env->yieldContent('og_description')
            : (isset($tool) && $tool->getRawOriginal('og_description')
                ? $tool->getRawOriginal('og_description')
                : $_seoDesc);

        // Share image: per-tool override, else the branded site default
        $_ogImage = $__env->hasSection('og_image')
            ? $__env->yieldContent('og_image')
            : (isset($tool) && $tool->getRawOriginal('og_image')
                ? $tool->getRawOriginal('og_image')
                : url('/og-default.png'));

        // Twitter Card
        $_twTitle = isset($tool) && $tool->getRawOriginal('twitter_title')
            ? $tool->getRawOriginal('twitter_title')
            : $_ogTitle;

        $_twDesc = isset($tool) && $tool->getRawOriginal('twitter_description')
            ? $tool->getRawOriginal('twitter_description')
            : $_seoDesc;

        // Canonical URL.
        // Paginated pages are self-canonical (page 2 must not claim to be page 1);
        // sort/type/category filter variants consolidate onto the clean URL.
        $_page      = (int) request()->query('page', 1);
        $_pageSuffix = $_page > 1 ? '?page=' . $_page : '';

        $_canonical = $__env->hasSection('canonical')
            ? trim($__env->yieldContent('canonical'))
            : (isset($tool) && $tool->getRawOriginal('canonical_url')
                ? $tool->getRawOriginal('canonical_url')
                : url()->current() . $_pageSuffix);

        // Robots. Internal search results are never indexed; error pages opt out.
        $_robots = $__env->hasSection('robots')
            ? trim($__env->yieldContent('robots'))
            : (request()->routeIs('search')
                ? 'noindex, follow'
                : (isset($tool) ? $tool->robots_meta : 'index, follow'));

        // SEO keywords
        $_keywords = isset($tool) && $tool->getRawOriginal('seo_keywords')
            ? $tool->getRawOriginal('seo_keywords')
            : '';
    @endphp
    <meta name="description" content="{{ $_seoDesc }}">
    @if($_keywords)
    <meta name="keywords" content="{{ $_keywords }}">
    @endif
    <meta name="robots" content="{{ $_robots }}">

    {{-- Canonical (suppressed on error pages) --}}
    @if($_canonical)
    <link rel="canonical" href="{{ $_canonical }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $_ogTitle }}">
    <meta property="og:description" content="{{ $_ogDesc }}">
    <meta property="og:url" content="{{ $_canonical ?: url()->current() }}">
    <meta property="og:type" content="{{ isset($tool) ? 'article' : 'website' }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_US' : str_replace('-', '_', app()->getLocale()) }}">
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', config('app.name')) }}">
    @if($_ogImage)
    <meta property="og:image" content="{{ $_ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif
    @yield('og_image_meta')

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ $_ogImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $_twTitle }}">
    <meta name="twitter:description" content="{{ $_twDesc }}">
    @if($_ogImage)
    <meta name="twitter:image" content="{{ $_ogImage }}">
    @endif

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="theme-color" content="#4f46e5">

    {{-- Inter is self-hosted (see resources/css/app.css). Preloading the latin
         file starts the download in parallel with the stylesheet, so text
         paints in the right face without a third-party round trip. --}}
    <link rel="preload" as="font" type="font/woff2" href="/fonts/inter-latin.woff2" crossorigin>

    {{-- Keep Alpine-hidden blocks hidden before Alpine boots. Inlined so a CSS
         rebuild can never purge it away and reintroduce layout shift. --}}
    <style>[x-cloak]{display:none!important}</style>

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head --}}
    @yield('head')

    {{-- Structured data. The shared graph covers every page (Organization,
         WebSite, and per-page SoftwareApplication / BreadcrumbList / FAQPage /
         CollectionPage). An admin-supplied schema_markup value is emitted in
         addition, and a view may opt out of the shared graph entirely. --}}
    @unless($__env->hasSection('structured_data'))
        @include('partials.schema')
    @else
        @yield('structured_data')
    @endunless
    @if(isset($tool) && $tool->getRawOriginal('schema_markup'))
        <script type="application/ld+json">{!! $tool->getRawOriginal('schema_markup') !!}</script>
    @endif

    {{-- Google Analytics --}}
    @if(\App\Models\Setting::get('google_analytics'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\Models\Setting::get('google_analytics') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ \App\Models\Setting::get('google_analytics') }}');
    </script>
    @endif
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    {{-- Navigation --}}
    @include('partials.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
        <div class="alert-success alert flex items-center gap-2" data-auto-dismiss="4000">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main>
        {{-- Breadcrumb for tool pages whose template does not render its own,
             so every tool page carries the same trail markup. --}}
        @if(request()->routeIs('tools.show') && isset($tool) && !$__env->hasSection('renders_own_breadcrumb'))
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3">
                <x-breadcrumb :items="array_values(array_filter([
                    ['label' => 'Home', 'url' => route('home')],
                    $tool->category ? ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category->slug)] : null,
                    ['label' => $tool->name],
                ]))"/>
            </div>
        </div>
        @endif

        @yield('content')

        {{-- Content sections injected for tool pages that don't render them inline --}}
        @unless($__env->hasSection('renders_own_content_sections'))
            @if(isset($tool) && $tool->contents->where('is_visible', true)->isNotEmpty())
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-8 space-y-6">
                @foreach($tool->contents->where('is_visible', true) as $section)
                <div class="card p-6">
                    @if($section->title)
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->title }}</h2>
                    @endif
                    <div class="tool-prose">{!! nl2br(e($section->content)) !!}</div>
                </div>
                @endforeach
            </div>
            @endif
        @endunless

        {{-- FAQs injected for tool pages that don't render them inline --}}
        @unless($__env->hasSection('renders_own_faqs'))
            @if(isset($tool) && $tool->faqs->where('is_visible', true)->isNotEmpty())
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-10">
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
                    <div class="space-y-3" x-data="{ open: null }">
                        @foreach($tool->faqs->where('is_visible', true) as $fi => $faq)
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <button @click="open = open === {{ $fi }} ? null : {{ $fi }}"
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                                     :class="open === {{ $fi }} ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open === {{ $fi }}" x-cloak
                                 class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">
                                {{ $faq->answer }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endunless
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Push scripts --}}
    @stack('scripts')

</body>
</html>
