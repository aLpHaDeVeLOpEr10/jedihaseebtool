@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->seo_description ?? 'Convert between Celsius, Fahrenheit, Kelvin, and Rankine instantly. Free online temperature converter.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Temperature Converter  —  prefix: tc-
   Theme: Red / Orange (#b91c1c / #dc2626 / #ef4444)
══════════════════════════════════════════════ */

/* Unit selector pill */
.tc-unit-pill {
  display: flex; flex-direction: column; align-items: center;
  padding: .55rem .8rem; border-radius: .875rem; min-width: 60px;
  cursor: pointer; border: 2px solid #fecaca; background: #fff;
  color: #991b1b; font-weight: 800; transition: all .15s; gap: .1rem;
}
.tc-unit-pill:hover  { border-color: #f87171; background: #fff7f7; }
.tc-unit-pill.active { background: linear-gradient(135deg,#b91c1c,#dc2626); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(220,38,38,.35); }
.tc-unit-pill .sym   { font-size: 1.05rem; line-height: 1; }
.tc-unit-pill .name  { font-size: .58rem; letter-spacing: .03em; opacity: .8; }

/* Result card */
.tc-result-card {
  border-radius: 1.25rem; padding: 1.1rem 1rem;
  border: 2px solid; cursor: pointer;
  transition: all .2s; position: relative; overflow: hidden;
}
.tc-result-card:hover { transform: translateY(-3px); }
.tc-result-card .tc-rc-val  { font-size: 1.65rem; font-weight: 900; line-height: 1.1; word-break: break-all; }
.tc-result-card .tc-rc-unit { font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; opacity: .7; }
.tc-result-card .tc-rc-name { font-size: .7rem; font-weight: 700; opacity: .6; }
.tc-result-card .tc-rc-swap { position: absolute; top: .6rem; right: .7rem; font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; opacity: 0; transition: opacity .15s; background: rgba(255,255,255,.6); padding: .15rem .4rem; border-radius: 9999px; }
.tc-result-card:hover .tc-rc-swap { opacity: 1; }
.tc-result-card.tc-active-src { box-shadow: 0 0 0 3px rgba(220,38,38,.4); }

/* Celsius  */  .tc-card-c { background:#fff7f7; border-color:#fca5a5; color:#7f1d1d; }
/* Fahrenheit*/ .tc-card-f { background:#eff6ff; border-color:#93c5fd; color:#1e3a8a; }
/* Kelvin   */  .tc-card-k { background:#faf5ff; border-color:#c4b5fd; color:#2e1065; }
/* Rankine  */  .tc-card-r { background:#f0fdf4; border-color:#86efac; color:#14532d; }

/* Thermometer SVG */
.tc-thermo-wrap { display: flex; justify-content: center; padding: .5rem 0; }

/* Context badge */
.tc-context {
  border-radius: 1rem; padding: .65rem 1rem;
  display: flex; align-items: center; gap: .6rem; font-size: .82rem; font-weight: 700;
}

/* Formula row */
.tc-formula-row { display: flex; justify-content: space-between; align-items: center;
                   padding: .35rem .7rem; border-radius: .5rem; font-size: .78rem; }
.tc-formula-row:hover { background: #fff7f7; }
.tc-formula-row code { font-family: 'JetBrains Mono', 'Fira Code', monospace;
                        background: #fef2f2; color: #b91c1c; padding: .1rem .35rem; border-radius: .3rem; font-size: .73rem; }

/* Reference table row */
.tc-ref-row { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr 1fr;
               gap: .3rem; align-items: center; padding: .35rem .5rem; border-radius: .4rem; font-size: .74rem; }
.tc-ref-row:hover { background: #fff7f7; }
.tc-ref-row.tc-ref-head { font-size: .58rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }

/* Div line */
.tc-div { display: flex; align-items: center; gap: .6rem; color: #f87171;
           font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; }
.tc-div::before, .tc-div::after { content: ''; flex: 1; height: 1px; background: #fecaca; }

/* Preset pill */
.tc-preset { padding: .3rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
              background: #fff7f7; color: #b91c1c; border: 1px solid #fecaca; cursor: pointer; white-space: nowrap; transition: all .15s; }
.tc-preset:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

@keyframes tcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.tc-in { animation: tcIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="tcCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#991b1b,#dc2626)">
        <span class="text-3xl">🌡️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Temperature Converter</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Convert between Celsius, Fahrenheit, Kelvin, and Rankine instantly.
        Click any result to use it as the new input.
      </p>
    </div>

    {{-- Error banner --}}
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
            <span>🌡️</span> Enter Temperature
          </h2>

          {{-- Value field --}}
          <div>
            <label class="form-label">Temperature Value</label>
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

          {{-- From-unit pills --}}
          <div>
            <label class="form-label">From Unit</label>
            <div class="flex gap-2 flex-wrap">
              <template x-for="u in units" :key="u.id">
                <button
                  @click="fromUnit = u.id; autoConvert()"
                  :class="['tc-unit-pill', fromUnit === u.id ? 'active' : '']">
                  <span class="sym" x-text="u.sym"></span>
                  <span class="name" x-text="u.name"></span>
                </button>
              </template>
            </div>
          </div>
        </div>

        {{-- Presets card --}}
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2.5">Common Reference Temperatures</p>
          <div class="flex flex-wrap gap-1.5">
            <template x-for="p in presets" :key="p.label">
              <button @click="applyPreset(p)" class="tc-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-3">
          <button
            @click="doConvert()"
            class="btn btn-primary flex-1 py-3 text-base font-bold"
            style="background:linear-gradient(135deg,#991b1b,#dc2626)">
            Convert
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Info card --}}
        <div class="card p-4 text-xs text-gray-500 space-y-1.5"
             style="background:linear-gradient(135deg,#fff7f7,#fef2f2); border-color:#fecaca;">
          <p class="font-bold text-red-800 text-xs uppercase tracking-wide">💡 Quick Facts</p>
          <p><strong class="text-gray-700">-40°</strong> — the only point where °C equals °F</p>
          <p><strong class="text-gray-700">0 K</strong> — absolute zero, the coldest possible temperature</p>
          <p><strong class="text-gray-700">Rankine</strong> — used in US engineering; 0°R = 0 K</p>
        </div>

      </div>

      {{-- ══════════ RIGHT — RESULTS ══════════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle placeholder --}}
        <div x-show="phase === 'idle'" class="card p-14 text-center text-gray-400">
          <div class="text-5xl mb-4">🌡️</div>
          <p class="font-medium text-gray-500">Enter a temperature value above</p>
          <p class="text-sm mt-1">All four unit conversions will appear here instantly</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-red-200 border-t-red-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Converting…</p>
        </div>

        {{-- Results --}}
        <template x-if="phase === 'done'">
          <div class="space-y-5 tc-in">

            {{-- 4 unit result cards --}}
            <div class="grid grid-cols-2 gap-3">
              <template x-for="card in result.cards" :key="card.id">
                <button
                  @click="useAsInput(card)"
                  :class="['tc-result-card tc-card-' + card.id.toLowerCase(), card.isSource ? 'tc-active-src' : '']"
                  type="button">
                  <div class="tc-rc-unit" x-text="card.sym + '  ' + card.name"></div>
                  <div class="tc-rc-val" x-text="card.display"></div>
                  <div class="tc-rc-name" x-text="card.exact !== card.display ? '= ' + card.exact : ''"></div>
                  <div class="tc-rc-swap" x-show="!card.isSource">↑ use as input</div>
                  <div class="tc-rc-swap" x-show="card.isSource" style="opacity:1!important">● current input</div>
                </button>
              </template>
            </div>

            {{-- Thermometer + Context --}}
            <div class="card p-5 flex gap-5 items-start">

              {{-- SVG thermometer --}}
              <div class="tc-thermo-wrap flex-shrink-0">
                <svg viewBox="0 0 56 230" width="48" xmlns="http://www.w3.org/2000/svg">
                  <!-- Tube background -->
                  <rect x="20" y="8" width="16" height="170" rx="8"
                        fill="#f3f4f6" stroke="#d1d5db" stroke-width="1.5"/>
                  <!-- Tube fill (dynamic) -->
                  <clipPath id="tc-tube-clip">
                    <rect x="20" y="8" width="16" height="170" rx="8"/>
                  </clipPath>
                  <rect
                    x="20"
                    :y="String(178 - result.thermo.fillH)"
                    width="16"
                    :height="String(result.thermo.fillH)"
                    rx="0"
                    :fill="result.thermo.color"
                    clip-path="url(#tc-tube-clip)"
                  />
                  <!-- Bulb background -->
                  <circle cx="28" cy="198" r="20" fill="#f3f4f6" stroke="#d1d5db" stroke-width="1.5"/>
                  <!-- Bulb fill -->
                  <circle cx="28" cy="198" r="16.5" :fill="result.thermo.color"/>
                  <!-- Glass shine -->
                  <ellipse cx="24" cy="14" rx="3" ry="6" fill="rgba(255,255,255,0.45)" rx="3"/>
                  <!-- Tick marks -->
                  <line x1="36" y1="18"  x2="44" y2="18"  stroke="#9ca3af" stroke-width="1" stroke-linecap="round"/>
                  <line x1="36" y1="60"  x2="42" y2="60"  stroke="#9ca3af" stroke-width="1" stroke-linecap="round"/>
                  <line x1="36" y1="101" x2="44" y2="101" stroke="#9ca3af" stroke-width="1" stroke-linecap="round"/>
                  <line x1="36" y1="143" x2="42" y2="143" stroke="#9ca3af" stroke-width="1" stroke-linecap="round"/>
                  <line x1="36" y1="178" x2="44" y2="178" stroke="#9ca3af" stroke-width="1" stroke-linecap="round"/>
                </svg>
              </div>

              {{-- Context info --}}
              <div class="flex-1 space-y-3">
                <div class="tc-context" :style="'background:' + result.context.bg + '; color:' + result.context.fg">
                  <span class="text-xl" x-text="result.context.icon"></span>
                  <div>
                    <p class="font-bold" x-text="result.context.label"></p>
                    <p class="text-xs font-normal opacity-80" x-text="result.context.note"></p>
                  </div>
                </div>

                {{-- Key equivalences --}}
                <div class="space-y-0.5">
                  <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">In perspective</p>
                  <template x-for="note in result.notes" :key="note">
                    <p class="text-xs text-gray-600 flex items-start gap-1">
                      <span class="text-red-400 mt-0.5 flex-shrink-0">•</span>
                      <span x-text="note"></span>
                    </p>
                  </template>
                </div>
              </div>
            </div>

            {{-- Conversion formulas --}}
            <div class="card p-5">
              <p class="tc-div mb-3">Conversion Formulas from <span x-text="result.fromName" class="font-black"></span></p>
              <template x-for="f in result.formulas" :key="f.label">
                <div class="tc-formula-row">
                  <span class="text-gray-600 font-medium" x-text="f.label"></span>
                  <code x-text="f.formula"></code>
                </div>
              </template>
            </div>

            {{-- Reference points table --}}
            <div class="card p-5">
              <p class="tc-div mb-3">Common Reference Points</p>
              <div class="tc-ref-row tc-ref-head">
                <span>Reference</span>
                <span>°C</span>
                <span>°F</span>
                <span>K</span>
                <span>°R</span>
              </div>
              <template x-for="ref in refPoints" :key="ref.label">
                <div class="tc-ref-row" :class="ref.highlight ? 'bg-red-50 rounded-lg font-semibold' : ''">
                  <span class="text-gray-700" x-text="ref.icon + ' ' + ref.label"></span>
                  <span class="font-mono text-gray-800" x-text="ref.c"></span>
                  <span class="font-mono text-gray-800" x-text="ref.f"></span>
                  <span class="font-mono text-gray-800" x-text="ref.k"></span>
                  <span class="font-mono text-gray-800" x-text="ref.r"></span>
                </div>
              </template>
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
/* ──────────────────────────────────────────────────────────
   Temperature Converter — Alpine.js component
   CSS prefix: tc-   Theme: Red / Orange
   Supports: Celsius (C), Fahrenheit (F), Kelvin (K), Rankine (R)
────────────────────────────────────────────────────────── */
function tcCalc() {
  return {

    // ── Inputs ─────────────────────────────────
    value:    '',
    fromUnit: 'C',

    // ── State ──────────────────────────────────
    phase:    'idle',   // idle | loading | done
    errorMsg: '',
    result:   null,

    // ── Unit definitions ───────────────────────
    units: [
      { id: 'C', sym: '°C', name: 'Celsius'    },
      { id: 'F', sym: '°F', name: 'Fahrenheit' },
      { id: 'K', sym: 'K',  name: 'Kelvin'     },
      { id: 'R', sym: '°R', name: 'Rankine'    },
    ],

    // ── Presets (value in Celsius) ─────────────
    presets: [
      { label: 'Absolute Zero',  c: -273.15, unit: 'C' },
      { label: 'Water Freezing', c: 0,       unit: 'C' },
      { label: 'Room Temp',      c: 20,      unit: 'C' },
      { label: 'Body Temp',      c: 37,      unit: 'C' },
      { label: 'Water Boiling',  c: 100,     unit: 'C' },
      { label: 'Oven 350°F',     c: 176.67,  unit: 'C' },
      { label: '-40° (C=F)',     c: -40,     unit: 'C' },
      { label: 'Lava (~1000°C)', c: 1000,    unit: 'C' },
    ],

    // ── Reference points table ─────────────────
    refPoints: (function () {
      function fmtR(v, d) { return parseFloat(v.toFixed(d)).toString(); }
      var pts = [
        { label: 'Absolute Zero',    icon: '🔬', c: -273.15 },
        { label: 'Coldest on Earth', icon: '🥶', c: -89.2   },
        { label: '-40° (C = F)',     icon: '❄️',  c: -40     },
        { label: 'Water Freezing',   icon: '💧', c: 0       },
        { label: 'Room Temperature', icon: '🏠', c: 20      },
        { label: 'Human Body',       icon: '🫀', c: 37      },
        { label: 'Hottest on Earth', icon: '☀️', c: 56.7    },
        { label: 'Water Boiling',    icon: '♨️', c: 100     },
        { label: 'Lava',             icon: '🌋', c: 1000    },
        { label: 'Sun Surface',      icon: '⭐', c: 5778    },
      ];
      return pts.map(function (p) {
        var f = p.c * 9 / 5 + 32;
        var k = p.c + 273.15;
        var r = (p.c + 273.15) * 9 / 5;
        return {
          label: p.label, icon: p.icon,
          c: fmtR(p.c, 2),
          f: fmtR(f, 2),
          k: fmtR(k, 2),
          r: fmtR(r, 2),
          highlight: false,
        };
      });
    }()),

    // ── Lifecycle ──────────────────────────────
    init() { /* nothing on mount */ },

    // ── Actions ────────────────────────────────
    applyPreset(p) {
      this.value    = String(p.c);
      this.fromUnit = p.unit;
      this.doConvert();
    },

    useAsInput(card) {
      if (card.isSource) return;
      this.value    = card.raw;        // raw numeric string
      this.fromUnit = card.id;
      this.doConvert();
    },

    reset() {
      this.value    = '';
      this.fromUnit = 'C';
      this.phase    = 'idle';
      this.errorMsg = '';
      this.result   = null;
    },

    autoConvert() {
      var v = parseFloat(this.value);
      if (this.value !== '' && !isNaN(v)) this.doConvert();
    },

    doConvert() {
      this.errorMsg = '';

      // ── Validate input ───────────────────────
      if (this.value === '' || this.value === null) {
        this.errorMsg = 'Please enter a temperature value.';
        return;
      }
      var v = parseFloat(this.value);
      if (isNaN(v)) {
        this.errorMsg = 'Please enter a valid numeric temperature.';
        return;
      }

      // Absolute zero checks per unit
      var absZeroC = -273.15;
      var celsiusVal = this._toC(v, this.fromUnit);
      if (celsiusVal < absZeroC - 0.0001) {
        var limitStr = '';
        if (this.fromUnit === 'C') limitStr = '−273.15 °C';
        else if (this.fromUnit === 'F') limitStr = '−459.67 °F';
        else if (this.fromUnit === 'K') limitStr = '0 K';
        else limitStr = '0 °R';
        this.errorMsg = 'Temperature cannot be below absolute zero (' + limitStr + ').';
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

    // ── Core conversion ────────────────────────

    /* Convert any unit → Celsius */
    _toC(v, unit) {
      switch (unit) {
        case 'C': return v;
        case 'F': return (v - 32) * 5 / 9;
        case 'K': return v - 273.15;
        case 'R': return (v - 491.67) * 5 / 9;
      }
    },

    /* Convert Celsius → any unit */
    _fromC(c, unit) {
      switch (unit) {
        case 'C': return c;
        case 'F': return c * 9 / 5 + 32;
        case 'K': return c + 273.15;
        case 'R': return (c + 273.15) * 9 / 5;
      }
    },

    // ── Format helpers ─────────────────────────
    _fmt(v, full) {
      if (!isFinite(v)) return '—';
      var abs = Math.abs(v);
      var decimals = abs >= 10000 ? 1 : abs >= 1000 ? 2 : abs >= 100 ? 3 : abs >= 10 ? 4 : 5;
      if (full) decimals = Math.max(decimals, 6);
      // Round to avoid -0.00000
      var str = parseFloat(v.toFixed(decimals)).toString();
      return str;
    },

    // ── Main compute ───────────────────────────
    _doCompute(inputVal) {
      var self = this;
      var celsiusVal = self._toC(inputVal, self.fromUnit);

      // All four values
      var vals = {
        C: celsiusVal,
        F: self._fromC(celsiusVal, 'F'),
        K: self._fromC(celsiusVal, 'K'),
        R: self._fromC(celsiusVal, 'R'),
      };

      // Unit meta
      var unitMeta = {
        C: { sym: '°C', name: 'Celsius'    },
        F: { sym: '°F', name: 'Fahrenheit' },
        K: { sym: 'K',  name: 'Kelvin'     },
        R: { sym: '°R', name: 'Rankine'    },
      };

      // Build result cards
      var cards = ['C', 'F', 'K', 'R'].map(function (id) {
        var raw     = self._fmt(vals[id], false);
        var display = raw;
        var exact   = self._fmt(vals[id], true);
        return {
          id:       id,
          sym:      unitMeta[id].sym,
          name:     unitMeta[id].name,
          raw:      raw,
          display:  display,
          exact:    exact,
          isSource: id === self.fromUnit,
        };
      });

      // ── Thermometer visual ─────────────────
      // Map -100°C … 200°C → 0 … 170 (tube height)
      var c = celsiusVal;
      var fillH = Math.max(0, Math.min(170, ((c + 100) / 300) * 170));

      var thermoColor = c <= -60  ? '#1d4ed8'   // deep blue
                      : c <= -20  ? '#3b82f6'   // blue
                      : c <= 0    ? '#60a5fa'   // light blue
                      : c <= 15   ? '#34d399'   // teal/green
                      : c <= 25   ? '#4ade80'   // green
                      : c <= 35   ? '#fbbf24'   // yellow
                      : c <= 50   ? '#fb923c'   // orange
                      : c <= 100  ? '#ef4444'   // red
                      : '#991b1b';              // deep red

      // ── Context badge ──────────────────────
      var ctx;
      if (c <= -273.15) {
        ctx = { icon: '🔬', label: 'Absolute Zero',    note: 'The theoretical minimum temperature — particles stop moving.', bg: '#eff6ff', fg: '#1e3a8a' };
      } else if (c <= -89) {
        ctx = { icon: '🥶', label: 'Colder than Earth\'s record low', note: 'Antarctica\'s coldest recorded: −89.2°C (−128.6°F).', bg: '#eff6ff', fg: '#1e40af' };
      } else if (c <= -40) {
        ctx = { icon: '❄️',  label: 'Extreme Arctic cold', note: 'At −40°, Celsius and Fahrenheit are equal.', bg: '#f0f9ff', fg: '#075985' };
      } else if (c <= -20) {
        ctx = { icon: '🌨️', label: 'Severe winter cold',  note: 'Frostbite risk within minutes without proper clothing.', bg: '#f0f9ff', fg: '#0369a1' };
      } else if (c <= 0) {
        ctx = { icon: '❄️',  label: 'Below freezing',      note: 'Water freezes at 0°C (32°F). Ice forms.', bg: '#e0f2fe', fg: '#0284c7' };
      } else if (c <= 10) {
        ctx = { icon: '🌨️', label: 'Cold weather',         note: 'Coat weather — feels chilly outdoors.', bg: '#ecfdf5', fg: '#065f46' };
      } else if (c <= 20) {
        ctx = { icon: '🌤️', label: 'Cool / mild',          note: 'Comfortable for outdoor activities with light layers.', bg: '#f0fdf4', fg: '#166534' };
      } else if (c <= 26) {
        ctx = { icon: '😊', label: 'Comfortable room temperature', note: 'Ideal indoor climate for most people (20–26°C).', bg: '#f0fdf4', fg: '#15803d' };
      } else if (c <= 35) {
        ctx = { icon: '☀️',  label: 'Warm day',             note: 'Drink water and stay in the shade. Feels pleasant.', bg: '#fefce8', fg: '#854d0e' };
      } else if (c <= 40) {
        ctx = { icon: '🌡️', label: 'Very warm / mild fever', note: 'Normal human body temperature is ~37°C (98.6°F).', bg: '#fff7ed', fg: '#9a3412' };
      } else if (c <= 56.7) {
        ctx = { icon: '🥵', label: 'Dangerously hot',       note: 'Earth\'s recorded maximum: 56.7°C (134°F) in Death Valley.', bg: '#fef2f2', fg: '#991b1b' };
      } else if (c <= 100) {
        ctx = { icon: '🔥', label: 'Well above survival range', note: 'Water boils at 100°C (212°F) at sea level.', bg: '#fef2f2', fg: '#7f1d1d' };
      } else if (c <= 660) {
        ctx = { icon: '🔥', label: 'Extreme heat',          note: 'Aluminum melts at ~660°C; lead at ~327°C.', bg: '#fef2f2', fg: '#7f1d1d' };
      } else if (c <= 1538) {
        ctx = { icon: '🌋', label: 'Molten rock / lava',    note: 'Iron melts at ~1538°C; lava erupts at 700–1200°C.', bg: '#fef2f2', fg: '#450a0a' };
      } else {
        ctx = { icon: '⭐', label: 'Astronomical / industrial', note: 'Sun\'s surface: ~5778°C. Sun\'s core: ~15,000,000°C.', bg: '#fdf4ff', fg: '#4a044e' };
      }

      // ── Perspective notes ──────────────────
      var notes = [];
      var c2 = Math.round(celsiusVal * 100) / 100;
      if (Math.abs(c2 - (-40)) < 0.5) notes.push('Exactly where °C and °F are equal (−40).');
      if (Math.abs(c2 - 0) < 1)       notes.push('Water freezes at this temperature at sea level.');
      if (Math.abs(c2 - 37) < 1)      notes.push('Average healthy human body temperature.');
      if (Math.abs(c2 - 100) < 1)     notes.push('Water boils at this temperature at sea level (1 atm).');
      if (Math.abs(c2 - 20) < 2)      notes.push('Typical comfortable room temperature range.');
      if (notes.length === 0) {
        // Contextual notes based on range
        if (c < 0)    notes.push('Celsius and Fahrenheit are equal at exactly −40°.');
        else if (c < 100) notes.push('Body temperature is 37°C (98.6°F). Water boils at 100°C (212°F).');
        notes.push('Kelvin = °C + 273.15. Rankine = °F + 459.67.');
      }

      // ── Conversion formulas ────────────────
      var formulaMap = {
        C: [
          { label: '°C → °F', formula: '°F = (°C × 9/5) + 32' },
          { label: '°C → K',  formula: 'K  = °C + 273.15' },
          { label: '°C → °R', formula: '°R = (°C + 273.15) × 9/5' },
        ],
        F: [
          { label: '°F → °C', formula: '°C = (°F − 32) × 5/9' },
          { label: '°F → K',  formula: 'K  = (°F − 32) × 5/9 + 273.15' },
          { label: '°F → °R', formula: '°R = °F + 459.67' },
        ],
        K: [
          { label: 'K → °C',  formula: '°C = K − 273.15' },
          { label: 'K → °F',  formula: '°F = (K − 273.15) × 9/5 + 32' },
          { label: 'K → °R',  formula: '°R = K × 9/5' },
        ],
        R: [
          { label: '°R → °C', formula: '°C = (°R − 491.67) × 5/9' },
          { label: '°R → °F', formula: '°F = °R − 459.67' },
          { label: '°R → K',  formula: 'K  = °R × 5/9' },
        ],
      };

      // Highlight matching reference point row
      self.refPoints.forEach(function (r) {
        r.highlight = Math.abs(parseFloat(r.c) - celsiusVal) < 0.05;
      });

      self.result = {
        cards:    cards,
        thermo:   { fillH: Math.round(fillH), color: thermoColor },
        context:  ctx,
        notes:    notes,
        formulas: formulaMap[self.fromUnit],
        fromName: unitMeta[self.fromUnit].name,
      };
    },
  };
}
</script>
@endpush
