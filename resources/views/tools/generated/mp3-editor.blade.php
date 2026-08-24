@extends('layouts.public')

@section('title', 'MP3 Editor — Trim, Cut, Volume, Fade In/Out Online | JEDISEBITOOL')
@section('description', 'Free browser-based MP3 editor. Trim audio, adjust volume, add fade in/fade out, preview in real-time, and download your edited MP3. No uploads — runs entirely in your browser.')

@section('head')
<style>
/* ══════════════════════════════════════════════════
   MP3 Editor   prefix: mp3-
   ══════════════════════════════════════════════════ */

/* ── Drop zone ── */
.mp3-drop{border:2px dashed #a5b4fc;border-radius:18px;padding:2.5rem 2rem;text-align:center;cursor:pointer;background:#f8f7ff;transition:border-color .2s,background .2s;user-select:none}
.mp3-drop:hover,.mp3-drop.over{border-color:#4f46e5;background:#eef2ff}
.mp3-drop-icon{width:72px;height:72px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem}

/* ── Waveform ── */
.mp3-wave-wrap{position:relative;border-radius:14px;overflow:hidden;background:#1e1e2e;cursor:crosshair;user-select:none;touch-action:none}
.mp3-wave-canvas{display:block;width:100%;height:130px}
.mp3-wave-overlay{position:absolute;inset:0;pointer-events:none}

/* Trim region overlay */
.mp3-trim-region{position:absolute;top:0;bottom:0;background:rgba(79,70,229,.12);border-left:2px solid #4f46e5;border-right:2px solid #4f46e5;pointer-events:none}

/* Handles */
.mp3-handle{position:absolute;top:0;bottom:0;width:14px;transform:translateX(-50%);cursor:ew-resize;z-index:10;pointer-events:all;display:flex;align-items:center;justify-content:center;touch-action:none}
.mp3-handle-bar{width:4px;height:60%;border-radius:3px;background:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.4),0 2px 8px rgba(0,0,0,.3);position:relative}
.mp3-handle-bar::after{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:18px;height:18px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.3)}

/* Playhead */
.mp3-playhead{position:absolute;top:0;bottom:0;width:2px;background:#ef4444;transform:translateX(-50%);pointer-events:none;z-index:8;box-shadow:0 0 4px rgba(239,68,68,.6)}
.mp3-playhead::after{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:8px;height:8px;background:#ef4444;border-radius:50%}

/* ── Timeline ruler ── */
.mp3-ruler{display:flex;justify-content:space-between;padding:.25rem .125rem;font-size:.6rem;font-family:monospace;color:#6b7280}

/* ── Transport controls ── */
.mp3-transport{display:flex;align-items:center;gap:.625rem;flex-wrap:wrap}
.mp3-ctrl-btn{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:1.5px solid #e5e7eb;background:#fff;color:#374151;cursor:pointer;transition:all .13s;flex-shrink:0}
.mp3-ctrl-btn:hover{background:#f5f3ff;border-color:#c4b5fd;color:#4f46e5}
.mp3-ctrl-btn.active{background:#4f46e5;border-color:#4f46e5;color:#fff}
.mp3-ctrl-btn.danger:hover{background:#fef2f2;border-color:#fca5a5;color:#dc2626}
.mp3-play-btn{width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;box-shadow:0 3px 10px rgba(79,70,229,.35);flex-shrink:0}
.mp3-play-btn:hover{box-shadow:0 5px 16px rgba(79,70,229,.45);transform:scale(1.05)}
.mp3-play-btn:active{transform:scale(.97)}
.mp3-play-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}
.mp3-time-display{font-family:monospace;font-size:.8rem;color:#6b7280;display:flex;align-items:center;gap:.25rem;min-width:110px}
.mp3-time-cur{color:#1f2937;font-weight:600}

/* ── Trim time inputs ── */
.mp3-time-input{width:100%;font-family:monospace;font-size:.85rem;font-weight:600;text-align:center;border:1.5px solid #e5e7eb;border-radius:9px;padding:.45rem .5rem;color:#1f2937;background:#fff;transition:border-color .15s;outline:none}
.mp3-time-input:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1)}
.mp3-time-input.err{border-color:#ef4444}

/* ── Range sliders ── */
.mp3-range{-webkit-appearance:none;appearance:none;width:100%;height:5px;border-radius:999px;outline:none;cursor:pointer;transition:opacity .15s}
.mp3-range::-webkit-slider-thumb{-webkit-appearance:none;width:17px;height:17px;border-radius:50%;background:#4f46e5;border:2.5px solid #fff;box-shadow:0 1px 5px rgba(79,70,229,.4);cursor:pointer}
.mp3-range::-moz-range-thumb{width:17px;height:17px;border-radius:50%;background:#4f46e5;border:2.5px solid #fff;cursor:pointer}
.mp3-vol-range{background:linear-gradient(to right,#4f46e5 var(--pct,100%),#e5e7eb var(--pct,100%))}
.mp3-fade-range{background:linear-gradient(to right,#7c3aed var(--pct,0%),#e5e7eb var(--pct,0%))}

/* ── Section label ── */
.mp3-sec{font-size:.67rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#4f46e5;margin-bottom:.5rem;display:flex;align-items:center;gap:.35rem}

/* ── Info box ── */
.mp3-info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.5rem .75rem;font-size:.76rem;color:#1d4ed8;display:flex;gap:.4rem;align-items:flex-start}

/* ── Spinner ── */
.mp3-spin{width:18px;height:18px;border:2.5px solid rgba(79,70,229,.25);border-top-color:#4f46e5;border-radius:50%;animation:mp3-rot .7s linear infinite;flex-shrink:0}
.mp3-spin-lg{width:40px;height:40px;border:3.5px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:mp3-rot .8s linear infinite}
@keyframes mp3-rot{to{transform:rotate(360deg)}}

/* ── Progress bar ── */
.mp3-prog-track{height:5px;background:#e5e7eb;border-radius:999px;overflow:hidden}
.mp3-prog-fill{height:100%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:999px;transition:width .1s}

/* ── Result card ── */
.mp3-result{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:1px solid #a7f3d0;border-radius:14px;padding:1.25rem}

/* ── Volume meter visual ── */
.mp3-vol-meter{display:flex;gap:1px;align-items:flex-end;height:20px}
.mp3-vol-bar{width:3px;border-radius:1px;transition:height .1s}

/* ── Mobile ── */
@media(max-width:639px){
  .mp3-wave-canvas{height:90px}
  .mp3-transport{gap:.5rem}
  .mp3-play-btn{width:42px;height:42px}
  .mp3-ctrl-btn{width:36px;height:36px}
}
</style>
@endsection

@section('renders_own_faqs', '1')
@section('content')
<div class="min-h-screen bg-gray-50">

{{-- ══ Hero ══ --}}
<div class="bg-white border-b border-gray-100">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M9 18V5l12-2v13"/>
          <circle cx="6" cy="18" r="3"/>
          <circle cx="18" cy="16" r="3"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-0.5">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">MP3 Editor</h1>
          <span class="badge badge-primary">Trim · Volume · Fade</span>
          <span class="badge" style="background:#dcfce7;color:#15803d">Browser-Only</span>
        </div>
        <p class="text-gray-500 text-sm">Trim audio clips, adjust volume, and add fade in/out effects — real-time preview, export as MP3. No uploads, no server.</p>
      </div>
    </div>
    <x-breadcrumb :items="[
      ['label' => 'Home',                'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => 'MP3 Editor']
    ]"/>
  </div>
</div>

{{-- ══ Tool ══ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6"
     x-data="mp3Editor()"
     x-init="init()"
     x-cloak>

  {{-- Error --}}
  <div x-show="errorMsg" x-transition
       class="alert alert-error mb-4 flex items-start gap-2">
    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <div class="flex-1">
      <span x-text="errorMsg"></span>
      <button class="ml-2 underline text-xs" @click="errorMsg=''">Dismiss</button>
    </div>
  </div>

  {{-- ══ UPLOAD state ══ --}}
  <div x-show="!audioBuffer" x-transition>
    <div class="card p-6 sm:p-8 max-w-xl mx-auto">
      <div class="mp3-drop"
           :class="dragging ? 'over' : ''"
           @dragover.prevent="dragging=true"
           @dragleave.prevent="dragging=false"
           @drop.prevent="onDrop($event)"
           @click="$refs.fileInput.click()">
        <div class="mp3-drop-icon">
          <svg width="32" height="32" fill="none" stroke="#6366f1" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M9 18V5l12-2v13"/>
            <circle cx="6" cy="18" r="3"/>
            <circle cx="18" cy="16" r="3"/>
          </svg>
        </div>
        <p class="text-sm font-semibold text-gray-700">
          <span x-show="!dragging">Drop MP3 file here</span>
          <span x-show="dragging" class="text-brand-600">Release to upload</span>
        </p>
        <p class="text-xs text-gray-400 mt-1">or <span class="text-brand-600 font-medium">click to browse</span></p>
        <div class="flex gap-1.5 justify-center mt-3">
          <span class="badge badge-gray">MP3</span>
          <span class="badge badge-gray">WAV</span>
          <span class="badge badge-gray">OGG</span>
          <span class="badge badge-gray">AAC</span>
        </div>
        <p class="text-xs text-gray-400 mt-2">Max 100 MB</p>
      </div>
      <input type="file" x-ref="fileInput" class="hidden"
             accept="audio/*,.mp3,.wav,.ogg,.aac,.flac,.m4a"
             @change="onFileInput($event)">

      {{-- Loading state inside upload card --}}
      <div class="mt-5" x-show="loading">
        <div class="flex flex-col items-center gap-3">
          <div class="mp3-spin-lg"></div>
          <p class="text-sm text-gray-600" x-text="loadMsg"></p>
          <div class="w-full max-w-xs">
            <div class="mp3-prog-track">
              <div class="mp3-prog-fill" :style="'width:'+loadProgress+'%'"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-4 mp3-info max-w-xl mx-auto">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span>Your audio file is processed <strong>entirely in your browser</strong> — it is never uploaded to any server.</span>
    </div>
  </div>

  {{-- ══ EDITOR state (file loaded) ══ --}}
  <div x-show="audioBuffer" x-transition class="space-y-4">

    {{-- File info bar --}}
    <div class="card px-4 py-3 flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe)">
        <svg width="16" height="16" fill="none" stroke="#6366f1" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
        <p class="text-xs text-gray-400" x-text="fileSize + ' · ' + _fmtTime(fileDuration) + ' · ' + sampleRate + ' Hz'"></p>
      </div>
      <button class="btn btn-secondary btn-sm flex-shrink-0" @click="reset()">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
        Change
      </button>
    </div>

    {{-- Main editor card --}}
    <div class="card p-5">

      {{-- ── Waveform ── --}}
      <div class="mp3-sec">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Waveform
      </div>

      <div class="mp3-wave-wrap mb-1" x-ref="waveWrap"
           @pointerdown="onWaveClick($event)">
        <canvas x-ref="waveCanvas" class="mp3-wave-canvas"></canvas>

        {{-- Trim region overlay --}}
        <div class="mp3-trim-region"
             :style="'left:'+_pct(startTime)+'%;width:'+(endTime-startTime)/fileDuration*100+'%;'">
        </div>

        {{-- Start handle --}}
        <div class="mp3-handle"
             :style="'left:'+_pct(startTime)+'%'"
             @pointerdown.stop.prevent="startDrag('start',$event)">
          <div class="mp3-handle-bar"></div>
        </div>

        {{-- End handle --}}
        <div class="mp3-handle"
             :style="'left:'+_pct(endTime)+'%'"
             @pointerdown.stop.prevent="startDrag('end',$event)">
          <div class="mp3-handle-bar" style="background:#7c3aed"></div>
        </div>

        {{-- Playhead --}}
        <div class="mp3-playhead"
             x-show="isPlaying || pausedAt > 0"
             :style="'left:'+_pct(currentTime)+'%'">
        </div>
      </div>

      {{-- Ruler --}}
      <div class="mp3-ruler mb-3">
        <template x-for="t in _rulerTicks()" :key="t">
          <span x-text="_fmtTime(t)"></span>
        </template>
      </div>

      {{-- ── Transport ── --}}
      <div class="mp3-transport mb-4">
        {{-- Play/Pause --}}
        <button class="mp3-play-btn" @click="togglePlay()" :disabled="!audioBuffer">
          <svg x-show="!isPlaying" width="18" height="18" fill="white" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          <svg x-show="isPlaying" width="16" height="16" fill="white" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
        </button>

        {{-- Stop --}}
        <button class="mp3-ctrl-btn danger" @click="stopPlayback()" title="Stop">
          <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
        </button>

        {{-- Loop --}}
        <button class="mp3-ctrl-btn" :class="loopEnabled ? 'active' : ''"
                @click="loopEnabled=!loopEnabled" title="Loop">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <polyline points="17 1 21 5 17 9"/>
            <path d="M3 11V9a4 4 0 014-4h14"/>
            <polyline points="7 23 3 19 7 15"/>
            <path d="M21 13v2a4 4 0 01-4 4H3"/>
          </svg>
        </button>

        {{-- Preview trim range only --}}
        <button class="mp3-ctrl-btn text-xs font-semibold" style="width:auto;border-radius:9px;padding:.4rem .75rem"
                :class="previewTrim ? 'active' : ''"
                @click="previewTrim=!previewTrim" title="Play trim range only">
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-0.5"><path d="M6 3v18M18 3v18M3 8h4M17 8h4M3 16h4M17 16h4"/></svg>
          Trim
        </button>

        {{-- Time display --}}
        <div class="mp3-time-display ml-auto">
          <span class="mp3-time-cur" x-text="_fmtTime(currentTime)"></span>
          <span>/</span>
          <span x-text="_fmtTime(fileDuration)"></span>
        </div>
      </div>

      {{-- ── Edit controls ── --}}
      <div class="grid gap-4 sm:grid-cols-2">

        {{-- Trim --}}
        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
          <div class="mp3-sec">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="6" cy="6" r="3"/><circle cx="18" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/></svg>
            Trim / Cut
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="form-label text-xs">Start Time</label>
              <input type="text" class="mp3-time-input"
                     :class="startErr?'err':''"
                     :value="_fmtTimeInput(startTime)"
                     @change="onTimeInput('start', $event.target.value)"
                     placeholder="0:00.000">
            </div>
            <div>
              <label class="form-label text-xs">End Time</label>
              <input type="text" class="mp3-time-input"
                     :class="endErr?'err':''"
                     :value="_fmtTimeInput(endTime)"
                     @change="onTimeInput('end', $event.target.value)"
                     placeholder="0:00.000">
            </div>
          </div>
          <div class="flex items-center justify-between text-xs text-gray-500">
            <span>Selection: <strong class="text-gray-800" x-text="_fmtTime(endTime - startTime)"></strong></span>
            <button class="text-brand-600 font-medium hover:underline"
                    @click="startTime=0; endTime=fileDuration; _drawWave()">
              Select All
            </button>
          </div>
        </div>

        {{-- Volume + Fades --}}
        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
          {{-- Volume --}}
          <div>
            <div class="mp3-sec">
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 010 14.14M15.54 8.46a5 5 0 010 7.07"/></svg>
              Volume
            </div>
            <div class="flex items-center gap-3">
              <input type="range" min="0" max="200" step="1"
                     x-model.number="volume"
                     @input="_updateRangeStyle($refs.volRange, volume/200)"
                     x-ref="volRange"
                     class="mp3-range mp3-vol-range flex-1">
              <span class="text-sm font-bold text-brand-600 w-12 text-right" x-text="volume+'%'"></span>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-0.5">
              <span>0%</span><span>100%</span><span>200%</span>
            </div>
          </div>

          {{-- Fade In --}}
          <div>
            <div class="flex items-center justify-between mb-1">
              <div class="mp3-sec mb-0" style="margin-bottom:0">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12h20M12 2v20"/></svg>
                Fade In
              </div>
              <span class="text-xs font-bold text-purple-600" x-text="fadeIn.toFixed(1)+'s'"></span>
            </div>
            <input type="range" min="0" :max="_maxFade('in')" step="0.1"
                   x-model.number="fadeIn"
                   @input="_updateRangeStyle($refs.fiRange, fadeIn/_maxFade('in'))"
                   x-ref="fiRange"
                   class="mp3-range mp3-fade-range w-full">
          </div>

          {{-- Fade Out --}}
          <div>
            <div class="flex items-center justify-between mb-1">
              <div class="mp3-sec mb-0" style="margin-bottom:0">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="2" y1="2" x2="22" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                Fade Out
              </div>
              <span class="text-xs font-bold text-purple-600" x-text="fadeOut.toFixed(1)+'s'"></span>
            </div>
            <input type="range" min="0" :max="_maxFade('out')" step="0.1"
                   x-model.number="fadeOut"
                   @input="_updateRangeStyle($refs.foRange, fadeOut/_maxFade('out'))"
                   x-ref="foRange"
                   class="mp3-range mp3-fade-range w-full">
          </div>
        </div>

      </div>

      {{-- ── Action buttons ── --}}
      <div class="flex flex-wrap gap-3 mt-5">
        {{-- Preview processed audio --}}
        <button class="btn btn-primary flex-1 sm:flex-none"
                @click="previewProcessed()"
                :disabled="processing || previewing">
          <template x-if="previewing">
            <div class="mp3-spin"></div>
          </template>
          <template x-if="!previewing">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </template>
          <span x-text="previewing ? 'Preparing…' : 'Preview Edited'"></span>
        </button>

        {{-- Process & Download --}}
        <button class="btn flex-1 sm:flex-none"
                style="background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none"
                @click="processAndDownload()"
                :disabled="processing || previewing">
          <template x-if="processing">
            <div class="mp3-spin" style="border-top-color:#fff;border-color:rgba(255,255,255,.3)"></div>
          </template>
          <template x-if="!processing">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          </template>
          <span x-text="processing ? 'Processing…' : 'Process & Download'"></span>
        </button>

        {{-- Reset settings --}}
        <button class="btn btn-secondary flex-shrink-0" @click="resetSettings()" :disabled="processing">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
          Reset
        </button>
      </div>

      {{-- Progress bar --}}
      <div class="mt-3 space-y-1.5" x-show="processing">
        <div class="mp3-prog-track">
          <div class="mp3-prog-fill" :style="'width:'+progress+'%'"></div>
        </div>
        <p class="text-xs text-gray-500 text-center" x-text="progressMsg"></p>
      </div>

    </div>{{-- /editor card --}}

    {{-- ── Result card ── --}}
    <div class="mp3-result" x-show="resultUrl && !processing" x-transition>
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div>
            <p class="font-semibold text-gray-900 text-sm">MP3 ready to download</p>
            <p class="text-xs text-gray-500 mt-0.5">
              Duration: <strong x-text="_fmtTime(endTime-startTime)"></strong> ·
              Size: <strong x-text="resultSize"></strong>
            </p>
          </div>
        </div>
        <div class="flex gap-2">
          <audio :src="resultUrl" controls class="h-9 rounded-lg max-w-[220px]" x-show="resultUrl"></audio>
          <a :href="resultUrl" :download="_dlName()" class="btn btn-success btn-sm">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download
          </a>
        </div>
      </div>
    </div>

  </div>{{-- /editor state --}}

  {{-- Bottom grid --}}
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
      <h3 class="text-sm font-semibold text-gray-700 mb-3">How to Use</h3>
      <ol class="space-y-1.5 text-xs text-gray-600 list-decimal list-inside">
        <li>Upload an MP3, WAV, OGG, or AAC file</li>
        <li>Drag the purple handles to set trim range</li>
        <li>Adjust volume (0–200%) and fades</li>
        <li>Click <strong>Preview Edited</strong> to listen</li>
        <li>Click <strong>Process &amp; Download</strong> to save MP3</li>
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

</div>{{-- /max-w-5xl --}}
</div>{{-- /min-h-screen --}}
@endsection

@push('scripts')
{{-- lamejs — JavaScript MP3 encoder (56 KB) --}}
<script src="https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.min.js"></script>

<script>
/* ═══════════════════════════════════════════════════════════════
   MP3 Editor — Alpine.js component   prefix: mp3-
   Stack: Web Audio API (decode/preview) + OfflineAudioContext
          (effects rendering) + lamejs (MP3 re-encoding)
   ═══════════════════════════════════════════════════════════════ */

function mp3Editor() {
  return {
    /* ── File ── */
    file: null,
    audioBuffer: null,
    fileName: '',
    fileSize: '',
    fileDuration: 0,
    sampleRate: 0,

    /* ── Edit settings ── */
    startTime: 0,
    endTime: 0,
    volume: 100,
    fadeIn: 0,
    fadeOut: 0,

    /* ── Playback ── */
    isPlaying: false,
    loopEnabled: false,
    previewTrim: true,
    currentTime: 0,
    pausedAt: 0,

    /* ── Loading ── */
    loading: false,
    loadMsg: 'Decoding audio…',
    loadProgress: 0,

    /* ── Processing ── */
    processing: false,
    previewing: false,
    progress: 0,
    progressMsg: '',

    /* ── Result ── */
    resultUrl: null,
    resultSize: '',

    /* ── UI ── */
    dragging: false,
    startErr: false,
    endErr: false,
    errorMsg: '',

    /* ── Internals ── */
    _peaks: null,
    _audioCtx: null,
    _srcNode: null,
    _gainNode: null,
    _startedAt: 0,
    _raf: null,
    _dragHandle: null,

    /* ══ Init ══ */
    init: function() {},

    /* ══ File input ══ */
    onFileInput: function(e) {
      if (e.target.files[0]) this._loadFile(e.target.files[0]);
    },

    onDrop: function(e) {
      this.dragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this._loadFile(f);
    },

    _loadFile: async function(file) {
      if (!file.type.startsWith('audio/') && !file.name.match(/\.(mp3|wav|ogg|aac|flac|m4a)$/i)) {
        this.errorMsg = 'Please upload an audio file (MP3, WAV, OGG, AAC, FLAC, M4A).';
        return;
      }
      if (file.size > 100 * 1024 * 1024) {
        this.errorMsg = 'File too large. Maximum size is 100 MB.';
        return;
      }

      this.loading = true;
      this.loadProgress = 5;
      this.loadMsg = 'Reading file…';
      this.errorMsg = '';
      this.resultUrl = null;
      this.file = file;
      this.fileName = file.name;
      this.fileSize = this._fmtBytes(file.size);

      try {
        /* Read as ArrayBuffer */
        var arrayBuf = await new Promise(function(resolve, reject) {
          var reader = new FileReader();
          reader.onload  = function(e){ resolve(e.target.result); };
          reader.onerror = reject;
          reader.readAsArrayBuffer(file);
        });

        this.loadProgress = 35;
        this.loadMsg = 'Decoding audio…';
        await this._tick();

        /* Decode */
        if (!this._audioCtx) {
          this._audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        var buffer = await this._audioCtx.decodeAudioData(arrayBuf);

        this.loadProgress = 75;
        this.loadMsg = 'Analysing waveform…';
        await this._tick();

        this.audioBuffer  = buffer;
        this.fileDuration = buffer.duration;
        this.sampleRate   = buffer.sampleRate;
        this.startTime    = 0;
        this.endTime      = buffer.duration;
        this.pausedAt     = 0;
        this.currentTime  = 0;

        /* Pre-compute peaks */
        this._peaks = this._computePeaks(buffer, 800);

        this.loadProgress = 100;
        await this._tick();

        /* Draw waveform after next tick (DOM must be ready) */
        var vm = this;
        vm.$nextTick(function() {
          vm._drawWave();
          vm._initRangeStyles();
        });

      } catch (err) {
        this.errorMsg = 'Could not decode audio: ' + (err.message || 'unsupported format.');
        this.audioBuffer = null;
      } finally {
        this.loading = false;
      }
    },

    /* ══ Waveform ══ */
    _computePeaks: function(buffer, numPeaks) {
      var ch0 = buffer.getChannelData(0);
      var ch1 = buffer.numberOfChannels > 1 ? buffer.getChannelData(1) : null;
      var blockSize = Math.max(1, Math.floor(ch0.length / numPeaks));
      var peaks = new Float32Array(numPeaks);
      for (var i = 0; i < numPeaks; i++) {
        var max = 0;
        var off = i * blockSize;
        for (var j = 0; j < blockSize; j++) {
          var abs0 = Math.abs(ch0[off + j] || 0);
          var abs1 = ch1 ? Math.abs(ch1[off + j] || 0) : 0;
          var v = Math.max(abs0, abs1);
          if (v > max) max = v;
        }
        peaks[i] = max;
      }
      return peaks;
    },

    _drawWave: function() {
      var canvas = this.$refs.waveCanvas;
      if (!canvas || !this._peaks) return;

      var W = canvas.clientWidth || 600;
      var H = 130;
      canvas.width  = W;
      canvas.height = H;
      var ctx = canvas.getContext('2d');

      /* Background */
      ctx.fillStyle = '#1e1e2e';
      ctx.fillRect(0, 0, W, H);

      var peaks = this._peaks;
      var duration = this.fileDuration;
      var midY = H / 2;
      var startFrac = this.startTime / duration;
      var endFrac   = this.endTime   / duration;

      /* Grid lines (every second) */
      ctx.strokeStyle = 'rgba(255,255,255,.05)';
      ctx.lineWidth = 1;
      for (var t = 1; t < Math.floor(duration); t++) {
        var gx = (t / duration) * W;
        ctx.beginPath(); ctx.moveTo(gx, 0); ctx.lineTo(gx, H); ctx.stroke();
      }

      /* Centre line */
      ctx.strokeStyle = 'rgba(255,255,255,.06)';
      ctx.beginPath(); ctx.moveTo(0, midY); ctx.lineTo(W, midY); ctx.stroke();

      /* Waveform bars */
      var barW = Math.max(1, Math.floor(W / peaks.length));
      for (var i = 0; i < peaks.length; i++) {
        var xFrac = i / peaks.length;
        var x = Math.floor(xFrac * W);
        var amp = Math.max(2, peaks[i] * midY * 0.92);
        var inTrim = xFrac >= startFrac && xFrac <= endFrac;

        if (inTrim) {
          /* Gradient inside trim: purple → indigo */
          var grad = ctx.createLinearGradient(x, midY - amp, x, midY + amp);
          grad.addColorStop(0, 'rgba(124,58,237,.9)');
          grad.addColorStop(0.5, 'rgba(79,70,229,1)');
          grad.addColorStop(1, 'rgba(124,58,237,.9)');
          ctx.fillStyle = grad;
        } else {
          ctx.fillStyle = '#313244';
        }

        ctx.fillRect(x, midY - amp, barW, amp * 2);
      }

      /* Playhead */
      if (this.isPlaying || this.pausedAt > 0) {
        var phX = (this.currentTime / duration) * W;
        ctx.strokeStyle = '#ef4444';
        ctx.lineWidth = 2;
        ctx.shadowBlur = 4;
        ctx.shadowColor = '#ef4444';
        ctx.beginPath(); ctx.moveTo(phX, 0); ctx.lineTo(phX, H); ctx.stroke();
        ctx.shadowBlur = 0;
      }
    },

    _initRangeStyles: function() {
      var vm = this;
      vm.$nextTick(function() {
        vm._updateRangeStyle(vm.$refs.volRange, vm.volume / 200);
        vm._updateRangeStyle(vm.$refs.fiRange, 0);
        vm._updateRangeStyle(vm.$refs.foRange, 0);
      });
    },

    _updateRangeStyle: function(el, pct) {
      if (!el) return;
      el.style.setProperty('--pct', (pct * 100).toFixed(1) + '%');
    },

    /* ══ Waveform click (set playhead) ══ */
    onWaveClick: function(e) {
      if (this._dragHandle) return;
      var wrap = this.$refs.waveWrap;
      if (!wrap) return;
      var rect = wrap.getBoundingClientRect();
      var pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
      var newTime = pct * this.fileDuration;
      this.currentTime = newTime;
      if (this.isPlaying) {
        this._stopSrc();
        this.pausedAt = newTime;
        this._playSrc(newTime);
      } else {
        this.pausedAt = newTime;
      }
      this._drawWave();
    },

    /* ══ Trim handle drag ══ */
    startDrag: function(handle, e) {
      e.preventDefault();
      this._dragHandle = handle;
      var vm = this;
      var wrap = vm.$refs.waveWrap;

      function onMove(ev) {
        var rect = wrap.getBoundingClientRect();
        var pct  = Math.max(0, Math.min(1, (ev.clientX - rect.left) / rect.width));
        var t    = pct * vm.fileDuration;
        if (handle === 'start') {
          vm.startTime = Math.max(0, Math.min(t, vm.endTime - 0.1));
        } else {
          vm.endTime = Math.min(vm.fileDuration, Math.max(t, vm.startTime + 0.1));
        }
        vm._drawWave();
      }

      function onUp() {
        vm._dragHandle = null;
        window.removeEventListener('pointermove', onMove);
        window.removeEventListener('pointerup', onUp);
      }

      window.addEventListener('pointermove', onMove);
      window.addEventListener('pointerup', onUp);
    },

    /* ══ Playback ══ */
    togglePlay: function() {
      if (!this.audioBuffer) return;
      if (this._audioCtx.state === 'suspended') this._audioCtx.resume();
      if (this.isPlaying) {
        this.pausedAt = this.currentTime;
        this._stopSrc();
        this.isPlaying = false;
        cancelAnimationFrame(this._raf);
      } else {
        var from = this.pausedAt || 0;
        if (this.previewTrim && (from < this.startTime || from >= this.endTime)) {
          from = this.startTime;
        }
        this._playSrc(from);
      }
    },

    _playSrc: function(from) {
      var vm = this;
      var buf = vm.audioBuffer;

      vm._srcNode  = vm._audioCtx.createBufferSource();
      vm._gainNode = vm._audioCtx.createGain();
      vm._srcNode.buffer = buf;
      vm._srcNode.loop   = vm.loopEnabled;
      if (vm.loopEnabled && vm.previewTrim) {
        vm._srcNode.loopStart = vm.startTime;
        vm._srcNode.loopEnd   = vm.endTime;
      }
      vm._gainNode.gain.value = vm.volume / 100;
      vm._srcNode.connect(vm._gainNode);
      vm._gainNode.connect(vm._audioCtx.destination);

      var offset  = from;
      var playEnd = vm.previewTrim ? (vm.endTime - from) : undefined;
      vm._srcNode.start(0, offset, playEnd);
      vm._startedAt = vm._audioCtx.currentTime - from;
      vm.isPlaying  = true;

      vm._srcNode.onended = function() {
        if (vm.isPlaying) {
          vm.isPlaying  = false;
          vm.pausedAt   = 0;
          vm.currentTime = vm.previewTrim ? vm.startTime : 0;
          cancelAnimationFrame(vm._raf);
          vm._drawWave();
        }
      };

      vm._rafLoop();
    },

    _stopSrc: function() {
      if (this._srcNode) {
        try { this._srcNode.stop(); } catch(e){}
        this._srcNode.onended = null;
        this._srcNode = null;
      }
    },

    stopPlayback: function() {
      this._stopSrc();
      this.isPlaying  = false;
      this.pausedAt   = 0;
      this.currentTime = this.previewTrim ? this.startTime : 0;
      cancelAnimationFrame(this._raf);
      this._drawWave();
    },

    _rafLoop: function() {
      var vm = this;
      function tick() {
        if (!vm.isPlaying) return;
        vm.currentTime = vm._audioCtx.currentTime - vm._startedAt;
        vm._drawWave();
        vm._raf = requestAnimationFrame(tick);
      }
      vm._raf = requestAnimationFrame(tick);
    },

    /* ══ Time input ══ */
    onTimeInput: function(which, val) {
      var t = this._parseTime(val);
      if (t === null) {
        if (which === 'start') this.startErr = true;
        else this.endErr = true;
        return;
      }
      this.startErr = false; this.endErr = false;
      if (which === 'start') {
        this.startTime = Math.max(0, Math.min(t, this.endTime - 0.1));
      } else {
        this.endTime = Math.min(this.fileDuration, Math.max(t, this.startTime + 0.1));
      }
      this._drawWave();
    },

    /* ══ Preview processed audio ══ */
    previewProcessed: async function() {
      if (!this.audioBuffer || this.previewing) return;
      this.previewing = true;
      this.errorMsg = '';

      try {
        if (this._audioCtx.state === 'suspended') await this._audioCtx.resume();
        this._stopSrc();
        this.isPlaying = false;

        var processed = await this._applyEffects();
        var src  = this._audioCtx.createBufferSource();
        src.buffer = processed;
        src.connect(this._audioCtx.destination);
        src.start(0);

        var vm = this;
        src.onended = function() { vm.isPlaying = false; vm._drawWave(); };
        this.isPlaying   = true;
        this.currentTime = this.startTime;
        this._startedAt  = this._audioCtx.currentTime - this.startTime;
        this._srcNode    = src;
        this._rafLoop();

      } catch (err) {
        this.errorMsg = 'Preview failed: ' + (err.message || err);
      } finally {
        this.previewing = false;
      }
    },

    /* ══ Apply effects → AudioBuffer ══ */
    _applyEffects: async function() {
      var buf  = this.audioBuffer;
      var sr   = buf.sampleRate;
      var ch   = buf.numberOfChannels;
      var s0   = Math.floor(this.startTime * sr);
      var s1   = Math.floor(this.endTime   * sr);
      var len  = s1 - s0;
      var dur  = len / sr;
      var gainVal = this.volume / 100;
      var fi   = Math.min(this.fadeIn,  dur * 0.49);
      var fo   = Math.min(this.fadeOut, dur * 0.49);

      var offCtx = new OfflineAudioContext(ch, len, sr);

      /* Build trimmed buffer */
      var trimBuf = offCtx.createBuffer(ch, len, sr);
      for (var c = 0; c < ch; c++) {
        var srcD = buf.getChannelData(c);
        var dstD = trimBuf.getChannelData(c);
        for (var i = 0; i < len; i++) dstD[i] = srcD[s0 + i];
      }

      var src  = offCtx.createBufferSource();
      src.buffer = trimBuf;

      var gain = offCtx.createGain();
      gain.gain.cancelScheduledValues(0);
      gain.gain.setValueAtTime(0.0001, 0);

      if (fi > 0) {
        gain.gain.setValueAtTime(0.0001, 0);
        gain.gain.linearRampToValueAtTime(gainVal, fi);
      } else {
        gain.gain.setValueAtTime(gainVal, 0);
      }

      if (fo > 0) {
        gain.gain.setValueAtTime(gainVal, Math.max(0, dur - fo));
        gain.gain.linearRampToValueAtTime(0.0001, dur);
      }

      src.connect(gain);
      gain.connect(offCtx.destination);
      src.start(0);

      return offCtx.startRendering();
    },

    /* ══ Process & encode to MP3 ══ */
    processAndDownload: async function() {
      if (!this.audioBuffer || this.processing) return;

      if (typeof lamejs === 'undefined') {
        this.errorMsg = 'MP3 encoder not loaded. Check your internet connection and refresh.';
        return;
      }

      this.processing  = true;
      this.progress    = 0;
      this.progressMsg = 'Applying effects…';
      this.resultUrl   = null;
      this.errorMsg    = '';

      try {
        /* 1 – render processed buffer */
        var rendered = await this._applyEffects();
        this.progress    = 28;
        this.progressMsg = 'Encoding MP3…';
        await this._tick();

        var ch   = rendered.numberOfChannels;
        var sr   = rendered.sampleRate;
        var len  = rendered.length;
        var L    = rendered.getChannelData(0);
        var R    = ch > 1 ? rendered.getChannelData(1) : null;

        /* 2 – lamejs encode */
        var bitrate = 128;
        var encoder = new lamejs.Mp3Encoder(ch, sr, bitrate);
        var mp3Chunks = [];
        var CHUNK = 1152;

        var l16 = new Int16Array(CHUNK);
        var r16 = ch > 1 ? new Int16Array(CHUNK) : null;

        for (var i = 0; i < len; i += CHUNK) {
          var count = Math.min(CHUNK, len - i);
          for (var j = 0; j < CHUNK; j++) {
            var s = j < count ? L[i + j] : 0;
            l16[j] = Math.max(-32768, Math.min(32767, s < 0 ? s * 32768 : s * 32767));
          }
          var mp3buf;
          if (ch > 1) {
            for (var j = 0; j < CHUNK; j++) {
              var s = j < count ? R[i + j] : 0;
              r16[j] = Math.max(-32768, Math.min(32767, s < 0 ? s * 32768 : s * 32767));
            }
            mp3buf = encoder.encodeBuffer(l16, r16);
          } else {
            mp3buf = encoder.encodeBuffer(l16);
          }
          if (mp3buf.length > 0) mp3Chunks.push(mp3buf);

          /* Update progress every 50 chunks and yield */
          if (i % (CHUNK * 50) === 0) {
            this.progress = 28 + Math.round((i / len) * 65);
            await this._tick();
          }
        }

        /* Flush */
        var flush = encoder.flush();
        if (flush.length > 0) mp3Chunks.push(flush);

        this.progress    = 96;
        this.progressMsg = 'Finalising…';
        await this._tick();

        var blob = new Blob(mp3Chunks, { type: 'audio/mpeg' });
        if (this.resultUrl) URL.revokeObjectURL(this.resultUrl);
        this.resultUrl  = URL.createObjectURL(blob);
        this.resultSize = this._fmtBytes(blob.size);
        this.progress   = 100;

      } catch (err) {
        this.errorMsg = 'Processing failed: ' + (err.message || err);
      } finally {
        this.processing = false;
      }
    },

    /* ══ Reset ══ */
    resetSettings: function() {
      this.startTime = 0;
      this.endTime   = this.fileDuration;
      this.volume    = 100;
      this.fadeIn    = 0;
      this.fadeOut   = 0;
      this.resultUrl = null;
      this.errorMsg  = '';
      this._initRangeStyles();
      this._drawWave();
    },

    reset: function() {
      this.stopPlayback();
      this.audioBuffer  = null;
      this.file         = null;
      this.fileName     = '';
      this.fileDuration = 0;
      this._peaks       = null;
      this.resultUrl    = null;
      this.errorMsg     = '';
      this.loading      = false;
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    /* ══ Helpers ══ */
    _pct: function(t) {
      if (!this.fileDuration) return 0;
      return (t / this.fileDuration) * 100;
    },

    _maxFade: function(which) {
      var dur = this.endTime - this.startTime;
      var half = dur * 0.49;
      if (which === 'in')  return Math.max(0, Math.min(10, half));
      if (which === 'out') return Math.max(0, Math.min(10, half));
      return 10;
    },

    _fmtTime: function(s) {
      if (isNaN(s) || s < 0) s = 0;
      var m = Math.floor(s / 60);
      var sec = (s % 60).toFixed(3);
      if (sec.split('.')[0].length < 2) sec = '0' + sec;
      return m + ':' + sec;
    },

    _fmtTimeInput: function(s) {
      if (isNaN(s) || s < 0) s = 0;
      var m   = Math.floor(s / 60);
      var sec = (s % 60).toFixed(3);
      if (sec.split('.')[0].length < 2) sec = '0' + sec;
      return m + ':' + sec;
    },

    _parseTime: function(str) {
      str = (str || '').trim();
      /* Accept M:SS.mmm or SS.mmm or SS */
      var parts = str.split(':');
      if (parts.length === 2) {
        var m = parseFloat(parts[0]);
        var s = parseFloat(parts[1]);
        if (isNaN(m) || isNaN(s)) return null;
        return m * 60 + s;
      }
      var v = parseFloat(str);
      return isNaN(v) ? null : v;
    },

    _fmtBytes: function(b) {
      if (b < 1024)       return b + ' B';
      if (b < 1048576)    return (b/1024).toFixed(1) + ' KB';
      return (b/1048576).toFixed(2) + ' MB';
    },

    _dlName: function() {
      var base = (this.fileName || 'audio').replace(/\.[^.]+$/, '');
      return base + '_edited.mp3';
    },

    _rulerTicks: function() {
      if (!this.fileDuration) return [];
      var step = this.fileDuration > 60 ? 10 : this.fileDuration > 20 ? 5 : 1;
      var ticks = [];
      for (var t = 0; t <= this.fileDuration; t += step) ticks.push(t);
      return ticks;
    },

    _tick: function() {
      return new Promise(function(r){ setTimeout(r, 0); });
    },
  };
}
</script>
@endpush