@props(['items' => []])

<nav class="flex items-center gap-1.5 text-sm text-gray-500" aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @if(!$loop->last)
            @if(isset($item['url']))
                <a href="{{ $item['url'] }}" class="hover:text-brand-600 transition-colors">{{ $item['label'] }}</a>
            @else
                <span>{{ $item['label'] }}</span>
            @endif
            <span class="text-gray-300">/</span>
        @else
            <span class="text-gray-700 font-medium truncate max-w-[200px]">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
