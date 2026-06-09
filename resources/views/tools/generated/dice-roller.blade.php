@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Dice shake animation ── */
@keyframes diceShake {
    0%,100%{ transform:translate(0,0) rotate(0deg); }
    15%    { transform:translate(-4px,3px) rotate(-8deg); }
    30%    { transform:translate(4px,-3px) rotate(8deg); }
    45%    { transform:translate(-4px,-3px) rotate(-5deg); }
    60%    { transform:translate(4px,4px) rotate(5deg); }
    75%    { transform:translate(-3px,2px) rotate(-3deg); }
}
@keyframes diceReveal {
    from{ transform:scale(0.6) rotateY(90deg); opacity:0; }
    to  { transform:scale(1)   rotateY(0deg);  opacity:1; }
}
@keyframes critPulse {
    0%,100%{ box-shadow:0 0 0 0 rgba(245,158,11,0); }
    50%    { box-shadow:0 0 0 10px rgba(245,158,11,0.25); }
}
.dice-area-rolling { animation:diceShake 0.65s ease-in-out; }
.die-reveal        { animation:diceReveal 0.35s ease-out both; }
.crit-pulse        { animation:critPulse 1.2s ease-in-out infinite; }

/* ── Spinner ── */
.spinner{width:20px;height:20px;border:3px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:inline-block;}
@keyframes spin{to{transform:rotate(360deg)}}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="diceRoller()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 space-y-5">

        {{-- ══════════════════════════
             QUICK PRESETS
             ══════════════════════════ --}}
        <div class="card p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Quick Presets</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="p in presets" :key="p.label">
                    <button type="button" @click="applyPreset(p)"
                            class="flex flex-col items-center px-3 py-2 rounded-xl border-2 border-gray-200 bg-white hover:border-brand-400 hover:bg-brand-50 transition-all group">
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-brand-700" x-text="p.label"></span>
                        <span class="text-xs text-gray-400 group-hover:text-brand-500 mt-0.5 font-mono" x-text="p.notation"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════
             CONFIG CARD
             ══════════════════════════ --}}
        <div class="card p-6">

            {{-- Dice Type --}}
            <div class="mb-5">
                <label class="form-label">Dice Type</label>
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-2">
                    <template x-for="opt in diceOptions" :key="opt.sides">
                        <button type="button"
                                @click="diceType = opt.sides; errors.sides = ''"
                                class="flex flex-col items-center py-3 px-1 rounded-xl border-2 font-bold text-sm transition-all"
                                :style="diceType === opt.sides
                                    ? 'border-color:'+dieColorOf(opt.sides || customSides).border+';background:'+dieColorOf(opt.sides || customSides).bg+';color:'+dieColorOf(opt.sides || customSides).text
                                    : 'border-color:#e5e7eb;background:#fff;color:#6b7280'"
                                :class="diceType === opt.sides ? 'shadow-sm' : 'hover:border-gray-300'">
                            <span class="text-lg leading-none mb-0.5">🎲</span>
                            <span x-text="opt.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Custom Sides --}}
            <div x-show="diceType === 0" x-transition class="mb-5">
                <label class="form-label">Custom Number of Sides</label>
                <input type="number" min="2" max="1000"
                       x-model.number="customSides"
                       @input="errors.sides=''"
                       class="form-input max-w-xs"
                       :class="errors.sides ? 'border-red-400 focus:border-red-400' : ''">
                <p x-show="errors.sides" class="form-error" x-text="errors.sides"></p>
                <p class="form-help">Enter any number between 2 and 1000.</p>
            </div>

            <div class="divider"></div>

            {{-- Number of Dice + Modifier --}}
            <div class="grid grid-cols-2 gap-5 mb-5">

                {{-- Number of Dice --}}
                <div>
                    <label class="form-label">Number of Dice</label>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="decDice()"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all"
                                :disabled="numDice <= 1">−</button>
                        <input type="number" min="1" max="100"
                               x-model.number="numDice"
                               @input="errors.numDice=''; clampKeepCount()"
                               class="form-input text-center font-bold text-lg w-full"
                               :class="errors.numDice ? 'border-red-400' : ''">
                        <button type="button" @click="incDice()"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all"
                                :disabled="numDice >= 100">+</button>
                    </div>
                    <p x-show="errors.numDice" class="form-error" x-text="errors.numDice"></p>
                    <p class="form-help">1 – 100 dice per roll</p>
                </div>

                {{-- Modifier --}}
                <div>
                    <label class="form-label">Modifier</label>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="decMod()"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all">−</button>
                        <div class="relative w-full">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-sm pointer-events-none"
                                  x-text="modifier >= 0 ? '+' : ''"></span>
                            <input type="number" min="-999" max="999"
                                   x-model.number="modifier"
                                   class="form-input text-center font-bold text-lg pl-7">
                        </div>
                        <button type="button" @click="incMod()"
                                class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all">+</button>
                    </div>
                    <p class="form-help">Added to the final total (can be negative)</p>
                </div>
            </div>

            {{-- Roll Type --}}
            <div class="mb-5">
                <label class="form-label">Roll Type</label>
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="rt in rollTypes" :key="rt.value">
                        <button type="button" @click="rollType = rt.value"
                                class="flex flex-col items-center gap-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all"
                                :class="rollType === rt.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span class="text-xl" x-text="rt.icon"></span>
                            <span x-text="rt.label"></span>
                            <span class="text-xs font-normal text-gray-400" x-text="rt.hint"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Advanced toggle --}}
            <button type="button" @click="showAdvanced = !showAdvanced"
                    class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-brand-600 transition-colors mb-3">
                <svg class="w-4 h-4 transition-transform" :class="showAdvanced ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Advanced options (Keep / Drop)
            </button>

            {{-- Advanced section --}}
            <div x-show="showAdvanced" x-transition class="mb-5 p-4 bg-gray-50 rounded-xl">
                <div class="mb-3">
                    <label class="form-label">Keep / Drop Mode</label>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                        <template x-for="km in keepModes" :key="km.value">
                            <button type="button" @click="keepMode = km.value; errors.keepCount=''"
                                    class="py-2 px-3 rounded-xl border-2 text-xs font-semibold transition-all text-center"
                                    :class="keepMode === km.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="km.label">
                            </button>
                        </template>
                    </div>
                </div>

                <div x-show="keepMode !== 'none'" x-transition class="mt-3">
                    <label class="form-label">
                        Count
                        <span x-show="keepMode==='kh'" class="font-normal text-gray-400">— keep this many highest</span>
                        <span x-show="keepMode==='kl'" class="font-normal text-gray-400">— keep this many lowest</span>
                        <span x-show="keepMode==='dh'" class="font-normal text-gray-400">— drop this many highest</span>
                        <span x-show="keepMode==='dl'" class="font-normal text-gray-400">— drop this many lowest</span>
                    </label>
                    <div class="flex items-center gap-2 max-w-xs">
                        <button type="button" @click="decKeep()" class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all">−</button>
                        <input type="number" min="1" :max="Math.max(1, numDice - 1)"
                               x-model.number="keepCount"
                               @input="clampKeepCount(); errors.keepCount=''"
                               class="form-input text-center font-bold"
                               :class="errors.keepCount ? 'border-red-400' : ''">
                        <button type="button" @click="incKeep()" class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-lg hover:border-brand-400 hover:text-brand-600 transition-all">+</button>
                    </div>
                    <p x-show="errors.keepCount" class="form-error" x-text="errors.keepCount"></p>
                </div>
            </div>

            {{-- Current Notation --}}
            <div class="mb-5 flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Notation:</span>
                <code class="font-mono font-bold text-brand-700 text-sm" x-text="currentNotation"></code>
            </div>

            {{-- Roll Button --}}
            <button type="button" @click="roll()"
                    :disabled="isRolling"
                    class="btn btn-primary w-full btn-lg relative overflow-hidden">
                <template x-if="isRolling">
                    <span class="spinner"></span>
                </template>
                <template x-if="!isRolling">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </template>
                <span x-text="isRolling ? 'Rolling...' : (hasRolled ? 'Roll Again' : 'Roll the Dice!')"></span>
            </button>
        </div>

        {{-- ══════════════════════════
             RESULTS CARD
             ══════════════════════════ --}}
        <div x-show="hasRolled" x-transition class="card overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">Roll Result</h3>
                    <code class="text-xs font-mono text-brand-600 mt-0.5 block"
                          x-text="rollResult ? rollResult.notation : '...'"></code>
                </div>
                <button type="button" @click="roll()" :disabled="isRolling"
                        class="btn btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reroll
                </button>
            </div>

            {{-- Crit / Fumble Banner --}}
            <div x-show="rollResult && rollResult.isCrit" x-transition
                 class="px-6 py-2 bg-amber-50 border-b border-amber-100 flex items-center gap-2">
                <span class="text-lg">⭐</span>
                <span class="font-semibold text-amber-700">Critical Hit! Maximum roll!</span>
            </div>
            <div x-show="rollResult && rollResult.isFumble" x-transition
                 class="px-6 py-2 bg-red-50 border-b border-red-100 flex items-center gap-2">
                <span class="text-lg">💀</span>
                <span class="font-semibold text-red-700">Fumble! Rolled a 1.</span>
            </div>

            <div class="p-6">

                {{-- ── ROLLING animation ── --}}
                <div x-show="isRolling">
                    <div class="dice-area-rolling flex flex-wrap justify-center gap-3 mb-6">
                        <template x-for="(v, i) in animValues" :key="i">
                            <div class="flex flex-col items-center justify-center w-14 h-14 rounded-xl border-2 font-black text-xl"
                                 :style="'border-color:'+dieColorOf(effectiveSides).border+';background:'+dieColorOf(effectiveSides).bg+';color:'+dieColorOf(effectiveSides).text">
                                <span x-text="v"></span>
                            </div>
                        </template>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full animate-pulse" style="width:100%"></div>
                    </div>
                </div>

                {{-- ── NORMAL roll results ── --}}
                <div x-show="!isRolling && rollResult && rollResult.type === 'normal'">

                    <div class="flex flex-wrap justify-center gap-3 mb-6"
                         :class="rollResult && rollResult.isCrit ? 'crit-pulse rounded-2xl p-2' : ''">
                        <template x-if="rollResult">
                            <template x-for="(die, idx) in rollResult.pools[0].dice" :key="idx">
                                <div class="die-reveal flex flex-col items-center justify-center w-14 h-14 rounded-xl border-2 font-black text-xl relative transition-all"
                                     :style="dieCardStyle(die)"
                                     :style-delay="idx * 60 + 'ms'">
                                    <span x-text="die.value" class="leading-none"></span>
                                    <span x-show="!die.kept"
                                          class="absolute inset-0 flex items-center justify-center rounded-xl bg-white/70">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                                        </svg>
                                    </span>
                                </div>
                            </template>
                        </template>
                    </div>

                    {{-- Kept/Dropped legend (only when keep/drop active) --}}
                    <div x-show="keepMode !== 'none' && rollResult"
                         class="flex items-center gap-4 justify-center mb-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded bg-emerald-400 inline-block"></span> Kept
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded bg-gray-200 inline-block"></span> Dropped
                        </span>
                    </div>
                </div>

                {{-- ── ADVANTAGE / DISADVANTAGE results ── --}}
                <div x-show="!isRolling && rollResult && (rollResult.type === 'advantage' || rollResult.type === 'disadvantage')">
                    <template x-if="rollResult">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <template x-for="(pool, pi) in rollResult.pools" :key="pi">
                                <div class="rounded-xl border-2 p-4 transition-all"
                                     :class="pool.kept
                                         ? 'border-brand-300 bg-brand-50'
                                         : 'border-gray-200 bg-gray-50 opacity-60'">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold text-gray-500" x-text="'Pool ' + (pi + 1)"></span>
                                        <span x-show="pool.kept"
                                              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                                            ✓ Kept
                                        </span>
                                        <span x-show="!pool.kept"
                                              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">
                                            ✗ Discarded
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <template x-for="(die, di) in pool.dice" :key="di">
                                            <div class="die-reveal flex flex-col items-center justify-center w-12 h-12 rounded-xl border-2 font-black text-lg"
                                                 :style="pool.kept
                                                     ? 'border-color:'+dieColorOf(die.sides).border+';background:'+dieColorOf(die.sides).bg+';color:'+dieColorOf(die.sides).text
                                                     : 'border-color:#e5e7eb;background:#f9fafb;color:#d1d5db'">
                                                <span x-text="die.value"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <span class="text-sm font-semibold text-gray-600">Sum: </span>
                                        <span class="font-bold text-gray-800" x-text="pool.subtotal"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- ── Totals breakdown ── --}}
                <div x-show="!isRolling && rollResult" x-transition>
                    <template x-if="rollResult">
                        <div class="mt-2 rounded-xl border border-gray-200 overflow-hidden">
                            <div class="grid divide-x divide-gray-200"
                                 :class="rollResult.modifier !== 0 ? 'grid-cols-3' : 'grid-cols-2'">

                                {{-- Dice subtotal --}}
                                <div class="px-4 py-3 text-center">
                                    <p class="text-xs text-gray-400 font-medium mb-1">Dice Total</p>
                                    <p class="text-xl font-bold text-gray-800" x-text="rollResult.subtotal"></p>
                                </div>

                                {{-- Modifier (only shown if ≠ 0) --}}
                                <div x-show="rollResult.modifier !== 0" class="px-4 py-3 text-center">
                                    <p class="text-xs text-gray-400 font-medium mb-1">Modifier</p>
                                    <p class="text-xl font-bold"
                                       :class="rollResult.modifier > 0 ? 'text-emerald-600' : 'text-red-500'"
                                       x-text="(rollResult.modifier > 0 ? '+' : '') + rollResult.modifier"></p>
                                </div>

                                {{-- Grand total --}}
                                <div class="px-4 py-3 text-center"
                                     :style="rollResult.isCrit
                                         ? 'background:'+dieColorOf(rollResult.sides).bg+';'
                                         : rollResult.isFumble
                                             ? 'background:#fef2f2'
                                             : 'background:#f0fdf4'">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                       :class="rollResult.isCrit ? 'text-amber-600' : rollResult.isFumble ? 'text-red-600' : 'text-emerald-600'">
                                        Final Total
                                    </p>
                                    <p class="text-3xl font-black"
                                       :class="rollResult.isCrit ? 'text-amber-700' : rollResult.isFumble ? 'text-red-700' : 'text-emerald-700'"
                                       x-text="rollResult.total"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            </div>{{-- /p-6 --}}
        </div>{{-- /results card --}}

        {{-- ══════════════════════════
             HISTORY CARD
             ══════════════════════════ --}}
        <div x-show="history.length > 0" x-transition class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Roll History</h3>
                <button type="button" @click="clearHistory()"
                        class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                    Clear all
                </button>
            </div>
            <div class="divide-y divide-gray-50">
                <template x-for="(h, i) in history" :key="i">
                    <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-400 text-xs font-bold flex items-center justify-center flex-shrink-0"
                                  x-text="i + 1"></span>
                            <div>
                                <code class="text-sm font-mono text-brand-700 font-semibold" x-text="h.notation"></code>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-400">
                                    <span>Dice: <span class="font-semibold text-gray-600" x-text="h.subtotal"></span></span>
                                    <template x-if="h.modifier !== 0">
                                        <span>Mod: <span class="font-semibold"
                                              :class="h.modifier>0?'text-emerald-600':'text-red-500'"
                                              x-text="(h.modifier>0?'+':'')+h.modifier"></span></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <span class="text-2xl font-black"
                              :class="h.isCrit ? 'text-amber-600' : h.isFumble ? 'text-red-600' : 'text-gray-800'"
                              x-text="h.total"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
