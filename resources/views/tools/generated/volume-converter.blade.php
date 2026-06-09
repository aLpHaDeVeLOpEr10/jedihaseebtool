@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between liters, milliliters, gallons, quarts, pints, cups, fluid ounces, tablespoons, teaspoons, and cubic units.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Volume Converter  —  prefix: vc-
   Theme: Lime-Green (#3f6212 / #65a30d / #84cc16)
   Base unit: liter (L)
══════════════════════════════════════════════ */

/* Styled select */
.vc-select-wrap { position: relative; }
.vc-select-wrap select {
  appearance: none; -webkit-appearance: none;
  padding: .65rem 2.5rem .65rem 1rem;
  border: 1.5px solid #bef264; border-radius: .875rem;
  background: #fff; color: #1a2e05; font-weight: 700; font-size: .9rem;
  width: 100%; cursor: pointer; transition: border-color .15s, box-shadow .15s;
}
.vc-select-wrap select:focus { outline: none; border-color: #65a30d; box-shadow: 0 0 0 3px rgba(101,163,13,.15); }
.vc-select-wrap::after {
  content: '▾'; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
  color: #65a30d; font-size: .75rem; pointer-events: none; font-weight: 900;
}

/* Swap button */
.vc-swap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2.6rem; height: 2.6rem; border-radius: 9999px;
  border: 2px solid #bef264; background: #fff; color: #65a30d;
  font-size: 1.2rem; cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.vc-swap-btn:hover { background: #65a30d; color: #fff; border-color: #65a30d; transform: rotate(180deg); }

/* Hero */
.vc-hero {
  background: linear-gradient(135deg, #1a2e05, #3f6212, #65a30d);
  border-radius: 1.5rem; padding: 1.75rem; color: #fff;
}
.vc-hero .vc-hero-val  { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1; letter-spacing: -.03em; }
.vc-hero .vc-hero-unit { font-size: 1rem; font-weight: 700; color: #bef264; margin-top: .25rem; }

/* All-units card */
.vc-unit-card {
  border: 1.5px solid #bef264; border-radius: 1rem;
  background: #fff; padding: .7rem .85rem;
  cursor: pointer; transition: all .18s;
  display: flex; flex-direction: column; gap: .15rem;
}
.vc-unit-card:hover { border-color: #65a30d; box-shadow: 0 4px 16px rgba(101,163,13,.12); transform: translateY(-1px); }
.vc-unit-card.vc-src { background: #f7fee7; border-color: #65a30d; border-width: 2px; }
.vc-unit-card.vc-dst {
  background: linear-gradient(135deg, #f7fee7, #ecfccb);
  border-color: #3f6212; border-width: 2px;
  box-shadow: 0 4px 18px rgba(63,98,18,.15);
}
.vc-unit-card .vc-uc-sym  { font-size: .62rem; font-weight: 800; color: #65a30d; text-transform: uppercase; letter-spacing: .1em; }
.vc-unit-card .vc-uc-name { font-size: .66rem; color: #94a3b8; font-weight: 600; }
.vc-unit-card .vc-uc-val  { font-size: 1rem; font-weight: 900; color: #1a2e05; word-break: break-all; line-height: 1.2; }
.vc-unit-card.vc-dst .vc-uc-val { color: #3f6212; }
.vc-unit-card .vc-uc-hint { font-size: .56rem; color: #65a30d; font-weight: 700; opacity: 0; transition: opacity .15s; }
.vc-unit-card:hover .vc-uc-hint { opacity: 1; }

/* Group label */
.vc-group-lbl {
  font-size: .6rem; font-weight: 800; color: #65a30d; text-transform: uppercase;
  letter-spacing: .1em; padding: .2rem .4rem;
}

/* Preset pill */
.vc-preset {
  padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
  background: #f7fee7; color: #3f6212; border: 1px solid #bef264;
  cursor: pointer; white-space: nowrap; transition: all .15s;
}
.vc-preset:hover { background: #65a30d; color: #fff; border-color: #65a30d; }

/* Divider */
.vc-div {
  display: flex; align-items: center; gap: .6rem;
  color: #65a30d; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.vc-div::before, .vc-div::after { content: ''; flex: 1; height: 1px; background: #bef264; }

/* Scale badge */
.vc-scale {
  background: linear-gradient(135deg, #f7fee7, #ecfccb);
  border: 1.5px solid #bef264; border-radius: 1rem; padding: .7rem 1rem;
  display: flex; align-items: center; gap: .75rem; font-size: .82rem; color: #3f6212;
}

/* Formula code */
.vc-code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  background: #f7fee7; color: #3f6212; padding: .15rem .45rem;
  border-radius: .35rem; font-size: .72rem; font-weight: 600;
}

@keyframes vcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.vc-in { animation: vcIn .28s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="vcCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #1a2e05, #65a30d);">
        <span class="text-3xl">🧪</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Volume Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between metric, imperial, and US cooking volume units instantly.
        Click any result card to use it as your new input.
      </p>
    </div>

    {{-- Error banner --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-base flex-shrink-0">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ══════════ LEFT — INPUTS ══════════ --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Input card --}}
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span>🧪</span> Enter Volume
          </h2>

          <div>
            <label class="form-label">Volume Value</label>
            <input
              type="number"
              step="any"
              placeholder="e.g. 2.5"
              x-model="value"
              @input.debounce.350ms="autoConvert()"
              class="form-input w-full text-lg font-semibold"
              autofocus
            />
          </div>

          <div>
            <label class="form-label">From Unit</label>
            <div class="vc-select-wrap">
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

          {{-- Swap --}}
          <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-lime-100"></div>
            <button @click="swap()" class="vc-swap-btn" title="Swap units">⇅</button>
            <div class="flex-1 h-px bg-lime-100"></div>
          </div>

          <div>
            <label class="form-label">To Unit</label>
            <div class="vc-select-wrap">
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
              <button @click="applyPreset(p)" class="vc-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
          <button @click="doConvert()"
                  class="btn btn-primary flex-1 py-3 text-base font-bold"
                  style="background: linear-gradient(135deg, #1a2e05, #65a30d);">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Tip card --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1"
             style="background: linear-gradient(135deg,#f7fee7,#ecfccb); border-color:#bef264;">
          <p class="font-bold text-lime-800 uppercase tracking-wide text-xs">💡 Cooking Equivalents</p>
          <p><strong class="text-gray-700">1 cup</strong> = 16 tbsp = 48 tsp = 8 fl oz</p>
          <p><strong class="text-gray-700">1 gallon</strong> = 4 qt = 8 pt = 16 cups</p>
          <p><strong class="text-gray-700">1 fl oz</strong> = 2 tbsp = 6 tsp = 29.574 mL</p>
          <p><strong class="text-gray-700">1 liter</strong> ≈ 4.227 cups ≈ 33.814 fl oz</p>
        </div>

      </div>

      {{-- ══════════ RIGHT — RESULTS ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">🧪</div>
          <p class="font-medium text-gray-500">Enter a volume value above</p>
          <p class="text-sm mt-1">Conversions across all 13 units will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-lime-200 border-t-lime-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        {{-- Results --}}
        <template x-if="phase === 'done'">
          <div class="space-y-5 vc-in">

            {{-- Hero --}}
            <div class="vc-hero">
              <p class="text-lime-300 text-xs font-bold uppercase tracking-widest mb-3">Result</p>
              <div class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                  <div class="text-lime-200 text-sm font-semibold mb-1">
                    <span x-text="result.fromDisplay"></span>
                    <span class="text-lime-300 mx-2 text-xl">→</span>
                    <span x-text="result.toName"></span>
                  </div>
                  <div class="vc-hero-val" x-text="result.primaryVal"></div>
                  <div class="vc-hero-unit" x-text="result.toFull"></div>
                </div>
                <button @click="swap()"
                        class="flex-shrink-0 text-lime-200 hover:text-white transition-colors text-xs font-bold uppercase tracking-wide border border-lime-700 hover:border-lime-400 rounded-lg px-3 py-2">
                  ⇅ Swap
                </button>
              </div>
              <div class="mt-3 pt-3 border-t border-lime-800 text-xs text-lime-200 font-mono"
                   x-text="result.equationStr"></div>
            </div>

            {{-- All-units grid — grouped --}}
            <div class="card p-5">
              <p class="vc-div mb-3">All Units</p>

              {{-- Metric group --}}
              <p class="vc-group-lbl">Metric</p>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                <template x-for="card in result.metricCards" :key="card.id">
                  <button @click="setToUnit(card.id)" class="vc-unit-card text-left"
                          :class="card.isSrc ? 'vc-src' : (card.isDst ? 'vc-dst' : '')">
                    <div class="flex items-center justify-between">
                      <span class="vc-uc-sym" x-text="card.sym"></span>
                      <span x-show="card.isDst" class="text-xs text-lime-600 font-bold">← result</span>
                      <span x-show="card.isSrc" class="text-xs text-lime-500 font-bold">input</span>
                    </div>
                    <div class="vc-uc-val" x-text="card.display"></div>
                    <div class="vc-uc-name" x-text="card.name"></div>
                    <div class="vc-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                  </button>
                </template>
              </div>

              {{-- US / Imperial group --}}
              <p class="vc-group-lbl">US Imperial</p>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="card in result.usCards" :key="card.id">
                  <button @click="setToUnit(card.id)" class="vc-unit-card text-left"
                          :class="card.isSrc ? 'vc-src' : (card.isDst ? 'vc-dst' : '')">
                    <div class="flex items-center justify-between">
                      <span class="vc-uc-sym" x-text="card.sym"></span>
                      <span x-show="card.isDst" class="text-xs text-lime-600 font-bold">← result</span>
                      <span x-show="card.isSrc" class="text-xs text-lime-500 font-bold">input</span>
                    </div>
                    <div class="vc-uc-val" x-text="card.display"></div>
                    <div class="vc-uc-name" x-text="card.name"></div>
                    <div class="vc-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                  </button>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-2.5">Click any card to update the "To unit" and recalculate.</p>
            </div>

            {{-- Real-world scale --}}
            <div class="vc-scale">
              <span class="text-2xl flex-shrink-0" x-text="result.scale.icon"></span>
              <div>
                <p class="font-bold text-lime-800 text-sm">In perspective</p>
                <p class="text-lime-700 text-xs mt-0.5" x-text="result.scale.note"></p>
              </div>
            </div>

            {{-- Conversion formula --}}
            <div class="card p-5">
              <p class="vc-div mb-3">Conversion Formula</p>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-lime-50 text-sm">
                  <span class="text-gray-600 font-medium" x-text="result.formula.label"></span>
                  <code class="vc-code" x-text="result.formula.expr"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-lime-50 text-sm">
                  <span class="text-gray-600 font-medium">Via liters (base unit)</span>
                  <code class="vc-code" x-text="result.formula.via"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-lime-50 text-sm font-semibold text-lime-800">
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
   Volume Converter — Alpine.js component
   CSS prefix: vc-   Theme: Lime-Green
   Base unit: liter (L)

   Exact definitions used:
   - 1 US gallon  = 231 in³  (exact, defined 1866)
   - 1 inch       = 25.4 mm  (exact, 1959 international agreement)
   - Therefore 1 in³ = 16.387064 mL  (25.4³ / 1000)
   - 1 US gallon  = 231 × 16.387064 mL = 3785.411784 mL = 3.785411784 L
   - 1 US fl oz   = 1/128 gallon = 29.5735295625 mL
   - 1 US tbsp    = 1/2 fl oz   = 14.78676478125 mL
   - 1 US tsp     = 1/6 fl oz   = 4.92892159375 mL
   - 1 US cup     = 8 fl oz     = 236.5882365 mL
   - 1 US pint    = 16 fl oz    = 473.176473 mL
   - 1 US quart   = 32 fl oz    = 946.352946 mL
   - 1 ft³        = 1728 in³    = 28316.846592 mL = 28.316846592 L
──────────────────────────────────────────────────────────── */
function vcCalc() {
  return {

    value:    '',
    fromUnit: 'L',
    toUnit:   'gal',

    phase:    'idle',
    errorMsg: '',
    result:   null,

    // ── Units — flat list (also used in _getUnit) ──
    _units: [
      // Metric
      { id: 'L',    sym: 'L',    name: 'Liter',           toL: 1,                  group: 'metric' },
      { id: 'mL',   sym: 'mL',   name: 'Milliliter',      toL: 0.001,              group: 'metric' },
      { id: 'm3',   sym: 'm³',   name: 'Cubic Meter',     toL: 1000,               group: 'metric' },
      { id: 'cm3',  sym: 'cm³',  name: 'Cubic Centimeter',toL: 0.001,              group: 'metric' },
      // US / Imperial
      { id: 'in3',  sym: 'in³',  name: 'Cubic Inch',      toL: 0.016387064,        group: 'us' },
      { id: 'ft3',  sym: 'ft³',  name: 'Cubic Foot',      toL: 28.316846592,       group: 'us' },
      { id: 'gal',  sym: 'gal',  name: 'US Gallon',       toL: 3.785411784,        group: 'us' },
      { id: 'qt',   sym: 'qt',   name: 'US Quart',        toL: 0.946352946,        group: 'us' },
      { id: 'pt',   sym: 'pt',   name: 'US Pint',         toL: 0.473176473,        group: 'us' },
      { id: 'cup',  sym: 'cup',  name: 'US Cup',          toL: 0.2365882365,       group: 'us' },
      { id: 'floz', sym: 'fl oz',name: 'Fluid Ounce',     toL: 0.0295735295625,    group: 'us' },
      { id: 'tbsp', sym: 'tbsp', name: 'Tablespoon',      toL: 0.01478676478125,   group: 'us' },
      { id: 'tsp',  sym: 'tsp',  name: 'Teaspoon',        toL: 0.00492892159375,   group: 'us' },
    ],

    // ── Grouped for <optgroup> selects ─────────────
    unitGroups: [],

    // ── Presets ───────────────────────────────────
    presets: [
      { label: '1 L → gal',       v: 1,    from: 'L',   to: 'gal'  },
      { label: '1 gal → L',       v: 1,    from: 'gal', to: 'L'    },
      { label: '1 cup → mL',      v: 1,    from: 'cup', to: 'mL'   },
      { label: '1 fl oz → mL',    v: 1,    from: 'floz',to: 'mL'   },
      { label: '1 tbsp → tsp',    v: 1,    from: 'tbsp',to: 'tsp'  },
      { label: '250 mL → cup',    v: 250,  from: 'mL',  to: 'cup'  },
      { label: '1 ft³ → L',       v: 1,    from: 'ft3', to: 'L'    },
      { label: '1 m³ → gal',      v: 1,    from: 'm3',  to: 'gal'  },
    ],

    // ── Lifecycle ─────────────────────────────────
    init() {
      // Build grouped units for <optgroup>
      this.unitGroups = [
        { label: 'Metric', units: this._units.filter(function(u){ return u.group==='metric'; }) },
        { label: 'US / Imperial', units: this._units.filter(function(u){ return u.group==='us'; }) },
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
      this.fromUnit = 'L';
      this.toUnit   = 'gal';
      this.phase    = 'idle';
      this.errorMsg = '';
      this.result   = null;
    },

    autoConvert() {
      if (this.value !== '' && !isNaN(parseFloat(this.value))) this.doConvert();
    },

    doConvert() {
      this.errorMsg = '';

      if (this.value === '' || this.value === null) {
        this.errorMsg = 'Please enter a volume value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid number (e.g. 2.5 or 1000).';
        return;
      }
      if (!isFinite(v)) {
        this.errorMsg = 'Please enter a finite number.';
        return;
      }
      if (v < 0) {
        this.errorMsg = 'Volume cannot be negative. Please enter a positive value.';
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

    // ── Helpers ────────────────────────────────────

    _getUnit(id) {
      return this._units.find(function (u) { return u.id === id; });
    },

    _toLiters(v, uid) { return v * this._getUnit(uid).toL; },

    _fromLiters(l, uid) { return l / this._getUnit(uid).toL; },

    _fmt(v) {
      if (!isFinite(v)) return '—';
      if (v === 0) return '0';
      var abs = Math.abs(v);
      if (abs < 1e-9)  return v.toExponential(4);
      if (abs < 0.0001)return parseFloat(v.toFixed(8)).toString();
      if (abs < 0.001) return parseFloat(v.toFixed(6)).toString();
      if (abs < 0.01)  return parseFloat(v.toFixed(5)).toString();
      if (abs < 1)     return parseFloat(v.toFixed(4)).toString();
      if (abs < 10)    return parseFloat(v.toFixed(4)).toString();
      if (abs < 1000)  return parseFloat(v.toFixed(3)).toString();
      if (abs < 1e6)   return parseFloat(v.toFixed(2)).toString();
      return v.toExponential(4);
    },

    _getScale(liters) {
      var a = liters;
      if (a <= 0)      return { icon: '•',  note: 'Empty — zero volume.' };
      if (a < 0.005)   return { icon: '💧', note: 'A few drops — a single water drop is about 0.05 mL.' };
      if (a < 0.015)   return { icon: '💊', note: 'About 1 teaspoon of liquid medicine (5 mL).' };
      if (a < 0.05)    return { icon: '🥄', note: 'About 1–3 tablespoons. A standard shot glass holds ~44 mL.' };
      if (a < 0.12)    return { icon: '🍵', note: 'About half a US cup. An espresso is ~30–60 mL.' };
      if (a < 0.28)    return { icon: '🥛', note: 'About 1 US cup (236.6 mL) — standard measuring cup.' };
      if (a < 0.55)    return { icon: '🥤', note: 'About a standard 12 fl oz soda can (355 mL).' };
      if (a < 0.90)    return { icon: '🍺', note: 'About a US pint (473 mL) — a large glass of beer.' };
      if (a < 1.10)    return { icon: '🍶', note: 'Close to 1 liter — a standard water bottle.' };
      if (a < 2.00)    return { icon: '🍾', note: 'A standard wine bottle is 750 mL; a magnum is 1.5 L.' };
      if (a < 4.20)    return { icon: '🛢️', note: 'About 1 US gallon (3.785 L) — a standard milk jug.' };
      if (a < 10)      return { icon: '🪣', note: 'Close to a small bucket (6–8 L) or a large paint can.' };
      if (a < 60)      return { icon: '🛁', note: 'A standard bathtub holds about 150–180 L of water.' };
      if (a < 200)     return { icon: '🚿', note: 'A 10-minute shower uses roughly 60–100 L of water.' };
      if (a < 1000)    return { icon: '🏊', note: 'A standard home hot tub holds about 500–1,500 L.' };
      if (a < 100000)  return { icon: '🏊', note: 'An Olympic swimming pool holds 2,500,000 L (2.5 ML).' };
      return { icon: '🌊', note: 'An extremely large volume — comparable to reservoirs or tanker ships.' };
    },

    // ── Core compute ────────────────────────────────
    _doCompute(inputVal) {
      var self  = this;
      var fromU = self._getUnit(self.fromUnit);
      var toU   = self._getUnit(self.toUnit);

      var liters  = self._toLiters(inputVal, self.fromUnit);
      var primary = self._fromLiters(liters, self.toUnit);

      // Build card data
      function makeCard(u) {
        return {
          id:     u.id,
          sym:    u.sym,
          name:   u.name,
          display:self._fmt(self._fromLiters(liters, u.id)),
          isSrc:  u.id === self.fromUnit,
          isDst:  u.id === self.toUnit,
        };
      }

      var metricCards = self._units.filter(function(u){ return u.group==='metric'; }).map(makeCard);
      var usCards     = self._units.filter(function(u){ return u.group==='us'; }).map(makeCard);

      // Conversion factor
      var factor = fromU.toL / toU.toL;

      var equationStr = self._fmt(inputVal) + ' ' + fromU.sym
        + '  ×  ' + self._fmt(factor)
        + '  =  ' + self._fmt(primary) + ' ' + toU.sym;

      var formula = {
        label:     fromU.name + ' → ' + toU.name,
        expr:      '1 ' + fromU.sym + ' = ' + self._fmt(factor) + ' ' + toU.sym,
        via:       toU.sym + ' = (' + fromU.sym + ' × ' + self._fmt(fromU.toL) + ') ÷ ' + self._fmt(toU.toL),
        calcLabel: self._fmt(inputVal) + ' ' + fromU.sym + ' =',
        calcVal:   self._fmt(primary) + ' ' + toU.sym,
      };

      self.result = {
        primaryVal:  self._fmt(primary),
        fromDisplay: self._fmt(inputVal) + ' ' + fromU.sym,
        toName:      toU.name,
        toFull:      toU.name + ' (' + toU.sym + ')',
        equationStr: equationStr,
        metricCards: metricCards,
        usCards:     usCards,
        formula:     formula,
        scale:       self._getScale(liters),
      };
    },
  };
}
</script>
@endpush
