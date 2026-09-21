@props([
    'size'  => 22,      // glyph size in px
    'tile'  => false,   // render the gradient rounded-square behind the glyph
    'tileSize' => 32,   // tile size in px when $tile is true
])

{{--
    Toolsearch brand mark — a magnifier lens holding two tool sliders.
    The glyph is drawn in currentColor, so it takes the colour of its parent.
--}}
@php
    $glyph = '<g fill="none" stroke="currentColor" stroke-linecap="round">
        <circle cx="20" cy="20" r="12" stroke-width="4.2"/>
        <path d="M14 16.6h12M14 23.6h12" stroke-width="3"/>
        <g fill="currentColor" stroke="none">
            <circle cx="23.2" cy="16.6" r="3.1"/>
            <circle cx="16.8" cy="23.6" r="3.1"/>
        </g>
        <path d="M28.8 28.8L39.5 39.5" stroke-width="5.8"/>
    </g>';
@endphp

@if($tile)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-lg hero-gradient shrink-0']) }}
          style="width:{{ $tileSize }}px;height:{{ $tileSize }}px">
        <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 48 48" role="img"
             aria-label="{{ \App\Models\Setting::get('site_name', config('app.name')) }}" class="text-white">
            {!! $glyph !!}
        </svg>
    </span>
@else
    <svg {{ $attributes }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 48 48" role="img"
         aria-label="{{ \App\Models\Setting::get('site_name', config('app.name')) }}">
        {!! $glyph !!}
    </svg>
@endif
