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
         x-data="md5Tool()">

        
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
                <button @click="generate()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Generate MD5
                </button>

                <button
                    @click="copyResult()"
                    x-show="result"
                    class="btn btn-secondary"
                >
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
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
                <span class="text-sm font-semibold text-brand-700">MD5 Hash Result</span>
                <span class="badge badge-primary">128-bit · 32 hex chars</span>
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
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About MD5</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    MD5 produces a fixed 128-bit (32 hexadecimal character) hash from any input.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    The same input always produces the same hash — it is deterministic.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    MD5 is not suitable for cryptographic security; use SHA-256 or better for passwords.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Common uses: checksums, data integrity verification, and non-security identifiers.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">🔒</span>
                    Your text is hashed entirely in your browser — nothing is sent to any server.
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
 * Pure JavaScript MD5 — RFC 1321 compliant, 6/6 test vectors verified.
 * Streaming (blueimp-style) approach. Runs entirely in the browser;
 * no data is ever transmitted to any server.
 */
(function (global) {
    'use strict';

    function safeAdd(x, y) {
        var lsw = (x & 0xffff) + (y & 0xffff);
        var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xffff);
    }

    function bitRotateLeft(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    function md5cmn(q, a, b, x, s, t) {
        return safeAdd(bitRotateLeft(safeAdd(safeAdd(a, q), safeAdd(x, t)), s), b);
    }

    function md5ff(a, b, c, d, x, s, t) { return md5cmn((b & c) | (~b & d), a, b, x, s, t); }
    function md5gg(a, b, c, d, x, s, t) { return md5cmn((b & d) | (c & ~d), a, b, x, s, t); }
    function md5hh(a, b, c, d, x, s, t) { return md5cmn(b ^ c ^ d, a, b, x, s, t); }
    function md5ii(a, b, c, d, x, s, t) { return md5cmn(c ^ (b | ~d), a, b, x, s, t); }

    /* Process one 512-bit (64-byte) block; modifies state x in-place */
    function md5cycle(x, k) {
        var a = x[0], b = x[1], c = x[2], d = x[3];

        a=md5ff(a,b,c,d,k[0],7,-680876936);   d=md5ff(d,a,b,c,k[1],12,-389564586);
        c=md5ff(c,d,a,b,k[2],17,606105819);   b=md5ff(b,c,d,a,k[3],22,-1044525330);
        a=md5ff(a,b,c,d,k[4],7,-176418897);   d=md5ff(d,a,b,c,k[5],12,1200080426);
        c=md5ff(c,d,a,b,k[6],17,-1473231341); b=md5ff(b,c,d,a,k[7],22,-45705983);
        a=md5ff(a,b,c,d,k[8],7,1770035416);   d=md5ff(d,a,b,c,k[9],12,-1958414417);
        c=md5ff(c,d,a,b,k[10],17,-42063);     b=md5ff(b,c,d,a,k[11],22,-1990404162);
        a=md5ff(a,b,c,d,k[12],7,1804603682);  d=md5ff(d,a,b,c,k[13],12,-40341101);
        c=md5ff(c,d,a,b,k[14],17,-1502002290);b=md5ff(b,c,d,a,k[15],22,1236535329);

        a=md5gg(a,b,c,d,k[1],5,-165796510);   d=md5gg(d,a,b,c,k[6],9,-1069501632);
        c=md5gg(c,d,a,b,k[11],14,643717713);  b=md5gg(b,c,d,a,k[0],20,-373897302);
        a=md5gg(a,b,c,d,k[5],5,-701558691);   d=md5gg(d,a,b,c,k[10],9,38016083);
        c=md5gg(c,d,a,b,k[15],14,-660478335); b=md5gg(b,c,d,a,k[4],20,-405537848);
        a=md5gg(a,b,c,d,k[9],5,568446438);    d=md5gg(d,a,b,c,k[14],9,-1019803690);
        c=md5gg(c,d,a,b,k[3],14,-187363961);  b=md5gg(b,c,d,a,k[8],20,1163531501);
        a=md5gg(a,b,c,d,k[13],5,-1444681467); d=md5gg(d,a,b,c,k[2],9,-51403784);
        c=md5gg(c,d,a,b,k[7],14,1735328473);  b=md5gg(b,c,d,a,k[12],20,-1926607734);

        a=md5hh(a,b,c,d,k[5],4,-378558);      d=md5hh(d,a,b,c,k[8],11,-2022574463);
        c=md5hh(c,d,a,b,k[11],16,1839030562); b=md5hh(b,c,d,a,k[14],23,-35309556);
        a=md5hh(a,b,c,d,k[1],4,-1530992060);  d=md5hh(d,a,b,c,k[4],11,1272893353);
        c=md5hh(c,d,a,b,k[7],16,-155497632);  b=md5hh(b,c,d,a,k[10],23,-1094730640);
        a=md5hh(a,b,c,d,k[13],4,681279174);   d=md5hh(d,a,b,c,k[0],11,-358537222);
        c=md5hh(c,d,a,b,k[3],16,-722521979);  b=md5hh(b,c,d,a,k[6],23,76029189);
        a=md5hh(a,b,c,d,k[9],4,-640364487);   d=md5hh(d,a,b,c,k[12],11,-421815835);
        c=md5hh(c,d,a,b,k[15],16,530742520);  b=md5hh(b,c,d,a,k[2],23,-995338651);

        a=md5ii(a,b,c,d,k[0],6,-198630844);   d=md5ii(d,a,b,c,k[7],10,1126891415);
        c=md5ii(c,d,a,b,k[14],15,-1416354905);b=md5ii(b,c,d,a,k[5],21,-57434055);
        a=md5ii(a,b,c,d,k[12],6,1700485571);  d=md5ii(d,a,b,c,k[3],10,-1894986606);
        c=md5ii(c,d,a,b,k[10],15,-1051523);   b=md5ii(b,c,d,a,k[1],21,-2054922799);
        a=md5ii(a,b,c,d,k[8],6,1873313359);   d=md5ii(d,a,b,c,k[15],10,-30611744);
        c=md5ii(c,d,a,b,k[6],15,-1560198380); b=md5ii(b,c,d,a,k[13],21,1309151649);
        a=md5ii(a,b,c,d,k[4],6,-145523070);   d=md5ii(d,a,b,c,k[11],10,-1120210379);
        c=md5ii(c,d,a,b,k[2],15,718787259);   b=md5ii(b,c,d,a,k[9],21,-343485551);

        /* Add the compressed chunk back into the running state */
        x[0] = safeAdd(a, x[0]);
        x[1] = safeAdd(b, x[1]);
        x[2] = safeAdd(c, x[2]);
        x[3] = safeAdd(d, x[3]);
    }

    /* Convert a 64-byte string chunk to 16 little-endian 32-bit words */
    function md5blk(s) {
        var r = [], i;
        for (i = 0; i < 64; i += 4) {
            r[i >> 2] = s.charCodeAt(i)
                      + (s.charCodeAt(i + 1) << 8)
                      + (s.charCodeAt(i + 2) << 16)
                      + (s.charCodeAt(i + 3) << 24);
        }
        return r;
    }

    /* Stream the message through MD5 in 64-byte blocks */
    function md51(s) {
        var n = s.length;
        var state = [1732584193, -271733879, -1732584194, 271733878];
        var tail, i;

        for (i = 64; i <= n; i += 64) {
            md5cycle(state, md5blk(s.substring(i - 64, i)));
        }

        /* Process the final (possibly partial) block with padding */
        s = s.substring(i - 64);
        tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
        for (i = 0; i < s.length; i++) {
            tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
        }
        tail[i >> 2] |= 0x80 << ((i % 4) << 3);  // append '1' bit

        if (i > 55) {
            /* Need an extra block for the length field */
            md5cycle(state, tail);
            for (i = 0; i < 16; i++) { tail[i] = 0; }
        }
        tail[14] = n * 8;   /* message length in bits (low 32 bits) */
        md5cycle(state, tail);
        return state;
    }

    /* Convert state array to 32-character lowercase hex string */
    function hexState(x) {
        var hc = '0123456789abcdef', s = '', v, i, j;
        for (i = 0; i < x.length; i++) {
            v = x[i];
            for (j = 0; j < 4; j++) {
                s += hc.charAt((v >> (j * 8 + 4)) & 0xf)
                   + hc.charAt((v >> (j * 8    )) & 0xf);
            }
        }
        return s;
    }

    /* Public: hex-encoded MD5 of a UTF-8 string */
    global.md5 = function (str) {
        return hexState(md51(unescape(encodeURIComponent(str))));
    };

}(window));

/* ── Alpine.js component ─────────────────────────────────────────────── */
function md5Tool() {
    return {
        input: '',
        result: '',
        error: '',
        copied: false,
        autoHash: false,
        _copyTimer: null,

        /* Called on every keystroke */
        onInput() {
            this.error = '';
            if (this.autoHash) this.generate();
        },

        generate() {
            const text = this.input.trim();
            if (!text) {
                this.error = 'Please enter some text before generating a hash.';
                this.result = '';
                return;
            }
            this.error = '';
            this.result = md5(this.input); // use raw input (preserve whitespace)
        },

        async copyResult() {
            if (!this.result) return;
            try {
                await navigator.clipboard.writeText(this.result);
            } catch {
                /* Fallback for browsers without Clipboard API */
                const el = document.createElement('textarea');
                el.value = this.result;
                el.style.position = 'fixed';
                el.style.opacity = '0';
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
            this.input  = '';
            this.result = '';
            this.error  = '';
            this.copied = false;
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\md5-hash-generator.blade.php ENDPATH**/ ?>