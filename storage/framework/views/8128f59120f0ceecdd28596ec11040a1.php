<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Word Counter  —  prefix: wcnt-
   Theme: Indigo (#4f46e5 / #6366f1 / #818cf8)
   Pure client-side, fully reactive via Alpine.js
══════════════════════════════════════════════ */

/* Textarea */
.wcnt-textarea {
  width: 100%; min-height: 240px; resize: vertical;
  font-family: 'Inter', system-ui, sans-serif;
  font-size: .95rem; line-height: 1.8; color: #1e293b;
  caret-color: #4f46e5; border: none; outline: none;
  padding: 1.1rem 1.25rem; background: transparent;
}
.wcnt-textarea::placeholder { color: #c7d2fe; }
.wcnt-textarea::-webkit-scrollbar { width: 4px; }
.wcnt-textarea::-webkit-scrollbar-thumb { background: #e0e7ff; border-radius: 9999px; }

/* Toolbar action button */
.wcnt-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .4rem .85rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.wcnt-action:disabled { opacity: .4; cursor: not-allowed; }
.wcnt-action-sample { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
.wcnt-action-sample:hover:not(:disabled) { background:#15803d; color:#fff; border-color:#15803d; }
.wcnt-action-clear  { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
.wcnt-action-clear:hover:not(:disabled) { background:#dc2626; color:#fff; border-color:#dc2626; }
.wcnt-action-copy   { background:#eef2ff; color:#4f46e5; border-color:#c7d2fe; }
.wcnt-action-copy:hover:not(:disabled) { background:#4f46e5; color:#fff; border-color:#4f46e5; }
.wcnt-action-copy.wcnt-copied { background:#dcfce7; color:#15803d; border-color:#86efac; }

/* Stat card */
.wcnt-stat {
  background: #fff; border: 1.5px solid #e0e7ff; border-radius: 1.25rem;
  padding: 1.2rem 1rem; text-align: center;
  transition: transform .15s, box-shadow .15s;
}
.wcnt-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,.12); }
.wcnt-stat-num   { font-size: clamp(1.5rem,3.5vw,2.1rem); font-weight: 900; color: #312e81; line-height: 1; }
.wcnt-stat-lbl   { font-size: .62rem; font-weight: 800; color: #6366f1; text-transform: uppercase; letter-spacing: .1em; margin-top: .4rem; }
.wcnt-stat-sub   { font-size: .68rem; color: #94a3b8; margin-top: .15rem; }

/* Featured (first) stat */
.wcnt-stat-hero {
  background: linear-gradient(135deg, #312e81, #4f46e5, #6366f1);
  border-color: #4f46e5;
}
.wcnt-stat-hero .wcnt-stat-num { color: #fff; }
.wcnt-stat-hero .wcnt-stat-lbl { color: #a5b4fc; }
.wcnt-stat-hero .wcnt-stat-sub { color: #c7d2fe; }

/* Top-word frequency bar */
.wcnt-bar-bg  { background: #e0e7ff; border-radius: 9999px; height: 5px; overflow: hidden; }
.wcnt-bar-fg  { background: linear-gradient(90deg,#4f46e5,#818cf8); border-radius: 9999px; height: 5px; transition: width .4s ease; }

/* Character-type breakdown bar */
.wcnt-cbar-bg { background: #f1f5f9; border-radius: 9999px; height: 8px; overflow: hidden; }
.wcnt-cbar-fg { border-radius: 9999px; height: 8px; transition: width .4s ease; }

/* Live badge */
.wcnt-live {
  font-size: .6rem; font-weight: 800; color: #6366f1; background: #eef2ff;
  padding: .15rem .55rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: .06em;
}

/* Section divider */
.wcnt-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6366f1;
}
.wcnt-div::before,.wcnt-div::after { content:''; flex:1; height:1px; background:#e0e7ff; }

@keyframes wcntFadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.wcnt-fadein { animation: wcntFadeIn .25s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="wcntCalc()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔢 Word Counter
          </h1>
          <p class="text-gray-500 mt-1 text-sm">Real-time word, character &amp; readability statistics — no page reload.</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <button @click="loadSample()" class="wcnt-action wcnt-action-sample">📄 Sample</button>
          <button @click="clearText()" :disabled="!text" class="wcnt-action wcnt-action-clear">🗑️ Clear</button>
          <button @click="copyText()" :disabled="!text" :class="['wcnt-action wcnt-action-copy', copied ? 'wcnt-copied' : '']">
            <span x-text="copied ? '✓ Copied!' : '⎘ Copy Text'"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    
    <div class="card">
      
      <div class="px-4 pt-3 pb-2 flex items-center justify-between border-b border-gray-50">
        <span class="text-xs text-gray-400">Paste or type your text — results update as you type</span>
        <div class="flex items-center gap-3 text-xs">
          <span x-show="text.trim()">
            <strong class="text-indigo-600" x-text="words.toLocaleString()"></strong>
            <span class="text-gray-400"> words</span>
          </span>
          <span x-show="text.trim()">
            <strong class="text-indigo-600" x-text="chars.toLocaleString()"></strong>
            <span class="text-gray-400"> chars</span>
          </span>
          <span x-show="text.trim()" class="wcnt-live">● live</span>
        </div>
      </div>

      <textarea
        x-model="text"
        placeholder="Start typing or paste your text here…"
        class="wcnt-textarea"
        spellcheck="true"
      ></textarea>
    </div>

    
    <div x-show="!text.trim()"
         x-transition:enter="wcnt-fadein"
         class="card p-14 text-center">
      <div class="text-5xl mb-4">📊</div>
      <p class="font-semibold text-gray-600">No text yet</p>
      <p class="text-sm text-gray-400 mt-1.5">Type or paste text above and statistics will appear here instantly.</p>
      <button @click="loadSample()" class="wcnt-action wcnt-action-sample mt-5 mx-auto">
        📄 Try sample text
      </button>
    </div>

    
    <div x-show="text.trim()"
         x-transition:enter="wcnt-fadein"
         class="space-y-5">

      
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        <div class="wcnt-stat wcnt-stat-hero">
          <div class="wcnt-stat-num" x-text="words.toLocaleString()"></div>
          <div class="wcnt-stat-lbl">Words</div>
          <div class="wcnt-stat-sub" x-text="uniqueWords.toLocaleString() + ' unique'"></div>
        </div>

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="chars.toLocaleString()"></div>
          <div class="wcnt-stat-lbl">Characters</div>
          <div class="wcnt-stat-sub">including spaces</div>
        </div>

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="charsNoSpace.toLocaleString()"></div>
          <div class="wcnt-stat-lbl">Characters</div>
          <div class="wcnt-stat-sub">without spaces</div>
        </div>

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="sentences.toLocaleString()"></div>
          <div class="wcnt-stat-lbl">Sentences</div>
          <div class="wcnt-stat-sub" x-text="paragraphs + ' paragraph' + (paragraphs !== 1 ? 's' : '')"></div>
        </div>

      </div>

      
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="readingTime"></div>
          <div class="wcnt-stat-lbl">Reading Time</div>
          <div class="wcnt-stat-sub">avg 225 wpm</div>
        </div>

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="speakingTime"></div>
          <div class="wcnt-stat-lbl">Speaking Time</div>
          <div class="wcnt-stat-sub">avg 130 wpm</div>
        </div>

        <div class="wcnt-stat">
          <div class="wcnt-stat-num" x-text="avgWordLen"></div>
          <div class="wcnt-stat-lbl">Avg Word Length</div>
          <div class="wcnt-stat-sub">characters per word</div>
        </div>

      </div>

      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        
        <div class="card p-5">
          <p class="wcnt-div mb-3">Top Words</p>
          <p class="text-xs text-gray-400 mb-3">Common stop words excluded (the, a, and, …)</p>

          <div x-show="topWords.length === 0" class="text-sm text-gray-400 italic text-center py-4">
            Not enough unique words to analyse.
          </div>

          <div class="space-y-2">
            <template x-for="([word, count], idx) in topWords" :key="word">
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-300 w-4 text-right flex-shrink-0"
                      x-text="(idx + 1) + '.'"></span>
                <span class="text-sm font-semibold text-gray-700 w-24 truncate flex-shrink-0"
                      x-text="word"></span>
                <div class="wcnt-bar-bg flex-1">
                  <div class="wcnt-bar-fg"
                       :style="'width:' + Math.round(count / topWords[0][1] * 100) + '%'"></div>
                </div>
                <span class="text-xs font-bold text-indigo-600 w-7 text-right flex-shrink-0"
                      x-text="count"></span>
              </div>
            </template>
          </div>
        </div>

        
        <div class="card p-5">
          <p class="wcnt-div mb-3">Character Breakdown</p>

          <div class="space-y-3">
            <template x-for="row in charBreakdown" :key="row.label">
              <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 font-medium w-24 flex-shrink-0"
                      x-text="row.label"></span>
                <div class="wcnt-cbar-bg flex-1">
                  <div class="wcnt-cbar-fg" :style="'width:' + row.pct + '%; background:' + row.color"></div>
                </div>
                <span class="text-xs font-bold text-gray-600 w-10 text-right flex-shrink-0"
                      x-text="row.val.toLocaleString()"></span>
                <span class="text-xs text-gray-400 w-8 text-right flex-shrink-0"
                      x-text="row.pct + '%'"></span>
              </div>
            </template>
          </div>

          
          <div class="mt-4 pt-3 border-t border-gray-50 flex flex-wrap gap-2">
            <template x-for="row in charBreakdown" :key="row.label + '_leg'">
              <span class="flex items-center gap-1 text-xs text-gray-500">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                      :style="'background:' + row.color"></span>
                <span x-text="row.label"></span>
              </span>
            </template>
          </div>
        </div>

      </div>

      
      <div class="card p-4">
        <p class="wcnt-div mb-3">More Details</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">

          <div class="py-2">
            <div class="text-lg font-bold text-gray-800" x-text="lines.toLocaleString()"></div>
            <div class="text-xs text-gray-400 mt-0.5">Lines</div>
          </div>

          <div class="py-2">
            <div class="text-lg font-bold text-gray-800" x-text="longestWord || '—'"></div>
            <div class="text-xs text-gray-400 mt-0.5">Longest Word</div>
          </div>

          <div class="py-2">
            <div class="text-lg font-bold text-gray-800" x-text="wordsPerSentence"></div>
            <div class="text-xs text-gray-400 mt-0.5">Words / Sentence</div>
          </div>

          <div class="py-2">
            <div class="text-lg font-bold text-gray-800" x-text="wordsPerParagraph"></div>
            <div class="text-xs text-gray-400 mt-0.5">Words / Paragraph</div>
          </div>

        </div>
      </div>

    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ────────────────────────────────────────────────────────────
   Word Counter — Alpine.js component
   CSS prefix: wcnt-  |  Theme: Indigo (#4f46e5)
   All stats are getter-based: purely reactive to `this.text`.
   Reading speed : 225 wpm (average adult silent reading)
   Speaking speed: 130 wpm (average conversational speech)
──────────────────────────────────────────────────────────── */
function wcntCalc() {
  return {

    text:   '',
    copied: false,

    init() { /* nothing to bootstrap */ },

    // ── Core getters — all reference this.text directly ───────

    get words() {
      if (!this.text.trim()) return 0;
      return this.text.trim().split(/\s+/).length;
    },

    get chars() {
      return this.text.length;
    },

    get charsNoSpace() {
      return this.text.replace(/\s/g, '').length;
    },

    get sentences() {
      if (!this.text.trim()) return 0;
      // Split on . ! ? followed by optional whitespace/quote/close-paren
      var parts = this.text
        .split(/[.!?]+(?:\s|$)/)
        .map(function(s) { return s.trim(); })
        .filter(function(s) { return s.length > 0; });
      return Math.max(1, parts.length);
    },

    get paragraphs() {
      if (!this.text.trim()) return 0;
      var parts = this.text
        .split(/\n\s*\n/)
        .map(function(p) { return p.trim(); })
        .filter(function(p) { return p.length > 0; });
      return Math.max(1, parts.length);
    },

    get lines() {
      if (!this.text) return 0;
      return this.text.split('\n').length;
    },

    get uniqueWords() {
      if (!this.text.trim()) return 0;
      var list = this.text.trim().split(/\s+/);
      var seen = new Set(list.map(function(w) {
        return w.toLowerCase().replace(/[^a-z0-9À-ɏ]/g, '');
      }));
      seen.delete('');
      return seen.size;
    },

    get avgWordLen() {
      if (!this.text.trim()) return '—';
      var list = this.text.trim().split(/\s+/);
      var total = list.reduce(function(acc, w) {
        return acc + w.replace(/[^a-zA-Z0-9À-ɏ]/g, '').length;
      }, 0);
      if (list.length === 0) return '—';
      return (total / list.length).toFixed(1);
    },

    get longestWord() {
      if (!this.text.trim()) return '';
      var list = this.text.trim().split(/\s+/);
      var best = '';
      list.forEach(function(w) {
        var clean = w.replace(/[^a-zA-Z0-9À-ɏ]/g, '');
        if (clean.length > best.length) best = clean;
      });
      return best;
    },

    get wordsPerSentence() {
      var s = this.sentences;
      if (s === 0) return '—';
      return (this.words / s).toFixed(1);
    },

    get wordsPerParagraph() {
      var p = this.paragraphs;
      if (p === 0) return '—';
      return (this.words / p).toFixed(1);
    },

    get readingTime()  { return this._fmtTime(this.words, 225); },
    get speakingTime() { return this._fmtTime(this.words, 130); },

    get topWords() {
      if (!this.text.trim()) return [];
      var list = this.text.trim().split(/\s+/);
      if (list.length < 3) return [];

      // Comprehensive English stop-word list
      var stop = new Set([
        'the','a','an','and','or','but','is','are','was','were','be','been','being',
        'to','of','in','on','at','for','with','that','this','it','he','she','they',
        'we','you','i','me','my','your','his','her','its','our','their','us',
        'not','no','so','if','as','by','up','out','do','did','does','has','have',
        'had','will','would','could','should','may','might','can','am',
        'about','from','which','who','when','what','how','all','any','each',
        'more','most','other','than','then','there','here','into','over',
        'just','like','also','after','before','very','only','some','such',
        'now','new','get','got','go','went','come','came','see','know','make',
        'take','say','said','tell','think','good','great','one','two','three',
        'even','still','back','own','same','well','much','many','these','those',
        'its','ve','re','ll','d','s','t','m','o','don','didn','won','isn',
        'aren','wasn','weren','hasn','haven','hadn','couldn','shouldn','wouldn',
      ]);

      var freq = {};
      list.forEach(function(w) {
        var clean = w.toLowerCase().replace(/[^a-z0-9À-ɏ]/g, '');
        if (clean.length > 2 && !stop.has(clean)) {
          freq[clean] = (freq[clean] || 0) + 1;
        }
      });
      return Object.entries(freq)
        .sort(function(a, b) { return b[1] - a[1]; })
        .slice(0, 8);
    },

    get charBreakdown() {
      var t = this.text;
      if (!t) return [];
      var total   = Math.max(1, t.length);
      var letters = (t.match(/[a-zA-ZÀ-ɏ]/g) || []).length;
      var digits  = (t.match(/[0-9]/g)                  || []).length;
      var spaces  = (t.match(/[ \t]/g)                  || []).length;
      var newlines= (t.match(/\n/g)                     || []).length;
      var punct   = (t.match(/[!-\/:-@[-`{-~]/g)       || []).length;
      var other   = Math.max(0, total - letters - digits - spaces - newlines - punct);

      var rows = [
        { label: 'Letters',     val: letters,  color: '#4f46e5', pct: 0 },
        { label: 'Spaces',      val: spaces,   color: '#6366f1', pct: 0 },
        { label: 'Digits',      val: digits,   color: '#818cf8', pct: 0 },
        { label: 'Punctuation', val: punct,    color: '#a5b4fc', pct: 0 },
        { label: 'Newlines',    val: newlines, color: '#c7d2fe', pct: 0 },
      ];
      if (other > 0) rows.push({ label: 'Other', val: other, color: '#e0e7ff', pct: 0 });

      rows.forEach(function(r) { r.pct = Math.round(r.val / total * 100); });
      return rows.filter(function(r) { return r.val > 0; });
    },

    // ── Helpers ───────────────────────────────────────────────

    _fmtTime(w, wpm) {
      if (w === 0) return '0s';
      var secs = Math.ceil(w / wpm * 60);
      if (secs < 60) return secs + 's';
      var m = Math.floor(secs / 60), s = secs % 60;
      if (m < 60) return m + 'm' + (s ? ' ' + s + 's' : '');
      var h = Math.floor(m / 60), rm = m % 60;
      return h + 'h' + (rm ? ' ' + rm + 'm' : '');
    },

    // ── Actions ───────────────────────────────────────────────

    clearText() {
      this.text   = '';
      this.copied = false;
    },

    copyText() {
      if (!this.text) return;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(this.text)
          .then(function()  { self._flashCopied(); })
          .catch(function() { self._legacyCopy();  });
      } else {
        this._legacyCopy();
      }
    },

    _legacyCopy() {
      var el = document.createElement('textarea');
      el.value = this.text;
      el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
      document.body.appendChild(el);
      el.focus(); el.select();
      try   { document.execCommand('copy'); this._flashCopied(); }
      catch (e) {}
      document.body.removeChild(el);
    },

    _flashCopied() {
      var self = this;
      this.copied = true;
      setTimeout(function() { self.copied = false; }, 2200);
    },

    loadSample() {
      this.text = "The quick brown fox jumps over the lazy dog. This classic sentence is often used as a typing sample because it contains every letter of the English alphabet.\n\nWord counters are indispensable tools for writers, students, bloggers, and content creators. Whether you are drafting an academic essay, composing a blog post, or crafting a social media caption, tracking your word count helps you stay within limits and measure progress.\n\nReading speed varies considerably between individuals and contexts. On average, adults read roughly 200 to 250 words per minute for non-fiction prose. Skilled readers may reach 300 words per minute or beyond. Speaking aloud, however, is naturally slower — a comfortable conversational pace is around 130 words per minute, while a formal presentation may settle closer to 100 to 120 words per minute to allow audiences time to absorb the content.";
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\word-counter.blade.php ENDPATH**/ ?>