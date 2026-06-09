<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   Diff Checker  —  prefix: dc-
   Pure JS LCS-based diff: line-level + inline word highlights
   Unified & Split views, context mode, copy result.
   Added lines: green  (#dcfce7 / #15803d)
   Removed lines: red  (#fee2e2 / #991b1b)
   Equal lines: gray   (#f9fafb / #6b7280)
══════════════════════════════════════════════════════════════ */

/* ── Input textareas ───────────────────────────────────── */
.dc-textarea {
  width:100%; resize:vertical; min-height:200px;
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:.8rem; line-height:1.7; color:#1e293b; caret-color:#4f46e5;
  border:none; outline:none; padding:.85rem 1rem; background:transparent;
}
.dc-textarea::placeholder { color:#d1d5db; }
.dc-textarea::-webkit-scrollbar       { width:4px; }
.dc-textarea::-webkit-scrollbar-thumb { background:#c7d2fe; border-radius:9999px; }

/* ── Panel headers ─────────────────────────────────────── */
.dc-pane-hdr {
  padding:.55rem 1rem; border-bottom:1px solid #f3f4f6;
  display:flex; align-items:center; justify-content:space-between;
}
.dc-pane-lbl { font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; }
.dc-pane-count { font-size:.68rem; font-weight:600; color:#94a3b8; }

/* ── Action buttons ────────────────────────────────────── */
.dc-btn {
  display:inline-flex; align-items:center; gap:.35rem; padding:.5rem 1.1rem;
  border-radius:.75rem; font-size:.78rem; font-weight:700; cursor:pointer;
  transition:all .15s; border:1.5px solid transparent; white-space:nowrap;
}
.dc-btn:disabled { opacity:.35; cursor:not-allowed; }
.dc-btn-compare {
  background:linear-gradient(135deg,#4338ca,#4f46e5,#6366f1);
  color:#fff; border-color:#4f46e5;
  box-shadow:0 3px 10px rgba(79,70,229,.3);
}
.dc-btn-compare:hover:not(:disabled) { box-shadow:0 4px 16px rgba(79,70,229,.45); transform:translateY(-1px); }
.dc-btn-swap  { background:#f1f5f9; color:#475569; border-color:#e2e8f0; }
.dc-btn-swap:hover:not(:disabled)  { background:#e2e8f0; color:#1e293b; }
.dc-btn-clear { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
.dc-btn-clear:hover:not(:disabled) { background:#dc2626; color:#fff; }
.dc-btn-copy  { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
.dc-btn-copy:hover:not(:disabled)  { background:#15803d; color:#fff; }
.dc-btn-copy.dc-done { background:#dcfce7; color:#15803d; border-color:#86efac; }

/* ── Stats badges ──────────────────────────────────────── */
.dc-stat {
  display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .75rem;
  border-radius:9999px; font-size:.72rem; font-weight:700; white-space:nowrap;
}
.dc-stat-add { background:#dcfce7; color:#15803d; border:1.5px solid #86efac; }
.dc-stat-del { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }
.dc-stat-eq  { background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0; }
.dc-stat-none{ background:#f8fafc; color:#94a3b8; border:1.5px solid #e2e8f0; }

/* ── View toggle ──────────────────────────────────────── */
.dc-view-btn {
  padding:.35rem .8rem; border-radius:.65rem; font-size:.72rem; font-weight:700;
  cursor:pointer; transition:all .14s; border:1.5px solid #e2e8f0;
  background:#fff; color:#6b7280;
}
.dc-view-btn:hover { border-color:#c7d2fe; color:#4f46e5; }
.dc-view-btn.dc-on { background:#eef2ff; color:#4f46e5; border-color:#c7d2fe; }

/* ── Toggle switch ─────────────────────────────────────── */
.dc-tog-row { display:flex; align-items:center; gap:.5rem; cursor:pointer; }
.dc-tog-lbl { font-size:.75rem; font-weight:600; color:#374151; user-select:none; }
.dc-toggle {
  position:relative; width:2.2rem; height:1.2rem; border-radius:9999px;
  background:#e2e8f0; transition:background .18s; flex-shrink:0;
}
.dc-toggle.dc-on { background:#4f46e5; }
.dc-toggle::after {
  content:''; position:absolute; top:.15rem; left:.15rem;
  width:.9rem; height:.9rem; border-radius:9999px;
  background:#fff; transition:transform .18s; box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.dc-toggle.dc-on::after { transform:translateX(1rem); }

/* ── Diff output container ─────────────────────────────── */
.dc-output {
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:.78rem; line-height:1.65; overflow:auto; border-radius:0 0 1rem 1rem;
}
.dc-output::-webkit-scrollbar       { width:5px; height:5px; }
.dc-output::-webkit-scrollbar-thumb { background:#c7d2fe; border-radius:9999px; }

/* ── Unified view: single line row ──────────────────────── */
.dc-line {
  display:grid;
  grid-template-columns: 2.8rem 2.8rem 1.2rem 1fr;
  min-width:0; border-bottom:1px solid transparent;
}
.dc-line:last-child { border-bottom:none; }
.dc-line-eq  { background:#f9fafb; border-color:#f3f4f6; }
.dc-line-del { background:#fff0f0; border-color:#fee2e2; }
.dc-line-ins { background:#f0fff4; border-color:#dcfce7; }
.dc-line-sep {
  background:#fef9c3; border:1px solid #fde047;
  text-align:center; padding:.3rem 0; color:#92400e;
  font-size:.7rem; font-weight:700; grid-column:1/-1;
  display:flex; align-items:center; justify-content:center; gap:.4rem;
}

.dc-lnum {
  text-align:right; padding:.25rem .5rem .25rem 0;
  user-select:none; font-size:.7rem; color:#9ca3af; flex-shrink:0;
}
.dc-line-del .dc-lnum { color:#fca5a5; }
.dc-line-ins .dc-lnum { color:#86efac; }

.dc-pfx {
  text-align:center; padding:.25rem 0; user-select:none; font-weight:800; flex-shrink:0;
}
.dc-line-del .dc-pfx { color:#ef4444; }
.dc-line-ins .dc-pfx { color:#22c55e; }
.dc-line-eq  .dc-pfx { color:#d1d5db; }

.dc-content {
  padding:.25rem .5rem .25rem .2rem; overflow:hidden; min-width:0;
  word-break:break-all; overflow-wrap:break-word; white-space:pre-wrap;
  color:#374151;
}
.dc-line-del .dc-content { color:#7f1d1d; }
.dc-line-ins .dc-content { color:#14532d; }

/* Inline word highlights */
.dc-word-del { background:#fca5a5; border-radius:2px; padding:0 1px; color:#7f1d1d; }
.dc-word-ins { background:#86efac; border-radius:2px; padding:0 1px; color:#14532d; }

/* ── Split view ────────────────────────────────────────── */
.dc-split-wrap  { display:flex; min-width:0; border-bottom:1px solid transparent; }
.dc-split-eq    { border-color:#f3f4f6; }
.dc-split-change{ border-color:#e5e7eb; }
.dc-split-sep   { background:#fef9c3; border:1px solid #fde047; width:100%; }

.dc-split-half {
  flex:1; min-width:0; display:grid;
  grid-template-columns:2.8rem 1fr;
  border-right:1px solid #e5e7eb;
}
.dc-split-half:last-child { border-right:none; }
.dc-split-half-del { background:#fff0f0; }
.dc-split-half-ins { background:#f0fff4; }
.dc-split-half-eq  { background:#f9fafb; }
.dc-split-half-empty{ background:#fafafa; }

/* ── Divider ───────────────────────────────────────────── */
.dc-div {
  display:flex; align-items:center; gap:.6rem;
  font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#6b7280;
}
.dc-div::before,.dc-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Error ─────────────────────────────────────────────── */
.dc-err {
  display:flex; align-items:flex-start; gap:.5rem; padding:.75rem 1rem;
  background:#fef2f2; border:1px solid #fecaca; border-radius:.875rem;
  font-size:.82rem; color:#991b1b;
}

/* ── Empty state ───────────────────────────────────────── */
.dc-empty {
  padding:2.5rem 1rem; text-align:center;
  color:#94a3b8; font-size:.88rem;
}

/* Context input */
.dc-ctx-input {
  width:3.5rem; text-align:center; padding:.25rem .4rem;
  border:1.5px solid #e2e8f0; border-radius:.5rem; font-size:.75rem;
  font-weight:700; color:#374151; outline:none;
}
.dc-ctx-input:focus { border-color:#c7d2fe; }

@keyframes dcIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.dc-in { animation:dcIn .25s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="dcTool()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔄 Diff Checker
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Compare two blocks of text and see exactly what changed — line-by-line with word-level highlights for modified lines.
          </p>
        </div>
        <button @click="loadSample()" class="dc-btn dc-btn-swap self-start sm:self-auto">
          📄 Load Sample
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-4">

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      
      <div class="card overflow-hidden">
        <div class="dc-pane-hdr">
          <span class="dc-pane-lbl">Original Text</span>
          <span class="dc-pane-count" x-show="textA.trim()"
                x-text="textA.split('\n').length + ' lines · ' + textA.length + ' chars'"></span>
        </div>
        <textarea
          x-model="textA"
          class="dc-textarea"
          placeholder="Paste your original / older text here…"
          spellcheck="false"
          aria-label="Original text"
        ></textarea>
      </div>

      
      <div class="card overflow-hidden">
        <div class="dc-pane-hdr">
          <span class="dc-pane-lbl">Modified Text</span>
          <span class="dc-pane-count" x-show="textB.trim()"
                x-text="textB.split('\n').length + ' lines · ' + textB.length + ' chars'"></span>
        </div>
        <textarea
          x-model="textB"
          class="dc-textarea"
          placeholder="Paste your modified / newer text here…"
          spellcheck="false"
          aria-label="Modified text"
        ></textarea>
      </div>

    </div>

    
    <div class="flex flex-wrap items-center gap-3">
      <button @click="compare()"
              :disabled="!textA && !textB"
              class="dc-btn dc-btn-compare text-sm">
        🔍 Compare Texts
      </button>
      <button @click="swapTexts()" :disabled="!textA && !textB" class="dc-btn dc-btn-swap">
        ⇅ Swap
      </button>
      <button @click="clear()" :disabled="!textA && !textB && !compared" class="dc-btn dc-btn-clear">
        🗑️ Clear
      </button>
      <p class="text-xs text-gray-400 ml-auto">Press <kbd class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 text-xs font-mono">Ctrl+Enter</kbd> to compare</p>
    </div>

    
    <div x-show="error" x-transition class="dc-err">
      <span class="text-base flex-shrink-0">⚠️</span>
      <span x-text="error"></span>
    </div>

    
    <div x-show="compared" x-transition id="dc-results" class="space-y-4 dc-in">

      
      <div class="card p-4">
        <div class="flex flex-wrap items-center gap-3">

          <template x-if="stats.totalChanges === 0">
            <span class="dc-stat dc-stat-eq">✓ Texts are identical — no differences found</span>
          </template>

          <template x-if="stats.totalChanges > 0">
            <div class="flex flex-wrap gap-2 items-center">
              <span class="dc-stat dc-stat-add">
                <span>+</span><strong x-text="stats.added"></strong>
                <span x-text="stats.added === 1 ? 'addition' : 'additions'"></span>
              </span>
              <span class="dc-stat dc-stat-del">
                <span>−</span><strong x-text="stats.removed"></strong>
                <span x-text="stats.removed === 1 ? 'deletion' : 'deletions'"></span>
              </span>
              <span class="dc-stat dc-stat-eq">
                <strong x-text="stats.unchanged"></strong>
                <span>unchanged</span>
              </span>
              <span class="text-xs text-gray-400">·</span>
              <span class="text-xs font-semibold text-gray-500"
                    x-text="stats.totalChanges + ' total ' + (stats.totalChanges === 1 ? 'change' : 'changes')"></span>
            </div>
          </template>

          
          <div class="ml-auto flex flex-wrap items-center gap-2.5">

            
            <div class="flex rounded-xl overflow-hidden border border-gray-200">
              <button @click="viewMode = 'unified'"
                      :class="['dc-view-btn rounded-none border-none', viewMode==='unified' ? 'dc-on' : '']">
                Unified
              </button>
              <button @click="viewMode = 'split'"
                      :class="['dc-view-btn rounded-none border-none border-l', viewMode==='split' ? 'dc-on' : '']">
                Split
              </button>
            </div>

            
            <label class="dc-tog-row" @click="onlyChanges = !onlyChanges">
              <div :class="['dc-toggle', onlyChanges ? 'dc-on' : '']"></div>
              <span class="dc-tog-lbl">Only changes</span>
            </label>

            
            <div x-show="onlyChanges" class="flex items-center gap-1.5">
              <span class="text-xs text-gray-500">Context:</span>
              <input type="number" x-model.number="contextLines" min="0" max="20"
                     class="dc-ctx-input" @change="contextLines = Math.min(20, Math.max(0, contextLines))">
              <span class="text-xs text-gray-400">lines</span>
            </div>

            
            <button @click="copyResult()" :disabled="stats.totalChanges === 0"
                    :class="['dc-btn dc-btn-copy', copied ? 'dc-done' : '']">
              <span x-text="copied ? '✓ Copied!' : '⎘ Copy Diff'"></span>
            </button>

          </div>
        </div>
      </div>

      
      <div x-show="stats.totalChanges > 0" class="card overflow-hidden">
        <div class="dc-output" style="max-height:600px">

          
          <template x-if="viewMode === 'unified'">
            <div>
              
              <div class="dc-line" style="background:#f8fafc; border-bottom:1px solid #e5e7eb">
                <span class="dc-lnum" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding-top:.35rem">ORIG</span>
                <span class="dc-lnum" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding-top:.35rem">MOD</span>
                <span class="dc-pfx"></span>
                <span class="dc-content" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding-top:.35rem">CONTENT</span>
              </div>

              <template x-for="(op, i) in displayedOps" :key="i">
                <div>
                  
                  <template x-if="op.type === 'separator'">
                    <div class="dc-line-sep">
                      <span>···</span>
                      <span x-text="op.skipped + ' unchanged ' + (op.skipped === 1 ? 'line' : 'lines') + ' hidden'"></span>
                      <span>···</span>
                    </div>
                  </template>

                  
                  <template x-if="op.type !== 'separator'">
                    <div :class="['dc-line', lineClass(op)]">
                      <span class="dc-lnum" x-text="op.lineA || ''"></span>
                      <span class="dc-lnum" x-text="op.lineB || ''"></span>
                      <span class="dc-pfx" x-text="linePrefix(op)"></span>
                      <div class="dc-content" x-html="renderContent(op)"></div>
                    </div>
                  </template>
                </div>
              </template>
            </div>
          </template>

          
          <template x-if="viewMode === 'split'">
            <div>
              
              <div class="dc-split-wrap" style="background:#f8fafc;border-bottom:1px solid #e5e7eb">
                <div class="dc-split-half" style="background:#f8fafc">
                  <span class="dc-lnum" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding:.35rem .5rem 0 0">ORIG</span>
                  <span class="dc-content" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding:.35rem .5rem .35rem .2rem">ORIGINAL TEXT</span>
                </div>
                <div class="dc-split-half" style="background:#f8fafc">
                  <span class="dc-lnum" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding:.35rem .5rem 0 0">MOD</span>
                  <span class="dc-content" style="color:#94a3b8;font-size:.6rem;font-weight:800;padding:.35rem .5rem .35rem .2rem">MODIFIED TEXT</span>
                </div>
              </div>

              <template x-for="(row, i) in splitRows" :key="i">
                <div>
                  
                  <template x-if="row.isSep">
                    <div class="dc-line-sep" style="grid-column:unset;display:flex">
                      <span>···</span>
                      <span x-text="row.skipped + ' unchanged ' + (row.skipped===1?'line':'lines') + ' hidden'"></span>
                      <span>···</span>
                    </div>
                  </template>

                  
                  <template x-if="!row.isSep">
                    <div class="dc-split-wrap" :class="splitRowClass(row)">

                      
                      <div :class="['dc-split-half', splitHalfClass(row.left)]">
                        <span class="dc-lnum" x-text="row.left ? row.left.lineA : ''"></span>
                        <div class="dc-content" x-html="renderContent(row.left)"></div>
                      </div>

                      
                      <div :class="['dc-split-half', splitHalfClass(row.right)]">
                        <span class="dc-lnum" x-text="row.right ? row.right.lineB : ''"></span>
                        <div class="dc-content" x-html="renderContent(row.right)"></div>
                      </div>

                    </div>
                  </template>
                </div>
              </template>
            </div>
          </template>

        </div>
      </div>

      
      <div x-show="stats.totalChanges === 0" class="card dc-empty">
        <div class="text-4xl mb-2">✅</div>
        <p class="font-semibold text-gray-600">The two texts are identical.</p>
        <p class="text-gray-400 text-sm mt-1">No additions, deletions, or modifications found.</p>
      </div>

    </div>

    
    <div class="card p-5">
      <p class="dc-div mb-4">How to Read the Diff</p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div class="flex items-start gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
          <span class="font-mono font-black text-green-600 text-lg leading-tight flex-shrink-0">+</span>
          <div>
            <p class="font-semibold text-green-800">Added Lines</p>
            <p class="text-green-700 text-xs mt-0.5">Lines present only in the Modified text. Shown with green background and + prefix.</p>
          </div>
        </div>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
          <span class="font-mono font-black text-red-600 text-lg leading-tight flex-shrink-0">−</span>
          <div>
            <p class="font-semibold text-red-800">Removed Lines</p>
            <p class="text-red-700 text-xs mt-0.5">Lines present only in the Original text. Shown with red background and − prefix.</p>
          </div>
        </div>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
          <span class="font-mono font-black text-amber-600 text-lg leading-tight flex-shrink-0">⊞</span>
          <div>
            <p class="font-semibold text-amber-800">Inline Word Diff</p>
            <p class="text-amber-700 text-xs mt-0.5">When a line is modified, individual changed words are highlighted more intensely within the line.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


<script>
document.addEventListener('keydown', function(e) {
  if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
    var comp = document.querySelector('[x-data]')?.__x;
    // Try to trigger compare via custom event
    document.dispatchEvent(new CustomEvent('dc-compare'));
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ─────────────────────────────────────────────────────────────────
   Diff Checker — Alpine.js component
   CSS prefix: dc-

   Algorithm: LCS (Longest Common Subsequence) based line diff.
     _lcs(a, b): builds DP table with Int32Array, backtracks to ops.
     Time/space: O(m*n) — hard-capped at m*n > 4,000,000 with error.

   Inline word diff: for each adjacent delete+insert pair in a block,
     _wordDiff(lineA, lineB) runs LCS on tokenized words/whitespace.
     Attached to each op as op.words[].

   Views:
     unified:  displayedOps — single list with lineA/lineB numbers
     split:    splitRows — paired {left, right} cells per row

   Context mode (onlyChanges):
     _applyContext(ops, ctx): keeps ops within `ctx` lines of any change,
     inserts { type:'separator', skipped:N } for hidden equal regions.

   renderContent(op):
     Returns HTML string. User text is always _esc()-processed first.
     <mark class="dc-word-ins|del"> for inline changed tokens.
     Correctly handles null (empty split-view cell) and empty lines.
─────────────────────────────────────────────────────────────────── */
function dcTool() {

  return {

    textA:        '',
    textB:        '',
    compared:     false,
    viewMode:     'unified',   // 'unified' | 'split'
    onlyChanges:  false,
    contextLines: 3,
    copied:       false,
    error:        '',

    // Set by compare()
    _ops:   [],   // raw diff ops (delete/insert/equal)
    stats:  { added: 0, removed: 0, unchanged: 0, totalChanges: 0 },

    init() {
      var self = this;
      document.addEventListener('dc-compare', function() { self.compare(); });
    },

    /* ── Computed views ────────────────────────────────── */

    get displayedOps() {
      if (!this.onlyChanges) return this._ops;
      return this._applyContext(this._ops, this.contextLines);
    },

    get splitRows() {
      var rows = [];
      var ops  = this.displayedOps;
      var i    = 0;
      while (i < ops.length) {
        var op = ops[i];
        if (op.type === 'separator') {
          rows.push({ isSep: true, skipped: op.skipped });
          i++;
        } else if (op.type === 'equal') {
          rows.push({ isSep: false, left: op, right: op });
          i++;
        } else {
          // Collect consecutive delete/insert block
          var dels = [], ins = [];
          var j = i;
          while (j < ops.length && (ops[j].type === 'delete' || ops[j].type === 'insert')) {
            if (ops[j].type === 'delete') dels.push(ops[j]);
            else                          ins.push(ops[j]);
            j++;
          }
          var maxLen = Math.max(dels.length, ins.length);
          for (var r = 0; r < maxLen; r++) {
            rows.push({
              isSep: false,
              left:  r < dels.length ? dels[r] : null,
              right: r < ins.length  ? ins[r]  : null,
            });
          }
          i = j;
        }
      }
      return rows;
    },

    /* ── CSS helpers ───────────────────────────────────── */

    lineClass(op) {
      if (!op) return '';
      return op.type === 'equal'  ? 'dc-line-eq'  :
             op.type === 'delete' ? 'dc-line-del' :
             op.type === 'insert' ? 'dc-line-ins' : '';
    },

    linePrefix(op) {
      if (!op || op.type === 'equal')  return ' ';
      if (op.type === 'delete') return '−';
      if (op.type === 'insert') return '+';
      return ' ';
    },

    splitHalfClass(op) {
      if (!op) return 'dc-split-half-empty';
      return op.type === 'delete' ? 'dc-split-half-del' :
             op.type === 'insert' ? 'dc-split-half-ins' : 'dc-split-half-eq';
    },

    splitRowClass(row) {
      var hasChange = (row.left && row.left.type !== 'equal')
                   || (row.right && row.right.type !== 'equal');
      return hasChange ? 'dc-split-change' : 'dc-split-eq';
    },

    /* ── Render content (XSS-safe via _esc) ────────────── */

    renderContent(op) {
      var self = this;
      if (!op) return '&nbsp;';
      if (!op.words) {
        return op.value !== '' ? this._esc(op.value) : '<span style="opacity:.3;font-style:italic;font-size:.7rem">↵</span>';
      }
      // Inline word diff
      return op.words.map(function(w) {
        if (!w.changed) return self._esc(w.value);
        return '<mark class="dc-word-' + w.type + '">' + self._esc(w.value) + '</mark>';
      }).join('');
    },

    /* ── Main compare action ───────────────────────────── */

    compare() {
      this.error = '';
      if (!this.textA && !this.textB) {
        this.error = 'Please paste text into both fields before comparing.';
        return;
      }

      var linesA = this.textA.split('\n');
      var linesB = this.textB.split('\n');

      // Size guard
      if (linesA.length * linesB.length > 4000000) {
        this.error = 'Input too large ('
          + linesA.length.toLocaleString() + ' × ' + linesB.length.toLocaleString()
          + ' lines). Please reduce to under ~2 000 lines per text for performance.';
        return;
      }

      this._ops  = this._computeDiff(linesA, linesB);
      this.stats = this._computeStats(this._ops);
      this.compared = true;

      // Scroll to results
      var self = this;
      setTimeout(function() {
        var el = document.getElementById('dc-results');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 80);
    },

    swapTexts() {
      var tmp  = this.textA;
      this.textA = this.textB;
      this.textB = tmp;
      if (this.compared) this.compare();
    },

    clear() {
      this.textA    = '';
      this.textB    = '';
      this._ops     = [];
      this.stats    = { added: 0, removed: 0, unchanged: 0, totalChanges: 0 };
      this.compared = false;
      this.error    = '';
      this.copied   = false;
    },

    loadSample() {
      this.textA = '// Server configuration (v1)\nconst config = {\n  host: \'localhost\',\n  port: 3000,\n  debug: true,\n  timeout: 5000,\n  retries: 3,\n  database: \'myapp_dev\',\n  logLevel: \'verbose\',\n};\n\nmodule.exports = config;';
      this.textB = '// Server configuration (v2)\nconst config = {\n  host: \'production.example.com\',\n  port: 8080,\n  debug: false,\n  timeout: 10000,\n  database: \'myapp_prod\',\n  logLevel: \'error\',\n  ssl: true,\n  maxConnections: 100,\n};\n\nmodule.exports = config;';
      this.compare();
    },

    copyResult() {
      var lines = this._ops.map(function(op) {
        var pfx = op.type === 'insert' ? '+ ' : op.type === 'delete' ? '- ' : '  ';
        return pfx + op.value;
      }).join('\n');
      var self = this;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(lines).then(function() {
          self.copied = true;
          setTimeout(function() { self.copied = false; }, 2200);
        });
      }
    },

    /* ── Diff algorithms ────────────────────────────────── */

    _computeDiff(linesA, linesB) {
      var ops = this._lcs(linesA, linesB);
      return this._addInlineDiffs(ops);
    },

    // LCS-based diff using Int32Array DP table
    _lcs(a, b) {
      var m = a.length, n = b.length;
      if (m === 0 && n === 0) return [];

      // Build DP table (bottom-up, stored as flat Int32Array)
      var dp   = new Int32Array((m + 1) * (n + 1));
      var cols = n + 1;
      for (var i = m - 1; i >= 0; i--) {
        for (var j = n - 1; j >= 0; j--) {
          var idx = i * cols + j;
          if (a[i] === b[j]) {
            dp[idx] = dp[(i + 1) * cols + (j + 1)] + 1;
          } else {
            var up   = dp[(i + 1) * cols + j];
            var left = dp[i * cols + (j + 1)];
            dp[idx]  = up > left ? up : left;
          }
        }
      }

      // Backtrack to produce operations
      var result = [];
      var lnA = 1, lnB = 1;
      var ii = 0, jj = 0;
      while (ii < m || jj < n) {
        if (ii < m && jj < n && a[ii] === b[jj]) {
          result.push({ type: 'equal',  lineA: lnA, lineB: lnB, value: a[ii] });
          ii++; jj++; lnA++; lnB++;
        } else if (jj < n && (ii >= m || dp[ii * cols + (jj + 1)] >= dp[(ii + 1) * cols + jj])) {
          result.push({ type: 'insert', lineA: null, lineB: lnB, value: b[jj] });
          jj++; lnB++;
        } else {
          result.push({ type: 'delete', lineA: lnA, lineB: null, value: a[ii] });
          ii++; lnA++;
        }
      }
      return result;
    },

    // Attach inline word diffs to adjacent delete+insert pairs
    _addInlineDiffs(ops) {
      var result = [];
      var i = 0;
      while (i < ops.length) {
        if (ops[i].type !== 'delete' && ops[i].type !== 'insert') {
          result.push(this._clone(ops[i], null));
          i++;
        } else {
          // Collect block of consecutive deletes + inserts
          var dels = [], ins = [];
          var j = i;
          while (j < ops.length && (ops[j].type === 'delete' || ops[j].type === 'insert')) {
            if (ops[j].type === 'delete') dels.push(ops[j]);
            else                          ins.push(ops[j]);
            j++;
          }

          var paired = Math.min(dels.length, ins.length);

          // Precompute word diffs for each pair
          var wdiffs = [];
          for (var k = 0; k < paired; k++) {
            wdiffs.push(this._wordDiff(dels[k].value, ins[k].value));
          }

          // Emit: all deletes first, then all inserts (standard unified order)
          for (var k = 0; k < dels.length; k++) {
            result.push(this._clone(dels[k], k < paired ? wdiffs[k].a : null));
          }
          for (var k = 0; k < ins.length; k++) {
            result.push(this._clone(ins[k], k < paired ? wdiffs[k].b : null));
          }
          i = j;
        }
      }
      return result;
    },

    // Word-level diff between two lines
    // Returns { a: [{value, changed, type}], b: [...] }
    _wordDiff(lineA, lineB) {
      var tA = this._tokenize(lineA);
      var tB = this._tokenize(lineB);
      var m  = tA.length, n = tB.length;
      if (m === 0 && n === 0) return { a: [], b: [] };

      // LCS on token arrays (small, use plain Array)
      var dp = [];
      for (var i = 0; i <= m; i++) { dp[i] = new Array(n + 1).fill(0); }
      for (var i = m - 1; i >= 0; i--) {
        for (var j = n - 1; j >= 0; j--) {
          if (tA[i] === tB[j]) dp[i][j] = dp[i+1][j+1] + 1;
          else dp[i][j] = dp[i+1][j] > dp[i][j+1] ? dp[i+1][j] : dp[i][j+1];
        }
      }

      // Backtrack
      var seqA = [], seqB = [];
      var i = 0, j = 0;
      while (i < m || j < n) {
        if (i < m && j < n && tA[i] === tB[j]) {
          seqA.push({ value: tA[i], changed: false });
          seqB.push({ value: tB[j], changed: false });
          i++; j++;
        } else if (j < n && (i >= m || dp[i][j+1] >= dp[i+1][j])) {
          seqB.push({ value: tB[j], changed: true, type: 'ins' }); j++;
        } else {
          seqA.push({ value: tA[i], changed: true, type: 'del' }); i++;
        }
      }
      return { a: seqA, b: seqB };
    },

    // Tokenize a line into word + whitespace tokens
    _tokenize(str) {
      var tokens = [];
      var re = /\S+|\s+/g;
      var m;
      while ((m = re.exec(str)) !== null) tokens.push(m[0]);
      return tokens;
    },

    _computeStats(ops) {
      var added = 0, removed = 0, unchanged = 0;
      ops.forEach(function(op) {
        if      (op.type === 'insert') added++;
        else if (op.type === 'delete') removed++;
        else if (op.type === 'equal')  unchanged++;
      });
      return { added: added, removed: removed, unchanged: unchanged, totalChanges: added + removed };
    },

    // Apply context: keep N equal lines around each change, insert separators for hidden runs
    _applyContext(ops, ctx) {
      if (!ops.length) return ops;
      ctx = ctx < 0 ? 0 : ctx;

      // Mark which indices to show
      var show = new Array(ops.length).fill(false);
      for (var i = 0; i < ops.length; i++) {
        if (ops[i].type !== 'equal') {
          var lo = i - ctx < 0 ? 0 : i - ctx;
          var hi = i + ctx >= ops.length ? ops.length - 1 : i + ctx;
          for (var c = lo; c <= hi; c++) show[c] = true;
        }
      }

      var result = [];
      var prevShown = -1;
      for (var i = 0; i < ops.length; i++) {
        if (show[i]) {
          if (prevShown !== -1 && i > prevShown + 1) {
            result.push({ type: 'separator', skipped: i - prevShown - 1 });
          }
          result.push(ops[i]);
          prevShown = i;
        }
      }
      return result;
    },

    _clone(op, words) {
      return { type: op.type, lineA: op.lineA, lineB: op.lineB, value: op.value, words: words };
    },

    // HTML-escape user content — prevents XSS in x-html
    _esc(text) {
      return String(text)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;');
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\diff-checker.blade.php ENDPATH**/ ?>