<a href="{{ route('tools.show', $tool) }}" class="tool-card group">
    <div class="flex items-start gap-3">
        <div class="tool-icon flex-shrink-0 text-xl"
             style="background: {{ $tool->color }}22; color: {{ $tool->color }}">
            {{ $tool->icon }}
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 text-sm leading-tight group-hover:text-brand-600 transition-colors">
                {{ $tool->name }}
            </h3>
            @if($tool->short_description)
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $tool->short_description }}</p>
            @endif
        </div>
        @if($tool->is_featured)
        <span class="text-yellow-400 text-xs flex-shrink-0">★</span>
        @endif
    </div>
    <div class="flex items-center justify-between mt-2">
        <span class="badge badge-gray text-xs">{{ $tool->category->name ?? '' }}</span>
        <span class="text-xs text-gray-400">{{ number_format($tool->view_count) }} uses</span>
    </div>
</a>
