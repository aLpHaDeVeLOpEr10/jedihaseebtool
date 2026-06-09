@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Toggle switch ── */
.tog-track {
    width: 2.25rem; height: 1.25rem;
    border-radius: 9999px;
    transition: background .15s;
    flex-shrink: 0;
}
.tog-thumb {
    position: absolute; top: .125rem; left: .125rem;
    width: 1rem; height: 1rem;
    background: white; border-radius: 9999px;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
    transition: transform .15s;
}

/* ── Stat chip ── */
.j-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem; border-radius: 9999px;
    background: #f1f5f9; font-size: .75rem; font-weight: 500; color: #475569;
}

/* ── Preview table ── */
.prev-table { width: 100%; border-collapse: collapse; font-size: .75rem; }
.prev-table th {
    padding: .5rem .75rem;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    text-align: left;
    font-weight: 700; font-size: .68rem;
    text-transform: uppercase; letter-spacing: .05em;
    color: #64748b; white-space: nowrap;
}
.prev-table td {
    padding: .45rem .75rem;
    border-bottom: 1px solid #f1f5f9;
    color: #374151; max-width: 200px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.prev-table tbody tr:hover td { background: #f8fafc; }
.prev-table tbody tr:nth-child(even) td { background: #fafafa; }

/* ── Mono textarea ── */
.mono-area {
    font-family: 'JetBrains Mono','Fira Code','Cascadia Code',ui-monospace,monospace;
    font-size: .78rem; line-height: 1.6;
    resize: vertical;
}

/* ── Error shake ── */
@keyframes shake {
    0%,100% { transform: translateX(0); }
    20%,60%  { transform: translateX(-4px); }
    40%,80%  { transform: translateX(4px); }
}
.shake { animation: shake .3s ease; }

/* ── Copy flash ── */
@keyframes flashGreen {
    0%,100% { background:#f0fdf4; color:#166534; border-color:#86efac; }
    50%      { background:#dcfce7; }
}
.copy-flash { animation: flashGreen .6s ease 2 !important; }

/* ── Radio pill ── */
.radio-pill {
    display: flex; align-items: center; gap: .5rem;
    padding: .5rem .9rem; border-radius: .75rem;
    border: 1.5px solid #e2e8f0; cursor: pointer;
    font-size: .8rem; font-weight: 500; color: #475569;
    transition: all .13s; user-select: none;
}
.radio-pill.active {
    border-color: #4f46e5; background: #eef2ff; color: #4338ca;
}

/* ── Idle placeholder ── */
.idle-placeholder {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    min-height: 280px; text-align: center;
    border: 2.5px dashed #e2e8f0; border-radius: 1.25rem;
    padding: 2.5rem 1.5rem;
}

/* ── Step arrow ── */
.step-arrow {
    display: flex; align-items: center; justify-content: center;
    width: 2rem; height: 2rem; border-radius: 9999px;
    background: #eef2ff; color: #4f46e5; font-size: .8rem; font-weight: 700;
    flex-shrink: 0;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="jsonToCsv()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-xl">
                        Convert JSON arrays and objects to CSV format — handles nested objects, arrays, and mixed schemas. 100% in-browser, no data leaves your device.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Upload</span>
                    <span class="badge badge-primary">Client-side</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            {{-- ═══════════════════════════════
                 LEFT COLUMN — Input + Options
                 ═══════════════════════════════ --}}
            <div class="space-y-4">

                {{-- JSON Input Card --}}
                <div class="card">
                    {{-- Card header --}}
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span class="font-semibold text-gray-800 text-sm">JSON Input</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400"
                                  x-show="jsonInput.length > 0"
                                  x-text="jsonInput.length.toLocaleString('en-US') + ' chars'">
                            </span>
                            <span class="badge badge-success text-xs" x-show="jsonValid && !jsonError">✓ Valid JSON</span>
                            <span class="badge badge-danger text-xs" x-show="jsonError">✗ Invalid</span>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        {{-- Textarea --}}
                        <textarea
                            x-model="jsonInput"
                            @input="onInput()"
                            @keydown.ctrl.enter.prevent="convert()"
                            @keydown.meta.enter.prevent="convert()"
                            placeholder='[&#10;  {&#10;    "name": "Alice",&#10;    "age": 30,&#10;    "city": "New York"&#10;  },&#10;  {&#10;    "name": "Bob",&#10;    "age": 25,&#10;    "city": "London"&#10;  }&#10;]'
                            rows="14"
                            class="form-input mono-area"
                            :class="jsonError ? 'border-red-300 focus:border-red-400 focus:ring-red-300' : (jsonValid ? 'border-emerald-300 focus:border-emerald-400 focus:ring-emerald-300' : '')">
                        </textarea>

                        {{-- Validation error --}}
                        <div x-show="jsonError"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl">
                            <span class="text-red-500 shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-red-700 mb-0.5">JSON Syntax Error</p>
                                <p class="text-xs text-red-600 font-mono" x-text="jsonError"></p>
                            </div>
                        </div>

                        {{-- Hint when empty --}}
                        <p class="text-xs text-gray-400 text-center" x-show="!jsonInput">
                            Supports arrays of objects, single objects, and objects with array properties &nbsp;·&nbsp;
                            <button type="button" @click="loadSample()" class="text-indigo-500 hover:text-indigo-700 font-medium">Load sample data</button>
                        </p>

                        {{-- Action buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button"
                                    @click="convert()"
                                    :disabled="!jsonInput.trim()"
                                    class="btn btn-primary flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                Convert to CSV
                            </button>
                            <button type="button"
                                    @click="prettyPrint()"
                                    :disabled="!jsonInput.trim()"
                                    class="btn btn-secondary"
                                    title="Format JSON (Ctrl+Shift+F)">
                                ✨ Pretty
                            </button>
                            <button type="button"
                                    @click="minify()"
                                    :disabled="!jsonInput.trim()"
                                    class="btn btn-secondary"
                                    title="Minify JSON">
                                ⬡ Minify
                            </button>
                            <button type="button"
                                    @click="loadSample()"
                                    class="btn btn-secondary">
                                📄 Sample
                            </button>
                            <button type="button"
                                    @click="clearAll()"
                                    x-show="jsonInput || csvOutput"
                                    class="btn btn-secondary text-gray-500">
                                ✕ Clear
                            </button>
                        </div>

                        <p class="text-xs text-gray-400 text-center">
                            Tip: Press <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Ctrl</kbd>+<kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to convert
                        </p>
                    </div>
                </div>

                {{-- Options Card --}}
                <div class="card p-5 space-y-5">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="font-semibold text-gray-700 text-sm">Conversion Options</h3>
                    </div>

                    {{-- Delimiter + Separator row --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-xs">CSV Delimiter</label>
                            <select x-model="delimiter" class="form-input text-sm">
                                <option value=",">Comma (,) — default</option>
                                <option value=";">Semicolon (;)</option>
                                <option value="&#9;">Tab (\t)</option>
                                <option value="|">Pipe (|)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-xs">Nesting Separator</label>
                            <select x-model="flattenSep" class="form-input text-sm" :disabled="!flattenNested">
                                <option value=".">Dot (a.b.c)</option>
                                <option value="_">Underscore (a_b_c)</option>
                                <option value="/">Slash (a/b/c)</option>
                                <option value="__">Double underscore (a__b)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Toggle row --}}
                    <div class="space-y-3">
                        {{-- Flatten nested --}}
                        <label class="flex items-center justify-between gap-3 cursor-pointer group">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Flatten nested objects</p>
                                <p class="text-xs text-gray-400">
                                    <span x-show="flattenNested">
                                        <code class="bg-gray-100 px-1 rounded">{"a":{"b":1}}</code> →
                                        <code class="bg-gray-100 px-1 rounded">a<span x-text="flattenSep"></span>b</code>
                                    </span>
                                    <span x-show="!flattenNested">Nested objects will be JSON-stringified as a single cell</span>
                                </p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" x-model="flattenNested" class="sr-only">
                                <div class="tog-track" :class="flattenNested ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                <div class="tog-thumb" :class="flattenNested ? 'translate-x-4' : 'translate-x-0'"></div>
                            </div>
                        </label>

                        {{-- Include headers --}}
                        <label class="flex items-center justify-between gap-3 cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Include header row</p>
                                <p class="text-xs text-gray-400">First row contains column names</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" x-model="includeHeaders" class="sr-only">
                                <div class="tog-track" :class="includeHeaders ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                <div class="tog-thumb" :class="includeHeaders ? 'translate-x-4' : 'translate-x-0'"></div>
                            </div>
                        </label>

                        {{-- Quote all --}}
                        <label class="flex items-center justify-between gap-3 cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Quote all values</p>
                                <p class="text-xs text-gray-400">Wrap every cell in double quotes (safer for imports)</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" x-model="quoteAll" class="sr-only">
                                <div class="tog-track" :class="quoteAll ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                <div class="tog-thumb" :class="quoteAll ? 'translate-x-4' : 'translate-x-0'"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Array handling --}}
                    <div>
                        <label class="form-label text-xs">Arrays in values</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <button type="button"
                                    @click="arrayHandling = 'join'"
                                    class="radio-pill" :class="arrayHandling === 'join' ? 'active' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                                Join with semicolons
                            </button>
                            <button type="button"
                                    @click="arrayHandling = 'json'"
                                    class="radio-pill" :class="arrayHandling === 'json' ? 'active' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                Stringify (JSON)
                            </button>
                            <button type="button"
                                    @click="arrayHandling = 'first'"
                                    class="radio-pill" :class="arrayHandling === 'first' ? 'active' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h10"/>
                                </svg>
                                First element only
                            </button>
                        </div>
                    </div>
                </div>

                {{-- How it works --}}
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wider">How it works</h3>
                    <div class="space-y-3">
                        <div class="flex gap-3 items-start">
                            <span class="step-arrow">1</span>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Paste JSON</p>
                                <p class="text-xs text-gray-400">Array of objects, single object, or object containing an array</p>
                            </div>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="step-arrow">2</span>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Choose options</p>
                                <p class="text-xs text-gray-400">Delimiter, flatten depth, array handling</p>
                            </div>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="step-arrow">3</span>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Copy or download</p>
                                <p class="text-xs text-gray-400">Get your CSV — UTF-8 with BOM for Excel compatibility</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════
                 RIGHT COLUMN — Output
                 ═══════════════════════════════ --}}
            <div class="space-y-4" id="output-col">

                {{-- ─── IDLE placeholder ─── --}}
                <div x-show="phase === 'idle'"
                     x-transition
                     class="card p-8">
                    <div class="idle-placeholder">
                        <p class="text-5xl mb-4">🔄</p>
                        <p class="text-base font-semibold text-gray-600 mb-1">No output yet</p>
                        <p class="text-sm text-gray-400 mb-4 max-w-xs">Paste your JSON on the left and click <strong>Convert to CSV</strong> to get started.</p>
                        <button type="button"
                                @click="loadSample(); $nextTick(() => convert())"
                                class="btn btn-outline text-sm">
                            Try with sample data →
                        </button>
                    </div>
                </div>

                {{-- ─── OUTPUT card ─── --}}
                <div x-show="phase === 'done'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="card">

                    {{-- Card header --}}
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="font-semibold text-gray-800 text-sm">CSV Output</span>
                        </div>
                        <span class="badge badge-success text-xs">✓ Converted</span>
                    </div>

                    {{-- Stats chips --}}
                    <div class="flex flex-wrap gap-2 px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <span class="j-chip">
                            <span>📊</span>
                            <strong class="text-gray-700" x-text="stats.rows.toLocaleString('en-US')"></strong>
                            <span>row<span x-show="stats.rows !== 1">s</span></span>
                        </span>
                        <span class="j-chip">
                            <span>📋</span>
                            <strong class="text-gray-700" x-text="stats.cols"></strong>
                            <span>column<span x-show="stats.cols !== 1">s</span></span>
                        </span>
                        <span class="j-chip">
                            <span>💾</span>
                            <span x-text="formatBytes(new Blob([csvOutput]).size)"></span>
                        </span>
                        <span class="j-chip" x-show="stats.format">
                            <span>⚙️</span>
                            <span x-text="stats.format"></span>
                        </span>
                    </div>

                    <div class="p-5 space-y-3">
                        {{-- CSV Textarea --}}
                        <textarea x-ref="csvOut"
                                  x-model="csvOutput"
                                  readonly
                                  rows="14"
                                  class="form-input mono-area bg-gray-50 cursor-default select-all">
                        </textarea>

                        {{-- Action buttons --}}
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                    @click="copyCsv()"
                                    class="btn flex-1 sm:flex-none border border-gray-200"
                                    :class="copyFlash
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300'
                                        : 'bg-white text-gray-700 hover:bg-gray-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="copyFlash ? '✓ Copied!' : 'Copy CSV'"></span>
                            </button>

                            <button type="button"
                                    @click="downloadCsv()"
                                    class="btn btn-primary flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download .csv
                            </button>

                            <button type="button"
                                    @click="clearOutput()"
                                    class="btn btn-secondary">
                                ✕ Clear
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ─── PREVIEW TABLE ─── --}}
                <div x-show="phase === 'done' && previewHeaders.length > 0"
                     x-transition
                     class="card overflow-hidden">

                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800 text-sm">Preview</span>
                            <span class="badge badge-gray text-xs">first 5 rows</span>
                        </div>
                        <span class="text-xs text-gray-400"
                              x-text="stats.cols + ' column' + (stats.cols !== 1 ? 's' : '') + ' · ' + stats.rows + ' data row' + (stats.rows !== 1 ? 's' : '')">
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="prev-table">
                            <thead>
                                <tr>
                                    <th class="w-10 text-center text-gray-300">#</th>
                                    <template x-for="(h, i) in previewHeaders" :key="i">
                                        <th x-text="h"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, ri) in previewRows" :key="ri">
                                    <tr>
                                        <td class="text-center text-gray-300 font-mono text-xs" x-text="ri + 1"></td>
                                        <template x-for="(cell, ci) in row" :key="ci">
                                            <td :title="cell" x-text="cell"></td>
                                        </template>
                                    </tr>
                                </template>
                                <tr x-show="stats.rows > 5">
                                    <td :colspan="previewHeaders.length + 1"
                                        class="text-center text-xs text-gray-400 py-2.5 bg-gray-50">
                                        … and <strong x-text="(stats.rows - 5).toLocaleString('en-US')"></strong> more rows in the CSV
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ─── Supported formats info ─── --}}
                <div x-show="phase === 'idle'" class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wider">Supported Formats</h3>
                    <div class="space-y-3">

                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-3">
                            <p class="text-xs font-semibold text-gray-600 mb-1.5">✅ Array of objects <span class="badge badge-success ml-1">Recommended</span></p>
                            <pre class="text-xs text-gray-500 overflow-x-auto"><code>[{"name":"Alice","age":30},
 {"name":"Bob","age":25}]</code></pre>
                        </div>

                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-3">
                            <p class="text-xs font-semibold text-gray-600 mb-1.5">✅ Nested objects (flattened)</p>
                            <pre class="text-xs text-gray-500 overflow-x-auto"><code>[{"name":"Alice",
  "address":{"city":"NY"}}]
