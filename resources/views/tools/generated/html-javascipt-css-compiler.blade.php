@extends('layouts.public')

@section('title', 'HTML, CSS & JavaScript Compiler — JEDISEBITOOL')
@section('description', 'Free online HTML, CSS & JavaScript live compiler. Write code and see results instantly — syntax highlighting, sandboxed preview, console output. No installation needed.')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css" crossorigin="anonymous">
<style>
/* ═══════════════════════════════════════════════
   HTML / CSS / JS Compiler   (prefix: hcc-)
   ═══════════════════════════════════════════════ */

/* ── Toolbar ── */
.hcc-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;padding:.75rem 1rem;background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.hcc-toolbar-sep{width:1px;height:20px;background:#e5e7eb;flex-shrink:0}

/* ── Workspace ── */
.hcc-workspace{display:flex;flex-direction:row;background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 2px 8px rgba(0,0,0,.06);overflow:hidden;height:530px}

/* ── Editor panel ── */
.hcc-editors-panel{display:flex;flex-direction:column;width:50%;min-width:180px;background:#282a36;flex-shrink:0}

/* ── Tab bar ── */
.hcc-tabs{display:flex;align-items:stretch;background:#1e1f2e;border-bottom:1px solid #383a4e;flex-shrink:0;overflow-x:auto;scrollbar-width:none}
.hcc-tabs::-webkit-scrollbar{display:none}
.hcc-tab{display:inline-flex;align-items:center;gap:.375rem;padding:.5rem .875rem;font-size:.75rem;font-weight:600;color:#8b8fa8;border:none;background:transparent;cursor:pointer;border-bottom:2px solid transparent;white-space:nowrap;transition:color .15s,border-color .15s;flex-shrink:0}
.hcc-tab:hover{color:#cdd6f4}
.hcc-tab.active{color:#cdd6f4;border-bottom-color:#4f46e5}
.hcc-tab-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.hcc-dot-html{background:#f97316}
.hcc-dot-css{background:#3b82f6}
.hcc-dot-js{background:#eab308}
.hcc-tab-actions{margin-left:auto;display:flex;align-items:center;gap:.25rem;padding:0 .5rem;flex-shrink:0}
.hcc-tab-action{display:inline-flex;align-items:center;gap:.25rem;padding:.25rem .5rem;font-size:.68rem;font-weight:500;color:#6b7280;border-radius:5px;border:1px solid transparent;background:transparent;cursor:pointer;transition:all .12s}
.hcc-tab-action:hover{background:#383a4e;color:#cdd6f4;border-color:#4a4c5e}

/* ── Editor container ── */
.hcc-editors-container{flex:1;overflow:hidden;position:relative}
.hcc-editor-wrap{position:absolute;inset:0;overflow:hidden}
.hcc-editor-wrap .CodeMirror{height:100%;font-size:13px;font-family:'JetBrains Mono','Fira Code','Cascadia Code','Consolas',monospace;line-height:1.6}
.hcc-editor-wrap .CodeMirror-scroll{overflow:auto!important}

/* Fallback textarea */
.hcc-textarea{width:100%;height:100%;background:#282a36;color:#f8f8f2;font-family:'JetBrains Mono','Fira Code',monospace;font-size:13px;line-height:1.6;border:none;outline:none;resize:none;padding:1rem;box-sizing:border-box;tab-size:2}
.hcc-textarea::placeholder{color:#565869}

/* ── Drag divider ── */
.hcc-divider{width:5px;background:#383a4e;cursor:col-resize;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:background .15s;position:relative;user-select:none}
.hcc-divider:hover,.hcc-divider.dragging{background:#4f46e5}
.hcc-divider-grip{display:flex;flex-direction:column;gap:3px;position:absolute}
.hcc-divider-grip span{width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,.35)}

/* ── Preview panel ── */
.hcc-preview-panel{flex:1;display:flex;flex-direction:column;background:#fff;min-width:180px;overflow:hidden}

/* ── Preview header ── */
.hcc-preview-header{display:flex;align-items:center;justify-content:space-between;padding:.5rem .875rem;background:#f8fafc;border-bottom:1px solid #e5e7eb;flex-shrink:0}
.hcc-preview-dots{display:flex;align-items:center;gap:.375rem}
.hcc-win-dot{width:10px;height:10px;border-radius:50%}
.hcc-win-dot.red{background:#ff5f57}
.hcc-win-dot.yellow{background:#febc2e}
.hcc-win-dot.green{background:#28c840}
.hcc-preview-title{font-size:.7rem;font-weight:600;color:#94a3b8;letter-spacing:.04em;margin-left:.375rem}
.hcc-preview-btn{display:inline-flex;align-items:center;padding:.25rem .375rem;border-radius:5px;border:1px solid transparent;background:transparent;cursor:pointer;color:#9ca3af;transition:all .12s;font-size:.75rem}
.hcc-preview-btn:hover{background:#f3f4f6;color:#4f46e5;border-color:#e0e7ff}

/* ── Preview iframe ── */
.hcc-preview-frame{flex:1;border:none;width:100%;display:block;background:#fff}

/* ── Status bar ── */
.hcc-status{display:flex;align-items:center;justify-content:space-between;padding:.25rem .75rem;font-size:.68rem;color:#9ca3af;user-select:none}
.hcc-status-item{display:flex;align-items:center;gap:.375rem}
.hcc-status-dot{width:6px;height:6px;border-radius:50%;background:#22c55e}
.hcc-status-dot.error{background:#ef4444}
.hcc-status-dot.idle{background:#e5e7eb}

/* ── Console panel ── */
.hcc-console{background:#1a1b26;border-radius:12px;border:1px solid #383a4e;overflow:hidden;margin-top:.75rem}
.hcc-console-header{display:flex;align-items:center;justify-content:space-between;padding:.5rem .875rem;background:#1e1f2e;border-bottom:1px solid #383a4e;cursor:pointer;user-select:none}
.hcc-console-title{font-size:.72rem;font-weight:700;color:#6b7280;letter-spacing:.06em;text-transform:uppercase}
.hcc-console-count{display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 5px;border-radius:9px;font-size:.65rem;font-weight:700;background:#4f46e5;color:#fff;margin-left:.375rem}
.hcc-console-body{max-height:160px;overflow-y:auto;padding:.375rem 0}
.hcc-console-body::-webkit-scrollbar{width:4px}
.hcc-console-body::-webkit-scrollbar-thumb{background:#383a4e;border-radius:2px}
.hcc-log-row{display:flex;align-items:flex-start;gap:.5rem;padding:.2rem .875rem;font-size:.72rem;font-family:'JetBrains Mono','Fira Code',monospace;line-height:1.5;border-bottom:1px solid rgba(255,255,255,.03)}
.hcc-log-row:last-child{border-bottom:none}
.hcc-log-row.log{color:#a6e3a1}
.hcc-log-row.warn{color:#f9e2af;background:rgba(249,226,175,.04)}
.hcc-log-row.error{color:#f38ba8;background:rgba(243,139,168,.06)}
.hcc-log-row.info{color:#89b4fa}
.hcc-log-time{color:#565869;flex-shrink:0;min-width:44px}
.hcc-log-level{flex-shrink:0;min-width:32px;opacity:.7}
.hcc-log-msg{word-break:break-all;white-space:pre-wrap;flex:1}
.hcc-console-empty{text-align:center;padding:1.25rem;font-size:.75rem;color:#4a4c5e}

/* ── Buttons (tool-specific overrides) ── */
.hcc-btn-run{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:1.5px solid #4338ca;border-radius:9px;padding:.4rem .9rem;font-size:.8125rem;font-weight:600;display:inline-flex;align-items:center;gap:.375rem;cursor:pointer;transition:all .15s;box-shadow:0 2px 6px rgba(79,70,229,.25)}
.hcc-btn-run:hover{background:linear-gradient(135deg,#4338ca,#6d28d9);box-shadow:0 3px 10px rgba(79,70,229,.35)}
.hcc-btn-run:active{transform:scale(.97)}
.hcc-btn-auto{border-radius:9px;padding:.35rem .75rem;font-size:.75rem;font-weight:600;display:inline-flex;align-items:center;gap:.375rem;cursor:pointer;border:1.5px solid;transition:all .15s}
.hcc-btn-auto.on{background:#f0fdf4;border-color:#86efac;color:#15803d}
.hcc-btn-auto.off{background:#f9fafb;border-color:#e5e7eb;color:#6b7280}
.hcc-btn{border-radius:9px;padding:.35rem .75rem;font-size:.75rem;font-weight:500;display:inline-flex;align-items:center;gap:.35rem;cursor:pointer;border:1.5px solid #e5e7eb;background:#fff;color:#374151;transition:all .12s;white-space:nowrap}
.hcc-btn:hover{background:#f5f3ff;border-color:#c4b5fd;color:#4f46e5}

/* ── Templates dropdown ── */
.hcc-templates-wrap{position:relative}
.hcc-templates-menu{position:absolute;top:calc(100% + 6px);left:0;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:50;min-width:200px;overflow:hidden}
.hcc-template-item{display:flex;align-items:flex-start;gap:.625rem;padding:.625rem .875rem;cursor:pointer;transition:background .1s;border-bottom:1px solid #f3f4f6}
.hcc-template-item:last-child{border-bottom:none}
.hcc-template-item:hover{background:#f5f3ff}
.hcc-template-name{font-size:.78rem;font-weight:600;color:#1f2937}
.hcc-template-desc{font-size:.68rem;color:#9ca3af;margin-top:.1rem}

/* ── Mobile responsive ── */
@media(max-width:1023px){
  .hcc-workspace{flex-direction:column;height:auto}
  .hcc-editors-panel{width:100%!important;min-height:300px;max-height:380px}
  .hcc-divider{display:none}
  .hcc-preview-panel{min-height:300px;border-top:1px solid #e5e7eb}
  .hcc-preview-frame{min-height:280px}
  .hcc-editor-wrap .CodeMirror{font-size:12px}
}
@media(max-width:639px){
  .hcc-workspace{border-radius:12px}
  .hcc-editors-panel{min-height:260px;max-height:320px}
}
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">

{{-- ══ Hero ══ --}}
<div class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-4">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0"
           style="background:linear-gradient(135deg,#e0e7ff,#ede9fe)">
        {{ $tool->icon }}
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">HTML, CSS &amp; JavaScript Compiler</h1>
          <span class="badge badge-primary text-xs">Live Preview</span>
        </div>
        <p class="text-gray-500 text-sm">Write HTML, CSS, and JS — see the result instantly in a sandboxed preview. No installation needed.</p>
      </div>
    </div>
    <x-breadcrumb :items="[
      ['label' => 'Home', 'url' => url('/')],
      ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
      ['label' => 'HTML CSS JavaScript Compiler']
    ]"/>
  </div>
</div>

{{-- ══ Editor ══ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="hccEditor()"
     x-cloak>

  {{-- Toolbar --}}
  <div class="hcc-toolbar mb-3">
    {{-- Run --}}
    <button class="hcc-btn-run" @click="run()">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      Run
      <kbd class="text-xs opacity-60 ml-0.5">Ctrl+↵</kbd>
    </button>

    {{-- Auto-run toggle --}}
    <button class="hcc-btn-auto" :class="autoRun ? 'on' : 'off'" @click="autoRun=!autoRun" :title="autoRun ? 'Auto-run ON — click to disable' : 'Auto-run OFF — click to enable'">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>
      </svg>
      <span x-text="autoRun ? 'Auto' : 'Manual'"></span>
    </button>

    <div class="hcc-toolbar-sep"></div>

    {{-- Templates --}}
    <div class="hcc-templates-wrap" @click.outside="showTemplates=false">
      <button class="hcc-btn" @click="showTemplates=!showTemplates">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Templates
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="hcc-templates-menu" x-show="showTemplates" x-transition x-cloak>
        <template x-for="tpl in templates" :key="tpl.name">
          <div class="hcc-template-item" @click="loadTemplate(tpl)">
            <span class="text-lg" x-text="tpl.icon"></span>
            <div>
              <div class="hcc-template-name" x-text="tpl.name"></div>
              <div class="hcc-template-desc" x-text="tpl.desc"></div>
            </div>
          </div>
        </template>
      </div>
    </div>

    {{-- Reset --}}
    <button class="hcc-btn" @click="resetAll()" title="Reset to default starter code">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-5.04"/></svg>
      Reset
    </button>

    {{-- Copy --}}
    <button class="hcc-btn" @click="copyCode()" :title="'Copy ' + activeTab.toUpperCase() + ' code'">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
      <span x-text="copied ? 'Copied!' : 'Copy'"></span>
    </button>

    <div class="hcc-toolbar-sep"></div>

    {{-- Open in new tab --}}
    <button class="hcc-btn" @click="openInNewTab()" title="Open preview in new tab">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      New Tab
    </button>

    {{-- Fullscreen --}}
    <button class="hcc-btn" @click="fullscreen()" title="Fullscreen workspace">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
      Fullscreen
    </button>

    {{-- Status indicator (right side) --}}
    <div class="ml-auto flex items-center gap-1.5 text-xs text-gray-400">
      <div class="hcc-status-dot" :class="lastRunOk===true ? '' : lastRunOk===false ? 'error' : 'idle'"></div>
      <span x-text="statusMsg"></span>
    </div>
  </div>

  {{-- ── Workspace ── --}}
  <div class="hcc-workspace" x-ref="workspace" @keydown.ctrl.enter.window="run()" @keydown.meta.enter.window="run()">

    {{-- ── Editors Panel ── --}}
    <div class="hcc-editors-panel" x-ref="editorsPanel">

      {{-- Tab bar --}}
      <div class="hcc-tabs">
        <button class="hcc-tab" :class="activeTab==='html' ? 'active' : ''" @click="switchTab('html')">
          <span class="hcc-tab-dot hcc-dot-html"></span> HTML
        </button>
        <button class="hcc-tab" :class="activeTab==='css' ? 'active' : ''" @click="switchTab('css')">
          <span class="hcc-tab-dot hcc-dot-css"></span> CSS
        </button>
        <button class="hcc-tab" :class="activeTab==='js' ? 'active' : ''" @click="switchTab('js')">
          <span class="hcc-tab-dot hcc-dot-js"></span> JS
        </button>

        <div class="hcc-tab-actions">
          <button class="hcc-tab-action" @click="clearCurrent()" title="Clear current editor">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            Clear
          </button>
          <button class="hcc-tab-action" @click="formatCode()" title="Format code (basic indent)">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/></svg>
            Wrap
          </button>
        </div>
      </div>

      {{-- Editor panes (all in DOM, JS shows/hides) --}}
      <div class="hcc-editors-container">
        <div class="hcc-editor-wrap" x-ref="htmlWrap">
          <div x-ref="htmlEditorEl"></div>
          <textarea x-ref="htmlFallback" class="hcc-textarea" style="display:none" placeholder="HTML code..."></textarea>
        </div>
        <div class="hcc-editor-wrap" x-ref="cssWrap" style="display:none">
          <div x-ref="cssEditorEl"></div>
          <textarea x-ref="cssFallback" class="hcc-textarea" style="display:none" placeholder="CSS code..."></textarea>
        </div>
        <div class="hcc-editor-wrap" x-ref="jsWrap" style="display:none">
          <div x-ref="jsEditorEl"></div>
          <textarea x-ref="jsFallback" class="hcc-textarea" style="display:none" placeholder="JavaScript code..."></textarea>
        </div>
      </div>
    </div>

    {{-- ── Drag divider ── --}}
    <div class="hcc-divider" x-ref="divider"
         @pointerdown.prevent="startResize($event)"
         :class="resizing ? 'dragging' : ''">
      <div class="hcc-divider-grip">
        <span></span><span></span><span></span><span></span><span></span>
      </div>
    </div>

    {{-- ── Preview Panel ── --}}
    <div class="hcc-preview-panel" x-ref="previewPanel">
      {{-- Header --}}
      <div class="hcc-preview-header">
        <div class="hcc-preview-dots">
          <div class="hcc-win-dot red"></div>
          <div class="hcc-win-dot yellow"></div>
          <div class="hcc-win-dot green"></div>
          <span class="hcc-preview-title">Preview</span>
          <span x-show="previewError" class="ml-2 text-xs text-red-400" x-text="'⚠ ' + previewError" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
        </div>
        <div class="flex items-center gap-0.5">
          <button class="hcc-preview-btn" @click="run()" title="Refresh preview">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-5.04"/></svg>
          </button>
          <button class="hcc-preview-btn" @click="openInNewTab()" title="Open in new tab">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          </button>
          <button class="hcc-preview-btn" @click="fullscreen()" title="Fullscreen workspace">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>
      </div>

      {{-- iframe --}}
      <iframe x-ref="preview"
        class="hcc-preview-frame"
        sandbox="allow-scripts"
        title="Code preview"
      ></iframe>
    </div>

  </div>{{-- /workspace --}}

  {{-- Status bar --}}
  <div class="hcc-status mt-1.5 px-1">
    <div class="hcc-status-item">
      <span class="font-mono" x-text="activeTab.toUpperCase()"></span>
      <span>•</span>
      <span x-text="lineCounts[activeTab] + ' lines'"></span>
      <span>•</span>
      <span x-text="charCounts[activeTab] + ' chars'"></span>
    </div>
    <div class="hcc-status-item">
      <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Sandboxed iframe • safe execution
    </div>
  </div>

  {{-- Console panel --}}
  <div class="hcc-console">
    <div class="hcc-console-header" @click="showConsole=!showConsole">
      <div class="flex items-center">
        <span class="hcc-console-title">Console</span>
        <span class="hcc-console-count" x-show="consoleLogs.length" x-text="consoleLogs.length"></span>
      </div>
      <div class="flex items-center gap-2">
        <button class="hcc-tab-action" @click.stop="consoleLogs=[]" x-show="consoleLogs.length">
          Clear
        </button>
        <svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"
             :style="showConsole ? 'transform:rotate(180deg)' : ''"
             style="transition:transform .2s;flex-shrink:0">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </div>
    </div>
    <div class="hcc-console-body" x-show="showConsole" x-transition x-ref="consoleBody">
      <template x-if="consoleLogs.length === 0">
        <div class="hcc-console-empty">No output yet — run your code or use console.log()</div>
      </template>
      <template x-for="(entry, i) in consoleLogs" :key="i">
        <div class="hcc-log-row" :class="entry.level">
          <span class="hcc-log-time" x-text="entry.time"></span>
          <span class="hcc-log-level" x-text="entry.level.toUpperCase()"></span>
          <span class="hcc-log-msg" x-text="entry.msg"></span>
        </div>
      </template>
    </div>
  </div>

  {{-- Bottom row: sidebar content horizontal --}}
  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    {{-- Category --}}
    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
      <a href="{{ route('categories.show', $tool->category) }}"
         class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
        <span class="text-xl">{{ $tool->category->icon }}</span>
        <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
      </a>
    </div>

    {{-- Keyboard shortcuts --}}
    <div class="card p-5">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Keyboard Shortcuts</h3>
      <div class="space-y-1.5 text-xs">
        @foreach([
          ['Ctrl + Enter', 'Run code'],
          ['Ctrl + Z', 'Undo'],
          ['Ctrl + Y', 'Redo'],
          ['Tab', 'Indent'],
          ['Shift + Tab', 'Unindent'],
          ['Ctrl + /', 'Toggle comment'],
        ] as $s)
        <div class="flex items-center justify-between">
          <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-600">{{ $s[0] }}</kbd>
          <span class="text-gray-500">{{ $s[1] }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Related tools --}}
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

  </div>{{-- /bottom grid --}}

  {{-- Long description / FAQ --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/comment/comment.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/selection/active-line.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/fold/foldcode.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/display/autorefresh.min.js" crossorigin="anonymous"></script>

<script>
/* ═══════════════════════════════════════════════════════════════
   HTML / CSS / JS Compiler — Alpine.js component
   CSS prefix: hcc-  |  Component: hccEditor()
   ═══════════════════════════════════════════════════════════════ */

/* ── Default starter code ── */
var HCC_DEFAULTS = {
  html: [
    '<div class="container">',
    '  <h1>Hello, World! 👋</h1>',
    '  <p>Edit HTML, CSS &amp; JS to see live changes.</p>',
    '  <button id="btn">Click Me</button>',
    '  <p id="output"></p>',
    '</div>'
  ].join('\n'),

  css: [
    '* { box-sizing: border-box; margin: 0; padding: 0; }',
    '',
    'body {',
    '  font-family: "Segoe UI", sans-serif;',
    '  background: linear-gradient(135deg, #f0f4ff 0%, #e8e0ff 100%);',
    '  min-height: 100vh;',
    '  display: flex;',
    '  align-items: center;',
    '  justify-content: center;',
    '  padding: 20px;',
    '}',
    '',
    '.container {',
    '  background: #fff;',
    '  border-radius: 18px;',
    '  padding: 40px 48px;',
    '  box-shadow: 0 6px 32px rgba(79,70,229,.14);',
    '  text-align: center;',
    '  max-width: 440px;',
    '  width: 100%;',
    '}',
    '',
    'h1 {',
    '  color: #4f46e5;',
    '  font-size: 2rem;',
    '  margin-bottom: 12px;',
    '}',
    '',
    'p {',
    '  color: #6b7280;',
    '  line-height: 1.6;',
    '  margin-bottom: 20px;',
    '}',
    '',
    'button {',
    '  background: #4f46e5;',
    '  color: #fff;',
    '  border: none;',
    '  padding: 12px 28px;',
    '  border-radius: 10px;',
    '  font-size: 1rem;',
    '  cursor: pointer;',
    '  transition: background .2s, transform .1s;',
    '}',
    '',
    'button:hover { background: #4338ca; }',
    'button:active { transform: scale(.97); }',
    '',
    '#output {',
    '  margin-top: 16px;',
    '  color: #059669;',
    '  font-weight: 600;',
    '  min-height: 24px;',
    '}'
  ].join('\n'),

  js: [
    'const btn = document.getElementById("btn");',
    'const output = document.getElementById("output");',
    'let count = 0;',
    '',
    'btn.addEventListener("click", () => {',
    '  count++;',
    '  output.textContent = `🎉 You clicked ${count} time${count !== 1 ? "s" : ""}!`;',
    '});',
    '',
    'console.log("JavaScript loaded successfully!");'
  ].join('\n')
};

/* ── Starter templates ── */
var HCC_TEMPLATES = [
  {
    icon: '👋',
    name: 'Hello World',
    desc: 'Default starter template',
    html: HCC_DEFAULTS.html,
    css: HCC_DEFAULTS.css,
    js: HCC_DEFAULTS.js
  },
  {
    icon: '🎨',
    name: 'CSS Animation',
    desc: 'Bouncing balls animation',
    html: '<div class="stage">\n  <div class="ball b1"></div>\n  <div class="ball b2"></div>\n  <div class="ball b3"></div>\n</div>',
    css: 'body { background: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }\n.stage { display: flex; gap: 16px; align-items: flex-end; height: 100px; }\n.ball { width: 24px; height: 24px; border-radius: 50%; animation: bounce .8s ease-in-out infinite alternate; }\n.b1 { background: #4f46e5; }\n.b2 { background: #7c3aed; animation-delay: .2s; }\n.b3 { background: #a855f7; animation-delay: .4s; }\n@keyframes bounce { from { transform: translateY(0); } to { transform: translateY(-80px); } }',
    js: 'console.log("Animation running!");'
  },
  {
    icon: '🔢',
    name: 'Counter App',
    desc: 'Interactive counter with JS',
    html: '<div class="app">\n  <h2>Counter</h2>\n  <div class="counter">\n    <button id="dec">−</button>\n    <span id="val">0</span>\n    <button id="inc">+</button>\n  </div>\n  <button id="reset" class="reset">Reset</button>\n</div>',
    css: 'body{background:#f0f4ff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;font-family:sans-serif}\n.app{background:#fff;border-radius:20px;padding:40px;text-align:center;box-shadow:0 4px 24px rgba(79,70,229,.15)}\nh2{color:#4f46e5;margin-bottom:24px;font-size:1.5rem}\n.counter{display:flex;align-items:center;gap:20px;margin-bottom:20px}\nbutton{background:#4f46e5;color:#fff;border:none;width:44px;height:44px;border-radius:12px;font-size:1.5rem;cursor:pointer;transition:background .2s}\nbutton:hover{background:#4338ca}\n#val{font-size:3rem;font-weight:700;color:#1f2937;min-width:80px}\n.reset{width:auto;padding:10px 28px;font-size:.9rem;background:#e5e7eb;color:#374151}\n.reset:hover{background:#d1d5db}',
    js: 'let n = 0;\nconst val = document.getElementById("val");\ndocument.getElementById("inc").onclick = () => { n++; update(); };\ndocument.getElementById("dec").onclick = () => { n--; update(); };\ndocument.getElementById("reset").onclick = () => { n = 0; update(); };\nfunction update() {\n  val.textContent = n;\n  val.style.color = n > 0 ? "#059669" : n < 0 ? "#dc2626" : "#1f2937";\n  console.log("Counter:", n);\n}'
  },
  {
    icon: '📝',
    name: 'Blank Canvas',
    desc: 'Empty starter — clean slate',
    html: '<!-- Write your HTML here -->\n<h1>My Page</h1>',
    css: '/* Write your CSS here */\nbody {\n  font-family: sans-serif;\n  padding: 20px;\n}',
    js: '// Write your JavaScript here\nconsole.log("Ready!");'
  }
];

/* ── Alpine component ── */
function hccEditor() {
  return {
    /* state */
    activeTab: 'html',
    autoRun: false,
    showTemplates: false,
    showConsole: true,
    copied: false,
    resizing: false,

    /* counters (reactive, updated on change) */
    lineCounts: { html: 0, css: 0, js: 0 },
    charCounts: { html: 0, css: 0, js: 0 },

    /* status */
    statusMsg: 'Ready',
    lastRunOk: null,
    previewError: '',

    /* console */
    consoleLogs: [],

    /* templates */
    templates: HCC_TEMPLATES,

    /* internals (non-reactive) */
    _editors: {},
    _fallback: false,
    _debounce: null,
    _resizeHandlers: null,
    _msgHandler: null,

    /* ── Lifecycle ── */
    init: function() {
      var vm = this;
      vm.$nextTick(function() {
        vm._initEditors();
        vm._setupMessageListener();
        vm.run();
      });
    },

    destroy: function() {
      if (this._msgHandler) window.removeEventListener('message', this._msgHandler);
    },

    /* ── Editor initialisation ── */
    _initEditors: function() {
      var vm = this;

      if (typeof CodeMirror === 'undefined') {
        /* Graceful fallback — plain textareas */
        vm._fallback = true;
        var tabs = ['html','css','js'];
        tabs.forEach(function(t) {
          var ta = vm.$refs[t + 'Fallback'];
          if (!ta) return;
          ta.style.display = 'block';
          ta.value = HCC_DEFAULTS[t];
          ta.addEventListener('input', function() {
            vm.lineCounts[t] = ta.value.split('\n').length;
            vm.charCounts[t] = ta.value.length;
            if (vm.autoRun) {
              clearTimeout(vm._debounce);
              vm._debounce = setTimeout(function(){ vm.run(); }, 600);
            }
          });
          vm.lineCounts[t] = ta.value.split('\n').length;
          vm.charCounts[t] = ta.value.length;
        });
        return;
      }

      /* CodeMirror options base */
      var baseOpts = {
        theme: 'dracula',
        lineNumbers: true,
        tabSize: 2,
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
              cm.replaceSelection('  ', 'end');
            }
          },
          'Shift-Tab': function(cm) { cm.indentSelection('subtract'); },
          'Ctrl-/': function(cm) { cm.toggleComment(); },
          'Cmd-/': function(cm) { cm.toggleComment(); },
        }
      };

      /* HTML */
      vm._editors.html = CodeMirror(vm.$refs.htmlEditorEl, Object.assign({}, baseOpts, {
        value: HCC_DEFAULTS.html,
        mode: 'htmlmixed',
      }));

      /* CSS */
      vm._editors.css = CodeMirror(vm.$refs.cssEditorEl, Object.assign({}, baseOpts, {
        value: HCC_DEFAULTS.css,
        mode: 'css',
      }));

      /* JS */
      vm._editors.js = CodeMirror(vm.$refs.jsEditorEl, Object.assign({}, baseOpts, {
        value: HCC_DEFAULTS.js,
        mode: 'javascript',
      }));

      /* Set sizes + attach change events */
      ['html','css','js'].forEach(function(t) {
        var ed = vm._editors[t];
        ed.setSize('100%', '100%');
        vm.lineCounts[t] = ed.lineCount();
        vm.charCounts[t] = ed.getValue().length;

        ed.on('change', function(cm, chg) {
          vm.lineCounts[t] = cm.lineCount();
          vm.charCounts[t] = cm.getValue().length;
          if (chg.origin === 'setValue') return;
          if (vm.autoRun) {
            clearTimeout(vm._debounce);
            vm._debounce = setTimeout(function() { vm.run(); }, 600);
          }
        });
      });
    },

    /* ── postMessage listener (console output from iframe) ── */
    _setupMessageListener: function() {
      var vm = this;
      vm._msgHandler = function(e) {
        if (!e.data || e.data.type !== 'hcc-console') return;
        var now = new Date();
        var time = String(now.getHours()).padStart(2,'0') + ':' +
                   String(now.getMinutes()).padStart(2,'0') + ':' +
                   String(now.getSeconds()).padStart(2,'0');
        vm.consoleLogs.push({ level: e.data.level || 'log', msg: e.data.msg, time: time });
        if (vm.consoleLogs.length > 200) vm.consoleLogs.splice(0, 50);
        /* Auto-scroll console */
        vm.$nextTick(function() {
          var body = vm.$refs.consoleBody;
          if (body) body.scrollTop = body.scrollHeight;
        });
        /* Surface JS errors */
        if (e.data.level === 'error') vm.previewError = e.data.msg;
      };
      window.addEventListener('message', vm._msgHandler);
    },

    /* ── Get current value from editor or fallback textarea ── */
    _getVal: function(tab) {
      if (this._fallback) {
        var ta = this.$refs[tab + 'Fallback'];
        return ta ? ta.value : '';
      }
      return this._editors[tab] ? this._editors[tab].getValue() : '';
    },

    /* ── Run ── */
    run: function() {
      this.previewError = '';
      var html = this._getVal('html');
      var css  = this._getVal('css');
      var js   = this._getVal('js');

      /* Inject console-capture shim + user JS */
      var consoleShim = [
        '(function(){',
        '  var _c={log:console.log,warn:console.warn,error:console.error,info:console.info};',
        '  function _s(lvl,args){',
        '    var msg=Array.prototype.slice.call(args).map(function(a){',
        '      if(typeof a==="object"){try{return JSON.stringify(a,null,2);}catch(e){return String(a);}}',
        '      return String(a);',
        '    }).join(" ");',
        '    try{window.parent.postMessage({type:"hcc-console",level:lvl,msg:msg},"*");}catch(e){}',
        '  }',
        '  ["log","warn","error","info"].forEach(function(l){',
        '    console[l]=function(){_c[l].apply(console,arguments);_s(l,arguments);};',
        '  });',
        '  window.onerror=function(m,s,line){',
        '    window.parent.postMessage({type:"hcc-console",level:"error",msg:"[Error] "+m+" (line "+line+")"},"*");',
        '    return false;',
        '  };',
        '  window.addEventListener("unhandledrejection",function(e){',
        '    window.parent.postMessage({type:"hcc-console",level:"error",msg:"[Promise] "+(e.reason&&e.reason.message?e.reason.message:String(e.reason))},"*");',
        '  });',
        '})();'
      ].join('\n');

      var doc = [
        '<!DOCTYPE html>',
        '<html>',
        '<head>',
        '<meta charset="UTF-8">',
        '<meta name="viewport" content="width=device-width,initial-scale=1">',
        '<style>' + css + '<\/style>',
        '<\/head>',
        '<body>',
        html,
        '<script>' + consoleShim + '\n' + js + '<\/script>',
        '<\/body>',
        '<\/html>'
      ].join('\n');

      this.$refs.preview.srcdoc = doc;
      this.statusMsg = 'Ran at ' + new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',second:'2-digit'});
      this.lastRunOk = true;
    },

    /* ── Tab switching ── */
    switchTab: function(tab) {
      var vm = this;
      /* Show/hide manually so CodeMirror doesn't lose layout info */
      ['html','css','js'].forEach(function(t) {
        var w = vm.$refs[t + 'Wrap'];
        if (w) w.style.display = t === tab ? 'block' : 'none';
      });
      this.activeTab = tab;
      this.$nextTick(function() {
        if (!vm._fallback && vm._editors[tab]) vm._editors[tab].refresh();
      });
    },

    /* ── Clear current editor ── */
    clearCurrent: function() {
      var tab = this.activeTab;
      if (!this._fallback && this._editors[tab]) {
        this._editors[tab].setValue('');
      } else {
        var ta = this.$refs[tab + 'Fallback'];
        if (ta) ta.value = '';
      }
    },

    /* ── Reset to defaults ── */
    resetAll: function() {
      var vm = this;
      if (!confirm('Reset all editors to the default starter code?')) return;
      ['html','css','js'].forEach(function(t) {
        if (!vm._fallback && vm._editors[t]) {
          vm._editors[t].setValue(HCC_DEFAULTS[t]);
        } else {
          var ta = vm.$refs[t + 'Fallback'];
          if (ta) ta.value = HCC_DEFAULTS[t];
        }
      });
      this.consoleLogs = [];
      this.previewError = '';
      this.run();
    },

    /* ── Load template ── */
    loadTemplate: function(tpl) {
      var vm = this;
      this.showTemplates = false;
      ['html','css','js'].forEach(function(t) {
        var val = tpl[t] || '';
        if (!vm._fallback && vm._editors[t]) {
          vm._editors[t].setValue(val);
        } else {
          var ta = vm.$refs[t + 'Fallback'];
          if (ta) ta.value = val;
        }
      });
      this.consoleLogs = [];
      this.previewError = '';
      this.run();
    },

    /* ── Copy code ── */
    copyCode: function() {
      var val = this._getVal(this.activeTab);
      var vm = this;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(val).then(function() {
          vm.copied = true;
          setTimeout(function(){ vm.copied = false; }, 2000);
        });
      } else {
        /* Fallback */
        var ta = document.createElement('textarea');
        ta.value = val;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        this.copied = true;
        setTimeout(function(){ vm.copied = false; }, 2000);
      }
    },

    /* ── Toggle line wrap ── */
    formatCode: function() {
      var tab = this.activeTab;
      if (!this._fallback && this._editors[tab]) {
        var ed = this._editors[tab];
        ed.setOption('lineWrapping', !ed.getOption('lineWrapping'));
      }
    },

    /* ── Open preview in new tab ── */
    openInNewTab: function() {
      var html = this._getVal('html');
      var css  = this._getVal('css');
      var js   = this._getVal('js');
      var doc = '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>' + css + '<\/style><\/head><body>' + html + '<script>' + js + '<\/script><\/body><\/html>';
      var blob = new Blob([doc], { type: 'text/html' });
      var url  = URL.createObjectURL(blob);
      var w = window.open(url, '_blank');
      /* revoke after window loads */
      if (w) {
        w.addEventListener('load', function() {
          URL.revokeObjectURL(url);
        });
      }
    },

    /* ── Fullscreen workspace ── */
    fullscreen: function() {
      var el = this.$refs.workspace;
      if (!el) return;
      if (!document.fullscreenElement) {
        el.requestFullscreen && el.requestFullscreen();
        el.style.borderRadius = '0';
      } else {
        document.exitFullscreen && document.exitFullscreen();
        el.style.borderRadius = '';
      }
    },

    /* ── Drag-to-resize ── */
    startResize: function(e) {
      var vm = this;
      vm.resizing = true;

      var ws       = vm.$refs.workspace;
      var ep       = vm.$refs.editorsPanel;
      var startX   = e.clientX;
      var startW   = ep.getBoundingClientRect().width;
      var totalW   = ws.getBoundingClientRect().width;

      function onMove(ev) {
        var dx   = ev.clientX - startX;
        var newW = startW + dx;
        var pct  = (newW / totalW) * 100;
        pct = Math.max(20, Math.min(75, pct));
        ep.style.width = pct + '%';
        if (!vm._fallback) {
          Object.values(vm._editors).forEach(function(ed) { if (ed) ed.refresh(); });
        }
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
