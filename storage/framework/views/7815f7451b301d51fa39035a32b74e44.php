<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Wheel canvas wrapper ── */
.wheel-wrap { position:relative; display:flex; justify-content:center; }
.wheel-wrap canvas { display:block; max-width:100%; height:auto; border-radius:50%; }

/* ── Spin button pulse while spinning ── */
@keyframes spinPulse {
    0%,100%{ box-shadow:0 0 0 0 rgba(79,70,229,.4); }
    50%     { box-shadow:0 0 0 12px rgba(79,70,229,0); }
}
.spinning-glow { animation:spinPulse 1s ease-in-out infinite; }

/* ── Winner result pop ── */
@keyframes resultPop {
    0%  { transform:scale(.6); opacity:0; }
    75% { transform:scale(1.05); }
    100%{ transform:scale(1);   opacity:1; }
}
.result-pop { animation:resultPop .4s cubic-bezier(.34,1.56,.64,1) both; }

/* ── Confetti particle ── */
@keyframes confettiFall {
    0%   { transform:translateY(-20px) rotate(0deg); opacity:1; }
    100% { transform:translateY(300px) rotate(720deg); opacity:0; }
}
.confetti-p {
    position:absolute;
    width:10px; height:10px;
    border-radius:2px;
    animation:confettiFall 1.5s ease-in forwards;
    pointer-events:none;
}