→ columns: name, address.city</code></pre>
                        </div>

                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-3">
                            <p class="text-xs font-semibold text-gray-600 mb-1.5">✅ Object with array property</p>
                            <pre class="text-xs text-gray-500 overflow-x-auto"><code>{"users":[{"id":1},{"id":2}]}
→ auto-extracts "users" array</code></pre>
                        </div>

                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-3">
                            <p class="text-xs font-semibold text-gray-600 mb-1.5">✅ Single object (1-row CSV)</p>
                            <pre class="text-xs text-gray-500 overflow-x-auto"><code>{"name":"Alice","age":30}
→ 1 header row + 1 data row</code></pre>
                        </div>

                    </div>
                </div>

                {{-- Related tools --}}
                @if($relatedTools->count())
                <div x-show="phase === 'idle'" class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Related Tools</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($relatedTools as $related)
                        <a href="{{ route('tools.show', $related->slug) }}"
                           class="card-hover p-4 flex items-center gap-3 no-underline">
                            <span class="text-2xl">{{ $related->icon }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   JSON TO CSV CONVERTER — Alpine.js component
═══════════════════════════════════════════════════════════ */
function jsonToCsv() {
    return {
        /* ── Input state ── */
        jsonInput:  '',
        jsonError:  '',
        jsonValid:  false,
        _valTimer:  null,

        /* ── Options ── */
        delimiter:      ',',
        flattenNested:  true,
        flattenSep:     '.',
        arrayHandling:  'join',   /* 'join' | 'json' | 'first' */
        includeHeaders: true,
        quoteAll:       false,

        /* ── Output state ── */
        csvOutput:  '',
        phase:      'idle',       /* 'idle' | 'done' */
        stats:      { rows: 0, cols: 0, format: '' },
        copyFlash:  false,

        /* ── Sample data ── */
        SAMPLE: JSON.stringify([
            { id: 1, name: "Alice Johnson",  age: 32, email: "alice@example.com",
              address: { city: "New York", country: "USA" },
              tags: ["developer", "designer"], active: true,  salary: 95000 },
            { id: 2, name: "Bob Smith",      age: 28, email: "bob@example.com",
              address: { city: "London",   country: "UK"  },
              tags: ["marketer"],            active: false, salary: 72000 },
            { id: 3, name: "Carol White",    age: 45, email: "carol@example.com",
              address: { city: "Toronto",  country: "Canada" },
              tags: ["manager","consultant"],active: true,  salary: 115000 },
            { id: 4, name: "David Lee",      age: 37, email: "david@example.com",
              address: { city: "Sydney",   country: "Australia" },
              tags: ["designer"],            active: true,  salary: 88000 },
            { id: 5, name: "Emma Brown",     age: 29, email: "emma@example.com",
              address: { city: "Berlin",   country: "Germany" },
              tags: ["developer"],           active: false, salary: 79000 },
            { id: 6, name: "Frank Davis",    age: 52, email: "frank@example.com",
              address: { city: "Paris",    country: "France" },
              tags: ["manager"],             active: true,  salary: 125000 },
        ], null, 2),

        /* ══════════════════════════════════
           COMPUTED
        ══════════════════════════════════ */

        get previewHeaders() {
            if (!this.csvOutput) return [];
            var lines = this.csvOutput.split(/\r?\n/);
            if (!lines.length) return [];
            if (this.includeHeaders) return this._splitLine(lines[0]);
            /* No headers — generate "Column N" labels */
            var first = this._splitLine(lines[0]);
            return first.map(function(_, i) { return 'Column ' + (i + 1); });
        },

        get previewRows() {
            if (!this.csvOutput) return [];
            var lines = this.csvOutput.split(/\r?\n/).filter(function(l) { return l.trim(); });
            var start = this.includeHeaders ? 1 : 0;
            var self  = this;
            return lines.slice(start, start + 5).map(function(l) {
                return self._splitLine(l);
            });
        },

        /* ══════════════════════════════════
           LIFECYCLE
        ══════════════════════════════════ */

        init() { /* nothing to bootstrap */ },

        /* ══════════════════════════════════
           VALIDATION
        ══════════════════════════════════ */

        onInput() {
            var self = this;
            clearTimeout(this._valTimer);
            this._valTimer = setTimeout(function() { self._validate(); }, 320);
        },

        _validate() {
            var txt = this.jsonInput.trim();
            if (!txt) { this.jsonError = ''; this.jsonValid = false; return; }
            try {
                JSON.parse(txt);
                this.jsonError = '';
                this.jsonValid = true;
            } catch(e) {
                this.jsonError = e.message;
                this.jsonValid = false;
            }
        },

        /* ══════════════════════════════════
           CONVERSION
        ══════════════════════════════════ */

        convert() {
            var txt = this.jsonInput.trim();
            if (!txt) { this.jsonError = 'Please enter some JSON first.'; return; }

            /* Parse */
            var data;
            try {
                data = JSON.parse(txt);
            } catch(e) {
                this.jsonError = e.message;
                this.jsonValid = false;
                return;
            }

            /* Extract array */
            var extracted = this._extractArray(data);
            if (!extracted) {
                this.jsonError = 'Unsupported format. Input must be an array, a single object, or an object containing an array property.';
                return;
            }

            var arr    = extracted.array;
            var format = extracted.format;

            if (arr.length === 0) {
                this.jsonError = 'The JSON array is empty — there is nothing to convert.';
                return;
            }

            /* Flatten all rows */
            var self = this;
            var flatRows = arr.map(function(item) {
                if (item === null || typeof item !== 'object' || Array.isArray(item)) {
                    return { value: item === null ? '' : String(item) };
                }
                return self.flattenNested
                    ? self._flatten(item, '', self.flattenSep)
                    : self._shallowStringify(item);
            });

            /* Union of all keys (stable insertion order) */
            var seen = Object.create(null);
            var keys = [];
            flatRows.forEach(function(row) {
                Object.keys(row).forEach(function(k) {
                    if (!seen[k]) { seen[k] = true; keys.push(k); }
                });
            });

            /* Build CSV lines */
            var lines = [];
            if (this.includeHeaders) {
                lines.push(keys.map(function(k) { return self._esc(k); }).join(self.delimiter));
            }
            flatRows.forEach(function(row) {
                lines.push(keys.map(function(k) {
                    var v = row[k] !== undefined ? row[k] : '';
                    return self._esc(v === null ? '' : String(v));
                }).join(self.delimiter));
            });

            this.csvOutput = lines.join('\r\n');
            this.stats     = { rows: arr.length, cols: keys.length, format: format };
            this.jsonError = '';
            this.jsonValid = true;
            this.phase     = 'done';

            /* Scroll to output on mobile */
            if (window.innerWidth < 1024) {
                var el = document.getElementById('output-col');
                if (el) setTimeout(function() {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 80);
            }
        },

        /* ══════════════════════════════════
           HELPERS — extraction & flattening
        ══════════════════════════════════ */

        _extractArray(data) {
            /* Case 1: top-level array */
            if (Array.isArray(data)) {
                return { array: data, format: 'Array of objects' };
            }

            /* Case 2: plain object */
            if (typeof data === 'object' && data !== null) {
                /* Find array-valued properties */
                var arrKeys = Object.keys(data).filter(function(k) {
                    return Array.isArray(data[k]);
                });

                if (arrKeys.length === 1) {
                    return { array: data[arrKeys[0]], format: 'data["' + arrKeys[0] + '"] array' };
                }
                if (arrKeys.length > 1) {
                    /* Pick the largest array */
                    arrKeys.sort(function(a, b) { return data[b].length - data[a].length; });
                    var key = arrKeys[0];
                    return { array: data[key], format: 'data["' + key + '"] (largest array, ' + arrKeys.length + ' candidates)' };
                }
                /* Single object → 1-row CSV */
                return { array: [data], format: 'Single object' };
            }

            return null;
        },

        _flatten(obj, prefix, sep) {
            var result = {};
            var self   = this;
            Object.keys(obj).forEach(function(key) {
                var fullKey = prefix ? prefix + sep + key : key;
                var val     = obj[key];

                if (val !== null && typeof val === 'object' && !Array.isArray(val)) {
                    /* Nested object — recurse */
                    var nested = self._flatten(val, fullKey, sep);
                    Object.keys(nested).forEach(function(nk) { result[nk] = nested[nk]; });

                } else if (Array.isArray(val)) {
                    if (self.arrayHandling === 'join') {
                        result[fullKey] = val.map(function(v) {
                            if (v === null) return '';
                            return typeof v === 'object' ? JSON.stringify(v) : String(v);
                        }).join('; ');

                    } else if (self.arrayHandling === 'first') {
                        result[fullKey] = val.length > 0
                            ? (val[0] === null ? '' : (typeof val[0] === 'object' ? JSON.stringify(val[0]) : String(val[0])))
                            : '';

                    } else { /* json */
                        result[fullKey] = JSON.stringify(val);
                    }

                } else {
                    result[fullKey] = val === null ? '' : val;
                }
            });
            return result;
        },

        _shallowStringify(obj) {
            var result = {};
            Object.keys(obj).forEach(function(key) {
                var val = obj[key];
                if (typeof val === 'object' && val !== null) {
                    result[key] = JSON.stringify(val);
                } else {
                    result[key] = val === null ? '' : val;
                }
            });
            return result;
        },

        /* CSV-escape a single cell value */
        _esc(val) {
            var s     = String(val === null || val === undefined ? '' : val);
            var delim = this.delimiter;
            if (this.quoteAll
                || s.indexOf(delim) !== -1
                || s.indexOf('"') !== -1
                || s.indexOf('\n') !== -1
                || s.indexOf('\r') !== -1) {
                return '"' + s.replace(/"/g, '""') + '"';
            }
            return s;
        },

        /* Simple single-line CSV parser for preview */
        _splitLine(line) {
            var delim  = this.delimiter;
            var fields = [];
            var field  = '';
            var inQ    = false;
            for (var i = 0; i < line.length; i++) {
                var ch = line[i];
                if (inQ) {
                    if (ch === '"' && line[i+1] === '"') { field += '"'; i++; }
                    else if (ch === '"') { inQ = false; }
                    else { field += ch; }
                } else {
                    if (ch === '"') { inQ = true; }
                    else if (ch === delim) { fields.push(field); field = ''; }
                    else { field += ch; }
                }
            }
            fields.push(field);
            return fields;
        },

        /* ══════════════════════════════════
           FORMAT UTILITIES
        ══════════════════════════════════ */

        prettyPrint() {
            try {
                var parsed = JSON.parse(this.jsonInput);
                this.jsonInput = JSON.stringify(parsed, null, 2);
                this.jsonError = '';
                this.jsonValid = true;
            } catch(e) {
                this.jsonError = 'Cannot format: ' + e.message;
            }
        },

        minify() {
            try {
                var parsed = JSON.parse(this.jsonInput);
                this.jsonInput = JSON.stringify(parsed);
                this.jsonError = '';
                this.jsonValid = true;
            } catch(e) {
                this.jsonError = 'Cannot minify: ' + e.message;
            }
        },

        loadSample() {
            this.jsonInput = this.SAMPLE;
            this.jsonError = '';
            this.jsonValid = true;
        },

        /* ══════════════════════════════════
           ACTIONS
        ══════════════════════════════════ */

        clearAll() {
            this.jsonInput  = '';
            this.csvOutput  = '';
            this.jsonError  = '';
            this.jsonValid  = false;
            this.phase      = 'idle';
            this.stats      = { rows: 0, cols: 0, format: '' };
        },

        clearOutput() {
            this.csvOutput = '';
            this.phase     = 'idle';
            this.stats     = { rows: 0, cols: 0, format: '' };
        },

        async copyCsv() {
            if (!this.csvOutput) return;
            try {
                await navigator.clipboard.writeText(this.csvOutput);
            } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = this.csvOutput;
                ta.style.position = 'fixed';
                ta.style.opacity  = '0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.copyFlash = true;
            setTimeout(function() { self.copyFlash = false; }, 1800);
        },

        downloadCsv() {
            if (!this.csvOutput) return;
            /* Add UTF-8 BOM so Excel opens it correctly */
            var blob = new Blob(['﻿' + this.csvOutput], { type: 'text/csv;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = 'converted.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        formatBytes(b) {
            if (b < 1024)    return b + ' B';
            if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
            return (b / 1048576).toFixed(1) + ' MB';
        },
    };
}
</script>
@endpush
