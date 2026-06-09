@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════════
   Regex Tester — prefix: rx-
   Theme: Dark code-editor bar + Yellow/Amber highlights
   (#1e293b bar, #eab308 accents, cycling mark colors)
   Live reactive via Alpine.js getters. No backend.
══════════════════════════════════════════════════════ */

/* ── Pattern bar ────────────────────────────────────── */
.rx-bar {
  display: flex; align-items: center; gap: .5rem; flex-wrap: wrap;
  background: #0f172a; border-radius: 1rem; padding: .55rem .8rem;
  border: 2px solid #1e293b; transition: border-color .18s;
}
.rx-bar:focus-within { border-color: #eab308; box-shadow: 0 0 0 3px rgba(234,179,8,.15); }
.rx-slash { font-family: monospace; font-size: 1.35rem; font-weight: 900; color: #eab308; flex-shrink:0; line-height:1; user-select:none; }
.rx-pattern-input {
  flex: 1; min-width: 120px; background: transparent; border: none; outline: none;
  font-family: 'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size: .9rem; color: #f1f5f9; caret-color: #eab308; padding: .4rem .1rem; letter-spacing: .02em;
}
.rx-pattern-input::placeholder { color: #334155; }

/* Flag toggles */
.rx-flag {
  padding: .22rem .55rem; border-radius: .45rem; font-size: .68rem;
  font-family: monospace; font-weight: 900; cursor: pointer; transition: all .14s; flex-shrink:0;
  border: 1.5px solid #334155; background: transparent; color: #64748b; letter-spacing: .04em;
  white-space: nowrap;
}
.rx-flag:hover  { border-color: #64748b; color: #cbd5e1; }
.rx-flag.rx-on  { background: #eab308; border-color: #eab308; color: #1e293b; box-shadow: 0 0 8px rgba(234,179,8,.4); }

/* ── Stat / badge row ───────────────────────────────── */
.rx-badge {
  display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .7rem;
  border-radius: 9999px; font-size: .7rem; font-weight: 700; white-space: nowrap;
}
.rx-badge-match  { background: #fef9c3; color: #713f12; border: 1.5px solid #fde047; }
.rx-badge-none   { background: #f8fafc; color: #94a3b8; border: 1.5px solid #e2e8f0; }
.rx-badge-error  { background: #fef2f2; color: #991b1b; border: 1.5px solid #fecaca; }
.rx-badge-chars  { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }

/* ── Textareas ──────────────────────────────────────── */
.rx-textarea {
  width:100%; min-height:180px; resize:vertical;
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:.82rem; line-height:1.75; color:#1e293b;
  caret-color:#a16207; border:none; outline:none; padding:1rem 1.1rem; background:transparent;
}
.rx-textarea::placeholder { color:#d1d5db; }
.rx-textarea::-webkit-scrollbar       { width:4px; }
.rx-textarea::-webkit-scrollbar-thumb { background:#fde047; border-radius:9999px; }

/* ── Match preview (highlighted view) ──────────────── */
.rx-prev-hdr {
  padding:.5rem 1rem; border-top:1px solid #f3f4f6; border-bottom:1px dashed #fde047;
  background:#fefce8; display:flex; align-items:center; gap:.4rem;
  font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#a16207;
}
.rx-preview {
  width:100%; min-height:50px; padding:.85rem 1.1rem;
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:.82rem; line-height:1.75; color:#374151;
  white-space:pre-wrap; word-break:break-word; overflow-wrap:break-word;
  background:#fffbeb; overflow-x:auto;
}
.rx-preview::-webkit-scrollbar       { height:3px; }
.rx-preview::-webkit-scrollbar-thumb { background:#fde047; border-radius:9999px; }

/* Match highlight colours — 4 cycling colours */
.rx-mark { border-radius:3px; padding:1px 0; cursor:default; font-weight:inherit; }
.rx-h0 { background:#fde047; color:#1a1a00; }   /* yellow-300    */
.rx-h1 { background:#fdba74; color:#1c0a00; }   /* orange-300    */
.rx-h2 { background:#6ee7b7; color:#022c22; }   /* emerald-300   */
.rx-h3 { background:#c4b5fd; color:#1e003b; }   /* violet-300    */
/* Zero-length match marker */
.rx-zero { display:inline-block; width:2px; height:1.1em; background:#ef4444; vertical-align:text-bottom; border-radius:1px; }

/* ── Panel header ───────────────────────────────────── */
.rx-panel-hdr {
  padding:.55rem 1rem; border-bottom:1px solid #f3f4f6;
  display:flex; align-items:center; justify-content:space-between;
}
.rx-panel-lbl { font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; }

/* ── Match list ─────────────────────────────────────── */
.rx-match-list { overflow-y:auto; }
.rx-match-list::-webkit-scrollbar       { width:4px; }
.rx-match-list::-webkit-scrollbar-thumb { background:#fde047; border-radius:9999px; }
.rx-match-item {
  padding:.65rem .9rem; border-bottom:1px solid #f3f4f6; transition:background .12s;
}
.rx-match-item:hover     { background:#fefce8; }
.rx-match-item:last-child{ border-bottom:none; }

/* Match number bubble */
.rx-mn { display:inline-flex; align-items:center; justify-content:center; width:1.5rem; height:1.5rem; border-radius:9999px; font-size:.6rem; font-weight:900; flex-shrink:0; }
.rx-mn0 { background:#fde047; color:#1e293b; }
.rx-mn1 { background:#fdba74; color:#1e293b; }
.rx-mn2 { background:#6ee7b7; color:#1e293b; }
.rx-mn3 { background:#c4b5fd; color:#1e293b; }

.rx-match-val {
  font-family:monospace; font-size:.78rem; color:#1e293b;
  background:#f8fafc; border:1px solid #e2e8f0; border-radius:.4rem;
  padding:.1rem .45rem; word-break:break-all; display:block; margin-top:.2rem;
}
.rx-match-val.rx-empty-match { color:#9ca3af; font-style:italic; }
.rx-meta { font-size:.65rem; color:#9ca3af; margin-top:.15rem; }

/* Group badges */
.rx-groups { display:flex; flex-wrap:wrap; gap:.3rem; margin-top:.35rem; }
.rx-grp {
  font-size:.62rem; font-weight:700; padding:.1rem .45rem; border-radius:.4rem;
  background:#fef9c3; color:#92400e; border:1px solid #fde047; font-family:monospace;
  max-width:12rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.rx-grp-null { background:#f3f4f6; color:#9ca3af; border-color:#e5e7eb; }
.rx-grp-lbl  { opacity:.6; font-weight:600; margin-right:.2rem; }

/* Copy match button */
.rx-copy-m {
  padding:.1rem .4rem; border-radius:.35rem; font-size:.6rem; font-weight:700;
  cursor:pointer; border:1px solid #e2e8f0; background:#fff; color:#64748b; transition:all .12s;
}
.rx-copy-m:hover { border-color:#eab308; color:#a16207; background:#fefce8; }
.rx-copy-m.rx-done { background:#dcfce7; color:#15803d; border-color:#86efac; }

/* ── Action buttons ──────────────────────────────────── */
.rx-action {
  display:inline-flex; align-items:center; gap:.35rem; padding:.45rem .9rem; border-radius:.75rem;
  font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em;
  cursor:pointer; transition:all .15s; border:1.5px solid transparent; white-space:nowrap;
}
.rx-action:disabled { opacity:.3; cursor:not-allowed; }
.rx-action-copy  { background:#fefce8; color:#713f12; border-color:#fde047; }
.rx-action-copy:hover:not(:disabled)  { background:#eab308; color:#1e293b; border-color:#eab308; }
.rx-action-copy.rx-done { background:#dcfce7; color:#15803d; border-color:#86efac; }
.rx-action-clear { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
.rx-action-clear:hover:not(:disabled) { background:#dc2626; color:#fff; border-color:#dc2626; }

/* ── Error ───────────────────────────────────────────── */
.rx-error {
  display:flex; align-items:flex-start; gap:.5rem; padding:.75rem 1rem;
  background:#fef2f2; border:1px solid #fecaca; border-radius:.875rem;
  font-size:.8rem; color:#991b1b;
}
.rx-error code { font-family:monospace; font-weight:700; }

/* ── Divider label ───────────────────────────────────── */
.rx-div {
  display:flex; align-items:center; gap:.6rem;
  font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#a16207;
}
.rx-div::before,.rx-div::after { content:''; flex:1; height:1px; background:#fde047; }

/* ── Presets ─────────────────────────────────────────── */
.rx-preset {
  display:inline-flex; align-items:center; gap:.35rem; padding:.4rem .8rem;
  border-radius:.6rem; font-size:.75rem; font-weight:600; cursor:pointer; transition:all .15s;
  border:1.5px solid #e2e8f0; background:#fff; color:#374151; white-space:nowrap;
}
.rx-preset:hover { border-color:#eab308; color:#713f12; background:#fefce8; }

/* ── Cheatsheet table ────────────────────────────────── */
.rx-cs-table { width:100%; font-size:.75rem; border-collapse:collapse; }
.rx-cs-table th { text-align:left; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; padding:.4rem .6rem; border-bottom:1px solid #f5e9de; background:#fefce8; }
.rx-cs-table td { padding:.38rem .6rem; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:top; }
.rx-cs-table tr:hover td { background:#fefce8; }
.rx-cs-table code { font-family:monospace; color:#92400e; font-weight:700; font-size:.78rem; }
.rx-cs-section { font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#a16207; padding:.55rem .6rem .25rem; background:#fefce8; border-bottom:1px solid #fde047; }

@keyframes rxIn { from{opacity:0;transform:translateY(4px)} to{opacity:1;transform:translateY(0)} }
.rx-in { animation:rxIn .2s ease-out; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="rxTester()"
     x-init="init()">

  {{-- ── Header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            🔍 Regex Tester
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Test regular expressions against text in real-time — highlights every match, shows capture groups, index positions, and named groups.
          </p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
          <button @click="loadSample()" class="rx-action rx-action-copy">
            📄 Sample
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-4">

    {{-- ── Pattern bar ── --}}
    <div class="card p-4">
      <label class="rx-panel-lbl block mb-2.5">Regular Expression</label>
      <div class="rx-bar">
        <span class="rx-slash" aria-hidden="true">/</span>
        <input
          type="text"
          x-model="pattern"
          placeholder="Enter your regex pattern…"
          class="rx-pattern-input"
          spellcheck="false"
          autocomplete="off"
          aria-label="Regex pattern"
        >
        <span class="rx-slash" aria-hidden="true">/</span>
        {{-- Flag buttons --}}
        <div class="flex gap-1.5 flex-wrap">
          <button @click="flags.g = !flags.g"   :class="['rx-flag', flags.g ? 'rx-on' : '']" title="g — global: find all matches (not just first)">g</button>
          <button @click="flags.i = !flags.i"   :class="['rx-flag', flags.i ? 'rx-on' : '']" title="i — case insensitive: A matches a">i</button>
          <button @click="flags.m = !flags.m"   :class="['rx-flag', flags.m ? 'rx-on' : '']" title="m — multiline: ^ and $ match start/end of each line">m</button>
          <button @click="flags.s = !flags.s"   :class="['rx-flag', flags.s ? 'rx-on' : '']" title="s — dotAll: . matches newlines too">s</button>
          <button @click="flags.u = !flags.u"   :class="['rx-flag', flags.u ? 'rx-on' : '']" title="u — unicode: enable full Unicode support">u</button>
        </div>
      </div>

      {{-- Inline flag descriptions --}}
      <div x-show="activeFlags.length" x-transition class="flex flex-wrap gap-2 mt-2.5">
        <template x-for="f in activeFlags" :key="f.k">
          <span class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-0.5 font-medium" x-text="f.k + ' — ' + f.desc"></span>
        </template>
      </div>
    </div>

    {{-- ── Pattern error ── --}}
    <div x-show="patternError" x-transition class="rx-error">
      <span class="text-base flex-shrink-0">⚠️</span>
      <div>
        <strong class="block mb-0.5">Invalid Regular Expression</strong>
        <code x-text="patternError"></code>
      </div>
    </div>

    {{-- ── Stats + actions ── --}}
    <div class="flex flex-wrap items-center gap-2.5">

      {{-- Match count --}}
      <div x-show="!hasPattern || (!testText.trim() && !patternError)"
           class="rx-badge rx-badge-none">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 flex-shrink-0"></span>
        <span x-text="!hasPattern ? 'Enter a pattern above' : 'Paste test text below'"></span>
      </div>

      <div x-show="hasPattern && patternError && testText.trim()"
           class="rx-badge rx-badge-error">
        <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
        Fix the pattern to see matches
      </div>

      <div x-show="hasPattern && !patternError && testText.trim() && matchCount === 0"
           class="rx-badge rx-badge-none rx-in">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
        No matches found
      </div>

      <div x-show="hasPattern && !patternError && matchCount > 0"
           class="rx-badge rx-badge-match rx-in">
        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 flex-shrink-0"></span>
        <strong x-text="matchCount.toLocaleString()"></strong>
        <span x-text="matchCount === 1 ? 'match' : 'matches'"></span>
        <span x-show="!flags.g" class="opacity-60 text-xs">(g off)</span>
      </div>

      <div x-show="testText.trim()" class="rx-badge rx-badge-chars">
        <span x-text="testText.length.toLocaleString() + ' chars'"></span>
        <span class="opacity-40">·</span>
        <span x-text="testText.split('\n').length + ' lines'"></span>
      </div>

      <div class="flex gap-2 ml-auto flex-wrap">
        <button @click="copyAllMatches()"
                :disabled="matchCount === 0"
                :class="['rx-action rx-action-copy', copiedAll ? 'rx-done' : '']">
          <span x-text="copiedAll ? '✓ Copied!' : '⎘ Copy Matches'"></span>
        </button>
        <button @click="clearAll()"
                :disabled="!pattern && !testText"
                class="rx-action rx-action-clear">
          🗑️ Clear
        </button>
      </div>
    </div>

    {{-- ── Main grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- Left: test text + match preview --}}
      <div class="card overflow-hidden lg:col-span-2">

        <div class="rx-panel-hdr">
          <span class="rx-panel-lbl">Test Text</span>
          <span x-show="testText.trim()" class="text-xs font-semibold text-gray-400"
                x-text="testText.length.toLocaleString() + ' chars'"></span>
        </div>

        <textarea
          x-model="testText"
          class="rx-textarea"
          placeholder="Paste or type your test text here…&#10;&#10;Matches will be highlighted below in real-time."
          spellcheck="false"
          aria-label="Test text"
        ></textarea>

        {{-- Match preview panel --}}
        <div x-show="hasPattern && testText.trim()" x-transition>
          <div class="rx-prev-hdr">
            <span>🔆</span>
            <span>Match Preview</span>
            <span x-show="matchCount > 0" class="ml-auto text-xs text-amber-600 font-bold"
                  x-text="matchCount + (matchCount === 1 ? ' match' : ' matches') + ' highlighted'">
            </span>
            <span x-show="matchCount === 0 && !patternError" class="ml-auto text-xs text-gray-400">no matches — showing plain text</span>
          </div>
          <div class="rx-preview" x-html="highlightedHtml"></div>
        </div>

      </div>

      {{-- Right: match details --}}
      <div class="card overflow-hidden flex flex-col">

        <div class="rx-panel-hdr flex-shrink-0">
          <span class="rx-panel-lbl">Match Details</span>
          <span x-show="matchCount > MAX_DISPLAY"
                class="text-xs text-amber-600 font-bold"
                x-text="'showing ' + MAX_DISPLAY + '/' + matchCount">
          </span>
        </div>

        {{-- Empty states --}}
        <div x-show="!hasPattern" class="p-8 text-center flex-1">
          <div class="text-3xl mb-2">🔬</div>
          <p class="text-sm text-gray-400">Enter a regex pattern above to see match details here.</p>
        </div>

        <div x-show="hasPattern && patternError" class="p-6 text-center flex-1">
          <div class="text-3xl mb-2">⚠️</div>
          <p class="text-sm text-red-400">Fix the pattern error to see matches.</p>
        </div>

        <div x-show="hasPattern && !patternError && !testText.trim()" class="p-6 text-center flex-1">
          <div class="text-3xl mb-2">📝</div>
          <p class="text-sm text-gray-400">Paste test text on the left.</p>
        </div>

        <div x-show="hasPattern && !patternError && testText.trim() && matchCount === 0"
             class="p-6 text-center flex-1 rx-in">
          <div class="text-3xl mb-2">🔎</div>
          <p class="text-sm text-gray-500 font-medium">No matches found.</p>
          <p class="text-xs text-gray-400 mt-1">Try toggling the <strong>i</strong> flag for case-insensitive matching, or the <strong>m</strong> flag for multiline.</p>
        </div>

        {{-- Match list --}}
        <div x-show="matchCount > 0" class="rx-match-list rx-in" style="max-height:520px">
          <template x-for="m in matchesDisplayed" :key="m.n">
            <div class="rx-match-item">
              <div class="flex items-start gap-2">
                <span :class="['rx-mn', 'rx-mn' + ((m.n-1) % 4)]" x-text="m.n"></span>
                <div class="flex-1 min-w-0">
                  <code :class="['rx-match-val', m.value === '' ? 'rx-empty-match' : '']"
                        x-text="m.value !== '' ? m.value : '(zero-length match)'"></code>
                  <div class="rx-meta">
                    idx <strong class="text-gray-600" x-text="m.index"></strong>
                    <span class="opacity-50 mx-0.5">·</span>
                    end <strong class="text-gray-600" x-text="m.end"></strong>
                    <span class="opacity-50 mx-0.5">·</span>
                    len <strong class="text-gray-600" x-text="m.length"></strong>
                  </div>

                  {{-- Numbered capture groups --}}
                  <template x-if="m.captures.length > 0">
                    <div class="rx-groups">
                      <template x-for="(cap, gi) in m.captures" :key="gi">
                        <span :class="['rx-grp', cap === undefined ? 'rx-grp-null' : '']"
                              :title="cap !== undefined ? ('Group ' + (gi+1) + ': ' + cap) : 'Group ' + (gi+1) + ': did not participate'">
                          <span class="rx-grp-lbl" x-text="'$' + (gi + 1) + ':'"></span>
                          <span x-text="cap !== undefined ? cap : '∅'"></span>
                        </span>
                      </template>
                    </div>
                  </template>

                  {{-- Named groups --}}
                  <template x-if="m.named.length > 0">
                    <div class="rx-groups mt-1">
                      <template x-for="ng in m.named" :key="ng[0]">
                        <span class="rx-grp"
                              :title="'Named group &lt;' + ng[0] + '&gt;: ' + ng[1]">
                          <span class="rx-grp-lbl" x-text="ng[0] + ':'"></span>
                          <span x-text="ng[1] !== null && ng[1] !== undefined ? ng[1] : '∅'"></span>
                        </span>
                      </template>
                    </div>
                  </template>
                </div>

                {{-- Copy this match --}}
                <button @click="copyMatch(m.n, m.value)"
                        :class="['rx-copy-m flex-shrink-0', copiedMatchN === m.n ? 'rx-done' : '']"
                        title="Copy this match">
                  <span x-text="copiedMatchN === m.n ? '✓' : '⎘'"></span>
                </button>
              </div>
            </div>
          </template>

          {{-- Overflow notice --}}
          <div x-show="matchCount > MAX_DISPLAY"
               class="p-3 text-center text-xs text-amber-700 bg-amber-50 border-t border-amber-100">
            Showing first <strong x-text="MAX_DISPLAY"></strong> of <strong x-text="matchCount.toLocaleString()"></strong> matches.
            Remove the <strong>g</strong> flag or refine your pattern to reduce results.
          </div>
        </div>

      </div>
    </div>

    {{-- ── Quick presets ── --}}
    <div class="card p-5">
      <p class="rx-div mb-4">Quick Presets</p>
      <div class="flex flex-wrap gap-2">
        <template x-for="p in PRESETS" :key="p.label">
          <button @click="loadPreset(p)" class="rx-preset">
            <span x-text="p.icon" aria-hidden="true"></span>
            <span x-text="p.label"></span>
          </button>
        </template>
      </div>
    </div>

    {{-- ── Cheatsheet ── --}}
    <div class="card p-5">
      <p class="rx-div mb-4">Regex Quick Reference</p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="overflow-x-auto">
          <table class="rx-cs-table">
            <thead><tr><th>Pattern</th><th>Description</th></tr></thead>
            <tbody>
              <tr><td colspan="2" class="rx-cs-section">Anchors</td></tr>
              <tr><td><code>^</code></td><td>Start of string (or line with <code>m</code>)</td></tr>
              <tr><td><code>$</code></td><td>End of string (or line with <code>m</code>)</td></tr>
              <tr><td><code>\b</code></td><td>Word boundary</td></tr>
              <tr><td><code>\B</code></td><td>Non-word boundary</td></tr>

              <tr><td colspan="2" class="rx-cs-section">Character Classes</td></tr>
              <tr><td><code>.</code></td><td>Any char except <code>\n</code> (or all with <code>s</code>)</td></tr>
              <tr><td><code>\d</code> / <code>\D</code></td><td>Digit / non-digit</td></tr>
              <tr><td><code>\w</code> / <code>\W</code></td><td>Word char [A-Za-z0-9_] / not</td></tr>
              <tr><td><code>\s</code> / <code>\S</code></td><td>Whitespace / non-whitespace</td></tr>
              <tr><td><code>[abc]</code></td><td>Character class — a, b, or c</td></tr>
              <tr><td><code>[^abc]</code></td><td>Negated class — not a, b, or c</td></tr>
              <tr><td><code>[a-z]</code></td><td>Range — lowercase letter</td></tr>
            </tbody>
          </table>
        </div>

        <div class="overflow-x-auto">
          <table class="rx-cs-table">
            <thead><tr><th>Pattern</th><th>Description</th></tr></thead>
            <tbody>
              <tr><td colspan="2" class="rx-cs-section">Quantifiers</td></tr>
              <tr><td><code>*</code></td><td>0 or more (greedy)</td></tr>
              <tr><td><code>+</code></td><td>1 or more (greedy)</td></tr>
              <tr><td><code>?</code></td><td>0 or 1 (optional)</td></tr>
              <tr><td><code>{n}</code></td><td>Exactly n times</td></tr>
              <tr><td><code>{n,m}</code></td><td>Between n and m times</td></tr>
              <tr><td><code>*?</code> <code>+?</code></td><td>Non-greedy (lazy) variants</td></tr>

              <tr><td colspan="2" class="rx-cs-section">Groups &amp; Lookarounds</td></tr>
              <tr><td><code>(x)</code></td><td>Capturing group</td></tr>
              <tr><td><code>(?:x)</code></td><td>Non-capturing group</td></tr>
              <tr><td><code>(?&lt;n&gt;x)</code></td><td>Named capturing group</td></tr>
              <tr><td><code>(?=x)</code></td><td>Positive lookahead</td></tr>
              <tr><td><code>(?!x)</code></td><td>Negative lookahead</td></tr>
              <tr><td><code>(?&lt;=x)</code></td><td>Positive lookbehind</td></tr>
              <tr><td><code>(?&lt;!x)</code></td><td>Negative lookbehind</td></tr>
              <tr><td><code>x|y</code></td><td>Alternation — x or y</td></tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────────
   Regex Tester — Alpine.js component
   CSS prefix: rx-  |  Theme: Dark bar + Yellow/Amber (#eab308)

   Architecture:
     - pattern, testText, flags{g,i,m,s,u} are reactive data
     - ALL outputs are pure computed getters (no side-effects)
     - matches getter: iterates with re.exec(), caps at 10 000
     - highlightedHtml: escapes user text, wraps matches in <mark>
       Uses 4-colour cycling: yellow / orange / emerald / violet
       Caps at 1 000 matches to keep DOM updates fast
     - XSS safety: _esc() encodes all user text before x-html

   Flags:
     g  — global: iterate all matches (stored as flags.g)
     i  — case insensitive
     m  — multiline: ^ $ match per line
     s  — dotAll: . matches \n
     u  — full Unicode mode

   Match object shape:
     { n, index, end, value, length, captures[], named[] }
     captures = Array.from(matchArr).slice(1) — numbered groups
     named    = Object.entries(match.groups) — named groups
─────────────────────────────────────────────────────────────────── */
function rxTester() {
  return {

    pattern:  '',
    testText: '',
    flags:    { g: true, i: false, m: false, s: false, u: false },

    MAX_DISPLAY:   100,
    copiedMatchN:  0,
    copiedAll:     false,

    /* ── Presets ─────────────────────────────────────── */
    PRESETS: [
      {
        label: 'Email', icon: '📧', flagStr: 'gi',
        pattern: '[\\w.+\\-]+@[\\w\\-]+(?:\\.[a-zA-Z]{2,})+',
        text: 'Contacts: hello@example.com, support@company.org\nInvalid: foo@ @bar.com, missing@.com'
      },
      {
        label: 'URL', icon: '🔗', flagStr: 'gi',
        pattern: 'https?:\\/\\/[\\w\\-._~:/?#\\[\\]@!$&\'()*+,;=]+',
        text: 'Visit https://example.com/path?q=test#hash or http://www.site.org/about'
      },
      {
        label: 'Phone (US)', icon: '📞', flagStr: 'g',
        pattern: '\\(?([0-9]{3})\\)?[\\s.\\-]?([0-9]{3})[\\s.\\-]?([0-9]{4})',
        text: 'Call (555) 123-4567 or 555.987.6543 or 1-800-555-0199 ext 42'
      },
      {
        label: 'Date (ISO)', icon: '📅', flagStr: 'g',
        pattern: '(\\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\\d|3[01])',
        text: 'Events: 2024-01-15, 2024-12-31. Invalid: 2024-13-01, 2024-00-05'
      },
      {
        label: 'Hex Color', icon: '🎨', flagStr: 'gi',
        pattern: '#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})\\b',
        text: 'CSS: color:#ff0000; background:#abc; border:#3a5f9e; fill:#FFF (bad: #xyz #12345)'
      },
      {
        label: 'HTML Tag', icon: '🏷', flagStr: 'gi',
        pattern: '<\\/?([a-zA-Z][a-zA-Z0-9]*)([^>]*)>',
        text: '<div class="box"><p>Hello <strong>world</strong></p>\n<img src="a.png" alt="test"></div>'
      },
      {
        label: 'Numbers', icon: '🔢', flagStr: 'g',
        pattern: '-?\\d+(?:\\.\\d+)?',
        text: 'Pi=3.14159, e=2.718, temp=-12.5°C, items=42, price=9.99, offset=-0.001'
      },
      {
        label: 'Named Groups', icon: '🔬', flagStr: 'g',
        pattern: '(?<year>\\d{4})-(?<month>\\d{2})-(?<day>\\d{2})',
        text: 'ISO dates: 2024-01-15 and 2023-12-31 — open Match Details to see named groups!'
      },
    ],

    FLAG_INFO: {
      g: 'global — find all matches',
      i: 'case insensitive',
      m: 'multiline — ^ $ match per line',
      s: 'dotAll — . matches newlines',
      u: 'unicode mode',
    },

    init() {},

    /* ── Pure computed getters ───────────────────────── */

    get flagString() {
      return (this.flags.g ? 'g' : '') +
             (this.flags.i ? 'i' : '') +
             (this.flags.m ? 'm' : '') +
             (this.flags.s ? 's' : '') +
             (this.flags.u ? 'u' : '');
    },

    get activeFlags() {
      var self = this;
      return Object.entries(this.flags)
        .filter(function (kv) { return kv[1]; })
        .map(function (kv) { return { k: kv[0], desc: self.FLAG_INFO[kv[0]] }; });
    },

    get hasPattern() { return this.pattern.trim().length > 0; },

    get patternError() {
      if (!this.hasPattern) return '';
      try { new RegExp(this.pattern, this.flagString); return ''; }
      catch (e) { return e.message; }
    },

    get matches() {
      if (!this.hasPattern || this.patternError || !this.testText) return [];
      try {
        var text = this.testText;
        var results = [];

        if (!this.flags.g) {
          // No global flag: single match only
          var re = new RegExp(this.pattern, this.flagString);
          var m = re.exec(text);
          if (m) results.push(this._makeMatch(m, 1));
        } else {
          // Global: iterate all non-overlapping matches
          var re2 = new RegExp(this.pattern, this.flagString);
          var m2, n = 1;
          while ((m2 = re2.exec(text)) !== null) {
            results.push(this._makeMatch(m2, n++));
            // Prevent infinite loop on zero-length match
            if (m2[0].length === 0) re2.lastIndex++;
            if (n > 10000) break; // hard safety cap
          }
        }
        return results;
      } catch (e) { return []; }
    },

    get matchesDisplayed() {
      return this.matches.slice(0, this.MAX_DISPLAY);
    },

    get matchCount() { return this.matches.length; },

    get highlightedHtml() {
      var text = this.testText;
      if (!text) return '';
      // No valid pattern or error: show plain escaped text
      if (!this.hasPattern || this.patternError) return this._esc(text);
      var ms = this.matches;
      if (ms.length === 0) return this._esc(text);
      // Too many matches: skip highlights for performance
      if (ms.length > 1000) {
        return '<span style="color:#a16207;font-size:.78rem;font-style:normal">'
          + '⚠ ' + ms.length.toLocaleString() + ' matches — too many to highlight. Showing plain text.</span>\n\n'
          + this._esc(text);
      }
      var COLORS = ['rx-h0', 'rx-h1', 'rx-h2', 'rx-h3'];
      var out    = '';
      var last   = 0;
      for (var i = 0; i < ms.length; i++) {
        var m = ms[i];
        // Text before this match
        if (m.index > last) out += this._esc(text.slice(last, m.index));
        // The match itself
        if (m.value === '') {
          out += '<span class="rx-zero" title="Zero-length match at index ' + m.index + '"></span>';
        } else {
          var cls = COLORS[i % COLORS.length];
          out += '<mark class="rx-mark ' + cls + '" title="#' + m.n + ' · idx ' + m.index + ' · len ' + m.length + '">'
               + this._esc(m.value)
               + '</mark>';
        }
        last = m.end;
      }
      // Remaining text after last match
      if (last < text.length) out += this._esc(text.slice(last));
      return out;
    },

    /* ── Actions ─────────────────────────────────────── */

    loadPreset(p) {
      this.pattern  = p.pattern;
      this.testText = p.text;
      var f = p.flagStr;
      this.flags = {
        g: f.indexOf('g') !== -1,
        i: f.indexOf('i') !== -1,
        m: f.indexOf('m') !== -1,
        s: f.indexOf('s') !== -1,
        u: f.indexOf('u') !== -1,
      };
      this.copiedMatchN = 0;
      this.copiedAll    = false;
    },

    loadSample() {
      this.loadPreset(this.PRESETS[0]); // Email preset as demo
    },

    copyMatch(n, val) {
      var self = this;
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(val).then(function () {
        self.copiedMatchN = n;
        setTimeout(function () { self.copiedMatchN = 0; }, 1500);
      }).catch(function () { self._legacyCopy(val); });
    },

    copyAllMatches() {
      var self = this;
      if (this.matchCount === 0) return;
      var text = this.matches.map(function (m) { return m.value; }).join('\n');
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function () {
          self.copiedAll = true;
          setTimeout(function () { self.copiedAll = false; }, 2000);
        }).catch(function () { self._legacyCopy(text); });
      } else {
        this._legacyCopy(text);
      }
    },

    clearAll() {
      this.pattern  = '';
      this.testText = '';
      this.copiedMatchN = 0;
      this.copiedAll    = false;
    },

    /* ── Helpers ─────────────────────────────────────── */

    _makeMatch(m, n) {
      return {
        n:        n,
        index:    m.index,
        end:      m.index + m[0].length,
        value:    m[0],
        length:   m[0].length,
        captures: Array.from(m).slice(1),
        named:    m.groups ? Object.entries(m.groups) : [],
      };
    },

    // HTML-escape user content before inserting with x-html
    _esc(text) {
      return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    },

    _legacyCopy(text) {
      var el = document.createElement('textarea');
      el.value = text;
      el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
      document.body.appendChild(el);
      el.focus(); el.select();
      try { document.execCommand('copy'); } catch (e) {}
      document.body.removeChild(el);
    },
  };
}
</script>
@endpush
