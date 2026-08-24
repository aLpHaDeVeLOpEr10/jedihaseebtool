@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)
@section('renders_own_content_sections', '1')

@section('content')
<style>
/* ══════════════════════════════════════════════════════════════
   PDF to Text Converter  —  prefix: pt-
   Theme: Deep blue (#1e40af) + sky accents
   Library: PDF.js 3.11.174 (text extraction via getTextContent)
   Processing is 100% in-browser — no file is ever uploaded.
══════════════════════════════════════════════════════════════ */

/* ── Drop zone ──────────────────────────────────────────────── */
.pt-dropzone {
  border: 2.5px dashed #bfdbfe;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #eff6ff;
  position: relative;
  user-select: none;
}
.pt-dropzone:hover,
.pt-dropzone.pt-drag-over {
  border-color: #1d4ed8;
  background: #dbeafe;
  transform: scale(1.01);
}
.pt-dropzone.pt-has-file {
  border-color: #16a34a;
  background: #f0fdf4;
}
.pt-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.pt-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .25rem; word-break: break-all; }
.pt-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── Progress bar ───────────────────────────────────────────── */
.pt-progress-track {
  width: 100%; height: .65rem; border-radius: 9999px;
  background: #dbeafe; overflow: hidden;
}
.pt-progress-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #1e40af, #2563eb, #3b82f6);
  transition: width .3s ease; position: relative; overflow: hidden;
}
.pt-progress-fill::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.35) 50%, transparent 100%);
  animation: ptShimmer 1.4s infinite;
}
@keyframes ptShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Section divider ────────────────────────────────────────── */
.pt-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6b7280;
}
.pt-div::before,.pt-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Privacy badge ──────────────────────────────────────────── */
.pt-privacy {
  display: flex; align-items: center; gap: .5rem;
  padding: .5rem .85rem; border-radius: .75rem;
  background: #eff6ff; border: 1px solid #bfdbfe;
  font-size: .75rem; color: #1e40af; font-weight: 500;
}

/* ── Error / warning strips ─────────────────────────────────── */
.pt-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}
.pt-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fffbeb; border: 1.5px solid #fde68a;
  font-size: .8rem; color: #92400e;
}
.pt-info {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #eff6ff; border: 1.5px solid #bfdbfe;
  font-size: .8rem; color: #1e40af;
}

/* ── Stat pills ─────────────────────────────────────────────── */
.pt-stat-pill {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .3rem .7rem; border-radius: 9999px;
  font-size: .72rem; font-weight: 700;
  background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe;
}
.pt-stat-pill.pt-stat-green {
  background: #f0fdf4; color: #15803d; border-color: #bbf7d0;
}

