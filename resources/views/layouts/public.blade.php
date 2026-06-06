<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', config('app.name', 'JEDISEBITOOL'))</title>
    <meta name="description" content="@yield('description', \App\Models\Setting::get('seo_default_description', 'Free online tools for everyone.'))">
    @yield('seo_keywords_meta')

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', '')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', config('app.name')) }}">
    @yield('og_image_meta')

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description" content="@yield('description', '')">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head --}}
    @yield('head')

    {{-- Structured Data --}}
    @yield('structured_data')

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
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Push scripts --}}
    @stack('scripts')

</body>
</html>
