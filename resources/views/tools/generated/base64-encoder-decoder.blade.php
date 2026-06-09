@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════
   Base64 Encoder / Decoder  —  prefix: b64-
   Theme: Warm Chestnut / Coffee  (#7c3418 / #a05a30)
   Live reactive via Alpine.js getters.
   Unicode-safe: TextEncoder → btoa / atob → TextDecoder
══════════════════════════════════════════════════ */

/* Textareas */
.b64-textarea {
  width: 100%; min-height: 168px; resize: vertical;
  font-family: 'JetBrains Mono','Fira Code','Courier New', monospace;
  font-size: .82rem; line-height: 1.75; color: #1e293b;
  caret-color: #a05a30; border: none; outline: none;
  padding: 1rem 1.1rem; background: transparent;
}
.b64-textarea::placeholder { color: #d4a88a; }
.b64-textarea.b64-output    { color: #3c1a08; background: #fdf6f0; cursor: default; }
.b64-textarea::-webkit-scrollbar       { width: 4px; }
.b64-textarea::-webkit-scrollbar-thumb { background: #f5cba7; border-radius: 9999px; }

/* Mode toggle */
.b64-mode-btn {
  flex: 1; padding: .65rem 1rem; border-radius: .875rem; font-size: .85rem;
  font-weight: 800; text-align: center; cursor: pointer; transition: all .18s;
  border: 2px solid #f5cba7; background: #fff; color: #7c3418;
}
.b64-mode-btn:hover { border-color: #a05a30; background: #fdf6f0; }
.b64-mode-btn.b64-on {
  background: linear-gradient(135deg, #431407, #7c3418, #a05a30);
  border-color: #7c3418; color: #fff;
  box-shadow: 0 4px 16px rgba(124,52,24,.32);
}

/* Option toggle row */
.b64-opt-row { display: flex; align-items: center; justify-content: space-between; gap: .5rem; cursor: pointer; }
.b64-opt-lbl { font-size: .8rem; font-weight: 600; color: #374151; }
.b64-opt-sub { font-size: .64rem; color: #9ca3af; margin-top: .1rem; }
.b64-toggle {
  position: relative; width: 2.4rem; height: 1.3rem; border-radius: 9999px;
  background: #e2e8f0; transition: background .2s; flex-shrink: 0;
}
.b64-toggle.b64-on { background: #a05a30; }
.b64-toggle::after {
  content: ''; position: absolute; top: .175rem; left: .175rem;
  width: .95rem; height: .95rem; border-radius: 9999px;
  background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.b64-toggle.b64-on::after { transform: translateX(1.1rem); }

/* Panel headers */
.b64-panel-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6;
}
.b64-panel-hdr.b64-out-hdr { background: #fdf6f0; border-color: #f5e9de; }
.b64-panel-lbl {
  font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.b64-panel-lbl.b64-in-lbl  { color: #94a3b8; }
.b64-panel-lbl.b64-out-lbl { color: #a05a30; }

/* Stat pill */
.b64-stat {
  font-size: .68rem; font-weight: 600; color: #7c3418;
  background: #fdf6f0; padding: .15rem .55rem;
  border-radius: 9999px; border: 1px solid #f5cba7;
}

/* Action buttons */
.b64-action {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .75rem;
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; cursor: pointer; transition: all .15s;
  border: 1.5px solid transparent; white-space: nowrap;
}
.b64-action:disabled { opacity: .35; cursor: not-allowed; }
.b64-action-copy  { background: #fdf6f0; color: #7c3418; border-color: #f5cba7; }
.b64-action-copy:hover:not(:disabled)  { background: #7c3418; color: #fff; border-color: #7c3418; }
.b64-action-copy.b64-done { background: #dcfce7; color: #15803d; border-color: #86efac; }
.b64-action-swap  { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
.b64-action-swap:hover:not(:disabled)  { background: #0369a1; color: #fff; border-color: #0369a1; }
.b64-action-clear { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.b64-action-clear:hover:not(:disabled) { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Divider */
.b64-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #a05a30;
}
.b64-div::before,.b64-div::after { content:''; flex:1; height:1px; background:#f5e9de; }

/* Error */
.b64-error {
  display: flex; align-items: flex-start; gap: .5rem; padding: .75rem 1rem;
  background: #fef2f2; border: 1px solid #fecaca; border-radius: .75rem;
  font-size: .82rem; color: #991b1b;
}

/* Output empty state */
.b64-out-empty {
  min-height: 168px; display: flex; align-items: center; justify-content: center;
  color: #d4a88a; font-size: .88rem; font-style: italic;
  background: #fdf6f0; border-top: 1px solid #f5e9de;
}

/* Stat grid card */
.b64-stat-card {
  background: #fdf6f0; border: 1.5px solid #f5cba7; border-radius: .875rem;
  padding: 1.25rem 1.5rem; text-align: center;
}
.b64-stat-card .b64-sc-val { font-size: 1.4rem; font-weight: 900; color: #7c3418; }
.b64-stat-card .b64-sc-lbl { font-size: .65rem; font-weight: 700; color: #a05a30; text-transform: uppercase; letter-spacing: .08em; }

/* Alphabet table */
.b64-alpha-table { width: 100%; font-size: .75rem; border-collapse: collapse; }
.b64-alpha-table th {
  text-align: left; font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .08em; color: #94a3b8; padding: .4rem .6rem;
  border-bottom: 1px solid #f5e9de; background: #fdf6f0;
}
.b64-alpha-table td {
  padding: .4rem .6rem; border-bottom: 1px solid #f3f4f6; color: #374151;
}
.b64-alpha-table tr:hover td { background: #fdf6f0; }
.b64-alpha-table code {
  font-family: monospace; color: #7c3418; font-size: .78rem; font-weight: 700;
}

/* Ratio badge */
.b64-ratio-badge {
  display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .7rem;
  border-radius: 9999px; font-size: .72rem; font-weight: 700;
  background: #fdf6f0; color: #7c3418; border: 1.5px solid #f5cba7;
}

/* Active mode badge */
.b64-active-badge {
  display: inline-flex; align-items: center; gap: .4rem; font-size: .72rem;
  font-weight: 700; color: #7c3418; background: #fdf6f0;
  border: 1.5px solid #f5cba7; padding: .35rem .75rem; border-radius: 9999px;
}
.b64-active-badge .b64-dot {
  width: .45rem; height: .45rem; border-radius: 9999px;
  background: #a05a30; flex-shrink: 0;
}

@keyframes b64In { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
.b64-in { animation: b64In .22s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="b64Calc()"
     x-init="init()">

  {{-- ── Header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔐 Base64 Encoder / Decoder
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Encode text to Base64 or decode Base64 back to text — supports full Unicode (emoji, CJK, accents). Output updates as you type.
          </p>
        </div>
        <button @click="loadSample()" class="b64-action b64-action-copy self-start sm:self-auto">
          📄 Sample
        </button>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    {{-- ── Mode toggle ── --}}
    <div class="flex gap-3">
      <button @click="mode = 'encode'" :class="['b64-mode-btn', mode==='encode' ? 'b64-on' : '']">
        ⇨ Encode to Base64
      </button>
      <button @click="mode = 'decode'" :class="['b64-mode-btn', mode==='decode' ? 'b64-on' : '']">
        ⇦ Decode from Base64
      </button>
    </div>

    {{-- ── Encode options ── --}}
    <div x-show="mode === 'encode'" x-transition class="card p-4 space-y-3">
      <div class="b64-opt-row" @click="urlSafe = !urlSafe">
        <div>
          <div class="b64-opt-lbl">URL-safe Base64 <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-amber-700">-_</code> instead of <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-amber-700">+/</code></div>
          <div class="b64-opt-sub">Replaces + with – and / with _ — safe for URLs, filenames, and JSON without escaping</div>
        </div>
        <div :class="['b64-toggle', urlSafe ? 'b64-on' : '']"></div>
      </div>
      <div class="border-t border-amber-50 pt-3 b64-opt-row" @click="noPadding = !noPadding">
        <div>
          <div class="b64-opt-lbl">Strip padding <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-amber-700">=</code> signs</div>
          <div class="b64-opt-sub">Removes trailing = characters — some APIs require unpadded Base64</div>
        </div>
        <div :class="['b64-toggle', noPadding ? 'b64-on' : '']"></div>
      </div>
    </div>

    {{-- ── Decode options ── --}}
    <div x-show="mode === 'decode'" x-transition class="card p-4 space-y-3">
      <div class="b64-opt-row" @click="urlSafe = !urlSafe">
        <div>
          <div class="b64-opt-lbl">Input uses URL-safe Base64 <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-amber-700">-_</code></div>
          <div class="b64-opt-sub">Converts – back to + and _ back to / before decoding</div>
        </div>
        <div :class="['b64-toggle', urlSafe ? 'b64-on' : '']"></div>
      </div>
      <div class="border-t border-amber-50 pt-3 b64-opt-row" @click="lineByLine = !lineByLine">
        <div>
          <div class="b64-opt-lbl">Process each line independently</div>
          <div class="b64-opt-sub">Decodes each line as a separate Base64 string — useful for lists of tokens</div>
        </div>
        <div :class="['b64-toggle', lineByLine ? 'b64-on' : '']"></div>
      </div>
    </div>

    {{-- ── Decode error ── --}}
    <div x-show="decodeError" x-transition class="b64-error">
      <span class="text-base flex-shrink-0 mt-0.5">⚠️</span>
      <div>
        <strong class="block mb-0.5">Invalid Base64</strong>
        <span x-text="decodeError"></span>
      </div>
    </div>

    {{-- ── Dual pane ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      {{-- Input --}}
      <div class="card overflow-hidden">
        <div class="b64-panel-hdr">
          <span class="b64-panel-lbl b64-in-lbl"
                x-text="mode === 'encode' ? 'Text to Encode' : 'Base64 to Decode'">
          </span>
          <div class="flex items-center gap-2">
            <span x-show="inputText.trim()" class="b64-stat"
                  x-text="inputText.length + (inputText.length === 1 ? ' char' : ' chars')">
            </span>
          </div>
        </div>
        <textarea
          x-model="inputText"
          :placeholder="mode === 'encode'
            ? 'Paste any text to encode…\n\nHello, World! 🌍\nSupports emoji, CJK, accented chars…'
            : 'Paste Base64-encoded text to decode…\n\nSGVsbG8sIFdvcmxkISDwn4yN'"
          class="b64-textarea"
          spellcheck="false"
        ></textarea>
      </div>

      {{-- Output --}}
      <div class="card overflow-hidden">
        <div class="b64-panel-hdr b64-out-hdr">
          <span class="b64-panel-lbl b64-out-lbl"
                x-text="mode === 'encode' ? 'Base64 Output' : 'Decoded Text'">
          </span>
          <div class="flex items-center gap-2">
            <span x-show="outputText.trim()" class="b64-stat"
                  x-text="outputText.length + ' chars'">
            </span>
          </div>
        </div>

        <div x-show="!outputText.trim()" class="b64-out-empty">
          <span x-text="!inputText.trim()
            ? 'Paste text on the left…'
            : (decodeError ? 'Fix the error to see output' : '...')">
          </span>
        </div>

        <textarea
          x-show="outputText.trim()"
          :value="outputText"
          readonly
          class="b64-textarea b64-output"
          spellcheck="false"
        ></textarea>
      </div>

    </div>

    {{-- ── Actions ── --}}
    <div class="flex flex-wrap items-center gap-2.5">

      <button @click="copyOutput()" :disabled="!outputText.trim()"
              :class="['b64-action b64-action-copy', copied ? 'b64-done' : '']">
        <span x-text="copied ? '✓ Copied!' : '⎘ Copy Result'"></span>
      </button>

      <button @click="swapToInput()" :disabled="!outputText.trim()"
              class="b64-action b64-action-swap"
              title="Put result back as input (flips encode↔decode)">
        ⇅ Use as Input
      </button>

      <button @click="clear()" :disabled="!inputText" class="b64-action b64-action-clear">
        🗑️ Clear
      </button>

      <div x-show="outputText.trim()" x-transition class="ml-auto b64-active-badge">
        <span class="b64-dot"></span>
        <span x-text="mode === 'encode'
          ? (urlSafe ? 'URL-safe Base64' : 'Standard Base64') + (noPadding ? ' · no padding' : '')
          : 'Base64 → UTF-8'">
        </span>
      </div>

    </div>

    {{-- ── Stats panel ── --}}
    <div x-show="outputText.trim()" x-transition class="b64-in">
      <p class="b64-div mb-3">Encoding Statistics</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        <div class="b64-stat-card">
          <div class="b64-sc-val" x-text="stats.inBytes"></div>
          <div class="b64-sc-lbl">Input Bytes</div>
        </div>

        <div class="b64-stat-card">
          <div class="b64-sc-val" x-text="stats.outLen"></div>
          <div class="b64-sc-lbl"
               x-text="mode === 'encode' ? 'Encoded Chars' : 'Output Bytes'"></div>
        </div>

        <div class="b64-stat-card">
          <div class="b64-sc-val" x-text="stats.overhead"></div>
          <div class="b64-sc-lbl"
               x-text="mode === 'encode' ? 'Extra Chars' : 'Bytes Saved'"></div>
        </div>

        <div class="b64-stat-card">
          <div class="b64-sc-val text-xl" x-text="stats.ratio"></div>
          <div class="b64-sc-lbl">Size Ratio</div>
        </div>

      </div>
      <p class="text-xs text-gray-400 mt-2 text-center"
         x-show="mode === 'encode'">
        Base64 adds ~33 % overhead — 3 input bytes become 4 Base64 characters.
      </p>
    </div>

    {{-- ── Alphabet reference ── --}}
    <div class="card p-5">
      <p class="b64-div mb-4">Base64 Alphabet &amp; Quick Reference</p>

      <div class="overflow-x-auto mb-4">
        <table class="b64-alpha-table">
          <thead>
            <tr>
              <th>Value</th><th>Char</th><th>Value</th><th>Char</th>
              <th>Value</th><th>Char</th><th>Value</th><th>Char</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>0–25</td><td><code>A–Z</code></td>
              <td>26–51</td><td><code>a–z</code></td>
              <td>52–61</td><td><code>0–9</code></td>
              <td>62</td><td><code>+ (or – URL)</code></td>
            </tr>
            <tr>
              <td>63</td><td><code>/ (or _ URL)</code></td>
              <td colspan="2">Padding</td><td colspan="2"><code>=</code></td>
              <td colspan="2"><em class="text-gray-400 text-xs">64 chars total</em></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
        <div class="space-y-2">
          <p class="font-bold text-amber-900 text-xs uppercase tracking-wide">Common Use Cases</p>
          <ul class="space-y-1 text-gray-600 text-xs">
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>Email attachments (MIME)</li>
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>Embed images in HTML / CSS as data URIs</li>
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>Store binary data in JSON or XML</li>
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>HTTP Basic Auth header (<code class="bg-gray-100 px-1 rounded">user:pass</code>)</li>
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>JWT tokens (header.payload.signature)</li>
            <li class="flex gap-1.5"><span class="text-amber-600 font-bold">→</span>SSH / PGP key encoding</li>
          </ul>
        </div>
        <div class="space-y-2">
          <p class="font-bold text-amber-900 text-xs uppercase tracking-wide">Standard vs URL-safe</p>
          <div class="overflow-x-auto">
            <table class="b64-alpha-table">
              <thead>
                <tr><th>Standard</th><th>URL-safe</th><th>When to use</th></tr>
              </thead>
              <tbody>
                <tr><td><code>+</code></td><td><code>-</code></td><td>URLs, filenames</td></tr>
                <tr><td><code>/</code></td><td><code>_</code></td><td>AWS S3 keys, JWT</td></tr>
                <tr><td><code>=</code></td><td>optional</td><td>Strip for URLs</td></tr>
              </tbody>
            </table>
          </div>
          <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-100 text-xs text-amber-900">
            <strong>Unicode:</strong> This tool encodes text as UTF-8 bytes first, then Base64. Decode reverses the process — raw binary Base64 (non-UTF-8) may show replacement characters (�).
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────
   Base64 Encoder / Decoder — Alpine.js component
   CSS prefix: b64-  |  Theme: Warm Chestnut (#7c3418 / #a05a30)

   Encoding (Unicode-safe):
     TextEncoder().encode(text) → Uint8Array of UTF-8 bytes
     → String.fromCharCode per byte → binary string → btoa()
     Chunked to avoid call-stack overflow on large strings.

   Decoding (Unicode-safe):
     atob(b64) → binary string → Uint8Array
     → TextDecoder('utf-8', {fatal:false}).decode()
     fatal:false gives U+FFFD replacement chars instead of throwing
     on invalid UTF-8 sequences (e.g. raw binary data).

   Error detection:
     decodeError getter: pure, no side-effects. Calls atob() in a
     try/catch per line. DOMException → user-friendly message.

   Options:
     urlSafe    — + → - and / → _ (RFC 4648 §5)
     noPadding  — strip trailing = signs
     lineByLine — encode/decode each \n-separated line independently
──────────────────────────────────────────────────────────── */
function b64Calc() {
  return {

    inputText:  '',
    mode:       'encode',   // 'encode' | 'decode'
    urlSafe:    false,
    noPadding:  false,
    lineByLine: false,
    copied:     false,

    init() {},

    /* ── Pure reactive getters (no side-effects) ───────────── */

    get outputText() {
      if (!this.inputText.trim() && !this.inputText) return '';
      if (!this.inputText) return '';
      if (this.mode === 'encode') return this._doEncode(this.inputText);
      if (this.decodeError)       return '';  // show error; no partial output
      return this._doDecode(this.inputText);
    },

    get decodeError() {
      if (this.mode !== 'decode' || !this.inputText.trim()) return '';
      var lines = this.lineByLine ? this.inputText.split('\n') : [this.inputText];
      for (var i = 0; i < lines.length; i++) {
        var line = lines[i].trim();
        if (!line) continue;
        var b64 = line;
        if (this.urlSafe) b64 = b64.replace(/-/g, '+').replace(/_/g, '/');
        b64 = b64.replace(/\s/g, '');
        // Add padding so length is multiple of 4
        while (b64.length % 4 !== 0) b64 += '=';
        try { atob(b64); }
        catch (e) {
          var pfx = this.lineByLine ? 'Line ' + (i + 1) + ': ' : '';
          return pfx + 'Contains invalid characters. Base64 only allows A–Z, a–z, 0–9, + (or –), / (or _), and = padding. '
               + 'Check for spaces, line breaks inside a token, or a truncated string. '
               + (this.urlSafe ? '' : 'If input uses – and _, enable the URL-safe option above.');
        }
      }
      return '';
    },

    get stats() {
      if (!this.inputText) return { inBytes: 0, outLen: 0, overhead: 0, ratio: '—' };
      var inBytes = new TextEncoder().encode(this.inputText).length;
      var outLen  = this.outputText.length;
      if (this.mode === 'encode') {
        var oh    = outLen - inBytes;
        var ratio = inBytes > 0 ? Math.round((outLen / inBytes) * 100) + '%' : '—';
        return { inBytes: inBytes, outLen: outLen, overhead: oh, ratio: ratio };
      } else {
        var outBytes = this.outputText ? new TextEncoder().encode(this.outputText).length : 0;
        var saved    = inBytes - outBytes;
        var ratio2   = inBytes > 0 ? Math.round((outBytes / inBytes) * 100) + '%' : '—';
        return { inBytes: inBytes, outLen: outBytes, overhead: saved >= 0 ? saved : 0, ratio: ratio2 };
      }
    },

    /* ── Encode ────────────────────────────────────────────── */

    _doEncode(text) {
      var self = this;
      if (this.lineByLine) {
        return text.split('\n').map(function (l) { return self._encodeLine(l); }).join('\n');
      }
      return this._encodeLine(text);
    },

    _encodeLine(text) {
      // UTF-8 bytes → binary string → btoa (chunked to avoid stack overflow)
      var bytes  = new TextEncoder().encode(text);
      var binary = '';
      var chunk  = 8192;
      for (var i = 0; i < bytes.length; i += chunk) {
        binary += String.fromCharCode.apply(null, bytes.subarray(i, i + chunk));
      }
      var b64 = btoa(binary);
      if (this.urlSafe)   b64 = b64.replace(/\+/g, '-').replace(/\//g, '_');
      if (this.noPadding) b64 = b64.replace(/=/g, '');
      return b64;
    },

    /* ── Decode ────────────────────────────────────────────── */

    _doDecode(text) {
      var self = this;
      if (this.lineByLine) {
        return text.split('\n').map(function (l) { return self._decodeLine(l); }).join('\n');
      }
      return this._decodeLine(text);
    },

    _decodeLine(b64) {
      b64 = b64.replace(/\s/g, '');
      if (!b64) return '';
      if (this.urlSafe) b64 = b64.replace(/-/g, '+').replace(/_/g, '/');
      while (b64.length % 4 !== 0) b64 += '=';
      try {
        var binary = atob(b64);
        var bytes  = new Uint8Array(binary.length);
        for (var i = 0; i < binary.length; i++) {
          bytes[i] = binary.charCodeAt(i);
        }
        // fatal:false → invalid UTF-8 → U+FFFD instead of throw
        return new TextDecoder('utf-8', { fatal: false }).decode(bytes);
      } catch (e) {
        return ''; // decodeError getter will surface the message
      }
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
      this.mode      = (this.mode === 'encode') ? 'decode' : 'encode';
      this.copied    = false;
    },

    clear() {
      this.inputText = '';
      this.copied    = false;
    },

    loadSample() {
      if (this.mode === 'encode') {
        this.inputText = 'Hello, World! 🌍\nBase64 supports Unicode: こんにちは, Ärger, café\nTry the URL-safe option and the swap button!';
      } else {
        // Decodes to: Hello, World! 🌍  (standard Base64, UTF-8)
        this.inputText = 'SGVsbG8sIFdvcmxkISDwn4yNClRoaXMgaXMgQmFzZTY0LiAgU3VwcG9ydHMg77+9IFVuaWNvZGUgdG9vIQ==';
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
  };
}
</script>
@endpush