/* ── Extracted text output ──────────────────────────────────── */
.pt-output {
  width: 100%; min-height: 320px; max-height: 500px;
  resize: vertical; overflow-y: auto;
  font-family: 'JetBrains Mono','Fira Code','Courier New', monospace;
  font-size: .8rem; line-height: 1.75; color: #1e293b;
  background: #f8fafc; border: 1.5px solid #e2e8f0;
  border-radius: .875rem; padding: 1rem 1.1rem;
  outline: none; transition: border-color .15s;
  white-space: pre-wrap; word-break: break-word;
}
.pt-output:focus { border-color: #2563eb; background: #fff; }

/* ── Convert button ─────────────────────────────────────────── */
.pt-convert-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s;
  background: linear-gradient(135deg, #1e3a8a, #1d4ed8, #2563eb);
  color: #fff; border: none;
  box-shadow: 0 4px 14px rgba(29,78,216,.35);
}
.pt-convert-btn:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(29,78,216,.5);
  transform: translateY(-1px);
}
.pt-convert-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Action buttons row ─────────────────────────────────────── */
.pt-action {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .5rem 1rem; border-radius: .75rem;
  font-size: .82rem; font-weight: 700; cursor: pointer;
  transition: all .14s; border: 1.5px solid transparent;
  white-space: nowrap;
}
.pt-action:disabled { opacity: .4; cursor: not-allowed; }
.pt-action-copy  { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
.pt-action-copy:hover:not(:disabled)  { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }
.pt-action-copy.pt-copied { background: #f0fdf4; color: #15803d; border-color: #86efac; }
.pt-action-dl    { background: #f0fdf4; color: #15803d; border-color: #86efac; }
.pt-action-dl:hover:not(:disabled)    { background: #15803d; color: #fff; border-color: #15803d; }
.pt-action-reset { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
.pt-action-reset:hover { background: #e2e8f0; color: #374151; }

/* ── Toggle row ─────────────────────────────────────────────── */
.pt-toggle-row  { display: flex; align-items: center; justify-content: space-between; gap: .5rem; cursor: pointer; }
.pt-toggle-lbl  { font-size: .8rem; font-weight: 600; color: #374151; }
.pt-toggle-sub  { font-size: .67rem; color: #94a3b8; margin-top: .1rem; }
.pt-toggle {
  position: relative; width: 2.4rem; height: 1.3rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0;
}
.pt-toggle.pt-on { background: #2563eb; }
.pt-toggle::after {
  content: ''; position: absolute; top: .175rem; left: .175rem;
  width: .95rem; height: .95rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.pt-toggle.pt-on::after { transform: translateX(1.1rem); }

/* ── Spinner ────────────────────────────────────────────────── */
@keyframes ptSpin { to { transform: rotate(360deg); } }
.pt-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid currentColor; border-top-color: transparent;
  animation: ptSpin .6s linear infinite; flex-shrink: 0;
}

@media (max-width: 640px) {
  .pt-dropzone { padding: 1.5rem 1rem; }
  .pt-dz-icon  { font-size: 2rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="tool-icon bg-blue-100 text-blue-600 text-3xl w-14 h-14 flex items-center justify-center rounded-xl">
          {{ $tool->icon ?? '📝' }}
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">{{ $tool->short_description }}</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]"/>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      {{-- ── Main tool area ── --}}
      <div class="lg:col-span-2 space-y-5"
           x-data="ptTool()"
           x-init="init()">

        {{-- ── Upload & Options card ── --}}
        <div class="card p-6 space-y-5" x-show="!converting && !extracted">

          <h2 class="text-lg font-semibold text-gray-900">Select a PDF</h2>

          {{-- Privacy badge --}}
          <div class="pt-privacy">
            <span>🔒</span>
            <span>Your file never leaves your browser — text extraction happens entirely on your device.</span>
          </div>

          {{-- Drop zone --}}
          <div
            :class="['pt-dropzone', isDragging ? 'pt-drag-over' : '', file ? 'pt-has-file' : '']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
            role="button"
            tabindex="0"
            @keydown.enter.prevent="$refs.fileInput.click()"
            @keydown.space.prevent="$refs.fileInput.click()"
            :aria-label="file ? 'PDF selected: ' + file.name + '. Click to change.' : 'Drop PDF here or click to browse'"
          >
            <input
              type="file"
              x-ref="fileInput"
              id="pt-file-input"
              accept=".pdf,application/pdf"
              @change="onFileChange($event)"
              class="hidden"
              aria-hidden="true"
            >

            {{-- Empty state --}}
            <div x-show="!file">
              <div class="pt-dz-icon">📄 → 📝</div>
              <p class="pt-dz-title">Drag &amp; drop your PDF here</p>
              <p class="pt-dz-sub">or click to browse &nbsp;·&nbsp; PDF only &nbsp;·&nbsp; max 50 MB</p>
            </div>

            {{-- File selected --}}
            <div x-show="file">
              <div class="pt-dz-icon">✅</div>
              <p class="pt-dz-title" x-text="file ? file.name : ''"></p>
              <p class="pt-dz-sub"
                 x-text="file ? formatSize(file.size) + '  ·  click to change file' : ''">
              </p>
            </div>
          </div>

          {{-- Remove file button (when file is selected) --}}
          <div x-show="file && !fileError" class="flex items-center justify-between">
            <span class="text-sm text-gray-500" x-text="file ? file.name : ''"></span>
            <button type="button"
                    @click.stop="removeFile()"
                    class="text-xs font-semibold text-red-600 hover:text-red-800 transition-colors"
                    aria-label="Remove selected file">
              ✕ Remove
            </button>
          </div>

          {{-- Validation error --}}
          <div x-show="fileError" x-transition role="alert" class="pt-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          {{-- Options --}}
          <div class="border border-gray-100 rounded-xl p-4 space-y-3">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Extraction Options</p>

            <div class="pt-toggle-row" @click="addPageMarkers = !addPageMarkers">
              <div>
                <div class="pt-toggle-lbl">Add page markers</div>
                <div class="pt-toggle-sub">Insert "— Page X of Y —" separators between pages</div>
              </div>
              <div :class="['pt-toggle', addPageMarkers ? 'pt-on' : '']"
                   role="checkbox" :aria-checked="addPageMarkers"
                   tabindex="0" @keydown.enter.prevent="addPageMarkers = !addPageMarkers"></div>
            </div>
          </div>

          {{-- Large file warning --}}
          <div x-show="fileTooLarge" x-transition class="pt-warn">
            <span>⚠️</span>
            <span>This is a large file. Extraction may take a few seconds depending on page count.</span>
          </div>

          {{-- Engine error --}}
          <div x-show="convError && !converting" x-transition role="alert" class="pt-error">
            <span>⚠</span><span x-text="convError"></span>
          </div>

          {{-- Convert button --}}
          <button
            type="button"
            @click="convert()"
            :disabled="!file || !!fileError || libError"
            class="pt-convert-btn"
            aria-live="polite"
          >
            <span x-show="libError" class="pt-spin" style="width:.9em;height:.9em"></span>
            <span x-show="libError">PDF engine unavailable — please refresh</span>
            <span x-show="!libError">📝 Extract Text</span>
          </button>

        </div>

        {{-- ── Progress card ── --}}
        <div class="card p-6 space-y-4" x-show="converting" x-transition aria-live="polite">

          <h2 class="text-lg font-semibold text-gray-900">Extracting text…</h2>

          <div class="flex justify-between text-sm text-gray-600">
            <span>
              <span x-show="currentPage > 0">
                Page <strong x-text="currentPage"></strong>
                of <strong x-text="totalPages"></strong>
              </span>
              <span x-show="currentPage === 0">Loading PDF…</span>
            </span>
            <span class="font-bold text-blue-700" x-text="progressPct + '%'"></span>
          </div>

          <div class="pt-progress-track" role="progressbar"
               :aria-valuenow="progressPct" aria-valuemin="0" aria-valuemax="100">
            <div class="pt-progress-fill" :style="'width:' + progressPct + '%'"></div>
          </div>

          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="pt-spin" style="width:.85em;height:.85em;border-width:1.5px"></span>
            <span>Reading page content in your browser — please keep this tab open.</span>
          </div>

        </div>

        {{-- ── Results card ── --}}
        <div class="card p-6 space-y-5" x-show="extracted" x-transition>

          {{-- Results header --}}
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">Extracted Text</h2>
              <p class="text-xs text-gray-400 mt-0.5" x-text="'from: ' + (file ? file.name : '')"></p>
            </div>
            <button type="button" @click="reset()" class="pt-action pt-action-reset">
              ↺ Convert Another
            </button>
          </div>

          {{-- Stats row --}}
          <div class="flex flex-wrap gap-2">
            <span class="pt-stat-pill">
              <span x-text="pageCount"></span>&nbsp;page<span x-show="pageCount !== 1">s</span>
            </span>
            <span x-show="!isScanned" class="pt-stat-pill pt-stat-green">
              <span x-text="wordCount.toLocaleString()"></span>&nbsp;words
            </span>
            <span x-show="!isScanned" class="pt-stat-pill">
              <span x-text="charCount.toLocaleString()"></span>&nbsp;characters
            </span>
          </div>

          {{-- Scanned / image-only PDF notice --}}
          <div x-show="isScanned" x-transition role="alert" class="pt-warn">
            <span class="text-lg flex-shrink-0">🖼️</span>
            <div>
              <p class="font-semibold mb-0.5">No selectable text found</p>
              <p>This PDF appears to contain only scanned images or drawings. PDF text extraction only works on PDFs that have embedded text layers. For scanned documents, please use an OCR (Optical Character Recognition) tool instead.</p>
            </div>
          </div>

          {{-- Extracted text output --}}
          <div x-show="!isScanned">
            <label class="form-label" for="pt-output">Extracted text</label>
            <textarea
              id="pt-output"
              :value="extractedText"
              class="pt-output"
              readonly
              spellcheck="false"
              aria-label="Extracted text content"
            ></textarea>
          </div>

          {{-- Action buttons --}}
          <div x-show="!isScanned" class="flex flex-wrap gap-2.5">
            <button type="button"
                    @click="copyText()"
                    :disabled="!extractedText"
                    :class="['pt-action', 'pt-action-copy', copied ? 'pt-copied' : '']"
                    aria-live="polite">
              <span x-text="copied ? '✓ Copied!' : '⎘ Copy Text'"></span>
            </button>

            <button type="button"
                    @click="downloadTxt()"
                    :disabled="!extractedText"
                    class="pt-action pt-action-dl">
              ⬇ Download .txt
            </button>
          </div>

          {{-- Conversion warning (partial failures) --}}
          <div x-show="convError" x-transition role="alert" class="pt-warn">
            <span>⚠</span><span x-text="convError"></span>
          </div>

        </div>

        {{-- ── How it works (always visible, not while converting) ── --}}
        <div class="card p-6" x-show="!converting" x-transition>
          <p class="pt-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">📤</span>
              <div>
                <p class="font-semibold text-gray-800">1. Upload PDF</p>
                <p class="text-gray-500 text-xs mt-0.5">Select any text-based PDF — contracts, articles, reports, eBooks, or forms.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">⚙️</span>
              <div>
                <p class="font-semibold text-gray-800">2. Extract Text</p>
                <p class="text-gray-500 text-xs mt-0.5">PDF.js reads the embedded text layer from every page in the correct order.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">3. Copy or Download</p>
                <p class="text-gray-500 text-xs mt-0.5">Copy to clipboard or download the full text as a <code class="bg-gray-100 px-1 rounded">.txt</code> file.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- ── Long Description ── --}}
        @if($tool->long_description)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
          <div class="tool-prose">{!! nl2br(e($tool->long_description)) !!}</div>
        </div>
        @endif

        {{-- ── Content Sections ── --}}
        @foreach($tool->contents->where('is_visible', true) as $section)
        <div class="card p-6">
          @if($section->title)
          <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->title }}</h2>
          @endif
          <div class="tool-prose">{!! nl2br(e($section->content)) !!}</div>
        </div>
        @endforeach

        {{-- ── FAQs ── --}}
        @if($tool->faqs->where('is_visible', true)->count() > 0)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
          <div class="space-y-3" x-data="{ open: null }">
            @foreach($tool->faqs->where('is_visible', true) as $fi => $faq)
            <div class="border border-gray-100 rounded-xl overflow-hidden">
              <button @click="open = open === {{ $fi }} ? null : {{ $fi }}"
                      class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                     :class="open === {{ $fi }} ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div x-show="open === {{ $fi }}" x-cloak
                   class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">
                {{ $faq->answer }}
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- /main col --}}

      {{-- ── Sidebar ── --}}
      <div class="space-y-5">

        {{-- Category --}}
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="{{ route('categories.show', $tool->category) }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl">{{ $tool->category->icon }}</span>
            <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
          </a>
        </div>

        {{-- Tips --}}
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0 mt-0.5">•</span>
              <span>Works best with text-based PDFs — Word exports, digital invoices, eBooks, reports.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0 mt-0.5">•</span>
              <span>Scanned paper documents appear as images — no text layer is embedded.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0 mt-0.5">•</span>
              <span>Password-protected PDFs must be unlocked before extraction.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0 mt-0.5">•</span>
              <span>Multi-column PDFs may have mixed column order — reading order depends on PDF structure.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-green-500 font-bold flex-shrink-0 mt-0.5">🔒</span>
              <span>Your PDF is processed entirely in your browser and is <strong>never uploaded</strong>.</span>
            </li>
          </ul>
        </div>

        {{-- Supported files --}}
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported &amp; Unsupported</h3>
          <div class="space-y-2 text-xs">
            <div>
              <p class="font-semibold text-green-700 mb-1">✅ Supported</p>
              <ul class="space-y-1 text-gray-600 pl-2">
                <li>Text-based PDFs (Word/Google Docs exports)</li>
                <li>Digital invoices &amp; receipts</li>
                <li>eBooks &amp; research papers</li>
                <li>Multi-page documents</li>
                <li>PDFs with multiple fonts</li>
              </ul>
            </div>
            <div class="border-t border-gray-100 pt-2">
              <p class="font-semibold text-red-600 mb-1">❌ Not supported</p>
              <ul class="space-y-1 text-gray-600 pl-2">
                <li>Scanned / image-only PDFs</li>
                <li>Password-protected PDFs</li>
                <li>Encrypted or corrupted files</li>
              </ul>
            </div>
          </div>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count() > 0)
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
          <div class="space-y-2">
            @foreach($relatedTools as $related)
            <a href="{{ route('tools.show', $related) }}"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
              <span class="text-lg">{{ $related->icon }}</span>
              <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">
                {{ $related->name }}
              </span>
            </a>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- /sidebar --}}

    </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- PDF.js — same version already used by the PDF-to-JPG tool --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
/* ─────────────────────────────────────────────────────────────────
   PDF to Text Converter — Alpine.js component
   CSS prefix: pt-

   Extraction flow:
     1. User drops / selects a PDF (client-side validation)
     2. convert():
        a. file.arrayBuffer() → loads raw bytes
        b. pdfjsLib.getDocument({ data }) → parses PDF structure
        c. For each page: page.getTextContent() → text items
        d. _buildPageText() groups items by Y-coordinate into lines,
           sorts left-to-right, joins into a string
        e. All pages assembled into extractedText with optional
           "— Page N of M —" markers
     3. copyText() → navigator.clipboard / legacy execCommand
     4. downloadTxt() → Blob URL → anchor download

   Notes on scanned PDFs:
     getTextContent() returns zero items if the page is an image.
     We detect this and show a clear "use OCR" message instead
     of displaying an empty textarea.

   Security:
     Extracted text is rendered inside a <textarea> (readonly),
     which is plain-text only — no HTML injection possible.
     No data is transmitted to any server.
────────────────────────────────────────────────────────────────── */

// Point PDF.js at its web worker (same CDN version)
if (window.pdfjsLib) {
  window.pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

function ptTool() {
  return {

    // ── File state ────────────────────────────────────────
    file:       null,
    fileError:  '',
    isDragging: false,

    // ── Options ───────────────────────────────────────────
    addPageMarkers: true,

    // ── Conversion state ──────────────────────────────────
    libError:    false,
    converting:  false,
    currentPage: 0,
    totalPages:  0,
    convError:   '',

    // ── Results ───────────────────────────────────────────
    extracted:     false,
    extractedText: '',
    pageCount:     0,
    wordCount:     0,
    charCount:     0,
    isScanned:     false,

    // ── UI ────────────────────────────────────────────────
    copied: false,

    // ── Computed ──────────────────────────────────────────
    get progressPct() {
      if (!this.totalPages) return 0;
      return Math.round((this.currentPage / this.totalPages) * 100);
    },

    get fileTooLarge() {
      return this.file && this.file.size > 10 * 1024 * 1024;
    },

    // ── Lifecycle ─────────────────────────────────────────
    init() {
      if (!window.pdfjsLib) {
        this.libError  = true;
        this.convError = 'PDF engine failed to load. Please refresh the page.';
      }
    },

    // ── File selection ────────────────────────────────────
    onFileChange(e) {
      var f = e.target.files[0];
      if (f) this._setFile(f);
    },

    onDrop(e) {
      this.isDragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this._setFile(f);
    },

    _setFile(file) {
      this.fileError     = '';
      this.convError     = '';
      this.extracted     = false;
      this.extractedText = '';
      this.isScanned     = false;

      var err = this._validate(file);
      if (err) {
        this.fileError = err;
        this.file      = null;
        return;
      }
      this.file = file;
    },

    removeFile() {
      this.file         = null;
      this.fileError    = '';
      this.convError    = '';
      this.extracted    = false;
      this.extractedText = '';
      this.isScanned    = false;
      // Reset the hidden file input so the same file can be re-selected
      var input = document.getElementById('pt-file-input');
      if (input) input.value = '';
    },

    _validate(file) {
      if (!file) return 'No file selected.';
      var isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
      if (!isPdf)
        return 'Only PDF files are supported. Please select a file ending in .pdf.';
      if (file.size === 0)
        return 'The selected file is empty. Please choose a valid PDF.';
      if (file.size > 50 * 1024 * 1024)
        return 'File too large (' + this.formatSize(file.size) + '). Maximum allowed size is 50 MB.';
      return '';
    },

    // ── Conversion ────────────────────────────────────────
    async convert() {
      if (!this.file || this.fileError) return;
      if (!window.pdfjsLib) {
        this.convError = 'PDF engine is not available. Please reload the page.';
        return;
      }

      this.convError     = '';
      this.extracted     = false;
      this.extractedText = '';
      this.isScanned     = false;
      this.currentPage   = 0;
      this.totalPages    = 0;
      this.converting    = true;

      try {
        var arrayBuffer = await this.file.arrayBuffer();

        // Load the PDF (fast — only parses structure, not content)
        var loadingTask = window.pdfjsLib.getDocument({ data: arrayBuffer });
        var pdf         = await loadingTask.promise;

        this.totalPages = pdf.numPages;
        this.pageCount  = pdf.numPages;

        var pageResults   = [];  // [{pageNum, text}]
        var totalChars    = 0;

        for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
          this.currentPage = pageNum;

          var page        = await pdf.getPage(pageNum);
          var textContent = await page.getTextContent();
          var pageText    = this._buildPageText(textContent);

          totalChars += pageText.length;
          pageResults.push({ pageNum: pageNum, text: pageText });

          // Allow the event loop to breathe (re-render progress)
          await this._tick();
        }

        // Detect scanned / image-only PDF
        if (totalChars === 0) {
          this.isScanned = true;
          this.extracted = true;
          return;
        }

        // Assemble full document text
        var parts = [];
        for (var i = 0; i < pageResults.length; i++) {
          var p = pageResults[i];
          if (this.addPageMarkers && pageResults.length > 1) {
            parts.push('─── Page ' + p.pageNum + ' of ' + pageResults.length + ' ───\n\n' + p.text);
          } else {
            parts.push(p.text);
          }
        }
        var fullText = parts.join('\n\n');

        // Collapse more than 2 consecutive blank lines
        fullText = fullText.replace(/\n{3,}/g, '\n\n');

        this.extractedText = fullText.trim();
        this.charCount     = this.extractedText.length;
        this.wordCount     = this.extractedText
          ? this.extractedText.trim().split(/\s+/).filter(function (w) { return w.length > 0; }).length
          : 0;
        this.extracted     = true;

      } catch (err) {
        this.convError = this._friendlyError(err);
        this.extracted = false;
      } finally {
        this.converting = false;
      }
    },

    // ── Text assembly from PDF.js text items ─────────────
    _buildPageText(textContent) {
      var items = textContent.items;
      if (!items || items.length === 0) return '';

      /*
       * PDF.js text items carry a `transform` array:
       *   [scaleX, skewY, skewX, scaleY, translateX, translateY]
       * translateX = horizontal position (left to right)
       * translateY = vertical position   (bottom to top — PDF coordinates)
       *
       * Strategy:
       *  1. Bucket items into "lines" by rounding their Y to the nearest
       *     threshold (5 units) so items on the same visual line group together.
       *  2. Sort lines top-to-bottom (descending Y in PDF space).
       *  3. Within each line sort items left-to-right and concatenate.
       *  4. Items already carry `hasEOL` in PDF.js 3.x; we respect it.
       */

      var THRESHOLD = 5;
      var lineMap   = {};

      for (var i = 0; i < items.length; i++) {
        var item = items[i];
        if (!item.str && !item.hasEOL) continue;  // skip empty markers

        var rawY = item.transform[5];
        var y    = Math.round(rawY / THRESHOLD) * THRESHOLD;
        var x    = item.transform[4];

        if (!lineMap[y]) lineMap[y] = [];
        lineMap[y].push({ x: x, str: item.str, hasEOL: !!item.hasEOL });
      }

      // Sort Y keys descending (top of page → bottom)
      var ys = Object.keys(lineMap).map(Number).sort(function (a, b) { return b - a; });

      var lines = [];
      for (var j = 0; j < ys.length; j++) {
        var lineItems = lineMap[ys[j]];
        lineItems.sort(function (a, b) { return a.x - b.x; });

        // Join items; use hasEOL as a hint for paragraph breaks
        var lineStr = '';
        for (var k = 0; k < lineItems.length; k++) {
          lineStr += lineItems[k].str;
          if (lineItems[k].hasEOL) lineStr += '\n';
        }
        var trimmed = lineStr.trimEnd();
        if (trimmed.length > 0) {
          lines.push(trimmed);
        }
      }

      return lines.join('\n');
    },

    _friendlyError(err) {
      if (!err) return 'An unknown error occurred.';
      var msg = (err.message || String(err)).toLowerCase();

      if (msg.includes('invalid pdf') || msg.includes('missing pdf') || msg.includes('startxref'))
        return 'The file does not appear to be a valid PDF. It may be corrupted or renamed. Please try a different file.';
      if (msg.includes('password') || msg.includes('encrypted'))
        return 'This PDF is password-protected. Please remove the password and try again.';
      if (msg.includes('out of memory') || msg.includes('quota'))
        return 'The browser ran out of memory. Try a smaller PDF or close other tabs and refresh.';
      if (msg.includes('network') || msg.includes('fetch'))
        return 'A network error occurred loading the PDF engine. Please check your connection and refresh.';

      return 'Extraction failed: ' + (err.message || String(err));
    },

    // ── Clipboard ─────────────────────────────────────────
    copyText() {
      if (!this.extractedText) return;
      var self = this;

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.extractedText)
          .then(function ()  { self._flashCopied(); })
          .catch(function () { self._legacyCopy(); });
      } else {
        this._legacyCopy();
      }
    },

    _legacyCopy() {
      var el = document.getElementById('pt-output');
      if (!el) return;
      el.focus();
      el.select();
      try {
        document.execCommand('copy');
        this._flashCopied();
      } catch (e) { /* silently fail */ }
    },

    _flashCopied() {
      var self = this;
      this.copied = true;
      setTimeout(function () { self.copied = false; }, 2200);
    },

    // ── Download ──────────────────────────────────────────
    downloadTxt() {
      var text = this.extractedText || '';
      var baseName = this.file
        ? this.file.name.replace(/\.pdf$/i, '')
        : 'extracted-text';
      var fname = baseName + '.txt';
      var blob  = new Blob([text], { type: 'text/plain;charset=utf-8' });
      var url   = URL.createObjectURL(blob);
      var a     = document.createElement('a');
      a.href     = url;
      a.download = fname;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      setTimeout(function () { URL.revokeObjectURL(url); }, 2000);
    },

    // ── Reset ─────────────────────────────────────────────
    reset() {
      this.file          = null;
      this.fileError     = '';
      this.convError     = '';
      this.extracted     = false;
      this.extractedText = '';
      this.isScanned     = false;
      this.converting    = false;
      this.currentPage   = 0;
      this.totalPages    = 0;
      this.pageCount     = 0;
      this.wordCount     = 0;
      this.charCount     = 0;
      this.copied        = false;
      // Allow same file to be re-selected
      var input = document.getElementById('pt-file-input');
      if (input) input.value = '';
    },

    // ── Utilities ─────────────────────────────────────────
    formatSize(bytes) {
      if (bytes < 1024)             return bytes + ' B';
      if (bytes < 1024 * 1024)      return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    },

    _tick() {
      return new Promise(function (resolve) { setTimeout(resolve, 0); });
    },

  };
}
</script>
@endpush
