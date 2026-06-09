<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap" x-data x-cloak>
                    <div class="flex items-center gap-1.5 bg-gray-100 rounded-xl px-3 py-1.5">
                        <span class="text-xs text-gray-500">Currency:</span>
                        <input type="text"
                               x-data
                               x-model="$store.expenseApp.currency"
                               @change="$store.expenseApp.saveCurrency()"
                               maxlength="5"
                               class="w-12 text-sm font-semibold text-gray-700 bg-transparent border-none focus:outline-none text-center">
                    </div>
                    <button x-data @click="$store.expenseApp.exportCSV()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8"
         x-data="expenseTracker()"
         x-init="init()">

        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">All Time</p>
                <p class="text-xl font-bold text-gray-900" x-text="formatAmount(totalAllTime)"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-text="expenseCount + ' expenses'"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" x-text="thisMonthLabel"></p>
                <p class="text-xl font-bold text-brand-600" x-text="formatAmount(thisMonthTotal)"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-text="thisMonthCount + ' this month'"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Highest</p>
                <p class="text-xl font-bold text-gray-900"
                   x-text="highestExpense ? formatAmount(highestExpense.amount) : '—'"></p>
                <p class="text-xs text-gray-400 mt-0.5 truncate"
                   x-text="highestExpense ? highestExpense.title : 'No data yet'"></p>
            </div>

            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Filtered Total</p>
                <p class="text-xl font-bold text-emerald-600" x-text="formatAmount(filteredTotal)"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-text="filteredExpenses.length + ' shown'"></p>
            </div>

        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2 space-y-4">

                
                <div class="card" x-ref="formCard">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700"
                            x-text="editMode ? '✏️ Edit Expense' : '➕ Add Expense'"></h3>
                        <button type="button" x-show="editMode" @click="resetForm()"
                                class="text-xs text-gray-400 hover:text-gray-600 font-medium transition-colors">
                            Cancel edit
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updateExpense() : addExpense()" class="p-5 space-y-3">

                        
                        <div x-show="formError" x-transition
                             class="alert alert-error text-xs" x-text="formError"></div>

                        
                        <div>
                            <label class="form-label">Expense Name *</label>
                            <input type="text" x-model="form.title" required
                                   placeholder="e.g. Grocery, Netflix, Rent"
                                   class="form-input">
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Amount *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium"
                                          x-text="currency"></span>
                                    <input type="number" x-model="form.amount"
                                           min="0.01" step="0.01" required
                                           placeholder="0.00"
                                           class="form-input"
                                           style="padding-left:2rem">
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Date *</label>
                                <input type="date" x-model="form.date" required class="form-input">
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Category *</label>
                                <select x-model="form.category" class="form-input">
                                    <option value="Food &amp; Dining">🍔 Food &amp; Dining</option>
                                    <option value="Transportation">🚗 Transportation</option>
                                    <option value="Shopping">🛍️ Shopping</option>
                                    <option value="Entertainment">🎭 Entertainment</option>
                                    <option value="Health &amp; Medical">💊 Health &amp; Medical</option>
                                    <option value="Utilities &amp; Bills">💡 Utilities &amp; Bills</option>
                                    <option value="Education">📚 Education</option>
                                    <option value="Travel">✈️ Travel</option>
                                    <option value="Personal Care">💄 Personal Care</option>
                                    <option value="Home">🏠 Home</option>
                                    <option value="Others">📦 Others</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Payment *</label>
                                <select x-model="form.paymentMethod" class="form-input">
                                    <option>Cash</option>
                                    <option>Credit Card</option>
                                    <option>Debit Card</option>
                                    <option>Bank Transfer</option>
                                    <option>Digital Wallet</option>
                                    <option>Cheque</option>
                                </select>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">
                                Notes
                                <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <textarea x-model="form.notes" rows="2"
                                      placeholder="Any extra details…"
                                      class="form-input resize-none"></textarea>
                        </div>

                        
                        <div class="flex gap-2 pt-1">
                            <button type="submit" class="btn btn-primary flex-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>
                                <span x-text="editMode ? 'Update Expense' : 'Add Expense'"></span>
                            </button>
                            <button type="button" @click="resetForm()"
                                    x-show="editMode"
                                    class="btn btn-secondary">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>

                
                <div class="card" x-show="expenses.length > 0" x-transition>
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700">Category Breakdown</h3>
                        <span class="text-xs text-gray-400"
                              x-text="filterCategory !== 'all' || filterDateFrom || filterDateTo || filterPayment !== 'all' || searchQuery ? 'filtered view' : 'all time'"></span>
                    </div>
                    <div class="p-5 space-y-3">
                        <template x-if="categoryTotals.length === 0">
                            <p class="text-sm text-gray-400 text-center py-4">No data to show.</p>
                        </template>
                        <template x-for="cat in categoryTotals" :key="cat.category">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="text-base flex-shrink-0"
                                              x-text="(categoryMeta[cat.category] || {}).icon || '📦'"></span>
                                        <span class="text-xs text-gray-700 truncate" x-text="cat.category"></span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                        <span class="text-xs text-gray-400" x-text="cat.pct + '%'"></span>
                                        <span class="text-xs font-semibold text-gray-900 w-20 text-right"
                                              x-text="formatAmount(cat.total)"></span>
                                    </div>
                                </div>
                                <div class="bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-brand-500 transition-all duration-500"
                                         :style="`width:${cat.pct}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            
            <div class="lg:col-span-3 space-y-4">

                
                <div class="card p-4 space-y-3">

                    
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <input type="text" x-model="searchQuery"
                               placeholder="Search by name, category, or notes…"
                               class="form-input pl-9">
                        <button type="button" x-show="searchQuery"
                                @click="searchQuery=''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <select x-model="filterCategory" class="form-input text-sm">
                            <option value="all">All Categories</option>
                            <option value="Food &amp; Dining">🍔 Food &amp; Dining</option>
                            <option value="Transportation">🚗 Transportation</option>
                            <option value="Shopping">🛍️ Shopping</option>
                            <option value="Entertainment">🎭 Entertainment</option>
                            <option value="Health &amp; Medical">💊 Health &amp; Medical</option>
                            <option value="Utilities &amp; Bills">💡 Utilities &amp; Bills</option>
                            <option value="Education">📚 Education</option>
                            <option value="Travel">✈️ Travel</option>
                            <option value="Personal Care">💄 Personal Care</option>
                            <option value="Home">🏠 Home</option>
                            <option value="Others">📦 Others</option>
                        </select>

                        <select x-model="filterPayment" class="form-input text-sm">
                            <option value="all">All Payments</option>
                            <option>Cash</option>
                            <option>Credit Card</option>
                            <option>Debit Card</option>
                            <option>Bank Transfer</option>
                            <option>Digital Wallet</option>
                            <option>Cheque</option>
                        </select>

                        <input type="date" x-model="filterDateFrom"
                               class="form-input text-sm" title="From date">

                        <input type="date" x-model="filterDateTo"
                               class="form-input text-sm" title="To date">
                    </div>

                    
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-xs text-gray-400 font-medium">Quick:</span>
                        <button type="button" @click="setDatePreset('week')"
                                class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                :class="activePreset==='week' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            This Week
                        </button>
                        <button type="button" @click="setDatePreset('month')"
                                class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                :class="activePreset==='month' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            This Month
                        </button>
                        <button type="button" @click="setDatePreset('last')"
                                class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                :class="activePreset==='last' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            Last Month
                        </button>
                        <button type="button" @click="setDatePreset('year')"
                                class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                :class="activePreset==='year' ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            This Year
                        </button>
                        <button type="button" @click="clearFilters()"
                                x-show="hasActiveFilters"
                                class="px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            ✕ Clear
                        </button>
                        <span class="ml-auto text-xs text-gray-400"
                              x-text="filteredExpenses.length + ' of ' + expenses.length + ' expenses'"></span>
                    </div>
                </div>

                
                <div class="card overflow-hidden">

                    
                    <div class="hidden sm:grid grid-cols-12 gap-2 px-4 py-2.5 border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider select-none">
                        <button class="col-span-4 flex items-center gap-1 text-left hover:text-gray-700 transition-colors"
                                @click="setSort('title')">
                            Expense
                            <span x-show="sortBy==='title'" x-text="sortDir==='desc'?'↓':'↑'" class="text-brand-500"></span>
                        </button>
                        <button class="col-span-3 flex items-center gap-1 text-left hover:text-gray-700 transition-colors"
                                @click="setSort('category')">
                            Category
                            <span x-show="sortBy==='category'" x-text="sortDir==='desc'?'↓':'↑'" class="text-brand-500"></span>
                        </button>
                        <button class="col-span-2 flex items-center gap-1 text-left hover:text-gray-700 transition-colors"
                                @click="setSort('date')">
                            Date
                            <span x-show="sortBy==='date'" x-text="sortDir==='desc'?'↓':'↑'" class="text-brand-500"></span>
                        </button>
                        <div class="col-span-1 text-center">Pay</div>
                        <button class="col-span-2 flex items-center justify-end gap-1 hover:text-gray-700 transition-colors"
                                @click="setSort('amount')">
                            <span x-show="sortBy==='amount'" x-text="sortDir==='desc'?'↓':'↑'" class="text-brand-500"></span>
                            Amount
                        </button>
                    </div>

                    
                    <div x-show="filteredExpenses.length === 0" class="py-16 text-center">
                        <div class="text-5xl mb-3">💸</div>
                        <p class="font-semibold text-gray-600" x-show="expenses.length === 0">No expenses yet!</p>
                        <p class="text-sm text-gray-400 mt-1" x-show="expenses.length === 0">Add your first expense using the form.</p>
                        <p class="font-semibold text-gray-600" x-show="expenses.length > 0 && filteredExpenses.length === 0">No matches found.</p>
                        <p class="text-sm text-gray-400 mt-1" x-show="expenses.length > 0 && filteredExpenses.length === 0">Try adjusting your filters.</p>
                    </div>

                    
                    <div class="hidden sm:block divide-y divide-gray-50">
                        <template x-for="expense in filteredExpenses" :key="expense.id">
                            <div class="grid grid-cols-12 gap-2 px-4 py-3 hover:bg-gray-50 group transition-colors items-center">
                                
                                <div class="col-span-4 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="expense.title"></p>
                                    <p x-show="expense.notes" class="text-xs text-gray-400 truncate mt-0.5" x-text="expense.notes"></p>
                                </div>
                                
                                <div class="col-span-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                                          :class="(categoryMeta[expense.category] || {}).color || 'bg-gray-100 text-gray-600'">
                                        <span x-text="(categoryMeta[expense.category] || {}).icon || '📦'"></span>
                                        <span class="truncate" x-text="expense.category"></span>
                                    </span>
                                </div>
                                
                                <div class="col-span-2 text-xs text-gray-500" x-text="formatDate(expense.date)"></div>
                                
                                <div class="col-span-1 text-center">
                                    <span class="badge badge-gray text-xs" x-text="paymentIcon(expense.paymentMethod)"></span>
                                </div>
                                
                                <div class="col-span-2 text-right font-semibold text-gray-900 text-sm"
                                     x-text="formatAmount(expense.amount)"></div>
                                
                                <div class="col-span-12 flex gap-2 justify-end -mt-1 pb-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                     x-show="true">
                                    <div x-show="_deleteTarget !== expense.id" class="flex gap-1.5">
                                        <button type="button" @click="startEdit(expense)"
                                                class="btn btn-secondary btn-sm text-xs">Edit</button>
                                        <button type="button" @click="requestDelete(expense.id)"
                                                class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 rounded-lg hover:bg-red-50 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                    <div x-show="_deleteTarget === expense.id" class="flex items-center gap-2">
                                        <span class="text-xs text-gray-600">Are you sure?</span>
                                        <button type="button" @click="confirmDelete()"
                                                class="btn btn-sm bg-red-500 text-white text-xs">Yes, delete</button>
                                        <button type="button" @click="cancelDelete()"
                                                class="btn btn-secondary btn-sm text-xs">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    
                    <div class="sm:hidden divide-y divide-gray-50">
                        <template x-for="expense in filteredExpenses" :key="expense.id">
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex items-start gap-2.5 min-w-0">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                                             :class="(categoryMeta[expense.category] || {}).color || 'bg-gray-100 text-gray-600'">
                                            <span x-text="(categoryMeta[expense.category] || {}).icon || '📦'"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="expense.title"></p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                <span x-text="expense.category"></span>
                                                · <span x-text="formatDate(expense.date)"></span>
                                            </p>
                                            <p x-show="expense.notes" class="text-xs text-gray-400 mt-0.5 truncate" x-text="expense.notes"></p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="font-bold text-gray-900 text-sm" x-text="formatAmount(expense.amount)"></p>
                                        <span class="badge badge-gray text-xs mt-1" x-text="expense.paymentMethod"></span>
                                    </div>
                                </div>
                                
                                <div x-show="_deleteTarget !== expense.id" class="flex gap-2">
                                    <button type="button" @click="startEdit(expense)"
                                            class="btn btn-secondary btn-sm flex-1 text-xs">Edit</button>
                                    <button type="button" @click="requestDelete(expense.id)"
                                            class="btn btn-sm flex-1 text-xs text-red-600 border border-red-200 hover:bg-red-50">Delete</button>
                                </div>
                                <div x-show="_deleteTarget === expense.id" class="flex gap-2">
                                    <span class="text-xs text-gray-600 my-auto">Sure?</span>
                                    <button type="button" @click="confirmDelete()"
                                            class="btn btn-sm flex-1 bg-red-500 text-white text-xs">Yes, delete</button>
                                    <button type="button" @click="cancelDelete()"
                                            class="btn btn-secondary btn-sm flex-1 text-xs">Cancel</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    
                    <div x-show="filteredExpenses.length > 0"
                         class="px-4 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            <span x-text="filteredExpenses.length"></span>
                            <span x-show="filteredExpenses.length !== expenses.length"> of <span x-text="expenses.length"></span></span>
                            expenses
                        </span>
                        <span class="text-sm font-bold text-gray-900"
                              x-text="formatAmount(filteredTotal)"></span>
                    </div>

                </div>

            </div>

        </div>

        
        <p class="text-xs text-center text-gray-400 mt-8">
            🔒 All data is stored locally in your browser using localStorage — nothing is sent to any server.
        </p>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-8">
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
/* ── Alpine Store (shared with header actions) ── */
document.addEventListener('alpine:init', function() {
    Alpine.store('expenseApp', {
        currency: '$',
        saveCurrency() {
            localStorage.setItem('expense_cfg', JSON.stringify({ currency: this.currency }));
            /* Sync into the main component via a custom event */
            window.dispatchEvent(new CustomEvent('expense-currency-change', { detail: this.currency }));
        },
        exportCSV() {
            window.dispatchEvent(new CustomEvent('expense-export-csv'));
        },
    });
});

