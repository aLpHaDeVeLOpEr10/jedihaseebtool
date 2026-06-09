@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between m/s, km/h, mph, knots, ft/s, Mach, and more. Free instant speed converter.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Speed Converter  —  prefix: sc-
   Theme: Golden-Yellow (#a16207 / #ca8a04 / #eab308)
   Base unit: meters per second (m/s)
══════════════════════════════════════════════ */

/* Styled select */
.sc-select-wrap { position: relative; }
.sc-select-wrap select {
  appearance: none; -webkit-appearance: none;
  padding: .65rem 2.5rem .65rem 1rem;
  border: 1.5px solid #fde68a; border-radius: .875rem;
  background: #fff; color: #422006; font-weight: 700; font-size: .9rem;
  width: 100%; cursor: pointer; transition: border-color .15s, box-shadow .15s;
}
.sc-select-wrap select:focus { outline: none; border-color: #ca8a04; box-shadow: 0 0 0 3px rgba(202,138,4,.15); }
.sc-select-wrap::after {
  content: '▾'; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
  color: #ca8a04; font-size: .75rem; pointer-events: none; font-weight: 900;
}

/* Swap */
.sc-swap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2.6rem; height: 2.6rem; border-radius: 9999px;
  border: 2px solid #fde68a; background: #fff; color: #ca8a04;
  font-size: 1.2rem; cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.sc-swap-btn:hover { background: #ca8a04; color: #fff; border-color: #ca8a04; transform: rotate(180deg); }

/* Hero */
.sc-hero {
  background: linear-gradient(135deg, #422006, #a16207, #ca8a04);
  border-radius: 1.5rem; padding: 1.75rem; color: #fff;
}
.sc-hero .sc-hero-val  { font-size: clamp(1.9rem, 4.5vw, 3.2rem); font-weight: 900; line-height: 1; letter-spacing: -.03em; word-break: break-all; }
.sc-hero .sc-hero-unit { font-size: 1rem; font-weight: 700; color: #fde68a; margin-top: .25rem; }

/* All-units card */
.sc-unit-card {
  border: 1.5px solid #fde68a; border-radius: 1rem;
  background: #fff; padding: .7rem .85rem;
  cursor: pointer; transition: all .18s;
  display: flex; flex-direction: column; gap: .15rem;
}
.sc-unit-card:hover { border-color: #ca8a04; box-shadow: 0 4px 16px rgba(202,138,4,.12); transform: translateY(-1px); }
.sc-unit-card.sc-src { background: #fffbeb; border-color: #ca8a04; border-width: 2px; }
.sc-unit-card.sc-dst {
  background: linear-gradient(135deg, #fffbeb, #fef9c3);
  border-color: #a16207; border-width: 2px;
  box-shadow: 0 4px 18px rgba(161,98,7,.15);
}
.sc-unit-card .sc-uc-sym  { font-size: .62rem; font-weight: 800; color: #ca8a04; text-transform: uppercase; letter-spacing: .1em; }
.sc-unit-card .sc-uc-name { font-size: .66rem; color: #94a3b8; font-weight: 600; }
.sc-unit-card .sc-uc-val  { font-size: .98rem; font-weight: 900; color: #1c0a00; word-break: break-all; line-height: 1.2; }
.sc-unit-card.sc-dst .sc-uc-val { color: #a16207; }
.sc-unit-card .sc-uc-note { font-size: .6rem; color: #ca8a04; font-weight: 600; }
.sc-unit-card .sc-uc-hint { font-size: .56rem; color: #ca8a04; font-weight: 700; opacity: 0; transition: opacity .15s; }
.sc-unit-card:hover .sc-uc-hint { opacity: 1; }

/* Group label */
.sc-grp { font-size: .6rem; font-weight: 800; color: #ca8a04; text-transform: uppercase; letter-spacing: .1em; }

/* Preset pill */
.sc-preset {
  padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
  background: #fffbeb; color: #a16207; border: 1px solid #fde68a;
  cursor: pointer; white-space: nowrap; transition: all .15s;
}
.sc-preset:hover { background: #ca8a04; color: #fff; border-color: #ca8a04; }

/* Divider */
.sc-div {
  display: flex; align-items: center; gap: .6rem;
  color: #ca8a04; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.sc-div::before, .sc-div::after { content: ''; flex: 1; height: 1px; background: #fde68a; }

/* Scale badge */
.sc-scale {
  background: linear-gradient(135deg, #fffbeb, #fef9c3);
  border: 1.5px solid #fde68a; border-radius: 1rem; padding: .7rem 1rem;
  display: flex; align-items: center; gap: .75rem; font-size: .82rem; color: #a16207;
}

/* Formula code */
.sc-code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  background: #fffbeb; color: #a16207; padding: .15rem .45rem;
  border-radius: .35rem; font-size: .72rem; font-weight: 600;
}

@keyframes scIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.sc-in { animation: scIn .28s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="scCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #422006, #ca8a04);">
        <span class="text-3xl">💨</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Speed Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between common speed units including km/h, mph, m/s, knots, Mach, and more.
        Click any result to use it as the new input.
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
            <span>💨</span> Enter Speed
          </h2>

          <div>
            <label class="form-label">Speed Value</label>
            <input
              type="number"
              step="any"
              placeholder="e.g. 100"
              x-model="value"
              @input.debounce.350ms="autoConvert()"
              class="form-input w-full text-lg font-semibold"
              autofocus
            />
          </div>

          <div>
            <label class="form-label">From Unit</label>
            <div class="sc-select-wrap">
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
            <div class="flex-1 h-px bg-yellow-100"></div>
            <button @click="swap()" class="sc-swap-btn" title="Swap units">⇅</button>
            <div class="flex-1 h-px bg-yellow-100"></div>
          </div>

          <div>
            <label class="form-label">To Unit</label>
            <div class="sc-select-wrap">
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
              <button @click="applyPreset(p)" class="sc-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
          <button @click="doConvert()"
                  class="btn btn-primary flex-1 py-3 text-base font-bold"
                  style="background: linear-gradient(135deg, #422006, #ca8a04);">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Tip card --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1"
             style="background: linear-gradient(135deg,#fffbeb,#fef9c3); border-color:#fde68a;">
          <p class="font-bold text-yellow-800 uppercase tracking-wide text-xs">💡 Key Conversions</p>
          <p><strong class="text-gray-700">1 mph</strong> = 1.60934 km/h = 0.44704 m/s</p>
          <p><strong class="text-gray-700">1 knot</strong> = 1.852 km/h = 1.15078 mph</p>
          <p><strong class="text-gray-700">Mach 1</strong> ≈ 343 m/s ≈ 1,235 km/h (at 20°C, sea level)</p>
          <p><strong class="text-gray-700">Speed of Light</strong> ≈ 299,792 km/s ≈ 670,616,629 mph</p>
        </div>

      </div>

      {{-- ══════════ RIGHT — RESULTS ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">💨</div>
          <p class="font-medium text-gray-500">Enter a speed value above</p>
          <p class="text-sm mt-1">All 10 unit conversions will appear here instantly</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-yellow-200 border-t-yellow-500 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        {{-- Results --}}
        <template x-if="phase === 'done'">
          <div class="space-y-5 sc-in">

            {{-- Hero --}}
            <div class="sc-hero">
              <p class="text-yellow-300 text-xs font-bold uppercase tracking-widest mb-3">Result</p>
              <div class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                  <div class="text-yellow-200 text-sm font-semibold mb-1">
                    <span x-text="result.fromDisplay"></span>
                    <span class="text-yellow-300 mx-2 text-xl">→</span>
                    <span x-text="result.toName"></span>
                  </div>
                  <div class="sc-hero-val" x-text="result.primaryVal"></div>
                  <div class="sc-hero-unit" x-text="result.toFull"></div>
                </div>
                <button @click="swap()"
                        class="flex-shrink-0 text-yellow-200 hover:text-white transition-colors text-xs font-bold uppercase tracking-wide border border-yellow-700 hover:border-yellow-400 rounded-lg px-3 py-2">
                  ⇅ Swap
                </button>
              </div>
              <div class="mt-3 pt-3 border-t border-yellow-800 text-xs text-yellow-200 font-mono"
                   x-text="result.equationStr"></div>
            </div>

            {{-- All-units grid — grouped --}}
            <div class="card p-5 space-y-4">
              <p class="sc-div">All Units</p>

              <div>
                <p class="sc-grp mb-2">Common Speed Units</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  <template x-for="card in result.commonCards" :key="card.id">
                    <button @click="setToUnit(card.id)" class="sc-unit-card text-left"
                            :class="card.isSrc ? 'sc-src' : (card.isDst ? 'sc-dst' : '')">
                      <div class="flex items-center justify-between">
                        <span class="sc-uc-sym" x-text="card.sym"></span>
                        <span x-show="card.isDst" class="text-xs text-yellow-600 font-bold">← result</span>
                        <span x-show="card.isSrc" class="text-xs text-yellow-500 font-bold">input</span>
                      </div>
                      <div class="sc-uc-val" x-text="card.display"></div>
                      <div class="sc-uc-name" x-text="card.name"></div>
                      <div class="sc-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                    </button>
                  </template>
                </div>
              </div>

              <div>
                <p class="sc-grp mb-2">Advanced / Special Units</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                  <template x-for="card in result.advCards" :key="card.id">
                    <button @click="setToUnit(card.id)" class="sc-unit-card text-left"
                            :class="card.isSrc ? 'sc-src' : (card.isDst ? 'sc-dst' : '')">
                      <div class="flex items-center justify-between">
                        <span class="sc-uc-sym" x-text="card.sym"></span>
                        <span x-show="card.isDst" class="text-xs text-yellow-600 font-bold">← result</span>
                        <span x-show="card.isSrc" class="text-xs text-yellow-500 font-bold">input</span>
                      </div>
                      <div class="sc-uc-val" x-text="card.display"></div>
                      <div class="sc-uc-name" x-text="card.name"></div>
                      <div x-show="card.note" class="sc-uc-note" x-text="card.note"></div>
                      <div class="sc-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                    </button>
                  </template>
                </div>
              </div>
              <p class="text-xs text-gray-400">Click any card to update the "To unit" and recalculate.</p>
            </div>

            {{-- Context badge --}}
            <div class="sc-scale">
              <span class="text-2xl flex-shrink-0" x-text="result.scale.icon"></span>
              <div>
                <p class="font-bold text-yellow-800 text-sm">In perspective</p>
                <p class="text-yellow-700 text-xs mt-0.5" x-text="result.scale.note"></p>
              </div>
            </div>

            {{-- Formula --}}
            <div class="card p-5">
              <p class="sc-div mb-3">Conversion Formula</p>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-yellow-50 text-sm">
                  <span class="text-gray-600 font-medium" x-text="result.formula.label"></span>
                  <code class="sc-code" x-text="result.formula.expr"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-yellow-50 text-sm">
                  <span class="text-gray-600 font-medium">Via m/s (base unit)</span>
                  <code class="sc-code" x-text="result.formula.via"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-yellow-50 text-sm font-semibold text-yellow-800">
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
   Speed Converter — Alpine.js component
   CSS prefix: sc-   Theme: Golden-Yellow
   Base unit: meter per second (m/s)

   Exact definitions:
   - 1 km/h  = 1000 / 3600 m/s  = 0.27̄  m/s
   - 1 mph   = 1609.344 / 3600 m/s = 0.44704 m/s (exact)
   - 1 ft/s  = 0.3048 m/s  (exact: 1 ft = 0.3048 m)
   - 1 knot  = 1852 / 3600 m/s = 0.514444... m/s (1 nmi = 1852 m, exact)
   - 1 km/s  = 1000 m/s
   - 1 mi/s  = 1609.344 m/s
   - Mach 1  ≈ 343.0 m/s  (speed of sound, dry air, 20 °C, sea level)
   - c       = 299 792 458 m/s  (exact, defined 1983)
──────────────────────────────────────────────────────────── */
function scCalc() {
  return {

    value:    '',
    fromUnit: 'kmh',
    toUnit:   'mph',

    phase:    'idle',
    errorMsg: '',
    result:   null,

    // ── Unit definitions ──────────────────────────
    _units: [
      // Common
      { id: 'ms',   sym: 'm/s',   name: 'Meter / Second',         toMPS: 1,                  group: 'common', note: null },
      { id: 'kmh',  sym: 'km/h',  name: 'Kilometer / Hour',       toMPS: 1000/3600,          group: 'common', note: null },
      { id: 'mph',  sym: 'mph',   name: 'Mile / Hour',            toMPS: 0.44704,            group: 'common', note: null },
      { id: 'fts',  sym: 'ft/s',  name: 'Foot / Second',          toMPS: 0.3048,             group: 'common', note: null },
      { id: 'knot', sym: 'knot',  name: 'Knot (nmi/h)',           toMPS: 1852/3600,          group: 'common', note: null },
      { id: 'cms',  sym: 'cm/s',  name: 'Centimeter / Second',    toMPS: 0.01,               group: 'common', note: null },
      // Advanced
      { id: 'kms',  sym: 'km/s',  name: 'Kilometer / Second',     toMPS: 1000,               group: 'adv',    note: null },
      { id: 'mis',  sym: 'mi/s',  name: 'Mile / Second',          toMPS: 1609.344,           group: 'adv',    note: null },
      { id: 'mach', sym: 'Mach',  name: 'Mach Number',            toMPS: 343.0,              group: 'adv',    note: '20°C sea level' },
      { id: 'c',    sym: 'c',     name: 'Speed of Light',         toMPS: 299792458,          group: 'adv',    note: '299,792 km/s' },
    ],

    unitGroups: [],

    presets: [
      { label: '100 km/h → mph',  v: 100,  from: 'kmh',  to: 'mph'  },
      { label: '60 mph → km/h',   v: 60,   from: 'mph',  to: 'kmh'  },
      { label: '1 Mach → km/h',   v: 1,    from: 'mach', to: 'kmh'  },
      { label: '1 knot → km/h',   v: 1,    from: 'knot', to: 'kmh'  },
      { label: '10 m/s → km/h',   v: 10,   from: 'ms',   to: 'kmh'  },
      { label: 'Bolt (44.7 km/h)',v: 44.72,from: 'kmh',  to: 'ms'   },
      { label: '30 km/s (ISS)',    v: 30,   from: 'kms',  to: 'mph'  },
      { label: '0.01c → km/s',    v: 0.01, from: 'c',    to: 'kms'  },
    ],

    // ── Lifecycle ─────────────────────────────────
    init() {
      this.unitGroups = [
        { label: 'Common Speed Units', units: this._units.filter(function(u){ return u.group==='common'; }) },
        { label: 'Advanced / Special', units: this._units.filter(function(u){ return u.group==='adv'; }) },
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
      this.fromUnit = 'kmh';
      this.toUnit   = 'mph';
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
        this.errorMsg = 'Please enter a speed value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid number (e.g. 100 or 0.5).';
        return;
      }
      if (!isFinite(v)) {
        this.errorMsg = 'Please enter a finite number.';
        return;
      }
      if (v < 0) {
        this.errorMsg = 'Speed cannot be negative. Please enter a positive value.';
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

    _toMPS(v, uid) { return v * this._getUnit(uid).toMPS; },
    _fromMPS(mps, uid) { return mps / this._getUnit(uid).toMPS; },

    _fmt(v) {
      if (!isFinite(v)) return '—';
      if (v === 0) return '0';
      var abs = Math.abs(v);
      if (abs < 1e-12) return v.toExponential(4);
      if (abs < 0.0001)return parseFloat(v.toFixed(9)).toString();
      if (abs < 0.001) return parseFloat(v.toFixed(7)).toString();
      if (abs < 0.01)  return parseFloat(v.toFixed(6)).toString();
      if (abs < 1)     return parseFloat(v.toFixed(5)).toString();
      if (abs < 10)    return parseFloat(v.toFixed(4)).toString();
      if (abs < 1000)  return parseFloat(v.toFixed(3)).toString();
      if (abs < 1e6)   return parseFloat(v.toFixed(1)).toString();
      if (abs < 1e9)   return Math.round(v).toLocaleString('en-US');
      return v.toExponential(4);
    },

    _getScale(mps) {
      var a = mps;
      if (a <= 0)        return { icon: '•',  note: 'Zero speed — stationary.' };
      if (a < 0.3)       return { icon: '🐌', note: 'Very slow — a garden snail moves at about 0.001–0.003 m/s.' };
      if (a < 1.0)       return { icon: '🚶', note: 'Slow walk. Average human walking pace is ~1.4 m/s (5 km/h).' };
      if (a < 3.0)       return { icon: '🚶', note: 'Walking to brisk-walking pace (1–3 m/s / 4–11 km/h).' };
      if (a < 7.0)       return { icon: '🏃', note: 'Jogging or fast cycling (3–7 m/s / 11–25 km/h).' };
      if (a < 13.0)      return { icon: '🏃', note: 'Fast sprint. Usain Bolt\'s peak speed: ~12.4 m/s (44.7 km/h).' };
      if (a < 17.0)      return { icon: '🚗', note: 'Urban car speed — typical 30–60 km/h city zone.' };
      if (a < 34.0)      return { icon: '🚗', note: 'Rural/highway driving (80–120 km/h / 50–75 mph).' };
      if (a < 50.0)      return { icon: '🚄', note: 'High-speed rail (180 km/h). The TGV tops out at ~320 km/h.' };
      if (a < 100.0)     return { icon: '🚄', note: 'High-speed rail or a propeller aircraft (~300–360 km/h).' };
      if (a < 280.0)     return { icon: '✈️',  note: 'Commercial jet airliner cruising speed (~800–1,000 km/h).' };
      if (a < 400.0)     return { icon: '💨', note: 'Near or past Mach 1 (343 m/s at 20°C sea level).' };
      if (a < 2000.0)    return { icon: '🚀', note: 'Supersonic. The SR-71 Blackbird reached ~970 m/s (Mach 3.3).' };
      if (a < 8500.0)    return { icon: '🚀', note: 'Approaching orbital velocity — the ISS orbits at ~7,660 m/s.' };
      if (a < 12000.0)   return { icon: '🚀', note: 'Around Earth escape velocity (~11,186 m/s = 40,270 km/h).' };
      if (a < 617000.0)  return { icon: '⭐', note: 'Extraordinary speed — 1% of the speed of light is ~3,000 km/s.' };
      if (a < 299792458) return { icon: '⭐', note: 'A significant fraction of the speed of light (c).' };
      return { icon: '💡', note: 'At or beyond the speed of light (c = 299,792,458 m/s). Nothing with mass can reach c.' };
    },

    // ── Core compute ──────────────────────────────
    _doCompute(inputVal) {
      var self  = this;
      var fromU = self._getUnit(self.fromUnit);
      var toU   = self._getUnit(self.toUnit);

      var mps     = self._toMPS(inputVal, self.fromUnit);
      var primary = self._fromMPS(mps, self.toUnit);
      var factor  = fromU.toMPS / toU.toMPS;

      function makeCard(u) {
        return {
          id:      u.id,
          sym:     u.sym,
          name:    u.name,
          display: self._fmt(self._fromMPS(mps, u.id)),
          note:    u.note,
          isSrc:   u.id === self.fromUnit,
          isDst:   u.id === self.toUnit,
        };
      }

      var equationStr = self._fmt(inputVal) + ' ' + fromU.sym
        + '  ×  ' + self._fmt(factor)
        + '  =  ' + self._fmt(primary) + ' ' + toU.sym;

      self.result = {
        primaryVal:   self._fmt(primary),
        fromDisplay:  self._fmt(inputVal) + ' ' + fromU.sym,
        toName:       toU.name,
        toFull:       toU.name + ' (' + toU.sym + ')',
        equationStr:  equationStr,
        commonCards:  self._units.filter(function(u){ return u.group==='common'; }).map(makeCard),
        advCards:     self._units.filter(function(u){ return u.group==='adv'; }).map(makeCard),
        formula: {
          label:     fromU.name + ' → ' + toU.name,
          expr:      '1 ' + fromU.sym + ' = ' + self._fmt(factor) + ' ' + toU.sym,
          via:       toU.sym + ' = (' + fromU.sym + ' × ' + self._fmt(fromU.toMPS) + ') ÷ ' + self._fmt(toU.toMPS),
          calcLabel: self._fmt(inputVal) + ' ' + fromU.sym + ' =',
          calcVal:   self._fmt(primary) + ' ' + toU.sym,
        },
        scale: self._getScale(mps),
      };
    },
  };
}
</script>
@endpush
