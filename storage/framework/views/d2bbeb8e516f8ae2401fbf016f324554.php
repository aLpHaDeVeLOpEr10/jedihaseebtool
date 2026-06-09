<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Compound Interest Calculator  —  prefix: ci-
══════════════════════════════════════════════ */

/* ── Big gradient hero number ── */
.ci-hero-amount {
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900;
    line-height: 1;
    letter-spacing: -.02em;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    word-break: break-all;
}

/* ── Stat cards ── */
.ci-stat {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 1.125rem;
    padding: 1.1rem 1rem;
    display: flex; flex-direction: column; align-items: center;
    gap: .35rem; text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.ci-stat:hover { border-color: #a5b4fc; box-shadow: 0 4px 16px rgba(79,70,229,.08); transform: translateY(-1px); }
.ci-stat-lbl { font-size: .65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }
.ci-stat-val {
    font-size: 1.55rem; font-weight: 800;
    background: linear-gradient(135deg, #059669, #10b981);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    word-break: break-all;
}
.ci-stat-val.violet { background: linear-gradient(135deg, #7c3aed, #a855f7); -webkit-background-clip: text; background-clip: text; }
.ci-stat-val.amber  { background: linear-gradient(135deg, #d97706, #f59e0b); -webkit-background-clip: text; background-clip: text; }
.ci-stat-val.rose   { background: linear-gradient(135deg, #e11d48, #f43f5e); -webkit-background-clip: text; background-clip: text; }
.ci-stat-sub { font-size: .68rem; color: #94a3b8; }

/* ── Stacked breakdown bar ── */
.ci-breakdown-track {
    height: 18px;
    border-radius: 9999px;
    overflow: hidden;
    display: flex;
    background: #f1f5f9;
}
.ci-breakdown-principal { background: #4f46e5; }
.ci-breakdown-contrib   { background: #7c3aed; }
.ci-breakdown-interest  { background: #10b981; }
.ci-legend-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}

/* ── Year table ── */
.ci-table { width: 100%; border-collapse: collapse; font-size: .78rem; }
.ci-table th {
    text-align: right; padding: .5rem .75rem;
    font-size: .65rem; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .07em;
    border-bottom: 1.5px solid #e2e8f0; background: #f8fafc;
}
.ci-table th:first-child { text-align: center; }
.ci-table td {
    text-align: right; padding: .5rem .75rem;
    border-bottom: 1px solid #f1f5f9; color: #374151; font-variant-numeric: tabular-nums;
}
.ci-table td:first-child { text-align: center; font-weight: 600; color: #6366f1; }
.ci-table tr:last-child td { border-bottom: none; font-weight: 700; }
.ci-table tr:hover td { background: #f5f3ff; }
.ci-table .ci-td-interest { color: #059669; font-weight: 600; }

/* ── SVG chart ── */
.ci-chart-wrap { overflow: hidden; border-radius: .75rem; background: #f8fafc; border: 1px solid #e2e8f0; }

/* ── Currency prefix ── */
.ci-prefix-wrap { display: flex; align-items: stretch; }
.ci-prefix {
    display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-right: none;
    border-radius: .75rem 0 0 .75rem;
    font-size: .9rem; font-weight: 700; color: #374151; white-space: nowrap;
}
.ci-prefix-wrap .form-input { border-radius: 0 .75rem .75rem 0 !important; flex: 1; min-width: 0; }
.ci-suffix-wrap { display: flex; align-items: stretch; }
.ci-suffix-wrap .form-input { border-radius: .75rem 0 0 .75rem !important; flex: 1; border-right: none; min-width: 0; }
.ci-suffix {
    display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-left: none;
    border-radius: 0 .75rem .75rem 0;
    font-size: .82rem; font-weight: 600; color: #64748b;
}

/* ── Toggle buttons ── */
.ci-toggle-group { display: flex; background: #f1f5f9; border-radius: .6rem; padding: .2rem; gap: .15rem; }
.ci-toggle-btn {
    flex: 1; padding: .3rem .65rem; border-radius: .45rem;
    font-size: .75rem; font-weight: 600; color: #64748b;
    cursor: pointer; transition: all .15s; border: none; background: none;
}
.ci-toggle-btn.active { background: white; color: #4f46e5; box-shadow: 0 1px 4px rgba(0,0,0,.1); }

/* ── Entrance ── */
@keyframes ciIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.ci-in { animation: ciIn .3s ease-out; }

/* ── Shimmer ── */
@keyframes ciShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.ci-shim { height: 5.5rem; border-radius: 1.125rem;
    background: linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%);
    background-size: 1200px 100%; animation: ciShim 1.4s infinite; }

/* ── Section divider ── */
.ci-div {
    display: flex; align-items: center; gap: .6rem;
    color: #94a3b8; font-size: .65rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .09em;
}
.ci-div::before,.ci-div::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

/* ── Insight row ── */
.ci-insight { display: flex; align-items: center; gap: .75rem; padding: .65rem .9rem;
    background: #f5f3ff; border-radius: .75rem; border: 1px solid #ddd6fe; }
.ci-insight-icon { font-size: 1.25rem; flex-shrink: 0; }
.ci-insight-val { font-weight: 800; color: #4f46e5; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="ciCalc()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Calculate how your money grows over time with <strong>compound interest</strong>, optional regular contributions, multiple compounding frequencies, and a full year-by-year breakdown.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-primary">Year-by-Year</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100">
                        <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                        <span class="font-semibold text-gray-800 text-sm">Calculator Inputs</span>
                    </div>

                    <div class="p-5 space-y-4">

                        
                        <div>
                            <label class="form-label">Principal Amount</label>
                            <div class="flex gap-2">
                                <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                    <option value="$">$ USD</option>
                                    <option value="€">€ EUR</option>
                                    <option value="£">£ GBP</option>
                                    <option value="¥">¥ JPY</option>
                                    <option value="₹">₹ INR</option>
                                    <option value="₦">₦ NGN</option>
                                    <option value="A$">A$ AUD</option>
                                    <option value="C$">C$ CAD</option>
                                </select>
                                <div class="ci-prefix-wrap flex-1">
                                    <span class="ci-prefix" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="principal"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 10 000">
                                </div>
                            </div>
                            <p class="form-help">The initial amount you are investing.</p>
                        </div>

                        
                        <div>
                            <label class="form-label">Annual Interest Rate</label>
                            <div class="ci-suffix-wrap">
                                <input type="number" step="any" min="0" max="1000" x-model="rate"
                                       @input.debounce.400ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 8">
                                <span class="ci-suffix">% / year</span>
                            </div>
                            <p class="form-help" x-show="parseFloat(rate) > 50" x-transition
                               class="text-amber-600 text-xs mt-1">⚠ Rate looks very high — double check it's a percentage, not a decimal.</p>
                        </div>

                        
                        <div>
                            <label class="form-label">Time Period</label>
                            <div class="flex gap-2">
                                <input type="number" step="any" min="0" x-model="timePeriod"
                                       @input.debounce.400ms="autoCalc()"
                                       class="form-input flex-1" placeholder="e.g. 10">
                                <div class="ci-toggle-group" style="min-width:120px">
                                    <button type="button" class="ci-toggle-btn" :class="{active: timeUnit==='years'}"
                                            @click="timeUnit='years'; autoCalc()">Years</button>
                                    <button type="button" class="ci-toggle-btn" :class="{active: timeUnit==='months'}"
                                            @click="timeUnit='months'; autoCalc()">Months</button>
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">Compounding Frequency</label>
                            <select x-model="compFreq" @change="autoCalc()" class="form-input text-sm">
                                <option value="365">Daily (365×/year)</option>
                                <option value="52">Weekly (52×/year)</option>
                                <option value="26">Bi-Weekly (26×/year)</option>
                                <option value="12" selected>Monthly (12×/year)</option>
                                <option value="4">Quarterly (4×/year)</option>
                                <option value="2">Semi-Annually (2×/year)</option>
                                <option value="1">Annually (1×/year)</option>
                                <option value="0">Continuously</option>
                            </select>
                            <p class="form-help">How often interest is added to your balance.</p>
                        </div>

                        
                        <div>
                            <label class="form-label">Regular Contribution <span class="text-gray-400 font-normal">(optional)</span></label>
                            <div class="ci-prefix-wrap">
                                <span class="ci-prefix" x-text="currency"></span>
                                <input type="number" step="any" min="0" x-model="contribution"
                                       @input.debounce.400ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 200">
                            </div>
                            <p class="form-help">Amount added at each contribution interval.</p>
                        </div>

                        
                        <div x-show="hasContrib" x-transition class="space-y-3 pl-3 border-l-2 border-brand-100">

                            <div>
                                <label class="form-label">Contribution Frequency</label>
                                <select x-model="contribFreq" @change="autoCalc()" class="form-input text-sm">
                                    <option value="12">Monthly</option>
                                    <option value="4">Quarterly</option>
                                    <option value="2">Semi-Annually</option>
                                    <option value="1">Annually</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Contribution Timing</label>
                                <div class="ci-toggle-group">
                                    <button type="button" class="ci-toggle-btn" :class="{active: contribTiming==='end'}"
                                            @click="contribTiming='end'; autoCalc()">End of Period</button>
                                    <button type="button" class="ci-toggle-btn" :class="{active: contribTiming==='start'}"
                                            @click="contribTiming='start'; autoCalc()">Beginning</button>
                                </div>
                            </div>
                        </div>

                        
                        <div x-show="error" x-transition
                             class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="error"></span>
                        </div>

                        
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Calculate
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-3 space-y-4" id="ci-results">

                
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="ci-shim"></div></template>
                </div>

                
                <div x-show="phase==='done' && result"
                     class="space-y-4 ci-in"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">

                    
                    <div class="card overflow-hidden">
                        <div style="background:linear-gradient(135deg,#f5f3ff 0%,#ede9fe 100%);" class="px-6 py-5">
                            <p class="text-xs font-bold text-brand-400 uppercase tracking-widest mb-1">Final Balance</p>
                            <p class="ci-hero-amount" x-text="result ? fmtC(result.finalBalance) : '—'"></p>
                            <p class="text-xs text-gray-500 mt-1.5">
                                after <strong x-text="result ? fmtYrs() : ''"></strong>
                                — compounded <strong x-text="compFreqLabel()"></strong>
                            </p>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-3 gap-3">
                        <div class="ci-stat">
                            <div class="ci-stat-lbl">Total Interest</div>
                            <div class="ci-stat-val" x-text="result ? fmtC(result.totalInterest) : '—'"></div>
                            <div class="ci-stat-sub" x-text="result ? fmtPct(result.totalInterest / result.totalInvested * 100) + ' return' : ''"></div>
                        </div>
                        <div class="ci-stat">
                            <div class="ci-stat-lbl">Total Invested</div>
                            <div class="ci-stat-val violet" x-text="result ? fmtC(result.totalInvested) : '—'"></div>
                            <div class="ci-stat-sub" x-text="result && result.totalContribs > 0 ? 'incl. ' + fmtC(result.totalContribs) + ' contributions' : 'principal only'"></div>
                        </div>
                        <div class="ci-stat">
                            <div class="ci-stat-lbl">Eff. Annual Yield</div>
                            <div class="ci-stat-val amber" x-text="result ? fmtPct(result.apy * 100) : '—'"></div>
                            <div class="ci-stat-sub">APY / EAR</div>
                        </div>
                    </div>

                    
                    <div class="card p-4 space-y-3">
                        <p class="text-sm font-semibold text-gray-700">Balance Breakdown</p>
                        <div class="ci-breakdown-track">
                            <div class="ci-breakdown-principal transition-all duration-500"
                                 :style="'width:' + (result ? result.principalPct : 0) + '%'" :title="'Principal: ' + (result ? fmtC(result.principal) : '')"></div>
                            <div class="ci-breakdown-contrib transition-all duration-500"
                                 :style="'width:' + (result ? result.contribPct : 0) + '%'" :title="'Contributions: ' + (result ? fmtC(result.totalContribs) : '')"></div>
                            <div class="ci-breakdown-interest transition-all duration-500"
                                 :style="'width:' + (result ? result.interestPct : 0) + '%'" :title="'Interest: ' + (result ? fmtC(result.totalInterest) : '')"></div>
                        </div>
                        <div class="flex flex-wrap gap-4 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="ci-legend-dot" style="background:#4f46e5"></span>
                                <span class="text-gray-600">Principal <strong class="text-gray-800" x-text="result ? fmtC(result.principal) : ''"></strong>
                                (<span x-text="result ? fmtPct(result.principalPct) : ''"></span>)</span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="result && result.totalContribs > 0">
                                <span class="ci-legend-dot" style="background:#7c3aed"></span>
                                <span class="text-gray-600">Contributions <strong class="text-gray-800" x-text="result ? fmtC(result.totalContribs) : ''"></strong>
                                (<span x-text="result ? fmtPct(result.contribPct) : ''"></span>)</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="ci-legend-dot" style="background:#10b981"></span>
                                <span class="text-gray-600">Interest <strong class="text-gray-800" x-text="result ? fmtC(result.totalInterest) : ''"></strong>
                                (<span x-text="result ? fmtPct(result.interestPct) : ''"></span>)</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="ci-insight">
                            <span class="ci-insight-icon">⏱</span>
                            <div class="text-sm">
                                <p class="text-gray-500 text-xs">Doubling time</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="ci-insight-val" x-text="result ? fmt(result.doublingYears) : '—'"></span>
                                    <span class="text-gray-500 text-xs"> years (exact)</span>
                                </p>
                            </div>
                        </div>
                        <div class="ci-insight">
                            <span class="ci-insight-icon">💰</span>
                            <div class="text-sm">
                                <p class="text-gray-500 text-xs">Rule of 72</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="ci-insight-val" x-text="result ? fmt(result.rule72) : '—'"></span>
                                    <span class="text-gray-500 text-xs"> years (approx.)</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Growth Over Time</p>
                        <div class="ci-chart-wrap">
                            <svg x-show="result && result.chart"
                                 :viewBox="result && result.chart ? '0 0 ' + result.chart.w + ' ' + result.chart.h : '0 0 500 200'"
                                 class="w-full" style="min-height:150px; display:block;">
                                <defs>
                                    <linearGradient id="ciAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#4f46e5" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#4f46e5" stop-opacity="0.02"/>
                                    </linearGradient>
                                    <linearGradient id="ciLineGrad" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#4f46e5"/>
                                        <stop offset="100%" stop-color="#7c3aed"/>
                                    </linearGradient>
                                </defs>

                                
                                <template x-if="result && result.chart">
                                    <g>
                                        <template x-for="gl in result.chart.gridLines" :key="gl.y">
                                            <line :x1="result.chart.padX" :y1="gl.y" :x2="result.chart.w - result.chart.padR" :y2="gl.y"
                                                  stroke="#e2e8f0" stroke-width="1"/>
                                        </template>
                                        
                                        <template x-for="gl in result.chart.gridLines" :key="'lbl'+gl.y">
                                            <text :x="result.chart.padX - 6" :y="gl.y + 4"
                                                  text-anchor="end" font-size="9" fill="#9ca3af" x-text="gl.label"></text>
                                        </template>
                                        
                                        <path :d="result.chart.areaPath" fill="url(#ciAreaGrad)"/>
                                        
                                        <path :d="result.chart.principalPath" fill="none" stroke="#c7d2fe" stroke-width="1.5" stroke-dasharray="4 3"/>
                                        
                                        <path :d="result.chart.linePath" fill="none" stroke="url(#ciLineGrad)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        
                                        <template x-for="pt in result.chart.points" :key="pt.x">
                                            <circle :cx="pt.x" :cy="pt.y" r="3" fill="#4f46e5" stroke="white" stroke-width="1.5"/>
                                        </template>
                                        
                                        <template x-for="xl in result.chart.xLabels" :key="xl.x">
                                            <text :x="xl.x" :y="result.chart.h - 4" text-anchor="middle" font-size="9" fill="#9ca3af" x-text="xl.label"></text>
                                        </template>
                                    </g>
                                </template>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-3">
                            <span class="inline-flex items-center gap-1">
                                <svg width="18" height="6"><line x1="0" y1="3" x2="18" y2="3" stroke="#4f46e5" stroke-width="2.5"/></svg>
                                Balance
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg width="18" height="6"><line x1="0" y1="3" x2="18" y2="3" stroke="#c7d2fe" stroke-width="1.5" stroke-dasharray="4 3"/></svg>
                                Principal
                            </span>
                        </p>
                    </div>

                    
                    <div class="card overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="text-sm font-semibold text-gray-700">Year-by-Year Breakdown</span>
                            <button type="button" @click="showTable = !showTable"
                                    class="btn btn-secondary btn-sm flex items-center gap-1">
                                <span x-text="showTable ? 'Hide' : 'Show'"></span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'-rotate-180': showTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        <div x-show="showTable" x-transition style="max-height:400px; overflow-y:auto;">
                            <table class="ci-table">
                                <thead>
                                    <tr>
                                        <th>Yr</th>
                                        <th>Opening Balance</th>
                                        <th x-show="result && result.totalContribs > 0">Contributions</th>
                                        <th>Interest Earned</th>
                                        <th>Closing Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in result ? result.yearlyData : []" :key="row.year">
                                        <tr>
                                            <td x-text="row.year"></td>
                                            <td x-text="fmtC(row.startBal)"></td>
                                            <td x-show="result && result.totalContribs > 0" x-text="fmtC(row.contributions)"></td>
                                            <td class="ci-td-interest" x-text="fmtC(row.interest)"></td>
                                            <td x-text="fmtC(row.endBal)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="card p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-sm font-medium text-gray-600">Export:</span>
                            <button type="button" @click="copySummary()"
                                    class="btn btn-secondary btn-sm"
                                    :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                    x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                            <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                            <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                        </div>
                    </div>

                </div>

                
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                        <?php $__currentLoopData = [
                            ['💰','Final Balance','See how much your investment grows over time'],
                            ['📈','Compound Growth','Interest earns interest — the snowball effect'],
                            ['🔢','APY / EAR','Effective annual yield accounting for compounding'],
                            ['📅','Year-by-Year','Full annual breakdown of growth'],
                            ['➕','Contributions','Add regular top-ups monthly, quarterly, or annually'],
                            ['⏱','Doubling Time','How long until your money doubles'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                            <p class="text-2xl mb-1.5"><?php echo e($icon); ?></p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo e($title); ?></p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($desc); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="card p-4 bg-gradient-to-r from-brand-50 to-violet-50 border-brand-100">
                        <p class="text-sm font-semibold text-brand-700 mb-1">💡 Compound Interest Formula</p>
                        <p class="text-xs text-gray-600 font-mono">A = P × (1 + r/n)^(n×t)</p>
                        <p class="text-xs text-gray-400 mt-1">P = principal · r = annual rate · n = compounds/year · t = years</p>
                    </div>
                </div>

                
                <?php if($relatedTools->count()): ?>
                <div x-show="phase==='idle'">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                           class="card-hover p-4 flex items-center gap-3 no-underline">
                            <span class="text-xl"><?php echo e($related->icon); ?></span>
                            <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ═══════════════════════════════════════════════════════════════
   COMPOUND INTEREST CALCULATOR — pure client-side Alpine.js
═══════════════════════════════════════════════════════════════ */
function ciCalc() {
    return {
        /* ── Inputs ── */
        currency:       '$',
        principal:      '10000',
        rate:           '8',
        timePeriod:     '10',
        timeUnit:       'years',
        compFreq:       '12',
        contribution:   '',
        contribFreq:    '12',
        contribTiming:  'end',

        /* ── State ── */
        phase:              'idle',
        error:              '',
        result:             null,
        showTable:          false,
        summaryCopyFlash:   false,

        /* ── Computed ── */
        get hasContrib() { return parseFloat(this.contribution) > 0; },

        /* ── Lifecycle ── */
        init() { /* ready — auto-calculate will fire on input changes */ },

        /* ── Auto-calculate (debounced from inputs) ── */
        autoCalc() {
            this.error = '';
            // Only auto-calc if we have minimum values
            const P = parseFloat(this.principal);
            const r = parseFloat(this.rate);
            const t = parseFloat(this.timePeriod);
            if (P > 0 && r >= 0 && t > 0) {
                this.calculate();
            }
        },

        /* ── Main calculate ── */
        calculate() {
            this.error = '';
            try {
                const res = this._validate();
                const self = this;
                self.phase = 'loading';
                setTimeout(function() {
                    try {
                        self.result = self._compute(res.P, res.r, res.t, res.n, res.PMT, res.pmtPerYear, res.pmtAtStart);
                        self.phase  = 'done';
                        if (window.innerWidth < 1024) {
                            const el = document.getElementById('ci-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = String(e);
                        self.phase = 'idle';
                    }
                }, 120);
            } catch(e) {
                this.error = String(e);
                this.phase = 'idle';
            }
        },

        /* ── Validate inputs ── */
        _validate() {
            const P = parseFloat(this.principal);
            if (isNaN(P) || P <= 0) throw 'Principal must be a positive number.';
            if (P > 1e15) throw 'Principal is unrealistically large.';

            const r = parseFloat(this.rate);
            if (isNaN(r) || r < 0) throw 'Interest rate must be zero or positive.';
            if (r > 1000) throw 'Interest rate seems unrealistically high (max 1000%).';

            const tp = parseFloat(this.timePeriod);
            if (isNaN(tp) || tp <= 0) throw 'Time period must be a positive number.';
            const t = this.timeUnit === 'months' ? tp / 12 : tp;
            if (t > 200) throw 'Time period cannot exceed 200 years.';

            const n = parseInt(this.compFreq, 10); // 0 = continuous
            if (isNaN(n) || n < 0) throw 'Invalid compounding frequency.';

            const PMT = parseFloat(this.contribution) || 0;
            if (PMT < 0) throw 'Contribution cannot be negative.';
            const pmtPerYear = PMT > 0 ? parseInt(this.contribFreq, 10) : 0;
            const pmtAtStart = this.contribTiming === 'start';

            return { P, r: r/100, t, n, PMT, pmtPerYear, pmtAtStart };
        },

        /* ── Core compound interest computation ── */
        _compute(P, r, t, n, PMT, pmtPerYear, pmtAtStart) {
            // Monthly simulation — convert everything to monthly steps
            const totalMonths = Math.max(1, Math.round(t * 12));

            // Monthly growth factor (exact conversion from any compounding frequency)
            let mFactor;
            if (n === 0) {
                mFactor = Math.exp(r / 12); // continuous → monthly
            } else {
                mFactor = Math.pow(1 + r / n, n / 12); // discrete → monthly equivalent
            }

            // APY / Effective Annual Rate
            const apy = n === 0 ? Math.exp(r) - 1 : Math.pow(1 + r/n, n) - 1;

            // Doubling time (exact, without contributions)
            let doublingYears;
            if (r <= 0) {
                doublingYears = Infinity;
            } else if (n === 0) {
                doublingYears = Math.LN2 / r;
            } else {
                doublingYears = Math.LN2 / (n * Math.log(1 + r/n));
            }
            const rule72 = r > 0 ? (72 / (r * 100)) : Infinity;

            // Contribution months interval
            const contribEvery = pmtPerYear > 0 ? Math.round(12 / pmtPerYear) : Infinity;

            let balance = P;
            let totalPMTAdded = 0;
            const yearlyData = [];
            let yearStartBal = P, yearInterest = 0, yearPMT = 0;

            for (let m = 1; m <= totalMonths; m++) {
                // Beginning-of-month contribution
                if (pmtAtStart && PMT > 0) {
                    const isContribMonth = contribEvery <= 1 ? true : ((m - 1) % contribEvery === 0);
                    if (isContribMonth) {
                        balance += PMT;
                        yearPMT += PMT;
                        totalPMTAdded += PMT;
                    }
                }

                // Apply monthly interest
                const prevBal = balance;
                balance *= mFactor;
                yearInterest += balance - prevBal;

                // End-of-month contribution
                if (!pmtAtStart && PMT > 0) {
                    const isContribMonth = contribEvery <= 1 ? true : (m % contribEvery === 0);
                    if (isContribMonth) {
                        balance += PMT;
                        yearPMT += PMT;
                        totalPMTAdded += PMT;
                    }
                }

                // Year boundary
                if (m % 12 === 0) {
                    yearlyData.push({
                        year: m / 12,
                        startBal: yearStartBal,
                        interest: yearInterest,
                        contributions: yearPMT,
                        endBal: balance,
                    });
                    yearStartBal = balance;
                    yearInterest = 0;
                    yearPMT = 0;
                }
            }

            // Partial last year (if time is not whole years)
            const remainMonths = totalMonths % 12;
            if (remainMonths > 0) {
                yearlyData.push({
                    year: parseFloat((totalMonths / 12).toFixed(2)),
                    startBal: yearStartBal,
                    interest: yearInterest,
                    contributions: yearPMT,
                    endBal: balance,
                });
            }

            const totalInterest   = balance - P - totalPMTAdded;
            const totalInvested   = P + totalPMTAdded;
            const principalPct    = (P / balance) * 100;
            const contribPct      = (totalPMTAdded / balance) * 100;
            const interestPct     = (totalInterest / balance) * 100;

            // Build chart data
            const chart = this._buildChart(P, yearlyData, balance);

            return {
                finalBalance: balance,
                totalInterest,
                totalContribs: totalPMTAdded,
                totalInvested,
                principal: P,
                principalPct, contribPct, interestPct,
                apy, doublingYears, rule72,
                yearlyData, chart,
            };
        },

        /* ── Build SVG chart data ── */
        _buildChart(P, yearlyData, finalBalance) {
            if (yearlyData.length < 1) return null;

            const W = 500, H = 200, PAD_X = 45, PAD_R = 15, PAD_T = 20, PAD_B = 28;
            const plotW = W - PAD_X - PAD_R, plotH = H - PAD_T - PAD_B;

            const maxBal = finalBalance;
            const minBal = 0;
            const range  = maxBal - minBal || 1;

            // Include year 0 (initial) in data
            const allPoints = [{ year: 0, bal: P }].concat(yearlyData.map(d => ({ year: d.year, bal: d.endBal })));

            const toX = (yr) => PAD_X + (yr / (yearlyData[yearlyData.length-1].year || 1)) * plotW;
            const toY = (bal) => PAD_T + (1 - (bal - minBal) / range) * plotH;

            const pts = allPoints.map(d => ({ x: toX(d.year), y: toY(d.bal) }));

            const linePath  = pts.map((p,i) => (i===0 ? 'M' : 'L') + p.x.toFixed(1) + ',' + p.y.toFixed(1)).join(' ');
            const areaPath  = linePath + ' L' + pts[pts.length-1].x.toFixed(1) + ',' + (PAD_T+plotH).toFixed(1) + ' L' + PAD_X.toFixed(1) + ',' + (PAD_T+plotH).toFixed(1) + ' Z';
            const principalPath = 'M' + PAD_X.toFixed(1)+','+toY(P).toFixed(1) + ' L' + (PAD_X+plotW).toFixed(1)+','+toY(P).toFixed(1);

            // Grid lines (4 horizontal)
            const gridLines = [];
            for (let i = 0; i <= 4; i++) {
                const val = (i / 4) * maxBal;
                const y   = toY(val);
                gridLines.push({ y: y.toFixed(1), label: this._shortNum(val) });
            }

            // X axis labels (show at most 10, evenly spaced)
            const maxYr = yearlyData[yearlyData.length-1].year;
            const labelStep = Math.ceil(maxYr / 10);
            const xLabels = [];
            for (let yr = labelStep; yr <= maxYr; yr += labelStep) {
                xLabels.push({ x: toX(yr).toFixed(1), label: 'Y' + yr });
            }

            // Only show dots for tables ≤ 20 years
            const points = yearlyData.length <= 20 ? pts.slice(1) : [];

            return { w: W, h: H, padX: PAD_X, padR: PAD_R, linePath, areaPath, principalPath, gridLines, xLabels, points };
        },

        /* ── Short number formatter for chart axis ── */
        _shortNum(n) {
            if (n >= 1e9)  return (n/1e9).toFixed(1) + 'B';
            if (n >= 1e6)  return (n/1e6).toFixed(1) + 'M';
            if (n >= 1e3)  return (n/1e3).toFixed(0) + 'K';
            return Math.round(n).toString();
        },

        /* ── Format helpers ── */
        fmt(v) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(v.toFixed(2)).toString();
        },

        fmtC(v) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            const abs = Math.abs(v);
            let s;
            if (abs >= 1e9)       s = (v/1e9).toFixed(2) + 'B';
            else if (abs >= 1e6)  s = (v/1e6).toFixed(2) + 'M';
            else {
                s = v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
            return this.currency + s;
        },

        fmtPct(v) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(v.toFixed(2)) + '%';
        },

        fmtYrs() {
            const tp = parseFloat(this.timePeriod);
            if (isNaN(tp)) return '';
            const t = this.timeUnit === 'months' ? tp/12 : tp;
            const yrs = Math.floor(t);
            const mos = Math.round((t - yrs) * 12);
            if (yrs === 0) return mos + ' month' + (mos!==1?'s':'');
            if (mos === 0) return yrs + ' year' + (yrs!==1?'s':'');
            return yrs + 'y ' + mos + 'm';
        },

        compFreqLabel() {
            const map = {'365':'daily','52':'weekly','26':'bi-weekly','12':'monthly','4':'quarterly','2':'semi-annually','1':'annually','0':'continuously'};
            return map[this.compFreq] || this.compFreq + '×/yr';
        },

        /* ── Sample data ── */
        loadSample() {
            this.currency      = '$';
            this.principal     = '10000';
            this.rate          = '8';
            this.timePeriod    = '20';
            this.timeUnit      = 'years';
            this.compFreq      = '12';
            this.contribution  = '200';
            this.contribFreq   = '12';
            this.contribTiming = 'end';
            this.error         = '';
            this.result        = null;
            this.phase         = 'idle';
            this.$nextTick(() => this.calculate());
        },

        clearAll() {
            this.principal     = '';
            this.rate          = '';
            this.timePeriod    = '';
            this.contribution  = '';
            this.error         = '';
            this.result        = null;
            this.phase         = 'idle';
            this.showTable     = false;
        },

        /* ── Build text summary ── */
        _buildSummary() {
            if (!this.result) return '';
            const r = this.result;
            const sep = '══════════════════════════';
            return [
                'Compound Interest Calculator Results',
                sep,
                'Principal        : ' + this.fmtC(r.principal),
                'Annual Rate      : ' + this.rate + '%',
                'Time Period      : ' + this.fmtYrs(),
                'Compounding      : ' + this.compFreqLabel(),
                this.hasContrib ? 'Contribution     : ' + this.fmtC(parseFloat(this.contribution)) + ' / ' + this.contribFreqLabel() : '',
                '',
                '── Results ──',
                'Final Balance    : ' + this.fmtC(r.finalBalance),
                'Total Interest   : ' + this.fmtC(r.totalInterest),
                'Total Invested   : ' + this.fmtC(r.totalInvested),
                'APY / EAR        : ' + this.fmtPct(r.apy * 100),
                'Doubling Time    : ' + this.fmt(r.doublingYears) + ' years',
                '',
                '── Year-by-Year ──',
                'Year | Opening Balance | Contributions | Interest | Closing Balance',
            ].concat(r.yearlyData.map(function(d) {
                return [d.year, d.startBal.toFixed(2), d.contributions.toFixed(2), d.interest.toFixed(2), d.endBal.toFixed(2)].join(' | ');
            })).filter(function(l){ return l !== ''; }).join('\n');
        },

        contribFreqLabel() {
            const map = {'12':'monthly','4':'quarterly','2':'semi-annually','1':'annually'};
            return map[this.contribFreq] || '';
        },

        async copySummary() {
            const text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                const ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            const self = this; this.summaryCopyFlash = true;
            setTimeout(function(){ self.summaryCopyFlash = false; }, 1800);
        },

        downloadSummary() {
            const text = this._buildSummary(); if (!text) return;
            const blob = new Blob([text], {type:'text/plain;charset=utf-8'});
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href = url; a.download = 'compound-interest-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\compound-interest-calculator.blade.php ENDPATH**/ ?>