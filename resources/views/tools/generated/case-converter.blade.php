@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Case Converter  —  prefix: cc-
   Theme: Violet (#6d28d9 / #7c3aed / #8b5cf6)
   Non-destructive: input preserved, output is
   a live computed getter on (inputText, activeCase)
══════════════════════════════════════════════ */

/* Textareas */
.cc-textarea {
  width: 100%; resize: vertical; min-height: 160px;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .95rem; line-height: 1.75; color: #1e293b;
  caret-color: #7c3aed; border: none; outline: none;
  padding: 1rem 1.1rem; background: transparent;
}
.cc-textarea::placeholder { color: #ddd6fe; }
.cc-textarea.cc-output {
  color: #2e1065; background: #faf5ff; cursor: default;
  border-top: 1px solid #ede9fe;
}
.cc-textarea::-webkit-scrollbar { width: 4px; }
.cc-textarea::-webkit-scrollbar-thumb { background: #ddd6fe; border-radius: 9999px; }

/* Case-type button */
.cc-case-btn {
  display: flex; flex-direction: column; align-items: flex-start; gap: .3rem;
  padding: .8rem .95rem; border-radius: 1rem; cursor: pointer;
  border: 1.5px solid #ede9fe; background: #fff;
  transition: all .15s; text-align: left; width: 100%;
}
.cc-case-btn:hover {
  border-color: #7c3aed; background: #f5f3ff;
  transform: translateY(-1px); box-shadow: 0 4px 14px rgba(124,58,237,.12);
}
.cc-case-btn.cc-selected {
  background: linear-gradient(135deg, #4c1d95, #6d28d9, #7c3aed);
  border-color: #6d28d9; box-shadow: 0 4px 18px rgba(109,40,217,.3);
}
.cc-case-btn .cc-btn-label {
  font-size: .8rem; font-weight: 800; color: #5b21b6; letter-spacing: .02em;
  white-space: nowrap;
}
.cc-case-btn.cc-selected .cc-btn-label { color: #ede9fe; }
.cc-case-btn .cc-btn-ex {
  font-size: .68rem; color: #94a3b8; font-weight: 500;
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  white-space: nowrap; overflow: hidden; max-width: 100%; text-overflow: ellipsis;
}
.cc-case-btn.cc-selected .cc-btn-ex { color: #c4b5fd; }
.cc-case-btn .cc-btn-icon {
  font-size: 1.1rem; line-height: 1; flex-shrink: 0;
}

/* Action bar button */
.cc-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.cc-action:disabled { opacity: .35; cursor: not-allowed; }
.cc-action-copy    { background: #f5f3ff; color: #6d28d9; border-color: #ddd6fe; }
.cc-action-copy:hover:not(:disabled)  { background: #6d28d9; color: #fff; border-color: #6d28d9; }
.cc-action-copy.cc-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.cc-action-clear   { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.cc-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }
.cc-action-use     { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.cc-action-use:hover:not(:disabled)   { background: #15803d; color: #fff; border-color: #15803d; }
.cc-action-sample  { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
.cc-action-sample:hover { background: #c2410c; color: #fff; border-color: #c2410c; }

/* Stat pill */
.cc-stat {
  font-size: .68rem; font-weight: 600; color: #7c3aed;
  background: #f5f3ff; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #ede9fe;
}

/* Panel header */
.cc-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; background: #faf5ff; border-bottom: 1px solid #ede9fe;
}
.cc-panel-hdr.cc-input-hdr { background: #fff; border-bottom: 1px solid #f3f4f6; }
.cc-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #7c3aed;
}
.cc-panel-lbl.cc-input-lbl { color: #94a3b8; }

/* Divider */
.cc-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #7c3aed;
}
.cc-div::before,.cc-div::after { content:''; flex:1; height:1px; background:#ede9fe; }

/* Empty output placeholder */
.cc-output-placeholder {
  min-height: 160px; display: flex; align-items: center; justify-content: center;
  color: #c4b5fd; font-size: .88rem; font-style: italic;
  background: #faf5ff; border-top: 1px solid #ede9fe;
}

@keyframes ccFadeIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
.cc-fadein { animation: ccFadeIn .2s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="ccCalc()"
     x-init="init()">

  {{-- ── Header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            ✍️ Case Converter
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Type or paste your text, choose a case — output updates instantly. Non-destructive: your original is always preserved.
          </p>
        </div>
        <button @click="loadSample()" class="cc-action cc-action-sample self-start sm:self-auto">
          📄 Sample Text
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    {{-- ── Case type buttons ── --}}
    <div class="space-y-2">
      <p class="cc-div">Choose a Case</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
        <template x-for="c in caseTypes" :key="c.key">
          <button
            @click="selectCase(c.key)"
            :class="['cc-case-btn', activeCase === c.key ? 'cc-selected' : '']"
          >
            <span class="cc-btn-icon" x-text="c.icon"></span>
            <span class="cc-btn-label" x-text="c.label"></span>
            <span class="cc-btn-ex"   x-text="c.ex"></span>
          </button>
        </template>
      </div>
    </div>

    {{-- ── Input / Output panes ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      {{-- Input --}}
      <div class="card overflow-hidden">
        <div class="cc-panel-hdr cc-input-hdr">
          <span class="cc-panel-lbl cc-input-lbl">Your Text</span>
          <div class="flex items-center gap-2">
            <span x-show="inputText.trim()" class="cc-stat" x-text="inputWords + ' words'"></span>
            <span x-show="inputText.trim()" class="cc-stat" x-text="inputChars + ' chars'"></span>
          </div>
        </div>
        <textarea
          x-model="inputText"
          placeholder="Paste or type your text here…"
          class="cc-textarea"
          spellcheck="true"
        ></textarea>
      </div>

      {{-- Output --}}
      <div class="card overflow-hidden">
        <div class="cc-panel-hdr">
          <span class="cc-panel-lbl" x-text="activeCase ? activeLabel + ' Result' : 'Converted Text'"></span>
          <div class="flex items-center gap-2">
            <span x-show="outputText.trim()" class="cc-stat" x-text="outputWords + ' words'"></span>
            <span x-show="outputText.trim()" class="cc-stat" x-text="outputChars + ' chars'"></span>
          </div>
        </div>

        {{-- Empty state --}}
        <div x-show="!outputText" class="cc-output-placeholder">
          <span x-text="!inputText.trim() ? 'Enter text on the left…' : 'Select a case type above…'"></span>
        </div>

        {{-- Result --}}
        <textarea
          x-show="outputText"
          :value="outputText"
          readonly
          class="cc-textarea cc-output"
          spellcheck="false"
        ></textarea>
      </div>

    </div>

    {{-- ── Action row ── --}}
    <div class="flex flex-wrap items-center gap-2.5">

      <button
        @click="copyOutput()"
        :disabled="!outputText"
        :class="['cc-action cc-action-copy', copied ? 'cc-done' : '']"
      >
        <span x-text="copied ? '✓ Copied!' : '⎘ Copy Result'"></span>
      </button>

      <button
        @click="useOutput()"
        :disabled="!outputText"
        class="cc-action cc-action-use"
        title="Replace input with the converted result"
      >
        ↓ Apply to Input
      </button>

      <button
        @click="clear()"
        :disabled="!inputText"
        class="cc-action cc-action-clear"
      >
        🗑️ Clear All
      </button>

      {{-- Last-action badge --}}
      <div x-show="activeCase && outputText"
           x-transition
           class="ml-auto flex items-center gap-1.5 text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-200 px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 rounded-full bg-violet-500 flex-shrink-0"></span>
        <span x-text="activeLabel + ' applied'"></span>
      </div>

    </div>

    {{-- ── Quick reference card ── --}}
    <div class="card p-5">
      <p class="cc-div mb-3">Case Reference</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
        <template x-for="c in caseTypes" :key="c.key + '_ref'">
          <div class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-violet-50 transition-colors cursor-default"
               @click="selectCase(c.key)">
            <span class="text-lg flex-shrink-0" x-text="c.icon"></span>
            <div class="min-w-0">
              <p class="text-xs font-bold text-violet-800" x-text="c.label"></p>
              <p class="text-xs text-gray-500 mt-0.5" x-text="c.desc"></p>
            </div>
          </div>
        </template>
      </div>
      <p class="text-xs text-gray-400 mt-3 text-center">Click any row above to apply that case type.</p>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ────────────────────────────────────────────────────────────
   Case Converter — Alpine.js component
   CSS prefix: cc-   |  Theme: Violet (#6d28d9)

   Design: non-destructive dual-pane.
     inputText  — editable by user, never touched by conversions.
     outputText — reactive getter: _transform(activeCase, inputText).
   Clicking "Apply to Input" copies output → input (one-way merge).
──────────────────────────────────────────────────────────── */
function ccCalc() {
  return {

    inputText:  '',
    activeCase: null,
    copied:     false,

    /* ── Case type definitions ─────────────────────────────── */
    caseTypes: [
      {
        key: 'upper',    icon: '⇧',
        label: 'UPPERCASE',
        ex:   'HELLO WORLD',
        desc: 'Every letter converted to uppercase',
      },
      {
        key: 'lower',    icon: '⇩',
        label: 'lowercase',
        ex:   'hello world',
        desc: 'Every letter converted to lowercase',
      },
      {
        key: 'title',    icon: '🔤',
        label: 'Title Case',
        ex:   'Hello the World',
        desc: 'Capitalise major words; skip minor ones (a, an, the, and…)',
      },
      {
        key: 'sentence', icon: '📝',
        label: 'Sentence case',
        ex:   'Hello world. Hi there.',
        desc: 'Capitalise first letter of each sentence',
      },
      {
        key: 'cap',      icon: '🔡',
        label: 'Capitalized Case',
        ex:   'Hello World Here',
        desc: 'First letter of every word capitalised — no exceptions',
      },
      {
        key: 'alt',      icon: '↕',
        label: 'aLtErNaTiNg cAsE',
        ex:   'hElLo wOrLd',
        desc: 'Alternates lowercase and uppercase letter by letter',
      },
      {
        key: 'inverse',  icon: '↔',
        label: 'iNVERSE cASE',
        ex:   'hELLO wORLD',
        desc: 'Flips the case of every letter individually',
      },
      {
        key: 'spaces',   icon: '⎵',
        label: 'Remove Extra Spaces',
        ex:   '"a  b" → "a b"',
        desc: 'Collapses multiple spaces, trims lines, max 1 blank line',
      },
    ],

    init() { /* nothing to preload */ },

    /* ── Computed getters ──────────────────────────────────── */
    get outputText() {
      if (!this.inputText || !this.activeCase) return '';
      return this._transform(this.activeCase, this.inputText);
    },
    get activeLabel() {
      if (!this.activeCase) return '';
      var found = this.caseTypes.find(function(c) { return c.key === this.activeCase; }, this);
      return found ? found.label : '';
    },
    get inputWords()  {
      return this.inputText.trim() ? this.inputText.trim().split(/\s+/).length : 0;
    },
    get inputChars()  { return this.inputText.length; },
    get outputWords() {
      return this.outputText.trim() ? this.outputText.trim().split(/\s+/).length : 0;
    },
    get outputChars() { return this.outputText.length; },

    /* ── Actions ───────────────────────────────────────────── */
    selectCase(key) {
      this.activeCase = key;
      this.copied = false;
    },

    copyOutput() {
      if (!this.outputText) return;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.outputText)
          .then(function()  { self._flash(); })
          .catch(function() { self._legacyCopy(self.outputText); });
      } else {
        this._legacyCopy(this.outputText);
      }
    },

    useOutput() {
      if (!this.outputText) return;
      this.inputText  = this.outputText;
      this.activeCase = null;
      this.copied     = false;
    },

    clear() {
      this.inputText  = '';
      this.activeCase = null;
      this.copied     = false;
    },

    loadSample() {
      this.inputText  = "the quick brown fox jumps over the lazy dog.\nTHIS SENTENCE HAS INCONSISTENT casing — and   extra   spaces too.\n\nanother paragraph: what a wonderful WORLD we live in!";
      this.activeCase = null;
    },

    _flash() {
      var self = this;
      this.copied = true;
      setTimeout(function() { self.copied = false; }, 2200);
    },

    _legacyCopy(text) {
      var el = document.createElement('textarea');
      el.value = text;
      el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
      document.body.appendChild(el);
      el.focus(); el.select();
      try { document.execCommand('copy'); this._flash(); } catch(e) {}
      document.body.removeChild(el);
    },

    /* ── Core transform dispatcher ─────────────────────────── */
    _transform(key, text) {
      switch (key) {
        case 'upper':    return text.toUpperCase();
        case 'lower':    return text.toLowerCase();
        case 'title':    return this._doTitleCase(text);
        case 'sentence': return this._doSentenceCase(text);
        case 'cap':      return this._doCapitalized(text);
        case 'alt':      return this._doAlternating(text);
        case 'inverse':  return this._doInverse(text);
        case 'spaces':   return this._doCleanSpaces(text);
        default:         return text;
      }
    },

    /* ── Case algorithms ───────────────────────────────────── */

    // Smart Title Case: lowercase minor words unless first/last in sentence
    _doTitleCase(text) {
      var minor = new Set([
        'a','an','the','and','but','or','nor','for','yet','so',
        'at','by','in','of','on','to','up','as','via','vs','vs.',
        'per','over','into','onto','from','with','than',
      ]);
      // Process line by line so first word of each line is always capitalised
      return text.split('\n').map(function(line) {
        var tokens = line.split(/(\s+)/);   // keep whitespace tokens
        var wordIdx = 0;
        var wordTotal = tokens.filter(function(t) { return !/^\s/.test(t) && t.length > 0; }).length;
        return tokens.map(function(tok) {
          if (/^\s/.test(tok) || tok === '') return tok;
          var letters = tok.replace(/[^a-zA-Z]/g, '').toLowerCase();
          var isFirst = wordIdx === 0;
          var isLast  = wordIdx === wordTotal - 1;
          wordIdx++;
          if (isFirst || isLast || !minor.has(letters)) {
            // Capitalise first letter in token (skip leading punctuation)
            return tok.toLowerCase().replace(/[a-z]/, function(c) { return c.toUpperCase(); });
          }
          return tok.toLowerCase();
        }).join('');
      }).join('\n');
    },

    // Sentence case: capitalise after . ! ? and at line start
    _doSentenceCase(text) {
      var result = text.toLowerCase();
      // After sentence-ending punctuation + whitespace
      result = result.replace(/([.!?]+\s+)([a-z])/g, function(m, p1, p2) {
        return p1 + p2.toUpperCase();
      });
      // After newline
      result = result.replace(/(\n[ \t]*)([a-z])/g, function(m, p1, p2) {
        return p1 + p2.toUpperCase();
      });
      // Very first letter (skip any leading non-letter chars)
      result = result.replace(/^([^a-zA-Z]*)([a-z])/, function(m, prefix, letter) {
        return prefix + letter.toUpperCase();
      });
      return result;
    },

    // Capitalised Case: every word's first letter → uppercase, rest → lowercase
    _doCapitalized(text) {
      return text.toLowerCase().replace(/\b[a-z]/g, function(c) {
        return c.toUpperCase();
      });
    },

    // Alternating case: letters alternate lo/HI, non-letters ignored in count
    _doAlternating(text) {
      var idx = 0;
      return text.split('').map(function(c) {
        if (c.match(/[a-zA-Z]/)) {
          return (idx++ % 2 === 0) ? c.toLowerCase() : c.toUpperCase();
        }
        return c;
      }).join('');
    },

    // Inverse case: flip each letter's case individually
    _doInverse(text) {
      return text.split('').map(function(c) {
        if (c >= 'a' && c <= 'z') return c.toUpperCase();
        if (c >= 'A' && c <= 'Z') return c.toLowerCase();
        return c;
      }).join('');
    },

    // Clean spaces: collapse multiples, trim lines, max 1 blank line
    _doCleanSpaces(text) {
      return text
        .replace(/\t/g, ' ')           // tabs → single space
        .replace(/[ ]{2,}/g, ' ')      // 2+ spaces → 1 space
        .replace(/^ +/gm, '')          // strip leading spaces per line
        .replace(/ +$/gm, '')          // strip trailing spaces per line
        .replace(/\n{3,}/g, '\n\n')    // 3+ blank lines → 1 blank line
        .trim();
    },
  };
}
</script>
@endpush
