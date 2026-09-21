@extends('layouts.public')
@section('renders_own_breadcrumb', '1')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)
@section('renders_own_content_sections', '1')
@section('renders_own_faqs', '1')

@section('content')
<style>
/* ══════════════════════════════════════════════════════════
   Water Intake Calculator  —  prefix: wi-
   Brand: sky / cyan
   All calculations are 100 % client-side.
══════════════════════════════════════════════════════════ */

/* Toggle pill */
.wi-pill { display:flex; background:#f1f5f9; border-radius:.625rem; padding:.2rem; gap:.15rem; }
.wi-pill-btn { flex:1; padding:.4rem .6rem; border-radius:.45rem; font-size:.78rem; font-weight:600; color:#64748b; cursor:pointer; border:none; background:none; transition:all .15s; white-space:nowrap; text-align:center; }
.wi-pill-btn:hover { color:#0369a1; }
.wi-pill-btn.wi-on { background:#fff; color:#0284c7; box-shadow:0 1px 4px rgba(0,0,0,.1); }

/* Input with unit prefix */
.wi-pre-wrap { display:flex; align-items:stretch; }
.wi-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.8rem; font-weight:700; color:#374151; white-space:nowrap; min-width:3.1rem; justify-content:center; }
.wi-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

.wi-select { width:100%; padding:.55rem .85rem; border:1px solid #d1d5db; border-radius:.75rem; font-size:.85rem; color:#374151; background:#fff; outline:none; cursor:pointer; }
.wi-select:focus { border-color:#38bdf8; box-shadow:0 0 0 3px rgba(56,189,248,.18); }

.wi-range { width:100%; accent-color:#0284c7; cursor:pointer; }

/* Section divider */
.wi-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.wi-div::before,.wi-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* Option cards (climate / intensity) */
.wi-opt-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.5rem; }
.wi-opt { border:1.5px solid #e2e8f0; border-radius:.75rem; padding:.55rem .65rem; cursor:pointer; background:#fff; transition:all .13s; text-align:left; }
.wi-opt:hover { border-color:#7dd3fc; background:#f0f9ff; }
.wi-opt.wi-on { border-color:#0ea5e9; background:#e0f2fe; }
.wi-opt-t { font-size:.78rem; font-weight:700; color:#1f2937; display:block; }
.wi-opt-d { font-size:.66rem; color:#94a3b8; display:block; margin-top:.1rem; line-height:1.3; }

/* Checkbox rows */
.wi-check { display:flex; align-items:flex-start; gap:.6rem; padding:.55rem .7rem; border:1.5px solid #e2e8f0; border-radius:.75rem; cursor:pointer; transition:all .13s; }
.wi-check:hover { border-color:#7dd3fc; }
.wi-check.wi-on { border-color:#0ea5e9; background:#f0f9ff; }
.wi-check input { margin-top:.15rem; accent-color:#0284c7; width:1rem; height:1rem; flex-shrink:0; }
.wi-check-t { font-size:.8rem; font-weight:600; color:#1f2937; }
.wi-check-d { font-size:.68rem; color:#94a3b8; }

/* Primary button */
.wi-btn { display:inline-flex; align-items:center; justify-content:center; gap:.45rem; padding:.75rem 1.4rem; border-radius:.8rem; font-weight:800; font-size:.95rem; color:#fff; border:none; cursor:pointer; background:linear-gradient(135deg,#0369a1,#0284c7,#0ea5e9); box-shadow:0 4px 14px rgba(2,132,199,.32); transition:all .15s; }
.wi-btn:hover { box-shadow:0 6px 20px rgba(2,132,199,.45); transform:translateY(-1px); }

/* Hero result */
.wi-hero-card { background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 55%,#cffafe 100%); }
.wi-hero { font-size:clamp(2.6rem,6vw,3.9rem); font-weight:900; line-height:1; letter-spacing:-.03em; background:linear-gradient(135deg,#0369a1 0%,#0284c7 50%,#06b6d4 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.wi-hero-unit { font-size:1.2rem; font-weight:800; color:#0284c7; margin-left:.3rem; }

/* Stat cards */
.wi-stats { display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; }
@media (min-width:640px) { .wi-stats { grid-template-columns:repeat(4,1fr); } }
.wi-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:1rem; padding:.85rem .6rem; text-align:center; }
.wi-stat-lbl { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.wi-stat-val { font-size:1.3rem; font-weight:800; color:#0369a1; line-height:1.2; margin-top:.2rem; }
.wi-stat-sub { font-size:.64rem; color:#94a3b8; margin-top:.1rem; }

/* Glass visual */
.wi-glasses { display:flex; flex-wrap:wrap; gap:.3rem; }
.wi-glass { width:1.35rem; height:1.75rem; border:2px solid #7dd3fc; border-top:none; border-radius:0 0 .35rem .35rem; position:relative; overflow:hidden; background:#fff; }
.wi-glass-fill { position:absolute; left:0; right:0; bottom:0; background:linear-gradient(180deg,#38bdf8,#0284c7); }

/* Breakdown bar */
.wi-bar { height:16px; border-radius:9999px; overflow:hidden; display:flex; background:#f1f5f9; }
.wi-bar > div { height:100%; transition:width .5s ease; }
.wi-dot { width:.6rem; height:.6rem; border-radius:50%; display:inline-block; flex-shrink:0; }
.wi-row { display:flex; justify-content:space-between; align-items:center; gap:.75rem; padding:.45rem .6rem; border-radius:.5rem; font-size:.82rem; }
.wi-row:hover { background:#f8fafc; }

/* Schedule */
.wi-sched { display:grid; grid-template-columns:repeat(2,1fr); gap:.5rem; }
@media (min-width:640px) { .wi-sched { grid-template-columns:repeat(4,1fr); } }
.wi-slot { border:1.5px solid #e0f2fe; background:#f8fcff; border-radius:.7rem; padding:.45rem .6rem; display:flex; justify-content:space-between; align-items:center; gap:.4rem; }
.wi-slot-time { font-size:.8rem; font-weight:700; color:#0c4a6e; font-variant-numeric:tabular-nums; }
.wi-slot-amt { font-size:.7rem; color:#0284c7; font-weight:600; white-space:nowrap; }

/* Tracker ring */
.wi-ring { width:7.5rem; height:7.5rem; flex-shrink:0; }
.wi-ring circle { fill:none; stroke-width:10; }
.wi-ring .wi-ring-bg { stroke:#e0f2fe; }
.wi-ring .wi-ring-fg { stroke:#0284c7; stroke-linecap:round; transition:stroke-dashoffset .4s ease; transform:rotate(-90deg); transform-origin:50% 50%; }
.wi-round-btn { width:2.6rem; height:2.6rem; border-radius:9999px; border:1.5px solid #bae6fd; background:#fff; color:#0284c7; font-size:1.3rem; font-weight:800; cursor:pointer; transition:all .13s; display:flex; align-items:center; justify-content:center; }
.wi-round-btn:hover { background:#e0f2fe; }
.wi-round-btn.wi-plus { background:#0284c7; color:#fff; border-color:#0284c7; }
.wi-round-btn.wi-plus:hover { background:#0369a1; }

/* Alerts */
.wi-error { display:flex; align-items:flex-start; gap:.5rem; padding:.7rem .9rem; border-radius:.75rem; background:#fef2f2; border:1.5px solid #fecaca; font-size:.8rem; color:#991b1b; font-weight:500; }
.wi-warn { display:flex; align-items:flex-start; gap:.5rem; padding:.7rem .9rem; border-radius:.75rem; background:#fffbeb; border:1.5px solid #fde68a; font-size:.78rem; color:#92400e; }
.wi-note { padding:.7rem .9rem; border-radius:.75rem; background:#f0f9ff; border:1px solid #bae6fd; font-size:.75rem; color:#075985; }

.wi-feat { display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; }
@media (min-width:640px) { .wi-feat { grid-template-columns:repeat(3,1fr); } }

/* Utilities used on this page that are not in the prebuilt Tailwind bundle */
.max-w-screen-xl { max-width:1280px; }
.w-14 { width:3.5rem; } .h-14 { height:3.5rem; }
.pt-1 { padding-top:.25rem; } .pt-2 { padding-top:.5rem; } .pt-5 { padding-top:1.25rem; } .pb-5 { padding-bottom:1.25rem; }
.mb-1\.5 { margin-bottom:.375rem; }
.gap-x-4 { column-gap:1rem; } .gap-y-1 { row-gap:.25rem; }
.space-y-0\.5 > :not([hidden]) ~ :not([hidden]) { margin-top:.125rem; }
.tracking-widest { letter-spacing:.1em; }
.whitespace-nowrap { white-space:nowrap; }
.leading-snug { line-height:1.375; }
.no-underline { text-decoration-line:none; }
@media (min-width:768px) {
  .md\:py-8 { padding-top:2rem; padding-bottom:2rem; }
  .md\:py-10 { padding-top:2.5rem; padding-bottom:2.5rem; }
}
@media (min-width:1024px) {
  .lg\:grid-cols-5 { grid-template-columns:repeat(5,minmax(0,1fr)); }
  .lg\:col-span-3 { grid-column:span 3 / span 3; }
}

@keyframes wiIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.wi-in { animation:wiIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="text-3xl w-14 h-14 flex items-center justify-center rounded-xl" style="background:#e0f2fe">
          {{ $tool->icon ?? '💧' }}
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">Find out how much water you should drink each day based on your weight, age, activity, climate and life stage — with a drinking schedule and daily tracker.</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]"/>
    </div>
  </div>

  <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8" x-data="wiCalc()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

      {{-- ═══════════════ LEFT — Inputs ═══════════════ --}}
      <div class="lg:col-span-2">
        <div class="card">
          <div class="px-5 pt-5 pb-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Your Details</p>
          </div>

          <form class="px-5 pb-5 space-y-4" @submit.prevent="calculate(true)" novalidate>

            {{-- Units --}}
            <div>
              <span class="form-label">Unit System</span>
              <div class="wi-pill" role="group" aria-label="Unit system">
                <button type="button" class="wi-pill-btn" :class="{ 'wi-on': units === 'metric' }" :aria-pressed="units === 'metric'" @click="switchUnits('metric')">🌍 Metric (kg, L)</button>
                <button type="button" class="wi-pill-btn" :class="{ 'wi-on': units === 'imperial' }" :aria-pressed="units === 'imperial'" @click="switchUnits('imperial')">🇺🇸 Imperial (lb, oz)</button>
              </div>
            </div>

            {{-- Sex --}}
            <div>
              <span class="form-label">Sex</span>
              <div class="wi-pill" role="group" aria-label="Sex">
                <button type="button" class="wi-pill-btn" :class="{ 'wi-on': sex === 'male' }" :aria-pressed="sex === 'male'" @click="sex = 'male'; lifeStage = 'none'; autoCalc()">♂ Male</button>
                <button type="button" class="wi-pill-btn" :class="{ 'wi-on': sex === 'female' }" :aria-pressed="sex === 'female'" @click="sex = 'female'; autoCalc()">♀ Female</button>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              {{-- Age --}}
              <div>
                <label class="form-label" for="wi-age">Age</label>
                <div class="wi-pre-wrap">
                  <span class="wi-pre">yrs</span>
                  <input id="wi-age" type="number" inputmode="numeric" step="1" min="4" max="100"
                         x-model="age" @input.debounce.300ms="autoCalc()" class="form-input" placeholder="30">
                </div>
              </div>
              {{-- Weight --}}
              <div>
                <label class="form-label" for="wi-weight">Weight</label>
                <div class="wi-pre-wrap">
                  <span class="wi-pre" x-text="units === 'metric' ? 'kg' : 'lb'"></span>
                  <input id="wi-weight" type="number" inputmode="decimal" step="any" min="1"
                         x-model="weight" @input.debounce.300ms="autoCalc()" class="form-input"
                         :placeholder="units === 'metric' ? '70' : '155'">
                </div>
              </div>
            </div>
            <p class="form-help -mt-2" x-show="weightHint" x-text="weightHint"></p>

            <div class="wi-div pt-1">Exercise</div>

            {{-- Exercise minutes --}}
            <div>
              <label class="form-label" for="wi-ex">
                Exercise per day — <span class="font-bold" style="color:#0284c7" x-text="exerciseMin + ' min'"></span>
              </label>
              <input id="wi-ex" type="range" min="0" max="240" step="5" x-model.number="exerciseMin" @input="autoCalc()" class="wi-range">
              <div class="flex justify-between text-xs text-gray-400 mt-1"><span>None</span><span>2 h</span><span>4 h</span></div>
            </div>

            {{-- Intensity --}}
            <div x-show="exerciseMin > 0" x-transition>
              <span class="form-label">Exercise Intensity</span>
              <div class="wi-opt-grid" style="grid-template-columns:repeat(3,1fr)">
                <template x-for="it in intensities" :key="it.value">
                  <button type="button" class="wi-opt" :class="{ 'wi-on': intensity === it.value }" :aria-pressed="intensity === it.value" @click="intensity = it.value; autoCalc()">
                    <span class="wi-opt-t" x-text="it.label"></span>
                    <span class="wi-opt-d" x-text="it.desc"></span>
                  </button>
                </template>
              </div>
            </div>

            <div class="wi-div pt-1">Environment</div>

            {{-- Climate --}}
            <div>
              <span class="form-label">Climate</span>
              <div class="wi-opt-grid">
                <template x-for="c in climates" :key="c.value">
                  <button type="button" class="wi-opt" :class="{ 'wi-on': climate === c.value }" :aria-pressed="climate === c.value" @click="climate = c.value; autoCalc()">
                    <span class="wi-opt-t" x-text="c.label"></span>
                    <span class="wi-opt-d" x-text="c.desc"></span>
                  </button>
                </template>
              </div>
            </div>

            <label class="wi-check" :class="{ 'wi-on': altitude }">
              <input type="checkbox" x-model="altitude" @change="autoCalc()">
              <span>
                <span class="wi-check-t block">High altitude</span>
                <span class="wi-check-d block">Living or training above ~2,500 m (8,200 ft)</span>
              </span>
            </label>

            <label class="wi-check" :class="{ 'wi-on': illness }">
              <input type="checkbox" x-model="illness" @change="autoCalc()">
              <span>
                <span class="wi-check-t block">Fever, vomiting or diarrhoea</span>
                <span class="wi-check-d block">Extra fluid to replace losses — see a doctor if it persists</span>
              </span>
            </label>

            {{-- Life stage --}}
            <div x-show="sex === 'female' && ageNum >= 14" x-transition>
              <label class="form-label" for="wi-life">Pregnancy / Breastfeeding</label>
              <select id="wi-life" x-model="lifeStage" @change="autoCalc()" class="wi-select">
                <option value="none">Not applicable</option>
                <option value="pregnant">Pregnant (+300 ml/day)</option>
                <option value="breastfeeding">Breastfeeding (+700 ml/day)</option>
              </select>
            </div>

            <div class="wi-div pt-1">Schedule</div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="form-label" for="wi-wake">Wake up</label>
                <input id="wi-wake" type="time" x-model="wakeTime" @change="autoCalc()" class="form-input">
              </div>
              <div>
                <label class="form-label" for="wi-sleep">Bedtime</label>
                <input id="wi-sleep" type="time" x-model="sleepTime" @change="autoCalc()" class="form-input">
              </div>
            </div>

            <div>
              <label class="form-label" for="wi-glass">Glass / Bottle Size</label>
              <select id="wi-glass" x-model="glassMl" @change="autoCalc()" class="wi-select">
                <option value="200">Small glass — 200 ml (6.8 oz)</option>
                <option value="250">Glass — 250 ml (8.5 oz)</option>
                <option value="237">US cup — 237 ml (8 oz)</option>
                <option value="330">Can size — 330 ml (11.2 oz)</option>
                <option value="500">Bottle — 500 ml (16.9 oz)</option>
                <option value="750">Sports bottle — 750 ml (25.4 oz)</option>
                <option value="1000">Large bottle — 1 L (33.8 oz)</option>
              </select>
            </div>

            {{-- Error --}}
            <div x-show="error" x-transition role="alert" class="wi-error">
              <span>⚠</span><span x-text="error"></span>
            </div>

            <div class="flex flex-wrap gap-2 pt-1">
              <button type="submit" class="wi-btn flex-1">💧 Calculate</button>
              <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
              <button type="button" @click="resetAll()" x-show="result || error" class="btn btn-secondary">✕ Reset</button>
            </div>
          </form>
        </div>
      </div>

      {{-- ═══════════════ RIGHT — Results ═══════════════ --}}
      <div class="lg:col-span-3 space-y-4" id="wi-results" aria-live="polite">

        <template x-if="result">
          <div class="space-y-4 wi-in">

            {{-- Hero --}}
            <div class="card overflow-hidden">
              <div class="wi-hero-card px-6 py-6">
                <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#0284c7">Recommended Daily Water Intake</p>
                <div class="flex flex-wrap items-end gap-x-4 gap-y-1">
                  <p><span class="wi-hero" x-text="primaryAmount"></span><span class="wi-hero-unit" x-text="primaryUnit"></span></p>
                  <p class="text-sm font-semibold text-gray-500 pb-1" x-text="secondaryAmount"></p>
                </div>
                <p class="text-sm text-gray-600 mt-3">
                  That's about <strong x-text="r.glasses"></strong>
                  <span x-text="glassLabel"></span> spread across your day, from drinks
                  (water, tea, milk and other beverages).
                </p>
                <div class="wi-glasses mt-4" aria-hidden="true">
                  <template x-for="g in glassIcons" :key="g.i">
                    <div class="wi-glass"><div class="wi-glass-fill" :style="'height:' + g.fill + '%'"></div></div>
                  </template>
                </div>
              </div>
            </div>

            {{-- Stats --}}
            <div class="wi-stats">
              <div class="wi-stat">
                <div class="wi-stat-lbl">Litres</div>
                <div class="wi-stat-val" x-text="fmt(r.ml / 1000, 2)"></div>
                <div class="wi-stat-sub">L per day</div>
              </div>
              <div class="wi-stat">
                <div class="wi-stat-lbl">Fluid Ounces</div>
                <div class="wi-stat-val" x-text="fmt(r.ml / ML_PER_OZ, 0)"></div>
                <div class="wi-stat-sub">US fl oz per day</div>
              </div>
              <div class="wi-stat">
                <div class="wi-stat-lbl">Cups</div>
                <div class="wi-stat-val" x-text="fmt(r.ml / ML_PER_CUP, 1)"></div>
                <div class="wi-stat-sub">US cups (8 oz)</div>
              </div>
              <div class="wi-stat">
                <div class="wi-stat-lbl">Per Waking Hour</div>
                <div class="wi-stat-val" x-text="r.awakeHours > 0 ? formatVol(r.ml / r.awakeHours, true) : '—'"></div>
                <div class="wi-stat-sub" x-text="fmt(r.awakeHours, 1) + ' h awake'"></div>
              </div>
            </div>

            {{-- Warnings --}}
            <template x-for="w in r.warnings" :key="w">
              <div class="wi-warn"><span>⚠</span><span x-text="w"></span></div>
            </template>

            {{-- Breakdown --}}
            <div class="card p-5">
              <p class="text-sm font-semibold text-gray-700 mb-3">How Your Target Is Built</p>
              <div class="wi-bar mb-3" role="img" :aria-label="'Breakdown of ' + formatVol(r.ml)">
                <template x-for="part in r.parts" :key="part.key">
                  <div :style="'width:' + (part.ml / r.ml * 100) + '%;background:' + part.color" :title="part.label"></div>
                </template>
              </div>
              <div class="space-y-0.5">
                <template x-for="part in r.parts" :key="part.key">
                  <div class="wi-row">
                    <span class="flex items-center gap-2 text-gray-600 min-w-0">
                      <span class="wi-dot" :style="'background:' + part.color"></span>
                      <span>
                        <span class="font-medium text-gray-800" x-text="part.label"></span>
                        <span class="block text-xs text-gray-400" x-text="part.note"></span>
                      </span>
                    </span>
                    <span class="font-bold text-gray-800 whitespace-nowrap" x-text="'+' + formatVol(part.ml)"></span>
                  </div>
                </template>
                <div class="wi-row border-t border-gray-100 mt-1 pt-2">
                  <span class="font-semibold text-gray-800">Daily target from drinks</span>
                  <span class="font-black whitespace-nowrap" style="color:#0369a1" x-text="formatVol(r.ml)"></span>
                </div>
              </div>
              <p class="text-xs text-gray-400 mt-3">
                Food normally provides another ~20% of your water, so your <strong>total</strong> water intake
                (drinks + food) works out to about <strong x-text="formatVol(r.ml / 0.8)"></strong>.
              </p>
            </div>

            {{-- Schedule --}}
            <div class="card p-5">
              <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <p class="text-sm font-semibold text-gray-700">Drinking Schedule</p>
                <span class="text-xs text-gray-400" x-text="wakeTime + ' – ' + sleepTime + ' · ' + r.schedule.length + ' reminders'"></span>
              </div>
              <div class="wi-sched">
                <template x-for="slot in r.schedule" :key="slot.key">
                  <div class="wi-slot">
                    <span class="wi-slot-time" x-text="slot.time"></span>
                    <span class="wi-slot-amt" x-text="formatVol(slot.ml, true)"></span>
                  </div>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-3">Last drink is scheduled an hour before bed to reduce night-time bathroom trips. Drink extra during and after exercise.</p>
            </div>

            {{-- Tracker --}}
            <div class="card p-5">
              <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <p class="text-sm font-semibold text-gray-700">Today's Tracker</p>
                <button type="button" class="text-xs font-semibold text-red-500 hover:text-red-700" @click="setDrunk(0)" x-show="drunk > 0">Reset today</button>
              </div>
              <div class="flex flex-wrap items-center gap-6">
                <svg class="wi-ring" viewBox="0 0 120 120" role="img" :aria-label="trackerPct + '% of daily goal'">
                  <circle class="wi-ring-bg" cx="60" cy="60" r="50"></circle>
                  <circle class="wi-ring-fg" cx="60" cy="60" r="50" stroke-dasharray="314.16" :stroke-dashoffset="314.16 * (1 - Math.min(trackerPct, 100) / 100)"></circle>
                  <text x="60" y="58" text-anchor="middle" font-size="22" font-weight="800" fill="#0369a1" x-text="trackerPct + '%'"></text>
                  <text x="60" y="78" text-anchor="middle" font-size="10" fill="#94a3b8">of goal</text>
                </svg>
                <div class="flex-1" style="min-width:12rem">
                  <p class="text-2xl font-black text-gray-900">
                    <span x-text="drunk"></span> <span class="text-base font-semibold text-gray-400">/ <span x-text="r.glasses"></span> <span x-text="glassLabel"></span></span>
                  </p>
                  <p class="text-sm text-gray-500 mt-1" x-text="formatVol(drunk * glassMlNum) + ' of ' + formatVol(r.ml)"></p>
                  <p class="text-sm font-semibold mt-1" style="color:#059669" x-show="trackerPct >= 100">🎉 Goal reached — nicely done!</p>
                  <div class="flex items-center gap-3 mt-3">
                    <button type="button" class="wi-round-btn" @click="setDrunk(drunk - 1)" :disabled="drunk === 0" aria-label="Remove one glass">−</button>
                    <button type="button" class="wi-round-btn wi-plus" @click="setDrunk(drunk + 1)" aria-label="Add one glass">+</button>
                    <span class="text-xs text-gray-400">Saved in this browser for today</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Reference --}}
            <div class="card p-5">
              <p class="text-sm font-semibold text-gray-700 mb-3">How You Compare With Official Guidelines</p>
              <div class="space-y-1">
                <template x-for="ref in r.references" :key="ref.label">
                  <div class="wi-row">
                    <span class="text-gray-600">
                      <span class="font-medium text-gray-800" x-text="ref.label"></span>
                      <span class="block text-xs text-gray-400" x-text="ref.note"></span>
                    </span>
                    <span class="font-bold text-gray-800 whitespace-nowrap" x-text="formatVol(ref.ml)"></span>
                  </div>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-3">Guideline values are averages for healthy people in temperate climates with low activity; your personal target adds exercise, climate and life-stage needs on top.</p>
            </div>

            {{-- Export --}}
            <div class="card p-4">
              <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Export:</span>
                <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm" x-text="copied ? '✓ Copied!' : 'Copy Summary'"></button>
                <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
              </div>
            </div>

          </div>
        </template>

        {{-- Idle state --}}
        <div x-show="!result">
          <div class="wi-feat mb-4">
            @foreach([
                ['⚖️', 'Body Weight', 'Base need scaled to your weight and age'],
                ['🏃', 'Exercise', 'Extra fluid for sweat lost during workouts'],
                ['☀️', 'Climate', 'Hot, humid, cold and high-altitude adjustments'],
                ['🤰', 'Life Stage', 'Pregnancy and breastfeeding allowances'],
                ['⏰', 'Schedule', 'Evenly spaced reminders across your waking hours'],
                ['📊', 'Tracker', 'Tick off glasses as you drink through the day'],
            ] as [$icon, $title, $desc])
            <div class="card p-4 text-center">
              <p class="text-2xl mb-1.5">{{ $icon }}</p>
              <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
              <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
            </div>
            @endforeach
          </div>
          <div class="card p-5" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border-color:#bae6fd">
            <p class="text-sm font-semibold mb-2" style="color:#0369a1">📐 How It's Calculated</p>
            <div class="space-y-1 text-xs text-gray-600">
              <p><strong>Base:</strong> weight (kg) × 35 ml (age 14–30), × 33 ml (31–55) or × 30 ml (56+)</p>
              <p><strong>Children 4–13:</strong> age-based reference values for drinks (1.2–1.8 L)</p>
              <p><strong>Exercise:</strong> + 8 / 12 / 16 ml per minute (light / moderate / intense)</p>
              <p><strong>Climate:</strong> + 250–750 ml · <strong>Altitude:</strong> + 500 ml · <strong>Illness:</strong> + 500 ml</p>
              <p><strong>Pregnancy:</strong> + 300 ml · <strong>Breastfeeding:</strong> + 700 ml</p>
            </div>
          </div>
        </div>

        <div class="wi-note">
          ℹ️ This calculator gives general guidance for healthy people and is not medical advice. If you have kidney, heart or liver
          conditions, or have been told to limit fluids, follow your doctor's advice. Thirst and pale-yellow urine are good everyday signs of good hydration.
        </div>

      </div>
    </div>

    {{-- ── Admin-editable content, FAQs & related tools ── --}}
    <div class="mt-8 space-y-5">
      @if($tool->long_description)
      <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
        <div class="tool-prose">{!! nl2br(e($tool->long_description)) !!}</div>
      </div>
      @endif

      @foreach($tool->contents->where('is_visible', true) as $section)
      <div class="card p-6">
        @if($section->title)
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->title }}</h2>
        @endif
        <div class="tool-prose">{!! nl2br(e($section->content)) !!}</div>
      </div>
      @endforeach

      @if($tool->faqs->where('is_visible', true)->count() > 0)
      <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
        <div class="space-y-3" x-data="{ open: null }">
          @foreach($tool->faqs->where('is_visible', true)->values() as $fi => $faq)
          <div class="border border-gray-100 rounded-xl overflow-hidden">
            <button type="button" @click="open = open === {{ $fi }} ? null : {{ $fi }}"
                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
              <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open === {{ $fi }} ? 'rotate-180' : ''"
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div x-show="open === {{ $fi }}" x-cloak class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">{{ $faq->answer }}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      @if($relatedTools->count())
      <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          @foreach($relatedTools as $related)
          <a href="{{ route('tools.show', $related->slug) }}" class="card-hover p-4 flex items-center gap-3 no-underline">
            <span class="text-xl">{{ $related->icon }}</span>
            <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
          </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────
   Water Intake Calculator — Alpine.js component (prefix: wi-)

   Daily target from drinks =
       base (weight × ml/kg by age, or child reference value)
     + exercise (minutes × ml/min by intensity)
     + climate / altitude / illness allowances
     + pregnancy (+300 ml) or breastfeeding (+700 ml)

   References: EFSA (2010) adequate intakes of water; US National
   Academies (2004) dietary reference intakes for water; ACSM
   guidance on fluid replacement during exercise.
──────────────────────────────────────────────────────────── */
function wiCalc() {
  var ML_PER_OZ  = 29.5735;
  var ML_PER_CUP = 236.588;
  var LB_PER_KG  = 2.20462;
  var MAX_ML     = 10000;

  return {
    ML_PER_OZ: ML_PER_OZ,
    ML_PER_CUP: ML_PER_CUP,

    // ── Inputs ──
    units:       'metric',
    sex:         'male',
    age:         '',
    weight:      '',
    exerciseMin: 30,
    intensity:   'moderate',
    climate:     'temperate',
    altitude:    false,
    illness:     false,
    lifeStage:   'none',
    wakeTime:    '07:00',
    sleepTime:   '23:00',
    glassMl:     '250',

    // ── State ──
    result: null,
    error:  '',
    copied: false,
    drunk:  0,

    intensities: [
      { value: 'light',    label: 'Light',    desc: 'Walking, yoga', mlPerMin: 8 },
      { value: 'moderate', label: 'Moderate', desc: 'Jogging, gym',  mlPerMin: 12 },
      { value: 'intense',  label: 'Intense',  desc: 'Running, HIIT', mlPerMin: 16 },
    ],

    climates: [
      { value: 'temperate', label: '🌤 Temperate',   desc: 'Mild, 10–25 °C',          ml: 0 },
      { value: 'hot',       label: '☀️ Hot & dry',    desc: 'Above ~27 °C',            ml: 500 },
      { value: 'humid',     label: '🌴 Hot & humid', desc: 'Tropical, heavy sweating', ml: 750 },
      { value: 'cold',      label: '❄️ Cold',         desc: 'Below ~5 °C, dry air',     ml: 250 },
    ],

    // ── Computed ──
    // Null-safe view of the result for the markup: Alpine can re-evaluate
    // bindings inside x-if once more while tearing the block down.
    get r() {
      return this.result || { ml: 0, glasses: 0, awakeHours: 0, parts: [], schedule: [], warnings: [], references: [] };
    },

    get ageNum()     { return parseInt(this.age, 10) || 0; },
    get glassMlNum() { return parseInt(this.glassMl, 10) || 250; },

    get weightKg() {
      var w = parseFloat(this.weight);
      if (!isFinite(w) || w <= 0) return 0;
      return this.units === 'metric' ? w : w / LB_PER_KG;
    },

    get weightHint() {
      var w = parseFloat(this.weight);
      if (!isFinite(w) || w <= 0) return '';
      return this.units === 'metric'
        ? '≈ ' + this.fmt(w * LB_PER_KG, 1) + ' lb'
        : '≈ ' + this.fmt(w / LB_PER_KG, 1) + ' kg';
    },

    get primaryAmount() {
      if (!this.result) return '';
      return this.units === 'metric'
        ? this.fmt(this.result.ml / 1000, 2)
        : this.fmt(this.result.ml / ML_PER_OZ, 0);
    },
    get primaryUnit()     { return this.units === 'metric' ? 'L' : 'fl oz'; },
    get secondaryAmount() {
      if (!this.result) return '';
      return this.units === 'metric'
        ? '≈ ' + this.fmt(this.result.ml / ML_PER_OZ, 0) + ' fl oz'
        : '≈ ' + this.fmt(this.result.ml / 1000, 2) + ' L';
    },

    get glassLabel() {
      var n = this.glassMlNum;
      var name = n >= 500 ? 'bottle' : 'glass';
      var plural = n >= 500 ? 'bottles' : 'glasses';
      var size = this.units === 'metric' ? n + ' ml' : this.fmt(n / ML_PER_OZ, 1) + ' oz';
      return (this.result && this.result.glasses === 1 ? name : plural) + ' (' + size + ')';
    },

    get glassIcons() {
      if (!this.result) return [];
      var exact = this.result.ml / this.glassMlNum;
      var count = Math.min(this.result.glasses, 40);
      var icons = [];
      for (var i = 0; i < count; i++) {
        var fill = Math.max(0, Math.min(1, exact - i));
        icons.push({ i: i, fill: Math.round(fill * 100) });
      }
      return icons;
    },

    get trackerPct() {
      if (!this.result || !this.result.ml) return 0;
      return Math.round(this.drunk * this.glassMlNum / this.result.ml * 100);
    },

    // ── Lifecycle ──
    init() {
      this.drunk = this._loadDrunk();
    },

    switchUnits(u) {
      if (u === this.units) return;
      var w = parseFloat(this.weight);
      if (isFinite(w) && w > 0) {
        this.weight = u === 'metric'
          ? String(Math.round(w / LB_PER_KG * 10) / 10)
          : String(Math.round(w * LB_PER_KG * 10) / 10);
      }
      this.units = u;
      if (this.result) this.calculate(false);
    },

    autoCalc() {
      if (this._validate() === null) this.calculate(false);
    },

    calculate(scroll) {
      var err = this._validate();
      if (err) {
        this.error = err;
        this.result = null;
        return;
      }
      this.error = '';
      this.result = this._compute();

      if (scroll && window.innerWidth < 1024) {
        var el = document.getElementById('wi-results');
        if (el) setTimeout(function () { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 60);
      }
    },

    // Returns an error message, or null when inputs are valid.
    _validate() {
      var age = this.ageNum;
      if (!age || age < 4 || age > 100) return 'Enter an age between 4 and 100 years.';
      var kg = this.weightKg;
      var minKg = age < 14 ? 12 : 30;
      if (!kg || kg < minKg || kg > 350) {
        return this.units === 'metric'
          ? 'Enter a weight between ' + minKg + ' and 350 kg.'
          : 'Enter a weight between ' + Math.round(minKg * LB_PER_KG) + ' and ' + Math.round(350 * LB_PER_KG) + ' lb.';
      }
      if (this._parseTime(this.wakeTime) === null) return 'Enter a valid wake-up time.';
      if (this._parseTime(this.sleepTime) === null) return 'Enter a valid bedtime.';
      return null;
    },

    _compute() {
      var age = this.ageNum;
      var kg  = this.weightKg;
      var parts = [];
      var warnings = [];

      // ── Base need ──
      if (age < 14) {
        var childMl = age <= 8 ? 1200 : (this.sex === 'male' ? 1800 : 1600);
        parts.push({ key: 'base', label: 'Base need', color: '#0284c7', ml: childMl,
                     note: 'Reference value for children aged ' + (age <= 8 ? '4–8' : '9–13') });
      } else {
        var perKg = age <= 30 ? 35 : (age <= 55 ? 33 : 30);
        parts.push({ key: 'base', label: 'Base need', color: '#0284c7', ml: kg * perKg,
                     note: this.fmt(kg, 1) + ' kg × ' + perKg + ' ml/kg for your age' });
      }

      // ── Exercise ──
      if (this.exerciseMin > 0) {
        var it = this._find(this.intensities, this.intensity);
        parts.push({ key: 'exercise', label: 'Exercise', color: '#06b6d4',
                     ml: this.exerciseMin * it.mlPerMin,
                     note: this.exerciseMin + ' min × ' + it.mlPerMin + ' ml/min (' + it.label.toLowerCase() + ')' });
      }

      // ── Climate / environment ──
      var cl = this._find(this.climates, this.climate);
      if (cl.ml > 0) {
        parts.push({ key: 'climate', label: 'Climate', color: '#f59e0b', ml: cl.ml, note: cl.desc });
      }
      if (this.altitude) {
        parts.push({ key: 'altitude', label: 'High altitude', color: '#8b5cf6', ml: 500, note: 'Faster breathing in thin, dry air' });
      }
      if (this.illness) {
        parts.push({ key: 'illness', label: 'Illness', color: '#ef4444', ml: 500, note: 'Replaces fluid lost to fever, vomiting or diarrhoea' });
        warnings.push('If vomiting or diarrhoea lasts more than a day, or you cannot keep fluids down, seek medical advice. An oral rehydration solution replaces lost salts better than plain water.');
      }

      // ── Life stage ──
      var female = this.sex === 'female' && age >= 14;
      if (female && this.lifeStage === 'pregnant') {
        parts.push({ key: 'life', label: 'Pregnancy', color: '#ec4899', ml: 300, note: 'EFSA recommendation' });
      } else if (female && this.lifeStage === 'breastfeeding') {
        parts.push({ key: 'life', label: 'Breastfeeding', color: '#ec4899', ml: 700, note: 'Replaces fluid in breast milk (EFSA)' });
      }

      var total = parts.reduce(function (s, p) { return s + p.ml; }, 0);

      // Adults should not drop below a sensible floor even at low body weight.
      if (age >= 14) {
        var floor = this.sex === 'male' ? 2000 : 1600;
        if (total < floor) {
          parts[0].ml += floor - total;
          parts[0].note += ' (raised to the healthy minimum)';
          total = floor;
        }
      }
      total = Math.min(total, MAX_ML);

      // Round to the nearest 50 ml so numbers read cleanly.
      var rounded = Math.round(total / 50) * 50;
      var diff = rounded - total;
      parts[0].ml += diff;
      total = rounded;

      // ── Waking hours & schedule ──
      var wake  = this._parseTime(this.wakeTime);
      var sleep = this._parseTime(this.sleepTime);
      var awakeMin = sleep - wake;
      if (awakeMin <= 0) awakeMin += 24 * 60;
      var awakeHours = awakeMin / 60;

      var glass = this.glassMlNum;
      var glasses = Math.max(1, Math.ceil(total / glass - 0.05));
      var schedule = this._schedule(wake, awakeMin, total, glasses);

      if (awakeHours < 8) {
        warnings.push('Your waking window is under 8 hours — double-check your wake-up and bedtime.');
      }
      var drinkingHours = Math.max(1, awakeHours - 1);
      if (total / drinkingHours > 1000) {
        warnings.push('This works out to more than 1 litre per hour. Drinking faster than your kidneys can clear water (roughly 0.8–1 L/hour) can be dangerous — spread it out and include electrolytes during long, sweaty exercise.');
      }
      if (this.exerciseMin >= 60) {
        warnings.push('For sessions over an hour, drink about 150–250 ml every 15–20 minutes and consider a sports drink to replace sodium lost in sweat.');
      }

      // ── Reference comparisons ──
      var references = [];
      if (age >= 14) {
        var efsa = this.sex === 'male' ? 2500 : 2000;
        var nasem = this.sex === 'male' ? 3700 : 2700;
        references.push({ label: 'EFSA (Europe) — total water', ml: efsa, note: 'Drinks + food, ' + (this.sex === 'male' ? 'men' : 'women') });
        references.push({ label: 'EFSA — from drinks (~80%)', ml: efsa * 0.8, note: 'The part you drink' });
        references.push({ label: 'US National Academies — total water', ml: nasem, note: 'Drinks + food, ' + (this.sex === 'male' ? 'men' : 'women') + ' 19+' });
        references.push({ label: 'US National Academies — from drinks', ml: this.sex === 'male' ? 3000 : 2200, note: 'About 13 cups (men) / 9 cups (women)' });
      } else {
        var childTotal = age <= 8 ? 1700 : (this.sex === 'male' ? 2400 : 2100);
        references.push({ label: 'US National Academies — total water', ml: childTotal, note: 'Drinks + food, age ' + (age <= 8 ? '4–8' : '9–13') });
      }

      return {
        ml: total,
        parts: parts,
        glasses: glasses,
        awakeHours: awakeHours,
        schedule: schedule,
        warnings: warnings,
        references: references,
      };
    },

    // Evenly spaced reminders from wake-up until one hour before bed.
    _schedule(wakeMin, awakeMin, totalMl, glasses) {
      var windowMin = Math.max(60, awakeMin - 60);
      var slots = Math.min(glasses, 16);
      var perSlot = totalMl / slots;
      var step = slots > 1 ? windowMin / (slots - 1) : 0;
      var out = [];
      for (var i = 0; i < slots; i++) {
        var t = Math.round((wakeMin + step * i) / 5) * 5;
        out.push({ key: i, time: this._formatTime(t), ml: perSlot });
      }
      return out;
    },

    // "HH:MM" → minutes after midnight, or null.
    _parseTime(v) {
      var m = /^(\d{1,2}):(\d{2})/.exec(v || '');
      if (!m) return null;
      var h = parseInt(m[1], 10), mi = parseInt(m[2], 10);
      if (h > 23 || mi > 59) return null;
      return h * 60 + mi;
    },

    _formatTime(min) {
      min = ((min % 1440) + 1440) % 1440;
      var h = Math.floor(min / 60), m = min % 60;
      var ampm = h >= 12 ? 'PM' : 'AM';
      var h12 = h % 12 === 0 ? 12 : h % 12;
      return h12 + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
    },

    _find(list, value) {
      for (var i = 0; i < list.length; i++) if (list[i].value === value) return list[i];
      return list[0];
    },

    // ── Tracker (per browser, per day) ──
    _trackerKey() {
      var d = new Date();
      var mm = d.getMonth() + 1, dd = d.getDate();
      return 'wi-tracker-' + d.getFullYear() + '-' + (mm < 10 ? '0' : '') + mm + '-' + (dd < 10 ? '0' : '') + dd;
    },
    _loadDrunk() {
      try {
        var v = parseInt(window.localStorage.getItem(this._trackerKey()), 10);
        return isFinite(v) && v > 0 ? v : 0;
      } catch (e) { return 0; }
    },
    setDrunk(n) {
      this.drunk = Math.max(0, Math.min(99, n));
      try {
        var key = this._trackerKey();
        // Drop entries from previous days.
        for (var i = window.localStorage.length - 1; i >= 0; i--) {
          var k = window.localStorage.key(i);
          if (k && k.indexOf('wi-tracker-') === 0 && k !== key) window.localStorage.removeItem(k);
        }
        window.localStorage.setItem(key, String(this.drunk));
      } catch (e) { /* storage unavailable — tracker still works for this visit */ }
    },

    // ── Actions ──
    loadSample() {
      this.units = 'metric';
      this.sex = 'female';
      this.age = '32';
      this.weight = '64';
      this.exerciseMin = 45;
      this.intensity = 'moderate';
      this.climate = 'hot';
      this.altitude = false;
      this.illness = false;
      this.lifeStage = 'none';
      this.wakeTime = '06:30';
      this.sleepTime = '22:30';
      this.glassMl = '250';
      this.calculate(true);
    },

    resetAll() {
      this.age = '';
      this.weight = '';
      this.exerciseMin = 30;
      this.intensity = 'moderate';
      this.climate = 'temperate';
      this.altitude = false;
      this.illness = false;
      this.lifeStage = 'none';
      this.wakeTime = '07:00';
      this.sleepTime = '23:00';
      this.glassMl = '250';
      this.result = null;
      this.error = '';
    },

    // ── Formatting ──
    fmt(v, dp) {
      if (v === null || v === undefined || !isFinite(v)) return '—';
      return Number(v).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: dp });
    },

    formatVol(ml, short) {
      if (!isFinite(ml)) return '—';
      if (this.units === 'imperial') return this.fmt(ml / ML_PER_OZ, short ? 0 : 1) + ' oz';
      if (ml >= 1000 && !short) return this.fmt(ml / 1000, 2) + ' L';
      return this.fmt(Math.round(ml / 10) * 10, 0) + ' ml';
    },

    // ── Export ──
    _summary() {
      var r = this.result;
      if (!r) return '';
      var self = this;
      var lines = [
        'Water Intake Calculator — Results',
        '=================================',
        'Daily target (drinks) : ' + this.fmt(r.ml / 1000, 2) + ' L  |  ' + this.fmt(r.ml / ML_PER_OZ, 0) + ' fl oz  |  ' + this.fmt(r.ml / ML_PER_CUP, 1) + ' cups',
        'Glasses               : ' + r.glasses + ' × ' + this.glassMlNum + ' ml',
        'Total incl. food      : ~' + this.fmt(r.ml / 0.8 / 1000, 2) + ' L',
        '',
        'Profile: ' + this.sex + ', ' + this.ageNum + ' yrs, ' + this.fmt(this.weightKg, 1) + ' kg, '
          + this.exerciseMin + ' min exercise (' + this.intensity + '), ' + this.climate + ' climate'
          + (this.altitude ? ', high altitude' : '') + (this.illness ? ', illness' : '')
          + (this.lifeStage !== 'none' && this.sex === 'female' ? ', ' + this.lifeStage : ''),
        '',
        'BREAKDOWN:',
      ];
      r.parts.forEach(function (p) {
        lines.push('  ' + (p.label + ':').padEnd(16) + '+' + self.fmt(p.ml, 0) + ' ml  (' + p.note + ')');
      });
      lines.push('', 'SCHEDULE (' + this.wakeTime + '–' + this.sleepTime + '):');
      r.schedule.forEach(function (s) {
        lines.push('  ' + s.time.padEnd(12) + self.fmt(s.ml, 0) + ' ml');
      });
      if (r.warnings.length) {
        lines.push('', 'NOTES:');
        r.warnings.forEach(function (w) { lines.push('  - ' + w); });
      }
      lines.push('', 'General guidance only — not medical advice.');
      return lines.join('\n');
    },

    async copySummary() {
      var text = this._summary();
      if (!text) return;
      try {
        await navigator.clipboard.writeText(text);
      } catch (e) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;opacity:0;';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
      }
      var self = this;
      this.copied = true;
      setTimeout(function () { self.copied = false; }, 1800);
    },

    downloadSummary() {
      var text = this._summary();
      if (!text) return;
      var blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement('a');
      a.href = url;
      a.download = 'water-intake-plan.txt';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
    },
  };
}
</script>
@endpush
