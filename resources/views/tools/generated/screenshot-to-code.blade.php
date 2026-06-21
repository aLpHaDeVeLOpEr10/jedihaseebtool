@extends('layouts.public')

@section('title', 'Screenshot to Code — Generate HTML & CSS from Screenshots | JEDISEBITOOL')
@section('description', 'Upload any screenshot or UI mockup and instantly get clean HTML + CSS starter code with the exact extracted color palette. Free, browser-based, no uploads.')

@section('head')
<style>
/* ══════════════════════════════════════════════
   Screenshot to Code   prefix: stc-
   ══════════════════════════════════════════════ */

/* ── Drop zone ── */
.stc-drop{border:2px dashed #a5b4fc;border-radius:18px;padding:2.5rem 1.5rem;text-align:center;cursor:pointer;background:#f8f7ff;transition:border-color .2s,background .2s;user-select:none}
.stc-drop:hover,.stc-drop.over{border-color:#4f46e5;background:#eef2ff}
.stc-drop-icon{width:68px;height:68px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem}

/* ── Option tabs (framework) ── */
.stc-fw-tab{flex:1;padding:.5rem .75rem;font-size:.78rem;font-weight:600;text-align:center;cursor:pointer;border-radius:9px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;transition:all .13s;white-space:nowrap}
.stc-fw-tab:hover{background:#f5f3ff;border-color:#c4b5fd;color:#4f46e5}
.stc-fw-tab.active{background:#4f46e5;border-color:#4f46e5;color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.28)}

/* ── Progress bar ── */
.stc-progress-track{height:6px;background:#e5e7eb;border-radius:999px;overflow:hidden}
.stc-progress-fill{height:100%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:999px;transition:width .12s linear}

/* ── Code output ── */
.stc-code-wrap{position:relative;border-radius:14px;overflow:hidden}
.stc-code-pre{margin:0;padding:1.25rem 1rem 1.25rem 1.25rem;background:#1e1e2e;color:#cdd6f4;font-family:'JetBrains Mono','Fira Code',Consolas,monospace;font-size:12px;line-height:1.7;overflow:auto;max-height:480px;white-space:pre;tab-size:2}
.stc-code-pre::-webkit-scrollbar{width:5px;height:5px}
.stc-code-pre::-webkit-scrollbar-thumb{background:#45475a;border-radius:3px}
.stc-copy-btn{position:absolute;top:.625rem;right:.625rem;background:#313244;color:#bac2de;border:1px solid #45475a;border-radius:7px;padding:.3rem .7rem;font-size:.7rem;font-weight:600;cursor:pointer;transition:all .12s;display:flex;align-items:center;gap:.3rem}
.stc-copy-btn:hover{background:#45475a;color:#cdd6f4}
.stc-copy-btn.ok{background:#1e3a29;color:#a6e3a1;border-color:#a6e3a1}

/* ── Output tabs ── */
.stc-tab{padding:.45rem .9rem;font-size:.75rem;font-weight:600;cursor:pointer;border-radius:8px;border:1.5px solid transparent;color:#6b7280;transition:all .12s;white-space:nowrap}
.stc-tab:hover{background:#f5f3ff;color:#4f46e5}
.stc-tab.active{background:#eef2ff;border-color:#4f46e5;color:#4f46e5}

/* ── Color palette strip ── */
.stc-swatch{width:36px;height:36px;border-radius:8px;border:2px solid rgba(0,0,0,.08);flex-shrink:0;cursor:pointer;transition:transform .12s;position:relative}
.stc-swatch:hover{transform:scale(1.15)}
.stc-swatch-tooltip{position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:#1e1e2e;color:#cdd6f4;font-size:.65rem;font-family:monospace;padding:.2rem .5rem;border-radius:5px;white-space:nowrap;pointer-events:none;opacity:0;transition:opacity .15s}
.stc-swatch:hover .stc-swatch-tooltip{opacity:1}

/* ── Preview iframe ── */
.stc-preview-frame{width:100%;border:none;border-radius:0 0 14px 14px;background:#fff;display:block}

/* ── Spinner ── */
.stc-spin{width:36px;height:36px;border:3px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:stc-rot .8s linear infinite}
.stc-spin-sm{width:14px;height:14px;border:2px solid rgba(79,70,229,.25);border-top-color:#4f46e5;border-radius:50%;animation:stc-rot .7s linear infinite;flex-shrink:0}
@keyframes stc-rot{to{transform:rotate(360deg)}}

/* ── Info boxes ── */
.stc-tip{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.6rem .875rem;font-size:.78rem;color:#15803d;display:flex;gap:.45rem;align-items:flex-start}
.stc-info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.6rem .875rem;font-size:.78rem;color:#1d4ed8;display:flex;gap:.45rem;align-items:flex-start}

/* ── Section label ── */
.stc-sec{font-size:.67rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#4f46e5;margin-bottom:.5rem}

/* ── Image preview ── */
.stc-img-preview{width:100%;max-h-44;object-fit:contain;display:block;border-radius:10px;border:1px solid #e5e7eb}

/* ── Mobile ── */
@media(max-width:1023px){
  .stc-code-pre{max-height:320px;font-size:11px}
}
@media(max-width:639px){
  .stc-drop{padding:2rem 1rem}
}
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">

{{-- ══ Hero ══ --}}
<div class="bg-white border-b border-gray-100">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
           style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <rect x="2" y="3" width="20" height="14" rx="2"/>
          <polyline points="8 21 12 17 16 21"/>
          <line x1="12" y1="17" x2="12" y2="21"/>
          <path d="M7 8h.01M11 8l4 3-4 3V8z" fill="white" stroke="none"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-0.5">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Screenshot to Code</h1>
          <span class="badge badge-primary">HTML + CSS</span>
          <span class="badge" style="background:#dcfce7;color:#15803d">Browser-Only</span>
        </div>
        <p class="text-gray-500 text-sm">Upload any screenshot — the tool extracts your exact color palette and generates a clean, structured HTML + CSS starter template.</p>
      </div>
    </div>
    <x-breadcrumb :items="[
      ['label' => 'Home',                'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => 'Screenshot to Code']
    ]"/>
  </div>
</div>

{{-- ══ Tool ══ --}}
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-6"
     x-data="stcGenerator()"
     x-init="init()"
     x-cloak>

  {{-- Error --}}
  <div x-show="errorMsg" x-transition
       class="alert alert-error mb-4 flex items-start gap-2">
    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span x-text="errorMsg"></span>
  </div>

  <div class="grid gap-5 lg:grid-cols-5">

    {{-- ════ Left: Upload + Options ════ --}}
    <div class="lg:col-span-2 space-y-4">

      {{-- Upload card --}}
      <div class="card p-5">
        <div class="stc-sec">Screenshot Input</div>

        {{-- Drop zone --}}
        <div x-show="!imgLoaded">
          <div class="stc-drop"
               :class="dragging ? 'over' : ''"
               @dragover.prevent="dragging=true"
               @dragleave.prevent="dragging=false"
               @drop.prevent="onDrop($event)"
               @click="$refs.fileInput.click()">
            <div class="stc-drop-icon">
              <svg width="28" height="28" fill="none" stroke="#6366f1" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <polyline points="8 21 12 17 16 21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
              </svg>
            </div>
            <p class="text-sm font-semibold text-gray-700">
              <span x-show="!dragging">Drop screenshot here</span>
              <span x-show="dragging" class="text-brand-600">Release to upload</span>
            </p>
            <p class="text-xs text-gray-400 mt-1">or <span class="text-brand-600 font-medium">click to browse</span></p>
            <div class="flex gap-1.5 justify-center mt-3 flex-wrap">
              <span class="badge badge-gray">JPG</span>
              <span class="badge badge-gray">PNG</span>
              <span class="badge badge-gray">WebP</span>
              <span class="badge badge-gray">GIF</span>
            </div>
            <p class="text-xs text-gray-400 mt-2">Max 20 MB · or paste with Ctrl+V</p>
          </div>

          <div class="stc-info mt-3">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>You can also paste a screenshot directly with <kbd class="px-1 py-0.5 text-xs bg-blue-50 border border-blue-200 rounded">Ctrl+V</kbd></span>
          </div>
        </div>

        {{-- Image loaded state --}}
        <div x-show="imgLoaded" class="space-y-3">
          <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
            <img :src="imgUrl" alt="Screenshot" class="w-full max-h-52 object-contain block">
          </div>
          <div class="flex items-center justify-between text-xs text-gray-500 px-0.5">
            <span class="truncate max-w-[140px]" x-text="fileName"></span>
            <span class="font-medium text-gray-600" x-text="imgWidth + ' × ' + imgHeight"></span>
          </div>
          <button class="btn btn-secondary btn-sm w-full" @click="reset()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
            Change Screenshot
          </button>
        </div>

        <input type="file" x-ref="fileInput" class="hidden"
               accept="image/jpeg,image/png,image/webp,image/gif"
               @change="onFileInput($event)">
      </div>

      {{-- Options (only when image loaded) --}}
      <div class="card p-5 space-y-4" x-show="imgLoaded">
        <div>
          <div class="stc-sec">Output Framework</div>
          <div class="flex gap-2">
            <button class="stc-fw-tab" :class="framework==='vanilla'?'active':''" @click="framework='vanilla'">
              Vanilla CSS
            </button>
            <button class="stc-fw-tab" :class="framework==='tailwind'?'active':''" @click="framework='tailwind'">
              Tailwind CSS
            </button>
          </div>
        </div>

        <div>
          <div class="stc-sec">Page Template</div>
          <select x-model="pageTemplate" class="form-input text-sm">
            <option value="landing">Landing Page</option>
            <option value="portfolio">Portfolio</option>
            <option value="dashboard">Dashboard / App</option>
            <option value="blog">Blog / Article</option>
          </select>
        </div>

        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-700">Include JavaScript</p>
            <p class="text-xs text-gray-400">Add basic interaction code</p>
          </div>
          <button @click="includeJs=!includeJs"
                  class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors flex-shrink-0"
                  :class="includeJs ? 'bg-brand-600' : 'bg-gray-300'">
            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform"
                  :class="includeJs ? 'translate-x-4' : 'translate-x-1'"></span>
          </button>
        </div>
      </div>

      {{-- Generate button --}}
      <div x-show="imgLoaded">
        <button class="btn btn-primary w-full btn-lg" @click="generate()" :disabled="processing">
          <template x-if="!processing">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </template>
          <template x-if="processing">
            <div class="stc-spin-sm"></div>
          </template>
          <span x-text="processing ? 'Generating…' : 'Generate Code'"></span>
        </button>
      </div>

      {{-- Processing state --}}
      <div class="card p-5" x-show="processing">
        <div class="flex flex-col items-center text-center gap-3">
          <div class="stc-spin"></div>
          <div>
            <p class="text-sm font-semibold text-gray-800" x-text="progressMsg"></p>
            <p class="text-xs text-gray-400 mt-0.5">Analyzing colors and layout…</p>
          </div>
          <div class="w-full">
            <div class="stc-progress-track">
              <div class="stc-progress-fill" :style="'width:'+progress+'%'"></div>
            </div>
            <p class="text-xs text-gray-400 text-right mt-1" x-text="progress+'%'"></p>
          </div>
        </div>
      </div>

      {{-- Color palette (after generation) --}}
      <div class="card p-5" x-show="palette.length > 0 && !processing">
        <div class="stc-sec">Extracted Palette</div>
        <div class="flex flex-wrap gap-2 mb-3">
          <template x-for="swatch in palette" :key="swatch.hex">
            <div class="stc-swatch" :style="'background:'+swatch.hex"
                 @click="copyHex(swatch.hex)" :title="swatch.hex + ' — ' + swatch.role">
              <span class="stc-swatch-tooltip" x-text="swatch.hex"></span>
            </div>
          </template>
        </div>
        <div class="space-y-1.5">
          <template x-for="swatch in palette" :key="'lbl-'+swatch.hex">
            <div class="flex items-center gap-2 text-xs">
              <div class="w-4 h-4 rounded flex-shrink-0" :style="'background:'+swatch.hex+';border:1px solid rgba(0,0,0,.1)'"></div>
              <span class="font-mono text-gray-600" x-text="swatch.hex"></span>
              <span class="text-gray-400" x-text="'— '+swatch.role"></span>
            </div>
          </template>
        </div>
        <p class="text-xs text-gray-400 mt-2">Click a swatch to copy its hex code</p>
      </div>

    </div>{{-- /left --}}

    {{-- ════ Right: Code Output ════ --}}
    <div class="lg:col-span-3 space-y-4">

      {{-- Idle placeholder --}}
      <div class="card" x-show="!generated && !processing">
        <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:22px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem">
            <svg width="36" height="36" fill="none" stroke="#6366f1" stroke-width="1.5" viewBox="0 0 24 24">
              <polyline points="16 18 22 12 16 6"/>
              <polyline points="8 6 2 12 8 18"/>
            </svg>
          </div>
          <p class="text-base font-semibold text-gray-600">Generated code will appear here</p>
          <p class="text-sm text-gray-400 mt-1 max-w-sm">Upload a screenshot on the left, choose your framework, and click <strong>Generate Code</strong>.</p>
          <div class="stc-tip mt-5 text-left max-w-sm">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Works great with website screenshots, Figma exports, mobile UI mockups, and design inspiration images.</span>
          </div>
        </div>
      </div>

      {{-- Generated code panel --}}
      <div x-show="generated && !processing" class="space-y-3">

        {{-- Tab bar + actions --}}
        <div class="card px-4 py-3">
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <div class="flex gap-1.5 flex-wrap">
              <button class="stc-tab" :class="activeTab==='combined'?'active':''" @click="activeTab='combined'">Combined</button>
              <button class="stc-tab" :class="activeTab==='html'?'active':''" @click="activeTab='html'">HTML</button>
              <button class="stc-tab" :class="activeTab==='css'?'active':''" @click="activeTab='css'">CSS</button>
              <button class="stc-tab" :class="activeTab==='preview'?'active':''" @click="activeTab='preview'">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="inline-block mr-0.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Preview
              </button>
              <template x-if="framework==='tailwind'">
                <button class="stc-tab" :class="activeTab==='tailwind'?'active':''" @click="activeTab='tailwind'">Tailwind</button>
              </template>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs text-gray-400" x-text="activeTab!=='preview' ? _lineCount(activeTab)+' lines' : ''"></span>
              <button class="btn btn-secondary btn-sm" @click="downloadHTML()">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download
              </button>
            </div>
          </div>
        </div>

        {{-- Combined tab --}}
        <div class="card overflow-hidden" x-show="activeTab==='combined'">
          <div class="stc-code-wrap">
            <pre class="stc-code-pre" x-ref="combinedPre" x-text="generated ? generated.combined : ''"></pre>
            <button class="stc-copy-btn" :class="copied.combined?'ok':''" @click="copyCode('combined')">
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
              <span x-text="copied.combined ? 'Copied!' : 'Copy'"></span>
            </button>
          </div>
        </div>

        {{-- HTML tab --}}
        <div class="card overflow-hidden" x-show="activeTab==='html'">
          <div class="stc-code-wrap">
            <pre class="stc-code-pre" x-text="generated ? generated.html : ''"></pre>
            <button class="stc-copy-btn" :class="copied.html?'ok':''" @click="copyCode('html')">
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
              <span x-text="copied.html ? 'Copied!' : 'Copy HTML'"></span>
            </button>
          </div>
        </div>

        {{-- CSS tab --}}
        <div class="card overflow-hidden" x-show="activeTab==='css'">
          <div class="stc-code-wrap">
            <pre class="stc-code-pre" x-text="generated ? generated.css : ''"></pre>
            <button class="stc-copy-btn" :class="copied.css?'ok':''" @click="copyCode('css')">
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
              <span x-text="copied.css ? 'Copied!' : 'Copy CSS'"></span>
            </button>
          </div>
        </div>

        {{-- Tailwind tab --}}
        <div class="card overflow-hidden" x-show="activeTab==='tailwind' && framework==='tailwind'">
          <div class="stc-code-wrap">
            <pre class="stc-code-pre" x-text="generated ? generated.combined : ''"></pre>
            <button class="stc-copy-btn" :class="copied.tailwind?'ok':''" @click="copyCode('tailwind')">
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
              <span x-text="copied.tailwind ? 'Copied!' : 'Copy'"></span>
            </button>
          </div>
        </div>

        {{-- Preview tab --}}
        <div class="card overflow-hidden" x-show="activeTab==='preview'">
          <div class="p-3 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Live Preview</span>
            <span class="text-xs text-gray-400">Rendered in a sandboxed iframe</span>
          </div>
          <iframe x-ref="previewFrame" class="stc-preview-frame" style="height:520px"
                  sandbox="allow-scripts" title="Code preview"></iframe>
        </div>

        {{-- Info note --}}
        <div class="stc-info text-xs">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Code uses your screenshot's <strong>exact color palette</strong>. Replace placeholder text and images to make it your own. All processing runs locally — nothing is uploaded.</span>
        </div>

      </div>{{-- /generated --}}

    </div>{{-- /right --}}

  </div>

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
      <h3 class="text-sm font-semibold text-gray-700 mb-3">How It Works</h3>
      <ol class="space-y-1.5 text-xs text-gray-600 list-decimal list-inside">
        <li>Upload or paste a screenshot (JPG, PNG, WebP)</li>
        <li>The tool extracts the dominant color palette</li>
        <li>Detects layout zones (navbar, hero, sections, footer)</li>
        <li>Generates a structured HTML + CSS template</li>
        <li>Copy or download and start customizing</li>
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

  <canvas x-ref="canvas" style="display:none"></canvas>
</div>
</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   Screenshot to Code — Alpine.js component   prefix: stc-
   Algorithm: Canvas k-means color extraction + layout zones
   + template-based HTML/CSS generation (no AI, no API)
   ═══════════════════════════════════════════════════════════ */

function stcGenerator() {
  return {
    /* ── upload ── */
    imgFile: null,
    imgUrl: null,
    imgLoaded: false,
    imgWidth: 0,
    imgHeight: 0,
    fileName: '',
    dragging: false,

    /* ── options ── */
    framework: 'vanilla',
    pageTemplate: 'landing',
    includeJs: false,

    /* ── processing ── */
    processing: false,
    progress: 0,
    progressMsg: 'Analysing image…',

    /* ── result ── */
    generated: null,
    palette: [],
    activeTab: 'combined',
    copied: {},

    /* ── error ── */
    errorMsg: '',

    /* ══ Lifecycle ══ */
    init: function() {
      var vm = this;
      /* Paste support */
      window.addEventListener('paste', function(e) {
        var items = (e.clipboardData || window.clipboardData).items;
        for (var i = 0; i < items.length; i++) {
          if (items[i].type.indexOf('image') !== -1) {
            vm._loadFile(items[i].getAsFile());
            break;
          }
        }
      });
    },

    /* ══ File handling ══ */
    onFileInput: function(e) {
      if (e.target.files[0]) this._loadFile(e.target.files[0]);
    },

    onDrop: function(e) {
      this.dragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this._loadFile(f);
    },

    _loadFile: function(file) {
      this.errorMsg = '';
      var allowed = ['image/jpeg','image/png','image/webp','image/gif'];
      if (!allowed.includes(file.type)) {
        this.errorMsg = 'Please upload a JPG, PNG, WebP, or GIF image.';
        return;
      }
      if (file.size > 20 * 1024 * 1024) {
        this.errorMsg = 'File exceeds 20 MB limit.';
        return;
      }
      var vm = this;
      var reader = new FileReader();
      reader.onload = function(ev) {
        var img = new Image();
        img.onload = function() {
          vm.imgWidth  = img.naturalWidth;
          vm.imgHeight = img.naturalHeight;
          vm.imgUrl    = ev.target.result;
          vm.imgLoaded = true;
          vm.fileName  = file.name || 'screenshot';
          vm.generated = null;
          vm.palette   = [];
        };
        img.onerror = function() { vm.errorMsg = 'Could not read image.'; };
        img.src = ev.target.result;
      };
      reader.readAsDataURL(file);
    },

    reset: function() {
      this.imgFile = null; this.imgUrl = null; this.imgLoaded = false;
      this.imgWidth = 0; this.imgHeight = 0; this.fileName = '';
      this.generated = null; this.palette = []; this.errorMsg = '';
      this.progress = 0; this.copied = {};
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    /* ══ Main generation pipeline ══ */
    generate: async function() {
      if (!this.imgLoaded || this.processing) return;
      this.processing = true;
      this.progress   = 0;
      this.errorMsg   = '';
      this.generated  = null;

      try {
        /* Step 1 – draw image to canvas */
        this.progressMsg = 'Loading image…';
        this.progress = 8;
        await this._tick();

        var img = await this._loadImg(this.imgUrl);
        var canvas = this.$refs.canvas;
        canvas.width  = img.naturalWidth;
        canvas.height = img.naturalHeight;
        var ctx = canvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(img, 0, 0);

        /* Step 2 – color extraction */
        this.progressMsg = 'Extracting colors…';
        this.progress = 22;
        await this._tick();

        var clusters = this._extractColors(ctx, img.naturalWidth, img.naturalHeight);

        /* Step 3 – section detection */
        this.progressMsg = 'Detecting layout zones…';
        this.progress = 50;
        await this._tick();

        var zones = this._detectZones(ctx, img.naturalWidth, img.naturalHeight);

        /* Step 4 – build color scheme */
        this.progressMsg = 'Building color scheme…';
        this.progress = 68;
        await this._tick();

        var colors = this._buildScheme(clusters, zones, img.naturalWidth, img.naturalHeight, ctx);
        this.palette = colors.palette;

        /* Step 5 – generate code */
        this.progressMsg = 'Generating code…';
        this.progress = 82;
        await this._tick();

        var result = this.framework === 'tailwind'
          ? this._genTailwind(colors)
          : this._genVanilla(colors);

        this.progress = 98;
        await this._tick();

        this.generated  = result;
        this.activeTab  = 'combined';
        this.progress   = 100;

        /* Populate preview iframe */
        var vm = this;
        vm.$nextTick(function() {
          if (vm.$refs.previewFrame) {
            vm.$refs.previewFrame.srcdoc = result.combined;
          }
        });

      } catch (err) {
        this.errorMsg = err.message || 'Generation failed. Please try again.';
      } finally {
        this.processing = false;
      }
    },

    /* ══ Color extraction — k-means k=8 ══ */
    _extractColors: function(ctx, w, h) {
      /* Sample pixels on a grid (max ~8000 samples) */
      var step = Math.max(1, Math.floor(Math.sqrt((w * h) / 8000)));
      var pixels = [];
      for (var y = 0; y < h; y += step) {
        for (var x = 0; x < w; x += step) {
          var d = ctx.getImageData(x, y, 1, 1).data;
          if (d[3] > 30) pixels.push([d[0], d[1], d[2]]);
        }
      }

      /* k-means k=8, 18 iterations */
      var k = 8, iters = 18;
      var step2 = Math.max(1, Math.floor(pixels.length / k));
      var centroids = [];
      for (var i = 0; i < k; i++) {
        var idx = Math.min(i * step2, pixels.length - 1);
        centroids.push(pixels[idx].slice());
      }

      var assignments = new Array(pixels.length).fill(0);
      for (var it = 0; it < iters; it++) {
        var changed = false;
        for (var p = 0; p < pixels.length; p++) {
          var best = 0, bestD = Infinity;
          for (var c = 0; c < k; c++) {
            var dr = pixels[p][0]-centroids[c][0], dg = pixels[p][1]-centroids[c][1], db = pixels[p][2]-centroids[c][2];
            var d2 = dr*dr + dg*dg + db*db;
            if (d2 < bestD) { bestD = d2; best = c; }
          }
          if (assignments[p] !== best) { assignments[p] = best; changed = true; }
        }
        if (!changed) break;
        var sums = Array.from({length:k}, function(){ return [0,0,0]; });
        var cnts = new Array(k).fill(0);
        for (var p = 0; p < pixels.length; p++) {
          var c = assignments[p];
          sums[c][0] += pixels[p][0]; sums[c][1] += pixels[p][1]; sums[c][2] += pixels[p][2]; cnts[c]++;
        }
        for (var c = 0; c < k; c++) {
          if (cnts[c] > 0) { centroids[c] = [sums[c][0]/cnts[c], sums[c][1]/cnts[c], sums[c][2]/cnts[c]]; }
        }
      }

      var result = centroids.map(function(c, i) {
        return { r: Math.round(c[0]), g: Math.round(c[1]), b: Math.round(c[2]),
                 count: assignments.filter(function(a){ return a===i; }).length };
      });
      return result.sort(function(a,b){ return b.count - a.count; });
    },

    /* ══ Zone detection ══ */
    _detectZones: function(ctx, w, h) {
      var bands = 20;
      var zones = [];
      for (var i = 0; i < bands; i++) {
        var y0 = Math.floor(i * h / bands);
        var y1 = Math.floor((i + 1) * h / bands);
        var bh = Math.max(1, y1 - y0);
        var data = ctx.getImageData(0, y0, w, bh).data;
        var r=0,g=0,b=0,n=0;
        for (var j = 0; j < data.length; j += 4) {
          if (data[j+3] > 30) { r+=data[j]; g+=data[j+1]; b+=data[j+2]; n++; }
        }
        if (n > 0) { r=r/n; g=g/n; b=b/n; }
        zones.push({ band: i, r: Math.round(r), g: Math.round(g), b: Math.round(b), frac: i/bands });
      }
      return zones;
    },

    /* ══ Color scheme builder ══ */
    _buildScheme: function(clusters, zones, w, h, ctx) {
      var vm = this;

      /* Helpers */
      function lum(r,g,b){ return 0.299*r + 0.587*g + 0.114*b; }
      function sat(r,g,b){ var max=Math.max(r,g,b)/255, min=Math.min(r,g,b)/255; var l=(max+min)/2; if(max===min) return 0; var d=max-min; return l>0.5 ? d/(2-max-min) : d/(max+min); }
      function toHex(r,g,b){ return '#'+[r,g,b].map(function(v){ return ('0'+Math.round(v).toString(16)).slice(-2); }).join(''); }
      function contrastColor(r,g,b){ return lum(r,g,b) > 128 ? '#1f2937' : '#ffffff'; }

      /* Average luminance */
      var totalPx = clusters.reduce(function(s,c){ return s+c.count; }, 0);
      var avgLum = clusters.reduce(function(s,c){ return s + lum(c.r,c.g,c.b)*c.count; }, 0) / totalPx;
      var isDark = avgLum < 100;

      /* Tag clusters */
      var tagged = clusters.map(function(c) {
        return { r:c.r, g:c.g, b:c.b, count:c.count, hex:toHex(c.r,c.g,c.b),
                 lum: lum(c.r,c.g,c.b), sat: sat(c.r,c.g,c.b) };
      });

      /* Background = most frequent */
      var bg = tagged[0];

      /* Text = best contrast vs bg */
      var textCands = tagged.filter(function(c){ return Math.abs(c.lum - bg.lum) > 80; });
      var text = textCands.length ? textCands[0] : { r:isDark?240:30, g:isDark?240:30, b:isDark?240:30, hex:isDark?'#f0f0f0':'#1f2937', lum:isDark?240:30, sat:0 };

      /* Primary = most saturated non-neutral */
      var colorful = tagged.filter(function(c){ return c.sat > 0.15 && Math.abs(c.r-c.g)>20 || Math.abs(c.g-c.b)>20 || Math.abs(c.r-c.b)>20; });
      colorful.sort(function(a,b){ return b.sat - a.sat; });
      var primary   = colorful[0] || { r:79, g:70, b:229, hex:'#4f46e5', lum:80, sat:0.8 };
      var secondary = colorful[1] || { r:124, g:58, b:237, hex:'#7c3aed', lum:80, sat:0.7 };

      /* Navbar color = average of top 8% of image */
      var navZones = zones.slice(0, Math.max(1, Math.floor(zones.length*0.15)));
      var nr=0,ng2=0,nb2=0;
      navZones.forEach(function(z){ nr+=z.r; ng2+=z.g; nb2+=z.b; });
      nr=Math.round(nr/navZones.length); ng2=Math.round(ng2/navZones.length); nb2=Math.round(nb2/navZones.length);
      var navBg = toHex(nr, ng2, nb2);
      var navText = contrastColor(nr, ng2, nb2);

      /* Footer = bottom 10% */
      var ftrZones = zones.slice(-Math.max(1, Math.floor(zones.length*0.1)));
      var fr=0,fg2=0,fb2=0;
      ftrZones.forEach(function(z){ fr+=z.r; fg2+=z.g; fb2+=z.b; });
      fr=Math.round(fr/ftrZones.length); fg2=Math.round(fg2/ftrZones.length); fb2=Math.round(fb2/ftrZones.length);
      var footerBg = toHex(fr, fg2, fb2);
      var footerText = contrastColor(fr, fg2, fb2);

      /* Surface = slightly different from bg */
      var surfR = isDark ? Math.min(255,bg.r+20) : Math.max(0,bg.r-8);
      var surfG = isDark ? Math.min(255,bg.g+20) : Math.max(0,bg.g-8);
      var surfB = isDark ? Math.min(255,bg.b+20) : Math.max(0,bg.b-8);

      /* Build palette for display */
      var roles = ['Background','Primary','Secondary','Text','Navbar','Footer','Surface','Accent'];
      var palColors = [
        {hex: bg.hex,         role: 'Background'},
        {hex: primary.hex,    role: 'Primary'},
        {hex: secondary.hex,  role: 'Secondary'},
        {hex: text.hex,       role: 'Text'},
        {hex: navBg,          role: 'Navbar'},
        {hex: footerBg,       role: 'Footer'},
        {hex: toHex(surfR,surfG,surfB), role: 'Surface'},
      ];
      /* deduplicate */
      var seen = new Set();
      palColors = palColors.filter(function(p){ if(seen.has(p.hex)) return false; seen.add(p.hex); return true; });

      return {
        bg:        bg.hex,
        bgContrast:contrastColor(bg.r,bg.g,bg.b),
        text:      text.hex,
        primary:   primary.hex,
        primaryContrast: contrastColor(primary.r,primary.g,primary.b),
        secondary: secondary.hex,
        secondaryContrast: contrastColor(secondary.r,secondary.g,secondary.b),
        surface:   toHex(surfR,surfG,surfB),
        border:    isDark ? toHex(Math.min(255,bg.r+30),Math.min(255,bg.g+30),Math.min(255,bg.b+30)) : toHex(Math.max(0,bg.r-20),Math.max(0,bg.g-20),Math.max(0,bg.b-20)),
        muted:     isDark ? '#9ca3af' : '#6b7280',
        navBg:     navBg,
        navText:   navText,
        footerBg:  footerBg,
        footerText:footerText,
        isDark:    isDark,
        palette:   palColors,
        year:      new Date().getFullYear(),
      };
    },

    /* ══ Vanilla CSS generation ══ */
    _genVanilla: function(c) {
      var vm   = this;
      var tmpl = vm.pageTemplate;
      var year = c.year;

      var css = '/* ════════════════════════════════════════════════\n'
              + '   Generated by Screenshot to Code — JEDISEBITOOL\n'
              + '   Colors extracted from your screenshot\n'
              + '   ════════════════════════════════════════════════ */\n\n'
              + ':root {\n'
              + '  --bg:         ' + c.bg        + ';\n'
              + '  --surface:    ' + c.surface    + ';\n'
              + '  --text:       ' + c.text       + ';\n'
              + '  --muted:      ' + c.muted      + ';\n'
              + '  --border:     ' + c.border     + ';\n'
              + '  --primary:    ' + c.primary    + ';\n'
              + '  --primary-fg: ' + c.primaryContrast + ';\n'
              + '  --secondary:  ' + c.secondary  + ';\n'
              + '  --nav-bg:     ' + c.navBg      + ';\n'
              + '  --nav-text:   ' + c.navText    + ';\n'
              + '  --footer-bg:  ' + c.footerBg   + ';\n'
              + '  --footer-text:' + c.footerText + ';\n'
              + '}\n\n'
              + '*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }\n'
              + 'html { scroll-behavior: smooth; }\n'
              + 'body { font-family: system-ui, -apple-system, \'Segoe UI\', sans-serif;\n'
              + '       background: var(--bg); color: var(--text); line-height: 1.6;\n'
              + '       -webkit-font-smoothing: antialiased; }\n'
              + 'a { color: inherit; text-decoration: none; }\n'
              + 'img { max-width: 100%; display: block; }\n\n'
              + '.container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }\n\n'
              + '/* Buttons */\n'
              + '.btn { display: inline-flex; align-items: center; gap: .5rem; padding: .625rem 1.5rem;\n'
              + '       border-radius: 8px; font-weight: 600; font-size: .9375rem; cursor: pointer;\n'
              + '       border: 2px solid transparent; transition: all .2s; }\n'
              + '.btn-primary { background: var(--primary); color: var(--primary-fg); }\n'
              + '.btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }\n'
              + '.btn-outline { background: transparent; color: #fff; border-color: rgba(255,255,255,.5); }\n'
              + '.btn-outline:hover { background: rgba(255,255,255,.1); border-color: #fff; }\n'
              + '.btn-white { background: #fff; color: var(--primary); }\n'
              + '.btn-white:hover { box-shadow: 0 4px 16px rgba(0,0,0,.15); }\n'
              + '.btn-lg { padding: .875rem 2rem; font-size: 1rem; }\n\n'
              + '/* Navbar */\n'
              + '.navbar { background: var(--nav-bg); padding: 1rem 0; position: sticky; top: 0; z-index: 100;\n'
              + '          box-shadow: 0 1px 8px rgba(0,0,0,.12); }\n'
              + '.navbar .container { display: flex; align-items: center; gap: 2rem; }\n'
              + '.navbar-brand { font-size: 1.25rem; font-weight: 800; color: var(--nav-text); flex-shrink: 0; }\n'
              + '.nav-menu { display: flex; gap: 2rem; list-style: none; flex: 1; }\n'
              + '.nav-menu a { color: var(--nav-text); opacity: .8; font-weight: 500; transition: opacity .15s; }\n'
              + '.nav-menu a:hover { opacity: 1; }\n\n'
              + '/* Hero */\n'
              + '.hero { background: linear-gradient(135deg, var(--primary), var(--secondary));\n'
              + '        padding: 6rem 0; color: #fff; text-align: center; }\n'
              + '.hero-content { max-width: 700px; margin: 0 auto; }\n'
              + '.hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800;\n'
              + '           line-height: 1.15; margin-bottom: 1.25rem; }\n'
              + '.hero p { font-size: 1.125rem; opacity: .85; margin-bottom: 2.5rem;\n'
              + '          max-width: 560px; margin-left: auto; margin-right: auto; }\n'
              + '.hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }\n\n'
              + '/* Features */\n'
              + '.features { padding: 5rem 0; background: var(--bg); }\n'
              + '.section-hd { text-align: center; margin-bottom: 3.5rem; }\n'
              + '.section-hd h2 { font-size: 2rem; font-weight: 800; margin-bottom: .75rem; }\n'
              + '.section-hd p { color: var(--muted); font-size: 1.0625rem; }\n'
              + '.features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }\n'
              + '.feature-card { background: var(--surface); border: 1px solid var(--border);\n'
              + '                border-radius: 16px; padding: 2rem 1.5rem;\n'
              + '                transition: box-shadow .2s, transform .2s; }\n'
              + '.feature-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-3px); }\n'
              + '.feature-icon { font-size: 2.25rem; margin-bottom: 1rem; }\n'
              + '.feature-card h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; }\n'
              + '.feature-card p { color: var(--muted); font-size: .9375rem; line-height: 1.65; }\n\n'
              + '/* Stats */\n'
              + '.stats { background: var(--surface); border-top: 1px solid var(--border);\n'
              + '         border-bottom: 1px solid var(--border); padding: 4rem 0; }\n'
              + '.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr);\n'
              + '              gap: 2rem; text-align: center; }\n'
              + '.stat-num { font-size: 2.5rem; font-weight: 800; color: var(--primary); line-height: 1; margin-bottom: .5rem; }\n'
              + '.stat-label { color: var(--muted); font-size: .875rem; font-weight: 500; }\n\n'
              + '/* CTA */\n'
              + '.cta { background: linear-gradient(135deg, var(--secondary), var(--primary));\n'
              + '       padding: 5rem 0; text-align: center; color: #fff; }\n'
              + '.cta h2 { font-size: 2.25rem; font-weight: 800; margin-bottom: 1rem; }\n'
              + '.cta p { font-size: 1.0625rem; opacity: .85; margin-bottom: 2.5rem; }\n\n'
              + '/* Footer */\n'
              + '.footer { background: var(--footer-bg); color: var(--footer-text); padding: 4rem 0 2rem; }\n'
              + '.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem; margin-bottom: 3rem; }\n'
              + '.footer-brand h3 { color: var(--footer-text); font-size: 1.25rem; font-weight: 700; margin-bottom: .75rem; }\n'
              + '.footer-brand p { font-size: .9375rem; opacity: .75; line-height: 1.65; }\n'
              + '.footer-col h4 { color: var(--footer-text); font-size: .8125rem; font-weight: 700;\n'
              + '                 text-transform: uppercase; letter-spacing: .06em; margin-bottom: 1rem; }\n'
              + '.footer-col ul { list-style: none; }\n'
              + '.footer-col li { margin-bottom: .5rem; }\n'
              + '.footer-col a { opacity: .7; font-size: .9375rem; transition: opacity .15s; }\n'
              + '.footer-col a:hover { opacity: 1; }\n'
              + '.footer-bottom { border-top: 1px solid rgba(128,128,128,.2); padding-top: 1.5rem;\n'
              + '                 text-align: center; font-size: .875rem; opacity: .65; }\n\n'
              + '/* Responsive */\n'
              + '@media (max-width: 1024px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }\n'
              + '@media (max-width: 768px) {\n'
              + '  .navbar .container { flex-wrap: wrap; }\n'
              + '  .nav-menu { display: none; }\n'
              + '  .features-grid { grid-template-columns: 1fr; }\n'
              + '  .stats-grid { grid-template-columns: repeat(2, 1fr); }\n'
              + '  .footer-grid { grid-template-columns: 1fr; gap: 2rem; }\n'
              + '}\n'
              + '@media (max-width: 480px) {\n'
              + '  .hero-btns { flex-direction: column; align-items: center; }\n'
              + '  .stats-grid { grid-template-columns: 1fr 1fr; }\n'
              + '}\n';

      var js = vm.includeJs
        ? '\n<script>\n'
          + '// Mobile nav toggle\n'
          + 'document.querySelector(".navbar-toggle")?.addEventListener("click", function() {\n'
          + '  document.querySelector(".nav-menu").classList.toggle("open");\n'
          + '});\n'
          + '// Smooth scroll for anchor links\n'
          + 'document.querySelectorAll(\'a[href^="#"]\').forEach(function(a) {\n'
          + '  a.addEventListener("click", function(e) {\n'
          + '    var target = document.querySelector(this.getAttribute("href"));\n'
          + '    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: "smooth" }); }\n'
          + '  });\n'
          + '});\n'
          + '<\/script>\n'
        : '';

      var html = '<!DOCTYPE html>\n'
               + '<html lang="en">\n'
               + '<head>\n'
               + '  <meta charset="UTF-8">\n'
               + '  <meta name="viewport" content="width=device-width, initial-scale=1.0">\n'
               + '  <title>My Website</title>\n'
               + '  <link rel="stylesheet" href="style.css">\n'
               + '</head>\n'
               + '<body>\n\n'
               + '  <!-- Navigation -->\n'
               + '  <header>\n'
               + '    <nav class="navbar">\n'
               + '      <div class="container">\n'
               + '        <a href="#" class="navbar-brand">YourBrand</a>\n'
               + '        <ul class="nav-menu">\n'
               + '          <li><a href="#">Home</a></li>\n'
               + '          <li><a href="#features">Features</a></li>\n'
               + '          <li><a href="#about">About</a></li>\n'
               + '          <li><a href="#contact">Contact</a></li>\n'
               + '        </ul>\n'
               + '        <a href="#" class="btn btn-primary">Get Started</a>\n'
               + '      </div>\n'
               + '    </nav>\n'
               + '  </header>\n\n'
               + '  <main>\n\n'
               + '    <!-- Hero Section -->\n'
               + '    <section class="hero">\n'
               + '      <div class="hero-content">\n'
               + '        <h1>Build Something Amazing</h1>\n'
               + '        <p>A compelling description of your product or service that resonates with your audience and drives action.</p>\n'
               + '        <div class="hero-btns">\n'
               + '          <a href="#" class="btn btn-white btn-lg">Start Free Trial</a>\n'
               + '          <a href="#features" class="btn btn-outline btn-lg">Learn More</a>\n'
               + '        </div>\n'
               + '      </div>\n'
               + '    </section>\n\n'
               + '    <!-- Features Section -->\n'
               + '    <section class="features" id="features">\n'
               + '      <div class="container">\n'
               + '        <div class="section-hd">\n'
               + '          <h2>Everything You Need</h2>\n'
               + '          <p>Powerful tools designed to accelerate your workflow</p>\n'
               + '        </div>\n'
               + '        <div class="features-grid">\n'
               + '          <div class="feature-card">\n'
               + '            <div class="feature-icon">✨</div>\n'
               + '            <h3>Feature One</h3>\n'
               + '            <p>Describe how this feature helps your users achieve their goals faster and more efficiently.</p>\n'
               + '          </div>\n'
               + '          <div class="feature-card">\n'
               + '            <div class="feature-icon">⚡</div>\n'
               + '            <h3>Feature Two</h3>\n'
               + '            <p>Describe how this feature helps your users achieve their goals faster and more efficiently.</p>\n'
               + '          </div>\n'
               + '          <div class="feature-card">\n'
               + '            <div class="feature-icon">🎯</div>\n'
               + '            <h3>Feature Three</h3>\n'
               + '            <p>Describe how this feature helps your users achieve their goals faster and more efficiently.</p>\n'
               + '          </div>\n'
               + '        </div>\n'
               + '      </div>\n'
               + '    </section>\n\n'
               + '    <!-- Stats Section -->\n'
               + '    <section class="stats">\n'
               + '      <div class="container">\n'
               + '        <div class="stats-grid">\n'
               + '          <div><div class="stat-num">10K+</div><div class="stat-label">Happy Customers</div></div>\n'
               + '          <div><div class="stat-num">99%</div><div class="stat-label">Satisfaction Rate</div></div>\n'
               + '          <div><div class="stat-num">24/7</div><div class="stat-label">Support Available</div></div>\n'
               + '          <div><div class="stat-num">50+</div><div class="stat-label">Integrations</div></div>\n'
               + '        </div>\n'
               + '      </div>\n'
               + '    </section>\n\n'
               + '    <!-- CTA Section -->\n'
               + '    <section class="cta">\n'
               + '      <div class="container">\n'
               + '        <h2>Ready to Get Started?</h2>\n'
               + '        <p>Join thousands of businesses already using our platform.</p>\n'
               + '        <a href="#" class="btn btn-white btn-lg">Start Your Free Trial</a>\n'
               + '      </div>\n'
               + '    </section>\n\n'
               + '  </main>\n\n'
               + '  <!-- Footer -->\n'
               + '  <footer class="footer">\n'
               + '    <div class="container">\n'
               + '      <div class="footer-grid">\n'
               + '        <div class="footer-brand">\n'
               + '          <h3>YourBrand</h3>\n'
               + '          <p>Building the future, one product at a time.</p>\n'
               + '        </div>\n'
               + '        <div class="footer-col"><h4>Product</h4><ul><li><a href="#">Features</a></li><li><a href="#">Pricing</a></li><li><a href="#">Changelog</a></li></ul></div>\n'
               + '        <div class="footer-col"><h4>Company</h4><ul><li><a href="#">About</a></li><li><a href="#">Blog</a></li><li><a href="#">Careers</a></li></ul></div>\n'
               + '        <div class="footer-col"><h4>Support</h4><ul><li><a href="#">Docs</a></li><li><a href="#">Help Center</a></li><li><a href="#">Contact</a></li></ul></div>\n'
               + '      </div>\n'
               + '      <div class="footer-bottom"><p>© ' + year + ' YourBrand. All rights reserved.</p></div>\n'
               + '    </div>\n'
               + '  </footer>\n\n'
               + js
               + '</body>\n'
               + '</html>';

      var combined = '<!DOCTYPE html>\n'
                   + '<html lang="en">\n'
                   + '<head>\n'
                   + '  <meta charset="UTF-8">\n'
                   + '  <meta name="viewport" content="width=device-width, initial-scale=1.0">\n'
                   + '  <title>My Website</title>\n'
                   + '  <style>\n'
                   + css.split('\n').map(function(l){ return '    '+l; }).join('\n')
                   + '\n  </style>\n'
                   + '</head>\n'
                   + html.replace('<!DOCTYPE html>\n<html lang="en">\n<head>\n  <meta charset="UTF-8">\n  <meta name="viewport" content="width=device-width, initial-scale=1.0">\n  <title>My Website</title>\n  <link rel="stylesheet" href="style.css">\n</head>\n', '');

      return { html: html, css: css, combined: combined };
    },

    /* ══ Tailwind CSS generation ══ */
    _genTailwind: function(c) {
      var vm  = this;
      var year = c.year;
      var js = vm.includeJs
        ? '\n<script>\n'
          + 'document.querySelectorAll(\'a[href^="#"]\').forEach(a => {\n'
          + '  a.addEventListener("click", e => {\n'
          + '    const t = document.querySelector(a.getAttribute("href"));\n'
          + '    if (t) { e.preventDefault(); t.scrollIntoView({ behavior: "smooth" }); }\n'
          + '  });\n'
          + '});\n'
          + '<\/script>\n'
        : '';

      var combined = '<!DOCTYPE html>\n'
        + '<html lang="en">\n'
        + '<head>\n'
        + '  <meta charset="UTF-8">\n'
        + '  <meta name="viewport" content="width=device-width, initial-scale=1.0">\n'
        + '  <title>My Website</title>\n'
        + '  <script src="https://cdn.tailwindcss.com"><\/script>\n'
        + '  <script>tailwind.config={theme:{extend:{colors:{\n'
        + '    primary:"' + c.primary + '",\n'
        + '    secondary:"' + c.secondary + '",\n'
        + '  }}}}<\/script>\n'
        + '  <style>\n'
        + '    :root { --nav-bg: ' + c.navBg + '; --footer-bg: ' + c.footerBg + '; }\n'
        + '    .nav-bg   { background-color: var(--nav-bg); }\n'
        + '    .footer-bg{ background-color: var(--footer-bg); }\n'
        + '  </style>\n'
        + '</head>\n'
        + '<body class="antialiased" style="background:' + c.bg + ';color:' + c.text + '">\n\n'
        + '  <!-- Navigation -->\n'
        + '  <header>\n'
        + '    <nav class="nav-bg sticky top-0 z-50 shadow-sm">\n'
        + '      <div class="max-w-6xl mx-auto px-6 py-4 flex items-center gap-8">\n'
        + '        <a href="#" class="text-xl font-extrabold" style="color:' + c.navText + '">YourBrand</a>\n'
        + '        <ul class="hidden md:flex gap-8 list-none flex-1">\n'
        + '          <li><a href="#" class="font-medium" style="color:' + c.navText + ';opacity:.8">Home</a></li>\n'
        + '          <li><a href="#features" class="font-medium" style="color:' + c.navText + ';opacity:.8">Features</a></li>\n'
        + '          <li><a href="#about" class="font-medium" style="color:' + c.navText + ';opacity:.8">About</a></li>\n'
        + '          <li><a href="#contact" class="font-medium" style="color:' + c.navText + ';opacity:.8">Contact</a></li>\n'
        + '        </ul>\n'
        + '        <a href="#" class="ml-auto px-5 py-2.5 rounded-lg font-semibold text-sm transition hover:brightness-110"\n'
        + '           style="background:' + c.primary + ';color:' + c.primaryContrast + '">Get Started</a>\n'
        + '      </div>\n'
        + '    </nav>\n'
        + '  </header>\n\n'
        + '  <main>\n\n'
        + '    <!-- Hero -->\n'
        + '    <section class="py-24 text-center text-white"\n'
        + '             style="background:linear-gradient(135deg,' + c.primary + ',' + c.secondary + ')">\n'
        + '      <div class="max-w-3xl mx-auto px-6">\n'
        + '        <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-5">Build Something Amazing</h1>\n'
        + '        <p class="text-lg sm:text-xl opacity-85 mb-10 max-w-xl mx-auto">A compelling description of your product that resonates with your audience.</p>\n'
        + '        <div class="flex flex-wrap gap-4 justify-center">\n'
        + '          <a href="#" class="px-8 py-3.5 bg-white font-bold rounded-xl text-base hover:shadow-xl transition"\n'
        + '             style="color:' + c.primary + '">Start Free Trial</a>\n'
        + '          <a href="#features" class="px-8 py-3.5 font-bold rounded-xl text-base border-2 border-white/50 text-white hover:bg-white/10 transition">Learn More</a>\n'
        + '        </div>\n'
        + '      </div>\n'
        + '    </section>\n\n'
        + '    <!-- Features -->\n'
        + '    <section class="py-20" id="features">\n'
        + '      <div class="max-w-6xl mx-auto px-6">\n'
        + '        <div class="text-center mb-14">\n'
        + '          <h2 class="text-4xl font-extrabold mb-3">Everything You Need</h2>\n'
        + '          <p class="text-lg" style="color:' + c.muted + '">Powerful tools designed to accelerate your workflow</p>\n'
        + '        </div>\n'
        + '        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">\n'
        + '          <div class="rounded-2xl p-8 border transition hover:shadow-lg hover:-translate-y-1"\n'
        + '               style="background:' + c.surface + ';border-color:' + c.border + '">\n'
        + '            <div class="text-4xl mb-4">✨</div>\n'
        + '            <h3 class="text-xl font-bold mb-2">Feature One</h3>\n'
        + '            <p style="color:' + c.muted + '">Describe how this feature helps your users achieve their goals.</p>\n'
        + '          </div>\n'
        + '          <div class="rounded-2xl p-8 border transition hover:shadow-lg hover:-translate-y-1"\n'
        + '               style="background:' + c.surface + ';border-color:' + c.border + '">\n'
        + '            <div class="text-4xl mb-4">⚡</div>\n'
        + '            <h3 class="text-xl font-bold mb-2">Feature Two</h3>\n'
        + '            <p style="color:' + c.muted + '">Describe how this feature helps your users achieve their goals.</p>\n'
        + '          </div>\n'
        + '          <div class="rounded-2xl p-8 border transition hover:shadow-lg hover:-translate-y-1"\n'
        + '               style="background:' + c.surface + ';border-color:' + c.border + '">\n'
        + '            <div class="text-4xl mb-4">🎯</div>\n'
        + '            <h3 class="text-xl font-bold mb-2">Feature Three</h3>\n'
        + '            <p style="color:' + c.muted + '">Describe how this feature helps your users achieve their goals.</p>\n'
        + '          </div>\n'
        + '        </div>\n'
        + '      </div>\n'
        + '    </section>\n\n'
        + '    <!-- Stats -->\n'
        + '    <section class="py-16 border-y" style="background:' + c.surface + ';border-color:' + c.border + '">\n'
        + '      <div class="max-w-6xl mx-auto px-6">\n'
        + '        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">\n'
        + '          <div><div class="text-4xl font-extrabold mb-1" style="color:' + c.primary + '">10K+</div><div class="text-sm font-medium" style="color:' + c.muted + '">Happy Customers</div></div>\n'
        + '          <div><div class="text-4xl font-extrabold mb-1" style="color:' + c.primary + '">99%</div><div class="text-sm font-medium" style="color:' + c.muted + '">Satisfaction Rate</div></div>\n'
        + '          <div><div class="text-4xl font-extrabold mb-1" style="color:' + c.primary + '">24/7</div><div class="text-sm font-medium" style="color:' + c.muted + '">Support</div></div>\n'
        + '          <div><div class="text-4xl font-extrabold mb-1" style="color:' + c.primary + '">50+</div><div class="text-sm font-medium" style="color:' + c.muted + '">Integrations</div></div>\n'
        + '        </div>\n'
        + '      </div>\n'
        + '    </section>\n\n'
        + '    <!-- CTA -->\n'
        + '    <section class="py-20 text-center text-white"\n'
        + '             style="background:linear-gradient(135deg,' + c.secondary + ',' + c.primary + ')">\n'
        + '      <div class="max-w-2xl mx-auto px-6">\n'
        + '        <h2 class="text-4xl font-extrabold mb-4">Ready to Get Started?</h2>\n'
        + '        <p class="text-lg opacity-85 mb-10">Join thousands of businesses already using our platform.</p>\n'
        + '        <a href="#" class="px-10 py-4 bg-white font-bold rounded-xl text-lg hover:shadow-xl transition"\n'
        + '           style="color:' + c.primary + '">Start Your Free Trial</a>\n'
        + '      </div>\n'
        + '    </section>\n\n'
        + '  </main>\n\n'
        + '  <!-- Footer -->\n'
        + '  <footer class="footer-bg py-16 pb-8">\n'
        + '    <div class="max-w-6xl mx-auto px-6">\n'
        + '      <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">\n'
        + '        <div class="md:col-span-2">\n'
        + '          <h3 class="text-xl font-extrabold mb-3" style="color:' + c.footerText + '">YourBrand</h3>\n'
        + '          <p class="text-sm opacity-70" style="color:' + c.footerText + '">Building the future, one product at a time.</p>\n'
        + '        </div>\n'
        + '        <div>\n'
        + '          <h4 class="text-xs font-bold uppercase tracking-wider mb-4" style="color:' + c.footerText + '">Product</h4>\n'
        + '          <ul class="space-y-2 text-sm opacity-70" style="color:' + c.footerText + '">\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">Features</a></li>\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">Pricing</a></li>\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">Changelog</a></li>\n'
        + '          </ul>\n'
        + '        </div>\n'
        + '        <div>\n'
        + '          <h4 class="text-xs font-bold uppercase tracking-wider mb-4" style="color:' + c.footerText + '">Company</h4>\n'
        + '          <ul class="space-y-2 text-sm opacity-70" style="color:' + c.footerText + '">\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">About</a></li>\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">Blog</a></li>\n'
        + '            <li><a href="#" class="hover:opacity-100 transition">Contact</a></li>\n'
        + '          </ul>\n'
        + '        </div>\n'
        + '      </div>\n'
        + '      <div class="border-t pt-6 text-center text-sm opacity-50" style="border-color:rgba(128,128,128,.2);color:' + c.footerText + '">\n'
        + '        <p>© ' + year + ' YourBrand. All rights reserved.</p>\n'
        + '      </div>\n'
        + '    </div>\n'
        + '  </footer>\n\n'
        + js
        + '</body>\n'
        + '</html>';

      return { html: combined, css: '/* Tailwind CSS — styles are inline utilities above */', combined: combined };
    },

    /* ══ Helpers ══ */
    _loadImg: function(src) {
      return new Promise(function(resolve, reject) {
        var img = new Image();
        img.onload  = function(){ resolve(img); };
        img.onerror = function(){ reject(new Error('Failed to load image')); };
        img.src = src;
      });
    },

    _tick: function() {
      return new Promise(function(r){ setTimeout(r, 0); });
    },

    _lineCount: function(tab) {
      if (!this.generated) return 0;
      var code = tab === 'html' ? this.generated.html
               : tab === 'css'  ? this.generated.css
               : this.generated.combined;
      return (code || '').split('\n').length;
    },

    copyCode: function(tab) {
      var vm = this;
      if (!vm.generated) return;
      var code = tab === 'html' ? vm.generated.html
               : tab === 'css'  ? vm.generated.css
               : vm.generated.combined;
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(code).then(function() {
        vm.copied[tab] = true;
        setTimeout(function(){ vm.copied[tab] = false; }, 2000);
      });
    },

    copyHex: function(hex) {
      if (navigator.clipboard) navigator.clipboard.writeText(hex);
    },

    downloadHTML: function() {
      if (!this.generated) return;
      var blob = new Blob([this.generated.combined], { type: 'text/html' });
      var url  = URL.createObjectURL(blob);
      var a    = document.createElement('a');
      a.href   = url;
      a.download = 'generated.html';
      a.click();
      URL.revokeObjectURL(url);
    },
  };
}
</script>
@endpush
