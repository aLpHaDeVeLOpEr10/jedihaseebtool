@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Mode tabs ── */
.fc-tab {
    padding: .55rem 1.1rem;
    border-bottom: 2.5px solid transparent;
    font-size: .82rem; font-weight: 600; color: #64748b;
    background: transparent; border-top: none; border-left: none; border-right: none;
    cursor: pointer; transition: color .12s, border-color .12s; white-space: nowrap;
}
.fc-tab:hover { color: #4f46e5; }
.fc-tab-active { color: #4f46e5; border-bottom-color: #4f46e5; }

/* ── Operation pills ── */
.fc-op {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .4rem 1rem; border-radius: 9999px;
    border: 1.5px solid #e2e8f0; font-size: .82rem; font-weight: 600;
    color: #475569; background: white; cursor: pointer; transition: all .12s;
}
.fc-op:hover { border-color: #a5b4fc; color: #4f46e5; background: #f0f4ff; }
.fc-op-active { border-color: #4f46e5; background: #4f46e5; color: white; box-shadow: 0 2px 8px rgba(79,70,229,.25); }

/* ── Convert type pills ── */
.fc-cv {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .38rem .85rem; border-radius: 9999px;
    border: 1.5px solid #e2e8f0; font-size: .78rem; font-weight: 600;
    color: #475569; background: white; cursor: pointer; transition: all .12s; white-space: nowrap;
}
.fc-cv:hover { border-color: #a5b4fc; color: #4f46e5; background: #f0f4ff; }
.fc-cv-active { border-color: #4f46e5; background: #4f46e5; color: white; }

/* ── Fraction input box ── */
.fc-inp-box {
    display: inline-flex; flex-direction: column; align-items: stretch;
    background: white; border: 1.5px solid #e2e8f0; border-radius: .875rem;
    overflow: hidden; transition: border-color .15s, box-shadow .15s; min-width: 5rem;
}
.fc-inp-box:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
.fc-inp-box.fc-result-box-style {
    border-color: #a5b4fc; background: #eef2ff;
}

/* hide number spinners */
.fc-num-input {
    width: 100%; text-align: center; border: none; outline: none;
    font-size: 1.35rem; font-weight: 700; color: #1e293b;
    padding: .45rem .5rem; background: transparent; font-family: inherit;
    -moz-appearance: textfield;
}
.fc-num-input::-webkit-outer-spin-button,
.fc-num-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.fc-num-input::placeholder { color: #cbd5e1; font-weight: 400; }
.fc-num-input.fc-result-val { color: #4f46e5; }

/* Fraction bar inside input */
.fc-inp-bar { height: 2.5px; background: #475569; margin: 0 .55rem; }
.fc-inp-bar.result { background: #4f46e5; }

/* ── Operator symbol ── */
.fc-opsym { font-size: 2rem; font-weight: 200; color: #94a3b8; line-height: 1; user-select: none; }
.fc-opsym.op-main { color: #64748b; }

/* ── Result fraction display (large) ── */
.fc-disp { display: inline-flex; flex-direction: column; align-items: center; gap: 3px; }
.fc-disp-n, .fc-disp-d {
    font-size: 2rem; font-weight: 900; line-height: 1; min-width: 2.5rem; text-align: center;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.fc-disp-bar { width: 100%; min-width: 3rem; height: 3px; background: linear-gradient(90deg, #4f46e5, #7c3aed); border-radius: 2px; }
.fc-disp-whole { font-size: 2.4rem; font-weight: 900; line-height: 1; background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

/* Muted version for "before" side */
.fc-disp-n.muted, .fc-disp-d.muted { background: linear-gradient(135deg,#94a3b8,#cbd5e1); -webkit-background-clip: text; background-clip: text; }
.fc-disp-bar.muted { background: #cbd5e1; }
.fc-disp-whole.muted { background: linear-gradient(135deg,#94a3b8,#cbd5e1); -webkit-background-clip: text; background-clip: text; }

/* ── Stat card ── */
.fc-stat {
    background: white; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1.1rem 1rem; text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.fc-stat:hover { border-color: #a5b4fc; box-shadow: 0 4px 16px rgba(79,70,229,.08); transform: translateY(-1px); }

/* ── Step row ── */
.fc-step {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .6rem .8rem; background: #f8fafc; border-radius: .75rem;
    border-left: 3px solid #c7d2fe; font-size: .84rem; color: #334155; line-height: 1.55;
}
.fc-step-num {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 1.5rem; height: 1.5rem; border-radius: 9999px;
    background: #4f46e5; color: white; font-size: .65rem; font-weight: 700; flex-shrink: 0; margin-top: .05rem;
}

/* ── Entrance animation ── */
@keyframes fcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.fc-in { animation: fcIn .28s ease-out; }

/* ── Equation display row ── */
.fc-eq { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; }
</style>

<div class="min-h-screen bg-gray-50" x-data="fracCalc()" x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Add, subtract, multiply or divide fractions — plus simplify, convert between improper and mixed numbers, and get decimal equivalents. Full step-by-step solutions shown.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">Step-by-Step</span>
                    <span class="badge badge-primary">Instant</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        {{-- ══════════════════════════════════════════════
             INPUT CARD
        ══════════════════════════════════════════════ --}}
        <div class="card">

            {{-- Mode tabs --}}
            <div class="border-b border-gray-100 px-5 flex gap-0 overflow-x-auto">
                <button type="button" @click="setMode('arithmetic')" class="fc-tab" :class="mode==='arithmetic' ? 'fc-tab-active' : ''">🔢 Arithmetic</button>
                <button type="button" @click="setMode('simplify')"   class="fc-tab" :class="mode==='simplify'   ? 'fc-tab-active' : ''">✂️ Simplify</button>
                <button type="button" @click="setMode('convert')"    class="fc-tab" :class="mode==='convert'    ? 'fc-tab-active' : ''">🔄 Convert</button>
            </div>

            <div class="p-5 space-y-5">

                {{-- ══ ARITHMETIC MODE ══ --}}
                <div x-show="mode === 'arithmetic'" class="space-y-5">

                    {{-- Operation selector --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2.5">Operation</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="op='add'" class="fc-op" :class="op==='add' ? 'fc-op-active' : ''">+ Add</button>
                            <button type="button" @click="op='sub'" class="fc-op" :class="op==='sub' ? 'fc-op-active' : ''">− Subtract</button>
                            <button type="button" @click="op='mul'" class="fc-op" :class="op==='mul' ? 'fc-op-active' : ''">× Multiply</button>
                            <button type="button" @click="op='div'" class="fc-op" :class="op==='div' ? 'fc-op-active' : ''">÷ Divide</button>
                        </div>
                    </div>

                    {{-- Two fraction inputs --}}
                    <div class="fc-eq py-2">
                        {{-- Fraction A --}}
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">First Fraction</p>
                            <div class="fc-inp-box">
                                <input type="number" x-model="a.n" placeholder="3" class="fc-num-input" @keydown.enter="calculate()">
                                <div class="fc-inp-bar"></div>
                                <input type="number" x-model="a.d" placeholder="4" class="fc-num-input" @keydown.enter="calculate()">
                            </div>
                        </div>

                        {{-- Operator --}}
                        <span class="fc-opsym op-main mt-5" x-text="opSym"></span>

                        {{-- Fraction B --}}
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">Second Fraction</p>
                            <div class="fc-inp-box">
                                <input type="number" x-model="b.n" placeholder="1" class="fc-num-input" @keydown.enter="calculate()">
                                <div class="fc-inp-bar"></div>
                                <input type="number" x-model="b.d" placeholder="2" class="fc-num-input" @keydown.enter="calculate()">
                            </div>
                        </div>

                        {{-- Equals + preview --}}
                        <span class="fc-opsym mt-5">=</span>

                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">Result</p>
                            <div class="fc-inp-box min-w-[5rem]"
                                 :class="phase==='done' && result ? 'fc-result-box-style' : ''"
                                 style="border-style: dashed;" :style="phase==='done' && result ? 'border-style:solid' : ''">
                                <div class="fc-num-input" :class="phase==='done' && result ? 'fc-result-val' : 'text-gray-300'"
                                     style="padding-top:.45rem;padding-bottom:.45rem;">
                                    <span x-text="phase==='done' && result && result.frac ? result.frac.n : '?'"></span>
                                </div>
                                <div class="fc-inp-bar" :class="phase==='done' && result ? 'result' : ''"></div>
                                <div class="fc-num-input" :class="phase==='done' && result ? 'fc-result-val' : 'text-gray-300'"
                                     style="padding-top:.45rem;padding-bottom:.45rem;">
                                    <span x-text="phase==='done' && result && result.frac ? result.frac.d : '?'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ SIMPLIFY MODE ══ --}}
                <div x-show="mode === 'simplify'" class="space-y-4">
                    <p class="text-sm text-gray-500">Enter any fraction and it will be reduced to its lowest terms.</p>
                    <div class="fc-eq py-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">Fraction to Simplify</p>
                            <div class="fc-inp-box">
                                <input type="number" x-model="s.n" placeholder="12" class="fc-num-input" @keydown.enter="calculate()">
                                <div class="fc-inp-bar"></div>
                                <input type="number" x-model="s.d" placeholder="18" class="fc-num-input" @keydown.enter="calculate()">
                            </div>
                        </div>
                        <span class="fc-opsym mt-5">→</span>
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">Simplified</p>
                            <div class="fc-inp-box min-w-[5rem]"
                                 :class="phase==='done' && result ? 'fc-result-box-style' : ''"
                                 style="border-style:dashed;" :style="phase==='done' && result ? 'border-style:solid' : ''">
                                <div class="fc-num-input" :class="phase==='done' && result ? 'fc-result-val' : 'text-gray-300'"
                                     style="padding-top:.45rem;padding-bottom:.45rem;">
                                    <span x-text="phase==='done' && result && result.frac ? result.frac.n : '?'"></span>
                                </div>
                                <div class="fc-inp-bar" :class="phase==='done' && result ? 'result' : ''"></div>
                                <div class="fc-num-input" :class="phase==='done' && result ? 'fc-result-val' : 'text-gray-300'"
                                     style="padding-top:.45rem;padding-bottom:.45rem;">
                                    <span x-text="phase==='done' && result && result.frac ? result.frac.d : '?'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══ CONVERT MODE ══ --}}
                <div x-show="mode === 'convert'" class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2.5">Conversion Type</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="cvType='imp2mix'"  class="fc-cv" :class="cvType==='imp2mix'  ? 'fc-cv-active' : ''">Improper → Mixed</button>
                            <button type="button" @click="cvType='mix2imp'"  class="fc-cv" :class="cvType==='mix2imp'  ? 'fc-cv-active' : ''">Mixed → Improper</button>
                            <button type="button" @click="cvType='frac2dec'" class="fc-cv" :class="cvType==='frac2dec' ? 'fc-cv-active' : ''">Fraction → Decimal</button>
                            <button type="button" @click="cvType='dec2frac'" class="fc-cv" :class="cvType==='dec2frac' ? 'fc-cv-active' : ''">Decimal → Fraction</button>
                        </div>
                    </div>

                    {{-- Improper fraction input (imp2mix + frac2dec) --}}
                    <div x-show="cvType === 'imp2mix' || cvType === 'frac2dec'" class="flex justify-center py-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2"
                               x-text="cvType === 'frac2dec' ? 'Fraction' : 'Improper Fraction (|numerator| ≥ denominator)'"></p>
                            <div class="fc-inp-box">
                                <input type="number" x-model="imp.n" placeholder="7" class="fc-num-input" @keydown.enter="calculate()">
                                <div class="fc-inp-bar"></div>
                                <input type="number" x-model="imp.d" placeholder="3" class="fc-num-input" @keydown.enter="calculate()">
                            </div>
                        </div>
                    </div>

                    {{-- Mixed number input (mix2imp) --}}
                    <div x-show="cvType === 'mix2imp'" class="flex justify-center py-2">
                        <div class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 font-medium mb-2">Whole</p>
                                <div class="fc-inp-box" style="min-width:4rem;">
                                    <input type="number" x-model="mix.whole" placeholder="2" class="fc-num-input" @keydown.enter="calculate()">
                                </div>
                            </div>
                            <div class="text-center mt-5 text-gray-300 font-light text-xl">and</div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 font-medium mb-2">Fraction Part</p>
                                <div class="fc-inp-box">
                                    <input type="number" x-model="mix.n" placeholder="3" class="fc-num-input" @keydown.enter="calculate()">
                                    <div class="fc-inp-bar"></div>
                                    <input type="number" x-model="mix.d" placeholder="4" class="fc-num-input" @keydown.enter="calculate()">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Decimal input (dec2frac) --}}
                    <div x-show="cvType === 'dec2frac'" class="flex justify-center py-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-400 font-medium mb-2">Decimal Number</p>
                            <input type="text" x-model="decVal" placeholder="0.625"
                                   class="form-input text-center text-2xl font-bold w-36 py-3"
                                   @keydown.enter="calculate()">
                        </div>
                    </div>
                </div>

                {{-- Error --}}
                <div x-show="errMsg" x-transition
                     class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="errMsg"></span>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-wrap gap-2 pt-1">
                    <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Calculate
                    </button>
                    <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                    <button type="button" @click="clearAll()" x-show="phase !== 'idle'" class="btn btn-secondary">✕ Clear</button>
                </div>
                <p class="text-xs text-gray-400 text-center">
                    Press <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> in any field to calculate
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             RESULTS CARD
        ══════════════════════════════════════════════ --}}
        <div class="card fc-in"
             x-show="phase === 'done' && result"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             id="fc-results">

            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span class="font-semibold text-gray-800 text-sm">Result</span>
                </div>
                <button type="button" @click="copyResult()"
                        class="btn btn-sm border border-gray-200"
                        :class="copyFlash ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-white text-gray-600 hover:bg-gray-50'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copyFlash ? '✓ Copied!' : 'Copy'"></span>
                </button>
            </div>

            <div class="p-5 space-y-5">

                {{-- ── ARITHMETIC result ── --}}
                <div x-show="result && result.type === 'arithmetic'" class="space-y-4">
                    {{-- Equation row --}}
                    <div class="result-box">
                        <div class="fc-eq">
                            {{-- FA --}}
                            <div x-show="result && result.fa && result.fa.d !== 1" class="fc-disp">
                                <span class="fc-disp-n muted" x-text="result ? result.fa.n : ''"></span>
                                <div class="fc-disp-bar muted"></div>
                                <span class="fc-disp-d muted" x-text="result ? result.fa.d : ''"></span>
                            </div>
                            <span x-show="result && result.fa && result.fa.d === 1" class="fc-disp-whole muted" x-text="result ? result.fa.n : ''"></span>

                            <span class="fc-opsym op-main" x-text="result ? result.opSym : ''"></span>

                            {{-- FB --}}
                            <div x-show="result && result.fb && result.fb.d !== 1" class="fc-disp">
                                <span class="fc-disp-n muted" x-text="result ? result.fb.n : ''"></span>
                                <div class="fc-disp-bar muted"></div>
                                <span class="fc-disp-d muted" x-text="result ? result.fb.d : ''"></span>
                            </div>
                            <span x-show="result && result.fb && result.fb.d === 1" class="fc-disp-whole muted" x-text="result ? result.fb.n : ''"></span>

                            <span class="fc-opsym">=</span>

                            {{-- Result --}}
                            <div x-show="result && result.frac && result.frac.d !== 1" class="fc-disp">
                                <span class="fc-disp-n" x-text="result ? result.frac.n : ''"></span>
                                <div class="fc-disp-bar"></div>
                                <span class="fc-disp-d" x-text="result ? result.frac.d : ''"></span>
                            </div>
                            <span x-show="result && result.frac && result.frac.d === 1" class="fc-disp-whole" x-text="result ? result.frac.n : ''"></span>
                        </div>
                    </div>

                    {{-- Stat cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="fc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Fraction</div>
                            <div x-show="result && result.frac && result.frac.d !== 1" class="fc-disp" style="scale:.8; transform-origin:center;">
                                <span class="fc-disp-n" x-text="result ? result.frac.n : ''"></span>
                                <div class="fc-disp-bar"></div>
                                <span class="fc-disp-d" x-text="result ? result.frac.d : ''"></span>
                            </div>
                            <span x-show="result && result.frac && result.frac.d === 1" class="fc-disp-whole" x-text="result ? result.frac.n : ''"></span>
                        </div>
                        <div class="fc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Decimal</div>
                            <span class="text-2xl font-black" style="color:#0891b2;" x-text="result ? fmtDec(result.decimal) : ''"></span>
                        </div>
                        <div class="fc-stat" x-show="result && result.mixed">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Mixed Number</div>
                            <div class="flex items-center gap-1 justify-center flex-wrap">
                                <span class="text-2xl font-black text-emerald-600" x-text="result && result.mixed ? result.mixed.whole : ''"></span>
                                <div x-show="result && result.mixed && !result.mixed.isWhole" class="fc-disp" style="scale:.75; transform-origin:center left;">
                                    <span class="fc-disp-n" x-text="result && result.mixed ? result.mixed.n : ''"></span>
                                    <div class="fc-disp-bar"></div>
                                    <span class="fc-disp-d" x-text="result && result.mixed ? result.mixed.d : ''"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Percentage</div>
                            <span class="text-2xl font-black text-rose-500" x-text="result ? fmtDec(result.decimal * 100) + '%' : ''"></span>
                        </div>
                    </div>
                </div>

                {{-- ── SIMPLIFY result ── --}}
                <div x-show="result && result.type === 'simplify'" class="space-y-4">
                    <div class="result-box">
                        <div class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Original</p>
                                <div class="fc-disp">
                                    <span class="fc-disp-n muted" x-text="result ? result.orig.n : ''"></span>
                                    <div class="fc-disp-bar muted"></div>
                                    <span class="fc-disp-d muted" x-text="result ? result.orig.d : ''"></span>
                                </div>
                            </div>
                            <span class="fc-opsym">→</span>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Simplified</p>
                                <div x-show="result && result.frac && result.frac.d !== 1" class="fc-disp">
                                    <span class="fc-disp-n" x-text="result ? result.frac.n : ''"></span>
                                    <div class="fc-disp-bar"></div>
                                    <span class="fc-disp-d" x-text="result ? result.frac.d : ''"></span>
                                </div>
                                <span x-show="result && result.frac && result.frac.d === 1" class="fc-disp-whole" x-text="result ? result.frac.n : ''"></span>
                            </div>
                        </div>
                    </div>
                    <div x-show="result && result.alreadySimplified" class="alert-success flex items-center gap-2">
                        <span>✓</span> Already in simplest form — GCD is 1, nothing to simplify.
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="fc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Decimal</div>
                            <span class="text-xl font-black" style="color:#0891b2;" x-text="result ? fmtDec(result.decimal) : ''"></span>
                        </div>
                        <div class="fc-stat" x-show="result && result.mixed">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Mixed Number</div>
                            <div class="flex items-center gap-1 justify-center flex-wrap">
                                <span class="text-xl font-black text-emerald-600" x-text="result && result.mixed ? result.mixed.whole : ''"></span>
                                <div x-show="result && result.mixed && !result.mixed.isWhole" class="fc-disp" style="scale:.7; transform-origin:center left;">
                                    <span class="fc-disp-n" x-text="result && result.mixed ? result.mixed.n : ''"></span>
                                    <div class="fc-disp-bar"></div>
                                    <span class="fc-disp-d" x-text="result && result.mixed ? result.mixed.d : ''"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">GCD Used</div>
                            <span class="text-2xl font-black text-amber-500" x-text="result ? result.gcd : ''"></span>
                        </div>
                    </div>
                </div>

                {{-- ── CONVERT result ── --}}
                <div x-show="result && result.type === 'convert'" class="space-y-4">
                    <div class="result-box">

                        {{-- imp2mix --}}
                        <div x-show="result && result.cvType === 'imp2mix'" class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Improper Fraction</p>
                                <div class="fc-disp">
                                    <span class="fc-disp-n muted" x-text="result ? result.frac.n : ''"></span>
                                    <div class="fc-disp-bar muted"></div>
                                    <span class="fc-disp-d muted" x-text="result ? result.frac.d : ''"></span>
                                </div>
                            </div>
                            <span class="fc-opsym">=</span>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Mixed Number</p>
                                <div x-show="result && result.mixed" class="flex items-center gap-2 justify-center">
                                    <span class="fc-disp-whole" x-text="result && result.mixed ? result.mixed.whole : ''"></span>
                                    <div x-show="result && result.mixed && !result.mixed.isWhole" class="fc-disp" style="scale:.85;transform-origin:center left;">
                                        <span class="fc-disp-n" x-text="result && result.mixed ? result.mixed.n : ''"></span>
                                        <div class="fc-disp-bar"></div>
                                        <span class="fc-disp-d" x-text="result && result.mixed ? result.mixed.d : ''"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- mix2imp --}}
                        <div x-show="result && result.cvType === 'mix2imp'" class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Mixed Number</p>
                                <div class="flex items-center gap-2 justify-center">
                                    <span class="fc-disp-whole muted" x-text="result ? result.whole : ''"></span>
                                    <div class="fc-disp" style="scale:.85;transform-origin:center left;">
                                        <span class="fc-disp-n muted" x-text="result ? result.mixN : ''"></span>
                                        <div class="fc-disp-bar muted"></div>
                                        <span class="fc-disp-d muted" x-text="result ? result.mixD : ''"></span>
                                    </div>
                                </div>
                            </div>
                            <span class="fc-opsym">=</span>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Improper Fraction</p>
                                <div class="fc-disp">
                                    <span class="fc-disp-n" x-text="result ? result.frac.n : ''"></span>
                                    <div class="fc-disp-bar"></div>
                                    <span class="fc-disp-d" x-text="result ? result.frac.d : ''"></span>
                                </div>
                            </div>
                        </div>

                        {{-- frac2dec --}}
                        <div x-show="result && result.cvType === 'frac2dec'" class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Fraction</p>
                                <div class="fc-disp">
                                    <span class="fc-disp-n muted" x-text="result ? result.frac.n : ''"></span>
                                    <div class="fc-disp-bar muted"></div>
                                    <span class="fc-disp-d muted" x-text="result ? result.frac.d : ''"></span>
                                </div>
                            </div>
                            <span class="fc-opsym">=</span>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Decimal</p>
                                <span class="fc-disp-whole" style="background:linear-gradient(135deg,#0891b2,#06b6d4);-webkit-background-clip:text;background-clip:text;" x-text="result ? fmtDec(result.decimal) : ''"></span>
                            </div>
                        </div>

                        {{-- dec2frac --}}
                        <div x-show="result && result.cvType === 'dec2frac'" class="fc-eq">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Decimal</p>
                                <span class="fc-disp-whole muted" x-text="result ? result.decimal : ''"></span>
                            </div>
                            <span class="fc-opsym">=</span>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 mb-2">Fraction</p>
                                <div x-show="result && result.frac && result.frac.d !== 1" class="fc-disp">
                                    <span class="fc-disp-n" x-text="result ? result.frac.n : ''"></span>
                                    <div class="fc-disp-bar"></div>
                                    <span class="fc-disp-d" x-text="result ? result.frac.d : ''"></span>
                                </div>
                                <span x-show="result && result.frac && result.frac.d === 1" class="fc-disp-whole" x-text="result ? result.frac.n : ''"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Extra info row --}}
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                        <span class="gc-chip" style="background:#dbeafe;color:#1d4ed8;">
                            Decimal: <strong x-text="result ? fmtDec(result.decimal) : ''"></strong>
                        </span>
                        <span class="gc-chip" style="background:#fce7f3;color:#9d174d;">
                            Percentage: <strong x-text="result ? fmtDec(result.decimal * 100) + '%' : ''"></strong>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             STEPS CARD (collapsible)
        ══════════════════════════════════════════════ --}}
        <div class="card overflow-hidden fc-in"
             x-show="phase === 'done' && result && result.steps && result.steps.length > 0">
            <button type="button" @click="showSteps = !showSteps"
                    class="w-full flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="font-semibold text-gray-800 text-sm">Step-by-Step Solution</span>
                    <span class="badge badge-primary ml-1" x-text="result ? result.steps.length + ' steps' : ''"></span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="showSteps ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="showSteps" x-transition class="p-4 space-y-2">
                <template x-for="(step, i) in (result ? result.steps : [])" :key="i">
                    <div class="fc-step">
                        <span class="fc-step-num" x-text="i + 1"></span>
                        <span x-html="step"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             IDLE INFO CARDS
        ══════════════════════════════════════════════ --}}
        <div x-show="phase === 'idle'" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['icon'=>'➕','label'=>'Add & Subtract','desc'=>'Find common denominators automatically'],
                ['icon'=>'✖️','label'=>'Multiply & Divide','desc'=>'Cross-multiply with instant simplification'],
                ['icon'=>'✂️','label'=>'Simplify','desc'=>'Reduce any fraction using GCD'],
                ['icon'=>'🔄','label'=>'Convert','desc'=>'Improper ↔ Mixed ↔ Decimal'],
            ] as $info)
            <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                <p class="text-2xl mb-1.5">{{ $info['icon'] }}</p>
                <p class="text-sm font-semibold text-gray-700">{{ $info['label'] }}</p>
                <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $info['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Related tools --}}
        @if($relatedTools->count())
        <div x-show="phase === 'idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Calculators</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
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
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════
   FRACTION CALCULATOR — pure client-side Alpine component
═══════════════════════════════════════════════════════ */
function fracCalc() {
    return {
        /* ── Input state ── */
        mode: 'arithmetic',   /* arithmetic | simplify | convert */
        op:   'add',          /* add | sub | mul | div */
        a:    { n: '', d: '' },
        b:    { n: '', d: '' },
        s:    { n: '', d: '' },
        cvType: 'imp2mix',    /* imp2mix | mix2imp | frac2dec | dec2frac */
        imp:  { n: '', d: '' },
        mix:  { whole: '', n: '', d: '' },
        decVal: '',

        /* ── UI state ── */
        phase:     'idle',
        result:    null,
        errMsg:    '',
        showSteps: true,
        copyFlash: false,

        /* ── Computed ── */
        get opSym() {
            return { add: '+', sub: '−', mul: '×', div: '÷' }[this.op] || '+';
        },

        init() { /* nothing needed */ },

        setMode(m) {
            this.mode   = m;
            this.errMsg = '';
            this.result = null;
            this.phase  = 'idle';
        },

        /* ════════════════════════════════════════════════
           MATH HELPERS
        ════════════════════════════════════════════════ */

        /* Euclidean GCD — always positive */
        _gcd(a, b) {
            a = Math.abs(a); b = Math.abs(b);
            while (b) { var t = b; b = a % b; a = t; }
            return a || 1;
        },

        _lcm(a, b) {
            return Math.abs(a * b) / this._gcd(a, b);
        },

        /* Simplify n/d: normalise sign to numerator, reduce by GCD */
        _simp(n, d) {
            if (d === 0) return null;
            if (n === 0) return { n: 0, d: 1 };
            if (d < 0) { n = -n; d = -d; }
            var g = this._gcd(Math.abs(n), d);
            return { n: n / g, d: d / g };
        },

        /* Arithmetic operations — all return simplified { n, d } or null */
        _add(an, ad, bn, bd) {
            var lcd = this._lcm(ad, bd);
            return this._simp(an * (lcd / ad) + bn * (lcd / bd), lcd);
        },
        _sub(an, ad, bn, bd) { return this._add(an, ad, -bn, bd); },
        _mul(an, ad, bn, bd) { return this._simp(an * bn, ad * bd); },
        _div(an, ad, bn, bd) {
            if (bn === 0) return null;
            return this._simp(an * bd, ad * bn);
        },

        /* Convert improper fraction to mixed number
           Returns { whole, n, d, isWhole } or null if already proper */
        _toMixed(n, d) {
            if (d === 1) return null; /* whole number — handled separately */
            var sign  = n < 0 ? -1 : 1;
            var absN  = Math.abs(n);
            if (absN < d) return null; /* proper fraction */
            var whole = sign * Math.floor(absN / d);
            var rem   = absN % d;
            if (rem === 0) return { whole: whole, n: 0, d: 1, isWhole: true };
            return { whole: whole, n: rem, d: d, isWhole: false };
        },

        /* Format decimal — up to 8 dp, strip trailing zeros */
        fmtDec(x) {
            if (x === null || x === undefined || isNaN(x)) return '—';
            return parseFloat(x.toFixed(8)).toString();
        },

        /* ════════════════════════════════════════════════
           INPUT PARSING & VALIDATION
        ════════════════════════════════════════════════ */

        /* Parse an integer field; throws human-readable string on error */
        _int(v, label) {
            if (v === '' || v === null || v === undefined) throw label + ' is required.';
            var n = parseInt(v, 10);
            if (isNaN(n) || !isFinite(n)) throw label + ' must be a whole number.';
            return n;
        },

        /* ════════════════════════════════════════════════
           CALCULATE — dispatcher
        ════════════════════════════════════════════════ */

        calculate() {
            this.errMsg = '';
            this.result = null;
            try {
                if      (this.mode === 'arithmetic') this.result = this._calcArithmetic();
                else if (this.mode === 'simplify')   this.result = this._calcSimplify();
                else                                  this.result = this._calcConvert();
                this.phase = 'done';

                /* Scroll to results on mobile */
                var self = this;
                if (window.innerWidth < 1024) {
                    setTimeout(function() {
                        var el = document.getElementById('fc-results');
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 80);
                }
            } catch (e) {
                this.errMsg = typeof e === 'string' ? e : 'Unexpected error. Please check your inputs.';
                this.phase  = 'idle';
            }
        },

        /* ── Arithmetic ── */
        _calcArithmetic() {
            var an = this._int(this.a.n, 'First numerator');
            var ad = this._int(this.a.d, 'First denominator');
            var bn = this._int(this.b.n, 'Second numerator');
            var bd = this._int(this.b.d, 'Second denominator');

            if (ad === 0) throw 'First denominator cannot be zero.';
            if (bd === 0) throw 'Second denominator cannot be zero.';

            /* Normalise inputs */
            var fa = this._simp(an, ad);
            var fb = this._simp(bn, bd);

            var frac, steps;
            if (this.op === 'add') {
                frac  = this._add(fa.n, fa.d, fb.n, fb.d);
                steps = this._stepsAdd(fa.n, fa.d, fb.n, fb.d, frac);
            } else if (this.op === 'sub') {
                frac  = this._sub(fa.n, fa.d, fb.n, fb.d);
                steps = this._stepsSub(fa.n, fa.d, fb.n, fb.d, frac);
            } else if (this.op === 'mul') {
                frac  = this._mul(fa.n, fa.d, fb.n, fb.d);
                steps = this._stepsMul(fa.n, fa.d, fb.n, fb.d, frac);
            } else {
                if (fb.n === 0) throw 'Cannot divide by zero (second fraction equals 0).';
                frac  = this._div(fa.n, fa.d, fb.n, fb.d);
                steps = this._stepsDiv(fa.n, fa.d, fb.n, fb.d, frac);
            }
            if (!frac) throw 'Could not compute — please check your inputs.';

            return {
                type: 'arithmetic', op: this.op, opSym: this.opSym,
                fa: fa, fb: fb, frac: frac,
                mixed:   this._toMixed(frac.n, frac.d),
                decimal: frac.n / frac.d,
                steps:   steps,
            };
        },

        /* ── Simplify ── */
        _calcSimplify() {
            var n = this._int(this.s.n, 'Numerator');
            var d = this._int(this.s.d, 'Denominator');
            if (d === 0) throw 'Denominator cannot be zero.';

            var g    = this._gcd(Math.abs(n), Math.abs(d));
            var frac = this._simp(n, d);
            if (!frac) throw 'Invalid fraction.';

            return {
                type: 'simplify',
                orig: { n: n, d: d },
                frac: frac,
                mixed:   this._toMixed(frac.n, frac.d),
                decimal: frac.n / frac.d,
                gcd:     g,
                alreadySimplified: g === 1,
                steps:   this._stepsSimplify(n, d, frac, g),
            };
        },

        /* ── Convert ── */
        _calcConvert() {
            if (this.cvType === 'imp2mix') {
                var n = this._int(this.imp.n, 'Numerator');
                var d = this._int(this.imp.d, 'Denominator');
                if (d === 0) throw 'Denominator cannot be zero.';
                if (d < 0)   throw 'Denominator should be a positive integer.';
                var frac  = this._simp(n, d);
                var mixed = this._toMixed(frac.n, frac.d);
                if (!mixed) throw Math.abs(n) < d
                    ? 'This is a proper fraction (numerator < denominator). Enter an improper fraction where |numerator| ≥ denominator.'
                    : n + '/' + d + ' is a whole number (' + (n/d) + '), not a mixed number.';
                return {
                    type: 'convert', cvType: 'imp2mix',
                    frac: frac, mixed: mixed,
                    decimal: frac.n / frac.d,
                    steps: this._stepsImp2Mix(frac.n, frac.d, mixed),
                };
            }

            if (this.cvType === 'mix2imp') {
                var whole = this._int(this.mix.whole, 'Whole number');
                var mn    = this._int(this.mix.n,     'Fraction numerator');
                var md    = this._int(this.mix.d,     'Fraction denominator');
                if (md === 0) throw 'Fraction denominator cannot be zero.';
                if (md < 0)   throw 'Fraction denominator should be positive.';
                if (mn < 0)   throw 'Fraction numerator should be positive (put the negative sign on the whole number).';

                var sign  = whole < 0 ? -1 : 1;
                var impN  = sign * (Math.abs(whole) * md + mn);
                var frac  = this._simp(impN, md);
                return {
                    type: 'convert', cvType: 'mix2imp',
                    whole: whole, mixN: mn, mixD: md,
                    frac: frac,
                    decimal: frac.n / frac.d,
                    steps: this._stepsMix2Imp(whole, mn, md, frac),
                };
            }

            if (this.cvType === 'frac2dec') {
                var n = this._int(this.imp.n, 'Numerator');
                var d = this._int(this.imp.d, 'Denominator');
                if (d === 0) throw 'Denominator cannot be zero.';
                var dec = n / d;
                return {
                    type: 'convert', cvType: 'frac2dec',
                    frac: { n: n, d: d },
                    decimal: dec,
                    steps: [
                        'Divide the numerator by the denominator: <strong>' + n + ' ÷ ' + d + '</strong>',
                        '= <strong>' + this.fmtDec(dec) + '</strong>',
                        this._isRepeating(n, d) ? 'Note: this is a <em>repeating decimal</em>.' : 'This is a terminating decimal.',
                    ],
                };
            }

            if (this.cvType === 'dec2frac') {
                var val = parseFloat(this.decVal);
                if (isNaN(val) || !isFinite(val)) throw 'Please enter a valid decimal number (e.g. 0.625).';
                var str    = this.decVal.toString().trim().replace(/^-/, '');
                var dotIdx = str.indexOf('.');
                if (dotIdx === -1) {
                    return { type: 'convert', cvType: 'dec2frac', decimal: val, frac: { n: val, d: 1 }, steps: [val + ' is already a whole number: <strong>' + val + '/1</strong>'] };
                }
                var dp    = str.length - dotIdx - 1;
                var denom = Math.pow(10, dp);
                var numer = Math.round(val * denom);
                var frac  = this._simp(numer, denom);
                var g     = this._gcd(Math.abs(numer), denom);
                return {
                    type: 'convert', cvType: 'dec2frac',
                    decimal: val, frac: frac,
                    steps: [
                        'Count decimal places: <strong>' + dp + '</strong>',
                        'Multiply by 10<sup>' + dp + '</sup> (' + denom + '): ' + val + ' × ' + denom + ' = <strong>' + numer + '</strong>',
                        'Write as fraction: <strong>' + numer + '/' + denom + '</strong>',
                        'Find GCD(' + Math.abs(numer) + ', ' + denom + ') = <strong>' + g + '</strong>',
                        g > 1 ? 'Divide both by ' + g + ': <strong>' + frac.n + '/' + frac.d + '</strong>' : 'GCD = 1 — already in simplest form: <strong>' + frac.n + '/' + frac.d + '</strong>',
                    ],
                };
            }
        },

        /* Detect if n/d produces a repeating decimal */
        _isRepeating(n, d) {
            var s = this._simp(n, d);
            if (!s) return false;
            var dd = s.d;
            while (dd % 2 === 0) dd /= 2;
            while (dd % 5 === 0) dd /= 5;
            return dd !== 1;
        },

        /* ════════════════════════════════════════════════
           STEP GENERATORS
        ════════════════════════════════════════════════ */

        _stepsAdd(an, ad, bn, bd, res) {
            var steps = [];
            if (ad === bd) {
                steps.push('Denominators match (' + ad + '), so add numerators directly.');
                steps.push(an + ' + ' + bn + ' = <strong>' + (an + bn) + '</strong>');
                steps.push('Result before simplifying: <strong>' + (an + bn) + '/' + ad + '</strong>');
            } else {
                var lcd  = this._lcm(ad, bd);
                var an2  = an * (lcd / ad);
                var bn2  = bn * (lcd / bd);
                steps.push('Find the LCD of ' + ad + ' and ' + bd + ': <strong>LCD = ' + lcd + '</strong>');
                steps.push('Convert: ' + an + '/' + ad + ' = ' + an2 + '/' + lcd + '&nbsp;&nbsp;and&nbsp;&nbsp;' + bn + '/' + bd + ' = ' + bn2 + '/' + lcd);
                steps.push('Add numerators: ' + an2 + ' + ' + bn2 + ' = <strong>' + (an2 + bn2) + '/' + lcd + '</strong>');
            }
            var g = this._gcd(Math.abs(res.n), res.d);
            steps.push(g > 1
                ? 'Simplify by dividing by GCD(' + Math.abs(res.n * g) + ', ' + res.d * g + ') = ' + g + ': <strong>' + res.n + '/' + res.d + '</strong>'
                : 'GCD = 1 — already in simplest form: <strong>' + res.n + '/' + res.d + '</strong>');
            return steps;
        },

        _stepsSub(an, ad, bn, bd, res) {
            var steps = [];
            if (ad === bd) {
                steps.push('Denominators match (' + ad + '), so subtract numerators directly.');
                steps.push(an + ' − ' + bn + ' = <strong>' + (an - bn) + '</strong>');
                steps.push('Result before simplifying: <strong>' + (an - bn) + '/' + ad + '</strong>');
            } else {
                var lcd  = this._lcm(ad, bd);
                var an2  = an * (lcd / ad);
                var bn2  = bn * (lcd / bd);
                steps.push('Find the LCD of ' + ad + ' and ' + bd + ': <strong>LCD = ' + lcd + '</strong>');
                steps.push('Convert: ' + an + '/' + ad + ' = ' + an2 + '/' + lcd + '&nbsp;&nbsp;and&nbsp;&nbsp;' + bn + '/' + bd + ' = ' + bn2 + '/' + lcd);
                steps.push('Subtract: ' + an2 + ' − ' + bn2 + ' = <strong>' + (an2 - bn2) + '/' + lcd + '</strong>');
            }
            var g = this._gcd(Math.abs(res.n), res.d);
            steps.push(g > 1
                ? 'Simplify by GCD = ' + g + ': <strong>' + res.n + '/' + res.d + '</strong>'
                : 'GCD = 1 — already simplified: <strong>' + res.n + '/' + res.d + '</strong>');
            return steps;
        },

        _stepsMul(an, ad, bn, bd, res) {
            var rn = an * bn, rd = ad * bd;
            var g  = this._gcd(Math.abs(rn), Math.abs(rd));
            return [
                'Multiply numerators: ' + an + ' × ' + bn + ' = <strong>' + rn + '</strong>',
                'Multiply denominators: ' + ad + ' × ' + bd + ' = <strong>' + rd + '</strong>',
                'Result before simplifying: <strong>' + rn + '/' + rd + '</strong>',
                g > 1
                    ? 'Simplify by GCD(' + Math.abs(rn) + ', ' + Math.abs(rd) + ') = ' + g + ': <strong>' + res.n + '/' + res.d + '</strong>'
                    : 'GCD = 1 — already in simplest form: <strong>' + res.n + '/' + res.d + '</strong>',
            ];
        },

        _stepsDiv(an, ad, bn, bd, res) {
            var rn = an * bd, rd = ad * bn;
            var g  = this._gcd(Math.abs(rn), Math.abs(rd));
            return [
                'Keep the first fraction: <strong>' + an + '/' + ad + '</strong>',
                'Find the reciprocal of the second fraction: ' + bn + '/' + bd + ' → <strong>' + bd + '/' + bn + '</strong>',
                'Multiply: ' + an + '/' + ad + ' × ' + bd + '/' + bn + ' = <strong>' + rn + '/' + rd + '</strong>',
                g > 1
                    ? 'Simplify by GCD = ' + g + ': <strong>' + res.n + '/' + res.d + '</strong>'
                    : 'GCD = 1 — already simplified: <strong>' + res.n + '/' + res.d + '</strong>',
            ];
        },

        _stepsSimplify(n, d, frac, g) {
            if (g === 1) return ['GCD(' + Math.abs(n) + ', ' + Math.abs(d) + ') = 1. The fraction is already in its simplest form.'];
            return [
                'Find GCD(' + Math.abs(n) + ', ' + Math.abs(d) + ') = <strong>' + g + '</strong>',
                'Divide numerator: ' + n + ' ÷ ' + g + ' = <strong>' + frac.n + '</strong>',
                'Divide denominator: ' + Math.abs(d) + ' ÷ ' + g + ' = <strong>' + frac.d + '</strong>',
                'Simplified result: <strong>' + frac.n + '/' + frac.d + '</strong>',
            ];
        },

        _stepsImp2Mix(n, d, mixed) {
            return [
                'Divide |' + n + '| ÷ ' + d + ':',
                'Quotient (whole part): <strong>' + Math.abs(mixed.whole) + '</strong>',
                'Remainder (new numerator): <strong>' + mixed.n + '</strong>',
                'Keep the denominator: <strong>' + mixed.d + '</strong>',
                'Mixed number: <strong>' + mixed.whole + ' and ' + mixed.n + '/' + mixed.d + '</strong>',
            ];
        },

        _stepsMix2Imp(whole, mn, md, frac) {
            var sign  = whole < 0 ? -1 : 1;
            var multi = Math.abs(whole) * md;
            var impN  = sign * (multi + mn);
            var steps = [
                'Multiply whole × denominator: ' + Math.abs(whole) + ' × ' + md + ' = <strong>' + multi + '</strong>',
                'Add numerator: ' + multi + ' + ' + mn + ' = <strong>' + (multi + mn) + '</strong>',
                'Apply sign (' + (sign < 0 ? 'negative' : 'positive') + '): <strong>' + impN + '</strong>',
                'Improper fraction: <strong>' + impN + '/' + md + '</strong>',
            ];
            var g = this._gcd(Math.abs(impN), md);
            if (g > 1) steps.push('Simplify by GCD = ' + g + ': <strong>' + frac.n + '/' + frac.d + '</strong>');
            return steps;
        },

        /* ════════════════════════════════════════════════
           SAMPLE & CLEAR
        ════════════════════════════════════════════════ */

        loadSample() {
            this.errMsg = '';
            if (this.mode === 'arithmetic') {
                var samples = {
                    add: [['3','4','1','2']], sub: [['5','6','1','3']],
                    mul: [['2','3','3','4']], div: [['7','8','1','4']],
                };
                var s = (samples[this.op] || samples.add)[0];
                this.a.n = s[0]; this.a.d = s[1]; this.b.n = s[2]; this.b.d = s[3];
            } else if (this.mode === 'simplify') {
                this.s.n = '12'; this.s.d = '18';
            } else {
                if (this.cvType === 'imp2mix')  { this.imp.n = '7';  this.imp.d = '3'; }
                if (this.cvType === 'mix2imp')  { this.mix.whole = '2'; this.mix.n = '3'; this.mix.d = '4'; }
                if (this.cvType === 'frac2dec') { this.imp.n = '3';  this.imp.d = '8'; }
                if (this.cvType === 'dec2frac') { this.decVal = '0.625'; }
            }
        },

        clearAll() {
            this.a = { n:'', d:'' }; this.b = { n:'', d:'' }; this.s = { n:'', d:'' };
            this.imp = { n:'', d:'' }; this.mix = { whole:'', n:'', d:'' }; this.decVal = '';
            this.errMsg = ''; this.result = null; this.phase = 'idle';
        },

        /* ════════════════════════════════════════════════
           COPY RESULT
        ════════════════════════════════════════════════ */

        async copyResult() {
            if (!this.result) return;
            var lines = [];
            if (this.result.frac)    lines.push('Fraction: ' + this.result.frac.n + '/' + this.result.frac.d);
            if (this.result.decimal !== undefined) lines.push('Decimal: ' + this.fmtDec(this.result.decimal));
            if (this.result.mixed && !this.result.mixed.isWhole)
                lines.push('Mixed: ' + this.result.mixed.whole + ' ' + this.result.mixed.n + '/' + this.result.mixed.d);
            var text = lines.join('\n');
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select(); document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.copyFlash = true;
            setTimeout(function() { self.copyFlash = false; }, 1800);
        },
    };
}
</script>
@endpush
