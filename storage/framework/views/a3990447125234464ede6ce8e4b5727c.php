<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   URL Encoder / Decoder  —  prefix: ue-
   Theme: Fuchsia (#86198f / #a21caf / #c026d3)
   Live reactive output. No backend required.
   encodeURIComponent / encodeURI / decodeURIComponent
══════════════════════════════════════════════ */

/* Textareas */
.ue-textarea {
  width: 100%; min-height: 160px; resize: vertical;
  font-family: 'JetBrains Mono','Fira Code','Courier New', monospace;
  font-size: .82rem; line-height: 1.75; color: #1e293b;
  caret-color: #a21caf; border: none; outline: none;
  padding: 1rem 1.1rem; background: transparent;
}
.ue-textarea::placeholder { color: #f0abfc; }
.ue-textarea.ue-output     { color: #4a044e; background: #fdf4ff; cursor: default; }
.ue-textarea::-webkit-scrollbar       { width: 4px; }
.ue-textarea::-webkit-scrollbar-thumb { background: #f0abfc; border-radius: 9999px; }

/* Mode toggle */
.ue-mode-btn {
  flex: 1; padding: .65rem 1rem; border-radius: .875rem; font-size: .85rem;
  font-weight: 800; text-align: center; cursor: pointer; transition: all .18s;
  border: 2px solid #f0abfc; background: #fff; color: #86198f;
}
.ue-mode-btn:hover { border-color: #a21caf; background: #fdf4ff; }
.ue-mode-btn.ue-on {
  background: linear-gradient(135deg, #4a044e, #86198f, #a21caf);
  border-color: #86198f; color: #fff;
  box-shadow: 0 4px 16px rgba(162,28,175,.3);
}

/* Encode type pill */
.ue-type-pill {
  padding: .4rem .8rem; border-radius: .75rem; font-size: .74rem;
  font-weight: 700; cursor: pointer; transition: all .15s;
  border: 1.5px solid #f0abfc; background: #fff; color: #86198f; white-space: nowrap;
}
.ue-type-pill:hover { border-color: #a21caf; background: #fdf4ff; }
.ue-type-pill.ue-on {
  background: #a21caf; color: #fff; border-color: #a21caf;
  box-shadow: 0 2px 8px rgba(162,28,175,.25);
}

/* Option toggle */
.ue-opt-row { display: flex; align-items: center; justify-content: space-between; gap: .5rem; cursor: pointer; }
.ue-opt-lbl { font-size: .8rem; font-weight: 600; color: #374151; }
.ue-opt-sub { font-size: .65rem; color: #9ca3af; }
.ue-toggle {
  position: relative; width: 2.4rem; height: 1.3rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0;
}
.ue-toggle.ue-on { background: #a21caf; }
.ue-toggle::after {
  content: ''; position: absolute; top: .175rem; left: .175rem;
  width: .95rem; height: .95rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.ue-toggle.ue-on::after { transform: translateX(1.1rem); }

/* Panel headers */
.ue-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6;
}
.ue-panel-hdr.ue-out-hdr { background: #fdf4ff; border-color: #fae8ff; }
.ue-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.ue-panel-lbl.ue-in-lbl  { color: #94a3b8; }
.ue-panel-lbl.ue-out-lbl { color: #a21caf; }

/* Stat pill */
.ue-stat {
  font-size: .68rem; font-weight: 600; color: #86198f;
  background: #fae8ff; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #f0abfc;
}

/* Action buttons */
.ue-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.ue-action:disabled { opacity: .35; cursor: not-allowed; }
.ue-action-copy  { background: #fae8ff; color: #86198f; border-color: #f0abfc; }
.ue-action-copy:hover:not(:disabled)  { background: #86198f; color: #fff; border-color: #86198f; }
.ue-action-copy.ue-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.ue-action-swap  { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
.ue-action-swap:hover:not(:disabled)  { background: #0369a1; color: #fff; border-color: #0369a1; }
.ue-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.ue-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }

/* URL parts table */
.ue-parts-row {
  display: grid; grid-template-columns: 6rem 1fr;
  gap: .5rem; padding: .4rem .6rem; border-radius: .5rem; align-items: start;
}
.ue-parts-row:hover { background: #fdf4ff; }
.ue-parts-key {
  font-size: .68rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .06em; color: #a21caf;
}
.ue-parts-val {
  font-family: monospace; font-size: .78rem; color: #1e293b;
  word-break: break-all;
}
.ue-parts-val.ue-empty { color: #d8b4fe; font-style: italic; }

/* Percent stat badge */
.ue-pct-badge {
  display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .65rem;
  border-radius: 9999px; font-size: .72rem; font-weight: 700;
  background: #fae8ff; color: #86198f; border: 1.5px solid #f0abfc;
}

/* Output empty */
.ue-out-empty {
  min-height: 160px; display: flex; align-items: center; justify-content: center;
  color: #e879f9; font-size: .88rem; font-style: italic;
  background: #fdf4ff; border-top: 1px solid #fae8ff;
}

/* Divider */
.ue-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #a21caf;
}
.ue-div::before,.ue-div::after { content:''; flex:1; height:1px; background:#fae8ff; }

/* Error alert */
.ue-error {
  display: flex; align-items: flex-start; gap: .5rem; padding: .75rem 1rem;
  background: #fef2f2; border: 1px solid #fecaca; border-radius: .75rem;
  font-size: .82rem; color: #991b1b;
}

/* Ref table */
.ue-ref-table { width: 100%; font-size: .75rem; border-collapse: collapse; }
.ue-ref-table th {
  text-align: left; font-size: .58rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .08em; color: #94a3b8; padding: .4rem .6rem;
  border-bottom: 1px solid #fae8ff; background: #fdf4ff;
}
.ue-ref-table td {
  padding: .4rem .6rem; border-bottom: 1px solid #f3f4f6; color: #374151;
}
.ue-ref-table tr:hover td { background: #fdf4ff; }
.ue-ref-table code { font-family: monospace; color: #86198f; font-size: .78rem; font-weight: 600; }

@keyframes ueIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
.ue-in { animation: ueIn .22s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="ueCalc()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔗 URL Encoder / Decoder
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Encode/decode URLs and query parameters using <code class="bg-gray-100 px-1.5 py-0.5 rounded text-fuchsia-700">encodeURIComponent</code> or <code class="bg-gray-100 px-1.5 py-0.5 rounded text-fuchsia-700">encodeURI</code> — output updates as you type.
          </p>
        </div>
        <button @click="loadSample()" class="ue-action ue-action-copy self-start sm:self-auto">
          📄 Sample
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    
    <div class="flex gap-3">
      <button @click="mode = 'encode'; decErr = ''"
              :class="['ue-mode-btn', mode === 'encode' ? 'ue-on' : '']">
        ⇨ Encode URL
      </button>
      <button @click="mode = 'decode'; decErr = ''"
              :class="['ue-mode-btn', mode === 'decode' ? 'ue-on' : '']">
        ⇦ Decode URL
      </button>
    </div>

    
    <div x-show="mode === 'encode'" x-transition class="card p-4 space-y-3">
      <div class="flex flex-wrap gap-2 items-center">
        <span class="text-xs font-bold text-fuchsia-800 uppercase tracking-wide mr-1">Encode Type:</span>
        <button @click="encType = 'component'"
                :class="['ue-type-pill', encType === 'component' ? 'ue-on' : '']">
          encodeURIComponent
        </button>
        <button @click="encType = 'uri'"
                :class="['ue-type-pill', encType === 'uri' ? 'ue-on' : '']">
          encodeURI
        </button>
      </div>
      <p class="text-xs text-gray-500"
         x-text="encType === 'component'
           ? 'encodeURIComponent — encodes ALL special chars. Best for query param values, path segments, or any text fragment. Encodes: : / ? # [ ] @ ! $ & \' ( ) * + , ; ='
           : 'encodeURI — preserves URL-structure characters (:, /, ?, #, @, &, =, +, $, ,). Best for encoding a complete URL while keeping it structurally valid.'">
      </p>
      <div class="flex items-center justify-between pt-1 border-t border-fuchsia-50">
        <div>
          <div class="ue-opt-lbl">Encode spaces as <code class="text-fuchsia-700">+</code> instead of <code class="text-fuchsia-700">%20</code></div>
          <div class="ue-opt-sub">For HTML form (application/x-www-form-urlencoded) compatibility</div>
        </div>
        <div class="ue-opt-row ml-3" @click="plusSpace = !plusSpace">
          <div :class="['ue-toggle', plusSpace ? 'ue-on' : '']"></div>
        </div>
      </div>
    </div>

    
    <div x-show="mode === 'decode'" x-transition class="card p-4">
      <div class="flex items-center justify-between">
        <div>
          <div class="ue-opt-lbl">Process each line independently</div>
          <div class="ue-opt-sub">Decodes every line separately — useful for lists of encoded values</div>
        </div>
        <div class="ue-opt-row ml-3" @click="lineByLine = !lineByLine">
          <div :class="['ue-toggle', lineByLine ? 'ue-on' : '']"></div>
        </div>
      </div>
    </div>

    
    <div x-show="decErr" x-transition class="ue-error">
      <span class="text-base flex-shrink-0">⚠️</span>
      <div>
        <strong class="block mb-0.5">Decoding Error</strong>
        <span x-text="decErr"></span>
      </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      
      <div class="card overflow-hidden">
        <div class="ue-panel-hdr">
          <span class="ue-panel-lbl ue-in-lbl"
                x-text="mode === 'encode' ? 'Text / URL to Encode' : 'Encoded URL to Decode'">
          </span>
          <div class="flex items-center gap-2">
            <span x-show="inputText.trim()" class="ue-stat"
                  x-text="inputText.length + ' chars'"></span>
          </div>
        </div>
        <textarea
          x-model="inputText"
          :placeholder="mode === 'encode'
            ? 'Paste a URL or text to encode…\n\nhttps://example.com/search?q=hello world&lang=en&tag=c++ code'
            : 'Paste a percent-encoded URL to decode…\n\nhttps%3A%2F%2Fexample.com%2Fsearch%3Fq%3Dhello%20world%26lang%3Den'"
          class="ue-textarea"
          spellcheck="false"
          @input="decErr = ''"
        ></textarea>
      </div>

      
      <div class="card overflow-hidden">
        <div class="ue-panel-hdr ue-out-hdr">
          <span class="ue-panel-lbl ue-out-lbl"
                x-text="mode === 'encode' ? 'Encoded Output' : 'Decoded Output'">
          </span>
          <div class="flex items-center gap-2">
            <span x-show="outputText.trim()" class="ue-stat"
                  x-text="outputText.length + ' chars'"></span>
          </div>
        </div>

        <div x-show="!outputText" class="ue-out-empty">
          <span x-text="!inputText.trim() ? 'Paste text on the left…' : (decErr ? 'Fix errors to see output' : '...')"></span>
        </div>

        <textarea
          x-show="outputText.trim()"
          :value="outputText"
          readonly
          class="ue-textarea ue-output"
          spellcheck="false"
        ></textarea>
      </div>

    </div>

    
    <div class="flex flex-wrap items-center gap-2.5">

      <button @click="copyOutput()" :disabled="!outputText.trim()"
              :class="['ue-action ue-action-copy', copied ? 'ue-done' : '']">
        <span x-text="copied ? '✓ Copied!' : '⎘ Copy Result'"></span>
      </button>

      <button @click="swapToInput()" :disabled="!outputText.trim()"
              class="ue-action ue-action-swap"
              title="Use the result as input (flips encode/decode mode)">
        ⇅ Use as Input
      </button>

      <button @click="clear()" :disabled="!inputText" class="ue-action ue-action-clear">
        🗑️ Clear
      </button>

      <div x-show="outputText.trim()" x-transition
           class="ml-auto flex items-center gap-1.5 text-xs font-semibold text-fuchsia-700
                  bg-fuchsia-50 border border-fuchsia-200 px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 rounded-full bg-fuchsia-500 flex-shrink-0"></span>
        <span x-text="mode === 'encode'
          ? (encType === 'component' ? 'encodeURIComponent' : 'encodeURI')
          : 'decodeURIComponent'">
        </span>
      </div>

    </div>

    
    <div x-show="outputText.trim() && pctStats.total > 0" x-transition class="card p-5 ue-in">
      <p class="ue-div mb-3">
        <span x-text="mode === 'encode' ? 'Percent-Encoded Sequences' : 'Decoded Sequences'"></span>
      </p>
      <div class="flex flex-wrap gap-2 items-center mb-3">
        <span class="ue-pct-badge">
          <span x-text="pctStats.total"></span>
          <span x-text="mode === 'encode' ? ' sequences encoded' : ' entities decoded'"></span>
        </span>
        <span x-show="mode === 'encode'" class="ue-pct-badge">
          <span x-text="pctStats.unique"></span> unique chars
        </span>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5">
        <template x-for="row in pctStats.top" :key="row.raw">
          <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-fuchsia-100 bg-fuchsia-50">
            <code class="text-xs font-bold text-fuchsia-700 flex-shrink-0" x-text="row.enc"></code>
            <span class="text-gray-400 text-xs">×</span>
            <span class="text-xs font-bold text-gray-600 ml-auto" x-text="row.count"></span>
          </div>
        </template>
      </div>
    </div>

    
    <div x-show="urlParts" x-transition class="card p-5 ue-in">
      <p class="ue-div mb-3">URL Breakdown</p>
      <div class="space-y-0.5">

        <div class="ue-parts-row">
          <span class="ue-parts-key">Protocol</span>
          <code class="ue-parts-val" :class="!urlParts?.protocol ? 'ue-empty' : ''"
                x-text="urlParts?.protocol || '(none)'"></code>
        </div>
        <div class="ue-parts-row">
          <span class="ue-parts-key">Host</span>
          <code class="ue-parts-val" :class="!urlParts?.hostname ? 'ue-empty' : ''"
                x-text="urlParts?.hostname || '(none)'"></code>
        </div>
        <div class="ue-parts-row" x-show="urlParts?.port">
          <span class="ue-parts-key">Port</span>
          <code class="ue-parts-val" x-text="urlParts?.port"></code>
        </div>
        <div class="ue-parts-row">
          <span class="ue-parts-key">Path</span>
          <code class="ue-parts-val" :class="urlParts?.pathname === '/' ? 'ue-empty' : ''"
                x-text="urlParts?.pathname || '/'"></code>
        </div>
        <div class="ue-parts-row" x-show="urlParts?.search">
          <span class="ue-parts-key">Query</span>
          <code class="ue-parts-val" x-text="urlParts?.search"></code>
        </div>
        <div class="ue-parts-row" x-show="urlParts?.hash">
          <span class="ue-parts-key">Fragment</span>
          <code class="ue-parts-val" x-text="urlParts?.hash"></code>
        </div>

        <template x-if="urlParts?.params?.length > 0">
          <div class="mt-3 pt-3 border-t border-fuchsia-100">
            <p class="text-xs font-bold text-fuchsia-700 uppercase tracking-wide mb-2">Query Parameters</p>
            <div class="space-y-1">
              <template x-for="(p, i) in urlParts.params" :key="i">
                <div class="flex items-start gap-2 px-2.5 py-1.5 rounded-lg bg-fuchsia-50 border border-fuchsia-100">
                  <code class="text-xs font-bold text-fuchsia-800 flex-shrink-0" x-text="p.key"></code>
                  <span class="text-gray-400 text-xs flex-shrink-0">=</span>
                  <code class="text-xs text-gray-700 break-all" x-text="p.value || '(empty)'"></code>
                </div>
              </template>
            </div>
          </div>
        </template>
      </div>
    </div>

    
    <div class="card p-5">
      <p class="ue-div mb-3">Common URL Encoding Reference</p>
      <div class="overflow-x-auto">
        <table class="ue-ref-table">
          <thead>
            <tr>
              <th>Character</th>
              <th>Encoded</th>
              <th>Notes</th>
              <th>Encoded by</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Space</td><td><code>%20</code> or <code>+</code></td><td>Most common encoding issue</td><td>Both</td></tr>
            <tr><td><code>&amp;</code></td><td><code>%26</code></td><td>Param separator in query string</td><td>encodeURIComponent</td></tr>
            <tr><td><code>=</code></td><td><code>%3D</code></td><td>Key-value separator</td><td>encodeURIComponent</td></tr>
            <tr><td><code>+</code></td><td><code>%2B</code></td><td>Encoded when literal + is intended</td><td>encodeURIComponent</td></tr>
            <tr><td><code>?</code></td><td><code>%3F</code></td><td>Query string start</td><td>encodeURIComponent</td></tr>
            <tr><td><code>#</code></td><td><code>%23</code></td><td>Fragment identifier</td><td>encodeURIComponent</td></tr>
            <tr><td><code>/</code></td><td><code>%2F</code></td><td>Path separator</td><td>encodeURIComponent</td></tr>
            <tr><td><code>:</code></td><td><code>%3A</code></td><td>Protocol separator</td><td>encodeURIComponent</td></tr>
            <tr><td><code>@</code></td><td><code>%40</code></td><td>User info delimiter</td><td>encodeURIComponent</td></tr>
            <tr><td><code>"</code></td><td><code>%22</code></td><td>Double quote</td><td>Both</td></tr>
            <tr><td><code>&lt;</code></td><td><code>%3C</code></td><td>Less-than</td><td>Both</td></tr>
            <tr><td><code>&gt;</code></td><td><code>%3E</code></td><td>Greater-than</td><td>Both</td></tr>
            <tr><td><code>%</code></td><td><code>%25</code></td><td>Percent sign itself</td><td>Both</td></tr>
          </tbody>
        </table>
      </div>
      <div class="mt-3 p-3 rounded-xl bg-fuchsia-50 border border-fuchsia-100 text-xs text-fuchsia-800 space-y-1">
        <p><strong>encodeURIComponent</strong> — use for individual values (query params, path segments). Encodes <em>everything</em> including <code>:/?#[]@!$&amp;'()*+,;=</code></p>
        <p><strong>encodeURI</strong> — use for a complete URL. Leaves URL-structure chars untouched so the URL stays valid as a whole.</p>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ────────────────────────────────────────────────────────────
   URL Encoder / Decoder — Alpine.js component
   CSS prefix: ue-   |  Theme: Fuchsia (#a21caf)

   Encoding:
     encodeURIComponent — safest; encodes all reserved chars
     encodeURI          — preserves URL-structure chars
     plusSpace          — replaces %20 with + for form encoding

   Decoding:
     decodeURIComponent — with try/catch for URIError
     lineByLine         — decode each line independently
     decErr             — reactive error getter, pure (no side-effects)

   URL Breakdown:
     Uses browser URL API on the live text — shows if parseable.
     Works on both input (encode mode) and output (decode mode).

   pctStats:
     Counts %XX sequences in encode output / input for decode.
──────────────────────────────────────────────────────────── */
function ueCalc() {
  return {

    inputText:  '',
    mode:       'encode',      // 'encode' | 'decode'
    encType:    'component',   // 'component' | 'uri'
    plusSpace:  false,
    lineByLine: false,
    copied:     false,
    decErr:     '',            // set by _doDecode when URIError occurs

    init() {},

    /* ── Reactive getters ──────────────────────────────────── */

    get outputText() {
      if (!this.inputText) return '';
      if (this.mode === 'encode') return this._doEncode(this.inputText);
      var result = this._doDecode(this.inputText);
      return result;
    },

    // Pure getter: tries decode and reports error (no side-effects on other data)
    get decodeError() {
      if (this.mode !== 'decode' || !this.inputText.trim()) return '';
      var self = this;
      var lines = this.lineByLine ? this.inputText.split('\n') : [this.inputText];
      for (var i = 0; i < lines.length; i++) {
        try { decodeURIComponent(lines[i]); }
        catch (e) {
          var lineLabel = this.lineByLine ? 'Line ' + (i + 1) + ': ' : '';
          return lineLabel + 'Invalid percent-encoding. Check for bare % signs not followed by two hex digits (e.g. replace % with %25). JS says: ' + e.message;
        }
      }
      return '';
    },

    get urlParts() {
      // Try parsing the most-relevant text as a URL
      var text = (this.mode === 'decode' && this.outputText.trim())
               ? this.outputText.trim()
               : this.inputText.trim();
      if (!text) return null;
      try {
        var u = new URL(text);
        return {
          protocol: u.protocol,
          hostname: u.hostname,
          port:     u.port,
          pathname: u.pathname,
          search:   u.search,
          hash:     u.hash,
          params:   Array.from(u.searchParams.entries()).map(function (e) {
            return { key: e[0], value: e[1] };
          }),
        };
      } catch (e) { return null; }
    },

    get pctStats() {
      var src = this.mode === 'encode' ? this.outputText : this.inputText;
      if (!src) return { total: 0, unique: 0, top: [] };
      var matches = src.match(/%[0-9A-Fa-f]{2}/g) || [];
      if (matches.length === 0) return { total: 0, unique: 0, top: [] };

      var freq = {};
      matches.forEach(function (m) {
        var upper = m.toUpperCase();
        freq[upper] = (freq[upper] || 0) + 1;
      });

      var entries = Object.entries(freq).sort(function (a, b) { return b[1] - a[1]; });

      return {
        total:  matches.length,
        unique: entries.length,
        top:    entries.slice(0, 8).map(function (kv) {
          // Decode the sequence for display
          var raw = '';
          try { raw = decodeURIComponent(kv[0]); } catch (e) { raw = '?'; }
          return { enc: kv[0], raw: raw, count: kv[1] };
        }),
      };
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
      this.inputText = this.outputText;
      this.decErr    = '';
      this.copied    = false;
      // Flip mode for round-trip convenience
      this.mode = (this.mode === 'encode') ? 'decode' : 'encode';
    },

    clear() {
      this.inputText = '';
      this.decErr    = '';
      this.copied    = false;
    },

    loadSample() {
      if (this.mode === 'encode') {
        this.inputText = 'https://example.com/search?q=hello world&lang=en&tag=c++ code&comment=it\'s "great"!';
      } else {
        this.inputText = 'https%3A%2F%2Fexample.com%2Fsearch%3Fq%3Dhello%20world%26lang%3Den%26tag%3Dc%2B%2B%20code';
      }
      this.decErr = '';
      this.copied = false;
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

    /* ── Encode algorithm ──────────────────────────────────── */

    _doEncode(text) {
      var self = this;
      if (this.lineByLine) {
        return text.split('\n').map(function (l) { return self._encodeLine(l); }).join('\n');
      }
      return this._encodeLine(text);
    },

    _encodeLine(text) {
      var out;
      try {
        out = this.encType === 'component'
          ? encodeURIComponent(text)
          : encodeURI(text);
      } catch (e) {
        // encodeURI can throw on lone surrogates — fallback to component
        out = encodeURIComponent(text);
      }
      // Optional: replace %20 with + (form encoding)
      if (this.plusSpace) out = out.replace(/%20/g, '+');
      return out;
    },

    /* ── Decode algorithm ──────────────────────────────────── */

    _doDecode(text) {
      var self = this;
      if (this.lineByLine) {
        return text.split('\n').map(function (l) { return self._decodeLine(l); }).join('\n');
      }
      return this._decodeLine(text);
    },

    _decodeLine(text) {
      // Normalise + → %20 before decoding (reverse of form encoding)
      var normalized = text.replace(/\+/g, '%20');
      try {
        return decodeURIComponent(normalized);
      } catch (e) {
        // Try partial decoding: decode only valid %XX sequences, leave invalid ones
        return text.replace(/%[0-9A-Fa-f]{2}/g, function (seq) {
          try { return decodeURIComponent(seq); }
          catch (e2) { return seq; }
        });
      }
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\url-encoder-decoder.blade.php ENDPATH**/ ?>