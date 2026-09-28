@props([
    'variant'  => 'icon',   // 'icon' = square mark only, 'full' = mark + wordmark
    'height'   => 32,       // rendered height in px
    'class'    => '',
])

@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name', 'Toolsearch'));
    $isFull   = $variant === 'full';
    // Intrinsic sizes of the source files, used to reserve space so the logo
    // cannot shift the layout while it loads.
    $ratio    = $isFull ? 646 / 260 : 1;
    $width    = (int) round($height * $ratio);
    $src      = $isFull ? '/images/logo-full.png' : '/images/logo-icon-180.png';
@endphp

<img src="{{ $src }}"
     alt="{{ $siteName }}"
     width="{{ $width }}"
     height="{{ $height }}"
     style="height:{{ $height }}px;width:auto"
     {{ $attributes->merge(['class' => 'block shrink-0 ' . $class]) }}>
