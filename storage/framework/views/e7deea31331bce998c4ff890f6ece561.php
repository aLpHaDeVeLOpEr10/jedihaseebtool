

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>

<style>
/* ── Image Editor Styles (prefix: ie-) ── */
.ie-hero{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#4338ca 100%);border-bottom:1px solid #3730a3;padding:2rem 0}
.ie-hero h1{color:#fff}
.ie-hero p{color:#c7d2fe}
.ie-badge{display:inline-flex;align-items:center;gap:.375rem;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#e0e7ff;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* ── Upload drop zone ── */
.ie-drop{border:2px dashed #a5b4fc;border-radius:14px;padding:3rem 2rem;text-align:center;cursor:pointer;background:#f5f3ff;transition:all .2s}
.ie-drop:hover,.ie-drop.active{border-color:#4f46e5;background:#eef2ff}
.ie-drop-icon{width:56px;height:56px;background:#e0e7ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem}

/* ── Editor wrapper ── */
.ie-editor{display:flex;flex-direction:column;gap:0;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.ie-toolbar{display:flex;align-items:center;justify-content:space-between;padding:.625rem 1rem;background:#f8fafc;border-bottom:1px solid #e5e7eb;gap:.5rem;flex-wrap:wrap}
.ie-toolbar-left{display:flex;align-items:center;gap:.5rem;min-width:0}
.ie-toolbar-right{display:flex;align-items:center;gap:.375rem;flex-wrap:wrap}
.ie-fname{font-size:.8125rem;font-weight:600;color:#374151;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px}
.ie-fsize{font-size:.75rem;color:#9ca3af}

/* ── Layout ── */
.ie-body{display:flex;flex-direction:column}
@media(min-width:900px){.ie-body{flex-direction:row;min-height:540px}}
.ie-sidebar{width:100%;border-bottom:1px solid #e5e7eb;background:#fff;flex-shrink:0}
@media(min-width:900px){.ie-sidebar{width:280px;border-bottom:none;border-right:1px solid #e5e7eb;overflow-y:auto;max-height:600px}}
.ie-canvas-col{flex:1;display:flex;flex-direction:column;min-width:0;background:#1a1a2e}

/* ── Tabs ── */
.ie-tabs{display:flex;border-bottom:1px solid #e5e7eb;background:#f8fafc}
.ie-tab{flex:1;padding:.5rem .25rem;font-size:.75rem;font-weight:600;color:#6b7280;cursor:pointer;border:none;background:transparent;border-bottom:2.5px solid transparent;transition:all .15s;text-align:center}
.ie-tab.active{color:#4f46e5;border-bottom-color:#4f46e5;background:#fff}
.ie-tab:hover:not(.active){color:#374151;background:#f3f4f6}

/* ── Panel content ── */
.ie-panel{padding:.875rem 1rem;overflow-y:auto}

/* ── Sliders ── */
.ie-slider-row{margin-bottom:.875rem}
.ie-slider-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem}
.ie-slider-label{font-size:.75rem;font-weight:600;color:#374151}
.ie-slider-val{font-size:.75rem;font-weight:600;color:#4f46e5;min-width:36px;text-align:right}
.ie-range{-webkit-appearance:none;appearance:none;width:100%;height:4px;border-radius:999px;background:linear-gradient(to right,#4f46e5 var(--pct,50%),#e5e7eb var(--pct,50%));outline:none;cursor:pointer}
.ie-range::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 1px 4px rgba(79,70,229,.4);cursor:pointer}
.ie-range::-moz-range-thumb{width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 1px 4px rgba(79,70,229,.4);cursor:pointer;border:none}

/* ── Filter grid ── */
.ie-filter-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.375rem}
.ie-filter-btn{border:2px solid #e5e7eb;border-radius:8px;padding:.25rem;cursor:pointer;background:#fff;transition:all .12s;text-align:center}
.ie-filter-btn:hover{border-color:#a5b4fc}
.ie-filter-btn.active{border-color:#4f46e5;background:#eef2ff}
.ie-filter-thumb{width:100%;aspect-ratio:1;object-fit:cover;border-radius:4px;display:block;margin-bottom:.2rem}
.ie-filter-name{font-size:.65rem;font-weight:600;color:#374151}
.ie-filter-btn.active .ie-filter-name{color:#4f46e5}

/* ── Transform buttons ── */
.ie-transform-grid{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.75rem}
.ie-tr-btn{display:flex;align-items:center;justify-content:center;gap:.375rem;padding:.5rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer;background:#fff;transition:all .12s}
.ie-tr-btn:hover{border-color:#a5b4fc;color:#4338ca;background:#eef2ff}
.ie-tr-btn.active{border-color:#4f46e5;background:#eef2ff;color:#4338ca}

/* ── Section label ── */
.ie-sec-lbl{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#4f46e5;margin:.875rem 0 .4rem}
.ie-sec-lbl:first-child{margin-top:0}

/* ── Text controls ── */
.ie-txt-input{width:100%;padding:.45rem .625rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;background:#fff;transition:border-color .15s}
.ie-txt-input:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.1)}
.ie-row2{display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.5rem}
.ie-color-swatch{width:32px;height:32px;border-radius:6px;border:1.5px solid #e5e7eb;cursor:pointer;padding:2px}
.ie-select{width:100%;padding:.4rem .625rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;background:#fff}
.ie-select:focus{outline:none;border-color:#4f46e5}

/* ── Toolbar buttons ── */
.ie-btn{display:inline-flex;align-items:center;gap:.3rem;padding:.375rem .75rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer;background:#fff;transition:all .12s;white-space:nowrap}
.ie-btn:hover:not(:disabled){border-color:#a5b4fc;color:#4338ca;background:#eef2ff}
.ie-btn:disabled{opacity:.4;cursor:not-allowed}
.ie-btn-primary{background:linear-gradient(135deg,#4f46e5,#7c3aed);border-color:#4f46e5;color:#fff}
.ie-btn-primary:hover:not(:disabled){background:linear-gradient(135deg,#4338ca,#6d28d9);border-color:#4338ca;color:#fff}
.ie-btn-danger{border-color:#fca5a5;color:#dc2626}
.ie-btn-danger:hover:not(:disabled){background:#fef2f2;border-color:#ef4444}
.ie-btn-green{background:linear-gradient(135deg,#16a34a,#15803d);border-color:#16a34a;color:#fff}
.ie-btn-green:hover:not(:disabled){background:linear-gradient(135deg,#15803d,#166534);border-color:#15803d;color:#fff}
.ie-divider{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}

/* ── Canvas area ── */
.ie-canvas-wrap{flex:1;display:flex;align-items:center;justify-content:center;overflow:auto;padding:12px;background:repeating-conic-gradient(#2d2d44 0% 25%,#1a1a2e 0% 50%) 0 0/20px 20px;position:relative;min-height:300px}
.ie-canvas-wrap canvas{display:block;max-width:100%;box-shadow:0 4px 20px rgba(0,0,0,.5);border-radius:2px}
.ie-crop-hint{position:absolute;bottom:12px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.7);color:#fff;font-size:.75rem;padding:.375rem .875rem;border-radius:999px;pointer-events:none;white-space:nowrap}

/* ── Resize inputs ── */
.ie-dim-row{display:grid;grid-template-columns:1fr auto 1fr;gap:.375rem;align-items:end}
.ie-lock-btn{display:flex;align-items:center;justify-content:center;width:28px;height:28px;border:1.5px solid #e5e7eb;border-radius:6px;cursor:pointer;background:#fff;flex-shrink:0}
.ie-lock-btn.on{border-color:#4f46e5;background:#eef2ff;color:#4f46e5}
.ie-lock-btn:hover{border-color:#a5b4fc}
.ie-num-input{width:100%;padding:.4rem .5rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;background:#fff}
.ie-num-input:focus{outline:none;border-color:#4f46e5}

/* ── Text items list ── */
.ie-text-item{display:flex;align-items:center;gap:.375rem;padding:.3rem .375rem;background:#f9fafb;border-radius:6px;border:1px solid #f3f4f6;margin-bottom:.3rem;font-size:.75rem;color:#374151}
.ie-text-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.ie-text-str{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ie-del-btn{width:20px;height:20px;border:none;background:transparent;color:#9ca3af;cursor:pointer;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ie-del-btn:hover{background:#fee2e2;color:#dc2626}

/* ── Spinner ── */
.ie-spin{width:16px;height:16px;border:2px solid rgba(79,70,229,.2);border-top-color:#4f46e5;border-radius:50%;animation:ie-rotate .7s linear infinite}
@keyframes ie-rotate{to{transform:rotate(360deg)}}
</style>

<!-- Hero -->
<div class="ie-hero">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex items-start gap-4">
      <div style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold"><?php echo e($tool->name); ?></h1>
          <span class="ie-badge">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
            Browser-Only &middot; Privacy-First
          </span>
        </div>
        <p class="text-sm max-w-2xl" style="color:#c7d2fe"><?php echo e($tool->description); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Tool wrapper -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="ieEditor()">

  <!-- ════ UPLOAD PHASE ════ -->
  <div x-show="phase === 'upload'" x-transition>
    <div class="max-w-2xl mx-auto">
      <div
        class="ie-drop"
        :class="{ active: isDragging }"
        @dragover.prevent="isDragging=true"
        @dragleave.prevent="isDragging=false"
        @drop.prevent="onDrop($event)"
        @click="$refs.fileInput.click()"
      >
        <div class="ie-drop-icon">
          <svg width="28" height="28" fill="none" stroke="#4f46e5" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/>
          </svg>
        </div>
        <p class="text-gray-800 font-semibold text-lg mb-1">Drop your image here</p>
        <p class="text-gray-500 text-sm mb-3">or click to browse files</p>
        <p class="text-xs text-gray-400">Supports JPG, PNG, WEBP, GIF, BMP &mdash; any size</p>
        <input type="file" x-ref="fileInput" accept="image/*" class="hidden" @change="onFileChange($event)">
      </div>
      <p x-show="uploadError" class="mt-3 text-sm text-red-600 text-center" x-text="uploadError"></p>
      <p class="mt-4 text-xs text-center text-gray-400">Your image is processed entirely in your browser &mdash; never uploaded to any server.</p>
    </div>
  </div>

  <!-- ════ EDITOR PHASE ════ -->
  <div x-show="phase === 'editing'" x-transition x-cloak>
    <div class="ie-editor">

      <!-- ── Toolbar ── -->
      <div class="ie-toolbar">
        <div class="ie-toolbar-left">
          <button class="ie-btn" @click="phase='upload'; resetAll()" title="Open new image">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/></svg>
            New
          </button>
          <div class="ie-divider"></div>
          <span class="ie-fname" x-text="fileName"></span>
          <span class="ie-fsize" x-text="imgInfo"></span>
        </div>
        <div class="ie-toolbar-right">
          <button class="ie-btn" :disabled="!canUndo" @click="undo()" title="Undo (Ctrl+Z)">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
            Undo
          </button>
          <button class="ie-btn" :disabled="!canRedo" @click="redo()" title="Redo (Ctrl+Y)">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
            Redo
          </button>
          <div class="ie-divider"></div>
          <button class="ie-btn" @click="resetAdjustments()" title="Reset brightness/contrast/filters to default">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            Reset Adj.
          </button>
          <button class="ie-btn ie-btn-danger" @click="resetFull()" title="Restore to original uploaded image">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
            Reset All
          </button>
          <div class="ie-divider"></div>
          <select x-model="exportFmt" class="ie-select" style="width:auto;padding:.35rem .5rem;font-size:.8rem">
            <option value="png">PNG</option>
            <option value="jpeg">JPEG</option>
            <option value="webp">WEBP</option>
          </select>
          <button class="ie-btn ie-btn-green" @click="downloadImage()">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download
          </button>
        </div>
      </div>

      <!-- ── Body: sidebar + canvas ── -->
      <div class="ie-body">

        <!-- ─ Controls Sidebar ─ -->
        <div class="ie-sidebar">
          <div class="ie-tabs">
            <button class="ie-tab" :class="{ active: tab==='adjust' }" @click="tab='adjust'">Adjust</button>
            <button class="ie-tab" :class="{ active: tab==='filters' }" @click="tab='filters'">Filters</button>
            <button class="ie-tab" :class="{ active: tab==='transform' }" @click="tab='transform'">Transform</button>
            <button class="ie-tab" :class="{ active: tab==='text' }" @click="tab='text'">Text</button>
          </div>

          <!-- ── Adjust tab ── -->
          <div class="ie-panel" x-show="tab==='adjust'">
            <template x-for="s in sliders" :key="s.key">
              <div class="ie-slider-row">
                <div class="ie-slider-header">
                  <span class="ie-slider-label" x-text="s.label"></span>
                  <span class="ie-slider-val" x-text="formatSliderVal(s)"></span>
                </div>
                <input
                  type="range"
                  class="ie-range"
                  :min="s.min" :max="s.max" :step="s.step"
                  x-model.number="adj[s.key]"
                  @input="updateSliderBg($event); renderCanvas()"
                  :style="'--pct:' + sliderPct(s) + '%'"
                >
              </div>
            </template>
          </div>

          <!-- ── Filters tab ── -->
          <div class="ie-panel" x-show="tab==='filters'">
            <div class="ie-filter-grid">
              <template x-for="f in filterList" :key="f.key">
                <button
                  class="ie-filter-btn"
                  :class="{ active: activePreset === f.key }"
                  @click="setPreset(f.key)"
                >
                  <img class="ie-filter-thumb" :src="thumbUrl" :style="f.css ? 'filter:' + f.css : ''" alt="">
                  <div class="ie-filter-name" x-text="f.label"></div>
                </button>
              </template>
            </div>
          </div>

          <!-- ── Transform tab ── -->
          <div class="ie-panel" x-show="tab==='transform'">
            <p class="ie-sec-lbl">Rotate &amp; Flip</p>
            <div class="ie-transform-grid">
              <button class="ie-tr-btn" @click="rotateImage(-90)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2.5 2v6h6M2.66 15.57a10 10 0 1 0 .57-8.38"/></svg>
                Rotate CCW
              </button>
              <button class="ie-tr-btn" @click="rotateImage(90)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38"/></svg>
                Rotate CW
              </button>
              <button class="ie-tr-btn" @click="flipImage('h')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 00-2 2v14a2 2 0 002 2h3m8-18h3a2 2 0 012 2v14a2 2 0 01-2 2h-3m-4-18v18"/></svg>
                Flip H
              </button>
              <button class="ie-tr-btn" @click="flipImage('v')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 8V5a2 2 0 00-2-2H5a2 2 0 00-2 2v3m18 8v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3m18-4H3"/></svg>
                Flip V
              </button>
            </div>

            <p class="ie-sec-lbl">Resize</p>
            <div class="ie-dim-row" style="margin-bottom:.4rem">
              <div>
                <label style="font-size:.7rem;color:#6b7280;font-weight:600;display:block;margin-bottom:.2rem">Width (px)</label>
                <input type="number" class="ie-num-input" x-model.number="resizeW" @input="onResizeWChange()" min="1" max="8000">
              </div>
              <div>
                <button class="ie-lock-btn" :class="{ on: lockRatio }" @click="lockRatio=!lockRatio" :title="lockRatio?'Unlock ratio':'Lock ratio'">
                  <svg x-show="lockRatio" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                  <svg x-show="!lockRatio" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 019.9-1"/></svg>
                </button>
              </div>
              <div>
                <label style="font-size:.7rem;color:#6b7280;font-weight:600;display:block;margin-bottom:.2rem">Height (px)</label>
                <input type="number" class="ie-num-input" x-model.number="resizeH" @input="onResizeHChange()" min="1" max="8000" :readonly="lockRatio">
              </div>
            </div>
            <button class="ie-btn ie-btn-primary" style="width:100%;justify-content:center;margin-bottom:.75rem" @click="resizeImage()">
              Apply Resize
            </button>

            <p class="ie-sec-lbl">Crop</p>
            <template x-if="!cropMode">
              <button class="ie-tr-btn" style="width:100%;margin-bottom:0" @click="enterCrop()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 2 6 6 2 6"/><polyline points="18 22 18 18 22 18"/><path d="M2 22h16V6H6V2"/><path d="M22 2L6 18"/></svg>
                Enter Crop Mode
              </button>
            </template>
            <template x-if="cropMode">
              <div>
                <p class="text-xs text-indigo-600 font-semibold mb-2">Click &amp; drag on the canvas to set crop region</p>
                <div style="display:flex;gap:.5rem">
                  <button class="ie-btn ie-btn-primary" style="flex:1;justify-content:center" @click="applyCrop()">Apply Crop</button>
                  <button class="ie-btn" style="flex:1;justify-content:center" @click="exitCrop()">Cancel</button>
                </div>
              </div>
            </template>
          </div>

          <!-- ── Text tab ── -->
          <div class="ie-panel" x-show="tab==='text'">
            <p class="ie-sec-lbl">Add Text Overlay</p>
            <div style="margin-bottom:.5rem">
              <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem">Text</label>
              <input type="text" class="ie-txt-input" x-model="txtContent" placeholder="Enter text…" maxlength="120">
            </div>
            <div class="ie-row2">
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem">Font</label>
                <select class="ie-select" x-model="txtFont">
                  <option value="Arial">Arial</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Impact">Impact</option>
                  <option value="Courier New">Courier</option>
                  <option value="Times New Roman">Times</option>
                  <option value="Verdana">Verdana</option>
                </select>
              </div>
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem">Size (px)</label>
                <input type="number" class="ie-num-input" x-model.number="txtSize" min="8" max="300" value="32">
              </div>
            </div>
            <div style="display:flex;gap:.5rem;align-items:center;margin-bottom:.625rem">
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem">Color</label>
                <div style="display:flex;gap:.375rem;align-items:center">
                  <input type="color" x-model="txtColor" class="ie-color-swatch">
                  <input type="text" class="ie-txt-input" x-model="txtColor" style="width:80px;font-size:.75rem" placeholder="#ffffff">
                </div>
              </div>
              <div style="margin-top:.875rem">
                <label style="display:flex;align-items:center;gap:.375rem;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer">
                  <input type="checkbox" x-model="txtBold" class="rounded">
                  Bold
                </label>
              </div>
            </div>

            <template x-if="!placingText">
              <button class="ie-btn ie-btn-primary" style="width:100%;justify-content:center" :disabled="!txtContent.trim()" @click="startPlaceText()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/></svg>
                Click on Canvas to Place
              </button>
            </template>
            <template x-if="placingText">
              <div>
                <p class="text-xs text-indigo-600 font-semibold mb-2">&#x1F449; Click anywhere on the image to place text</p>
                <button class="ie-btn" style="width:100%;justify-content:center" @click="placingText=false">Cancel Placement</button>
              </div>
            </template>

            <!-- Placed text list -->
            <template x-if="textItems.length > 0">
              <div style="margin-top:.75rem">
                <p class="ie-sec-lbl">Placed Text</p>
                <template x-for="(item, i) in textItems" :key="i">
                  <div class="ie-text-item">
                    <span class="ie-text-dot" :style="'background:' + item.color"></span>
                    <span class="ie-text-str" x-text="item.text"></span>
                    <button class="ie-del-btn" @click="removeText(i)">
                      <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                  </div>
                </template>
              </div>
            </template>
          </div>

        </div><!-- /sidebar -->

        <!-- ─ Canvas Column ─ -->
        <div class="ie-canvas-col">
          <div
            class="ie-canvas-wrap"
            x-ref="canvasWrap"
            :style="(cropMode ? 'cursor:crosshair' : (placingText ? 'cursor:text' : 'cursor:default'))"
            @mousedown="onPointerDown($event)"
            @mousemove="onPointerMove($event)"
            @mouseup="onPointerUp($event)"
            @mouseleave="onPointerUp($event)"
            @touchstart.prevent="onPointerDown($event.changedTouches[0])"
            @touchmove.prevent="onPointerMove($event.changedTouches[0])"
            @touchend.prevent="onPointerUp($event.changedTouches[0])"
          >
            <canvas x-ref="mainCanvas"></canvas>
            <div class="ie-crop-hint" x-show="cropMode">Drag to draw crop region &nbsp;|&nbsp; Drag corners/edge to resize &nbsp;|&nbsp; Drag inside to move</div>
            <div class="ie-crop-hint" x-show="placingText">Click on the image to place your text</div>
          </div>

          <!-- Canvas info bar -->
          <div style="padding:.4rem .875rem;background:#111827;display:flex;align-items:center;justify-content:space-between;font-size:.7rem;color:#6b7280;gap:.5rem;flex-wrap:wrap">
            <span>Canvas: <strong style="color:#d1d5db" x-text="canvasInfo"></strong></span>
            <span x-show="cropMode && cropRect">
              Crop: <strong style="color:#a5b4fc" x-text="cropInfo"></strong>
            </span>
          </div>
        </div>

      </div><!-- /body -->
    </div><!-- /editor -->
  </div><!-- /editing phase -->

</div><!-- /wrapper -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function ieEditor() {
  // ── Filter presets (CSS filter strings) ──────────────────────────────────
  var PRESETS = {
    '':         '',
    vivid:      'saturate(2.2) contrast(1.1)',
    warm:       'sepia(0.2) saturate(1.4) brightness(1.05) hue-rotate(-5deg)',
    cold:       'hue-rotate(195deg) saturate(0.75) brightness(0.95)',
    vintage:    'sepia(0.45) contrast(0.88) brightness(1.1) saturate(0.65)',
    fade:       'brightness(1.12) saturate(0.45) contrast(0.8)',
    noir:       'grayscale(1) contrast(1.45) brightness(0.85)',
    dramatic:   'contrast(1.75) saturate(1.4) brightness(0.8)',
    lomo:       'saturate(1.6) contrast(1.5) brightness(0.9) hue-rotate(5deg)',
    grayscale:  'grayscale(1)',
    sepia:      'sepia(0.9)',
    invert:     'invert(1)',
  };

  return {
    // ── Upload ──
    phase: 'upload',
    isDragging: false,
    uploadError: '',
    fileName: 'image',
    fileSize: 0,

    // ── Image state ──
    _origUrl: null,   // original uploaded data URL (never changes)
    _imgEl: null,     // current work HTMLImageElement
    _imgW: 0, _imgH: 0,
    _thumbUrl: '',    // 80x60 thumbnail for filter previews
    _dispW: 0, _dispH: 0,

    // ── Adjustments ──
    adj: { brightness: 100, contrast: 100, saturate: 100, hue: 0, blur: 0, sharpness: 0 },

    sliders: [
      { key:'brightness', label:'Brightness', min:0, max:200, step:1, dflt:100 },
      { key:'contrast',   label:'Contrast',   min:0, max:200, step:1, dflt:100 },
      { key:'saturate',   label:'Saturation', min:0, max:300, step:1, dflt:100 },
      { key:'hue',        label:'Hue Rotate', min:-180, max:180, step:1, dflt:0 },
      { key:'blur',       label:'Blur',       min:0, max:20,  step:0.5, dflt:0 },
      { key:'sharpness',  label:'Sharpness',  min:0, max:100, step:1, dflt:0 },
    ],

    // ── Filters ──
    activePreset: '',
    filterList: [
      { key:'',         label:'Original',  css:'' },
      { key:'vivid',    label:'Vivid',     css:PRESETS.vivid },
      { key:'warm',     label:'Warm',      css:PRESETS.warm },
      { key:'cold',     label:'Cold',      css:PRESETS.cold },
      { key:'vintage',  label:'Vintage',   css:PRESETS.vintage },
      { key:'fade',     label:'Fade',      css:PRESETS.fade },
      { key:'noir',     label:'Noir',      css:PRESETS.noir },
      { key:'dramatic', label:'Dramatic',  css:PRESETS.dramatic },
      { key:'lomo',     label:'Lomo',      css:PRESETS.lomo },
      { key:'grayscale',label:'B&W',       css:PRESETS.grayscale },
      { key:'sepia',    label:'Sepia',     css:PRESETS.sepia },
      { key:'invert',   label:'Invert',    css:PRESETS.invert },
    ],
    PRESETS: PRESETS,
    thumbUrl: '',

    // ── Tab ──
    tab: 'adjust',

    // ── Crop ──
    cropMode: false,
    cropRect: null,   // { x, y, w, h } normalized 0-1
    _cropDrag: null,

    // ── Resize ──
    resizeW: 0, resizeH: 0,
    lockRatio: true,
    _origRatio: 1,

    // ── Text ──
    txtContent: '',
    txtFont: 'Arial',
    txtSize: 32,
    txtColor: '#ffffff',
    txtBold: false,
    placingText: false,
    textItems: [],  // [{text,nx,ny,size,color,font,bold}]

    // ── History ──
    _history: [],   // [{url, textItems}]
    _histIdx: -1,

    // ── Export ──
    exportFmt: 'png',

    // ── Computed ──
    get canUndo() { return this._histIdx > 0; },
    get canRedo() { return this._histIdx < this._history.length - 1; },
    get imgInfo() {
      if (!this._imgW) return '';
      return this._imgW + '\xD7' + this._imgH + ' px';
    },
    get canvasInfo() {
      return this._dispW + '\xD7' + this._dispH + ' px';
    },
    get cropInfo() {
      if (!this.cropRect) return '';
      var c = this.cropRect;
      var iw = this._imgW, ih = this._imgH;
      return Math.round(c.x * iw) + ',' + Math.round(c.y * ih)
        + '  ' + Math.round(c.w * iw) + '\xD7' + Math.round(c.h * ih);
    },
    get filterString() {
      var f = [];
      var a = this.adj;
      if (a.brightness !== 100) f.push('brightness(' + (a.brightness/100) + ')');
      if (a.contrast   !== 100) f.push('contrast('   + (a.contrast/100)   + ')');
      if (a.saturate   !== 100) f.push('saturate('   + (a.saturate/100)   + ')');
      if (a.hue        !== 0)   f.push('hue-rotate(' + a.hue + 'deg)');
      if (a.blur       > 0)     f.push('blur('       + (a.blur * 0.25) + 'px)');
      var pf = this.PRESETS[this.activePreset];
      if (pf) f.push(pf);
      return f.join(' ') || 'none';
    },

    // ── Init ──────────────────────────────────────────────────────────────
    init: function() {
      var vm = this;
      vm.$watch('phase', function(v) {
        if (v === 'editing') {
          vm.$nextTick(function() { vm.setupCanvas(); vm.renderCanvas(); });
        }
      });
      // Keyboard shortcuts
      document.addEventListener('keydown', function(e) {
        if (vm.phase !== 'editing') return;
        if ((e.ctrlKey || e.metaKey) && e.key === 'z') { e.preventDefault(); vm.undo(); }
        if ((e.ctrlKey || e.metaKey) && e.key === 'y') { e.preventDefault(); vm.redo(); }
        if (e.key === 'Escape') { vm.cropMode = false; vm.placingText = false; vm.renderCanvas(); }
      });
    },

    // ── File handling ─────────────────────────────────────────────────────
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
      if (!f.type.startsWith('image/')) {
        this.uploadError = 'Please select a valid image file.';
        return;
      }
      if (f.size > 50 * 1024 * 1024) {
        this.uploadError = 'Image too large. Maximum 50 MB.';
        return;
      }
      this.fileName = f.name;
      this.fileSize = f.size;
      var vm = this;
      var reader = new FileReader();
      reader.onload = function(ev) {
        vm.initEditor(ev.target.result);
      };
      reader.readAsDataURL(f);
    },
    initEditor: function(dataUrl) {
      var vm = this;
      this._origUrl = dataUrl;
      this._history = [];
      this._histIdx = -1;
      this.textItems = [];
      this.adj = { brightness:100, contrast:100, saturate:100, hue:0, blur:0, sharpness:0 };
      this.activePreset = '';
      this.cropMode = false;
      this.cropRect = null;

      var img = new Image();
      img.onload = function() {
        vm._imgEl = img;
        vm._imgW = img.naturalWidth;
        vm._imgH = img.naturalHeight;
        vm._origRatio = img.naturalWidth / img.naturalHeight;
        vm.resizeW = img.naturalWidth;
        vm.resizeH = img.naturalHeight;
        vm._pushHistory(dataUrl);
        vm.generateThumb(dataUrl);
        vm.phase = 'editing';
      };
      img.src = dataUrl;
    },

    // ── Canvas setup & render ─────────────────────────────────────────────
    setupCanvas: function() {
      var canvas = this.$refs.mainCanvas;
      var wrap   = this.$refs.canvasWrap;
      if (!canvas || !wrap || !this._imgEl) return;
      var iw = this._imgW, ih = this._imgH;
      var maxW = Math.max(200, wrap.clientWidth - 24);
      var maxH = Math.max(200, Math.floor(window.innerHeight * 0.62));
      var scale = Math.min(maxW / iw, maxH / ih, 1);
      var cw = Math.max(1, Math.floor(iw * scale));
      var ch = Math.max(1, Math.floor(ih * scale));
      canvas.width  = cw;
      canvas.height = ch;
      canvas.style.width  = cw + 'px';
      canvas.style.height = ch + 'px';
      this._dispW = cw;
      this._dispH = ch;
    },
    renderCanvas: function() {
      var canvas = this.$refs.mainCanvas;
      if (!canvas || !this._imgEl) return;
      var ctx = canvas.getContext('2d');
      var cw = canvas.width, ch = canvas.height;
      ctx.clearRect(0, 0, cw, ch);

      // Draw image with CSS filters
      ctx.filter = this.filterString;
      ctx.drawImage(this._imgEl, 0, 0, cw, ch);
      ctx.filter = 'none';

      // Sharpness via convolution
      if (this.adj.sharpness > 0) {
        this._applySharpen(ctx, canvas, this.adj.sharpness);
      }

      // Text items
      this.textItems.forEach(function(item) {
        ctx.font = (item.bold ? 'bold ' : '') + item.size + 'px ' + item.font;
        ctx.fillStyle = item.color;
        ctx.shadowColor = 'rgba(0,0,0,0.55)';
        ctx.shadowBlur = 3;
        ctx.shadowOffsetX = 1;
        ctx.shadowOffsetY = 1;
        ctx.fillText(item.text, item.nx * cw, item.ny * ch);
      });
      ctx.shadowBlur = 0; ctx.shadowOffsetX = 0; ctx.shadowOffsetY = 0;

      // Crop overlay
      if (this.cropMode && this.cropRect) {
        this._drawCropOverlay(ctx, cw, ch);
      }
    },

    // ── Sharpen (3×3 unsharp convolution) ────────────────────────────────
    _applySharpen: function(ctx, canvas, amount) {
      var s = (amount / 100) * 0.65;
      var kernel = [0, -s, 0, -s, 1 + 4*s, -s, 0, -s, 0];
      var src = ctx.getImageData(0, 0, canvas.width, canvas.height);
      var sd = src.data;
      var w = src.width, h = src.height;
      var dst = ctx.createImageData(w, h);
      var dd = dst.data;
      for (var y = 1; y < h-1; y++) {
        for (var x = 1; x < w-1; x++) {
          var i = (y*w+x)*4;
          for (var c = 0; c < 3; c++) {
            var v = 0;
            for (var ky = 0; ky < 3; ky++)
              for (var kx = 0; kx < 3; kx++)
                v += sd[((y+ky-1)*w+(x+kx-1))*4+c] * kernel[ky*3+kx];
            dd[i+c] = v < 0 ? 0 : v > 255 ? 255 : Math.round(v);
          }
          dd[i+3] = sd[i+3];
        }
      }
      // copy border pixels
      for (var y2 = 0; y2 < h; y2++) for (var x2 = 0; x2 < w; x2++) {
        if (y2===0||y2===h-1||x2===0||x2===w-1) {
          var b=(y2*w+x2)*4; dd[b]=sd[b]; dd[b+1]=sd[b+1]; dd[b+2]=sd[b+2]; dd[b+3]=sd[b+3];
        }
      }
      ctx.putImageData(dst, 0, 0);
    },

    // ── Crop overlay ─────────────────────────────────────────────────────
    _drawCropOverlay: function(ctx, cw, ch) {
      var cr = this.cropRect;
      var rx = cr.x*cw, ry = cr.y*ch, rw = cr.w*cw, rh = cr.h*ch;
      ctx.fillStyle = 'rgba(0,0,0,0.52)';
      ctx.fillRect(0, 0, cw, ry);
      ctx.fillRect(0, ry+rh, cw, ch-ry-rh);
      ctx.fillRect(0, ry, rx, rh);
      ctx.fillRect(rx+rw, ry, cw-rx-rw, rh);
      // Border
      ctx.strokeStyle = '#fff';
      ctx.lineWidth = 1.5;
      ctx.strokeRect(rx+.75, ry+.75, rw-1.5, rh-1.5);
      // Rule-of-thirds
      ctx.strokeStyle = 'rgba(255,255,255,.35)';
      ctx.lineWidth = .75;
      for (var i = 1; i < 3; i++) {
        ctx.beginPath(); ctx.moveTo(rx+rw*i/3,ry); ctx.lineTo(rx+rw*i/3,ry+rh); ctx.stroke();
        ctx.beginPath(); ctx.moveTo(rx,ry+rh*i/3); ctx.lineTo(rx+rw,ry+rh*i/3); ctx.stroke();
      }
      // Corner handles
      var hs = 8;
      ctx.fillStyle = '#fff';
      [[rx,ry],[rx+rw,ry],[rx,ry+rh],[rx+rw,ry+rh]].forEach(function(p) {
        ctx.fillRect(p[0]-hs/2, p[1]-hs/2, hs, hs);
      });
      // Edge mid handles
      [[rx+rw/2,ry],[rx+rw/2,ry+rh],[rx,ry+rh/2],[rx+rw,ry+rh/2]].forEach(function(p) {
        ctx.fillRect(p[0]-hs/2, p[1]-hs/2, hs, hs);
      });
    },

    // ── Pointer events (crop + text placement) ────────────────────────────
    _getCanvasXY: function(e) {
      var canvas = this.$refs.mainCanvas;
      var rect = canvas.getBoundingClientRect();
      return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    },
    onPointerDown: function(e) {
      if (this.placingText) {
        this._placeText(e); return;
      }
      if (!this.cropMode) return;
      var p = this._getCanvasXY(e);
      var cw = this._dispW, ch = this._dispH;
      var nx = p.x/cw, ny = p.y/ch;
      // Check handle/inside/outside
      if (this.cropRect) {
        var cr = this.cropRect;
        var rx = cr.x*cw, ry = cr.y*ch, rw = cr.w*cw, rh = cr.h*ch;
        var handle = this._getCropHandle(p.x, p.y, rx, ry, rw, rh);
        if (handle) {
          this._cropDrag = { type:'handle', handle:handle, sx:p.x, sy:p.y, orig:{...cr} };
          return;
        }
        if (p.x>=rx && p.x<=rx+rw && p.y>=ry && p.y<=ry+rh) {
          this._cropDrag = { type:'move', sx:p.x, sy:p.y, orig:{...cr} };
          return;
        }
      }
      // Draw new crop
      this.cropRect = { x:nx, y:ny, w:0, h:0 };
      this._cropDrag = { type:'draw', sx:nx, sy:ny };
    },
    onPointerMove: function(e) {
      if (!this.cropMode || !this._cropDrag) return;
      var p = this._getCanvasXY(e);
      var cw = this._dispW, ch = this._dispH;
      var drag = this._cropDrag;
      if (drag.type === 'draw') {
        var x2 = p.x/cw, y2 = p.y/ch;
        this.cropRect = {
          x: Math.max(0, Math.min(drag.sx, x2)),
          y: Math.max(0, Math.min(drag.sy, y2)),
          w: Math.min(1, Math.abs(x2 - drag.sx)),
          h: Math.min(1, Math.abs(y2 - drag.sy)),
        };
      } else if (drag.type === 'move') {
        var dx = (p.x-drag.sx)/cw, dy = (p.y-drag.sy)/ch;
        var o = drag.orig;
        this.cropRect = {
          x: Math.max(0, Math.min(1-o.w, o.x+dx)),
          y: Math.max(0, Math.min(1-o.h, o.y+dy)),
          w: o.w, h: o.h,
        };
      } else if (drag.type === 'handle') {
        this._resizeCropHandle(drag, p.x, p.y, cw, ch);
      }
      this.renderCanvas();
    },
    onPointerUp: function(e) {
      if (this._cropDrag && this._cropDrag.type === 'draw') {
        if (!this.cropRect || this.cropRect.w < 0.03 || this.cropRect.h < 0.03) {
          this.cropRect = { x:.05, y:.05, w:.9, h:.9 };
          this.renderCanvas();
        }
      }
      this._cropDrag = null;
    },
    _getCropHandle: function(mx, my, rx, ry, rw, rh) {
      var hs = 14;
      var pts = { tl:[rx,ry], tr:[rx+rw,ry], bl:[rx,ry+rh], br:[rx+rw,ry+rh],
                  tc:[rx+rw/2,ry], bc:[rx+rw/2,ry+rh], lc:[rx,ry+rh/2], rc:[rx+rw,ry+rh/2] };
      for (var k in pts) {
        if (Math.abs(mx-pts[k][0])<hs && Math.abs(my-pts[k][1])<hs) return k;
      }
      return null;
    },
    _resizeCropHandle: function(drag, mx, my, cw, ch) {
      var nx = mx/cw, ny = my/ch;
      var o = drag.orig;
      var r = { x:o.x, y:o.y, w:o.w, h:o.h };
      switch(drag.handle) {
        case 'tl': r.w=o.x+o.w-nx; r.h=o.y+o.h-ny; r.x=nx; r.y=ny; break;
        case 'tr': r.w=nx-o.x; r.h=o.y+o.h-ny; r.y=ny; break;
        case 'bl': r.w=o.x+o.w-nx; r.h=ny-o.y; r.x=nx; break;
        case 'br': r.w=nx-o.x; r.h=ny-o.y; break;
        case 'tc': r.h=o.y+o.h-ny; r.y=ny; break;
        case 'bc': r.h=ny-o.y; break;
        case 'lc': r.w=o.x+o.w-nx; r.x=nx; break;
        case 'rc': r.w=nx-o.x; break;
      }
      r.x=Math.max(0,r.x); r.y=Math.max(0,r.y);
      r.w=Math.max(0.03,Math.min(1-r.x,r.w));
      r.h=Math.max(0.03,Math.min(1-r.y,r.h));
      this.cropRect = r;
    },

    // ── Crop apply/enter/exit ─────────────────────────────────────────────
    enterCrop: function() {
      this.cropMode = true;
      this.cropRect = { x:.05, y:.05, w:.9, h:.9 };
      this.renderCanvas();
    },
    exitCrop: function() {
      this.cropMode = false;
      this.cropRect = null;
      this.renderCanvas();
    },
    applyCrop: function() {
      if (!this.cropRect || !this._imgEl) return;
      var cr = this.cropRect;
      var iw = this._imgEl.naturalWidth, ih = this._imgEl.naturalHeight;
      var sx = Math.round(cr.x*iw), sy = Math.round(cr.y*ih);
      var sw = Math.max(1, Math.round(cr.w*iw)), sh = Math.max(1, Math.round(cr.h*ih));
      var off = document.createElement('canvas');
      off.width=sw; off.height=sh;
      off.getContext('2d').drawImage(this._imgEl, sx, sy, sw, sh, 0, 0, sw, sh);
      this.cropMode = false;
      this.cropRect = null;
      this._loadDataUrl(off.toDataURL('image/png'));
    },

    // ── Rotate / Flip ─────────────────────────────────────────────────────
    rotateImage: function(deg) {
      var img = this._imgEl;
      var ow = img.naturalWidth, oh = img.naturalHeight;
      var isOrthogonal = (Math.abs(deg) === 90);
      var off = document.createElement('canvas');
      off.width  = isOrthogonal ? oh : ow;
      off.height = isOrthogonal ? ow : oh;
      var ctx = off.getContext('2d');
      ctx.translate(off.width/2, off.height/2);
      ctx.rotate(deg * Math.PI/180);
      ctx.drawImage(img, -ow/2, -oh/2);
      this._loadDataUrl(off.toDataURL('image/png'));
    },
    flipImage: function(axis) {
      var img = this._imgEl;
      var ow = img.naturalWidth, oh = img.naturalHeight;
      var off = document.createElement('canvas');
      off.width=ow; off.height=oh;
      var ctx = off.getContext('2d');
      if (axis==='h') { ctx.translate(ow,0); ctx.scale(-1,1); }
      else            { ctx.translate(0,oh); ctx.scale(1,-1); }
      ctx.drawImage(img, 0, 0);
      this._loadDataUrl(off.toDataURL('image/png'));
    },

    // ── Resize ────────────────────────────────────────────────────────────
    onResizeWChange: function() {
      if (this.lockRatio && this._origRatio) {
        this.resizeH = Math.max(1, Math.round(this.resizeW / this._origRatio));
      }
    },
    onResizeHChange: function() {
      if (this.lockRatio && this._origRatio) {
        this.resizeW = Math.max(1, Math.round(this.resizeH * this._origRatio));
      }
    },
    resizeImage: function() {
      var img = this._imgEl;
      var w = Math.max(1, Math.min(8000, this.resizeW));
      var h = Math.max(1, Math.min(8000, this.resizeH));
      var off = document.createElement('canvas');
      off.width=w; off.height=h;
      var ctx = off.getContext('2d');
      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';
      ctx.drawImage(img, 0, 0, w, h);
      this._loadDataUrl(off.toDataURL('image/png'));
    },

    // ── Text ──────────────────────────────────────────────────────────────
    startPlaceText: function() {
      if (!this.txtContent.trim()) return;
      this.placingText = true;
    },
    _placeText: function(e) {
      if (!this.txtContent.trim()) return;
      var p = this._getCanvasXY(e);
      var cw = this._dispW, ch = this._dispH;
      this.textItems.push({
        text:  this.txtContent,
        nx:    p.x / cw,
        ny:    p.y / ch,
        size:  this.txtSize,
        color: this.txtColor,
        font:  this.txtFont,
        bold:  this.txtBold,
      });
      this.placingText = false;
      // Push history snapshot using current image URL
      this._pushHistory(this._history[this._histIdx]?.url || this._origUrl);
      this.renderCanvas();
    },
    removeText: function(i) {
      this.textItems.splice(i, 1);
      this.renderCanvas();
    },

    // ── Presets ───────────────────────────────────────────────────────────
    setPreset: function(key) {
      this.activePreset = this.activePreset === key ? '' : key;
      this.renderCanvas();
    },

    // ── Adjustments reset ─────────────────────────────────────────────────
    resetAdjustments: function() {
      this.adj = { brightness:100, contrast:100, saturate:100, hue:0, blur:0, sharpness:0 };
      this.activePreset = '';
      this.renderCanvas();
    },

    // ── Full reset ────────────────────────────────────────────────────────
    resetFull: function() {
      if (!this._origUrl) return;
      this.textItems = [];
      this.resetAdjustments();
      this._loadDataUrl(this._origUrl, true);
    },
    resetAll: function() {
      this._origUrl = null; this._imgEl = null; this._imgW = 0; this._imgH = 0;
      this._history = []; this._histIdx = -1; this.textItems = [];
      this.adj = { brightness:100, contrast:100, saturate:100, hue:0, blur:0, sharpness:0 };
      this.activePreset = ''; this.cropMode = false; this.cropRect = null;
      this.thumbUrl = ''; this._thumbUrl = '';
    },

    // ── History ───────────────────────────────────────────────────────────
    _pushHistory: function(url) {
      this._history = this._history.slice(0, this._histIdx + 1);
      this._history.push({ url: url, textItems: JSON.parse(JSON.stringify(this.textItems)) });
      if (this._history.length > 20) this._history.shift();
      this._histIdx = this._history.length - 1;
    },
    undo: function() {
      if (!this.canUndo) return;
      this._histIdx--;
      this._restoreHistory(this._histIdx);
    },
    redo: function() {
      if (!this.canRedo) return;
      this._histIdx++;
      this._restoreHistory(this._histIdx);
    },
    _restoreHistory: function(idx) {
      var item = this._history[idx];
      if (!item) return;
      this.textItems = JSON.parse(JSON.stringify(item.textItems));
      var vm = this;
      var img = new Image();
      img.onload = function() {
        vm._imgEl = img;
        vm._imgW = img.naturalWidth;
        vm._imgH = img.naturalHeight;
        vm._origRatio = img.naturalWidth / img.naturalHeight;
        vm.resizeW = img.naturalWidth;
        vm.resizeH = img.naturalHeight;
        vm.setupCanvas();
        vm.renderCanvas();
      };
      img.src = item.url;
    },

    // ── Shared: load data URL into _imgEl and push history ────────────────
    _loadDataUrl: function(url, skipHistory) {
      var vm = this;
      var img = new Image();
      img.onload = function() {
        vm._imgEl = img;
        vm._imgW = img.naturalWidth;
        vm._imgH = img.naturalHeight;
        vm._origRatio = img.naturalWidth / img.naturalHeight;
        vm.resizeW = img.naturalWidth;
        vm.resizeH = img.naturalHeight;
        if (!skipHistory) vm._pushHistory(url);
        vm.setupCanvas();
        vm.renderCanvas();
      };
      img.src = url;
    },

    // ── Thumbnail for filter previews ─────────────────────────────────────
    generateThumb: function(dataUrl) {
      var vm = this;
      var img = new Image();
      img.onload = function() {
        var off = document.createElement('canvas');
        off.width=80; off.height=60;
        var ctx = off.getContext('2d');
        ctx.drawImage(img, 0, 0, 80, 60);
        vm.thumbUrl = off.toDataURL('image/jpeg', 0.6);
      };
      img.src = dataUrl;
    },

    // ── Download ─────────────────────────────────────────────────────────
    downloadImage: function() {
      var img = this._imgEl;
      if (!img) return;
      var iw = img.naturalWidth, ih = img.naturalHeight;
      var off = document.createElement('canvas');
      off.width = iw; off.height = ih;
      var ctx = off.getContext('2d');
      // Apply filters at full resolution
      ctx.filter = this.filterString;
      ctx.drawImage(img, 0, 0);
      ctx.filter = 'none';
      // Sharpen
      if (this.adj.sharpness > 0) this._applySharpen(ctx, off, this.adj.sharpness);
      // Text items scaled to full resolution
      var scaleF = iw / this._dispW;
      this.textItems.forEach(function(item) {
        var fs = Math.round(item.size * scaleF);
        ctx.font = (item.bold ? 'bold ' : '') + fs + 'px ' + item.font;
        ctx.fillStyle = item.color;
        ctx.shadowColor = 'rgba(0,0,0,0.55)';
        ctx.shadowBlur = 3 * scaleF;
        ctx.shadowOffsetX = scaleF;
        ctx.shadowOffsetY = scaleF;
        ctx.fillText(item.text, item.nx * iw, item.ny * ih);
      });
      ctx.shadowBlur = 0;
      var fmt = this.exportFmt;
      var mime = fmt==='jpeg' ? 'image/jpeg' : fmt==='webp' ? 'image/webp' : 'image/png';
      var quality = fmt==='jpeg' ? 0.92 : fmt==='webp' ? 0.9 : undefined;
      var dataUrl = off.toDataURL(mime, quality);
      var a = document.createElement('a');
      a.href = dataUrl;
      a.download = this.fileName.replace(/\.[^.]+$/, '') + '_edited.' + fmt;
      a.click();
    },

    // ── Slider display helpers ────────────────────────────────────────────
    formatSliderVal: function(s) {
      var v = this.adj[s.key];
      if (s.key === 'brightness' || s.key === 'contrast' || s.key === 'saturate')
        return (v - 100 >= 0 ? '+' : '') + (v - 100) + '%';
      if (s.key === 'hue') return v + '\xB0';
      if (s.key === 'blur') return v + ' px';
      return v;
    },
    sliderPct: function(s) {
      return Math.round(((this.adj[s.key] - s.min) / (s.max - s.min)) * 100);
    },
    updateSliderBg: function(e) {
      var pct = Math.round(((e.target.value - e.target.min) / (e.target.max - e.target.min)) * 100);
      e.target.style.setProperty('--pct', pct + '%');
    },

  }; // end return
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\image-photoshop-editor.blade.php ENDPATH**/ ?>