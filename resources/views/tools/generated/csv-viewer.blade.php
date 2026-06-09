@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Input tabs ── */
.inp-tab {
    flex: 1; display: flex; align-items: center; justify-content: center;
    gap: .4rem; padding: .65rem 1rem;
    border: 2px solid; border-radius: 1rem;
    font-size: .85rem; font-weight: 600;
    cursor: pointer; transition: all .13s;
}

/* ── Drop zone ── */
.drop-zone {
    border: 2.5px dashed #cbd5e1;
    border-radius: 1.25rem;
    padding: 3.5rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
}
.drop-zone.drag-over { border-color: #4f46e5; background: #eef2ff; }

/* ── Table ── */
.csv-table { width: 100%; border-collapse: collapse; font-size: .8rem; }

.csv-table thead tr {
    background: #f8fafc;
    position: sticky; top: 0; z-index: 2;
}
.csv-table th {
    padding: .65rem .85rem;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    user-select: none;
    font-weight: 700; font-size: .72rem;
    text-transform: uppercase; letter-spacing: .05em;
    color: #475569;
    cursor: pointer;
    transition: background .12s;
}
.csv-table th:hover { background: #f1f5f9; }
.csv-table th.sorted { background: #eef2ff; color: #4f46e5; }
.csv-table th.num    { text-align: right; }
.csv-table th.rn     { width: 3rem; cursor: default; color: #94a3b8; }

.csv-table td {
    padding: .55rem .85rem;
    border-bottom: 1px solid #f1f5f9;
    max-width: 260px;
    vertical-align: top;
    color: #374151;
}
.csv-table td.num    { text-align: right; font-variant-numeric: tabular-nums; color: #1e40af; }
.csv-table td.rn     { color: #94a3b8; font-size: .7rem; font-variant-numeric: tabular-nums; text-align: right; }
.csv-table td .cell-txt { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 240px; }

.csv-table tbody tr:hover td { background: #f8fafc; }
.csv-table tbody tr:nth-child(even) td { background: #fafafa; }
.csv-table tbody tr:nth-child(even):hover td { background: #f1f5f9; }
.csv-table tbody tr.csv-no-data td { text-align:center; padding:2.5rem; color:#94a3b8; }

/* ── Search highlight ── */
.csv-table mark { background: #fef08a; color: #713f12; border-radius: 2px; padding: 0 1px; }

/* ── Pagination button ── */
.pg-btn {
    min-width: 2rem; height: 2rem; padding: 0 .5rem;
    border-radius: .5rem; border: 1.5px solid;
    font-size: .75rem; font-weight: 600;
    cursor: pointer; transition: all .12s;
    display: inline-flex; align-items: center; justify-content: center;
}

/* ── Stat chip ── */
.stat-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .4rem .9rem; border-radius: 9999px;
    background: #f1f5f9; font-size: .8rem;
    font-weight: 500; color: #475569;
}

/* ── Column picker dropdown ── */
.col-picker {
    position: absolute; top: calc(100% + .5rem); right: 0; z-index: 30;
    background: white; border: 1.5px solid #e2e8f0;
    border-radius: 1rem; padding: 1rem;
    box-shadow: 0 10px 40px rgba(0,0,0,.12);
    min-width: 200px; max-width: 320px;
    max-height: 340px; overflow-y: auto;
}

/* ── Sort arrow ── */
.sort-arrow { display: inline-block; margin-left: .3rem; opacity: .4; font-size: .65rem; }
.sort-arrow.active { opacity: 1; color: #4f46e5; }

/* ── Loading spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner { animation: spin .7s linear infinite; display: inline-block; }

/* ── Flash ── */
@keyframes flashGreen {
    0%,100% { background:#f0fdf4; color:#166534; border-color:#86efac; }
    50%      { background:#dcfce7; color:#14532d; }
}
.copy-flash { animation: flashGreen .6s ease 2; }

/* ── Col picker scrollbar ── */
.col-picker::-webkit-scrollbar { width: 4px; }
.col-picker::-webkit-scrollbar-track { background: transparent; }
.col-picker::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }

/* ── Table scroll hint on mobile ── */
@media (max-width:640px) {
    .table-scroll-hint { display: block; }
}
.table-scroll-hint { display: none; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="csvViewer()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        {{-- ════════════════════════════════
             INPUT PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'input' || phase === 'loading'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="max-w-2xl mx-auto space-y-4">

            {{-- Mode tabs --}}
            <div class="flex gap-2">
                <button type="button"
                        @click="inputTab = 'upload'"
                        class="inp-tab"
                        :class="inputTab === 'upload'
                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                    📂 Upload File
                </button>
                <button type="button"
                        @click="inputTab = 'paste'"
                        class="inp-tab"
                        :class="inputTab === 'paste'
                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                    📋 Paste Text
                </button>
            </div>

            {{-- Upload tab --}}
            <div x-show="inputTab === 'upload'" class="card p-5">
                <div class="drop-zone"
                     :class="dragging ? 'drag-over' : ''"
                     @dragenter.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @dragover.prevent
                     @drop.prevent="handleDrop($event)"
                     @click="$refs.fileInp.click()">
                    <input type="file"
                           x-ref="fileInp"
                           accept=".csv,.tsv,.txt"
                           class="hidden"
                           @change="handleFileInput($event)">
                    <p class="text-4xl mb-3" x-text="dragging ? '📂' : '📁'"></p>
                    <p class="text-sm font-semibold text-gray-700 mb-1">
                        <span x-show="!dragging">Drop your CSV here, or click to browse</span>
                        <span x-show="dragging" class="text-indigo-600">Release to load!</span>
                    </p>
                    <p class="text-xs text-gray-400">Supported: .csv · .tsv · .txt — up to 50 MB</p>
                </div>

                {{-- Error in upload tab --}}
                <div x-show="error && inputTab === 'upload'"
                     x-transition
                     class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-start gap-2">
                    <span class="shrink-0 mt-0.5">❌</span>
                    <span x-text="error"></span>
                </div>
            </div>

            {{-- Paste tab --}}
            <div x-show="inputTab === 'paste'" class="card p-5 space-y-4">
                <div>
                    <label class="form-label">Paste CSV Content</label>
                    <textarea x-model="pasteText"
                              rows="10"
                              placeholder="name,age,city&#10;Alice,30,New York&#10;Bob,25,London&#10;..."
                              class="form-input font-mono text-xs resize-y leading-relaxed"></textarea>
                    <p class="form-help mt-1.5"
                       x-text="pasteText ? pasteText.split('\n').length + ' lines' : 'Paste comma-separated, semicolon-separated, or tab-separated data'">
                    </p>
                </div>

                <div class="flex gap-3 flex-wrap">
                    <button type="button"
                            @click="loadPasted()"
                            :disabled="!pasteText.trim() || phase === 'loading'"
                            class="btn btn-primary flex-1">
                        <span x-show="phase === 'loading'" class="spinner">⏳</span>
                        <span x-show="phase !== 'loading'">Parse CSV</span>
                    </button>
                    <button type="button"
                            @click="loadSample()"
                            class="btn btn-secondary">
                        📄 Load Sample
                    </button>
                    <button type="button"
                            @click="pasteText = ''"
                            x-show="pasteText"
                            class="btn btn-secondary">
                        Clear
                    </button>
                </div>

                <div x-show="error && inputTab === 'paste'"
                     x-transition
                     class="p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-start gap-2">
                    <span class="shrink-0">❌</span>
                    <span x-text="error"></span>
                </div>
            </div>

            {{-- Parse options --}}
            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Parse Options</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label text-xs">Delimiter</label>
                        <select x-model="delimiter" class="form-input text-sm">
                            <option value="auto">Auto-detect</option>
                            <option value=",">Comma (,)</option>
                            <option value=";">Semicolon (;)</option>
                            <option value="&#9;">Tab (\t)</option>
                            <option value="|">Pipe (|)</option>
                        </select>
                    </div>
                    <div class="flex flex-col justify-end">
                        <label class="flex items-center gap-2 cursor-pointer select-none pb-1">
                            <div class="relative">
                                <input type="checkbox" x-model="hasHeaders" class="sr-only">
                                <div class="w-9 h-5 rounded-full transition-colors"
                                     :class="hasHeaders ? 'bg-indigo-500' : 'bg-gray-300'"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"
                                     :class="hasHeaders ? 'translate-x-4' : 'translate-x-0'"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">First row as headers</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Loading state --}}
            <div x-show="phase === 'loading'"
                 class="card p-8 text-center">
                <p class="text-3xl mb-3 spinner">⏳</p>
                <p class="text-sm font-medium text-gray-700">Parsing CSV…</p>
                <p class="text-xs text-gray-400 mt-1">Large files may take a moment</p>
            </div>

        </div>

        {{-- ════════════════════════════════
             LOADED PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'loaded'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="space-y-4">

            {{-- Stats bar --}}
            <div class="card p-4">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="stat-chip">
                        <span>📄</span>
                        <span class="font-semibold text-gray-700 max-w-[180px] truncate" x-text="fileName || 'Pasted CSV'"></span>
                    </span>
                    <span class="stat-chip">
                        <span>📊</span>
                        <strong class="text-gray-700" x-text="rows.length.toLocaleString('en-US')"></strong>
                        <span>row<span x-show="rows.length !== 1">s</span></span>
                    </span>
                    <span class="stat-chip">
                        <span>📋</span>
                        <strong class="text-gray-700" x-text="headers.length"></strong>
                        <span>column<span x-show="headers.length !== 1">s</span></span>
                    </span>
                    <span class="stat-chip" x-show="fileSize > 0">
                        <span>💾</span>
                        <span x-text="formatBytes(fileSize)"></span>
                    </span>
                    <span class="stat-chip" x-show="delimiter !== 'auto'">
                        <span>⚙️</span>
                        <span x-text="delimLabel"></span>
                    </span>

                    {{-- Re-parse options --}}
                    <div class="ml-auto flex gap-2 flex-wrap">
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-500 font-medium">
                            <input type="checkbox"
                                   x-model="hasHeaders"
                                   @change="toggleHeaders()"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                            Header row
                        </label>
                        <button type="button"
                                @click="resetAll()"
                                class="btn btn-secondary btn-sm">
                            ✕ Close
                        </button>
                    </div>
                </div>

                {{-- Large file warning --}}
                <div x-show="rows.length > 50000"
                     class="mt-3 p-2.5 bg-amber-50 border border-amber-100 rounded-lg text-xs text-amber-800">
                    ⚠️ Large dataset (<span x-text="rows.length.toLocaleString()"></span> rows). Filtering and sorting may be slightly slower.
                </div>
            </div>

            {{-- Controls bar --}}
            <div class="card p-3">
                <div class="flex gap-2 flex-wrap items-center">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[180px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="search"
                               x-model="searchQuery"
                               @input="currentPage = 1"
                               placeholder="Search all columns…"
                               class="form-input pl-9 pr-4 text-sm">
                    </div>

                    {{-- Rows per page --}}
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-500 hidden sm:inline">Show</span>
                        <select x-model.number="pageSize"
                                @change="currentPage = 1"
                                class="form-input text-sm w-20">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="0">All</option>
                        </select>
                    </div>

                    {{-- Column visibility --}}
                    <div class="relative" x-data="{open:false}">
                        <button type="button"
                                @click="open = !open"
                                class="btn btn-secondary btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                            <span class="hidden sm:inline">Columns</span>
                            <span class="badge badge-gray ml-0.5" x-text="hiddenCount > 0 ? hiddenCount + ' hidden' : ''"></span>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition
                             class="col-picker">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Visible Columns</p>
                                <div class="flex gap-2">
                                    <button type="button" @click="showAllCols()" class="text-xs text-indigo-600 hover:underline">All</button>
                                    <button type="button" @click="hideAllCols()" class="text-xs text-gray-400 hover:underline">None</button>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <template x-for="(h, i) in headers" :key="i">
                                    <label class="flex items-center gap-2 cursor-pointer px-1 py-0.5 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox"
                                               :checked="visibleCols[i]"
                                               @change="toggleCol(i)"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                        <span class="text-xs text-gray-700 truncate" x-text="h || '(col ' + (i+1) + ')'"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Export --}}
                    <button type="button"
                            @click="exportCSV()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Export CSV</span>
                        <span x-show="searchQuery" class="badge badge-warning ml-0.5 text-[10px]">filtered</span>
                    </button>

                    {{-- Clear --}}
                    <button type="button"
                            @click="resetAll()"
                            class="btn btn-secondary btn-sm">
                        ✕ Clear
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="card overflow-hidden">
                <p class="table-scroll-hint text-xs text-center text-gray-400 py-1.5 border-b border-gray-100">
                    ← Scroll horizontally to see all columns →
                </p>

                {{-- Table info row --}}
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100 bg-gray-50 flex-wrap gap-2">
                    <p class="text-xs text-gray-500">
                        <template x-if="searchQuery">
                            <span>
                                <strong class="text-gray-800" x-text="filteredRows.length.toLocaleString()"></strong>
                                of <strong class="text-gray-800" x-text="rows.length.toLocaleString()"></strong> rows match
                                "<span class="text-indigo-600" x-text="searchQuery"></span>"
                            </span>
                        </template>
                        <template x-if="!searchQuery">
                            <span>
                                Showing <strong class="text-gray-800" x-text="rowRangeLabel"></strong>
                                of <strong class="text-gray-800" x-text="rows.length.toLocaleString()"></strong> rows
                            </span>
                        </template>
                    </p>
                    <p x-show="sortCol >= 0" class="text-xs text-indigo-600 font-medium">
                        Sorted by: <strong x-text="headers[sortCol]"></strong>
                        <span x-text="sortDir === 'asc' ? '↑' : '↓'"></span>
                        <button type="button" @click="sortCol = -1" class="ml-1 text-gray-400 hover:text-gray-600">✕</button>
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="csv-table">
                        <thead>
                            <tr>
                                <th class="rn">#</th>
                                <template x-for="(colIdx, hIdx) in visibleColIndices" :key="colIdx">
                                    <th @click="setSort(colIdx)"
                                        :class="{
                                            'sorted': sortCol === colIdx,
                                            'num':    colTypes[colIdx] === 'number',
                                        }">
                                        <span x-text="headers[colIdx] || '(col ' + (colIdx+1) + ')'"></span>
                                        <span class="sort-arrow"
                                              :class="sortCol === colIdx ? 'active' : ''"
                                              x-text="sortCol === colIdx ? (sortDir === 'asc' ? '↑' : '↓') : '↕'">
                                        </span>
                                    </th>
                                </template>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="pagedRows.length === 0">
                                <tr class="csv-no-data">
                                    <td :colspan="visibleColIndices.length + 1">
                                        <p class="text-4xl mb-2">🔍</p>
                                        <p class="font-medium text-gray-500" x-text="searchQuery ? 'No rows match your search.' : 'No data to display.'"></p>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(row, rIdx) in pagedRows" :key="rIdx">
                                <tr>
                                    <td class="rn" x-text="rowNumber(rIdx)"></td>
                                    <template x-for="colIdx in visibleColIndices" :key="colIdx">
                                        <td :class="{ 'num': colTypes[colIdx] === 'number' }">
                                            <span class="cell-txt"
                                                  :title="row[colIdx] || ''"
                                                  x-html="hl(row[colIdx] || '')"></span>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div x-show="totalPages > 1"
                     class="flex items-center justify-between px-4 py-3 border-t border-gray-100 flex-wrap gap-3">
                    <p class="text-xs text-gray-500">
                        Page <strong x-text="currentPage"></strong> of <strong x-text="totalPages"></strong>
                    </p>
                    <div class="flex items-center gap-1 flex-wrap">
                        {{-- Prev --}}
                        <button type="button"
                                @click="goPage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="pg-btn border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed">
                            ‹
                        </button>

                        {{-- Page numbers --}}
                        <template x-for="p in pageNumbers" :key="p + '-' + currentPage">
                            <span x-show="p === -1" class="px-1 text-gray-400 text-xs">…</span>
                            <button x-show="p !== -1"
                                    type="button"
                                    @click="goPage(p)"
                                    class="pg-btn"
                                    :class="p === currentPage
                                        ? 'border-brand-500 bg-brand-600 text-white'
                                        : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'"
                                    x-text="p">
                            </button>
                        </template>

                        {{-- Next --}}
                        <button type="button"
                                @click="goPage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="pg-btn border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed">
                            ›
                        </button>
                    </div>

                    {{-- Jump to page --}}
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-400">Go to</span>
                        <input type="number"
                               x-model.number="jumpPage"
                               @keydown.enter="goPage(jumpPage); jumpPage = ''"
                               :min="1" :max="totalPages"
                               placeholder="…"
                               class="form-input w-14 text-xs text-center py-1 px-2">
                        <button type="button"
                                @click="goPage(jumpPage); jumpPage = ''"
                                class="btn btn-secondary btn-sm py-1">Go</button>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Related Tools ── --}}
        @if($relatedTools->count())
        <div x-show="phase === 'input'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   CSV VIEWER  —  Alpine.js component
═══════════════════════════════════════════════════════════ */
function csvViewer() {
    return {
        /* ── App state ── */
        phase:       'input',   /* 'input' | 'loading' | 'loaded' */
        inputTab:    'upload',
        pasteText:   '',
        error:       '',
        dragging:    false,

        /* ── Parse options ── */
        delimiter:   'auto',
        hasHeaders:  true,

        /* ── Loaded data ── */
        fileName:    '',
        fileSize:    0,
        headers:     [],
        rows:        [],
        colTypes:    [],
        visibleCols: [],

        /* ── Table state ── */
        searchQuery: '',
        sortCol:     -1,
        sortDir:     'asc',
        pageSize:    25,
        currentPage: 1,
        jumpPage:    '',

        /* ── Sample CSV ── */
        SAMPLE: [
            'Name,Age,City,Country,Salary,Department,Join Date',
            'Alice Johnson,32,New York,USA,95000,Engineering,2021-03-15',
            'Bob Smith,28,London,UK,72000,Marketing,2022-07-01',
            'Carol White,45,Toronto,Canada,115000,Engineering,2018-11-20',
            'David Lee,37,Sydney,Australia,88000,Design,2020-05-10',
            'Emma Brown,29,Berlin,Germany,79000,Product,2023-01-08',
            'Frank Davis,52,Paris,France,125000,Management,2015-09-30',
            'Grace Kim,34,Tokyo,Japan,98000,Engineering,2019-04-22',
            'Henry Zhang,41,Singapore,Singapore,105000,Finance,2017-12-05',
            'Isabella Müller,26,Vienna,Austria,68000,Marketing,2023-08-14',
            'James Wilson,48,Chicago,USA,135000,Management,2014-02-28',
            'Keiko Tanaka,33,Osaka,Japan,91000,Design,2020-10-17',
            'Liam O\'Brien,30,Dublin,Ireland,77000,Engineering,2022-03-25',
            'Maria Garcia,38,Madrid,Spain,87000,Product,2018-06-11',
            'Nathan Clark,27,Boston,USA,82000,Engineering,2023-05-19',
            'Olivia Martin,44,Lyon,France,99000,Finance,2016-08-07',
            'Patrick Brown,35,Cape Town,South Africa,74000,Marketing,2021-11-03',
            'Quinn Adams,31,Vancouver,Canada,93000,Design,2020-02-14',
            'Rachel Green,40,Melbourne,Australia,108000,Engineering,2017-07-29',
            'Samuel Chen,29,Shanghai,China,86000,Product,2022-09-16',
        ].join('\n'),

        /* ══════════════════════════════════
           COMPUTED
        ══════════════════════════════════ */

        get filteredRows() {
            var q = this.searchQuery.toLowerCase().trim();
            if (!q) return this.rows;
            return this.rows.filter(function(row) {
                return row.some(function(cell) {
                    return cell.toLowerCase().indexOf(q) !== -1;
                });
            });
        },

        get sortedRows() {
            if (this.sortCol < 0) return this.filteredRows;
            var col  = this.sortCol;
            var dir  = this.sortDir;
            var type = this.colTypes[col] || 'text';

            return this.filteredRows.slice().sort(function(a, b) {
                var av = (a[col] || '').trim();
                var bv = (b[col] || '').trim();
                var cmp;

                if (type === 'number') {
                    var an = parseFloat(av.replace(/,/g, ''));
                    var bn = parseFloat(bv.replace(/,/g, ''));
                    if (isNaN(an) && isNaN(bn)) cmp = 0;
                    else if (isNaN(an)) cmp = 1;
                    else if (isNaN(bn)) cmp = -1;
                    else cmp = an - bn;
                } else {
                    cmp = av.localeCompare(bv, undefined, { numeric: true, sensitivity: 'base' });
                }

                return dir === 'asc' ? cmp : -cmp;
            });
        },

        get totalPages() {
            if (!this.pageSize) return 1;
            return Math.max(1, Math.ceil(this.sortedRows.length / this.pageSize));
        },

        get pagedRows() {
            if (!this.pageSize) return this.sortedRows;
            var start = (this.currentPage - 1) * this.pageSize;
            return this.sortedRows.slice(start, start + this.pageSize);
        },

        get pageNumbers() {
            var total = this.totalPages;
            var cur   = this.currentPage;

            if (total <= 9) {
                return Array.from({ length: total }, function(_, i) { return i + 1; });
            }

            var pages = [1];
            if (cur <= 4) {
                for (var i = 2; i <= Math.min(5, total - 1); i++) pages.push(i);
                if (total > 6) pages.push(-1);
            } else if (cur >= total - 3) {
                pages.push(-1);
                for (var i = Math.max(2, total - 4); i < total; i++) pages.push(i);
            } else {
                pages.push(-1);
                pages.push(cur - 1);
                pages.push(cur);
                pages.push(cur + 1);
                pages.push(-1);
            }

            if (pages[pages.length - 1] !== total) pages.push(total);
            return pages;
        },

        get visibleColIndices() {
            var self = this;
            return this.headers.map(function(_, i) { return i; })
                .filter(function(i) { return self.visibleCols[i]; });
        },

        get hiddenCount() {
            return this.visibleCols.filter(function(v) { return !v; }).length;
        },

        get rowRangeLabel() {
            var total = this.sortedRows.length;
            if (!this.pageSize || total === 0) return total.toLocaleString('en-US');
            var start = (this.currentPage - 1) * this.pageSize + 1;
            var end   = Math.min(this.currentPage * this.pageSize, total);
            return start.toLocaleString('en-US') + '–' + end.toLocaleString('en-US');
        },

        get delimLabel() {
            var map = { ',':'Comma', ';':'Semicolon', '\t':'Tab', '|':'Pipe' };
            return 'Delimiter: ' + (map[this.delimiter] || 'Auto');
        },

        /* ══════════════════════════════════
           INIT
        ══════════════════════════════════ */

        init() { /* nothing to pre-load */ },

        /* ══════════════════════════════════
           FILE HANDLING
        ══════════════════════════════════ */

        handleDrop(e) {
            this.dragging = false;
            var file = e.dataTransfer.files[0];
            if (file) this._readFile(file);
        },

        handleFileInput(e) {
            var file = e.target.files[0];
            if (file) this._readFile(file);
            e.target.value = '';
        },

        _readFile(file) {
            this.error = '';

            /* Validate extension */
            var name = file.name.toLowerCase();
            if (!name.endsWith('.csv') && !name.endsWith('.tsv') && !name.endsWith('.txt')) {
                this.error = 'Invalid file type. Please upload a .csv, .tsv, or .txt file.';
                return;
            }

            /* Warn on very large files */
            if (file.size > 52428800) { /* 50 MB */
                this.error = 'File is too large (max 50 MB). Please use a smaller file.';
                return;
            }

            this.fileName = file.name;
            this.fileSize = file.size;

            var self = this;
            var reader = new FileReader();
            reader.onload = function(ev) {
                self._triggerLoad(ev.target.result);
            };
            reader.onerror = function() {
                self.error = 'Could not read the file. Please try again.';
            };
            reader.readAsText(file, 'UTF-8');
        },

        loadPasted() {
            var txt = this.pasteText.trim();
            if (!txt) {
                this.error = 'Please paste some CSV content first.';
                return;
            }
            this.fileName = '';
            this.fileSize = 0;
            this._triggerLoad(txt);
        },

        loadSample() {
            this.pasteText = this.SAMPLE;
            this.inputTab  = 'paste';
        },

        _triggerLoad(text) {
            var self = this;
            this.error = '';
            this.phase = 'loading';
            /* Defer to let the loading state paint first */
            setTimeout(function() {
                try {
                    self._parseAndApply(text);
                } catch(e) {
                    self.phase = 'input';
                    self.error = 'Parse error: ' + (e.message || 'Unknown error. Check your CSV format.');
                }
            }, 60);
        },

        /* ══════════════════════════════════
           CSV PARSING
        ══════════════════════════════════ */

        _parseAndApply(text) {
            /* Strip UTF-8 BOM */
            text = text.replace(/^﻿/, '');

            if (!text.trim()) {
                this.phase = 'input';
                this.error = 'The file is empty.';
                return;
            }

            /* Detect delimiter */
            var delim = this.delimiter === 'auto' ? this._detectDelim(text) : this.delimiter;

            /* Parse */
            var allRows = this._parseCSV(text, delim);

            if (allRows.length === 0) {
                this.phase = 'input';
                this.error = 'No data found. Check that the file is a valid CSV.';
                return;
            }

            /* Normalise row lengths */
            var maxCols = allRows.reduce(function(m, r) { return Math.max(m, r.length); }, 0);
            allRows = allRows.map(function(r) {
                while (r.length < maxCols) r.push('');
                return r;
            });

            /* Split headers / data */
            var headers, rows;
            if (this.hasHeaders && allRows.length > 0) {
                headers = allRows[0];
                rows    = allRows.slice(1);
            } else {
                headers = Array.from({ length: maxCols }, function(_, i) { return 'Column ' + (i + 1); });
                rows    = allRows;
            }

            if (rows.length === 0) {
                this.phase = 'input';
                this.error = 'The file only contains a header row — no data rows found.';
                return;
            }

            /* Detect column types */
            var colTypes = headers.map(function(_, ci) {
                var vals = rows.map(function(r) { return r[ci] || ''; })
                               .filter(function(v) { return v.trim() !== ''; });
                if (vals.length === 0) return 'text';
                var numeric = vals.filter(function(v) {
                    return !isNaN(parseFloat(v.replace(/,/g, ''))) && isFinite(v.replace(/,/g, ''));
                });
                return numeric.length / vals.length >= 0.8 ? 'number' : 'text';
            });

            /* Apply */
            this.headers     = headers;
            this.rows        = rows;
            this.colTypes    = colTypes;
            this.visibleCols = headers.map(function() { return true; });
            this.searchQuery = '';
            this.sortCol     = -1;
            this.sortDir     = 'asc';
            this.currentPage = 1;
            this.pageSize    = 25;
            this.phase       = 'loaded';
        },

        _detectDelim(text) {
            var sample = text.split('\n').filter(function(l) { return l.trim(); }).slice(0, 5);
            var counts = { ',': 0, ';': 0, '\t': 0, '|': 0 };

            sample.forEach(function(line) {
                var inQ = false;
                for (var i = 0; i < line.length; i++) {
                    var ch = line[i];
                    if (ch === '"') { inQ = !inQ; continue; }
                    if (!inQ && counts[ch] !== undefined) counts[ch]++;
                }
            });

            var best = ',', bestN = -1;
            Object.keys(counts).forEach(function(d) {
                if (counts[d] > bestN) { bestN = counts[d]; best = d; }
            });
            return best;
        },

        _parseCSV(text, delim) {
            var rows  = [];
            var row   = [];
            var field = '';
            var inQ   = false;
            var i     = 0;
            var len   = text.length;

            while (i < len) {
                var ch = text[i];

                if (inQ) {
                    if (ch === '"') {
                        if (i + 1 < len && text[i + 1] === '"') {
                            /* Escaped quote */
                            field += '"';
                            i += 2;
                        } else {
                            inQ = false;
                            i++;
                        }
                    } else {
                        field += ch;
                        i++;
                    }
                    continue;
                }

                if (ch === '"') {
                    inQ = true;
                    i++;
                } else if (ch === delim) {
                    row.push(field);
                    field = '';
                    i++;
                } else if (ch === '\r') {
                    row.push(field);
                    rows.push(row);
                    row   = [];
                    field = '';
                    i++;
                    if (i < len && text[i] === '\n') i++;
                } else if (ch === '\n') {
                    row.push(field);
                    rows.push(row);
                    row   = [];
                    field = '';
                    i++;
                } else {
                    field += ch;
                    i++;
                }
            }

            /* Flush last field/row */
            if (field !== '' || row.length > 0) {
                row.push(field);
                rows.push(row);
            }

            /* Drop trailing empty rows */
            while (rows.length > 0) {
                var last = rows[rows.length - 1];
                if (last.length === 1 && last[0] === '') rows.pop();
                else break;
            }

            return rows;
        },

        /* ══════════════════════════════════
           TABLE ACTIONS
        ══════════════════════════════════ */

        setSort(colIdx) {
            if (this.sortCol === colIdx) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = colIdx;
                this.sortDir = 'asc';
            }
            this.currentPage = 1;
        },

        goPage(p) {
            p = parseInt(p, 10);
            if (isNaN(p)) return;
            this.currentPage = Math.max(1, Math.min(p, this.totalPages));
        },

        rowNumber(rIdx) {
            if (!this.pageSize) return rIdx + 1;
            return (this.currentPage - 1) * this.pageSize + rIdx + 1;
        },

        toggleCol(i) {
            var c = this.visibleCols.slice();
            c[i] = !c[i];
            this.visibleCols = c;
        },

        showAllCols() {
            this.visibleCols = this.headers.map(function() { return true; });
        },

        hideAllCols() {
            this.visibleCols = this.headers.map(function() { return false; });
        },

        toggleHeaders() {
            /* Re-apply headers toggle on existing data */
            if (!this._rawRows) return;
            var allRows = this._rawRows;
            if (this.hasHeaders) {
                this.headers = allRows[0] || [];
                this.rows    = allRows.slice(1);
            } else {
                var n = (allRows[0] || []).length;
                this.headers = Array.from({ length: n }, function(_, i) { return 'Column ' + (i + 1); });
                this.rows    = allRows;
            }
            this.visibleCols = this.headers.map(function() { return true; });
            this.currentPage = 1;
        },

        /* ══════════════════════════════════
           EXPORT
        ══════════════════════════════════ */

        exportCSV() {
            var visIdx = this.visibleColIndices;
            var self   = this;

            function esc(v) {
                var s = String(v);
                if (s.includes(',') || s.includes('"') || s.includes('\n') || s.includes('\r')) {
                    return '"' + s.replace(/"/g, '""') + '"';
                }
                return s;
            }

            var lines = [];
            /* Headers */
            lines.push(visIdx.map(function(ci) { return esc(self.headers[ci] || ''); }).join(','));
            /* Data */
            this.sortedRows.forEach(function(row) {
                lines.push(visIdx.map(function(ci) { return esc(row[ci] || ''); }).join(','));
            });

            var csv  = lines.join('\r\n');
            var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            var name = (this.fileName ? this.fileName.replace(/\.[^.]+$/, '') : 'export')
                     + (this.searchQuery ? '-filtered' : '')
                     + '.csv';
            a.href     = url;
            a.download = name;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        /* ══════════════════════════════════
           RESET
        ══════════════════════════════════ */

        resetAll() {
            this.phase       = 'input';
            this.error       = '';
            this.fileName    = '';
            this.fileSize    = 0;
            this.headers     = [];
            this.rows        = [];
            this.colTypes    = [];
            this.visibleCols = [];
            this.searchQuery = '';
            this.sortCol     = -1;
            this.sortDir     = 'asc';
            this.currentPage = 1;
            this._rawRows    = null;
        },

        /* ══════════════════════════════════
           HELPERS
        ══════════════════════════════════ */

        hl(text) {
            var q = this.searchQuery.trim();
            var s = String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
            if (!q) return s;
            var sq = q.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                      .replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return s.replace(new RegExp('(' + sq + ')', 'gi'), '<mark>$1</mark>');
        },

        formatBytes(bytes) {
            if (bytes < 1024)    return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            if (bytes < 1073741824) return (bytes / 1048576).toFixed(1) + ' MB';
            return (bytes / 1073741824).toFixed(2) + ' GB';
        },
    };
}
</script>
@endpush
