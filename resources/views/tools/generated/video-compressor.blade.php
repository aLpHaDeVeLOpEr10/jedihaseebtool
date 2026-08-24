@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════════════════
   Video Compressor  —  prefix: vc-
   Theme: violet-700 (#7c3aed)
   Engine: FFmpeg.wasm (@ffmpeg/core@0.12.6 — single-threaded,
           no SharedArrayBuffer, no special HTTP headers needed)
   Pipeline:
     1. Dynamic import @ffmpeg/ffmpeg + @ffmpeg/util  (lazy)
     2. Load @ffmpeg/core WASM via blob URLs            (lazy)
     3. writeFile → exec → readFile → Blob URL
   Stages: idle → loading → compressing → done | error
══════════════════════════════════════════════════════════════ */

/* ── Drop zone ─────────────────────────────────────────── */
.vc-dropzone {
  border: 2.5px dashed #c4b5fd;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #f5f3ff;
  user-select: none;
}
.vc-dropzone:hover,
.vc-dropzone.vc-drag    { border-color: #7c3aed; background: #ede9fe; transform: scale(1.01); }
.vc-dropzone.vc-has-file{ border-color: #7c3aed; background: #ede9fe; border-style: solid; }
.vc-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.vc-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .25rem; word-break: break-all; }
.vc-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── File info row ──────────────────────────────────────── */
.vc-file-row {
  display: flex; flex-wrap: wrap; gap: .75rem;
  align-items: center; padding: .75rem 1rem;
  border-radius: .75rem; background: #f5f3ff; border: 1px solid #ddd6fe;
}
.vc-file-icon { font-size: 1.4rem; flex-shrink: 0; }
.vc-file-name { font-size: .875rem; font-weight: 600; color: #374151; word-break: break-all; }
.vc-file-meta { font-size: .75rem; color: #9ca3af; }

/* ── Option pills ───────────────────────────────────────── */
.vc-opt-group { display: flex; flex-wrap: wrap; gap: .4rem; }
.vc-opt-pill  {
  display: flex; align-items: center; gap: .3rem;
  padding: .3rem .8rem; border-radius: 9999px; cursor: pointer;
  font-size: .78rem; font-weight: 600; transition: all .14s;
  border: 1.5px solid #ddd6fe; background: #fff; color: #6b7280;
}
.vc-opt-pill:hover    { border-color: #a78bfa; color: #7c3aed; }
.vc-opt-pill.vc-on    { border-color: #7c3aed; background: #ede9fe; color: #6d28d9; }
.vc-opt-pill.vc-on .vc-dot { background: #7c3aed; }
.vc-dot { width: .45rem; height: .45rem; border-radius: 50%; background: #d1d5db; flex-shrink: 0; }

/* ── Convert button ─────────────────────────────────────── */
.vc-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer; border: none;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s;
  background: linear-gradient(135deg, #6d28d9, #7c3aed, #8b5cf6);
  color: #fff; box-shadow: 0 4px 14px rgba(124,58,237,.35);
}
.vc-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(124,58,237,.5); transform: translateY(-1px); }
.vc-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Progress ───────────────────────────────────────────── */
.vc-progress-track {
  width: 100%; height: .65rem; border-radius: 9999px;
  background: #ede9fe; overflow: hidden;
}
.vc-progress-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #6d28d9, #a78bfa);
  transition: width .5s ease;
  position: relative; overflow: hidden;
}
.vc-progress-fill.vc-pulse::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);
  animation: vcShimmer 1.4s infinite;
}
@keyframes vcShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Result bars ────────────────────────────────────────── */
.vc-size-bar {
  display: flex; align-items: center; gap: .75rem;
  padding: .65rem 1rem; border-radius: .75rem;
  background: #f9fafb; border: 1px solid #f3f4f6;
}
.vc-bar-label { font-size: .75rem; font-weight: 700; color: #6b7280; width: 6.5rem; flex-shrink: 0; }
.vc-bar-track { flex: 1; height: .5rem; border-radius: 9999px; background: #e5e7eb; overflow: hidden; }
.vc-bar-fill  { height: 100%; border-radius: 9999px; }
.vc-bar-orig  { background: #d1d5db; }
.vc-bar-comp  { background: linear-gradient(90deg, #6d28d9, #a78bfa); }
.vc-bar-num   { font-size: .8rem; font-weight: 700; color: #374151; width: 5rem; text-align: right; flex-shrink: 0; }

/* ── Saving badge ───────────────────────────────────────── */
.vc-saving {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .35rem .9rem; border-radius: 9999px;
  background: #f0fdf4; border: 1.5px solid #bbf7d0;
  font-size: .85rem; font-weight: 800; color: #166534;
}
.vc-saving-bad {
  background: #fefce8; border-color: #fde68a; color: #854d0e;
}

/* ── Download button ────────────────────────────────────── */
.vc-dl-btn {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  padding: .75rem 1.5rem; border-radius: .875rem; font-size: .95rem; font-weight: 800;
  background: #166534; color: #fff; border: 1.5px solid #15803d;
  box-shadow: 0 2px 8px rgba(22,101,52,.2);
  cursor: pointer; transition: all .14s; text-decoration: none;
}
.vc-dl-btn:hover { background: #14532d; }

/* ── Cancel button ──────────────────────────────────────── */
.vc-cancel-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .45rem 1rem; border-radius: .75rem; font-size: .8rem; font-weight: 700;
  background: #fff; color: #991b1b; border: 1.5px solid #fecaca;
  cursor: pointer; transition: all .14s;
}
.vc-cancel-btn:hover { background: #fef2f2; }

/* ── Alert badges ───────────────────────────────────────── */
.vc-privacy {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .55rem .85rem; border-radius: .75rem;
  background: #f5f3ff; border: 1px solid #ddd6fe;
  font-size: .75rem; color: #6d28d9; font-weight: 500;
}
.vc-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #fffbeb; border: 1.5px solid #fde68a;
  font-size: .78rem; color: #92400e;
}
.vc-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}
.vc-info {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #f0fdf4; border: 1.5px solid #bbf7d0;
  font-size: .78rem; color: #166534;
}

/* ── Section divider ────────────────────────────────────── */
.vc-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.vc-div::before,.vc-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Spinner ────────────────────────────────────────────── */
@keyframes vcSpin { to{ transform:rotate(360deg); } }
.vc-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: vcSpin .65s linear infinite; flex-shrink: 0;
}
.vc-spin-v {
  border-color: rgba(124,58,237,.2); border-top-color: #7c3aed;
}

/* ── Format comparison table ────────────────────────────── */
.vc-fmt-table { width: 100%; border-collapse: collapse; font-size: .7rem; }
.vc-fmt-table th, .vc-fmt-table td {
  padding: .3rem .5rem; text-align: left; border-bottom: 1px solid #f3f4f6;
}
.vc-fmt-table th { font-weight: 700; color: #374151; background: #f9fafb; font-size: .65rem; text-transform: uppercase; }

@media (max-width: 640px) {
  .vc-dropzone { padding: 1.5rem 1rem; }
  .vc-dz-icon  { font-size: 2rem; }
  .vc-bar-label { width: 4.5rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="w-14 h-14 rounded-xl bg-violet-100 flex items-center justify-center text-3xl flex-shrink-0">
          🎬
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Video Compressor</h1>
          <p class="text-gray-500 mt-1">Reduce video file size in your browser — free, instant, private. No uploads to any server.</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',  'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'Video Compressor']
      ]"/>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      {{-- ── Main tool area ── --}}
      <div class="lg:col-span-2 space-y-5" x-data="vcTool()">

        {{-- ══ Upload + Settings card ══ --}}
        <div class="card p-6 space-y-5" x-show="phase === 'idle'">

          <h2 class="text-lg font-semibold text-gray-900">Upload a Video</h2>

          {{-- Privacy badge --}}
          <div class="vc-privacy">
            <span class="flex-shrink-0 mt-0.5">🔒</span>
            <span>Your video is compressed entirely in your browser using WebAssembly — nothing is uploaded to any server.</span>
          </div>

          {{-- Drop zone --}}
          <div
            :class="['vc-dropzone', isDragging?'vc-drag':'', file?'vc-has-file':'']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
          >
            <input
              type="file"
              x-ref="fileInput"
              accept="video/*,.mp4,.webm,.avi,.mkv,.mov,.flv,.wmv,.3gp,.m4v"
              @change="onFileChange($event)"
              class="hidden"
            >
            <div x-show="!file">
              <div class="vc-dz-icon">🎬</div>
              <p class="vc-dz-title">Drag &amp; drop your video here</p>
              <p class="vc-dz-sub">or click to browse &nbsp;·&nbsp; MP4, MKV, MOV, AVI, WebM &amp; more &nbsp;·&nbsp; max 500 MB</p>
            </div>
            <div x-show="file">
              <div class="vc-dz-icon">✅</div>
              <p class="vc-dz-title" x-text="file ? file.name : ''"></p>
              <p class="vc-dz-sub" x-text="file ? (fmtBytes(file.size) + ' — click to change') : ''"></p>
            </div>
          </div>

          {{-- File details row --}}
          <div class="vc-file-row" x-show="file && !fileError" x-transition>
            <span class="vc-file-icon">📹</span>
            <div class="flex-1 min-w-0">
              <p class="vc-file-name" x-text="file ? file.name : ''"></p>
              <p class="vc-file-meta" x-text="file ? (fmtBytes(file.size) + ' · ' + (file.type || 'video')) : ''"></p>
            </div>
            <button @click.stop="clearFile()" class="text-xs text-gray-400 hover:text-red-500 font-bold px-2">✕</button>
          </div>

          {{-- Large-file warning --}}
          <div x-show="file && file.size > 200*1024*1024 && !fileError" x-transition class="vc-warn">
            <span class="flex-shrink-0">⚠️</span>
            <span>
              <strong>Large file detected (<span x-text="file ? fmtBytes(file.size) : ''"></span>).</strong>
              Compression will take several minutes and requires significant browser memory.
              Files over 400 MB may cause browser memory errors on low-RAM devices.
            </span>
          </div>

          {{-- File error --}}
          <div x-show="fileError" x-transition class="vc-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          {{-- Previous compression error --}}
          <div x-show="convError" x-transition class="vc-error">
            <span>⚠</span>
            <span>
              <strong>Compression failed:</strong>
              <span x-text="convError"></span>
            </span>
          </div>

          {{-- ── Compression settings ── --}}
          <div x-show="file && !fileError" x-transition class="space-y-4 pt-1">

            <p class="vc-div">Compression Settings</p>

            {{-- Quality --}}
            <div>
              <label class="form-label mb-2 flex items-center gap-1.5">
                Quality / File Size
                <span class="text-xs font-normal text-gray-400">(lower quality = smaller file)</span>
              </label>
              <div class="vc-opt-group">
                <template x-for="opt in qualityOpts" :key="opt.value">
                  <label
                    :class="['vc-opt-pill', quality===opt.value?'vc-on':'']"
                    :title="opt.hint"
                  >
                    <input type="radio" x-model="quality" :value="opt.value" class="sr-only">
                    <span class="vc-dot"></span>
                    <span x-text="opt.label"></span>
                  </label>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-1.5" x-text="qualityOpts.find(o=>o.value===quality)?.hint || ''"></p>
            </div>

            {{-- Resolution --}}
            <div>
              <label class="form-label mb-2">Output Resolution</label>
              <div class="vc-opt-group">
                <template x-for="opt in resOpts" :key="opt.value">
                  <label :class="['vc-opt-pill', resolution===opt.value?'vc-on':'']">
                    <input type="radio" x-model="resolution" :value="opt.value" class="sr-only">
                    <span class="vc-dot"></span>
                    <span x-text="opt.label"></span>
                  </label>
                </template>
              </div>
            </div>

            {{-- Output format --}}
            <div>
              <label class="form-label mb-2">Output Format</label>
              <div class="vc-opt-group">
                <label :class="['vc-opt-pill', format==='mp4'?'vc-on':'']">
                  <input type="radio" x-model="format" value="mp4" class="sr-only">
                  <span class="vc-dot"></span>
                  <span>MP4 (H.264) — most compatible</span>
                </label>
                <label :class="['vc-opt-pill', format==='webm'?'vc-on':'']">
                  <input type="radio" x-model="format" value="webm" class="sr-only">
                  <span class="vc-dot"></span>
                  <span>WebM (VP9) — better compression</span>
                </label>
              </div>
            </div>

          </div>

          {{-- Compress button --}}
          <button
            @click="compress()"
            :disabled="!file || !!fileError"
            class="vc-btn"
          >
            🔄 Compress Video
          </button>

        </div>

        {{-- ══ Progress card ══ --}}
        <div class="card p-6 space-y-5" x-show="phase === 'loading' || phase === 'compressing'" x-transition>

          <div class="flex items-start justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-gray-900" x-text="phaseTitle"></h2>
              <p class="text-sm text-gray-500 mt-0.5" x-text="phaseSubtitle"></p>
            </div>
            <button
              @click="abort()"
              class="vc-cancel-btn flex-shrink-0"
              :disabled="aborting"
              x-show="phase === 'compressing'"
            >
              ✕ Cancel
            </button>
          </div>

          {{-- Progress bar --}}
          <div>
            <div class="flex justify-between text-sm font-medium text-gray-600 mb-1.5">
              <span x-text="progressLabel"></span>
              <span class="text-violet-600 font-bold" x-text="displayProgress + '%'"></span>
            </div>
            <div class="vc-progress-track">
              <div
                class="vc-progress-fill vc-pulse"
                :style="'width:' + Math.max(4, displayProgress) + '%'"
              ></div>
            </div>
          </div>

          {{-- Loading sub-phase detail --}}
          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="vc-spin vc-spin-v" style="width:.85em;height:.85em;border-width:1.5px"></span>
            <span x-show="subPhase === 'script'">Loading FFmpeg engine scripts from CDN…</span>
            <span x-show="subPhase === 'wasm'">
              Downloading FFmpeg WASM binary (~25 MB) — <strong>first run only</strong>, your browser caches this.
            </span>
            <span x-show="subPhase === 'init'">Initializing FFmpeg engine…</span>
            <span x-show="phase === 'compressing' && !aborting">
              Compressing video — keep this tab active for best performance.
            </span>
          </div>

          {{-- Abort feedback --}}
          <div x-show="aborting" class="vc-warn text-xs">
            <span>⏳</span><span>Stopping compression, please wait…</span>
          </div>

        </div>

        {{-- ══ Results card ══ --}}
        <div class="card p-6 space-y-5" x-show="phase === 'done'" x-transition>

          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">✅ Compression Complete</h2>
              <p class="text-xs text-gray-400 mt-0.5" x-text="'Output: ' + outputName"></p>
            </div>
            <button @click="reset()" class="btn btn-secondary text-sm">↺ Compress Another</button>
          </div>

          {{-- Size comparison bars --}}
          <div class="space-y-2">
            <div class="vc-size-bar">
              <span class="vc-bar-label">Original</span>
              <div class="vc-bar-track">
                <div class="vc-bar-fill vc-bar-orig" style="width:100%"></div>
              </div>
              <span class="vc-bar-num" x-text="fmtBytes(origSize)"></span>
            </div>
            <div class="vc-size-bar">
              <span class="vc-bar-label">Compressed</span>
              <div class="vc-bar-track">
                <div class="vc-bar-fill vc-bar-comp" :style="'width:' + Math.min(100, (compSize/origSize)*100) + '%'"></div>
              </div>
              <span class="vc-bar-num" x-text="fmtBytes(compSize)"></span>
            </div>
          </div>

          {{-- Reduction badge --}}
          <div class="flex items-center gap-3 flex-wrap">
            <div :class="['vc-saving', reduction < 5 ? 'vc-saving-bad' : '']">
              <span x-text="reduction >= 5 ? '✅' : 'ℹ️'"></span>
              <span x-text="reduction >= 5
                ? reduction + '% smaller  (–' + fmtBytes(origSize - compSize) + ')'
                : 'Only ' + reduction + '% reduction — file was already compressed'">
              </span>
            </div>
          </div>

          <p class="vc-div">Download</p>

          {{-- Download button --}}
          <a
            :href="outputUrl || '#'"
            :download="outputName"
            class="vc-dl-btn"
            x-show="outputUrl"
          >
            ⬇ Download <span x-text="outputName"></span>
          </a>

          {{-- Tips if reduction was small --}}
          <div x-show="reduction < 10" class="vc-info text-xs">
            <span class="flex-shrink-0">💡</span>
            <span>
              The file was already well-compressed — try <strong>Max Compression</strong> quality preset
              or a lower output resolution for a bigger reduction.
            </span>
          </div>

        </div>

        {{-- ══ How it works ══ --}}
        <div class="card p-6" x-show="phase !== 'loading' && phase !== 'compressing'">
          <p class="vc-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">📤</span>
              <div>
                <p class="font-semibold text-gray-800">1. Upload</p>
                <p class="text-gray-500 text-xs mt-0.5">Select any video file up to 500 MB.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">⚙️</span>
              <div>
                <p class="font-semibold text-gray-800">2. Choose settings</p>
                <p class="text-gray-500 text-xs mt-0.5">Pick quality, resolution, and output format.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">🔄</span>
              <div>
                <p class="font-semibold text-gray-800">3. Compress</p>
                <p class="text-gray-500 text-xs mt-0.5">FFmpeg runs in WebAssembly inside your browser.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">4. Download</p>
                <p class="text-gray-500 text-xs mt-0.5">Save the compressed video to your device.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      {{-- ── Sidebar ── --}}
      <div class="space-y-6">

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="{{ route('categories.show', $tool->category) }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl">{{ $tool->category->icon }}</span>
            <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
          </a>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported Formats</h3>
          <div class="flex flex-wrap gap-1.5">
            @foreach(['MP4','MKV','MOV','AVI','WebM','FLV','WMV','3GP','M4V'] as $fmt)
            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-violet-50 text-violet-700 border border-violet-200">{{ $fmt }}</span>
            @endforeach
          </div>
          <p class="text-xs text-gray-400 mt-2">Any format FFmpeg can decode is accepted.</p>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">MP4 vs WebM</h3>
          <table class="vc-fmt-table">
            <thead>
              <tr><th></th><th>MP4</th><th>WebM</th></tr>
            </thead>
            <tbody class="text-gray-600">
              <tr><td class="font-semibold">Codec</td><td>H.264</td><td>VP9</td></tr>
              <tr><td class="font-semibold">Compression</td><td>Good</td><td>Better</td></tr>
              <tr><td class="font-semibold">Compatibility</td><td>Universal</td><td>Modern browsers</td></tr>
              <tr><td class="font-semibold">Best for</td><td>Sharing/email</td><td>Web embeds</td></tr>
            </tbody>
          </table>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Quality Presets</h3>
          <ul class="space-y-2 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-violet-600 font-bold">◼</span><span><strong>Max Compress</strong> — biggest size reduction, noticeable quality drop at high motion</span></li>
            <li class="flex gap-2"><span class="text-violet-600 font-bold">◼</span><span><strong>Balanced</strong> — good trade-off, most videos look fine (recommended)</span></li>
            <li class="flex gap-2"><span class="text-violet-600 font-bold">◼</span><span><strong>High Quality</strong> — small quality loss, moderate compression</span></li>
            <li class="flex gap-2"><span class="text-violet-600 font-bold">◼</span><span><strong>Near Lossless</strong> — minimal quality loss, largest output file</span></li>
          </ul>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-violet-600 font-bold flex-shrink-0">•</span><span>First run downloads the FFmpeg engine (~25 MB, one time). Subsequent compressions start instantly.</span></li>
            <li class="flex gap-2"><span class="text-violet-600 font-bold flex-shrink-0">•</span><span>Lowering resolution (e.g. 1080p→720p) often reduces file size more than quality settings alone.</span></li>
            <li class="flex gap-2"><span class="text-violet-600 font-bold flex-shrink-0">•</span><span>Keep the browser tab active during compression for best performance.</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">🔒</span><span>Your video never leaves your device — compression runs locally.</span></li>
          </ul>
        </div>

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
<script>
/* ─────────────────────────────────────────────────────────────────
   Video Compressor  —  Alpine.js component  (prefix: vc-)

   Engine: FFmpeg.wasm v0.12 (single-threaded @ffmpeg/core)
     - NO SharedArrayBuffer required
     - NO special HTTP headers (no COOP / COEP needed)

   Loading strategy (FIX for "Could not load FFmpeg engine" error):
     ✗ OLD: dynamic ESM import() — blocked in many browser security
             contexts, fragile on cross-origin module workers
     ✓ NEW: lazy <script> tags (UMD builds) via jsDelivr CDN
             → exposes window.FFmpegWASM  (no import() needed)
             → UMD worker is inlined as a blob, no cross-origin worker

   Self-implemented helpers (eliminates @ffmpeg/util dependency):
     _toBlobURL(url, mime, onPct)  — fetch with streaming progress → blob URL
     _fileToUint8(file)            — File → Uint8Array via arrayBuffer()

   CDN: jsDelivr (primary) — more reliable than unpkg for large WASM files
   Fallback CDN: unpkg (tried automatically if jsDelivr fails)

   FFmpeg command (H.264 / MP4):
     ffmpeg -i input.ext
       -c:v libx264 -crf {crf} -preset fast
       -map 0:v -map 0:a?        ← audio skipped gracefully if absent
       -c:a aac -b:a 128k
       [-vf scale=-2:{height}]   ← only when downscaling
       -movflags +faststart
       output.mp4
   WebM/VP9: libvpx-vp9 -crf {crf} -b:v 0 + libopus
─────────────────────────────────────────────────────────────────── */
function vcTool() {
  return {

    // ── File ────────────────────────────────────────────
    file:       null,
    fileError:  '',
    isDragging: false,

    // ── Settings ─────────────────────────────────────────
    quality:    'balanced',
    resolution: 'original',
    format:     'mp4',

    qualityOpts: [
      { value: 'max',      label: 'Max Compress',  hint: 'CRF 35/41 — significant quality drop, smallest file size' },
      { value: 'balanced', label: 'Balanced',      hint: 'CRF 28/33 — good quality / size trade-off (recommended)' },
      { value: 'quality',  label: 'High Quality',  hint: 'CRF 23/27 — minor quality loss, moderate size reduction' },
      { value: 'lossless', label: 'Near Lossless', hint: 'CRF 18/20 — minimal quality loss, largest output file' },
    ],

    resOpts: [
      { value: 'original', label: 'Original' },
      { value: '1080p',    label: '1080p HD' },
      { value: '720p',     label: '720p HD'  },
      { value: '480p',     label: '480p SD'  },
      { value: '360p',     label: '360p'     },
    ],

    // ── State ────────────────────────────────────────────
    phase:        'idle',   // idle | loading | compressing | done
    subPhase:     '',       // script | wasm | init  (sub-steps of 'loading')
    progress:     0,        // compression progress 0-100
    wasmProgress: 0,        // WASM download progress 0-100
    aborting:     false,

    // ── Results ──────────────────────────────────────────
    origSize:   0,
    compSize:   0,
    outputUrl:  null,
    outputName: '',
    convError:  '',

    // ── Cached FFmpeg instance (persists across conversions) ──
    _ffmpeg: null,

    // ── Computed ─────────────────────────────────────────
    get reduction() {
      if (!this.origSize || !this.compSize) return 0;
      return Math.max(0, Math.round((1 - this.compSize / this.origSize) * 100));
    },

    get phaseTitle() {
      if (this.phase === 'loading')     return 'Loading FFmpeg Engine…';
      if (this.phase === 'compressing') return 'Compressing Video…';
      return '';
    },

    get phaseSubtitle() {
      if (this.phase === 'loading')     return 'WebAssembly engine (~25 MB) — cached by your browser after first run.';
      if (this.phase === 'compressing') return this.file ? this.file.name : '';
      return '';
    },

    get progressLabel() {
      if (this.subPhase === 'script') return 'Loading FFmpeg scripts…';
      if (this.subPhase === 'wasm')   return 'Downloading WASM binary…';
      if (this.subPhase === 'init')   return 'Initializing FFmpeg…';
      if (this.phase === 'loading')   return 'Loading FFmpeg…';
      if (this.progress > 0)          return 'Compressing…';
      return 'Preparing…';
    },

    // displayProgress shows wasmProgress during loading, compression progress otherwise
    get displayProgress() {
      if (this.phase === 'loading') {
        if (this.subPhase === 'wasm') return this.wasmProgress;
        if (this.subPhase === 'init') return 100;
        return 0;
      }
      return this.progress;
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

    clearFile() {
      this.file = null; this.fileError = ''; this.convError = '';
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    _setFile(file) {
      this.fileError = ''; this.convError = '';
      var err = this._validateFile(file);
      if (err) { this.fileError = err; this.file = null; return; }
      this.file = file;
    },

    _validateFile(file) {
      if (!file) return 'No file selected.';
      var name = file.name.toLowerCase();
      var mime = (file.type || '').toLowerCase();
      var exts = ['.mp4','.webm','.avi','.mkv','.mov','.flv','.wmv','.3gp','.m4v','.ts','.ogv','.ogg','.rm','.rmvb'];
      if (!mime.startsWith('video/') && !exts.some(function(e){ return name.endsWith(e); }))
        return 'Unsupported file type. Please select a video file (MP4, MKV, MOV, AVI, WebM, etc.).';
      if (file.size > 500 * 1024 * 1024)
        return 'File too large (' + this.fmtBytes(file.size) + '). Maximum is 500 MB.';
      if (file.size < 1024)
        return 'File appears to be empty or corrupt.';
      return '';
    },

    // ── Compress ─────────────────────────────────────────
    async compress() {
      if (!this.file || this.fileError) return;

      this.convError   = '';
      this.outputUrl   = null;
      this.outputName  = '';
      this.origSize    = this.file.size;
      this.compSize    = 0;
      this.progress    = 0;
      this.wasmProgress= 0;
      this.aborting    = false;

      try {
        // ── Load FFmpeg engine (lazy, cached) ──
        if (!this._ffmpeg) {
          this.phase    = 'loading';
          this.subPhase = 'script';
          await this._loadFFmpeg();
        }
        if (this.aborting) { this._resetPhase(); return; }

        // ── Build FFmpeg command ──
        var inputExt  = (this.file.name.split('.').pop() || 'mp4').toLowerCase();
        var inputName = 'vc_in.'  + inputExt;
        var outputExt = this.format;
        var outName   = 'vc_out.' + outputExt;
        var cmd       = this._buildCmd(inputName, outName);

        // ── Set up compression progress ──
        this.phase    = 'compressing';
        this.subPhase = '';
        this.progress = 0;
        var vm        = this;
        this._ffmpeg.on('progress', function(ref) {
          if (ref.progress >= 0) vm.progress = Math.min(99, Math.round(ref.progress * 100));
        });

        // ── Load file into FFmpeg virtual FS ──
        var fileData = await this._fileToUint8(this.file);
        if (this.aborting) { this._resetPhase(); return; }
        await this._ffmpeg.writeFile(inputName, fileData);
        this.progress = 2;

        // ── Run compression ──
        await this._ffmpeg.exec(cmd);
        if (this.aborting) {
          await this._safeDelete(inputName); await this._safeDelete(outName);
          this._resetPhase(); return;
        }

        // ── Read output → Blob URL ──
        this.progress = 99;
        var data     = await this._ffmpeg.readFile(outName);
        var mime     = outputExt === 'mp4' ? 'video/mp4' : 'video/webm';
        var blob     = new Blob([data.buffer], { type: mime });
        this.compSize   = blob.size;
        this.outputUrl  = URL.createObjectURL(blob);
        this.outputName = this.file.name.replace(/\.[^.]+$/, '') + '-compressed.' + outputExt;
        this.progress   = 100;
        this.phase      = 'done';

        await this._safeDelete(inputName);
        await this._safeDelete(outName);

      } catch (err) {
        if (this.aborting) { this._resetPhase(); return; }
        this.convError = this._friendlyError(err);
        this.phase     = 'idle';
        this.subPhase  = '';
        this.progress  = 0;
        if (this._ffmpeg) {
          try { this._ffmpeg.terminate(); } catch(e) {}
          this._ffmpeg = null;
        }
      }
    },

    // ── FFmpeg command builder ────────────────────────────
    _buildCmd(inputName, outName) {
      var crfTable = {
        max:      { mp4: '35', webm: '41' },
        balanced: { mp4: '28', webm: '33' },
        quality:  { mp4: '23', webm: '27' },
        lossless: { mp4: '18', webm: '20' },
      };
      var crf = crfTable[this.quality][this.format];
      var heights = { '1080p': '1080', '720p': '720', '480p': '480', '360p': '360' };
      var resH = this.resolution !== 'original' ? heights[this.resolution] : null;

      var cmd = ['-i', inputName];
      if (this.format === 'mp4') {
        cmd.push('-c:v', 'libx264', '-crf', crf, '-preset', 'fast');
      } else {
        cmd.push('-c:v', 'libvpx-vp9', '-crf', crf, '-b:v', '0');
      }
      if (resH) cmd.push('-vf', 'scale=-2:' + resH);
      cmd.push('-map', '0:v', '-map', '0:a?');
      if (this.format === 'mp4') {
        cmd.push('-c:a', 'aac', '-b:a', '128k', '-movflags', '+faststart');
      } else {
        cmd.push('-c:a', 'libopus', '-b:a', '96k');
      }
      cmd.push(outName);
      return cmd;
    },

    // ── Load FFmpeg.wasm ──────────────────────────────────
    // Uses <script> tags (UMD build) instead of dynamic ESM import().
    // Reason: dynamic import() is blocked by some browser security policies
    // and is fragile with cross-origin module workers. The UMD build exposes
    // window.FFmpegWASM and uses an inline blob worker (no cross-origin issue).
    async _loadFFmpeg() {
      var CDN_PRIMARY  = 'https://cdn.jsdelivr.net/npm';
      var CDN_FALLBACK = 'https://unpkg.com';

      // ── Step 1: Load the FFmpeg UMD script ──
      // Tries jsDelivr first; falls back to unpkg if it fails.
      this.subPhase = 'script';
      if (!window.FFmpegWASM) {
        var scriptUrl = CDN_PRIMARY + '/@ffmpeg/ffmpeg@0.12.7/dist/umd/ffmpeg.js';
        try {
          await this._loadScript(scriptUrl);
        } catch (e) {
          // Fallback to unpkg
          scriptUrl = CDN_FALLBACK + '/@ffmpeg/ffmpeg@0.12.7/dist/umd/ffmpeg.js';
          await this._loadScript(scriptUrl);
        }
      }

      // Verify the global was exposed
      var FFmpegCls = (window.FFmpegWASM && window.FFmpegWASM.FFmpeg)
        ? window.FFmpegWASM.FFmpeg
        : null;
      if (!FFmpegCls) {
        throw new Error(
          'The FFmpeg script loaded but the expected global (window.FFmpegWASM) was not found. ' +
          'This may be a CDN version mismatch — please try refreshing the page.'
        );
      }

      // ── Step 2: Download the single-threaded WASM core ──
      // We download it ourselves using a streaming fetch so we can show real %
      // progress. We then create a blob: URL so FFmpeg can load it without CORS.
      this.subPhase     = 'wasm';
      this.wasmProgress = 0;

      var vm       = this;
      var coreBase = CDN_PRIMARY + '/@ffmpeg/core@0.12.6/dist/esm';
      var coreBaseF= CDN_FALLBACK + '/@ffmpeg/core@0.12.6/dist/esm';

      var coreURL, wasmURL;
      try {
        coreURL = await this._toBlobURL(coreBase + '/ffmpeg-core.js',   'text/javascript');
        wasmURL = await this._toBlobURL(coreBase + '/ffmpeg-core.wasm', 'application/wasm',
          function(pct) { vm.wasmProgress = pct; });
      } catch (e) {
        // Fallback CDN
        coreURL = await this._toBlobURL(coreBaseF + '/ffmpeg-core.js',   'text/javascript');
        wasmURL = await this._toBlobURL(coreBaseF + '/ffmpeg-core.wasm', 'application/wasm',
          function(pct) { vm.wasmProgress = pct; });
      }

      // ── Step 3: Initialize FFmpeg with the blob URLs ──
      this.subPhase     = 'init';
      this.wasmProgress = 100;

      var ffmpeg = new FFmpegCls();
      // Suppress FFmpeg's verbose log output (it would pollute the console)
      ffmpeg.on('log', function() {});

      await ffmpeg.load({ coreURL: coreURL, wasmURL: wasmURL });
      this._ffmpeg = ffmpeg;
    },

    // ── Abort ─────────────────────────────────────────────
    async abort() {
      if (this.phase !== 'compressing') return;
      this.aborting = true;
      if (this._ffmpeg) {
        try { this._ffmpeg.terminate(); } catch(e) {}
        this._ffmpeg = null;
      }
      this._resetPhase();
    },

    _resetPhase() {
      this.phase = 'idle'; this.subPhase = '';
      this.progress = 0;   this.aborting = false;
    },

    // ── Reset ─────────────────────────────────────────────
    reset() {
      if (this.outputUrl) { URL.revokeObjectURL(this.outputUrl); this.outputUrl = null; }
      this.file = null; this.fileError = ''; this.convError = '';
      this.isDragging = false; this.phase = 'idle'; this.subPhase = '';
      this.progress = 0; this.wasmProgress = 0; this.aborting = false;
      this.origSize = 0; this.compSize = 0; this.outputName = '';
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    // ── Helpers ───────────────────────────────────────────

    // Load a <script> tag lazily; resolves when the script executes.
    _loadScript(src) {
      return new Promise(function(resolve, reject) {
        if (document.querySelector('script[src="' + src + '"]')) { resolve(); return; }
        var s = document.createElement('script');
        s.src = src;
        s.onload  = resolve;
        s.onerror = function() { reject(new Error('Failed to load script: ' + src)); };
        document.head.appendChild(s);
      });
    },

    // Fetch a URL, stream it for progress reporting, and return a blob: URL.
    // onPct(0-100) is called as bytes arrive. Falls back to a simple fetch if
    // ReadableStream is not available (e.g. older mobile browsers).
    async _toBlobURL(url, mimeType, onPct) {
      var response = await fetch(url);
      if (!response.ok) throw new Error('HTTP ' + response.status + ' fetching ' + url.split('/').pop());

      var total = parseInt(response.headers.get('Content-Length') || '0', 10);
      var received = 0;
      var chunks   = [];

      if (response.body && response.body.getReader && total > 0) {
        var reader = response.body.getReader();
        while (true) {
          var step = await reader.read();
          if (step.done) break;
          chunks.push(step.value);
          received += step.value.length;
          if (onPct) onPct(Math.round((received / total) * 100));
        }
        // Combine chunks into one Uint8Array
        var buf = new Uint8Array(received);
        var off = 0;
        for (var i = 0; i < chunks.length; i++) { buf.set(chunks[i], off); off += chunks[i].length; }
        var blob = new Blob([buf.buffer], { type: mimeType });
      } else {
        // Simple fallback (no streaming progress)
        if (onPct) onPct(50);
        var arrayBuf = await response.arrayBuffer();
        if (onPct) onPct(100);
        var blob = new Blob([arrayBuf], { type: mimeType });
      }
      return URL.createObjectURL(blob);
    },

    // Read a File into a Uint8Array (replaces @ffmpeg/util fetchFile).
    async _fileToUint8(file) {
      var buf = await file.arrayBuffer();
      return new Uint8Array(buf);
    },

    async _safeDelete(name) {
      try { await this._ffmpeg.deleteFile(name); } catch(e) {}
    },

    fmtBytes(bytes) {
      if (!bytes) return '0 B';
      if (bytes < 1024)        return bytes + ' B';
      if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
      if (bytes < 1073741824)  return (bytes / 1048576).toFixed(1) + ' MB';
      return (bytes / 1073741824).toFixed(2) + ' GB';
    },

    // Show a helpful, actionable error. Also includes the raw message so the
    // user can report it if needed.
    _friendlyError(err) {
      var raw = String(err && err.message ? err.message : err);
      if (raw.includes('memory') || raw.includes('quota') || raw.includes('OOM'))
        return 'Browser ran out of memory. Try a smaller file, lower resolution, or close other tabs.';
      if (raw.includes('codec') || raw.includes('Encoder') || raw.includes('muxer') || raw.includes('Invalid data'))
        return 'Unsupported codec or container in this file. Try a different file or convert to MP4 first. (' + raw.slice(0,120) + ')';
      if (raw.includes('terminate') || raw.includes('Worker stopped'))
        return 'The FFmpeg engine was terminated. Please try again.';
      if (raw.includes('HTTP 4') || raw.includes('HTTP 5'))
        return 'Could not download the FFmpeg engine from CDN (' + raw + '). Check your internet connection and try again.';
      if (raw.includes('script') || raw.includes('Failed to load script'))
        return 'Could not load the FFmpeg engine script. Check your internet connection and try again. (' + raw + ')';
      if (raw.includes('FFmpegWASM'))
        return raw; // already a descriptive message from _loadFFmpeg
      return raw.length < 300 ? raw : 'Compression error — ' + raw.slice(0, 200) + '…';
    },
  };
}
</script>
@endpush