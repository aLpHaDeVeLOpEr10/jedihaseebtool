@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Diff markup ── */
ins.gc-ins {
    background: #dcfce7; color: #14532d;
    text-decoration: none; border-radius: 3px;
    padding: 1px 3px; font-style: normal;
}
del.gc-del {
    background: #fee2e2; color: #991b1b;
    text-decoration: line-through; border-radius: 3px;
    padding: 1px 3px; font-style: normal;
}

/* ── Output prose box ── */
.gc-output {
    min-height: 220px;
    font-size: .9rem; line-height: 1.75; color: #1e293b;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: .875rem; padding: .875rem 1rem;
    white-space: pre-wrap; word-break: break-word;
    position: relative;
}

/* ── Issue item ── */
.gc-issue {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .65rem .875rem;
    border: 1.5px solid #e2e8f0; border-radius: .875rem;
    background: white; font-size: .82rem;
    transition: border-color .12s, background .12s;
}
.gc-issue:hover { border-color: #c7d2fe; background: #f8faff; }

/* ── Issue type badge ── */
.gc-badge {
    display: inline-flex; align-items: center; justify-content: center;
    padding: .2rem .55rem; border-radius: 9999px;
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap; flex-shrink: 0;
}
.gc-badge-spelling      { background: #fee2e2; color: #dc2626; }
.gc-badge-grammar       { background: #fef3c7; color: #d97706; }
.gc-badge-punctuation   { background: #dbeafe; color: #2563eb; }
.gc-badge-capitalization{ background: #ede9fe; color: #7c3aed; }
.gc-badge-style         { background: #f1f5f9; color: #64748b; }

/* ── Stat chip ── */
.gc-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem; border-radius: 9999px;
    background: #f1f5f9; font-size: .72rem; font-weight: 500; color: #475569;
}
.gc-chip.good  { background: #dcfce7; color: #166534; }
.gc-chip.warn  { background: #fef3c7; color: #92400e; }
.gc-chip.error { background: #fee2e2; color: #991b1b; }

/* ── Score ring ── */
.gc-score-ring {
    width: 5rem; height: 5rem; border-radius: 9999px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 800;
    border: 4px solid;
    flex-shrink: 0;
}
.gc-score-ring.perfect { border-color: #22c55e; color: #15803d; background: #f0fdf4; }
.gc-score-ring.good    { border-color: #f59e0b; color: #92400e; background: #fffbeb; }
.gc-score-ring.poor    { border-color: #ef4444; color: #991b1b; background: #fef2f2; }

/* ── Arrow between original/corrected ── */
.gc-arrow {
    display: flex; align-items: center; color: #94a3b8; font-size: .8rem;
    gap: .35rem; font-weight: 500;
}

/* ── Shimmer loading ── */
@keyframes shimmer { 0% { background-position: -600px 0; } 100% { background-position: 600px 0; } }
.gc-shimmer {
    height: .85rem; border-radius: .4rem; margin-bottom: .75rem;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 1200px 100%;
    animation: shimmer 1.5s infinite;
}

/* ── Copy flash ── */
@keyframes copyFlash { 0%,100% { background:#f0fdf4; color:#166534; } 50% { background:#dcfce7; } }
.gc-copy-flash { animation: copyFlash .5s ease 2 !important; }

/* ── Spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.gc-spin { animation: spin .65s linear infinite; display: inline-block; }

/* ── Result entrance ── */
@keyframes gcIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.gc-in { animation: gcIn .3s ease-out; }

/* ── Textarea ── */
.gc-ta { font-family: inherit; font-size: .9rem; line-height: 1.75; resize: vertical; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="grammarChecker()"
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
                        Instantly detect and correct spelling mistakes, grammar errors, punctuation issues, and capitalization problems. Paste your text below and click <strong>Check Grammar</strong>.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account Needed</span>
                    <span class="badge badge-primary">Instant</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        {{-- ══════════════════════════════════════════════
             TOP TWO-PANEL GRID: Input | Corrected text
             ══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            {{-- ══ LEFT: Input card ══ --}}
            <div class="card">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="font-semibold text-gray-800 text-sm">Your Text</span>
                    </div>
                    <div class="flex items-center gap-2" x-show="inputText.length > 0">
                        <span class="gc-chip"><span x-text="wordCount(inputText)"></span> words</span>
                        <span class="gc-chip"><span x-text="inputText.length"></span> chars</span>
                    </div>
                </div>

                <div class="p-5 space-y-3">
                    <textarea
                        x-model="inputText"
                        @keydown.ctrl.enter.prevent="check()"
                        @keydown.meta.enter.prevent="check()"
                        rows="13"
                        placeholder="Paste or type your text here to check grammar, spelling, and punctuation…

Example:
i went to the store yesterday and buyed some apple. the weather was very good and i feel great. she dont like going their on weekdays."
                        class="form-input gc-ta"
                        :class="inputError ? 'border-red-300 focus:border-red-400 focus:ring-red-300' : ''">
                    </textarea>

                    {{-- Validation error --}}
                    <div x-show="inputError"
                         x-transition
                         class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="inputError"></span>
                    </div>

                    {{-- Server/network error --}}
                    <div x-show="serverError"
                         x-transition
                         class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="serverError"></span>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex flex-wrap gap-2 pt-1">
                        <button type="button"
                                @click="check()"
                                :disabled="phase === 'loading'"
                                class="btn btn-primary flex-1 sm:flex-none">
                            <span x-show="phase === 'loading'" class="gc-spin">⟳</span>
                            <span x-show="phase !== 'loading'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span x-text="phase === 'loading' ? 'Checking…' : 'Check Grammar'"></span>
                        </button>

                        <button type="button"
                                @click="loadSample()"
                                class="btn btn-secondary">
                            📄 Sample
                        </button>

                        <button type="button"
                                @click="clearAll()"
                                x-show="inputText || phase === 'done'"
                                class="btn btn-secondary">
                            ✕ Clear
                        </button>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Ctrl</kbd>+<kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to check &nbsp;·&nbsp; Max ~2,500 words
                    </p>
                </div>
            </div>

            {{-- ══ RIGHT: Output card ══ --}}
            <div class="card" id="gc-output">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full"
                              :class="{
                                  'bg-emerald-400': phase === 'done' && result && result.is_clean,
                                  'bg-amber-400':   phase === 'done' && result && !result.is_clean,
                                  'bg-indigo-400':  phase === 'loading',
                                  'bg-gray-300':    phase === 'idle',
                              }">
                        </span>
                        <span class="font-semibold text-gray-800 text-sm">Corrected Text</span>
                    </div>
                    <div x-show="phase === 'done' && result" class="flex items-center gap-2">
                        <span class="gc-chip"
                              :class="result && result.is_clean ? 'good' : (result && result.issue_count > 5 ? 'error' : 'warn')">
                            <span x-show="result && result.is_clean">✓ No issues</span>
                            <span x-show="result && !result.is_clean">
                                <span x-text="result ? result.issue_count : 0"></span> issue<span x-show="result && result.issue_count !== 1">s</span>
                            </span>
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    {{-- IDLE --}}
                    <div x-show="phase === 'idle'"
                         class="flex flex-col items-center justify-center min-h-[260px] text-center">
                        <p class="text-5xl mb-4">✅</p>
                        <p class="text-sm font-semibold text-gray-600 mb-1">No text checked yet</p>
                        <p class="text-xs text-gray-400 max-w-xs mb-4">
                            Type or paste text on the left and click <strong>Check Grammar</strong> to see corrections here.
                        </p>
                        <button type="button"
                                @click="loadSample(); $nextTick(() => check())"
                                class="btn btn-outline text-sm">
                            Try with sample text →
                        </button>
                    </div>

                    {{-- LOADING shimmer --}}
                    <div x-show="phase === 'loading'" class="py-4">
                        <div class="gc-shimmer w-full"></div>
                        <div class="gc-shimmer w-10/12"></div>
                        <div class="gc-shimmer w-full"></div>
                        <div class="gc-shimmer w-9/12"></div>
                        <div class="gc-shimmer w-full"></div>
                        <div class="gc-shimmer w-11/12"></div>
                        <p class="text-center text-xs text-gray-400 mt-3">
                            <span class="gc-spin mr-1">⟳</span> Analysing your text…
                        </p>
                    </div>

                    {{-- DONE --}}
                    <div x-show="phase === 'done' && result"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="space-y-3 gc-in">

                        {{-- Clean badge --}}
                        <div x-show="result && result.is_clean"
                             class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <span class="text-2xl">🎉</span>
                            <div>
                                <p class="font-semibold text-emerald-800 text-sm">No issues found!</p>
                                <p class="text-xs text-emerald-600">Your text looks great — no grammar, spelling, or punctuation issues detected.</p>
                            </div>
                        </div>

                        {{-- Diff output --}}
                        <div class="gc-output"
                             x-html="result ? result.diff_html : ''">
                        </div>

                        {{-- Legend (show only when there are changes) --}}
                        <div x-show="result && result.changed" class="flex flex-wrap gap-3 text-xs">
                            <span class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-green-100 text-green-800 font-mono text-[10px]">corrected</span>
                                <span class="text-gray-400">= added/fixed</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-red-100 text-red-800 font-mono line-through text-[10px]">removed</span>
                                <span class="text-gray-400">= was wrong</span>
                            </span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button"
                                    @click="copyCorrected()"
                                    class="btn flex-1 sm:flex-none border border-gray-200"
                                    :class="copyFlash
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300'
                                        : 'bg-white text-gray-700 hover:bg-gray-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="copyFlash ? '✓ Copied!' : 'Copy Corrected'"></span>
                            </button>

                            <button type="button"
                                    @click="downloadCorrected()"
                                    class="btn btn-secondary flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download .txt
                            </button>

                            <button type="button"
                                    @click="check()"
                                    class="btn btn-secondary"
                                    title="Check again">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Re-check
                            </button>
                        </div>

                        {{-- Stats footer --}}
                        <div class="flex flex-wrap gap-2 pt-1 border-t border-gray-100">
                            <span class="gc-chip">📊 <span x-text="result ? result.word_count : 0"></span> words</span>
                            <span class="gc-chip">📝 <span x-text="result ? result.sentence_count : 0"></span> sentence<span x-show="result && result.sentence_count !== 1">s</span></span>
                            <span class="gc-chip"
                                  :class="result && result.is_clean ? 'good' : 'warn'">
                                🔍 <span x-text="result ? result.issue_count : 0"></span> issue<span x-show="result && result.issue_count !== 1">s</span> fixed
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             ISSUES PANEL (full width, only when done)
             ══════════════════════════════════════════════ --}}
        <div x-show="phase === 'done' && result && result.issues && result.issues.length > 0"
             x-transition
             class="card overflow-hidden gc-in">

            {{-- Panel header --}}
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <span class="font-semibold text-gray-800 text-sm">
                        Issues Found
                        <span class="ml-2 badge badge-warning text-xs" x-text="result ? result.issue_count : 0"></span>
                    </span>
                </div>
                {{-- Breakdown chips --}}
                <div class="hidden sm:flex flex-wrap gap-2" x-show="result && result.issues">
                    <template x-for="type in issueTypesSummary" :key="type.label">
                        <span class="gc-badge"
                              :class="'gc-badge-' + type.label"
                              x-text="type.label + ' (' + type.count + ')'">
                        </span>
                    </template>
                </div>
            </div>

            {{-- Issue list --}}
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <template x-for="(issue, idx) in result.issues" :key="idx">
                    <div class="gc-issue">
                        {{-- Type badge --}}
                        <span class="gc-badge mt-0.5"
                              :class="'gc-badge-' + issue.type"
                              x-text="issue.type">
                        </span>

                        <div class="min-w-0 flex-1">
                            {{-- original → corrected --}}
                            <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                                <code class="text-xs bg-red-50 text-red-700 px-1.5 py-0.5 rounded line-through"
                                      x-text="issue.original"></code>
                                <span class="text-gray-400 text-xs">→</span>
                                <code class="text-xs bg-green-50 text-green-700 px-1.5 py-0.5 rounded"
                                      x-text="issue.corrected"></code>
                                <span x-show="issue.count > 1"
                                      class="text-xs text-gray-400 ml-0.5"
                                      x-text="'×' + issue.count">
                                </span>
                            </div>
                            {{-- Explanation --}}
                            <p class="text-xs text-gray-500 leading-snug" x-text="issue.message"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             WHAT WE CHECK — info cards (visible when idle)
             ══════════════════════════════════════════════ --}}
        <div x-show="phase === 'idle'" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="card p-5 text-center">
                <p class="text-3xl mb-2">📝</p>
                <p class="text-sm font-semibold text-gray-700">Spelling</p>
                <p class="text-xs text-gray-400 mt-1">200+ common misspellings detected and corrected</p>
            </div>
            <div class="card p-5 text-center">
                <p class="text-3xl mb-2">⚠️</p>
                <p class="text-sm font-semibold text-gray-700">Grammar</p>
                <p class="text-xs text-gray-400 mt-1">Article errors, double words, phrase mistakes</p>
            </div>
            <div class="card p-5 text-center">
                <p class="text-3xl mb-2">🔤</p>
                <p class="text-sm font-semibold text-gray-700">Capitalization</p>
                <p class="text-xs text-gray-400 mt-1">Sentence starts, pronoun "I", days, and months</p>
            </div>
            <div class="card p-5 text-center">
                <p class="text-3xl mb-2">➤</p>
                <p class="text-sm font-semibold text-gray-700">Punctuation</p>
                <p class="text-xs text-gray-400 mt-1">Spacing, missing apostrophes, sentence spacing</p>
            </div>
        </div>

        {{-- Related tools --}}
        @if($relatedTools->count())
        <div x-show="phase === 'idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
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
/* ═══════════════════════════════════════════════════════════
   GRAMMAR CHECKER — Alpine.js component
   Backend: POST /tools/grammar-checker/process
═══════════════════════════════════════════════════════════ */
function grammarChecker() {
    return {
        /* ── State ── */
        inputText:   '',
        phase:       'idle',   /* idle | loading | done */
        result:      null,
        inputError:  '',
        serverError: '',
        copyFlash:   false,

        /* ── Computed ── */
        get issueTypesSummary() {
            if (!this.result || !this.result.issues) return [];
            var counts = {};
            this.result.issues.forEach(function(iss) {
                counts[iss.type] = (counts[iss.type] || 0) + (iss.count || 1);
            });
            return Object.keys(counts).map(function(t) {
                return { label: t, count: counts[t] };
            });
        },

        /* ── Lifecycle ── */
        init() { /* nothing */ },

        /* ── Public actions ── */
        check() {
            this.inputError  = '';
            this.serverError = '';

            var txt = this.inputText.trim();
            if (!txt) {
                this.inputError = 'Please enter some text to check.';
                return;
            }
            if (txt.split(/\s+/).length < 2) {
                this.inputError = 'Please enter at least a few words.';
                return;
            }

            var self   = this;
            this.phase = 'loading';
            this.result = null;

            fetch('{{ route("tools.process", "grammar-checker") }}', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ text: txt }),
            })
            .then(function(res) {
                if (res.status === 429) throw new Error('Too many requests. Please wait a moment and try again.');
                if (!res.ok)           throw new Error('Server error (' + res.status + '). Please try again.');
                return res.json();
            })
            .then(function(data) {
                if (!data.success) {
                    self.phase       = 'idle';
                    self.serverError = data.error || 'Something went wrong. Please try again.';
                    return;
                }
                self.result = data;
                self.phase  = 'done';

                /* Scroll to output on mobile */
                if (window.innerWidth < 1024) {
                    var el = document.getElementById('gc-output');
                    if (el) setTimeout(function() {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            })
            .catch(function(err) {
                self.phase       = 'idle';
                self.serverError = err.message || 'Connection failed. Please check your internet and try again.';
            });
        },

        loadSample() {
            this.inputText = 'i went to the store yesterday and buyed some apple. the weather was very good and i feel great about it. she dont like going their on weekdays. The team has did there best work this year. There are alot of things we could of done better, but overall the project was succesfull. We need to seperate the importent tasks from the less importent ones.';
            this.inputError  = '';
            this.serverError = '';
        },

        clearAll() {
            this.inputText   = '';
            this.result      = null;
            this.phase       = 'idle';
            this.inputError  = '';
            this.serverError = '';
        },

        async copyCorrected() {
            if (!this.result || !this.result.corrected) return;
            try {
                await navigator.clipboard.writeText(this.result.corrected);
            } catch(e) {
                /* Fallback for older browsers */
                var ta = document.createElement('textarea');
                ta.value = this.result.corrected;
                ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.copyFlash = true;
            setTimeout(function() { self.copyFlash = false; }, 1800);
        },

        downloadCorrected() {
            if (!this.result || !this.result.corrected) return;
            var blob = new Blob([this.result.corrected], { type: 'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = 'corrected-text.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        wordCount(text) {
            return (text || '').trim() === '' ? 0
                 : ((text || '').trim().match(/\S+/g) || []).length;
        },
    };
}
</script>
@endpush
