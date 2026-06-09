<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Age Calculator  —  prefix: ag-
   Theme: Violet / Purple
══════════════════════════════════════════════ */

/* Hero */
.ag-hero-years  { font-size:clamp(3rem,7vw,5rem); font-weight:900; line-height:1; letter-spacing:-.04em; background:linear-gradient(135deg,#6d28d9 0%,#7c3aed 50%,#8b5cf6 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ag-hero-detail { font-size:clamp(1rem,2.5vw,1.35rem); font-weight:700; color:#6d28d9; }

/* Stat tile */
.ag-tile { background:#fff; border:1.5px solid #e2e8f0; border-radius:1.125rem; padding:.9rem .8rem; display:flex; flex-direction:column; align-items:center; gap:.25rem; text-align:center; transition:all .15s; }
.ag-tile:hover { border-color:#ddd6fe; box-shadow:0 4px 16px rgba(109,40,217,.08); transform:translateY(-1px); }
.ag-tile-icon { font-size:1.3rem; }
.ag-tile-lbl  { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.ag-tile-val  { font-size:1.15rem; font-weight:900; word-break:break-all; background:linear-gradient(135deg,#6d28d9,#8b5cf6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ag-tile-sub  { font-size:.62rem; color:#94a3b8; }

/* Live tick — separate colour so it pulses */
.ag-tile.live  { border-color:#ddd6fe; background:linear-gradient(135deg,#faf5ff,#ede9fe); }
.ag-tile.live .ag-tile-val { background:linear-gradient(135deg,#4c1d95,#6d28d9); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
@keyframes agPulse { 0%,100%{opacity:1} 50%{opacity:.6} }
.ag-live-dot { display:inline-block; width:6px; height:6px; border-radius:50%; background:#7c3aed; animation:agPulse 1.2s ease-in-out infinite; vertical-align:middle; margin-left:3px; }

/* Next birthday card */
.ag-bday { background:linear-gradient(135deg,#faf5ff,#ede9fe); border:1.5px solid #ddd6fe; border-radius:1.25rem; padding:1.1rem 1.25rem; }
.ag-bday.today { background:linear-gradient(135deg,#fdf4ff,#fae8ff); border-color:#c084fc; }

/* Zodiac / info pill */
.ag-info-pill { display:inline-flex; align-items:center; gap:.4rem; padding:.35rem .85rem; border-radius:9999px; font-size:.75rem; font-weight:600; background:#ede9fe; color:#6d28d9; }

/* Milestone row */
.ag-ms-row { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:.5rem .75rem; border-radius:.625rem; }
.ag-ms-row:hover { background:#faf5ff; }
.ag-ms-past   { opacity:.55; }
.ag-ms-future { font-weight:700; }
.ag-ms-badge  { font-size:.62rem; font-weight:700; padding:.15rem .55rem; border-radius:9999px; white-space:nowrap; }
.ag-ms-past-badge    { background:#d1fae5; color:#065f46; }
.ag-ms-future-badge  { background:#ede9fe; color:#6d28d9; }
.ag-ms-next-badge    { background:#fef3c7; color:#92400e; }

/* Fun fact row */
.ag-ff-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem; padding:.45rem .75rem; border-radius:.5rem; font-size:.82rem; }
.ag-ff-row:hover { background:#faf5ff; }

/* Progress bar for next birthday */
.ag-bday-prog { height:6px; border-radius:9999px; background:#e9d5ff; overflow:hidden; }
.ag-bday-prog-fill { height:100%; border-radius:9999px; background:linear-gradient(90deg,#7c3aed,#8b5cf6); transition:width .6s ease; }

/* Date input prefix wrapper */
.ag-pre-wrap { display:flex; align-items:stretch; }
.ag-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.85rem; font-weight:600; color:#374151; white-space:nowrap; }
.ag-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Shimmer */
@keyframes agShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.ag-shim { height:5rem; border-radius:1.125rem; background:linear-gradient(90deg,#faf5ff 25%,#ede9fe 50%,#faf5ff 75%); background-size:1200px 100%; animation:agShim 1.4s infinite; }

@keyframes agIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.ag-in { animation:agIn .3s ease-out; }

/* Birthday confetti burst */
@keyframes agBurst { 0%{transform:scale(0) rotate(-10deg);opacity:0} 60%{transform:scale(1.15) rotate(5deg);opacity:1} 100%{transform:scale(1) rotate(0);opacity:1} }
.ag-birthday-emoji { display:inline-block; animation:agBurst .5s ease-out; }
</style>

<div class="min-h-screen bg-gray-50" x-data="ageCalc()" x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3"><?php echo e($tool->icon); ?> <?php echo e($tool->name); ?></h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">Calculate your <strong>exact age</strong> in years, months and days — plus total hours, minutes, seconds, next birthday countdown, zodiac sign, and more fun facts.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-primary">Free</span>
                    <span class="badge badge-gray">Exact Age</span>
                    <span class="badge" style="background:#ede9fe;color:#6d28d9">Live Seconds</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="px-5 pt-5 pb-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Enter Dates</p>
                    </div>
                    <div class="px-5 pb-5 space-y-4">

                        
                        <div>
                            <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" x-model="dob"
                                   :max="asOfDate || todayStr"
                                   @change="autoCalc()"
                                   class="form-input"
                                   placeholder="YYYY-MM-DD">
                            <p class="form-help">Enter your date of birth (required).</p>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Calculate Age As Of</label>
                                <button type="button" @click="setToday()" class="btn btn-secondary btn-sm">
                                    📅 Today
                                </button>
                            </div>
                            <input type="date" x-model="asOfDate"
                                   :min="dob || ''"
                                   @change="autoCalc()"
                                   class="form-input">
                            <p class="form-help">Defaults to today. Change to calculate a future or past age.</p>
                        </div>

                        
                        <div x-show="error" x-transition class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span x-text="error"></span>
                        </div>

                        
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn flex-1 sm:flex-none btn-lg font-bold" style="background:linear-gradient(135deg,#6d28d9,#7c3aed);color:white;border:none;box-shadow:0 4px 14px rgba(109,40,217,.3)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Calculate Age
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>

                        
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-2">Quick fill</p>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="q in quickFills" :key="q.label">
                                    <button type="button" @click="fillDate(q.dob)" class="text-xs px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 hover:bg-violet-100 hover:text-violet-700 transition-colors font-medium" x-text="q.label"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card mt-4 p-4" style="background:linear-gradient(135deg,#faf5ff,#ede9fe);border-color:#ddd6fe">
                    <p class="text-sm font-semibold text-violet-700 mb-2">⏱ How Age Is Calculated</p>
                    <div class="space-y-1 text-xs text-gray-600 leading-relaxed">
                        <p>• <strong>Years</strong>: full calendar years from DOB to target date.</p>
                        <p>• <strong>Months &amp; days</strong>: remaining after full years (borrows days from prev month when needed).</p>
                        <p>• <strong>Total days</strong>: exact millisecond difference ÷ 86 400 000.</p>
                        <p>• <strong>Live seconds</strong> counts up in real time when calculating to today.</p>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-3 space-y-4" id="ag-results">

                
                <div x-show="phase==='loading'" class="space-y-3">
                    <div class="ag-shim" style="height:9rem"></div>
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="i in 6" :key="i"><div class="ag-shim"></div></template>
                    </div>
                </div>

                
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 ag-in">

                        
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#faf5ff 0%,#ede9fe 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-violet-500 uppercase tracking-widest mb-2"
                                   x-text="result.asOfIsToday ? 'Your Age Today' : 'Age on ' + result.asOfFormatted"></p>

                                
                                <div x-show="result.isBirthdayToday" class="mb-3 flex items-center gap-2 px-4 py-2.5 rounded-xl" style="background:#f3e8ff;border:1.5px solid #c084fc">
                                    <span class="ag-birthday-emoji text-2xl">🎉</span>
                                    <span class="font-bold text-violet-700">Happy Birthday! Today is your special day!</span>
                                    <span class="ag-birthday-emoji text-2xl">🎂</span>
                                </div>

                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                    <span class="ag-hero-years" x-text="result.years"></span>
                                    <span class="ag-hero-detail">yrs</span>
                                    <span class="ag-hero-years" x-text="result.months" style="font-size:clamp(2rem,5vw,3.5rem)"></span>
                                    <span class="ag-hero-detail">mo</span>
                                    <span class="ag-hero-years" x-text="result.days" style="font-size:clamp(2rem,5vw,3.5rem)"></span>
                                    <span class="ag-hero-detail">d</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">
                                    Born <strong x-text="result.dobFormatted"></strong>
                                    <span x-show="!result.asOfIsToday">&nbsp;→ As of <strong x-text="result.asOfFormatted"></strong></span>
                                </p>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5">
                            <div class="ag-tile">
                                <div class="ag-tile-icon">📅</div>
                                <div class="ag-tile-val" x-text="fmt(result.totalMonths)"></div>
                                <div class="ag-tile-lbl">Months</div>
                            </div>
                            <div class="ag-tile">
                                <div class="ag-tile-icon">📆</div>
                                <div class="ag-tile-val" x-text="fmt(result.totalWeeks)"></div>
                                <div class="ag-tile-lbl">Weeks</div>
                            </div>
                            <div class="ag-tile">
                                <div class="ag-tile-icon">☀️</div>
                                <div class="ag-tile-val" x-text="fmt(result.totalDays)"></div>
                                <div class="ag-tile-lbl">Days</div>
                            </div>
                            <div class="ag-tile">
                                <div class="ag-tile-icon">🕐</div>
                                <div class="ag-tile-val" x-text="fmtBig(result.totalHours)"></div>
                                <div class="ag-tile-lbl">Hours</div>
                            </div>
                            <div class="ag-tile">
                                <div class="ag-tile-icon">⏱</div>
                                <div class="ag-tile-val" x-text="fmtBig(result.totalMinutes)"></div>
                                <div class="ag-tile-lbl">Minutes</div>
                            </div>
                            <div class="ag-tile live">
                                <div class="ag-tile-icon">⚡<span class="ag-live-dot" x-show="result.asOfIsToday"></span></div>
                                <div class="ag-tile-val" x-text="fmtBig(liveSecs)"></div>
                                <div class="ag-tile-lbl">Seconds</div>
                                <div class="ag-tile-sub" x-text="result.asOfIsToday ? 'live' : 'total'"></div>
                            </div>
                        </div>

                        
                        <div class="ag-bday" :class="{today: result.isBirthdayToday}">
                            <div class="flex items-start justify-between gap-3 flex-wrap">
                                <div>
                                    <p class="text-xs font-bold text-violet-500 uppercase tracking-widest mb-1">
                                        <span x-text="result.isBirthdayToday ? '🎂 Today is Your Birthday!' : '🎂 Next Birthday'"></span>
                                    </p>
                                    <p class="text-xl font-black text-violet-800" x-show="!result.isBirthdayToday">
                                        In <span x-text="result.daysUntilBirthday"></span> days
                                    </p>
                                    <p class="text-sm text-violet-600 mt-0.5" x-show="!result.isBirthdayToday" x-text="result.nextBirthdayFormatted + ' · Turns ' + (result.years + 1)"></p>
                                    <p class="text-xl font-black text-violet-800" x-show="result.isBirthdayToday" x-text="'Turning ' + result.years + ' today! 🎉'"></p>
                                </div>
                                <div class="text-right shrink-0" x-show="!result.isBirthdayToday">
                                    <p class="text-3xl font-black text-violet-700" x-text="result.daysUntilBirthday"></p>
                                    <p class="text-xs text-violet-500">days to go</p>
                                </div>
                            </div>
                            <div class="mt-3" x-show="!result.isBirthdayToday">
                                <div class="ag-bday-prog">
                                    <div class="ag-bday-prog-fill" :style="'width:'+result.birthdayProgressPct+'%'"></div>
                                </div>
                                <div class="flex justify-between text-xs text-violet-400 mt-1">
                                    <span x-text="'Last: ' + result.lastBirthdayFormatted"></span>
                                    <span x-text="result.birthdayProgressPct.toFixed(0) + '% of year passed'"></span>
                                    <span x-text="'Next: ' + result.nextBirthdayFormatted"></span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">📖 Born On</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5" x-text="result.dayEmoji"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="result.dayOfWeek"></p>
                                    <p class="text-xs text-gray-400">day of week</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5" x-text="result.zodiacEmoji"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="result.zodiacSign"></p>
                                    <p class="text-xs text-gray-400">zodiac sign</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5" x-text="result.chineseEmoji"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="result.chineseZodiac"></p>
                                    <p class="text-xs text-gray-400">Chinese zodiac</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5" x-text="result.seasonEmoji"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="result.season"></p>
                                    <p class="text-xs text-gray-400">birth season</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5" x-text="result.birthstoneEmoji"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="result.birthstone"></p>
                                    <p class="text-xs text-gray-400">birthstone</p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <p class="text-xl mb-0.5">🌍</p>
                                    <p class="text-sm font-bold text-gray-800" x-text="'Day #' + result.dayOfYear"></p>
                                    <p class="text-xs text-gray-400">of that year</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">🏆 Age Milestones</span>
                                <button type="button" @click="showMilestones=!showMilestones" class="btn btn-secondary btn-sm flex items-center gap-1">
                                    <span x-text="showMilestones ? 'Hide' : 'Show'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="{'-rotate-180':showMilestones}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <div x-show="showMilestones" x-transition class="p-4">
                                <template x-for="m in result.milestones" :key="m.age">
                                    <div class="ag-ms-row" :class="m.isPast ? 'ag-ms-past' : 'ag-ms-future'">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="text-lg" x-text="m.emoji"></span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="m.label"></p>
                                                <p class="text-xs text-gray-400" x-text="m.dateStr"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="text-sm text-gray-500" x-text="m.yearsAwayStr"></span>
                                            <span class="ag-ms-badge" :class="m.isNext ? 'ag-ms-next-badge' : (m.isPast ? 'ag-ms-past-badge' : 'ag-ms-future-badge')" x-text="m.isNext ? 'Next' : (m.isPast ? '✓ Past' : 'Upcoming')"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">🎲 Fun Facts</span>
                                <button type="button" @click="showFunFacts=!showFunFacts" class="btn btn-secondary btn-sm flex items-center gap-1">
                                    <span x-text="showFunFacts ? 'Hide' : 'Show'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="{'-rotate-180':showFunFacts}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <div x-show="showFunFacts" x-transition class="p-4 space-y-0.5">
                                <template x-for="f in result.funFacts" :key="f.label">
                                    <div class="ag-ff-row">
                                        <span class="flex items-center gap-2 text-gray-600"><span x-text="f.icon"></span><span x-text="f.label"></span></span>
                                        <span class="font-bold text-violet-700 text-right" x-text="f.value"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        
                        <div class="card p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Export:</span>
                                <button type="button" @click="copySummary()"
                                        class="btn btn-secondary btn-sm"
                                        :class="copyFlash ? 'bg-violet-50 text-violet-700' : ''"
                                        x-text="copyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                                <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                                <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                            </div>
                        </div>

                    </div>
                </template>

                
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        <?php $__currentLoopData = [
                            ['🎂','Exact Age','Years, months, and days with calendar-accurate precision'],
                            ['⚡','Live Seconds','Real-time counter that ticks every second'],
                            ['🎉','Birthday','Next birthday countdown with progress bar'],
                            ['🔮','Zodiac','Zodiac sign, Chinese zodiac, birthstone & season'],
                            ['🏆','Milestones','1st, 18th, 21st, 50th, 100th birthday and more'],
                            ['🎲','Fun Facts','Heart beats, breaths taken, nights slept & dog years'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card p-4 text-center hover:border-violet-200 transition-colors">
                            <p class="text-2xl mb-1.5"><?php echo e($icon); ?></p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo e($title); ?></p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($desc); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#faf5ff,#ede9fe);border-color:#ddd6fe">
                        <p class="text-sm font-semibold text-violet-700 mb-2">📐 Age Calculation Formula</p>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p>1. Start with full calendar years from DOB to target date.</p>
                            <p>2. Count remaining full months after the year boundary.</p>
                            <p>3. Count remaining days — borrowing from previous month if needed.</p>
                            <p>4. Total days = exact difference in milliseconds ÷ 86,400,000.</p>
                        </div>
                    </div>
                </div>

                <?php if($relatedTools->count()): ?>
                <div x-show="phase==='idle'">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.show', $related->slug)); ?>" class="card-hover p-4 flex items-center gap-3 no-underline">
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
function ageCalc() {
    return {
        /* ── Inputs ── */
        dob:       '',
        asOfDate:  '',
        todayStr:  '',

        /* ── UI state ── */
        phase:          'idle',
        error:          '',
        result:         null,
        liveSecs:       0,
        _ticker:        null,
        showMilestones: true,
        showFunFacts:   true,
        copyFlash:      false,

        /* ── Quick fills ── */
        quickFills: [
            { label: '20 years ago',  dob: '' },
            { label: '30 years ago',  dob: '' },
            { label: '50 years ago',  dob: '' },
            { label: '18 today',      dob: '' },
        ],

        /* ── Init ── */
        init() {
            var today    = new Date();
            this.todayStr  = this._fmt(today);
            this.asOfDate  = this.todayStr;

            /* Build quick fill DOBs */
            var self = this;
            function subtractYears(y) {
                var d = new Date(today);
                d.setFullYear(d.getFullYear() - y);
                return self._fmt(d);
            }
            this.quickFills[0].dob = subtractYears(20);
            this.quickFills[1].dob = subtractYears(30);
            this.quickFills[2].dob = subtractYears(50);
            this.quickFills[3].dob = subtractYears(18);
        },

        _fmt(d) {
            var y = d.getFullYear();
            var m = String(d.getMonth() + 1).padStart(2, '0');
            var dd = String(d.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + dd;
        },

        setToday() {
            var today = new Date();
            this.todayStr = this._fmt(today);
            this.asOfDate = this.todayStr;
            this.autoCalc();
        },

        fillDate(dobVal) {
            this.dob = dobVal;
            this.setToday();
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        autoCalc() {
            if (this.dob && this.asOfDate) {
                var self = this;
                setTimeout(function(){ self.calculate(); }, 0);
            }
        },

        calculate() {
            this.error = '';
            var self = this;
            try {
                var v = this._validate();
                this.phase = 'loading';
                if (this._ticker) { clearInterval(this._ticker); this._ticker = null; }

                setTimeout(function() {
                    try {
                        self.result   = self._compute(v);
                        self.liveSecs = self.result.totalSeconds;
                        self.phase    = 'done';
                        self._startTicker(v.dobTs, self.result.asOfIsToday);
                        if (window.innerWidth < 1024) {
                            var el = document.getElementById('ag-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth', block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = e.message || String(e);
                        self.phase = 'idle';
                    }
                }, 120);
            } catch(e) {
                this.error = e.message || String(e);
                this.phase = 'idle';
            }
        },

        _validate() {
            if (!this.dob) throw new Error('Please enter your date of birth.');
            if (!this.asOfDate) throw new Error('Please enter the "calculate as of" date.');

            var dob   = new Date(this.dob + 'T00:00:00');
            var asOf  = new Date(this.asOfDate + 'T00:00:00');

            if (isNaN(dob.getTime()))  throw new Error('Date of birth is not a valid date.');
            if (isNaN(asOf.getTime())) throw new Error('"Calculate as of" date is not valid.');

            var now   = new Date();
            now.setHours(0, 0, 0, 0);

            /* DOB cannot be in the future */
            if (dob > now) throw new Error('Date of birth cannot be in the future.');

            /* DOB year sanity */
            if (dob.getFullYear() < 1000) throw new Error('Year must be 1000 or later.');
            if (dob.getFullYear() > now.getFullYear()) throw new Error('Date of birth cannot be in the future.');

            /* asOf must be >= DOB */
            if (asOf < dob) throw new Error('"Calculate as of" date must be on or after the date of birth.');

            /* asOf cannot be more than 200 years in the future */
            var maxFuture = new Date();
            maxFuture.setFullYear(maxFuture.getFullYear() + 200);
            if (asOf > maxFuture) throw new Error('"Calculate as of" date is too far in the future.');

            return {
                dob:       dob,
                asOf:      asOf,
                dobTs:     dob.getTime(),
                asOfIsToday: this.asOfDate === this.todayStr,
            };
        },

        _compute(v) {
            var dob   = v.dob;
            var asOf  = v.asOf;
            var self  = this;

            /* ── Exact years / months / days ── */
            var years  = asOf.getFullYear() - dob.getFullYear();
            var months = asOf.getMonth()    - dob.getMonth();
            var days   = asOf.getDate()     - dob.getDate();

            if (days < 0) {
                months--;
                /* Days in previous month of asOf */
                var prevMonthDays = new Date(asOf.getFullYear(), asOf.getMonth(), 0).getDate();
                days += prevMonthDays;
            }
            if (months < 0) {
                years--;
                months += 12;
            }

            /* ── Total time units ── */
            var msPerDay    = 86400000;
            var totalMs     = asOf.getTime() - dob.getTime();
            var totalDays   = Math.floor(totalMs / msPerDay);
            var totalWeeks  = Math.floor(totalDays / 7);
            var totalMonths = years * 12 + months;
            var totalHours  = totalDays * 24;
            var totalMins   = totalHours * 60;
            var totalSecs   = totalMins * 60;

            /* ── Birthday info ── */
            var today = new Date(); today.setHours(0,0,0,0);
            var asOfNorm = new Date(asOf); asOfNorm.setHours(0,0,0,0);

            var isBirthdayToday = (
                asOf.getDate()    === dob.getDate() &&
                asOf.getMonth()   === dob.getMonth()
            );

            /* Next birthday */
            var nextBday = new Date(asOf.getFullYear(), dob.getMonth(), dob.getDate());
            if (nextBday <= asOf && !isBirthdayToday) {
                nextBday = new Date(asOf.getFullYear() + 1, dob.getMonth(), dob.getDate());
            } else if (isBirthdayToday) {
                nextBday = new Date(asOf.getFullYear() + 1, dob.getMonth(), dob.getDate());
            }

            /* Last birthday */
            var lastBday = new Date(asOf.getFullYear(), dob.getMonth(), dob.getDate());
            if (lastBday > asOf || isBirthdayToday) {
                lastBday = new Date(asOf.getFullYear() - 1, dob.getMonth(), dob.getDate());
            }

            var daysUntilBirthday   = Math.ceil((nextBday.getTime() - asOf.getTime()) / msPerDay);
            var daysSinceLastBday   = Math.floor((asOf.getTime() - lastBday.getTime()) / msPerDay);
            var yearTotalDays       = Math.ceil((nextBday.getTime() - lastBday.getTime()) / msPerDay);
            var birthdayProgressPct = Math.min(100, (daysSinceLastBday / yearTotalDays) * 100);

            /* ── Day of week born ── */
            var dayNames  = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var dayEmojis = ['☀️','🌙','🔥','💧','⚡','❤️','🌿'];
            var dobDay    = dob.getDay();
            var dayOfWeek = dayNames[dobDay];

            /* ── Zodiac sign ── */
            var zodiac = self._getZodiac(dob.getMonth() + 1, dob.getDate());

            /* ── Chinese zodiac ── */
            var chineseZodiac = self._getChineseZodiac(dob.getFullYear());

            /* ── Season ── */
            var season = self._getSeason(dob.getMonth() + 1);

            /* ── Birthstone ── */
            var birthstone = self._getBirthstone(dob.getMonth() + 1);

            /* ── Day of year ── */
            var startOfYear  = new Date(dob.getFullYear(), 0, 0);
            var dayOfYear    = Math.floor((dob.getTime() - startOfYear.getTime()) / msPerDay);

            /* ── Milestones ── */
            var milestoneAges = [
                { age:1,   label:'1st Birthday',   emoji:'🍼' },
                { age:5,   label:'5th Birthday',   emoji:'🎈' },
                { age:10,  label:'10th Birthday',  emoji:'🎒' },
                { age:13,  label:'13th Birthday',  emoji:'🎮' },
                { age:16,  label:'Sweet 16',        emoji:'🚗' },
                { age:18,  label:'18th Birthday',  emoji:'🎓' },
                { age:21,  label:'21st Birthday',  emoji:'🥂' },
                { age:25,  label:'Quarter Century', emoji:'🌟' },
                { age:30,  label:'30th Birthday',  emoji:'🎯' },
                { age:40,  label:'40th Birthday',  emoji:'🏆' },
                { age:50,  label:'50th Birthday',  emoji:'💎' },
                { age:60,  label:'60th Birthday',  emoji:'🧘' },
                { age:70,  label:'70th Birthday',  emoji:'🌺' },
                { age:80,  label:'80th Birthday',  emoji:'🌸' },
                { age:100, label:'Centennial',      emoji:'👑' },
            ];

            var nextMilestoneFound = false;
            var milestones = milestoneAges.map(function(m) {
                var mDate   = new Date(dob.getFullYear() + m.age, dob.getMonth(), dob.getDate());
                var isPast  = mDate <= asOf;
                var isToday = (mDate.getFullYear() === asOf.getFullYear() && mDate.getMonth() === asOf.getMonth() && mDate.getDate() === asOf.getDate());
                var isNext  = !isPast && !nextMilestoneFound;
                if (isNext) nextMilestoneFound = true;
                var diff = mDate.getFullYear() - asOf.getFullYear();
                var yearsAwayStr = isPast
                    ? (isToday ? 'Today!' : diff === 0 ? 'This year' : Math.abs(diff) + (Math.abs(diff) === 1 ? ' year ago' : ' years ago'))
                    : (diff === 0 ? 'This year' : 'In ' + diff + (diff === 1 ? ' year' : ' years'));
                return {
                    age:          m.age,
                    label:        m.label,
                    emoji:        m.emoji,
                    dateStr:      self._fmtDate(mDate),
                    isPast:       isPast && !isToday,
                    isNext:       isNext,
                    yearsAwayStr: yearsAwayStr,
                };
            });

            /* ── Fun facts ── */
            var heartBeats = totalDays * 24 * 60 * 70;
            var breaths    = totalDays * 24 * 60 * 16;
            var dogYears   = years * 7;
            var catYears   = years <= 1 ? 15 : years <= 2 ? 24 : 24 + (years - 2) * 4;
            var schoolDays = Math.max(0, Math.min(years - 5, 13)) * 180;

            var funFacts = [
                { icon:'❤️', label:'Estimated heartbeats',    value: self.fmtBig(heartBeats) },
                { icon:'🌬', label:'Estimated breaths taken', value: self.fmtBig(breaths) },
                { icon:'😴', label:'Nights slept (~)',         value: self.fmt(totalDays) },
                { icon:'🐕', label:'Age in dog years',         value: dogYears + ' yrs' },
                { icon:'🐈', label:'Age in cat years',         value: catYears + ' yrs' },
                { icon:'🌙', label:'Full moons witnessed (~)', value: self.fmt(Math.floor(totalDays / 29.53)) },
                { icon:'🌍', label:'Times Earth orbited Sun',  value: years + ' times' },
                { icon:'📚', label:'Approx. school days',      value: self.fmt(schoolDays) },
            ];

            /* ── Formatted dates ── */
            var dobFormatted  = self._fmtDate(dob);
            var asOfFormatted = self._fmtDate(asOf);

            return {
                years, months, days,
                totalMonths, totalWeeks, totalDays, totalHours,
                totalMinutes: totalMins, totalSeconds: totalSecs,
                dobFormatted, asOfFormatted, asOfIsToday: v.asOfIsToday,
                isBirthdayToday,
                daysUntilBirthday,
                nextBirthdayFormatted: self._fmtDate(nextBday),
                lastBirthdayFormatted: self._fmtDate(lastBday),
                birthdayProgressPct,
                dayOfWeek, dayEmoji: dayEmojis[dobDay],
                zodiacSign: zodiac.sign, zodiacEmoji: zodiac.emoji,
                chineseZodiac: chineseZodiac.name, chineseEmoji: chineseZodiac.emoji,
                season: season.name, seasonEmoji: season.emoji,
                birthstone: birthstone.stone, birthstoneEmoji: birthstone.emoji,
                dayOfYear,
                milestones, funFacts,
            };
        },

        _startTicker(dobTs, isToday) {
            var self = this;
            if (self._ticker) { clearInterval(self._ticker); self._ticker = null; }
            if (isToday) {
                self._ticker = setInterval(function() {
                    self.liveSecs = Math.floor((Date.now() - dobTs) / 1000);
                }, 1000);
            }
        },

        /* ── Lookup tables ── */
        _getZodiac(month, day) {
            var signs = [
                { sign:'Capricorn',   emoji:'♑', m1:12, d1:22, m2:1,  d2:19 },
                { sign:'Aquarius',    emoji:'♒', m1:1,  d1:20, m2:2,  d2:18 },
                { sign:'Pisces',      emoji:'♓', m1:2,  d1:19, m2:3,  d2:20 },
                { sign:'Aries',       emoji:'♈', m1:3,  d1:21, m2:4,  d2:19 },
                { sign:'Taurus',      emoji:'♉', m1:4,  d1:20, m2:5,  d2:20 },
                { sign:'Gemini',      emoji:'♊', m1:5,  d1:21, m2:6,  d2:20 },
                { sign:'Cancer',      emoji:'♋', m1:6,  d1:21, m2:7,  d2:22 },
                { sign:'Leo',         emoji:'♌', m1:7,  d1:23, m2:8,  d2:22 },
                { sign:'Virgo',       emoji:'♍', m1:8,  d1:23, m2:9,  d2:22 },
                { sign:'Libra',       emoji:'♎', m1:9,  d1:23, m2:10, d2:22 },
                { sign:'Scorpio',     emoji:'♏', m1:10, d1:23, m2:11, d2:21 },
                { sign:'Sagittarius', emoji:'♐', m1:11, d1:22, m2:12, d2:21 },
            ];
            for (var i = 0; i < signs.length; i++) {
                var s = signs[i];
                if ((month === s.m1 && day >= s.d1) || (month === s.m2 && day <= s.d2)) return s;
            }
            return { sign:'Capricorn', emoji:'♑' };
        },

        _getChineseZodiac(year) {
            var animals = [
                { name:'Rat',     emoji:'🐀' },
                { name:'Ox',      emoji:'🐂' },
                { name:'Tiger',   emoji:'🐅' },
                { name:'Rabbit',  emoji:'🐇' },
                { name:'Dragon',  emoji:'🐉' },
                { name:'Snake',   emoji:'🐍' },
                { name:'Horse',   emoji:'🐎' },
                { name:'Goat',    emoji:'🐐' },
                { name:'Monkey',  emoji:'🐒' },
                { name:'Rooster', emoji:'🐓' },
                { name:'Dog',     emoji:'🐕' },
                { name:'Pig',     emoji:'🐖' },
            ];
            return animals[((year - 4) % 12 + 12) % 12];
        },

        _getSeason(month) {
            /* Northern hemisphere */
            if (month === 12 || month <= 2)  return { name:'Winter',  emoji:'❄️' };
            if (month >= 3  && month <= 5)   return { name:'Spring',  emoji:'🌸' };
            if (month >= 6  && month <= 8)   return { name:'Summer',  emoji:'☀️' };
            return                                  { name:'Autumn',  emoji:'🍂' };
        },

        _getBirthstone(month) {
            var stones = [
                { stone:'Garnet',         emoji:'💎' },
                { stone:'Amethyst',       emoji:'💜' },
                { stone:'Aquamarine',     emoji:'💠' },
                { stone:'Diamond',        emoji:'💍' },
                { stone:'Emerald',        emoji:'💚' },
                { stone:'Pearl',          emoji:'🤍' },
                { stone:'Ruby',           emoji:'❤️' },
                { stone:'Peridot',        emoji:'💚' },
                { stone:'Sapphire',       emoji:'💙' },
                { stone:'Opal',           emoji:'🌈' },
                { stone:'Topaz',          emoji:'🟡' },
                { stone:'Turquoise',      emoji:'🩵' },
            ];
            return stones[month - 1] || stones[0];
        },

        _fmtDate(d) {
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        },

        /* ── Formatters ── */
        fmt(v) {
            if (v === null || v === undefined || isNaN(v)) return '—';
            return Math.round(v).toLocaleString();
        },
        fmtBig(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            v = Math.round(v);
            if (v >= 1e12) return (v / 1e12).toFixed(2) + 'T';
            if (v >= 1e9)  return (v / 1e9).toFixed(2)  + 'B';
            if (v >= 1e6)  return (v / 1e6).toFixed(2)  + 'M';
            if (v >= 1e3)  return (v / 1e3).toFixed(1)  + 'K';
            return v.toLocaleString();
        },

        /* ── Sample & helpers ── */
        loadSample() {
            var today = new Date();
            var dob   = new Date(today.getFullYear() - 28, today.getMonth() - 3, today.getDate() - 15);
            this.dob      = this._fmt(dob);
            this.asOfDate = this.todayStr;
            this.error    = '';
            this.result   = null;
            this.phase    = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            if (this._ticker) { clearInterval(this._ticker); this._ticker = null; }
            this.error   = '';
            this.result  = null;
            this.phase   = 'idle';
            this.liveSecs = 0;
        },

        /* ── Export ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var lines = [
                'Age Calculator Results',
                '======================',
                'Date of Birth  : ' + r.dobFormatted,
                'Calculated As Of: ' + r.asOfFormatted,
                '',
                'EXACT AGE:',
                '  ' + r.years + ' years, ' + r.months + ' months, ' + r.days + ' days',
                '',
                'TOTAL TIME:',
                '  Months  : ' + this.fmt(r.totalMonths),
                '  Weeks   : ' + this.fmt(r.totalWeeks),
                '  Days    : ' + this.fmt(r.totalDays),
                '  Hours   : ' + this.fmtBig(r.totalHours),
                '  Minutes : ' + this.fmtBig(r.totalMinutes),
                '  Seconds : ' + this.fmtBig(r.totalSeconds),
                '',
                'NEXT BIRTHDAY:',
                '  In ' + r.daysUntilBirthday + ' days (' + r.nextBirthdayFormatted + ')',
                '',
                'BORN ON:',
                '  Day     : ' + r.dayOfWeek,
                '  Zodiac  : ' + r.zodiacSign + ' ' + r.zodiacEmoji,
                '  Chinese : ' + r.chineseZodiac + ' ' + r.chineseEmoji,
                '  Season  : ' + r.season,
                '  Stone   : ' + r.birthstone,
            ];
            return lines.join('\n');
        },

        async copySummary() {
            var text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); } catch(e) {
                var ta = document.createElement('textarea'); ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;'; document.body.appendChild(ta);
                ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
            }
            var self = this; this.copyFlash = true;
            setTimeout(function(){ self.copyFlash = false; }, 1800);
        },

        downloadSummary() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text], { type:'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a'); a.href = url; a.download = 'age-results.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\age-calculator.blade.php ENDPATH**/ ?>