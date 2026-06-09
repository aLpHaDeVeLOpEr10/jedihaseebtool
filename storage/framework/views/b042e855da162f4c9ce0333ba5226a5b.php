<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
@media print {
    nav, header, footer, .no-print { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; break-inside: avoid; }
    body { background: white !important; }
}
</style>

<div class="min-h-screen bg-gray-50" x-data="budgetPlanner()" x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
                <div class="flex items-center gap-2 flex-wrap no-print">
                    
                    <div class="flex items-center gap-1.5 bg-gray-100 rounded-xl px-3 py-1.5">
                        <span class="text-xs text-gray-500">Currency:</span>
                        <input type="text" x-model="currency" maxlength="5" @change="save()"
                               class="w-12 text-sm font-semibold text-gray-700 bg-transparent border-none focus:outline-none text-center">
                    </div>
                    
                    <div class="flex bg-gray-100 rounded-xl p-1 gap-0.5">
                        <button type="button" @click="period='monthly'; save()"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-all"
                                :class="period==='monthly' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                            Monthly
                        </button>
                        <button type="button" @click="period='annual'; save()"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-all"
                                :class="period==='annual' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                            Annual
                        </button>
                    </div>
                    <button type="button" @click="printBudget()" class="btn btn-secondary btn-sm no-print">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    <button type="button" @click="resetAll()"
                            class="btn btn-sm border border-red-200 text-red-600 hover:bg-red-50 no-print">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1"
                   x-text="period === 'annual' ? 'Annual Income' : 'Monthly Income'"></p>
                <p class="text-xl font-bold text-emerald-600" x-text="fmt(totalIncome)"></p>
                <p class="text-xs text-gray-400 mt-0.5"
                   x-text="incomes.length + (incomes.length === 1 ? ' source' : ' sources')"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1"
                   x-text="period === 'annual' ? 'Annual Expenses' : 'Total Expenses'"></p>
                <p class="text-xl font-bold text-red-500" x-text="fmt(totalExpenses)"></p>
                <p class="text-xs text-gray-400 mt-0.5"
                   x-text="(needsCategories.length + wantsCategories.length) + ' categories'"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1"
                   x-text="period === 'annual' ? 'Annual Savings' : 'Total Savings'"></p>
                <p class="text-xl font-bold text-brand-600" x-text="fmt(totalSavings)"></p>
                <p class="text-xs text-gray-400 mt-0.5"
                   x-text="savingsGoals.length + (savingsGoals.length === 1 ? ' goal' : ' goals')"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Remaining</p>
                <p class="text-xl font-bold transition-colors"
                   :class="remaining < 0 ? 'text-red-600' : remaining > 0 ? 'text-gray-900' : 'text-gray-400'"
                   x-text="fmt(remaining)"></p>
                <p class="text-xs mt-0.5 transition-colors"
                   :class="remaining < 0 ? 'text-red-400' : 'text-gray-400'"
                   x-text="remaining < 0 ? 'Over budget!' : remaining > 0 ? 'Unallocated' : 'Balanced ✓'"></p>
            </div>

        </div>

        
        <template x-if="totalIncome > 0 && remaining < 0">
            <div class="mb-5" x-transition>
                <div class="alert alert-error flex items-start gap-2">
                    <span class="flex-shrink-0">⚠️</span>
                    <span>You are <strong x-text="fmt(Math.abs(remaining))"></strong> over budget. Reduce expenses or savings, or increase your income.</span>
                </div>
            </div>
        </template>
        <template x-if="totalIncome > 0 && remaining > 0">
            <div class="mb-5" x-transition>
                <div class="alert alert-warning flex items-start gap-2">
                    <span class="flex-shrink-0">💡</span>
                    <span><strong x-text="fmt(remaining)"></strong> is unallocated. Consider moving it to savings or an expense category so every dollar has a purpose.</span>
                </div>
            </div>
        </template>
        <template x-if="totalIncome > 0 && remaining === 0">
            <div class="mb-5" x-transition>
                <div class="alert alert-success flex items-center gap-2">
                    <span>✅</span>
                    <span>Your budget is perfectly balanced — every dollar is accounted for!</span>
                </div>
            </div>
        </template>

        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">

            
            <div class="lg:col-span-2 space-y-4">

                
                <div class="card">
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between"
                         style="background:linear-gradient(to right,#f0fdf4,#ecfdf5)">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">💰</span>
                            <h3 class="text-sm font-semibold text-gray-700">Income Sources</h3>
                        </div>
                        <span class="text-sm font-bold text-emerald-600" x-text="fmt(totalIncome)"></span>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <template x-for="(item, idx) in incomes" :key="item.id">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="item.label" @change="save()"
                                       placeholder="Source name"
                                       class="form-input flex-1 text-sm min-w-0">
                                <div class="relative flex-shrink-0 w-28">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none"
                                          x-text="currency"></span>
                                    <input type="number" x-model="item.amount" @input="save()"
                                           min="0" step="0.01" placeholder="0.00"
                                           class="form-input text-sm text-right w-full"
                                           style="padding-left:1.6rem;padding-right:0.4rem">
                                </div>
                                <button type="button" @click="removeIncome(idx)"
                                        x-show="incomes.length > 1"
                                        title="Remove"
                                        class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <div x-show="incomes.length <= 1" class="w-7 flex-shrink-0"></div>
                            </div>
                        </template>

                        <button type="button" @click="addIncome()"
                                class="no-print w-full py-2 border-2 border-dashed border-emerald-200 text-emerald-600 text-xs font-medium rounded-xl hover:bg-emerald-50 transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Income Source
                        </button>

                        <p class="text-xs text-gray-400 pt-0.5" x-show="period === 'monthly'">
                            Annual equivalent: <strong class="text-gray-600" x-text="fmtAnnual(totalIncome)"></strong>
                        </p>
                        <p class="text-xs text-gray-400 pt-0.5" x-show="period === 'annual'">
                            Monthly equivalent: <strong class="text-gray-600" x-text="fmtMonthly(totalIncome)"></strong>
                        </p>
                    </div>
                </div>

                
                <div class="card">
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between"
                         style="background:linear-gradient(to right,#eef2ff,#f5f3ff)">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🎯</span>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">Savings Goals</h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-brand-600" x-text="fmt(totalSavings)"></span>
                            <span x-show="totalIncome > 0"
                                  class="block text-xs text-gray-400"
                                  x-text="pct(totalSavings, totalIncome) + '% of income'"></span>
                        </div>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <template x-for="(goal, idx) in savingsGoals" :key="goal.id">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="goal.label" @change="save()"
                                       placeholder="Goal name"
                                       class="form-input flex-1 text-sm min-w-0">
                                <div class="relative flex-shrink-0 w-28">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none"
                                          x-text="currency"></span>
                                    <input type="number" x-model="goal.amount" @input="save()"
                                           min="0" step="0.01" placeholder="0.00"
                                           class="form-input text-sm text-right w-full"
                                           style="padding-left:1.6rem;padding-right:0.4rem">
                                </div>
                                <button type="button" @click="removeSaving(idx)"
                                        x-show="savingsGoals.length > 1"
                                        title="Remove"
                                        class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <div x-show="savingsGoals.length <= 1" class="w-7 flex-shrink-0"></div>
                            </div>
                        </template>

                        <button type="button" @click="addSaving()"
                                class="no-print w-full py-2 border-2 border-dashed border-brand-200 text-brand-600 text-xs font-medium rounded-xl hover:bg-brand-50 transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Savings Goal
                        </button>

                        <p class="text-xs text-gray-400 pt-0.5" x-show="period === 'monthly'">
                            Annual savings: <strong class="text-gray-600" x-text="fmtAnnual(totalSavings)"></strong>
                        </p>
                        <p class="text-xs text-gray-400 pt-0.5" x-show="period === 'annual'">
                            Monthly savings: <strong class="text-gray-600" x-text="fmtMonthly(totalSavings)"></strong>
                        </p>
                    </div>
                </div>

            </div>

            
            <div class="lg:col-span-3 space-y-4">

                
                <div class="card">
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between"
                         style="background:linear-gradient(to right,#eff6ff,#f0f9ff)">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🏠</span>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">
                                    Needs
                                    <span class="font-normal text-gray-400 text-xs ml-1">(Essentials)</span>
                                </h3>
                                <p class="text-xs text-gray-400">50/30/20 guide: aim for ≤ 50% of income</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <span class="text-sm font-bold text-blue-600" x-text="fmt(totalNeeds)"></span>
                            <span x-show="totalIncome > 0"
                                  class="block text-xs font-medium"
                                  :class="pct(totalNeeds, totalIncome) <= 50 ? 'text-emerald-500' : 'text-red-500'"
                                  x-text="pct(totalNeeds, totalIncome) + '% of income'"></span>
                        </div>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <template x-for="(cat, idx) in needsCategories" :key="cat.id">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="cat.label" @change="save()"
                                       placeholder="Category name"
                                       class="form-input flex-1 text-sm min-w-0">
                                <div class="relative flex-shrink-0 w-28">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none"
                                          x-text="currency"></span>
                                    <input type="number" x-model="cat.amount" @input="save()"
                                           min="0" step="0.01" placeholder="0.00"
                                           class="form-input text-sm text-right w-full"
                                           style="padding-left:1.6rem;padding-right:0.4rem">
                                </div>
                                <button type="button" @click="removeNeeds(idx)"
                                        title="Remove"
                                        class="no-print flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="addNeeds()"
                                class="no-print w-full py-2 border-2 border-dashed border-blue-200 text-blue-600 text-xs font-medium rounded-xl hover:bg-blue-50 transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Need
                        </button>
                    </div>
                </div>

                
                <div class="card">
                    <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between"
                         style="background:linear-gradient(to right,#fffbeb,#fdf4ff)">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🛍️</span>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">
                                    Wants
                                    <span class="font-normal text-gray-400 text-xs ml-1">(Discretionary)</span>
                                </h3>
                                <p class="text-xs text-gray-400">50/30/20 guide: aim for ≤ 30% of income</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <span class="text-sm font-bold text-amber-600" x-text="fmt(totalWants)"></span>
                            <span x-show="totalIncome > 0"
                                  class="block text-xs font-medium"
                                  :class="pct(totalWants, totalIncome) <= 30 ? 'text-emerald-500' : 'text-red-500'"
                                  x-text="pct(totalWants, totalIncome) + '% of income'"></span>
                        </div>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <template x-for="(cat, idx) in wantsCategories" :key="cat.id">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="cat.label" @change="save()"
                                       placeholder="Category name"
                                       class="form-input flex-1 text-sm min-w-0">
                                <div class="relative flex-shrink-0 w-28">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none"
                                          x-text="currency"></span>
                                    <input type="number" x-model="cat.amount" @input="save()"
                                           min="0" step="0.01" placeholder="0.00"
                                           class="form-input text-sm text-right w-full"
                                           style="padding-left:1.6rem;padding-right:0.4rem">
                                </div>
                                <button type="button" @click="removeWants(idx)"
                                        title="Remove"
                                        class="no-print flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="addWants()"
                                class="no-print w-full py-2 border-2 border-dashed border-amber-200 text-amber-600 text-xs font-medium rounded-xl hover:bg-amber-50 transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Want
                        </button>
                    </div>
                </div>

            </div>
        </div>

        
        <div x-show="totalIncome > 0" x-transition class="space-y-4">

            
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Budget Allocation</h3>

                
                <div class="flex flex-wrap gap-4 mb-3 text-xs text-gray-600">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-blue-500 flex-shrink-0"></span>Needs
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-amber-400 flex-shrink-0"></span>Wants
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-brand-500 flex-shrink-0"></span>Savings
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-gray-200 flex-shrink-0"></span>Unallocated
                    </span>
                </div>

                
                <div class="flex rounded-xl overflow-hidden h-6 bg-gray-100 mb-1">
                    <div class="bg-blue-500 h-full flex items-center justify-center transition-all duration-500"
                         :style="`width:${allocation.needsPct}%`">
                        <span x-show="allocation.needsPct >= 10"
                              class="text-xs font-bold text-white px-1"
                              x-text="allocation.needsPct + '%'"></span>
                    </div>
                    <div class="bg-amber-400 h-full flex items-center justify-center transition-all duration-500"
                         :style="`width:${allocation.wantsPct}%`">
                        <span x-show="allocation.wantsPct >= 10"
                              class="text-xs font-bold text-white px-1"
                              x-text="allocation.wantsPct + '%'"></span>
                    </div>
                    <div class="bg-brand-500 h-full flex items-center justify-center transition-all duration-500"
                         :style="`width:${allocation.savingsPct}%`">
                        <span x-show="allocation.savingsPct >= 10"
                              class="text-xs font-bold text-white px-1"
                              x-text="allocation.savingsPct + '%'"></span>
                    </div>
                    <div class="bg-gray-200 h-full flex items-center justify-center transition-all duration-500"
                         :style="`width:${allocation.unallocatedPct}%`">
                        <span x-show="allocation.unallocatedPct >= 10"
                              class="text-xs font-bold text-gray-500 px-1"
                              x-text="allocation.unallocatedPct + '%'"></span>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mb-6">
                    <span x-text="currency + '0'"></span>
                    <span x-text="fmt(totalIncome)"></span>
                </div>

                
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">50 / 30 / 20 Rule Analysis</h4>
                <div class="grid grid-cols-3 gap-3">

                    <div class="rounded-xl border-2 p-3 sm:p-4 text-center transition-colors"
                         :class="allocation.needsPct <= 50 ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50'">
                        <p class="text-2xl sm:text-3xl font-bold leading-none"
                           :class="allocation.needsPct <= 50 ? 'text-emerald-600' : 'text-red-600'"
                           x-text="allocation.needsPct + '%'"></p>
                        <p class="text-xs font-semibold text-gray-700 mt-1">Needs</p>
                        <p class="text-xs text-gray-400">Target: ≤ 50%</p>
                        <span class="mt-2 inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                              :class="allocation.needsPct <= 50 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                              x-text="allocation.needsPct <= 50 ? '✓ On track' : '↑ Over'"></span>
                    </div>

                    <div class="rounded-xl border-2 p-3 sm:p-4 text-center transition-colors"
                         :class="allocation.wantsPct <= 30 ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50'">
                        <p class="text-2xl sm:text-3xl font-bold leading-none"
                           :class="allocation.wantsPct <= 30 ? 'text-emerald-600' : 'text-amber-600'"
                           x-text="allocation.wantsPct + '%'"></p>
                        <p class="text-xs font-semibold text-gray-700 mt-1">Wants</p>
                        <p class="text-xs text-gray-400">Target: ≤ 30%</p>
                        <span class="mt-2 inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                              :class="allocation.wantsPct <= 30 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                              x-text="allocation.wantsPct <= 30 ? '✓ On track' : '↑ Over'"></span>
                    </div>

                    <div class="rounded-xl border-2 p-3 sm:p-4 text-center transition-colors"
                         :class="allocation.savingsPct >= 20 ? 'border-emerald-200 bg-emerald-50' : 'border-orange-200 bg-orange-50'">
                        <p class="text-2xl sm:text-3xl font-bold leading-none"
                           :class="allocation.savingsPct >= 20 ? 'text-emerald-600' : 'text-orange-500'"
                           x-text="allocation.savingsPct + '%'"></p>
                        <p class="text-xs font-semibold text-gray-700 mt-1">Savings</p>
                        <p class="text-xs text-gray-400">Target: ≥ 20%</p>
                        <span class="mt-2 inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                              :class="allocation.savingsPct >= 20 ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700'"
                              x-text="allocation.savingsPct >= 20 ? '✓ On track' : '↓ Under'"></span>
                    </div>

                </div>
            </div>

            
            <div class="card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Category Breakdown</h3>
                    <span class="text-xs text-gray-400">% of total income</span>
                </div>

                <div x-show="needsCategories.concat(wantsCategories).filter(function(c){return parseFloat(c.amount) > 0;}).length === 0"
                     class="text-sm text-gray-400 text-center py-6">
                    Fill in expense amounts above to see the breakdown.
                </div>

                <div class="space-y-2.5">
                    <template x-for="cat in allCategories" :key="cat.id">
                        <div x-show="parseFloat(cat.amount) > 0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-600 truncate mr-2" x-text="cat.label || 'Unnamed'"></span>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs text-gray-400" x-text="pct(cat.amount, totalIncome) + '%'"></span>
                                    <span class="text-xs font-semibold text-gray-900 w-20 text-right" x-text="fmt(cat.amount)"></span>
                                </div>
                            </div>
                            <div class="bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all duration-500"
                                     :class="cat.group === 'needs' ? 'bg-blue-500' : 'bg-amber-400'"
                                     :style="`width:${Math.min(100, pct(cat.amount, totalIncome))}%`"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    Budget Summary
                    <span class="font-normal text-gray-400 ml-1"
                          x-text="period === 'annual' ? '(Annual)' : '(Monthly)'"></span>
                </h3>

                <div class="space-y-0.5">
                    
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider py-2">Income</div>
                    <template x-for="item in incomes" :key="'s-' + item.id">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600" x-text="item.label || 'Income'"></span>
                            <span class="text-sm font-medium text-emerald-600" x-text="fmt(item.amount || 0)"></span>
                        </div>
                    </template>
                    <div class="flex items-center justify-between py-2 px-3 -mx-3 bg-emerald-50 rounded-lg mt-1 mb-3">
                        <span class="text-sm font-bold text-gray-800">Total Income</span>
                        <span class="text-sm font-bold text-emerald-600" x-text="fmt(totalIncome)"></span>
                    </div>

                    
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider py-2">Needs</div>
                    <template x-for="cat in needsCategories" :key="'s-' + cat.id">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600" x-text="cat.label || 'Expense'"></span>
                            <span class="text-sm font-medium text-red-500"
                                  x-text="'(' + fmt(cat.amount || 0) + ')'"></span>
                        </div>
                    </template>

                    
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider py-2 mt-1">Wants</div>
                    <template x-for="cat in wantsCategories" :key="'s-' + cat.id">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600" x-text="cat.label || 'Expense'"></span>
                            <span class="text-sm font-medium text-red-500"
                                  x-text="'(' + fmt(cat.amount || 0) + ')'"></span>
                        </div>
                    </template>

                    <div class="flex items-center justify-between py-2 px-3 -mx-3 bg-red-50 rounded-lg mt-1 mb-3">
                        <span class="text-sm font-bold text-gray-800">Total Expenses</span>
                        <span class="text-sm font-bold text-red-600"
                              x-text="'(' + fmt(totalExpenses) + ')'"></span>
                    </div>

                    
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider py-2">Savings</div>
                    <template x-for="goal in savingsGoals" :key="'s-' + goal.id">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600" x-text="goal.label || 'Savings'"></span>
                            <span class="text-sm font-medium text-brand-600"
                                  x-text="'(' + fmt(goal.amount || 0) + ')'"></span>
                        </div>
                    </template>

                    <div class="flex items-center justify-between py-2 px-3 -mx-3 bg-brand-50 rounded-lg mt-1 mb-3">
                        <span class="text-sm font-bold text-gray-800">Total Savings</span>
                        <span class="text-sm font-bold text-brand-600"
                              x-text="'(' + fmt(totalSavings) + ')'"></span>
                    </div>

                    
                    <div class="flex items-center justify-between pt-3 pb-1 border-t-2 border-gray-200">
                        <span class="text-base font-bold text-gray-900">Remaining Balance</span>
                        <span class="text-base font-bold"
                              :class="remaining < 0 ? 'text-red-600' : remaining === 0 ? 'text-emerald-600' : 'text-gray-900'"
                              x-text="fmt(remaining)"></span>
                    </div>
                </div>
            </div>

        </div>

        
        <div x-show="totalIncome === 0" x-transition class="card p-12 text-center">
            <div class="text-5xl mb-4">📊</div>
            <p class="font-semibold text-gray-600 text-lg">Enter your income to get started</p>
            <p class="text-sm text-gray-400 mt-2 max-w-md mx-auto">Fill in your income, expense categories, and savings goals above. The breakdown and 50/30/20 analysis will appear automatically.</p>
        </div>

        <p class="text-xs text-center text-gray-400 mt-8 no-print">
            🔒 Your budget data is saved locally in your browser — nothing is sent to any server.
        </p>

        <?php if($relatedTools->count()): ?>
        <div class="mt-8 no-print">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl"><?php echo e($related->icon); ?></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
                        <p class="text-xs text-gray-400 truncate"><?php echo e($related->short_description); ?></p>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function budgetPlanner() {
    return {
        currency: '$',
        period:   'monthly',

        incomes:         [],
        needsCategories: [],
        wantsCategories: [],
        savingsGoals:    [],
        _nextId: 1,

        /* ── Computed (all internal values are BASE/monthly; fmt() applies period multiplier) ── */

        get totalIncome() {
            return this.incomes.reduce(function(s, i) { return s + (parseFloat(i.amount) || 0); }, 0);
        },
        get totalNeeds() {
            return this.needsCategories.reduce(function(s, c) { return s + (parseFloat(c.amount) || 0); }, 0);
        },
        get totalWants() {
            return this.wantsCategories.reduce(function(s, c) { return s + (parseFloat(c.amount) || 0); }, 0);
        },
        get totalExpenses() { return this.totalNeeds + this.totalWants; },
        get totalSavings() {
            return this.savingsGoals.reduce(function(s, g) { return s + (parseFloat(g.amount) || 0); }, 0);
        },
        get remaining() { return this.totalIncome - this.totalExpenses - this.totalSavings; },

        get allocation() {
            var base = this.totalIncome || 1;
            var n = Math.round((this.totalNeeds   / base) * 100);
            var w = Math.round((this.totalWants   / base) * 100);
            var s = Math.round((this.totalSavings / base) * 100);
            /* Guard: cap sum at 100 */
            var sum = n + w + s;
            if (sum > 100) { var over = sum - 100; n = Math.max(0, n - over); }
            var u = Math.max(0, 100 - n - w - s);
            return { needsPct: n, wantsPct: w, savingsPct: s, unallocatedPct: u };
        },

        get allCategories() {
            var self = this;
            var needs = this.needsCategories.map(function(c) { return { id: c.id, label: c.label, amount: c.amount, group: 'needs' }; });
            var wants = this.wantsCategories.map(function(c) { return { id: 'w' + c.id, label: c.label, amount: c.amount, group: 'wants' }; });
            return needs.concat(wants);
        },

        /* ── Lifecycle ── */
        init() {
            var raw = localStorage.getItem('budget_planner_data');
            if (raw) {
                try {
                    var d = JSON.parse(raw);
                    this.currency        = d.currency        || '$';
                    this.period          = d.period          || 'monthly';
                    this.incomes         = d.incomes         || [];
                    this.needsCategories = d.needsCategories || [];
                    this.wantsCategories = d.wantsCategories || [];
                    this.savingsGoals    = d.savingsGoals    || [];
                    this._nextId         = d._nextId         || 100;
                } catch(e) { /* corrupt data — fall through to defaults */ }
            }
            if (!this.incomes.length) this._seedDefaults();
        },

        _seedDefaults() {
            var id = 1;
            this.incomes = [
                { id: id++, label: 'Primary Salary', amount: '' },
            ];
            this.needsCategories = [
                { id: id++, label: 'Housing / Rent',      amount: '' },
                { id: id++, label: 'Utilities',           amount: '' },
                { id: id++, label: 'Groceries',           amount: '' },
                { id: id++, label: 'Transportation',      amount: '' },
                { id: id++, label: 'Health & Insurance',  amount: '' },
                { id: id++, label: 'Loan / EMI',          amount: '' },
            ];
            this.wantsCategories = [
                { id: id++, label: 'Dining Out',          amount: '' },
                { id: id++, label: 'Entertainment',       amount: '' },
                { id: id++, label: 'Shopping & Clothing', amount: '' },
                { id: id++, label: 'Subscriptions',       amount: '' },
                { id: id++, label: 'Personal Care',       amount: '' },
                { id: id++, label: 'Travel & Vacation',   amount: '' },
            ];
            this.savingsGoals = [
                { id: id++, label: 'Emergency Fund', amount: '' },
                { id: id++, label: 'Investments',    amount: '' },
            ];
            this._nextId = id;
        },

        /* ── Mutations ── */
        addIncome()       { this.incomes.push({ id: this._nextId++, label: '', amount: '' }); this.save(); },
        removeIncome(idx) { if (this.incomes.length > 1) { this.incomes.splice(idx, 1); this.save(); } },

        addNeeds()        { this.needsCategories.push({ id: this._nextId++, label: '', amount: '' }); this.save(); },
        removeNeeds(idx)  { this.needsCategories.splice(idx, 1); this.save(); },

        addWants()        { this.wantsCategories.push({ id: this._nextId++, label: '', amount: '' }); this.save(); },
        removeWants(idx)  { this.wantsCategories.splice(idx, 1); this.save(); },

        addSaving()       { this.savingsGoals.push({ id: this._nextId++, label: '', amount: '' }); this.save(); },
        removeSaving(idx) { if (this.savingsGoals.length > 1) { this.savingsGoals.splice(idx, 1); this.save(); } },

        resetAll() {
            if (!confirm('Reset all budget data to defaults? This cannot be undone.')) return;
            localStorage.removeItem('budget_planner_data');
            this.incomes = []; this.needsCategories = []; this.wantsCategories = []; this.savingsGoals = [];
            this._nextId = 1;
            this._seedDefaults();
        },

        printBudget() { window.print(); },

        /* ── Persistence ── */
        save() {
            localStorage.setItem('budget_planner_data', JSON.stringify({
                currency:        this.currency,
                period:          this.period,
                incomes:         this.incomes,
                needsCategories: this.needsCategories,
                wantsCategories: this.wantsCategories,
                savingsGoals:    this.savingsGoals,
                _nextId:         this._nextId,
            }));
        },

        /* ── Formatting — fmt() applies period multiplier ── */
        _mult() { return this.period === 'annual' ? 12 : 1; },

        fmt(n) {
            var v = (parseFloat(n) || 0) * this._mult();
            return this.currency + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        fmtAnnual(monthlyBase) {
            var v = (parseFloat(monthlyBase) || 0) * 12;
            return this.currency + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        fmtMonthly(annualDisplayed) {
            /* annualDisplayed is the annual total; divide by 12 for monthly */
            var v = (parseFloat(annualDisplayed) || 0) / 12;
            return this.currency + v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        pct(part, total) {
            if (!total) return 0;
            return Math.round(((parseFloat(part) || 0) / (parseFloat(total) || 1)) * 100);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\budget-planner.blade.php ENDPATH**/ ?>