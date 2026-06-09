<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   HTML Encoder / Decoder  —  prefix: he-
   Theme: Sky-Blue (#0284c7 / #0369a1 / #0ea5e9)
   Live reactive: outputText = getter on (inputText, mode, opts)
   Decoding uses browser-native textarea innerHTML trick.
══════════════════════════════════════════════ */

/* Textareas */
.he-textarea {
  width: 100%; min-height: 210px; resize: vertical;
  font-family: 'JetBrains Mono','Fira Code','Courier New', monospace;
  font-size: .82rem; line-height: 1.75; color: #1e293b;
  caret-color: #0284c7; border: none; outline: none;
  padding: 1rem 1.1rem; background: transparent;
}
.he-textarea::placeholder { color: #bae6fd; }
.he-textarea.he-output     { color: #0c4a6e; background: #f0f9ff; cursor: default; }
.he-textarea::-webkit-scrollbar       { width: 4px; }
.he-textarea::-webkit-scrollbar-thumb { background: #bae6fd; border-radius: 9999px; }

/* Mode toggle */
.he-mode-btn {
  flex: 1; padding: .65rem 1rem; border-radius: .875rem; font-size: .85rem;
  font-weight: 800; text-align: center; cursor: pointer; transition: all .18s;
  border: 2px solid #bae6fd; background: #fff; color: #0369a1; letter-spacing: .02em;
}
.he-mode-btn:hover  { border-color: #0284c7; background: #f0f9ff; }
.he-mode-btn.he-on  {
  background: linear-gradient(135deg, #0c4a6e, #0369a1, #0284c7);
  border-color: #0369a1; color: #fff;
  box-shadow: 0 4px 16px rgba(2,132,199,.3);
}

/* Option toggle */
.he-opt-row { display: flex; align-items: center; justify-content: space-between; gap: .5rem; cursor: pointer; }
.he-opt-info .he-opt-lbl { font-size: .8rem; font-weight: 600; color: #374151; }
.he-opt-info .he-opt-sub { font-size: .65rem; color: #9ca3af; }
.he-toggle {
  position: relative; width: 2.4rem; height: 1.3rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0;
}
.he-toggle.he-on { background: #0284c7; }
.he-toggle::after {
  content: ''; position: absolute; top: .175rem; left: .175rem;
  width: .95rem; height: .95rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.he-toggle.he-on::after { transform: translateX(1.1rem); }

/* Panel headers */
.he-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6;
}
.he-panel-hdr.he-out-hdr { background: #f0f9ff; border-color: #e0f2fe; }
.he-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.he-panel-lbl.he-in-lbl  { color: #94a3b8; }
.he-panel-lbl.he-out-lbl { color: #0284c7; }

/* Stat pill */
.he-stat {
  font-size: .68rem; font-weight: 600; color: #0369a1;
  background: #e0f2fe; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #bae6fd;
}

/* Entity stats row */
.he-ent-row {
  display: flex; align-items: center; gap: .4rem;
  padding: .35rem .7rem; border-radius: .5rem; font-size: .72rem;
}
.he-ent-row:hover { background: #f0f9ff; }
.he-ent-before { font-family: monospace; font-weight: 700; color: #334155; }
.he-ent-arrow  { color: #94a3b8; font-size: .7rem; }
.he-ent-after  { font-family: monospace; font-weight: 700; color: #0369a1; }
.he-ent-count  {
  margin-left: auto; font-weight: 800; color: #fff;
  background: #0284c7; padding: .1rem .45rem; border-radius: 9999px; font-size: .65rem;
}

/* HTML preview pane */
.he-preview {
  min-height: 100px; padding: 1rem 1.1rem;
  font-size: .9rem; line-height: 1.7; color: #1e293b;
  overflow-y: auto;
}

/* Output empty */
.he-out-empty {
  min-height: 210px; display: flex; align-items: center; justify-content: center;
  color: #7dd3fc; font-size: .88rem; font-style: italic;
  background: #f0f9ff; border-top: 1px solid #e0f2fe;
}

/* Action buttons */
.he-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.he-action:disabled { opacity: .35; cursor: not-allowed; }
.he-action-copy  { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.he-action-copy:hover:not(:disabled)  { background: #0369a1; color: #fff; border-color: #0369a1; }
.he-action-copy.he-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.he-action-swap  { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }
.he-action-swap:hover:not(:disabled)  { background: #7e22ce; color: #fff; border-color: #7e22ce; }
.he-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.he-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Reference table */
.he-ref-table { width: 100%; font-size: .75rem; border-collapse: collapse; }
.he-ref-table th {
  text-align: left; font-size: .58rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .08em; color: #94a3b8; padding: .4rem .6rem;
  border-bottom: 1px solid #e0f2fe; background: #f0f9ff;
}
.he-ref-table td {
  padding: .4rem .6rem; border-bottom: 1px solid #f3f4f6;
  font-family: monospace; color: #1e293b;
}
.he-ref-table tr:hover td { background: #f0f9ff; }
.he-ref-table td:first-child { font-weight: 700; color: #0369a1; }

/* Divider */
.he-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #0284c7;
}
.he-div::before,.he-div::after { content:''; flex:1; height:1px; background:#e0f2fe; }

/* Preview toggle button */
.he-prev-btn {
  padding: .25rem .65rem; border-radius: .5rem; font-size: .65rem;
  font-weight: 700; text-transform: uppercase; letter-spacing: .06em; cursor: pointer;
  transition: all .15s; border: 1px solid #bae6fd;
  background: #fff; color: #0369a1;
}
.he-prev-btn:hover { background: #0369a1; color: #fff; border-color: #0369a1; }
.he-prev-btn.he-on { background: #0369a1; color: #fff; border-color: #0369a1; }

@keyframes heIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
.he-in { animation: heIn .22s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="heCalc()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🌐 HTML Encoder / Decoder
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Convert special characters to HTML entities and back — output updates as you type.
          </p>
        </div>
        <button @click="loadSample()" class="he-action he-action-copy self-start sm:self-auto">
          📄 Sample
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    
    <div class="flex gap-3">
      <button @click="mode = 'encode'"
              :class="['he-mode-btn', mode === 'encode' ? 'he-on' : '']">
        ⇨ Encode HTML
      </button>
      <button @click="mode = 'decode'"
              :class="['he-mode-btn', mode === 'decode' ? 'he-on' : '']">
        ⇦ Decode HTML
      </button>
    </div>

    
    <div x-show="mode === 'encode'" x-transition
         class="card p-4">
      <p class="text-xs font-bold text-sky-700 uppercase tracking-wide mb-3">Encoding Options</p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class="he-opt-row" @click="encQuotes = !encQuotes">
          <div class="he-opt-info">
            <div class="he-opt-lbl">Encode Quotes</div>
            <div class="he-opt-sub"><code class="text-sky-600">'</code> → <code class="text-sky-600">&#39;</code></div>
          </div>
          <div :class="['he-toggle', encQuotes ? 'he-on' : '']"></div>
        </div>

        <div class="he-opt-row" @click="encSlashes = !encSlashes">
          <div class="he-opt-info">
            <div class="he-opt-lbl">Encode Slashes</div>
            <div class="he-opt-sub"><code class="text-sky-600">/</code> → <code class="text-sky-600">&#47;</code></div>
          </div>
          <div :class="['he-toggle', encSlashes ? 'he-on' : '']"></div>
        </div>

        <div class="he-opt-row" @click="encSpaces = !encSpaces">
          <div class="he-opt-info">
            <div class="he-opt-lbl">Encode Spaces</div>
            <div class="he-opt-sub"><code class="text-sky-600"> </code> → <code class="text-sky-600">&amp;nbsp;</code></div>
          </div>
          <div :class="['he-toggle', encSpaces ? 'he-on' : '']"></div>
        </div>

      </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      
      <div class="card overflow-hidden">
        <div class="he-panel-hdr">
          <span class="he-panel-lbl he-in-lbl"
                x-text="mode === 'encode' ? 'Plain Text / HTML to Encode' : 'Encoded HTML to Decode'">
          </span>
          <span x-show="inputText.trim()" class="he-stat"
                x-text="inputText.length + ' chars'"></span>
        </div>
        <textarea
          x-model="inputText"
          :placeholder="mode === 'encode'
            ? 'Paste HTML or text here…\n\ne.g. <div class=\"hello\">It\'s a test & example</div>'
            : 'Paste encoded HTML entities here…\n\ne.g. &lt;div class=&quot;hello&quot;&gt;It\'s a test &amp; example&lt;/div&gt;'"
          class="he-textarea"
          spellcheck="false"
        ></textarea>
      </div>

      
      <div class="card overflow-hidden">
        <div class="he-panel-hdr he-out-hdr">
          <span class="he-panel-lbl he-out-lbl"
                x-text="mode === 'encode' ? 'Encoded Output' : 'Decoded Output'">
          </span>
          <div class="flex items-center gap-2">
            <span x-show="outputText.trim()" class="he-stat"
                  x-text="outputText.length + ' chars'"></span>
            <button x-show="mode === 'decode' && outputText.trim()"
                    @click="showPreview = !showPreview"
                    :class="['he-prev-btn', showPreview ? 'he-on' : '']">
              <span x-text="showPreview ? '&lt;/&gt; Text' : '👁 Preview'"></span>
            </button>
          </div>
        </div>

        
        <div x-show="!outputText" class="he-out-empty">
          <span x-text="!inputText.trim()
            ? 'Paste text on the left…'
            : (mode === 'encode' ? 'Nothing to encode' : 'Nothing to decode')">
          </span>
        </div>

        
        <textarea
          x-show="outputText && !showPreview"
          :value="outputText"
          readonly
          class="he-textarea he-output"
          spellcheck="false"
        ></textarea>

        
        <div x-show="outputText && showPreview && mode === 'decode'"
             class="he-preview" x-html="outputText"></div>
      </div>

    </div>

    
    <div class="flex flex-wrap items-center gap-2.5">

      <button @click="copyOutput()" :disabled="!outputText.trim()"
              :class="['he-action he-action-copy', copied ? 'he-done' : '']">
        <span x-text="copied ? '✓ Copied!' : '⎘ Copy Result'"></span>
      </button>

      <button @click="swapToInput()" :disabled="!outputText.trim()"
              class="he-action he-action-swap"
              title="Put the result back as input (to re-encode or re-decode)">
        ⇅ Use as Input
      </button>

      <button @click="clear()" :disabled="!inputText" class="he-action he-action-clear">
        🗑️ Clear
      </button>

      
      <div x-show="outputText.trim()" x-transition
           class="ml-auto flex items-center gap-1.5 text-xs font-semibold text-sky-700
                  bg-sky-50 border border-sky-200 px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 flex-shrink-0"></span>
        <span x-text="mode === 'encode' ? 'Encoded' : 'Decoded'"></span>
      </div>

    </div>

    
    <div x-show="outputText.trim() && entityStats.length > 0" x-transition
         class="card p-5 he-in">
      <p class="he-div mb-3">
        <span x-text="mode === 'encode' ? 'Entities Encoded' : 'Entities Decoded'"></span>
      </p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
        <template x-for="row in entityStats" :key="row.before">
          <div class="he-ent-row">
            <code class="he-ent-before" x-text="row.before"></code>
            <span class="he-ent-arrow">→</span>
            <code class="he-ent-after" x-text="row.after"></code>
            <span class="he-ent-count" x-text="row.count + '×'"></span>
          </div>
        </template>
      </div>
      <p class="text-xs text-gray-400 mt-3 text-center">
        Total substitutions:
        <strong class="text-sky-600"
                x-text="entityStats.reduce(function(a,r){ return a+r.count; }, 0)"></strong>
      </p>
    </div>

    
    <div class="card p-5">
      <p class="he-div mb-3">HTML Entity Quick Reference</p>
      <div class="overflow-x-auto">
        <table class="he-ref-table">
          <thead>
            <tr>
              <th>Entity</th>
              <th>Character</th>
              <th>Description</th>
              <th>Named</th>
              <th>Numeric</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>&amp;amp;</td><td>&amp;</td><td>Ampersand</td><td>&amp;amp;</td><td>&amp;#38;</td></tr>
            <tr><td>&amp;lt;</td><td>&lt;</td><td>Less-than sign</td><td>&amp;lt;</td><td>&amp;#60;</td></tr>
            <tr><td>&amp;gt;</td><td>&gt;</td><td>Greater-than sign</td><td>&amp;gt;</td><td>&amp;#62;</td></tr>
            <tr><td>&amp;quot;</td><td>"</td><td>Double quotation mark</td><td>&amp;quot;</td><td>&amp;#34;</td></tr>
            <tr><td>&amp;#39;</td><td>'</td><td>Single quotation mark (apostrophe)</td><td>&amp;apos; *</td><td>&amp;#39;</td></tr>
            <tr><td>&amp;nbsp;</td><td>&nbsp;</td><td>Non-breaking space</td><td>&amp;nbsp;</td><td>&amp;#160;</td></tr>
            <tr><td>&amp;#47;</td><td>/</td><td>Forward slash</td><td>—</td><td>&amp;#47;</td></tr>
            <tr><td>&amp;copy;</td><td>&copy;</td><td>Copyright symbol</td><td>&amp;copy;</td><td>&amp;#169;</td></tr>
            <tr><td>&amp;reg;</td><td>&reg;</td><td>Registered trademark</td><td>&amp;reg;</td><td>&amp;#174;</td></tr>
            <tr><td>&amp;trade;</td><td>&trade;</td><td>Trademark symbol</td><td>&amp;trade;</td><td>&amp;#8482;</td></tr>
            <tr><td>&amp;mdash;</td><td>&mdash;</td><td>Em dash</td><td>&amp;mdash;</td><td>&amp;#8212;</td></tr>
            <tr><td>&amp;ndash;</td><td>&ndash;</td><td>En dash</td><td>&amp;ndash;</td><td>&amp;#8211;</td></tr>
            <tr><td>&amp;euro;</td><td>&euro;</td><td>Euro sign</td><td>&amp;euro;</td><td>&amp;#8364;</td></tr>
            <tr><td>&amp;pound;</td><td>&pound;</td><td>Pound sign</td><td>&amp;pound;</td><td>&amp;#163;</td></tr>
          </tbody>
        </table>
      </div>
      <p class="text-xs text-gray-400 mt-2.5">* <code>&amp;apos;</code> is valid in HTML5 and XML; use <code>&amp;#39;</code> for broadest compatibility.</p>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ────────────────────────────────────────────────────────────
   HTML Encoder / Decoder — Alpine.js component
   CSS prefix: he-   |  Theme: Sky-Blue (#0284c7)

   Encoding — manual replacement (order matters):
     1. & → &amp;  (MUST be first to avoid double-encoding)
     2. < → &lt;
     3. > → &gt;
     4. " → &quot;
     5. ' → &#39;   (if encQuotes option on)
     6. / → &#47;   (if encSlashes option on)
     7. ' ' → &nbsp; (if encSpaces option on)

   Decoding — browser-native textarea innerHTML trick:
     Handles ALL entity types: named (&amp; &lt; &copy; …),
     decimal (&#65;), hex (&#x41;), and all HTML5 named entities.

   entityStats — computed once per outputText change via getter.
──────────────────────────────────────────────────────────── */
function heCalc() {
  return {

    inputText:   '',
    mode:        'encode',   // 'encode' | 'decode'
    showPreview: false,
    copied:      false,

    /* Encoding options */
    encQuotes:  false,
    encSlashes: false,
    encSpaces:  false,

    init() {},

    /* ── Reactive getters ──────────────────────────────────── */

    get outputText() {
      if (!this.inputText) return '';
      return this.mode === 'encode'
        ? this._encode(this.inputText)
        : this._decode(this.inputText);
    },

    get entityStats() {
      if (!this.inputText || !this.outputText) return [];
      var stats = [];

      if (this.mode === 'encode') {
        /* Count how many of each entity was injected */
        var pairs = [
          { before: '&',  after: '&amp;'  },
          { before: '<',  after: '&lt;'   },
          { before: '>',  after: '&gt;'   },
          { before: '"',  after: '&quot;' },
        ];
        if (this.encQuotes)  pairs.push({ before: "'",  after: "&#39;"  });
        if (this.encSlashes) pairs.push({ before: '/',  after: '&#47;'  });
        if (this.encSpaces)  pairs.push({ before: ' ', after: '&nbsp;' });

        var src = this.inputText;
        pairs.forEach(function (p) {
          var count = (src.split(p.before).length - 1);
          if (count > 0) stats.push({ before: p.before, after: p.after, count: count });
        });
      } else {
        /* Decode mode: count &xxx; patterns in the INPUT */
        var re = /&[a-zA-Z][a-zA-Z0-9]*;|&#[0-9]+;|&#x[0-9a-fA-F]+;/g;
        var matches = this.inputText.match(re) || [];
        var freq = {};
        matches.forEach(function (m) { freq[m] = (freq[m] || 0) + 1; });
        Object.entries(freq)
          .sort(function (a, b) { return b[1] - a[1]; })
          .slice(0, 12)
          .forEach(function (kv) {
            /* Decode this single entity for the "after" display */
            var ta = document.createElement('textarea');
            ta.innerHTML = kv[0];
            stats.push({ before: kv[0], after: ta.value, count: kv[1] });
          });
      }
      return stats;
    },

    /* ── Actions ───────────────────────────────────────────── */

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

    swapToInput() {
      if (!this.outputText.trim()) return;
      this.inputText   = this.outputText;
      this.showPreview = false;
      this.copied      = false;
      /* Flip mode so the swapped text makes sense to process again */
      this.mode = (this.mode === 'encode') ? 'decode' : 'encode';
    },

    clear() {
      this.inputText   = '';
      this.showPreview = false;
      this.copied      = false;
    },

    loadSample() {
      if (this.mode === 'encode') {
        this.inputText = '<div class="container">\n  <h1>Hello & Welcome!</h1>\n  <p>It\'s a "great" day > yesterday.</p>\n  <a href="/path/to/page">Visit us</a>\n</div>';
      } else {
        this.inputText = '&lt;div class=&quot;container&quot;&gt;\n  &lt;h1&gt;Hello &amp; Welcome!&lt;/h1&gt;\n  &lt;p&gt;It&#39;s a &quot;great&quot; day &gt; yesterday.&lt;/p&gt;\n&lt;/div&gt;';
      }
      this.showPreview = false;
      this.copied      = false;
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

    /* ── Encoding algorithm ────────────────────────────────── */
    _encode(text) {
      /* Step 1: & MUST come first to avoid re-encoding other entities */
      var out = text.replace(/&/g, '&amp;');

      /* Step 2: core HTML special chars */
      out = out
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;');

      /* Step 3: optional extras */
      if (this.encQuotes)  out = out.replace(/'/g,  '&#39;');
      if (this.encSlashes) out = out.replace(/\//g, '&#47;');
      if (this.encSpaces)  out = out.replace(/ /g,  '&nbsp;');

      return out;
    },

    /* ── Decoding algorithm ────────────────────────────────── */
    _decode(html) {
      /* Browser-native approach: setting innerHTML on a textarea
         makes the browser parse ALL entity types:
           - Named:   &amp; &lt; &gt; &quot; &copy; &euro; …
           - Decimal: &#65; &#8364; …
           - Hex:     &#x41; &#x20AC; …
         The decoded value is read back from .value. */
      var ta = document.createElement('textarea');
      ta.innerHTML = html;
      return ta.value;
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\html-encoder-decoder.blade.php ENDPATH**/ ?>