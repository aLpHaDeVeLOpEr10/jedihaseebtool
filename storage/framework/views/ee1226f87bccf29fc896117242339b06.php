<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Duplicate Line Remover  —  prefix: dlr-
   Theme: Emerald (#047857 / #059669 / #10b981)
   Button-triggered processing with live options.
══════════════════════════════════════════════ */

/* Textareas */
.dlr-textarea {
  width: 100%; resize: vertical;
  font-family: 'JetBrains Mono','Fira Code','Courier New', monospace;
  font-size: .82rem; line-height: 1.7; color: #1e293b;
  caret-color: #059669; border: none; outline: none;
  padding: 1rem 1.1rem; background: transparent; min-height: 220px;
}
.dlr-textarea::placeholder { color: #a7f3d0; }
.dlr-textarea.dlr-output   { color: #064e3b; background: #f0fdf4; cursor: default; }
.dlr-textarea::-webkit-scrollbar       { width: 4px; }
.dlr-textarea::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 9999px; }

/* Toggle switch */
.dlr-toggle-row { display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
.dlr-toggle-info { min-width: 0; }
.dlr-toggle-info .dlr-tl { font-size: .82rem; font-weight: 600; color: #374151; }
.dlr-toggle-info .dlr-td { font-size: .68rem; color: #9ca3af; margin-top: .1rem; }
.dlr-toggle {
  position: relative; width: 2.6rem; height: 1.4rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0; cursor: pointer;
}
.dlr-toggle.dlr-on { background: #059669; }
.dlr-toggle::after {
  content: ''; position: absolute; top: .2rem; left: .2rem;
  width: 1rem; height: 1rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.dlr-toggle.dlr-on::after { transform: translateX(1.2rem); }

/* Keep occurrence radio-style toggle */
.dlr-occ-pill {
  flex: 1; padding: .4rem .5rem; text-align: center; font-size: .74rem;
  font-weight: 700; border-radius: .625rem; cursor: pointer; transition: all .15s;
  border: 1.5px solid #a7f3d0; background: #fff; color: #047857;
}
.dlr-occ-pill:hover  { border-color: #059669; background: #ecfdf5; }
.dlr-occ-pill.dlr-on {
  background: #059669; color: #fff; border-color: #059669;
  box-shadow: 0 2px 8px rgba(5,150,105,.25);
}

/* Process button */
.dlr-process-btn {
  width: 100%; padding: .85rem; border-radius: 1rem; border: none;
  background: linear-gradient(135deg, #064e3b, #047857, #059669);
  color: #fff; font-size: .95rem; font-weight: 800; letter-spacing: .02em;
  cursor: pointer; transition: all .2s; box-shadow: 0 4px 18px rgba(5,150,105,.3);
}
.dlr-process-btn:hover  { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(5,150,105,.4); }
.dlr-process-btn:active { transform: translateY(0); }
.dlr-process-btn:disabled { opacity: .45; cursor: not-allowed; transform: none; }

/* Action button (copy/clear) */
.dlr-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.dlr-action:disabled { opacity: .35; cursor: not-allowed; }
.dlr-action-copy  { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
.dlr-action-copy:hover:not(:disabled)  { background: #047857; color: #fff; border-color: #047857; }
.dlr-action-copy.dlr-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.dlr-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.dlr-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Stat cards */
.dlr-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; }
.dlr-stat {
  background: #fff; border: 1.5px solid #d1fae5; border-radius: 1rem;
  padding: .75rem .5rem; text-align: center;
}
.dlr-stat-num { font-size: 1.4rem; font-weight: 900; color: #064e3b; line-height: 1; }
.dlr-stat-num.dlr-red  { color: #dc2626; }
.dlr-stat-num.dlr-grn  { color: #047857; }
.dlr-stat-lbl { font-size: .58rem; font-weight: 800; color: #6ee7b7; text-transform: uppercase; letter-spacing: .08em; margin-top: .3rem; }
.dlr-stat-sub { font-size: .6rem; color: #94a3b8; margin-top: .1rem; }

/* Panel header */
.dlr-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6; background: #fff;
}
.dlr-panel-hdr.dlr-out-hdr { background: #f0fdf4; border-color: #d1fae5; }
.dlr-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.dlr-panel-lbl.dlr-in-lbl  { color: #94a3b8; }
.dlr-panel-lbl.dlr-out-lbl { color: #059669; }

/* Line count pill */
.dlr-lc {
  font-size: .68rem; font-weight: 600; color: #047857;
  background: #ecfdf5; padding: .15rem .5rem;
  border-radius: 9999px; border: 1px solid #a7f3d0;
}

/* Divider */
.dlr-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #059669;
}
.dlr-div::before,.dlr-div::after { content:''; flex:1; height:1px; background:#d1fae5; }

/* Removed-lines badge row */
.dlr-dup-badge {
  display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .65rem;
  border-radius: 9999px; font-size: .72rem; font-weight: 700;
  background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5;
}
.dlr-dup-badge.dlr-zero { background: #ecfdf5; color: #065f46; border-color: #6ee7b7; }

/* Output empty */
.dlr-out-empty {
  min-height: 220px; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: .5rem;
  color: #6ee7b7; background: #f0fdf4; border-top: 1px solid #d1fae5;
}

@keyframes dlrIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.dlr-in { animation: dlrIn .25s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8"
     x-data="dlrCalc()"
     x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg,#064e3b,#059669);">
        <span class="text-3xl">🗑️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Duplicate Line Remover</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto text-sm">
        Paste your text, configure options, and remove duplicate lines instantly.
        Supports case-insensitive matching, whitespace trimming, and more.
      </p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 flex items-start gap-2 text-sm text-red-700">
      <span class="text-base flex-shrink-0">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        <div class="card p-5 space-y-5">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm">
            <span>⚙️</span> Processing Options
          </h2>

          
          <div>
            <label class="form-label text-xs">Keep which occurrence?</label>
            <div class="flex gap-2">
              <button @click="keepLast = false"
                      :class="['dlr-occ-pill', !keepLast ? 'dlr-on' : '']">
                ↑ Keep First
              </button>
              <button @click="keepLast = true"
                      :class="['dlr-occ-pill', keepLast ? 'dlr-on' : '']">
                ↓ Keep Last
              </button>
            </div>
            <p class="text-xs text-gray-400 mt-1.5"
               x-text="keepLast ? 'The last occurrence of each duplicate is kept.' : 'The first occurrence of each duplicate is kept.'">
            </p>
          </div>

          <div class="dlr-div"></div>

          
          <div class="space-y-4">

            
            <div class="dlr-toggle-row cursor-pointer" @click="ignoreCase = !ignoreCase">
              <div class="dlr-toggle-info">
                <div class="dlr-tl">Ignore Case</div>
                <div class="dlr-td">"Hello" and "hello" treated as duplicates</div>
              </div>
              <div :class="['dlr-toggle', ignoreCase ? 'dlr-on' : '']"></div>
            </div>

            
            <div class="dlr-toggle-row cursor-pointer" @click="trimSpaces = !trimSpaces">
              <div class="dlr-toggle-info">
                <div class="dlr-tl">Trim Whitespace</div>
                <div class="dlr-td">Strip leading/trailing spaces before comparing</div>
              </div>
              <div :class="['dlr-toggle', trimSpaces ? 'dlr-on' : '']"></div>
            </div>

            
            <div class="dlr-toggle-row cursor-pointer" @click="removeEmpty = !removeEmpty">
              <div class="dlr-toggle-info">
                <div class="dlr-tl">Remove Empty Lines</div>
                <div class="dlr-td">Delete blank lines from the output</div>
              </div>
              <div :class="['dlr-toggle', removeEmpty ? 'dlr-on' : '']"></div>
            </div>

            
            <div class="dlr-toggle-row cursor-pointer" @click="sortOutput = !sortOutput">
              <div class="dlr-toggle-info">
                <div class="dlr-tl">Sort Output</div>
                <div class="dlr-td">Alphabetically sort the final lines</div>
              </div>
              <div :class="['dlr-toggle', sortOutput ? 'dlr-on' : '']"></div>
            </div>

          </div>

          <div class="dlr-div"></div>

          
          <button
            @click="doProcess()"
            :disabled="!inputText.trim()"
            class="dlr-process-btn"
          >
            ✨ Remove Duplicates
          </button>

          
          <button
            @click="reset()"
            :disabled="!inputText && phase === 'idle'"
            class="dlr-action dlr-action-clear w-full justify-center py-2.5"
          >
            🗑️ Clear Everything
          </button>
        </div>

        
        <div class="card p-4 text-xs text-gray-500 space-y-1.5"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#a7f3d0;">
          <p class="font-bold text-emerald-800 uppercase tracking-wide text-xs">💡 Tips</p>
          <p>Each line is one unit. Lines are compared character-by-character unless options are on.</p>
          <p><strong class="text-gray-700">Ignore Case</strong> + <strong class="text-gray-700">Trim</strong> gives the most aggressive deduplication.</p>
          <p class="pt-1 border-t border-emerald-200 text-emerald-700 font-medium">
            "Keep Last" is useful for config files where the last value overrides earlier ones.
          </p>
        </div>

      </div>

      
      <div class="lg:col-span-3 space-y-4">

        
        <div class="card overflow-hidden">
          <div class="dlr-panel-hdr">
            <span class="dlr-panel-lbl dlr-in-lbl">Input Text</span>
            <div class="flex items-center gap-2">
              <span x-show="inputText" class="dlr-lc"
                    x-text="inputLineCount + ' line' + (inputLineCount !== 1 ? 's' : '')"></span>
              <button @click="loadSample()" class="dlr-action dlr-action-copy"
                      style="padding:.2rem .55rem;font-size:.62rem;">
                📄 Sample
              </button>
            </div>
          </div>
          <textarea
            x-model="inputText"
            placeholder="Paste your lines here — one entry per line…&#10;&#10;apple&#10;banana&#10;apple&#10;Cherry&#10;banana&#10;cherry"
            class="dlr-textarea"
            spellcheck="false"
          ></textarea>
        </div>

        

        
        <div x-show="phase === 'idle'" class="card p-10 text-center text-gray-400">
          <div class="text-4xl mb-3">🗑️</div>
          <p class="font-medium text-gray-500 text-sm">Configure options and click <strong>Remove Duplicates</strong></p>
          <p class="text-xs mt-1">Output and statistics will appear here</p>
        </div>

        
        <div x-show="phase === 'loading'" class="card p-10 text-center">
          <div class="inline-block w-6 h-6 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Processing…</p>
        </div>

        
        <template x-if="phase === 'done'">
          <div class="space-y-4 dlr-in">

            
            <div class="dlr-stat-grid">
              <div class="dlr-stat">
                <div class="dlr-stat-num" x-text="stats.total.toLocaleString()"></div>
                <div class="dlr-stat-lbl">Input</div>
                <div class="dlr-stat-sub">total lines</div>
              </div>
              <div class="dlr-stat">
                <div class="dlr-stat-num dlr-red" x-text="stats.dups.toLocaleString()"></div>
                <div class="dlr-stat-lbl">Removed</div>
                <div class="dlr-stat-sub">duplicates</div>
              </div>
              <div class="dlr-stat" x-show="stats.emptyRemoved > 0">
                <div class="dlr-stat-num dlr-red" x-text="stats.emptyRemoved.toLocaleString()"></div>
                <div class="dlr-stat-lbl">Blanks</div>
                <div class="dlr-stat-sub">removed</div>
              </div>
              <div class="dlr-stat" :class="stats.emptyRemoved === 0 ? 'col-span-1' : ''">
                <div class="dlr-stat-num dlr-grn" x-text="stats.final.toLocaleString()"></div>
                <div class="dlr-stat-lbl">Output</div>
                <div class="dlr-stat-sub">unique lines</div>
              </div>
            </div>

            
            <div class="flex items-center gap-2 flex-wrap">
              <span :class="['dlr-dup-badge', stats.dups === 0 ? 'dlr-zero' : '']">
                <span x-show="stats.dups > 0">🗑️</span>
                <span x-show="stats.dups === 0">✅</span>
                <span x-text="stats.dups > 0
                  ? stats.dups + ' duplicate' + (stats.dups !== 1 ? 's' : '') + ' removed'
                  : 'No duplicates found'">
                </span>
              </span>
              <span x-show="sortOutput"
                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                ↕ Sorted
              </span>
              <span x-show="ignoreCase"
                    class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                Aa case-insensitive
              </span>
            </div>

            
            <div class="card overflow-hidden">
              <div class="dlr-panel-hdr dlr-out-hdr">
                <span class="dlr-panel-lbl dlr-out-lbl">Cleaned Output</span>
                <div class="flex items-center gap-2">
                  <span class="dlr-lc"
                        x-text="stats.final + ' line' + (stats.final !== 1 ? 's' : '')"></span>
                </div>
              </div>

              <div x-show="!outputText.trim()" class="dlr-out-empty">
                <span class="text-3xl">✅</span>
                <span class="text-sm font-medium text-emerald-700">No output — all lines were removed</span>
              </div>

              <textarea
                x-show="outputText.trim()"
                :value="outputText"
                readonly
                class="dlr-textarea dlr-output"
                spellcheck="false"
              ></textarea>
            </div>

            
            <div class="flex flex-wrap gap-2.5 items-center">
              <button @click="copyOutput()"
                      :disabled="!outputText.trim()"
                      :class="['dlr-action dlr-action-copy', copied ? 'dlr-done' : '']">
                <span x-text="copied ? '✓ Copied!' : '⎘ Copy Output'"></span>
              </button>

              <button @click="doProcess()"
                      :disabled="!inputText.trim()"
                      class="dlr-action"
                      style="background:#ecfdf5;color:#047857;border-color:#a7f3d0;">
                ↻ Re-process
              </button>

              <button @click="reset()" class="dlr-action dlr-action-clear">
                🗑️ Clear All
              </button>

              <span class="ml-auto text-xs text-gray-400 font-medium">
                Saved
                <span class="font-bold text-emerald-700"
                      x-text="Math.round((1 - stats.final / Math.max(1, stats.total)) * 100) + '%'"></span>
                of lines
              </span>
            </div>

          </div>
        </template>

      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ────────────────────────────────────────────────────────────
   Duplicate Line Remover — Alpine.js component
   CSS prefix: dlr-   |  Theme: Emerald (#059669)

   Algorithm:
     1. Split input on '\n'
     2. Optionally remove empty lines
     3. Walk forward (keepFirst) or reverse (keepLast):
        - Build comparison key (trim / lower if options on)
        - Track seen keys in a Set
        - Collect unique lines, count duplicates
     4. Optionally sort output alphabetically
     5. Join with '\n' → outputText
──────────────────────────────────────────────────────────── */
function dlrCalc() {
  return {

    inputText:   '',
    outputText:  '',

    /* Options */
    ignoreCase:  false,
    trimSpaces:  true,
    removeEmpty: false,
    keepLast:    false,
    sortOutput:  false,

    /* UI state */
    phase:    'idle',   // 'idle' | 'loading' | 'done'
    copied:   false,
    errorMsg: '',
    stats:    null,     // { total, dups, emptyRemoved, final }

    init() {},

    /* Computed */
    get inputLineCount() {
      if (!this.inputText) return 0;
      return this.inputText.split('\n').length;
    },

    /* ── Actions ─────────────────────────────────────────── */

    doProcess() {
      this.errorMsg = '';

      if (!this.inputText.trim()) {
        this.errorMsg = 'Please paste some text before processing.';
        return;
      }

      this.phase  = 'loading';
      this.copied = false;

      var self = this;
      setTimeout(function () {
        try {
          self._process();
          self.phase = 'done';
        } catch (e) {
          self.errorMsg = 'An unexpected error occurred. Please try again.';
          self.phase = 'idle';
        }
      }, 60);
    },

    reset() {
      this.inputText   = '';
      this.outputText  = '';
      this.phase       = 'idle';
      this.errorMsg    = '';
      this.stats       = null;
      this.copied      = false;
    },

    copyOutput() {
      if (!this.outputText.trim()) return;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.outputText)
          .then(function ()  { self._flash(); })
          .catch(function () { self._legacyCopy(self.outputText); });
      } else {
        this._legacyCopy(this.outputText);
      }
    },

    loadSample() {
      this.inputText =
        "apple\nBanana\napple\ncherry\nBANANA\ndate\nCherry\napple\nelderberry\ndate\n\nfig\nFIG\ngrape\n\napple";
      this.phase     = 'idle';
      this.outputText = '';
      this.stats     = null;
    },

    /* ── Core algorithm ──────────────────────────────────── */

    _process() {
      var raw   = this.inputText.split('\n');
      var total = raw.length;

      /* Step 1 — optionally remove empty lines */
      var emptyRemoved = 0;
      var lines = raw;
      if (this.removeEmpty) {
        lines = raw.filter(function (l) { return l.trim() !== ''; });
        emptyRemoved = total - lines.length;
      }

      /* Step 2 — build comparison key per line */
      var self = this;
      function makeKey(line) {
        var k = self.trimSpaces  ? line.trim()        : line;
        if (self.ignoreCase) k  = k.toLowerCase();
        return k;
      }

      /* Step 3 — deduplicate */
      var seen     = new Set();
      var unique   = [];
      var dupCount = 0;

      if (!this.keepLast) {
        /* Keep FIRST occurrence — forward scan */
        for (var i = 0; i < lines.length; i++) {
          var key = makeKey(lines[i]);
          if (seen.has(key)) {
            dupCount++;
          } else {
            seen.add(key);
            unique.push(lines[i]);
          }
        }
      } else {
        /* Keep LAST occurrence — reverse scan, then restore order */
        for (var j = lines.length - 1; j >= 0; j--) {
          var rKey = makeKey(lines[j]);
          if (seen.has(rKey)) {
            dupCount++;
          } else {
            seen.add(rKey);
            unique.push(lines[j]);
          }
        }
        unique.reverse();
      }

      /* Step 4 — optional sort */
      if (this.sortOutput) {
        var ic = this.ignoreCase;
        unique.sort(function (a, b) {
          var ka = ic ? a.toLowerCase() : a;
          var kb = ic ? b.toLowerCase() : b;
          return ka.localeCompare(kb);
        });
      }

      /* Step 5 — write results */
      this.outputText = unique.join('\n');
      this.stats = {
        total:        total,
        dups:         dupCount,
        emptyRemoved: emptyRemoved,
        final:        unique.length,
      };
    },

    /* ── Helpers ─────────────────────────────────────────── */

    _flash() {
      var self = this;
      this.copied = true;
      setTimeout(function () { self.copied = false; }, 2200);
    },

    _legacyCopy(text) {
      var el = document.createElement('textarea');
      el.value = text;
      el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
      document.body.appendChild(el);
      el.focus(); el.select();
      try { document.execCommand('copy'); this._flash(); } catch (e) {}
      document.body.removeChild(el);
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\duplicate-line-remover.blade.php ENDPATH**/ ?>