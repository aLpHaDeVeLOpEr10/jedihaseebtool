@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')

<style>
/* ══════════════════════════════════════════
   MP4 Video Editor  (prefix: ve-)
   ══════════════════════════════════════════ */

/* Hero */
.ve-hero{background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 50%,#312e81 100%);border-bottom:1px solid #1e1b4b;padding:2rem 0}
.ve-hero h1{color:#fff}
.ve-badge{display:inline-flex;align-items:center;gap:.375rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:#e0e7ff;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* Drop zone */
.ve-drop{border:2px dashed #a5b4fc;border-radius:16px;padding:3rem 1.5rem;text-align:center;cursor:pointer;background:#f8f7ff;transition:all .2s;user-select:none}
.ve-drop:hover,.ve-drop.over{border-color:#4f46e5;background:#eef2ff}
.ve-drop-icon{width:68px;height:68px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}

/* Editor card */
.ve-editor{background:#fff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06)}

/* Video wrapper */
.ve-video-wrap{background:#000;position:relative;display:flex;align-items:center;justify-content:center;min-height:200px}
.ve-video-wrap video{display:block;max-width:100%;max-height:420px;width:100%}

/* Toolbar */
.ve-toolbar{display:flex;align-items:center;gap:.5rem;padding:.625rem 1rem;background:#f8fafc;border-bottom:1px solid #e5e7eb;flex-wrap:wrap;justify-content:space-between}
.ve-toolbar-l{display:flex;align-items:center;gap:.375rem;flex-wrap:wrap}
.ve-toolbar-r{display:flex;align-items:center;gap:.375rem;flex-wrap:wrap}

/* Buttons */
.ve-btn{display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .7rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8rem;font-weight:500;color:#374151;cursor:pointer;background:#fff;transition:all .12s;white-space:nowrap;line-height:1.3}
.ve-btn:hover:not(:disabled){border-color:#a5b4fc;color:#4338ca;background:#eef2ff}
.ve-btn:disabled{opacity:.4;cursor:not-allowed}
.ve-btn.active{border-color:#4f46e5;background:#eef2ff;color:#4338ca}
.ve-btn-primary{background:linear-gradient(135deg,#4f46e5,#7c3aed);border-color:#4f46e5;color:#fff;padding:.45rem 1rem}
.ve-btn-primary:hover:not(:disabled){background:linear-gradient(135deg,#4338ca,#6d28d9);border-color:#4338ca;color:#fff}
.ve-btn-green{background:linear-gradient(135deg,#059669,#047857);border-color:#059669;color:#fff;padding:.45rem 1rem}
.ve-btn-green:hover:not(:disabled){background:linear-gradient(135deg,#047857,#065f46);border-color:#047857;color:#fff}
.ve-btn-red{border-color:#fca5a5;color:#dc2626}
.ve-btn-red:hover:not(:disabled){background:#fef2f2;border-color:#ef4444}
.ve-divider{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}

/* Timeline */
.ve-timeline-wrap{padding:1rem 1.25rem .5rem;background:#fff;border-top:1px solid #f3f4f6}
.ve-timeline{position:relative;height:44px;background:#f1f5f9;border-radius:8px;cursor:pointer;user-select:none;touch-action:none;margin:0 8px}
.ve-tl-bg{position:absolute;inset:0;border-radius:8px;overflow:hidden}
.ve-tl-bg-inner{position:absolute;inset:0;background:linear-gradient(90deg,#cbd5e1 1px,transparent 1px) 0 0/10% 100%;opacity:.4}
.ve-tl-select{position:absolute;top:0;height:100%;background:rgba(79,70,229,.15);border-top:2.5px solid #4f46e5;border-bottom:2.5px solid #4f46e5;pointer-events:none}
.ve-handle{position:absolute;top:50%;width:14px;height:36px;transform:translate(-50%,-50%);background:#4f46e5;border-radius:4px;cursor:ew-resize;touch-action:none;z-index:4;box-shadow:0 2px 8px rgba(79,70,229,.4)}
.ve-handle::after{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:2px;height:12px;background:rgba(255,255,255,.7);border-radius:1px;box-shadow:4px 0 rgba(255,255,255,.7),-4px 0 rgba(255,255,255,.7)}
.ve-playhead{position:absolute;top:-5px;bottom:-5px;width:2.5px;background:#ef4444;transform:translateX(-50%);z-index:5;pointer-events:none;border-radius:1px}
.ve-playhead::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid #ef4444}
.ve-tl-labels{display:flex;justify-content:space-between;padding:4px 8px 0;font-size:.68rem;color:#94a3b8;font-variant-numeric:tabular-nums}

/* Time inputs */
.ve-time-input{width:88px;padding:.35rem .5rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;font-variant-numeric:tabular-nums;text-align:center;background:#fff;transition:border-color .15s}
.ve-time-input:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1)}

/* Sliders & controls */
.ve-range{-webkit-appearance:none;appearance:none;width:100%;height:4px;border-radius:999px;background:linear-gradient(to right,#4f46e5 var(--p,50%),#e5e7eb var(--p,50%));outline:none;cursor:pointer}
.ve-range::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 1px 4px rgba(79,70,229,.4);cursor:pointer}
.ve-range::-moz-range-thumb{width:16px;height:16px;border-radius:50%;background:#4f46e5;border:2px solid #fff;cursor:pointer;border:none}

/* Toggle switch */
.ve-toggle{position:relative;width:40px;height:22px;flex-shrink:0}
.ve-toggle input{opacity:0;width:0;height:0;position:absolute}
.ve-toggle-track{position:absolute;inset:0;background:#e5e7eb;border-radius:999px;transition:background .2s;cursor:pointer}
.ve-toggle input:checked+.ve-toggle-track{background:#4f46e5}
.ve-toggle-thumb{position:absolute;top:3px;left:3px;width:16px;height:16px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);pointer-events:none}
.ve-toggle input:checked~.ve-toggle-thumb{transform:translateX(18px)}

/* Select */
.ve-select{padding:.4rem .625rem;border:1.5px solid #e5e7eb;border-radius:7px;font-size:.8125rem;color:#1f2937;background:#fff;cursor:pointer;transition:border-color .15s}
.ve-select:focus{outline:none;border-color:#4f46e5}

/* Section label */
.ve-sec{font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#4f46e5;margin-bottom:.5rem}

/* Info panel */
.ve-info-row{display:flex;align-items:center;justify-content:space-between;font-size:.8125rem;padding:.3rem 0;border-bottom:1px solid #f3f4f6}
.ve-info-row:last-child{border-bottom:none}
.ve-info-key{color:#6b7280;font-weight:500}
.ve-info-val{color:#1f2937;font-weight:600;font-variant-numeric:tabular-nums}

/* Progress bar */
.ve-progress-track{height:8px;background:#e5e7eb;border-radius:999px;overflow:hidden}
.ve-progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,#4f46e5,#7c3aed);transition:width .4s ease}

/* Processing card */
.ve-proc-card{background:#fff;border-radius:14px;border:1px solid #e5e7eb;padding:3rem 2rem;text-align:center}
.ve-proc-spin{width:52px;height:52px;border:3px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:ve-spin .8s linear infinite}
@keyframes ve-spin{to{transform:rotate(360deg)}}

/* Stage indicator */
.ve-stage{display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:#6b7280;justify-content:center}
.ve-stage-dot{width:6px;height:6px;border-radius:50%;background:#e5e7eb;flex-shrink:0}
.ve-stage-dot.done{background:#10b981}
.ve-stage-dot.active{background:#4f46e5;animation:ve-pulse 1s ease-in-out infinite}
@keyframes ve-pulse{0%,100%{opacity:1}50%{opacity:.4}}

/* Comparison (done phase) */
.ve-compare-tab{padding:.3rem .7rem;font-size:.78rem;font-weight:600;cursor:pointer;border-radius:6px;color:#6b7280;border:1px solid transparent}
.ve-compare-tab.active{background:#fff;color:#4f46e5;border-color:#c7d2fe}

/* Result stat */
.ve-stat-box{background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:.75rem 1rem;text-align:center}
.ve-stat-val{font-size:1.125rem;font-weight:700;color:#1f2937}
.ve-stat-lbl{font-size:.7rem;color:#9ca3af;margin-top:.15rem;font-weight:500}

/* Warning strip */
.ve-warn{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.625rem .875rem;font-size:.78rem;color:#92400e;display:flex;gap:.5rem;align-items:flex-start}
</style>

{{-- ── Hero ── --}}
<div class="ve-hero">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
      <div style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl sm:text-3xl font-bold">MP4 Video Editor</h1>
          <span class="ve-badge">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Browser-Only &bull; Powered by FFmpeg.wasm
          </span>
        </div>
        <p class="text-sm" style="color:#c7d2fe">Trim, mute, resize and export MP4 videos entirely in your browser — nothing is uploaded anywhere.</p>
      </div>
    </div>
  </div>
</div>

{{-- ── Tool wrapper ── --}}
<div class="bg-gray-50 min-h-screen pb-16">
<div
  class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
  x-data="veEditor()"
  x-init="init()"
>

  {{-- ══ UPLOAD ══ --}}
  <div x-show="phase==='upload'" x-transition.opacity>

    {{-- Error --}}
    <div x-show="errorMsg" x-cloak
         class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <span x-text="errorMsg"></span>
    </div>

    <div class="max-w-2xl mx-auto">
      <div class="card p-6 sm:p-8">
        <div
          class="ve-drop"
          :class="{ over: isDragging }"
          @dragover.prevent="isDragging=true"
          @dragleave.prevent="isDragging=false"
          @drop.prevent="onDrop($event)"
          @click="$refs.fileIn.click()"
        >
          <div class="ve-drop-icon">
            <svg width="34" height="34" fill="none" stroke="#4f46e5" stroke-width="1.7" viewBox="0 0 24 24">
              <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
            </svg>
          </div>
          <p class="text-gray-800 font-semibold text-lg mb-1.5">Drop your video here</p>
          <p class="text-gray-500 text-sm mb-3">or click to browse from your device</p>
          <p class="text-xs text-gray-400">MP4, MOV, AVI, WEBM &bull; Max 500 MB</p>
          <input type="file" x-ref="fileIn" accept="video/*" class="hidden" @change="onFileChange($event)">
        </div>

        <div class="mt-5 pt-5 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs text-gray-600">
          <div><div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-1.5"><svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12l7 7 7-7"/></svg></div>Trim &amp; Cut</div>
          <div><div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-1.5"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="1" y1="1" x2="23" y2="23"/><path d="M9 9v3a3 3 0 005.12 2.12M15 9.34V4a3 3 0 00-5.94-.6M17 16.95A7 7 0 015 12v-2m14 0v2a7 7 0 01-.11 1.23M12 20v4M8 20h8"/></svg></div>Mute Audio</div>
          <div><div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-1.5"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>Resize</div>
          <div><div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-1.5"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg></div>Export MP4</div>
        </div>

        <div class="mt-4 ve-warn">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <div><strong>First use:</strong> FFmpeg engine (~25 MB) downloads once from CDN and is cached. Subsequent uses are instant. Video files never leave your device.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══ EDIT ══ --}}
  <div x-show="phase==='edit'" x-cloak x-transition.opacity>

    {{-- Error in edit --}}
    <div x-show="errorMsg" x-cloak
         class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
      <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <span x-text="errorMsg"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

      {{-- ── Left: Video + Timeline ── --}}
      <div class="lg:col-span-2 flex flex-col gap-4">

        {{-- Video editor card --}}
        <div class="ve-editor">

          {{-- Toolbar --}}
          <div class="ve-toolbar">
            <div class="ve-toolbar-l">
              {{-- Play / Pause --}}
              <button class="ve-btn" @click="togglePlay()" :title="isPlaying ? 'Pause' : 'Play'">
                <template x-if="!isPlaying">
                  <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </template>
                <template x-if="isPlaying">
                  <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                </template>
                <span x-text="isPlaying ? 'Pause' : 'Play'"></span>
              </button>

              <div class="ve-divider"></div>

              {{-- Current time --}}
              <span class="text-xs text-gray-500 font-mono tabular-nums" x-text="fmtTime(currentTime) + ' / ' + fmtTime(duration)"></span>
            </div>
            <div class="ve-toolbar-r">
              {{-- File info --}}
              <span class="text-xs text-gray-500 truncate max-w-[140px]" x-text="fileName"></span>
              <div class="ve-divider"></div>
              <button class="ve-btn ve-btn-red" @click="resetAll()">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                New Video
              </button>
            </div>
          </div>

          {{-- Video player --}}
          <div class="ve-video-wrap">
            <video
              x-ref="videoEl"
              class="w-full"
              style="max-height:420px"
              preload="metadata"
              playsinline
              @timeupdate="currentTime=$el.currentTime"
              @ended="isPlaying=false"
              @play="isPlaying=true"
              @pause="isPlaying=false"
              @loadedmetadata="onVideoLoaded($event)"
            ></video>
          </div>

          {{-- ── Timeline ── --}}
          <div class="ve-timeline-wrap">
            <div class="flex items-center justify-between mb-2">
              <span class="ve-sec" style="margin:0">Timeline</span>
              <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                Drag handles to set trim points
              </div>
            </div>

            {{-- Main scrubber track --}}
            <div
              class="ve-timeline"
              x-ref="timeline"
              @pointerdown="onTrackClick($event)"
            >
              {{-- Background grid --}}
              <div class="ve-tl-bg">
                <div class="ve-tl-bg-inner"></div>
              </div>

              {{-- Selected region --}}
              <div
                class="ve-tl-select"
                :style="{ left: startPct+'%', width: (endPct-startPct)+'%' }"
              ></div>

              {{-- Start handle --}}
              <div
                class="ve-handle"
                :style="{ left: startPct+'%' }"
                @pointerdown.stop="startDrag('start',$event)"
                title="Drag to set start time"
              ></div>

              {{-- End handle --}}
              <div
                class="ve-handle"
                :style="{ left: endPct+'%' }"
                @pointerdown.stop="startDrag('end',$event)"
                title="Drag to set end time"
              ></div>

              {{-- Playhead --}}
              <div
                class="ve-playhead"
                :style="{ left: playPct+'%' }"
              ></div>
            </div>

            {{-- Timeline labels --}}
            <div class="ve-tl-labels">
              <span>0:00</span>
              <span x-text="fmtTime(duration * .25)"></span>
              <span x-text="fmtTime(duration * .5)"></span>
              <span x-text="fmtTime(duration * .75)"></span>
              <span x-text="fmtTime(duration)"></span>
            </div>

            {{-- Trim point controls --}}
            <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 border-t border-gray-100">

              {{-- Start --}}
              <div class="flex items-center gap-1.5">
                <span class="text-xs font-semibold text-gray-500">IN</span>
                <input
                  type="text"
                  class="ve-time-input"
                  :value="fmtTime(startTime)"
                  @change="onStartInput($event.target.value)"
                  placeholder="00:00"
                >
                <button class="ve-btn text-xs" @click="setStart()" title="Set from current position">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 15 14"/></svg>
                  Set
                </button>
              </div>

              <div class="flex-1 text-center text-xs text-gray-400 tabular-nums">
                Selected: <strong class="text-gray-700" x-text="fmtTime(trimDuration)"></strong>
                <span x-show="trimDuration >= duration" class="text-emerald-600">(full video)</span>
              </div>

              {{-- End --}}
              <div class="flex items-center gap-1.5">
                <button class="ve-btn text-xs" @click="setEnd()" title="Set from current position">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 15 14"/></svg>
                  Set
                </button>
                <input
                  type="text"
                  class="ve-time-input"
                  :value="fmtTime(endTime)"
                  @change="onEndInput($event.target.value)"
                  placeholder="00:00"
                >
                <span class="text-xs font-semibold text-gray-500">OUT</span>
              </div>
            </div>
          </div><!-- /timeline wrap -->

        </div><!-- /editor -->

        {{-- Video info card --}}
        <div class="card p-4">
          <p class="ve-sec">Video Information</p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="fmtTime(duration)"></div>
              <div class="ve-stat-lbl">Duration</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="fileSize"></div>
              <div class="ve-stat-lbl">File Size</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="videoW && videoH ? videoW + '×' + videoH : '—'"></div>
              <div class="ve-stat-lbl">Resolution</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="fmtTime(trimDuration)"></div>
              <div class="ve-stat-lbl">Trim Length</div>
            </div>
          </div>
        </div>

      </div><!-- /left column -->

      {{-- ── Right: Controls ── --}}
      <div class="flex flex-col gap-4">

        {{-- Trim quick presets --}}
        <div class="card p-4">
          <p class="ve-sec">Quick Trim Presets</p>
          <div class="grid grid-cols-2 gap-2">
            <button class="ve-btn text-xs justify-center" @click="setTrimPreset(0,30)">First 30s</button>
            <button class="ve-btn text-xs justify-center" @click="setTrimPreset(0,60)">First 1 min</button>
            <button class="ve-btn text-xs justify-center" @click="trimFromEnd(30)">Last 30s</button>
            <button class="ve-btn text-xs justify-center" @click="trimFromEnd(60)">Last 1 min</button>
            <button class="ve-btn text-xs justify-center col-span-2" @click="resetTrim()">Full Video (no trim)</button>
          </div>
        </div>

        {{-- Audio --}}
        <div class="card p-4">
          <p class="ve-sec">Audio</p>
          <label class="flex items-center justify-between gap-3 cursor-pointer py-1">
            <div>
              <p class="text-sm font-semibold text-gray-800">Mute Audio</p>
              <p class="text-xs text-gray-500 mt-0.5">Remove all audio from output</p>
            </div>
            <label class="ve-toggle">
              <input type="checkbox" x-model="muteAudio">
              <div class="ve-toggle-track"></div>
              <div class="ve-toggle-thumb"></div>
            </label>
          </label>
        </div>

        {{-- Output settings --}}
        <div class="card p-4">
          <p class="ve-sec">Output Settings</p>

          {{-- Resolution --}}
          <div class="mb-4">
            <label class="form-label">Resolution</label>
            <select x-model="resolution" class="ve-select w-full">
              <option value="original">Original (no change)</option>
              <option value="1080">1080p (Full HD)</option>
              <option value="720">720p (HD)</option>
              <option value="480">480p (SD)</option>
              <option value="360">360p (Low)</option>
            </select>
          </div>

          {{-- Quality --}}
          <div x-show="resolution !== 'original' || !isCopyMode">
            <div class="flex items-center justify-between mb-2">
              <label class="form-label mb-0">Quality (CRF)</label>
              <span class="text-xs font-bold text-brand-600" x-text="crf === 18 ? 'High' : crf <= 23 ? 'Standard' : 'Low'"></span>
            </div>
            <input
              type="range" min="18" max="35" step="1"
              x-model.number="crf"
              class="ve-range"
              :style="'--p:' + Math.round(((crf-18)/17)*100) + '%'"
              @input="e => e.target.style.setProperty('--p', Math.round(((e.target.value-18)/17)*100)+'%')"
            >
            <div class="flex justify-between text-xs text-gray-400 mt-1">
              <span>Bigger file (better)</span>
              <span>Smaller file</span>
            </div>
          </div>

          {{-- Stream copy note --}}
          <div x-show="isCopyMode" class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 flex gap-2">
            <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Stream copy mode — no re-encode. Very fast &amp; lossless.
          </div>
        </div>

        {{-- Process button --}}
        <div class="card p-4">
          <button
            class="ve-btn ve-btn-primary w-full justify-center text-base py-3"
            :disabled="isProcessing"
            @click="processVideo()"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
            </svg>
            Process Video
          </button>
          <p class="text-xs text-gray-400 text-center mt-2">
            <span x-text="isCopyMode ? 'Fast copy (lossless trim)' : 'Re-encode with libx264'"></span>
          </p>

          {{-- Summary --}}
          <div class="mt-3 space-y-1">
            <div class="ve-info-row">
              <span class="ve-info-key">Trim</span>
              <span class="ve-info-val text-xs" x-text="trimDuration >= duration ? 'No trim' : fmtTime(startTime) + ' → ' + fmtTime(endTime)"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Audio</span>
              <span class="ve-info-val text-xs" x-text="muteAudio ? 'Muted' : 'Keep original'"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Resolution</span>
              <span class="ve-info-val text-xs" x-text="resolution === 'original' ? 'No change' : resolution + 'p'"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Mode</span>
              <span class="ve-info-val text-xs" x-text="isCopyMode ? 'Stream copy' : 'libx264 CRF ' + crf"></span>
            </div>
          </div>
        </div>

      </div><!-- /right column -->

    </div><!-- /grid -->
  </div><!-- /edit phase -->

  {{-- ══ PROCESSING ══ --}}
  <div x-show="phase==='processing'" x-cloak x-transition.opacity>
    <div class="max-w-lg mx-auto">
      <div class="ve-proc-card">

        {{-- Spinner --}}
        <div class="relative mb-6 mx-auto w-20 h-20">
          <div class="ve-proc-spin"></div>
          <div class="absolute inset-0 flex items-center justify-center">
            <svg width="22" height="22" fill="none" stroke="#4f46e5" stroke-width="1.8" viewBox="0 0 24 24">
              <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
            </svg>
          </div>
        </div>

        <h2 class="text-lg font-semibold text-gray-900 mb-1" x-text="processMsg || 'Processing…'"></h2>
        <p class="text-sm text-gray-500 mb-6">This may take a moment. Please don't close the tab.</p>

        {{-- Progress bar --}}
        <div class="ve-progress-track mb-2 mx-auto max-w-sm">
          <div class="ve-progress-fill" :style="{ width: processPct + '%' }"></div>
        </div>
        <p class="text-xs text-gray-400 mb-6" x-text="processPct + '%'"></p>

        {{-- Stage indicators --}}
        <div class="flex items-center justify-center gap-6 text-xs">
          <div class="ve-stage">
            <div class="ve-stage-dot" :class="{ done: processPct>=5, active: processPct<5 }"></div>
            Load engine
          </div>
          <div class="ve-stage">
            <div class="ve-stage-dot" :class="{ done: processPct>=15, active: processPct>=5&&processPct<15 }"></div>
            Read file
          </div>
          <div class="ve-stage">
            <div class="ve-stage-dot" :class="{ done: processPct>=95, active: processPct>=15&&processPct<95 }"></div>
            Encode
          </div>
          <div class="ve-stage">
            <div class="ve-stage-dot" :class="{ done: processPct>=100, active: processPct>=95&&processPct<100 }"></div>
            Export
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══ DONE ══ --}}
  <div x-show="phase==='done'" x-cloak x-transition.opacity>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

      {{-- Result video --}}
      <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="ve-editor">

          {{-- Tab toolbar --}}
          <div class="ve-toolbar">
            <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-lg">
              <button class="ve-compare-tab" :class="{active:resultView==='result'}" @click="resultView='result'">Result</button>
              <button class="ve-compare-tab" :class="{active:resultView==='original'}" @click="resultView='original'">Original</button>
            </div>
            <div class="ve-toolbar-r">
              <span class="text-xs text-gray-400" x-show="resultView==='result'">Processed video</span>
              <span class="text-xs text-gray-400" x-show="resultView==='original'">Original video</span>
              <div class="ve-divider"></div>
              <button class="ve-btn" @click="backToEdit()">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Edit Again
              </button>
              <button class="ve-btn ve-btn-red" @click="resetAll()">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                New Video
              </button>
            </div>
          </div>

          {{-- Videos --}}
          <div class="ve-video-wrap">
            <video
              x-ref="resultVideo"
              class="w-full"
              style="max-height:420px"
              controls
              playsinline
              x-show="resultView==='result'"
            ></video>
            <video
              class="w-full"
              style="max-height:420px"
              controls
              playsinline
              :src="videoUrl"
              x-show="resultView==='original'"
            ></video>
          </div>
        </div>

        {{-- Stats --}}
        <div class="card p-4">
          <p class="ve-sec">Output Summary</p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="ve-stat-box">
              <div class="ve-stat-val text-emerald-600">&#10003; Done</div>
              <div class="ve-stat-lbl">Status</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="resultSize"></div>
              <div class="ve-stat-lbl">File Size</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="resultDuration || fmtTime(trimDuration)"></div>
              <div class="ve-stat-lbl">Duration</div>
            </div>
            <div class="ve-stat-box">
              <div class="ve-stat-val" x-text="resolution === 'original' ? 'Original' : resolution + 'p'"></div>
              <div class="ve-stat-lbl">Resolution</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Download panel --}}
      <div class="flex flex-col gap-4">
        <div class="card p-5">
          <div class="text-center mb-5">
            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
              <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Video Ready!</h3>
            <p class="text-sm text-gray-500 mt-1">Your edited video has been processed successfully.</p>
          </div>

          <button
            class="ve-btn ve-btn-green w-full justify-center text-base py-3 mb-3"
            @click="downloadResult()"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Download MP4
          </button>

          <div class="space-y-2 pt-3 border-t border-gray-100">
            <div class="ve-info-row">
              <span class="ve-info-key">Original</span>
              <span class="ve-info-val text-xs" x-text="fileSize"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Processed</span>
              <span class="ve-info-val text-xs" x-text="resultSize"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Trim</span>
              <span class="ve-info-val text-xs" x-text="trimDuration >= duration ? 'None' : fmtTime(startTime) + '→' + fmtTime(endTime)"></span>
            </div>
            <div class="ve-info-row">
              <span class="ve-info-key">Audio</span>
              <span class="ve-info-val text-xs" x-text="muteAudio ? 'Muted' : 'Original'"></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div><!-- /done -->

</div>
</div>

@endsection

@push('scripts')
{{-- FFmpeg.wasm v0.11.6 API wrapper --}}
<script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.11.6/dist/ffmpeg.min.js"></script>

<script>
/* ══════════════════════════════════════════════════════════════════
   MP4 Video Editor — Alpine.js + FFmpeg.wasm v0.11.6
   CSS prefix: ve-  |  Alpine component: veEditor()
   ══════════════════════════════════════════════════════════════════ */
function veEditor() {
  return {
    /* ── phase ── */
    phase: 'upload',            // upload | edit | processing | done
    isDragging: false,
    errorMsg: '',

    /* ── file ── */
    file: null,
    fileName: '',
    fileSize: '',
    videoUrl: null,
    duration: 0,
    videoW: 0, videoH: 0,
    currentTime: 0,
    isPlaying: false,

    /* ── trim ── */
    startTime: 0,
    endTime: 0,
    _dragging: null,
    _dragHandlers: null,

    /* ── options ── */
    muteAudio: false,
    resolution: 'original',
    crf: 23,

    /* ── ffmpeg ── */
    _ff: null,
    _fetchFile: null,
    _ffLoaded: false,
    processPct: 0,
    processMsg: '',
    isProcessing: false,

    /* ── result ── */
    resultUrl: null,
    resultSize: '',
    resultDuration: '',
    resultView: 'result',

    /* ── computed ── */
    get startPct()    { return this.duration ? (this.startTime / this.duration) * 100 : 0; },
    get endPct()      { return this.duration ? (this.endTime   / this.duration) * 100 : 100; },
    get playPct()     { return this.duration ? (this.currentTime / this.duration) * 100 : 0; },
    get trimDuration(){ return Math.max(0, this.endTime - this.startTime); },
    get isCopyMode()  {
      return this.resolution === 'original' && !this.muteAudio;
    },

    /* ── init ── */
    init: function() {
      var vm = this;
      vm._dragHandlers = {
        move: function(e){ vm._onDragMove(e); },
        up:   function(e){ vm._onDragUp(e);   },
      };
    },

    /* ── file handling ── */
    onDrop: function(e) {
      this.isDragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this.loadFile(f);
    },
    onFileChange: function(e) {
      if (e.target.files.length) this.loadFile(e.target.files[0]);
    },
    loadFile: function(f) {
      this.errorMsg = '';
      if (!f.type.startsWith('video/')) {
        this.errorMsg = 'Please select a video file (MP4, MOV, AVI, WEBM…).';
        return;
      }
      if (f.size > 500 * 1024 * 1024) {
        this.errorMsg = 'File too large (max 500 MB). For larger files, please use desktop software.';
        return;
      }
      this.file     = f;
      this.fileName = f.name;
      this.fileSize = this._fmtSize(f.size);
      if (this.videoUrl) URL.revokeObjectURL(this.videoUrl);
      this.videoUrl = URL.createObjectURL(f);

      var vm = this;
      this.$nextTick(function() {
        var vid = vm.$refs.videoEl;
        if (!vid) return;
        vid.src = vm.videoUrl;
        vid.addEventListener('loadedmetadata', function() {
          vm.duration  = vid.duration;
          vm.startTime = 0;
          vm.endTime   = vid.duration;
          vm.videoW    = vid.videoWidth;
          vm.videoH    = vid.videoHeight;
          vm.phase     = 'edit';
        }, { once: true });
        vid.load();
      });
    },

    /* ── video metadata ── */
    onVideoLoaded: function(e) {
      var vid = e.target;
      this.duration  = vid.duration  || this.duration;
      this.videoW    = vid.videoWidth  || this.videoW;
      this.videoH    = vid.videoHeight || this.videoH;
      if (!this.endTime || this.endTime === 0) {
        this.startTime = 0;
        this.endTime   = this.duration;
      }
    },

    /* ── video player controls ── */
    togglePlay: function() {
      var vid = this.$refs.videoEl;
      if (!vid) return;
      if (vid.paused) { vid.play(); }
      else            { vid.pause(); }
    },
    setStart: function() {
      var vid = this.$refs.videoEl;
      if (!vid) return;
      this.startTime = Math.max(0, Math.min(vid.currentTime, this.endTime - 0.1));
    },
    setEnd: function() {
      var vid = this.$refs.videoEl;
      if (!vid) return;
      this.endTime = Math.max(this.startTime + 0.1, Math.min(vid.currentTime, this.duration));
    },

    /* ── time input parse ── */
    _parseTime: function(str) {
      var parts = (str || '').trim().split(':').map(Number).filter(function(n){ return !isNaN(n); });
      if (parts.length === 1) return parts[0];
      if (parts.length === 2) return parts[0]*60 + parts[1];
      return parts[0]*3600 + parts[1]*60 + parts[2];
    },
    onStartInput: function(val) {
      var t = this._parseTime(val);
      if (!isNaN(t)) this.startTime = Math.max(0, Math.min(t, this.endTime - 0.1));
    },
    onEndInput: function(val) {
      var t = this._parseTime(val);
      if (!isNaN(t)) this.endTime = Math.max(this.startTime + 0.1, Math.min(t, this.duration));
    },

    /* ── timeline drag ── */
    startDrag: function(handle, e) {
      e.preventDefault();
      this._dragging = handle;
      window.addEventListener('pointermove', this._dragHandlers.move);
      window.addEventListener('pointerup',   this._dragHandlers.up);
    },
    _onDragMove: function(e) {
      var tl = this.$refs.timeline;
      if (!tl || !this._dragging) return;
      var rect = tl.getBoundingClientRect();
      var pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      var t    = pct * this.duration;

      if (this._dragging === 'start') {
        this.startTime = Math.max(0, Math.min(t, this.endTime - 0.5));
        var vid = this.$refs.videoEl;
        if (vid) vid.currentTime = this.startTime;
      } else {
        this.endTime = Math.min(this.duration, Math.max(t, this.startTime + 0.5));
        var vid2 = this.$refs.videoEl;
        if (vid2) vid2.currentTime = this.endTime;
      }
    },
    _onDragUp: function() {
      this._dragging = null;
      window.removeEventListener('pointermove', this._dragHandlers.move);
      window.removeEventListener('pointerup',   this._dragHandlers.up);
    },
    onTrackClick: function(e) {
      if (this._dragging) return;
      var tl = e.currentTarget;
      var rect = tl.getBoundingClientRect();
      var pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      var vid  = this.$refs.videoEl;
      if (vid) vid.currentTime = pct * this.duration;
    },

    /* ── quick presets ── */
    setTrimPreset: function(start, end) {
      this.startTime = Math.max(0, start);
      this.endTime   = Math.min(this.duration, end);
    },
    trimFromEnd: function(secs) {
      this.startTime = Math.max(0, this.duration - secs);
      this.endTime   = this.duration;
    },
    resetTrim: function() {
      this.startTime = 0;
      this.endTime   = this.duration;
    },

    /* ── FFmpeg processing ── */
    processVideo: async function() {
      this.phase       = 'processing';
      this.processPct  = 0;
      this.processMsg  = 'Loading FFmpeg engine…';
      this.isProcessing = true;
      this.errorMsg    = '';

      try {
        /* 1. Load FFmpeg.wasm if not yet loaded */
        await this._loadFFmpeg();
        this.processPct = 5;
        this.processMsg = 'Reading video file…';

        /* 2. Write input file to virtual FS */
        var ext    = this._ext(this.fileName);
        var inName = 'input.' + ext;
        var outName = 'output.mp4';

        this._ff.FS('writeFile', inName, await this._fetchFile(this.file));
        this.processPct = 15;
        this.processMsg = 'Processing…';

        /* 3. Build args */
        var args = this._buildArgs(inName, outName);

        /* 4. Run FFmpeg */
        var vm = this;
        this._ff.setProgress(function(p) {
          if (p.ratio > 0) {
            vm.processPct = 15 + Math.round(p.ratio * 80);
            if (p.ratio < .3)       vm.processMsg = 'Encoding video…';
            else if (p.ratio < .7)  vm.processMsg = 'Processing audio…';
            else if (p.ratio < .95) vm.processMsg = 'Finalizing…';
            else                    vm.processMsg = 'Almost done…';
          }
        });

        await this._ff.run.apply(this._ff, args);

        this.processPct = 96;
        this.processMsg = 'Preparing output…';

        /* 5. Read result */
        var data = this._ff.FS('readFile', outName);
        var blob = new Blob([data.buffer], { type: 'video/mp4' });
        if (this.resultUrl) URL.revokeObjectURL(this.resultUrl);
        this.resultUrl  = URL.createObjectURL(blob);
        this.resultSize = this._fmtSize(blob.size);

        /* 6. Cleanup FS */
        try { this._ff.FS('unlink', inName); } catch(e){}
        try { this._ff.FS('unlink', outName); } catch(e){}

        this.processPct = 100;
        await new Promise(function(r){ setTimeout(r, 350); });
        this.phase = 'done';
        this.resultView = 'result';

        /* 7. Load result into video element */
        var rv = this.$refs.resultVideo;
        if (rv) {
          rv.addEventListener('loadedmetadata', function() {
            vm.resultDuration = vm.fmtTime(rv.duration);
          }, { once: true });
          rv.src = this.resultUrl;
          rv.load();
        }

      } catch(err) {
        console.error('[veEditor] FFmpeg error:', err);
        var msg = (err && err.message) || String(err) || 'Unknown error';
        if (msg.toLowerCase().includes('sharedarraybuffer')) {
          this.errorMsg = 'Browser security error: SharedArrayBuffer unavailable. Please try a different browser (Chrome/Edge recommended) or reload the page.';
        } else if (msg.includes('memory') || msg.includes('OOM') || msg.includes('out of memory')) {
          this.errorMsg = 'Out of memory. Try a shorter trim or smaller file — browsers have limited WASM memory.';
        } else if (msg.includes('404') || msg.includes('fetch') || msg.includes('network') || msg.includes('core')) {
          this._ffLoaded = false; // allow retry
          this.errorMsg = 'Failed to download FFmpeg engine. Check your internet connection and try again.';
        } else {
          this.errorMsg = 'Processing failed: ' + msg;
        }
        this.phase = 'edit';
      }
      this.isProcessing = false;
    },

    /* ── Build FFmpeg argument array ── */
    _buildArgs: function(inName, outName) {
      var args = [];
      var hasTrim = (this.startTime > 0.01 || this.endTime < this.duration - 0.01);
      var needEncode = (this.resolution !== 'original' || this.muteAudio);

      if (this.isCopyMode && hasTrim) {
        /* Seek before input for fast seek (keyframe accurate) */
        args.push('-ss', this._ffTime(this.startTime));
        args.push('-i', inName);
        args.push('-t',  this._ffTime(this.endTime - this.startTime));
        args.push('-c', 'copy', '-avoid_negative_ts', '1');
      } else {
        args.push('-i', inName);
        if (hasTrim) {
          args.push('-ss', this._ffTime(this.startTime));
          args.push('-to', this._ffTime(this.endTime));
        }
        /* Video codec */
        args.push('-c:v', 'libx264', '-preset', 'ultrafast', '-crf', String(this.crf));
        /* Resolution */
        if (this.resolution !== 'original') {
          args.push('-vf', 'scale=-2:' + this.resolution);
        }
        /* Audio */
        if (this.muteAudio) {
          args.push('-an');
        } else {
          args.push('-c:a', 'aac', '-b:a', '128k');
        }
      }

      args.push('-movflags', '+faststart');
      args.push(outName);
      return args;
    },

    /* ── Load FFmpeg.wasm ── */
    _loadFFmpeg: async function() {
      if (this._ffLoaded) return;

      if (typeof FFmpeg === 'undefined' || !FFmpeg.createFFmpeg) {
        throw new Error('FFmpeg library failed to load from CDN. Check your internet connection and refresh the page.');
      }

      var { createFFmpeg, fetchFile } = FFmpeg;
      this._fetchFile = fetchFile;
      this._ff = createFFmpeg({
        log: false,
        /* core-st = single-threaded build — no SharedArrayBuffer required */
        corePath: 'https://cdn.jsdelivr.net/npm/@ffmpeg/core-st@0.11.0/dist/ffmpeg-core.js',
      });
      this.processMsg = 'Downloading FFmpeg engine (~25 MB, cached after first use)…';
      await this._ff.load();
      this._ffLoaded = true;
    },

    /* ── Download result ── */
    downloadResult: function() {
      if (!this.resultUrl) return;
      var a = document.createElement('a');
      a.href     = this.resultUrl;
      a.download = this.fileName.replace(/\.[^.]+$/, '') + '_edited.mp4';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    },

    /* ── Navigation ── */
    backToEdit: function() {
      this.phase = 'edit';
    },

    /* ── Full reset ── */
    resetAll: function() {
      if (this.videoUrl)  URL.revokeObjectURL(this.videoUrl);
      if (this.resultUrl) URL.revokeObjectURL(this.resultUrl);
      this.phase       = 'upload';
      this.file        = null;
      this.fileName    = '';
      this.fileSize    = '';
      this.videoUrl    = null;
      this.resultUrl   = null;
      this.duration    = 0;
      this.startTime   = 0;
      this.endTime     = 0;
      this.currentTime = 0;
      this.isPlaying   = false;
      this.muteAudio   = false;
      this.resolution  = 'original';
      this.crf         = 23;
      this.errorMsg    = '';
      this.processPct  = 0;
      this.isProcessing = false;
      if (this.$refs.fileIn) this.$refs.fileIn.value = '';
    },

    /* ── Helpers ── */
    fmtTime: function(s) {
      if (!s || isNaN(s) || s < 0) return '0:00';
      var h  = Math.floor(s / 3600);
      var m  = Math.floor((s % 3600) / 60);
      var sc = Math.floor(s % 60);
      if (h > 0) return h + ':' + String(m).padStart(2,'0') + ':' + String(sc).padStart(2,'0');
      return m + ':' + String(sc).padStart(2,'0');
    },
    _ffTime: function(s) {
      var h  = Math.floor(s / 3600);
      var m  = Math.floor((s % 3600) / 60);
      var sc = (s % 60).toFixed(3);
      return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(Number(sc).toFixed(3)).padStart(6,'0');
    },
    _fmtSize: function(bytes) {
      if (bytes < 1024)     return bytes + ' B';
      if (bytes < 1048576)  return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },
    _ext: function(name) {
      var parts = (name || 'video.mp4').split('.');
      return (parts.length > 1 ? parts.pop() : 'mp4').toLowerCase();
    },
  };
}
</script>
@endpush