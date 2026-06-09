<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Result</h3>
        <button @click="copyResult()"
                class="btn btn-sm btn-ghost text-xs gap-1.5"
                x-show="result && result.type !== 'file'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
            </svg>
            Copy
        </button>
    </div>

    {{-- Text / HTML result --}}
    <template x-if="result && result.type === 'text'">
        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-800 whitespace-pre-wrap break-words font-mono"
             x-text="result.content"></div>
    </template>

    <template x-if="result && result.type === 'html'">
        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-800"
             x-html="result.content"></div>
    </template>

    {{-- File download result --}}
    <template x-if="result && result.type === 'file'">
        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl border border-green-100">
            <div class="text-green-600 text-2xl">&#128190;</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate" x-text="result.filename"></p>
                <p class="text-xs text-gray-500 mt-0.5" x-text="result.size ?? ''"></p>
            </div>
            <a :href="result.url" :download="result.filename"
               class="btn btn-primary btn-sm">Download</a>
        </div>
    </template>

    {{-- Generic object result --}}
    <template x-if="result && result.type === 'json'">
        <div class="bg-gray-50 rounded-xl p-4 text-sm font-mono text-gray-800 whitespace-pre-wrap break-words"
             x-text="JSON.stringify(result.content, null, 2)"></div>
    </template>
</div>
