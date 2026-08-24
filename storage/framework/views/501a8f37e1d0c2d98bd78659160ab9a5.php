

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>

<style>
/* ── Image Background Remover (prefix: bgr-) ── */

/* Upload drop zone */
.bgr-drop{border:2px dashed #a5b4fc;border-radius:16px;padding:3rem 1.5rem;text-align:center;cursor:pointer;background:#f8f7ff;transition:border-color .2s,background .2s;user-select:none}
.bgr-drop:hover,.bgr-drop.over{border-color:#4f46e5;background:#eef2ff}
.bgr-drop-icon{width:64px;height:64px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}

/* Checkerboard transparency background */
.bgr-checker{background-image:repeating-conic-gradient(#e5e7eb 0% 25%,#f9fafb 0% 50%);background-size:18px 18px}

/* Toolbar view tabs */
.bgr-tab{padding:.375rem .875rem;font-size:.8rem;font-weight:500;color:#6b7280;cursor:pointer;border-radius:7px;transition:all .12s;border:1.5px solid transparent}
.bgr-tab.active{background:#fff;color:#4f46e5;border-color:#c7d2fe;font-weight:600}
.bgr-tab:not(.active):hover{background:#f3f4f6;color:#374151}

/* Range input */
.bgr-range{-webkit-appearance:none;appearance:none;width:100%;height:4px;border-radius:999px;background:linear-gradient(to right,#4f46e5 var(--pct,50%),#e5e7eb var(--pct,50%));outline:none;cursor:pointer}
.bgr-range::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 1px 4px rgba(79,70,229,.35);cursor:pointer}
.bgr-range::-moz-range-thumb{width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;cursor:pointer;border:none}

/* Color swatch input */
.bgr-swatch{-webkit-appearance:none;appearance:none;width:40px;height:40px;border:2px solid #e5e7eb;border-radius:8px;cursor:pointer;padding:2px;background:none}
.bgr-swatch::-webkit-color-swatch-wrapper{padding:0;border-radius:5px}
.bgr-swatch::-webkit-color-swatch{border:none;border-radius:5px}

/* Color dot */
.bgr-color-dot{width:32px;height:32px;border-radius:8px;border:2px solid rgba(0,0,0,.08);flex-shrink:0}

/* Processing overlay */
.bgr-overlay{position:absolute;inset:0;background:rgba(255,255,255,.75);display:flex;align-items:center;justify-content:center;z-index:5}
.bgr-spin{width:36px;height:36px;border:3px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:bgr-spin .8s linear infinite}
@keyframes bgr-spin{to{transform:rotate(360deg)}}

/* Tip box */
.bgr-tip{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#15803d;display:flex;gap:.5rem;align-items:flex-start}
.bgr-info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#1d4ed8;display:flex;gap:.5rem;align-items:flex-start}

/* Sample mode cursor hint */
.bgr-crosshair-hint{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.75);color:#fff;font-size:.72rem;padding:.3rem .75rem;border-radius:999px;pointer-events:none;white-space:nowrap;z-index:10}

/* Section label */
.bgr-sec{font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#4f46e5;margin-bottom:.5rem}

/* Spinning download button */
.bgr-dl-spin{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:bgr-spin .7s linear infinite}
</style>


<div class="bg-white border-b border-gray-100">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <rect x="3" y="3" width="18" height="18" rx="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
          <line x1="3" y1="21" x2="21" y2="3" stroke-dasharray="2 3" stroke-width="1.5"/>
        </svg>
      </div>
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($tool->name); ?></h1>
        <p class="text-gray-500 mt-0.5 text-sm">
          Instantly remove solid or near-solid backgrounds using smart edge-flood fill — no AI models, no uploads, runs entirely in your browser.
        </p>
      </div>
    </div>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
      ['label' => 'Home',                'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => $tool->name]
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
      ['label' => 'Home',                'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => $tool->name]
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
  </div>
</div>


<div class="bg-gray-50 min-h-screen pb-16">
<div
  class="max-w-5xl mx-auto px-4 sm:px-6 py-8"
  x-data="bgrTool()"
>

  
  <div x-show="phase==='upload'" x-transition.opacity>

    
    <div x-show="errorMsg" x-cloak
         class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <span x-text="errorMsg"></span>
    </div>

    <div class="card p-6 sm:p-8 max-w-2xl mx-auto">
      <div
        class="bgr-drop"
        :class="{ over: isDragging }"
        @dragover.prevent="isDragging=true"
        @dragleave.prevent="isDragging=false"
        @drop.prevent="onDrop($event)"
        @click="$refs.fileIn.click()"
      >
        <div class="bgr-drop-icon">
          <svg width="32" height="32" fill="none" stroke="#4f46e5" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
          </svg>
        </div>
        <p class="text-gray-800 font-semibold text-lg mb-1.5">Drop your image here</p>
        <p class="text-gray-500 text-sm mb-3">or click to browse files from your device</p>
        <p class="text-xs text-gray-400">Supports <strong>JPG, PNG, WEBP</strong> &middot; Max 30 MB</p>
        <input type="file" x-ref="fileIn" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onFileChange($event)">
      </div>

      
      <div class="flex flex-wrap justify-center gap-2 mt-5 pt-5 border-t border-gray-100 text-xs text-gray-600">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 font-medium">
          <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          100% private &ndash; no uploads
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 font-medium">
          <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          Instant processing
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 font-medium">
          <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Transparent PNG output
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 font-medium">
          <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Works offline
        </span>
      </div>
    </div>
  </div>

  
  <div x-show="phase==='edit'" x-cloak x-transition.opacity>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

      
      <div class="lg:col-span-2 flex flex-col gap-3">

        
        <div x-show="errorMsg" x-cloak
             class="flex items-start gap-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span x-text="errorMsg"></span>
        </div>

        <div class="card overflow-hidden">
          
          <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100 gap-3 flex-wrap">
            
            <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-lg">
              <button class="bgr-tab" :class="{active:view==='original'}" @click="view='original'">Original</button>
              <button class="bgr-tab" :class="{active:view==='result'}" @click="view='result'">Result</button>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs text-gray-400 hidden sm:block" x-text="imgDims"></span>
              <button class="btn btn-sm btn-secondary" @click="resetAll()">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                New Image
              </button>
            </div>
          </div>

          
          <div
            class="relative overflow-hidden"
            :class="view==='result' ? 'bgr-checker' : 'bg-gray-100'"
            style="min-height:280px"
            @click="onCanvasClick($event)"
          >
            
            <img
              x-ref="origImg"
              :src="origUrl"
              class="w-full block"
              style="max-height:520px;object-fit:contain"
              x-show="view==='original'"
              alt="Original"
            >
            
            <img
              :src="resultUrl || origUrl"
              class="w-full block"
              style="max-height:520px;object-fit:contain"
              x-show="view==='result'"
              alt="Result"
            >

            
            <div class="bgr-overlay" x-show="isProcessing">
              <div class="flex flex-col items-center gap-2">
                <div class="bgr-spin"></div>
                <span class="text-xs text-brand-600 font-medium">Processing…</span>
              </div>
            </div>

            
            <div class="bgr-crosshair-hint" x-show="sampleMode && !isProcessing">
              &#x1F4A1; Click anywhere on the image to sample the background color
            </div>
          </div>
        </div>

        
        <div class="bgr-tip">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
          <div>
            <strong class="font-semibold">Best results:</strong> works best on images with a solid or near-solid background (white, black, blue, green, etc.). Adjust <em>Tolerance</em> if too much or too little is removed.
          </div>
        </div>

      </div>

      
      <div class="flex flex-col gap-4">

        
        <div class="card p-4">
          <p class="bgr-sec">Background Color</p>

          
          <div class="flex items-center gap-3 mb-3">
            <div class="bgr-color-dot" :style="'background:' + bgColor"></div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-gray-700">Current color</p>
              <p class="text-xs text-gray-400 font-mono" x-text="bgColor.toUpperCase()"></p>
            </div>
          </div>

          
          <div class="grid grid-cols-2 gap-2 mb-3">
            <button
              type="button"
              class="btn btn-sm btn-secondary"
              @click="autoDetect()"
              title="Auto-detect from image corners"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              Auto Detect
            </button>
            <button
              type="button"
              class="btn btn-sm"
              :class="sampleMode ? 'btn-primary' : 'btn-secondary'"
              @click="sampleMode = !sampleMode; if(sampleMode) view='original'"
              title="Click on image to pick color"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 3a3 3 0 0 1 3 3v1l-4 4-3-3L3 19l-1 3 3-1 11.5-11.5-3-3L14 3z"/></svg>
              <span x-text="sampleMode ? 'Cancel' : 'Sample'"></span>
            </button>
          </div>

          
          <div class="flex items-center gap-2">
            <input type="color" x-model="bgColor" class="bgr-swatch" @input="onColorInput()">
            <input
              type="text"
              x-model="bgColor"
              class="form-input font-mono text-sm flex-1"
              placeholder="#000000"
              maxlength="7"
              @input="onColorInput()"
            >
          </div>
        </div>

        
        <div class="card p-4 space-y-5">

          
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-sm font-semibold text-gray-700">Color Tolerance</label>
              <span class="text-sm font-bold text-brand-600 tabular-nums" x-text="tolerance"></span>
            </div>
            <input
              type="range" min="0" max="100" step="1"
              x-model.number="tolerance"
              class="bgr-range"
              :style="'--pct:'+tolerance+'%'"
              @input="e=>{e.target.style.setProperty('--pct',e.target.value+'%'); scheduleProcess()}"
            >
            <p class="form-help mt-1.5">Higher = removes more similar shades. Start at 20–40.</p>
          </div>

          
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-sm font-semibold text-gray-700">Edge Feather</label>
              <span class="text-sm font-bold text-brand-600 tabular-nums" x-text="feather + ' px'"></span>
            </div>
            <input
              type="range" min="0" max="12" step="1"
              x-model.number="feather"
              class="bgr-range"
              :style="'--pct:'+(feather/12*100)+'%'"
              @input="e=>{e.target.style.setProperty('--pct',(e.target.value/12*100)+'%'); scheduleProcess()}"
            >
            <p class="form-help mt-1.5">Softens jagged edges. 1–3 px recommended.</p>
          </div>
        </div>

        
        <div class="card p-4">
          <p class="bgr-sec">Export</p>

          <button
            type="button"
            class="btn btn-primary w-full btn-lg mb-2"
            :disabled="isDownloading || !resultUrl"
            @click="downloadPNG()"
          >
            <template x-if="!isDownloading">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Download PNG (transparent)
              </span>
            </template>
            <template x-if="isDownloading">
              <span class="flex items-center gap-2">
                <span class="bgr-dl-spin"></span> Processing full resolution…
              </span>
            </template>
          </button>

          
          <div class="border border-gray-200 rounded-xl p-3">
            <p class="text-xs font-semibold text-gray-600 mb-2">Download with background color</p>
            <div class="flex items-center gap-2 mb-2">
              <input type="color" x-model="dlBgColor" class="bgr-swatch w-8 h-8">
              <input type="text" x-model="dlBgColor" class="form-input font-mono text-xs flex-1" maxlength="7" placeholder="#ffffff">
            </div>
            <button
              type="button"
              class="btn btn-outline w-full text-sm"
              :disabled="isDownloading || !resultUrl"
              @click="downloadWithBg()"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
              Download with color
            </button>
          </div>
        </div>

        
        <div class="bgr-info">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
          <div class="text-xs">
            Download exports at <strong>original resolution</strong>. Processing large images may take 1–2 seconds.
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ══════════════════════════════════════════════════════════════════
   Image Background Remover
   Algorithm: BFS flood-fill from image edges + separable box blur
   for feathered alpha. Pure Canvas API — no libraries, no uploads.
   ══════════════════════════════════════════════════════════════════ */

// ── Color helpers ─────────────────────────────────────────────────────────
function _hexToRgb(hex) {
  var v = parseInt((hex||'#000000').replace('#',''), 16);
  return { r:(v>>16)&255, g:(v>>8)&255, b:v&255 };
}
function _rgbToHex(r, g, b) {
  return '#' + [r,g,b].map(function(c){
    return Math.max(0,Math.min(255,Math.round(c))).toString(16).padStart(2,'0');
  }).join('');
}
function _colorDist(r1,g1,b1,r2,g2,b2) {
  var dr=r1-r2, dg=g1-g2, db=b1-b2;
  return Math.sqrt(dr*dr + dg*dg + db*db);
}

// ── Core BFS removal ──────────────────────────────────────────────────────
// Returns new ImageData with transparent background.
// pixels = Uint8ClampedArray (RGBA), w/h = dimensions
// sr,sg,sb = seed RGB color to remove
// tolerance = 0–100
// feather = px radius for edge softening
function bfsRemoveBackground(pixels, w, h, sr, sg, sb, tolerance, feather) {
  var N = w * h;
  var MAX_DIST = (tolerance / 100) * 441.67; // max possible = sqrt(3*255^2)

  // mask[i]: 1 = remove (background), 0 = keep (foreground)
  var mask    = new Uint8Array(N);
  var visited = new Uint8Array(N);

  // Use a pre-allocated typed array as queue (much faster than push/shift)
  var queue  = new Uint32Array(N);
  var qH = 0, qT = 0;

  function tryAdd(idx) {
    if (visited[idx]) return;
    visited[idx] = 1;
    var i = idx * 4;
    if (_colorDist(pixels[i], pixels[i+1], pixels[i+2], sr, sg, sb) <= MAX_DIST) {
      mask[idx] = 1;
      queue[qT++] = idx;
    }
  }

  // Seed from all border pixels
  for (var x = 0; x < w; x++) { tryAdd(x); tryAdd((h-1)*w + x); }
  for (var y = 1; y < h-1; y++) { tryAdd(y*w); tryAdd(y*w + w-1); }

  // BFS — 8-connectivity for better corner handling
  while (qH < qT) {
    var idx = queue[qH++];
    var cx  = idx % w;
    var cy  = (idx / w) | 0;
    var x0  = cx > 0   ? cx-1 : cx;
    var x1  = cx < w-1 ? cx+1 : cx;
    var y0  = cy > 0   ? cy-1 : cy;
    var y1  = cy < h-1 ? cy+1 : cy;
    for (var ny = y0; ny <= y1; ny++) {
      for (var nx = x0; nx <= x1; nx++) {
        tryAdd(ny * w + nx);
      }
    }
  }

  // ── Build alpha channel ──
  // 0 = fully transparent (removed), 255 = fully opaque (kept)
  var alpha = new Float32Array(N);
  for (var i = 0; i < N; i++) alpha[i] = mask[i] ? 0.0 : 255.0;

  // ── Feather: separable box blur on alpha ──
  if (feather > 0) {
    var tmp = new Float32Array(N);
    var r   = feather;

    // Horizontal pass
    for (var y2 = 0; y2 < h; y2++) {
      var rowStart = y2 * w;
      // Running sum for sliding window
      var sum = 0, cnt = 0;
      // Pre-fill left edge
      for (var dx = 0; dx <= r && dx < w; dx++) { sum += alpha[rowStart+dx]; cnt++; }
      for (var x2 = 0; x2 < w; x2++) {
        tmp[rowStart+x2] = sum / cnt;
        // slide window
        var addX = x2 + r + 1;
        var rmX  = x2 - r;
        if (addX < w) { sum += alpha[rowStart+addX]; cnt++; }
        if (rmX  >= 0) { sum -= alpha[rowStart+rmX];  cnt--; }
      }
    }

    // Vertical pass
    alpha = new Float32Array(N);
    for (var x3 = 0; x3 < w; x3++) {
      var sum2 = 0, cnt2 = 0;
      for (var dy = 0; dy <= r && dy < h; dy++) { sum2 += tmp[dy*w+x3]; cnt2++; }
      for (var y3 = 0; y3 < h; y3++) {
        alpha[y3*w+x3] = sum2 / cnt2;
        var addY = y3 + r + 1;
        var rmY  = y3 - r;
        if (addY < h) { sum2 += tmp[addY*w+x3]; cnt2++; }
        if (rmY  >= 0) { sum2 -= tmp[rmY *w+x3]; cnt2--; }
      }
    }
  }

  // ── Build result ImageData ──
  var result = new ImageData(w, h);
  var rd = result.data;
  for (var i2 = 0; i2 < N; i2++) {
    var s = i2 * 4;
    rd[s]   = pixels[s];
    rd[s+1] = pixels[s+1];
    rd[s+2] = pixels[s+2];
    rd[s+3] = Math.max(0, Math.min(255, Math.round(alpha[i2])));
  }
  return result;
}

// ── Alpine Component ──────────────────────────────────────────────────────
function bgrTool() {
  return {
    /* state */
    phase: 'upload',
    isDragging: false,
    errorMsg: '',

    /* image */
    _origImg: null,
    origUrl: null,
    fileName: '',
    fileSize: '',
    imgDims: '',

    /* display-res image data (for fast preview) */
    _prevW: 0, _prevH: 0,
    _prevData: null,  // ImageData at preview res

    /* result */
    resultUrl: null,

    /* view */
    view: 'result',

    /* settings */
    bgColor:   '#000000',
    _bgRgb:    { r:0, g:0, b:0 },
    tolerance: 30,
    feather:   2,
    dlBgColor: '#ffffff',

    /* flags */
    isProcessing:  false,
    isDownloading: false,
    sampleMode:    false,
    _debounce:     null,

    /* ── init ── */
    init: function() {},

    /* ── file handling ── */
    onDrop: function(e) {
      this.isDragging = false;
      if (e.dataTransfer.files.length) this.loadFile(e.dataTransfer.files[0]);
    },
    onFileChange: function(e) {
      if (e.target.files.length) this.loadFile(e.target.files[0]);
    },
    loadFile: function(f) {
      this.errorMsg = '';
      var allowed = ['image/jpeg','image/png','image/webp'];
      if (!allowed.includes(f.type)) {
        this.errorMsg = 'Unsupported type. Please select a JPG, PNG, or WEBP image.';
        return;
      }
      if (f.size > 30 * 1024 * 1024) {
        this.errorMsg = 'File too large. Maximum size is 30 MB.';
        return;
      }
      this.fileName = f.name;
      this.fileSize = this._fmtSize(f.size);
      var vm = this;
      if (this.origUrl) URL.revokeObjectURL(this.origUrl);
      this.origUrl = URL.createObjectURL(f);
      var img = new Image();
      img.onload = function() {
        vm._origImg  = img;
        vm.imgDims   = img.naturalWidth + ' × ' + img.naturalHeight + ' px';
        vm.phase     = 'edit';
        vm.$nextTick(function() {
          vm._buildPreviewData();
          vm.autoDetect();
          vm._runProcess();
        });
      };
      img.onerror = function() { vm.errorMsg = 'Could not load image. Please try another file.'; };
      img.src = this.origUrl;
    },

    /* ── Build display-res ImageData (max 1000 px wide for live preview) ── */
    _buildPreviewData: function() {
      var img  = this._origImg;
      var maxW = Math.min(1000, img.naturalWidth);
      var sc   = maxW / img.naturalWidth;
      this._prevW = Math.max(1, Math.floor(img.naturalWidth  * sc));
      this._prevH = Math.max(1, Math.floor(img.naturalHeight * sc));
      var c   = document.createElement('canvas');
      c.width = this._prevW; c.height = this._prevH;
      var ctx = c.getContext('2d');
      ctx.drawImage(img, 0, 0, this._prevW, this._prevH);
      this._prevData = ctx.getImageData(0, 0, this._prevW, this._prevH);
    },

    /* ── Auto-detect background by sampling 10×10 corner areas ── */
    autoDetect: function() {
      if (!this._prevData) return;
      var d = this._prevData.data;
      var w = this._prevW, h = this._prevH;
      var SAMP = 10, r=0,g=0,b=0,cnt=0;
      for (var dy = 0; dy < SAMP; dy++) {
        for (var dx = 0; dx < SAMP; dx++) {
          var pts = [[dx,dy],[w-1-dx,dy],[dx,h-1-dy],[w-1-dx,h-1-dy]];
          for (var k = 0; k < 4; k++) {
            if (pts[k][0]>=0&&pts[k][0]<w&&pts[k][1]>=0&&pts[k][1]<h) {
              var i=(pts[k][1]*w+pts[k][0])*4;
              r+=d[i]; g+=d[i+1]; b+=d[i+2]; cnt++;
            }
          }
        }
      }
      if (cnt===0) return;
      var rr=r/cnt, rg=g/cnt, rb=b/cnt;
      this._bgRgb  = { r:Math.round(rr), g:Math.round(rg), b:Math.round(rb) };
      this.bgColor = _rgbToHex(rr, rg, rb);
    },

    /* ── Handle click on canvas to sample color ── */
    onCanvasClick: function(e) {
      if (!this.sampleMode || !this._prevData) return;
      var el   = this.$refs.origImg;
      if (!el) return;
      var rect = el.getBoundingClientRect();
      // account for object-fit:contain letterboxing
      var natW = this._origImg.naturalWidth, natH = this._origImg.naturalHeight;
      var boxW = rect.width, boxH = rect.height;
      var scale  = Math.min(boxW / natW, boxH / natH);
      var rendW  = natW * scale, rendH = natH * scale;
      var offX   = (boxW - rendW) / 2, offY = (boxH - rendH) / 2;
      var imgX   = (e.clientX - rect.left - offX) / rendW;
      var imgY   = (e.clientY - rect.top  - offY) / rendH;
      if (imgX < 0||imgX > 1||imgY < 0||imgY > 1) return;
      // Sample from preview data
      var px  = Math.floor(imgX * this._prevW);
      var py  = Math.floor(imgY * this._prevH);
      px = Math.max(0, Math.min(this._prevW-1, px));
      py = Math.max(0, Math.min(this._prevH-1, py));
      var pi  = (py * this._prevW + px) * 4;
      var dd  = this._prevData.data;
      this._bgRgb  = { r:dd[pi], g:dd[pi+1], b:dd[pi+2] };
      this.bgColor = _rgbToHex(dd[pi], dd[pi+1], dd[pi+2]);
      this.sampleMode = false;
      this.view = 'result';
      this._runProcess();
    },

    /* ── Color input handler ── */
    onColorInput: function() {
      if (/^#[0-9a-fA-F]{6}$/.test(this.bgColor)) {
        this._bgRgb = _hexToRgb(this.bgColor);
        this.scheduleProcess();
      }
    },

    /* ── Debounced process trigger ── */
    scheduleProcess: function() {
      clearTimeout(this._debounce);
      var vm = this;
      this._debounce = setTimeout(function() { vm._runProcess(); }, 160);
    },

    /* ── Run BFS on preview-resolution data ── */
    _runProcess: function() {
      if (!this._prevData || this.isProcessing) return;
      this.isProcessing = true;
      var vm = this;
      // Use setTimeout(0) so the processing overlay renders first
      setTimeout(function() {
        try {
          var rgb    = vm._bgRgb;
          var result = bfsRemoveBackground(
            vm._prevData.data,
            vm._prevW, vm._prevH,
            rgb.r, rgb.g, rgb.b,
            vm.tolerance, vm.feather
          );
          var c = document.createElement('canvas');
          c.width = vm._prevW; c.height = vm._prevH;
          c.getContext('2d').putImageData(result, 0, 0);
          vm.resultUrl = c.toDataURL('image/png');
          vm.view = 'result';
        } catch(ex) {
          vm.errorMsg = 'Processing error: ' + ex.message;
        }
        vm.isProcessing = false;
      }, 0);
    },

    /* ── Download transparent PNG at full resolution ── */
    downloadPNG: function() {
      if (!this._origImg || this.isDownloading) return;
      this.isDownloading = true;
      var vm = this;
      setTimeout(function() {
        try {
          var img = vm._origImg;
          var c   = document.createElement('canvas');
          c.width = img.naturalWidth; c.height = img.naturalHeight;
          var ctx = c.getContext('2d');
          ctx.drawImage(img, 0, 0);
          var fullData = ctx.getImageData(0, 0, c.width, c.height);
          var rgb      = vm._bgRgb;
          var result   = bfsRemoveBackground(
            fullData.data, c.width, c.height,
            rgb.r, rgb.g, rgb.b,
            vm.tolerance, vm.feather
          );
          ctx.putImageData(result, 0, 0);
          var a = document.createElement('a');
          a.href     = c.toDataURL('image/png', 1.0);
          a.download = vm.fileName.replace(/\.[^.]+$/, '') + '_no_bg.png';
          document.body.appendChild(a); a.click(); document.body.removeChild(a);
        } catch(ex) {
          vm.errorMsg = 'Download failed: ' + ex.message;
        }
        vm.isDownloading = false;
      }, 0);
    },

    /* ── Download with solid background color ── */
    downloadWithBg: function() {
      if (!this._origImg || this.isDownloading) return;
      this.isDownloading = true;
      var vm = this;
      setTimeout(function() {
        try {
          var img = vm._origImg;
          var c   = document.createElement('canvas');
          c.width = img.naturalWidth; c.height = img.naturalHeight;
          var ctx = c.getContext('2d');
          ctx.drawImage(img, 0, 0);
          var fullData = ctx.getImageData(0, 0, c.width, c.height);
          var rgb      = vm._bgRgb;
          var result   = bfsRemoveBackground(
            fullData.data, c.width, c.height,
            rgb.r, rgb.g, rgb.b,
            vm.tolerance, vm.feather
          );
          // Composite onto solid color
          var c2   = document.createElement('canvas');
          c2.width = c.width; c2.height = c.height;
          var ctx2 = c2.getContext('2d');
          ctx2.fillStyle = /^#[0-9a-fA-F]{6}$/.test(vm.dlBgColor) ? vm.dlBgColor : '#ffffff';
          ctx2.fillRect(0, 0, c2.width, c2.height);
          ctx.putImageData(result, 0, 0);
          ctx2.drawImage(c, 0, 0);
          var a = document.createElement('a');
          a.href     = c2.toDataURL('image/png', 1.0);
          a.download = vm.fileName.replace(/\.[^.]+$/, '') + '_bg.png';
          document.body.appendChild(a); a.click(); document.body.removeChild(a);
        } catch(ex) {
          vm.errorMsg = 'Download failed: ' + ex.message;
        }
        vm.isDownloading = false;
      }, 0);
    },

    /* ── Reset ── */
    resetAll: function() {
      if (this.origUrl) { URL.revokeObjectURL(this.origUrl); this.origUrl = null; }
      this._origImg  = null;
      this._prevData = null;
      this.resultUrl = null;
      this.fileName  = '';
      this.fileSize  = '';
      this.imgDims   = '';
      this.errorMsg  = '';
      this.sampleMode    = false;
      this.isProcessing  = false;
      this.isDownloading = false;
      this.view      = 'result';
      this.tolerance = 30;
      this.feather   = 2;
      this.phase     = 'upload';
      // reset file input
      if (this.$refs.fileIn) this.$refs.fileIn.value = '';
    },

    /* ── Helpers ── */
    _fmtSize: function(bytes) {
      if (bytes < 1024)        return bytes + ' B';
      if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\image-background-remover.blade.php ENDPATH**/ ?>