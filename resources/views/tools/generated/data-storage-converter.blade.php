@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between bits, bytes, KB, MB, GB, TB, PB and KiB, MiB, GiB, TiB, PiB. Free instant data storage converter.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Data Storage Converter  —  prefix: ds-
   Theme: Pink (#9d174d / #be185d / #db2777)
   Base unit: bit (b)
══════════════════════════════════════════════ */

/* Styled select */
.ds-select-wrap { position: relative; }
.ds-select-wrap select {
  appearance: none; -webkit-appearance: none;
  padding: .65rem 2.5rem .65rem 1rem;
  border: 1.5px solid #fbcfe8; border-radius: .875rem;
  background: #fff; color: #500724; font-weight: 700; font-size: .9rem;
  width: 100%; cursor: pointer; transition: border-color .15s, box-shadow .15s;
}
.ds-select-wrap select:focus { outline: none; border-color: #db2777; box-shadow: 0 0 0 3px rgba(219,39,119,.15); }
.ds-select-wrap::after {
  content: '▾'; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
  color: #be185d; font-size: .75rem; pointer-events: none; font-weight: 900;
}

/* Swap */
.ds-swap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2.6rem; height: 2.6rem; border-radius: 9999px;
  border: 2px solid #fbcfe8; background: #fff; color: #be185d;
  font-size: 1.2rem; cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.ds-swap-btn:hover { background: #be185d; color: #fff; border-color: #be185d; transform: rotate(180deg); }

/* Hero */
.ds-hero {
  background: linear-gradient(135deg, #500724, #9d174d, #be185d);
  border-radius: 1.5rem; padding: 1.75rem; color: #fff;
}
.ds-hero .ds-hv { font-size: clamp(1.8rem, 4.5vw, 3rem); font-weight: 900; line-height: 1.1; letter-spacing: -.03em; word-break: break-all; }
.ds-hero .ds-hu { font-size: 1rem; font-weight: 700; color: #fbcfe8; margin-top: .25rem; }

/* Copy button */
.ds-copy-btn {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .45rem .9rem; border-radius: .625rem; font-size: .72rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .06em; cursor: pointer; transition: all .2s;
  border: 1.5px solid rgba(255,255,255,.35); background: rgba(255,255,255,.12); color: #fff;
}
.ds-copy-btn:hover { background: rgba(255,255,255,.25); border-color: rgba(255,255,255,.6); }
.ds-copy-btn.ds-copied { background: rgba(255,255,255,.25); border-color: #a7f3d0; color: #a7f3d0; }

/* Unit card */
.ds-unit-card {
  border: 1.5px solid #fbcfe8; border-radius: 1rem;
  background: #fff; padding: .7rem .85rem;
  cursor: pointer; transition: all .18s;
  display: flex; flex-direction: column; gap: .15rem;
}
.ds-unit-card:hover { border-color: #db2777; box-shadow: 0 4px 16px rgba(219,39,119,.1); transform: translateY(-1px); }
.ds-unit-card.ds-src { background: #fdf2f8; border-color: #db2777; border-width: 2px; }
.ds-unit-card.ds-dst {
  background: linear-gradient(135deg, #fdf2f8, #fce7f3);
  border-color: #9d174d; border-width: 2px;
  box-shadow: 0 4px 18px rgba(157,23,77,.15);
}
.ds-unit-card .ds-uc-sym  { font-size: .62rem; font-weight: 800; color: #be185d; text-transform: uppercase; letter-spacing: .1em; }
.ds-unit-card .ds-uc-name { font-size: .64rem; color: #94a3b8; font-weight: 600; }
.ds-unit-card .ds-uc-val  { font-size: .94rem; font-weight: 900; color: #500724; word-break: break-all; line-height: 1.2; }
.ds-unit-card.ds-dst .ds-uc-val { color: #9d174d; }
.ds-unit-card .ds-uc-base { font-size: .58rem; color: #db2777; font-weight: 700; }
.ds-unit-card .ds-uc-hint { font-size: .56rem; color: #be185d; font-weight: 700; opacity: 0; transition: opacity .15s; }
.ds-unit-card:hover .ds-uc-hint { opacity: 1; }

/* Group label */
.ds-grp { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; }
.ds-grp.si  { color: #be185d; }
.ds-grp.iec { color: #7e22ce; }
.ds-grp.base{ color: #0369a1; }

/* SI/IEC comparison row */
.ds-cmp-row {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: .5rem; padding: .5rem .75rem; border-radius: .6rem; font-size: .78rem;
}
.ds-cmp-row:hover { background: #fdf2f8; }
.ds-cmp-row.ds-cmp-head { font-size: .58rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }

/* Preset pill */
.ds-preset {
  padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
  background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8;
  cursor: pointer; white-space: nowrap; transition: all .15s;
}
.ds-preset:hover { background: #be185d; color: #fff; border-color: #be185d; }

/* Divider */
.ds-div {
  display: flex; align-items: center; gap: .6rem;
  color: #db2777; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.ds-div::before, .ds-div::after { content: ''; flex: 1; height: 1px; background: #fbcfe8; }

/* Scale badge */
.ds-scale {
  background: linear-gradient(135deg, #fdf2f8, #fce7f3);
  border: 1.5px solid #fbcfe8; border-radius: 1rem; padding: .7rem 1rem;
  display: flex; align-items: center; gap: .75rem; font-size: .82rem; color: #9d174d;
}

/* Formula code */
.ds-code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  background: #fdf2f8; color: #9d174d; padding: .15rem .45rem;
  border-radius: .35rem; font-size: .72rem; font-weight: 600;
}

@keyframes dsIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.ds-in { animation: dsIn .28s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="dsCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #500724, #be185d);">
        <span class="text-3xl">💾</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Data Storage Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between bits, bytes, SI units (KB/MB/GB) and IEC binary units (KiB/MiB/GiB).
        Click any result card to use it as the new input.
      </p>
    </div>

    {{-- Error --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-base flex-shrink-0">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ══════════ LEFT ══════════ --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Input card --}}
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span>💾</span> Enter Value
          </h2>

          <div>
            <label class="form-label">Data Size</label>
            <input
              type="number"
              step="any"
              min="0"
              placeholder="e.g. 500"
              x-model="value"
              @input.debounce.350ms="autoConvert()"
              class="form-input w-full text-lg font-semibold"
              autofocus
            />
          </div>

          <div>
            <label class="form-label">From Unit</label>
            <div class="ds-select-wrap">
              <select x-model="fromUnit" @change="autoConvert()">
                <template x-for="grp in unitGroups" :key="grp.label">
                  <optgroup :label="grp.label">
                    <template x-for="u in grp.units" :key="u.id">
                      <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
                    </template>
                  </optgroup>
                </template>
              </select>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-pink-100"></div>
            <button @click="swap()" class="ds-swap-btn" title="Swap units">⇅</button>
            <div class="flex-1 h-px bg-pink-100"></div>
          </div>

          <div>
            <label class="form-label">To Unit</label>
            <div class="ds-select-wrap">
              <select x-model="toUnit" @change="autoConvert()">
                <template x-for="grp in unitGroups" :key="grp.label">
                  <optgroup :label="grp.label">
                    <template x-for="u in grp.units" :key="u.id">
                      <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
                    </template>
                  </optgroup>
                </template>
              </select>
            </div>
          </div>
        </div>

        {{-- Presets --}}
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2.5">Quick Presets</p>
          <div class="flex flex-wrap gap-1.5">
            <template x-for="p in presets" :key="p.label">
              <button @click="applyPreset(p)" class="ds-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
          <button @click="doConvert()"
                  class="btn btn-primary flex-1 py-3 text-base font-bold"
                  style="background: linear-gradient(135deg, #500724, #be185d);">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Info card --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1.5"
             style="background: linear-gradient(135deg,#fdf2f8,#fce7f3); border-color:#fbcfe8;">
          <p class="font-bold text-pink-800 uppercase tracking-wide text-xs">💡 SI vs IEC</p>
          <p><strong class="text-gray-700">SI (Decimal):</strong> 1 KB = 1,000 bytes (hard drives, SSDs, network)</p>
          <p><strong class="text-gray-700">IEC (Binary):</strong> 1 KiB = 1,024 bytes (RAM, OS file sizes)</p>
          <p class="pt-1 border-t border-pink-200 text-pink-700 font-medium">⚠️ A "500 GB" hard drive = ~465 GiB — this is why your OS shows less space!</p>
        </div>

      </div>

      {{-- ══════════ RIGHT ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">💾</div>
          <p class="font-medium text-gray-500">Enter a data size value above</p>
          <p class="text-sm mt-1">Conversions across all 12 units will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-pink-200 border-t-pink-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        <template x-if="phase === 'done'">
          <div class="space-y-5 ds-in">

            {{-- Hero --}}
            <div class="ds-hero">
              <p class="text-pink-200 text-xs font-bold uppercase tracking-widest mb-3">Result</p>
              <div class="flex items-start gap-3 flex-wrap">
                <div class="flex-1 min-w-0">
                  <div class="text-pink-200 text-sm font-semibold mb-1">
                    <span x-text="result.fromDisplay"></span>
                    <span class="text-pink-300 mx-2 text-xl">→</span>
                    <span x-text="result.toName"></span>
                  </div>
                  <div class="ds-hv" x-text="result.primaryVal"></div>
                  <div class="ds-hu" x-text="result.toFull"></div>
                </div>
                <div class="flex flex-col gap-2 flex-shrink-0">
                  <button @click="copyResult()" :class="['ds-copy-btn', copied ? 'ds-copied' : '']">
                    <span x-text="copied ? '✓ Copied' : '⎘ Copy'"></span>
                  </button>
                  <button @click="swap()"
                          class="text-pink-200 hover:text-white transition-colors text-xs font-bold uppercase tracking-wide border border-pink-600 hover:border-pink-400 rounded-lg px-3 py-1.5">
                    ⇅ Swap
                  </button>
                </div>
              </div>
              <div class="mt-3 pt-3 border-t border-pink-800 text-xs text-pink-200 font-mono"
                   x-text="result.equationStr"></div>
            </div>

            {{-- All-units grid --}}
            <div class="card p-5 space-y-4">
              <p class="ds-div">All Units</p>

              {{-- Base --}}
              <div>
                <p class="ds-grp base mb-2">Base Units</p>
                <div class="grid grid-cols-2 gap-2">
                  <template x-for="card in result.baseCards" :key="card.id">
                    <button @click="setToUnit(card.id)" class="ds-unit-card text-left"
                            :class="card.isSrc ? 'ds-src' : (card.isDst ? 'ds-dst' : '')">
                      <div class="flex items-center justify-between">
                        <span class="ds-uc-sym" x-text="card.sym"></span>
                        <span x-show="card.isDst" class="text-xs text-pink-600 font-bold">← result</span>
                        <span x-show="card.isSrc" class="text-xs text-pink-400 font-bold">input</span>
                      </div>
                      <div class="ds-uc-val" x-text="card.display"></div>
                      <div class="ds-uc-name" x-text="card.name"></div>
                      <div class="ds-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                    </button>
                  </template>
                </div>
              </div>

              {{-- SI --}}
              <div>
                <p class="ds-grp si mb-2">SI — Decimal (powers of 1,000)</p>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                  <template x-for="card in result.siCards" :key="card.id">
                    <button @click="setToUnit(card.id)" class="ds-unit-card text-left"
                            :class="card.isSrc ? 'ds-src' : (card.isDst ? 'ds-dst' : '')">
                      <div class="flex items-center justify-between">
                        <span class="ds-uc-sym" x-text="card.sym"></span>
                        <span x-show="card.isDst" class="text-xs text-pink-600 font-bold">← result</span>
                        <span x-show="card.isSrc" class="text-xs text-pink-400 font-bold">input</span>
                      </div>
                      <div class="ds-uc-val" x-text="card.display"></div>
                      <div class="ds-uc-name" x-text="card.name"></div>
                      <div class="ds-uc-base" x-text="card.base"></div>
                      <div class="ds-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                    </button>
                  </template>
                </div>
              </div>

              {{-- IEC --}}
              <div>
                <p class="ds-grp iec mb-2">IEC — Binary (powers of 1,024)</p>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                  <template x-for="card in result.iecCards" :key="card.id">
                    <button @click="setToUnit(card.id)" class="ds-unit-card text-left"
                            :class="card.isSrc ? 'ds-src' : (card.isDst ? 'ds-dst' : '')">
                      <div class="flex items-center justify-between">
                        <span class="ds-uc-sym" x-text="card.sym"></span>
                        <span x-show="card.isDst" class="text-xs text-pink-600 font-bold">← result</span>
                        <span x-show="card.isSrc" class="text-xs text-pink-400 font-bold">input</span>
                      </div>
                      <div class="ds-uc-val" x-text="card.display"></div>
                      <div class="ds-uc-name" x-text="card.name"></div>
                      <div class="ds-uc-base" x-text="card.base"></div>
                      <div class="ds-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                    </button>
                  </template>
                </div>
              </div>
              <p class="text-xs text-gray-400">Click any card to update the "To unit" and recalculate.</p>
            </div>

            {{-- SI vs IEC comparison --}}
            <div class="card p-5" x-show="result.siVsIec">
              <p class="ds-div mb-3">SI vs IEC Comparison for Your Value</p>
              <p class="text-xs text-gray-500 mb-2.5">
                The same data expressed in both systems — this explains the "missing space" on hard drives.
              </p>
              <div class="ds-cmp-row ds-cmp-head">
                <span>SI (Decimal, ×1000)</span>
                <span>IEC (Binary, ×1024)</span>
              </div>
              <template x-for="row in result.siVsIec" :key="row.si + row.iec">
                <div class="ds-cmp-row">
                  <span class="font-semibold text-pink-700" x-text="row.si"></span>
                  <span class="font-semibold text-purple-700" x-text="row.iec"></span>
                </div>
              </template>
            </div>

            {{-- Context --}}
            <div class="ds-scale">
              <span class="text-2xl flex-shrink-0" x-text="result.scale.icon"></span>
              <div>
                <p class="font-bold text-pink-800 text-sm">In perspective</p>
                <p class="text-pink-700 text-xs mt-0.5" x-text="result.scale.note"></p>
              </div>
            </div>

            {{-- Formula --}}
            <div class="card p-5">
              <p class="ds-div mb-3">Conversion Details</p>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-pink-50 text-sm">
                  <span class="text-gray-600 font-medium" x-text="result.formula.label"></span>
                  <code class="ds-code" x-text="result.formula.expr"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-pink-50 text-sm">
                  <span class="text-gray-600 font-medium">Via bits (base unit)</span>
                  <code class="ds-code" x-text="result.formula.via"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-pink-50 text-sm font-semibold text-pink-800">
                  <span x-text="result.formula.calcLabel"></span>
                  <span x-text="result.formula.calcVal"></span>
                </div>
              </div>
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
   Data Storage Converter — Alpine.js component
   CSS prefix: ds-   Theme: Pink / Fuchsia
   Base unit: bit (b)

   SI (Decimal — powers of 1,000):
     1 KB  = 1,000 bytes  = 8,000 bits
     1 MB  = 1,000,000 bytes
     1 GB  = 1,000,000,000 bytes
     1 TB  = 1,000,000,000,000 bytes
     1 PB  = 1,000,000,000,000,000 bytes

   IEC (Binary — powers of 1,024):
     1 KiB = 1,024 bytes    = 8,192 bits
     1 MiB = 1,048,576 bytes
     1 GiB = 1,073,741,824 bytes
     1 TiB = 1,099,511,627,776 bytes
     1 PiB = 1,125,899,906,842,624 bytes
──────────────────────────────────────────────────────────── */
function dsCalc() {
  return {

    value:    '',
    fromUnit: 'GB',
    toUnit:   'GiB',

    phase:    'idle',
    errorMsg: '',
    result:   null,
    copied:   false,

    // ── Unit definitions (toBits = bits per unit) ──
    _units: [
      // Base
      { id: 'b',   sym: 'b',   name: 'Bit',        toBits: 1,                      group: 'base', base: '1 bit' },
      { id: 'B',   sym: 'B',   name: 'Byte',        toBits: 8,                      group: 'base', base: '8 bits' },
      // SI decimal
      { id: 'KB',  sym: 'KB',  name: 'Kilobyte',    toBits: 8e3,                    group: 'si',   base: '10³ bytes' },
      { id: 'MB',  sym: 'MB',  name: 'Megabyte',    toBits: 8e6,                    group: 'si',   base: '10⁶ bytes' },
      { id: 'GB',  sym: 'GB',  name: 'Gigabyte',    toBits: 8e9,                    group: 'si',   base: '10⁹ bytes' },
      { id: 'TB',  sym: 'TB',  name: 'Terabyte',    toBits: 8e12,                   group: 'si',   base: '10¹² bytes' },
      { id: 'PB',  sym: 'PB',  name: 'Petabyte',    toBits: 8e15,                   group: 'si',   base: '10¹⁵ bytes' },
      // IEC binary
      { id: 'KiB', sym: 'KiB', name: 'Kibibyte',    toBits: 8 * 1024,               group: 'iec',  base: '2¹⁰ bytes' },
      { id: 'MiB', sym: 'MiB', name: 'Mebibyte',    toBits: 8 * 1048576,            group: 'iec',  base: '2²⁰ bytes' },
      { id: 'GiB', sym: 'GiB', name: 'Gibibyte',    toBits: 8 * 1073741824,         group: 'iec',  base: '2³⁰ bytes' },
      { id: 'TiB', sym: 'TiB', name: 'Tebibyte',    toBits: 8 * 1099511627776,      group: 'iec',  base: '2⁴⁰ bytes' },
      { id: 'PiB', sym: 'PiB', name: 'Pebibyte',    toBits: 8 * 1125899906842624,   group: 'iec',  base: '2⁵⁰ bytes' },
    ],

    unitGroups: [],

    presets: [
      { label: '500 GB → GiB',   v: 500,   from: 'GB',  to: 'GiB' },
      { label: '1 GB → MB',      v: 1,     from: 'GB',  to: 'MB'  },
      { label: '8 GB RAM → GiB', v: 8,     from: 'GB',  to: 'GiB' },
      { label: '1 GiB → GB',     v: 1,     from: 'GiB', to: 'GB'  },
      { label: '1 TB → TiB',     v: 1,     from: 'TB',  to: 'TiB' },
      { label: '1 MiB → MB',     v: 1,     from: 'MiB', to: 'MB'  },
      { label: '100 MB → MiB',   v: 100,   from: 'MB',  to: 'MiB' },
      { label: '1 PB → PiB',     v: 1,     from: 'PB',  to: 'PiB' },
    ],

    // ── Lifecycle ─────────────────────────────────
    init() {
      this.unitGroups = [
        { label: 'Base Units',              units: this._units.filter(function(u){ return u.group==='base'; }) },
        { label: 'SI — Decimal (×1,000)',   units: this._units.filter(function(u){ return u.group==='si'; }) },
        { label: 'IEC — Binary (×1,024)',   units: this._units.filter(function(u){ return u.group==='iec'; }) },
      ];
    },

    // ── Actions ───────────────────────────────────
    applyPreset(p) {
      this.value    = String(p.v);
      this.fromUnit = p.from;
      this.toUnit   = p.to;
      this.doConvert();
    },

    swap() {
      var tmp       = this.fromUnit;
      this.fromUnit = this.toUnit;
      this.toUnit   = tmp;
      if (this.phase === 'done') this.doConvert();
    },

    setToUnit(id) {
      if (id === this.fromUnit) return;
      this.toUnit = id;
      if (this.phase === 'done') this.doConvert();
    },

    reset() {
      this.value    = '';
      this.fromUnit = 'GB';
      this.toUnit   = 'GiB';
      this.phase    = 'idle';
      this.errorMsg = '';
      this.result   = null;
      this.copied   = false;
    },

    autoConvert() {
      if (this.value !== '' && !isNaN(parseFloat(this.value))) this.doConvert();
    },

    copyResult() {
      if (!this.result) return;
      var text = this.result.primaryVal + ' ' + this.result.toFull;
      var self = this;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () {
          self.copied = true;
          setTimeout(function () { self.copied = false; }, 2000);
        }).catch(function () { self._fallbackCopy(text); });
      } else {
        self._fallbackCopy(text);
      }
    },

    _fallbackCopy(text) {
      var el = document.createElement('textarea');
      el.value = text;
      el.style.position = 'fixed'; el.style.opacity = '0';
      document.body.appendChild(el);
      el.focus(); el.select();
      try { document.execCommand('copy'); this.copied = true; setTimeout(function(){ this.copied = false; }.bind(this), 2000); }
      catch(e) {}
      document.body.removeChild(el);
    },

    doConvert() {
      this.errorMsg = '';

      if (this.value === '' || this.value === null) {
        this.errorMsg = 'Please enter a data size value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid number (e.g. 500 or 1.5).';
        return;
      }
      if (!isFinite(v)) {
        this.errorMsg = 'Please enter a finite number.';
        return;
      }
      if (v < 0) {
        this.errorMsg = 'Data size cannot be negative. Please enter a positive value.';
        return;
      }

      this.phase = 'loading';
      var self = this;
      setTimeout(function () {
        try {
          self._doCompute(v);
          self.phase = 'done';
        } catch (e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 60);
    },

    // ── Helpers ───────────────────────────────────
    _getUnit(id) {
      return this._units.find(function (u) { return u.id === id; });
    },

    _toBits(v, uid) { return v * this._getUnit(uid).toBits; },
    _fromBits(bits, uid) { return bits / this._getUnit(uid).toBits; },

    _fmt(v) {
      if (!isFinite(v)) return '—';
      if (v === 0) return '0';
      var abs = Math.abs(v);
      if (abs < 1e-12) return v.toExponential(4);
      if (abs < 0.0001)return parseFloat(v.toFixed(10)).toString();
      if (abs < 0.001) return parseFloat(v.toFixed(8)).toString();
      if (abs < 0.01)  return parseFloat(v.toFixed(6)).toString();
      if (abs < 1)     return parseFloat(v.toFixed(5)).toString();
      if (abs < 10)    return parseFloat(v.toFixed(4)).toString();
      if (abs < 1000)  return parseFloat(v.toFixed(3)).toString();
      if (abs < 1e6)   return parseFloat(v.toFixed(1)).toString();
      if (abs < 1e15)  return Math.round(v).toLocaleString('en-US');
      return v.toExponential(4);
    },

    /* Smart unit label: pick most readable SI & IEC unit for a bit count */
    _bestSI(bits) {
      var bytes = bits / 8;
      if (bytes >= 1e15) return { val: bytes / 1e15, sym: 'PB' };
      if (bytes >= 1e12) return { val: bytes / 1e12, sym: 'TB' };
      if (bytes >= 1e9)  return { val: bytes / 1e9,  sym: 'GB' };
      if (bytes >= 1e6)  return { val: bytes / 1e6,  sym: 'MB' };
      if (bytes >= 1e3)  return { val: bytes / 1e3,  sym: 'KB' };
      if (bytes >= 1)    return { val: bytes,         sym: 'B'  };
      return                      { val: bits,          sym: 'b'  };
    },

    _bestIEC(bits) {
      var bytes = bits / 8;
      var k = 1024, m = k*k, g = m*k, t = g*k, p = t*k;
      if (bytes >= p) return { val: bytes / p, sym: 'PiB' };
      if (bytes >= t) return { val: bytes / t, sym: 'TiB' };
      if (bytes >= g) return { val: bytes / g, sym: 'GiB' };
      if (bytes >= m) return { val: bytes / m, sym: 'MiB' };
      if (bytes >= k) return { val: bytes / k, sym: 'KiB' };
      return               { val: bytes,       sym: 'B'   };
    },

    _getScale(bits) {
      var bytes = bits / 8;
      if (bytes <= 0)      return { icon: '•',   note: 'Zero storage.' };
      if (bytes < 1)       return { icon: '💡',  note: 'Less than 1 byte — a single bit or two.' };
      if (bytes < 100)     return { icon: '📝',  note: 'A few bytes — enough for a short text string.' };
      if (bytes < 1e3)     return { icon: '📄',  note: 'A few hundred bytes — like a plain-text email.' };
      if (bytes < 50e3)    return { icon: '📧',  note: 'Kilobytes — a typical text document or web page HTML.' };
      if (bytes < 1e6)     return { icon: '🖼️',  note: 'Hundreds of KB — a compressed image (JPEG/PNG).' };
      if (bytes < 10e6)    return { icon: '🎵',  note: 'A few MB — an MP3 song (3–5 MB) or a high-res photo.' };
      if (bytes < 100e6)   return { icon: '📱',  note: 'Tens of MB — a mobile app or small software installation.' };
      if (bytes < 1e9)     return { icon: '🎬',  note: 'Hundreds of MB — a compressed SD video or a large app.' };
      if (bytes < 10e9)    return { icon: '🎥',  note: 'A few GB — a full HD movie (4–8 GB) or a PC game install.' };
      if (bytes < 100e9)   return { icon: '💿',  note: 'Tens of GB — a 4K movie, large game, or a USB flash drive.' };
      if (bytes < 1e12)    return { icon: '🖥️',  note: 'Hundreds of GB — a laptop SSD or external hard drive.' };
      if (bytes < 10e12)   return { icon: '🗄️',  note: 'A few TB — a large desktop HDD or small NAS device.' };
      if (bytes < 1e15)    return { icon: '🏢',  note: 'Hundreds of TB — a small enterprise server or data centre rack.' };
      return                       { icon: '🌐',  note: 'Petabytes — the scale of major cloud providers and internet archives.' };
    },

    // ── Core compute ──────────────────────────────
    _doCompute(inputVal) {
      var self  = this;
      var fromU = self._getUnit(self.fromUnit);
      var toU   = self._getUnit(self.toUnit);

      var bits    = self._toBits(inputVal, self.fromUnit);
      var primary = self._fromBits(bits, self.toUnit);
      var factor  = fromU.toBits / toU.toBits;

      // Card builder
      function makeCard(u) {
        return {
          id:      u.id, sym: u.sym, name: u.name, base: u.base,
          display: self._fmt(self._fromBits(bits, u.id)),
          isSrc:   u.id === self.fromUnit,
          isDst:   u.id === self.toUnit,
        };
      }

      // SI vs IEC comparison rows
      var siLevels  = ['KB','MB','GB','TB','PB'];
      var iecLevels = ['KiB','MiB','GiB','TiB','PiB'];
      var siVsIec   = [];
      for (var i = 0; i < siLevels.length; i++) {
        var siVal  = self._fromBits(bits, siLevels[i]);
        var iecVal = self._fromBits(bits, iecLevels[i]);
        // Only show rows that have a "reasonable" value (not absurdly small or large)
        var siAbs  = Math.abs(siVal);
        if (siAbs >= 0.001 && siAbs < 1e15) {
          siVsIec.push({
            si:  self._fmt(siVal)  + ' ' + siLevels[i],
            iec: self._fmt(iecVal) + ' ' + iecLevels[i],
          });
        }
      }
      // Cap at 4 most relevant rows
      if (siVsIec.length > 4) siVsIec = siVsIec.slice(0, 4);

      var equationStr = self._fmt(inputVal) + ' ' + fromU.sym
        + '  ×  ' + self._fmt(factor)
        + '  =  ' + self._fmt(primary) + ' ' + toU.sym;

      self.result = {
        primaryVal:  self._fmt(primary),
        fromDisplay: self._fmt(inputVal) + ' ' + fromU.sym,
        toName:      toU.name,
        toFull:      toU.name + ' (' + toU.sym + ')',
        equationStr: equationStr,
        baseCards:   self._units.filter(function(u){ return u.group==='base'; }).map(makeCard),
        siCards:     self._units.filter(function(u){ return u.group==='si'; }).map(makeCard),
        iecCards:    self._units.filter(function(u){ return u.group==='iec'; }).map(makeCard),
        siVsIec:     siVsIec.length > 0 ? siVsIec : null,
        formula: {
          label:     fromU.name + ' → ' + toU.name,
          expr:      '1 ' + fromU.sym + ' = ' + self._fmt(factor) + ' ' + toU.sym,
          via:       toU.sym + ' = (' + fromU.sym + ' × ' + self._fmt(fromU.toBits) + ') ÷ ' + self._fmt(toU.toBits),
          calcLabel: self._fmt(inputVal) + ' ' + fromU.sym + ' =',
          calcVal:   self._fmt(primary) + ' ' + toU.sym,
        },
        scale: self._getScale(bits),
      };
    },
  };
}
</script>
@endpush
