@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Lorem Ipsum Generator  —  prefix: li-
   Theme: Teal (#0f766e / #0d9488 / #14b8a6)
   Pure client-side; no backend required.
══════════════════════════════════════════════ */

/* Type pill toggle */
.li-pill {
  flex: 1; padding: .55rem .5rem; border-radius: .75rem; font-size: .78rem;
  font-weight: 700; text-align: center; cursor: pointer; transition: all .18s;
  border: 1.5px solid #99f6e4; background: #fff; color: #0f766e;
  white-space: nowrap;
}
.li-pill:hover   { border-color: #0d9488; background: #f0fdfa; }
.li-pill.li-on   {
  background: linear-gradient(135deg, #134e4a, #0f766e, #0d9488);
  border-color: #0f766e; color: #fff;
  box-shadow: 0 4px 14px rgba(13,148,136,.25);
}

/* Format pill (smaller) */
.li-fmt {
  padding: .35rem .75rem; border-radius: .625rem; font-size: .7rem;
  font-weight: 700; cursor: pointer; transition: all .15s;
  border: 1.5px solid #ccfbf1; background: #fff; color: #0f766e;
  white-space: nowrap;
}
.li-fmt:hover { border-color: #0d9488; background: #f0fdfa; }
.li-fmt.li-on {
  background: #0f766e; color: #fff; border-color: #0f766e;
  box-shadow: 0 2px 8px rgba(13,148,136,.2);
}

/* Count stepper */
.li-stepper { display: flex; align-items: center; gap: 0; border-radius: .875rem; overflow: hidden; border: 1.5px solid #99f6e4; }
.li-step-btn {
  width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center;
  background: #f0fdfa; color: #0f766e; font-size: 1.1rem; font-weight: 700;
  cursor: pointer; border: none; transition: background .15s; flex-shrink: 0; line-height: 1;
}
.li-step-btn:hover { background: #ccfbf1; }
.li-step-btn:disabled { opacity: .3; cursor: not-allowed; }
.li-step-input {
  flex: 1; min-width: 0; text-align: center; border: none; outline: none;
  font-size: 1.05rem; font-weight: 800; color: #134e4a;
  padding: .4rem .25rem; background: #fff;
}
.li-step-input::-webkit-outer-spin-button,
.li-step-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

/* Quick preset */
.li-quick {
  padding: .25rem .55rem; border-radius: 9999px; font-size: .68rem; font-weight: 700;
  cursor: pointer; background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4;
  transition: all .13s;
}
.li-quick:hover { background: #0d9488; color: #fff; border-color: #0d9488; }

/* Toggle checkbox */
.li-toggle-wrap { display: flex; align-items: center; gap: .6rem; cursor: pointer; }
.li-toggle {
  position: relative; width: 2.5rem; height: 1.35rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0; cursor: pointer;
}
.li-toggle.li-on { background: #0d9488; }
.li-toggle::after {
  content: ''; position: absolute; top: .175rem; left: .175rem;
  width: 1rem; height: 1rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.li-toggle.li-on::after { transform: translateX(1.15rem); }
.li-toggle-label { font-size: .82rem; font-weight: 600; color: #475569; }

/* Output textarea */
.li-output {
  width: 100%; min-height: 320px; resize: vertical;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .9rem; line-height: 1.8; color: #134e4a;
  border: none; outline: none; padding: 1.1rem 1.25rem; background: transparent;
}
.li-output::-webkit-scrollbar { width: 4px; }
.li-output::-webkit-scrollbar-thumb { background: #99f6e4; border-radius: 9999px; }

/* Output HTML preview */
.li-preview {
  min-height: 200px; padding: 1.1rem 1.25rem;
  font-size: .9rem; line-height: 1.8; color: #134e4a;
  overflow-y: auto;
}
.li-preview p { margin-bottom: .75rem; }
.li-preview p:last-child { margin-bottom: 0; }

/* Action button */
.li-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.li-action:disabled { opacity: .35; cursor: not-allowed; }
.li-action-copy  { background: #f0fdfa; color: #0f766e; border-color: #99f6e4; }
.li-action-copy:hover:not(:disabled) { background: #0f766e; color: #fff; border-color: #0f766e; }
.li-action-copy.li-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.li-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.li-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }
.li-action-preview { background: #f0fdfa; color: #0f766e; border-color: #99f6e4; }
.li-action-preview.li-on { background: #0f766e; color: #fff; border-color: #0f766e; }

/* Stat pill */
.li-stat {
  font-size: .68rem; font-weight: 600; color: #0d9488;
  background: #f0fdfa; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #ccfbf1;
}

/* Divider */
.li-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #0d9488;
}
.li-div::before,.li-div::after { content:''; flex:1; height:1px; background:#ccfbf1; }

/* Gradient generate button */
.li-gen-btn {
  width: 100%; padding: .85rem; border-radius: 1rem; border: none;
  background: linear-gradient(135deg, #134e4a, #0f766e, #0d9488);
  color: #fff; font-size: .95rem; font-weight: 800; letter-spacing: .03em;
  cursor: pointer; transition: all .2s; box-shadow: 0 4px 18px rgba(13,148,136,.3);
}
.li-gen-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(13,148,136,.4); }
.li-gen-btn:active { transform: translateY(0); }

@keyframes liFadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.li-fadein { animation: liFadeIn .25s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8"
     x-data="liGen()"
     x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg,#134e4a,#0d9488);">
        <span class="text-3xl">📄</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Lorem Ipsum Generator</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto text-sm">
        Generate placeholder text by paragraphs, sentences, or words. Choose your format and copy instantly.
      </p>
    </div>

    {{-- Error --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 flex items-start gap-2 text-sm text-red-700">
      <span class="flex-shrink-0 text-base">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ══════════ LEFT — Controls ══════════ --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Output type --}}
        <div class="card p-5 space-y-5">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2 text-sm">
            <span>⚙️</span> Generator Settings
          </h2>

          {{-- Type selector --}}
          <div>
            <label class="form-label">Output Type</label>
            <div class="flex gap-2">
              <button @click="outputType='paragraphs'; adjustCount()"
                      :class="['li-pill', outputType==='paragraphs' ? 'li-on' : '']">
                ¶ Paragraphs
              </button>
              <button @click="outputType='sentences'; adjustCount()"
                      :class="['li-pill', outputType==='sentences' ? 'li-on' : '']">
                ≡ Sentences
              </button>
              <button @click="outputType='words'; adjustCount()"
                      :class="['li-pill', outputType==='words' ? 'li-on' : '']">
                W Words
              </button>
            </div>
          </div>

          {{-- Count --}}
          <div>
            <label class="form-label">
              How many
              <span x-text="outputType" class="capitalize"></span>?
            </label>
            <div class="li-stepper">
              <button @click="count = Math.max(minCount, count - 1)"
                      :disabled="count <= minCount"
                      class="li-step-btn">−</button>
              <input
                type="number"
                x-model.number="count"
                :min="minCount"
                :max="maxCount"
                class="li-step-input"
                @input="count = Math.min(maxCount, Math.max(minCount, count || 1))"
              />
              <button @click="count = Math.min(maxCount, count + 1)"
                      :disabled="count >= maxCount"
                      class="li-step-btn">+</button>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">
              Range: <span x-text="minCount"></span> – <span x-text="maxCount.toLocaleString()"></span>
            </p>
          </div>

          {{-- Quick presets --}}
          <div>
            <label class="form-label text-xs">Quick Select</label>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="n in quickPresets" :key="n">
                <button @click="count = n" class="li-quick"
                        :class="count === n ? 'bg-teal-600 text-white border-teal-600' : ''">
                  <span x-text="n"></span>
                </button>
              </template>
            </div>
          </div>

          <div class="li-div"></div>

          {{-- Options --}}
          <div class="space-y-3">
            <label class="form-label text-xs">Options</label>

            {{-- Start with Lorem ipsum --}}
            <div class="li-toggle-wrap" @click="startWithLorem = !startWithLorem">
              <div :class="['li-toggle', startWithLorem ? 'li-on' : '']"></div>
              <span class="li-toggle-label">Start with "Lorem ipsum dolor sit amet…"</span>
            </div>

            {{-- Format --}}
            <div>
              <p class="text-xs text-gray-500 font-medium mb-1.5">Output format</p>
              <div class="flex flex-wrap gap-1.5">
                <button @click="format='plain'"
                        :class="['li-fmt', format==='plain' ? 'li-on' : '']">Plain Text</button>
                <button @click="format='html'"
                        :class="['li-fmt', format==='html' ? 'li-on' : '']">&lt;p&gt; HTML</button>
                <button @click="format='markdown'"
                        :class="['li-fmt', format==='markdown' ? 'li-on' : '']">Markdown</button>
              </div>
            </div>
          </div>

          {{-- Generate --}}
          <button @click="generate()" class="li-gen-btn">
            ✨ Generate Lorem Ipsum
          </button>
        </div>

        {{-- Info card --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1.5"
             style="background:linear-gradient(135deg,#f0fdfa,#ccfbf1); border-color:#99f6e4;">
          <p class="font-bold text-teal-800 uppercase tracking-wide text-xs">💡 About Lorem Ipsum</p>
          <p>Lorem Ipsum has been the industry's standard placeholder text since the 1500s.</p>
          <p>It is derived from <em>"de Finibus Bonorum et Malorum"</em> by Cicero, written in 45 BC.</p>
          <p class="pt-1 border-t border-teal-200 text-teal-700 font-medium">
            Plain text uses line breaks between paragraphs. HTML wraps each in
            <code class="bg-teal-100 px-1 rounded">&lt;p&gt;</code> tags.
          </p>
        </div>

      </div>

      {{-- ══════════ RIGHT — Output ══════════ --}}
      <div class="lg:col-span-3 space-y-4">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">📄</div>
          <p class="font-medium text-gray-500">No text generated yet</p>
          <p class="text-sm mt-1">Configure your settings and click <strong>Generate Lorem Ipsum</strong></p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Generating…</p>
        </div>

        {{-- Done --}}
        <template x-if="phase === 'done'">
          <div class="space-y-4 li-fadein">

            {{-- Output card --}}
            <div class="card overflow-hidden">

              {{-- Header bar --}}
              <div class="px-4 py-3 bg-gradient-to-r from-teal-50 to-emerald-50 border-b border-teal-100
                          flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="text-xs font-bold text-teal-700 uppercase tracking-wide">Generated Text</span>
                  <span class="li-stat" x-text="outputWordCount.toLocaleString() + ' words'"></span>
                  <span class="li-stat" x-text="outputCharCount.toLocaleString() + ' characters'"></span>
                  <span class="li-stat" x-text="formatLabel"></span>
                </div>
                <div class="flex items-center gap-2">
                  {{-- Preview toggle (HTML only) --}}
                  <button x-show="format === 'html'"
                          @click="showPreview = !showPreview"
                          :class="['li-action li-action-preview', showPreview ? 'li-on' : '']"
                          style="font-size:.65rem; padding:.3rem .65rem;">
                    <span x-text="showPreview ? '&lt;/&gt; Code' : '👁 Preview'"></span>
                  </button>
                </div>
              </div>

              {{-- Code view --}}
              <div x-show="!showPreview">
                <textarea
                  :value="output"
                  readonly
                  class="li-output"
                  spellcheck="false"
                ></textarea>
              </div>

              {{-- HTML preview --}}
              <div x-show="showPreview && format === 'html'"
                   class="li-preview" x-html="output"></div>

            </div>

            {{-- Action row --}}
            <div class="flex flex-wrap items-center gap-2.5">
              <button
                @click="copyOutput()"
                :class="['li-action li-action-copy', copied ? 'li-done' : '']"
              >
                <span x-text="copied ? '✓ Copied!' : '⎘ Copy Text'"></span>
              </button>

              <button @click="generate()" class="li-action"
                      style="background:#f0fdfa;color:#0f766e;border-color:#99f6e4;">
                ↻ Regenerate
              </button>

              <button @click="reset()" class="li-action li-action-clear">
                🗑️ Clear
              </button>

              <span class="ml-auto text-xs text-gray-400 font-medium">
                Generated <span class="text-teal-600 font-bold"
                               x-text="generatedCount.toLocaleString() + ' ' + outputType"></span>
              </span>
            </div>

          </div>
        </template>

      </div>

    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ────────────────────────────────────────────────────────────
   Lorem Ipsum Generator — Alpine.js component
   CSS prefix: li-   |  Theme: Teal (#0d9488)

   Word bank: ~180 words from the classic Cicero source text
   plus the "de Finibus" extension (standard lorem ipsum corpus).

   Sentence length:  8–20 words (random)
   Paragraph size:   4–7 sentences (random)
   Commas:           ~33% chance per sentence at word 3-8
   Formats:          Plain (double-newline), HTML (<p>), Markdown (same as plain)
──────────────────────────────────────────────────────────── */
function liGen() {
  return {

    outputType:    'paragraphs',
    count:         3,
    startWithLorem: true,
    format:        'plain',
    showPreview:   false,
    phase:         'idle',
    output:        '',
    copied:        false,
    errorMsg:      '',
    generatedCount: 0,

    /* ── Count bounds per type ───────────────── */
    get minCount() { return 1; },
    get maxCount() {
      return this.outputType === 'paragraphs' ? 50
           : this.outputType === 'sentences'  ? 200
           :                                    1000;
    },
    get quickPresets() {
      return this.outputType === 'paragraphs' ? [1, 2, 3, 5, 10]
           : this.outputType === 'sentences'  ? [1, 5, 10, 20, 50]
           :                                    [10, 25, 50, 100, 200];
    },

    /* ── Computed output stats ───────────────── */
    get outputWordCount() {
      if (!this.output.trim()) return 0;
      return this.output.trim().replace(/<[^>]*>/g,'').split(/\s+/).length;
    },
    get outputCharCount() {
      return this.output.replace(/<[^>]*>/g,'').length;
    },
    get formatLabel() {
      return this.format === 'plain'    ? 'Plain Text'
           : this.format === 'html'     ? 'HTML'
           :                              'Markdown';
    },

    init() {},

    adjustCount() {
      // Keep count within new bounds when switching type
      this.count = Math.min(this.maxCount, Math.max(this.minCount, this.count));
      // Suggest sensible defaults when switching
      if (this.outputType === 'paragraphs' && this.count > 10)  this.count = 3;
      if (this.outputType === 'sentences'  && this.count > 50)  this.count = 10;
    },

    /* ── Actions ─────────────────────────────── */
    generate() {
      this.errorMsg = '';

      var n = parseInt(this.count, 10);
      if (isNaN(n) || n < this.minCount) {
        this.errorMsg = 'Please enter a number of at least ' + this.minCount + '.';
        return;
      }
      if (n > this.maxCount) {
        this.errorMsg = 'Maximum is ' + this.maxCount.toLocaleString() + ' ' + this.outputType + '.';
        return;
      }

      this.count = n;
      this.phase = 'loading';
      this.showPreview = false;
      this.copied = false;

      var self = this;
      setTimeout(function () {
        var raw;
        try {
          if (self.outputType === 'paragraphs') raw = self._genParagraphs(n);
          else if (self.outputType === 'sentences') raw = self._genSentences(n);
          else raw = self._genWords(n);

          self.output = raw;
          self.generatedCount = n;
          self.phase = 'done';
        } catch (e) {
          self.errorMsg = 'An unexpected error occurred. Please try again.';
          self.phase = 'idle';
        }
      }, 60);
    },

    reset() {
      this.output = '';
      this.phase  = 'idle';
      this.copied = false;
      this.errorMsg = '';
      this.generatedCount = 0;
      this.showPreview = false;
    },

    copyOutput() {
      var text = this.output.replace(/<[^>]*>/g, '').trim();
      if (!text) return;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.output)
          .then(function () { self._flash(); })
          .catch(function () { self._legacyCopy(self.output); });
      } else {
        this._legacyCopy(this.output);
      }
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

    /* ── Generators ──────────────────────────── */

    _genParagraphs(n) {
      var paras = [];
      for (var i = 0; i < n; i++) {
        paras.push(this._makeParagraph(i === 0 && this.startWithLorem));
      }
      if (this.format === 'html') {
        return paras.map(function (p) { return '<p>' + p + '</p>'; }).join('\n');
      }
      return paras.join('\n\n');
    },

    _genSentences(n) {
      var sents = [];
      for (var i = 0; i < n; i++) {
        if (i === 0 && this.startWithLorem) {
          sents.push(this._loremOpener());
        } else {
          sents.push(this._makeSentence());
        }
      }
      var text = sents.join(' ');
      if (this.format === 'html') return '<p>' + text + '</p>';
      return text;
    },

    _genWords(n) {
      var words = [];
      if (this.startWithLorem) {
        var opener = 'Lorem ipsum dolor sit amet consectetur adipiscing elit';
        var openerWords = opener.split(' ');
        var take = Math.min(n, openerWords.length);
        for (var j = 0; j < take; j++) words.push(openerWords[j]);
        n -= take;
      }
      for (var i = 0; i < n; i++) {
        words.push(this._pick());
      }
      var text = words.join(' ');
      // Capitalise first word
      text = text.charAt(0).toUpperCase() + text.slice(1);
      if (this.format === 'html') return '<p>' + text + '</p>';
      return text;
    },

    /* ── Text-building helpers ───────────────── */

    _makeParagraph(isFirst) {
      var numSentences = this._rand(4, 7);
      var sentences = [];
      for (var i = 0; i < numSentences; i++) {
        if (i === 0 && isFirst) {
          sentences.push(this._loremOpener());
        } else {
          sentences.push(this._makeSentence());
        }
      }
      return sentences.join(' ');
    },

    _loremOpener() {
      // Classic opening sentence — always used when "startWithLorem" is on
      return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    },

    _makeSentence(wordCount) {
      var len = wordCount || this._rand(8, 20);
      var words = [];
      for (var i = 0; i < len; i++) {
        words.push(this._pick());
      }
      // Add comma at ~33% chance (between word 3 and 8)
      if (this._rand(0, 2) === 0 && len > 8) {
        var commaPos = this._rand(2, Math.min(7, len - 3));
        words[commaPos] = words[commaPos] + ',';
      }
      var sentence = words.join(' ');
      return sentence.charAt(0).toUpperCase() + sentence.slice(1) + '.';
    },

    /* ── Pseudo-random utilities ─────────────── */
    _rand(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    _pick() {
      return this._WORDS[Math.floor(Math.random() * this._WORDS.length)];
    },

    /* ── Lorem Ipsum word bank (~180 words) ─── */
    _WORDS: [
      /* Original Cicero passage */
      'lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit',
      'sed','eiusmod','tempor','incididunt','labore','dolore','magna','aliqua',
      'enim','ad','minim','veniam','quis','nostrud','exercitation','ullamco',
      'laboris','nisi','aliquip','ex','ea','commodo','consequat','duis','aute',
      'irure','reprehenderit','voluptate','velit','esse','cillum','eu','fugiat',
      'nulla','pariatur','excepteur','sint','occaecat','cupidatat','non','proident',
      'culpa','qui','officia','deserunt','mollit','anim','est','laborum',
      /* Extended — de Finibus extension (standard lorem ipsum corpus) */
      'perspiciatis','unde','omnis','iste','natus','error','voluptatem',
      'accusantium','doloremque','laudantium','totam','rem','aperiam','eaque',
      'ipsa','quae','ab','illo','inventore','veritatis','quasi','architecto',
      'beatae','vitae','dicta','explicabo','nemo','ipsam','quia','voluptas',
      'aspernatur','odit','aut','consequuntur','magni','dolores','eos',
      'ratione','sequi','nesciunt','neque','porro','quisquam','numquam',
      'eius','modi','tempora','incidunt','aliquam','quaerat','maxime',
      'placeat','facere','possimus','assumenda','temporibus','autem',
      'quibusdam','officiis','debitis','rerum','necessitatibus','saepe',
      'eveniet','voluptates','repudiandae','itaque','earum','tenetur',
      'sapiente','delectus','reiciendis','maiores','alias','perferendis',
      'doloribus','asperiores','repellat','blanditiis','deleniti','atque',
      'corrupti','quos','quas','molestias','obcaecati','cupiditate',
      'provident','similique','eligendi','optio','cumque','nihil','impedit',
      'minima','nostrum','exercitationem','ullam','corporis','suscipit',
      'laboriosam','aliquid','praesentium','harum','accusamus','vitae',
      'voluptatum','recusandae','expedita','distinctio','nam','libero',
      'tempore','cum','soluta','nobis','eligendi','cumque','nihil',
      'molestiae','consequatur','vel','illum','qui','dolorem',
    ],
  };
}
</script>
@endpush
