@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between millimeters, centimeters, meters, kilometers, inches, feet, yards, and miles instantly.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Length Converter  —  prefix: lc-
   Theme: Cyan / Teal (#0e7490 / #0891b2 / #06b6d4)
══════════════════════════════════════════════ */

/* Unit dropdown wrapper (styled select) */
.lc-select-wrap { position: relative; }
.lc-select-wrap select {
  appearance: none; -webkit-appearance: none;
  padding: .65rem 2.5rem .65rem 1rem;
  border: 1.5px solid #a5f3fc; border-radius: .875rem;
  background: #fff; color: #164e63; font-weight: 700; font-size: .9rem;
  width: 100%; cursor: pointer; transition: border-color .15s, box-shadow .15s;
}
.lc-select-wrap select:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6,182,212,.15); }
.lc-select-wrap::after {
  content: '▾'; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
  color: #0891b2; font-size: .75rem; pointer-events: none; font-weight: 900;
}

/* Swap button */
.lc-swap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2.6rem; height: 2.6rem; border-radius: 9999px; border: 2px solid #a5f3fc;
  background: #fff; color: #0891b2; font-size: 1.2rem; cursor: pointer;
  transition: all .2s; flex-shrink: 0;
}
.lc-swap-btn:hover { background: #0891b2; color: #fff; border-color: #0891b2; transform: rotate(180deg); }

/* Primary result hero */
.lc-result-hero {
  background: linear-gradient(135deg, #083344, #0e7490, #0891b2);
  border-radius: 1.5rem; padding: 1.75rem;
  color: #fff;
}
.lc-result-hero .lc-rh-val {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 900; line-height: 1; letter-spacing: -.03em;
}
.lc-result-hero .lc-rh-unit {
  font-size: 1rem; font-weight: 700; color: #a5f3fc; margin-top: .25rem;
}

/* All-units grid card */
.lc-unit-card {
  border: 1.5px solid #a5f3fc; border-radius: 1rem;
  background: #fff; padding: .75rem .9rem;
  cursor: pointer; transition: all .18s;
  display: flex; flex-direction: column; gap: .2rem;
}
.lc-unit-card:hover { border-color: #06b6d4; box-shadow: 0 4px 16px rgba(6,182,212,.12); transform: translateY(-1px); }
.lc-unit-card.lc-src { background: #ecfeff; border-color: #06b6d4; border-width: 2px; }
.lc-unit-card.lc-dst { background: linear-gradient(135deg, #ecfeff, #cffafe); border-color: #0891b2; border-width: 2px;
                         box-shadow: 0 4px 18px rgba(8,145,178,.15); }
.lc-unit-card .lc-uc-sym  { font-size: .65rem; font-weight: 800; color: #0891b2; text-transform: uppercase; letter-spacing: .1em; }
.lc-unit-card .lc-uc-name { font-size: .7rem;  color: #94a3b8; font-weight: 600; }
.lc-unit-card .lc-uc-val  { font-size: 1.05rem; font-weight: 900; color: #164e63; word-break: break-all; }
.lc-unit-card.lc-dst .lc-uc-val { color: #0e7490; }
.lc-unit-card .lc-uc-use  { font-size: .58rem; color: #06b6d4; font-weight: 700; opacity: 0; transition: opacity .15s; }
.lc-unit-card:hover .lc-uc-use { opacity: 1; }

/* Preset pill */
.lc-preset {
  padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
  background: #ecfeff; color: #0e7490; border: 1px solid #a5f3fc; cursor: pointer; white-space: nowrap; transition: all .15s;
}
.lc-preset:hover { background: #0891b2; color: #fff; border-color: #0891b2; }

/* Section divider */
.lc-div {
  display: flex; align-items: center; gap: .6rem; color: #06b6d4;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.lc-div::before, .lc-div::after { content: ''; flex: 1; height: 1px; background: #a5f3fc; }

/* Scale reference badge */
.lc-scale {
  background: linear-gradient(135deg, #ecfeff, #f0f9ff);
  border: 1.5px solid #a5f3fc; border-radius: 1rem;
  padding: .7rem 1rem; display: flex; align-items: center; gap: .75rem;
  font-size: .82rem; color: #0e7490;
}

/* Conversion formula inline */
.lc-formula {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  background: #ecfeff; color: #0e7490; padding: .15rem .45rem;
  border-radius: .35rem; font-size: .73rem; font-weight: 600;
}

@keyframes lcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.lc-in { animation: lcIn .28s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="lcCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #083344, #0891b2);">
        <span class="text-3xl">📏</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Length Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between metric and imperial length units instantly.
        All unit values shown at once — click any result to use it as input.
      </p>
    </div>

    {{-- Validation error --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-base leading-none flex-shrink-0">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ══════════ LEFT — INPUTS ══════════ --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Input card --}}
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span>📐</span> Enter Length
          </h2>

          <div>
            <label class="form-label">Length Value</label>
            <input
              type="number"
              step="any"
              placeholder="e.g. 5.6"
              x-model="value"
              @input.debounce.350ms="autoConvert()"
              class="form-input w-full text-lg font-semibold"
              autofocus
            />
          </div>

          {{-- From / To selects + swap --}}
          <div>
            <label class="form-label">From Unit</label>
            <div class="lc-select-wrap">
              <select x-model="fromUnit" @change="autoConvert()">
                <template x-for="u in units" :key="u.id">
                  <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
                </template>
              </select>
            </div>
          </div>

          {{-- Swap row --}}
          <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-cyan-100"></div>
            <button @click="swap()" class="lc-swap-btn" title="Swap units">⇅</button>
            <div class="flex-1 h-px bg-cyan-100"></div>
          </div>

          <div>
            <label class="form-label">To Unit</label>
            <div class="lc-select-wrap">
              <select x-model="toUnit" @change="autoConvert()">
                <template x-for="u in units" :key="u.id">
                  <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
                </template>
              </select>
            </div>
          </div>
        </div>

        {{-- Quick presets --}}
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2.5">Quick Presets</p>
          <div class="flex flex-wrap gap-1.5">
            <template x-for="p in presets" :key="p.label">
              <button @click="applyPreset(p)" class="lc-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
          <button
            @click="doConvert()"
            class="btn btn-primary flex-1 py-3 text-base font-bold"
            style="background: linear-gradient(135deg, #083344, #0891b2);">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Quick tip --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1"
             style="background: linear-gradient(135deg,#ecfeff,#f0f9ff); border-color:#a5f3fc;">
          <p class="font-bold text-cyan-800 uppercase tracking-wide text-xs">💡 Handy References</p>
          <p><strong class="text-gray-700">1 inch</strong> = exactly 2.54 cm (since 1959)</p>
          <p><strong class="text-gray-700">1 foot</strong> = 12 inches = 30.48 cm</p>
          <p><strong class="text-gray-700">1 mile</strong> = 5,280 feet = 1,609.344 m</p>
          <p><strong class="text-gray-700">1 nautical mile</strong> = 1,852 m</p>
        </div>

      </div>

      {{-- ══════════ RIGHT — RESULTS ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">📏</div>
          <p class="font-medium text-gray-500">Enter a length and press Convert</p>
          <p class="text-sm mt-1">The result and all unit conversions will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-cyan-200 border-t-cyan-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        {{-- Results --}}
        <template x-if="phase === 'done'">
          <div class="space-y-5 lc-in">

            {{-- Primary result hero --}}
            <div class="lc-result-hero">
              <p class="text-cyan-300 text-xs font-bold uppercase tracking-widest mb-3">Result</p>
              <div class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                  <div class="text-cyan-200 text-sm font-semibold mb-1">
                    <span x-text="result.fromDisplay"></span>
                    <span class="text-cyan-400 mx-2 text-xl">→</span>
                    <span x-text="result.toUnitName"></span>
                  </div>
                  <div class="lc-rh-val" x-text="result.primaryVal"></div>
                  <div class="lc-rh-unit" x-text="result.toUnitFull"></div>
                </div>
                {{-- Swap shortcut inside hero --}}
                <button
                  @click="swap()"
                  class="flex-shrink-0 text-cyan-200 hover:text-white transition-colors text-xs font-bold uppercase tracking-wide border border-cyan-600 hover:border-cyan-400 rounded-lg px-3 py-2">
                  ⇅ Swap
                </button>
              </div>
              <div class="mt-3 pt-3 border-t border-cyan-700 text-xs text-cyan-200 font-mono" x-text="result.equationStr"></div>
            </div>

            {{-- All-units grid --}}
            <div class="card p-5">
              <p class="lc-div mb-3">All Units</p>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                <template x-for="card in result.allUnits" :key="card.id">
                  <button @click="setToUnit(card.id)" class="lc-unit-card text-left"
                          :class="card.isSrc ? 'lc-src' : (card.isDst ? 'lc-dst' : '')">
                    <div class="flex items-center justify-between">
                      <span class="lc-uc-sym" x-text="card.sym"></span>
                      <span x-show="card.isDst" class="text-xs text-cyan-600 font-bold">← result</span>
                      <span x-show="card.isSrc" class="text-xs text-cyan-400 font-bold">input</span>
                    </div>
                    <div class="lc-uc-val" x-text="card.display"></div>
                    <div class="lc-uc-name" x-text="card.name"></div>
                    <div class="lc-uc-use" x-show="!card.isDst && !card.isSrc">↑ set as To unit</div>
                  </button>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-2">Click any unit card to update the "To unit" field.</p>
            </div>

            {{-- Scale reference --}}
            <div class="lc-scale">
              <span class="text-2xl flex-shrink-0" x-text="result.scale.icon"></span>
              <div>
                <p class="font-bold text-cyan-800 text-sm">In perspective</p>
                <p class="text-cyan-700 text-xs mt-0.5" x-text="result.scale.note"></p>
              </div>
            </div>

            {{-- Conversion formula card --}}
            <div class="card p-5">
              <p class="lc-div mb-3">Conversion Formula</p>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-cyan-50 text-sm">
                  <span class="text-gray-600 font-medium" x-text="result.formula.label"></span>
                  <code class="lc-formula" x-text="result.formula.expr"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-cyan-50 text-sm">
                  <span class="text-gray-600 font-medium">Via meters (base unit)</span>
                  <code class="lc-formula" x-text="result.formula.via"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-cyan-50 text-sm font-semibold text-cyan-800">
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
   Length Converter — Alpine.js component
   CSS prefix: lc-   Theme: Cyan / Teal
   Base unit: meter (m). All toMeters factors are exact.
───────────────────────────────────────────────────────────── */
function lcCalc() {
  return {

    // ── Inputs ──────────────────────────────────
    value:    '',
    fromUnit: 'm',
    toUnit:   'ft',

    // ── State ────────────────────────────────────
    phase:    'idle',
    errorMsg: '',
    result:   null,

    // ── Unit definitions (all exact SI/defined values) ──
    units: [
      { id: 'mm',  sym: 'mm',  name: 'Millimeter',    toM: 0.001      },
      { id: 'cm',  sym: 'cm',  name: 'Centimeter',    toM: 0.01       },
      { id: 'm',   sym: 'm',   name: 'Meter',          toM: 1          },
      { id: 'km',  sym: 'km',  name: 'Kilometer',      toM: 1000       },
      { id: 'in',  sym: 'in',  name: 'Inch',           toM: 0.0254     },
      { id: 'ft',  sym: 'ft',  name: 'Foot',           toM: 0.3048     },
      { id: 'yd',  sym: 'yd',  name: 'Yard',           toM: 0.9144     },
      { id: 'mi',  sym: 'mi',  name: 'Mile',           toM: 1609.344   },
      { id: 'nmi', sym: 'nmi', name: 'Nautical Mile',  toM: 1852       },
    ],

    // ── Presets ──────────────────────────────────
    presets: [
      { label: '1 Inch',           v: 1,       from: 'in',  to: 'cm'  },
      { label: '1 Foot',           v: 1,       from: 'ft',  to: 'cm'  },
      { label: '1 Yard',           v: 1,       from: 'yd',  to: 'm'   },
      { label: '1 Mile',           v: 1,       from: 'mi',  to: 'km'  },
      { label: '1 km → miles',     v: 1,       from: 'km',  to: 'mi'  },
      { label: '5K run',           v: 5,       from: 'km',  to: 'mi'  },
      { label: 'Marathon',         v: 42.195,  from: 'km',  to: 'mi'  },
      { label: '100m sprint',      v: 100,     from: 'm',   to: 'ft'  },
    ],

    // ── Lifecycle ────────────────────────────────
    init() { /* nothing on mount */ },

    // ── Actions ──────────────────────────────────
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
      this.fromUnit = 'm';
      this.toUnit   = 'ft';
      this.phase    = 'idle';
      this.errorMsg = '';
      this.result   = null;
    },

    autoConvert() {
      if (this.value !== '' && !isNaN(parseFloat(this.value))) this.doConvert();
    },

    doConvert() {
      this.errorMsg = '';

      // ── Validation ─────────────────────────────
      if (this.value === '' || this.value === null) {
        this.errorMsg = 'Please enter a length value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid number (e.g. 5.6 or 1000).';
        return;
      }
      if (!isFinite(v)) {
        this.errorMsg = 'Please enter a finite number.';
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
      return this.units.find(function (u) { return u.id === id; });
    },

    /* Convert value from any unit → meters */
    _toMeters(v, unitId) {
      return v * this._getUnit(unitId).toM;
    },

    /* Convert meters → any unit */
    _fromMeters(m, unitId) {
      return m / this._getUnit(unitId).toM;
    },

    /* Smart number formatter */
    _fmt(v) {
      if (!isFinite(v)) return '—';
      if (v === 0) return '0';
      var abs = Math.abs(v);
      if (abs < 1e-9)  return v.toExponential(4);
      if (abs < 0.001) return v.toFixed(8).replace(/\.?0+$/, '');
      if (abs < 0.01)  return parseFloat(v.toFixed(6)).toString();
      if (abs < 1)     return parseFloat(v.toFixed(5)).toString();
      if (abs < 10)    return parseFloat(v.toFixed(4)).toString();
      if (abs < 1000)  return parseFloat(v.toFixed(4)).toString();
      if (abs < 1e6)   return parseFloat(v.toFixed(2)).toString();
      if (abs < 1e9)   return parseFloat(v.toFixed(0)).toString();
      return v.toExponential(4);
    },

    /* Real-world scale reference */
    _getScale(meters) {
      var a = Math.abs(meters);
      if (a === 0)       return { icon: '•',  note: 'Zero length.' };
      if (a < 0.0001)    return { icon: '🦠', note: 'Smaller than a grain of fine sand (~0.1 mm).' };
      if (a < 0.005)     return { icon: '🪲', note: 'About the size of an ant or small insect (1–5 mm).' };
      if (a < 0.012)     return { icon: '✏️',  note: 'About as wide as a standard pencil (~7 mm).' };
      if (a < 0.03)      return { icon: '💳', note: 'Close to the thickness of a credit card (~0.76 mm) × many.' };
      if (a < 0.16)      return { icon: '📱', note: 'About the width of a smartphone screen (~7–15 cm).' };
      if (a < 0.32)      return { icon: '📏', note: 'About the length of a standard 30 cm ruler (1 foot).' };
      if (a < 0.95)      return { icon: '🚪', note: 'Close to the width of a standard door (~80–90 cm).' };
      if (a < 1.9)       return { icon: '👤', note: 'Close to average adult height (~1.70–1.80 m / 5\'7"–5\'11").' };
      if (a < 5)         return { icon: '🚗', note: 'About the length of a mid-size car (~4.5–5 m).' };
      if (a < 13)        return { icon: '🚌', note: 'Close to the length of a standard city bus (~12 m).' };
      if (a < 35)        return { icon: '✈️',  note: 'About the wingspan of a large commercial aircraft (~30–35 m).' };
      if (a < 110)       return { icon: '⚽', note: 'Comparable to a full-size football (soccer) pitch length (~100 m).' };
      if (a < 320)       return { icon: '🗽', note: 'Less than the height of the Eiffel Tower (330 m / 1,083 ft).' };
      if (a < 890)       return { icon: '🏙️', note: 'About the length of a typical city block or short walk.' };
      if (a < 9000)      return { icon: '⛰️', note: 'Could be approaching Mount Everest height (8,848 m / 29,032 ft).' };
      if (a < 42500)     return { icon: '🏃', note: 'A marathon is exactly 42.195 km (26.219 mi) — you\'re in that range.' };
      if (a < 120000)    return { icon: '🗺️', note: 'Longer than many small countries end-to-end.' };
      if (a < 400000)    return { icon: '🌍', note: 'Earth\'s circumference is ~40,075 km (24,901 mi).' };
      if (a < 400000000) return { icon: '🌙', note: 'The Moon is ~384,400 km from Earth.' };
      return { icon: '⭐', note: 'Astronomical distances — the Sun is ~149.6 million km away.' };
    },

    // ── Core compute ────────────────────────────────
    _doCompute(inputVal) {
      var self     = this;
      var fromU    = self._getUnit(self.fromUnit);
      var toU      = self._getUnit(self.toUnit);

      var meters   = self._toMeters(inputVal, self.fromUnit);
      var primary  = self._fromMeters(meters, self.toUnit);

      // All-units grid
      var allUnits = self.units.map(function (u) {
        var converted = self._fromMeters(meters, u.id);
        return {
          id:      u.id,
          sym:     u.sym,
          name:    u.name,
          display: self._fmt(converted),
          isSrc:   u.id === self.fromUnit,
          isDst:   u.id === self.toUnit,
        };
      });

      // Conversion factor (exact)
      var factor = fromU.toM / toU.toM;

      // Equation string
      var equationStr = self._fmt(inputVal) + ' ' + fromU.sym
        + '  ×  ' + self._fmt(factor)
        + '  =  ' + self._fmt(primary) + ' ' + toU.sym;

      // Formula card
      var formula = {
        label:     fromU.name + ' → ' + toU.name,
        expr:      '1 ' + fromU.sym + ' = ' + self._fmt(factor) + ' ' + toU.sym,
        via:       toU.sym + ' = (' + fromU.sym + ' × ' + self._fmt(fromU.toM) + ') ÷ ' + self._fmt(toU.toM),
        calcLabel: self._fmt(inputVal) + ' ' + fromU.sym + ' =',
        calcVal:   self._fmt(primary) + ' ' + toU.sym,
      };

      // Scale reference based on meters equivalent
      var scale = self._getScale(meters);

      self.result = {
        primaryVal:   self._fmt(primary),
        fromDisplay:  self._fmt(inputVal) + ' ' + fromU.sym,
        toUnitName:   toU.name,
        toUnitFull:   toU.name + ' (' + toU.sym + ')',
        equationStr:  equationStr,
        allUnits:     allUnits,
        formula:      formula,
        scale:        scale,
      };
    },
  };
}
</script>
@endpush
