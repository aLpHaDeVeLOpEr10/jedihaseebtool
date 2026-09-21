@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)
@section('renders_own_content_sections', '1')
@section('renders_own_faqs', '1')

@section('content')
<style>
/* ══════════════════════════════════════════════════════════
   PDF Merger  —  prefix: pm-
   Brand: indigo #4f46e5 (brand-600) — red is reserved for errors
   Libraries: pdf-lib 1.17.1 (merge), pdf.js 3.11.174 (thumbnails)
   All processing is 100 % client-side — files are never uploaded.
══════════════════════════════════════════════════════════ */

/* Drop zone */
.pm-drop { border:2.5px dashed #c7d2fe; border-radius:1rem; padding:2.75rem 1.5rem; text-align:center; cursor:pointer; background:#eef2ff; transition:all .18s; user-select:none; }
.pm-drop:hover, .pm-drop.pm-hover { border-color:#4f46e5; background:#e0e7ff; }
.pm-drop:focus-visible { outline:3px solid rgba(79,70,229,.35); outline-offset:2px; }
.pm-drop-sm { padding:.9rem 1rem; border-width:2px; }
.pm-dz-icon { font-size:2.6rem; line-height:1; margin-bottom:.6rem; }
.pm-dz-title { font-size:1rem; font-weight:700; color:#374151; }
.pm-dz-sub { font-size:.8rem; color:#9ca3af; margin-top:.2rem; }

/* File rows */
.pm-list { display:flex; flex-direction:column; gap:.6rem; }
.pm-item { display:flex; gap:.85rem; align-items:stretch; border:1.5px solid #e5e7eb; border-radius:.9rem; background:#fff; padding:.65rem; transition:border-color .14s, box-shadow .14s, opacity .14s; }
.pm-item:hover { border-color:#a5b4fc; box-shadow:0 4px 12px rgba(79,70,229,.08); }
.pm-item.pm-dragging { opacity:.4; }
.pm-item.pm-over { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.18); }
.pm-item.pm-bad { border-color:#fecaca; background:#fef2f2; }

.pm-handle { display:flex; align-items:center; color:#d1d5db; cursor:grab; font-size:1.1rem; padding:0 .1rem; }
.pm-thumb { width:4.2rem; height:5.4rem; flex-shrink:0; border-radius:.45rem; border:1px solid #e5e7eb; background:#f8fafc; overflow:hidden; display:flex; align-items:center; justify-content:center; position:relative; }
.pm-thumb img { width:100%; height:100%; object-fit:contain; display:block; background:#fff; }
.pm-thumb-num { position:absolute; top:.2rem; left:.2rem; background:rgba(79,70,229,.9); color:#fff; font-size:.6rem; font-weight:800; padding:.05rem .4rem; border-radius:9999px; }

.pm-body { flex:1; min-width:0; display:flex; flex-direction:column; gap:.4rem; }
.pm-name { font-size:.85rem; font-weight:700; color:#1f2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pm-meta { font-size:.7rem; color:#9ca3af; }
.pm-controls { display:flex; flex-wrap:wrap; gap:.4rem; align-items:center; }
.pm-range { flex:1; min-width:8rem; padding:.35rem .6rem; border:1.5px solid #e5e7eb; border-radius:.55rem; font-size:.75rem; color:#374151; outline:none; }
.pm-range:focus { border-color:#818cf8; box-shadow:0 0 0 3px rgba(129,140,248,.15); }
.pm-range.pm-invalid { border-color:#f87171; background:#fef2f2; }
.pm-field-err { font-size:.68rem; color:#b91c1c; }

.pm-side { display:flex; flex-direction:column; gap:.3rem; justify-content:center; }
.pm-icon-btn { width:1.9rem; height:1.9rem; border-radius:.5rem; border:1.5px solid #e5e7eb; background:#fff; color:#6b7280; font-size:.8rem; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .12s; }
.pm-icon-btn:hover:not(:disabled) { border-color:#818cf8; color:#4f46e5; background:#eef2ff; }
.pm-icon-btn:disabled { opacity:.35; cursor:not-allowed; }
.pm-icon-btn.pm-del:hover { border-color:#ef4444; color:#fff; background:#ef4444; }
.pm-chip { display:inline-flex; align-items:center; gap:.25rem; padding:.3rem .55rem; border-radius:.5rem; border:1.5px solid #e5e7eb; background:#fff; font-size:.7rem; font-weight:700; color:#6b7280; cursor:pointer; white-space:nowrap; }
.pm-chip:hover { border-color:#818cf8; color:#4f46e5; }

/* Toolbar */
.pm-toolbar { display:flex; flex-wrap:wrap; gap:.4rem; align-items:center; }
.pm-tbtn { padding:.35rem .65rem; border-radius:.55rem; border:1.5px solid #e5e7eb; background:#fff; font-size:.72rem; font-weight:700; color:#4b5563; cursor:pointer; transition:all .12s; }
.pm-tbtn:hover { border-color:#818cf8; color:#4f46e5; }

/* Settings */
.pm-input { width:100%; padding:.55rem .8rem; border:1.5px solid #e5e7eb; border-radius:.7rem; font-size:.85rem; color:#374151; outline:none; }
.pm-input:focus { border-color:#818cf8; box-shadow:0 0 0 3px rgba(129,140,248,.15); }
.pm-suffix-wrap { display:flex; align-items:stretch; }
.pm-suffix-wrap .pm-input { border-radius:.7rem 0 0 .7rem; }
.pm-suffix { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1.5px solid #e5e7eb; border-left:none; border-radius:0 .7rem .7rem 0; font-size:.8rem; font-weight:700; color:#6b7280; }
.pm-check { display:flex; gap:.6rem; align-items:flex-start; padding:.6rem .75rem; border:1.5px solid #e5e7eb; border-radius:.75rem; cursor:pointer; }
.pm-check:hover { border-color:#a5b4fc; }
.pm-check input { margin-top:.15rem; width:1rem; height:1rem; accent-color:#4f46e5; flex-shrink:0; }

/* Merge button */
.pm-go { width:100%; padding:.95rem 1.5rem; border-radius:.9rem; border:none; font-size:1rem; font-weight:800; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.5rem; background:linear-gradient(135deg,#3730a3,#4f46e5,#6366f1); box-shadow:0 4px 14px rgba(79,70,229,.35); transition:all .16s; }
.pm-go:hover:not(:disabled) { box-shadow:0 6px 20px rgba(79,70,229,.5); transform:translateY(-1px); }
.pm-go:disabled { opacity:.45; cursor:not-allowed; box-shadow:none; transform:none; }

/* Progress */
.pm-prog { width:100%; height:.6rem; border-radius:9999px; background:#e0e7ff; overflow:hidden; }
.pm-prog-fill { height:100%; border-radius:9999px; background:linear-gradient(90deg,#3730a3,#6366f1); transition:width .2s ease; }

/* Alerts */
.pm-error { display:flex; gap:.5rem; align-items:flex-start; padding:.7rem .9rem; border-radius:.75rem; background:#fef2f2; border:1.5px solid #fecaca; font-size:.8rem; color:#991b1b; font-weight:500; }
.pm-privacy { display:flex; gap:.5rem; align-items:center; padding:.5rem .85rem; border-radius:.75rem; background:#eef2ff; border:1px solid #c7d2fe; font-size:.75rem; color:#3730a3; font-weight:500; }
.pm-done { border:1.5px solid #86efac; background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-radius:1rem; padding:1.1rem 1.2rem; }
.pm-dl { display:inline-flex; align-items:center; gap:.45rem; padding:.65rem 1.2rem; border-radius:.75rem; background:#16a34a; color:#fff; font-weight:800; font-size:.9rem; text-decoration:none; border:none; cursor:pointer; }
.pm-dl:hover { background:#15803d; color:#fff; }

.pm-pill { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .6rem; border-radius:9999px; font-size:.68rem; font-weight:700; background:#eef2ff; color:#3730a3; border:1.5px solid #c7d2fe; }

/* Utilities used on this page that are not in the prebuilt Tailwind bundle */
.w-14 { width:3.5rem; } .h-14 { height:3.5rem; }
.space-y-2\.5 > :not([hidden]) ~ :not([hidden]) { margin-top:.625rem; }
.hover\:bg-brand-100:hover { background-color:#e0e9ff; }

@keyframes pmSpin { to { transform:rotate(360deg); } }
.pm-spin { display:inline-block; width:1em; height:1em; border-radius:50%; border:2px solid currentColor; border-top-color:transparent; animation:pmSpin .6s linear infinite; flex-shrink:0; }

@media (max-width:520px) {
  .pm-handle { display:none; }
  .pm-thumb { width:3.3rem; height:4.3rem; }
  .pm-drop { padding:1.75rem 1rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="text-3xl w-14 h-14 flex items-center justify-center rounded-xl" style="background:#e0e7ff">
          {{ $tool->icon ?? '📄' }}
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">Combine multiple PDF files into one document. Reorder files, pick page ranges, rotate pages — free, private and entirely in your browser.</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]"/>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      {{-- ── Main column ── --}}
      <div class="lg:col-span-2 space-y-5" x-data="pmTool()" x-init="init()">

        {{-- ═══ FILES CARD ═══ --}}
        <div class="card p-6 space-y-4"
             @dragover.prevent="onZoneDragOver($event)"
             @dragleave.prevent="zoneHover = false"
             @drop.prevent="onZoneDrop($event)">

          <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-lg font-semibold text-gray-900">PDF Files</h2>
            <div x-show="files.length > 0" class="flex flex-wrap items-center gap-2">
              <span class="pm-pill" x-text="files.length + ' file' + (files.length !== 1 ? 's' : '')"></span>
              <span class="pm-pill" x-show="totalPages > 0" x-text="totalPages + ' page' + (totalPages !== 1 ? 's' : '') + ' selected'"></span>
              <button type="button" @click="clearAll()" class="text-xs font-semibold text-red-500 hover:text-red-700">Clear All</button>
            </div>
          </div>

          <div class="pm-privacy">
            <span>🔒</span>
            <span>Your PDFs never leave your device — merging happens entirely in your browser.</span>
          </div>

          {{-- Empty-state drop zone --}}
          <div x-show="files.length === 0"
               class="pm-drop" :class="{ 'pm-hover': zoneHover }"
               @click="$refs.fileInput.click()"
               role="button" tabindex="0"
               @keydown.enter.prevent="$refs.fileInput.click()"
               @keydown.space.prevent="$refs.fileInput.click()"
               aria-label="Add PDF files — click or drag and drop">
            <div class="pm-dz-icon">📑</div>
            <p class="pm-dz-title">Drag &amp; drop PDF files here</p>
            <p class="pm-dz-sub">or click to browse · up to 50 files · 200 MB per file</p>
          </div>

          {{-- File list --}}
          <div x-show="files.length > 0" class="space-y-3">

            <div class="pm-toolbar">
              <span class="text-xs text-gray-400 mr-1">⠿ Drag to reorder ·</span>
              <button type="button" class="pm-tbtn" @click="sortByName(1)">A → Z</button>
              <button type="button" class="pm-tbtn" @click="sortByName(-1)">Z → A</button>
              <button type="button" class="pm-tbtn" @click="reverseOrder()">⇅ Reverse</button>
            </div>

            <div class="pm-list">
              <template x-for="(f, idx) in files" :key="f.id">
                <div class="pm-item"
                     :class="{ 'pm-dragging': dragIdx === idx, 'pm-over': overIdx === idx && dragIdx !== null && dragIdx !== idx, 'pm-bad': f.status === 'error' }"
                     draggable="true"
                     @dragstart="onItemDragStart(idx, $event)"
                     @dragover.prevent.stop="onItemDragOver(idx, $event)"
                     @drop.prevent.stop="onItemDrop(idx, $event)"
                     @dragend="dragIdx = null; overIdx = null">

                  <div class="pm-handle" aria-hidden="true">⠿</div>

                  <div class="pm-thumb">
                    <span x-show="f.status === 'loading'" class="pm-spin" style="color:#818cf8;width:1.3rem;height:1.3rem"></span>
                    <img x-show="f.thumb" :src="f.thumb" :alt="'First page of ' + f.name" :style="'transform:rotate(' + f.rotation + 'deg)'">
                    <span x-show="f.status !== 'loading' && !f.thumb" class="text-2xl" x-text="f.status === 'error' ? '⚠️' : '📄'"></span>
                    <span class="pm-thumb-num" x-text="idx + 1"></span>
                  </div>

                  <div class="pm-body">
                    <p class="pm-name" :title="f.name" x-text="f.name"></p>
                    <p class="pm-meta">
                      <span x-text="formatSize(f.size)"></span>
                      <span x-show="f.pages > 0" x-text="' · ' + f.pages + ' page' + (f.pages !== 1 ? 's' : '')"></span>
                      <span x-show="f.status === 'ready' && f.pages > 0 && selectedCount(f) !== f.pages" style="color:#4f46e5;font-weight:700" x-text="' · using ' + selectedCount(f)"></span>
                    </p>

                    <template x-if="f.status === 'error'">
                      <p class="pm-field-err" x-text="f.error"></p>
                    </template>

                    <template x-if="f.status === 'ready'">
                      <div class="space-y-1">
                        <div class="pm-controls">
                          <input type="text" class="pm-range" :class="{ 'pm-invalid': f.rangeError }"
                                 x-model="f.range" @input="validateRange(f)"
                                 :placeholder="'All pages (e.g. 1-3, 5, 8-' + f.pages + ')'"
                                 :aria-label="'Page range for ' + f.name">
                          <button type="button" class="pm-chip" @click="rotate(f)" :title="'Rotate pages of ' + f.name">
                            ↻ <span x-text="f.rotation + '°'"></span>
                          </button>
                        </div>
                        <p class="pm-field-err" x-show="f.rangeError" x-text="f.rangeError"></p>
                      </div>
                    </template>
                  </div>

                  <div class="pm-side">
                    <button type="button" class="pm-icon-btn" @click="move(idx, -1)" :disabled="idx === 0" :aria-label="'Move ' + f.name + ' up'">▲</button>
                    <button type="button" class="pm-icon-btn pm-del" @click="removeFile(f.id)" :aria-label="'Remove ' + f.name">✕</button>
                    <button type="button" class="pm-icon-btn" @click="move(idx, 1)" :disabled="idx === files.length - 1" :aria-label="'Move ' + f.name + ' down'">▼</button>
                  </div>
                </div>
              </template>
            </div>

            <div class="pm-drop pm-drop-sm" :class="{ 'pm-hover': zoneHover }"
                 @click="$refs.fileInput.click()" role="button" tabindex="0"
                 @keydown.enter.prevent="$refs.fileInput.click()"
                 @keydown.space.prevent="$refs.fileInput.click()"
                 aria-label="Add more PDF files">
              <span class="text-sm font-semibold" style="color:#4f46e5">＋ Add more PDFs</span>
            </div>
          </div>

          <input type="file" x-ref="fileInput" accept="application/pdf,.pdf" multiple class="hidden"
                 @change="onFileInput($event)" aria-hidden="true" tabindex="-1">

          <div x-show="fileError" x-transition role="alert" class="pm-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>
        </div>

        {{-- ═══ SETTINGS CARD ═══ --}}
        <div class="card p-6 space-y-4">
          <h2 class="text-lg font-semibold text-gray-900">Output Settings</h2>

          <div>
            <label class="form-label" for="pm-filename">File name</label>
            <div class="pm-suffix-wrap">
              <input id="pm-filename" type="text" x-model="outputName" class="pm-input" placeholder="merged" maxlength="120">
              <span class="pm-suffix">.pdf</span>
            </div>
          </div>

          <label class="pm-check">
            <input type="checkbox" x-model="blankBetween">
            <span>
              <span class="block text-sm font-semibold text-gray-800">Double-sided printing</span>
              <span class="block text-xs text-gray-400">Add a blank page after any file with an odd number of pages, so each file starts on a new sheet.</span>
            </span>
          </label>
        </div>

        {{-- ═══ MERGE CARD ═══ --}}
        <div class="card p-6 space-y-4">

          <div x-show="mergeError" x-transition role="alert" class="pm-error">
            <span>⚠</span><span x-text="mergeError"></span>
          </div>

          <div x-show="merging" x-transition>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
              <span x-text="progressLabel"></span>
              <span class="font-bold" style="color:#4f46e5" x-text="progress + '%'"></span>
            </div>
            <div class="pm-prog" role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
              <div class="pm-prog-fill" :style="'width:' + progress + '%'"></div>
            </div>
          </div>

          <template x-if="output && !merging">
            <div class="pm-done">
              <p class="text-base font-bold" style="color:#15803d">✅ Your PDF is ready</p>
              <p class="text-sm text-gray-600 mt-1">
                <strong x-text="output.name"></strong> ·
                <span x-text="output.pages + ' page' + (output.pages !== 1 ? 's' : '')"></span> ·
                <span x-text="formatSize(output.size)"></span>
              </p>
              <div class="flex flex-wrap items-center gap-3 mt-3">
                <a class="pm-dl" :href="output.url" :download="output.name">⬇ Download PDF</a>
                <a class="text-sm font-semibold" style="color:#16a34a" :href="output.url" target="_blank" rel="noopener">Open preview ↗</a>
              </div>
            </div>
          </template>

          <button type="button" class="pm-go" @click="merge()" :disabled="!canMerge">
            <template x-if="!merging">
              <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                <span x-text="mergeLabel"></span>
              </span>
            </template>
            <template x-if="merging">
              <span class="flex items-center gap-2"><span class="pm-spin"></span> Merging…</span>
            </template>
          </button>

          <p class="text-center text-xs text-gray-400">Files are merged top to bottom in the order shown above.</p>
        </div>

        {{-- ── Admin-editable content ── --}}
        @if($tool->long_description)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
          <div class="tool-prose">{!! nl2br(e($tool->long_description)) !!}</div>
        </div>
        @endif

        @foreach($tool->contents->where('is_visible', true) as $section)
        <div class="card p-6">
          @if($section->title)
          <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->title }}</h2>
          @endif
          <div class="tool-prose">{!! nl2br(e($section->content)) !!}</div>
        </div>
        @endforeach

        @if($tool->faqs->where('is_visible', true)->count() > 0)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
          <div class="space-y-3" x-data="{ open: null }">
            @foreach($tool->faqs->where('is_visible', true)->values() as $fi => $faq)
            <div class="border border-gray-100 rounded-xl overflow-hidden">
              <button type="button" @click="open = open === {{ $fi }} ? null : {{ $fi }}"
                      class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open === {{ $fi }} ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div x-show="open === {{ $fi }}" x-cloak class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">{{ $faq->answer }}</div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- /main col --}}

      {{-- ── Sidebar ── --}}
      <div class="space-y-5">

        <div class="card p-5">
          <h2 class="text-sm font-semibold text-gray-700 mb-3">How to Merge PDFs</h2>
          <ol class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="font-bold flex-shrink-0" style="color:#4f46e5">1.</span><span>Add two or more PDF files by dropping them in or clicking to browse.</span></li>
            <li class="flex gap-2"><span class="font-bold flex-shrink-0" style="color:#4f46e5">2.</span><span>Drag files (or use ▲ ▼) to set the order.</span></li>
            <li class="flex gap-2"><span class="font-bold flex-shrink-0" style="color:#4f46e5">3.</span><span>Optionally choose page ranges or rotate a file.</span></li>
            <li class="flex gap-2"><span class="font-bold flex-shrink-0" style="color:#4f46e5">4.</span><span>Click <strong>Merge</strong> and download your combined PDF.</span></li>
          </ol>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Page Range Examples</h3>
          <ul class="space-y-2 text-xs text-gray-600">
            <li><code class="font-mono font-bold" style="color:#3730a3">(empty)</code> — all pages</li>
            <li><code class="font-mono font-bold" style="color:#3730a3">1-3</code> — pages 1 to 3</li>
            <li><code class="font-mono font-bold" style="color:#3730a3">1, 4, 7</code> — specific pages</li>
            <li><code class="font-mono font-bold" style="color:#3730a3">5-</code> — page 5 to the end</li>
            <li><code class="font-mono font-bold" style="color:#3730a3">10-1</code> — pages in reverse order</li>
          </ul>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Good to Know</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="flex-shrink-0">🔒</span><span>Files are processed on your device and are <strong>never uploaded</strong>.</span></li>
            <li class="flex gap-2"><span class="flex-shrink-0">🔑</span><span>Password-protected PDFs must be unlocked before merging.</span></li>
            <li class="flex gap-2"><span class="flex-shrink-0">🔗</span><span>Links and text stay intact; bookmarks and fillable form fields may not carry over.</span></li>
            <li class="flex gap-2"><span class="flex-shrink-0">💾</span><span>Very large merges depend on your device's memory.</span></li>
          </ul>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="{{ route('categories.show', $tool->category) }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl">{{ $tool->category->icon }}</span>
            <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
          </a>
        </div>

        @if($relatedTools->count() > 0)
        <div class="card p-5">
          <h2 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h2>
          <div class="space-y-2">
            @foreach($relatedTools as $related)
            <a href="{{ route('tools.show', $related->slug) }}"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
              <span class="text-lg">{{ $related->icon }}</span>
              <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">{{ $related->name }}</span>
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
{{-- pdf-lib and pdf.js are ~3 MB combined. They are fetched on the first file
     the visitor adds, not on page load, so a visitor who only reads the page
     never downloads them. --}}
<script>
/* ─────────────────────────────────────────────────────────────
   PDF Merger — Alpine.js component (prefix: pm-)

   Flow:
     1. User adds PDFs → bytes read with File.arrayBuffer()
        (kept in a plain Map outside Alpine so large buffers are
        never wrapped in reactive proxies)
     2. pdf-lib loads each file to validate it and count pages;
        pdf.js renders a first-page thumbnail
     3. User orders files, sets page ranges / rotation
     4. merge(): PDFDocument.create() → copyPages() per file →
        save() → Blob URL → automatic download

   Security: files never leave the browser.
──────────────────────────────────────────────────────────── */
function pmTool() {
  var MAX_FILES       = 50;
  var MAX_FILE_BYTES  = 200 * 1024 * 1024;
  var MAX_TOTAL_BYTES = 750 * 1024 * 1024;
  var MAX_OUT_PAGES   = 10000;

  var store = new Map();   // id → Uint8Array
  var nextId = 1;

  return {
    files:        [],   // [{id, name, size, pages, thumb, status, error, range, rangeError, rotation}]
    fileError:    '',
    zoneHover:    false,
    dragIdx:      null,
    overIdx:      null,

    outputName:   'merged',
    blankBetween: false,

    merging:       false,
    progress:      0,
    progressLabel: '',
    mergeError:    '',
    output:        null,   // {url, name, pages, size}

    // ── Computed ──
    get readyFiles() {
      return this.files.filter(function (f) { return f.status === 'ready'; });
    },

    get totalPages() {
      var self = this;
      return this.readyFiles.reduce(function (n, f) { return n + self.selectedCount(f); }, 0);
    },

    get canMerge() {
      if (this.merging) return false;
      if (this.files.some(function (f) { return f.status === 'loading'; })) return false;
      if (this.readyFiles.some(function (f) { return f.rangeError; })) return false;
      return this.readyFiles.length >= 2 || (this.readyFiles.length === 1 && this._hasCustomisation(this.readyFiles[0]));
    },

    get mergeLabel() {
      var ready = this.readyFiles.length;
      if (this.files.some(function (f) { return f.status === 'loading'; })) return 'Reading files…';
      if (ready === 0) return 'Add PDF files to get started';
      if (ready === 1 && !this._hasCustomisation(this.readyFiles[0])) return 'Add at least one more PDF';
      if (this.readyFiles.some(function (f) { return f.rangeError; })) return 'Fix the page ranges above';
      return 'Merge ' + ready + ' PDF' + (ready !== 1 ? 's' : '') + ' (' + this.totalPages + ' pages)';
    },

    // ── Lifecycle ──
    init() {
      var self = this;
      window.addEventListener('beforeunload', function () { self._revokeOutput(); });
    },

    // ── Lazy library loading ──────────────────────────────────
    _libsPromise: null,

    _loadScript(src) {
      return new Promise(function (resolve, reject) {
        var existing = document.querySelector('script[src="' + src + '"]');
        if (existing) {
          if (existing.dataset.loaded === '1') return resolve();
          existing.addEventListener('load', function () { resolve(); });
          existing.addEventListener('error', function () { reject(new Error('Failed to load ' + src)); });
          return;
        }
        var s = document.createElement('script');
        s.src = src;
        s.async = true;
        s.onload = function () { s.dataset.loaded = '1'; resolve(); };
        s.onerror = function () { reject(new Error('Failed to load ' + src)); };
        document.head.appendChild(s);
      });
    },

    ensureLibs() {
      var self = this;
      if (window.PDFLib && window.pdfjsLib) return Promise.resolve();
      if (this._libsPromise) return this._libsPromise;

      this._libsPromise = Promise.all([
        this._loadScript('https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js'),
        this._loadScript('https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js'),
      ]).then(function () {
        if (window.pdfjsLib) {
          window.pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
        }
      }).catch(function (err) {
        self._libsPromise = null;
        throw err;
      });

      return this._libsPromise;
    },

    // ── Adding files ──
    onFileInput(e) {
      this._addFiles(Array.from(e.target.files || []));
      e.target.value = '';
    },

    onZoneDragOver(e) {
      // Only highlight for files dragged in from outside, not row reordering.
      if (this.dragIdx === null && e.dataTransfer && Array.prototype.indexOf.call(e.dataTransfer.types || [], 'Files') !== -1) {
        this.zoneHover = true;
      }
    },

    onZoneDrop(e) {
      this.zoneHover = false;
      if (this.dragIdx !== null) return;
      this._addFiles(Array.from((e.dataTransfer && e.dataTransfer.files) || []));
    },

    async _addFiles(list) {
      if (!list.length) return;
      this.fileError = '';

      try {
        await this.ensureLibs();
      } catch (err) {
        this.fileError = 'The PDF library could not be loaded. Check your connection and try again.';
        return;
      }

      var skipped = [];
      var totalBytes = this.files.reduce(function (s, f) { return s + f.size; }, 0);

      for (var i = 0; i < list.length; i++) {
        var file = list[i];
        if (this.files.length >= MAX_FILES) {
          skipped.push('only ' + MAX_FILES + ' files can be merged at once');
          break;
        }
        var looksPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
        if (!looksPdf) { skipped.push('"' + file.name + '" is not a PDF'); continue; }
        if (file.size === 0) { skipped.push('"' + file.name + '" is empty'); continue; }
        if (file.size > MAX_FILE_BYTES) { skipped.push('"' + file.name + '" is larger than 200 MB'); continue; }
        if (totalBytes + file.size > MAX_TOTAL_BYTES) { skipped.push('total size is limited to 750 MB'); break; }
        totalBytes += file.size;

        var entry = {
          id: nextId++, name: file.name, size: file.size, pages: 0, thumb: '',
          status: 'loading', error: '', range: '', rangeError: '', rotation: 0,
        };
        this.files.push(entry);
        this._loadFile(entry.id, file);
      }

      if (skipped.length) this.fileError = 'Skipped: ' + skipped.join('; ') + '.';
      this._invalidateOutput();
    },

    _entry(id) {
      for (var i = 0; i < this.files.length; i++) if (this.files[i].id === id) return this.files[i];
      return null;
    },

    async _loadFile(id, file) {
      var self = this;
      var fail = function (msg) {
        var e = self._entry(id);
        if (e) { e.status = 'error'; e.error = msg; }
        store.delete(id);
      };

      if (!window.PDFLib) {
        fail('The PDF library failed to load. Check your connection and refresh the page.');
        return;
      }

      var bytes;
      try {
        bytes = new Uint8Array(await file.arrayBuffer());
      } catch (err) {
        fail('This file could not be read.');
        return;
      }

      if (!this._hasPdfHeader(bytes)) {
        fail('This file is not a valid PDF.');
        return;
      }

      var doc;
      try {
        doc = await PDFLib.PDFDocument.load(bytes, { updateMetadata: false });
      } catch (err) {
        var m = String((err && err.message) || err).toLowerCase();
        fail(m.indexOf('encrypt') !== -1
          ? 'This PDF is password-protected. Unlock it first, then add it again.'
          : 'This PDF is damaged or uses an unsupported format.');
        return;
      }

      if (!this._entry(id)) return;   // removed while loading
      store.set(id, bytes);

      var entry = this._entry(id);
      entry.pages = doc.getPageCount();
      if (entry.pages === 0) {
        fail('This PDF has no pages.');
        return;
      }
      entry.status = 'ready';

      this._renderThumb(id, bytes);
    },

    _hasPdfHeader(bytes) {
      var limit = Math.min(bytes.length - 4, 1024);
      for (var i = 0; i < limit; i++) {
        if (bytes[i] === 0x25 && bytes[i + 1] === 0x50 && bytes[i + 2] === 0x44 && bytes[i + 3] === 0x46 && bytes[i + 4] === 0x2D) {
          return true;   // "%PDF-"
        }
      }
      return false;
    },

    async _renderThumb(id, bytes) {
      if (!window.pdfjsLib) return;
      var pdf = null;
      try {
        // pdf.js takes ownership of the buffer it is given, so pass a copy.
        pdf = await window.pdfjsLib.getDocument({ data: bytes.slice(0), isEvalSupported: false }).promise;
        var page = await pdf.getPage(1);
        var base = page.getViewport({ scale: 1 });
        var scale = 160 / Math.max(base.width, base.height);
        var vp = page.getViewport({ scale: scale });
        var canvas = document.createElement('canvas');
        canvas.width = Math.ceil(vp.width);
        canvas.height = Math.ceil(vp.height);
        await page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
        var e = this._entry(id);
        if (e) e.thumb = canvas.toDataURL('image/jpeg', 0.8);
        canvas.width = 0; canvas.height = 0;
      } catch (err) {
        // Thumbnail is optional — the file can still be merged.
      } finally {
        if (pdf) pdf.destroy();
      }
    },

    // ── Page ranges ──
    // Returns { pages: [zero-based indices] } or { error: message }.
    parseRange(text, count) {
      var src = String(text || '').trim();
      if (src === '' || /^all$/i.test(src)) {
        var all = [];
        for (var i = 0; i < count; i++) all.push(i);
        return { pages: all };
      }
      var out = [];
      var parts = src.split(/[,;]+/);
      for (var p = 0; p < parts.length; p++) {
        var token = parts[p].trim();
        if (!token) continue;
        var m = /^(\d*)\s*[-–]\s*(\d*)$/.exec(token);
        var from, to;
        if (m) {
          if (m[1] === '' && m[2] === '') return { error: '"' + token + '" is not a valid range.' };
          from = m[1] === '' ? 1 : parseInt(m[1], 10);
          to   = m[2] === '' ? count : parseInt(m[2], 10);
        } else if (/^\d+$/.test(token)) {
          from = to = parseInt(token, 10);
        } else {
          return { error: '"' + token + '" is not valid. Use numbers like 1-3, 5, 8-.' };
        }
        if (from < 1 || to < 1 || from > count || to > count) {
          return { error: 'Pages must be between 1 and ' + count + '.' };
        }
        var step = from <= to ? 1 : -1;
        for (var n = from; step > 0 ? n <= to : n >= to; n += step) {
          out.push(n - 1);
          if (out.length > MAX_OUT_PAGES) return { error: 'Too many pages selected.' };
        }
      }
      if (!out.length) return { error: 'Select at least one page.' };
      return { pages: out };
    },

    validateRange(f) {
      var r = this.parseRange(f.range, f.pages);
      f.rangeError = r.error || '';
      this._invalidateOutput();
    },

    selectedCount(f) {
      if (f.status !== 'ready') return 0;
      var r = this.parseRange(f.range, f.pages);
      return r.pages ? r.pages.length : 0;
    },

    _hasCustomisation(f) {
      return f.range.trim() !== '' || f.rotation !== 0;
    },

    rotate(f) {
      f.rotation = (f.rotation + 90) % 360;
      this._invalidateOutput();
    },

    // ── Ordering ──
    move(idx, delta) {
      var to = idx + delta;
      if (to < 0 || to >= this.files.length) return;
      var item = this.files.splice(idx, 1)[0];
      this.files.splice(to, 0, item);
      this._invalidateOutput();
    },

    sortByName(dir) {
      this.files.sort(function (a, b) {
        return dir * a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' });
      });
      this._invalidateOutput();
    },

    reverseOrder() {
      this.files.reverse();
      this._invalidateOutput();
    },

    onItemDragStart(idx, e) {
      this.dragIdx = idx;
      if (e.dataTransfer) {
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', String(idx)); } catch (err) {}
      }
    },

    onItemDragOver(idx, e) {
      if (this.dragIdx === null) {
        this.onZoneDragOver(e);   // external files dragged over a row
        return;
      }
      this.overIdx = idx;
    },

    onItemDrop(idx, e) {
      if (this.dragIdx === null) {
        this.onZoneDrop(e);
        return;
      }
      if (this.dragIdx !== idx) {
        var item = this.files.splice(this.dragIdx, 1)[0];
        this.files.splice(idx, 0, item);
        this._invalidateOutput();
      }
      this.dragIdx = null;
      this.overIdx = null;
    },

    // ── Removing ──
    removeFile(id) {
      store.delete(id);
      this.files = this.files.filter(function (f) { return f.id !== id; });
      this.fileError = '';
      this._invalidateOutput();
    },

    clearAll() {
      store.clear();
      this.files = [];
      this.fileError = '';
      this.mergeError = '';
      this._invalidateOutput();
    },

    // ── Merge ──
    async merge() {
      if (!this.canMerge) return;

      try {
        await this.ensureLibs();
      } catch (err) {
        this.mergeError = 'The PDF library could not be loaded. Check your connection and try again.';
        return;
      }

      var queue = this.readyFiles.slice();
      this.merging = true;
      this.mergeError = '';
      this.progress = 0;
      this._invalidateOutput();

      try {
        var PDFDocument = PDFLib.PDFDocument;
        var degrees = PDFLib.degrees;
        var out = await PDFDocument.create();
        var total = queue.length;

        for (var i = 0; i < total; i++) {
          var f = queue[i];
          this.progressLabel = 'Adding ' + f.name + ' (' + (i + 1) + ' of ' + total + ')…';
          this.progress = Math.round(i / total * 90);
          await this._tick();

          var range = this.parseRange(f.range, f.pages);
          if (range.error) throw new Error(f.name + ': ' + range.error);

          var bytes = store.get(f.id);
          if (!bytes) throw new Error(f.name + ' is no longer available. Remove it and add it again.');

          var src = await PDFDocument.load(bytes, { updateMetadata: false });
          var copied = await out.copyPages(src, range.pages);
          var lastSize = null;

          for (var c = 0; c < copied.length; c++) {
            var page = copied[c];
            if (f.rotation) {
              page.setRotation(degrees((page.getRotation().angle + f.rotation) % 360));
            }
            out.addPage(page);
            lastSize = page.getSize();
          }

          if (out.getPageCount() > MAX_OUT_PAGES) {
            throw new Error('The merged document would exceed ' + MAX_OUT_PAGES.toLocaleString() + ' pages.');
          }

          if (this.blankBetween && copied.length % 2 === 1 && i < total - 1 && lastSize) {
            out.addPage([lastSize.width, lastSize.height]);
          }
        }

        this.progressLabel = 'Saving merged PDF…';
        this.progress = 92;
        await this._tick();

        var name = this._safeName(this.outputName);
        out.setTitle(name.replace(/\.pdf$/i, ''));
        out.setCreator(@json(config('app.name', 'Toolsearch') . ' PDF Merger'));
        out.setProducer('pdf-lib');
        out.setCreationDate(new Date());
        out.setModificationDate(new Date());

        var saved = await out.save({ useObjectStreams: true });
        var blob = new Blob([saved], { type: 'application/pdf' });

        this.output = {
          url: URL.createObjectURL(blob),
          name: name,
          pages: out.getPageCount(),
          size: blob.size,
        };
        this.progress = 100;
        this._triggerDownload(this.output.url, name);

      } catch (err) {
        this.mergeError = this._friendly(err);
      } finally {
        this.merging = false;
        this.progressLabel = '';
      }
    },

    _triggerDownload(url, name) {
      var a = document.createElement('a');
      a.href = url;
      a.download = name;
      a.rel = 'noopener';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    },

    _invalidateOutput() {
      this._revokeOutput();
      this.output = null;
    },

    _revokeOutput() {
      if (this.output && this.output.url) {
        try { URL.revokeObjectURL(this.output.url); } catch (e) {}
      }
    },

    _safeName(raw) {
      var base = String(raw || '').trim().replace(/\.pdf$/i, '');
      base = base.replace(/[\\\/:*?"<>|\x00-\x1F]+/g, '-').replace(/\s+/g, ' ').replace(/^[.\-\s]+|[.\-\s]+$/g, '');
      if (!base) base = 'merged';
      return base.slice(0, 120) + '.pdf';
    },

    _friendly(err) {
      var msg = String((err && err.message) || err || 'Unknown error');
      var low = msg.toLowerCase();
      if (low.indexOf('memory') !== -1 || low.indexOf('allocation') !== -1 || err instanceof RangeError) {
        return 'Your browser ran out of memory. Try merging fewer or smaller files at a time.';
      }
      if (low.indexOf('encrypt') !== -1) {
        return 'One of the files is password-protected. Unlock it first, then try again.';
      }
      return 'Merge failed: ' + msg;
    },

    formatSize(bytes) {
      if (!bytes) return '0 B';
      if (bytes < 1024) return bytes + ' B';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },

    _tick() {
      return new Promise(function (r) { setTimeout(r, 16); });
    },
  };
}
</script>
@endpush
