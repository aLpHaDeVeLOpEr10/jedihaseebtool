<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10"
         x-data="sha256Tool()">

        
        <div class="card p-6 mb-5">
            <div class="flex items-center justify-between mb-1.5">
                <label class="form-label mb-0">Input Text</label>
                <span class="text-xs text-gray-400" x-text="input.length + ' characters'"></span>
            </div>

            <textarea
                x-model="input"
                @input="onInput()"
                placeholder="Type or paste your text here…"
                rows="6"
                class="form-input resize-y"
                spellcheck="false"
            ></textarea>

            
            <p x-show="error" x-text="error" class="form-error"></p>

            
            <div class="flex items-center gap-2 mt-3">
                <button
                    type="button"
                    role="switch"
                    :aria-checked="autoHash"
                    @click="autoHash = !autoHash; autoHash && generate()"
                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                    :class="autoHash ? 'bg-brand-600' : 'bg-gray-200'"
                >
                    <span
                        class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200"
                        :class="autoHash ? 'translate-x-4' : 'translate-x-0'"
                    ></span>
                </button>
                <span class="text-xs text-gray-500">Auto-generate while typing</span>
            </div>

            
            <div class="flex flex-wrap gap-3 mt-4">
                <button @click="generate()" :disabled="loading" class="btn btn-primary">
                    
                    <svg x-show="loading" class="spinner w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span x-text="loading ? 'Hashing…' : 'Generate SHA-256'"></span>
                </button>

                <button
                    @click="copyResult()"
                    x-show="result"
                    class="btn btn-secondary"
                >
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <svg x-show="copied" class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Hash'"></span>
                </button>

                <button @click="clearAll()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        
        <div x-show="result" x-transition class="result-box result-animate mb-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-brand-700">SHA-256 Hash Result</span>
                <span class="badge badge-primary">256-bit · 64 hex chars</span>
            </div>
            <div class="bg-white/60 rounded-xl px-4 py-3 border border-brand-100">
                <code
                    class="block font-mono text-sm text-gray-900 break-all select-all"
                    x-text="result"
                ></code>
            </div>
            <p class="text-xs text-brand-600 mt-2">
                Click anywhere on the hash to select it, or use the Copy button above.
            </p>
        </div>

        
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About SHA-256</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    SHA-256 produces a fixed 256-bit (64 hex character) hash from any input.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Part of the SHA-2 family, standardised by NIST — considered cryptographically secure.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Even a tiny change in input produces a completely different hash (avalanche effect).
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Common uses: password hashing, digital signatures, TLS certificates, blockchain, and file integrity verification.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">🔒</span>
                    Your text is hashed entirely in your browser using the native <code class="text-brand-600">crypto.subtle</code> API — nothing is sent to any server.
                </li>
            </ul>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
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
/*
 * SHA-256 via the browser's native Web Crypto API (crypto.subtle).
 * Async, zero dependencies, runs entirely client-side.
 * Nothing is ever transmitted to any server.
 */
async function sha256(str) {
    const data   = new TextEncoder().encode(str);               // UTF-8 bytes
    const buffer = await crypto.subtle.digest('SHA-256', data); // 32-byte ArrayBuffer
    return Array.from(new Uint8Array(buffer))
                .map(b => b.toString(16).padStart(2, '0'))
                .join('');
}

/* ── Alpine.js component ─────────────────────────────────────────────── */
function sha256Tool() {
    return {
        input:    '',
        result:   '',
        error:    '',
        loading:  false,
        copied:   false,
        autoHash: false,
        _copyTimer: null,

        /* Called on every keystroke */
        onInput() {
            this.error = '';
            if (this.autoHash) this.generate();
        },

        async generate() {
            if (!this.input.trim()) {
                this.error  = 'Please enter some text before generating a hash.';
                this.result = '';
                return;
            }
            this.error   = '';
            this.loading = true;
            try {
                /* crypto.subtle.digest is async; use raw input to preserve whitespace */
                this.result = await sha256(this.input);
            } finally {
                this.loading = false;
            }
        },

        async copyResult() {
            if (!this.result) return;
            try {
                await navigator.clipboard.writeText(this.result);
            } catch {
                /* Fallback for browsers that block clipboard access */
                const el = Object.assign(document.createElement('textarea'), {
                    value: this.result,
                    style: 'position:fixed;opacity:0;pointer-events:none'
                });
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
            this.copied = true;
            clearTimeout(this._copyTimer);
            this._copyTimer = setTimeout(() => { this.copied = false; }, 2000);
        },

        clearAll() {
            this.input   = '';
            this.result  = '';
            this.error   = '';
            this.copied  = false;
            this.loading = false;
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\sha256-hash-generator.blade.php ENDPATH**/ ?>