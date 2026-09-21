@props(['items' => []])

{{-- Ordered list semantics so assistive technology announces position and
     depth; the matching BreadcrumbList JSON-LD is emitted by partials/schema. --}}
<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'text-sm text-gray-500']) }}>
    <ol class="flex flex-wrap items-center gap-1.5 list-none p-0 m-0">
        @foreach($items as $index => $item)
            <li class="flex items-center gap-1.5 {{ $loop->last ? 'min-w-0' : '' }}">
                @if(!$loop->last)
                    @if(isset($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-brand-600 transition-colors">{{ $item['label'] }}</a>
                    @else
                        <span>{{ $item['label'] }}</span>
                    @endif
                    <span class="text-gray-300" aria-hidden="true">/</span>
                @else
                    <span class="text-gray-700 font-medium truncate max-w-[220px]" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
