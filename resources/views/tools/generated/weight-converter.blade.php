@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between kilograms, grams, pounds, ounces, stone, metric tons, and more. Free instant weight converter.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Weight Converter  —  prefix: wc-
   Theme: Orange (#c2410c / #ea580c / #f97316)
   Base unit: gram (g)
══════════════════════════════════════════════ */

/* Styled select dropdown */
.wc-select-wrap { position: relative; }
.wc-select-wrap select {
  appearance: none; -webkit-appearance: none;
  padding: .65rem 2.5rem .65rem 1rem;
  border: 1.5px solid #fed7aa; border-radius: .875rem;
  background: #fff; color: #7c2d12; font-weight: 700; font-size: .9rem;
  width: 100%; cursor: pointer; transition: border-color .15s, box-shadow .15s;
}
.wc-select-wrap select:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,.15); }
.wc-select-wrap::after {
  content: '▾'; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
  color: #ea580c; font-size: .75rem; pointer-events: none; font-weight: 900;
}

/* Swap button */
.wc-swap-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2.6rem; height: 2.6rem; border-radius: 9999px;
  border: 2px solid #fed7aa; background: #fff; color: #ea580c;
  font-size: 1.2rem; cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.wc-swap-btn:hover { background: #ea580c; color: #fff; border-color: #ea580c; transform: rotate(180deg); }

/* Primary result hero */
.wc-hero {
  background: linear-gradient(135deg, #7c2d12, #c2410c, #ea580c);
  border-radius: 1.5rem; padding: 1.75rem; color: #fff;
}
.wc-hero .wc-hero-val  { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1; letter-spacing: -.03em; }
.wc-hero .wc-hero-unit { font-size: 1rem; font-weight: 700; color: #fed7aa; margin-top: .25rem; }

/* All-units card */
.wc-unit-card {
  border: 1.5px solid #fed7aa; border-radius: 1rem;
  background: #fff; padding: .75rem .9rem;
  cursor: pointer; transition: all .18s;
  display: flex; flex-direction: column; gap: .18rem;
}
.wc-unit-card:hover { border-color: #f97316; box-shadow: 0 4px 16px rgba(249,115,22,.12); transform: translateY(-1px); }
.wc-unit-card.wc-src { background: #fff7ed; border-color: #f97316; border-width: 2px; }
.wc-unit-card.wc-dst {
  background: linear-gradient(135deg, #fff7ed, #ffedd5);
  border-color: #ea580c; border-width: 2px;
  box-shadow: 0 4px 18px rgba(234,88,12,.15);
}
.wc-unit-card .wc-uc-sym  { font-size: .63rem; font-weight: 800; color: #ea580c; text-transform: uppercase; letter-spacing: .1em; }
.wc-unit-card .wc-uc-name { font-size: .68rem; color: #94a3b8; font-weight: 600; }
.wc-unit-card .wc-uc-val  { font-size: 1.05rem; font-weight: 900; color: #431407; word-break: break-all; line-height: 1.2; }
.wc-unit-card.wc-dst .wc-uc-val { color: #c2410c; }
.wc-unit-card .wc-uc-hint { font-size: .57rem; color: #f97316; font-weight: 700; opacity: 0; transition: opacity .15s; }
.wc-unit-card:hover .wc-uc-hint { opacity: 1; }

/* Preset pill */
.wc-preset {
  padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
  background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;
  cursor: pointer; white-space: nowrap; transition: all .15s;
}
.wc-preset:hover { background: #ea580c; color: #fff; border-color: #ea580c; }

/* Section divider */
.wc-div {
  display: flex; align-items: center; gap: .6rem;
  color: #f97316; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
}
.wc-div::before, .wc-div::after { content: ''; flex: 1; height: 1px; background: #fed7aa; }

/* Scale badge */
.wc-scale {
  background: linear-gradient(135deg, #fff7ed, #ffedd5);
  border: 1.5px solid #fed7aa; border-radius: 1rem; padding: .7rem 1rem;
  display: flex; align-items: center; gap: .75rem; font-size: .82rem; color: #c2410c;
}

/* Formula inline code */
.wc-code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  background: #fff7ed; color: #c2410c; padding: .15rem .45rem;
  border-radius: .35rem; font-size: .73rem; font-weight: 600;
}

/* Stone sub-display */
.wc-stone-fmt {
  font-size: .65rem; color: #9a3412; font-weight: 700; margin-top: .1rem;
}

@keyframes wcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.wc-in { animation: wcIn .28s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="wcCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #7c2d12, #ea580c);">
        <span class="text-3xl">⚖️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Weight Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between kilograms, grams, pounds, ounces, stone, and more.
        Click any result card to use it as your new input.
      </p>
    </div>

    {{-- Validation error --}}
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
            <span>⚖️</span> Enter Weight
          </h2>

          {{-- Value --}}
          <div>
            <label class="form-label">Weight Value</label>
            <input
              type="number"
              step="any"
              placeholder="e.g. 75"
              x-model="value"
              @input.debounce.350ms="autoConvert()"
              class="form-input w-full text-lg font-semibold"
              autofocus
            />
          </div>

          {{-- From unit --}}
          <div>
            <label class="form-label">From Unit</label>
            <div class="wc-select-wrap">
              <select x-model="fromUnit" @change="autoConvert()">
                <template x-for="u in units" :key="u.id">
                  <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
                </template>
              </select>
            </div>
          </div>

          {{-- Swap --}}
          <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-orange-100"></div>
            <button @click="swap()" class="wc-swap-btn" title="Swap units">⇅</button>
            <div class="flex-1 h-px bg-orange-100"></div>
          </div>

          {{-- To unit --}}
          <div>
            <label class="form-label">To Unit</label>
            <div class="wc-select-wrap">
              <select x-model="toUnit" @change="autoConvert()">
                <template x-for="u in units" :key="u.id">
                  <option :value="u.id" x-text="u.sym + ' — ' + u.name"></option>
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
              <button @click="applyPreset(p)" class="wc-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-3">
          <button @click="doConvert()"
                  class="btn btn-primary flex-1 py-3 text-base font-bold"
                  style="background: linear-gradient(135deg, #7c2d12, #ea580c);">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Quick-reference tip --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1"
             style="background: linear-gradient(135deg,#fff7ed,#ffedd5); border-color:#fed7aa;">
          <p class="font-bold text-orange-800 uppercase tracking-wide text-xs">💡 Key Conversions</p>
          <p><strong class="text-gray-700">1 kg</strong> = 2.20462 lb = 35.274 oz</p>
          <p><strong class="text-gray-700">1 lb</strong> = 16 oz = 453.592 g</p>
          <p><strong class="text-gray-700">1 stone</strong> = 14 lb = 6.350 kg</p>
          <p><strong class="text-gray-700">1 metric ton</strong> = 1,000 kg = 2,204.62 lb</p>
        </div>

      </div>

      {{-- ══════════ RIGHT — RESULTS ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">⚖️</div>
          <p class="font-medium text-gray-500">Enter a weight value above</p>
          <p class="text-sm mt-1">The converted result and all unit values will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        {{-- Results --}}
        <template x-if="phase === 'done'">
          <div class="space-y-5 wc-in">

            {{-- Hero result --}}
            <div class="wc-hero">
              <p class="text-orange-200 text-xs font-bold uppercase tracking-widest mb-3">Result</p>
              <div class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                  <div class="text-orange-200 text-sm font-semibold mb-1">
                    <span x-text="result.fromDisplay"></span>
                    <span class="text-orange-300 mx-2 text-xl">→</span>
                    <span x-text="result.toName"></span>
                  </div>
                  <div class="wc-hero-val" x-text="result.primaryVal"></div>
                  <div class="wc-hero-unit" x-text="result.toFull"></div>
                  {{-- Stone sub-display --}}
                  <div x-show="result.stoneDisplay" class="mt-1 text-orange-200 text-sm font-medium"
                       x-text="result.stoneDisplay"></div>
                </div>
                <button @click="swap()"
                        class="flex-shrink-0 text-orange-200 hover:text-white transition-colors text-xs font-bold uppercase tracking-wide border border-orange-600 hover:border-orange-400 rounded-lg px-3 py-2">
                  ⇅ Swap
                </button>
              </div>
              <div class="mt-3 pt-3 border-t border-orange-700 text-xs text-orange-200 font-mono"
                   x-text="result.equationStr"></div>
            </div>

            {{-- All-units grid --}}
            <div class="card p-5">
              <p class="wc-div mb-3">All Units</p>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <template x-for="card in result.allUnits" :key="card.id">
                  <button @click="setToUnit(card.id)"
                          class="wc-unit-card text-left"
                          :class="card.isSrc ? 'wc-src' : (card.isDst ? 'wc-dst' : '')">
                    <div class="flex items-center justify-between">
                      <span class="wc-uc-sym" x-text="card.sym"></span>
                      <span x-show="card.isDst" class="text-xs text-orange-500 font-bold">← result</span>
                      <span x-show="card.isSrc" class="text-xs text-orange-400 font-bold">input</span>
                    </div>
                    <div class="wc-uc-val" x-text="card.display"></div>
                    <div class="wc-uc-name" x-text="card.name"></div>
                    <div x-show="card.stoneFmt" class="wc-stone-fmt" x-text="card.stoneFmt"></div>
                    <div class="wc-uc-hint" x-show="!card.isDst && !card.isSrc">↑ set as To</div>
                  </button>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-2">Click any card to update the "To unit" and recalculate.</p>
            </div>

            {{-- Real-world scale --}}
            <div class="wc-scale">
              <span class="text-2xl flex-shrink-0" x-text="result.scale.icon"></span>
              <div>
                <p class="font-bold text-orange-800 text-sm">In perspective</p>
                <p class="text-orange-700 text-xs mt-0.5" x-text="result.scale.note"></p>
              </div>
            </div>

            {{-- Conversion formula --}}
            <div class="card p-5">
              <p class="wc-div mb-3">Conversion Formula</p>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-orange-50 text-sm">
                  <span class="text-gray-600 font-medium" x-text="result.formula.label"></span>
                  <code class="wc-code" x-text="result.formula.expr"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-orange-50 text-sm">
                  <span class="text-gray-600 font-medium">Via grams (base unit)</span>
                  <code class="wc-code" x-text="result.formula.via"></code>
                </div>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-orange-50 text-sm font-semibold text-orange-800">
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
   Weight Converter — Alpine.js component
   CSS prefix: wc-   Theme: Orange
   Base unit: gram (g). All toGrams factors use exact 1959 definitions.
   1 lb = 453.59237 g  (exact, international definition)
   1 oz = 1 lb / 16   = 28.349523125 g
   1 st = 14 lb        = 6350.29318 g
   1 US short ton = 2000 lb = 907184.74 g
   1 UK long ton  = 2240 lb = 1016046.9088 g
   1 metric tonne = 1,000,000 g
──────────────────────────────────────────────────────────── */
function wcCalc() {
  return {

    // ── Inputs ──────────────────────────────────
    value:    '',
    fromUnit: 'kg',
    toUnit:   'lb',

    // ── State ────────────────────────────────────
    phase:    'idle',
    errorMsg: '',
    result:   null,

    // ── Unit definitions ─────────────────────────
    units: [
      { id: 'mg',   sym: 'mg',  name: 'Milligram',       toG: 0.001            },
      { id: 'g',    sym: 'g',   name: 'Gram',             toG: 1                },
      { id: 'kg',   sym: 'kg',  name: 'Kilogram',         toG: 1000             },
      { id: 't',    sym: 't',   name: 'Metric Tonne',     toG: 1e6              },
      { id: 'oz',   sym: 'oz',  name: 'Ounce',            toG: 28.349523125     },
      { id: 'lb',   sym: 'lb',  name: 'Pound',            toG: 453.59237        },
      { id: 'st',   sym: 'st',  name: 'Stone',            toG: 6350.29318       },
      { id: 'ston', sym: 'ston',name: 'US Short Ton',     toG: 907184.74        },
    ],

    // ── Presets ──────────────────────────────────
    presets: [
      { label: '1 kg → lb',       v: 1,    from: 'kg',  to: 'lb'   },
      { label: '1 lb → kg',       v: 1,    from: 'lb',  to: 'kg'   },
      { label: '1 st → kg',       v: 1,    from: 'st',  to: 'kg'   },
      { label: '1 oz → g',        v: 1,    from: 'oz',  to: 'g'    },
      { label: '70 kg (person)',   v: 70,   from: 'kg',  to: 'lb'   },
      { label: '150 lb (person)',  v: 150,  from: 'lb',  to: 'kg'   },
      { label: '1 metric tonne',  v: 1,    from: 't',   to: 'lb'   },
      { label: '500 g → oz',      v: 500,  from: 'g',   to: 'oz'   },
    ],

    // ── Lifecycle ────────────────────────────────
    init() { /* nothing */ },

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
      this.fromUnit = 'kg';
      this.toUnit   = 'lb';
      this.phase    = 'idle';
      this.errorMsg = '';
      this.result   = null;
    },

    autoConvert() {
      if (this.value !== '' && !isNaN(parseFloat(this.value))) this.doConvert();
    },

    doConvert() {
      this.errorMsg = '';

      // ── Validate ─────────────────────────────────
      if (this.value === '' || this.value === null) {
        this.errorMsg = 'Please enter a weight value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid number (e.g. 75 or 0.5).';
        return;
      }
      if (!isFinite(v)) {
        this.errorMsg = 'Please enter a finite number.';
        return;
      }
      if (v < 0) {
        this.errorMsg = 'Weight cannot be negative. Please enter a positive value.';
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

    // ── Helpers ──────────────────────────────────

    _getUnit(id) {
      return this.units.find(function (u) { return u.id === id; });
    },

    /* Convert value from any unit → grams */
    _toGrams(v, uid) { return v * this._getUnit(uid).toG; },

    /* Convert grams → any unit */
    _fromGrams(g, uid) { return g / this._getUnit(uid).toG; },

    /* Smart number formatter: avoids unnecessary trailing zeros */
    _fmt(v) {
      if (!isFinite(v)) return '—';
      if (v === 0) return '0';
      var abs = Math.abs(v);
      if (abs < 1e-9)  return v.toExponential(4);
      if (abs < 0.001) return parseFloat(v.toFixed(8)).toString();
      if (abs < 0.01)  return parseFloat(v.toFixed(6)).toString();
      if (abs < 1)     return parseFloat(v.toFixed(5)).toString();
      if (abs < 10)    return parseFloat(v.toFixed(4)).toString();
      if (abs < 1000)  return parseFloat(v.toFixed(4)).toString();
      if (abs < 1e6)   return parseFloat(v.toFixed(2)).toString();
      if (abs < 1e9)   return v.toLocaleString('en-US', {maximumFractionDigits: 0});
      return v.toExponential(4);
    },

    /* Format stone as "X st Y lb" */
    _stoneFmt(grams) {
      var totalLb  = grams / 453.59237;
      var stones   = Math.floor(totalLb / 14);
      var lbsRem   = Math.round((totalLb % 14) * 100) / 100;
      if (stones === 0) return null;
      return stones + ' st ' + parseFloat(lbsRem.toFixed(2)) + ' lb';
    },

    /* Real-world scale reference */
    _getScale(grams) {
      var a = grams;
      if (a <= 0)       return { icon: '•',  note: 'Zero weight.' };
      if (a < 0.5)      return { icon: '🪶', note: 'Lighter than a feather — a typical feather weighs ~0.01–0.1 g.' };
      if (a < 1.5)      return { icon: '📎', note: 'About the weight of a standard paperclip (~1 g).' };
      if (a < 3)        return { icon: '🍬', note: 'About the weight of a piece of candy or sugar sachet (~2–3 g).' };
      if (a < 6)        return { icon: '💶', note: 'Close to a €1 coin (~7.5 g) or a US penny (~2.5 g).' };
      if (a < 30)       return { icon: '🍪', note: 'About the weight of a large cookie or a AA battery (~24 g).' };
      if (a < 60)       return { icon: '🥚', note: 'About the weight of a large egg (~57–60 g).' };
      if (a < 120)      return { icon: '🍎', note: 'Close to the weight of a medium apple (~100–130 g).' };
      if (a < 300)      return { icon: '📱', note: 'Similar to a smartphone weight (150–250 g).' };
      if (a < 600)      return { icon: '🥤', note: 'About the weight of a standard can of soda (~355 g).' };
      if (a < 1100)     return { icon: '🍾', note: 'About the weight of a standard wine bottle with contents (~1.2 kg).' };
      if (a < 2500)     return { icon: '💻', note: 'Similar to a laptop computer (~1.5–2.5 kg).' };
      if (a < 5000)     return { icon: '🧳', note: 'Similar to a carry-on bag weight limit (~5–7 kg).' };
      if (a < 15000)    return { icon: '🎒', note: 'About the weight of a loaded hiking backpack (10–15 kg).' };
      if (a < 40000)    return { icon: '🧒', note: 'Similar to the weight of a young child (15–40 kg).' };
      if (a < 110000)   return { icon: '👤', note: 'In the range of adult human body weight (50–100 kg).' };
      if (a < 300000)   return { icon: '🏍️', note: 'Similar to a motorcycle (150–250 kg).' };
      if (a < 2000000)  return { icon: '🚗', note: 'In the range of a typical passenger car (1,000–2,000 kg).' };
      if (a < 8000000)  return { icon: '🐘', note: 'Close to the weight of an African elephant (~5,000–6,000 kg).' };
      if (a < 15000000) return { icon: '🚛', note: 'Close to a fully loaded semi-truck (max ~13,600 kg in EU).' };
      return { icon: '🚢', note: 'Extremely heavy — in the range of ships, trains, or industrial equipment.' };
    },

    // ── Core compute ─────────────────────────────
    _doCompute(inputVal) {
      var self  = this;
      var fromU = self._getUnit(self.fromUnit);
      var toU   = self._getUnit(self.toUnit);

      var grams   = self._toGrams(inputVal, self.fromUnit);
      var primary = self._fromGrams(grams, self.toUnit);

      // All-units grid
      var allUnits = self.units.map(function (u) {
        var converted = self._fromGrams(grams, u.id);
        var stoneFmt  = u.id === 'st' ? self._stoneFmt(grams) : null;
        return {
          id:       u.id,
          sym:      u.sym,
          name:     u.name,
          display:  self._fmt(converted),
          stoneFmt: stoneFmt,
          isSrc:    u.id === self.fromUnit,
          isDst:    u.id === self.toUnit,
        };
      });

      // Stone sub-display on hero (if toUnit is stone, or show it anyway as extra context)
      var stoneDisplay = null;
      if (self.toUnit === 'st') {
        stoneDisplay = self._stoneFmt(grams);
      }

      // Conversion factor
      var factor = fromU.toG / toU.toG;

      // Equation string
      var equationStr = self._fmt(inputVal) + ' ' + fromU.sym
        + '  ×  ' + self._fmt(factor)
        + '  =  ' + self._fmt(primary) + ' ' + toU.sym;

      // Formula card
      var formula = {
        label:     fromU.name + ' → ' + toU.name,
        expr:      '1 ' + fromU.sym + ' = ' + self._fmt(factor) + ' ' + toU.sym,
        via:       toU.sym + ' = (' + fromU.sym + ' × ' + self._fmt(fromU.toG) + ') ÷ ' + self._fmt(toU.toG),
        calcLabel: self._fmt(inputVal) + ' ' + fromU.sym + ' =',
        calcVal:   self._fmt(primary) + ' ' + toU.sym,
      };

      self.result = {
        primaryVal:   self._fmt(primary),
        fromDisplay:  self._fmt(inputVal) + ' ' + fromU.sym,
        toName:       toU.name,
        toFull:       toU.name + ' (' + toU.sym + ')',
        equationStr:  equationStr,
        stoneDisplay: stoneDisplay,
        allUnits:     allUnits,
        formula:      formula,
        scale:        self._getScale(grams),
      };
    },
  };
}
</script>
@endpush
