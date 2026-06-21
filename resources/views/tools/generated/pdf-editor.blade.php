@extends('layouts.public')

@section('title', $tool->seo_title ?: 'Professional PDF Editor – Annotate, Sign & Edit PDFs Free Online')
@section('description', $tool->seo_description ?: 'Full-featured browser PDF editor. Add text, shapes, signatures, highlights, images and watermarks. Merge, split, rotate pages. 100% private — nothing leaves your device.')

@section('content')
<style>
/* ══ PDF Editor Advanced — pea- prefix ══════════════════════════════ */
[x-cloak]{display:none!important}

/* ── Landing ─────────────────────────────────────────────────────── */
.pea-landing{min-height:calc(100vh - 64px);background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 100%);display:flex;align-items:center;justify-content:center;padding:40px 16px}
.pea-drop{border:2px dashed #4f46e5;border-radius:16px;padding:48px 40px;text-align:center;background:rgba(99,102,241,.05);transition:all .2s;cursor:pointer;max-width:560px;width:100%}
.pea-drop.over{border-color:#818cf8;background:rgba(99,102,241,.12)}
.pea-drop svg{width:56px;height:56px;margin:0 auto 16px;opacity:.7}
.pea-quick-btns{display:flex;gap:10px;justify-content:center;margin-top:20px;flex-wrap:wrap}
.pea-quick-btn{padding:8px 18px;border-radius:8px;font-size:13px;cursor:pointer;border:1px solid #374151;background:#1f2937;color:#e5e7eb;transition:all .15s}
.pea-quick-btn:hover{background:#374151;border-color:#6366f1;color:#a5b4fc}

/* ── Editor shell ────────────────────────────────────────────────── */
.pea-root{display:flex;flex-direction:column;height:calc(100vh - 64px);background:#111827;color:#e5e7eb;overflow:hidden}

/* ── Toolbar ─────────────────────────────────────────────────────── */
.pea-toolbar{display:flex;align-items:center;gap:2px;background:#1f2937;border-bottom:1px solid #374151;padding:4px 8px;flex-shrink:0;flex-wrap:wrap;min-height:46px}
.pea-sep{width:1px;height:24px;background:#374151;margin:0 4px;flex-shrink:0}
.pea-tbtn{display:flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:5px;border:none;background:transparent;color:#9ca3af;cursor:pointer;font-size:14px;transition:background .1s,color .1s;flex-shrink:0;padding:0;position:relative}
.pea-tbtn:hover{background:#374151;color:#e5e7eb}
.pea-tbtn.active{background:#6366f1;color:#fff}
.pea-tbtn:disabled{opacity:.4;cursor:not-allowed}
.pea-tbtn-wide{padding:0 10px;width:auto;font-size:12px;gap:4px}
.pea-tbtn-green{color:#34d399!important}
.pea-tbtn-green:hover{background:#064e3b!important}
.pea-tbtn-red{color:#f87171!important}
.pea-tbtn-red:hover{background:#7f1d1d!important}
.pea-zoom-lbl{font-size:12px;color:#9ca3af;min-width:42px;text-align:center;cursor:pointer;user-select:none}
.pea-color-box{width:24px;height:24px;border-radius:4px;border:2px solid #4b5563;cursor:pointer;flex-shrink:0;position:relative}
.pea-color-box input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}

/* ── Workspace ───────────────────────────────────────────────────── */
.pea-workspace{display:flex;flex:1;overflow:hidden}

/* ── Pages panel ─────────────────────────────────────────────────── */
.pea-pages{width:192px;flex-shrink:0;background:#1f2937;border-right:1px solid #374151;display:flex;flex-direction:column;overflow:hidden}
.pea-pages-hd{padding:7px 10px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;border-bottom:1px solid #374151;display:flex;justify-content:space-between;align-items:center;flex-shrink:0}
.pea-drag-hint{font-size:9px;color:#4b5563;letter-spacing:.5px}
.pea-thumbs{flex:1;overflow-y:auto;padding:8px 6px;display:flex;flex-direction:column;gap:6px}
.pea-tcard{position:relative;border-radius:6px;border:2px solid transparent;overflow:hidden;background:#374151;cursor:pointer;transition:border-color .15s;user-select:none}
.pea-tcard.cur{border-color:#6366f1}
.pea-tcard:hover:not(.cur){border-color:#4b5563}
.pea-tcard img,.pea-tcard canvas{width:100%;display:block}
.pea-tcard-ph{height:90px;display:flex;align-items:center;justify-content:center;color:#4b5563;font-size:11px}
.pea-tnum{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.65);font-size:10px;text-align:center;padding:2px;color:#e5e7eb}
.pea-tacts{position:absolute;top:2px;right:2px;display:none;flex-direction:column;gap:2px}
.pea-tcard:hover .pea-tacts{display:flex}
.pea-tacts-row{display:flex;gap:2px}
.pea-tbtn-xs{background:rgba(0,0,0,.75);border:none;color:#fff;border-radius:3px;width:20px;height:20px;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;flex-shrink:0;line-height:1}
.pea-tbtn-xs:hover{background:#6366f1}
.pea-pages-ops{padding:6px;border-top:1px solid #374151;display:flex;gap:4px;flex-shrink:0;flex-wrap:wrap}
.pea-page-op{flex:1;min-width:calc(50% - 2px);padding:4px 0;border-radius:5px;border:none;font-size:11px;cursor:pointer;font-weight:500;color:#9ca3af;background:#374151;transition:all .1s;white-space:nowrap;text-align:center}
.pea-page-op:hover{background:#4b5563;color:#e5e7eb}
.sortable-ghost{opacity:.4;background:#6366f1!important;border-color:#818cf8!important}
.sortable-chosen{cursor:grabbing!important}

/* ── Canvas area ─────────────────────────────────────────────────── */
.pea-canvas-area{flex:1;overflow:auto;background:#0f172a;display:flex;align-items:flex-start;justify-content:center;padding:20px;position:relative}
.pea-page-wrap{position:relative;display:inline-block;line-height:0;box-shadow:0 4px 28px rgba(0,0,0,.7);border-radius:2px}
.pea-page-wrap canvas{display:block}
.pea-page-wrap .canvas-container{position:absolute!important;top:0!important;left:0!important}
.pea-page-wrap .canvas-container,.pea-page-wrap .lower-canvas,.pea-page-wrap .upper-canvas{background:transparent!important}
.pea-spinner{width:36px;height:36px;border:3px solid #374151;border-top-color:#6366f1;border-radius:50%;animation:peaSpin .7s linear infinite;margin:0}
@keyframes peaSpin{to{transform:rotate(360deg)}}

/* ── Props panel ─────────────────────────────────────────────────── */
.pea-props{width:218px;flex-shrink:0;background:#1f2937;border-left:1px solid #374151;overflow-y:auto;padding:10px}
.pea-ph{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin:0 0 8px}
.pea-prow{margin-bottom:10px}
.pea-prow label{display:block;font-size:11px;color:#6b7280;margin-bottom:3px}
.pea-prow input[type=color]{width:100%;height:28px;border-radius:4px;border:1px solid #374151;background:#374151;cursor:pointer;padding:1px;display:block}
.pea-prow input[type=range]{width:100%;accent-color:#6366f1;margin-top:2px}
.pea-prow input[type=number],.pea-prow select{width:100%;background:#374151;border:1px solid #4b5563;border-radius:4px;color:#e5e7eb;padding:4px 8px;font-size:12px}
.pea-prow select{cursor:pointer}
.pea-chips{display:flex;flex-wrap:wrap;gap:4px;margin-top:5px}
.pea-chip{width:20px;height:20px;border-radius:50%;cursor:pointer;border:2px solid transparent;transition:border-color .1s;flex-shrink:0}
.pea-chip:hover,.pea-chip.on{border-color:#fff}
.pea-note-chip{width:22px;height:22px;border-radius:4px;cursor:pointer;border:2px solid transparent;flex-shrink:0;transition:border-color .1s}
.pea-note-chip:hover,.pea-note-chip.on{border-color:#fff}

/* ── Status bar ──────────────────────────────────────────────────── */
.pea-status{height:26px;background:#1f2937;border-top:1px solid #374151;display:flex;align-items:center;gap:16px;padding:0 14px;font-size:11px;color:#6b7280;flex-shrink:0;overflow:hidden}
.pea-status-ok{color:#34d399}
.pea-status-err{color:#f87171}

/* ── Buttons ─────────────────────────────────────────────────────── */
.pea-btn{padding:6px 14px;border-radius:6px;font-size:12px;cursor:pointer;border:none;font-weight:500;transition:all .15s;display:inline-flex;align-items:center;gap:5px}
.pea-btn-primary{background:#6366f1;color:#fff}.pea-btn-primary:hover{background:#4f46e5}
.pea-btn-secondary{background:#374151;color:#e5e7eb;border:1px solid #4b5563}.pea-btn-secondary:hover{background:#4b5563}
.pea-btn-danger{background:#dc2626;color:#fff}.pea-btn-danger:hover{background:#b91c1c}
.pea-btn-sm{padding:4px 10px;font-size:11px}
.pea-w-full{width:100%;justify-content:center;margin-bottom:5px}

/* ── Modals ──────────────────────────────────────────────────────── */
.pea-modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:9000;display:flex;align-items:center;justify-content:center;padding:16px}
.pea-modal{background:#1f2937;border-radius:12px;border:1px solid #374151;max-width:500px;width:100%;padding:22px;position:relative;max-height:90vh;overflow-y:auto}
.pea-modal h3{font-size:15px;font-weight:700;margin:0 0 14px;color:#f3f4f6}
.pea-modal-x{position:absolute;top:12px;right:14px;background:none;border:none;color:#6b7280;font-size:18px;cursor:pointer;line-height:1}
.pea-modal-x:hover{color:#f3f4f6}
.pea-sig-canvas{border-radius:8px;border:2px solid #374151;cursor:crosshair;background:#fff;display:block;width:100%;touch-action:none}
.pea-wm-preview{border-radius:6px;padding:18px;text-align:center;font-weight:700;opacity:.5;letter-spacing:4px;user-select:none;margin-top:8px;background:repeating-linear-gradient(-45deg,#374151,#374151 8px,#2d3748 8px,#2d3748 16px)}
.pea-merge-row{display:flex;align-items:center;justify-content:space-between;background:#374151;border-radius:6px;padding:7px 10px;font-size:12px;margin-bottom:5px}
.pea-input{width:100%;background:#374151;border:1px solid #4b5563;border-radius:6px;color:#e5e7eb;padding:7px 12px;font-size:13px;box-sizing:border-box}
.pea-grid2{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.pea-modal-foot{display:flex;justify-content:flex-end;gap:8px;margin-top:14px;flex-wrap:wrap}
.pea-find-hit{padding:6px 10px;background:#374151;border-radius:5px;margin-bottom:4px;cursor:pointer;font-size:12px;transition:background .1s}
.pea-find-hit:hover{background:#4b5563}
.pea-divider{height:1px;background:#374151;margin:12px 0}
.pea-presets{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px}
.pea-size-btn{padding:5px 12px;border-radius:6px;border:1px solid #4b5563;background:#374151;color:#9ca3af;cursor:pointer;font-size:12px;transition:all .1s}
.pea-size-btn:hover,.pea-size-btn.on{background:#6366f1;border-color:#6366f1;color:#fff}
</style>

<div x-data="pdfAdvEditor()" x-init="init()" id="pea-app">

  {{-- ══════════════ LANDING ══════════════ --}}
  <section class="pea-landing" x-show="!loaded" x-cloak
    x-on:dragover.prevent="dragging=true"
    x-on:dragleave="dragging=false"
    x-on:drop.prevent="dragging=false; loadFile($event.dataTransfer.files[0])">
    <div class="pea-drop" :class="dragging && 'over'" @click="$refs.fi0.click()">
      <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:#6366f1">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
      </svg>
      <h2 class="text-2xl font-bold text-white mb-2">Professional PDF Editor</h2>
      <p class="text-sm text-gray-400 mb-1">Drop a PDF here, or click to browse</p>
      <p class="text-xs text-gray-500 mb-4">Text · Shapes · Redact · Sign · Highlight · Watermark · Merge &amp; Split</p>
      <p class="text-xs font-semibold" style="color:#6366f1">🔒 100% private — nothing ever leaves your device</p>
      <div class="pea-quick-btns">
        <button class="pea-quick-btn" @click.stop="$refs.fi0.click()">📂 Open PDF</button>
        <button class="pea-quick-btn" @click.stop="openMergeModal()">⊕ Merge PDFs</button>
      </div>
      <input type="file" accept=".pdf,application/pdf" x-ref="fi0" class="hidden"
             x-on:change="loadFile($event.target.files[0]); $event.target.value=''">
      <p class="text-xs mt-4" x-show="loadErr" x-text="loadErr" style="color:#f87171"></p>
    </div>
  </section>

  {{-- ══════════════ EDITOR ══════════════ --}}
  <div class="pea-root" x-show="loaded" x-cloak>

    {{-- ── TOOLBAR ── --}}
    <div class="pea-toolbar">

      {{-- File --}}
      <button class="pea-tbtn pea-tbtn-wide" @click="$refs.fi1.click()" title="Open PDF">📂 <span>Open</span></button>
      <input type="file" accept=".pdf,application/pdf" x-ref="fi1" class="hidden"
             x-on:change="loadFile($event.target.files[0]); $event.target.value=''">
      <button class="pea-tbtn pea-tbtn-wide pea-tbtn-green" @click="downloadPdf()" :disabled="exporting" title="Save PDF (Ctrl+S)">
        <span x-text="exporting ? '⏳' : '⬇'"></span><span x-text="exporting ? 'Saving…' : 'Save PDF'"></span>
      </button>
      <button class="pea-tbtn" @click="exportPageAsPng()" title="Export page as PNG">🖼</button>
      <div class="pea-sep"></div>

      {{-- Undo / Redo / Copy / Paste --}}
      <button class="pea-tbtn" @click="undo()" title="Undo (Ctrl+Z)" :disabled="!undoStack.length" style="font-size:16px">↩</button>
      <button class="pea-tbtn" @click="redo()" title="Redo (Ctrl+Y)" :disabled="!redoStack.length" style="font-size:16px">↪</button>
      <button class="pea-tbtn" @click="copyObj()" title="Copy selected (Ctrl+C)" style="font-size:13px">⎘</button>
      <button class="pea-tbtn" @click="pasteObj()" title="Paste (Ctrl+V)" :disabled="!_clipboard" style="font-size:13px">📋</button>
      <div class="pea-sep"></div>

      {{-- Select / Text --}}
      <button class="pea-tbtn" :class="tool==='select'&&'active'"    @click="setTool('select')"    title="Select (V)">⬡</button>
      <button class="pea-tbtn" :class="tool==='text'&&'active'"      @click="setTool('text')"      title="Add Text (T)" style="font-weight:800;font-size:15px">T</button>
      <button class="pea-tbtn" :class="tool==='note'&&'active'"      @click="setTool('note')"      title="Sticky Note (N)" style="font-size:13px">📌</button>
      <div class="pea-sep"></div>

      {{-- Shapes --}}
      <button class="pea-tbtn" :class="tool==='highlight'&&'active'" @click="setTool('highlight')" title="Highlight" style="font-size:15px">🖍</button>
      <button class="pea-tbtn" :class="tool==='rect'&&'active'"      @click="setTool('rect')"      title="Rectangle">▭</button>
      <button class="pea-tbtn" :class="tool==='ellipse'&&'active'"   @click="setTool('ellipse')"   title="Ellipse">◯</button>
      <button class="pea-tbtn" :class="tool==='line'&&'active'"      @click="setTool('line')"      title="Line" style="font-size:16px">╱</button>
      <button class="pea-tbtn" :class="tool==='arrow'&&'active'"     @click="setTool('arrow')"     title="Arrow" style="font-size:16px">→</button>
      <button class="pea-tbtn" :class="tool==='redact'&&'active'"    @click="setTool('redact')"    title="Redact / Cover Text" style="font-size:12px;background:tool==='redact'?'#6366f1':'transparent'">⬛</button>
      <div class="pea-sep"></div>

      {{-- Draw / Erase --}}
      <button class="pea-tbtn" :class="tool==='draw'&&'active'"      @click="setTool('draw')"      title="Freehand Draw (D)">✏️</button>
      <button class="pea-tbtn" :class="tool==='eraser'&&'active'"    @click="setTool('eraser')"    title="Eraser (E)">🧹</button>
      <div class="pea-sep"></div>

      {{-- Image / Signature --}}
      <button class="pea-tbtn" @click="$refs.imgIn.click()" title="Insert Image">🖼️</button>
      <input type="file" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp" x-ref="imgIn" class="hidden"
             x-on:change="insertImage($event.target.files[0]); $event.target.value=''">
      <button class="pea-tbtn" @click="showSig=true; $nextTick(initSigCanvas)" title="Signature (S)">✍️</button>
      <div class="pea-sep"></div>

      {{-- Colors --}}
      <div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#6b7280">
        <div style="display:flex;flex-direction:column;gap:3px">
          <div style="display:flex;align-items:center;gap:3px">
            <span>Fill</span>
            <div class="pea-color-box" :style="`background:${fillColor||'transparent'};border:2px solid #4b5563`" title="Fill color">
              <input type="color" x-model="fillColor">
            </div>
            <button @click="fillColor=''" style="font-size:10px;color:#6b7280;background:none;border:none;cursor:pointer;padding:0" title="No fill">✕</button>
          </div>
          <div style="display:flex;align-items:center;gap:3px">
            <span>Line</span>
            <div class="pea-color-box" :style="`background:${strokeColor}`" title="Stroke color">
              <input type="color" x-model="strokeColor">
            </div>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:3px">
          <div style="display:flex;align-items:center;gap:3px">
            <span>Txt</span>
            <div class="pea-color-box" :style="`background:${textColor}`" title="Text color">
              <input type="color" x-model="textColor">
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:3px">
            <span>W</span>
            <input type="number" x-model.number="strokeW" min="1" max="30"
                   style="width:34px;background:#374151;border:1px solid #4b5563;border-radius:3px;color:#e5e7eb;padding:1px 4px;font-size:11px">
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:3px">
          <div style="display:flex;align-items:center;gap:3px">
            <span>Sz</span>
            <input type="number" x-model.number="fontSize" min="6" max="120"
                   style="width:34px;background:#374151;border:1px solid #4b5563;border-radius:3px;color:#e5e7eb;padding:1px 4px;font-size:11px">
          </div>
        </div>
      </div>
      <div class="pea-sep"></div>

      {{-- Zoom --}}
      <button class="pea-tbtn" @click="setZoom(zoom-0.15)" title="Zoom out (-)">−</button>
      <span class="pea-zoom-lbl" @click="setZoom(1)" x-text="Math.round(zoom*100)+'%'" title="Reset zoom (click)"></span>
      <button class="pea-tbtn" @click="setZoom(zoom+0.15)" title="Zoom in (+)">+</button>
      <button class="pea-tbtn" @click="fitPage()" title="Fit page to window" style="font-size:11px">⤢</button>
      <div class="pea-sep"></div>

      {{-- Extra tools --}}
      <button class="pea-tbtn pea-tbtn-wide" @click="showWm=true" title="Add Watermark">🔏 WM</button>
      <button class="pea-tbtn pea-tbtn-wide" @click="showPn=true" title="Add Page Numbers">🔢 #</button>
      <button class="pea-tbtn pea-tbtn-wide" @click="showFind=true" title="Find Text in PDF">🔍 Find</button>
      <button class="pea-tbtn pea-tbtn-wide" @click="openMergeModal()" title="Merge PDFs">⊕ Merge</button>
    </div>

    {{-- ── WORKSPACE ── --}}
    <div class="pea-workspace">

      {{-- Pages Panel --}}
      <div class="pea-pages">
        <div class="pea-pages-hd">
          <span>Pages (<span x-text="pages.length"></span>)</span>
          <span class="pea-drag-hint">drag to reorder</span>
        </div>
        <div class="pea-thumbs" id="pea-thumbs">
          <template x-for="(pg, i) in pages" :key="pg.id">
            <div class="pea-tcard" :class="curPage===i&&'cur'" @click="switchPage(i)">
              <img :src="pg.thumb" x-show="pg.thumb" loading="lazy">
              <div class="pea-tcard-ph" x-show="!pg.thumb" style="font-size:9px;color:#4b5563">Loading…</div>
              <div class="pea-tnum" x-text="i+1"></div>
              <div class="pea-tacts">
                <div class="pea-tacts-row">
                  <button class="pea-tbtn-xs" @click.stop="movePgUp(i)"    title="Move up">↑</button>
                  <button class="pea-tbtn-xs" @click.stop="movePgDown(i)"  title="Move down">↓</button>
                  <button class="pea-tbtn-xs" @click.stop="rotatePage(i,90)" title="Rotate">⟳</button>
                </div>
                <div class="pea-tacts-row">
                  <button class="pea-tbtn-xs" @click.stop="dupPage(i)"    title="Duplicate">⎘</button>
                  <button class="pea-tbtn-xs" @click.stop="delPage(i)"    title="Delete" style="color:#f87171">✕</button>
                </div>
              </div>
            </div>
          </template>
        </div>
        <div class="pea-pages-ops">
          <button class="pea-page-op" @click="showPgSize=true">+ Blank</button>
          <button class="pea-page-op" @click="extractPage()" title="Extract current page to new PDF">⊘ Extract</button>
          <button class="pea-page-op" @click="showSplit=true" title="Split PDF by page range">✂ Split</button>
        </div>
      </div>

      {{-- Canvas Area --}}
      <div class="pea-canvas-area" id="pea-scroll">
        <div x-show="rendering"
             style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,0.88);z-index:50;pointer-events:none">
          <div class="pea-spinner"></div>
        </div>
        <div class="pea-page-wrap" id="pea-wrap"
             :style="`transform:scale(${zoom});transform-origin:top center;`">
          <canvas id="pea-pdf"></canvas>
          <canvas id="pea-fc" style="position:absolute;top:0;left:0"></canvas>
        </div>
      </div>

      {{-- Properties Panel --}}
      <div class="pea-props">
        <p class="pea-ph">Properties</p>

        {{-- Select --}}
        <template x-if="tool==='select'">
          <div>
            <p style="font-size:11px;color:#6b7280;margin-bottom:8px">Click to select objects. Drag handles to resize or rotate.</p>
            <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="selectAll()">☑ Select All</button>
            <button class="pea-btn pea-btn-danger pea-btn-sm pea-w-full" @click="delSelected()">🗑 Delete Selected</button>
            <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="bringFwd()">⬆ Bring Forward</button>
            <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="sendBwd()">⬇ Send Backward</button>
            <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="copyObj()">⎘ Copy (Ctrl+C)</button>
            <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="pasteObj()" :disabled="!_clipboard">📋 Paste (Ctrl+V)</button>
          </div>
        </template>

        {{-- Text --}}
        <template x-if="tool==='text'">
          <div>
            <div class="pea-prow">
              <label>Font Size</label>
              <input type="number" x-model.number="fontSize" min="6" max="120" @change="applyToSel()">
            </div>
            <div class="pea-prow">
              <label>Text Color</label>
              <input type="color" x-model="textColor" @change="applyToSel()">
            </div>
            <div class="pea-prow">
              <label>Font</label>
              <select x-model="fontFamily" @change="applyToSel()">
                <option>Helvetica</option><option>Times New Roman</option>
                <option>Courier</option><option>Arial</option><option>Georgia</option>
                <option>Verdana</option><option>Impact</option>
              </select>
            </div>
            <div style="display:flex;gap:6px;margin-bottom:8px">
              <button class="pea-btn pea-btn-secondary pea-btn-sm" :class="bold&&'active'" @click="bold=!bold;applyToSel()" style="font-weight:700;flex:1">B</button>
              <button class="pea-btn pea-btn-secondary pea-btn-sm" :class="italic&&'active'" @click="italic=!italic;applyToSel()" style="font-style:italic;flex:1">I</button>
              <button class="pea-btn pea-btn-secondary pea-btn-sm" :class="uline&&'active'" @click="uline=!uline;applyToSel()" style="flex:1"><u>U</u></button>
            </div>
            <p style="font-size:11px;color:#6b7280">Click on the page to add a text box. Double-click to edit text.</p>
          </div>
        </template>

        {{-- Note --}}
        <template x-if="tool==='note'">
          <div>
            <div class="pea-prow">
              <label>Note Background</label>
              <div class="pea-chips">
                <template x-for="c in ['#fef08a','#bbf7d0','#bfdbfe','#fbcfe8','#e9d5ff','#fed7aa','#ffffff','#1f2937']">
                  <div class="pea-note-chip" :class="noteColor===c&&'on'" :style="`background:${c}`" @click="noteColor=c"></div>
                </template>
              </div>
            </div>
            <div class="pea-prow">
              <label>Text Color</label>
              <input type="color" x-model="textColor">
            </div>
            <div class="pea-prow">
              <label>Font Size</label>
              <input type="number" x-model.number="fontSize" min="8" max="60" @change="applyToSel()">
            </div>
            <p style="font-size:11px;color:#6b7280">Click on the page to place a sticky note.</p>
          </div>
        </template>

        {{-- Redact --}}
        <template x-if="tool==='redact'">
          <div>
            <p style="font-size:11px;color:#9ca3af;margin-bottom:10px">Draw a box to cover / redact text.</p>
            <div class="pea-prow">
              <label>Cover Color</label>
              <div class="pea-chips" style="margin-top:5px">
                <template x-for="c in ['#000000','#ffffff','#1e3a5f','#1a1a1a','#374151']">
                  <div class="pea-chip" :class="redactColor===c&&'on'" :style="`background:${c};border-color:#4b5563`" @click="redactColor=c"></div>
                </template>
              </div>
              <input type="color" x-model="redactColor" style="margin-top:6px">
            </div>
            <p style="font-size:11px;color:#6b7280">Click and drag to draw the redaction box.</p>
          </div>
        </template>

        {{-- Draw / Eraser --}}
        <template x-if="tool==='draw'||tool==='eraser'">
          <div>
            <div class="pea-prow">
              <label>Brush Size (<span x-text="brushSz"></span>px)</label>
              <input type="range" x-model.number="brushSz" min="1" max="60" @input="updBrush()">
            </div>
            <template x-if="tool==='draw'">
              <div class="pea-prow">
                <label>Brush Color</label>
                <input type="color" x-model="brushColor" @change="updBrush()">
                <div class="pea-chips">
                  <template x-for="c in ['#000000','#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#8b5cf6','#ec4899','#ffffff']">
                    <div class="pea-chip" :class="brushColor===c&&'on'" :style="`background:${c};border-color:#4b5563`" @click="brushColor=c;updBrush()"></div>
                  </template>
                </div>
              </div>
            </template>
            <template x-if="tool==='eraser'">
              <p style="font-size:11px;color:#6b7280">Draw over annotations to erase them. Brush size controls eraser width.</p>
            </template>
          </div>
        </template>

        {{-- Shapes / Highlight --}}
        <template x-if="['rect','ellipse','line','arrow','highlight'].includes(tool)">
          <div>
            <template x-if="tool!=='highlight'">
              <div class="pea-prow">
                <label>Fill Color</label>
                <input type="color" x-model="fillColor">
                <button @click="fillColor=''" style="font-size:10px;color:#6b7280;background:none;border:none;cursor:pointer;margin-top:2px">✕ No fill</button>
              </div>
            </template>
            <template x-if="tool==='highlight'">
              <div class="pea-prow">
                <label>Highlight Color</label>
                <div class="pea-chips">
                  <template x-for="c in ['#fef08a','#bbf7d0','#bfdbfe','#fbcfe8','#e9d5ff','#fed7aa']">
                    <div class="pea-chip" :class="fillColor===c&&'on'" :style="`background:${c}`" @click="fillColor=c"></div>
                  </template>
                </div>
              </div>
            </template>
            <template x-if="tool!=='highlight'">
              <div class="pea-prow">
                <label>Stroke Color</label>
                <input type="color" x-model="strokeColor">
              </div>
            </template>
            <div class="pea-prow">
              <label>Opacity (<span x-text="Math.round(shapeOp*100)"></span>%)</label>
              <input type="range" x-model.number="shapeOp" min="0.05" max="1" step="0.05">
            </div>
            <template x-if="tool!=='highlight'">
              <div class="pea-prow">
                <label>Stroke Width</label>
                <input type="number" x-model.number="strokeW" min="0" max="20">
              </div>
            </template>
            <p style="font-size:11px;color:#6b7280;margin-top:4px">Click and drag to draw.</p>
          </div>
        </template>

        <div class="pea-divider"></div>
        <p class="pea-ph">Page</p>
        <p style="font-size:11px;color:#6b7280;margin-bottom:8px">Page <span x-text="curPage+1"></span> of <span x-text="pages.length"></span></p>
        <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="rotatePage(curPage,90)">⟳ Rotate CW</button>
        <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="rotatePage(curPage,-90)">⟲ Rotate CCW</button>
        <button class="pea-btn pea-btn-secondary pea-btn-sm pea-w-full" @click="dupPage(curPage)">⎘ Duplicate Page</button>
        <button class="pea-btn pea-btn-danger pea-btn-sm pea-w-full" @click="delPage(curPage)">🗑 Delete Page</button>
        <div class="pea-divider"></div>
        <p class="pea-ph">File</p>
        <p style="font-size:11px;color:#9ca3af;word-break:break-all;margin-bottom:3px" x-text="fileName"></p>
        <p style="font-size:11px;color:#6b7280;margin-bottom:8px" x-text="fileSize"></p>
        <div class="pea-divider"></div>
        <p style="font-size:10px;color:#4b5563;line-height:1.5">
          Ctrl+Z Undo · Ctrl+Y Redo<br>
          Ctrl+C Copy · Ctrl+V Paste<br>
          Ctrl+A Select All · Del Delete<br>
          Esc Select tool · ← → Page nav
        </p>
      </div>
    </div>

    {{-- Status Bar --}}
    <div class="pea-status">
      <span>Page <span x-text="curPage+1"></span>/<span x-text="pages.length"></span></span>
      <span>Tool: <span x-text="tool" style="color:#a5b4fc;text-transform:capitalize"></span></span>
      <span>Zoom: <span x-text="Math.round(zoom*100)+'%'"></span></span>
      <span x-show="_clipboard" style="color:#a5b4fc">📋 Clipboard ready</span>
      <span class="pea-status-ok" x-show="statusOk" x-text="statusOk"></span>
      <span class="pea-status-err" x-show="statusErr" x-text="statusErr"></span>
    </div>
  </div>

  {{-- ══════════════ SIGNATURE MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showSig" x-cloak @click.self="showSig=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showSig=false">✕</button>
      <h3>✍️ Signature</h3>
      <canvas id="pea-sig" class="pea-sig-canvas" width="456" height="140"></canvas>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
        <button class="pea-btn pea-btn-secondary pea-btn-sm" @click="clearSig()">🗑 Clear</button>
        <button class="pea-btn pea-btn-secondary pea-btn-sm" @click="$refs.sigImg.click()">📂 Upload</button>
        <input type="file" accept="image/*" x-ref="sigImg" class="hidden"
               x-on:change="loadSigImg($event.target.files[0]); $event.target.value=''">
        <button class="pea-btn pea-btn-primary pea-btn-sm" @click="placeSig()" style="margin-left:auto">✔ Place on PDF</button>
      </div>
      <template x-if="savedSigs.length">
        <div style="margin-top:12px">
          <p style="font-size:11px;color:#6b7280;margin-bottom:6px">Recent signatures — click to reuse:</p>
          <div style="display:flex;gap:6px;flex-wrap:wrap">
            <template x-for="(s,i) in savedSigs" :key="i">
              <img :src="s" @click="placeSigData(s)" style="height:36px;background:#fff;border-radius:4px;cursor:pointer;border:2px solid #374151;padding:2px" title="Click to place">
            </template>
          </div>
        </div>
      </template>
    </div>
  </div>

  {{-- ══════════════ WATERMARK MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showWm" x-cloak @click.self="showWm=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showWm=false">✕</button>
      <h3>🔏 Add Watermark</h3>
      <div class="pea-prow"><label>Text</label><input class="pea-input" type="text" x-model="wmText" placeholder="e.g. CONFIDENTIAL"></div>
      <div class="pea-grid2">
        <div class="pea-prow"><label>Color</label><input type="color" x-model="wmColor"></div>
        <div class="pea-prow"><label>Size (<span x-text="wmSz"></span>px)</label><input type="range" x-model.number="wmSz" min="20" max="120"></div>
        <div class="pea-prow"><label>Opacity (<span x-text="Math.round(wmOp*100)"></span>%)</label><input type="range" x-model.number="wmOp" min="0.05" max="1" step="0.05"></div>
        <div class="pea-prow"><label>Angle (°)</label><input type="number" x-model.number="wmAngle" min="-90" max="90"></div>
      </div>
      <div class="pea-wm-preview" :style="`color:${wmColor};font-size:${wmSz/2}px;opacity:${wmOp}`" x-text="wmText||'WATERMARK'"></div>
      <div class="pea-modal-foot">
        <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#9ca3af;cursor:pointer;margin-right:auto">
          <input type="checkbox" x-model="wmAll" style="accent-color:#6366f1"> All pages
        </label>
        <button class="pea-btn pea-btn-secondary" @click="showWm=false">Cancel</button>
        <button class="pea-btn pea-btn-primary" @click="applyWm()">Apply</button>
      </div>
    </div>
  </div>

  {{-- ══════════════ PAGE NUMBERS MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showPn" x-cloak @click.self="showPn=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showPn=false">✕</button>
      <h3>🔢 Page Numbers</h3>
      <div class="pea-grid2">
        <div class="pea-prow"><label>Position</label>
          <select x-model="pnPos">
            <option value="bc">Bottom Center</option><option value="br">Bottom Right</option>
            <option value="bl">Bottom Left</option><option value="tc">Top Center</option>
            <option value="tr">Top Right</option><option value="tl">Top Left</option>
          </select>
        </div>
        <div class="pea-prow"><label>Start From</label><input type="number" x-model.number="pnStart" min="0" max="999"></div>
        <div class="pea-prow"><label>Prefix</label><input class="pea-input" type="text" x-model="pnPrefix" placeholder="Page "></div>
        <div class="pea-prow"><label>Suffix</label><input class="pea-input" type="text" x-model="pnSuffix" placeholder=""></div>
        <div class="pea-prow"><label>Font Size</label><input type="number" x-model.number="pnSz" min="8" max="32"></div>
        <div class="pea-prow"><label>Color</label><input type="color" x-model="pnColor"></div>
      </div>
      <div class="pea-modal-foot">
        <button class="pea-btn pea-btn-secondary" @click="showPn=false">Cancel</button>
        <button class="pea-btn pea-btn-primary" @click="applyPn()">Apply to All Pages</button>
      </div>
    </div>
  </div>

  {{-- ══════════════ FIND MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showFind" x-cloak @click.self="showFind=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showFind=false">✕</button>
      <h3>🔍 Find Text</h3>
      <div style="display:flex;gap:8px;margin-bottom:8px">
        <input class="pea-input" type="text" x-model="findQ" @keyup.enter="doFind()" placeholder="Search text…" style="flex:1">
        <button class="pea-btn pea-btn-primary" @click="doFind()">Search</button>
      </div>
      <p style="font-size:12px;color:#6b7280;margin-bottom:6px" x-text="findMsg"></p>
      <template x-if="findHits.length">
        <div style="max-height:220px;overflow-y:auto">
          <template x-for="r in findHits" :key="r.page">
            <div class="pea-find-hit" @click="switchPage(r.page); showFind=false">
              <strong style="color:#a5b4fc">Page <span x-text="r.page+1"></span></strong>
              <span style="color:#9ca3af;margin-left:6px" x-text="r.snippet"></span>
            </div>
          </template>
        </div>
      </template>
    </div>
  </div>

  {{-- ══════════════ MERGE MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showMerge" x-cloak @click.self="showMerge=false">
    <div class="pea-modal" style="max-width:540px">
      <button class="pea-modal-x" @click="showMerge=false">✕</button>
      <h3>⊕ Merge PDFs</h3>
      <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Add 2+ PDF files in the order you want, then download merged.</p>
      <div>
        <template x-for="(f,i) in mFiles" :key="i">
          <div class="pea-merge-row">
            <span style="color:#a5b4fc;margin-right:8px" x-text="i+1+'. '"></span>
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="f.name"></span>
            <button @click="mFiles.splice(i,1)" style="background:none;border:none;color:#f87171;cursor:pointer;margin-left:8px">✕</button>
          </div>
        </template>
      </div>
      <div class="pea-modal-foot">
        <button class="pea-btn pea-btn-secondary" @click="$refs.mIn.click()">📂 Add PDF(s)</button>
        <input type="file" accept=".pdf,application/pdf" multiple x-ref="mIn" class="hidden"
               x-on:change="addMergeFiles($event.target.files); $event.target.value=''">
        <button class="pea-btn pea-btn-primary" @click="doMerge()"
                :disabled="mFiles.length<2||merging" style="margin-left:auto">
          <span x-text="merging?'Merging…':'⊕ Merge & Download'"></span>
        </button>
      </div>
    </div>
  </div>

  {{-- ══════════════ ADD BLANK PAGE SIZE MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showPgSize" x-cloak @click.self="showPgSize=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showPgSize=false">✕</button>
      <h3>+ Add Blank Page</h3>
      <p style="font-size:12px;color:#6b7280;margin-bottom:10px">Choose a preset or enter custom dimensions (in points; 1pt ≈ 0.353mm).</p>
      <div class="pea-presets">
        <template x-for="p in pgPresets" :key="p.name">
          <button class="pea-size-btn" :class="pgSzPreset===p.name&&'on'" @click="pgSzPreset=p.name;pgSzW=p.w;pgSzH=p.h" x-text="p.name"></button>
        </template>
        <button class="pea-size-btn" :class="pgSzPreset==='custom'&&'on'" @click="pgSzPreset='custom'">Custom</button>
      </div>
      <div class="pea-grid2">
        <div class="pea-prow">
          <label>Width (pt)</label>
          <input type="number" x-model.number="pgSzW" min="72" max="5000" @input="pgSzPreset='custom'">
        </div>
        <div class="pea-prow">
          <label>Height (pt)</label>
          <input type="number" x-model.number="pgSzH" min="72" max="5000" @input="pgSzPreset='custom'">
        </div>
      </div>
      <p style="font-size:11px;color:#6b7280;margin-top:-4px">≈ <span x-text="Math.round(pgSzW/2.835)"></span> × <span x-text="Math.round(pgSzH/2.835)"></span> mm</p>
      <div class="pea-modal-foot">
        <button class="pea-btn pea-btn-secondary" @click="showPgSize=false">Cancel</button>
        <button class="pea-btn pea-btn-primary" @click="addBlankPage()">Add Page</button>
      </div>
    </div>
  </div>

  {{-- ══════════════ SPLIT RANGE MODAL ══════════════ --}}
  <div class="pea-modal-bg" x-show="showSplit" x-cloak @click.self="showSplit=false">
    <div class="pea-modal">
      <button class="pea-modal-x" @click="showSplit=false">✕</button>
      <h3>✂ Split PDF</h3>
      <p style="font-size:12px;color:#6b7280;margin-bottom:12px">Extract a range of pages into a new PDF download. Leaves the current document unchanged.</p>
      <div class="pea-grid2">
        <div class="pea-prow">
          <label>From Page</label>
          <input type="number" x-model.number="splitFrom" :min="1" :max="pages.length">
        </div>
        <div class="pea-prow">
          <label>To Page</label>
          <input type="number" x-model.number="splitTo" :min="splitFrom" :max="pages.length">
        </div>
      </div>
      <p style="font-size:11px;color:#6b7280">Will extract <span x-text="Math.max(0, splitTo-splitFrom+1)"></span> page(s) · Total pages: <span x-text="pages.length"></span></p>
      <div class="pea-modal-foot">
        <button class="pea-btn pea-btn-secondary" @click="showSplit=false">Cancel</button>
        <button class="pea-btn pea-btn-primary" @click="splitRange()">✂ Extract & Download</button>
      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
function pdfAdvEditor() {
  return {
    // ── Core ─────────────────────────────────────────────────────────
    loaded: false, rendering: false, exporting: false, dragging: false,
    pdfBytes: null, pdfJsDoc: null, fileName: '', fileSize: '',
    pages: [], curPage: 0, _idSeq: 0,
    zoom: 1.0, loadErr: '',

    // ── Tools ────────────────────────────────────────────────────────
    tool: 'select',
    fillColor: '', strokeColor: '#ef4444', strokeW: 2,
    textColor: '#000000', fontFamily: 'Helvetica', fontSize: 16,
    bold: false, italic: false, uline: false,
    brushSz: 5, brushColor: '#000000',
    shapeOp: 1.0,
    redactColor: '#000000',
    noteColor: '#fef08a',

    // ── Fabric ───────────────────────────────────────────────────────
    fc: null, _dwg: false, _dwgObj: null, _dwgStart: null,
    _clipboard: null,

    // ── Modals ───────────────────────────────────────────────────────
    showSig: false, showWm: false, showPn: false, showFind: false,
    showMerge: false, showPgSize: false, showSplit: false,

    // ── Signature ────────────────────────────────────────────────────
    _sigCtx: null, _sigDwg: false, savedSigs: [],

    // ── Watermark ────────────────────────────────────────────────────
    wmText: '', wmColor: '#888888', wmSz: 60, wmOp: 0.3, wmAngle: -45, wmAll: true,

    // ── Page Numbers ─────────────────────────────────────────────────
    pnPos: 'bc', pnStart: 1, pnPrefix: '', pnSuffix: '', pnSz: 12, pnColor: '#555555',

    // ── Find ─────────────────────────────────────────────────────────
    findQ: '', findMsg: '', findHits: [],

    // ── Merge ────────────────────────────────────────────────────────
    mFiles: [], merging: false,

    // ── Page Size ────────────────────────────────────────────────────
    pgSzPreset: 'A4', pgSzW: 595, pgSzH: 842,
    pgPresets: [
      { name:'A4',    w:595,  h:842  },
      { name:'Letter',w:612,  h:792  },
      { name:'Legal', w:612,  h:1008 },
      { name:'A3',    w:842,  h:1191 },
      { name:'A5',    w:420,  h:595  },
    ],

    // ── Split ────────────────────────────────────────────────────────
    splitFrom: 1, splitTo: 1,

    // ── History ──────────────────────────────────────────────────────
    undoStack: [], redoStack: [],

    // ── Status ───────────────────────────────────────────────────────
    statusOk: '', statusErr: '',

    BASE: 1.5,

    // ════════════════════════════════════════════════════════════════
    // LIFECYCLE
    // ════════════════════════════════════════════════════════════════
    init() {
      pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';

      document.addEventListener('keydown', e => {
        if (!this.loaded) return;
        const ctrl = e.ctrlKey || e.metaKey;
        const tag  = document.activeElement ? document.activeElement.tagName : '';
        const editing = (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT');

        if (ctrl && e.key === 'z') { e.preventDefault(); this.undo(); return; }
        if (ctrl && (e.key === 'y' || (e.shiftKey && e.key === 'z'))) { e.preventDefault(); this.redo(); return; }
        if (ctrl && e.key === 's') { e.preventDefault(); this.downloadPdf(); return; }

        if (!editing) {
          if (ctrl && e.key === 'c') { e.preventDefault(); this.copyObj(); return; }
          if (ctrl && e.key === 'v') { e.preventDefault(); this.pasteObj(); return; }
          if (ctrl && e.key === 'a') { e.preventDefault(); this.selectAll(); return; }
          if (e.key === 'Escape') { this.setTool('select'); return; }
          if (e.key === 'v' || e.key === 'V') { this.setTool('select'); return; }
          if (e.key === 't' || e.key === 'T') { this.setTool('text'); return; }
          if (e.key === 'n' || e.key === 'N') { this.setTool('note'); return; }
          if (e.key === 'd' || e.key === 'D') { this.setTool('draw'); return; }
          if (e.key === 'e' || e.key === 'E') { this.setTool('eraser'); return; }
          if (e.key === 's' || e.key === 'S') { this.showSig = true; this.$nextTick(() => this.initSigCanvas()); return; }

          // Arrow key page navigation (only when no object selected + not editing text)
          const activeObj = this.fc ? this.fc.getActiveObject() : null;
          if (!activeObj || !activeObj.isEditing) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
              e.preventDefault();
              if (this.curPage < this.pages.length - 1) this.switchPage(this.curPage + 1);
              return;
            }
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
              e.preventDefault();
              if (this.curPage > 0) this.switchPage(this.curPage - 1);
              return;
            }
          }

          if (e.key === 'Delete' || e.key === 'Backspace') {
            if (this.fc) {
              const obj = this.fc.getActiveObject();
              if (obj && !obj.isEditing) {
                if (obj.type === 'activeSelection') {
                  obj.forEachObject(o => this.fc.remove(o));
                  this.fc.discardActiveObject();
                } else {
                  this.fc.remove(obj);
                  this.fc.discardActiveObject();
                }
                this.fc.renderAll();
                this.pushHistory();
              }
            }
          }
        }
      });
    },

    // ════════════════════════════════════════════════════════════════
    // PDF LOADING
    // ════════════════════════════════════════════════════════════════
    async loadFile(file) {
      if (!file) return;
      if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
        this.loadErr = 'Please select a valid PDF file.'; return;
      }
      this.loadErr = ''; this.rendering = true;
      try {
        const buf = await file.arrayBuffer();
        this.pdfBytes = new Uint8Array(buf);
        this.fileName = file.name;
        this.fileSize = this.fmtSz(file.size);
        this.pdfJsDoc = await pdfjsLib.getDocument({ data: this.pdfBytes.slice() }).promise;
        const n = this.pdfJsDoc.numPages;
        this.pages = Array.from({ length: n }, (_, i) => ({
          id: ++this._idSeq, orig: i, rot: 0, thumb: null, fcJSON: null
        }));
        this.curPage  = 0;
        this.undoStack = []; this.redoStack = [];
        this.loaded = true;
        await this.$nextTick();
        this.initFc();
        this.initSortable();
        await this.renderPage();
        this.renderThumbs();
        this.splitFrom = 1; this.splitTo = n;
        this.ok('PDF loaded — ' + n + ' page' + (n !== 1 ? 's' : ''));
      } catch(e) {
        this.loadErr = 'Could not load PDF. File may be password-protected or corrupted.';
        this.loaded = false;
        console.error('[pea] load:', e);
      }
      this.rendering = false;
    },

    // ════════════════════════════════════════════════════════════════
    // SORTABLE PAGES
    // ════════════════════════════════════════════════════════════════
    initSortable() {
      const el = document.getElementById('pea-thumbs');
      if (!el || !window.Sortable) return;
      if (el._sortable) { el._sortable.destroy(); }
      el._sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: evt => {
          if (evt.oldIndex === evt.newIndex) return;
          if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
          const moved = this.pages.splice(evt.oldIndex, 1)[0];
          this.pages.splice(evt.newIndex, 0, moved);
          // Track curPage through the reorder
          let cur = this.curPage;
          if (cur === evt.oldIndex) {
            cur = evt.newIndex;
          } else if (evt.oldIndex < cur && cur <= evt.newIndex) {
            cur--;
          } else if (evt.newIndex <= cur && cur < evt.oldIndex) {
            cur++;
          }
          this.curPage = cur;
          this.ok('Page moved');
        }
      });
    },

    movePgUp(i) {
      if (i <= 0) return;
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      const a = this.pages[i]; const b = this.pages[i - 1];
      this.pages.splice(i - 1, 2, a, b);
      if      (this.curPage === i)     this.curPage = i - 1;
      else if (this.curPage === i - 1) this.curPage = i;
    },

    movePgDown(i) {
      if (i >= this.pages.length - 1) return;
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      const a = this.pages[i]; const b = this.pages[i + 1];
      this.pages.splice(i, 2, b, a);
      if      (this.curPage === i)     this.curPage = i + 1;
      else if (this.curPage === i + 1) this.curPage = i;
    },

    // ════════════════════════════════════════════════════════════════
    // RENDER
    // ════════════════════════════════════════════════════════════════
    async renderPage() {
      if (!this.pdfJsDoc || !this.fc) return;
      this.rendering = true;
      try {
        const pg   = this.pages[this.curPage];
        const pdfPg = await this.pdfJsDoc.getPage(pg.orig + 1);
        const vp   = pdfPg.getViewport({ scale: this.BASE, rotation: pg.rot || 0 });
        const cv   = document.getElementById('pea-pdf');
        cv.width = vp.width; cv.height = vp.height;
        await pdfPg.render({ canvasContext: cv.getContext('2d'), viewport: vp }).promise;

        this.fc.setWidth(vp.width); this.fc.setHeight(vp.height);
        if (this.fc.wrapperEl) {
          this.fc.wrapperEl.style.width  = vp.width  + 'px';
          this.fc.wrapperEl.style.height = vp.height + 'px';
        }
        this.fc.calcOffset();

        const json = pg.fcJSON;
        if (json && json.objects && json.objects.length > 0) {
          await new Promise(r => this.fc.loadFromJSON(json, () => { this.fc.renderAll(); r(); }));
        } else {
          this.fc.clear();
        }
        this.setTool(this.tool);
      } catch(e) {
        console.error('[pea] render:', e);
        this.err('Render error: ' + e.message);
      }
      this.rendering = false;
    },

    async renderThumbs() {
      for (let i = 0; i < this.pages.length; i++) {
        await this.renderThumb(i);
      }
    },

    async renderThumb(i) {
      try {
        const pg    = this.pages[i];
        const pdfPg = await this.pdfJsDoc.getPage(pg.orig + 1);
        const vp    = pdfPg.getViewport({ scale: 0.28, rotation: pg.rot || 0 });
        const c = document.createElement('canvas');
        c.width = vp.width; c.height = vp.height;
        await pdfPg.render({ canvasContext: c.getContext('2d'), viewport: vp }).promise;
        this.pages[i].thumb = c.toDataURL('image/jpeg', 0.6);
      } catch(e) {}
    },

    // ════════════════════════════════════════════════════════════════
    // FABRIC.JS
    // ════════════════════════════════════════════════════════════════
    initFc() {
      if (this.fc) { try { this.fc.dispose(); } catch(e){} }
      const el = document.getElementById('pea-fc');
      this.fc  = new fabric.Canvas(el, {
        backgroundColor: null, preserveObjectStacking: true, enableRetinaScaling: false
      });
      if (this.fc.wrapperEl) {
        Object.assign(this.fc.wrapperEl.style, { position:'absolute', top:'0', left:'0' });
      }
      this.fc.on('object:modified', () => this.pushHistory());
      this.fc.on('path:created',    () => this.pushHistory());
      this.fc.on('mouse:down', e => this.onDown(e));
      this.fc.on('mouse:move', e => this.onMove(e));
      this.fc.on('mouse:up',   e => this.onUp(e));
    },

    setTool(t) {
      this.tool = t;
      if (!this.fc) return;
      this.fc.isDrawingMode = false;
      const isSel = (t === 'select');
      this.fc.selection = isSel;
      this.fc.getObjects().forEach(o => { o.selectable = isSel; o.evented = isSel; });

      if (t === 'draw') {
        this.fc.isDrawingMode = true; this.updBrush();
      } else if (t === 'eraser') {
        this.fc.isDrawingMode = true;
        const b = new fabric.PencilBrush(this.fc);
        b.width = this.brushSz * 5; b.color = '#ffffff';
        this.fc.freeDrawingBrush = b;
      }
      this.fc.renderAll();
    },

    updBrush() {
      if (!this.fc) return;
      const b = new fabric.PencilBrush(this.fc);
      b.width = this.brushSz; b.color = this.brushColor;
      this.fc.freeDrawingBrush = b;
    },

    onDown(e) {
      const t = this.tool;
      if (['rect','ellipse','line','arrow','highlight','redact'].includes(t)) {
        this._dwg = true;
        const p = this.fc.getPointer(e.e);
        this._dwgStart = { x: p.x, y: p.y };

        let fc, sc, op, sw;
        if (t === 'redact') {
          fc = this.redactColor || '#000000'; sc = 'transparent'; op = 1; sw = 0;
        } else if (t === 'highlight') {
          fc = this.fillColor || '#fef08a'; sc = 'transparent'; op = 0.4; sw = 0;
        } else {
          fc = this.fillColor || 'transparent';
          sc = this.strokeColor || '#000000';
          op = this.shapeOp || 1; sw = this.strokeW;
        }

        if (t === 'rect' || t === 'highlight' || t === 'redact') {
          this._dwgObj = new fabric.Rect({
            left:p.x, top:p.y, width:0, height:0,
            fill:fc, stroke:sc, strokeWidth:sw,
            opacity:op, selectable:false, evented:false
          });
        } else if (t === 'ellipse') {
          this._dwgObj = new fabric.Ellipse({
            left:p.x, top:p.y, rx:0, ry:0,
            fill:fc, stroke:sc, strokeWidth:sw,
            opacity:op, selectable:false, evented:false
          });
        } else if (t === 'line' || t === 'arrow') {
          this._dwgObj = new fabric.Line([p.x,p.y,p.x,p.y], {
            stroke:sc, strokeWidth:sw, opacity:op, selectable:false, evented:false
          });
        }
        if (this._dwgObj) this.fc.add(this._dwgObj);

      } else if (t === 'text') {
        const p  = this.fc.getPointer(e.e);
        const tb = new fabric.IText('Type here', {
          left:p.x, top:p.y, fontSize:this.fontSize,
          fill:this.textColor, fontFamily:this.fontFamily,
          fontWeight: this.bold   ? 'bold'   : 'normal',
          fontStyle:  this.italic ? 'italic' : 'normal',
          underline: this.uline,
          selectable:true, evented:true, editable:true
        });
        this.fc.add(tb);
        this.fc.setActiveObject(tb);
        tb.enterEditing(); tb.selectAll();
        this.fc.renderAll();
        this.pushHistory();

      } else if (t === 'note') {
        const p    = this.fc.getPointer(e.e);
        const note = new fabric.IText('✎ Click to edit', {
          left:p.x, top:p.y,
          fontSize:this.fontSize || 13,
          fill:this.textColor || '#111827',
          backgroundColor:this.noteColor || '#fef08a',
          padding:8, fontFamily:'Helvetica',
          selectable:true, evented:true, editable:true, width:180
        });
        this.fc.add(note);
        this.fc.setActiveObject(note);
        note.enterEditing(); note.selectAll();
        this.fc.renderAll();
        this.pushHistory();
      }
    },

    onMove(e) {
      if (!this._dwg || !this._dwgObj) return;
      const p = this.fc.getPointer(e.e), s = this._dwgStart, t = this.tool;
      if (t === 'rect' || t === 'highlight' || t === 'redact') {
        this._dwgObj.set({ left:Math.min(p.x,s.x), top:Math.min(p.y,s.y), width:Math.abs(p.x-s.x), height:Math.abs(p.y-s.y) });
      } else if (t === 'ellipse') {
        this._dwgObj.set({ left:Math.min(p.x,s.x), top:Math.min(p.y,s.y), rx:Math.abs(p.x-s.x)/2, ry:Math.abs(p.y-s.y)/2 });
      } else if (t === 'line' || t === 'arrow') {
        this._dwgObj.set({ x2:p.x, y2:p.y });
      }
      this._dwgObj.setCoords(); this.fc.renderAll();
    },

    onUp(e) {
      if (!this._dwg) return;
      this._dwg = false;
      if (this._dwgObj) {
        if (this.tool === 'arrow') {
          const ln  = this._dwgObj;
          const ang = Math.atan2(ln.y2 - ln.y1, ln.x2 - ln.x1) * 180 / Math.PI;
          const head = new fabric.Triangle({
            left:ln.x2, top:ln.y2, width:12, height:14,
            fill:this.strokeColor || '#ef4444', angle:ang + 90,
            originX:'center', originY:'center', selectable:false, evented:false
          });
          this.fc.add(head);
        }
        this._dwgObj.selectable = (this.tool === 'select');
        this._dwgObj.evented    = (this.tool === 'select');
        this._dwgObj = null;
        this.pushHistory();
      }
    },

    applyToSel() {
      if (!this.fc) return;
      const obj = this.fc.getActiveObject();
      if (!obj) return;
      if (['i-text','textbox','text'].includes(obj.type)) {
        obj.set({
          fontSize:   this.fontSize,   fill:       this.textColor,
          fontFamily: this.fontFamily, fontWeight: this.bold   ? 'bold'   : 'normal',
          fontStyle:  this.italic ? 'italic' : 'normal', underline: this.uline
        });
      }
      this.fc.renderAll();
    },

    selectAll() {
      if (!this.fc) return;
      const objs = this.fc.getObjects();
      if (!objs.length) return;
      const sel = new fabric.ActiveSelection(objs, { canvas: this.fc });
      this.fc.setActiveObject(sel);
      this.fc.renderAll();
    },

    copyObj() {
      if (!this.fc) return;
      const obj = this.fc.getActiveObject();
      if (!obj) return;
      obj.clone(cloned => { this._clipboard = cloned; this.ok('Copied to clipboard'); });
    },

    pasteObj() {
      if (!this.fc || !this._clipboard) return;
      this._clipboard.clone(cloned => {
        const off = 18;
        cloned.set({
          left:     (this._clipboard.left || 0) + off,
          top:      (this._clipboard.top  || 0) + off,
          evented:  true, selectable: true
        });
        if (cloned.type === 'activeSelection') {
          cloned.canvas = this.fc;
          cloned.forEachObject(o => this.fc.add(o));
          cloned.setCoords();
        } else {
          this.fc.add(cloned);
        }
        this.fc.setActiveObject(cloned);
        this.fc.renderAll();
        this._clipboard = cloned;
        this.pushHistory();
        this.ok('Pasted');
      });
    },

    delSelected() {
      if (!this.fc) return;
      const obj = this.fc.getActiveObject();
      if (!obj) return;
      if (obj.type === 'activeSelection') {
        obj.forEachObject(o => this.fc.remove(o));
        this.fc.discardActiveObject();
      } else {
        this.fc.remove(obj);
      }
      this.fc.renderAll();
      this.pushHistory();
    },

    bringFwd() { if (this.fc) { const o = this.fc.getActiveObject(); if (o) this.fc.bringForward(o); } },
    sendBwd()  { if (this.fc) { const o = this.fc.getActiveObject(); if (o) this.fc.sendBackwards(o); } },

    fitPage() {
      const area = document.getElementById('pea-scroll');
      const cv   = document.getElementById('pea-pdf');
      if (!area || !cv || !cv.width) return;
      const aW = area.clientWidth  - 44;
      const aH = area.clientHeight - 44;
      this.setZoom(Math.min(aW / cv.width, aH / cv.height));
    },

    // ════════════════════════════════════════════════════════════════
    // PAGE SWITCHING
    // ════════════════════════════════════════════════════════════════
    async switchPage(i) {
      if (i < 0 || i >= this.pages.length || i === this.curPage) return;
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      this.curPage = i;
      await this.renderPage();
    },

    // ════════════════════════════════════════════════════════════════
    // PAGE OPERATIONS
    // ════════════════════════════════════════════════════════════════
    async addBlankPage() {
      this.showPgSize = false;
      const { PDFDocument } = PDFLib;
      const src = await PDFDocument.load(this.pdfBytes.slice(0));
      const w   = this.pgSzW || 595;
      const h   = this.pgSzH || 842;
      src.addPage([w, h]);
      const newBytes    = await src.save();
      this.pdfBytes     = newBytes;
      this.pdfJsDoc     = await pdfjsLib.getDocument({ data: newBytes.slice() }).promise;
      const origCount   = this.pdfJsDoc.numPages - 1;
      const ni          = this.curPage + 1;
      this.pages.splice(ni, 0, { id:++this._idSeq, orig:origCount, rot:0, thumb:null, fcJSON:null });
      await this.renderThumb(ni);
      await this.switchPage(ni);
      this.ok('Blank page added (' + this.pgSzPreset + ')');
    },

    async dupPage(i) {
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      const pg    = this.pages[i];
      const clone = {
        id: ++this._idSeq, orig: pg.orig, rot: pg.rot,
        thumb: pg.thumb,
        fcJSON: pg.fcJSON ? JSON.parse(JSON.stringify(pg.fcJSON)) : null
      };
      this.pages.splice(i + 1, 0, clone);
      await this.switchPage(i + 1);
    },

    async delPage(i) {
      if (this.pages.length <= 1) { this.err('Cannot delete the only page.'); return; }
      this.pages.splice(i, 1);
      const ni = Math.min(i, this.pages.length - 1);
      this.curPage = -1;
      await this.switchPage(ni);
    },

    async rotatePage(i, deg) {
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      this.pages[i].rot = ((this.pages[i].rot || 0) + deg + 360) % 360;
      if (i === this.curPage) await this.renderPage();
      await this.renderThumb(i);
    },

    // ════════════════════════════════════════════════════════════════
    // IMAGE INSERTION
    // ════════════════════════════════════════════════════════════════
    insertImage(file) {
      if (!file || !this.fc) return;
      const allowed = ['image/png','image/jpeg','image/jpg','image/gif','image/webp'];
      if (!allowed.includes(file.type)) { this.err('Only PNG, JPG, JPEG, GIF, WEBP images allowed.'); return; }
      const reader = new FileReader();
      reader.onload = ev => {
        fabric.Image.fromURL(ev.target.result, img => {
          const maxW = this.fc.getWidth() * 0.5;
          if (img.width > maxW) img.scaleToWidth(maxW);
          img.set({ left:50, top:50, selectable:true, evented:true });
          this.fc.add(img); this.fc.setActiveObject(img); this.fc.renderAll();
          this.pushHistory();
          this.ok('Image inserted — drag to move, handles to resize/rotate');
        });
      };
      reader.readAsDataURL(file);
    },

    // ════════════════════════════════════════════════════════════════
    // SIGNATURE
    // ════════════════════════════════════════════════════════════════
    initSigCanvas() {
      const el = document.getElementById('pea-sig');
      if (!el) return;
      this._sigCtx = el.getContext('2d');
      this._sigCtx.strokeStyle = '#1a1a2e'; this._sigCtx.lineWidth = 2.5; this._sigCtx.lineCap = 'round';
      this._sigCtx.clearRect(0, 0, el.width, el.height);
      el.onpointerdown = ev => {
        this._sigDwg = true;
        this._sigCtx.beginPath(); this._sigCtx.moveTo(ev.offsetX, ev.offsetY);
        el.setPointerCapture(ev.pointerId);
      };
      el.onpointermove = ev => {
        if (!this._sigDwg) return;
        this._sigCtx.lineTo(ev.offsetX, ev.offsetY); this._sigCtx.stroke();
      };
      el.onpointerup = () => { this._sigDwg = false; };
    },

    clearSig() {
      const el = document.getElementById('pea-sig');
      if (el && this._sigCtx) this._sigCtx.clearRect(0, 0, el.width, el.height);
    },

    loadSigImg(file) {
      if (!file) return;
      const r = new FileReader();
      r.onload = ev => {
        const img = new Image();
        img.onload = () => {
          const el = document.getElementById('pea-sig');
          if (!el) return;
          const s = Math.min(el.width / img.width, el.height / img.height, 1);
          this._sigCtx.clearRect(0, 0, el.width, el.height);
          this._sigCtx.drawImage(img,
            (el.width  - img.width  * s) / 2,
            (el.height - img.height * s) / 2,
            img.width * s, img.height * s);
        };
        img.src = ev.target.result;
      };
      r.readAsDataURL(file);
    },

    placeSig() {
      const el = document.getElementById('pea-sig');
      if (!el) return;
      const data = el.toDataURL('image/png');
      if (!this.savedSigs.includes(data)) {
        this.savedSigs.unshift(data);
        if (this.savedSigs.length > 6) this.savedSigs.pop();
      }
      this.placeSigData(data);
    },

    placeSigData(dataUrl) {
      this.showSig = false;
      if (!this.fc) return;
      fabric.Image.fromURL(dataUrl, img => {
        img.scaleToWidth(Math.min(180, this.fc.getWidth() * 0.28));
        img.set({ left:70, top:this.fc.getHeight() * 0.72, selectable:true, evented:true });
        this.fc.add(img); this.fc.setActiveObject(img); this.fc.renderAll();
        this.pushHistory();
      });
    },

    // ════════════════════════════════════════════════════════════════
    // WATERMARK
    // ════════════════════════════════════════════════════════════════
    async applyWm() {
      if (!this.wmText.trim()) { this.err('Please enter watermark text.'); return; }
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      const idxs = this.wmAll ? this.pages.map((_, i) => i) : [this.curPage];
      for (const i of idxs) {
        const pdfPg = await this.pdfJsDoc.getPage(this.pages[i].orig + 1);
        const vp    = pdfPg.getViewport({ scale: this.BASE });
        const json  = this.pages[i].fcJSON || { version:'5.3.0', objects:[] };
        json.objects = json.objects || [];
        json.objects.push({
          type:'text', version:'5.3.0', originX:'center', originY:'center',
          left:vp.width/2, top:vp.height/2,
          text:this.wmText.toUpperCase(), fontSize:this.wmSz,
          fill:this.wmColor, opacity:this.wmOp, angle:this.wmAngle,
          fontWeight:'bold', selectable:true, evented:true
        });
        this.pages[i].fcJSON = json;
      }
      await this.renderPage();
      this.showWm = false;
      this.ok('Watermark applied to ' + idxs.length + ' page(s)');
    },

    // ════════════════════════════════════════════════════════════════
    // PAGE NUMBERS
    // ════════════════════════════════════════════════════════════════
    async applyPn() {
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      const posMap = {
        bc:{ xa:'center', xf:w=>w/2,    yf:h=>h-24 },
        br:{ xa:'right',  xf:w=>w-16,   yf:h=>h-24 },
        bl:{ xa:'left',   xf:_=>16,     yf:h=>h-24 },
        tc:{ xa:'center', xf:w=>w/2,    yf:_=>24   },
        tr:{ xa:'right',  xf:w=>w-16,   yf:_=>24   },
        tl:{ xa:'left',   xf:_=>16,     yf:_=>24   },
      };
      const pos = posMap[this.pnPos] || posMap.bc;
      for (let i = 0; i < this.pages.length; i++) {
        const pdfPg = await this.pdfJsDoc.getPage(this.pages[i].orig + 1);
        const vp    = pdfPg.getViewport({ scale: this.BASE });
        const label = (this.pnPrefix || '') + (i + this.pnStart) + (this.pnSuffix || '');
        const json  = this.pages[i].fcJSON || { version:'5.3.0', objects:[] };
        json.objects = json.objects || [];
        json.objects.push({
          type:'text', version:'5.3.0', originX:pos.xa, originY:'center',
          left:pos.xf(vp.width), top:pos.yf(vp.height),
          text:label, fontSize:this.pnSz * this.BASE, fill:this.pnColor,
          selectable:true, evented:true
        });
        this.pages[i].fcJSON = json;
      }
      await this.renderPage();
      this.showPn = false;
      this.ok('Page numbers added to ' + this.pages.length + ' pages');
    },

    // ════════════════════════════════════════════════════════════════
    // FIND TEXT
    // ════════════════════════════════════════════════════════════════
    async doFind() {
      const q = this.findQ.trim().toLowerCase();
      if (!q || !this.pdfJsDoc) { this.findMsg = 'Enter search text.'; return; }
      this.findMsg = 'Searching…'; this.findHits = [];
      let hits = 0;
      for (let i = 0; i < this.pdfJsDoc.numPages; i++) {
        try {
          const pg      = await this.pdfJsDoc.getPage(i + 1);
          const content = await pg.getTextContent();
          const text    = content.items.map(it => it.str).join(' ');
          const idx     = text.toLowerCase().indexOf(q);
          if (idx !== -1) {
            hits++;
            const snip = text.substring(Math.max(0, idx - 20), idx + 50).replace(/\s+/g,' ');
            this.findHits.push({ page:i, snippet:'…' + snip + '…' });
          }
        } catch(e) {}
      }
      this.findMsg = hits === 0
        ? `"${this.findQ}" not found.`
        : `Found on ${hits} page(s):`;
    },

    // ════════════════════════════════════════════════════════════════
    // UNDO / REDO
    // ════════════════════════════════════════════════════════════════
    pushHistory() {
      if (!this.fc) return;
      this.undoStack.push(this.fc.toJSON(['id','opacity','notes']));
      if (this.undoStack.length > 50) this.undoStack.shift();
      this.redoStack = [];
    },

    undo() {
      if (!this.fc || !this.undoStack.length) return;
      this.redoStack.push(this.fc.toJSON(['id','opacity','notes']));
      const prev = this.undoStack.pop();
      this.fc.loadFromJSON(prev, () => { this.fc.renderAll(); });
    },

    redo() {
      if (!this.fc || !this.redoStack.length) return;
      this.undoStack.push(this.fc.toJSON(['id','opacity','notes']));
      const next = this.redoStack.pop();
      this.fc.loadFromJSON(next, () => { this.fc.renderAll(); });
    },

    // ════════════════════════════════════════════════════════════════
    // ZOOM
    // ════════════════════════════════════════════════════════════════
    setZoom(z) {
      this.zoom = Math.max(0.15, Math.min(5.0, parseFloat(z.toFixed(2))));
    },

    // ════════════════════════════════════════════════════════════════
    // MERGE
    // ════════════════════════════════════════════════════════════════
    openMergeModal() { this.showMerge = true; },

    addMergeFiles(files) {
      Array.from(files).forEach(f => {
        if (f.type === 'application/pdf' || f.name.toLowerCase().endsWith('.pdf'))
          this.mFiles.push(f);
      });
    },

    async doMerge() {
      if (this.mFiles.length < 2) { this.err('Add at least 2 PDF files.'); return; }
      this.merging = true;
      try {
        const { PDFDocument } = PDFLib;
        const out = await PDFDocument.create();
        for (const f of this.mFiles) {
          const ab  = await f.arrayBuffer();
          const src = await PDFDocument.load(ab, { ignoreEncryption:true });
          const copied = await out.copyPages(src, src.getPageIndices());
          copied.forEach(p => out.addPage(p));
        }
        const bytes = await out.save();
        this.dlBytes(bytes, 'merged.pdf');
        this.showMerge = false; this.mFiles = [];
        this.ok('PDFs merged and downloaded!');
      } catch(e) {
        this.err('Merge failed: ' + e.message);
      }
      this.merging = false;
    },

    // ════════════════════════════════════════════════════════════════
    // SPLIT / EXTRACT
    // ════════════════════════════════════════════════════════════════
    async extractPage() {
      if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
      try {
        const { PDFDocument, degrees } = PDFLib;
        const src  = await PDFDocument.load(this.pdfBytes);
        const out  = await PDFDocument.create();
        const pg   = this.pages[this.curPage];
        const [cp] = await out.copyPages(src, [pg.orig]);
        if (pg.rot) cp.setRotation(degrees(pg.rot));
        out.addPage(cp);
        const bytes = await out.save();
        this.dlBytes(bytes, 'page-' + (this.curPage + 1) + '.pdf');
        this.ok('Page ' + (this.curPage + 1) + ' extracted');
      } catch(e) { this.err('Extract failed: ' + e.message); }
    },

    async splitRange() {
      this.showSplit = false;
      const from = Math.max(1, this.splitFrom) - 1;
      const to   = Math.min(this.pages.length, this.splitTo) - 1;
      if (from > to) { this.err('Invalid page range.'); return; }
      try {
        const { PDFDocument, degrees } = PDFLib;
        const src = await PDFDocument.load(this.pdfBytes);
        const out = await PDFDocument.create();
        for (let i = from; i <= to; i++) {
          const pg   = this.pages[i];
          const [cp] = await out.copyPages(src, [pg.orig]);
          if (pg.rot) cp.setRotation(degrees(pg.rot));
          out.addPage(cp);
        }
        const bytes = await out.save();
        this.dlBytes(bytes, 'pages-' + (from+1) + '-' + (to+1) + '.pdf');
        this.ok('Pages ' + (from+1) + '–' + (to+1) + ' extracted (' + (to - from + 1) + ' pages)');
      } catch(e) { this.err('Split failed: ' + e.message); }
    },

    // ════════════════════════════════════════════════════════════════
    // EXPORT PAGE AS PNG
    // ════════════════════════════════════════════════════════════════
    async exportPageAsPng() {
      if (!this.pdfJsDoc) return;
      try {
        if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
        const pg    = this.pages[this.curPage];
        const pdfPg = await this.pdfJsDoc.getPage(pg.orig + 1);
        const vp    = pdfPg.getViewport({ scale:2.0, rotation:pg.rot || 0 });
        const c     = document.createElement('canvas');
        c.width = vp.width; c.height = vp.height;
        const ctx   = c.getContext('2d');
        await pdfPg.render({ canvasContext:ctx, viewport:vp }).promise;
        const annotPng = await this.getAnnotPng(this.curPage);
        if (annotPng) {
          const img = new Image();
          await new Promise(r => { img.onload = r; img.src = annotPng; });
          ctx.drawImage(img, 0, 0, vp.width, vp.height);
        }
        c.toBlob(b => {
          const a = document.createElement('a');
          a.href = URL.createObjectURL(b);
          a.download = 'page-' + (this.curPage + 1) + '.png';
          a.click();
        }, 'image/png');
        this.ok('Page exported as PNG');
      } catch(e) { this.err('PNG export failed: ' + e.message); }
    },

    // ════════════════════════════════════════════════════════════════
    // ANNOTATION PNG (transparent overlay for download)
    // ════════════════════════════════════════════════════════════════
    async getAnnotPng(pageIdx) {
      const json = this.pages[pageIdx].fcJSON;
      if (!json || !json.objects || !json.objects.length) return null;
      let w = 595, h = 842;
      try {
        const pdfPg = await this.pdfJsDoc.getPage(this.pages[pageIdx].orig + 1);
        const vp    = pdfPg.getViewport({ scale:this.BASE, rotation:this.pages[pageIdx].rot || 0 });
        w = vp.width; h = vp.height;
      } catch(e) {
        if (this.fc) { w = this.fc.getWidth(); h = this.fc.getHeight(); }
      }
      const el = document.createElement('canvas');
      el.style.cssText = 'position:fixed;left:-9999px;visibility:hidden';
      document.body.appendChild(el);
      try {
        const sc = new fabric.StaticCanvas(el, { width:w, height:h, backgroundColor:null, enableRetinaScaling:false });
        await new Promise(r => sc.loadFromJSON(json, () => { sc.renderAll(); r(); }));
        const data = sc.toDataURL({ format:'png', multiplier:1 });
        sc.dispose(); return data;
      } catch(e) { return null; }
      finally { document.body.removeChild(el); }
    },

    // ════════════════════════════════════════════════════════════════
    // DOWNLOAD PDF
    // ════════════════════════════════════════════════════════════════
    async downloadPdf() {
      if (!this.pdfBytes || this.exporting) return;
      this.exporting = true; this.ok('Preparing PDF…');
      try {
        if (this.fc) this.pages[this.curPage].fcJSON = this.fc.toJSON(['id','opacity','notes']);
        const { PDFDocument, degrees } = PDFLib;
        const srcDoc = await PDFDocument.load(this.pdfBytes);
        const outDoc = await PDFDocument.create();
        for (let i = 0; i < this.pages.length; i++) {
          const pg   = this.pages[i];
          const [cp] = await outDoc.copyPages(srcDoc, [pg.orig]);
          outDoc.addPage(cp);
          const outPg    = outDoc.getPage(outDoc.getPageCount() - 1);
          if (pg.rot) outPg.setRotation(degrees(pg.rot));
          const annotPng = await this.getAnnotPng(i);
          if (annotPng) {
            const { width:pw, height:ph } = outPg.getSize();
            const img = await outDoc.embedPng(annotPng);
            outPg.drawImage(img, { x:0, y:0, width:pw, height:ph });
          }
        }
        const bytes = await outDoc.save();
        const name  = this.fileName
          ? this.fileName.replace(/\.pdf$/i, '-edited.pdf')
          : 'edited.pdf';
        this.dlBytes(bytes, name);
        this.ok('PDF saved successfully!');
      } catch(e) {
        this.err('Save failed: ' + e.message);
        console.error('[pea] save:', e);
      }
      this.exporting = false;
    },

    // ════════════════════════════════════════════════════════════════
    // HELPERS
    // ════════════════════════════════════════════════════════════════
    fmtSz(b) {
      if (b >= 1048576) return (b/1048576).toFixed(1) + ' MB';
      if (b >= 1024)    return Math.round(b/1024)      + ' KB';
      return b + ' B';
    },

    dlBytes(bytes, name) {
      const blob = new Blob([bytes], { type:'application/pdf' });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob); a.download = name;
      document.body.appendChild(a); a.click();
      setTimeout(() => { document.body.removeChild(a); URL.revokeObjectURL(a.href); }, 800);
    },

    ok(msg)  {
      this.statusOk = msg; this.statusErr = '';
      setTimeout(() => { if (this.statusOk === msg) this.statusOk = ''; }, 4000);
    },
    err(msg) { this.statusErr = msg; this.statusOk = ''; },
  };
}
</script>
@endpush
