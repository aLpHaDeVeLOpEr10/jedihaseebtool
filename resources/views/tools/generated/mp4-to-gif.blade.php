@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')

<style>
/* ── MP4-to-GIF Tool Styles (prefix: mg-) ── */
.mg-hero{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 50%,#fde68a 100%);border-bottom:1px solid #fde68a;padding:2rem 0}
.mg-hero-icon{width:56px;height:56px;background:linear-gradient(135deg,#d97706,#b45309);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.mg-badge{display:inline-flex;align-items:center;gap:.375rem;background:#fef3c7;border:1px solid #fde68a;color:#92400e;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* ── Drop Zone ── */
.mg-dropzone{border:2px dashed #fcd34d;border-radius:12px;padding:2.5rem 1.5rem;text-align:center;transition:all .2s;cursor:pointer;background:#fffbeb}
.mg-dropzone:hover,.mg-dropzone.active{border-color:#d97706;background:#fef9ec}
.mg-dropzone-icon{width:48px;height:48px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem}

/* ── Video Preview ── */
.mg-video-wrap{background:#000;border-radius:10px;overflow:hidden}
.mg-video-wrap video{width:100%;max-height:260px;display:block;object-fit:contain}
.mg-meta-row{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
.mg-meta-pill{background:#fef3c7;border:1px solid #fde68a;color:#78350f;font-size:.75rem;font-weight:500;padding:.2rem .65rem;border-radius:999px}

/* ── Settings ── */
.mg-section-label{font-size:.6875rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d97706;margin-bottom:.5rem}
.mg-radio-group{display:flex;flex-wrap:wrap;gap:.5rem}
.mg-radio-btn{cursor:pointer}
.mg-radio-btn input{position:absolute;opacity:0;pointer-events:none}
.mg-radio-btn span{display:inline-block;padding:.3rem .875rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8125rem;font-weight:500;color:#4b5563;transition:all .15s}
.mg-radio-btn input:checked+span{border-color:#d97706;background:#fef3c7;color:#92400e;font-weight:600}
.mg-radio-btn span:hover{border-color:#fbbf24;background:#fffbeb}

/* ── Time inputs ── */
.mg-time-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.mg-num-input{width:100%;padding:.45rem .75rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.875rem;color:#1f2937;background:#fff;transition:border-color .15s}
.mg-num-input:focus{outline:none;border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1)}
.mg-num-input.is-error{border-color:#ef4444}
.mg-field-error{font-size:.75rem;color:#dc2626;margin-top:.25rem}

/* ── Progress ── */
.mg-progress-bar{height:10px;border-radius:999px;background:#f3f4f6;overflow:hidden}
.mg-progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,#f59e0b,#d97706);transition:width .3s ease}
.mg-phase-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.mg-phase-dot.active{background:#d97706;animation:mg-pulse 1.4s infinite}
.mg-phase-dot.done{background:#10b981}
.mg-phase-dot.wait{background:#e5e7eb}
@keyframes mg-pulse{0%,100%{opacity:1}50%{opacity:.4}}

/* ── Result ── */
.mg-gif-preview{border-radius:10px;overflow:hidden;border:2px solid #fde68a;background:#000;display:flex;align-items:center;justify-content:center;min-height:120px}
.mg-gif-preview img{max-width:100%;max-height:360px;display:block}
.mg-stat-row{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
.mg-stat{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:.4rem .875rem;text-align:center}
.mg-stat-val{font-size:1rem;font-weight:700;color:#1f2937}
.mg-stat-lbl{font-size:.6875rem;color:#6b7280;font-weight:500;margin-top:.05rem}

/* ── Buttons ── */
.mg-btn-primary{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#d97706,#b45309);color:#fff;font-size:.9375rem;font-weight:600;padding:.7rem 1.75rem;border-radius:10px;border:none;cursor:pointer;transition:all .15s;text-decoration:none}
.mg-btn-primary:hover:not(:disabled){background:linear-gradient(135deg,#b45309,#92400e);transform:translateY(-1px);box-shadow:0 4px 12px rgba(180,83,9,.3)}
.mg-btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
.mg-btn-secondary{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:#fff;color:#6b7280;font-size:.875rem;font-weight:500;padding:.625rem 1.25rem;border-radius:9px;border:1.5px solid #e5e7eb;cursor:pointer;transition:all .15s}
.mg-btn-secondary:hover{border-color:#d1d5db;color:#374151;background:#f9fafb}
.mg-btn-download{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:.9375rem;font-weight:600;padding:.7rem 1.75rem;border-radius:10px;border:none;cursor:pointer;transition:all .15s;text-decoration:none}
.mg-btn-download:hover{background:linear-gradient(135deg,#15803d,#166534);transform:translateY(-1px);box-shadow:0 4px 12px rgba(22,163,74,.3)}

/* ── Warning banner ── */
.mg-warn{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.625rem .875rem;display:flex;gap:.5rem;align-items:flex-start;font-size:.8125rem;color:#92400e}

/* ── Sidebar ── */
.mg-tip{display:flex;gap:.625rem;align-items:flex-start;padding:.75rem;background:#fffbeb;border-radius:8px;border-left:3px solid #f59e0b;margin-bottom:.5rem}
.mg-tip:last-child{margin-bottom:0}
</style>

<!-- Hero -->
<div class="mg-hero">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex items-start gap-4">
      <div class="mg-hero-icon">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
          <rect x="2" y="2" width="20" height="20" rx="4"/><path d="M8 12h8M12 8v8" stroke-width="2.2"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <span class="mg-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
            Browser-Only &middot; No Upload Needed
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
    <div class="lg:col-span-2 space-y-5" x-data="mgTool()">

      <!-- ── UPLOAD / SETTINGS CARD ── -->
      <div class="card" x-show="phase === 'idle'" x-transition>
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Upload &amp; Configure</h2>

          <!-- Drop zone -->
          <div
            class="mg-dropzone"
            :class="{ active: isDragging }"
            x-show="!file"
            @dragover.prevent="isDragging=true"
            @dragleave.prevent="isDragging=false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
          >
            <div class="mg-dropzone-icon">
              <svg width="24" height="24" fill="none" stroke="#d97706" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M15 10l-4-4-4 4M11 6v8"/><rect x="3" y="14" width="18" height="7" rx="2"/>
              </svg>
            </div>
            <p class="text-gray-700 font-semibold mb-1">Drop your MP4 here</p>
            <p class="text-sm text-gray-500">or click to browse &mdash; MP4 only, up to 500 MB</p>
            <input type="file" x-ref="fileInput" accept="video/mp4,.mp4" class="hidden" @change="onFileChange($event)">
          </div>

          <!-- File loaded view -->
          <div x-show="file" x-transition>

            <!-- Video preview -->
            <div class="mg-video-wrap mb-3">
              <video x-ref="previewVideo" :src="videoUrl" controls preload="metadata" class="w-full"></video>
            </div>

            <!-- Meta pills -->
            <div class="mg-meta-row">
              <span class="mg-meta-pill" x-show="videoDuration > 0">
                &#x23F1; Duration: <span x-text="fmtTime(videoDuration)"></span>
              </span>
              <span class="mg-meta-pill" x-show="videoW > 0">
                &#x1F4F9; <span x-text="videoW + '\xD7' + videoH"></span>
              </span>
              <span class="mg-meta-pill">
                &#x1F4C4; <span x-text="fmtSize(file ? file.size : 0)"></span>
              </span>
              <button
                class="mg-meta-pill"
                style="background:#fff;cursor:pointer"
                @click="resetAll()"
              >&#x2715; Change file</button>
            </div>

            <!-- File error -->
            <p x-show="fileError" class="mt-2 text-sm text-red-600 font-medium" x-text="fileError"></p>

            <!-- Settings (only when metadata loaded) -->
            <div x-show="videoDuration > 0" x-transition class="mt-5 space-y-5">
              <hr class="border-gray-100">

              <!-- Clip range -->
              <div>
                <p class="mg-section-label">Clip Range</p>
                <div class="mg-time-row">
                  <div>
                    <label class="form-label">Start time (seconds)</label>
                    <input
                      type="number"
                      x-model.number="startTime"
                      min="0"
                      :max="Math.max(0, videoDuration - 0.1)"
                      step="0.5"
                      class="mg-num-input"
                      :class="{'is-error': startError}"
                    >
                    <p x-show="startError" class="mg-field-error" x-text="startError"></p>
                  </div>
                  <div>
                    <label class="form-label">Duration (seconds, max 30)</label>
                    <input
                      type="number"
                      x-model.number="clipDuration"
                      min="0.5"
                      max="30"
                      step="0.5"
                      class="mg-num-input"
                      :class="{'is-error': durationError}"
                    >
                    <p x-show="durationError" class="mg-field-error" x-text="durationError"></p>
                  </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                  Video ends at <strong x-text="fmtTime(videoDuration)"></strong>
                  <span x-show="!durationError && !startError && clipDuration > 0">
                    &mdash; clip <strong x-text="fmtTime(startTime)"></strong> &rarr; <strong x-text="fmtTime(startTime + clipDuration)"></strong>
                  </span>
                </p>
              </div>

              <!-- FPS -->
              <div>
                <p class="mg-section-label">Frames Per Second (FPS)</p>
                <div class="mg-radio-group">
                  <template x-for="opt in fpsOpts" :key="opt.val">
                    <label class="mg-radio-btn">
                      <input type="radio" :value="opt.val" x-model.number="fps">
                      <span x-text="opt.label"></span>
                    </label>
                  </template>
                </div>
                <p class="text-xs text-gray-500 mt-1">Higher FPS = smoother animation but larger file</p>
              </div>

              <!-- Output width -->
              <div>
                <p class="mg-section-label">Output Width</p>
                <div class="mg-radio-group">
                  <template x-for="opt in widthOpts" :key="opt.val">
                    <label class="mg-radio-btn">
                      <input type="radio" :value="opt.val" x-model="outputWidth">
                      <span x-text="opt.label"></span>
                    </label>
                  </template>
                </div>
              </div>

              <!-- Quality -->
              <div>
                <p class="mg-section-label">GIF Quality</p>
                <div class="mg-radio-group">
                  <template x-for="opt in qualityOpts" :key="opt.val">
                    <label class="mg-radio-btn">
                      <input type="radio" :value="opt.val" x-model="quality">
                      <span x-text="opt.label"></span>
                    </label>
                  </template>
                </div>
                <p class="text-xs text-gray-500 mt-1">High quality produces better colors but takes longer</p>
              </div>

              <!-- Frame count warning -->
              <div class="mg-warn" x-show="estFrames > 150 && !durationError && !startError">
                <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5">
                  <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                  <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span>
                  <strong x-text="estFrames"></strong> frames &mdash; the resulting GIF may be large (&gt;5 MB).
                  Consider reducing duration, FPS, or width.
                </span>
              </div>

              <!-- Frame count info (normal) -->
              <p
                x-show="estFrames > 0 && estFrames <= 150 && !durationError && !startError"
                class="text-sm text-gray-500"
              >
                Estimated <strong x-text="estFrames" class="text-gray-700"></strong> frames &middot;
                Output: <strong x-text="calcOutputDims()" class="text-gray-700"></strong>
              </p>

              <!-- Convert button -->
              <div class="flex gap-3 pt-1">
                <button
                  class="mg-btn-primary"
                  :disabled="!canConvert"
                  @click="startConvert()"
                >
                  <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8l4 4-4 4"/>
                  </svg>
                  Convert to GIF
                </button>
              </div>

            </div><!-- /settings -->
          </div><!-- /file loaded -->

        </div>
      </div>

      <!-- ── PROGRESS CARD ── -->
      <div class="card" x-show="phase === 'loading' || phase === 'extracting' || phase === 'encoding'" x-transition>
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-5" x-text="phaseTitle"></h2>

          <div class="mg-progress-bar mb-3">
            <div class="mg-progress-fill" :style="'width:' + progress + '%'"></div>
          </div>
          <div class="flex justify-between text-sm text-gray-500 mb-6">
            <span x-text="phaseDetail"></span>
            <span x-text="progress + '%'"></span>
          </div>

          <div class="space-y-3">
            <!-- Step 1: Library -->
            <div class="flex items-center gap-3 text-sm">
              <div class="mg-phase-dot"
                :class="phase==='loading' ? 'active' : (progress >= 10 ? 'done' : 'wait')"></div>
              <span :class="phase==='loading' ? 'font-semibold text-gray-900' : (progress>=10 ? 'text-gray-400 line-through' : 'text-gray-400')">
                Loading GIF library
              </span>
            </div>
            <!-- Step 2: Extract -->
            <div class="flex items-center gap-3 text-sm">
              <div class="mg-phase-dot"
                :class="phase==='extracting' ? 'active' : (phase==='encoding'||phase==='done' ? 'done' : 'wait')"></div>
              <span :class="phase==='extracting' ? 'font-semibold text-gray-900' : (phase==='encoding'||phase==='done' ? 'text-gray-400 line-through' : 'text-gray-400')">
                Extracting frames
                <span x-show="phase==='extracting'" class="text-amber-600 not-italic">
                  (<span x-text="framesDone"></span>/<span x-text="totalFrames"></span>)
                </span>
              </span>
            </div>
            <!-- Step 3: Encode -->
            <div class="flex items-center gap-3 text-sm">
              <div class="mg-phase-dot"
                :class="phase==='encoding' ? 'active' : (phase==='done' ? 'done' : 'wait')"></div>
              <span :class="phase==='encoding' ? 'font-semibold text-gray-900' : (phase==='done' ? 'text-gray-400 line-through' : 'text-gray-400')">
                Encoding GIF
              </span>
            </div>
          </div>

          <!-- Conversion error -->
          <div x-show="convError" class="mt-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700" x-text="convError"></div>
          <button x-show="convError" class="mg-btn-secondary mt-3" @click="resetAll()">Try Again</button>
        </div>
      </div>

      <!-- ── RESULT CARD ── -->
      <div class="card" x-show="phase === 'done'" x-transition>
        <div class="p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
              <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Your GIF is Ready!</h2>
          </div>

          <!-- GIF preview -->
          <div class="mg-gif-preview mb-4">
            <img :src="gifUrl" alt="Generated GIF" style="image-rendering:auto">
          </div>

          <!-- Stats row -->
          <div class="mg-stat-row">
            <div class="mg-stat">
              <div class="mg-stat-val" x-text="gifW + '\xD7' + gifH"></div>
              <div class="mg-stat-lbl">Dimensions</div>
            </div>
            <div class="mg-stat">
              <div class="mg-stat-val" x-text="fmtSize(gifSizeBytes)"></div>
              <div class="mg-stat-lbl">File Size</div>
            </div>
            <div class="mg-stat">
              <div class="mg-stat-val" x-text="fps + ' fps'"></div>
              <div class="mg-stat-lbl">Frame Rate</div>
            </div>
            <div class="mg-stat">
              <div class="mg-stat-val" x-text="totalFrames"></div>
              <div class="mg-stat-lbl">Frames</div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 mt-5">
            <a :href="gifUrl" :download="gifFilename" class="mg-btn-download">
              <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download GIF
            </a>
            <button class="mg-btn-secondary" @click="resetAll()">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M1 4v6h6M23 20v-6h-6"/>
                <path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15"/>
              </svg>
              Convert Another
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
              <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
              <span><strong class="text-gray-800">Upload</strong> your MP4 file &mdash; it never leaves your browser, 100% private.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
              <span><strong class="text-gray-800">Configure</strong> the clip range, frame rate, output size, and quality preset.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
              <span><strong class="text-gray-800">Convert</strong> &mdash; each frame is drawn to an HTML5 canvas and encoded into a GIF locally using gif.js.</span>
            </li>
            <li class="flex gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
              <span><strong class="text-gray-800">Download</strong> your GIF &mdash; no watermarks, no registration, no limits.</span>
            </li>
          </ol>
        </div>
      </div>

    </div><!-- /main column -->

    <!-- ════════════ SIDEBAR ════════════ -->
    <div class="space-y-5">

      <!-- About GIF -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">About GIF Format</h3>
        <p class="text-sm text-gray-600 leading-relaxed mb-3">GIF supports up to 256 colors per frame, making it ideal for short animations, memes, and previews. Plays automatically with no click required.</p>
        <div class="space-y-1.5 text-sm text-gray-600">
          <div class="flex items-start gap-2"><span class="text-amber-500 font-bold">&#x2713;</span><span>Auto-plays everywhere (email, Slack, social)</span></div>
          <div class="flex items-start gap-2"><span class="text-amber-500 font-bold">&#x2713;</span><span>Infinite loop by default</span></div>
          <div class="flex items-start gap-2"><span class="text-amber-500 font-bold">&#x2713;</span><span>No codec or player needed</span></div>
          <div class="flex items-start gap-2"><span class="text-red-400 font-bold">&#x2717;</span><span>No audio &mdash; GIFs are always silent</span></div>
          <div class="flex items-start gap-2"><span class="text-red-400 font-bold">&#x2717;</span><span>256-color limit per frame</span></div>
        </div>
      </div>

      <!-- Tips -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Tips for Best Results</h3>
        <div>
          <div class="mg-tip">
            <span class="text-amber-600">&#x23F1;</span>
            <p class="text-sm text-gray-600"><strong class="text-gray-800">Keep clips short.</strong> 5&ndash;10 seconds at 10&nbsp;fps makes compact, shareable GIFs.</p>
          </div>
          <div class="mg-tip">
            <span class="text-amber-600">&#x1F4D0;</span>
            <p class="text-sm text-gray-600"><strong class="text-gray-800">480&nbsp;px width</strong> is the sweet spot between quality and file size for most uses.</p>
          </div>
          <div class="mg-tip">
            <span class="text-amber-600">&#x1F3A8;</span>
            <p class="text-sm text-gray-600"><strong class="text-gray-800">High quality</strong> works best for text, logos, and flat-color animations.</p>
          </div>
          <div class="mg-tip">
            <span class="text-amber-600">&#x26A1;</span>
            <p class="text-sm text-gray-600"><strong class="text-gray-800">Low quality / 5&nbsp;fps</strong> for quick reactions &mdash; fast encode and tiny file.</p>
          </div>
        </div>
      </div>

      <!-- Size guide -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Estimated File Sizes</h3>
        <table class="w-full text-sm">
          <thead>
            <tr class="text-xs text-gray-500 border-b">
              <th class="text-left pb-1.5 font-medium">Settings</th>
              <th class="text-right pb-1.5 font-medium">~Size</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-600">
            <tr><td class="py-1.5">320px &middot; 5fps &middot; 5s</td><td class="text-right py-1.5 font-medium">~0.3 MB</td></tr>
            <tr><td class="py-1.5">480px &middot; 10fps &middot; 5s</td><td class="text-right py-1.5 font-medium">~1&ndash;2 MB</td></tr>
            <tr><td class="py-1.5">480px &middot; 10fps &middot; 10s</td><td class="text-right py-1.5 font-medium">~2&ndash;4 MB</td></tr>
            <tr><td class="py-1.5">640px &middot; 15fps &middot; 10s</td><td class="text-right py-1.5 font-medium">~6&ndash;10 MB</td></tr>
          </tbody>
        </table>
      </div>

    </div><!-- /sidebar -->

  </div>
</div>

<!-- Hidden canvas for frame extraction -->
<canvas id="mg-capture-canvas" style="position:fixed;left:-99999px;top:-99999px;pointer-events:none;visibility:hidden"></canvas>

@endsection

@push('scripts')
<script>
function mgTool() {
  return {
    // ── File ──
    file: null,
    fileError: '',
    isDragging: false,
    videoUrl: null,
    videoDuration: 0,
    videoW: 0,
    videoH: 0,

    // ── Settings ──
    startTime: 0,
    clipDuration: 5,
    fps: 10,
    outputWidth: '480',
    quality: 'medium',

    // ── Conversion state ──
    phase: 'idle',
    progress: 0,
    framesDone: 0,
    totalFrames: 0,

    // ── Results ──
    gifUrl: null,
    gifSizeBytes: 0,
    gifW: 0,
    gifH: 0,
    gifFilename: 'animation.gif',

    // ── Error ──
    convError: '',

    // ── Cached ──
    _workerBlobUrl: null,
    _gifLibLoaded: false,

    // ── Option definitions ──
    fpsOpts: [
      { val: 5,  label: '5 fps — Compact' },
      { val: 10, label: '10 fps — Balanced' },
      { val: 15, label: '15 fps — Smooth' },
      { val: 20, label: '20 fps — Fluent' },
    ],
    widthOpts: [
      { val: '320',      label: '320 px' },
      { val: '480',      label: '480 px' },
      { val: '640',      label: '640 px' },
      { val: 'original', label: 'Original' },
    ],
    qualityOpts: [
      { val: 'low',    label: 'Low (fast)' },
      { val: 'medium', label: 'Medium' },
      { val: 'high',   label: 'High (slow)' },
    ],

    // ── Computed getters ──
    get startError() {
      if (this.startTime < 0) return 'Start time cannot be negative.';
      if (this.videoDuration > 0 && this.startTime >= this.videoDuration)
        return 'Start time exceeds video length (' + this.fmtTime(this.videoDuration) + ').';
      return '';
    },
    get durationError() {
      if (this.clipDuration <= 0) return 'Duration must be greater than 0.';
      if (this.clipDuration > 30) return 'Maximum clip duration is 30 seconds.';
      if (this.videoDuration > 0 && (parseFloat(this.startTime) + parseFloat(this.clipDuration)) > this.videoDuration)
        return 'Clip end (' + this.fmtTime(parseFloat(this.startTime) + parseFloat(this.clipDuration)) + ') exceeds video length (' + this.fmtTime(this.videoDuration) + ').';
      return '';
    },
    get estFrames() {
      if (this.durationError || this.startError || this.clipDuration <= 0) return 0;
      return Math.min(Math.round(this.clipDuration * this.fps), 300);
    },
    get canConvert() {
      return !!(this.file && !this.fileError && this.videoDuration > 0 && !this.startError && !this.durationError);
    },
    get phaseTitle() {
      var map = { loading: 'Loading GIF Library…', extracting: 'Extracting Frames…', encoding: 'Encoding GIF…' };
      return map[this.phase] || '';
    },
    get phaseDetail() {
      if (this.phase === 'loading')    return 'Downloading gif.js library (∸60 KB)…';
      if (this.phase === 'extracting') return 'Capturing frame ' + this.framesDone + ' of ' + this.totalFrames + '…';
      if (this.phase === 'encoding')   return 'Running GIF encoder in Web Worker…';
      return '';
    },

    // ── File handling ──
    onDrop: function(e) {
      this.isDragging = false;
      var files = e.dataTransfer.files;
      if (files && files.length) this.loadFile(files[0]);
    },
    onFileChange: function(e) {
      var files = e.target.files;
      if (files && files.length) this.loadFile(files[0]);
    },
    loadFile: function(f) {
      this.fileError = '';
      var ext = f.name.split('.').pop().toLowerCase();
      if (ext !== 'mp4' && f.type !== 'video/mp4') {
        this.fileError = 'Please select an MP4 file (.mp4). Other formats are not supported.';
        return;
      }
      if (f.size > 500 * 1024 * 1024) {
        this.fileError = 'File is too large. Maximum size is 500 MB.';
        return;
      }
      if (f.size < 1024) {
        this.fileError = 'File appears to be empty or corrupt.';
        return;
      }
      this.file = f;
      this.gifFilename = f.name.replace(/\.mp4$/i, '') + '.gif';
      if (this.videoUrl) URL.revokeObjectURL(this.videoUrl);
      this.videoUrl = URL.createObjectURL(f);

      var vm = this;
      this.$nextTick(function() {
        var vid = vm.$refs.previewVideo;
        if (!vid) return;
        vid.onloadedmetadata = function() {
          vm.videoDuration = vid.duration;
          vm.videoW = vid.videoWidth;
          vm.videoH = vid.videoHeight;
          // Sensible defaults
          vm.startTime   = 0;
          vm.clipDuration = Math.min(5, Math.max(0.5, Math.floor(vid.duration * 10) / 10));
        };
        vid.onerror = function() {
          vm.fileError = 'Could not read video metadata. Ensure the file is a valid MP4.';
        };
      });
    },

    // ── Helpers ──
    calcOutputDims: function() {
      if (!this.videoW) return '';
      var w = this.outputWidth === 'original' ? this.videoW : parseInt(this.outputWidth);
      var h = Math.round(w * this.videoH / this.videoW);
      return w + '\xD7' + h + ' px';
    },
    fmtTime: function(secs) {
      secs = parseFloat(secs) || 0;
      var m  = Math.floor(secs / 60);
      var s  = Math.floor(secs % 60);
      var ms = Math.round((secs % 1) * 10);
      if (m > 0) return m + 'm ' + s + 's';
      return s + '.' + ms + 's';
    },
    fmtSize: function(bytes) {
      if (!bytes) return '0 B';
      if (bytes < 1024) return bytes + ' B';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(2) + ' MB';
    },
    _loadScript: function(src) {
      return new Promise(function(resolve, reject) {
        if (document.querySelector('script[src="' + src + '"]')) { resolve(); return; }
        var s = document.createElement('script');
        s.src = src;
        s.onload = resolve;
        s.onerror = function() { reject(new Error('Failed to load: ' + src.split('/').slice(-1)[0])); };
        document.head.appendChild(s);
      });
    },

    // ── Main conversion ──
    startConvert: async function() {
      if (!this.canConvert) return;

      this.phase      = 'loading';
      this.progress   = 0;
      this.framesDone = 0;
      this.convError  = '';

      try {
        // Step 1 — load gif.js
        var CDN        = 'https://cdn.jsdelivr.net/npm/gif.js@0.2.0/dist/gif.js';
        var CDN_FB     = 'https://unpkg.com/gif.js@0.2.0/dist/gif.js';
        var WORKER     = 'https://cdn.jsdelivr.net/npm/gif.js@0.2.0/dist/gif.worker.js';
        var WORKER_FB  = 'https://unpkg.com/gif.js@0.2.0/dist/gif.worker.js';

        if (!this._gifLibLoaded) {
          try { await this._loadScript(CDN); }
          catch(e) { await this._loadScript(CDN_FB); }
          this._gifLibLoaded = true;
        }

        // Step 2 — fetch worker as blob URL (avoids CORS on Worker constructor)
        if (!this._workerBlobUrl) {
          var wres;
          try { wres = await fetch(WORKER); if (!wres.ok) throw new Error(); }
          catch(e) { wres = await fetch(WORKER_FB); }
          if (!wres.ok) throw new Error('Could not fetch GIF worker (HTTP ' + wres.status + ').');
          var wtext = await wres.text();
          var wblob = new Blob([wtext], { type: 'application/javascript' });
          this._workerBlobUrl = URL.createObjectURL(wblob);
        }

        this.progress = 10;

        // Step 3 — setup canvas + gather dimensions
        var canvas  = document.getElementById('mg-capture-canvas');
        var ctx     = canvas.getContext('2d');
        var video   = this.$refs.previewVideo;

        var outW = this.outputWidth === 'original' ? this.videoW : parseInt(this.outputWidth);
        var outH = Math.round(outW * this.videoH / this.videoW);
        if (outW < 1 || outH < 1) throw new Error('Invalid output dimensions.');
        canvas.width  = outW;
        canvas.height = outH;
        this.gifW = outW;
        this.gifH = outH;

        var startTime   = parseFloat(this.startTime)   || 0;
        var clipDur     = parseFloat(this.clipDuration) || 5;
        var fps         = parseInt(this.fps) || 10;
        var frameDelay  = Math.round(1000 / fps);
        var totalFrames = Math.min(Math.round(clipDur * fps), 300);
        if (totalFrames < 1) throw new Error('Clip is too short to produce any frames.');
        this.totalFrames = totalFrames;

        // Step 4 — create GIF encoder
        var GIFLib = window.GIF;
        if (!GIFLib) throw new Error('gif.js library did not expose window.GIF. Please refresh and try again.');

        var qualityMap = { low: 20, medium: 10, high: 1 };
        var vm = this;

        var gif = new GIFLib({
          workers:      2,
          quality:      qualityMap[this.quality] || 10,
          workerScript: this._workerBlobUrl,
          width:        outW,
          height:       outH,
          repeat:       0,
        });

        gif.on('progress', function(p) {
          vm.progress = Math.round(80 + p * 19);
        });

        // Step 5 — extract frames by seeking through the video
        this.phase = 'extracting';
        video.pause();
        video.muted = true;

        for (var i = 0; i < totalFrames; i++) {
          var frameTime = startTime + (i / fps);

          await new Promise(function(resolve) {
            var handler = function() { resolve(); };
            video.addEventListener('seeked', handler, { once: true });
            video.currentTime = frameTime;
          });

          ctx.clearRect(0, 0, outW, outH);
          ctx.drawImage(video, 0, 0, outW, outH);
          gif.addFrame(ctx, { copy: true, delay: frameDelay });

          vm.framesDone = i + 1;
          vm.progress   = Math.round(10 + (i / totalFrames) * 70);
        }

        // Step 6 — render GIF
        this.phase    = 'encoding';
        this.progress = 80;

        await new Promise(function(resolve, reject) {
          gif.on('finished', function(blob) {
            vm.gifSizeBytes = blob.size;
            if (vm.gifUrl) URL.revokeObjectURL(vm.gifUrl);
            vm.gifUrl = URL.createObjectURL(blob);
            resolve();
          });
          gif.on('error', function(err) {
            reject(new Error((err && err.message) ? err.message : 'GIF encoding failed.'));
          });
          gif.render();
        });

        this.progress = 100;
        this.phase    = 'done';

      } catch (err) {
        this.convError = 'Conversion failed: ' + (err.message || String(err));
        // Phase stays as-is so the error banner is visible
      }
    },

    resetAll: function() {
      if (this.videoUrl) { URL.revokeObjectURL(this.videoUrl); }
      if (this.gifUrl)   { URL.revokeObjectURL(this.gifUrl);   }
      this.file          = null;
      this.fileError     = '';
      this.isDragging    = false;
      this.videoUrl      = null;
      this.videoDuration = 0;
      this.videoW        = 0;
      this.videoH        = 0;
      this.startTime     = 0;
      this.clipDuration  = 5;
      this.fps           = 10;
      this.outputWidth   = '480';
      this.quality       = 'medium';
      this.phase         = 'idle';
      this.progress      = 0;
      this.framesDone    = 0;
      this.totalFrames   = 0;
      this.gifUrl        = null;
      this.gifSizeBytes  = 0;
      this.gifW          = 0;
      this.gifH          = 0;
      this.convError     = '';
      var vm = this;
      this.$nextTick(function() {
        if (vm.$refs.fileInput) vm.$refs.fileInput.value = '';
      });
    },

  }; // end return
}
</script>
@endpush
