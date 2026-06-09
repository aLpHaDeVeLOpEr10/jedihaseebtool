<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── 3D Coin ── */
.coin-viewport { perspective: 700px; display:flex; align-items:center; justify-content:center; height:200px; }
.coin-3d       { width:180px; height:180px; position:relative; transform-style:preserve-3d; }
.coin-face     { position:absolute; width:100%; height:100%; border-radius:50%;
                 backface-visibility:hidden; -webkit-backface-visibility:hidden;
                 display:flex; align-items:center; justify-content:center; overflow:hidden; }
.coin-heads-f  { background:radial-gradient(circle at 35% 30%,#fff9c4,#f5c518 45%,#c8860a 90%);
                 box-shadow:inset 0 -5px 10px rgba(0,0,0,.2),0 8px 24px rgba(200,134,10,.4); }
.coin-tails-f  { background:radial-gradient(circle at 35% 30%,#ffffff,#d0d0d0 45%,#808080 90%);
                 box-shadow:inset 0 -5px 10px rgba(0,0,0,.2),0 8px 24px rgba(100,100,100,.3);
                 transform:rotateY(180deg); }

/* ── Flip keyframes (4 combos) ── */
@keyframes flip_h_h { from{transform:rotateY(0deg)}   to{transform:rotateY(1440deg)} }
@keyframes flip_h_t { from{transform:rotateY(0deg)}   to{transform:rotateY(1260deg)} }
@keyframes flip_t_h { from{transform:rotateY(180deg)} to{transform:rotateY(1440deg)} }
@keyframes flip_t_t { from{transform:rotateY(180deg)} to{transform:rotateY(1620deg)} }
/* 1260 mod 360 = 180 → tails ✓ | 1440 mod 360 = 0 → heads ✓ | 1620 mod 360 = 180 → tails ✓ */

/* ── Mini coin (multi-flip) ── */
.mini-coin   { width:52px;height:52px;border-radius:50%;display:flex;align-items:center;
               justify-content:center;font-weight:900;font-size:15px;
               box-shadow:0 3px 8px rgba(0,0,0,.15),inset 0 -2px 4px rgba(0,0,0,.1); }
.mini-heads  { background:radial-gradient(circle at 35% 30%,#fff9c4,#f5c518 40%,#c8860a 90%); color:#7a4d00; }
.mini-tails  { background:radial-gradient(circle at 35% 30%,#ffffff,#d0d0d0 40%,#808080 90%); color:#333; }

@keyframes miniSpin { to{transform:rotateY(360deg)} }
.mini-spin  { animation:miniSpin .45s linear infinite; background:linear-gradient(135deg,#f5c518,#c8c8c8); }

@keyframes chipIn { from{transform:scale(0) rotate(-15deg);opacity:0} to{transform:scale(1) rotate(0);opacity:1} }
.chip-in { animation:chipIn .35s cubic-bezier(.34,1.56,.64,1) both; }
</style>

<div class="min-h-screen bg-gray-50" x-data="coinFlip()" x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 space-y-5">

        
        <div class="card p-6">

            
            <div class="flex flex-wrap gap-5 justify-between mb-6">
                <div>
                    <p class="form-label">Number of Coins</p>
                    <div class="flex gap-1.5">
                        <template x-for="n in [1,2,3,5,10]" :key="n">
                            <button type="button"
                                    @click="numCoins = n; results = []"
                                    class="w-10 h-10 rounded-xl border-2 text-sm font-bold transition-all"
                                    :class="numCoins === n
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="n">
                            </button>
                        </template>
                    </div>
                </div>

                <div>
                    <p class="form-label">Speed</p>
                    <div class="flex gap-1.5">
                        <template x-for="sp in speeds" :key="sp.value">
                            <button type="button"
                                    @click="speed = sp.value"
                                    class="px-3 h-10 rounded-xl border-2 text-sm font-semibold transition-all"
                                    :class="speed === sp.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="sp.label">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            
            <div x-show="numCoins === 1" class="flex flex-col items-center gap-5">

                <div class="coin-viewport">
                    <div class="coin-3d" x-ref="coinEl">
                        
                        <div class="coin-face coin-heads-f">
                            <svg viewBox="0 0 180 180" width="178" height="178">
                                <defs>
                                    <radialGradient id="hg" cx="35%" cy="30%">
                                        <stop offset="0%"  stop-color="#fff9c4"/>
                                        <stop offset="45%" stop-color="#f5c518"/>
                                        <stop offset="100%" stop-color="#c8860a"/>
                                    </radialGradient>
                                </defs>
                                <circle cx="89" cy="89" r="87" fill="url(#hg)"/>
                                <circle cx="89" cy="89" r="78" fill="none" stroke="#e6a817" stroke-width="1.5" stroke-dasharray="6 3" opacity=".6"/>
                                <text x="89" y="92" text-anchor="middle" dominant-baseline="middle" font-size="54">👑</text>
                                <text x="89" y="148" text-anchor="middle" dominant-baseline="middle" font-size="11" font-weight="700" fill="#7a4d00" letter-spacing="5" font-family="Georgia,serif">HEADS</text>
                            </svg>
                        </div>
                        
                        <div class="coin-face coin-tails-f">
                            <svg viewBox="0 0 180 180" width="178" height="178">
                                <defs>
                                    <radialGradient id="tg" cx="35%" cy="30%">
                                        <stop offset="0%"  stop-color="#ffffff"/>
                                        <stop offset="45%" stop-color="#d0d0d0"/>
                                        <stop offset="100%" stop-color="#787878"/>
                                    </radialGradient>
                                </defs>
                                <circle cx="89" cy="89" r="87" fill="url(#tg)"/>
                                <circle cx="89" cy="89" r="78" fill="none" stroke="#aaaaaa" stroke-width="1.5" stroke-dasharray="6 3" opacity=".6"/>
                                <text x="89" y="92" text-anchor="middle" dominant-baseline="middle" font-size="54">⭐</text>
                                <text x="89" y="148" text-anchor="middle" dominant-baseline="middle" font-size="11" font-weight="700" fill="#555" letter-spacing="5" font-family="Georgia,serif">TAILS</text>
                            </svg>
                        </div>
                    </div>
                </div>

                
                <div class="h-16 flex items-center justify-center">
                    <div x-show="results.length > 0 && !isFlipping" x-transition
                         class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-black text-2xl shadow-sm"
                         :class="results[0]==='heads'
                             ? 'bg-amber-50 border-2 border-amber-200 text-amber-700'
                             : 'bg-gray-100 border-2 border-gray-200 text-gray-600'">
                        <span x-text="results[0]==='heads' ? '👑' : '⭐'"></span>
                        <span x-text="results[0]==='heads' ? 'HEADS!' : 'TAILS!'"></span>
                    </div>
                    <p x-show="isFlipping" class="text-brand-600 font-semibold animate-pulse">Flipping…</p>
                    <p x-show="results.length===0 && !isFlipping" class="text-gray-400 text-sm">
                        Press the button below to flip
                    </p>
                </div>
            </div>

            
            <div x-show="numCoins > 1" class="py-2">

                
                <div x-show="results.length===0 && !isFlipping"
                     class="flex flex-wrap justify-center gap-3 py-5">
                    <template x-for="i in numCoins" :key="i">
                        <div class="w-[52px] h-[52px] rounded-full border-2 border-dashed border-gray-200
                                    flex items-center justify-center text-xl text-gray-300">🪙</div>
                    </template>
                </div>

                
                <div x-show="isFlipping" class="flex flex-wrap justify-center gap-3 py-5">
                    <template x-for="i in numCoins" :key="i">
                        <div class="mini-coin mini-spin" style="color:transparent">🪙</div>
                    </template>
                </div>

                
                <div x-show="results.length>0 && !isFlipping"
                     class="flex flex-wrap justify-center gap-4 py-3">
                    <template x-for="(r,i) in results" :key="i">
                        <div class="chip-in flex flex-col items-center gap-1.5"
                             :style="'animation-delay:'+(i*80)+'ms'">
                            <div class="mini-coin"
                                 :class="r==='heads' ? 'mini-heads' : 'mini-tails'"
                                 x-text="r==='heads' ? 'H' : 'T'"></div>
                            <span class="text-xs font-semibold"
                                  :class="r==='heads' ? 'text-amber-600' : 'text-gray-500'"
                                  x-text="r==='heads' ? 'Heads' : 'Tails'"></span>
                        </div>
                    </template>
                </div>

                
                <div x-show="results.length>0 && !isFlipping"
                     class="mt-4 flex items-center justify-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                        <span class="text-xl">👑</span>
                        <span class="font-black text-amber-700 text-xl" x-text="multiHeads"></span>
                        <span class="text-amber-600 text-sm font-medium">Heads</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl">
                        <span class="text-xl">⭐</span>
                        <span class="font-black text-gray-700 text-xl" x-text="multiTails"></span>
                        <span class="text-gray-500 text-sm font-medium">Tails</span>
                    </div>
                </div>
            </div>

            
            <div class="mt-5">
                <button type="button" @click="flip()" :disabled="isFlipping"
                        class="btn btn-primary w-full btn-lg">
                    <span x-show="isFlipping"
                          class="w-5 h-5 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                    <span x-show="!isFlipping" class="text-xl">🪙</span>
                    <span x-text="isFlipping
                        ? 'Flipping…'
                        : (results.length > 0 ? 'Flip Again' : 'Flip the Coin!')"></span>
                </button>
            </div>
        </div>

        
        <div x-show="stats.total > 0" x-transition class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Statistics</h3>
                <button type="button" @click="resetStats()"
                        class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                    Reset stats
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-black text-gray-800" x-text="stats.total"></p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Total Flips</p>
                </div>
                <div class="text-center p-3 bg-amber-50 rounded-xl">
                    <p class="text-2xl font-black text-amber-700" x-text="stats.heads"></p>
                    <p class="text-xs text-amber-500 mt-0.5 font-medium">Heads 👑</p>
                </div>
                <div class="text-center p-3 bg-gray-100 rounded-xl">
                    <p class="text-2xl font-black text-gray-600" x-text="stats.tails"></p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Tails ⭐</p>
                </div>
                <div class="text-center p-3 rounded-xl"
                     :class="stats.streakType==='heads' ? 'bg-amber-50' : 'bg-gray-100'">
                    <p class="text-2xl font-black"
                       :class="stats.streakType==='heads' ? 'text-amber-700' : 'text-gray-600'"
                       x-text="stats.currentStreak"></p>
                    <p class="text-xs mt-0.5 font-medium"
                       :class="stats.streakType==='heads' ? 'text-amber-500' : 'text-gray-400'"
                       x-text="'Streak ('+(stats.streakType==='heads'?'👑':'⭐')+')'"></p>
                </div>
            </div>

            
            <div>
                <div class="flex justify-between text-xs font-semibold mb-1.5">
                    <span class="text-amber-600">👑 Heads — <span x-text="headsPercent"></span>%</span>
                    <span class="text-gray-500">Tails — <span x-text="100 - headsPercent"></span>% ⭐</span>
                </div>
                <div class="h-3 rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-700"
                         :style="'width:'+headsPercent+'%'"></div>
                </div>
            </div>

            
            <div x-show="stats.longestStreak > 2" class="mt-4 flex items-center gap-1.5 text-sm text-gray-500">
                <svg class="w-4 h-4 text-brand-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Longest streak:
                <strong class="text-gray-700" x-text="stats.longestStreak"></strong>
                consecutive
                <span :class="stats.longestStreakType==='heads' ? 'text-amber-600 font-semibold' : 'text-gray-600 font-semibold'"
                      x-text="stats.longestStreakType==='heads' ? 'Heads 👑' : 'Tails ⭐'"></span>
            </div>
        </div>

        
        <div x-show="history.length > 0" x-transition class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-900">
                    Recent Flips
                    <span class="text-gray-400 font-normal text-sm"
                          x-text="'('+history.length+' shown)'"></span>
                </h3>
                <button type="button" @click="clearHistory()"
                        class="text-xs text-gray-400 hover:text-red-500 transition-colors">Clear</button>
            </div>
            <div class="flex flex-wrap gap-1.5">
                <template x-for="(h, i) in [...history].reverse()" :key="i">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center
                                text-xs font-black shadow-sm cursor-default select-none"
                         :title="h==='H' ? 'Heads' : 'Tails'"
                         :class="h==='H' ? 'bg-amber-400 text-amber-900' : 'bg-gray-300 text-gray-600'"
                         x-text="h"></div>
                </template>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-4">
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
function coinFlip() {
    return {
        /* ── Config ── */
        numCoins: 1,
        speed:    'normal',   /* 'normal' | 'fast' | 'instant' */

        /* ── State ── */
        results:    [],
        isFlipping: false,
        coinFace:   'heads',  /* tracks resting face of the single coin */

        /* ── Persistent ── */
        stats: {
            total:0, heads:0, tails:0,
            currentStreak:0, streakType:'heads',
            longestStreak:0, longestStreakType:'heads',
        },
        history: [],

        /* ── Options ── */
        speeds: [
            { value:'normal',  label:'Normal'  },
            { value:'fast',    label:'Fast'    },
            { value:'instant', label:'Instant' },
        ],

        /* ── Init ── */
        init() {
            try {
                var s = localStorage.getItem('cf_stats_v2');
                if (s) this.stats = JSON.parse(s);
                var h = localStorage.getItem('cf_history_v2');
                if (h) this.history = JSON.parse(h).slice(0, 60);
            } catch(e) {}
        },

        /* ── Computed ── */
        get headsPercent() {
            return this.stats.total ? Math.round(this.stats.heads / this.stats.total * 100) : 50;
        },
        get multiHeads() {
            return this.results.filter(function(r){ return r === 'heads'; }).length;
        },
        get multiTails() {
            return this.results.filter(function(r){ return r === 'tails'; }).length;
        },

        /* ════════ FLIP ENTRY POINT ════════ */
        flip() {
            if (this.isFlipping) return;
            this.numCoins === 1 ? this._flipSingle() : this._flipMultiple();
        },

        /* ── Single 3D coin ── */
        _flipSingle() {
            var self   = this;
            var coin   = this.$refs.coinEl;
            var result = Math.random() < 0.5 ? 'heads' : 'tails';
            var dur    = this.speed === 'fast' ? 0.65 : this.speed === 'instant' ? 0 : 1.3;

            this.isFlipping = true;

            /* Instant mode — no animation */
            if (dur === 0) {
                coin.style.animation = 'none';
                coin.style.transform = result === 'tails' ? 'rotateY(180deg)' : 'rotateY(0deg)';
                self.coinFace   = result;
                self.results    = [result];
                self.isFlipping = false;
                self._record([result]);
                return;
            }

            /* Pick animation based on current → new face */
            var from = self.coinFace === 'tails' ? 't' : 'h';
            var to   = result       === 'tails' ? 't' : 'h';

            /* Clear any previous animation so the new one fires cleanly */
            coin.style.animation = 'none';
            coin.offsetHeight;   /* force reflow */

            coin.style.animation = 'flip_' + from + '_' + to + ' ' +
                                   dur + 's cubic-bezier(.45,.05,.55,.95) forwards';

            setTimeout(function() {
                /* 1. Hold position via inline style */
                coin.style.animation = 'none';
                coin.style.transform = result === 'tails' ? 'rotateY(180deg)' : 'rotateY(0deg)';

                /* 2. Update Alpine state */
                self.coinFace   = result;
                self.results    = [result];
                self.isFlipping = false;
                self._record([result]);

                /* 3. Let Alpine class take over (clear inline override) */
                self.$nextTick(function() {
                    coin.style.transform = result === 'tails' ? 'rotateY(180deg)' : 'rotateY(0deg)';
                });
            }, Math.round(dur * 1000) + 60);
        },

        /* ── Multiple coins ── */
        _flipMultiple() {
            var self = this;
            this.isFlipping = true;
            this.results    = [];

            /* Pre-compute outcomes */
            var outcomes = [];
            for (var i = 0; i < this.numCoins; i++) {
                outcomes.push(Math.random() < 0.5 ? 'heads' : 'tails');
            }

            var spinMs = this.speed === 'instant' ? 0 : this.speed === 'fast' ? 250 : 500;
            var gapMs  = this.speed === 'instant' ? 0 : this.speed === 'fast' ? 70  : 120;

            setTimeout(function() {
                self.isFlipping = false;

                var reveal = function(idx) {
                    if (idx >= outcomes.length) {
                        self._record(outcomes);
                        return;
                    }
                    setTimeout(function() {
                        self.results.push(outcomes[idx]);
                        reveal(idx + 1);
                    }, idx === 0 ? 0 : gapMs);
                };
                reveal(0);
            }, spinMs);
        },

        /* ── Record results → stats + history ── */
        _record(outcomes) {
            var self = this;
            outcomes.forEach(function(r) {
                self.stats.total++;
                if (r === 'heads') self.stats.heads++; else self.stats.tails++;

                if (r === self.stats.streakType) {
                    self.stats.currentStreak++;
                } else {
                    self.stats.streakType    = r;
                    self.stats.currentStreak = 1;
                }
                if (self.stats.currentStreak > self.stats.longestStreak) {
                    self.stats.longestStreak     = self.stats.currentStreak;
                    self.stats.longestStreakType = self.stats.streakType;
                }

                self.history.push(r === 'heads' ? 'H' : 'T');
                if (self.history.length > 60) self.history.shift();
            });

            try {
                localStorage.setItem('cf_stats_v2',   JSON.stringify(self.stats));
                localStorage.setItem('cf_history_v2', JSON.stringify(self.history));
            } catch(e) {}
        },

        /* ── Reset / Clear ── */
        resetStats() {
            this.stats = {
                total:0, heads:0, tails:0,
                currentStreak:0, streakType:'heads',
                longestStreak:0, longestStreakType:'heads',
            };
            try { localStorage.removeItem('cf_stats_v2'); } catch(e) {}
        },
        clearHistory() {
            this.history = [];
            try { localStorage.removeItem('cf_history_v2'); } catch(e) {}
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\coin-flip.blade.php ENDPATH**/ ?>