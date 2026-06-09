@extends('layouts.public')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════════════════
   Cron Expression Generator — prefix: cg-
   Theme: dark expression card + cobalt blue (#1e40af) chrome
   Field color coding: minute=blue, hour=green, dom=amber,
                       month=violet, dow=pink
   5 modes: every | step | specific | range | range+step
══════════════════════════════════════════════════════════════ */

/* ── Expression display card (dark) ────────────────────── */
.cg-dark-card {
  background:#0f172a; border-radius:1rem; padding:1.5rem;
  border:1px solid #1e293b;
}
.cg-field-badge { text-align:center; }
.cg-field-lbl {
  font-size:.55rem; font-weight:800; text-transform:uppercase; letter-spacing:.12em;
  display:block; margin-bottom:.2rem; opacity:.7;
}
.cg-field-val {
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:1.4rem; font-weight:900; display:block; line-height:1;
}
.cg-sep { color:#334155; font-size:1.3rem; font-weight:300; margin:0 .1rem; }

.cg-minute  { color:#60a5fa; } .cg-lbl-minute  { color:#3b82f6; }
.cg-hour    { color:#4ade80; } .cg-lbl-hour    { color:#22c55e; }
.cg-dom     { color:#fbbf24; } .cg-lbl-dom     { color:#f59e0b; }
.cg-month   { color:#c084fc; } .cg-lbl-month   { color:#a855f7; }
.cg-dow     { color:#f472b6; } .cg-lbl-dow     { color:#ec4899; }

.cg-expr-line {
  display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
  padding-top:1rem; margin-top:.75rem;
  border-top:1px solid #1e293b;
}
.cg-expr-code {
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:1.25rem; font-weight:800; color:#e2e8f0; letter-spacing:.08em;
  flex:1; min-width:0; word-break:break-all;
}
.cg-human-line {
  padding-top:.85rem; margin-top:.75rem; border-top:1px solid #1e293b;
  font-size:.9rem; color:#94a3b8; line-height:1.55;
}
.cg-human-line strong { color:#e2e8f0; font-weight:600; }

/* ── Copy button ────────────────────────────────────────── */
.cg-copy {
  display:inline-flex; align-items:center; gap:.3rem;
  padding:.45rem .95rem; border-radius:.75rem; font-size:.75rem; font-weight:700;
  cursor:pointer; transition:all .14s; white-space:nowrap;
  border:1.5px solid #334155; background:transparent; color:#94a3b8;
}
.cg-copy:hover { border-color:#60a5fa; color:#60a5fa; }
.cg-copy.cg-done { border-color:#4ade80; color:#4ade80; background:rgba(74,222,128,.08); }

/* ── Preset cards ───────────────────────────────────────── */
.cg-preset {
  padding:.55rem .85rem; border-radius:.875rem; cursor:pointer; text-align:left;
  border:1.5px solid #e2e8f0; background:#fff; transition:all .14s; display:block;
  width:100%;
}
.cg-preset:hover { border-color:#93c5fd; background:#eff6ff; transform:translateY(-1px); box-shadow:0 2px 8px rgba(59,130,246,.12); }
.cg-preset-expr {
  font-family:'JetBrains Mono','Fira Code','Courier New',monospace;
  font-size:.68rem; font-weight:800; color:#1e40af; display:block;
}
.cg-preset-label { font-size:.72rem; font-weight:600; color:#374151; display:block; margin-top:.18rem; }

/* ── Builder tabs ───────────────────────────────────────── */
.cg-tabs { display:flex; overflow-x:auto; border-bottom:1px solid #e5e7eb; background:#f8fafc; gap:0; }
.cg-tabs::-webkit-scrollbar { height:3px; }
.cg-tabs::-webkit-scrollbar-thumb { background:#c7d2fe; border-radius:9999px; }

.cg-tab {
  flex-shrink:0; padding:.6rem 1.1rem; font-size:.75rem; font-weight:700; cursor:pointer;
  border-bottom:2.5px solid transparent; color:#6b7280; transition:all .14s;
  white-space:nowrap; background:transparent;
}
.cg-tab:hover { color:#374151; border-color:#e5e7eb; }
.cg-tab-val { font-family:'JetBrains Mono','Fira Code',monospace; font-size:.68rem; }

.cg-tab[data-key="minute"].cg-on  { color:#1d4ed8; border-color:#3b82f6; background:#eff6ff; }
.cg-tab[data-key="hour"].cg-on    { color:#15803d; border-color:#22c55e; background:#f0fdf4; }
.cg-tab[data-key="dom"].cg-on     { color:#b45309; border-color:#f59e0b; background:#fffbeb; }
.cg-tab[data-key="month"].cg-on   { color:#7e22ce; border-color:#a855f7; background:#faf5ff; }
.cg-tab[data-key="dow"].cg-on     { color:#be185d; border-color:#ec4899; background:#fdf2f8; }

/* ── Mode selector ──────────────────────────────────────── */
.cg-modes { display:flex; }
.cg-mode {
  flex:1; padding:.4rem .5rem; font-size:.7rem; font-weight:700; text-align:center;
  cursor:pointer; transition:all .13s; background:#fff; color:#6b7280;
  border:1.5px solid #e2e8f0;
}
.cg-mode:not(:first-child) { border-left:none; }
.cg-mode:first-child { border-radius:.65rem 0 0 .65rem; }
.cg-mode:last-child  { border-radius:0 .65rem .65rem 0; }
.cg-mode:hover { background:#f1f5f9; color:#1e40af; }
.cg-mode.cg-on { background:#1e40af; color:#fff; border-color:#1e40af; position:relative; z-index:1; }

/* ── Value pills ────────────────────────────────────────── */
.cg-pill {
  width:2rem; height:2rem; border-radius:.45rem; font-size:.7rem; font-weight:700;
  cursor:pointer; display:inline-flex; align-items:center; justify-content:center;
  transition:all .11s; border:1.5px solid #e2e8f0;
  background:#f8fafc; color:#6b7280; flex-shrink:0;
}
.cg-pill:hover { border-color:#93c5fd; color:#1d4ed8; background:#eff6ff; }
.cg-pill.cg-on { background:#1e40af; color:#fff; border-color:#1e40af; }

.cg-named-pill {
  padding:.3rem .7rem; border-radius:.45rem; font-size:.73rem; font-weight:700;
  cursor:pointer; display:inline-flex; align-items:center; justify-content:center;
  transition:all .11s; border:1.5px solid #e2e8f0;
  background:#f8fafc; color:#6b7280;
}
.cg-named-pill:hover { border-color:#93c5fd; color:#1d4ed8; background:#eff6ff; }
.cg-named-pill.cg-on { background:#1e40af; color:#fff; border-color:#1e40af; }

/* ── Number inputs (range/step) ─────────────────────────── */
.cg-num {
  width:4.8rem; text-align:center; padding:.38rem .5rem;
  border:1.5px solid #e2e8f0; border-radius:.65rem; font-size:.82rem;
  font-weight:700; color:#374151; outline:none;
  font-family:'JetBrains Mono','Fira Code',monospace;
}
.cg-num:focus { border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.12); }

/* ── Field preview chip ─────────────────────────────────── */
.cg-preview-chip {
  font-family:'JetBrains Mono','Fira Code',monospace;
  font-size:.85rem; font-weight:800; padding:.2rem .7rem; border-radius:.45rem;
  background:#0f172a; color:#60a5fa; display:inline-block;
}

/* ── Validation error ────────────────────────────────────── */
.cg-ferr {
  display:flex; align-items:flex-start; gap:.4rem; padding:.5rem .8rem;
  background:#fef2f2; border:1.5px solid #fecaca; border-radius:.65rem;
  font-size:.75rem; color:#991b1b; margin-top:.75rem;
}

/* ── Import section ─────────────────────────────────────── */
.cg-import-input {
  font-family:'JetBrains Mono','Fira Code',monospace; font-size:.9rem;
}

/* ── Reference table ─────────────────────────────────────── */
.cg-ref { width:100%; border-collapse:collapse; font-size:.82rem; }
.cg-ref th { background:#f8fafc; padding:.45rem .75rem; font-size:.65rem; font-weight:800;
  text-transform:uppercase; letter-spacing:.07em; color:#64748b; text-align:left;
  border-bottom:1px solid #f3f4f6; }
.cg-ref td { padding:.45rem .75rem; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:middle; }
.cg-ref tr:last-child td { border-bottom:none; }
.cg-ref tr:hover td { background:#f8fafc; }
.cg-chip {
  font-family:'JetBrains Mono','Fira Code',monospace; font-size:.78rem; font-weight:800;
  background:#eff6ff; color:#1e40af; padding:.1rem .45rem; border-radius:.3rem;
  border:1px solid #bfdbfe; white-space:nowrap;
}

/* ── Divider ─────────────────────────────────────────────── */
.cg-div {
  display:flex; align-items:center; gap:.6rem;
  font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#6b7280;
}
.cg-div::before,.cg-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

@media(max-width:640px){
  .cg-field-val { font-size:1.1rem; }
  .cg-expr-code { font-size:1rem; }
  .cg-dark-card { padding:1rem; }
}
</style>

<div class="min-h-screen bg-gray-50" x-data="cgTool()">

  {{-- ── Header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            ⏱️ Cron Expression Generator
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Build cron expressions visually — choose a schedule for each field, then copy the result.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-5">

    {{-- ══ Expression display card ══ --}}
    <div class="cg-dark-card">

      {{-- 5 labeled field parts --}}
      <div class="flex flex-wrap items-end gap-x-4 gap-y-3">
        <template x-for="(key, idx) in fieldOrder" :key="key">
          <div class="flex items-end gap-3">
            <div class="cg-field-badge">
              <span :class="'cg-field-lbl cg-lbl-' + key" x-text="meta[key].label"></span>
              <span :class="'cg-field-val cg-' + key" x-text="parts[key]"></span>
            </div>
            <span x-show="idx < 4" class="cg-sep mb-0.5">·</span>
          </div>
        </template>
      </div>

      {{-- Full expression line --}}
      <div class="cg-expr-line">
        <span class="cg-expr-code" x-text="expression"></span>
        <button @click="copyExpr()" :class="['cg-copy', copied ? 'cg-done' : '']">
          <span x-text="copied ? '✓ Copied!' : '⎘ Copy'"></span>
        </button>
      </div>

      {{-- Human-readable --}}
      <div class="cg-human-line" x-text="humanReadable"></div>

    </div>

    {{-- ══ Quick presets ══ --}}
    <div class="card p-5">
      <p class="cg-div mb-4">Quick Presets</p>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
        <template x-for="p in presets" :key="p.expr">
          <button @click="loadPreset(p.expr)" class="cg-preset">
            <span class="cg-preset-expr" x-text="p.expr"></span>
            <span class="cg-preset-label" x-text="p.label"></span>
          </button>
        </template>
      </div>
    </div>

    {{-- ══ Visual builder ══ --}}
    <div class="card overflow-hidden">

      {{-- Tab bar --}}
      <div class="cg-tabs">
        <template x-for="key in fieldOrder" :key="'tab-' + key">
          <button
            :data-key="key"
            @click="activeTab = key"
            :class="['cg-tab', activeTab === key ? 'cg-on' : '']">
            <span x-text="meta[key].label"></span>
            <span class="cg-tab-val ml-1.5" x-text="'[' + parts[key] + ']'"></span>
          </button>
        </template>
      </div>

      {{-- Tab panels (all rendered, hidden via x-show) --}}
      <template x-for="key in fieldOrder" :key="'panel-' + key">
        <div x-show="activeTab === key" class="p-5 space-y-4">

          {{-- Hint --}}
          <p class="text-xs text-gray-500 leading-relaxed" x-text="meta[key].hint"></p>

          {{-- Mode selector --}}
          <div>
            <label class="form-label text-xs mb-2 block">Schedule Mode</label>
            <div class="cg-modes">
              <template x-for="m in modes" :key="m.id">
                <button
                  @click="f[key].mode = m.id"
                  :class="['cg-mode', f[key].mode === m.id ? 'cg-on' : '']"
                  x-text="m.label"
                ></button>
              </template>
            </div>
          </div>

          {{-- ── Mode: every ── --}}
          <div x-show="f[key].mode === 'every'">
            <p class="text-sm text-gray-500">
              Matches <em>every</em> valid value in this field.
              Generates&nbsp;<span class="cg-chip">*</span>.
            </p>
          </div>

          {{-- ── Mode: step ── --}}
          <div x-show="f[key].mode === 'step'" class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-gray-700">Every</span>
            <input type="number" x-model.number="f[key].step"
                   :min="1" :max="meta[key].max"
                   @input="f[key].step = Math.max(1, f[key].step || 1)"
                   class="cg-num">
            <span class="text-sm font-medium text-gray-700" x-text="meta[key].unit + (f[key].step > 1 ? 's' : '')"></span>
            <span class="text-gray-300">→</span>
            <span class="cg-preview-chip" x-text="'*/' + (f[key].step || 1)"></span>
          </div>

          {{-- ── Mode: specific ── --}}
          <div x-show="f[key].mode === 'specific'">
            {{-- Named pills (month / dow) --}}
            <div x-show="meta[key].names !== null" class="flex flex-wrap gap-1.5">
              <template x-for="(name, ni) in (meta[key].names || [])" :key="ni">
                <button
                  @click="toggleVal(key, meta[key].min + ni)"
                  :class="['cg-named-pill', hasVal(key, meta[key].min + ni) ? 'cg-on' : '']"
                  x-text="name"
                ></button>
              </template>
            </div>
            {{-- Numeric pills (minute / hour / dom) --}}
            <div x-show="meta[key].names === null" class="flex flex-wrap gap-1">
              <template x-for="v in numRange(meta[key].min, meta[key].max)" :key="v">
                <button
                  @click="toggleVal(key, v)"
                  :class="['cg-pill', hasVal(key, v) ? 'cg-on' : '']"
                  x-text="v"
                ></button>
              </template>
            </div>
            <p class="text-xs text-gray-400 mt-2" x-show="f[key].vals.length"
               x-text="f[key].vals.length + ' value' + (f[key].vals.length !== 1 ? 's' : '') + ' selected → ' + fieldVal(key)"></p>
            <div x-show="f[key].mode === 'specific' && !f[key].vals.length" class="cg-ferr">
              <span>⚠</span><span>Select at least one value.</span>
            </div>
          </div>

          {{-- ── Mode: range ── --}}
          <div x-show="f[key].mode === 'range'" class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-gray-700">From</span>
            <input type="number" x-model.number="f[key].from"
                   :min="meta[key].min" :max="meta[key].max" class="cg-num">
            <span class="text-sm font-medium text-gray-700">to</span>
            <input type="number" x-model.number="f[key].to"
                   :min="meta[key].min" :max="meta[key].max" class="cg-num">
            <span class="text-gray-300">→</span>
            <span class="cg-preview-chip" x-text="fieldVal(key)"></span>
            <div x-show="(f[key].from || 0) > (f[key].to || 0)" class="w-full">
              <div class="cg-ferr"><span>⚠</span><span>Start must be ≤ end.</span></div>
            </div>
          </div>

          {{-- ── Mode: range + step ── --}}
          <div x-show="f[key].mode === 'range_step'" class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-gray-700">From</span>
            <input type="number" x-model.number="f[key].from"
                   :min="meta[key].min" :max="meta[key].max" class="cg-num">
            <span class="text-sm font-medium text-gray-700">to</span>
            <input type="number" x-model.number="f[key].to"
                   :min="meta[key].min" :max="meta[key].max" class="cg-num">
            <span class="text-sm font-medium text-gray-700">every</span>
            <input type="number" x-model.number="f[key].fstep"
                   min="1" :max="meta[key].max" class="cg-num">
            <span class="text-sm font-medium text-gray-600" x-text="meta[key].unit + 's'"></span>
            <span class="text-gray-300">→</span>
            <span class="cg-preview-chip" x-text="fieldVal(key)"></span>
          </div>

          {{-- Current value --}}
          <div class="pt-1 flex items-center gap-2 text-xs text-gray-400">
            <span>Current value:</span>
            <span :class="'cg-chip font-mono font-bold cg-' + key" x-text="fieldVal(key)"></span>
          </div>

        </div>
      </template>

    </div>

    {{-- ══ Import raw expression ══ --}}
    <div class="card p-5">
      <p class="cg-div mb-4">Import Existing Expression</p>
      <p class="text-sm text-gray-500 mb-3">
        Paste any 5-field cron expression — or a special string like
        <code class="cg-chip">@daily</code>, <code class="cg-chip">@hourly</code>,
        <code class="cg-chip">@weekly</code>, <code class="cg-chip">@monthly</code> — to load it into the builder.
      </p>
      <div class="flex flex-col sm:flex-row gap-2">
        <input type="text" x-model="rawInput"
               @keydown.enter="importRaw()"
               placeholder="e.g. 30 8 * * 1-5"
               class="form-input flex-1 cg-import-input">
        <button @click="importRaw()" class="btn btn-primary">Import</button>
        <button @click="rawInput = ''; importError = ''" class="btn btn-secondary">Clear</button>
      </div>
      <div x-show="importError" x-transition class="cg-ferr mt-3">
        <span>⚠</span><span x-text="importError"></span>
      </div>
    </div>

    {{-- ══ Reference ══ --}}
    <div class="card p-5">
      <p class="cg-div mb-5">Cron Expression Reference</p>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Structure diagram --}}
        <div>
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Field Structure</p>
          <div class="font-mono text-xs bg-slate-900 text-slate-300 rounded-xl p-5 leading-[1.9]">
            <span class="text-blue-400 font-bold">┌─────────</span> minute &nbsp;<span class="text-slate-500">(0–59)</span><br>
            <span class="text-blue-400 font-bold">│ </span><span class="text-green-400 font-bold">┌───────</span> hour &nbsp;&nbsp;&nbsp;<span class="text-slate-500">(0–23)</span><br>
            <span class="text-blue-400 font-bold">│ </span><span class="text-green-400 font-bold">│ </span><span class="text-amber-400 font-bold">┌─────</span> day of month <span class="text-slate-500">(1–31)</span><br>
            <span class="text-blue-400 font-bold">│ </span><span class="text-green-400 font-bold">│ </span><span class="text-amber-400 font-bold">│ </span><span class="text-purple-400 font-bold">┌───</span> month &nbsp;&nbsp;<span class="text-slate-500">(1–12)</span><br>
            <span class="text-blue-400 font-bold">│ </span><span class="text-green-400 font-bold">│ </span><span class="text-amber-400 font-bold">│ </span><span class="text-purple-400 font-bold">│ </span><span class="text-pink-400 font-bold">┌─</span> day of week <span class="text-slate-500">(0–6, Sun=0)</span><br>
            <span class="text-blue-400 font-bold">│ </span><span class="text-green-400 font-bold">│ </span><span class="text-amber-400 font-bold">│ </span><span class="text-purple-400 font-bold">│ </span><span class="text-pink-400 font-bold">│</span><br>
            <span class="text-blue-400 font-bold">* </span><span class="text-green-400 font-bold">* </span><span class="text-amber-400 font-bold">* </span><span class="text-purple-400 font-bold">* </span><span class="text-pink-400 font-bold">*</span>
          </div>
        </div>

        {{-- Special characters --}}
        <div>
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Special Characters</p>
          <table class="cg-ref">
            <thead><tr><th>Symbol</th><th>Meaning</th><th>Example</th><th>Result</th></tr></thead>
            <tbody>
              <tr>
                <td><code class="cg-chip">*</code></td>
                <td>Every value</td>
                <td><code class="cg-chip">* * * * *</code></td>
                <td class="text-gray-500 text-xs">Every minute</td>
              </tr>
              <tr>
                <td><code class="cg-chip">,</code></td>
                <td>List of values</td>
                <td><code class="cg-chip">0,30 * * * *</code></td>
                <td class="text-gray-500 text-xs">At :00 and :30</td>
              </tr>
              <tr>
                <td><code class="cg-chip">-</code></td>
                <td>Range</td>
                <td><code class="cg-chip">* 9-17 * * *</code></td>
                <td class="text-gray-500 text-xs">Every minute, 9 AM–5 PM</td>
              </tr>
              <tr>
                <td><code class="cg-chip">/</code></td>
                <td>Step</td>
                <td><code class="cg-chip">*/15 * * * *</code></td>
                <td class="text-gray-500 text-xs">Every 15 minutes</td>
              </tr>
              <tr>
                <td><code class="cg-chip">*/N</code></td>
                <td>Every N units</td>
                <td><code class="cg-chip">0 */6 * * *</code></td>
                <td class="text-gray-500 text-xs">Every 6 hours</td>
              </tr>
              <tr>
                <td><code class="cg-chip">N-M/S</code></td>
                <td>Range with step</td>
                <td><code class="cg-chip">0 8-18/2 * * *</code></td>
                <td class="text-gray-500 text-xs">Every 2 hours, 8 AM–6 PM</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      {{-- Special strings --}}
      <div class="mt-6">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Special Strings (supported by most cron daemons)</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 text-center">
          <template x-for="s in specialStrings" :key="s.str">
            <div class="bg-slate-50 rounded-xl p-3 border border-gray-100">
              <code class="cg-chip text-xs" x-text="s.str"></code>
              <p class="text-xs text-gray-500 mt-1.5 font-medium" x-text="s.desc"></p>
              <p class="font-mono text-xs text-gray-400 mt-0.5" x-text="s.equiv"></p>
            </div>
          </template>
        </div>
      </div>

    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────────
   Cron Expression Generator — Alpine.js component
   CSS prefix: cg-

   Data model:
     f[key]  — field config: { mode, step, vals[], from, to, fstep }
     meta[key] — field metadata: { label, unit, min, max, names, hint }
     fieldOrder — ordered array of field keys

   5 schedule modes per field:
     every       → *
     step        → */N
     specific    → N,M,... (clickable pills)
     range       → N-M
     range_step  → N-M/S

   humanReadable:
     Converts the 5-field expression to natural English, handling
     all mode combinations. Produces readable output for common
     and uncommon patterns alike.
─────────────────────────────────────────────────────────────────── */
function cgTool() {
  return {

    // ── Field state ─────────────────────────────────────
    f: {
      minute: { mode:'every', step:5,  vals:[], from:0, to:30, fstep:1 },
      hour:   { mode:'every', step:4,  vals:[], from:8, to:17, fstep:1 },
      dom:    { mode:'every', step:5,  vals:[], from:1, to:15, fstep:1 },
      month:  { mode:'every', step:3,  vals:[], from:1, to:6,  fstep:1 },
      dow:    { mode:'every', step:1,  vals:[], from:1, to:5,  fstep:1 },
    },

    fieldOrder: ['minute','hour','dom','month','dow'],
    activeTab:  'minute',
    rawInput:   '',
    importError:'',
    copied:     false,

    meta: {
      minute: {
        label:'Minute', unit:'minute', min:0, max:59, names:null,
        hint:'Which minute(s) the job fires within each hour. 0 = start of the hour, 59 = last minute. Use "Every" to fire every minute.',
      },
      hour: {
        label:'Hour', unit:'hour', min:0, max:23, names:null,
        hint:'Which hour(s) the job fires (24-hour clock). 0 = midnight, 12 = noon, 23 = 11 PM. Combine with a specific Minute to get an exact time.',
      },
      dom: {
        label:'Day of Month', unit:'day', min:1, max:31, names:null,
        hint:'Which day(s) of the month the job fires (1–31). Note: if both Day of Month and Day of Week are set, most systems fire when either condition is true.',
      },
      month: {
        label:'Month', unit:'month', min:1, max:12,
        names:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        hint:'Which month(s) the job is active (1=January … 12=December). Useful for quarterly or seasonal tasks.',
      },
      dow: {
        label:'Day of Week', unit:'day', min:0, max:6,
        names:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
        hint:'Which day(s) of the week the job fires. 0 and 7 both mean Sunday on most systems. "1-5" covers Monday–Friday.',
      },
    },

    modes: [
      { id:'every',      label:'Every'        },
      { id:'step',       label:'Every N'      },
      { id:'specific',   label:'Specific'     },
      { id:'range',      label:'Range'        },
      { id:'range_step', label:'Range + Step' },
    ],

    presets: [
      { expr:'* * * * *',    label:'Every minute'        },
      { expr:'*/5 * * * *',  label:'Every 5 minutes'     },
      { expr:'*/15 * * * *', label:'Every 15 minutes'    },
      { expr:'*/30 * * * *', label:'Every 30 minutes'    },
      { expr:'0 * * * *',    label:'Every hour'          },
      { expr:'0 */2 * * *',  label:'Every 2 hours'       },
      { expr:'0 0 * * *',    label:'Daily at midnight'   },
      { expr:'0 12 * * *',   label:'Daily at noon'       },
      { expr:'0 9 * * 1-5',  label:'Weekdays at 9 AM'   },
      { expr:'30 8 * * 1',   label:'Monday 8:30 AM'      },
      { expr:'0 6,18 * * *', label:'6 AM & 6 PM'         },
      { expr:'0 0 1 * *',    label:'Monthly on 1st'      },
      { expr:'0 0 1 1 *',    label:'Yearly on Jan 1st'   },
      { expr:'0 0 * * 0',    label:'Every Sunday'        },
      { expr:'*/10 9-17 * * 1-5', label:'Every 10 min, business hours' },
    ],

    specialStrings: [
      { str:'@reboot',   desc:'On startup',       equiv:'(not standard)' },
      { str:'@yearly',   desc:'Once a year',      equiv:'0 0 1 1 *'      },
      { str:'@monthly',  desc:'Once a month',     equiv:'0 0 1 * *'      },
      { str:'@weekly',   desc:'Once a week',      equiv:'0 0 * * 0'      },
      { str:'@daily',    desc:'Once a day',       equiv:'0 0 * * *'      },
      { str:'@hourly',   desc:'Once an hour',     equiv:'0 * * * *'      },
    ],

    // ── Computed: field values ───────────────────────────
    fieldVal(key) {
      var cfg = this.f[key];
      var m   = this.meta[key];
      switch (cfg.mode) {
        case 'every':
          return '*';
        case 'step':
          return '*/' + Math.max(1, cfg.step || 1);
        case 'specific':
          if (!cfg.vals.length) return '*';
          return cfg.vals.slice().sort(function(a,b){return a-b;}).join(',');
        case 'range': {
          var f = Math.max(m.min, Math.min(m.max, cfg.from != null ? cfg.from : m.min));
          var t = Math.min(m.max, Math.max(f, cfg.to   != null ? cfg.to   : m.max));
          return f + '-' + t;
        }
        case 'range_step': {
          var f2 = Math.max(m.min, Math.min(m.max, cfg.from  != null ? cfg.from  : m.min));
          var t2 = Math.min(m.max, Math.max(f2, cfg.to    != null ? cfg.to    : m.max));
          var s  = Math.max(1, cfg.fstep || 1);
          return f2 + '-' + t2 + '/' + s;
        }
      }
      return '*';
    },

    get parts() {
      var self = this;
      var r = {};
      this.fieldOrder.forEach(function(k){ r[k] = self.fieldVal(k); });
      return r;
    },

    get expression() {
      var p = this.parts;
      return p.minute + ' ' + p.hour + ' ' + p.dom + ' ' + p.month + ' ' + p.dow;
    },

    // ── Human-readable description ───────────────────────
    get humanReadable() {
      try {
        var e = this.expression.split(' ');
        return this._desc(e[0], e[1], e[2], e[3], e[4]);
      } catch(err) {
        return 'Unable to describe this expression.';
      }
    },

    _desc(min, hr, dom, mon, dow) {
      if (min==='*' && hr==='*' && dom==='*' && mon==='*' && dow==='*')
        return 'Every minute of every hour, every day.';

      var out = [];

      // ── Time part ──
      if (min === '*' && hr === '*') {
        out.push('every minute');
      } else if (hr === '*') {
        var mp = this._parse(min);
        if      (mp.type==='step' && mp.n===1) out.push('every minute');
        else if (mp.type==='step')             out.push('every ' + mp.n + ' minutes');
        else if (mp.type==='single')           out.push('at minute ' + mp.v + ' of every hour');
        else                                   out.push('at minute ' + this._dfld(min,'minute') + ' of every hour');
      } else if (min === '*') {
        out.push('every minute of ' + this._dfld(hr,'hour'));
      } else {
        var mp2 = this._parse(min), hp = this._parse(hr);
        if (mp2.type === 'single' && hp.type === 'single') {
          out.push('at ' + String(hp.v).padStart(2,'0') + ':' + String(mp2.v).padStart(2,'0'));
        } else {
          out.push('at minute ' + this._dfld(min,'minute') + ' past ' + this._dfld(hr,'hour'));
        }
      }

      // ── Day part ──
      if (dow !== '*' && dom !== '*') {
        out.push('on ' + this._dfld(dow,'dow') + ' and day ' + this._dfld(dom,'dom') + ' of the month');
      } else if (dow !== '*') {
        out.push('on ' + this._dfld(dow,'dow'));
      } else if (dom !== '*') {
        out.push('on day ' + this._dfld(dom,'dom') + ' of the month');
      }

      // ── Month part ──
      if (mon !== '*') out.push('in ' + this._dfld(mon,'month'));

      if (!out.length) return 'No schedule defined.';
      var s = out[0][0].toUpperCase() + out[0].slice(1);
      if (out.length > 1) s += ', ' + out.slice(1).join(', ');
      return s + '.';
    },

    _parse(val) {
      if (val === '*')               return { type:'all' };
      if (/^\d+$/.test(val))         return { type:'single', v:+val };
      var s  = val.match(/^\*\/(\d+)$/);
      if (s)                         return { type:'step', n:+s[1] };
      var r  = val.match(/^(\d+)-(\d+)$/);
      if (r)                         return { type:'range', a:+r[1], b:+r[2] };
      var rs = val.match(/^(\d+)-(\d+)\/(\d+)$/);
      if (rs)                        return { type:'range_step', a:+rs[1], b:+rs[2], n:+rs[3] };
      var ls = val.split(',');
      if (ls.every(function(p){return /^\d+$/.test(p);})) return { type:'list', vs:ls.map(Number) };
      return { type:'raw', raw:val };
    },

    _dfld(val, type) {
      var MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      var DOWS   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
      var p = this._parse(val);
      var self = this;

      var name = function(type, n) {
        if (type==='month') return MONTHS[n-1] || n;
        if (type==='dow')   return DOWS[n]     || n;
        if (type==='hour')  return n + ':00';
        return n;
      };

      var uSg = { minute:'minute', hour:'hour', dom:'day', month:'month', dow:'weekday' }[type] || type;
      var uPl = { minute:'minutes', hour:'hours', dom:'days', month:'months', dow:'weekdays' }[type] || type + 's';

      if (p.type==='all')    return 'every ' + uSg;
      if (p.type==='single') return String(name(type, p.v));
      if (p.type==='step')   return 'every ' + (p.n===1 ? uSg : p.n + ' ' + uPl);
      if (p.type==='range')  return name(type,p.a) + ' through ' + name(type,p.b);
      if (p.type==='range_step') return 'every ' + p.n + ' ' + uPl + ' from ' + name(type,p.a) + ' through ' + name(type,p.b);
      if (p.type==='list') {
        var ns = p.vs.map(function(v){ return String(name(type,v)); });
        if (ns.length===1) return ns[0];
        if (ns.length===2) return ns[0] + ' and ' + ns[1];
        return ns.slice(0,-1).join(', ') + ', and ' + ns[ns.length-1];
      }
      return val;
    },

    // ── Helpers ──────────────────────────────────────────
    numRange(min, max) {
      var r = [];
      for (var i = min; i <= max; i++) r.push(i);
      return r;
    },

    toggleVal(key, v) {
      var arr = this.f[key].vals;
      var idx = arr.indexOf(v);
      if (idx >= 0) {
        this.f[key].vals = arr.filter(function(x){ return x !== v; });
      } else {
        this.f[key].vals = arr.concat([v]);
      }
    },

    hasVal(key, v) {
      return this.f[key].vals.indexOf(v) >= 0;
    },

    copyExpr() {
      var expr = this.expression;
      var self = this;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(expr).then(function() {
          self.copied = true;
          setTimeout(function(){ self.copied = false; }, 2200);
        });
      } else {
        var el = document.createElement('textarea');
        el.value = expr;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        self.copied = true;
        setTimeout(function(){ self.copied = false; }, 2200);
      }
    },

    loadPreset(expr) {
      this._applyExpr(expr);
    },

    importRaw() {
      this.importError = '';
      var raw = this.rawInput.trim();
      if (!raw) { this.importError = 'Please enter a cron expression.'; return; }

      // Special strings
      var specials = {
        '@yearly':  '0 0 1 1 *', '@annually':'0 0 1 1 *',
        '@monthly': '0 0 1 * *', '@weekly':  '0 0 * * 0',
        '@daily':   '0 0 * * *', '@midnight':'0 0 * * *',
        '@hourly':  '0 * * * *',
      };
      if (specials[raw.toLowerCase()]) raw = specials[raw.toLowerCase()];

      if (this._applyExpr(raw)) this.rawInput = '';
    },

    _applyExpr(expr) {
      var parts = expr.trim().split(/\s+/);
      if (parts.length !== 5) {
        this.importError = 'Expected 5 fields separated by spaces. Got ' + parts.length + '.';
        return false;
      }
      var keys = ['minute','hour','dom','month','dow'];
      for (var i = 0; i < 5; i++) {
        var r = this._parseFieldStr(parts[i], keys[i]);
        if (r.error) {
          this.importError = this.meta[keys[i]].label + ': ' + r.error;
          return false;
        }
        var cfg = this.f[keys[i]];
        cfg.mode = r.mode;
        if (r.step  !== undefined) cfg.step  = r.step;
        if (r.vals  !== undefined) cfg.vals  = r.vals;
        if (r.from  !== undefined) cfg.from  = r.from;
        if (r.to    !== undefined) cfg.to    = r.to;
        if (r.fstep !== undefined) cfg.fstep = r.fstep;
      }
      return true;
    },

    _parseFieldStr(val, key) {
      var m = this.meta[key];
      if (val === '*') return { mode:'every' };

      var s = val.match(/^\*\/(\d+)$/);
      if (s) {
        var n = parseInt(s[1]);
        if (n < 1) return { error: 'Step must be ≥ 1.' };
        return { mode:'step', step:n };
      }

      var rs = val.match(/^(\d+)-(\d+)\/(\d+)$/);
      if (rs) {
        var f = parseInt(rs[1]), t = parseInt(rs[2]), st = parseInt(rs[3]);
        if (f < m.min || t > m.max) return { error:'Value out of range (' + m.min + '-' + m.max + ').' };
        if (f > t) return { error:'Range start > end.' };
        return { mode:'range_step', from:f, to:t, fstep:st };
      }

      var r = val.match(/^(\d+)-(\d+)$/);
      if (r) {
        var fa = parseInt(r[1]), tb = parseInt(r[2]);
        if (fa < m.min || tb > m.max) return { error:'Value out of range (' + m.min + '-' + m.max + ').' };
        if (fa > tb) return { error:'Range start > end.' };
        return { mode:'range', from:fa, to:tb };
      }

      var ls = val.split(',');
      if (ls.every(function(p){ return /^\d+$/.test(p); })) {
        var nums = ls.map(Number);
        for (var i = 0; i < nums.length; i++) {
          if (nums[i] < m.min || nums[i] > m.max)
            return { error:'Value ' + nums[i] + ' is out of range (' + m.min + '-' + m.max + ').' };
        }
        return { mode:'specific', vals:nums };
      }

      return { error:'Unrecognized pattern "' + val + '".' };
    },

  };
}
</script>
@endpush