/* ── Entry list scroll ── */
.entry-list { max-height:380px; overflow-y:auto; }
.entry-list::-webkit-scrollbar{ width:4px; }
.entry-list::-webkit-scrollbar-track{ background:#f1f5f9; border-radius:4px; }
.entry-list::-webkit-scrollbar-thumb{ background:#cbd5e1; border-radius:4px; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="wheelOfFortune()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

            
            <div class="lg:col-span-3 space-y-4">

                
                <div class="card p-5">
                    <div class="wheel-wrap">
                        <canvas width="500" height="500"
                                x-ref="wheelCanvas"
                                class="cursor-pointer select-none"
                                style="max-width:480px;"
                                @click="canSpin && spin()">
                        </canvas>
                    </div>
                </div>

                
                <div class="card p-5">

                    
                    <div x-show="entries.length < 2" x-transition
                         class="mb-4 flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Add at least <strong class="mx-1">2 entries</strong> to spin the wheel.
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 items-center">

                        
                        <button type="button"
                                @click="spin()"
                                :disabled="!canSpin"
                                class="btn btn-primary btn-lg w-full sm:flex-1"
                                :class="isSpinning ? 'spinning-glow' : ''">
                            <span x-show="isSpinning"
                                  class="w-5 h-5 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                            <svg x-show="!isSpinning" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-text="isSpinning ? 'Spinning…' : '🎡 Spin the Wheel!'"></span>
                        </button>

                        
                        <div class="flex gap-1 flex-shrink-0">
                            <template x-for="sp in speeds" :key="sp.value">
                                <button type="button" @click="spinSpeed = sp.value"
                                        class="px-3 h-10 rounded-xl border-2 text-xs font-semibold transition-all"
                                        :class="spinSpeed === sp.value
                                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'"
                                        x-text="sp.label">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                
                <div x-show="showResult" x-transition class="card overflow-hidden">
                    <div class="result-pop p-6 flex flex-col sm:flex-row items-center gap-5"
                         :style="winner ? 'background: linear-gradient(135deg, '+winnerBg+', white 60%)' : ''">

                        
                        <div class="flex-shrink-0 w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm"
                             :style="winner ? 'background:'+winnerColor+';' : ''">
                            🎉
                        </div>

                        <div class="flex-1 text-center sm:text-left">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Winner</p>
                            <p class="text-2xl font-black text-gray-900 leading-tight"
                               x-text="winner ? winner.label : ''"></p>
                            <p x-show="winner && removeWinner" class="text-xs text-amber-600 mt-1 font-medium">
                                ✓ Entry removed from wheel
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <button type="button" @click="spin()" :disabled="!canSpin"
                                    class="btn btn-primary btn-sm">
                                🎡 Spin Again
                            </button>
                            <button type="button" @click="copyResult()"
                                    class="btn btn-secondary btn-sm"
                                    :class="copiedResult ? 'text-emerald-600' : ''">
                                <svg x-show="!copiedResult" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <svg x-show="copiedResult" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedResult ? 'Copied!' : 'Copy Result'"></span>
                            </button>
                        </div>
                    </div>

                    
                    <div x-ref="confettiBox" class="relative h-0 overflow-visible pointer-events-none"></div>
                </div>

                
                <div x-show="history.length > 0" x-transition class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">
                            Spin History
                            <span class="text-gray-400 font-normal text-sm" x-text="'('+history.length+')'"></span>
                        </h3>
                        <button type="button" @click="history = []"
                                class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                            Clear
                        </button>
                    </div>
                    <div class="space-y-1.5">
                        <template x-for="(h, i) in history" :key="i">
                            <div class="flex items-center gap-2.5 py-1">
                                <span class="flex-shrink-0 w-5 h-5 rounded-full text-white text-xs font-bold flex items-center justify-center"
                                      :style="'background:'+h.color"></span>
                                <span class="text-sm text-gray-800 font-medium flex-1 truncate" x-text="h.label"></span>
                                <span class="text-xs text-gray-400 flex-shrink-0" x-text="h.time"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            
            <div class="lg:col-span-2 space-y-4">

                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Add Entry</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Entry Label</label>
                            <input type="text"
                                   x-model="newLabel"
                                   @keydown.enter="addEntry()"
                                   placeholder="e.g. Grand Prize, John, Option A…"
                                   maxlength="50"
                                   class="form-input"
                                   :class="newLabelError ? 'border-red-400' : ''">
                            <p x-show="newLabelError" class="form-error" x-text="newLabelError"></p>
                        </div>

                        <div>
                            <label class="form-label">
                                Weight / Probability
                                <span class="font-normal text-gray-400">(1 = normal, higher = more likely)</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        @click="newWeight = Math.max(1, newWeight - 1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold hover:border-brand-400 hover:text-brand-600 transition-all">−</button>
                                <input type="number" min="1" max="20"
                                       x-model.number="newWeight"
                                       class="form-input text-center font-bold w-full">
                                <button type="button"
                                        @click="newWeight = Math.min(20, newWeight + 1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-600 font-bold hover:border-brand-400 hover:text-brand-600 transition-all">+</button>
                            </div>
                        </div>

                        <button type="button" @click="addEntry()"
                                class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Entry
                        </button>
                    </div>

                    
                    <div class="mt-4">
                        <button type="button" @click="showBulk = !showBulk"
                                class="flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-brand-600 transition-colors">
                            <svg class="w-3.5 h-3.5 transition-transform" :class="showBulk ? 'rotate-90' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Bulk import (one entry per line)
                        </button>
                        <div x-show="showBulk" x-transition class="mt-2 space-y-2">
                            <textarea x-model="bulkText" rows="4"
                                      placeholder="Apple&#10;Banana&#10;Cherry&#10;…"
                                      class="form-input resize-none text-sm"></textarea>
                            <button type="button" @click="importBulk()"
                                    class="btn btn-secondary btn-sm w-full">Import Lines</button>
                        </div>
                    </div>
                </div>

                
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">
                            Entries
                            <span class="ml-1 badge badge-primary" x-text="entries.length"></span>
                        </h3>
                        <div class="flex gap-2">
                            <button type="button" @click="shuffle()"
                                    :disabled="entries.length < 2"
                                    class="btn btn-secondary btn-sm">
                                🔀 Shuffle
                            </button>
                            <button type="button" @click="clearAll()"
                                    :disabled="entries.length === 0"
                                    class="btn btn-secondary btn-sm text-red-500 hover:text-red-700">
                                🗑 Clear
                            </button>
                        </div>
                    </div>

                    
                    <div x-show="entries.length === 0" class="py-8 text-center text-gray-400">
                        <div class="text-4xl mb-2">🎡</div>
                        <p class="text-sm">No entries yet. Add some above!</p>
                    </div>

                    
                    <div x-show="entries.length > 0" class="entry-list space-y-1.5">
                        <template x-for="(entry, idx) in entries" :key="entry.id">
                            <div>
                                
                                <div x-show="editingId !== entry.id"
                                     class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-gray-50 group transition-colors">
                                    
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full shadow-sm"
                                          :style="'background:'+COLORS[idx % COLORS.length]"></span>

                                    
                                    <span class="flex-1 text-sm font-medium text-gray-800 truncate"
                                          x-text="entry.label"></span>

                                    
                                    <span class="text-xs text-gray-400 flex-shrink-0"
                                          x-text="probPercent(entry) + '%'"></span>

                                    
                                    <span x-show="entry.weight > 1"
                                          class="flex-shrink-0 px-1.5 py-0.5 rounded-lg bg-brand-100 text-brand-700 text-xs font-bold"
                                          x-text="'×'+entry.weight"></span>

                                    
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                        <button type="button" @click="startEdit(entry)"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-all"
                                                title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="removeEntry(entry.id)"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
                                                title="Remove">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                
                                <div x-show="editingId === entry.id"
                                     class="flex items-center gap-2 p-2 rounded-xl bg-brand-50 border border-brand-200">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full"
                                          :style="'background:'+COLORS[idx % COLORS.length]"></span>
                                    <input type="text" x-model="editLabel"
                                           @keydown.enter="saveEdit()"
                                           @keydown.escape="editingId = null"
                                           class="form-input py-1 text-sm flex-1 min-w-0"
                                           maxlength="50">
                                    <input type="number" x-model.number="editWeight"
                                           min="1" max="20"
                                           class="form-input py-1 text-sm text-center w-16 flex-shrink-0">
                                    <button type="button" @click="saveEdit()"
                                            class="btn btn-primary btn-sm flex-shrink-0">✓</button>
                                    <button type="button" @click="editingId = null"
                                            class="btn btn-secondary btn-sm flex-shrink-0">✕</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    
                    <div x-show="entries.length > 0"
                         class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span x-text="entries.length + ' entr' + (entries.length === 1 ? 'y' : 'ies')"></span>
                        <span x-text="'Total weight: ' + totalWeight"></span>
                    </div>
                </div>

                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Settings</h3>
                    <div class="space-y-3">

                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Remove winner after spin</p>
                                <p class="text-xs text-gray-400">Entry is deleted once selected</p>
                            </div>
                            <button type="button" @click="removeWinner = !removeWinner"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="removeWinner ? 'bg-brand-600' : 'bg-gray-200'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                                      :class="removeWinner ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                        </div>

                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Confetti on win</p>
                                <p class="text-xs text-gray-400">Show celebration particles</p>
                            </div>
                            <button type="button" @click="showConfetti = !showConfetti"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                    :class="showConfetti ? 'bg-brand-600' : 'bg-gray-200'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                                      :class="showConfetti ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        
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
/* ─────────────────────────────────────────────────────
   CONSTANTS
───────────────────────────────────────────────────── */
var WHEEL_COLORS = [
    '#4f46e5','#f59e0b','#10b981','#ef4444',
    '#8b5cf6','#06b6d4','#f97316','#ec4899',
    '#84cc16','#14b8a6','#a855f7','#64748b',
    '#dc2626','#0891b2','#65a30d','#db2777',
];

/* ─────────────────────────────────────────────────────
   ALPINE COMPONENT
───────────────────────────────────────────────────── */
function wheelOfFortune() {
    return {
        /* ── Entries ── */
        entries: [
            { id:1, label:'🎁 Grand Prize',   weight:1 },
            { id:2, label:'⭐ Double Points', weight:2 },
            { id:3, label:'🍕 Free Lunch',    weight:1 },
            { id:4, label:'🎉 Bonus Round',   weight:3 },
            { id:5, label:'💰 Gift Card',     weight:1 },
            { id:6, label:'🏆 Trophy',        weight:1 },
        ],
        COLORS: WHEEL_COLORS,
        _nextId: 100,

        /* ── Form ── */
        newLabel:     '',
        newWeight:    1,
        newLabelError:'',
        editingId:    null,
        editLabel:    '',
        editWeight:   1,
        showBulk:     false,
        bulkText:     '',

        /* ── Spin state ── */
        currentAngle: 0,
        isSpinning:   false,
        winner:       null,
        winnerColor:  '#4f46e5',
        winnerBg:     '#eef2ff',
        showResult:   false,
        copiedResult: false,
        _cpTimer:     null,

        /* ── Settings ── */
        removeWinner: false,
        showConfetti: true,
        spinSpeed:    'normal',

        /* ── History ── */
        history: [],

        /* ── Options ── */
        speeds: [
            { value:'fast',   label:'Fast'   },
            { value:'normal', label:'Normal' },
            { value:'slow',   label:'Slow'   },
        ],

        /* ══════ INIT ══════ */
        init() {
            var self = this;
            this.$nextTick(function() { self._draw(); });
        },

        /* ══════ COMPUTED ══════ */

        get canSpin() {
            return this.entries.length >= 2 && !this.isSpinning;
        },

        get totalWeight() {
            return this.entries.reduce(function(s,e){ return s + e.weight; }, 0);
        },

        probPercent(entry) {
            if (!this.totalWeight) return 0;
            return Math.round(entry.weight / this.totalWeight * 100);
        },

        /* ══════ ENTRY MANAGEMENT ══════ */

        addEntry() {
            this.newLabelError = '';
            var label = this.newLabel.trim();
            if (!label) { this.newLabelError = 'Entry label is required.'; return; }
            if (label.length > 50) { this.newLabelError = 'Max 50 characters.'; return; }
            this.entries.push({
                id:     ++this._nextId,
                label:  label,
                weight: Math.max(1, Math.min(20, this.newWeight || 1)),
            });
            this.newLabel  = '';
            this.newWeight = 1;
            this._draw();
        },

        removeEntry(id) {
            this.entries = this.entries.filter(function(e){ return e.id !== id; });
            if (this.editingId === id) this.editingId = null;
            this._draw();
        },

        startEdit(entry) {
            this.editingId  = entry.id;
            this.editLabel  = entry.label;
            this.editWeight = entry.weight;
        },

        saveEdit() {
            var label = this.editLabel.trim();
            if (!label) { this.editingId = null; return; }
            var self = this;
            this.entries = this.entries.map(function(e) {
                if (e.id !== self.editingId) return e;
                return { id:e.id, label:label, weight:Math.max(1,Math.min(20,self.editWeight||1)) };
            });
            this.editingId = null;
            this._draw();
        },

        shuffle() {
            var arr = this.entries.slice();
            for (var i = arr.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
            }
            this.entries = arr;
            this._draw();
        },

        clearAll() {
            if (!confirm('Clear all entries? This cannot be undone.')) return;
            this.entries   = [];
            this.showResult = false;
            this.winner    = null;
            this._draw();
        },

        importBulk() {
            var lines = this.bulkText.split('\n').map(function(l){ return l.trim(); }).filter(Boolean);
            var self  = this;
            lines.forEach(function(line) {
                if (line.length > 50) line = line.slice(0, 50);
                self.entries.push({ id: ++self._nextId, label: line, weight: 1 });
            });
            this.bulkText = '';
            this.showBulk = false;
            this._draw();
        },

        /* ══════ SPIN ══════ */

        spin() {
            if (!this.canSpin) return;

            this.isSpinning = true;
            this.showResult  = false;
            this.winner      = null;

            /* 1. Pick winner (weighted) */
            var total = this.totalWeight;
            var rand  = Math.random() * total;
            var cum   = 0;
            var winnerIdx = this.entries.length - 1;
            for (var i = 0; i < this.entries.length; i++) {
                cum += this.entries[i].weight;
                if (rand < cum) { winnerIdx = i; break; }
            }

            /* 2. Compute target angle */
            var twoPI    = 2 * Math.PI;
            var cumAngle = 0;
            for (var j = 0; j < winnerIdx; j++) {
                cumAngle += this.entries[j].weight / total;
            }
            /* Winner midpoint in [0,1] fraction → convert to radians */
            var winnerMid = (cumAngle + this.entries[winnerIdx].weight / (2 * total)) * twoPI;

            /* We want (-targetAngle) mod 2π == winnerMid */
            /* targetAngle = -winnerMid + k*2π, k large enough for 5+ rotations */
            var extraRot  = 5 + Math.floor(Math.random() * 4);  /* 5–8 full rotations */
            var k         = Math.ceil((this.currentAngle + winnerMid) / twoPI) + extraRot;
            var targetAngle = -winnerMid + k * twoPI;

            /* 3. Animate */
            this._animate(this.currentAngle, targetAngle, winnerIdx);
        },

        _animate(startAngle, targetAngle, winnerIdx) {
            var self      = this;
            var dur       = self.spinSpeed === 'fast' ? 2800 : self.spinSpeed === 'slow' ? 6500 : 4500;
            var delta     = targetAngle - startAngle;
            var startTime = null;

            /* Cubic ease-out */
            var ease = function(t) { return 1 - Math.pow(1 - t, 3); };

            var frame = function(now) {
                if (!startTime) startTime = now;
                var t      = Math.min((now - startTime) / dur, 1);
                var eased  = ease(t);

                self.currentAngle = startAngle + delta * eased;
                self._draw();

                if (t < 1) {
                    requestAnimationFrame(frame);
                } else {
                    /* Spin complete */
                    self.currentAngle = targetAngle;
                    self._draw();
                    self.isSpinning   = false;

                    var won = self.entries[winnerIdx];
                    self.winnerColor  = WHEEL_COLORS[winnerIdx % WHEEL_COLORS.length];
                    self.winnerBg     = self._lightenHex(self.winnerColor, 0.93);
                    self.winner       = { id: won.id, label: won.label };
                    self.showResult   = true;

                    self.history.unshift({
                        label: won.label,
                        color: self.winnerColor,
                        time:  new Date().toLocaleTimeString(),
                    });
                    if (self.history.length > 20) self.history.pop();

                    if (self.showConfetti) self._burst();

                    if (self.removeWinner) {
                        self.entries.splice(winnerIdx, 1);
                        self.entries = self.entries.slice(); /* force reactivity */
                        self._draw();
                    }
                }
            };

            requestAnimationFrame(frame);
        },

        /* ══════ CANVAS DRAW ══════ */

        _draw() {
            var canvas = this.$refs.wheelCanvas;
            if (!canvas) return;
            var ctx    = canvas.getContext('2d');
            var W      = canvas.width;   /* 500 */
            var H      = canvas.height;  /* 500 */
            var cx     = W / 2;
            var cy     = H / 2;
            var radius = cx - 12;        /* 238 */

            ctx.clearRect(0, 0, W, H);

            /* ── Empty state ── */
            if (this.entries.length === 0) {
                ctx.beginPath();
                ctx.arc(cx, cy, radius, 0, 2 * Math.PI);
                ctx.fillStyle = '#f3f4f6';
                ctx.fill();
                ctx.strokeStyle = '#e5e7eb';
                ctx.lineWidth = 4;
                ctx.stroke();
                ctx.fillStyle = '#9ca3af';
                ctx.font = 'bold 15px Inter,ui-sans-serif,sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Add entries to spin!', cx, cy);
                this._drawPointer(ctx, cx);
                return;
            }

            /* ── Sectors ── */
            var total     = this.totalWeight;
            var startAngle = this.currentAngle - Math.PI / 2;  /* 0 = top */

            for (var i = 0; i < this.entries.length; i++) {
                var entry      = this.entries[i];
                var sliceAngle = (entry.weight / total) * 2 * Math.PI;
                var endAngle   = startAngle + sliceAngle;
                var color      = WHEEL_COLORS[i % WHEEL_COLORS.length];

                /* Fill */
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.arc(cx, cy, radius, startAngle, endAngle);
                ctx.closePath();
                ctx.fillStyle = color;
                ctx.fill();

                /* Divider line */
                ctx.strokeStyle = 'rgba(255,255,255,0.75)';
                ctx.lineWidth   = 2;
                ctx.stroke();

                /* Label text */
                if (sliceAngle > 0.1) {
                    var midAngle   = startAngle + sliceAngle / 2;
                    var textR      = radius * 0.62;
                    var tx         = cx + textR * Math.cos(midAngle);
                    var ty         = cy + textR * Math.sin(midAngle);

                    ctx.save();
                    ctx.translate(tx, ty);
                    ctx.rotate(midAngle + Math.PI / 2);

                    var fontSize   = Math.min(14, Math.max(9, Math.floor(sliceAngle * 20)));
                    ctx.font       = 'bold ' + fontSize + 'px Inter,ui-sans-serif,sans-serif';
                    ctx.fillStyle  = 'rgba(255,255,255,0.96)';
                    ctx.textAlign  = 'center';
                    ctx.textBaseline = 'middle';

                    var maxChars   = Math.max(3, Math.floor(sliceAngle * 11));
                    var lbl        = entry.label.length > maxChars
                                   ? entry.label.slice(0, maxChars) + '…'
                                   : entry.label;

                    /* Drop shadow for readability */
                    ctx.shadowColor   = 'rgba(0,0,0,0.4)';
                    ctx.shadowBlur    = 3;
                    ctx.fillText(lbl, 0, 0);
                    ctx.shadowBlur    = 0;
                    ctx.restore();
                }

                startAngle = endAngle;
            }

            /* ── Outer ring ── */
            ctx.beginPath();
            ctx.arc(cx, cy, radius, 0, 2 * Math.PI);
            ctx.strokeStyle = '#d1d5db';
            ctx.lineWidth   = 5;
            ctx.stroke();

            /* ── Hub ── */
            ctx.beginPath();
            ctx.arc(cx, cy, 22, 0, 2 * Math.PI);
            ctx.fillStyle   = '#1f2937';
            ctx.fill();

            ctx.beginPath();
            ctx.arc(cx, cy, 13, 0, 2 * Math.PI);
            ctx.fillStyle   = '#ffffff';
            ctx.fill();

            ctx.beginPath();
            ctx.arc(cx, cy, 6, 0, 2 * Math.PI);
            ctx.fillStyle   = '#4f46e5';
            ctx.fill();

            /* ── Pointer (drawn on canvas, always on top) ── */
            this._drawPointer(ctx, cx);
        },

        _drawPointer(ctx, cx) {
            /* Downward-pointing triangle at top-center */
            var px  = cx;
            var py0 = 0;
            var py1 = 36;
            var pw  = 14;

            ctx.beginPath();
            ctx.moveTo(px - pw, py0);
            ctx.lineTo(px + pw, py0);
            ctx.lineTo(px,      py1);
            ctx.closePath();
            ctx.fillStyle = '#1f2937';
            ctx.fill();

            ctx.beginPath();
            ctx.moveTo(px - (pw - 5), py0 + 4);
            ctx.lineTo(px + (pw - 5), py0 + 4);
            ctx.lineTo(px,            py1 - 6);
            ctx.closePath();
            ctx.fillStyle = '#4f46e5';
            ctx.fill();
        },

        /* ══════ CONFETTI ══════ */

        _burst() {
            var box = this.$refs.confettiBox;
            if (!box) return;
            var colors = ['#4f46e5','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#06b6d4'];
            for (var i = 0; i < 30; i++) {
                (function(i) {
                    setTimeout(function() {
                        var p = document.createElement('div');
                        p.className = 'confetti-p';
                        p.style.left        = (20 + Math.random() * 60) + '%';
                        p.style.top         = '0px';
                        p.style.background  = colors[Math.floor(Math.random() * colors.length)];
                        p.style.transform   = 'rotate(' + (Math.random() * 360) + 'deg)';
                        p.style.animationDuration = (1 + Math.random() * 1) + 's';
                        p.style.animationDelay    = (Math.random() * 0.4) + 's';
                        box.appendChild(p);
                        setTimeout(function() { p.remove(); }, 2500);
                    }, i * 40);
                })(i);
            }
        },

        /* ══════ COPY RESULT ══════ */

        copyResult() {
            if (!this.winner) return;
            var self = this;
            clearTimeout(this._cpTimer);
            navigator.clipboard.writeText(this.winner.label).then(function() {
                self.copiedResult = true;
                self._cpTimer = setTimeout(function() { self.copiedResult = false; }, 2000);
            }).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = self.winner.label;
                ta.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                self.copiedResult = true;
                self._cpTimer = setTimeout(function() { self.copiedResult = false; }, 2000);
            });
        },

        /* ══════ COLOUR UTILS ══════ */

        _lightenHex(hex, amount) {
            var r = parseInt(hex.slice(1,3),16);
            var g = parseInt(hex.slice(3,5),16);
            var b = parseInt(hex.slice(5,7),16);
            r = Math.round(r + (255 - r) * amount);
            g = Math.round(g + (255 - g) * amount);
            b = Math.round(b + (255 - b) * amount);
            return 'rgba('+r+','+g+','+b+',0.25)';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\wheel-of-fortune.blade.php ENDPATH**/ ?>