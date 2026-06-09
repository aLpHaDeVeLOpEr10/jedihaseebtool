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
         x-data="passwordStrengthChecker()">

        
        <div class="card p-6 mb-5">
            <label class="form-label">Enter your password</label>
            <div class="relative">
                <input
                    :type="showPassword ? 'text' : 'password'"
                    x-model="password"
                    @input="analyze()"
                    placeholder="Type a password to check its strength..."
                    class="form-input pr-12"
                    autocomplete="new-password"
                    spellcheck="false"
                >
                
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                    
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <p class="form-help flex items-center gap-1.5 mt-2">
                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Your password is <strong>never stored, sent, or logged</strong> — all checks run locally in your browser.
            </p>
        </div>

        
        <div x-show="password.length === 0" class="card p-10 text-center text-gray-400">
            <div class="text-4xl mb-3">🔐</div>
            <p class="text-sm">Type a password above to instantly check its strength.</p>
        </div>

        
        <div x-show="password.length > 0" x-transition class="space-y-4">

            
            <div class="card p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-700">Password Strength</span>
                    <span class="badge" :class="strengthBadgeClass" x-text="strengthLabel"></span>
                </div>

                
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div
                        class="h-2.5 rounded-full transition-all duration-500 ease-out"
                        :class="strengthBarColor"
                        :style="'width: ' + strengthPercent + '%'"
                    ></div>
                </div>

                
                <div class="grid grid-cols-4 text-xs text-gray-400 mt-1.5">
                    <span>Weak</span>
                    <span class="text-center">Medium</span>
                    <span class="text-center">Strong</span>
                    <span class="text-right">Very Strong</span>
                </div>
            </div>

            
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Requirements</h3>
                <ul class="space-y-3">
                    <template x-for="rule in rules" :key="rule.label">
                        <li class="flex items-center gap-3 text-sm">
                            
                            <span
                                class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center transition-colors duration-200"
                                :class="rule.passed ? 'bg-emerald-100' : 'bg-gray-100'"
                            >
                                
                                <svg x-show="rule.passed" class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                
                                <svg x-show="!rule.passed" class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <span
                                class="transition-colors duration-200"
                                :class="rule.passed ? 'text-gray-700' : 'text-gray-400'"
                                x-text="rule.label"
                            ></span>
                        </li>
                    </template>
                </ul>
            </div>

            
            <div x-show="suggestions.length > 0" class="card p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    💡 Suggestions to improve
                </h3>
                <ul class="space-y-2">
                    <template x-for="tip in suggestions" :key="tip">
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="tip"></span>
                        </li>
                    </template>
                </ul>
            </div>

            
            <div x-show="score >= 4" class="alert alert-success flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Great job! Your password meets all requirements and is very strong.
            </div>

        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-10">
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
 * A curated list of the most common passwords.
 * Checked client-side only — never sent anywhere.
 */
const COMMON_PASSWORDS = new Set([
    'password','123456','12345678','qwerty','abc123','monkey','1234567',
    'letmein','trustno1','dragon','master','sunshine','welcome','shadow',
    'superman','michael','football','iloveyou','starwars','password1',
    'passw0rd','qwerty123','111111','123123','admin','login','hello',
    '123456789','1234567890','password123','pass123','pass1234',
    'p@ssword','p@$$w0rd','baseball','access','batman','princess',
    '696969','123321','hottie','loveme','zxcvbnm','qazwsx','test',
    'guest','user','default','root','toor','admin123','administrator',
    'changeme','secret','temp','temp123','abc','qwerty1','password!',
]);

function passwordStrengthChecker() {
    return {
        password: '',
        showPassword: false,
        score: 0,        // 0 (Weak) → 5 (Very Strong)
        rules: [],
        suggestions: [],

        /* Label shown next to the progress bar */
        get strengthLabel() {
            if (this.score <= 1) return 'Weak';
            if (this.score === 2) return 'Medium';
            if (this.score === 3) return 'Strong';
            return 'Very Strong';
        },

        /* Width of the progress bar as a percentage */
        get strengthPercent() {
            return Math.max(8, (this.score / 5) * 100);
        },

        /* Progress bar fill colour */
        get strengthBarColor() {
            if (this.score <= 1) return 'bg-red-500';
            if (this.score === 2) return 'bg-amber-400';
            if (this.score === 3) return 'bg-emerald-400';
            return 'bg-emerald-600';
        },

        /* Badge colour class */
        get strengthBadgeClass() {
            if (this.score <= 1) return 'badge badge-danger';
            if (this.score === 2) return 'badge badge-warning';
            return 'badge badge-success';
        },

        /* Run all checks against the current password */
        analyze() {
            const p = this.password;

            const hasMinLength = p.length >= 8;
            const hasUpper     = /[A-Z]/.test(p);
            const hasLower     = /[a-z]/.test(p);
            const hasNumber    = /\d/.test(p);
            const hasSpecial   = /[^A-Za-z0-9]/.test(p);
            const notCommon    = !COMMON_PASSWORDS.has(p.toLowerCase());

            /* Update the visible checklist */
            this.rules = [
                { label: 'At least 8 characters',            passed: hasMinLength },
                { label: 'Uppercase letter (A–Z)',            passed: hasUpper     },
                { label: 'Lowercase letter (a–z)',            passed: hasLower     },
                { label: 'Number (0–9)',                      passed: hasNumber    },
                { label: 'Special character (!@#$%^&* …)',    passed: hasSpecial   },
                { label: 'Not a commonly-used password',      passed: notCommon    },
            ];

            /* Count how many criteria pass */
            const passed = [hasMinLength, hasUpper, hasLower, hasNumber, hasSpecial, notCommon]
                .filter(Boolean).length;

            /*
             * Score mapping:
             *   All 6 + length ≥ 12  → 5  (Very Strong)
             *   All 6                 → 4  (Very Strong)
             *   5 of 6               → 3  (Strong)
             *   4 of 6               → 2  (Medium)
             *   2–3 of 6             → 1  (Weak)
             *   0–1 of 6             → 0  (Weak)
             */
            if      (passed === 6 && p.length >= 12) this.score = 5;
            else if (passed === 6)                   this.score = 4;
            else if (passed === 5)                   this.score = 3;
            else if (passed === 4)                   this.score = 2;
            else if (passed >= 2)                    this.score = 1;
            else                                     this.score = 0;

            /* Build actionable suggestions for failing rules */
            this.suggestions = [];
            if (!hasMinLength)
                this.suggestions.push('Make it at least 8 characters long.');
            else if (p.length < 12)
                this.suggestions.push('Use 12+ characters for even greater security.');
            if (!hasUpper)
                this.suggestions.push('Add at least one uppercase letter (A–Z).');
            if (!hasLower)
                this.suggestions.push('Add at least one lowercase letter (a–z).');
            if (!hasNumber)
                this.suggestions.push('Include at least one number (0–9).');
            if (!hasSpecial)
                this.suggestions.push('Use special characters such as !@#$%^&* to boost strength.');
            if (!notCommon)
                this.suggestions.push('Avoid commonly used passwords — choose something more unique.');
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/tools/generated/password-strength-checker.blade.php ENDPATH**/ ?>