function diceRoller() {
    return {
        /* ── Config ── */
        diceType:     20,
        customSides:  6,
        numDice:      1,
        modifier:     0,
        rollType:     'normal',
        keepMode:     'none',
        keepCount:    1,
        showAdvanced: false,

        /* ── State ── */
        hasRolled:  false,
        isRolling:  false,
        rollResult: null,
        animValues: [],
        history:    [],
        errors:     {},

        /* ── Static options ── */
        diceOptions: [
            { sides: 4,   label: 'D4'   },
            { sides: 6,   label: 'D6'   },
            { sides: 8,   label: 'D8'   },
            { sides: 10,  label: 'D10'  },
            { sides: 12,  label: 'D12'  },
            { sides: 20,  label: 'D20'  },
            { sides: 100, label: 'D100' },
            { sides: 0,   label: 'Custom'},
        ],
        rollTypes: [
            { value:'normal',      icon:'🎲', label:'Normal',      hint:'Roll as-is'         },
            { value:'advantage',   icon:'⬆️', label:'Advantage',   hint:'Best of 2 sets'     },
            { value:'disadvantage',icon:'⬇️', label:'Disadvantage',hint:'Worst of 2 sets'    },
        ],
        keepModes: [
            { value:'none', label:'No Keep/Drop'   },
            { value:'kh',   label:'Keep Highest'   },
            { value:'kl',   label:'Keep Lowest'    },
            { value:'dh',   label:'Drop Highest'   },
            { value:'dl',   label:'Drop Lowest'    },
        ],
        presets: [
            { label:'Attack Roll',     notation:'1d20',        n:1, s:20,  m:0, t:'normal',       km:'none', kc:1 },
            { label:'Advantage',       notation:'2×1d20↑',     n:1, s:20,  m:0, t:'advantage',    km:'none', kc:1 },
            { label:'Disadvantage',    notation:'2×1d20↓',     n:1, s:20,  m:0, t:'disadvantage', km:'none', kc:1 },
            { label:'Stat Roll',       notation:'4d6 drop low',n:4, s:6,   m:0, t:'normal',       km:'dl',   kc:1 },
            { label:'2d6 Damage',      notation:'2d6',         n:2, s:6,   m:0, t:'normal',       km:'none', kc:1 },
            { label:'3d6 Ability',     notation:'3d6',         n:3, s:6,   m:0, t:'normal',       km:'none', kc:1 },
            { label:'Percentile',      notation:'1d100',       n:1, s:100, m:0, t:'normal',       km:'none', kc:1 },
            { label:'8d8 Fireball',    notation:'8d8',         n:8, s:8,   m:0, t:'normal',       km:'none', kc:1 },
        ],

        /* ── Init ── */
        init() {
            try {
                var saved = localStorage.getItem('dice_history_v2');
                if (saved) this.history = JSON.parse(saved).slice(0, 15);
            } catch(e) {}
        },

        /* ── Computed ── */
        get effectiveSides() {
            return this.diceType === 0 ? (this.customSides || 6) : this.diceType;
        },

        get currentNotation() {
            var n  = this.numDice;
            var s  = this.effectiveSides;
            var m  = this.modifier;
            var t  = this.rollType;
            var km = this.keepMode;
            var kc = this.keepCount;
            var str = '';
            if (t !== 'normal') str = '2×';
            str += n + 'd' + s;
            if (km !== 'none') {
                var lbl = { kh:'kh', kl:'kl', dh:'dh', dl:'dl' };
                str += lbl[km] + kc;
            }
            if (m > 0) str += '+' + m;
            if (m < 0) str += m;
            if (t === 'advantage')    str += ' (Advantage)';
            if (t === 'disadvantage') str += ' (Disadvantage)';
            return str;
        },

        /* ── Preset ── */
        applyPreset(p) {
            this.diceType  = p.s;
            this.numDice   = p.n;
            this.modifier  = p.m;
            this.rollType  = p.t;
            this.keepMode  = p.km;
            this.keepCount = p.kc;
            this.roll();
        },

        /* ── Validation ── */
        _validate() {
            this.errors = {};
            var s = this.effectiveSides;
            if (!s || s < 2 || s > 1000) {
                this.errors.sides = 'Sides must be between 2 and 1000.';
            }
            if (!this.numDice || this.numDice < 1 || this.numDice > 100) {
                this.errors.numDice = 'Number of dice must be 1–100.';
            }
            if (this.keepMode !== 'none' && this.numDice > 1) {
                if (this.keepCount < 1 || this.keepCount >= this.numDice) {
                    this.errors.keepCount = 'Must be between 1 and ' + (this.numDice - 1) + '.';
                }
            }
            return Object.keys(this.errors).length === 0;
        },

        /* ── Roll ── */
        roll() {
            if (!this._validate()) return;

            this.hasRolled = true;
            this.isRolling = true;
            this.rollResult = null;

            var s = this.effectiveSides;
            var t = this.rollType;
            var n = this.numDice;
            var animCount = (t === 'normal') ? n : n * 2;

            /* Pre-compute final result */
            var final = this._compute();

            /* Cycle random values until done */
            var self   = this;
            var ticks  = 0;
            var total  = 16;

            self.animValues = self._randomArr(animCount, s);

            var iv = setInterval(function() {
                ticks++;
                if (ticks >= total) {
                    clearInterval(iv);
                    self.animValues  = [];
                    self.rollResult  = final;
                    self.isRolling   = false;
                    self._addHistory(final);
                } else {
                    self.animValues = self._randomArr(animCount, s);
                }
            }, 50);
        },

        /* ── Compute roll ── */
        _compute() {
            var n  = this.numDice;
            var s  = this.effectiveSides;
            var m  = this.modifier;
            var t  = this.rollType;
            var km = this.keepMode;
            var kc = this.keepCount;

            var rollN = function(count) {
                var arr = [];
                for (var i = 0; i < count; i++) {
                    arr.push({ sides: s, value: Math.floor(Math.random() * s) + 1, kept: true });
                }
                return arr;
            };

            var pools, keptDice;

            if (t === 'advantage' || t === 'disadvantage') {
                var pA    = rollN(n);
                var pB    = rollN(n);
                var sumA  = pA.reduce(function(a,d){ return a+d.value; }, 0);
                var sumB  = pB.reduce(function(a,d){ return a+d.value; }, 0);
                var keepA = (t === 'advantage') ? (sumA >= sumB) : (sumA <= sumB);

                pools    = [
                    { dice: pA, subtotal: sumA, kept: keepA  },
                    { dice: pB, subtotal: sumB, kept: !keepA },
                ];
                keptDice = keepA ? pA : pB;

            } else {
                var dice = rollN(n);

                /* Apply keep/drop */
                if (km !== 'none' && n > 1) {
                    /* Sort by value descending; track original indices */
                    var indexed = dice.map(function(d,i){ return { d:d, i:i }; });
                    indexed.sort(function(a,b){ return b.d.value - a.d.value; });

                    var keepSet = new Set();
                    if (km === 'kh') {
                        indexed.slice(0, kc).forEach(function(x){ keepSet.add(x.i); });
                    } else if (km === 'kl') {
                        indexed.slice(n - kc).forEach(function(x){ keepSet.add(x.i); });
                    } else if (km === 'dh') {
                        /* drop kc highest → keep the rest */
                        indexed.slice(kc).forEach(function(x){ keepSet.add(x.i); });
                    } else if (km === 'dl') {
                        /* drop kc lowest → keep the rest */
                        indexed.slice(0, n - kc).forEach(function(x){ keepSet.add(x.i); });
                    }
                    dice.forEach(function(d,i){ d.kept = keepSet.has(i); });
                }

                pools    = [{ dice: dice, subtotal: 0, kept: true }];
                keptDice = dice;
            }

            /* Subtotal = sum of kept dice */
            var subtotal;
            if (t === 'advantage' || t === 'disadvantage') {
                subtotal = keptDice.reduce(function(a,d){ return a + d.value; }, 0);
            } else {
                subtotal = keptDice.reduce(function(a,d){ return d.kept ? a + d.value : a; }, 0);
            }

            var total    = subtotal + m;
            var maxPoss  = (t === 'advantage' || t === 'disadvantage') ? n * s : keptDice.filter(function(d){return d.kept;}).length * s;
            var isCrit   = (subtotal === maxPoss) && n <= 4;
            var isFumble = (t === 'advantage' || t === 'disadvantage')
                ? false
                : keptDice.every(function(d){ return !d.kept || d.value === 1; }) && n === 1;

            return {
                notation:  this.currentNotation,
                type:      t,
                pools:     pools,
                keptDice:  keptDice,
                subtotal:  subtotal,
                modifier:  m,
                total:     total,
                sides:     s,
                numDice:   n,
                isCrit:    isCrit,
                isFumble:  isFumble,
            };
        },

        /* ── Helpers ── */
        _randomArr(count, sides) {
            var arr = [];
            for (var i = 0; i < count; i++) {
                arr.push(Math.floor(Math.random() * sides) + 1);
            }
            return arr;
        },

        _addHistory(r) {
            this.history.unshift({
                notation: r.notation,
                total:    r.total,
                subtotal: r.subtotal,
                modifier: r.modifier,
                isCrit:   r.isCrit,
                isFumble: r.isFumble,
            });
            if (this.history.length > 15) this.history.pop();
            try { localStorage.setItem('dice_history_v2', JSON.stringify(this.history)); } catch(e) {}
        },

        clearHistory() {
            this.history = [];
            try { localStorage.removeItem('dice_history_v2'); } catch(e) {}
        },

        /* ── Die colour ── */
        dieColorOf(sides) {
            var map = {
                4:  { border:'#f59e0b', bg:'#fffbeb', text:'#92400e' },
                6:  { border:'#3b82f6', bg:'#eff6ff', text:'#1e40af' },
                8:  { border:'#10b981', bg:'#ecfdf5', text:'#065f46' },
                10: { border:'#8b5cf6', bg:'#f5f3ff', text:'#5b21b6' },
                12: { border:'#ef4444', bg:'#fef2f2', text:'#991b1b' },
                20: { border:'#4f46e5', bg:'#eef2ff', text:'#312e81' },
                100:{ border:'#6b7280', bg:'#f9fafb', text:'#374151' },
            };
            return map[sides] || map[100];
        },

        dieCardStyle(die) {
            if (!die.kept) {
                return 'border-color:#e5e7eb;background:#f9fafb;color:#d1d5db;';
            }
            var c = this.dieColorOf(die.sides);
            return 'border-color:' + c.border + ';background:' + c.bg + ';color:' + c.text + ';';
        },

        /* ── Incrementers ── */
        incDice()  { this.numDice   = Math.min(100, this.numDice + 1); this.clampKeepCount(); },
        decDice()  { this.numDice   = Math.max(1,   this.numDice - 1); this.clampKeepCount(); },
        incMod()   { this.modifier  = Math.min(999, this.modifier + 1); },
        decMod()   { this.modifier  = Math.max(-999,this.modifier - 1); },
        incKeep()  { this.keepCount = Math.min(Math.max(1, this.numDice - 1), this.keepCount + 1); },
        decKeep()  { this.keepCount = Math.max(1, this.keepCount - 1); },

        clampKeepCount() {
            var max = Math.max(1, this.numDice - 1);
            if (this.keepCount > max) this.keepCount = max;
            if (this.keepCount < 1)   this.keepCount = 1;
        },
    };
}
</script>
@endpush
