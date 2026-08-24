@extends('layouts.public')

@section('title', 'Python Compiler — Run Python Code Online | JEDISEBITOOL')
@section('description', 'Free online Python compiler. Write and run Python 3 code instantly — real backend execution, live stdout/stderr output, stdin support, and syntax highlighting. No install needed.')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css" crossorigin="anonymous">
<style>
/* ══════════════════════════════════════════════════
   Python Compiler   prefix: pyc-
   ══════════════════════════════════════════════════ */

/* ── Toolbar ── */
.pyc-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;padding:.75rem 1rem;background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.pyc-sep{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}

/* ── Run button ── */
.pyc-run{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:1.5px solid #4338ca;border-radius:9px;padding:.42rem 1.15rem;font-size:.8125rem;font-weight:700;display:inline-flex;align-items:center;gap:.4rem;cursor:pointer;transition:all .15s;box-shadow:0 2px 8px rgba(79,70,229,.28);white-space:nowrap}
.pyc-run:hover:not(:disabled){background:linear-gradient(135deg,#4338ca,#6d28d9);box-shadow:0 4px 14px rgba(79,70,229,.38);transform:translateY(-1px)}
.pyc-run:disabled{opacity:.55;cursor:not-allowed;transform:none!important}
.pyc-run:active:not(:disabled){transform:scale(.97)!important}

/* ── Generic toolbar buttons ── */
.pyc-btn{border-radius:9px;padding:.38rem .75rem;font-size:.75rem;font-weight:500;display:inline-flex;align-items:center;gap:.35rem;cursor:pointer;border:1.5px solid #e5e7eb;background:#fff;color:#374151;transition:all .12s;white-space:nowrap}
.pyc-btn:hover{background:#f5f3ff;border-color:#c4b5fd;color:#4f46e5}
.pyc-btn.copied{border-color:#a7f3d0;color:#059669;background:#f0fdf4}

/* ── Templates dropdown ── */
.pyc-tmpl-wrap{position:relative}
.pyc-tmpl-menu{position:absolute;top:calc(100% + 6px);left:0;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 8px 28px rgba(0,0,0,.1);z-index:50;min-width:250px;overflow:hidden}
.pyc-tmpl-item{display:flex;align-items:flex-start;gap:.625rem;padding:.65rem 1rem;cursor:pointer;border-bottom:1px solid #f3f4f6;transition:background .1s}
.pyc-tmpl-item:last-child{border-bottom:none}
.pyc-tmpl-item:hover{background:#f5f3ff}
.pyc-tmpl-name{font-size:.78rem;font-weight:600;color:#1f2937}
.pyc-tmpl-desc{font-size:.68rem;color:#9ca3af;margin-top:.1rem}

/* ── Workspace ── */
.pyc-workspace{display:flex;flex-direction:row;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 2px 10px rgba(0,0,0,.07);overflow:hidden;min-height:500px}

/* ── Editor side ── */
.pyc-editor-side{display:flex;flex-direction:column;width:55%;min-width:240px;background:#282a36;flex-shrink:0}
.pyc-editor-header{display:flex;align-items:center;justify-content:space-between;padding:.45rem .875rem;background:#1e1f2e;border-bottom:1px solid #383a4e;flex-shrink:0}
.pyc-file-name{font-size:.7rem;font-weight:600;color:#6272a4;font-family:monospace;display:flex;align-items:center;gap:.375rem}
.pyc-lang-badge{background:#3d4251;color:#8be9fd;font-size:.6rem;font-weight:700;padding:.15rem .45rem;border-radius:4px;letter-spacing:.06em}
.pyc-hdr-actions{display:flex;align-items:center;gap:.25rem}
.pyc-hdr-btn{display:inline-flex;align-items:center;gap:.25rem;padding:.25rem .5rem;font-size:.68rem;font-weight:500;color:#6272a4;border-radius:5px;border:1px solid transparent;background:transparent;cursor:pointer;transition:all .12s}
.pyc-hdr-btn:hover{background:#383a4e;color:#cdd6f4;border-color:#4a4c5e}
.pyc-hdr-btn.active{background:#383a4e;color:#bd93f9}

/* ── CodeMirror container ── */
.pyc-cm-wrap{flex:1;overflow:hidden;min-height:0}
.pyc-cm-wrap .CodeMirror{height:100%;font-size:13.5px;font-family:'JetBrains Mono','Fira Code','Cascadia Code',Consolas,monospace;line-height:1.65}
.pyc-cm-wrap .CodeMirror-scroll{overflow:auto!important}

/* ── Fallback textarea ── */
.pyc-textarea{width:100%;height:100%;background:#282a36;color:#f8f8f2;font-family:'JetBrains Mono','Fira Code',Consolas,monospace;font-size:13px;line-height:1.65;border:none;outline:none;resize:none;padding:1rem;box-sizing:border-box;tab-size:4}

/* ── Stdin section ── */
.pyc-stdin-wrap{border-top:1px solid #383a4e;flex-shrink:0}
.pyc-stdin-toggle{display:flex;align-items:center;justify-content:space-between;padding:.35rem .875rem;cursor:pointer;user-select:none;background:#1e1f2e}
.pyc-stdin-label{font-size:.68rem;font-weight:600;color:#6272a4;letter-spacing:.06em;text-transform:uppercase;display:flex;align-items:center;gap:.375rem}
.pyc-stdin-has{background:#4f46e5;color:#fff;font-size:.55rem;padding:.1rem .4rem;border-radius:99px;font-weight:700}
.pyc-stdin-body{overflow:hidden}
.pyc-stdin-ta{width:100%;background:#1a1b26;color:#8be9fd;font-family:'JetBrains Mono','Fira Code',Consolas,monospace;font-size:.75rem;line-height:1.5;border:none;outline:none;resize:none;padding:.625rem .875rem;box-sizing:border-box;height:78px;border-top:1px solid #2d2f45;display:block}
.pyc-stdin-ta::placeholder{color:#44475a}

/* ── Drag divider ── */
.pyc-divider{width:5px;background:#1e1f2e;cursor:col-resize;flex-shrink:0;display:flex;align-items:center;justify-content:center;user-select:none;transition:background .15s}
.pyc-divider:hover,.pyc-divider.active{background:#4f46e5}
.pyc-divider-dots{display:flex;flex-direction:column;gap:3px}
.pyc-divider-dots span{width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,.2)}

/* ── Output side ── */
.pyc-output-side{flex:1;display:flex;flex-direction:column;background:#fafafa;min-width:200px;overflow:hidden}
.pyc-output-header{display:flex;align-items:center;justify-content:space-between;padding:.45rem .875rem;background:#fff;border-bottom:1px solid #e5e7eb;flex-shrink:0}
.pyc-output-title{font-size:.7rem;font-weight:700;color:#6b7280;letter-spacing:.07em;text-transform:uppercase;display:flex;align-items:center;gap:.5rem}

/* ── Status pill ── */
.pyc-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .55rem;border-radius:99px;font-size:.65rem;font-weight:700;letter-spacing:.03em}
.pyc-pill-idle{background:#f3f4f6;color:#9ca3af}
.pyc-pill-run{background:#ede9fe;color:#7c3aed}
.pyc-pill-ok{background:#dcfce7;color:#15803d}
.pyc-pill-err{background:#fee2e2;color:#dc2626}
.pyc-pill-timeout{background:#fef3c7;color:#d97706}

/* ── Output body ── */
.pyc-output-body{flex:1;overflow:auto;font-family:'JetBrains Mono','Fira Code',Consolas,monospace;font-size:12.5px;line-height:1.65;position:relative;min-height:0}
.pyc-output-body::-webkit-scrollbar{width:5px;height:5px}
.pyc-output-body::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}

/* Empty state */
.pyc-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:2rem;text-align:center;color:#d1d5db;gap:.75rem}

/* Output blocks */
.pyc-stdout{padding:.875rem 1rem;white-space:pre-wrap;word-break:break-all;color:#1f2937;background:#fff;border-bottom:1px solid #f3f4f6}
.pyc-stderr{padding:.875rem 1rem;white-space:pre-wrap;word-break:break-all;color:#dc2626;background:#fff8f8;border-top:1px solid #fee2e2;border-bottom:1px solid #fee2e2}
.pyc-stderr-lbl{font-size:.65rem;font-weight:700;color:#dc2626;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.35rem;display:flex;align-items:center;gap:.3rem}

/* Meta bar */
.pyc-meta{display:flex;align-items:center;gap:1rem;padding:.375rem .875rem;background:#fff;border-top:1px solid #f3f4f6;flex-shrink:0;font-size:.68rem;color:#9ca3af}
.pyc-meta-val{color:#4b5563;font-weight:600;font-variant-numeric:tabular-nums}

/* Status bar */
.pyc-statusbar{display:flex;align-items:center;justify-content:space-between;padding:.25rem .75rem;font-size:.68rem;color:#9ca3af;user-select:none}

/* Spinners */
.pyc-spin-w{width:14px;height:14px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:pyc-spin .7s linear infinite;flex-shrink:0}
.pyc-spin-p{width:12px;height:12px;border:2px solid rgba(124,58,237,.3);border-top-color:#7c3aed;border-radius:50%;animation:pyc-spin .7s linear infinite}
.pyc-spin-lg{width:36px;height:36px;border:3px solid #e0e7ff;border-top-color:#4f46e5;border-radius:50%;animation:pyc-spin .8s linear infinite}
@keyframes pyc-spin{to{transform:rotate(360deg)}}

/* Mobile */
@media(max-width:1023px){
  .pyc-workspace{flex-direction:column;min-height:auto}
  .pyc-editor-side{width:100%!important;min-height:280px;max-height:340px}
  .pyc-divider{display:none}
  .pyc-output-side{min-height:240px;border-top:1px solid #e5e7eb}
  .pyc-cm-wrap .CodeMirror{font-size:12px}
}
@media(max-width:639px){
  .pyc-editor-side{max-height:280px}
  .pyc-workspace{border-radius:12px}
  .pyc-toolbar{gap:.35rem}
}
</style>
@endsection

@section('renders_own_faqs', '1')
@section('content')
<div class="min-h-screen bg-gray-50">

{{-- ══ Hero ══ --}}
<div class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-4">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0"
           style="background:linear-gradient(135deg,#ede9fe,#dbeafe)">
        🐍
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Python Compiler</h1>
          <span class="badge badge-primary">Python 3</span>
          <span class="badge" style="background:#dcfce7;color:#15803d">Real Execution</span>
        </div>
        <p class="text-gray-500 text-sm">Write Python 3 code and run it instantly — real backend execution, live stdout/stderr, and stdin support.</p>
      </div>
    </div>
    <x-breadcrumb :items="[
      ['label' => 'Home', 'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => 'Python Compiler']
    ]"/>
  </div>
</div>

{{-- ══ Compiler ══ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="pycCompiler()"
     x-init="init()"
     x-cloak>

  {{-- ── Toolbar ── --}}
  <div class="pyc-toolbar mb-3">

    {{-- Run --}}
    <button class="pyc-run" @click="run()" :disabled="running">
      <template x-if="!running">
        <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      </template>
      <template x-if="running">
        <div class="pyc-spin-w"></div>
      </template>
      <span x-text="running ? 'Running…' : 'Run'"></span>
      <kbd class="text-xs opacity-60 ml-0.5" style="font-size:.65rem" x-show="!running">Ctrl+↵</kbd>
    </button>

    <div class="pyc-sep"></div>

    {{-- Templates --}}
    <div class="pyc-tmpl-wrap" @click.outside="showTemplates=false">
      <button class="pyc-btn" @click="showTemplates=!showTemplates">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        Templates
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="pyc-tmpl-menu" x-show="showTemplates" x-transition>
        <template x-for="tpl in templates" :key="tpl.name">
          <div class="pyc-tmpl-item" @click="loadTemplate(tpl)">
            <span class="text-base flex-shrink-0" x-text="tpl.icon"></span>
            <div>
              <div class="pyc-tmpl-name" x-text="tpl.name"></div>
              <div class="pyc-tmpl-desc" x-text="tpl.desc"></div>
            </div>
          </div>
        </template>
      </div>
    </div>

    {{-- Clear --}}
    <button class="pyc-btn" @click="clearEditor()">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
      Clear
    </button>

    {{-- Reset --}}
    <button class="pyc-btn" @click="resetEditor()">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15A9 9 0 1 0 4 9.04"/></svg>
      Reset
    </button>

    {{-- Copy code --}}
    <button class="pyc-btn" :class="copied ? 'copied' : ''" @click="copyCode()">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
      <span x-text="copied ? '✓ Copied' : 'Copy'"></span>
    </button>

    <div class="pyc-sep"></div>

    {{-- Fullscreen --}}
    <button class="pyc-btn" @click="toggleFullscreen()">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
      Fullscreen
    </button>

    {{-- Status pill (pushed right) --}}
    <div class="ml-auto flex items-center gap-1.5">
      <div class="pyc-pill"
           :class="running ? 'pyc-pill-run' : lastStatus==='ok' ? 'pyc-pill-ok' : lastStatus==='err' ? 'pyc-pill-err' : lastStatus==='timeout' ? 'pyc-pill-timeout' : 'pyc-pill-idle'">
        <div class="pyc-spin-p" x-show="running"></div>
        <svg x-show="!running && lastStatus==='ok'" width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        <svg x-show="!running && (lastStatus==='err'||lastStatus==='timeout')" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span x-text="running ? 'Executing…' : lastStatus==='ok' ? 'Success' : lastStatus==='err' ? 'Error' : lastStatus==='timeout' ? 'Timeout' : 'Ready'"></span>
      </div>
    </div>
  </div>

  {{-- ── Workspace ── --}}
  <div class="pyc-workspace" x-ref="workspace">

    {{-- ── Editor side ── --}}
    <div class="pyc-editor-side" x-ref="editorSide">

      {{-- Editor header --}}
      <div class="pyc-editor-header">
        <div class="pyc-file-name">
          <svg width="12" height="12" fill="none" stroke="#6272a4" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          main.py
          <span class="pyc-lang-badge">PY3</span>
        </div>
        <div class="pyc-hdr-actions">
          <button class="pyc-hdr-btn" @click="toggleIndent()" :title="'Tab size: '+(indentWith4?4:2)">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="4 6 8 6"/><polyline points="4 12 20 12"/><polyline points="4 18 16 18"/></svg>
            <span x-text="'Tab: '+(indentWith4?4:2)"></span>
          </button>
          <button class="pyc-hdr-btn" :class="lineWrap?'active':''" @click="toggleWrap()">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="17 10 21 10"/><path d="M21 6H3M21 14H3M17 18l4-4-4-4"/><line x1="3" y1="18" x2="11" y2="18"/></svg>
            Wrap
          </button>
        </div>
      </div>

      {{-- CodeMirror --}}
      <div class="pyc-cm-wrap">
        <div x-ref="cmEl"></div>
        <textarea x-ref="cmFallback" class="pyc-textarea" style="display:none" placeholder="Write Python code here…" spellcheck="false"></textarea>
      </div>

      {{-- Stdin --}}
      <div class="pyc-stdin-wrap">
        <div class="pyc-stdin-toggle" @click="showStdin=!showStdin">
          <span class="pyc-stdin-label">
            <svg width="10" height="10" fill="none" stroke="#6272a4" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Standard Input (stdin)
            <span class="pyc-stdin-has" x-show="stdin.trim().length > 0">has input</span>
          </span>
          <svg width="11" height="11" fill="none" stroke="#6272a4" stroke-width="2" viewBox="0 0 24 24"
               :style="showStdin ? 'transform:rotate(180deg)' : ''"
               style="transition:transform .2s;flex-shrink:0">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </div>
        <div x-show="showStdin" x-transition.duration.150ms class="pyc-stdin-body">
          <textarea
            x-model="stdin"
            class="pyc-stdin-ta"
            placeholder="Enter input values here (one per line) — fed to input() calls in your program"
            spellcheck="false"
          ></textarea>
        </div>
      </div>

    </div>{{-- /editor-side --}}

    {{-- ── Drag divider ── --}}
    <div class="pyc-divider" x-ref="divider"
         @pointerdown.prevent="startResize($event)"
         :class="resizing ? 'active' : ''">
      <div class="pyc-divider-dots">
        <span></span><span></span><span></span><span></span><span></span>
      </div>
    </div>

    {{-- ── Output side ── --}}
    <div class="pyc-output-side">

      {{-- Output header --}}
      <div class="pyc-output-header">
        <div class="pyc-output-title">
          Output
          <div class="pyc-spin-p" x-show="running"></div>
        </div>
        <div class="flex items-center gap-1">
          <button class="pyc-btn" style="padding:.28rem .6rem;font-size:.68rem"
                  @click="clearOutput()" x-show="hasOutput">
            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
            Clear
          </button>
          <button class="pyc-btn" style="padding:.28rem .6rem;font-size:.68rem"
                  :class="copiedOut ? 'copied' : ''"
                  @click="copyOutput()" x-show="hasOutput">
            <span x-text="copiedOut ? '✓' : 'Copy'"></span>
          </button>
        </div>
      </div>

      {{-- Output body --}}
      <div class="pyc-output-body" x-ref="outputBody">

        {{-- Empty state --}}
        <div class="pyc-empty" x-show="!hasOutput && !running">
          <div style="width:52px;height:52px;background:#f3f4f6;border-radius:14px;display:flex;align-items:center;justify-content:center">
            <svg width="22" height="22" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-400">Output will appear here</p>
            <p class="text-xs text-gray-300 mt-0.5">Press <kbd class="px-1 py-0.5 text-xs bg-gray-100 border border-gray-200 rounded text-gray-500">Run</kbd> or <kbd class="px-1 py-0.5 text-xs bg-gray-100 border border-gray-200 rounded text-gray-500">Ctrl+Enter</kbd></p>
          </div>
        </div>

        {{-- Running state --}}
        <div class="pyc-empty" x-show="running">
          <div class="pyc-spin-lg"></div>
          <p class="text-sm text-gray-400 mt-2">Executing Python code…</p>
        </div>

        {{-- stdout --}}
        <div class="pyc-stdout" x-show="stdout !== ''" x-text="stdout"></div>

        {{-- stderr --}}
        <div class="pyc-stderr" x-show="stderr !== ''">
          <div class="pyc-stderr-lbl">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            stderr
          </div>
          <span x-text="stderr"></span>
        </div>

      </div>{{-- /output-body --}}

      {{-- Meta bar --}}
      <div class="pyc-meta" x-show="hasOutput && !running">
        <div class="flex items-center gap-1">
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span x-text="execTime !== null ? execTime + ' ms' : '—'"></span>
        </div>
        <div class="flex items-center gap-1">
          <span>Exit:</span>
          <span class="pyc-meta-val" x-text="exitCode !== null ? exitCode : '—'"></span>
        </div>
        <div class="flex items-center gap-1" x-show="stdout !== ''">
          <span>Lines:</span>
          <span class="pyc-meta-val" x-text="stdout.split('\n').filter(function(l){return l.length>0}).length"></span>
        </div>
      </div>

    </div>{{-- /output-side --}}

  </div>{{-- /workspace --}}

  {{-- Status bar --}}
  <div class="pyc-statusbar mt-1 px-1">
    <div class="flex items-center gap-2">
      <span class="font-mono">main.py</span>
      <span>•</span>
      <span x-text="lineCount + ' lines'"></span>
      <span>•</span>
      <span x-text="charCount + ' chars'"></span>
      <span>•</span>
      <span x-text="'Indent: ' + (indentWith4 ? 4 : 2)"></span>
    </div>
    <div class="flex items-center gap-1.5">
      <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Real Python 3 · server-side
    </div>
  </div>

  {{-- Bottom grid --}}
  <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
      <a href="{{ route('categories.show', $tool->category) }}"
         class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
        <span class="text-xl">{{ $tool->category->icon }}</span>
        <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
      </a>
    </div>

    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Keyboard Shortcuts</h3>
      <div class="space-y-2 text-xs">
        @foreach([
          ['Ctrl + Enter','Run code'],
          ['Ctrl + Z','Undo'],
          ['Ctrl + Y','Redo'],
          ['Tab','Indent'],
          ['Shift + Tab','Unindent'],
          ['Ctrl + /','Toggle comment'],
        ] as $s)
        <div class="flex items-center justify-between">
          <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-600 text-xs">{{ $s[0] }}</kbd>
          <span class="text-gray-500">{{ $s[1] }}</span>
        </div>
        @endforeach
      </div>
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

</div>{{-- /max-w-7xl --}}
</div>{{-- /min-h-screen --}}
@endsection

@push('scripts')
{{-- CodeMirror 5 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/comment/comment.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/selection/active-line.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/display/autorefresh.min.js" crossorigin="anonymous"></script>

<script>
/* ══════════════════════════════════════════════════════
   Python Compiler — Alpine.js component   prefix: pyc-
   ══════════════════════════════════════════════════════ */

var PYC_DEFAULT = [
  '# Python Compiler — write your code here',
  '# Press Run (or Ctrl+Enter) to execute',
  '',
  'def greet(name):',
  '    return f"Hello, {name}! 🐍"',
  '',
  'print(greet("World"))',
  '',
  '# List comprehension',
  'numbers = [1, 2, 3, 4, 5]',
  'squares = [n ** 2 for n in numbers]',
  'print("Squares:", squares)',
  '',
  '# Dictionary iteration',
  'person = {"name": "Alice", "age": 30, "city": "Paris"}',
  'for key, value in person.items():',
  '    print(f"  {key}: {value}")',
].join('\n');

var PYC_TEMPLATES = [
  {
    icon: '👋',
    name: 'Hello World',
    desc: 'Default starter — greet & list ops',
    code: PYC_DEFAULT,
    stdin: '',
  },
  {
    icon: '🔢',
    name: 'FizzBuzz',
    desc: 'Classic programming exercise',
    code: [
      'for i in range(1, 101):',
      '    if i % 15 == 0:',
      '        print("FizzBuzz")',
      '    elif i % 3 == 0:',
      '        print("Fizz")',
      '    elif i % 5 == 0:',
      '        print("Buzz")',
      '    else:',
      '        print(i)',
    ].join('\n'),
    stdin: '',
  },
  {
    icon: '🌀',
    name: 'Fibonacci',
    desc: 'Recursive Fibonacci sequence',
    code: [
      'def fib(n):',
      '    if n <= 1:',
      '        return n',
      '    return fib(n - 1) + fib(n - 2)',
      '',
      'print("Fibonacci sequence:")',
      'for i in range(15):',
      '    print(f"  fib({i:2d}) = {fib(i)}")',
    ].join('\n'),
    stdin: '',
  },
  {
    icon: '⌨️',
    name: 'Input / stdin',
    desc: 'Uses input() — provide stdin below',
    code: [
      'name = input("Enter your name: ")',
      'age  = int(input("Enter your age: "))',
      '',
      'print(f"\\nHello, {name}!")',
      'print(f"In 10 years you will be {age + 10} years old.")',
    ].join('\n'),
    stdin: 'Alice\n25',
  },
  {
    icon: '🏛️',
    name: 'OOP — Classes',
    desc: 'Object-oriented programming example',
    code: [
      'class Animal:',
      '    def __init__(self, name, sound):',
      '        self.name  = name',
      '        self.sound = sound',
      '',
      '    def speak(self):',
      '        return f"{self.name} says {self.sound}!"',
      '',
      '    def __repr__(self):',
      '        return f"Animal({self.name!r})"',
      '',
      '',
      'class Dog(Animal):',
      '    def fetch(self, item):',
      '        return f"{self.name} fetches the {item}!"',
      '',
      '',
      'dog = Dog("Rex", "Woof")',
      'cat = Animal("Whiskers", "Meow")',
      '',
      'print(dog.speak())',
      'print(cat.speak())',
      'print(dog.fetch("ball"))',
      'print(repr(dog))',
    ].join('\n'),
    stdin: '',
  },
  {
    icon: '🧮',
    name: 'Data Structures',
    desc: 'Lists, dicts, sets, tuples',
    code: [
      '# Lists & sorting',
      'nums = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3]',
      'print("Original:", nums)',
      'print("Sorted:  ", sorted(nums))',
      'print("Unique:  ", sorted(set(nums)))',
      '',
      '# Dict comprehension',
      'word = "hello world"',
      'freq = {ch: word.count(ch) for ch in set(word) if ch != " "}',
      'print("\\nChar frequency:", dict(sorted(freq.items())))',
      '',
      '# Stack using list',
      'stack = []',
      'for item in ["a", "b", "c", "d"]:',
      '    stack.append(item)',
      'print("\\nStack pop order:", end=" ")',
      'while stack:',
      '    print(stack.pop(), end=" ")',
      'print()',
    ].join('\n'),
    stdin: '',
  },
];

/* ── Alpine component ── */
function pycCompiler() {
  return {
    /* editor state */
    indentWith4: true,
    lineWrap: false,
    lineCount: 0,
    charCount: 0,

    /* execution */
    running: false,
    lastStatus: null,   // null | 'ok' | 'err' | 'timeout'

    /* output */
    stdout: '',
    stderr: '',
    exitCode: null,
    execTime: null,

    /* stdin */
    stdin: '',
    showStdin: false,

    /* ui */
    showTemplates: false,
    copied: false,
    copiedOut: false,
    resizing: false,

    templates: PYC_TEMPLATES,

    /* internals */
    _editor: null,
    _fallback: false,

    get hasOutput() { return this.stdout !== '' || this.stderr !== ''; },

    /* ── Init ── */
    init: function() {
      var vm = this;
      vm.$nextTick(function() { vm._initEditor(); });

      window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
          e.preventDefault();
          vm.run();
        }
      });
    },

    /* ── CodeMirror init ── */
    _initEditor: function() {
      var vm = this;

      if (typeof CodeMirror === 'undefined') {
        vm._fallback = true;
        var ta = vm.$refs.cmFallback;
        if (!ta) return;
        ta.style.display = 'block';
        ta.value = PYC_DEFAULT;
        ta.addEventListener('input', function() {
          vm.lineCount = ta.value.split('\n').length;
          vm.charCount = ta.value.length;
        });
        vm.lineCount = ta.value.split('\n').length;
        vm.charCount = ta.value.length;
        return;
      }

      vm._editor = CodeMirror(vm.$refs.cmEl, {
        value: PYC_DEFAULT,
        mode: 'python',
        theme: 'dracula',
        lineNumbers: true,
        tabSize: 4,
        indentUnit: 4,
        indentWithTabs: false,
        lineWrapping: false,
        matchBrackets: true,
        autoCloseBrackets: true,
        styleActiveLine: true,
        autoRefresh: true,
        extraKeys: {
          'Tab': function(cm) {
            if (cm.somethingSelected()) {
              cm.indentSelection('add');
            } else {
              var spaces = Array(cm.getOption('indentUnit') + 1).join(' ');
              cm.replaceSelection(spaces, 'end');
            }
          },
          'Shift-Tab': function(cm) { cm.indentSelection('subtract'); },
          'Ctrl-/': function(cm) { cm.toggleComment(); },
          'Cmd-/':  function(cm) { cm.toggleComment(); },
        },
      });

      vm._editor.setSize('100%', '100%');

      vm._editor.on('change', function(cm) {
        vm.lineCount = cm.lineCount();
        vm.charCount = cm.getValue().length;
      });

      vm.lineCount = vm._editor.lineCount();
      vm.charCount = vm._editor.getValue().length;
    },

    _getCode: function() {
      if (this._fallback) return this.$refs.cmFallback ? this.$refs.cmFallback.value : '';
      return this._editor ? this._editor.getValue() : '';
    },

    _setCode: function(val) {
      if (this._fallback) {
        if (this.$refs.cmFallback) { this.$refs.cmFallback.value = val; }
      } else if (this._editor) {
        this._editor.setValue(val);
        this._editor.clearHistory();
      }
    },

    /* ── Run ── */
    run: async function() {
      if (this.running) return;

      var code = this._getCode().trim();
      if (!code) {
        this.stdout = '';
        this.stderr = 'No code to run — write some Python first!';
        this.lastStatus = 'err';
        return;
      }

      this.running  = true;
      this.stdout   = '';
      this.stderr   = '';
      this.exitCode = null;
      this.execTime = null;

      try {
        var csrfMeta  = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        var res = await fetch('/tools/python-compiler/process', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ code: this._getCode(), stdin: this.stdin }),
        });

        var data = await res.json();

        this.stdout   = data.output || '';
        this.stderr   = data.error  || '';
        this.exitCode = data.exit_code  != null ? data.exit_code  : null;
        this.execTime = data.execution_time != null ? data.execution_time : null;

        if (data.exit_code === -1 || (this.stderr && this.stderr.includes('timed out'))) {
          this.lastStatus = 'timeout';
        } else if (data.success === false || (data.exit_code !== null && data.exit_code !== 0)) {
          this.lastStatus = 'err';
        } else {
          this.lastStatus = 'ok';
        }

      } catch (err) {
        this.stderr = 'Request failed: ' + err.message + '\nMake sure the development server is running.';
        this.lastStatus = 'err';
      } finally {
        this.running = false;
        var vm = this;
        vm.$nextTick(function() {
          var ob = vm.$refs.outputBody;
          if (ob) ob.scrollTop = 0;
        });
      }
    },

    clearEditor: function() { this._setCode(''); },

    resetEditor: function() {
      if (this._getCode().trim() && !confirm('Reset editor to the default starter code?')) return;
      this._setCode(PYC_DEFAULT);
      this.stdin = '';
      this.clearOutput();
    },

    loadTemplate: function(tpl) {
      this.showTemplates = false;
      this._setCode(tpl.code);
      this.stdin = tpl.stdin || '';
      if (this.stdin) this.showStdin = true;
      this.clearOutput();
    },

    clearOutput: function() {
      this.stdout = '';
      this.stderr = '';
      this.exitCode = null;
      this.execTime = null;
      this.lastStatus = null;
    },

    copyCode: function() {
      var vm = this;
      var val = this._getCode();
      if (!val || !navigator.clipboard) return;
      navigator.clipboard.writeText(val).then(function() {
        vm.copied = true;
        setTimeout(function(){ vm.copied = false; }, 2000);
      });
    },

    copyOutput: function() {
      var vm = this;
      var val = (this.stdout + (this.stderr ? '\n--- stderr ---\n' + this.stderr : '')).trim();
      if (!val || !navigator.clipboard) return;
      navigator.clipboard.writeText(val).then(function() {
        vm.copiedOut = true;
        setTimeout(function(){ vm.copiedOut = false; }, 2000);
      });
    },

    toggleIndent: function() {
      this.indentWith4 = !this.indentWith4;
      if (!this._fallback && this._editor) {
        var n = this.indentWith4 ? 4 : 2;
        this._editor.setOption('indentUnit', n);
        this._editor.setOption('tabSize', n);
      }
    },

    toggleWrap: function() {
      this.lineWrap = !this.lineWrap;
      if (!this._fallback && this._editor) {
        this._editor.setOption('lineWrapping', this.lineWrap);
      }
    },

    toggleFullscreen: function() {
      var el = this.$refs.workspace;
      if (!el) return;
      if (!document.fullscreenElement) {
        el.requestFullscreen && el.requestFullscreen();
      } else {
        document.exitFullscreen && document.exitFullscreen();
      }
    },

    /* ── Drag-to-resize ── */
    startResize: function(e) {
      var vm = this;
      vm.resizing = true;
      var ws     = vm.$refs.workspace;
      var es     = vm.$refs.editorSide;
      var startX = e.clientX;
      var startW = es.getBoundingClientRect().width;
      var totalW = ws.getBoundingClientRect().width;

      function onMove(ev) {
        var pct = ((startW + ev.clientX - startX) / totalW) * 100;
        pct = Math.max(25, Math.min(72, pct));
        es.style.width = pct + '%';
        if (!vm._fallback && vm._editor) vm._editor.refresh();
      }

      function onUp() {
        vm.resizing = false;
        window.removeEventListener('pointermove', onMove);
        window.removeEventListener('pointerup', onUp);
      }

      window.addEventListener('pointermove', onMove);
      window.addEventListener('pointerup', onUp);
    },
  };
}
</script>
@endpush