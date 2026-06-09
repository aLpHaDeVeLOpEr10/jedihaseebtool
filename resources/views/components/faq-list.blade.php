@props(['faqs'])

<div class="space-y-2" x-data="{ open: null }">
    @foreach($faqs->where('is_visible', true) as $faq)
    <div class="border border-gray-100 rounded-xl overflow-hidden">
        <button
            type="button"
            @click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
            class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-gray-50 transition-colors">
            <span class="text-sm font-medium text-gray-800">{{ $faq->question }}</span>
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                 :class="{ 'rotate-180': open === {{ $loop->index }} }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div
            x-show="open === {{ $loop->index }}"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
            {!! nl2br(e($faq->answer)) !!}
        </div>
    </div>
    @endforeach
</div>
