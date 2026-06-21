@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')

<style>
/* ── SVG to PNG Tool Styles (prefix: sp-) ── */
.sp-hero{background:linear-gradient(135deg,#f0fdfa 0%,#ccfbf1 50%,#99f6e4 100%);border-bottom:1px solid #99f6e4;padding:2rem 0}
.sp-hero-icon{width:56px;height:56px;background:linear-gradient(135deg,#0d9488,#0f766e);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sp-badge{display:inline-flex;align-items:center;gap:.375rem;background:#ccfbf1;border:1px solid #99f6e4;color:#134e4a;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* ── Tabs ── */
.sp-tabs{display:flex;gap:.25rem;background:#f1f5f9;padding:.3rem;border-radius:10px;margin-bottom:1.25rem}
.sp-tab{flex:1;text-align:center;padding:.45rem .875rem;border-radius:7px;font-size:.875rem;font-weight:500;color:#64748b;cursor:pointer;transition:all .15s;border:none;background:transparent}
.sp-tab.active{background:#fff;color:#0d9488;font-weight:600;box-shadow:0 1px 3px rgba(0,0,0,.08)}

/* ── Drop Zone ── */
.sp-dropzone{border:2px dashed #5eead4;border-radius:12px;padding:2.5rem 1.5rem;text-align:center;transition:all .2s;cursor:pointer;background:#f0fdfa}
.sp-dropzone:hover,.sp-dropzone.active{border-color:#0d9488;background:#e6faf7}
.sp-dropzone-icon{width:48px;height:48px;background:#ccfbf1;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem}

/* ── Textarea ── */
.sp-code-area{width:100%;min-height:160px;padding:.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-family:'Courier New',Courier,monospace;font-size:.8125rem;line-height:1.6;color:#1f2937;background:#fafafa;resize:vertical;transition:border-color .15s}
.sp-code-area:focus{outline:none;border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.1);background:#fff}
.sp-code-area.is-error{border-color:#ef4444}

/* ── SVG Preview ── */
.sp-preview-wrap{background:repeating-conic-gradient(#e5e7eb 0% 25%,#f9fafb 0% 50%) 0 0/16px 16px;border-radius:10px;overflow:hidden;border:1.5px solid #e5e7eb;display:flex;align-items:center;justify-content:center;min-height:120px;padding:1rem}
.sp-preview-wrap img{max-width:100%;max-height:280px;display:block;border-radius:4px}

/* ── Settings ── */
.sp-section-label{font-size:.6875rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0d9488;margin-bottom:.5rem}
.sp-radio-group{display:flex;flex-wrap:wrap;gap:.5rem}
.sp-radio-btn{cursor:pointer}
.sp-radio-btn input{position:absolute;opacity:0;pointer-events:none}
.sp-radio-btn span{display:inline-block;padding:.3rem .875rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8125rem;font-weight:500;color:#4b5563;transition:all .15s}
.sp-radio-btn input:checked+span{border-color:#0d9488;background:#f0fdfa;color:#0f766e;font-weight:600}
.sp-radio-btn span:hover{border-color:#2dd4bf;background:#f0fdfa}
.sp-num-input{padding:.45rem .75rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.875rem;color:#1f2937;background:#fff;transition:border-color .15s;width:100%}
.sp-num-input:focus{outline:none;border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.1)}
.sp-text-input{padding:.45rem .75rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.875rem;color:#1f2937;background:#fff;transition:border-color .15s;width:100%}
.sp-text-input:focus{outline:none;border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.1)}

/* ── Dimension row ── */
.sp-dim-row{display:grid;grid-template-columns:1fr auto 1fr;gap:.5rem;align-items:end}
.sp-lock-btn{display:flex;align-items:center;justify-content:center;width:36px;height:36px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;background:#fff;transition:all .15s;margin-bottom:0;flex-shrink:0}
.sp-lock-btn:hover{border-color:#0d9488;background:#f0fdfa}
.sp-lock-btn.locked{border-color:#0d9488;background:#f0fdfa;color:#0d9488}

/* ── Color swatch ── */
.sp-color-row{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center}
.sp-swatch{width:28px;height:28px;border-radius:6px;border:2px solid transparent;cursor:pointer;transition:all .15s;flex-shrink:0}
.sp-swatch:hover,.sp-swatch.active{border-color:#0d9488;transform:scale(1.1)}
.sp-swatch-transparent{background:repeating-conic-gradient(#ccc 0% 25%,#fff 0% 50%) 0 0/10px 10px;border:1.5px dashed #94a3b8}
.sp-swatch-transparent.active{border-color:#0d9488}

/* ── Scale presets ── */
.sp-scale-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem}
@media(min-width:480px){.sp-scale-grid{grid-template-columns:repeat(5,1fr)}}
.sp-scale-btn{padding:.35rem .5rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8rem;font-weight:500;color:#4b5563;cursor:pointer;background:#fff;transition:all .15s;text-align:center}
.sp-scale-btn:hover{border-color:#2dd4bf;background:#f0fdfa}
.sp-scale-btn.active{border-color:#0d9488;background:#f0fdfa;color:#0f766e;font-weight:600}

/* ── Convert button ── */
.sp-btn-primary{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#0d9488,#0f766e);color:#fff;font-size:.9375rem;font-weight:600;padding:.7rem 1.75rem;border-radius:10px;border:none;cursor:pointer;transition:all .15s}
.sp-btn-primary:hover:not(:disabled){background:linear-gradient(135deg,#0f766e,#115e59);transform:translateY(-1px);box-shadow:0 4px 12px rgba(15,118,110,.3)}
.sp-btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
.sp-btn-secondary{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:#fff;color:#6b7280;font-size:.875rem;font-weight:500;padding:.625rem 1.25rem;border-radius:9px;border:1.5px solid #e5e7eb;cursor:pointer;transition:all .15s}
.sp-btn-secondary:hover{border-color:#d1d5db;color:#374151;background:#f9fafb}
.sp-btn-download{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:.9375rem;font-weight:600;padding:.7rem 1.75rem;border-radius:10px;border:none;cursor:pointer;transition:all .15s;text-decoration:none}
.sp-btn-download:hover{background:linear-gradient(135deg,#15803d,#166534);transform:translateY(-1px);box-shadow:0 4px 12px rgba(22,163,74,.3)}

/* ── Result PNG ── */
.sp-png-preview{background:repeating-conic-gradient(#e5e7eb 0% 25%,#f9fafb 0% 50%) 0 0/16px 16px;border-radius:10px;overflow:hidden;border:2px solid #a7f3d0;display:flex;align-items:center;justify-content:center;min-height:120px;padding:1rem}
.sp-png-preview img{max-width:100%;max-height:360px;display:block;border-radius:4px}
.sp-stat-row{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
.sp-stat{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:.4rem .875rem;text-align:center}
.sp-stat-val{font-size:1rem;font-weight:700;color:#1f2937}
.sp-stat-lbl{font-size:.6875rem;color:#6b7280;font-weight:500;margin-top:.05rem}

/* ── Spinner ── */
.sp-spinner{width:18px;height:18px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:sp-spin .7s linear infinite}
@keyframes sp-spin{to{transform:rotate(360deg)}}

/* ── Sidebar ── */
.sp-use-case{display:flex;gap:.625rem;align-items:flex-start;padding:.65rem .75rem;border-radius:8px;background:#f0fdfa;margin-bottom:.4rem;border-left:3px solid #2dd4bf}
.sp-use-case:last-child{margin-bottom:0}
</style>

<!-- Hero -->
<div class="sp-hero">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex items-start gap-4">
      <div class="sp-hero-icon">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <span class="sp-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
            100% Browser-Side &middot; Instant
          </span>
        </div>
        <p class="text-gray-600 text-sm max-w-2xl">{{ $tool->description }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Main layout -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="grid gap-8 lg:grid-cols-3">

    <!-- ════════════ MAIN COLUMN ════════════ -->
    <div class="lg:col-span-2 space-y-5" x-data="spTool()">

      <!-- ── INPUT + SETTINGS CARD ── -->
      <div class="card" x-show="phase !== 'done'" x-transition>
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">SVG Input</h2>

          <!-- Tabs -->
          <div class="sp-tabs">
            <button class="sp-tab" :class="{ active: activeTab === 'upload' }" @click="switchTab('upload')">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="inline-block mr-1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              Upload SVG File
            </button>
            <button class="sp-tab" :class="{ active: activeTab === 'paste' }" @click="switchTab('paste')">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="inline-block mr-1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Paste SVG Code
            </button>
          </div>

          <!-- ─ Upload Tab ─ -->
          <div x-show="activeTab === 'upload'">
            <!-- Drop zone (no file yet) -->
            <div
              class="sp-dropzone"
              :class="{ active: isDragging }"
              x-show="!svgContent"
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="onDrop($event)"
              @click="$refs.fileInput.click()"
            >
              <div class="sp-dropzone-icon">
                <svg width="24" height="24" fill="none" stroke="#0d9488" stroke-width="1.8" viewBox="0 0 24 24">
                  <path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/>
                </svg>
              </div>
              <p class="text-gray-700 font-semibold mb-1">Drop your SVG file here</p>
              <p class="text-sm text-gray-500">or click to browse &mdash; .svg files only</p>
              <input type="file" x-ref="fileInput" accept=".svg,image/svg+xml" class="hidden" @change="onFileChange($event)">
            </div>

            <!-- File loaded indicator -->
            <div x-show="svgContent && activeTab === 'upload'" class="flex items-center justify-between p-3 bg-teal-50 border border-teal-200 rounded-lg">
              <div class="flex items-center gap-2 text-sm text-teal-800 font-medium min-w-0">
                <svg width="16" height="16" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span class="truncate" x-text="svgFileName || 'file.svg'"></span>
              </div>
              <button class="text-xs text-gray-500 hover:text-red-500 ml-3 flex-shrink-0" @click="clearInput()">&#x2715; Remove</button>
            </div>
          </div>

          <!-- ─ Paste Tab ─ -->
          <div x-show="activeTab === 'paste'">
            <div class="relative">
              <textarea
                x-ref="pasteArea"
                x-model="pasteCode"
                @input="onPasteInput()"
                placeholder="&lt;svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 100&quot;&gt;&#10;  &lt;circle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;40&quot; fill=&quot;#0d9488&quot;/&gt;&#10;&lt;/svg&gt;"
                class="sp-code-area"
                :class="{'is-error': svgError && activeTab === 'paste'}"
                spellcheck="false"
              ></textarea>
              <button
                x-show="pasteCode"
                class="absolute top-2 right-2 text-xs text-gray-400 hover:text-red-500 bg-white border border-gray-200 rounded px-2 py-1"
                @click="pasteCode = ''; clearInput()"
              >Clear</button>
            </div>
          </div>

          <!-- ── SVG Error ── -->
          <div x-show="svgError" class="mt-2 flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span x-text="svgError"></span>
          </div>

          <!-- ── SVG Preview + Meta ── -->
          <div x-show="svgContent && !svgError" x-transition class="mt-4 space-y-3">
            <div>
              <p class="sp-section-label">SVG Preview</p>
              <div class="sp-preview-wrap">
                <img :src="svgPreviewUrl" alt="SVG Preview" x-show="svgPreviewUrl">
              </div>
              <div class="flex flex-wrap gap-2 mt-2 text-xs text-gray-500">
                <span x-show="svgNatW > 0">
                  Natural size: <strong class="text-gray-700" x-text="svgNatW + '\xD7' + svgNatH + ' px'"></strong>
                </span>
                <span x-show="svgNatW === 0" class="italic">No explicit dimensions detected (using viewBox)</span>
              </div>
            </div>

            <hr class="border-gray-100">

            <!-- ── OUTPUT SIZE ── -->
            <div>
              <p class="sp-section-label">Output Size</p>

              <!-- Quick scale presets -->
              <div x-show="svgNatW > 0" class="sp-scale-grid mb-3">
                <template x-for="s in [1,2,3,4]" :key="s">
                  <button
                    class="sp-scale-btn"
                    :class="{ active: scalePreset === s }"
                    @click="applyScale(s)"
                    x-text="s + 'x'"
                  ></button>
                </template>
                <button
                  class="sp-scale-btn"
                  :class="{ active: scalePreset === 0 }"
                  @click="scalePreset = 0"
                >Custom</button>
              </div>

              <!-- Width / Height fields -->
              <div class="sp-dim-row">
                <div>
                  <label class="form-label">Width (px)</label>
                  <input type="number" x-model.number="outputW" @input="onWidthChange()" min="1" max="8192" class="sp-num-input">
                </div>
                <div>
                  <button
                    class="sp-lock-btn"
                    :class="{ locked: lockRatio }"
                    @click="lockRatio = !lockRatio"
                    :title="lockRatio ? 'Unlock aspect ratio' : 'Lock aspect ratio'"
                  >
                    <svg x-show="lockRatio" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <svg x-show="!lockRatio" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 019.9-1"/></svg>
                  </button>
                </div>
                <div>
                  <label class="form-label">Height (px)</label>
                  <input type="number" x-model.number="outputH" @input="onHeightChange()" min="1" max="8192" class="sp-num-input" :readonly="lockRatio && svgNatW > 0">
                </div>
              </div>
              <p class="text-xs text-gray-500 mt-1.5">
                Output: <strong class="text-gray-700" x-text="outputW + ' \xD7 ' + outputH + ' px'"></strong>
                <span x-show="lockRatio" class="text-teal-600"> &middot; Ratio locked</span>
              </p>
            </div>

            <!-- ── BACKGROUND ── -->
            <div>
              <p class="sp-section-label">Background</p>
              <div class="sp-radio-group">
                <label class="sp-radio-btn">
                  <input type="radio" value="transparent" x-model="bgMode">
                  <span>Transparent</span>
                </label>
                <label class="sp-radio-btn">
                  <input type="radio" value="white" x-model="bgMode">
                  <span>White</span>
                </label>
                <label class="sp-radio-btn">
                  <input type="radio" value="black" x-model="bgMode">
                  <span>Black</span>
                </label>
                <label class="sp-radio-btn">
                  <input type="radio" value="custom" x-model="bgMode">
                  <span>Custom</span>
                </label>
              </div>
              <div x-show="bgMode === 'custom'" class="mt-2 flex items-center gap-3">
                <input type="color" x-model="customBgColor" class="w-10 h-9 rounded border border-gray-300 cursor-pointer p-0.5">
                <input type="text" x-model="customBgColor" class="sp-text-input" style="max-width:120px" placeholder="#ffffff"
                  @input="if(!/^#[0-9a-fA-F]{6}$/.test(customBgColor)) {}">
              </div>
              <p x-show="bgMode === 'transparent'" class="text-xs text-gray-500 mt-1">PNG will have a transparent (alpha) background</p>
            </div>

            <!-- ── OUTPUT FILENAME ── -->
            <div>
              <p class="sp-section-label">Output Filename</p>
              <div class="flex items-center gap-2">
                <input type="text" x-model="outputFilename" class="sp-text-input" style="max-width:240px" placeholder="output">
                <span class="text-sm text-gray-400 font-medium">.png</span>
              </div>
            </div>

            <!-- ── CONVERT BUTTON ── -->
            <div class="flex gap-3 pt-2">
              <button
                class="sp-btn-primary"
                :disabled="!canConvert || phase === 'converting'"
                @click="doConvert()"
              >
                <span x-show="phase !== 'converting'" class="flex items-center gap-2">
                  <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M5 3l14 9-14 9V3z"/>
                  </svg>
                  Convert to PNG
                </span>
                <span x-show="phase === 'converting'" class="flex items-center gap-2">
                  <span class="sp-spinner"></span>
                  Converting&hellip;
                </span>
              </button>
            </div>

            <!-- Conversion error -->
            <div x-show="convError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700" x-text="convError"></div>

          </div><!-- /settings -->
        </div>
      </div>

      <!-- ── RESULT CARD ── -->
      <div class="card" x-show="phase === 'done'" x-transition>
        <div class="p-6">
          <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <h2 class="text-lg font-semibold text-gray-900">PNG Ready!</h2>
            </div>
            <button class="sp-btn-secondary text-sm" @click="resetTool()">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 4v6h6M23 20v-6h-6"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15"/></svg>
              Convert Another
            </button>
          </div>

          <!-- PNG Preview -->
          <div class="sp-png-preview mb-4">
            <img :src="pngUrl" alt="Converted PNG">
          </div>

          <!-- Stats -->
          <div class="sp-stat-row">
            <div class="sp-stat">
              <div class="sp-stat-val" x-text="pngW + '\xD7' + pngH"></div>
              <div class="sp-stat-lbl">Dimensions</div>
            </div>
            <div class="sp-stat">
              <div class="sp-stat-val" x-text="fmtSize(pngSizeBytes)"></div>
              <div class="sp-stat-lbl">File Size</div>
            </div>
            <div class="sp-stat">
              <div class="sp-stat-val" x-text="bgMode === 'transparent' ? 'Alpha' : 'Solid'"></div>
              <div class="sp-stat-lbl">Background</div>
            </div>
          </div>

          <!-- Download -->
          <div class="flex flex-wrap gap-3 mt-5">
            <a :href="pngUrl" :download="(outputFilename || 'output') + '.png'" class="sp-btn-download">
              <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download PNG
            </a>
            <button class="sp-btn-secondary" @click="phase = 'idle'">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit Settings
            </button>
          </div>
        </div>
      </div>

      <!-- ── HOW IT WORKS ── -->
      <div class="card" x-show="phase === 'idle' || phase === 'done'">
        <div class="p-6">
          <h2 class="text-base font-semibold text-gray-900 mb-4">How It Works</h2>
          <ol class="space-y-3">
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
              <span><strong class="text-gray-800">Provide your SVG</strong> by uploading a file or pasting the code directly &mdash; no server involved.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
              <span><strong class="text-gray-800">Configure</strong> output dimensions, background color, and filename.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
              <span><strong class="text-gray-800">Convert</strong> &mdash; the SVG is drawn onto an HTML5 Canvas at your specified resolution and exported as PNG.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
              <span><strong class="text-gray-800">Download</strong> your lossless PNG with a single click.</span>
            </li>
          </ol>
        </div>
      </div>

    </div><!-- /main column -->

    <!-- ════════════ SIDEBAR ════════════ -->
    <div class="space-y-5">

      <!-- Tips -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">SVG vs PNG</h3>
        <p class="text-sm text-gray-600 leading-relaxed mb-3">SVG is vector-based (infinitely scalable), while PNG is raster (fixed pixels). Use PNG when you need a specific pixel size for web, social media, or print.</p>
        <div class="space-y-1.5 text-sm text-gray-600">
          <div class="flex items-start gap-2"><span class="text-teal-500 font-bold">&#x2713;</span><span>PNG works in all apps (Office, Photoshop, etc.)</span></div>
          <div class="flex items-start gap-2"><span class="text-teal-500 font-bold">&#x2713;</span><span>Supports transparent background</span></div>
          <div class="flex items-start gap-2"><span class="text-teal-500 font-bold">&#x2713;</span><span>Lossless &mdash; no quality degradation</span></div>
          <div class="flex items-start gap-2"><span class="text-yellow-500 font-bold">!</span><span>Use 2&times; or higher for retina/sharp prints</span></div>
        </div>
      </div>

      <!-- Use cases -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Common Use Cases</h3>
        <div>
          <div class="sp-use-case">
            <span class="text-teal-600 text-base">&#x1F5BC;</span>
            <p class="text-sm text-gray-600">Export logos/icons from Figma or Illustrator as PNG for use in documents or email</p>
          </div>
          <div class="sp-use-case">
            <span class="text-teal-600 text-base">&#x1F4F1;</span>
            <p class="text-sm text-gray-600">Convert SVG illustrations to exact pixel sizes required by app stores or social media</p>
          </div>
          <div class="sp-use-case">
            <span class="text-teal-600 text-base">&#x1F4CB;</span>
            <p class="text-sm text-gray-600">Embed SVG graphics into Word, PowerPoint, or PDF documents as high-res PNGs</p>
          </div>
          <div class="sp-use-case">
            <span class="text-teal-600 text-base">&#x1F5A8;</span>
            <p class="text-sm text-gray-600">Prepare SVG for print by rasterizing at 300+ PPI (e.g. 2480&times;3508 for A4 at 300 DPI)</p>
          </div>
        </div>
      </div>

      <!-- Limits note -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Important Notes</h3>
        <div class="space-y-2 text-sm text-gray-600">
          <p>&#x26A0; SVGs with <strong class="text-gray-800">external fonts</strong> may render with a fallback font unless the font is installed locally.</p>
          <p>&#x26A0; SVGs with <strong class="text-gray-800">linked external images</strong> may not render due to browser CORS restrictions.</p>
          <p>&#x2705; SVGs with <strong class="text-gray-800">inline styles, gradients, filters,</strong> and <strong class="text-gray-800">masks</strong> are fully supported.</p>
          <p>&#x2705; <strong class="text-gray-800">Max canvas size</strong> is typically 16,384&times;16,384 px in modern browsers.</p>
        </div>
      </div>

    </div><!-- /sidebar -->

  </div>
</div>

@endsection

@push('scripts')
<script>
function spTool() {
  return {
    // ── Input ──
    activeTab: 'upload',
    svgContent: '',
    svgFileName: '',
    pasteCode: '',
    isDragging: false,

    // ── Parsed SVG info ──
    svgNatW: 0,
    svgNatH: 0,
    svgRatio: 1,
    svgPreviewUrl: null,
    svgError: '',

    // ── Settings ──
    scalePreset: 1,
    outputW: 800,
    outputH: 600,
    lockRatio: true,
    bgMode: 'transparent',
    customBgColor: '#ffffff',
    outputFilename: 'output',

    // ── State ──
    phase: 'idle',
    convError: '',

    // ── Result ──
    pngUrl: null,
    pngSizeBytes: 0,
    pngW: 0,
    pngH: 0,

    // ── Computed ──
    get bgColor() {
      if (this.bgMode === 'transparent') return null;
      if (this.bgMode === 'white') return '#ffffff';
      if (this.bgMode === 'black') return '#000000';
      return this.customBgColor || '#ffffff';
    },
    get canConvert() {
      return !!(this.svgContent && !this.svgError && this.outputW > 0 && this.outputH > 0);
    },

    // ── Tab switch ──
    switchTab: function(tab) {
      this.activeTab = tab;
      if (tab === 'paste' && this.svgContent && !this.pasteCode) {
        this.pasteCode = this.svgContent;
      }
    },

    // ── File upload ──
    onDrop: function(e) {
      this.isDragging = false;
      var files = e.dataTransfer.files;
      if (files && files.length) this.loadSvgFile(files[0]);
    },
    onFileChange: function(e) {
      var files = e.target.files;
      if (files && files.length) this.loadSvgFile(files[0]);
    },
    loadSvgFile: function(f) {
      this.svgError = '';
      var ext = f.name.split('.').pop().toLowerCase();
      if (ext !== 'svg' && f.type !== 'image/svg+xml') {
        this.svgError = 'Please select an SVG file (.svg).';
        return;
      }
      if (f.size > 10 * 1024 * 1024) {
        this.svgError = 'SVG file is too large. Maximum 10 MB.';
        return;
      }
      this.svgFileName = f.name;
      this.outputFilename = f.name.replace(/\.svg$/i, '');
      var vm = this;
      var reader = new FileReader();
      reader.onload = function(e) {
        vm.processSvgContent(e.target.result);
      };
      reader.onerror = function() {
        vm.svgError = 'Could not read the file.';
      };
      reader.readAsText(f);
    },

    // ── Paste input ──
    onPasteInput: function() {
      var code = this.pasteCode.trim();
      if (!code) { this.clearInput(); return; }
      this.processSvgContent(code);
    },

    // ── Core SVG processing ──
    processSvgContent: function(raw) {
      this.svgError = '';
      var trimmed = raw.trim();
      if (!trimmed) { this.svgError = 'No SVG content provided.'; return; }

      // Parse SVG DOM
      var parser = new DOMParser();
      var doc;
      try {
        doc = parser.parseFromString(trimmed, 'image/svg+xml');
      } catch(e) {
        this.svgError = 'Could not parse the SVG content.';
        return;
      }

      // Check for XML parse errors
      var parseErr = doc.querySelector('parsererror');
      if (parseErr) {
        var msg = parseErr.textContent || '';
        this.svgError = 'Invalid SVG: ' + msg.substring(0, 120).replace(/\s+/g, ' ').trim();
        return;
      }

      var svgEl = doc.documentElement;
      if (!svgEl || svgEl.tagName.toLowerCase() !== 'svg') {
        this.svgError = 'The content does not appear to be a valid SVG (no <svg> root element found).';
        return;
      }

      // Extract natural dimensions
      var natW = 0, natH = 0;

      var wAttr = svgEl.getAttribute('width');
      var hAttr = svgEl.getAttribute('height');
      if (wAttr && hAttr) {
        natW = parseFloat(wAttr) || 0;
        natH = parseFloat(hAttr) || 0;
      }

      // Fall back to viewBox
      var viewBox = svgEl.getAttribute('viewBox');
      if (viewBox && (!natW || !natH)) {
        var vbParts = viewBox.trim().split(/[\s,]+/);
        if (vbParts.length >= 4) {
          var vbW = parseFloat(vbParts[2]);
          var vbH = parseFloat(vbParts[3]);
          if (!natW) natW = vbW;
          if (!natH) natH = vbH;
        }
      }

      this.svgNatW = Math.round(natW) || 0;
      this.svgNatH = Math.round(natH) || 0;
      this.svgRatio = (natW && natH) ? (natW / natH) : 1;

      // Set initial output dimensions
      if (this.svgNatW > 0 && this.svgNatH > 0) {
        this.outputW = this.svgNatW;
        this.outputH = this.svgNatH;
        this.scalePreset = 1;
      } else {
        // No explicit dimensions — default 800×600
        this.outputW = 800;
        this.outputH = 600;
        this.scalePreset = 0;
        this.lockRatio = false;
      }

      this.svgContent = trimmed;

      // Build preview URL
      if (this.svgPreviewUrl) URL.revokeObjectURL(this.svgPreviewUrl);
      var blob = new Blob([trimmed], { type: 'image/svg+xml;charset=utf-8' });
      this.svgPreviewUrl = URL.createObjectURL(blob);
    },

    // ── Scale presets ──
    applyScale: function(s) {
      this.scalePreset = s;
      if (this.svgNatW > 0) {
        this.outputW = Math.round(this.svgNatW * s);
        this.outputH = Math.round(this.svgNatH * s);
      }
    },

    // ── Dimension change handlers ──
    onWidthChange: function() {
      this.scalePreset = 0;
      if (this.lockRatio && this.svgRatio && this.outputW > 0) {
        this.outputH = Math.max(1, Math.round(this.outputW / this.svgRatio));
      }
    },
    onHeightChange: function() {
      this.scalePreset = 0;
      if (this.lockRatio && this.svgRatio && this.outputH > 0) {
        this.outputW = Math.max(1, Math.round(this.outputH * this.svgRatio));
      }
    },

    // ── Clear ──
    clearInput: function() {
      if (this.svgPreviewUrl) URL.revokeObjectURL(this.svgPreviewUrl);
      this.svgContent    = '';
      this.svgFileName   = '';
      this.svgPreviewUrl = null;
      this.svgNatW       = 0;
      this.svgNatH       = 0;
      this.svgRatio      = 1;
      this.svgError      = '';
      var vm = this;
      this.$nextTick(function() {
        if (vm.$refs.fileInput) vm.$refs.fileInput.value = '';
      });
    },

    // ── Main conversion ──
    doConvert: async function() {
      if (!this.canConvert) return;

      this.phase     = 'converting';
      this.convError = '';

      try {
        var outW = Math.max(1, Math.round(this.outputW));
        var outH = Math.max(1, Math.round(this.outputH));

        if (outW > 16384 || outH > 16384) {
          throw new Error('Output dimensions exceed 16,384 px. Please reduce the size.');
        }

        // Inject explicit dimensions into the SVG so the browser renders at the right size
        var parser = new DOMParser();
        var doc = parser.parseFromString(this.svgContent, 'image/svg+xml');
        var svgEl = doc.documentElement;
        svgEl.setAttribute('width', outW);
        svgEl.setAttribute('height', outH);
        var serializer = new XMLSerializer();
        var preparedSvg = serializer.serializeToString(doc);

        // Create Blob URL
        var svgBlob = new Blob([preparedSvg], { type: 'image/svg+xml;charset=utf-8' });
        var svgUrl  = URL.createObjectURL(svgBlob);

        // Load into Image
        var img = await new Promise(function(resolve, reject) {
          var i = new Image();
          i.onload  = function() { resolve(i); };
          i.onerror = function() { reject(new Error('Failed to render the SVG. It may reference unsupported external resources.')); };
          i.src = svgUrl;
        });

        URL.revokeObjectURL(svgUrl);

        // Draw to canvas
        var canvas = document.createElement('canvas');
        canvas.width  = outW;
        canvas.height = outH;
        var ctx = canvas.getContext('2d');

        if (this.bgColor) {
          ctx.fillStyle = this.bgColor;
          ctx.fillRect(0, 0, outW, outH);
        }

        ctx.drawImage(img, 0, 0, outW, outH);

        // Export as PNG blob
        var vm = this;
        var pngBlob = await new Promise(function(resolve, reject) {
          canvas.toBlob(function(b) {
            if (b) resolve(b);
            else reject(new Error('Canvas export failed. The output may be too large.'));
          }, 'image/png');
        });

        vm.pngSizeBytes = pngBlob.size;
        vm.pngW = outW;
        vm.pngH = outH;
        if (vm.pngUrl) URL.revokeObjectURL(vm.pngUrl);
        vm.pngUrl = URL.createObjectURL(pngBlob);

        this.phase = 'done';

      } catch(err) {
        this.convError = err.message || String(err);
        this.phase = 'idle';
      }
    },

    // ── Reset ──
    resetTool: function() {
      if (this.svgPreviewUrl) { URL.revokeObjectURL(this.svgPreviewUrl); this.svgPreviewUrl = null; }
      if (this.pngUrl) { URL.revokeObjectURL(this.pngUrl); this.pngUrl = null; }
      this.activeTab      = 'upload';
      this.svgContent     = '';
      this.svgFileName    = '';
      this.pasteCode      = '';
      this.isDragging     = false;
      this.svgNatW        = 0;
      this.svgNatH        = 0;
      this.svgRatio       = 1;
      this.svgError       = '';
      this.scalePreset    = 1;
      this.outputW        = 800;
      this.outputH        = 600;
      this.lockRatio      = true;
      this.bgMode         = 'transparent';
      this.customBgColor  = '#ffffff';
      this.outputFilename = 'output';
      this.phase          = 'idle';
      this.convError      = '';
      this.pngSizeBytes   = 0;
      this.pngW           = 0;
      this.pngH           = 0;
      var vm = this;
      this.$nextTick(function() {
        if (vm.$refs.fileInput) vm.$refs.fileInput.value = '';
      });
    },

    // ── Helpers ──
    fmtSize: function(bytes) {
      if (!bytes) return '0 B';
      if (bytes < 1024) return bytes + ' B';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(2) + ' MB';
    },

  }; // end return
}
</script>
@endpush
