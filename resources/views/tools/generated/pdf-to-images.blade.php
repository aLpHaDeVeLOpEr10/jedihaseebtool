@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)
@section('renders_own_content_sections', '1')

@section('content')
<style>
/* ══════════════════════════════════════════════════════════
   PDF to Images  —  prefix: pi-
   Brand: indigo #4f46e5 (brand-600)
   Modes: "Extract Pages" (page-render) | "Extract Images" (embedded)
   Libraries: PDF.js 3.11.174 · JSZip 3.10.1 (lazy)
   All processing is 100 % client-side.
══════════════════════════════════════════════════════════ */

/* ── Drop zone ─────────────────────────────────────────── */
.pi-drop {
  border: 2.5px dashed #c7d2fe;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #eef2ff;
  position: relative;
  user-select: none;
}
.pi-drop:hover, .pi-drop.pi-drag-over {
  border-color: #4f46e5;
  background: #e0e7ff;
  transform: scale(1.01);
}
.pi-drop.pi-has-file { border-color: #16a34a; background: #f0fdf4; }
.pi-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.pi-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .25rem; word-break: break-all; }
.pi-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── Mode tabs ──────────────────────────────────────────── */
.pi-tabs {
  display: flex; border-radius: .875rem; overflow: hidden;
  border: 1.5px solid #e0e7ff; background: #eef2ff;
  padding: .3rem; gap: .3rem;
}
.pi-tab {
  flex: 1; padding: .55rem .75rem; border-radius: .6rem;
  font-size: .82rem; font-weight: 700; cursor: pointer;
  transition: all .15s; border: none; background: transparent;
  color: #6366f1;
}
.pi-tab.pi-tab-active {
  background: #fff; color: #4338ca;
  box-shadow: 0 1px 4px rgba(79,70,229,.18);
}
.pi-tab:hover:not(.pi-tab-active) { background: #c7d2fe44; }

/* ── Settings row ───────────────────────────────────────── */
.pi-select {
  width: 100%; padding: .5rem .85rem;
  border: 1.5px solid #e0e7ff; border-radius: .75rem;
  font-size: .82rem; font-weight: 600; color: #374151;
  background: #fff; outline: none; cursor: pointer;
  transition: border-color .14s;
}
.pi-select:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }

.pi-range { width: 100%; height: 6px; border-radius: 9999px; accent-color: #4f46e5; cursor: pointer; }

.pi-opt-lbl { font-size: .78rem; font-weight: 600; color: #374151; }
.pi-opt-sub { font-size: .67rem; color: #9ca3af; margin-top: .1rem; }

/* ── Progress ───────────────────────────────────────────── */
.pi-progress-track {
  width: 100%; height: .65rem; border-radius: 9999px;
  background: #e0e7ff; overflow: hidden;
}
.pi-progress-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #4338ca, #4f46e5, #6366f1);
  transition: width .3s ease; position: relative; overflow: hidden;
}
.pi-progress-fill::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.35) 50%, transparent 100%);
  animation: piShimmer 1.4s infinite;
}
@keyframes piShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Cards: image grid ──────────────────────────────────── */
.pi-img-card {
  border: 1.5px solid #f3f4f6; border-radius: .875rem;
  overflow: hidden; background: #fff;
  transition: box-shadow .15s, transform .15s;
}
.pi-img-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }

