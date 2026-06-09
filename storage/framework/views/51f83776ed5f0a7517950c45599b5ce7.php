<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Savings Calculator  —  prefix: sv-
══════════════════════════════════════════════ */

/* ── Mode tabs ── */
.sv-tab {
    padding: .5rem 1rem; border-bottom: 2.5px solid transparent;
    font-size: .8rem; font-weight: 600; color: #64748b;
    background: transparent; border-top: none; border-left: none; border-right: none;
    cursor: pointer; transition: color .12s, border-color .12s; white-space: nowrap;
}
.sv-tab:hover { color: #4f46e5; }
.sv-tab-active { color: #4f46e5; border-bottom-color: #4f46e5; }

/* ── Big hero number ── */
.sv-hero-amount {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 900; line-height: 1; letter-spacing: -.02em;
    background: linear-gradient(135deg, #059669 0%, #10b981 60%, #34d399 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    word-break: break-all;
}

/* ── Stat cards ── */
.sv-stat {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1.1rem 1rem; display: flex; flex-direction: column; align-items: center;
    gap: .35rem; text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.sv-stat:hover { border-color: #6ee7b7; box-shadow: 0 4px 16px rgba(16,185,129,.08); transform: translateY(-1px); }
.sv-stat-lbl { font-size: .65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }
.sv-stat-val {
    font-size: 1.5rem; font-weight: 800;
    background: linear-gradient(135deg, #059669, #10b981);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    word-break: break-all;
}
.sv-stat-val.brand  { background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; background-clip:text; }
.sv-stat-val.amber  { background: linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; background-clip:text; }
.sv-stat-val.cyan   { background: linear-gradient(135deg,#0891b2,#06b6d4); -webkit-background-clip:text; background-clip:text; }
.sv-stat-sub { font-size: .68rem; color: #94a3b8; }

/* ── Breakdown bar ── */
.sv-bar-track { height: 18px; border-radius: 9999px; overflow: hidden; display: flex; background: #f1f5f9; }
.sv-bar-initial  { background: #4f46e5; }
.sv-bar-contrib  { background: #6366f1; }
.sv-bar-interest { background: #10b981; }
.sv-legend-dot   { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* ── Milestone timeline ── */
.sv-milestone {
    display: flex; align-items: center; gap: .75rem;
    padding: .5rem .75rem; border-radius: .6rem;
    background: #f0fdf4; border: 1px solid #a7f3d0;
    transition: background .15s;
}
.sv-milestone:hover { background: #dcfce7; }
.sv-milestone-badge {
    font-size: .7rem; font-weight: 800; padding: .2rem .55rem;
    border-radius: 9999px; background: #d1fae5; color: #065f46; white-space: nowrap; flex-shrink: 0;
}
.sv-milestone-time { font-size: .72rem; color: #6b7280; }
.sv-milestone-check { color: #10b981; font-size: 1rem; flex-shrink: 0; }

/* ── What-If comparison ── */
.sv-whatif-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .5rem; }
.sv-whatif-card {
    padding: .75rem; border-radius: .75rem; border: 1.5px solid #e2e8f0; background: #fff;
    text-align: center; cursor: pointer; transition: all .15s;
}
.sv-whatif-card:hover { border-color: #a5b4fc; background: #f5f3ff; }
.sv-whatif-card.current { border-color: #059669; background: #f0fdf4; }
.sv-whatif-amount { font-size: 1rem; font-weight: 800; color: #059669; }
.sv-whatif-label  { font-size: .65rem; color: #6b7280; margin-top: .15rem; }

/* ── Goal result hero ── */
.sv-goal-hero {
    background: linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);
    border-radius: 1rem; padding: 1.5rem; text-align: center;
}
.sv-goal-amount {
    font-size: 2.4rem; font-weight: 900; line-height: 1;
    color: #065f46;
}
.sv-goal-time {
    font-size: 1.8rem; font-weight: 900; line-height: 1;
    color: #0f766e;
}

/* ── Progress toward goal ── */
.sv-goal-bar-wrap { height: 12px; border-radius: 9999px; background: #f1f5f9; overflow: hidden; }
.sv-goal-bar-fill { height: 100%; border-radius: 9999px; background: linear-gradient(90deg,#4f46e5,#10b981); transition: width .5s ease; }

/* ── Table ── */
.sv-table { width: 100%; border-collapse: collapse; font-size: .78rem; }
.sv-table th { text-align: right; padding: .5rem .75rem; font-size: .65rem; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .07em; border-bottom: 1.5px solid #e2e8f0; background: #f8fafc; }
.sv-table th:first-child { text-align: center; }
.sv-table td { text-align: right; padding: .5rem .75rem; border-bottom: 1px solid #f1f5f9; color: #374151; font-variant-numeric: tabular-nums; }
.sv-table td:first-child { text-align: center; font-weight: 600; color: #059669; }
.sv-table tr:last-child td { border-bottom: none; font-weight: 700; }
.sv-table tr:hover td { background: #f0fdf4; }
.sv-table .sv-td-int { color: #059669; font-weight: 600; }

/* ── Input prefix/suffix ── */
.sv-prefix-wrap { display: flex; align-items: stretch; }
.sv-prefix {
    display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-right: none;
    border-radius: .75rem 0 0 .75rem;
    font-size: .9rem; font-weight: 700; color: #374151; white-space: nowrap;
}
.sv-prefix-wrap .form-input { border-radius: 0 .75rem .75rem 0 !important; flex: 1; min-width: 0; }
.sv-suffix-wrap { display: flex; align-items: stretch; }
.sv-suffix-wrap .form-input { border-radius: .75rem 0 0 .75rem !important; flex: 1; border-right: none; min-width: 0; }
.sv-suffix { display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-left: none;
    border-radius: 0 .75rem .75rem 0; font-size: .82rem; font-weight: 600; color: #64748b; }

/* ── Toggle group ── */
.sv-toggle-group { display: flex; background: #f1f5f9; border-radius: .6rem; padding: .2rem; gap: .15rem; }
.sv-toggle-btn { flex: 1; padding: .3rem .65rem; border-radius: .45rem;
    font-size: .75rem; font-weight: 600; color: #64748b;
    cursor: pointer; transition: all .15s; border: none; background: none; }
.sv-toggle-btn.active { background: white; color: #4f46e5; box-shadow: 0 1px 4px rgba(0,0,0,.1); }

/* ── Section divider ── */
.sv-div { display: flex; align-items: center; gap: .6rem;
    color: #94a3b8; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; }
.sv-div::before,.sv-div::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

/* ── Chart wrap ── */
.sv-chart-wrap { overflow: hidden; border-radius: .75rem; background: #f8fffe; border: 1px solid #d1fae5; }

/* ── Shimmer ── */
@keyframes svShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.sv-shim { height: 5.5rem; border-radius: 1.125rem;
    background: linear-gradient(90deg,#f0fdf4 25%,#dcfce7 50%,#f0fdf4 75%);
    background-size: 1200px 100%; animation: svShim 1.4s infinite; }

/* ── Entrance ── */
@keyframes svIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.sv-in { animation: svIn .3s ease-out; }

/* ── Insight ── */
.sv-insight { display: flex; align-items: center; gap: .75rem; padding: .65rem .9rem;
    background: #f0fdf4; border-radius: .75rem; border: 1px solid #a7f3d0; }
.sv-insight-val { font-weight: 800; color: #059669; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="svCalc()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Plan your savings journey — calculate your <strong>future balance</strong>, track <strong>milestones</strong>, find out <strong>how long to reach a goal</strong>, or discover <strong>how much to save each month</strong>.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-primary">Goal Planner</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2">
                <div class="card">
                    
                    <div class="flex border-b border-gray-100 overflow-x-auto">
                        <button type="button" class="sv-tab" :class="{'sv-tab-active': mode==='build'}" @click="switchMode('build')">📈 Build Savings</button>
                        <button type="button" class="sv-tab" :class="{'sv-tab-active': mode==='goal_time'}" @click="switchMode('goal_time')">⏱ Time to Goal</button>
                        <button type="button" class="sv-tab" :class="{'sv-tab-active': mode==='goal_pmt'}" @click="switchMode('goal_pmt')">🎯 Monthly Target</button>
                    </div>

                    <div class="p-5 space-y-4">

                        
                        <div x-show="mode==='build'" class="space-y-4">

                            
                            <div>
                                <label class="form-label">Currency &amp; Initial Deposit</label>
                                <div class="flex gap-2">
                                    <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$ USD</option><option value="€">€ EUR</option>
                                        <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                        <option value="₹">₹ INR</option><option value="₦">₦ NGN</option>
                                        <option value="A$">A$</option><option value="C$">C$</option>
                                    </select>
                                    <div class="sv-prefix-wrap flex-1">
                                        <span class="sv-prefix" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="initialDeposit"
                                               @input.debounce.400ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 1000">
                                    </div>
                                </div>
                                <p class="form-help">Starting amount (can be 0).</p>
                            </div>

                            
                            <div>
                                <label class="form-label">Regular Contribution</label>
                                <div class="flex gap-2">
                                    <div class="sv-prefix-wrap flex-1">
                                        <span class="sv-prefix" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="monthlyContrib"
                                               @input.debounce.400ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 500">
                                    </div>
                                    <select x-model="contribFreq" @change="autoCalc()" class="form-input w-36 py-2 text-sm shrink-0">
                                        <option value="12">Monthly</option>
                                        <option value="26">Bi-Weekly</option>
                                        <option value="52">Weekly</option>
                                        <option value="4">Quarterly</option>
                                        <option value="2">Semi-Annually</option>
                                        <option value="1">Annually</option>
                                    </select>
                                </div>
                                <p class="form-help">Amount added each period.</p>
                            </div>

                            
                            <div>
                                <label class="form-label">Contribution Timing</label>
                                <div class="sv-toggle-group">
                                    <button type="button" class="sv-toggle-btn" :class="{active: contribTiming==='end'}"
                                            @click="contribTiming='end'; autoCalc()">End of Period</button>
                                    <button type="button" class="sv-toggle-btn" :class="{active: contribTiming==='start'}"
                                            @click="contribTiming='start'; autoCalc()">Beginning</button>
                                </div>
                            </div>

                            
                            <div>
                                <label class="form-label">Annual Interest Rate</label>
                                <div class="sv-suffix-wrap">
                                    <input type="number" step="any" min="0" max="1000" x-model="annualRate"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 6">
                                    <span class="sv-suffix">% / year</span>
                                </div>
                            </div>

                            
                            <div>
                                <label class="form-label">Savings Duration</label>
                                <div class="flex gap-2">
                                    <input type="number" step="any" min="0" x-model="duration"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input flex-1" placeholder="e.g. 20">
                                    <div class="sv-toggle-group" style="min-width:120px">
                                        <button type="button" class="sv-toggle-btn" :class="{active: durationUnit==='years'}"
                                                @click="durationUnit='years'; autoCalc()">Years</button>
                                        <button type="button" class="sv-toggle-btn" :class="{active: durationUnit==='months'}"
                                                @click="durationUnit='months'; autoCalc()">Months</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div>
                                <label class="form-label">Compounding Frequency</label>
                                <select x-model="compFreq" @change="autoCalc()" class="form-input text-sm">
                                    <option value="365">Daily (365×)</option>
                                    <option value="12" selected>Monthly (12×)</option>
                                    <option value="4">Quarterly (4×)</option>
                                    <option value="2">Semi-Annually (2×)</option>
                                    <option value="1">Annually (1×)</option>
                                    <option value="0">Continuously</option>
                                </select>
                            </div>

                        </div>

                        
                        <div x-show="mode==='goal_time'" class="space-y-4">

                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800">
                                💡 Enter your savings goal and we'll calculate how long it will take.
                            </div>

                            <div>
                                <label class="form-label">Savings Goal</label>
                                <div class="flex gap-2">
                                    <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$ USD</option><option value="€">€ EUR</option>
                                        <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                        <option value="₹">₹ INR</option><option value="₦">₦ NGN</option>
                                    </select>
                                    <div class="sv-prefix-wrap flex-1">
                                        <span class="sv-prefix" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="goalAmount"
                                               @input.debounce.400ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 50000">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Initial Deposit</label>
                                <div class="sv-prefix-wrap">
                                    <span class="sv-prefix" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="goalInitial"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 1000">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Monthly Contribution</label>
                                <div class="sv-prefix-wrap">
                                    <span class="sv-prefix" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="goalMonthly"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 500">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Annual Interest Rate</label>
                                <div class="sv-suffix-wrap">
                                    <input type="number" step="any" min="0" x-model="goalRate"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 6">
                                    <span class="sv-suffix">% / year</span>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Compounding</label>
                                <select x-model="goalCompFreq" @change="autoCalc()" class="form-input text-sm">
                                    <option value="365">Daily</option>
                                    <option value="12" selected>Monthly</option>
                                    <option value="4">Quarterly</option>
                                    <option value="1">Annually</option>
                                    <option value="0">Continuously</option>
                                </select>
                            </div>

                        </div>

                        
                        <div x-show="mode==='goal_pmt'" class="space-y-4">

                            <div class="p-3 bg-brand-50 border border-brand-200 rounded-xl text-xs text-brand-800">
                                💡 Enter your goal and timeframe — we'll calculate the monthly savings needed.
                            </div>

                            <div>
                                <label class="form-label">Savings Goal</label>
                                <div class="flex gap-2">
                                    <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$ USD</option><option value="€">€ EUR</option>
                                        <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                        <option value="₹">₹ INR</option><option value="₦">₦ NGN</option>
                                    </select>
                                    <div class="sv-prefix-wrap flex-1">
                                        <span class="sv-prefix" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="pmtGoalAmount"
                                               @input.debounce.400ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 100000">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Initial Deposit</label>
                                <div class="sv-prefix-wrap">
                                    <span class="sv-prefix" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="pmtInitial"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 0">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Time Period</label>
                                <div class="flex gap-2">
                                    <input type="number" step="any" min="0" x-model="pmtYears"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input flex-1" placeholder="e.g. 10">
                                    <div class="sv-toggle-group" style="min-width:120px">
                                        <button type="button" class="sv-toggle-btn" :class="{active: pmtTimeUnit==='years'}"
                                                @click="pmtTimeUnit='years'; autoCalc()">Years</button>
                                        <button type="button" class="sv-toggle-btn" :class="{active: pmtTimeUnit==='months'}"
                                                @click="pmtTimeUnit='months'; autoCalc()">Months</button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Annual Interest Rate</label>
                                <div class="sv-suffix-wrap">
                                    <input type="number" step="any" min="0" x-model="pmtRate"
                                           @input.debounce.400ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 6">
                                    <span class="sv-suffix">% / year</span>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Compounding</label>
                                <select x-model="pmtCompFreq" @change="autoCalc()" class="form-input text-sm">
                                    <option value="365">Daily</option>
                                    <option value="12" selected>Monthly</option>
                                    <option value="4">Quarterly</option>
                                    <option value="1">Annually</option>
                                    <option value="0">Continuously</option>
                                </select>
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

            
            <div class="lg:col-span-3 space-y-4" id="sv-results">

                
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="sv-shim"></div></template>
                </div>

                
                <div x-show="phase==='done' && result && mode==='build'"
                     class="space-y-4 sv-in"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     id="sv-results-inner">

                    
                    <div class="card overflow-hidden">
                        <div style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);" class="px-6 py-5">
                            <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Future Savings Balance</p>
                            <p class="sv-hero-amount" x-text="result ? fmtC(result.finalBalance) : '—'"></p>
                            <p class="text-xs text-gray-500 mt-1.5">
                                after <strong x-text="result ? fmtDuration(result.totalMonths) : ''"></strong>
                                · compounded <strong x-text="compFreqLabel(compFreq)"></strong>
                            </p>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Initial Deposit</div>
                            <div class="sv-stat-val brand" x-text="result ? fmtC(result.principal) : '—'"></div>
                            <div class="sv-stat-sub">starting amount</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Total Contributions</div>
                            <div class="sv-stat-val brand" x-text="result ? fmtC(result.totalContribs) : '—'"></div>
                            <div class="sv-stat-sub">added over time</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Interest Earned</div>
                            <div class="sv-stat-val" x-text="result ? fmtC(result.totalInterest) : '—'"></div>
                            <div class="sv-stat-sub" x-text="result ? fmtPct(result.interestPct) + ' of total' : ''"></div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Eff. Annual Yield</div>
                            <div class="sv-stat-val amber" x-text="result ? fmtPct(result.apy * 100) : '—'"></div>
                            <div class="sv-stat-sub">APY</div>
                        </div>
                    </div>

                    
                    <div class="card p-4 space-y-3">
                        <p class="text-sm font-semibold text-gray-700">Balance Composition</p>
                        <div class="sv-bar-track">
                            <div class="sv-bar-initial transition-all duration-500"  :style="'width:' + (result ? result.initialPct  : 0) + '%'"></div>
                            <div class="sv-bar-contrib transition-all duration-500"  :style="'width:' + (result ? result.contribPct  : 0) + '%'"></div>
                            <div class="sv-bar-interest transition-all duration-500" :style="'width:' + (result ? result.interestPct : 0) + '%'"></div>
                        </div>
                        <div class="flex flex-wrap gap-4 text-xs">
                            <span class="flex items-center gap-1.5">
                                <span class="sv-legend-dot" style="background:#4f46e5"></span>
                                <span class="text-gray-600">Initial <strong x-text="result ? fmtC(result.principal) : ''"></strong> (<span x-text="result ? fmtPct(result.initialPct) : ''"></span>)</span>
                            </span>
                            <span class="flex items-center gap-1.5" x-show="result && result.totalContribs > 0">
                                <span class="sv-legend-dot" style="background:#6366f1"></span>
                                <span class="text-gray-600">Contributions <strong x-text="result ? fmtC(result.totalContribs) : ''"></strong> (<span x-text="result ? fmtPct(result.contribPct) : ''"></span>)</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="sv-legend-dot" style="background:#10b981"></span>
                                <span class="text-gray-600">Interest <strong x-text="result ? fmtC(result.totalInterest) : ''"></strong> (<span x-text="result ? fmtPct(result.interestPct) : ''"></span>)</span>
                            </span>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sv-insight">
                            <span style="font-size:1.25rem">⏱</span>
                            <div class="text-sm">
                                <p class="text-gray-500 text-xs">Money doubles in</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="sv-insight-val" x-text="result && result.doublingYears !== Infinity ? fmt(result.doublingYears) + ' years' : 'Never (0% rate)'"></span>
                                </p>
                            </div>
                        </div>
                        <div class="sv-insight">
                            <span style="font-size:1.25rem">📊</span>
                            <div class="text-sm">
                                <p class="text-gray-500 text-xs">You invested / Interest generated</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="sv-insight-val" x-text="result ? fmtC(result.totalInvested) : ''"></span>
                                    <span class="text-gray-400 text-xs"> / </span>
                                    <span class="sv-insight-val" x-text="result ? fmtC(result.totalInterest) : ''"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card p-4" x-show="result && result.milestones.length > 0">
                        <p class="text-sm font-semibold text-gray-700 mb-3">🏁 Savings Milestones</p>
                        <div class="space-y-2">
                            <template x-for="m in result ? result.milestones : []" :key="m.amount">
                                <div class="sv-milestone">
                                    <span class="sv-milestone-check">✓</span>
                                    <span class="sv-milestone-badge" x-text="fmtC(m.amount)"></span>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-700" x-text="fmtDuration(m.month)"></p>
                                        <p class="sv-milestone-time" x-text="'Month ' + m.month"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    
                    <div class="card p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Savings Growth Chart</p>
                        <div class="sv-chart-wrap">
                            <svg x-show="result && result.chart"
                                 :viewBox="result && result.chart ? '0 0 ' + result.chart.w + ' ' + result.chart.h : '0 0 500 200'"
                                 class="w-full" style="min-height:150px;display:block;">
                                <defs>
                                    <linearGradient id="svAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.02"/>
                                    </linearGradient>
                                    <linearGradient id="svLineGrad" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#059669"/>
                                        <stop offset="100%" stop-color="#10b981"/>
                                    </linearGradient>
                                </defs>
                                <template x-if="result && result.chart">
                                    <g>
                                        <template x-for="gl in result.chart.gridLines" :key="gl.y">
                                            <line :x1="result.chart.padX" :y1="gl.y" :x2="result.chart.w - result.chart.padR" :y2="gl.y" stroke="#d1fae5" stroke-width="1"/>
                                        </template>
                                        <template x-for="gl in result.chart.gridLines" :key="'lbl'+gl.y">
                                            <text :x="result.chart.padX - 6" :y="gl.y + 4" text-anchor="end" font-size="9" fill="#9ca3af" x-text="gl.label"></text>
                                        </template>
                                        
                                        <path :d="result.chart.contribAreaPath" fill="rgba(99,102,241,0.1)"/>
                                        
                                        <path :d="result.chart.contribLinePath" fill="none" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="5 3"/>
                                        
                                        <path :d="result.chart.areaPath" fill="url(#svAreaGrad)"/>
                                        
                                        <path :d="result.chart.linePath" fill="none" stroke="url(#svLineGrad)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        
                                        <template x-for="pt in result.chart.points" :key="pt.x">
                                            <circle :cx="pt.x" :cy="pt.y" r="3" fill="#059669" stroke="white" stroke-width="1.5"/>
                                        </template>
                                        <template x-for="xl in result.chart.xLabels" :key="xl.x">
                                            <text :x="xl.x" :y="result.chart.h - 4" text-anchor="middle" font-size="9" fill="#9ca3af" x-text="xl.label"></text>
                                        </template>
                                    </g>
                                </template>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-4">
                            <span class="inline-flex items-center gap-1">
                                <svg width="18" height="6"><line x1="0" y1="3" x2="18" y2="3" stroke="#059669" stroke-width="2.5"/></svg>
                                Balance
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg width="18" height="6"><line x1="0" y1="3" x2="18" y2="3" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="5 3"/></svg>
                                Contributions Only
                            </span>
                        </p>
                    </div>

                    
                    <div class="card p-4" x-show="result && parseFloat(monthlyContrib) > 0">
                        <p class="text-sm font-semibold text-gray-700 mb-3">💡 What If You Save More?</p>
                        <div class="sv-whatif-row">
                            <template x-for="(wi,i) in result ? result.whatIf : []" :key="i">
                                <div class="sv-whatif-card" :class="{'current': wi.isCurrent}">
                                    <div class="sv-whatif-amount" x-text="fmtC(wi.finalBalance)"></div>
                                    <div class="sv-whatif-label">
                                        <span x-text="fmtC(wi.pmt)"></span>/mo
                                        <template x-if="wi.isCurrent"><span class="text-brand-600"> ← current</span></template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">Same duration and rate, different contribution amounts</p>
                    </div>

                    
                    <div class="card overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="text-sm font-semibold text-gray-700">Year-by-Year Breakdown</span>
                            <button type="button" @click="showTable=!showTable" class="btn btn-secondary btn-sm flex items-center gap-1">
                                <span x-text="showTable ? 'Hide' : 'Show'"></span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'-rotate-180': showTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        <div x-show="showTable" x-transition style="max-height:380px;overflow-y:auto;">
                            <table class="sv-table">
                                <thead>
                                    <tr>
                                        <th>Yr</th>
                                        <th>Opening</th>
                                        <th>Contributions</th>
                                        <th>Interest</th>
                                        <th>Closing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in result ? result.yearlyData : []" :key="row.year">
                                        <tr>
                                            <td x-text="row.year"></td>
                                            <td x-text="fmtC(row.startBal)"></td>
                                            <td x-text="fmtC(row.contributions)"></td>
                                            <td class="sv-td-int" x-text="fmtC(row.interest)"></td>
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
                            <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm"
                                    :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                    x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                            <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                            <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                        </div>
                    </div>

                </div>

                
                <div x-show="phase==='done' && result && mode==='goal_time'"
                     class="space-y-4 sv-in"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <div class="card overflow-hidden">
                        <div class="sv-goal-hero">
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Time to Reach Goal</p>
                            <p class="sv-goal-time" x-text="result ? fmtDuration(result.monthsNeeded) : '—'"></p>
                            <p class="text-sm text-emerald-700 mt-1" x-text="result ? '(' + result.monthsNeeded + ' months)' : ''"></p>
                        </div>
                    </div>

                    
                    <div class="card p-4 space-y-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Start: <strong x-text="result ? fmtC(parseFloat(goalInitial)||0) : ''"></strong></span>
                            <span>Goal: <strong class="text-emerald-600" x-text="result ? fmtC(parseFloat(goalAmount)) : ''"></strong></span>
                        </div>
                        <div class="sv-goal-bar-wrap">
                            <div class="sv-goal-bar-fill" style="width:100%"></div>
                        </div>
                        <p class="text-xs text-center text-gray-400">Goal reached in <strong class="text-emerald-600" x-text="result ? fmtDuration(result.monthsNeeded) : ''"></strong></p>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Goal Amount</div>
                            <div class="sv-stat-val" x-text="result ? fmtC(parseFloat(goalAmount)) : '—'"></div>
                            <div class="sv-stat-sub">target</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Total Invested</div>
                            <div class="sv-stat-val brand" x-text="result ? fmtC(result.totalInvested) : '—'"></div>
                            <div class="sv-stat-sub">principal + contributions</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Interest Earned</div>
                            <div class="sv-stat-val" x-text="result ? fmtC(result.totalInterest) : '—'"></div>
                            <div class="sv-stat-sub">when goal reached</div>
                        </div>
                    </div>

                    <div class="card p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm"
                                    :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                    x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                            <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                        </div>
                    </div>

                </div>

                
                <div x-show="phase==='done' && result && mode==='goal_pmt'"
                     class="space-y-4 sv-in"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <div class="card overflow-hidden">
                        <div style="background:linear-gradient(135deg,#f5f3ff 0%,#ede9fe 100%);" class="px-6 py-5 text-center">
                            <p class="text-xs font-bold text-brand-400 uppercase tracking-widest mb-1">Required Monthly Savings</p>
                            <p style="font-size:clamp(2rem,5vw,3rem);font-weight:900;line-height:1;background:linear-gradient(135deg,#4f46e5,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"
                               x-text="result ? fmtC(result.requiredPMT) : '—'"></p>
                            <p class="text-xs text-gray-500 mt-1.5">per month for <strong x-text="result ? fmtDuration(result.totalMonths) : ''"></strong></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Savings Goal</div>
                            <div class="sv-stat-val" x-text="result ? fmtC(parseFloat(pmtGoalAmount)) : '—'"></div>
                            <div class="sv-stat-sub">target amount</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Total You Save</div>
                            <div class="sv-stat-val brand" x-text="result ? fmtC(result.totalInvested) : '—'"></div>
                            <div class="sv-stat-sub">initial + contributions</div>
                        </div>
                        <div class="sv-stat">
                            <div class="sv-stat-lbl">Interest Does</div>
                            <div class="sv-stat-val amber" x-text="result ? fmtC(result.interestContrib) : '—'"></div>
                            <div class="sv-stat-sub">interest contribution</div>
                        </div>
                    </div>

                    
                    <div class="card p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">📅 What if you save more per month?</p>
                        <div class="space-y-2">
                            <template x-for="(alt,i) in result ? result.altPlans : []" :key="i">
                                <div class="flex items-center justify-between p-2.5 rounded-lg border"
                                     :class="alt.isCurrent ? 'border-brand-300 bg-brand-50' : 'border-gray-200 bg-gray-50'">
                                    <div>
                                        <span class="text-sm font-bold text-gray-800" x-text="fmtC(alt.pmt) + '/mo'"></span>
                                        <span x-show="alt.isCurrent" class="ml-2 badge badge-primary text-xs">current target</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Reach goal in</p>
                                        <p class="text-sm font-bold text-emerald-600" x-text="fmtDuration(alt.months)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="card p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm"
                                    :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                    x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                            <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                        </div>
                    </div>

                </div>

                
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        @foreach([
                            ['🏦','Build Savings','Enter a contribution amount and see your future balance grow'],
                            ['🎯','Goal Planner','Set a target amount and find out how long it takes to reach it'],
                            ['📅','Monthly Target','Tell us your goal and timeframe — get the exact monthly savings needed'],
                            ['🏁','Milestones','See when you'll hit $10K, $50K, $100K and beyond'],
                            ['📊','Growth Chart','Visual line chart showing your savings journey over time'],
                            ['💡','What-If','Compare different contribution amounts side by side'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-emerald-200 transition-colors">
                            <p class="text-2xl mb-1.5"><?php echo e($icon); ?></p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo e($title); ?></p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($desc); ?></p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-emerald-100">
                        <p class="text-sm font-semibold text-emerald-700 mb-1">💡 Future Value Formula</p>
                        <p class="text-xs text-gray-600 font-mono">FV = P(1+r/n)^(nt) + PMT × [(1+r/n)^(nt) − 1] / (r/n)</p>
                        <p class="text-xs text-gray-400 mt-1">P = initial deposit · PMT = contribution · r = rate · n = compounds/year · t = years</p>
                    </div>
                </div>

                
                @if($relatedTools->count())
                <div x-show="phase==='idle'">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($relatedTools as $related)
                        <a href="<?php echo e(route('tools.show', $related->slug)); ?>" class="card-hover p-4 flex items-center gap-3 no-underline">
                            <span class="text-xl"><?php echo e($related->icon); ?></span>
                            <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
                        </a>
                        @endforeach
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
   SAVINGS CALCULATOR — pure client-side Alpine.js component
═══════════════════════════════════════════════════════════════ */
function svCalc() {
    return {
        /* ── Mode ── */
        mode: 'build',

        /* ── Build Savings inputs ── */
        currency: '$',
        initialDeposit: '1000',
        monthlyContrib: '500',
        contribFreq: '12',
        contribTiming: 'end',
        annualRate: '7',
        duration: '20',
        durationUnit: 'years',
        compFreq: '12',

        /* ── Goal: Time mode ── */
        goalAmount: '100000',
        goalInitial: '1000',
        goalMonthly: '500',
        goalRate: '7',
        goalCompFreq: '12',

        /* ── Goal: PMT mode ── */
        pmtGoalAmount: '100000',
        pmtInitial: '0',
        pmtYears: '10',
        pmtTimeUnit: 'years',
        pmtRate: '7',
        pmtCompFreq: '12',

        /* ── State ── */
        phase: 'idle',
        error: '',
        result: null,
        showTable: false,
        summaryCopyFlash: false,

        /* ── Lifecycle ── */
        init() {},

        switchMode(m) {
            this.mode = m;
            this.error = '';
            this.result = null;
            this.phase = 'idle';
        },

        autoCalc() {
            this.error = '';
            var self = this;
            // Try silently — only show result if inputs are sufficient
            try {
                if (this.mode === 'build') {
                    var P = parseFloat(this.initialDeposit) || 0;
                    var r = parseFloat(this.annualRate);
                    var d = parseFloat(this.duration);
                    if (r >= 0 && d > 0) self.calculate();
                } else if (this.mode === 'goal_time') {
                    var goal = parseFloat(this.goalAmount);
                    var P2   = parseFloat(this.goalInitial) || 0;
                    var pmt  = parseFloat(this.goalMonthly) || 0;
                    var r2   = parseFloat(this.goalRate);
                    if (goal > 0 && r2 >= 0) self.calculate();
                } else if (this.mode === 'goal_pmt') {
                    var goal3  = parseFloat(this.pmtGoalAmount);
                    var years3 = parseFloat(this.pmtYears);
                    var r3     = parseFloat(this.pmtRate);
                    if (goal3 > 0 && years3 > 0 && r3 >= 0) self.calculate();
                }
            } catch(e) {}
        },

        calculate() {
            this.error = '';
            var self = this;
            try {
                var validated = this._validate();
                self.phase = 'loading';
                setTimeout(function() {
                    try {
                        if (self.mode === 'build') {
                            self.result = self._computeBuild(validated);
                        } else if (self.mode === 'goal_time') {
                            self.result = self._computeGoalTime(validated);
                        } else {
                            self.result = self._computeGoalPMT(validated);
                        }
                        self.phase = 'done';
                        if (window.innerWidth < 1024) {
                            var el = document.getElementById('sv-results');
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

        /* ── Validate ── */
        _validate() {
            var v = {};
            if (this.mode === 'build') {
                v.P   = parseFloat(this.initialDeposit) || 0;
                v.PMT = parseFloat(this.monthlyContrib) || 0;
                v.r   = parseFloat(this.annualRate);
                v.d   = parseFloat(this.duration);
                v.n   = parseInt(this.compFreq, 10);
                v.pmtPerYear = parseInt(this.contribFreq, 10);
                v.pmtAtStart = this.contribTiming === 'start';
                if (isNaN(v.r) || v.r < 0) throw 'Interest rate must be zero or a positive number.';
                if (v.r > 1000)            throw 'Interest rate seems too high (max 1000%).';
                if (isNaN(v.d) || v.d <= 0) throw 'Duration must be a positive number.';
                v.months = Math.max(1, Math.round((this.durationUnit === 'months' ? v.d : v.d * 12)));
                if (v.months > 2400)       throw 'Duration cannot exceed 200 years.';
                if (v.P < 0)               throw 'Initial deposit cannot be negative.';
                if (v.PMT < 0)             throw 'Contribution cannot be negative.';
                if (v.P === 0 && v.PMT === 0) throw 'Enter an initial deposit or a regular contribution (or both).';
                v.r_dec = v.r / 100;

            } else if (this.mode === 'goal_time') {
                v.goal = parseFloat(this.goalAmount);
                v.P    = parseFloat(this.goalInitial) || 0;
                v.PMT  = parseFloat(this.goalMonthly) || 0;
                v.r    = parseFloat(this.goalRate);
                v.n    = parseInt(this.goalCompFreq, 10);
                if (isNaN(v.goal) || v.goal <= 0) throw 'Savings goal must be a positive number.';
                if (isNaN(v.r) || v.r < 0)       throw 'Interest rate must be zero or positive.';
                if (v.P < 0)                      throw 'Initial deposit cannot be negative.';
                if (v.PMT < 0)                    throw 'Monthly contribution cannot be negative.';
                if (v.PMT === 0 && v.r === 0 && v.P < v.goal) throw 'With no contribution and 0% rate, the goal is unreachable.';
                if (v.P >= v.goal)                throw 'Initial deposit already meets or exceeds the goal!';
                v.r_dec = v.r / 100;

            } else { // goal_pmt
                v.goal  = parseFloat(this.pmtGoalAmount);
                v.P     = parseFloat(this.pmtInitial) || 0;
                v.years = parseFloat(this.pmtYears);
                v.r     = parseFloat(this.pmtRate);
                v.n     = parseInt(this.pmtCompFreq, 10);
                if (isNaN(v.goal) || v.goal <= 0) throw 'Savings goal must be a positive number.';
                if (isNaN(v.years) || v.years <= 0) throw 'Time period must be a positive number.';
                if (isNaN(v.r) || v.r < 0)       throw 'Interest rate must be zero or positive.';
                if (v.P < 0)                      throw 'Initial deposit cannot be negative.';
                if (v.P >= v.goal)                throw 'Initial deposit already meets or exceeds the goal!';
                v.months = Math.max(1, Math.round((this.pmtTimeUnit === 'months' ? v.years : v.years * 12)));
                if (v.months > 2400)              throw 'Duration cannot exceed 200 years.';
                v.r_dec = v.r / 100;
            }
            return v;
        },

        /* ── Monthly growth factor ── */
        _mFactor(r, n) {
            if (n === 0) return Math.exp(r / 12);
            return Math.pow(1 + r / n, n / 12);
        },

        /* ── Core simulation (month-by-month) ── */
        _simulate(P, r, months, n, PMT, pmtPerYear, pmtAtStart) {
            var mF = this._mFactor(r, n);
            var contribEvery = pmtPerYear > 0 ? Math.round(12 / pmtPerYear) : Infinity;
            var balance = P, totalPMT = 0;
            var yearStart = P, yearInt = 0, yearPMT = 0;
            var yearlyData = [];
            var milestoneTargets = [1000,5000,10000,25000,50000,100000,250000,500000,1000000,5000000,10000000];
            var milestones = [];
            var milestoneHit = {};

            for (var m = 1; m <= months; m++) {
                // Beginning contrib
                if (pmtAtStart && PMT > 0 && contribEvery < Infinity) {
                    if ((m - 1) % contribEvery === 0) {
                        balance += PMT; yearPMT += PMT; totalPMT += PMT;
                    }
                }
                // Interest
                var prev = balance;
                balance *= mF;
                yearInt += balance - prev;
                // End contrib
                if (!pmtAtStart && PMT > 0 && contribEvery < Infinity) {
                    if (m % contribEvery === 0) {
                        balance += PMT; yearPMT += PMT; totalPMT += PMT;
                    }
                }
                // Milestones
                for (var ti = 0; ti < milestoneTargets.length; ti++) {
                    var tgt = milestoneTargets[ti];
                    if (!milestoneHit[tgt] && balance >= tgt) {
                        milestoneHit[tgt] = true;
                        milestones.push({ amount: tgt, month: m });
                    }
                }
                // Year boundary
                if (m % 12 === 0) {
                    yearlyData.push({ year: m/12, startBal: yearStart, interest: yearInt, contributions: yearPMT, endBal: balance });
                    yearStart = balance; yearInt = 0; yearPMT = 0;
                }
            }
            // Partial last year
            var rem = months % 12;
            if (rem > 0) {
                yearlyData.push({ year: parseFloat((months/12).toFixed(2)), startBal: yearStart, interest: yearInt, contributions: yearPMT, endBal: balance });
            }
            return { balance: balance, totalPMT: totalPMT, yearlyData: yearlyData, milestones: milestones };
        },

        /* ── Build Savings computation ── */
        _computeBuild(v) {
            var sim = this._simulate(v.P, v.r_dec, v.months, v.n, v.PMT, v.pmtPerYear, v.pmtAtStart);
            var finalBalance  = sim.balance;
            var totalContribs = sim.totalPMT;
            var totalInvested = v.P + totalContribs;
            var totalInterest = finalBalance - totalInvested;
            var apy = v.n === 0 ? Math.exp(v.r_dec) - 1 : Math.pow(1 + v.r_dec/v.n, v.n) - 1;
            var doublingYears = v.r_dec > 0
                ? (v.n === 0 ? Math.LN2/v.r_dec : Math.LN2/(v.n*Math.log(1+v.r_dec/v.n)))
                : Infinity;

            var initialPct  = finalBalance > 0 ? (v.P / finalBalance) * 100 : 0;
            var contribPct  = finalBalance > 0 ? (totalContribs / finalBalance) * 100 : 0;
            var interestPct = finalBalance > 0 ? (totalInterest / finalBalance) * 100 : 0;

            // What-if (3 variants around current PMT)
            var self = this;
            var halfPMT = Math.max(0, v.PMT * 0.5);
            var dblPMT  = v.PMT * 2;
            var whatIf = [
                { pmt: halfPMT, isCurrent: false },
                { pmt: v.PMT,   isCurrent: true  },
                { pmt: dblPMT,  isCurrent: false },
            ].map(function(wi) {
                var s = self._simulate(v.P, v.r_dec, v.months, v.n, wi.pmt, v.pmtPerYear, v.pmtAtStart);
                return { pmt: wi.pmt, finalBalance: s.balance, isCurrent: wi.isCurrent };
            });

            // Chart
            var chart = this._buildChart(v.P, totalInvested, sim.yearlyData, finalBalance);

            return {
                finalBalance: finalBalance,
                principal: v.P,
                totalContribs: totalContribs,
                totalInvested: totalInvested,
                totalInterest: totalInterest,
                initialPct: initialPct,
                contribPct: contribPct,
                interestPct: interestPct,
                apy: apy,
                doublingYears: doublingYears,
                totalMonths: v.months,
                yearlyData: sim.yearlyData,
                milestones: sim.milestones,
                whatIf: whatIf,
                chart: chart,
            };
        },

        /* ── Time to Goal computation ── */
        _computeGoalTime(v) {
            var mF   = this._mFactor(v.r_dec, v.n);
            var goal = v.goal;
            var bal  = v.P, totalPMT = 0, m = 0;
            var MAX  = 1200; // 100 years

            while (bal < goal && m < MAX) {
                m++;
                // End of month contributions (monthly)
                var prev = bal;
                bal *= mF;
                if (v.PMT > 0) { bal += v.PMT; totalPMT += v.PMT; }
            }

            if (bal < goal) {
                throw 'With these inputs the goal cannot be reached within 100 years. Try increasing the contribution or interest rate.';
            }

            var totalInvested = v.P + totalPMT;
            var totalInterest = bal - totalInvested;

            return {
                monthsNeeded: m,
                totalInvested: totalInvested,
                totalInterest: totalInterest,
                finalBalance: bal,
                goal: goal,
            };
        },

        /* ── Required PMT computation ── */
        _computeGoalPMT(v) {
            var mF = this._mFactor(v.r_dec, v.n);
            var compound = Math.pow(mF, v.months);
            var PMT;

            if (Math.abs(mF - 1) < 1e-10) {
                // No growth: PMT = (goal - P) / months
                PMT = (v.goal - v.P) / v.months;
            } else {
                PMT = (v.goal - v.P * compound) * (mF - 1) / (compound - 1);
            }
            PMT = Math.max(0, PMT);

            var totalContribs = PMT * v.months;
            var totalInvested = v.P + totalContribs;
            var interestContrib = v.goal - totalInvested;

            // Alternative plans: save 25%, 50%, 75% more → reach goal sooner
            var self = this;
            var altPlans = [1.0, 1.25, 1.5, 2.0].map(function(mult) {
                var altPMT = PMT * mult;
                var altBal = v.P, altM = 0;
                while (altBal < v.goal && altM < 1200) {
                    altM++;
                    altBal *= mF;
                    altBal += altPMT;
                }
                return { pmt: altPMT, months: altM, isCurrent: mult === 1.0 };
            });

            return {
                requiredPMT: PMT,
                totalMonths: v.months,
                totalInvested: totalInvested,
                totalContribs: totalContribs,
                interestContrib: interestContrib,
                altPlans: altPlans,
            };
        },

        /* ── Build SVG chart ── */
        _buildChart(P, totalInvested, yearlyData, finalBalance) {
            if (!yearlyData || yearlyData.length < 1) return null;
            var W=500, H=200, PX=45, PR=15, PT=20, PB=28;
            var plotW = W-PX-PR, plotH = H-PT-PB;
            var maxY = finalBalance, range = maxY || 1;
            var maxYr = yearlyData[yearlyData.length-1].year || 1;

            var allPts = [{ yr:0, bal:P, inv:P }];
            var cumInv = P;
            for (var i=0; i<yearlyData.length; i++) {
                cumInv += yearlyData[i].contributions;
                allPts.push({ yr: yearlyData[i].year, bal: yearlyData[i].endBal, inv: cumInv });
            }

            var toX = function(yr){ return PX + (yr/maxYr)*plotW; };
            var toY = function(v){ return PT + (1 - v/range)*plotH; };

            var linePath = '', areaPath = '', contribLinePath = '', contribAreaPath = '';
            for (var j=0; j<allPts.length; j++) {
                var px = toX(allPts[j].yr).toFixed(1), py = toY(allPts[j].bal).toFixed(1);
                var cx2 = px, cy2 = toY(allPts[j].inv).toFixed(1);
                linePath        += (j===0 ? 'M' : 'L') + px + ',' + py + ' ';
                contribLinePath += (j===0 ? 'M' : 'L') + cx2 + ',' + cy2 + ' ';
            }
            var lastX = toX(allPts[allPts.length-1].yr).toFixed(1);
            var botY  = (PT+plotH).toFixed(1);
            areaPath        = linePath.trim()        + ' L' + lastX+','+botY + ' L'+PX.toFixed(1)+','+botY + ' Z';
            contribAreaPath = contribLinePath.trim() + ' L' + lastX+','+botY + ' L'+PX.toFixed(1)+','+botY + ' Z';

            // Grid lines
            var gridLines = [];
            for (var g=0; g<=4; g++) {
                var val = (g/4)*maxY;
                gridLines.push({ y: toY(val).toFixed(1), label: this._shortNum(val) });
            }

            // X labels
            var labelStep = Math.ceil(maxYr/10);
            var xLabels = [];
            for (var yr=labelStep; yr<=maxYr; yr+=labelStep) {
                xLabels.push({ x: toX(yr).toFixed(1), label: 'Y'+yr });
            }

            var points = yearlyData.length <= 20
                ? allPts.slice(1).map(function(p){ return { x: toX(p.yr).toFixed(1), y: toY(p.bal).toFixed(1) }; })
                : [];

            return { w:W, h:H, padX:PX, padR:PR, linePath:linePath.trim(), areaPath:areaPath,
                     contribLinePath:contribLinePath.trim(), contribAreaPath:contribAreaPath,
                     gridLines:gridLines, xLabels:xLabels, points:points };
        },

        /* ── Short number ── */
        _shortNum(n) {
            if (n>=1e9) return (n/1e9).toFixed(1)+'B';
            if (n>=1e6) return (n/1e6).toFixed(1)+'M';
            if (n>=1e3) return (n/1e3).toFixed(0)+'K';
            return Math.round(n).toString();
        },

        /* ── Format helpers ── */
        fmt(v) {
            if (v===null||isNaN(v)||!isFinite(v)) return '—';
            return parseFloat(v.toFixed(2)).toString();
        },

        fmtC(v) {
            if (v===null||isNaN(v)||!isFinite(v)) return '—';
            var abs = Math.abs(v), s;
            if (abs>=1e9)      s=(v/1e9).toFixed(2)+'B';
            else if (abs>=1e6) s=(v/1e6).toFixed(2)+'M';
            else               s=v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
            return this.currency + s;
        },

        fmtPct(v) {
            if (v===null||isNaN(v)||!isFinite(v)) return '—';
            return parseFloat(v.toFixed(2))+'%';
        },

        fmtDuration(months) {
            if (!months || isNaN(months)) return '—';
            var m = Math.round(months);
            var y = Math.floor(m/12), mo = m%12;
            if (y===0)  return mo+' month'+(mo!==1?'s':'');
            if (mo===0) return y+' year'+(y!==1?'s':'');
            return y+' yr '+(mo>0 ? mo+' mo':'');
        },

        compFreqLabel(freq) {
            var map = {'365':'daily','52':'weekly','26':'bi-weekly','12':'monthly','4':'quarterly','2':'semi-annually','1':'annually','0':'continuously'};
            return map[String(freq)] || freq+'×/yr';
        },

        /* ── Sample data ── */
        loadSample() {
            this.mode = 'build';
            this.currency = '$'; this.initialDeposit = '5000';
            this.monthlyContrib = '300'; this.contribFreq = '12';
            this.contribTiming = 'end'; this.annualRate = '7';
            this.duration = '25'; this.durationUnit = 'years'; this.compFreq = '12';
            this.error = ''; this.result = null; this.phase = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            this.error = ''; this.result = null; this.phase = 'idle'; this.showTable = false;
        },

        /* ── Summary text ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            if (this.mode === 'build') {
                return [
                    'Savings Calculator Results', '=========================='  ,
                    'Mode: Build Savings',
                    'Initial Deposit  : ' + this.fmtC(r.principal),
                    'Regular Contrib  : ' + this.fmtC(parseFloat(this.monthlyContrib)||0) + ' / ' + this.compFreqLabel(this.contribFreq),
                    'Annual Rate      : ' + this.annualRate + '%',
                    'Duration         : ' + this.fmtDuration(r.totalMonths),
                    'Compounding      : ' + this.compFreqLabel(this.compFreq),
                    '',
                    '── Results ──',
                    'Future Balance   : ' + this.fmtC(r.finalBalance),
                    'Total Invested   : ' + this.fmtC(r.totalInvested),
                    'Interest Earned  : ' + this.fmtC(r.totalInterest),
                    'APY              : ' + this.fmtPct(r.apy*100),
                    '',
                    '── Year-by-Year ──',
                    'Year | Opening | Contributions | Interest | Closing',
                ].concat(r.yearlyData.map(function(d){
                    return [d.year, d.startBal.toFixed(2), d.contributions.toFixed(2), d.interest.toFixed(2), d.endBal.toFixed(2)].join(' | ');
                })).join('\n');
            } else if (this.mode === 'goal_time') {
                return [
                    'Savings Calculator – Time to Goal',
                    'Goal             : ' + this.fmtC(parseFloat(this.goalAmount)),
                    'Initial Deposit  : ' + this.fmtC(parseFloat(this.goalInitial)||0),
                    'Monthly Contrib  : ' + this.fmtC(parseFloat(this.goalMonthly)||0),
                    'Rate             : ' + this.goalRate + '%',
                    '',
                    'Time to Goal     : ' + this.fmtDuration(r.monthsNeeded),
                    'Total Invested   : ' + this.fmtC(r.totalInvested),
                    'Interest Earned  : ' + this.fmtC(r.totalInterest),
                ].join('\n');
            } else {
                return [
                    'Savings Calculator – Monthly Target',
                    'Goal             : ' + this.fmtC(parseFloat(this.pmtGoalAmount)),
                    'Time Period      : ' + this.fmtDuration(r.totalMonths),
                    'Rate             : ' + this.pmtRate + '%',
                    '',
                    'Required Monthly : ' + this.fmtC(r.requiredPMT),
                    'Total You Save   : ' + this.fmtC(r.totalInvested),
                    'Interest Adds    : ' + this.fmtC(r.interestContrib),
                ].join('\n');
            }
        },

        async copySummary() {
            var text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            var self = this; this.summaryCopyFlash = true;
            setTimeout(function(){ self.summaryCopyFlash = false; }, 1800);
        },

        downloadSummary() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text], {type:'text/plain;charset=utf-8'});
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href = url; a.download = 'savings-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\savings-calculator.blade.php ENDPATH**/ ?>