<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Tone / Strength pills ── */
.mode-pill {
    display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
    padding: .55rem 1.1rem; border-radius: 9999px;
    border: 2px solid #e2e8f0; background: white;
    font-size: .8rem; font-weight: 600; color: #64748b;
    cursor: pointer; transition: all .13s; user-select: none; white-space: nowrap;
}
.mode-pill.active {
    border-color: #4f46e5; background: #eef2ff; color: #4338ca;
}
.mode-pill:hover:not(.active) { border-color: #c7d2fe; background: #f8faff; }

/* ── Strength mini pills ── */
.str-pill {
    flex: 1; display: flex; align-items: center; justify-content: center;
    padding: .45rem .6rem; border-radius: .6rem;
    border: 2px solid #e2e8f0; background: white;
    font-size: .78rem; font-weight: 600; color: #64748b;
    cursor: pointer; transition: all .13s; user-select: none;
}
.str-pill.active { border-color: #4f46e5; background: #eef2ff; color: #4338ca; }
.str-pill:hover:not(.active) { background: #f8faff; }

/* ── Output area ── */
.output-box {
    min-height: 220px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: .875rem;
    padding: .875rem 1rem;
    font-size: .875rem; line-height: 1.75;
    color: #1e293b;
    white-space: pre-wrap; word-break: break-word;
    position: relative;
}
.output-box mark {
    background: #ddd6fe; color: #4c1d95;
    border-radius: 3px; padding: 0 2px;
}
.output-box.is-formal mark    { background: #dbeafe; color: #1e3a8a; }
.output-box.is-simple mark    { background: #dcfce7; color: #14532d; }
.output-box.is-creative mark  { background: #fce7f3; color: #831843; }

/* ── Stat chip ── */
.p-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .28rem .7rem; border-radius: 9999px;
    background: #f1f5f9; font-size: .72rem; font-weight: 500; color: #475569;
}
.p-chip.green { background: #dcfce7; color: #166534; }
.p-chip.purple { background: #ede9fe; color: #5b21b6; }

/* ── Toggle switch ── */
.tog-track { width: 2.25rem; height: 1.25rem; border-radius: 9999px; transition: background .15s; flex-shrink: 0; }
.tog-thumb  { position: absolute; top: .125rem; left: .125rem; width: 1rem; height: 1rem; background: white; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,.2); transition: transform .15s; }

/* ── Spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.spin-anim { animation: spin .65s linear infinite; display: inline-block; }

/* ── Copy flash ── */
@keyframes copyFlash {
    0%,100% { background: #f0fdf4; color: #166534; border-color: #86efac; }
    50%      { background: #dcfce7; }
}
.do-copy-flash { animation: copyFlash .55s ease 2 !important; }

/* ── Result entrance ── */
@keyframes resultIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.result-in { animation: resultIn .3s ease-out; }

/* ── Textarea ── */
.para-ta {
    font-family: inherit; font-size: .9rem; line-height: 1.7;
    resize: vertical; transition: border-color .15s, box-shadow .15s;
}

/* ── Loading shimmer ── */
@keyframes shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
.shimmer-line {
    height: .9rem; border-radius: .4rem; margin-bottom: .75rem;
    background: linear-gradient(90deg, #f0f4f8 25%, #e2e8f0 50%, #f0f4f8 75%);
    background-size: 800px 100%;
    animation: shimmer 1.4s infinite;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="textParaphraser()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Instantly rewrite and rephrase text while preserving meaning. Choose your tone and strength — all processing happens in your browser.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Data Stored</span>
                    <span class="badge badge-primary">Instant</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        
        <div class="card p-4">
            <div class="flex flex-wrap gap-5 items-start">

                
                <div class="space-y-1.5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tone</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="tone='standard'" class="mode-pill" :class="tone==='standard' ? 'active':''">
                            📝 Standard
                        </button>
                        <button type="button" @click="tone='formal'" class="mode-pill" :class="tone==='formal' ? 'active':''">
                            🎩 Formal
                        </button>
                        <button type="button" @click="tone='simple'" class="mode-pill" :class="tone==='simple' ? 'active':''">
                            💬 Simple
                        </button>
                        <button type="button" @click="tone='creative'" class="mode-pill" :class="tone==='creative' ? 'active':''">
                            ✨ Creative
                        </button>
                    </div>
                </div>

                
                <div class="space-y-1.5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Strength</p>
                    <div class="flex gap-1.5 w-44">
                        <button type="button" @click="strength='light'" class="str-pill" :class="strength==='light' ? 'active':''">🌤 Light</button>
                        <button type="button" @click="strength='medium'" class="str-pill" :class="strength==='medium' ? 'active':''">⚡ Medium</button>
                        <button type="button" @click="strength='strong'" class="str-pill" :class="strength==='strong' ? 'active':''">🔥 Strong</button>
                    </div>
                </div>

                
                <div class="space-y-1.5 ml-auto">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Options</p>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" x-model="highlightChanges" class="sr-only">
                            <div class="tog-track" :class="highlightChanges ? 'bg-indigo-500':'bg-gray-300'"></div>
                            <div class="tog-thumb" :class="highlightChanges ? 'translate-x-4':'translate-x-0'"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Highlight changed words</span>
                    </label>
                </div>
            </div>

            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span x-show="tone==='standard'">📝 <strong>Standard</strong> — Clear, natural rewording that improves readability while preserving your original meaning.</span>
                    <span x-show="tone==='formal'">🎩 <strong>Formal</strong> — Professional, elevated language suited for academic writing, business documents, and official communication.</span>
                    <span x-show="tone==='simple'">💬 <strong>Simple</strong> — Plain, easy-to-read language. Great for making complex text more accessible to a general audience.</span>
                    <span x-show="tone==='creative'">✨ <strong>Creative</strong> — Vivid, expressive rephrasing with dynamic vocabulary and varied sentence structure.</span>
                </p>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            
            <div class="card">
                
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="font-semibold text-gray-800 text-sm">Original Text</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="p-chip" x-show="inputText.length > 0">
                            <span x-text="wordCount(inputText)"></span> words
                        </span>
                        <span class="p-chip" x-show="inputText.length > 0">
                            <span x-text="inputText.length"></span> chars
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-3">
                    
                    <textarea
                        x-model="inputText"
                        @keydown.ctrl.enter.prevent="paraphrase()"
                        @keydown.meta.enter.prevent="paraphrase()"
                        placeholder="Paste or type the text you want to paraphrase here…

For example:
The implementation of advanced machine learning algorithms has significantly transformed the way organizations process and analyze large volumes of data in real-time environments."
                        rows="13"
                        class="form-input para-ta"
                        :class="inputError ? 'border-red-300 focus:border-red-400 focus:ring-red-300' : ''">
                    </textarea>

                    
                    <div x-show="inputError"
                         x-transition
                         class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="inputError"></span>
                    </div>

                    
                    <div class="flex flex-wrap gap-2 pt-1">
                        <button type="button"
                                @click="paraphrase()"
                                :disabled="phase === 'processing'"
                                class="btn btn-primary flex-1 sm:flex-none">
                            <span x-show="phase === 'processing'" class="spin-anim">⟳</span>
                            <span x-show="phase !== 'processing'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </span>
                            <span x-text="phase === 'processing' ? 'Paraphrasing…' : 'Paraphrase'"></span>
                        </button>

                        <button type="button"
                                @click="loadSample()"
                                class="btn btn-secondary">
                            📄 Sample
                        </button>

                        <button type="button"
                                @click="clearAll()"
                                x-show="inputText || outputText"
                                class="btn btn-secondary">
                            ✕ Clear
                        </button>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Ctrl</kbd>+<kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to paraphrase
                        &nbsp;·&nbsp; Supports up to 5,000 words
                    </p>
                </div>
            </div>

            
            <div class="card" id="output-panel">
                
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full"
                              :class="{
                                'bg-emerald-400': phase === 'done',
                                'bg-amber-400':   phase === 'processing',
                                'bg-gray-300':    phase === 'idle',
                              }">
                        </span>
                        <span class="font-semibold text-gray-800 text-sm">Paraphrased Text</span>
                    </div>
                    
                    <div x-show="phase === 'done'" class="flex items-center gap-2">
                        <span class="p-chip green" x-show="changedCount > 0">
                            ✓ <span x-text="changedCount"></span> changed
                        </span>
                        <span class="p-chip purple">
                            <span x-text="wordCount(outputText)"></span> words
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    
                    <div x-show="phase === 'idle'"
                         class="flex flex-col items-center justify-center min-h-[260px] text-center">
                        <p class="text-5xl mb-4">🤖</p>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Ready to paraphrase</p>
                        <p class="text-xs text-gray-400 max-w-xs">
                            Type or paste text on the left, choose your tone and strength, then click <strong>Paraphrase</strong>.
                        </p>
                        <button type="button"
                                @click="loadSample(); $nextTick(() => paraphrase())"
                                class="btn btn-outline text-sm mt-4">
                            Try with sample text →
                        </button>
                    </div>

                    
                    <div x-show="phase === 'processing'" class="py-4 space-y-0">
                        <div class="shimmer-line w-full"></div>
                        <div class="shimmer-line w-11/12"></div>
                        <div class="shimmer-line w-full"></div>
                        <div class="shimmer-line w-4/5"></div>
                        <div class="shimmer-line w-full"></div>
                        <div class="shimmer-line w-10/12"></div>
                        <div class="shimmer-line w-3/4"></div>
                        <p class="text-center text-xs text-gray-400 mt-4">
                            <span class="spin-anim mr-1">⟳</span> Rewriting your text…
                        </p>
                    </div>

                    
                    <div x-show="phase === 'done'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="space-y-3">

                        
                        <div class="output-box result-in"
                             :class="{
                                'is-formal':   tone === 'formal',
                                'is-simple':   tone === 'simple',
                                'is-creative': tone === 'creative',
                             }"
                             x-html="displayHtml">
                        </div>

                        
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                    @click="copyResult()"
                                    class="btn flex-1 sm:flex-none border border-gray-200"
                                    :class="copyFlash
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300'
                                        : 'bg-white text-gray-700 hover:bg-gray-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="copyFlash ? '✓ Copied!' : 'Copy Result'"></span>
                            </button>

                            <button type="button"
                                    @click="downloadResult()"
                                    class="btn btn-secondary flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download .txt
                            </button>

                            <button type="button"
                                    @click="paraphrase()"
                                    class="btn btn-secondary"
                                    title="Paraphrase again for a different result">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Re-paraphrase
                            </button>
                        </div>

                        
                        <div class="flex flex-wrap gap-2 pt-1 border-t border-gray-100">
                            <span class="p-chip">📊 <span x-text="wordCount(inputText)"></span> → <span x-text="wordCount(outputText)"></span> words</span>
                            <span class="p-chip">🔄 <span x-text="changePercent"></span>% rewritten</span>
                            <span class="p-chip">🎯 <span x-text="toneLabel"></span> · <span x-text="strengthLabel"></span></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card p-5">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">🎩</span>
                    <div>
                        <p class="font-semibold text-sm text-gray-700 mb-1">Formal Tone</p>
                        <p class="text-xs text-gray-500">Best for academic papers, business reports, cover letters, and professional communication.</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">💬</span>
                    <div>
                        <p class="font-semibold text-sm text-gray-700 mb-1">Simple Tone</p>
                        <p class="text-xs text-gray-500">Perfect for making complex or technical content readable for a general audience or students.</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">✨</span>
                    <div>
                        <p class="font-semibold text-sm text-gray-700 mb-1">Creative Tone</p>
                        <p class="text-xs text-gray-500">Great for blog posts, marketing copy, social media, and engaging storytelling content.</p>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-xl"><?php echo e($related->icon); ?></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
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
/* ═══════════════════════════════════════════════════════════════
   TEXT PARAPHRASER — Alpine.js component
   Algorithm: synonym substitution + phrase transforms + sentence
   restructuring. 100% client-side — no API keys required.
═══════════════════════════════════════════════════════════════ */
function textParaphraser() {
    return {
        /* ── State ── */
        inputText:       '',
        outputText:      '',
        displayHtml:     '',
        changedCount:    0,
        phase:           'idle',    /* idle | processing | done */
        inputError:      '',
        tone:            'standard', /* standard | formal | simple | creative */
        strength:        'medium',   /* light | medium | strong */
        highlightChanges: true,
        copyFlash:       false,

        /* ── Computed ── */
        get toneLabel() {
            return { standard:'Standard', formal:'Formal', simple:'Simple', creative:'Creative' }[this.tone];
        },
        get strengthLabel() {
            return { light:'Light', medium:'Medium', strong:'Strong' }[this.strength];
        },
        get changePercent() {
            var total = this.wordCount(this.inputText);
            return total > 0 ? Math.round((this.changedCount / total) * 100) : 0;
        },

        /* ── Lifecycle ── */
        init() { /* nothing to preload */ },

        /* ════════════════════════════════════
           PUBLIC ACTIONS
        ════════════════════════════════════ */

        paraphrase() {
            var txt = this.inputText.trim();
            this.inputError = '';

            if (!txt) {
                this.inputError = 'Please enter some text to paraphrase.';
                return;
            }
            if (txt.split(/\s+/).length < 3) {
                this.inputError = 'Please enter at least a few words.';
                return;
            }
            if (txt.length > 30000) {
                this.inputError = 'Text is too long. Please limit to approximately 5,000 words.';
                return;
            }

            var self = this;
            this.phase = 'processing';
            this.outputText = '';
            this.displayHtml = '';

            /* Delay to render the shimmer first */
            setTimeout(function() {
                var result = self._doParaphrase(txt);
                self.outputText   = result.plain;
                self.displayHtml  = result.html;
                self.changedCount = result.changed;
                self.phase        = 'done';

                /* Mobile: scroll to output */
                if (window.innerWidth < 1024) {
                    var el = document.getElementById('output-panel');
                    if (el) setTimeout(function() {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 120);
                }
            }, 600);
        },

        loadSample() {
            this.inputText = 'The implementation of advanced machine learning algorithms has significantly transformed the way organizations process and analyze large volumes of data in real-time environments. Companies are now able to make better decisions faster by leveraging powerful computational systems that can identify complex patterns within massive datasets. However, the successful adoption of these technologies requires careful planning, skilled professionals, and a strong commitment to continuous improvement. Organizations that fail to invest in proper training and infrastructure may find it difficult to keep up with the rapid pace of technological change.';
            this.inputError = '';
        },

        clearAll() {
            this.inputText    = '';
            this.outputText   = '';
            this.displayHtml  = '';
            this.changedCount = 0;
            this.phase        = 'idle';
            this.inputError   = '';
        },

        async copyResult() {
            if (!this.outputText) return;
            try {
                await navigator.clipboard.writeText(this.outputText);
            } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = this.outputText;
                ta.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.copyFlash = true;
            setTimeout(function() { self.copyFlash = false; }, 1800);
        },

        downloadResult() {
            if (!this.outputText) return;
            var blob = new Blob([this.outputText], { type: 'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href = url;
            a.download = 'paraphrased.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        wordCount(text) {
            return (text || '').trim() === '' ? 0
                : (text.trim().match(/\S+/g) || []).length;
        },

        /* ════════════════════════════════════
           CORE PARAPHRASE ENGINE
        ════════════════════════════════════ */

        _doParaphrase(text) {
            /* 1. Apply phrase-level transforms */
            var processed = this._applyPhraseTransforms(text);

            /* 2. Split into sentences */
            var sentences = this._splitSentences(processed);

            /* 3. Process each sentence */
            var self        = this;
            var plainParts  = [];
            var htmlParts   = [];
            var totalChanged = 0;

            sentences.forEach(function(sent, sIdx) {
                var res = self._processSentence(sent.text, sIdx);
                totalChanged += res.changed;
                plainParts.push(res.plain + sent.terminator);
                htmlParts.push(self._escHtml(res.plain + sent.terminator));

                /* For HTML we rebuild from tokens to apply highlights */
                if (self.highlightChanges && res.tokens) {
                    var html = res.tokens.map(function(tok) {
                        if (tok.isWord && tok.changed) {
                            return '<mark>' + self._escHtml(tok.text) + '</mark>';
                        }
                        return self._escHtml(tok.text);
                    }).join('') + self._escHtml(sent.terminator);
                    htmlParts[htmlParts.length - 1] = html;
                }
            });

            return {
                plain:   plainParts.join(' ').replace(/\s{2,}/g, ' ').trim(),
                html:    htmlParts.join(' ').replace(/ {2,}/g, ' ').trim(),
                changed: totalChanged,
            };
        },

        _splitSentences(text) {
            /* Split on sentence-ending punctuation, keeping terminator */
            var results = [];
            var regex   = /([^.!?]+)([.!?]+["']?\s*|$)/g;
            var match;
            while ((match = regex.exec(text)) !== null) {
                var raw  = match[1];
                var term = (match[2] || '').trim();
                if (raw.trim()) {
                    results.push({ text: raw.trim(), terminator: term ? ' ' + term : '' });
                }
            }
            if (results.length === 0) {
                results.push({ text: text.trim(), terminator: '' });
            }
            return results;
        },

        _processSentence(sentence, sentIdx) {
            /* Tokenize: alternating word / non-word spans */
            var tokens  = this._tokenize(sentence);
            var changed = 0;

            /* Strength → replacement probability */
            var prob = { light: 0.22, medium: 0.48, strong: 0.72 }[this.strength];

            /* For strong mode at odd sentence indices, apply sentence-start variation */
            if (this.strength === 'strong' && sentIdx % 3 === 1) {
                tokens = this._reorderSentenceStart(tokens);
            }

            var wordIndex = 0;
            var isFirst   = true;

            for (var i = 0; i < tokens.length; i++) {
                var tok = tokens[i];
                if (!tok.isWord) continue;

                wordIndex++;
                var lower = tok.text.toLowerCase().replace(/['']/g, "'");

                /* Skip stop words */
                if (this.STOP_WORDS[lower]) { isFirst = false; continue; }

                /* Skip short tokens (≤ 2 chars) */
                if (tok.text.length <= 2) { isFirst = false; continue; }

                /* Skip likely proper nouns: capitalized mid-sentence (not first word) */
                if (!isFirst && /^[A-Z]/.test(tok.text) && !/^[A-Z]{2,}$/.test(tok.text)) {
                    isFirst = false; continue;
                }

                /* Get synonym options for this tone */
                var options = this._getSynonyms(lower);
                if (!options || options.length === 0) { isFirst = false; continue; }

                /* Apply probability */
                if (Math.random() > prob) { isFirst = false; continue; }

                /* Pick synonym */
                var syn = options[Math.floor(Math.random() * options.length)];

                /* Preserve case pattern */
                syn = this._matchCase(syn, tok.text, isFirst);

                tok.original = tok.text;
                tok.text     = syn;
                tok.changed  = true;
                changed++;

                isFirst = false;
            }

            var plain = tokens.map(function(t) { return t.text; }).join('');
            return { plain: plain, changed: changed, tokens: tokens };
        },

        _tokenize(text) {
            /* Split keeping punctuation/spaces as separate tokens */
            var parts  = text.match(/[A-Za-z''-]+|[^A-Za-z''-]+/g) || [text];
            return parts.map(function(p) {
                return {
                    text:    p,
                    isWord:  /^[A-Za-z''-]+$/.test(p),
                    changed: false,
                };
            });
        },

        _reorderSentenceStart(tokens) {
            /* Try to move a leading adverb or prepositional phrase to later */
            var SENT_STARTERS = {
                'additionally': 'Furthermore', 'furthermore': 'Additionally',
                'however': 'Nevertheless', 'nevertheless': 'However',
                'therefore': 'Consequently', 'consequently': 'Therefore',
                'also': 'Moreover', 'moreover': 'Also',
                'finally': 'Ultimately', 'ultimately': 'Finally',
                'generally': 'Overall', 'overall': 'Generally',
                'importantly': 'Significantly', 'significantly': 'Importantly',
                'typically': 'Usually', 'usually': 'Typically',
            };
            var first = tokens.find(function(t) { return t.isWord; });
            if (first) {
                var alt = SENT_STARTERS[first.text.toLowerCase()];
                if (alt) { first.text = alt; first.changed = true; }
            }
            return tokens;
        },

        _applyPhraseTransforms(text) {
            var PHRASES = [
                [/\bin order to\b/gi,              'to'],
                [/\bdue to the fact that\b/gi,      'because'],
                [/\bat this point in time\b/gi,     'currently'],
                [/\bon a regular basis\b/gi,         'regularly'],
                [/\ba large number of\b/gi,          'many'],
                [/\bin the event that\b/gi,          'if'],
                [/\bwith the exception of\b/gi,      'except for'],
                [/\bin the majority of cases\b/gi,   'usually'],
                [/\bprior to\b/gi,                   'before'],
                [/\bsubsequent to\b/gi,              'after'],
                [/\bfor the purpose of\b/gi,         'to'],
                [/\bat the present time\b/gi,        'currently'],
                [/\bin spite of\b/gi,                'despite'],
                [/\bas a result of\b/gi,             'because of'],
                [/\bon account of\b/gi,              'because of'],
                [/\bwith regard to\b/gi,             'regarding'],
                [/\bin terms of\b/gi,                'regarding'],
                [/\bwith respect to\b/gi,            'regarding'],
                [/\bin close proximity to\b/gi,      'near'],
                [/\bsufficient(?:ly)?\b/gi,          'enough'],
                [/\butilize\b/gi,                    this.tone === 'simple' ? 'use' : 'utilize'],
                [/\band also\b/gi,                   'and'],
                [/\bbut however\b/gi,                'however'],
            ];

            PHRASES.forEach(function(rule) {
                text = text.replace(rule[0], rule[1]);
            });

            return text;
        },

        _getSynonyms(word) {
            var map = this.SYNS[word];
            if (!map) return null;

            /* Creative has its own set */
            if (this.tone === 'creative' && this.CREATIVE_SYNS[word]) {
                return this.CREATIVE_SYNS[word];
            }

            if (!Array.isArray(map)) return null;

            /* Formal: first half (more elevated) */
            if (this.tone === 'formal') {
                return map.slice(0, Math.max(2, Math.ceil(map.length / 2)));
            }
            /* Simple: last half (more accessible) */
            if (this.tone === 'simple') {
                var start = Math.floor(map.length / 2);
                return map.slice(start);
            }
            /* Standard + creative fallback: all options */
            return map;
        },

        _matchCase(synonym, original, isFirst) {
            /* ALL CAPS */
            if (original === original.toUpperCase() && original.length > 2) {
                return synonym.toUpperCase();
            }
            /* Title Case / sentence start */
            if (/^[A-Z]/.test(original) || isFirst) {
                return synonym.charAt(0).toUpperCase() + synonym.slice(1);
            }
            return synonym;
        },

        _escHtml(s) {
            return String(s)
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;');
        },

        /* ════════════════════════════════════
           STOP WORDS (never replaced)
        ════════════════════════════════════ */
        STOP_WORDS: (function() {
            var words = [
                'a','an','the','and','or','but','nor','so','yet','for',
                'in','on','at','to','of','with','by','from','up',
                'into','out','off','over','under','through','about',
                'above','below','between','against','along','among',
                'around','before','behind','beside','beyond','during',
                'except','inside','near','outside','toward',
                'until','upon','within','without','after',
                'i','me','my','myself','we','our','ours','ourselves',
                'you','your','yours','yourself','yourselves',
                'he','him','his','himself','she','her','hers','herself',
                'it','its','itself','they','them','their','theirs','themselves',
                'who','whom','whose','what','which',
                'this','that','these','those',
                'am','is','are','was','were','be','been','being',
                'have','has','had','having',
                'do','does','did','doing',
                'will','would','shall','should','may','might',
                'can','could','must','ought',
                'not','no','never','neither',
                'here','there','when','where','why','how',
                'again','then','once','now','just','only',
                'both','each','either','all','any','some',
                'as','if','while','although','though','because',
                'unless','until','since','than','else',
                'one','two','three','four','five','six','seven','eight','nine','ten',
                'don\'t','doesn\'t','didn\'t','can\'t','won\'t','isn\'t','aren\'t',
                'wasn\'t','weren\'t','hasn\'t','haven\'t','hadn\'t','wouldn\'t',
                'couldn\'t','shouldn\'t','it\'s','that\'s','there\'s','they\'re',
                'we\'re','you\'re','i\'m','i\'ve','i\'ll','i\'d','let\'s',
                'i','he','she','it','we','they','you',
            ];
            var obj = Object.create(null);
            words.forEach(function(w) { obj[w] = true; });
            return obj;
        })(),

        /* ════════════════════════════════════
           SYNONYM DICTIONARY  (~300 entries)
           Format: [more-formal ... more-casual]
           Tone selection slices accordingly.
        ════════════════════════════════════ */
        SYNS: {
            /* ── Verbs ── */
            'achieve':     ['accomplish','attain','fulfill','realize','reach'],
            'add':         ['incorporate','append','include','supplement','attach'],
            'allow':       ['permit','authorize','enable','grant','let'],
            'analyze':     ['examine','evaluate','assess','review','study'],
            'apply':       ['implement','employ','utilize','use','put to use'],
            'ask':         ['inquire','request','query','pose','seek'],
            'attempt':     ['endeavor','try','strive','seek','aim'],
            'begin':       ['commence','initiate','launch','start','kick off'],
            'believe':     ['consider','hold','maintain','think','feel'],
            'build':       ['construct','develop','establish','create','form'],
            'cause':       ['produce','generate','trigger','bring about','lead to'],
            'change':      ['modify','alter','transform','revise','adjust'],
            'choose':      ['select','opt for','decide','prefer','settle on'],
            'combine':     ['integrate','merge','unify','blend','join'],
            'come':        ['arrive','emerge','appear','reach','approach'],
            'compare':     ['contrast','evaluate','weigh','measure','assess'],
            'complete':    ['finalize','accomplish','conclude','wrap up','finish'],
            'consider':    ['evaluate','contemplate','examine','weigh','think about'],
            'continue':    ['proceed','maintain','sustain','persist','carry on'],
            'create':      ['develop','produce','generate','establish','build'],
            'decide':      ['determine','resolve','conclude','settle','choose'],
            'define':      ['clarify','specify','outline','describe','explain'],
            'demonstrate': ['illustrate','show','prove','reveal','exhibit'],
            'describe':    ['outline','detail','explain','portray','depict'],
            'determine':   ['establish','identify','conclude','find','decide'],
            'develop':     ['advance','build','create','expand','grow'],
            'discuss':     ['address','explore','examine','consider','analyze'],
            'enable':      ['allow','permit','facilitate','empower','support'],
            'ensure':      ['guarantee','confirm','secure','verify','make certain'],
            'establish':   ['set up','found','create','introduce','build'],
            'evaluate':    ['assess','analyze','review','examine','measure'],
            'examine':     ['analyze','study','review','assess','investigate'],
            'explain':     ['clarify','outline','describe','detail','illustrate'],
            'find':        ['discover','identify','locate','detect','uncover'],
            'focus':       ['concentrate on','emphasize','highlight','center','target'],
            'gain':        ['acquire','obtain','achieve','earn','attain'],
            'generate':    ['produce','create','yield','deliver','develop'],
            'get':         ['obtain','acquire','receive','gain','secure'],
            'give':        ['provide','offer','supply','deliver','present'],
            'grow':        ['expand','increase','develop','advance','evolve'],
            'handle':      ['manage','address','deal with','tackle','oversee'],
            'help':        ['assist','support','facilitate','enable','aid'],
            'identify':    ['recognize','detect','spot','pinpoint','find'],
            'implement':   ['execute','deploy','apply','adopt','carry out'],
            'improve':     ['enhance','advance','refine','strengthen','upgrade'],
            'include':     ['incorporate','contain','comprise','encompass','cover'],
            'increase':    ['expand','grow','raise','boost','enhance'],
            'indicate':    ['suggest','show','signal','point to','reflect'],
            'involve':     ['include','require','entail','encompass','contain'],
            'keep':        ['maintain','retain','preserve','sustain','hold'],
            'know':        ['understand','recognize','comprehend','realize','be aware of'],
            'learn':       ['discover','acquire','grasp','understand','gain'],
            'look':        ['examine','review','observe','inspect','consider'],
            'make':        ['create','produce','develop','form','generate'],
            'manage':      ['oversee','coordinate','control','direct','handle'],
            'meet':        ['satisfy','fulfill','address','achieve','reach'],
            'move':        ['transition','transfer','shift','advance','progress'],
            'need':        ['require','demand','necessitate','depend on','call for'],
            'note':        ['observe','mention','highlight','point out','indicate'],
            'offer':       ['provide','present','propose','suggest','deliver'],
            'perform':     ['execute','carry out','conduct','complete','accomplish'],
            'present':     ['introduce','display','share','offer','provide'],
            'produce':     ['create','generate','develop','deliver','yield'],
            'provide':     ['supply','offer','deliver','give','present'],
            'put':         ['place','position','set','establish','locate'],
            'reach':       ['attain','achieve','arrive at','access','accomplish'],
            'reduce':      ['decrease','lower','minimize','cut','limit'],
            'remain':      ['continue','persist','endure','stay','last'],
            'require':     ['demand','necessitate','call for','need','involve'],
            'result':      ['yield','produce','lead to','generate','bring about'],
            'run':         ['operate','manage','execute','conduct','direct'],
            'say':         ['state','indicate','express','mention','note'],
            'see':         ['observe','notice','recognize','identify','detect'],
            'seem':        ['appear','look','come across as','feel','strike as'],
            'show':        ['demonstrate','display','illustrate','reveal','present'],
            'start':       ['initiate','launch','commence','begin','kick off'],
            'suggest':     ['recommend','propose','advise','indicate','imply'],
            'support':     ['back','promote','facilitate','assist','enable'],
            'take':        ['adopt','apply','acquire','use','employ'],
            'think':       ['consider','believe','view','perceive','feel'],
            'try':         ['endeavor','seek','aim','strive','attempt'],
            'understand':  ['comprehend','grasp','recognize','appreciate','realize'],
            'use':         ['employ','utilize','apply','leverage','work with'],
            'want':        ['seek','desire','aim for','wish for','intend'],
            'work':        ['operate','function','perform','serve','act'],

            /* ── Adjectives ── */
            'able':        ['capable','skilled','equipped','qualified','competent'],
            'accurate':    ['precise','correct','exact','valid','reliable'],
            'additional':  ['supplementary','extra','further','added','more'],
            'available':   ['accessible','obtainable','ready','on hand','at hand'],
            'bad':         ['poor','adverse','unfavorable','negative','problematic'],
            'basic':       ['fundamental','core','essential','primary','key'],
            'better':      ['superior','improved','enhanced','stronger','more effective'],
            'big':         ['substantial','large','considerable','significant','major'],
            'clear':       ['evident','apparent','obvious','transparent','plain'],
            'common':      ['widespread','prevalent','typical','standard','frequent'],
            'complete':    ['comprehensive','thorough','total','full','entire'],
            'complex':     ['sophisticated','intricate','elaborate','advanced','involved'],
            'critical':    ['crucial','pivotal','essential','key','vital'],
            'current':     ['existing','contemporary','modern','present','today\'s'],
            'different':   ['distinct','diverse','varied','alternative','unique'],
            'difficult':   ['challenging','demanding','complex','tough','arduous'],
            'easy':        ['straightforward','effortless','simple','accessible','uncomplicated'],
            'effective':   ['successful','productive','powerful','impactful','efficient'],
            'efficient':   ['productive','streamlined','optimized','capable','effective'],
            'essential':   ['vital','crucial','fundamental','key','necessary'],
            'excellent':   ['outstanding','exceptional','superior','remarkable','first-rate'],
            'fast':        ['rapid','swift','quick','prompt','speedy'],
            'flexible':    ['adaptable','versatile','adjustable','dynamic','open'],
            'good':        ['excellent','solid','positive','beneficial','effective'],
            'great':       ['outstanding','exceptional','remarkable','superb','excellent'],
            'high':        ['elevated','increased','greater','superior','advanced'],
            'ideal':       ['optimal','perfect','preferred','best','most suitable'],
            'important':   ['significant','crucial','critical','essential','vital'],
            'large':       ['substantial','extensive','considerable','major','significant'],
            'long':        ['extended','prolonged','lengthy','sustained','extended'],
            'main':        ['primary','central','core','principal','key'],
            'major':       ['significant','primary','critical','substantial','key'],
            'many':        ['numerous','various','multiple','several','a number of'],
            'modern':      ['contemporary','current','advanced','recent','cutting-edge'],
            'new':         ['novel','recent','innovative','fresh','emerging'],
            'next':        ['following','subsequent','upcoming','successive','future'],
            'old':         ['prior','previous','former','traditional','established'],
            'overall':     ['general','comprehensive','total','broad','combined'],
            'powerful':    ['robust','effective','potent','capable','strong'],
            'previous':    ['former','prior','earlier','past','preceding'],
            'primary':     ['central','key','core','principal','main'],
            'proper':      ['appropriate','suitable','correct','fitting','adequate'],
            'quick':       ['rapid','fast','swift','prompt','efficient'],
            'real':        ['genuine','authentic','actual','true','legitimate'],
            'recent':      ['latest','current','new','modern','contemporary'],
            'regular':     ['consistent','standard','typical','routine','common'],
            'right':       ['appropriate','correct','proper','suitable','accurate'],
            'robust':      ['strong','reliable','solid','powerful','durable'],
            'short':       ['brief','concise','compact','limited','quick'],
            'significant': ['considerable','notable','substantial','major','important'],
            'similar':     ['comparable','alike','equivalent','related','akin'],
            'simple':      ['basic','easy','straightforward','clear','uncomplicated'],
            'small':       ['minor','limited','modest','slight','minimal'],
            'specific':    ['particular','defined','precise','exact','targeted'],
            'strong':      ['powerful','robust','solid','firm','effective'],
            'successful':  ['effective','productive','accomplished','fruitful','achieved'],
            'unique':      ['distinctive','exclusive','one-of-a-kind','particular','special'],
            'useful':      ['helpful','practical','valuable','effective','beneficial'],
            'various':     ['diverse','different','multiple','numerous','a range of'],
            'whole':       ['entire','complete','total','full','comprehensive'],

            /* ── Nouns ── */
            'ability':     ['capability','capacity','skill','talent','competence'],
            'approach':    ['method','strategy','technique','means','way'],
            'area':        ['field','domain','sector','region','scope'],
            'aspect':      ['element','dimension','feature','side','component'],
            'benefit':     ['advantage','value','merit','gain','asset'],
            'case':        ['situation','instance','scenario','example','circumstance'],
            'challenge':   ['difficulty','obstacle','issue','hurdle','problem'],
            'change':      ['shift','transformation','modification','adjustment','update'],
            'choice':      ['option','selection','alternative','decision','preference'],
            'component':   ['element','part','piece','unit','section'],
            'concern':     ['issue','problem','worry','matter','consideration'],
            'context':     ['setting','background','framework','environment','situation'],
            'data':        ['information','details','statistics','metrics','figures'],
            'decision':    ['judgment','conclusion','determination','resolution','choice'],
            'effect':      ['impact','result','consequence','outcome','influence'],
            'effort':      ['work','attempt','initiative','endeavor','undertaking'],
            'example':     ['instance','illustration','case','demonstration','sample'],
            'experience':  ['exposure','background','knowledge','track record','expertise'],
            'factor':      ['element','aspect','consideration','variable','component'],
            'feature':     ['characteristic','attribute','quality','property','trait'],
            'focus':       ['emphasis','attention','priority','concentration','aim'],
            'goal':        ['objective','aim','target','purpose','aspiration'],
            'group':       ['team','collection','set','cluster','body'],
            'growth':      ['expansion','development','progress','increase','advancement'],
            'idea':        ['concept','notion','thought','perspective','approach'],
            'impact':      ['effect','influence','result','consequence','bearing'],
            'improvement': ['enhancement','advancement','progress','upgrade','development'],
            'information': ['data','details','knowledge','insight','content'],
            'issue':       ['problem','challenge','concern','matter','difficulty'],
            'knowledge':   ['understanding','expertise','insight','awareness','familiarity'],
            'level':       ['degree','extent','amount','magnitude','stage'],
            'method':      ['approach','technique','strategy','procedure','process'],
            'need':        ['requirement','necessity','demand','prerequisite','must'],
            'number':      ['count','quantity','amount','figure','total'],
            'opportunity': ['chance','possibility','prospect','potential','opening'],
            'option':      ['choice','alternative','possibility','selection','way'],
            'outcome':     ['result','consequence','effect','impact','product'],
            'part':        ['component','element','section','piece','portion'],
            'place':       ['location','area','region','site','setting'],
            'plan':        ['strategy','framework','blueprint','approach','roadmap'],
            'point':       ['aspect','element','consideration','fact','detail'],
            'problem':     ['issue','challenge','difficulty','concern','obstacle'],
            'process':     ['procedure','method','approach','workflow','system'],
            'purpose':     ['goal','objective','aim','intention','reason'],
            'quality':     ['standard','characteristic','attribute','feature','level'],
            'question':    ['matter','issue','concern','point','topic'],
            'reason':      ['rationale','basis','cause','justification','explanation'],
            'requirement': ['need','necessity','prerequisite','demand','condition'],
            'result':      ['outcome','consequence','finding','effect','impact'],
            'role':        ['function','purpose','position','responsibility','contribution'],
            'section':     ['part','portion','area','segment','component'],
            'situation':   ['case','scenario','circumstance','position','state'],
            'skill':       ['ability','expertise','talent','capability','competence'],
            'solution':    ['answer','resolution','approach','fix','remedy'],
            'source':      ['origin','basis','reference','foundation','provider'],
            'step':        ['phase','stage','action','measure','move'],
            'strategy':    ['plan','approach','method','framework','technique'],
            'study':       ['research','analysis','examination','review','investigation'],
            'success':     ['achievement','accomplishment','triumph','victory','gain'],
            'system':      ['framework','structure','platform','model','approach'],
            'task':        ['assignment','job','activity','responsibility','work'],
            'time':        ['period','duration','phase','moment','point'],
            'tool':        ['instrument','resource','utility','means','asset'],
            'type':        ['kind','form','variety','category','sort'],
            'use':         ['application','purpose','function','role','utility'],
            'value':       ['benefit','worth','importance','significance','merit'],
            'view':        ['perspective','opinion','stance','position','outlook'],
            'way':         ['method','approach','means','technique','manner'],
            'work':        ['effort','task','project','activity','assignment'],

            /* ── Adverbs / Connectors ── */
            'additionally':  ['furthermore','moreover','besides','in addition','also'],
            'also':          ['furthermore','additionally','moreover','as well','likewise'],
            'clearly':       ['evidently','obviously','plainly','apparently','transparently'],
            'currently':     ['presently','at present','today','at this time','now'],
            'directly':      ['straightforwardly','immediately','precisely','specifically'],
            'easily':        ['readily','effortlessly','smoothly','conveniently','simply'],
            'effectively':   ['successfully','productively','efficiently','adequately'],
            'especially':    ['particularly','notably','specifically','above all','in particular'],
            'eventually':    ['ultimately','finally','over time','in time','at last'],
            'finally':       ['ultimately','in the end','at last','lastly','conclusively'],
            'frequently':    ['regularly','often','commonly','repeatedly','consistently'],
            'furthermore':   ['additionally','moreover','besides','in addition','also'],
            'generally':     ['typically','usually','broadly','commonly','normally'],
            'highly':        ['greatly','particularly','extremely','notably','especially'],
            'however':       ['nevertheless','nonetheless','that said','yet','still'],
            'immediately':   ['promptly','instantly','right away','at once','quickly'],
            'importantly':   ['significantly','critically','notably','crucially'],
            'indeed':        ['certainly','truly','in fact','actually','undoubtedly'],
            'mainly':        ['primarily','chiefly','predominantly','largely','principally'],
            'moreover':      ['furthermore','additionally','besides','in addition','also'],
            'mostly':        ['primarily','largely','predominantly','chiefly','mainly'],
            'notably':       ['particularly','especially','significantly','importantly'],
            'often':         ['frequently','regularly','commonly','typically','consistently'],
            'overall':       ['generally','broadly','in total','on the whole','as a whole'],
            'particularly':  ['especially','notably','specifically','in particular'],
            'primarily':     ['mainly','principally','chiefly','above all','largely'],
            'probably':      ['likely','presumably','apparently','seemingly','in all likelihood'],
            'quickly':       ['rapidly','swiftly','promptly','speedily','efficiently'],
            'recently':      ['lately','of late','in recent times','not long ago'],
            'significantly': ['considerably','substantially','notably','markedly','greatly'],
            'simply':        ['merely','basically','just','plainly','straightforwardly'],
            'specifically':  ['particularly','precisely','explicitly','especially','exactly'],
            'still':         ['nonetheless','nevertheless','even so','despite this','yet'],
            'therefore':     ['consequently','accordingly','thus','hence','as a result'],
            'typically':     ['generally','usually','normally','commonly','ordinarily'],
            'ultimately':    ['finally','conclusively','in the end','at last','eventually'],
            'usually':       ['typically','generally','normally','commonly','ordinarily'],
            'well':          ['effectively','successfully','thoroughly','competently'],
        },

        /* ════════════════════════════════════
           CREATIVE SYNONYMS (vivid/expressive)
        ════════════════════════════════════ */
        CREATIVE_SYNS: {
            'show':        ['illuminate','spotlight','unveil','paint','highlight'],
            'important':   ['pivotal','groundbreaking','monumental','transformative'],
            'good':        ['remarkable','compelling','brilliant','stellar','impressive'],
            'bad':         ['troubling','alarming','concerning','disruptive','damaging'],
            'start':       ['ignite','spark','catalyze','propel','launch'],
            'help':        ['empower','elevate','amplify','propel','accelerate'],
            'use':         ['harness','leverage','unlock','tap into','deploy'],
            'create':      ['craft','forge','pioneer','architect','sculpt'],
            'change':      ['transform','reshape','revolutionize','reinvent','overhaul'],
            'grow':        ['flourish','surge','thrive','soar','skyrocket'],
            'increase':    ['surge','skyrocket','climb','escalate','soar'],
            'improve':     ['elevate','supercharge','transform','sharpen','advance'],
            'problem':     ['stumbling block','bottleneck','friction','barrier','hurdle'],
            'solution':    ['game-changer','breakthrough','lifeline','remedy','answer'],
            'idea':        ['vision','spark','inspiration','insight','concept'],
            'data':        ['intelligence','insights','signals','evidence','findings'],
            'goal':        ['vision','ambition','aspiration','mission','north star'],
            'work':        ['hustle','drive','effort','craft','dedication'],
            'result':      ['impact','ripple effect','breakthrough','milestone'],
            'success':     ['triumph','breakthrough','milestone','victory','achievement'],
            'ability':     ['superpower','strength','edge','gift','mastery'],
            'effort':      ['drive','dedication','hustle','commitment','initiative'],
            'challenge':   ['crucible','test','trial','friction','obstacle'],
            'focus':       ['laser focus','spotlight','attention','center stage'],
            'need':        ['hunger','drive','imperative','call','urgency'],
            'find':        ['uncover','surface','reveal','expose','unlock'],
            'make':        ['craft','build','engineer','architect','forge'],
            'think':       ['envision','imagine','rethink','reimagine','see'],
            'say':         ['declare','proclaim','reveal','share','champion'],
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\text-paraphraser.blade.php ENDPATH**/ ?>