.pi-thumb-wrap {
  position: relative; width: 100%; padding-top: 75%; /* 4:3 */
  background: #f8fafc; overflow: hidden;
}
.pi-thumb {
  position: absolute; inset: 0; width: 100%; height: 100%;
  object-fit: contain; display: block;
}
.pi-img-info { padding: .5rem .65rem .6rem; border-top: 1px solid #f3f4f6; }
.pi-img-num  { font-size: .72rem; font-weight: 800; color: #374151; }
.pi-img-meta { font-size: .65rem; color: #9ca3af; margin-top: .1rem; }

.pi-dl-btn {
  display: flex; align-items: center; justify-content: center; gap: .3rem;
  width: 100%; margin-top: .5rem; padding: .35rem .5rem;
  border-radius: .55rem; font-size: .7rem; font-weight: 700;
  cursor: pointer; transition: all .14s;
  border: 1.5px solid #c7d2fe; background: #eef2ff; color: #4338ca;
}
.pi-dl-btn:hover { background: #4338ca; color: #fff; border-color: #4338ca; }

/* ── Download All button ────────────────────────────────── */
.pi-zip-btn {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .55rem 1.2rem; border-radius: .875rem;
  font-size: .82rem; font-weight: 700; cursor: pointer; transition: all .14s;
  background: #166534; color: #fff; border: 1.5px solid #15803d;
  box-shadow: 0 2px 8px rgba(22,101,52,.2);
}
.pi-zip-btn:hover { background: #15803d; box-shadow: 0 3px 12px rgba(22,101,52,.35); }
.pi-zip-btn:disabled { opacity: .5; cursor: not-allowed; box-shadow: none; }

/* ── Convert button ─────────────────────────────────────── */
.pi-go-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s;
  background: linear-gradient(135deg, #3730a3, #4f46e5, #6366f1);
  color: #fff; border: none;
  box-shadow: 0 4px 14px rgba(79,70,229,.38);
}
.pi-go-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(79,70,229,.55); transform: translateY(-1px); }
.pi-go-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Privacy / info badges ──────────────────────────────── */
.pi-privacy {
  display: flex; align-items: center; gap: .5rem;
  padding: .5rem .85rem; border-radius: .75rem;
  background: #eef2ff; border: 1px solid #c7d2fe;
  font-size: .75rem; color: #3730a3; font-weight: 500;
}

/* ── Error / warning ────────────────────────────────────── */
.pi-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}
.pi-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fffbeb; border: 1.5px solid #fde68a;
  font-size: .8rem; color: #92400e;
}
.pi-empty {
  display: flex; flex-direction: column; align-items: center; gap: .75rem;
  padding: 2rem 1rem; text-align: center;
}
.pi-empty-icon { font-size: 2.5rem; }
.pi-empty-title { font-size: 1rem; font-weight: 700; color: #374151; }
.pi-empty-sub   { font-size: .82rem; color: #9ca3af; max-width: 28rem; }

/* ── Section divider ────────────────────────────────────── */
.pi-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6b7280;
}
.pi-div::before,.pi-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Spinner ────────────────────────────────────────────── */
@keyframes piSpin { to { transform: rotate(360deg); } }
.pi-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid currentColor; border-top-color: transparent;
  animation: piSpin .6s linear infinite; flex-shrink: 0;
}

/* ── Stat pill ──────────────────────────────────────────── */
.pi-pill {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .25rem .65rem; border-radius: 9999px;
  font-size: .7rem; font-weight: 700;
  background: #eef2ff; color: #3730a3; border: 1.5px solid #c7d2fe;
}

@media (max-width: 640px) {
  .pi-drop  { padding: 1.5rem 1rem; }
  .pi-dz-icon { font-size: 2rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14 flex items-center justify-center rounded-xl">
          {{ $tool->icon ?? '🖼️' }}
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">{{ $tool->short_description ?? 'Extract embedded images or convert every page to an image — free, instant, 100% in-browser.' }}</p>
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

      {{-- ── Main column ── --}}
      <div class="lg:col-span-2 space-y-5"
           x-data="piTool()"
           x-init="init()">

        {{-- ══ UPLOAD + SETTINGS CARD ══ --}}
        <div class="card p-6 space-y-5" x-show="!processing && !done">

          <h2 class="text-lg font-semibold text-gray-900">Select a PDF</h2>

          {{-- Privacy badge --}}
          <div class="pi-privacy">
            <span>🔒</span>
            <span>Your file never leaves your browser — all processing happens on your device.</span>
          </div>

          {{-- Mode selector --}}
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Mode</p>
            <div class="pi-tabs" role="tablist">
              <button type="button"
                      role="tab"
                      :aria-selected="mode === 'pages'"
                      :class="['pi-tab', mode === 'pages' ? 'pi-tab-active' : '']"
                      @click="mode = 'pages'">
                📄 Extract Pages
              </button>
              <button type="button"
                      role="tab"
                      :aria-selected="mode === 'images'"
                      :class="['pi-tab', mode === 'images' ? 'pi-tab-active' : '']"
                      @click="mode = 'images'">
                🖼️ Extract Images
              </button>
            </div>
            <p class="text-xs text-gray-400 mt-1.5"
               x-text="mode === 'pages'
                 ? 'Render every PDF page as a PNG or JPG image'
                 : 'Extract images that are embedded inside the PDF file'">
            </p>
          </div>

          {{-- Drop zone --}}
          <div
            :class="['pi-drop', isDragging ? 'pi-drag-over' : '', file ? 'pi-has-file' : '']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
            role="button"
            tabindex="0"
            @keydown.enter.prevent="$refs.fileInput.click()"
            @keydown.space.prevent="$refs.fileInput.click()"
            :aria-label="file ? 'PDF selected: ' + file.name + '. Click to change.' : 'Drop a PDF here or click to browse'"
          >
            <input type="file" x-ref="fileInput" id="pi-file-input"
                   accept=".pdf,application/pdf"
                   @change="onFileChange($event)"
                   class="hidden" aria-hidden="true">

            <div x-show="!file">
              <div class="pi-dz-icon">📄</div>
              <p class="pi-dz-title">Drag &amp; drop your PDF here</p>
              <p class="pi-dz-sub">or click to browse &nbsp;·&nbsp; PDF only &nbsp;·&nbsp; max 100 MB</p>
            </div>

            <div x-show="file">
              <div class="pi-dz-icon">✅</div>
              <p class="pi-dz-title" x-text="file ? file.name : ''"></p>
              <p class="pi-dz-sub"
                 x-text="file ? formatSize(file.size) + ' · click to change file' : ''"></p>
            </div>
          </div>

          {{-- Remove file --}}
          <div x-show="file && !fileError" class="flex items-center justify-between -mt-2">
            <span class="text-xs text-gray-400" x-text="file ? file.name : ''"></span>
            <button type="button" @click.stop="removeFile()"
                    class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors"
                    aria-label="Remove selected file">
              ✕ Remove
            </button>
          </div>

          {{-- File error --}}
          <div x-show="fileError" x-transition role="alert" class="pi-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          {{-- ─── Page-mode settings ─── --}}
          <div x-show="mode === 'pages'" x-transition class="space-y-4">
            <p class="pi-div">Page Output Settings</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="pi-opt-lbl block mb-1" for="pi-fmt">Output Format</label>
                <select id="pi-fmt" x-model="pageFormat" class="pi-select">
                  <option value="png">PNG — lossless, transparent backgrounds</option>
                  <option value="jpg">JPG — smaller file size, no transparency</option>
                </select>
              </div>
              <div>
                <label class="pi-opt-lbl block mb-1" for="pi-scale">
                  Resolution — <span class="text-brand-600 font-bold" x-text="pageScale + '×'"></span>
                  <span class="text-gray-400 font-normal" x-text="'(' + Math.round(pageScale * 72) + ' DPI)'"></span>
                </label>
                <input id="pi-scale" type="range" x-model.number="pageScale"
                       min="0.5" max="4" step="0.5" class="pi-range mt-2"
                       aria-label="Render scale multiplier">
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                  <span>0.5× (36 DPI)</span><span>2× (144)</span><span>4× (288)</span>
                </div>
              </div>
            </div>

            {{-- JPG quality --}}
            <div x-show="pageFormat === 'jpg'" x-transition>
              <label class="pi-opt-lbl block mb-1" for="pi-qual">
                JPG Quality — <span class="text-brand-600 font-bold" x-text="Math.round(pageQuality * 100) + '%'"></span>
              </label>
              <input id="pi-qual" type="range" x-model.number="pageQuality"
                     min="0.5" max="1" step="0.05" class="pi-range"
                     aria-label="JPG quality percentage">
              <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>50% (smallest)</span><span>82% (balanced)</span><span>100%</span>
              </div>
            </div>
          </div>

          {{-- ─── Image-extraction settings ─── --}}
          <div x-show="mode === 'images'" x-transition class="space-y-3">
            <p class="pi-div">Image Extraction Settings</p>
            <div class="pi-warn">
              <span class="text-lg flex-shrink-0">ℹ️</span>
              <div>
                <p class="font-semibold mb-0.5">What gets extracted?</p>
                <p>Only images that are directly embedded as raster objects in the PDF. Decorative backgrounds, text, and vector graphics are not extracted. Scanned PDFs (image-of-page) will show pages, not sub-images.</p>
              </div>
            </div>
            <div>
              <label class="pi-opt-lbl block mb-1" for="pi-img-fmt">Save Format</label>
              <select id="pi-img-fmt" x-model="imageFormat" class="pi-select">
                <option value="png">PNG — preserves full quality</option>
                <option value="jpg">JPG — smaller output files</option>
              </select>
            </div>
          </div>

          {{-- Large-file warning --}}
          <div x-show="file && file.size > 15 * 1024 * 1024" x-transition class="pi-warn">
            <span>⚠️</span>
            <span>Large file detected. Processing may take 10–30 seconds at higher resolutions. Keep this tab open.</span>
          </div>

          {{-- Processing error (before run) --}}
          <div x-show="processError && !processing" x-transition role="alert" class="pi-error">
            <span>⚠</span><span x-text="processError"></span>
          </div>

          {{-- GO button --}}
          <button type="button"
                  @click="run()"
                  :disabled="!file || !!fileError || libError"
                  class="pi-go-btn"
                  aria-live="polite">
            <span x-show="!libError">
              <span x-text="mode === 'pages' ? '🖼️ Convert Pages to Images' : '🔍 Extract Embedded Images'"></span>
            </span>
            <span x-show="libError" class="flex items-center gap-2">
              <span class="pi-spin"></span> PDF engine unavailable — please refresh
            </span>
          </button>

        </div>{{-- /upload card --}}

        {{-- ══ PROGRESS CARD ══ --}}
        <div class="card p-6 space-y-4" x-show="processing" x-transition aria-live="polite">

          <h2 class="text-lg font-semibold text-gray-900" x-text="mode === 'pages' ? 'Rendering pages…' : 'Scanning pages for images…'"></h2>

          <div class="flex justify-between text-sm text-gray-600">
            <span>
              <span x-show="currentStep > 0">
                Page <strong x-text="currentStep"></strong>
                of <strong x-text="totalSteps"></strong>
              </span>
              <span x-show="currentStep === 0">Loading PDF…</span>
            </span>
            <span class="font-bold text-brand-600" x-text="progressPct + '%'"></span>
          </div>

          <div class="pi-progress-track"
               role="progressbar"
               :aria-valuenow="progressPct" aria-valuemin="0" aria-valuemax="100">
            <div class="pi-progress-fill" :style="'width:' + progressPct + '%'"></div>
          </div>

          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="pi-spin" style="width:.85em;height:.85em;border-width:1.5px"></span>
            <span>Please keep this tab open while processing.</span>
          </div>

        </div>

        {{-- ══ RESULTS CARD ══ --}}
        <div class="card p-6 space-y-5" x-show="done" x-transition>

          {{-- Header --}}
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">
                <span x-show="mode === 'pages'">
                  <span x-text="pages.length"></span> page<span x-show="pages.length !== 1">s</span> converted
                </span>
                <span x-show="mode === 'images' && !noImagesFound">
                  <span x-text="extractedImages.length"></span> image<span x-show="extractedImages.length !== 1">s</span> extracted
                </span>
                <span x-show="mode === 'images' && noImagesFound">No embedded images found</span>
              </h2>
              <p class="text-xs text-gray-400 mt-0.5" x-text="'from: ' + (file ? file.name : '')"></p>
            </div>
            <div class="flex flex-wrap gap-2">
              {{-- Download All --}}
              <button type="button"
                      x-show="(mode === 'pages' && pages.length > 0) || (mode === 'images' && extractedImages.length > 0)"
                      @click="downloadAll()"
                      :disabled="zipping"
                      class="pi-zip-btn">
                <span x-show="zipping" class="pi-spin" style="width:.9em;height:.9em;border-color:rgba(255,255,255,.5);border-top-color:transparent;"></span>
                <span x-show="!zipping">⬇</span>
                <span x-show="zipping">Building ZIP…</span>
                <span x-show="!zipping">Download All as ZIP</span>
              </button>
              {{-- Reset --}}
              <button type="button" @click="reset()" class="btn btn-secondary text-sm">↺ New PDF</button>
            </div>
          </div>

          {{-- Stats row --}}
          <div x-show="mode === 'pages' && pages.length > 0" class="flex flex-wrap gap-2">
            <span class="pi-pill" x-text="pages.length + ' page' + (pages.length !== 1 ? 's' : '')"></span>
            <span class="pi-pill" x-text="pageFormat.toUpperCase()"></span>
            <span class="pi-pill" x-text="pageScale + '× (' + Math.round(pageScale * 72) + ' DPI)'"></span>
          </div>

          <div x-show="mode === 'images' && extractedImages.length > 0" class="flex flex-wrap gap-2">
            <span class="pi-pill" x-text="extractedImages.length + ' image' + (extractedImages.length !== 1 ? 's' : '')"></span>
            <span class="pi-pill" x-text="imageFormat.toUpperCase()"></span>
          </div>

          {{-- Empty state —  no embedded images --}}
          <div x-show="mode === 'images' && noImagesFound" class="pi-empty">
            <div class="pi-empty-icon">🔍</div>
            <p class="pi-empty-title">No embedded images found</p>
            <p class="pi-empty-sub">This PDF does not contain any directly embedded raster images. If it looks like it has images, they may be decorative vector graphics, or the PDF may be a scanned document. Try <strong>Extract Pages</strong> mode to render the whole page as an image.</p>
            <button type="button" @click="reset()" class="btn btn-secondary text-sm mt-2">Try Another PDF</button>
          </div>

          {{-- Page image grid --}}
          <div x-show="mode === 'pages' && pages.length > 0">
            <p class="pi-div mb-3">Converted Pages</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <template x-for="pg in pages" :key="pg.pageNum">
                <div class="pi-img-card">
                  <div class="pi-thumb-wrap">
                    <img :src="pg.dataUrl"
                         :alt="'Page ' + pg.pageNum"
                         class="pi-thumb" loading="lazy">
                  </div>
                  <div class="pi-img-info">
                    <p class="pi-img-num" x-text="'Page ' + pg.pageNum"></p>
                    <p class="pi-img-meta" x-text="pg.width + ' × ' + pg.height + ' px'"></p>
                    <button type="button" @click="downloadItem(pg)" class="pi-dl-btn">
                      ⬇ Save <span x-text="pageFormat.toUpperCase()"></span>
                    </button>
                  </div>
                </div>
              </template>
            </div>
          </div>

          {{-- Extracted images grid --}}
          <div x-show="mode === 'images' && extractedImages.length > 0">
            <p class="pi-div mb-3">Extracted Images</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <template x-for="img in extractedImages" :key="img.id">
                <div class="pi-img-card">
                  <div class="pi-thumb-wrap">
                    <img :src="img.dataUrl"
                         :alt="'Image ' + img.id"
                         class="pi-thumb" loading="lazy">
                  </div>
                  <div class="pi-img-info">
                    <p class="pi-img-num" x-text="'Image ' + img.id"></p>
                    <p class="pi-img-meta" x-text="img.width + ' × ' + img.height + ' px  ·  p.' + img.pageNum"></p>
                    <button type="button" @click="downloadItem(img)" class="pi-dl-btn">
                      ⬇ Save <span x-text="imageFormat.toUpperCase()"></span>
                    </button>
                  </div>
                </div>
              </template>
            </div>
          </div>

          {{-- ZIP error --}}
          <div x-show="zipError" x-transition role="alert" class="pi-error">
            <span>⚠</span><span x-text="zipError"></span>
          </div>

          {{-- Processing error (partial) --}}
          <div x-show="processError" x-transition class="pi-warn">
            <span>⚠️</span><span x-text="processError"></span>
          </div>

        </div>{{-- /results --}}

        {{-- ══ HOW IT WORKS ══ --}}
        <div class="card p-6" x-show="!processing">
          <p class="pi-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
            <div>
              <p class="font-bold text-gray-800 mb-1">📄 Extract Pages</p>
              <p class="text-gray-500 text-xs">Renders each PDF page onto a canvas using PDF.js, then exports it as PNG or JPG. Works with any PDF — text documents, presentations, invoices, or scans.</p>
            </div>
            <div>
              <p class="font-bold text-gray-800 mb-1">🖼️ Extract Images</p>
              <p class="text-gray-500 text-xs">Reads the PDF's internal image objects (XObjects) and extracts each raster image individually. Best for PDFs with embedded photos or illustrations.</p>
            </div>
          </div>
        </div>

        {{-- Content sections + FAQs --}}
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

        {{-- Tips --}}
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span>Use <strong>PNG</strong> for documents with text — it's lossless and sharper.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span><strong>2×</strong> resolution (144 DPI) is ideal for web use and most screens.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span>Use <strong>3–4×</strong> for print-quality output from page renders.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span><strong>Extract Images</strong> only works if images are embedded as raster objects — vector PDFs won't yield individual image files.</span></li>
            <li class="flex gap-2"><span class="text-green-500 font-bold flex-shrink-0 mt-0.5">🔒</span><span>Your PDF is processed entirely in your browser — <strong>never uploaded</strong>.</span></li>
          </ul>
        </div>

        {{-- Category --}}
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="{{ route('categories.show', $tool->category) }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl">{{ $tool->category->icon }}</span>
            <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
          </a>
        </div>

        {{-- Related tools --}}
        @if($relatedTools->count() > 0)
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
          <div class="space-y-2">
            @foreach($relatedTools as $related)
            <a href="{{ route('tools.show', $related) }}"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
/* ─────────────────────────────────────────────────────────────────
   PDF to Images — Alpine.js component  (prefix: pi-)
   Brand: indigo (#4f46e5)

   Mode A — "Extract Pages" (page render):
     pdfjsLib.getDocument() → for each page: getViewport + canvas.render
     → toDataURL(format, quality) → stored in pages[]

   Mode B — "Extract Images" (embedded XObjects):
     For each page: getOperatorList() → find paintImageXObject ops
     → page.objs.get(name, cb) → raw RGBA pixels
     → normalise to RGBA → ImageData → putImageData → toDataURL
     Inline images (paintInlineImageXObject) handled directly from args.

   Download single: anchor + data URL
   Download all:    JSZip loaded lazily → Blob → anchor
────────────────────────────────────────────────────────────────── */

if (window.pdfjsLib) {
  window.pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

function piTool() {
  return {

    // ── File ─────────────────────────────────────────────
    file:       null,
    fileError:  '',
    isDragging: false,

    // ── Mode ─────────────────────────────────────────────
    mode: 'pages',   // 'pages' | 'images'

    // ── Page-render settings ──────────────────────────────
    pageFormat:  'png',   // 'png' | 'jpg'
    pageQuality: 0.92,
    pageScale:   1.5,

    // ── Image-extraction settings ─────────────────────────
    imageFormat: 'png',   // 'png' | 'jpg'

    // ── Processing state ──────────────────────────────────
    libError:     false,
    processing:   false,
    done:         false,
    currentStep:  0,
    totalSteps:   0,
    processError: '',

    // ── Results ───────────────────────────────────────────
    pages:           [],   // [{pageNum, dataUrl, width, height}]
    extractedImages: [],   // [{id, pageNum, dataUrl, width, height}]
    noImagesFound:   false,

    // ── ZIP ───────────────────────────────────────────────
    zipping:  false,
    zipError: '',

    // ── Computed ──────────────────────────────────────────
    get progressPct() {
      return this.totalSteps
        ? Math.round((this.currentStep / this.totalSteps) * 100)
        : 0;
    },

    // ── Lifecycle ─────────────────────────────────────────
    init() {
      if (!window.pdfjsLib) {
        this.libError     = true;
        this.processError = 'PDF engine failed to load. Please refresh the page.';
      }
    },

    // ── File handling ─────────────────────────────────────
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
      this.fileError    = '';
      this.processError = '';
      this.done         = false;
      this.pages        = [];
      this.extractedImages = [];
      this.noImagesFound   = false;

      var err = this._validate(file);
      if (err) { this.fileError = err; this.file = null; return; }
      this.file = file;
    },

    removeFile() {
      this.file         = null;
      this.fileError    = '';
      this.processError = '';
      this.done         = false;
      this.pages        = [];
      this.extractedImages = [];
      var inp = document.getElementById('pi-file-input');
      if (inp) inp.value = '';
    },

    _validate(file) {
      if (!file) return 'No file selected.';
      var ok = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
      if (!ok) return 'Only PDF files are supported (.pdf).';
      if (file.size === 0) return 'The selected file is empty.';
      if (file.size > 100 * 1024 * 1024)
        return 'File too large (' + this.formatSize(file.size) + '). Maximum is 100 MB.';
      return '';
    },

    // ── Main entry point ──────────────────────────────────
    async run() {
      if (!this.file || this.fileError || !window.pdfjsLib) return;

      this.processError    = '';
      this.done            = false;
      this.pages           = [];
      this.extractedImages = [];
      this.noImagesFound   = false;
      this.currentStep     = 0;
      this.totalSteps      = 0;
      this.zipping         = false;
      this.zipError        = '';
      this.processing      = true;

      try {
        if (this.mode === 'pages') {
          await this._runPages();
        } else {
          await this._runImages();
        }
        this.done = true;
      } catch (err) {
        this.processError = this._friendly(err);
      } finally {
        this.processing = false;
      }
    },

    // ────────────────────────────────────────────────────
    // MODE A — Extract Pages
    // ────────────────────────────────────────────────────
    async _runPages() {
      var buf  = await this.file.arrayBuffer();
      var pdf  = await window.pdfjsLib.getDocument({ data: buf }).promise;
      this.totalSteps = pdf.numPages;

      var mimeType = this.pageFormat === 'jpg' ? 'image/jpeg' : 'image/png';
      var quality  = this.pageFormat === 'jpg' ? Number(this.pageQuality) : undefined;
      var scale    = Number(this.pageScale);
      var baseName = this.file.name.replace(/\.pdf$/i, '');

      for (var n = 1; n <= pdf.numPages; n++) {
        this.currentStep = n;
        await this._tick();

        var page     = await pdf.getPage(n);
        var viewport = page.getViewport({ scale: scale });

        var canvas    = document.createElement('canvas');
        canvas.width  = Math.round(viewport.width);
        canvas.height = Math.round(viewport.height);
        var ctx = canvas.getContext('2d');

        await page.render({ canvasContext: ctx, viewport: viewport }).promise;

        var dataUrl = quality !== undefined
          ? canvas.toDataURL(mimeType, quality)
          : canvas.toDataURL(mimeType);

        this.pages.push({
          pageNum:  n,
          dataUrl:  dataUrl,
          width:    canvas.width,
          height:   canvas.height,
          format:   this.pageFormat,
          baseName: baseName,
        });

        // Release canvas memory
        canvas.width = 0; canvas.height = 0;
      }
    },

    // ────────────────────────────────────────────────────
    // MODE B — Extract Embedded Images
    // ────────────────────────────────────────────────────
    async _runImages() {
      var buf  = await this.file.arrayBuffer();
      var pdf  = await window.pdfjsLib.getDocument({ data: buf }).promise;
      this.totalSteps = pdf.numPages;

      var OPS      = window.pdfjsLib.OPS;
      var mimeType = this.imageFormat === 'jpg' ? 'image/jpeg' : 'image/png';
      var imgCount = 0;
      var globalSeen = new Set(); // deduplicate XObject names across pages

      for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        this.currentStep = pageNum;
        await this._tick();

        var page   = await pdf.getPage(pageNum);
        var opList = await page.getOperatorList();

        // ── Inline images (data in args directly) ──────
        for (var i = 0; i < opList.fnArray.length; i++) {
          if (opList.fnArray[i] === OPS.paintInlineImageXObject) {
            var rawInline = opList.argsArray[i][0];
            if (!rawInline || !rawInline.width || !rawInline.height) continue;
            var rgba = this._toRGBA(rawInline);
            if (!rgba) continue;
            imgCount++;
            var du = this._pixelsToDataUrl(rawInline.width, rawInline.height, rgba, mimeType);
            if (du) {
              this.extractedImages.push({
                id: imgCount, pageNum: pageNum,
                dataUrl: du,
                width: rawInline.width, height: rawInline.height,
              });
            }
          }
        }

        // ── XObject images (looked up by name) ────────
        var xRefs = new Set();
        for (var j = 0; j < opList.fnArray.length; j++) {
          var fn = opList.fnArray[j];
          if (fn === OPS.paintImageXObject || fn === OPS.paintImageXObjectRepeat) {
            var name = opList.argsArray[j][0];
            if (name && !globalSeen.has(name)) xRefs.add(name);
          }
        }

        for (var nameVal of xRefs) {
          try {
            var imgData = await this._resolveObj(page, nameVal);
            if (!imgData || !imgData.width || !imgData.height) continue;

            globalSeen.add(nameVal);
            var rgbaData = this._toRGBA(imgData);
            if (!rgbaData) continue;

            imgCount++;
            var dataUrl = this._pixelsToDataUrl(imgData.width, imgData.height, rgbaData, mimeType);
            if (dataUrl) {
              this.extractedImages.push({
                id: imgCount, pageNum: pageNum,
                name: nameVal,
                dataUrl: dataUrl,
                width: imgData.width, height: imgData.height,
              });
            }
          } catch (e) {
            // Skip unresolvable image objects silently
          }
        }
      }

      if (this.extractedImages.length === 0) {
        this.noImagesFound = true;
      }
    },

    // Resolve a PDF.js page object by name (Promise wrapper)
    _resolveObj(page, name) {
      return new Promise(function (resolve, reject) {
        var t = setTimeout(function () { reject(new Error('timeout')); }, 6000);
        try {
          page.objs.get(name, function (data) {
            clearTimeout(t);
            resolve(data);
          });
        } catch (e) {
          clearTimeout(t);
          reject(e);
        }
      });
    },

    // Convert PDF.js image data (any colour space) → Uint8ClampedArray RGBA
    _toRGBA(imgData) {
      if (!imgData || !imgData.data) return null;

      var w    = imgData.width;
      var h    = imgData.height;
      var data = imgData.data;
      var kind = imgData.kind; // 1=GRAYSCALE_1BPP, 2=RGB_24BPP, 3=RGBA_32BPP

      var total = w * h;
      var rgba  = new Uint8ClampedArray(total * 4);

      if (!kind || kind === 3) {
        // Already RGBA (or unknown → assume RGBA)
        if (data.length >= total * 4) {
          rgba.set(data.subarray(0, total * 4));
          return rgba;
        }
        return null;
      }

      if (kind === 2) {
        // RGB 24 bpp → RGBA
        if (data.length < total * 3) return null;
        for (var i = 0; i < total; i++) {
          rgba[i * 4]     = data[i * 3];
          rgba[i * 4 + 1] = data[i * 3 + 1];
          rgba[i * 4 + 2] = data[i * 3 + 2];
          rgba[i * 4 + 3] = 255;
        }
        return rgba;
      }

      if (kind === 1) {
        // 1-bit grayscale packed into bytes (MSB first)
        var bpr = (w + 7) >> 3;
        for (var row = 0; row < h; row++) {
          for (var col = 0; col < w; col++) {
            var byte_ = data[row * bpr + (col >> 3)];
            var bit   = (byte_ >> (7 - (col & 7))) & 1;
            var val   = bit ? 255 : 0;
            var di    = (row * w + col) * 4;
            rgba[di] = rgba[di + 1] = rgba[di + 2] = val;
            rgba[di + 3] = 255;
          }
        }
        return rgba;
      }

      return null;
    },

    // Draw RGBA pixels on offscreen canvas → data URL
    _pixelsToDataUrl(w, h, rgba, mimeType) {
      try {
        var canvas    = document.createElement('canvas');
        canvas.width  = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.putImageData(new ImageData(rgba, w, h), 0, 0);
        var du = canvas.toDataURL(mimeType, 0.92);
        canvas.width = 0; canvas.height = 0; // free memory
        return du;
      } catch (e) {
        return null;
      }
    },

    // ── Downloads ─────────────────────────────────────────
    downloadItem(item) {
      var fmt  = this.mode === 'pages' ? item.format : this.imageFormat;
      var base = item.baseName
        || (this.file ? this.file.name.replace(/\.pdf$/i, '') : 'image');
      var name = this.mode === 'pages'
        ? base + '-page-' + String(item.pageNum).padStart(3, '0') + '.' + fmt
        : base + '-img-'  + String(item.id).padStart(3, '0') + '.' + fmt;

      var a = document.createElement('a');
      a.href     = item.dataUrl;
      a.download = name;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    },

    async downloadAll() {
      var list = this.mode === 'pages' ? this.pages : this.extractedImages;
      if (!list.length) return;

      this.zipping  = true;
      this.zipError = '';

      try {
        if (!window.JSZip) {
          await this._loadScript(
            'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'
          );
        }

        var zip    = new window.JSZip();
        var folder = zip.folder('images');
        var base   = this.file ? this.file.name.replace(/\.pdf$/i, '') : 'pdf';

        for (var i = 0; i < list.length; i++) {
          var item = list[i];
          var fmt  = this.mode === 'pages' ? item.format : this.imageFormat;
          var name = this.mode === 'pages'
            ? base + '-page-' + String(item.pageNum).padStart(3, '0') + '.' + fmt
            : base + '-img-'  + String(item.id).padStart(3, '0')      + '.' + fmt;

          var b64 = item.dataUrl.split(',')[1];
          folder.file(name, b64, { base64: true });
        }

        var blob = await zip.generateAsync({ type: 'blob', compression: 'DEFLATE' });
        var url  = URL.createObjectURL(blob);
        var a    = document.createElement('a');
        a.href = url;
        a.download = base + '-images.zip';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(url); }, 2000);

      } catch (err) {
        this.zipError = 'ZIP creation failed: ' + (err.message || String(err));
      } finally {
        this.zipping = false;
      }
    },

    // ── Reset ─────────────────────────────────────────────
    reset() {
      this.file            = null;
      this.fileError       = '';
      this.processError    = '';
      this.done            = false;
      this.processing      = false;
      this.pages           = [];
      this.extractedImages = [];
      this.noImagesFound   = false;
      this.currentStep     = 0;
      this.totalSteps      = 0;
      this.zipping         = false;
      this.zipError        = '';
      var inp = document.getElementById('pi-file-input');
      if (inp) inp.value = '';
    },

    // ── Utilities ─────────────────────────────────────────
    formatSize(bytes) {
      if (bytes < 1024)        return bytes + ' B';
      if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },

    _tick() {
      return new Promise(function (r) { setTimeout(r, 0); });
    },

    _loadScript(src) {
      return new Promise(function (resolve, reject) {
        var s   = document.createElement('script');
        s.src   = src;
        s.onload  = resolve;
        s.onerror = function () { reject(new Error('Failed to load ' + src)); };
        document.head.appendChild(s);
      });
    },

    _friendly(err) {
      if (!err) return 'An unknown error occurred.';
      var m = (err.message || String(err)).toLowerCase();
      if (m.includes('invalid pdf') || m.includes('missing pdf') || m.includes('startxref'))
        return 'This file does not appear to be a valid PDF. It may be corrupted or have been renamed. Please try another file.';
      if (m.includes('password') || m.includes('encrypted'))
        return 'This PDF is password-protected. Remove the password first, then try again.';
      if (m.includes('memory') || m.includes('quota'))
        return 'The browser ran out of memory. Try a smaller PDF, a lower scale, or close other tabs.';
      return 'Processing failed: ' + (err.message || String(err));
    },

  };
}
</script>
@endpush
