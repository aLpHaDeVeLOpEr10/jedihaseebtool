@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Big stat number ── */
.ac-big {
    font-size: 2.4rem;
    font-weight: 900;
    line-height: 1;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    word-break: break-all;
}
.ac-big.green {
    background: linear-gradient(135deg, #059669, #10b981);
    -webkit-background-clip: text;
    background-clip: text;
}
.ac-big.amber {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    -webkit-background-clip: text;
    background-clip: text;
}
.ac-big.rose {
    background: linear-gradient(135deg, #e11d48, #f43f5e);
    -webkit-background-clip: text;
    background-clip: text;
}
.ac-big.cyan {
    background: linear-gradient(135deg, #0891b2, #06b6d4);
    -webkit-background-clip: text;
    background-clip: text;
}
.ac-big.slate {
    background: linear-gradient(135deg, #475569, #64748b);
    -webkit-background-clip: text;
    background-clip: text;
}

/* ── Stat card ── */
.ac-stat {
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 1.125rem;
    padding: 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .45rem;
    text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.ac-stat:hover {
    border-color: #a5b4fc;
    box-shadow: 0 4px 16px rgba(79,70,229,.08);
    transform: translateY(-1px);
}

/* ── Number pill ── */
.ac-pill {
    display: inline-flex;
    align-items: center;
    padding: .25rem .65rem;
    border-radius: 9999px;
    font-size: .75rem;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    transition: background .12s;
}
.ac-pill:hover { background: #e0e9ff; border-color: #a5b4fc; color: #3730a3; }
.ac-pill.is-min { background: #fef3c7; border-color: #fcd34d; color: #92400e; }
.ac-pill.is-max { background: #dcfce7; border-color: #86efac; color: #14532d; }

/* ── Invalid token ── */
.ac-invalid-token {
    display: inline-flex; align-items: center;
    padding: .2rem .55rem; border-radius: .5rem;
    background: #fef2f2; border: 1px solid #fca5a5;
    color: #b91c1c; font-size: .75rem; font-weight: 600;
    font-family: monospace;
}

/* ── Result entrance ── */
@keyframes acIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0);   }
}
.ac-in { animation: acIn .28s ease-out; }

/* ── Shimmer ── */
@keyframes shimmer { 0% { background-position: -600px 0; } 100% { background-position: 600px 0; } }
.ac-shimmer {
    height: 4.5rem; border-radius: 1.125rem;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 1200px 100%;
    animation: shimmer 1.4s infinite;
}

/* ── Copy flash ── */
@keyframes acCopyFlash {
    0%,100% { background: #f0fdf4; color: #166534; border-color: #86efac; }
    50%     { background: #dcfce7; }
}
.ac-copy-flash { animation: acCopyFlash .5s ease 2 !important; }

/* ── Textarea number input ── */
.ac-textarea {
    font-family: 'Inter', ui-monospace, monospace;
    font-size: .92rem;
    line-height: 1.7;
    resize: vertical;
}

/* ── Divider label ── */
.ac-divider {
    display: flex; align-items: center; gap: .75rem;
    color: #94a3b8; font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em;
}
.ac-divider::before, .ac-divider::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}

/* ── Range bar ── */
.ac-range-bar-wrap { width: 100%; height: 6px; background: #f1f5f9; border-radius: 9999px; overflow: hidden; }
.ac-range-fill      { height: 100%; border-radius: 9999px; background: linear-gradient(90deg,#4f46e5,#7c3aed); transition: width .4s ease; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="avgCalc()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Enter a list of numbers to instantly compute the <strong>mean, sum, count, median, minimum, maximum, range</strong>, and more — separated by commas, spaces, or new lines.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-primary">Instant</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        {{-- ══════════════════════════════════════════════
             INPUT CARD
             ══════════════════════════════════════════════ --}}
        <div class="card">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span class="font-semibold text-gray-800 text-sm">Enter Numbers</span>
                </div>
                {{-- Live counter --}}
                <div class="flex items-center gap-2" x-show="parsed.valid.length > 0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-700">
                        <span x-text="parsed.valid.length"></span>&nbsp;number<span x-show="parsed.valid.length !== 1">s</span>
                    </span>
                </div>
            </div>

            <div class="p-5 space-y-4">

                {{-- ── Textarea ── --}}
                <div>
                    <label class="form-label">Numbers <span class="text-gray-400 font-normal">(comma, space, or newline separated)</span></label>
                    <textarea
                        x-model="raw"
                        @input.debounce.200ms="parseInput()"
                        @keydown.ctrl.enter.prevent="calculate()"
                        @keydown.meta.enter.prevent="calculate()"
                        rows="5"
                        placeholder="Examples:
10, 20, 30, 40, 50
1 2 3 4 5
100
200
300"
                        class="form-input ac-textarea"
                        :class="error ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : ''">
                    </textarea>
                    <p class="form-help">Separate numbers using commas, spaces, or new lines. Decimals (3.14) and negatives (-5) are supported.</p>
                </div>

                {{-- ── Options row ── --}}
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="form-label mb-0 whitespace-nowrap">Decimal places</label>
                        <select x-model="decimals" class="form-input py-1.5 pr-8 w-20 text-sm">
                            <option value="0">0</option>
                            <option value="2" selected>2</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="form-label mb-0 whitespace-nowrap">Show sorted list</label>
                        <button type="button"
                                @click="showSorted = !showSorted"
                                class="relative inline-flex items-center h-5 w-9 rounded-full transition-colors duration-200 focus:outline-none"
                                :class="showSorted ? 'bg-brand-600' : 'bg-gray-300'">
                            <span class="inline-block w-3.5 h-3.5 bg-white rounded-full shadow transform transition-transform duration-200"
                                  :class="showSorted ? 'translate-x-4' : 'translate-x-0.5'">
                            </span>
                        </button>
                    </div>
                </div>

                {{-- ── Error ── --}}
                <div x-show="error"
                     x-transition
                     class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="error"></span>
                </div>

                {{-- ── Invalid tokens warning ── --}}
                <div x-show="parsed.invalid.length > 0"
                     x-transition
                     class="flex items-start gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium">
                            <span x-text="parsed.invalid.length"></span> invalid value<span x-show="parsed.invalid.length !== 1">s</span> skipped:
                        </p>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <template x-for="inv in parsed.invalid" :key="inv">
                                <span class="ac-invalid-token" x-text="inv"></span>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- ── Action buttons ── --}}
                <div class="flex flex-wrap gap-2 pt-1">
                    <button type="button"
                            @click="calculate()"
                            :disabled="parsed.valid.length === 0"
                            class="btn btn-primary flex-1 sm:flex-none btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Calculate
                    </button>

                    <button type="button"
                            @click="loadSample()"
                            class="btn btn-secondary">
                        📋 Sample
                    </button>

                    <button type="button"
                            @click="clearAll()"
                            x-show="raw.length > 0 || phase === 'done'"
                            class="btn btn-secondary">
                        ✕ Clear
                    </button>
                </div>

                <p class="text-xs text-gray-400 text-center">
                    <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Ctrl</kbd>+<kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to calculate
                </p>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             RESULTS SECTION
             ══════════════════════════════════════════════ --}}

        {{-- Shimmer loading --}}
        <div x-show="phase === 'loading'" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <template x-for="i in 6" :key="i">
                <div class="ac-shimmer"></div>
            </template>
        </div>

        {{-- Results grid --}}
        <div x-show="phase === 'done' && result"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="space-y-5 ac-in"
             id="ac-results">

            {{-- ── Primary stat cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

                {{-- Mean --}}
                <div class="ac-stat lg:col-span-2">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mean / Average</div>
                    <div class="ac-big" x-text="result ? fmt(result.mean) : '—'"></div>
                    <div class="text-xs text-gray-400">sum ÷ count</div>
                </div>

                {{-- Sum --}}
                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sum</div>
                    <div class="ac-big green" x-text="result ? fmt(result.sum) : '—'"></div>
                    <div class="text-xs text-gray-400">total</div>
                </div>

                {{-- Count --}}
                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Count</div>
                    <div class="ac-big cyan" x-text="result ? result.count : '—'"></div>
                    <div class="text-xs text-gray-400">numbers</div>
                </div>

                {{-- Min --}}
                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Minimum</div>
                    <div class="ac-big amber" x-text="result ? fmt(result.min) : '—'"></div>
                    <div class="text-xs text-gray-400">smallest</div>
                </div>

                {{-- Max --}}
                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Maximum</div>
                    <div class="ac-big rose" x-text="result ? fmt(result.max) : '—'"></div>
                    <div class="text-xs text-gray-400">largest</div>
                </div>

            </div>

            {{-- ── Secondary stat cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Median</div>
                    <div class="ac-big slate" x-text="result ? fmt(result.median) : '—'"></div>
                    <div class="text-xs text-gray-400">middle value</div>
                </div>

                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Range</div>
                    <div class="ac-big slate" x-text="result ? fmt(result.range) : '—'"></div>
                    <div class="text-xs text-gray-400">max − min</div>
                </div>

                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Std Deviation</div>
                    <div class="ac-big slate" x-text="result ? fmt(result.stddev) : '—'"></div>
                    <div class="text-xs text-gray-400">population</div>
                </div>

                <div class="ac-stat">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mode</div>
                    <div class="text-lg font-bold text-slate-600 leading-tight" x-text="result ? result.modeLabel : '—'"></div>
                    <div class="text-xs text-gray-400">most frequent</div>
                </div>

            </div>

            {{-- ── Range visual bar ── --}}
            <div class="card p-5 space-y-3" x-show="result && result.count > 1">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Min: <strong class="text-amber-600" x-text="result ? fmt(result.min) : ''"></strong></span>
                    <span class="font-semibold text-gray-600">Mean position in range</span>
                    <span>Max: <strong class="text-emerald-600" x-text="result ? fmt(result.max) : ''"></strong></span>
                </div>
                <div class="relative">
                    <div class="ac-range-bar-wrap">
                        <div class="ac-range-fill" :style="'width:' + (result ? result.meanPct : 0) + '%'"></div>
                    </div>
                    {{-- Mean marker --}}
                    <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2"
                         :style="'left:' + (result ? result.meanPct : 0) + '%'"
                         style="transition: left .4s ease;">
                        <div class="w-3 h-3 rounded-full bg-brand-600 border-2 border-white shadow-sm ring-1 ring-brand-300"></div>
                    </div>
                </div>
                <p class="text-xs text-center text-gray-400">
                    Mean (<strong class="text-brand-600" x-text="result ? fmt(result.mean) : ''"></strong>)
                    is at <strong x-text="result ? result.meanPct.toFixed(1) : ''"></strong>% of the range
                </p>
            </div>

            {{-- ── Number list ── --}}
            <div class="card overflow-hidden" x-show="showSorted && result && result.count > 0">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">
                            Sorted Numbers
                            <span class="ml-1 badge badge-gray" x-text="result ? result.count : 0"></span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                @click="sortDir = sortDir === 'asc' ? 'desc' : 'asc'"
                                class="btn btn-secondary btn-sm">
                            <span x-text="sortDir === 'asc' ? '↑ Asc' : '↓ Desc'"></span>
                        </button>
                        <button type="button"
                                @click="copyList()"
                                class="btn btn-sm border border-gray-200"
                                :class="listCopyFlash ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-white text-gray-600 hover:bg-gray-50'">
                            <span x-text="listCopyFlash ? '✓ Copied' : 'Copy'"></span>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(n, idx) in displayedNumbers" :key="idx">
                            <span class="ac-pill"
                                  :class="{
                                      'is-min': result && n === result.min,
                                      'is-max': result && n === result.max
                                  }"
                                  x-text="fmt(n)">
                            </span>
                        </template>
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-3 pt-3 border-t border-gray-100 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full border border-amber-400 bg-amber-50 inline-block"></span>
                            Minimum
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full border border-emerald-400 bg-emerald-50 inline-block"></span>
                            Maximum
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Copy / share actions ── --}}
            <div class="card p-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-600">Export results:</span>
                    <button type="button"
                            @click="copySummary()"
                            class="btn btn-secondary btn-sm"
                            :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></span>
                    </button>
                    <button type="button"
                            @click="downloadSummary()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download .txt
                    </button>
                    <button type="button"
                            @click="clearAll()"
                            class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">
                        ✕ Reset
                    </button>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════
             IDLE STATE — info cards
             ══════════════════════════════════════════════ --}}
        <div x-show="phase === 'idle'" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach([
                ['icon' => '📐', 'label' => 'Mean', 'desc' => 'Arithmetic average of all values'],
                ['icon' => '➕', 'label' => 'Sum', 'desc' => 'Total of all numbers added together'],
                ['icon' => '#',  'label' => 'Count', 'desc' => 'How many numbers were entered'],
                ['icon' => '📊', 'label' => 'Median', 'desc' => 'Middle value when sorted'],
                ['icon' => '⬇️', 'label' => 'Min', 'desc' => 'Smallest number in the list'],
                ['icon' => '⬆️', 'label' => 'Max', 'desc' => 'Largest number in the list'],
            ] as $info)
            <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                <p class="text-2xl mb-1.5">{{ $info['icon'] }}</p>
                <p class="text-sm font-semibold text-gray-700">{{ $info['label'] }}</p>
                <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $info['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Related tools --}}
        @if($relatedTools->count())
        <div x-show="phase === 'idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Calculators</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════════
   AVERAGE CALCULATOR — pure client-side Alpine.js component
═══════════════════════════════════════════════════════════════ */
function avgCalc() {
    return {
        /* ── Input state ── */
        raw:       '',
        decimals:  '2',
        showSorted: true,
        sortDir:   'asc',

        /* ── Parse state ── */
        parsed: { valid: [], invalid: [] },

        /* ── UI phase ── */
        phase: 'idle',   /* idle | loading | done */
        error: '',
        result: null,

        /* ── Copy flash flags ── */
        summaryCopyFlash: false,
        listCopyFlash:    false,

        /* ── Lifecycle ── */
        init() { /* ready */ },

        /* ── Computed ── */
        get displayedNumbers() {
            if (!this.result) return [];
            var nums = this.result.sorted.slice();
            if (this.sortDir === 'desc') nums = nums.slice().reverse();
            return nums;
        },

        /* ── Parse the raw textarea input ── */
        parseInput() {
            this.error = '';
            var raw = this.raw;
            if (!raw.trim()) {
                this.parsed = { valid: [], invalid: [] };
                return;
            }

            /* Split on commas, semicolons, whitespace (including newlines) */
            var tokens = raw.split(/[\s,;]+/).filter(function(t) { return t.trim() !== ''; });

            var valid   = [];
            var invalid = [];

            tokens.forEach(function(tok) {
                var t = tok.trim();
                if (t === '') return;
                var n = parseFloat(t);
                if (!isNaN(n) && isFinite(n) && t.match(/^-?(\d+\.?\d*|\.\d+)([eE][+-]?\d+)?$/)) {
                    valid.push(n);
                } else {
                    if (invalid.indexOf(t) === -1) invalid.push(t); /* dedupe */
                }
            });

            this.parsed = { valid: valid, invalid: invalid };
        },

        /* ── Calculate all statistics ── */
        calculate() {
            this.error = '';
            this.parseInput(); /* ensure fresh parse */

            if (this.parsed.valid.length === 0) {
                this.error = this.raw.trim() === ''
                    ? 'Please enter at least one number.'
                    : 'No valid numbers found. Check your input for typos.';
                this.result = null;
                this.phase  = 'idle';
                return;
            }

            /* Brief shimmer to signal computation */
            var self = this;
            self.phase = 'loading';

            setTimeout(function() {
                self.result = self._compute(self.parsed.valid);
                self.phase  = 'done';

                /* Scroll to results on mobile */
                if (window.innerWidth < 1024) {
                    var el = document.getElementById('ac-results');
                    if (el) setTimeout(function() {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 80);
                }
            }, 180);
        },

        /* ── Core statistics engine ── */
        _compute(nums) {
            var n     = nums.length;
            var sum   = nums.reduce(function(a, b) { return a + b; }, 0);
            var mean  = sum / n;

            /* Sorted copy */
            var sorted = nums.slice().sort(function(a, b) { return a - b; });

            /* Median */
            var median;
            if (n % 2 === 0) {
                median = (sorted[n / 2 - 1] + sorted[n / 2]) / 2;
            } else {
                median = sorted[Math.floor(n / 2)];
            }

            var minVal = sorted[0];
            var maxVal = sorted[n - 1];
            var range  = maxVal - minVal;

            /* Population standard deviation */
            var variance = nums.reduce(function(acc, x) {
                return acc + Math.pow(x - mean, 2);
            }, 0) / n;
            var stddev = Math.sqrt(variance);

            /* Mode — most frequently occurring value(s) */
            var freq = {};
            nums.forEach(function(x) {
                var k = String(x);
                freq[k] = (freq[k] || 0) + 1;
            });
            var maxFreq = Math.max.apply(null, Object.values(freq));
            var modes   = Object.keys(freq).filter(function(k) { return freq[k] === maxFreq; }).map(Number);
            var modeLabel;
            if (maxFreq === 1) {
                modeLabel = 'No mode'; /* all values appear once */
            } else if (modes.length > 3) {
                modeLabel = modes.slice(0, 3).join(', ') + '…';
            } else {
                modeLabel = modes.join(', ');
            }

            /* Mean position as percentage of range (for the bar) */
            var meanPct = range === 0 ? 50 : Math.min(100, Math.max(0, ((mean - minVal) / range) * 100));

            return {
                count:     n,
                sum:       sum,
                mean:      mean,
                median:    median,
                min:       minVal,
                max:       maxVal,
                range:     range,
                stddev:    stddev,
                modeLabel: modeLabel,
                meanPct:   meanPct,
                sorted:    sorted,
            };
        },

        /* ── Format a number to the chosen decimal places ── */
        fmt(val) {
            if (val === null || val === undefined) return '—';
            if (this.decimals === 'auto') {
                /* Show up to 6 sig figs, strip trailing zeros */
                var s = parseFloat(val.toPrecision(6)).toString();
                return s;
            }
            var d = parseInt(this.decimals, 10);
            /* Trim trailing zeros only for display */
            var fixed = val.toFixed(d);
            if (d > 0) {
                fixed = fixed.replace(/\.?0+$/, function(m) {
                    /* Keep at least 'd' decimal places if they are significant */
                    return m === '.' + '0'.repeat(d) ? '' : m;
                });
            }
            return fixed;
        },

        /* ── Load sample data ── */
        loadSample() {
            this.raw = '12, 45, 7, 23, 67, 34, 89, 56, 78, 90, 3, 15, 42, 61, 28';
            this.error = '';
            this.parseInput();
        },

        /* ── Clear everything ── */
        clearAll() {
            this.raw     = '';
            this.parsed  = { valid: [], invalid: [] };
            this.result  = null;
            this.phase   = 'idle';
            this.error   = '';
        },

        /* ── Build plain-text summary ── */
        _buildSummary() {
            if (!this.result) return '';
            var r  = this.result;
            var f  = this.fmt.bind(this);
            var lines = [
                'Average Calculator Results',
                '==========================',
                'Numbers: ' + this.parsed.valid.join(', '),
                '',
                'Mean (Average) : ' + f(r.mean),
                'Median         : ' + f(r.median),
                'Sum            : ' + f(r.sum),
                'Count          : ' + r.count,
                'Minimum        : ' + f(r.min),
                'Maximum        : ' + f(r.max),
                'Range          : ' + f(r.range),
                'Std Deviation  : ' + f(r.stddev),
                'Mode           : ' + r.modeLabel,
            ];
            return lines.join('\n');
        },

        /* ── Copy summary to clipboard ── */
        async copySummary() {
            var text = this._buildSummary();
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
            } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.summaryCopyFlash = true;
            setTimeout(function() { self.summaryCopyFlash = false; }, 1800);
        },

        /* ── Copy number list ── */
        async copyList() {
            var nums = this.displayedNumbers;
            if (!nums.length) return;
            var self = this;
            var text = nums.map(function(n) { return self.fmt(n); }).join(', ');
            try {
                await navigator.clipboard.writeText(text);
            } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            this.listCopyFlash = true;
            setTimeout(function() { self.listCopyFlash = false; }, 1800);
        },

        /* ── Download summary as .txt ── */
        downloadSummary() {
            var text = this._buildSummary();
            if (!text) return;
            var blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = 'average-calculator-results.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
