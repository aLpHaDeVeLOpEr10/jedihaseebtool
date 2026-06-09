<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Text Reverser  —  prefix: tr-
   Theme: Rose (#be123c / #e11d48 / #f43f5e)
   Live reactive: output = getter on (inputText, mode)
   No backend. No page reload. Pure Alpine.js.
══════════════════════════════════════════════ */

/* Textareas */
.tr-textarea {
  width: 100%; min-height: 200px; resize: vertical;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .95rem; line-height: 1.75; color: #1e293b;
  caret-color: #e11d48; border: none; outline: none;
  padding: 1.1rem 1.25rem; background: transparent;
}
.tr-textarea::placeholder { color: #fecdd3; }
.tr-textarea.tr-output    { color: #4c0519; background: #fff1f2; cursor: default; }
.tr-textarea::-webkit-scrollbar       { width: 4px; }
.tr-textarea::-webkit-scrollbar-thumb { background: #fecdd3; border-radius: 9999px; }

/* Mode button */
.tr-mode {
  display: flex; flex-direction: column; align-items: flex-start; gap: .25rem;
  padding: .75rem .9rem; border-radius: 1rem; cursor: pointer;
  border: 1.5px solid #ffe4e6; background: #fff;
  transition: all .15s; text-align: left; width: 100%;
}
.tr-mode:hover {
  border-color: #e11d48; background: #fff1f2;
  transform: translateY(-1px); box-shadow: 0 4px 12px rgba(225,29,72,.1);
}
.tr-mode.tr-on {
  background: linear-gradient(135deg, #881337, #be123c, #e11d48);
  border-color: #be123c; box-shadow: 0 4px 18px rgba(190,18,60,.3);
}
.tr-mode .tr-mode-icon  { font-size: 1rem; line-height: 1; }
.tr-mode .tr-mode-label {
  font-size: .75rem; font-weight: 800; color: #9f1239; letter-spacing: .01em;
  white-space: nowrap;
}
.tr-mode.tr-on .tr-mode-label { color: #fecdd3; }
.tr-mode .tr-mode-ex {
  font-size: .64rem; color: #94a3b8; font-weight: 500;
  font-family: 'JetBrains Mono','Fira Code',monospace;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;
}
.tr-mode.tr-on .tr-mode-ex { color: #fda4af; }

/* Panel header */
.tr-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6;
}
.tr-panel-hdr.tr-out-hdr { background: #fff1f2; border-color: #ffe4e6; }
.tr-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em;
}
.tr-panel-lbl.tr-in-lbl  { color: #94a3b8; }
.tr-panel-lbl.tr-out-lbl { color: #e11d48; }

/* Stat pill */
.tr-stat {
  font-size: .68rem; font-weight: 600; color: #be123c;
  background: #fff1f2; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #fecdd3;
}

/* Action button */
.tr-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.tr-action:disabled { opacity: .35; cursor: not-allowed; }
.tr-action-copy  { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
.tr-action-copy:hover:not(:disabled)  { background: #be123c; color: #fff; border-color: #be123c; }
.tr-action-copy.tr-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.tr-action-swap  { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
.tr-action-swap:hover:not(:disabled)  { background: #0369a1; color: #fff; border-color: #0369a1; }
.tr-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.tr-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }
.tr-action-sample{ background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.tr-action-sample:hover { background: #15803d; color: #fff; border-color: #15803d; }

/* Palindrome badge */
.tr-palindrome {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .35rem .85rem; border-radius: 9999px; font-size: .75rem; font-weight: 700;
  background: linear-gradient(135deg, #fdf4ff, #fce7f3);
  color: #7e22ce; border: 1.5px solid #e9d5ff;
  animation: trPalindromePop .4s ease-out;
}
@keyframes trPalindromePop {
  0%   { transform: scale(.7); opacity: 0; }
  70%  { transform: scale(1.05); }
  100% { transform: scale(1);   opacity: 1; }
}

/* Output empty state */
.tr-out-empty {
  min-height: 200px; display: flex; align-items: center; justify-content: center;
  color: #fda4af; font-size: .88rem; font-style: italic;
  background: #fff1f2; border-top: 1px solid #ffe4e6;
}

/* Divider */
.tr-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #e11d48;
}
.tr-div::before,.tr-div::after { content:''; flex:1; height:1px; background:#ffe4e6; }

@keyframes trIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
.tr-in { animation: trIn .2s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="trCalc()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔄 Text Reverser
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Reverse text by character, word order, each word, or line — output updates as you type.
          </p>
        </div>
        <button @click="loadSample()" class="tr-action tr-action-sample self-start sm:self-auto">
          📄 Sample Text
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    
    <div class="space-y-2">
      <p class="tr-div">Choose Reversal Mode</p>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
        <template x-for="m in modes" :key="m.key">
          <button @click="mode = m.key"
                  :class="['tr-mode', mode === m.key ? 'tr-on' : '']">
            <span class="tr-mode-icon" x-text="m.icon"></span>
            <span class="tr-mode-label" x-text="m.label"></span>
            <span class="tr-mode-ex"   x-text="m.ex"></span>
          </button>
        </template>
      </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      
      <div class="card overflow-hidden">
        <div class="tr-panel-hdr">
          <span class="tr-panel-lbl tr-in-lbl">Your Text</span>
          <div class="flex items-center gap-2">
            <span x-show="inputText.trim()" class="tr-stat"
                  x-text="inputWords + ' words'"></span>
            <span x-show="inputText.trim()" class="tr-stat"
                  x-text="inputChars + ' chars'"></span>
          </div>
        </div>
        <textarea
          x-model="inputText"
          placeholder="Type or paste your text here…"
          class="tr-textarea"
          spellcheck="true"
        ></textarea>
      </div>

      
      <div class="card overflow-hidden">
        <div class="tr-panel-hdr tr-out-hdr">
          <span class="tr-panel-lbl tr-out-lbl" x-text="activeLabel + ' Result'"></span>
          <div class="flex items-center gap-2">
            <span x-show="outputText.trim()" class="tr-stat"
                  x-text="outputChars + ' chars'"></span>
          </div>
        </div>

        
        <div x-show="!outputText" class="tr-out-empty">
          <span x-text="!inputText.trim() ? 'Enter text on the left…' : '← select a mode'"></span>
        </div>

        
        <textarea
          x-show="outputText"
          :value="outputText"
          readonly
          class="tr-textarea tr-output"
          spellcheck="false"
        ></textarea>
      </div>

    </div>

    
    <div class="flex flex-wrap items-center gap-2.5">

      <button @click="copyOutput()"
              :disabled="!outputText"
              :class="['tr-action tr-action-copy', copied ? 'tr-done' : '']">
        <span x-text="copied ? '✓ Copied!' : '⎘ Copy Result'"></span>
      </button>

      <button @click="swapToInput()"
              :disabled="!outputText"
              class="tr-action tr-action-swap"
              title="Put the reversed text back into the input field">
        ⇅ Swap to Input
      </button>

      <button @click="clear()"
              :disabled="!inputText"
              class="tr-action tr-action-clear">
        🗑️ Clear
      </button>

      
      <div x-show="isPalindrome" x-transition class="tr-palindrome">
        <span>🎉</span>
        <span>Palindrome!</span>
      </div>

      
      <div x-show="inputText && outputText && !isPalindrome"
           class="ml-auto flex items-center gap-1.5 text-xs font-semibold text-rose-700
                  bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 flex-shrink-0"></span>
        <span x-text="activeLabel"></span>
      </div>

    </div>

    
    <div class="card p-5">
      <p class="tr-div mb-3">Mode Reference</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
        <template x-for="m in modes" :key="m.key + '_ref'">
          <div class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-rose-50 cursor-pointer transition-colors"
               @click="mode = m.key">
            <span class="text-lg flex-shrink-0" x-text="m.icon"></span>
            <div class="min-w-0">
              <p class="text-xs font-bold text-rose-800" x-text="m.label"></p>
              <p class="text-xs text-gray-500 mt-0.5" x-text="m.desc"></p>
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
   Text Reverser — Alpine.js component
   CSS prefix: tr-   |  Theme: Rose (#be123c)

   Non-destructive dual-pane (same pattern as case-converter):
     inputText  — user's editable text, never touched by reversals.
     outputText — reactive getter: _transform(mode, inputText).

   Unicode-safe: uses [...str] spread (handles emoji & surrogates).
   "Swap to Input" copies output → input for chaining reversals.
──────────────────────────────────────────────────────────── */
function trCalc() {
  return {

    inputText: '',
    mode:      'full',
    copied:    false,

    modes: [
      {
        key:   'full',
        icon:  '🔤',
        label: 'Full Reverse',
        ex:    '"Hello" → "olleH"',
        desc:  'Reverses every character in the entire text',
      },
      {
        key:   'words',
        icon:  '🔀',
        label: 'Word Order',
        ex:    '"A B C" → "C B A"',
        desc:  'Reverses the order of words, keeping each word intact',
      },
      {
        key:   'each-word',
        icon:  '↩️',
        label: 'Reverse Each Word',
        ex:    '"Hi Bye" → "iH eyB"',
        desc:  'Reverses the letters inside every word individually',
      },
      {
        key:   'lines',
        icon:  '📋',
        label: 'Line Order',
        ex:    'Line 1 ↔ Line 3',
        desc:  'Reverses the order of lines (for multi-line text)',
      },
      {
        key:   'each-line',
        icon:  '↔️',
        label: 'Reverse Each Line',
        ex:    '"abc" → "cba" per line',
        desc:  'Reverses characters within each line independently',
      },
    ],

    init() {},

    /* ── Computed getters ──────────────────────────────────── */

    get outputText() {
      if (!this.inputText) return '';
      return this._transform(this.mode, this.inputText);
    },

    get activeLabel() {
      var found = this.modes.find(function (m) { return m.key === this.mode; }, this);
      return found ? found.label : '';
    },

    get inputWords() {
      return this.inputText.trim() ? this.inputText.trim().split(/\s+/).length : 0;
    },
    get inputChars()  { return this.inputText.length; },
    get outputChars() { return this.outputText.length; },

    get isPalindrome() {
      // Only meaningful for Full Reverse mode
      if (this.mode !== 'full' || !this.inputText.trim()) return false;
      // Strip punctuation/spaces/case for check
      var clean = this.inputText.toLowerCase().replace(/[^a-z0-9]/g, '');
      if (clean.length < 2) return false;
      return clean === [...clean].reverse().join('');
    },

    /* ── Actions ───────────────────────────────────────────── */

    copyOutput() {
      if (!this.outputText) return;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.outputText)
          .then(function ()  { self._flash(); })
          .catch(function () { self._legacyCopy(self.outputText); });
      } else {
        this._legacyCopy(this.outputText);
      }
    },

    swapToInput() {
      if (!this.outputText) return;
      this.inputText = this.outputText;
      this.copied    = false;
    },

    clear() {
      this.inputText = '';
      this.copied    = false;
    },

    loadSample() {
      this.inputText = "Hello World! This is a Text Reverser.\nSecond line here.\nThird line of sample text.";
      this.mode      = 'full';
    },

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

    /* ── Transform dispatcher ──────────────────────────────── */

    _transform(mode, text) {
      switch (mode) {
        case 'full':       return this._reverseFull(text);
        case 'words':      return this._reverseWordOrder(text);
        case 'each-word':  return this._reverseEachWord(text);
        case 'lines':      return this._reverseLineOrder(text);
        case 'each-line':  return this._reverseEachLine(text);
        default:           return text;
      }
    },

    /* ── Reversal algorithms ───────────────────────────────── */

    // Full reverse: every character flipped, line breaks preserved in position
    // Uses spread [...s] for correct Unicode / emoji handling
    _reverseFull(text) {
      return [...text].reverse().join('');
    },

    // Reverse word order across the entire text (treats all whitespace as one separator)
    _reverseWordOrder(text) {
      // Split on whitespace, remove empties, reverse, rejoin with single spaces
      var words = text.trim().split(/\s+/);
      return words.reverse().join(' ');
    },

    // Reverse the letters inside every word; keep whitespace tokens in place
    _reverseEachWord(text) {
      // Split on whitespace-runs, keeping the delimiters as tokens
      return text.split(/(\s+)/).map(function (tok) {
        if (/^\s/.test(tok)) return tok;           // preserve whitespace
        return [...tok].reverse().join('');         // reverse the word
      }).join('');
    },

    // Reverse the order of lines
    _reverseLineOrder(text) {
      return text.split('\n').reverse().join('\n');
    },

    // Reverse characters within each line independently; newlines stay in place
    _reverseEachLine(text) {
      return text.split('\n').map(function (line) {
        return [...line].reverse().join('');
      }).join('\n');
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\text-reverser.blade.php ENDPATH**/ ?>