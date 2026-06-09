@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Hint feedback ── */
.hint-box {
    border-radius: 1rem;
    border-width: 2px;
    border-style: solid;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    transition: background .2s, border-color .2s;
}
.hint-high  { background:#fff1f2; border-color:#fda4af; color:#9f1239; }
.hint-low   { background:#f0fdf4; border-color:#86efac; color:#14532d; }
.hint-none  { background:#f8fafc; border-color:#e2e8f0; color:#64748b; }

/* ── Shake on wrong ── */
@keyframes shake {
    0%,100%{ transform:translateX(0); }
    20%,60%{ transform:translateX(-7px); }
    40%,80%{ transform:translateX(7px); }
}
.shake { animation: shake .38s ease both; }

/* ── Win bounce ── */
@keyframes wonBounce {
    0%  { transform:scale(.78); opacity:0; }
    65% { transform:scale(1.04); }
    100%{ transform:scale(1);   opacity:1; }
}
.won-bounce { animation: wonBounce .45s cubic-bezier(.34,1.56,.64,1) both; }

/* ── Lost slide ── */
@keyframes lostSlide {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}
.lost-slide { animation: lostSlide .3s ease both; }

/* ── Guess history item ── */
.guess-item {
    display:flex; align-items:center; gap:.75rem;
    padding:.6rem .9rem;
    border-radius:.875rem;
    border:1.5px solid;
    transition: opacity .2s;
}
.guess-item.high { background:#fff1f2; border-color:#fda4af; }
.guess-item.low  { background:#f0fdf4; border-color:#86efac; }
.guess-item.correct { background:#eef2ff; border-color:#a5b4fc; }

/* ── Attempts bar colour ── */
.bar-fill { transition: width .4s ease, background .4s; }

/* ── Number input ── */
.guess-input {
    font-size:1.5rem; font-weight:700; text-align:center;
    letter-spacing:.05em; width:100%; border-radius:1rem;
    border:2px solid #d1d5db; padding:.75rem 1rem;
    outline:none; transition:border-color .15s;
    -moz-appearance: textfield;
}
.guess-input::-webkit-outer-spin-button,
.guess-input::-webkit-inner-spin-button { -webkit-appearance:none; margin:0; }
.guess-input:focus { border-color:#4f46e5; }
.guess-input.err  { border-color:#f43f5e; }

/* ── Range badge ── */
.range-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.25rem .75rem; border-radius:9999px;
    font-size:.8rem; font-weight:600;
    background:#eef2ff; color:#3730a3; border:1.5px solid #c7d2fe;
}

/* ── Temp indicator dots ── */
.temp-dot {
    width:8px; height:8px; border-radius:50%;
    flex-shrink:0; transition:background .3s;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="numberGuessing()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 space-y-4">

        {{-- ════════════════════════════════
             SETUP PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'setup'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            <div class="card p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Choose Difficulty</h2>
                    <p class="text-sm text-gray-400 mt-1">Pick a difficulty level and start guessing!</p>
                </div>

                {{-- Difficulty selector --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <template x-for="(cfg, key) in DIFFICULTIES" :key="key">
                        <button type="button"
                                @click="difficulty = key"
                                class="flex flex-col items-center gap-1.5 p-4 rounded-2xl border-2 transition-all font-semibold text-sm cursor-pointer"
                                :class="difficulty === key
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
                            <span class="text-2xl" x-text="cfg.icon"></span>
                            <span x-text="cfg.label"></span>
                            <span class="text-xs font-normal opacity-60" x-text="'1–' + cfg.max"></span>
                        </button>
                    </template>
                </div>

                {{-- Custom options --}}
                <div x-show="difficulty === 'custom'"
                     x-transition
                     class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label class="form-label">Min Number</label>
                        <input type="number" x-model.number="customMin" min="0" max="9998"
                               class="form-input text-center font-semibold">
                    </div>
                    <div>
                        <label class="form-label">Max Number</label>
                        <input type="number" x-model.number="customMax" min="2" max="10000"
                               class="form-input text-center font-semibold">
                    </div>
                    <div>
                        <label class="form-label">Max Attempts</label>
                        <input type="number" x-model.number="customAttempts" min="1" max="30"
                               class="form-input text-center font-semibold">
                    </div>
                    <p x-show="customError" class="col-span-full form-error" x-text="customError"></p>
                </div>

                {{-- Info strip --}}
                <div class="flex flex-wrap gap-3 text-sm">
                    <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2 text-gray-500">
                        🎯 Range:
                        <strong class="text-gray-700"
                                x-text="activeMin + ' – ' + activeMax"></strong>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2 text-gray-500">
                        🔄 Attempts:
                        <strong class="text-gray-700" x-text="activeAttempts"></strong>
                    </div>
                    <div x-show="difficulty !== 'custom' && bestScores[difficulty]"
                         class="flex items-center gap-2 bg-amber-50 rounded-xl px-3 py-2 text-amber-700">
                        🏆 Best:
                        <strong x-text="bestScores[difficulty] ? bestScores[difficulty].attempts + ' guesses' : ''"></strong>
                    </div>
                </div>

                {{-- Start button --}}
                <button type="button"
                        @click="startGame()"
                        class="btn btn-primary btn-lg w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Start Game
                </button>
            </div>

            {{-- How to play --}}
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-3">How to play</h3>
                <div class="grid sm:grid-cols-2 gap-2.5 text-sm text-gray-500">
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">1.</span>
                        <p>A random number is picked within the chosen range.</p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">2.</span>
                        <p>Enter your guess and hit <strong class="text-gray-700">Guess!</strong></p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">3.</span>
                        <p>You'll be told if your guess is <strong class="text-rose-600">Too High</strong> or <strong class="text-emerald-600">Too Low</strong>.</p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">4.</span>
                        <p>Find the number before your attempts run out to win!</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════
             PLAYING PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'playing'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="space-y-4">

            {{-- Stats bar --}}
            <div class="card p-4">
                <div class="flex items-center gap-3 flex-wrap mb-3">
                    {{-- Range --}}
                    <div class="flex items-center gap-2 flex-1 min-w-[130px]">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 text-lg">🎯</div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Range</p>
                            <p class="font-black text-gray-800 leading-tight">
                                <span x-text="min"></span><span class="text-gray-300 font-normal"> – </span><span x-text="max"></span>
                            </p>
                        </div>
                    </div>
                    <div class="w-px h-9 bg-gray-100 shrink-0"></div>
                    {{-- Attempts --}}
                    <div class="flex items-center gap-2 flex-1 min-w-[100px]">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-lg transition-colors"
                             :class="attemptsLeft <= 2 ? 'bg-rose-50' : attemptsLeft <= Math.ceil(maxAttempts/2) ? 'bg-amber-50' : 'bg-emerald-50'">
                            🔄
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Attempts left</p>
                            <p class="font-black leading-tight"
                               :class="attemptsLeft <= 2 ? 'text-rose-600' : attemptsLeft <= Math.ceil(maxAttempts/2) ? 'text-amber-600' : 'text-emerald-600'"
                               x-text="attemptsLeft + ' / ' + maxAttempts">
                            </p>
                        </div>
                    </div>
                    <div class="w-px h-9 bg-gray-100 shrink-0"></div>
                    {{-- Timer --}}
                    <div class="flex items-center gap-2 flex-1 min-w-[80px]">
                        <div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 text-lg">⏱️</div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Time</p>
                            <p class="font-black text-gray-700 leading-tight font-mono" x-text="formattedTime"></p>
                        </div>
                    </div>
                    <button type="button" @click="newGame()"
                            class="btn btn-secondary btn-sm shrink-0 ml-auto">
                        ⚙️ Settings
                    </button>
                </div>

                {{-- Attempts progress bar --}}
                <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bar-fill h-full rounded-full"
                         :style="'width:' + progressPct + '%;'"
                         :class="attemptsLeft <= 2 ? 'bg-rose-500'
                               : attemptsLeft <= Math.ceil(maxAttempts/2) ? 'bg-amber-400'
                               : 'bg-emerald-500'">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span x-text="attemptsUsed + ' used'"></span>
                    <span x-text="attemptsLeft + ' left'"></span>
                </div>
            </div>

            {{-- Input card --}}
            <div class="card p-6">
                <p class="text-center text-sm font-medium text-gray-500 mb-1">
                    Guess a number between
                    <span class="font-black text-indigo-600" x-text="knownLo"></span>
                    and
                    <span class="font-black text-indigo-600" x-text="knownHi"></span>
                </p>
                <p x-show="guesses.length > 0"
                   class="text-center text-xs text-gray-400 mb-4">
                    (<span x-text="knownHi - knownLo + 1"></span> possibilities remaining)
                </p>
                <p x-show="guesses.length === 0" class="mb-4"></p>

                {{-- Hint box (after first guess) --}}
                <div x-show="lastHint !== null"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="hint-box mb-4"
                     :class="{
                         'hint-high': lastHint === 'high',
                         'hint-low':  lastHint === 'low',
                         'hint-none': lastHint === null,
                     }"
                     :id="'hint-' + hintKey">
                    <span class="text-2xl shrink-0" x-text="hintIcon"></span>
                    <div>
                        <p class="font-bold text-base" x-text="hintTitle"></p>
                        <p class="text-sm opacity-80" x-text="hintSub"></p>
                    </div>
                </div>

                {{-- Number input row --}}
                <div class="flex gap-3">
                    <input type="number"
                           x-model.number="currentGuess"
                           @keydown.enter.prevent="guess()"
                           :min="knownLo"
                           :max="knownHi"
                           placeholder="?"
                           class="guess-input"
                           :class="inputError ? 'err' : ''"
                           x-ref="guessInput">
                    <button type="button"
                            @click="guess()"
                            class="btn btn-primary btn-lg px-7 shrink-0 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                        Guess!
                    </button>
                </div>
                <p x-show="inputError" x-transition class="form-error mt-2 text-center" x-text="inputError"></p>

                {{-- Keyboard hint --}}
                <p class="text-xs text-center text-gray-400 mt-2">Press <kbd class="px-1.5 py-0.5 rounded-md bg-gray-100 text-gray-500 text-xs font-mono">Enter</kbd> to guess</p>
            </div>

            {{-- Guess history --}}
            <div x-show="guesses.length > 0"
                 x-transition
                 class="card p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900">
                        Guess History
                        <span class="ml-1.5 badge badge-gray" x-text="guesses.length + ' guess' + (guesses.length !== 1 ? 'es' : '')"></span>
                    </h3>
                </div>
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    <template x-for="(g, idx) in guesses" :key="g.number + '-' + idx">
                        <div class="guess-item"
                             :class="{
                                 'high':    g.hint === 'high',
                                 'low':     g.hint === 'low',
                                 'correct': g.hint === 'correct',
                             }">

                            {{-- Attempt number --}}
                            <span class="shrink-0 w-6 h-6 rounded-lg text-xs font-black flex items-center justify-center"
                                  :class="{
                                      'bg-rose-200 text-rose-800':     g.hint === 'high',
                                      'bg-emerald-200 text-emerald-800': g.hint === 'low',
                                      'bg-indigo-200 text-indigo-800':  g.hint === 'correct',
                                  }"
                                  x-text="guesses.length - idx">
                            </span>

                            {{-- Guessed number --}}
                            <span class="font-black text-lg w-12 text-center shrink-0"
                                  :class="{
                                      'text-rose-700':    g.hint === 'high',
                                      'text-emerald-700': g.hint === 'low',
                                      'text-indigo-700':  g.hint === 'correct',
                                  }"
                                  x-text="g.number">
                            </span>

                            {{-- Hint badge --}}
                            <span class="flex items-center gap-1 text-sm font-semibold flex-1"
                                  :class="{
                                      'text-rose-700':    g.hint === 'high',
                                      'text-emerald-700': g.hint === 'low',
                                      'text-indigo-700':  g.hint === 'correct',
                                  }">
                                <span x-text="g.hint === 'high' ? '↓ Too High' : g.hint === 'low' ? '↑ Too Low' : '✓ Correct!'"></span>
                            </span>

                            {{-- Range after guess --}}
                            <span x-show="g.hint !== 'correct'"
                                  class="shrink-0 text-xs text-gray-400 font-mono hidden sm:block">
                                <span x-text="g.lo + '–' + g.hi"></span>
                            </span>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════
             WON PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'won'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">

            <div class="card p-8 text-center won-bounce">
                <div class="text-6xl mb-3">🎉</div>
                <h2 class="text-2xl font-black text-gray-900 mb-1">You Got It!</h2>
                <p class="text-gray-500 mb-6">
                    The number was <strong class="text-indigo-600 text-xl" x-text="target"></strong>.
                    Nice work!
                </p>

                {{-- Win stats --}}
                <div class="grid grid-cols-3 gap-3 mb-6 max-w-sm mx-auto">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-indigo-600" x-text="attemptsUsed"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Guesses</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-amber-500 font-mono" x-text="formattedTime"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Time</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-emerald-500" x-text="score"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Score</p>
                    </div>
                </div>

                {{-- Stars --}}
                <div class="flex justify-center gap-1.5 mb-1">
                    <template x-for="n in [1,2,3]" :key="n">
                        <span class="text-3xl" :class="n <= starRating ? 'text-amber-400' : 'text-gray-200'">★</span>
                    </template>
                </div>
                <p class="text-sm text-gray-400 mb-5" x-text="starLabel"></p>

                {{-- New best --}}
                <div x-show="isNewBest && difficulty !== 'custom'"
                     class="inline-flex items-center gap-2 mb-5 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm font-semibold">
                    🏆 New Best!
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 justify-center flex-wrap">
                    <button type="button" @click="startGame()" class="btn btn-primary btn-lg">
                        🔄 Play Again
                    </button>
                    <button type="button" @click="newGame()" class="btn btn-secondary btn-lg">
                        ⚙️ Change Settings
                    </button>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════
             LOST PHASE
             ════════════════════════════════ --}}
        <div x-show="phase === 'lost'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">

            <div class="card p-8 text-center lost-slide">
                <div class="text-6xl mb-3">😞</div>
                <h2 class="text-2xl font-black text-gray-900 mb-1">Out of Attempts!</h2>
                <p class="text-gray-500 mb-6">
                    The number was
                    <strong class="text-rose-600 text-2xl font-black" x-text="target"></strong>.
                    Better luck next time!
                </p>

                <div class="grid grid-cols-2 gap-3 mb-6 max-w-xs mx-auto">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-gray-700" x-text="maxAttempts"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Attempts used</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-gray-700 font-mono" x-text="formattedTime"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Time</p>
                    </div>
                </div>

                {{-- Closest guess --}}
                <div x-show="guesses.length > 0"
                     class="mb-6 p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
                    Your closest guess was
                    <strong x-text="closestGuess"></strong>
                    (off by <strong x-text="Math.abs(closestGuess - target)"></strong>).
                </div>

                <div class="flex gap-3 justify-center flex-wrap">
                    <button type="button" @click="startGame()" class="btn btn-primary btn-lg">
                        🔄 Try Again
                    </button>
                    <button type="button" @click="newGame()" class="btn btn-secondary btn-lg">
                        ⚙️ Change Settings
                    </button>
                </div>
            </div>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div x-show="phase === 'setup'">
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
/* ══════════════════════════════════════════════════
   NUMBER GUESSING GAME — Alpine.js component
══════════════════════════════════════════════════ */
function numberGuessing() {
    return {

        /* ── Phase ── */
        phase: 'setup',   /* 'setup' | 'playing' | 'won' | 'lost' */

        /* ── Settings ── */
        difficulty:     'medium',
        customMin:      1,
        customMax:      100,
        customAttempts: 7,
        customError:    '',

        /* ── Active game ── */
        target:       null,
        min:          1,
        max:          100,
        maxAttempts:  7,
        attemptsLeft: 7,
        currentGuess: '',
        inputError:   '',

        /* ── Dynamic known range (narrows with each guess) ── */
        knownLo: 1,
        knownHi: 100,

        /* ── Hint state ── */
        lastHint: null,  /* null | 'high' | 'low' | 'correct' */
        hintKey:  0,     /* bumped each guess to re-trigger transition */

        /* ── Guess history ── */
        guesses: [],  /* [{number, hint, lo, hi}] */

        /* ── Timer ── */
        elapsedSeconds: 0,
        _timerHandle:   null,

        /* ── Persistence ── */
        bestScores: {},   /* {difficulty: {attempts, time}} */
        _newBest:   false,

        /* ── Static config ── */
        DIFFICULTIES: {
            easy:   { label:'Easy',   icon:'😊', max:50,   attempts:10 },
            medium: { label:'Medium', icon:'🧠', max:100,  attempts:7  },
            hard:   { label:'Hard',   icon:'🔥', max:200,  attempts:5  },
            custom: { label:'Custom', icon:'⚙️', max:100,  attempts:7  },
        },

        /* ══════════════════════════════════
           COMPUTED GETTERS
        ══════════════════════════════════ */

        get activeMin() {
            if (this.difficulty === 'custom') return this.customMin;
            return 1;
        },
        get activeMax() {
            if (this.difficulty === 'custom') return this.customMax;
            return this.DIFFICULTIES[this.difficulty].max;
        },
        get activeAttempts() {
            if (this.difficulty === 'custom') return this.customAttempts;
            return this.DIFFICULTIES[this.difficulty].attempts;
        },

        get attemptsUsed() {
            return this.maxAttempts - this.attemptsLeft;
        },
        get progressPct() {
            if (!this.maxAttempts) return 0;
            return Math.round((this.attemptsUsed / this.maxAttempts) * 100);
        },
        get formattedTime() {
            var m = Math.floor(this.elapsedSeconds / 60);
            var s = this.elapsedSeconds % 60;
            return (m ? m + ':' : '') + (m ? String(s).padStart(2,'0') : s) + 's';
        },

        get hintIcon() {
            if (this.lastHint === 'high') return '📉';
            if (this.lastHint === 'low')  return '📈';
            return '🎯';
        },
        get hintTitle() {
            if (this.lastHint === 'high') return 'Too High!';
            if (this.lastHint === 'low')  return 'Too Low!';
            return 'Correct!';
        },
        get hintSub() {
            if (this.lastHint === 'high') return 'Try a lower number between ' + this.knownLo + ' and ' + this.knownHi + '.';
            if (this.lastHint === 'low')  return 'Try a higher number between ' + this.knownLo + ' and ' + this.knownHi + '.';
            return 'You found the number!';
        },

        get score() {
            var base      = Math.round((this.attemptsLeft / this.maxAttempts) * 1000);
            var timeBonus = Math.max(0, 60 - this.elapsedSeconds) * 3;
            return Math.max(0, base + timeBonus);
        },

        get starRating() {
            var pct = this.attemptsLeft / this.maxAttempts;
            if (pct > 0.6) return 3;
            if (pct > 0.2) return 2;
            return 1;
        },
        get starLabel() {
            return ['','🙂 Well tried! Practice more.','😊 Good job! Nearly perfect.','🌟 Excellent! Minimal guesses!'][this.starRating] || '';
        },

        get isNewBest() {
            if (this.difficulty === 'custom') return false;
            return this._newBest;
        },

        get closestGuess() {
            if (!this.guesses.length) return null;
            var t = this.target;
            return this.guesses.reduce(function(best, g) {
                return Math.abs(g.number - t) < Math.abs(best - t) ? g.number : best;
            }, this.guesses[0].number);
        },

        /* ══════════════════════════════════
           INIT
        ══════════════════════════════════ */

        init() {
            try {
                var s = localStorage.getItem('ngg_best_v1');
                if (s) this.bestScores = JSON.parse(s);
            } catch(e) {}
        },

        /* ══════════════════════════════════
           START GAME
        ══════════════════════════════════ */

        startGame() {
            /* Validate custom settings */
            this.customError = '';
            var lo, hi, att;
            if (this.difficulty === 'custom') {
                lo  = parseInt(this.customMin, 10);
                hi  = parseInt(this.customMax, 10);
                att = parseInt(this.customAttempts, 10);
                if (isNaN(lo) || isNaN(hi) || isNaN(att)) {
                    this.customError = 'Please fill in all custom fields.';
                    return;
                }
                if (hi <= lo) {
                    this.customError = 'Max must be greater than Min.';
                    return;
                }
                if (att < 1 || att > 30) {
                    this.customError = 'Attempts must be between 1 and 30.';
                    return;
                }
            } else {
                var cfg = this.DIFFICULTIES[this.difficulty];
                lo  = 1;
                hi  = cfg.max;
                att = cfg.attempts;
            }

            /* Set up game */
            this.min          = lo;
            this.max          = hi;
            this.maxAttempts  = att;
            this.attemptsLeft = att;
            this.target       = Math.floor(Math.random() * (hi - lo + 1)) + lo;

            this.currentGuess  = '';
            this.inputError    = '';
            this.guesses       = [];
            this.lastHint      = null;
            this.hintKey       = 0;
            this.knownLo       = lo;
            this.knownHi       = hi;
            this.elapsedSeconds = 0;
            this._newBest      = false;
            this.phase         = 'playing';

            this._startTimer();

            /* Focus input after DOM settles */
            var self = this;
            this.$nextTick(function() {
                if (self.$refs.guessInput) self.$refs.guessInput.focus();
            });
        },

        /* ══════════════════════════════════
           GUESS
        ══════════════════════════════════ */

        guess() {
            /* Validate */
            var raw = this.currentGuess;
            if (raw === '' || raw === null || raw === undefined) {
                this.inputError = 'Please enter a number.';
                return;
            }
            var val = parseInt(raw, 10);
            if (isNaN(val)) {
                this.inputError = 'Please enter a valid whole number.';
                return;
            }
            if (val < this.min || val > this.max) {
                this.inputError = 'Number must be between ' + this.min + ' and ' + this.max + '.';
                return;
            }
            if (this.guesses.some(function(g) { return g.number === val; })) {
                this.inputError = 'You already guessed ' + val + '. Try a different number!';
                return;
            }

            this.inputError   = '';
            this.attemptsLeft = Math.max(0, this.attemptsLeft - 1);
            this.hintKey++;

            /* Evaluate */
            var hint;
            if (val === this.target) {
                hint = 'correct';
            } else if (val > this.target) {
                hint         = 'high';
                this.knownHi = Math.min(this.knownHi, val - 1);
            } else {
                hint         = 'low';
                this.knownLo = Math.max(this.knownLo, val + 1);
            }

            this.lastHint = hint;

            /* Record in history (newest first) */
            this.guesses.unshift({
                number: val,
                hint:   hint,
                lo:     this.knownLo,
                hi:     this.knownHi,
            });

            this.currentGuess = '';

            /* Re-focus input */
            var self = this;
            this.$nextTick(function() {
                if (self.$refs.guessInput) self.$refs.guessInput.focus();
            });

            /* End-game checks */
            if (hint === 'correct') {
                this._stopTimer();
                this._saveBest();
                var self2 = this;
                setTimeout(function() { self2.phase = 'won'; }, 400);
                return;
            }
            if (this.attemptsLeft <= 0) {
                this._stopTimer();
                var self3 = this;
                setTimeout(function() { self3.phase = 'lost'; }, 400);
            }
        },

        /* ══════════════════════════════════
           GAME CONTROL
        ══════════════════════════════════ */

        newGame() {
            this._stopTimer();
            this.phase = 'setup';
        },

        /* ══════════════════════════════════
           TIMER
        ══════════════════════════════════ */

        _startTimer() {
            var self = this;
            this._stopTimer();
            this._timerHandle = setInterval(function() {
                self.elapsedSeconds++;
            }, 1000);
        },

        _stopTimer() {
            if (this._timerHandle) {
                clearInterval(this._timerHandle);
                this._timerHandle = null;
            }
        },

        /* ══════════════════════════════════
           BEST SCORE
        ══════════════════════════════════ */

        _saveBest() {
            if (this.difficulty === 'custom') return;
            var key  = this.difficulty;
            var prev = this.bestScores[key];
            var curr = { attempts: this.attemptsUsed, time: this.elapsedSeconds };

            var better = !prev
                || curr.attempts < prev.attempts
                || (curr.attempts === prev.attempts && curr.time < prev.time);

            if (better) {
                this.bestScores  = Object.assign({}, this.bestScores, { [key]: curr });
                this._newBest = true;
                try { localStorage.setItem('ngg_best_v1', JSON.stringify(this.bestScores)); } catch(e) {}
            }
        },
    };
}
</script>
@endpush