function expenseTracker() {
    return {
        /* ── Data ── */
        expenses: [],

        /* ── Form ── */
        form: {
            id:            null,
            title:         '',
            amount:        '',
            category:      'Food & Dining',
            date:          '',
            paymentMethod: 'Cash',
            notes:         '',
        },
        editMode:  false,
        formError: '',

        /* ── Filters ── */
        searchQuery:    '',
        filterCategory: 'all',
        filterPayment:  'all',
        filterDateFrom: '',
        filterDateTo:   '',
        activePreset:   '',

        /* ── Sort ── */
        sortBy:  'date',
        sortDir: 'desc',

        /* ── Delete confirm ── */
        _deleteTarget: null,

        /* ── Settings ── */
        currency: '$',

        /* ── Category meta ── */
        categoryMeta: {
            'Food & Dining':     { icon: '🍔', color: 'bg-orange-100 text-orange-700' },
            'Transportation':    { icon: '🚗', color: 'bg-blue-100 text-blue-700' },
            'Shopping':          { icon: '🛍️', color: 'bg-pink-100 text-pink-700' },
            'Entertainment':     { icon: '🎭', color: 'bg-purple-100 text-purple-700' },
            'Health & Medical':  { icon: '💊', color: 'bg-red-100 text-red-700' },
            'Utilities & Bills': { icon: '💡', color: 'bg-yellow-100 text-yellow-700' },
            'Education':         { icon: '📚', color: 'bg-indigo-100 text-indigo-700' },
            'Travel':            { icon: '✈️', color: 'bg-sky-100 text-sky-700' },
            'Personal Care':     { icon: '💄', color: 'bg-rose-100 text-rose-700' },
            'Home':              { icon: '🏠', color: 'bg-emerald-100 text-emerald-700' },
            'Others':            { icon: '📦', color: 'bg-gray-100 text-gray-700' },
        },

        /* ── Computed ── */

        get filteredExpenses() {
            var q    = this.searchQuery.toLowerCase().trim();
            var list = this.expenses.filter(function(e) {
                /* Search */
                if (q && !(
                    e.title.toLowerCase().includes(q) ||
                    e.category.toLowerCase().includes(q) ||
                    (e.notes && e.notes.toLowerCase().includes(q)) ||
                    e.paymentMethod.toLowerCase().includes(q)
                )) return false;

                /* Category */
                if (this.filterCategory !== 'all' && e.category !== this.filterCategory) return false;

                /* Payment */
                if (this.filterPayment !== 'all' && e.paymentMethod !== this.filterPayment) return false;

                /* Date range */
                if (this.filterDateFrom && e.date < this.filterDateFrom) return false;
                if (this.filterDateTo   && e.date > this.filterDateTo)   return false;

                return true;
            }.bind(this));

            /* Sort */
            var by  = this.sortBy;
            var dir = this.sortDir;
            list.sort(function(a, b) {
                var av, bv;
                if (by === 'amount') {
                    av = parseFloat(a.amount); bv = parseFloat(b.amount);
                } else if (by === 'title') {
                    av = a.title.toLowerCase(); bv = b.title.toLowerCase();
                } else if (by === 'category') {
                    av = a.category; bv = b.category;
                } else {
                    av = a.date; bv = b.date;
                }
                if (av < bv) return dir === 'asc' ? -1 : 1;
                if (av > bv) return dir === 'asc' ? 1  : -1;
                return 0;
            });

            return list;
        },

        get filteredTotal() {
            return this.filteredExpenses.reduce(function(s, e) { return s + parseFloat(e.amount); }, 0);
        },

        get totalAllTime() {
            return this.expenses.reduce(function(s, e) { return s + parseFloat(e.amount); }, 0);
        },

        get expenseCount() { return this.expenses.length; },

        get thisMonthLabel() {
            return new Date().toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        get thisMonthTotal() {
            var ym = this._thisMonthPrefix();
            return this.expenses
                .filter(function(e) { return e.date && e.date.startsWith(ym); })
                .reduce(function(s, e) { return s + parseFloat(e.amount); }, 0);
        },

        get thisMonthCount() {
            var ym = this._thisMonthPrefix();
            return this.expenses.filter(function(e) { return e.date && e.date.startsWith(ym); }).length;
        },

        get highestExpense() {
            if (!this.expenses.length) return null;
            return this.expenses.reduce(function(max, e) {
                return parseFloat(e.amount) > parseFloat(max.amount) ? e : max;
            });
        },

        get hasActiveFilters() {
            return this.searchQuery || this.filterCategory !== 'all' ||
                   this.filterPayment !== 'all' || this.filterDateFrom || this.filterDateTo;
        },

        get categoryTotals() {
            var totals = {};
            var total  = this.filteredTotal || 1;
            this.filteredExpenses.forEach(function(e) {
                totals[e.category] = (totals[e.category] || 0) + parseFloat(e.amount);
            });
            return Object.entries(totals)
                .sort(function(a, b) { return b[1] - a[1]; })
                .map(function(entry) {
                    return {
                        category: entry[0],
                        total:    entry[1],
                        pct:      Math.round((entry[1] / total) * 100),
                    };
                });
        },

        /* ── Lifecycle ── */
        init() {
            /* Load expenses */
            var raw = localStorage.getItem('expense_tracker_data');
            this.expenses = raw ? JSON.parse(raw) : [];

            /* Load config */
            var cfg = JSON.parse(localStorage.getItem('expense_cfg') || '{}');
            if (cfg.currency) {
                this.currency = cfg.currency;
                Alpine.store('expenseApp').currency = cfg.currency;
            }

            /* Default date = today */
            this.form.date = this._todayStr();

            /* Listen for currency change from header store */
            var self = this;
            window.addEventListener('expense-currency-change', function(ev) {
                self.currency = ev.detail;
            });

            /* Listen for CSV export trigger from header */
            window.addEventListener('expense-export-csv', function() { self.exportCSV(); });
        },

        /* ── Form operations ── */
        addExpense() {
            var amount = parseFloat(this.form.amount);
            if (!this.form.title.trim() || !amount || amount <= 0 || !this.form.date) {
                this.formError = 'Please fill in name, a valid amount, and a date.';
                return;
            }
            this.formError = '';

            this.expenses.unshift({
                id:            Date.now(),
                title:         this.form.title.trim(),
                amount:        parseFloat(amount.toFixed(2)),
                category:      this.form.category,
                date:          this.form.date,
                paymentMethod: this.form.paymentMethod,
                notes:         this.form.notes.trim(),
            });

            this.saveExpenses();
            this.resetForm();
        },

        startEdit(expense) {
            this.form.id            = expense.id;
            this.form.title         = expense.title;
            this.form.amount        = expense.amount;
            this.form.category      = expense.category;
            this.form.date          = expense.date;
            this.form.paymentMethod = expense.paymentMethod;
            this.form.notes         = expense.notes || '';
            this.editMode  = true;
            this.formError = '';
            this._deleteTarget = null;

            /* Scroll form into view on mobile */
            var self = this;
            this.$nextTick(function() {
                var el = self.$refs.formCard;
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },

        updateExpense() {
            var amount = parseFloat(this.form.amount);
            if (!this.form.title.trim() || !amount || amount <= 0 || !this.form.date) {
                this.formError = 'Please fill in all required fields.';
                return;
            }
            this.formError = '';

            var idx = this.expenses.findIndex(function(e) { return e.id === this.form.id; }.bind(this));
            if (idx !== -1) {
                this.expenses[idx] = {
                    id:            this.form.id,
                    title:         this.form.title.trim(),
                    amount:        parseFloat(amount.toFixed(2)),
                    category:      this.form.category,
                    date:          this.form.date,
                    paymentMethod: this.form.paymentMethod,
                    notes:         this.form.notes.trim(),
                };
                /* Trigger Alpine reactivity for the array */
                this.expenses = [...this.expenses];
            }

            this.saveExpenses();
            this.resetForm();
        },

        resetForm() {
            this.form.id            = null;
            this.form.title         = '';
            this.form.amount        = '';
            this.form.category      = 'Food & Dining';
            this.form.date          = this._todayStr();
            this.form.paymentMethod = 'Cash';
            this.form.notes         = '';
            this.editMode  = false;
            this.formError = '';
        },

        /* ── Delete with inline confirmation ── */
        requestDelete(id) { this._deleteTarget = id; },
        cancelDelete()    { this._deleteTarget = null; },
        confirmDelete() {
            var id = this._deleteTarget;
            this._deleteTarget = null;
            this.expenses = this.expenses.filter(function(e) { return e.id !== id; });
            this.saveExpenses();
            /* If we were editing this expense, cancel edit */
            if (this.form.id === id) this.resetForm();
        },

        /* ── Sorting ── */
        setSort(col) {
            if (this.sortBy === col) {
                this.sortDir = this.sortDir === 'desc' ? 'asc' : 'desc';
            } else {
                this.sortBy  = col;
                this.sortDir = 'desc';
            }
        },

        /* ── Filters ── */
        setDatePreset(preset) {
            var now   = new Date();
            var today = this._todayStr();
            this.activePreset = preset;

            if (preset === 'week') {
                var start = new Date(now);
                start.setDate(now.getDate() - now.getDay());
                this.filterDateFrom = start.toISOString().slice(0, 10);
                this.filterDateTo   = today;
            } else if (preset === 'month') {
                var yr = now.getFullYear();
                var mo = String(now.getMonth() + 1).padStart(2, '0');
                this.filterDateFrom = yr + '-' + mo + '-01';
                this.filterDateTo   = today;
            } else if (preset === 'last') {
                var lmStart = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                var lmEnd   = new Date(now.getFullYear(), now.getMonth(), 0);
                this.filterDateFrom = lmStart.toISOString().slice(0, 10);
                this.filterDateTo   = lmEnd.toISOString().slice(0, 10);
            } else if (preset === 'year') {
                this.filterDateFrom = now.getFullYear() + '-01-01';
                this.filterDateTo   = today;
            }
        },

        clearFilters() {
            this.searchQuery    = '';
            this.filterCategory = 'all';
            this.filterPayment  = 'all';
            this.filterDateFrom = '';
            this.filterDateTo   = '';
            this.activePreset   = '';
        },

        /* ── CSV export ── */
        exportCSV() {
            var cur  = this.currency;
            var rows = [['Title', 'Amount (' + cur + ')', 'Category', 'Date', 'Payment Method', 'Notes']];
            this.filteredExpenses.forEach(function(e) {
                rows.push([e.title, e.amount, e.category, e.date, e.paymentMethod, e.notes || '']);
            });
            var csv  = rows.map(function(r) {
                return r.map(function(c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(',');
            }).join('\r\n');
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var a    = document.createElement('a');
            a.href   = URL.createObjectURL(blob);
            a.download = 'expenses_' + this._todayStr() + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);
        },

        /* ── Persistence ── */
        saveExpenses() {
            localStorage.setItem('expense_tracker_data', JSON.stringify(this.expenses));
        },

        /* ── Formatting ── */
        formatAmount(n) {
            var num = parseFloat(n) || 0;
            return this.currency + num.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },

        formatDate(d) {
            if (!d) return '';
            var date = new Date(d + 'T00:00:00');
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },

        paymentIcon(method) {
            var icons = {
                'Cash': '💵', 'Credit Card': '💳', 'Debit Card': '🏧',
                'Bank Transfer': '🏦', 'Digital Wallet': '📱', 'Cheque': '📝',
            };
            return icons[method] || method;
        },

        /* ── Private helpers ── */
        _todayStr() { return new Date().toISOString().slice(0, 10); },
        _thisMonthPrefix() {
            var n = new Date();
            return n.getFullYear() + '-' + String(n.getMonth() + 1).padStart(2, '0');
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\expense-tracker.blade.php ENDPATH**/ ?>