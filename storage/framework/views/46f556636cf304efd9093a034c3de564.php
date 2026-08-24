

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>

<style>
/* ── Object Remover Styles (prefix: or-) ── */
.or-hero{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#4338ca 100%);border-bottom:1px solid #3730a3;padding:2rem 0}
.or-hero h1{color:#fff}
.or-badge{display:inline-flex;align-items:center;gap:.375rem;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#e0e7ff;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* Upload drop zone */
.or-drop{border:2px dashed #a5b4fc;border-radius:14px;padding:3.5rem 2rem;text-align:center;cursor:pointer;background:#f5f3ff;transition:all .2s}
.or-drop:hover,.or-drop.active{border-color:#4f46e5;background:#eef2ff}
.or-drop-icon{width:60px;height:60px;background:#e0e7ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}

/* Editor card */
.or-editor{background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)}

/* Toolbar */
.or-toolbar{display:flex;align-items:center;justify-content:space-between;padding:.625rem 1rem;background:#f8fafc;border-bottom:1px solid #e5e7eb;gap:.5rem;flex-wrap:wrap}
.or-tl{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;min-width:0}
.or-tr{display:flex;align-items:center;gap:.375rem;flex-wrap:wrap}
.or-fname{font-size:.8125rem;font-weight:600;color:#374151;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px}
.or-divider{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}

/* Buttons */
.or-btn{display:inline-flex;align-items:center;gap:.3rem;padding:.375rem .75rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer;background:#fff;transition:all .12s;white-space:nowrap;line-height:1}
.or-btn:hover:not(:disabled){border-color:#a5b4fc;color:#4338ca;background:#eef2ff}
.or-btn:disabled{opacity:.4;cursor:not-allowed}
.or-btn.active{border-color:#4f46e5;background:#eef2ff;color:#4338ca}
.or-btn-primary{background:linear-gradient(135deg,#4f46e5,#7c3aed);border-color:#4f46e5;color:#fff}
.or-btn-primary:hover:not(:disabled){background:linear-gradient(135deg,#4338ca,#6d28d9);border-color:#4338ca;color:#fff}
.or-btn-green{background:linear-gradient(135deg,#16a34a,#15803d);border-color:#16a34a;color:#fff}
.or-btn-green:hover:not(:disabled){background:linear-gradient(135deg,#15803d,#166534);border-color:#15803d;color:#fff}
.or-btn-red{border-color:#fca5a5;color:#dc2626}
.or-btn-red:hover:not(:disabled){background:#fef2f2;border-color:#ef4444}

/* Body layout */
.or-body{display:flex;flex-direction:column}
@media(min-width:860px){.or-body{flex-direction:row;min-height:520px}}

/* Sidebar */
.or-sidebar{width:100%;border-bottom:1px solid #e5e7eb;background:#fff;flex-shrink:0}
@media(min-width:860px){.or-sidebar{width:240px;border-bottom:none;border-right:1px solid #e5e7eb;overflow-y:auto;max-height:560px}}

/* Sidebar panels */
.or-panel{padding:.875rem 1rem}
.or-sec{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#4f46e5;margin:.875rem 0 .5rem}
.or-sec:first-child{margin-top:0}

/* Brush mode toggle */
.or-mode-row{display:grid;grid-template-columns:1fr 1fr;gap:.375rem;margin-bottom:.75rem}
.or-mode-btn{display:flex;align-items:center;justify-content:center;gap:.375rem;padding:.5rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer;background:#fff;transition:all .12s}
.or-mode-btn:hover{border-color:#a5b4fc;background:#eef2ff}
.or-mode-btn.active{border-color:#4f46e5;background:#eef2ff;color:#4338ca}
.or-mode-btn.erase.active{border-color:#0891b2;background:#ecfeff;color:#0e7490}

/* Brush size */
.or-slider-row{margin-bottom:.75rem}
.or-slider-header{display:flex;justify-content:space-between;margin-bottom:.3rem}
.or-slider-lbl{font-size:.75rem;font-weight:600;color:#374151}
.or-slider-val{font-size:.75rem;font-weight:600;color:#4f46e5}
.or-range{-webkit-appearance:none;appearance:none;width:100%;height:4px;border-radius:999px;background:linear-gradient(to right,#4f46e5 var(--pct,50%),#e5e7eb var(--pct,50%));outline:none;cursor:pointer}
.or-range::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 1px 4px rgba(79,70,229,.4);cursor:pointer}
.or-range::-moz-range-thumb{width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;cursor:pointer;border:none}

/* Hotkey hint */
.or-hint-row{display:flex;align-items:center;gap:.375rem;font-size:.7rem;color:#9ca3af;margin-bottom:.3rem}
.or-kbd{display:inline-block;padding:.1rem .3rem;border:1px solid #d1d5db;border-radius:3px;background:#f9fafb;font-size:.65rem;font-family:monospace;color:#6b7280}

/* Canvas col */
.or-canvas-col{flex:1;display:flex;flex-direction:column;min-width:0;background:#1a1a2e}
.or-canvas-wrap{flex:1;display:flex;align-items:center;justify-content:center;overflow:auto;padding:12px;background:repeating-conic-gradient(#2d2d44 0% 25%,#1a1a2e 0% 50%) 0 0/20px 20px;position:relative;min-height:280px}
.or-canvas-wrap canvas{display:block;max-width:100%;box-shadow:0 4px 20px rgba(0,0,0,.5);border-radius:2px}

/* Processing overlay */
.or-processing-overlay{position:absolute;inset:0;background:rgba(17,17,46,.8);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1rem;z-index:10}
.or-spin{width:40px;height:40px;border:3px solid rgba(79,70,229,.25);border-top-color:#6366f1;border-radius:50%;animation:or-rotate .75s linear infinite}
@keyframes or-rotate{to{transform:rotate(360deg)}}

/* Status bar */
.or-status{padding:.35rem .875rem;background:#111827;display:flex;align-items:center;justify-content:space-between;font-size:.7rem;color:#6b7280;gap:.5rem;flex-wrap:wrap}

/* View tabs */
.or-view-tabs{display:flex;gap:.375rem}
.or-view-tab{padding:.3rem .6rem;border-radius:5px;font-size:.7rem;font-weight:600;cursor:pointer;border:1px solid transparent;transition:all .12s;color:#6b7280}
.or-view-tab:hover{color:#4f46e5}
.or-view-tab.active{background:#1e1b4b;border-color:#4f46e5;color:#a5b4fc}

/* Tip callout */
.or-tip{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#15803d;display:flex;gap:.5rem;align-items:flex-start;margin-bottom:.75rem}

/* Radius select */
.or-select{width:100%;padding:.4rem .625rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;background:#fff}
.or-select:focus{outline:none;border-color:#4f46e5}
</style>

<!-- Hero -->
<div class="or-hero">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex items-start gap-4">
      <div style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
          <path d="M15 9l-6 6M9 9l6 6" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold"><?php echo e($tool->name); ?></h1>
          <span class="or-badge">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
            Browser-Only &middot; Privacy-First
          </span>
        </div>
        <p class="text-sm max-w-2xl" style="color:#c7d2fe">Paint over any object with the brush, then click Remove — the tool fills the region with surrounding content using the Telea inpainting algorithm.</p>
      </div>
    </div>
  </div>
</div>

<!-- Wrapper -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="orEditor()">

  <!-- ══ UPLOAD PHASE ══ -->
  <div x-show="phase==='upload'" x-transition>
    <div class="max-w-xl mx-auto">
      <div
        class="or-drop"
        :class="{ active: isDragging }"
        @dragover.prevent="isDragging=true"
        @dragleave.prevent="isDragging=false"
        @drop.prevent="onDrop($event)"
        @click="$refs.fileIn.click()"
      >
        <div class="or-drop-icon">
          <svg width="30" height="30" fill="none" stroke="#4f46e5" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/>
          </svg>
        </div>
        <p class="text-gray-800 font-semibold text-lg mb-1">Drop your image here</p>
        <p class="text-gray-500 text-sm mb-3">or click to browse files</p>
        <p class="text-xs text-gray-400">JPG, PNG, WEBP, BMP &mdash; any size</p>
        <input type="file" x-ref="fileIn" accept="image/*" class="hidden" @change="onFileChange($event)">
      </div>
      <p x-show="uploadError" class="mt-3 text-sm text-red-600 text-center" x-text="uploadError"></p>
      <p class="mt-4 text-xs text-center text-gray-400">Images are processed 100% in your browser. Nothing is uploaded.</p>
    </div>
  </div>

  <!-- ══ EDITOR PHASE ══ -->
  <div x-show="phase==='editing'" x-cloak x-transition>
    <div class="or-editor">

      <!-- Toolbar -->
      <div class="or-toolbar">
        <div class="or-tl">
          <button class="or-btn" @click="phase='upload'; resetAll()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/></svg>
            New
          </button>
          <div class="or-divider"></div>
          <span class="or-fname" x-text="fileName"></span>
          <span style="font-size:.7rem;color:#9ca3af" x-text="imgDims"></span>
        </div>
        <div class="or-tr">
          <button class="or-btn" :disabled="!canUndo" @click="undoMask()" title="Undo brush (Ctrl+Z)">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
            Undo
          </button>
          <button class="or-btn" :disabled="!canRedo" @click="redoMask()" title="Redo brush (Ctrl+Y)">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
            Redo
          </button>
          <div class="or-divider"></div>
          <button class="or-btn or-btn-red" @click="clearMask()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Clear Mask
          </button>
          <div class="or-divider"></div>
          <!-- View tabs (only when result exists) -->
          <template x-if="hasResult">
            <div class="or-view-tabs">
              <button class="or-view-tab" :class="{active:viewMode==='mask'}" @click="setView('mask')">Original</button>
              <button class="or-view-tab" :class="{active:viewMode==='result'}" @click="setView('result')">Result</button>
              <button class="or-view-tab" :class="{active:viewMode==='compare'}" @click="setView('compare')">Compare</button>
            </div>
          </template>
          <div class="or-divider"></div>
          <button
            class="or-btn or-btn-primary"
            :disabled="processing || maskEmpty"
            @click="runRemove()"
          >
            <template x-if="!processing">
              <span style="display:flex;align-items:center;gap:.3rem">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6" stroke-linecap="round"/></svg>
                Remove Object
              </span>
            </template>
            <template x-if="processing">
              <span style="display:flex;align-items:center;gap:.375rem">
                <span style="width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:or-rotate .7s linear infinite;display:block"></span>
                Processing…
              </span>
            </template>
          </button>
          <template x-if="hasResult">
            <button class="or-btn or-btn-green" @click="downloadResult()">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Download
            </button>
          </template>
        </div>
      </div>

      <!-- Body -->
      <div class="or-body">

        <!-- Sidebar -->
        <div class="or-sidebar">
          <div class="or-panel">

            <div class="or-tip">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
              <span>Paint red over the object you want to remove, then click <strong>Remove Object</strong>.</span>
            </div>

            <p class="or-sec">Brush Mode</p>
            <div class="or-mode-row">
              <button
                class="or-mode-btn"
                :class="{active: brushMode==='paint'}"
                @click="brushMode='paint'"
              >
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
                Paint
              </button>
              <button
                class="or-mode-btn erase"
                :class="{active: brushMode==='erase'}"
                @click="brushMode='erase'"
              >
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 20H7L3 16l10-10 7 7-1.5 1.5"/><path d="M6.5 17.5l5-5"/></svg>
                Erase
              </button>
            </div>

            <p class="or-sec">Brush Size</p>
            <div class="or-slider-row">
              <div class="or-slider-header">
                <span class="or-slider-lbl">Size</span>
                <span class="or-slider-val" x-text="brushSize + ' px'"></span>
              </div>
              <input
                type="range" min="3" max="120" step="1"
                x-model.number="brushSize"
                class="or-range"
                :style="'--pct:' + Math.round(((brushSize-3)/117)*100) + '%'"
                @input="e => e.target.style.setProperty('--pct', Math.round(((e.target.value-3)/117)*100)+'%')"
              >
            </div>

            <p class="or-sec">Inpaint Radius</p>
            <div style="margin-bottom:.75rem">
              <select class="or-select" x-model.number="inpaintRadius">
                <option value="5">5 px — Fine (faster)</option>
                <option value="10" selected>10 px — Standard</option>
                <option value="15">15 px — Wide (slower)</option>
                <option value="20">20 px — Max</option>
              </select>
              <p style="font-size:.7rem;color:#9ca3af;margin-top:.3rem">Larger radius blends more surrounding pixels.</p>
            </div>

            <p class="or-sec">Keyboard Shortcuts</p>
            <div style="display:flex;flex-direction:column;gap:.3rem">
              <div class="or-hint-row"><span class="or-kbd">Ctrl+Z</span> Undo mask</div>
              <div class="or-hint-row"><span class="or-kbd">Ctrl+Y</span> Redo mask</div>
              <div class="or-hint-row"><span class="or-kbd">[</span> / <span class="or-kbd">]</span> Brush size</div>
              <div class="or-hint-row"><span class="or-kbd">P</span> Paint mode</div>
              <div class="or-hint-row"><span class="or-kbd">E</span> Erase mode</div>
            </div>

          </div>
        </div><!-- /sidebar -->

        <!-- Canvas column -->
        <div class="or-canvas-col">
          <div
            class="or-canvas-wrap"
            x-ref="canvasWrap"
            :style="'cursor:' + (viewMode==='compare' ? 'ew-resize' : 'none')"
            @mousedown="onDown($event)"
            @mousemove="onMove($event)"
            @mouseup="onUp($event)"
            @mouseleave="onLeave()"
            @touchstart.prevent="onDown($event.changedTouches[0])"
            @touchmove.prevent="onMove($event.changedTouches[0])"
            @touchend.prevent="onUp($event.changedTouches[0])"
          >
            <canvas x-ref="mainCanvas" style="touch-action:none"></canvas>

            <!-- Processing overlay -->
            <div class="or-processing-overlay" x-show="processing">
              <div class="or-spin"></div>
              <div style="color:#e0e7ff;font-size:.9rem;font-weight:600">Removing object…</div>
              <div style="color:#a5b4fc;font-size:.78rem" x-text="progressMsg"></div>
            </div>
          </div>

          <!-- Status bar -->
          <div class="or-status">
            <span>Canvas: <strong style="color:#d1d5db" x-text="dispDims"></strong></span>
            <span>Mask: <strong :style="maskEmpty ? 'color:#6b7280' : 'color:#f87171'" x-text="maskEmpty ? 'empty' : maskPx + ' px painted'"></strong></span>
            <template x-if="hasResult">
              <span style="color:#86efac">&#10003; Result ready — click Download to save</span>
            </template>
          </div>
        </div>

      </div><!-- /body -->
    </div><!-- /editor -->
  </div><!-- /editing phase -->

</div><!-- /wrapper -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ══════════════════════════════════════════════════════════════════
   Object Remover — Telea Fast Marching Method Inpainting (2004)
   Pure JavaScript, no dependencies
   ══════════════════════════════════════════════════════════════════ */

// ── Min-Heap (priority queue) ──────────────────────────────────────────────
function MinHeap() {
  this._d = []; // {k: key, v: value}
  this._m = {}; // key → index
}
MinHeap.prototype = {
  push: function(k, v) {
    if (k in this._m) {
      var i = this._m[k];
      if (v < this._d[i].v) { this._d[i].v = v; this._up(i); }
      return;
    }
    var i2 = this._d.length;
    this._d.push({k:k, v:v}); this._m[k]=i2; this._up(i2);
  },
  pop: function() {
    var top = this._d[0], n = this._d.length;
    if (n === 1) { this._d.length=0; delete this._m[top.k]; return top.k; }
    var last = this._d.pop();
    this._d[0] = last; this._m[last.k]=0; delete this._m[top.k];
    this._dn(0); return top.k;
  },
  empty: function() { return this._d.length === 0; },
  _sw: function(a,b) {
    this._m[this._d[a].k]=b; this._m[this._d[b].k]=a;
    var t=this._d[a]; this._d[a]=this._d[b]; this._d[b]=t;
  },
  _up: function(i) {
    while(i>0){var p=(i-1)>>1;if(this._d[p].v<=this._d[i].v)break;this._sw(i,p);i=p;}
  },
  _dn: function(i) {
    var n=this._d.length;
    for(;;){var l=2*i+1,r=2*i+2,m=i;
      if(l<n&&this._d[l].v<this._d[m].v)m=l;
      if(r<n&&this._d[r].v<this._d[m].v)m=r;
      if(m===i)break;this._sw(i,m);i=m;}
  }
};

// ── Telea Inpainting ────────────────────────────────────────────────────────
// pixels:  Uint8ClampedArray (RGBA, length = w*h*4)  — MODIFIED IN PLACE
// mask:    Uint8Array (1=to fill, 0=known, length = w*h)
// w, h:    image dimensions
// radius:  inpainting search radius (5–20)
function telaInpaint(pixels, mask, w, h, radius) {
  var N   = w * h;
  var INF = 1e7;

  // flags: 0=KNOWN  1=BAND  2=INSIDE
  var flags = new Uint8Array(N);
  for (var i = 0; i < N; i++) flags[i] = mask[i] ? 2 : 0;

  // dist: distance from known boundary
  var dist = new Float32Array(N);
  dist.fill(INF);

  // Find initial BAND: INSIDE pixels adjacent to KNOWN pixels, dist = 0
  var heap = new MinHeap();
  for (var y = 0; y < h; y++) {
    for (var x = 0; x < w; x++) {
      var idx = y*w+x;
      if (flags[idx] !== 2) continue;
      var adj = (x>0   && flags[idx-1]===0)
             || (x<w-1 && flags[idx+1]===0)
             || (y>0   && flags[idx-w]===0)
             || (y<h-1 && flags[idx+w]===0);
      if (adj) { flags[idx]=1; dist[idx]=0; heap.push(idx, 0); }
    }
  }

  var rSq = radius * radius;

  // Process pixels in order of distance (Fast Marching Method)
  while (!heap.empty()) {
    var cur = heap.pop();
    var cx  = cur % w, cy = (cur/w)|0;
    flags[cur] = 0; // mark as KNOWN

    // ── Inpaint this pixel (Telea formula) ──
    // Gradient of distance field at (cx,cy)
    var gx = 0, gy = 0;
    if (cx>0 && cx<w-1)      gx = (dist[cy*w+cx+1] - dist[cy*w+cx-1]) * 0.5;
    else if (cx===0 && w>1)  gx = dist[cy*w+1] - dist[cur];
    else if (cx===w-1&&w>1)  gx = dist[cur] - dist[cy*w+cx-1];
    if (cy>0 && cy<h-1)      gy = (dist[(cy+1)*w+cx] - dist[(cy-1)*w+cx]) * 0.5;
    else if (cy===0 && h>1)  gy = dist[w+cx] - dist[cx];
    else if (cy===h-1&&h>1)  gy = dist[cur] - dist[(cy-1)*w+cx];
    var gl = Math.sqrt(gx*gx+gy*gy);
    if (gl > 1e-4) { gx/=gl; gy/=gl; } else { gx=0; gy=0; }

    var Ir=0,Ig=0,Ib=0, ws=0;
    var x0=Math.max(0,cx-radius), x1=Math.min(w-1,cx+radius);
    var y0=Math.max(0,cy-radius), y1=Math.min(h-1,cy+radius);

    for (var qy=y0; qy<=y1; qy++) {
      var rowOff = qy*w;
      for (var qx=x0; qx<=x1; qx++) {
        var qi = rowOff+qx;
        if (flags[qi] !== 0) continue; // only KNOWN pixels
        var rx=cx-qx, ry=cy-qy;
        var d2=rx*rx+ry*ry;
        if (d2 > rSq) continue;
        var d = Math.sqrt(d2) || 0.01;
        // direction weight (dot with gradient)
        var dw = Math.abs((rx/d)*gx + (ry/d)*gy) + 1e-5;
        // distance weight (inv square)
        var fw = 1.0 / d2;
        // level-set weight
        var lw = 1.0 / (1.0 + Math.abs(dist[qi] - dist[cur]));
        var wt = dw * fw * lw;
        var qc = qi*4;
        Ir += wt * pixels[qc];
        Ig += wt * pixels[qc+1];
        Ib += wt * pixels[qc+2];
        ws += wt;
      }
    }

    if (ws > 0) {
      var pc = cur*4;
      pixels[pc]   = Math.max(0, Math.min(255, (Ir/ws + 0.5)|0));
      pixels[pc+1] = Math.max(0, Math.min(255, (Ig/ws + 0.5)|0));
      pixels[pc+2] = Math.max(0, Math.min(255, (Ib/ws + 0.5)|0));
      pixels[pc+3] = 255;
    }

    // Propagate to INSIDE 4-neighbors
    var nbrs = [];
    if (cx>0)   nbrs.push(cur-1);
    if (cx<w-1) nbrs.push(cur+1);
    if (cy>0)   nbrs.push(cur-w);
    if (cy<h-1) nbrs.push(cur+w);
    for (var ni=0; ni<nbrs.length; ni++) {
      var n = nbrs[ni];
      if (flags[n] !== 2) continue;
      // Distance = minimum of KNOWN 4-neighbors + 1
      var nx2=n%w, ny2=(n/w)|0;
      var dMin = INF;
      if (nx2>0   && flags[n-1]===0) dMin=Math.min(dMin,dist[n-1]);
      if (nx2<w-1 && flags[n+1]===0) dMin=Math.min(dMin,dist[n+1]);
      if (ny2>0   && flags[n-w]===0) dMin=Math.min(dMin,dist[n-w]);
      if (ny2<h-1 && flags[n+w]===0) dMin=Math.min(dMin,dist[n+w]);
      var nd = dMin + 1;
      if (nd < dist[n]) { dist[n]=nd; flags[n]=1; heap.push(n,nd); }
    }
  }

  return pixels;
}

// ── Post-process: soften inpaint boundary seam ──────────────────────────────
// One pass of weighted smoothing at mask boundary pixels
function softenSeam(pixels, mask, w, h) {
  var tmp = new Uint8ClampedArray(pixels);
  for (var y=1; y<h-1; y++) {
    for (var x=1; x<w-1; x++) {
      var idx = y*w+x;
      if (!mask[idx]) continue;
      // Only smooth pixels adjacent to known region
      var adjKnown = (!mask[idx-1]) || (!mask[idx+1]) || (!mask[idx-w]) || (!mask[idx+w]);
      if (!adjKnown) continue;
      var pc = idx*4;
      for (var c=0; c<3; c++) {
        tmp[pc+c] = Math.round(
          (pixels[pc+c]*2 +
           pixels[(idx-1)*4+c] + pixels[(idx+1)*4+c] +
           pixels[(idx-w)*4+c] + pixels[(idx+w)*4+c]) / 6
        );
      }
    }
  }
  return tmp;
}

// ── Alpine Component ────────────────────────────────────────────────────────
function orEditor() {
  return {
    /* ── state ── */
    phase: 'upload',
    isDragging: false,
    uploadError: '',
    fileName: '',
    processing: false,
    progressMsg: '',
    hasResult: false,
    viewMode: 'mask', // mask | result | compare
    comparePos: 50,   // % for before/after divider
    _isDraggingCompare: false,

    /* ── image ── */
    _origImg: null,
    _resultImgEl: null,
    _dispW: 0, _dispH: 0,

    /* ── mask (offscreen canvas same size as display canvas) ── */
    _maskCanvas: null,
    _maskHistory: [],
    _maskHistIdx: -1,
    maskPx: 0,

    /* ── brush ── */
    brushMode: 'paint',
    brushSize: 30,
    isPainting: false,
    _lastX: -1, _lastY: -1,
    _cursorX: -1, _cursorY: -1,

    /* ── settings ── */
    inpaintRadius: 10,

    /* ── computed ── */
    get canUndo() { return this._maskHistIdx > 0; },
    get canRedo() { return this._maskHistIdx < this._maskHistory.length - 1; },
    get maskEmpty() { return this.maskPx === 0; },
    get imgDims() {
      if (!this._origImg) return '';
      return this._origImg.naturalWidth + '\xD7' + this._origImg.naturalHeight;
    },
    get dispDims() { return this._dispW + '\xD7' + this._dispH + ' px'; },

    /* ── init ── */
    init: function() {
      var vm = this;
      document.addEventListener('keydown', function(e) {
        if (vm.phase !== 'editing') return;
        if ((e.ctrlKey||e.metaKey) && e.key==='z') { e.preventDefault(); vm.undoMask(); }
        if ((e.ctrlKey||e.metaKey) && e.key==='y') { e.preventDefault(); vm.redoMask(); }
        if (e.key==='[') vm.brushSize = Math.max(3,  vm.brushSize - 3);
        if (e.key===']') vm.brushSize = Math.min(120, vm.brushSize + 3);
        if (e.key==='p' || e.key==='P') vm.brushMode = 'paint';
        if (e.key==='e' || e.key==='E') vm.brushMode = 'erase';
      });
    },

    /* ── file handling ── */
    onDrop: function(e) {
      this.isDragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this.loadFile(f);
    },
    onFileChange: function(e) {
      var f = e.target.files[0];
      if (f) this.loadFile(f);
    },
    loadFile: function(f) {
      this.uploadError = '';
      if (!f.type.startsWith('image/')) { this.uploadError='Please select a valid image file.'; return; }
      if (f.size > 50*1024*1024) { this.uploadError='Image too large (max 50 MB).'; return; }
      this.fileName = f.name;
      var vm = this;
      var reader = new FileReader();
      reader.onload = function(ev) {
        var img = new Image();
        img.onload = function() {
          vm._origImg = img;
          vm.hasResult = false;
          vm.viewMode = 'mask';
          vm.phase = 'editing';
          vm.$nextTick(function() { vm.setupCanvas(); });
        };
        img.src = ev.target.result;
      };
      reader.readAsDataURL(f);
    },

    /* ── canvas setup ── */
    setupCanvas: function() {
      var wrap = this.$refs.canvasWrap;
      var canvas = this.$refs.mainCanvas;
      var img = this._origImg;
      if (!wrap || !canvas || !img) return;
      var maxW = Math.max(200, wrap.clientWidth);
      var maxH = Math.max(200, Math.floor(window.innerHeight * 0.65));
      var scale = Math.min(maxW / img.naturalWidth, maxH / img.naturalHeight, 1);
      var dw = Math.max(1, Math.floor(img.naturalWidth  * scale));
      var dh = Math.max(1, Math.floor(img.naturalHeight * scale));
      canvas.width  = dw; canvas.height = dh;
      canvas.style.width  = dw+'px'; canvas.style.height = dh+'px';
      this._dispW = dw; this._dispH = dh;
      // New mask canvas
      this._maskCanvas = document.createElement('canvas');
      this._maskCanvas.width = dw; this._maskCanvas.height = dh;
      // Reset history
      this._maskHistory = []; this._maskHistIdx = -1;
      this.maskPx = 0;
      this._pushMaskHistory();
      this.renderCanvas();
    },

    /* ── render ── */
    renderCanvas: function() {
      var canvas = this.$refs.mainCanvas;
      if (!canvas || !this._origImg) return;
      var ctx = canvas.getContext('2d');
      var cw = canvas.width, ch = canvas.height;

      if (this.viewMode === 'result' && this._resultImgEl) {
        ctx.drawImage(this._resultImgEl, 0, 0, cw, ch);
        return;
      }

      if (this.viewMode === 'compare' && this._resultImgEl) {
        // Draw original (right side / full)
        ctx.drawImage(this._origImg, 0, 0, cw, ch);
        // Draw result (left side up to comparePos)
        var pos = Math.round(cw * this.comparePos / 100);
        ctx.save();
        ctx.beginPath(); ctx.rect(0,0,pos,ch); ctx.clip();
        ctx.drawImage(this._resultImgEl, 0, 0, cw, ch);
        ctx.restore();
        // Divider line
        ctx.fillStyle = '#fff';
        ctx.fillRect(pos-1, 0, 2, ch);
        // Handle circle
        ctx.beginPath(); ctx.arc(pos, ch/2, 16, 0, Math.PI*2);
        ctx.fillStyle = '#fff'; ctx.fill();
        ctx.strokeStyle = '#4f46e5'; ctx.lineWidth=2; ctx.stroke();
        ctx.fillStyle='#4f46e5'; ctx.font='bold 13px sans-serif';
        ctx.textAlign='center'; ctx.textBaseline='middle';
        ctx.fillText('‹ ›', pos, ch/2);
        // Labels
        ctx.font='bold 11px sans-serif'; ctx.fillStyle='rgba(255,255,255,.85)';
        ctx.textAlign='left';
        ctx.fillText('Result', Math.max(8,pos-50), 16);
        ctx.textAlign='right';
        ctx.fillText('Original', Math.min(cw-8,pos+50), 16);
        return;
      }

      // Mask mode: draw image + red mask overlay
      ctx.drawImage(this._origImg, 0, 0, cw, ch);
      if (this._maskCanvas) {
        ctx.save();
        ctx.globalAlpha = 0.58;
        ctx.drawImage(this._maskCanvas, 0, 0, cw, ch);
        ctx.restore();
      }

      // Brush cursor
      if (this._cursorX >= 0 && !this.processing) {
        ctx.save();
        ctx.strokeStyle = this.brushMode==='erase' ? '#22d3ee' : '#ef4444';
        ctx.lineWidth = 1.5;
        ctx.beginPath(); ctx.arc(this._cursorX, this._cursorY, this.brushSize/2, 0, Math.PI*2);
        ctx.stroke();
        // Crosshair
        var r2 = this.brushSize/2;
        ctx.strokeStyle = 'rgba(255,255,255,.5)';
        ctx.lineWidth=1;
        ctx.beginPath();
        ctx.moveTo(this._cursorX-r2-5, this._cursorY); ctx.lineTo(this._cursorX+r2+5, this._cursorY);
        ctx.moveTo(this._cursorX, this._cursorY-r2-5); ctx.lineTo(this._cursorX, this._cursorY+r2+5);
        ctx.stroke();
        ctx.restore();
      }
    },

    /* ── pointer helpers ── */
    _pos: function(e) {
      var canvas = this.$refs.mainCanvas;
      var rect = canvas.getBoundingClientRect();
      return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    },

    onDown: function(e) {
      if (this.processing) return;
      if (this.viewMode === 'compare') {
        this._isDraggingCompare = true;
        this._moveCompare(e);
        return;
      }
      if (this.viewMode !== 'mask') return;
      var p = this._pos(e);
      this.isPainting = true;
      this._lastX = p.x; this._lastY = p.y;
      this._cursorX = p.x; this._cursorY = p.y;
      this._paintAt(p.x, p.y);
    },
    onMove: function(e) {
      if (this.processing) return;
      if (this._isDraggingCompare) { this._moveCompare(e); return; }
      var p = this._pos(e);
      this._cursorX = p.x; this._cursorY = p.y;
      if (this.isPainting && this.viewMode === 'mask') {
        this._paintLine(this._lastX, this._lastY, p.x, p.y);
        this._lastX = p.x; this._lastY = p.y;
      } else {
        this.renderCanvas();
      }
    },
    onUp: function(e) {
      if (this._isDraggingCompare) { this._isDraggingCompare = false; return; }
      if (this.isPainting) {
        this.isPainting = false;
        this._pushMaskHistory();
        this._countMaskPx();
      }
    },
    onLeave: function() {
      this._isDraggingCompare = false;
      this._cursorX = -1; this._cursorY = -1;
      if (this.isPainting) { this.isPainting = false; this._pushMaskHistory(); this._countMaskPx(); }
      else this.renderCanvas();
    },
    _moveCompare: function(e) {
      var p = this._pos(e);
      this.comparePos = Math.max(0, Math.min(100, (p.x / this._dispW) * 100));
      this.renderCanvas();
    },

    /* ── brush painting ── */
    _paintAt: function(cx, cy) {
      var mc = this._maskCanvas;
      if (!mc) return;
      var mCtx = mc.getContext('2d');
      var r = this.brushSize / 2;
      if (this.brushMode === 'erase') {
        mCtx.globalCompositeOperation = 'destination-out';
        mCtx.beginPath(); mCtx.arc(cx, cy, r, 0, Math.PI*2);
        mCtx.fillStyle = 'black'; mCtx.fill();
        mCtx.globalCompositeOperation = 'source-over';
      } else {
        mCtx.fillStyle = '#ff3333';
        mCtx.beginPath(); mCtx.arc(cx, cy, r, 0, Math.PI*2);
        mCtx.fill();
      }
      this.renderCanvas();
    },
    _paintLine: function(x0, y0, x1, y1) {
      var dist = Math.sqrt((x1-x0)*(x1-x0)+(y1-y0)*(y1-y0));
      var steps = Math.max(1, Math.ceil(dist / (this.brushSize * 0.25)));
      for (var i=0; i<=steps; i++) {
        var t = i/steps;
        this._paintAt(x0+(x1-x0)*t, y0+(y1-y0)*t);
      }
    },

    /* ── mask history ── */
    _pushMaskHistory: function() {
      var mc = this._maskCanvas;
      if (!mc) return;
      this._maskHistory = this._maskHistory.slice(0, this._maskHistIdx+1);
      this._maskHistory.push(mc.toDataURL('image/png'));
      if (this._maskHistory.length > 25) this._maskHistory.shift();
      this._maskHistIdx = this._maskHistory.length - 1;
    },
    _restoreMask: function(idx) {
      var vm = this, mc = this._maskCanvas;
      if (!mc) return;
      var img = new Image();
      img.onload = function() {
        var ctx = mc.getContext('2d');
        ctx.clearRect(0, 0, mc.width, mc.height);
        ctx.drawImage(img, 0, 0);
        vm._countMaskPx();
        vm.renderCanvas();
      };
      img.src = this._maskHistory[idx];
    },
    undoMask: function() {
      if (!this.canUndo) return;
      this._maskHistIdx--;
      this._restoreMask(this._maskHistIdx);
    },
    redoMask: function() {
      if (!this.canRedo) return;
      this._maskHistIdx++;
      this._restoreMask(this._maskHistIdx);
    },
    clearMask: function() {
      var mc = this._maskCanvas;
      if (!mc) return;
      mc.getContext('2d').clearRect(0, 0, mc.width, mc.height);
      this.maskPx = 0;
      this._pushMaskHistory();
      this.hasResult = false;
      this._resultImgEl = null;
      this.viewMode = 'mask';
      this.renderCanvas();
    },
    _countMaskPx: function() {
      var mc = this._maskCanvas;
      if (!mc) { this.maskPx=0; return; }
      var d = mc.getContext('2d').getImageData(0,0,mc.width,mc.height).data;
      var count=0;
      for (var i=3; i<d.length; i+=4) if (d[i]>10) count++;
      this.maskPx = count;
    },

    /* ── inpainting ── */
    runRemove: function() {
      if (this.maskEmpty || this.processing) return;
      this.processing = true;
      this.progressMsg = 'Preparing image data…';
      var vm = this;
      // Use setTimeout to let the overlay render before heavy computation
      setTimeout(function() {
        try {
          vm._doInpaint();
        } catch(err) {
          vm.processing = false;
          alert('Error during inpainting: ' + err.message);
        }
      }, 60);
    },
    _doInpaint: function() {
      var vm = this;
      var canvas = this.$refs.mainCanvas;
      var w = canvas.width, h = canvas.height;

      // Draw original image to a temporary canvas to get pixel data
      var tmpCanvas = document.createElement('canvas');
      tmpCanvas.width=w; tmpCanvas.height=h;
      var tmpCtx = tmpCanvas.getContext('2d');
      tmpCtx.drawImage(this._origImg, 0, 0, w, h);
      var imgData = tmpCtx.getImageData(0, 0, w, h);
      var pixels  = imgData.data; // Uint8ClampedArray

      // Build mask array from mask canvas
      var maskData = this._maskCanvas.getContext('2d').getImageData(0,0,w,h);
      var mask = new Uint8Array(w*h);
      var painted = 0;
      for (var i=0; i<w*h; i++) {
        if (maskData.data[i*4+3] > 10) { mask[i]=1; painted++; }
      }

      if (painted === 0) { vm.processing=false; return; }

      vm.progressMsg = 'Running Telea inpainting (' + painted + ' px)…';

      // Run inpainting (synchronous, ~0.2–3s depending on mask size)
      telaInpaint(pixels, mask, w, h, vm.inpaintRadius);

      // Soften seam
      var softened = softenSeam(pixels, mask, w, h);
      pixels.set(softened);

      // Write result to canvas
      var resCanvas = document.createElement('canvas');
      resCanvas.width=w; resCanvas.height=h;
      var resCtx = resCanvas.getContext('2d');
      resCtx.putImageData(imgData, 0, 0);

      // Load result as Image element for fast re-rendering
      var resultImg = new Image();
      resultImg.onload = function() {
        vm._resultImgEl = resultImg;
        vm.hasResult = true;
        vm.viewMode = 'result';
        vm.processing = false;
        vm.renderCanvas();
      };
      resultImg.src = resCanvas.toDataURL('image/png');
    },

    /* ── view ── */
    setView: function(mode) {
      this.viewMode = mode;
      if (mode==='compare') this.comparePos=50;
      this.renderCanvas();
    },

    /* ── download ── */
    downloadResult: function() {
      if (!this._resultImgEl) return;
      var off = document.createElement('canvas');
      off.width=this._resultImgEl.naturalWidth;
      off.height=this._resultImgEl.naturalHeight;
      off.getContext('2d').drawImage(this._resultImgEl,0,0);
      var a = document.createElement('a');
      a.href = off.toDataURL('image/png');
      a.download = this.fileName.replace(/\.[^.]+$/,'') + '_removed.png';
      a.click();
    },

    /* ── reset ── */
    resetAll: function() {
      this._origImg=null; this._resultImgEl=null;
      this._maskCanvas=null; this._maskHistory=[]; this._maskHistIdx=-1;
      this.hasResult=false; this.viewMode='mask'; this.maskPx=0;
      this.isPainting=false; this._cursorX=-1; this._cursorY=-1;
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\object-remover.blade.php ENDPATH**/ ?>