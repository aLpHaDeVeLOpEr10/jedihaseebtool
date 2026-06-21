@extends('layouts.public')

@section('title', 'Image Upscaler — Enlarge Images 2x 3x 4x Online Free | JEDISEBITOOL')
@section('description', 'Upscale and enlarge images 2x, 3x, or 4x using Lanczos bicubic resampling — free, browser-based, no uploads, no AI APIs. Supports JPG, PNG, and WebP.')

@section('head')
<style>
/* ══════════════════════════════════════════════════
   Image Upscaler   prefix: ius-
   ══════════════════════════════════════════════════ */

/* ── Drop zone ── */
.ius-drop{border:2px dashed #a5b4fc;border-radius:18px;padding:3rem 2rem;text-align:center;cursor:pointer;background:#f8f7ff;transition:border-color .2s,background .2s;user-select:none;position:relative}
.ius-drop:hover,.ius-drop.over{border-color:#4f46e5;background:#eef2ff}
.ius-drop-icon{width:72px;height:72px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}

/* ── Scale option cards ── */
.ius-scale-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem}
.ius-scale-card{border:2px solid #e5e7eb;border-radius:14px;padding:.875rem .75rem;text-align:center;cursor:pointer;transition:all .15s;background:#fff;user-select:none}
.ius-scale-card:hover{border-color:#a5b4fc;background:#f5f3ff}
.ius-scale-card.selected{border-color:#4f46e5;background:#eef2ff;box-shadow:0 0 0 3px rgba(79,70,229,.12)}
.ius-scale-num{font-size:1.5rem;font-weight:800;color:#4f46e5;line-height:1}
.ius-scale-lbl{font-size:.7rem;font-weight:500;color:#6b7280;margin-top:.25rem}
.ius-scale-size{font-size:.65rem;color:#9ca3af;margin-top:.2rem;font-variant-numeric:tabular-nums}

/* ── Algorithm select tabs ── */
.ius-algo-tabs{display:flex;gap:.5rem;flex-wrap:wrap}
.ius-algo-tab{padding:.35rem .875rem;font-size:.75rem;font-weight:500;color:#6b7280;cursor:pointer;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;transition:all .12s;white-space:nowrap}
.ius-algo-tab:hover{background:#f5f3ff;border-color:#c4b5fd;color:#4f46e5}
.ius-algo-tab.active{background:#eef2ff;border-color:#4f46e5;color:#4f46e5;font-weight:600}

/* ── Progress bar ── */
.ius-progress-track{height:6px;background:#e5e7eb;border-radius:999px;overflow:hidden;margin:.625rem 0}
.ius-progress-fill{height:100%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:999px;transition:width .1s linear;will-change:width}

/* ── Comparison viewer ── */
.ius-compare-wrap{position:relative;overflow:hidden;border-radius:14px;cursor:col-resize;select:none;touch-action:pan-y;background:#f3f4f6}
.ius-compare-after{position:absolute;inset:0;overflow:hidden}
.ius-compare-after img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain}
.ius-compare-before img{width:100%;display:block;object-fit:contain}
.ius-compare-handle{position:absolute;top:0;bottom:0;width:3px;background:#fff;box-shadow:0 0 0 1.5px rgba(0,0,0,.18),0 2px 10px rgba(0,0,0,.22);transform:translateX(-50%);cursor:col-resize;z-index:10}
.ius-compare-handle-btn{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:34px;height:34px;border-radius:50%;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.22);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ius-compare-label{position:absolute;bottom:10px;padding:.2rem .6rem;border-radius:999px;font-size:.65rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;pointer-events:none}
.ius-compare-label-l{left:10px;background:rgba(0,0,0,.55);color:#fff}
.ius-compare-label-r{right:10px;background:rgba(79,70,229,.85);color:#fff}

/* ── Spinner ── */
.ius-spin{width:38px;height:38px;border:3.5px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:ius-rotate .8s linear infinite}
.ius-spin-sm{width:16px;height:16px;border:2.5px solid rgba(79,70,229,.25);border-top-color:#4f46e5;border-radius:50%;animation:ius-rotate .7s linear infinite}
@keyframes ius-rotate{to{transform:rotate(360deg)}}

/* ── Info/tip boxes ── */
.ius-tip{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#15803d;display:flex;gap:.5rem;align-items:flex-start}
.ius-info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#1d4ed8;display:flex;gap:.5rem;align-items:flex-start}
.ius-warn{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#92400e;display:flex;gap:.5rem;align-items:flex-start}

/* ── Stats pill row ── */
.ius-stat{display:flex;flex-direction:column;align-items:center;padding:.625rem .875rem;background:#f9fafb;border-radius:10px;border:1px solid #f3f4f6;min-width:80px}
.ius-stat-val{font-size:.9rem;font-weight:700;color:#1f2937;white-space:nowrap}
.ius-stat-lbl{font-size:.62rem;color:#9ca3af;margin-top:.15rem;text-align:center;text-transform:uppercase;letter-spacing:.05em}

/* ── Section label ── */
.ius-sec{font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#4f46e5;margin-bottom:.5rem}

/* ── Checkerboard (transparent bg preview) ── */
.ius-checker{background-image:repeating-conic-gradient(#e5e7eb 0% 25%,#f9fafb 0% 50%);background-size:16px 16px}

/* ── Mobile ── */
@media(max-width:639px){
  .ius-scale-grid{grid-template-columns:repeat(3,1fr);gap:.5rem}
  .ius-scale-num{font-size:1.2rem}
  .ius-compare-handle-btn{width:28px;height:28px}
}
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">

{{-- ══ Hero ══ --}}
<div class="bg-white border-b border-gray-100">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <rect x="3" y="3" width="8" height="8" rx="1"/>
          <rect x="13" y="3" width="8" height="8" rx="1"/>
          <rect x="3" y="13" width="8" height="8" rx="1"/>
          <rect x="13" y="13" width="8" height="8" rx="1"/>
          <line x1="12" y1="1" x2="12" y2="23"/>
          <line x1="1" y1="12" x2="23" y2="12"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-0.5">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Image Upscaler</h1>
          <span class="badge badge-primary">2x · 3x · 4x</span>
          <span class="badge" style="background:#dcfce7;color:#15803d">Browser-Only</span>
        </div>
        <p class="text-gray-500 text-sm">Enlarge images without quality loss using Lanczos resampling — runs entirely in your browser, no uploads, no AI APIs.</p>
      </div>
    </div>
    <x-breadcrumb :items="[
      ['label' => 'Home',                'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => 'Image Upscaler']
    ]"/>
  </div>
</div>

{{-- ══ Tool ══ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6"
     x-data="iusUpscaler()"
     x-init="init()"
     x-cloak>

  {{-- Error alert --}}
  <div x-show="errorMsg" x-transition
       class="alert alert-error mb-4 flex items-start gap-2">
    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span x-text="errorMsg"></span>
  </div>

  <div class="grid gap-6 lg:grid-cols-3">

    {{-- ════ Left panel (upload + settings) ════ --}}
    <div class="lg:col-span-1 space-y-4">

      {{-- ── Upload card ── --}}
      <div class="card p-5">
        <div class="ius-sec">Image Input</div>

        {{-- Drop zone --}}
        <div class="ius-drop" :class="dragging ? 'over' : ''"
             @dragover.prevent="dragging=true"
             @dragleave.prevent="dragging=false"
             @drop.prevent="onDrop($event)"
             @click="$refs.fileInput.click()"
             x-show="!srcFile">
          <div class="ius-drop-icon">
            <svg width="30" height="30" fill="none" stroke="#6366f1" stroke-width="1.8" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="3"/>
              <circle cx="8.5" cy="8.5" r="1.5" fill="#6366f1" stroke="none"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
          </div>
          <p class="text-sm font-semibold text-gray-700">
            <span x-show="!dragging">Drop image here</span>
            <span x-show="dragging" class="text-brand-600">Release to upload</span>
          </p>
          <p class="text-xs text-gray-400 mt-1">or <span class="text-brand-600 font-medium">click to browse</span></p>
          <div class="flex gap-1.5 justify-center mt-3 flex-wrap">
            <span class="badge badge-gray">JPG</span>
            <span class="badge badge-gray">PNG</span>
            <span class="badge badge-gray">WebP</span>
          </div>
          <p class="text-xs text-gray-400 mt-2">Max 25 MB</p>
        </div>

        {{-- Thumbnail + file info --}}
        <div x-show="srcFile" class="space-y-3">
          <div class="rounded-xl overflow-hidden ius-checker border border-gray-200">
            <img :src="srcPreviewUrl" alt="Source" class="w-full max-h-44 object-contain block">
          </div>
          <div class="flex items-center justify-between text-xs text-gray-500 px-0.5">
            <div class="flex items-center gap-1.5">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span x-text="fileName" class="truncate max-w-[120px]"></span>
            </div>
            <span x-text="srcDimsLabel" class="font-medium text-gray-600"></span>
          </div>
          <button class="btn btn-secondary btn-sm w-full" @click="reset()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
            Change Image
          </button>
        </div>

        <input type="file" x-ref="fileInput" class="hidden" accept="image/jpeg,image/png,image/webp"
               @change="onFileChange($event)">
      </div>

      {{-- ── Scale options ── --}}
      <div class="card p-5" x-show="srcFile">
        <div class="ius-sec">Upscale Factor</div>
        <div class="ius-scale-grid">
          <template x-for="opt in scaleOptions" :key="opt.factor">
            <div class="ius-scale-card"
                 :class="scale === opt.factor ? 'selected' : ''"
                 @click="setScale(opt.factor)">
              <div class="ius-scale-num" x-text="opt.factor + 'x'"></div>
              <div class="ius-scale-lbl" x-text="opt.label"></div>
              <div class="ius-scale-size" x-text="getOutDims(opt.factor)"></div>
            </div>
          </template>
        </div>

        {{-- Large image warning --}}
        <div class="ius-warn mt-3" x-show="willBeLarge">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          <span>Output will be large — processing may take a few seconds on slower devices.</span>
        </div>
      </div>

      {{-- ── Algorithm ── --}}
      <div class="card p-5" x-show="srcFile">
        <div class="ius-sec">Resampling Algorithm</div>
        <div class="ius-algo-tabs">
          <button class="ius-algo-tab" :class="algo==='lanczos'?'active':''" @click="algo='lanczos'">
            Lanczos <span class="opacity-60 text-xs">(best)</span>
          </button>
          <button class="ius-algo-tab" :class="algo==='bicubic'?'active':''" @click="algo='bicubic'">
            Bicubic
          </button>
          <button class="ius-algo-tab" :class="algo==='bilinear'?'active':''" @click="algo='bilinear'">
            Bilinear <span class="opacity-60 text-xs">(fast)</span>
          </button>
        </div>
        <p class="text-xs text-gray-400 mt-2">Lanczos produces the sharpest results; Bilinear is fastest.</p>
      </div>

      {{-- ── Output format ── --}}
      <div class="card p-5" x-show="srcFile">
        <div class="ius-sec">Output Format & Quality</div>
        <div class="space-y-3">
          <div>
            <label class="form-label text-xs">Format</label>
            <select x-model="outputFormat" class="form-input text-sm">
              <option value="image/png">PNG (lossless)</option>
              <option value="image/jpeg">JPEG</option>
              <option value="image/webp">WebP</option>
            </select>
          </div>
          <div x-show="outputFormat !== 'image/png'">
            <label class="form-label text-xs flex items-center justify-between">
              Quality
              <span class="font-semibold text-brand-600" x-text="quality + '%'"></span>
            </label>
            <input type="range" min="60" max="100" step="1"
                   x-model.number="quality"
                   class="w-full h-1.5 rounded-full appearance-none cursor-pointer"
                   style="accent-color:#4f46e5">
          </div>
        </div>
      </div>

      {{-- ── Upscale button ── --}}
      <div x-show="srcFile">
        <button class="btn btn-primary w-full btn-lg"
                @click="upscale()"
                :disabled="processing">
          <template x-if="!processing">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </template>
          <template x-if="processing">
            <div class="ius-spin-sm"></div>
          </template>
          <span x-text="processing ? 'Upscaling…' : 'Upscale Image'"></span>
        </button>
      </div>

    </div>{{-- /left panel --}}

    {{-- ════ Right panel (result) ════ --}}
    <div class="lg:col-span-2 space-y-4">

      {{-- ── Idle state ── --}}
      <div class="card" x-show="!srcFile && !processing && !resultUrl">
        <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:22px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem">
            <svg width="36" height="36" fill="none" stroke="#6366f1" stroke-width="1.6" viewBox="0 0 24 24">
              <rect x="3" y="3" width="8" height="8" rx="1.5"/>
              <rect x="13" y="3" width="8" height="8" rx="1.5"/>
              <rect x="3" y="13" width="8" height="8" rx="1.5"/>
              <rect x="13" y="13" width="8" height="8" rx="1.5"/>
            </svg>
          </div>
          <p class="text-base font-semibold text-gray-600">Upload an image to get started</p>
          <p class="text-sm text-gray-400 mt-1 max-w-xs">Drag & drop or click the upload area on the left to select a JPG, PNG, or WebP image.</p>
          <div class="flex flex-wrap justify-center gap-3 mt-5">
            <div class="ius-tip text-left max-w-xs">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              <span>All processing happens <strong>locally in your browser</strong> — your image is never uploaded.</span>
            </div>
          </div>
        </div>
      </div>

      {{-- ── Processing state ── --}}
      <div class="card p-8" x-show="processing">
        <div class="flex flex-col items-center text-center gap-4">
          <div class="ius-spin"></div>
          <div>
            <p class="font-semibold text-gray-800" x-text="processingMsg"></p>
            <p class="text-xs text-gray-400 mt-1">This may take a moment for large images</p>
          </div>
          <div class="w-full max-w-xs">
            <div class="ius-progress-track">
              <div class="ius-progress-fill" :style="'width:'+progress+'%'"></div>
            </div>
            <p class="text-xs text-gray-400 text-right" x-text="progress + '%'"></p>
          </div>
        </div>
      </div>

      {{-- ── Result state ── --}}
      <div x-show="resultUrl && !processing" class="space-y-4">

        {{-- Stats row --}}
        <div class="card px-5 py-4">
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="ius-stat">
              <span class="ius-stat-val" x-text="srcDimsLabel || '—'"></span>
              <span class="ius-stat-lbl">Original</span>
            </div>
            <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            <div class="ius-stat" style="border-color:#c7d2fe;background:#eef2ff">
              <span class="ius-stat-val text-brand-700" x-text="outDimsLabel || '—'"></span>
              <span class="ius-stat-lbl" x-text="scale+'x Output'"></span>
            </div>
            <div class="ius-stat">
              <span class="ius-stat-val" x-text="processTime ? processTime+'ms' : '—'"></span>
              <span class="ius-stat-lbl">Time</span>
            </div>
            <div class="ius-stat">
              <span class="ius-stat-val" x-text="outSizeLabel || '—'"></span>
              <span class="ius-stat-lbl">File Size</span>
            </div>
            <div class="ml-auto flex gap-2">
              <button class="btn btn-secondary btn-sm" @click="toggleView()" x-show="resultUrl">
                <span x-text="viewMode==='compare' ? '🖼 Single' : '⇔ Compare'"></span>
              </button>
              <a :href="resultUrl" :download="downloadName"
                 class="btn btn-primary btn-sm"
                 x-show="resultUrl">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download
              </a>
            </div>
          </div>
        </div>

        {{-- Single view --}}
        <div class="card overflow-hidden" x-show="viewMode === 'single'">
          <div class="p-3 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Upscaled Result</span>
            <span class="badge badge-primary text-xs" x-text="scale+'x · '+outDimsLabel"></span>
          </div>
          <div class="ius-checker">
            <img :src="resultUrl" alt="Upscaled" class="w-full max-h-[520px] object-contain block">
          </div>
        </div>

        {{-- Compare view --}}
        <div class="card overflow-hidden" x-show="viewMode === 'compare'">
          <div class="p-3 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Before / After Comparison</span>
            <span class="text-xs text-gray-400">Drag the divider</span>
          </div>
          <div class="ius-checker">
            <div class="ius-compare-wrap"
                 x-ref="compareWrap"
                 @pointerdown="startCompare($event)"
                 @pointermove="moveCompare($event)"
                 @pointerup="stopCompare()"
                 @pointerleave="stopCompare()"
                 style="max-height:520px">
              {{-- Before (original scaled up visually via CSS) --}}
              <div class="ius-compare-before">
                <img :src="srcPreviewUrl" alt="Before"
                     x-ref="compareImg"
                     style="width:100%;max-height:520px;object-fit:contain;display:block">
              </div>
              {{-- After --}}
              <div class="ius-compare-after"
                   :style="'clip-path: inset(0 0 0 '+comparePos+'%)'">
                <img :src="resultUrl" alt="After"
                     style="width:100%;max-height:520px;object-fit:contain">
              </div>
              {{-- Divider handle --}}
              <div class="ius-compare-handle" :style="'left:'+comparePos+'%'">
                <div class="ius-compare-handle-btn">
                  <svg width="16" height="16" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/><polyline points="9 18 3 12 9 6" transform="translate(6,0)"/></svg>
                </div>
              </div>
              {{-- Labels --}}
              <span class="ius-compare-label ius-compare-label-l">Original</span>
              <span class="ius-compare-label ius-compare-label-r" x-text="scale+'x Upscaled'"></span>
            </div>
          </div>
        </div>

        {{-- Download again / new image --}}
        <div class="flex flex-wrap gap-3">
          <a :href="resultUrl" :download="downloadName"
             class="btn btn-primary flex-1 sm:flex-none">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Upscaled Image
          </a>
          <button class="btn btn-secondary flex-1 sm:flex-none" @click="reset()">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
            Upscale Another
          </button>
        </div>

      </div>{{-- /result --}}

      {{-- ── Show settings panel on right when no file but have space ── --}}
      <div class="card p-5" x-show="srcFile && !resultUrl && !processing">
        <div class="ius-tip">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Select your upscale factor and algorithm on the left, then press <strong>Upscale Image</strong>.</span>
        </div>
        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs text-gray-500">
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="text-lg mb-1">🎨</div>
            <div class="font-medium text-gray-700">Lanczos</div>
            <div class="mt-0.5 text-gray-400">Sharpest result</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="text-lg mb-1">🔒</div>
            <div class="font-medium text-gray-700">Private</div>
            <div class="mt-0.5 text-gray-400">Stays in browser</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="text-lg mb-1">⚡</div>
            <div class="font-medium text-gray-700">Fast</div>
            <div class="mt-0.5 text-gray-400">Web Workers</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="text-lg mb-1">📥</div>
            <div class="font-medium text-gray-700">PNG / JPEG</div>
            <div class="mt-0.5 text-gray-400">Export formats</div>
          </div>
        </div>
      </div>

    </div>{{-- /right panel --}}

  </div>

  {{-- ── Bottom info cards ── --}}
  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
      <a href="{{ route('categories.show', $tool->category) }}"
         class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
        <span class="text-xl">{{ $tool->category->icon }}</span>
        <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
      </a>
    </div>

    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">How It Works</h3>
      <ol class="space-y-1.5 text-xs text-gray-600 list-decimal list-inside">
        <li>Upload your JPG, PNG, or WebP image</li>
        <li>Choose the upscale factor (2x, 3x, or 4x)</li>
        <li>Select a resampling algorithm</li>
        <li>Click <span class="font-medium">Upscale Image</span></li>
        <li>Compare before/after, then download</li>
      </ol>
    </div>

    @if($relatedTools->count() > 0)
    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
      <div class="space-y-1.5">
        @foreach($relatedTools->take(5) as $related)
        <a href="{{ route('tools.show', $related) }}"
           class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
          <span class="text-base">{{ $related->icon }}</span>
          <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">{{ $related->name }}</span>
        </a>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  @if($tool->long_description)
  <div class="card p-6 mt-4">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
    <div class="tool-prose">{!! nl2br(e($tool->long_description)) !!}</div>
  </div>
  @endif

  @if($tool->faqs->count() > 0)
  <div class="card p-6 mt-4">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
    <x-faq-list :faqs="$tool->faqs" />
  </div>
  @endif

  {{-- Hidden processing canvas --}}
  <canvas x-ref="offscreen" style="display:none"></canvas>

</div>{{-- /max-w-5xl --}}
</div>{{-- /min-h-screen --}}
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════════════
   Image Upscaler — Alpine.js component   prefix: ius-
   Algorithm: Lanczos-3 / Bicubic / Bilinear via Canvas resampling
   ═══════════════════════════════════════════════════════════════════ */

function iusUpscaler() {
  return {
    /* ── State ── */
    dragging: false,
    srcFile: null,
    srcPreviewUrl: null,
    srcW: 0,
    srcH: 0,
    fileName: '',
    scale: 2,
    algo: 'lanczos',
    outputFormat: 'image/png',
    quality: 92,

    /* ── Processing ── */
    processing: false,
    progress: 0,
    processingMsg: 'Preparing…',

    /* ── Result ── */
    resultUrl: null,
    outW: 0,
    outH: 0,
    processTime: null,
    outSizeLabel: '',
    viewMode: 'compare',   // 'compare' | 'single'

    /* ── Compare slider ── */
    comparePos: 50,
    _comparing: false,

    /* ── Error ── */
    errorMsg: '',

    scaleOptions: [
      { factor: 2, label: 'Double' },
      { factor: 3, label: 'Triple' },
      { factor: 4, label: 'Quadruple' },
    ],

    /* ── Computed helpers ── */
    get srcDimsLabel() {
      return this.srcW ? this.srcW + ' × ' + this.srcH : '';
    },
    get outDimsLabel() {
      return this.outW ? this.outW + ' × ' + this.outH : '';
    },
    get downloadName() {
      var base = this.fileName.replace(/\.[^.]+$/, '');
      var ext  = this.outputFormat === 'image/png'  ? 'png'
               : this.outputFormat === 'image/jpeg' ? 'jpg' : 'webp';
      return (base || 'image') + '_' + this.scale + 'x.' + ext;
    },
    get willBeLarge() {
      return this.srcW * this.scale > 4000 || this.srcH * this.scale > 4000;
    },

    getOutDims: function(f) {
      if (!this.srcW) return '';
      return (this.srcW * f) + '×' + (this.srcH * f);
    },

    /* ── Init ── */
    init: function() {},

    /* ── File handling ── */
    onFileChange: function(e) {
      var f = e.target.files[0];
      if (f) this._loadFile(f);
    },

    onDrop: function(e) {
      this.dragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this._loadFile(f);
    },

    _loadFile: function(file) {
      this.errorMsg = '';
      var allowed = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowed.includes(file.type)) {
        this.errorMsg = 'Unsupported file type. Please upload a JPG, PNG, or WebP image.';
        return;
      }
      if (file.size > 25 * 1024 * 1024) {
        this.errorMsg = 'File is too large. Maximum allowed size is 25 MB.';
        return;
      }

      var vm = this;
      var reader = new FileReader();
      reader.onload = function(ev) {
        var img = new Image();
        img.onload = function() {
          vm.srcW = img.naturalWidth;
          vm.srcH = img.naturalHeight;
          vm.srcFile = file;
          vm.srcPreviewUrl = ev.target.result;
          vm.fileName = file.name;
          vm.resultUrl = null;
          vm.outW = 0; vm.outH = 0;
          vm.processTime = null;
          vm.progress = 0;
        };
        img.onerror = function() {
          vm.errorMsg = 'Could not read image. The file may be corrupt or unsupported.';
        };
        img.src = ev.target.result;
      };
      reader.readAsDataURL(file);
    },

    setScale: function(f) { this.scale = f; },

    /* ── Reset ── */
    reset: function() {
      this.srcFile = null;
      this.srcPreviewUrl = null;
      this.srcW = 0; this.srcH = 0;
      this.fileName = '';
      this.resultUrl = null;
      this.outW = 0; this.outH = 0;
      this.processTime = null;
      this.outSizeLabel = '';
      this.progress = 0;
      this.errorMsg = '';
      this.comparePos = 50;
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    toggleView: function() {
      this.viewMode = this.viewMode === 'compare' ? 'single' : 'compare';
    },

    /* ════ Main upscale routine ════ */
    upscale: async function() {
      if (!this.srcFile || this.processing) return;

      this.processing  = true;
      this.progress    = 0;
      this.resultUrl   = null;
      this.errorMsg    = '';
      this.processingMsg = 'Loading image…';

      var vm = this;

      try {
        /* 1. Load source into an ImageData */
        var t0 = performance.now();

        var srcImg = await new Promise(function(resolve, reject) {
          var img = new Image();
          img.onload  = function() { resolve(img); };
          img.onerror = function() { reject(new Error('Failed to load image.')); };
          img.src = vm.srcPreviewUrl;
        });

        var srcW = srcImg.naturalWidth;
        var srcH = srcImg.naturalHeight;
        var dstW = srcW * vm.scale;
        var dstH = srcH * vm.scale;

        vm.outW = dstW;
        vm.outH = dstH;

        /* Cap to prevent tab crash: 8000×8000 = 64MP should be safe */
        if (dstW > 8000 || dstH > 8000) {
          throw new Error('Output would be too large (' + dstW + '×' + dstH + '). Try a smaller scale factor or a smaller source image.');
        }

        vm.processingMsg = 'Reading pixel data…';
        vm.progress = 8;

        /* Draw source to an offscreen canvas to get raw pixel data */
        var srcCanvas = document.createElement('canvas');
        srcCanvas.width  = srcW;
        srcCanvas.height = srcH;
        var srcCtx = srcCanvas.getContext('2d', { willReadFrequently: true });
        srcCtx.drawImage(srcImg, 0, 0);
        var srcData = srcCtx.getImageData(0, 0, srcW, srcH);
        var src = srcData.data;

        vm.progress = 15;
        vm.processingMsg = 'Resampling (' + vm.algo + ')…';

        /* Yield to browser to update progress indicator */
        await new Promise(function(r){ setTimeout(r, 0); });

        /* ── Resample ── */
        var dstData;
        if (vm.algo === 'bilinear') {
          dstData = vm._bilinear(src, srcW, srcH, dstW, dstH, function(p) {
            vm.progress = 15 + Math.round(p * 75);
          });
        } else if (vm.algo === 'bicubic') {
          dstData = await vm._bicubic(src, srcW, srcH, dstW, dstH, function(p) {
            vm.progress = 15 + Math.round(p * 75);
          });
        } else {
          /* lanczos */
          dstData = await vm._lanczos(src, srcW, srcH, dstW, dstH, function(p) {
            vm.progress = 15 + Math.round(p * 75);
          });
        }

        vm.progress = 92;
        vm.processingMsg = 'Encoding output…';
        await new Promise(function(r){ setTimeout(r, 0); });

        /* ── Write result to visible canvas, then export ── */
        var dstCanvas = vm.$refs.offscreen;
        dstCanvas.width  = dstW;
        dstCanvas.height = dstH;
        var dstCtx = dstCanvas.getContext('2d');
        var imgData = new ImageData(dstData, dstW, dstH);
        dstCtx.putImageData(imgData, 0, 0);

        var q = vm.outputFormat === 'image/png' ? undefined : vm.quality / 100;
        var dataUrl = dstCanvas.toDataURL(vm.outputFormat, q);

        vm.progress = 100;
        vm.processTime = Math.round(performance.now() - t0);

        /* Estimate file size from base64 length */
        var b64 = dataUrl.split(',')[1] || '';
        var bytes = Math.round(b64.length * 0.75);
        vm.outSizeLabel = bytes < 1048576
          ? Math.round(bytes / 1024) + ' KB'
          : (bytes / 1048576).toFixed(1) + ' MB';

        vm.resultUrl = dataUrl;
        vm.viewMode  = 'compare';
        vm.comparePos = 50;

      } catch (err) {
        vm.errorMsg = err.message || 'Upscaling failed. Please try again.';
      } finally {
        vm.processing = false;
      }
    },

    /* ════ Bilinear resampling ════ */
    _bilinear: function(src, sw, sh, dw, dh, onProgress) {
      var dst  = new Uint8ClampedArray(dw * dh * 4);
      var xRat = sw / dw;
      var yRat = sh / dh;
      var total = dh;

      for (var y = 0; y < dh; y++) {
        var gv  = y * yRat;
        var y0  = Math.floor(gv);
        var y1  = Math.min(y0 + 1, sh - 1);
        var yd  = gv - y0;

        for (var x = 0; x < dw; x++) {
          var gu  = x * xRat;
          var x0  = Math.floor(gu);
          var x1  = Math.min(x0 + 1, sw - 1);
          var xd  = gu - x0;

          var i00 = (y0 * sw + x0) * 4;
          var i10 = (y0 * sw + x1) * 4;
          var i01 = (y1 * sw + x0) * 4;
          var i11 = (y1 * sw + x1) * 4;

          var oi  = (y * dw + x) * 4;

          for (var c = 0; c < 4; c++) {
            var top    = src[i00+c] * (1 - xd) + src[i10+c] * xd;
            var bottom = src[i01+c] * (1 - xd) + src[i11+c] * xd;
            dst[oi+c]  = top * (1 - yd) + bottom * yd;
          }
        }

        if (onProgress && y % 50 === 0) onProgress(y / total);
      }

      onProgress && onProgress(1);
      return dst;
    },

    /* ════ Bicubic resampling (Keys cubic) ════ */
    _bicubic: async function(src, sw, sh, dw, dh, onProgress) {
      var dst  = new Uint8ClampedArray(dw * dh * 4);
      var xRat = sw / dw;
      var yRat = sh / dh;

      function cubic(t) {
        var at = Math.abs(t);
        if (at < 1) return 1.5*at*at*at - 2.5*at*at + 1;
        if (at < 2) return -0.5*at*at*at + 2.5*at*at - 4*at + 2;
        return 0;
      }

      function clampPx(v) { return Math.max(0, Math.min(255, Math.round(v))); }

      var CHUNK = 64;

      for (var y = 0; y < dh; y++) {
        var gv = (y + 0.5) * yRat - 0.5;
        var gy = Math.floor(gv);
        var dy = gv - gy;

        for (var x = 0; x < dw; x++) {
          var gu = (x + 0.5) * xRat - 0.5;
          var gx = Math.floor(gu);
          var dx = gu - gx;

          var cr = 0, cg = 0, cb = 0, ca = 0;

          for (var m = -1; m <= 2; m++) {
            var wy = cubic(dy - m);
            var py = Math.max(0, Math.min(sh - 1, gy + m));
            for (var n = -1; n <= 2; n++) {
              var wx  = cubic(dx - n);
              var px  = Math.max(0, Math.min(sw - 1, gx + n));
              var idx = (py * sw + px) * 4;
              var w   = wy * wx;
              cr += src[idx    ] * w;
              cg += src[idx + 1] * w;
              cb += src[idx + 2] * w;
              ca += src[idx + 3] * w;
            }
          }

          var oi = (y * dw + x) * 4;
          dst[oi    ] = clampPx(cr);
          dst[oi + 1] = clampPx(cg);
          dst[oi + 2] = clampPx(cb);
          dst[oi + 3] = clampPx(ca);
        }

        if (y % CHUNK === 0) {
          onProgress && onProgress(y / dh);
          await new Promise(function(r){ setTimeout(r, 0); });
        }
      }

      onProgress && onProgress(1);
      return dst;
    },

    /* ════ Lanczos-3 resampling ════ */
    _lanczos: async function(src, sw, sh, dw, dh, onProgress) {
      var dst  = new Uint8ClampedArray(dw * dh * 4);
      var xRat = sw / dw;
      var yRat = sh / dh;
      var A    = 3; /* Lanczos window */

      function sinc(x) {
        if (x === 0) return 1;
        var px = Math.PI * x;
        return Math.sin(px) / px;
      }

      function lanczosKernel(x) {
        if (Math.abs(x) >= A) return 0;
        return sinc(x) * sinc(x / A);
      }

      function clampPx(v) { return Math.max(0, Math.min(255, Math.round(v))); }

      /* Pre-compute horizontal coefficients */
      var xContribs = new Array(dw);
      for (var ox = 0; ox < dw; ox++) {
        var cx   = (ox + 0.5) * xRat - 0.5;
        var left = Math.floor(cx) - A + 1;
        var right= Math.floor(cx) + A;
        var coeffs = [];
        var wsum = 0;
        for (var sx = left; sx <= right; sx++) {
          var w = lanczosKernel(cx - sx);
          if (w !== 0) {
            var csx = Math.max(0, Math.min(sw - 1, sx));
            coeffs.push({ px: csx, w: w });
            wsum += w;
          }
        }
        if (wsum !== 0) for (var k = 0; k < coeffs.length; k++) coeffs[k].w /= wsum;
        xContribs[ox] = coeffs;
      }

      /* Horizontal pass → intermediate buffer */
      var tmp  = new Float32Array(dw * sh * 4);

      for (var iy = 0; iy < sh; iy++) {
        for (var ix = 0; ix < dw; ix++) {
          var coeffs = xContribs[ix];
          var r = 0, g = 0, b = 0, a = 0;
          for (var k = 0; k < coeffs.length; k++) {
            var idx = (iy * sw + coeffs[k].px) * 4;
            var w   = coeffs[k].w;
            r += src[idx    ] * w;
            g += src[idx + 1] * w;
            b += src[idx + 2] * w;
            a += src[idx + 3] * w;
          }
          var ti = (iy * dw + ix) * 4;
          tmp[ti    ] = r;
          tmp[ti + 1] = g;
          tmp[ti + 2] = b;
          tmp[ti + 3] = a;
        }
      }

      onProgress && onProgress(0.45);
      await new Promise(function(r){ setTimeout(r, 0); });

      /* Pre-compute vertical coefficients */
      var yContribs = new Array(dh);
      for (var oy = 0; oy < dh; oy++) {
        var cy   = (oy + 0.5) * yRat - 0.5;
        var top  = Math.floor(cy) - A + 1;
        var bot  = Math.floor(cy) + A;
        var coeffs = [];
        var wsum = 0;
        for (var sy = top; sy <= bot; sy++) {
          var w = lanczosKernel(cy - sy);
          if (w !== 0) {
            var csy = Math.max(0, Math.min(sh - 1, sy));
            coeffs.push({ py: csy, w: w });
            wsum += w;
          }
        }
        if (wsum !== 0) for (var k = 0; k < coeffs.length; k++) coeffs[k].w /= wsum;
        yContribs[oy] = coeffs;
      }

      /* Vertical pass → final buffer */
      var CHUNK = 64;

      for (var dy = 0; dy < dh; dy++) {
        var ycs = yContribs[dy];
        for (var dx = 0; dx < dw; dx++) {
          var r = 0, g = 0, b = 0, a = 0;
          for (var k = 0; k < ycs.length; k++) {
            var ti = (ycs[k].py * dw + dx) * 4;
            var w  = ycs[k].w;
            r += tmp[ti    ] * w;
            g += tmp[ti + 1] * w;
            b += tmp[ti + 2] * w;
            a += tmp[ti + 3] * w;
          }
          var oi = (dy * dw + dx) * 4;
          dst[oi    ] = clampPx(r);
          dst[oi + 1] = clampPx(g);
          dst[oi + 2] = clampPx(b);
          dst[oi + 3] = clampPx(a);
        }

        if (dy % CHUNK === 0) {
          onProgress && onProgress(0.45 + 0.55 * (dy / dh));
          await new Promise(function(r){ setTimeout(r, 0); });
        }
      }

      onProgress && onProgress(1);
      return dst;
    },

    /* ════ Compare slider ════ */
    startCompare: function(e) {
      this._comparing = true;
      this.$refs.compareWrap && this.$refs.compareWrap.setPointerCapture(e.pointerId);
      this._updateCompare(e);
    },

    moveCompare: function(e) {
      if (!this._comparing) return;
      this._updateCompare(e);
    },

    stopCompare: function() {
      this._comparing = false;
    },

    _updateCompare: function(e) {
      var el   = this.$refs.compareWrap;
      if (!el) return;
      var rect = el.getBoundingClientRect();
      var pct  = ((e.clientX - rect.left) / rect.width) * 100;
      this.comparePos = Math.max(0, Math.min(100, pct));
    },
  };
}
</script>
@endpush
