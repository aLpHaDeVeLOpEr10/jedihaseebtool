@props([
    'variant' => 'light',   // 'light' = for light backgrounds (navy wordmark)
                            // 'dark'  = for dark backgrounds (white wordmark)
                            // 'icon'  = square mark only
    'height'  => 65,        // rendered height in px; width follows automatically
    'width'   => null,      // optional explicit width in px (overrides the ratio)
])

@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name', 'Toolsearch'));

    // Supplied artwork, used unmodified. Both lockups are 1152x240; the square
    // mark is cut from the same source for favicons and tight spaces.
    $sources = [
        'light' => ['src' => '/images/logo.png',            'w' => 1152, 'h' => 240],
        'dark'  => ['src' => '/images/logo-footer.png',     'w' => 1152, 'h' => 240],
        'icon'  => ['src' => '/images/logo-icon-180.png',   'w' => 180,  'h' => 180],
    ];

    $logo = $sources[$variant] ?? $sources['light'];

    // Intrinsic dimensions are emitted so the browser reserves the right space
    // and the logo cannot shift the layout while it loads.
    $h = (int) $height;
    $w = $width !== null ? (int) $width : (int) round($h * $logo['w'] / $logo['h']);
@endphp

<img src="{{ $logo['src'] }}"
     alt="{{ $siteName }}"
     width="{{ $w }}"
     height="{{ $h }}"
     style="height:{{ $h }}px;width:{{ $width !== null ? $w . 'px' : 'auto' }}"
     {{ $attributes->merge(['class' => 'block shrink-0']) }}>
