<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
@media print {
    nav, header, .no-print { display:none !important; }
    .card { box-shadow:none !important; border:1px solid #e5e7eb !important; break-inside:avoid; }
    body { background:white !important; }
    .quiz-option { break-inside:avoid; }
}
.spin { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.opt-btn { transition: all 0.15s ease; }
.opt-btn:hover:not(:disabled) { transform: translateX(2px); }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="quizGenerator()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
                <div class="flex items-center gap-2 flex-wrap no-print" x-show="quiz">
                    <button type="button" @click="copyQuiz()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span x-text="copySuccess ? 'Copied!' : 'Copy'"></span>
                    </button>
                    <button type="button" @click="downloadTxt()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </button>
                    <button type="button" @click="window.print()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    <button type="button" @click="resetAll()"
                            class="btn btn-sm border border-red-200 text-red-600 hover:bg-red-50">
                        New Quiz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2 space-y-4 no-print">

                <div class="card">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">⚙️ Quiz Settings</h3>
                    </div>
                    <div class="p-5 space-y-4">

                        
                        <div x-show="formError" x-transition
                             class="alert alert-error text-xs" x-text="formError"></div>

                        
                        <div>
                            <label class="form-label">Topic / Subject *</label>
                            <input type="text" x-model="form.topic"
                                   placeholder="e.g. Biology, World War II, Python…"
                                   class="form-input">
                            <p class="form-help">Provide source text below for best results.</p>
                        </div>

                        
                        <div>
                            <label class="form-label">Question Type</label>
                            <div class="grid grid-cols-2 gap-1.5">
                                <template x-for="t in questionTypes" :key="t.value">
                                    <button type="button" @click="form.type = t.value"
                                            class="flex flex-col items-center py-2.5 px-2 rounded-xl border-2 text-xs font-medium transition-all"
                                            :class="form.type === t.value
                                                ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                        <span class="text-base mb-0.5" x-text="t.icon"></span>
                                        <span x-text="t.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">Difficulty</label>
                            <div class="flex gap-1.5">
                                <template x-for="d in ['easy','medium','hard']" :key="d">
                                    <button type="button" @click="form.difficulty = d"
                                            class="flex-1 py-2 rounded-xl border-2 text-xs font-semibold capitalize transition-all"
                                            :class="{
                                                'border-emerald-400 bg-emerald-50 text-emerald-700': form.difficulty === d && d === 'easy',
                                                'border-amber-400 bg-amber-50 text-amber-700': form.difficulty === d && d === 'medium',
                                                'border-red-400 bg-red-50 text-red-700': form.difficulty === d && d === 'hard',
                                                'border-gray-200 bg-white text-gray-500 hover:border-gray-300': form.difficulty !== d
                                            }"
                                            x-text="d"></button>
                                </template>
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Number of Questions</label>
                                <span class="text-sm font-bold text-brand-600" x-text="form.count"></span>
                            </div>
                            <input type="range" x-model.number="form.count"
                                   min="3" max="20" step="1"
                                   class="w-full h-1.5 bg-gray-200 rounded-full appearance-none cursor-pointer"
                                   style="accent-color:#4f46e5">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>3</span><span>10</span><span>20</span>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">
                                Source Text
                                <span class="font-normal text-gray-400">(recommended)</span>
                            </label>
                            <textarea x-model="form.sourceText" rows="6"
                                      placeholder="Paste a paragraph, article, or study notes here. The quiz will be generated from this content for best accuracy…"
                                      class="form-input resize-y text-sm leading-relaxed"></textarea>
                            <p class="form-help mt-1">
                                <span x-show="form.sourceText.trim().length === 0">Without source text, questions come from the built-in knowledge bank.</span>
                                <span x-show="form.sourceText.trim().length > 0 && form.sourceText.trim().length < 80" class="text-amber-600">Add more text for better questions (aim for 3+ sentences).</span>
                                <span x-show="form.sourceText.trim().length >= 80" class="text-emerald-600">✓ <span x-text="sourceWords + ' words — great! Questions will be extracted from your text.'"></span></span>
                            </p>
                        </div>

                        
                        <button type="button" @click="generate()"
                                :disabled="isGenerating"
                                class="btn btn-primary w-full">
                            <svg x-show="isGenerating" class="w-4 h-4 spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <svg x-show="!isGenerating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span x-text="isGenerating ? 'Generating…' : (quiz ? 'Regenerate' : 'Generate Quiz')"></span>
                        </button>

                        <button type="button" @click="clearForm()"
                                x-show="form.topic || form.sourceText"
                                class="btn btn-secondary w-full text-xs">
                            Clear Form
                        </button>

                    </div>
                </div>

                
                <div class="card p-4 bg-gradient-to-br from-brand-50 to-indigo-50 border-brand-100" x-show="!quiz">
                    <h4 class="text-xs font-semibold text-brand-700 mb-2">💡 Tips for Best Results</h4>
                    <ul class="space-y-1.5 text-xs text-brand-600">
                        <li>• Paste 3+ sentences of source text for accurate questions</li>
                        <li>• Use "Multiple Choice" for self-study and classroom quizzes</li>
                        <li>• "True/False" works great for review sessions</li>
                        <li>• Use "Mixed" to create variety in longer quizzes</li>
                        <li>• Increase difficulty for advanced students</li>
                    </ul>
                </div>

                
                <div class="card p-5 text-center" x-show="graded" x-transition>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Your Score</p>
                    <p class="text-4xl font-bold mb-1"
                       :class="scorePercent >= 80 ? 'text-emerald-600' : scorePercent >= 60 ? 'text-amber-500' : 'text-red-500'"
                       x-text="score + '/' + quiz.questions.length"></p>
                    <p class="text-lg font-semibold"
                       :class="scorePercent >= 80 ? 'text-emerald-600' : scorePercent >= 60 ? 'text-amber-500' : 'text-red-500'"
                       x-text="scorePercent + '%'"></p>
                    <p class="text-sm text-gray-500 mt-1" x-text="scoreLabel"></p>
                    <button type="button" @click="retakeQuiz()" class="btn btn-secondary btn-sm mt-3 w-full">
                        🔄 Retake Quiz
                    </button>
                </div>

            </div>

            
            <div class="lg:col-span-3">

                
                <div x-show="!quiz && !isGenerating" class="card p-12 text-center">
                    <div class="text-6xl mb-4">❓</div>
                    <p class="font-semibold text-gray-600 text-lg">Your quiz will appear here</p>
                    <p class="text-sm text-gray-400 mt-2 max-w-xs mx-auto">Fill in the settings and click Generate Quiz to create questions instantly.</p>
                </div>

                
                <div x-show="isGenerating" class="card p-8">
                    <div class="animate-pulse space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-100 rounded-xl"></div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                            </div>
                        </div>
                        <template x-for="i in [1,2,3]" :key="i">
                            <div class="space-y-2 border-t border-gray-100 pt-5">
                                <div class="h-4 bg-gray-200 rounded w-full"></div>
                                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                <div class="grid grid-cols-2 gap-2 mt-3">
                                    <div class="h-9 bg-gray-100 rounded-xl"></div>
                                    <div class="h-9 bg-gray-100 rounded-xl"></div>
                                    <div class="h-9 bg-gray-100 rounded-xl"></div>
                                    <div class="h-9 bg-gray-100 rounded-xl"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                
                <div x-show="quiz && !isGenerating" x-transition>

                    
                    <div class="card mb-4 overflow-hidden">
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                             style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%)">
                            <div>
                                <h2 class="text-lg font-bold text-white" x-text="quiz ? quiz.title : ''"></h2>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="text-xs text-indigo-200" x-text="quiz ? quiz.questions.length + ' questions' : ''"></span>
                                    <span class="text-indigo-300">·</span>
                                    <span class="text-xs text-indigo-200 capitalize" x-text="quiz ? quiz.difficulty : ''"></span>
                                    <span class="text-indigo-300">·</span>
                                    <span class="text-xs text-indigo-200 capitalize" x-text="quiz ? quiz.typeLabel : ''"></span>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-wrap no-print">
                                <button type="button" @click="showAnswerKey = !showAnswerKey"
                                        class="btn btn-sm bg-white/20 text-white hover:bg-white/30 border border-white/30"
                                        x-text="showAnswerKey ? '🙈 Hide Answers' : '🔑 Answer Key'">
                                </button>
                                <button type="button" @click="gradeQuiz()"
                                        x-show="!graded && hasAnswered"
                                        class="btn btn-sm bg-white text-brand-700 hover:bg-brand-50">
                                    ✓ Submit & Score
                                </button>
                            </div>
                        </div>

                        
                        <div class="px-5 py-2 bg-indigo-50 border-b border-indigo-100 text-xs text-indigo-600 flex items-center gap-1.5">
                            <span x-show="quiz && quiz.fromText">📄 Generated from your source text</span>
                            <span x-show="quiz && !quiz.fromText">📚 Generated from built-in knowledge bank</span>
                        </div>
                    </div>

                    
                    <div class="space-y-4">
                        <template x-for="(q, qi) in (quiz ? quiz.questions : [])" :key="q.id">
                            <div class="card p-5 quiz-option"
                                 :class="{
                                    'border-l-4 border-emerald-400': graded && isCorrect(q, qi),
                                    'border-l-4 border-red-400':    graded && !isCorrect(q, qi)
                                 }">

                                
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex items-start gap-2.5">
                                        <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold"
                                              :class="graded
                                                ? (isCorrect(q,qi) ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700')
                                                : 'bg-brand-100 text-brand-700'"
                                              x-text="qi + 1"></span>
                                        <p class="text-sm font-semibold text-gray-900 leading-snug" x-text="q.text"></p>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <span class="badge badge-gray text-xs" x-text="q.typeLabel"></span>
                                        <span x-show="graded" class="text-lg"
                                              x-text="isCorrect(q, qi) ? '✓' : '✗'"></span>
                                    </div>
                                </div>

                                
                                <div x-show="q.type === 'mcq'" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <template x-for="opt in q.options" :key="opt.label">
                                        <button type="button"
                                                class="opt-btn flex items-center gap-2.5 px-3 py-2.5 rounded-xl border-2 text-left text-sm transition-all"
                                                :disabled="graded"
                                                :class="optClass(q, qi, opt)"
                                                @click="!graded && selectAnswer(qi, opt.label)">
                                            <span class="flex-shrink-0 w-6 h-6 rounded-lg text-xs font-bold flex items-center justify-center"
                                                  :class="optLabelClass(q, qi, opt)"
                                                  x-text="opt.label"></span>
                                            <span class="flex-1 leading-tight" x-text="opt.text"></span>
                                            
                                            <span x-show="showAnswerKey && opt.label === q.correct"
                                                  class="flex-shrink-0 text-emerald-500 font-bold text-xs">✓</span>
                                        </button>
                                    </template>
                                </div>

                                
                                <div x-show="q.type === 'tf'" class="flex gap-3">
                                    <button type="button" @click="!graded && selectAnswer(qi, 'True')"
                                            :disabled="graded"
                                            class="opt-btn flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all"
                                            :class="tfBtnClass(q, qi, 'True')">
                                        ✓ True
                                        <span x-show="showAnswerKey && q.correct === 'True'" class="ml-1 text-xs">(correct)</span>
                                    </button>
                                    <button type="button" @click="!graded && selectAnswer(qi, 'False')"
                                            :disabled="graded"
                                            class="opt-btn flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all"
                                            :class="tfBtnClass(q, qi, 'False')">
                                        ✗ False
                                        <span x-show="showAnswerKey && q.correct === 'False'" class="ml-1 text-xs">(correct)</span>
                                    </button>
                                </div>

                                
                                <div x-show="q.type === 'short'">
                                    <textarea x-model="userAnswers[qi]"
                                              :disabled="graded"
                                              rows="2"
                                              placeholder="Type your answer here…"
                                              class="form-input text-sm resize-none w-full"></textarea>
                                    <div x-show="showAnswerKey" class="mt-2 p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-800">
                                        <strong>Model answer:</strong> <span x-text="q.answer"></span>
                                    </div>
                                    <div x-show="graded" class="mt-2 p-2.5 bg-blue-50 rounded-xl border border-blue-200 text-xs text-blue-700">
                                        ℹ Short answers are marked manually. Compare your answer with the key above.
                                    </div>
                                </div>

                                
                                <div x-show="(graded || showAnswerKey) && q.explanation"
                                     class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500 italic"
                                     x-text="'💡 ' + (q.explanation || '')"></div>

                            </div>
                        </template>
                    </div>

                    
                    <div class="flex flex-wrap gap-3 mt-5 no-print" x-show="quiz">
                        <button type="button" @click="gradeQuiz()"
                                x-show="!graded"
                                :disabled="!hasAnswered"
                                class="btn btn-primary flex-1 sm:flex-none disabled:opacity-50 disabled:cursor-not-allowed">
                            ✓ Submit & Check Score
                        </button>
                        <button type="button" @click="showAnswerKey = !showAnswerKey"
                                class="btn btn-secondary flex-1 sm:flex-none">
                            <span x-text="showAnswerKey ? '🙈 Hide Answer Key' : '🔑 Show Answer Key'"></span>
                        </button>
                        <button type="button" @click="retakeQuiz()" x-show="graded"
                                class="btn btn-secondary flex-1 sm:flex-none">
                            🔄 Retake
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <p class="text-xs text-center text-gray-400 mt-8 no-print">
            All quiz generation happens in your browser. No data is sent to any server.
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
/* ══════════════════════════════════════════════════════════
   BUILT-IN QUESTION BANK  (80 curated questions, 8 topics)
   ══════════════════════════════════════════════════════════ */
var QB = {
    science: [
        { q:"What planet is closest to the Sun?", a:"Mercury", opts:["Venus","Mercury","Mars","Jupiter"], correct:"B", diff:"easy", type:"mcq" },
        { q:"What gas do plants primarily absorb during photosynthesis?", a:"Carbon dioxide (CO₂)", opts:["Oxygen","Nitrogen","Carbon dioxide (CO₂)","Hydrogen"], correct:"C", diff:"easy", type:"mcq" },
        { q:"What is the powerhouse of the cell?", a:"Mitochondria", opts:["Nucleus","Mitochondria","Ribosome","Cell membrane"], correct:"B", diff:"medium", type:"mcq" },
        { q:"What is the atomic number of carbon?", a:"6", opts:["4","6","8","12"], correct:"B", diff:"medium", type:"mcq" },
        { q:"Newton's second law of motion states that force equals:", a:"Mass × Acceleration (F = ma)", opts:["Mass × Velocity","Mass × Acceleration (F = ma)","Mass / Acceleration","Momentum × Time"], correct:"B", diff:"hard", type:"mcq" },
        { q:"What type of chemical bond involves the sharing of electron pairs?", a:"Covalent bond", opts:["Ionic bond","Covalent bond","Metallic bond","Hydrogen bond"], correct:"B", diff:"hard", type:"mcq" },
        { q:"The Earth revolves around the Sun.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"Sound travels faster than light.", a:"False", correct:"False", diff:"easy", type:"tf" },
        { q:"Protons carry a negative electric charge.", a:"False", correct:"False", diff:"medium", type:"tf" },
        { q:"DNA stands for Deoxyribonucleic Acid.", a:"True", correct:"True", diff:"medium", type:"tf" },
    ],
    history: [
        { q:"In what year did World War I begin?", a:"1914", opts:["1910","1912","1914","1916"], correct:"C", diff:"easy", type:"mcq" },
        { q:"Who was the first president of the United States?", a:"George Washington", opts:["Abraham Lincoln","Thomas Jefferson","John Adams","George Washington"], correct:"D", diff:"easy", type:"mcq" },
        { q:"Who discovered America in 1492?", a:"Christopher Columbus", opts:["Amerigo Vespucci","Christopher Columbus","Vasco da Gama","Ferdinand Magellan"], correct:"B", diff:"medium", type:"mcq" },
        { q:"In what year did the Berlin Wall fall?", a:"1989", opts:["1987","1988","1989","1991"], correct:"C", diff:"medium", type:"mcq" },
        { q:"What treaty formally ended World War I?", a:"Treaty of Versailles", opts:["Treaty of Paris","Treaty of Versailles","Treaty of Westphalia","Treaty of Utrecht"], correct:"B", diff:"hard", type:"mcq" },
        { q:"In what year did the French Revolution begin?", a:"1789", opts:["1776","1789","1799","1804"], correct:"B", diff:"hard", type:"mcq" },
        { q:"World War II ended in 1945.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"Napoleon Bonaparte was born in France.", a:"False", correct:"False", diff:"medium", type:"tf", expl:"Napoleon was born in Corsica, which had recently been transferred to France from Genoa."},
        { q:"The Magna Carta was signed in 1215.", a:"True", correct:"True", diff:"medium", type:"tf" },
        { q:"Julius Caesar was the first Emperor of Rome.", a:"False", correct:"False", diff:"hard", type:"tf", expl:"Caesar was never officially emperor; Augustus (Octavian) was the first Roman Emperor." },
    ],
    geography: [
        { q:"What is the capital of France?", a:"Paris", opts:["Lyon","Paris","Marseille","Nice"], correct:"B", diff:"easy", type:"mcq" },
        { q:"What is the largest continent by area?", a:"Asia", opts:["Africa","North America","Asia","Europe"], correct:"C", diff:"easy", type:"mcq" },
        { q:"What is the capital of Australia?", a:"Canberra", opts:["Sydney","Melbourne","Brisbane","Canberra"], correct:"D", diff:"medium", type:"mcq" },
        { q:"What is the longest river in the world?", a:"The Nile River", opts:["Amazon River","Nile River","Yangtze River","Mississippi River"], correct:"B", diff:"medium", type:"mcq" },
        { q:"What country has the most natural lakes?", a:"Canada", opts:["Russia","USA","Canada","Finland"], correct:"C", diff:"hard", type:"mcq" },
        { q:"What is the smallest country in the world?", a:"Vatican City", opts:["Monaco","Liechtenstein","Vatican City","San Marino"], correct:"C", diff:"hard", type:"mcq" },
        { q:"The Pacific Ocean is the largest ocean on Earth.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"Brazil is the largest country in South America.", a:"True", correct:"True", diff:"medium", type:"tf" },
        { q:"The Sahara Desert is the largest hot desert in the world.", a:"True", correct:"True", diff:"medium", type:"tf" },
        { q:"The Ural Mountains separate North America from South America.", a:"False", correct:"False", diff:"hard", type:"tf", expl:"The Ural Mountains separate Europe from Asia, not the Americas." },
    ],
    math: [
        { q:"What is the value of π (pi) to two decimal places?", a:"3.14", opts:["3.12","3.14","3.16","3.18"], correct:"B", diff:"easy", type:"mcq" },
        { q:"What is 12 × 12?", a:"144", opts:["134","142","144","148"], correct:"C", diff:"easy", type:"mcq" },
        { q:"What is the formula for the area of a circle?", a:"πr²", opts:["2πr","πr²","πd","2πr²"], correct:"B", diff:"medium", type:"mcq" },
        { q:"If 2x + 6 = 14, what is x?", a:"4", opts:["3","4","5","6"], correct:"B", diff:"medium", type:"mcq" },
        { q:"What is the Pythagorean theorem?", a:"a² + b² = c²", opts:["a + b = c","a² − b² = c²","a² + b² = c²","ab = c²"], correct:"C", diff:"hard", type:"mcq" },
        { q:"What is the sum of interior angles of a regular hexagon?", a:"720°", opts:["540°","640°","720°","800°"], correct:"C", diff:"hard", type:"mcq" },
        { q:"A right angle measures exactly 90 degrees.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"The sum of interior angles in any triangle is always 180°.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"A prime number can be evenly divided by numbers other than 1 and itself.", a:"False", correct:"False", diff:"medium", type:"tf" },
        { q:"The square root of 225 is 15.", a:"True", correct:"True", diff:"medium", type:"tf" },
    ],
    technology: [
        { q:"What does 'CPU' stand for?", a:"Central Processing Unit", opts:["Core Processing Unit","Central Programming Unit","Central Processing Unit","Computer Power Unit"], correct:"C", diff:"easy", type:"mcq" },
        { q:"Who co-founded Microsoft?", a:"Bill Gates", opts:["Steve Jobs","Mark Zuckerberg","Elon Musk","Bill Gates"], correct:"D", diff:"easy", type:"mcq" },
        { q:"Which programming language was created by Guido van Rossum?", a:"Python", opts:["Java","Ruby","Python","Perl"], correct:"C", diff:"medium", type:"mcq" },
        { q:"In what year was the first iPhone released?", a:"2007", opts:["2005","2006","2007","2008"], correct:"C", diff:"medium", type:"mcq" },
        { q:"What is the binary representation of the decimal number 13?", a:"1101", opts:["1010","1100","1101","1111"], correct:"C", diff:"hard", type:"mcq" },
        { q:"Who invented the World Wide Web?", a:"Tim Berners-Lee", opts:["Bill Gates","Steve Jobs","Vint Cerf","Tim Berners-Lee"], correct:"D", diff:"hard", type:"mcq" },
        { q:"HTML stands for HyperText Markup Language.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"SQL is primarily used for managing relational databases.", a:"True", correct:"True", diff:"medium", type:"tf" },
        { q:"IPv6 addresses are 128 bits long.", a:"True", correct:"True", diff:"hard", type:"tf" },
        { q:"Python is a compiled programming language.", a:"False", correct:"False", diff:"medium", type:"tf", expl:"Python is an interpreted language, not compiled." },
    ],
    literature: [
        { q:"Who wrote 'Romeo and Juliet'?", a:"William Shakespeare", opts:["Charles Dickens","Mark Twain","William Shakespeare","Jane Austen"], correct:"C", diff:"easy", type:"mcq" },
        { q:"Who wrote the Harry Potter series?", a:"J.K. Rowling", opts:["Tolkien","C.S. Lewis","J.K. Rowling","Roald Dahl"], correct:"C", diff:"easy", type:"mcq" },
        { q:"Who wrote '1984'?", a:"George Orwell", opts:["Aldous Huxley","George Orwell","H.G. Wells","Ray Bradbury"], correct:"B", diff:"medium", type:"mcq" },
        { q:"What is a haiku?", a:"A 3-line poem with 5-7-5 syllables", opts:["A type of short story","A 3-line poem with 5-7-5 syllables","A type of novel","A dramatic monologue"], correct:"B", diff:"medium", type:"mcq" },
        { q:"Who wrote 'The Canterbury Tales'?", a:"Geoffrey Chaucer", opts:["John Milton","Geoffrey Chaucer","Edmund Spenser","Thomas More"], correct:"B", diff:"hard", type:"mcq" },
        { q:"Who wrote 'Crime and Punishment'?", a:"Fyodor Dostoevsky", opts:["Leo Tolstoy","Anton Chekhov","Ivan Turgenev","Fyodor Dostoevsky"], correct:"D", diff:"hard", type:"mcq" },
        { q:"'The Great Gatsby' was written by F. Scott Fitzgerald.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"'Pride and Prejudice' was written by Charlotte Brontë.", a:"False", correct:"False", diff:"medium", type:"tf", expl:"'Pride and Prejudice' was written by Jane Austen." },
        { q:"A 'protagonist' is the main character in a story.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"'Ulysses' by James Joyce is considered one of the most complex novels in the English language.", a:"True", correct:"True", diff:"hard", type:"tf" },
    ],
    health: [
        { q:"How many bones are in the adult human body?", a:"206", opts:["186","196","206","216"], correct:"C", diff:"easy", type:"mcq" },
        { q:"What organ pumps blood through the body?", a:"Heart", opts:["Lungs","Liver","Kidney","Heart"], correct:"D", diff:"easy", type:"mcq" },
        { q:"Which vitamin is primarily produced when skin is exposed to sunlight?", a:"Vitamin D", opts:["Vitamin A","Vitamin B12","Vitamin C","Vitamin D"], correct:"D", diff:"medium", type:"mcq" },
        { q:"What is the most common blood type worldwide?", a:"O positive", opts:["A positive","B positive","O positive","AB positive"], correct:"C", diff:"medium", type:"mcq" },
        { q:"What hormone regulates blood sugar levels?", a:"Insulin", opts:["Estrogen","Cortisol","Insulin","Glucagon"], correct:"C", diff:"hard", type:"mcq" },
        { q:"What is the medical term for the collarbone?", a:"Clavicle", opts:["Femur","Tibia","Scapula","Clavicle"], correct:"D", diff:"hard", type:"mcq" },
        { q:"The human heart has four chambers.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"The largest organ in the human body is the liver.", a:"False", correct:"False", diff:"medium", type:"tf", expl:"The skin is the largest organ of the human body." },
        { q:"Normal adult human body temperature is approximately 37°C (98.6°F).", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"Mature red blood cells contain a nucleus.", a:"False", correct:"False", diff:"hard", type:"tf", expl:"Mature red blood cells lose their nucleus during development to make room for haemoglobin." },
    ],
    general: [
        { q:"How many colors are in a rainbow?", a:"7", opts:["5","6","7","8"], correct:"C", diff:"easy", type:"mcq" },
        { q:"In what year did humans first land on the Moon?", a:"1969", opts:["1965","1967","1969","1971"], correct:"C", diff:"easy", type:"mcq" },
        { q:"What is the tallest mountain in the world?", a:"Mount Everest", opts:["K2","Kangchenjunga","Mount Everest","Lhotse"], correct:"C", diff:"medium", type:"mcq" },
        { q:"How many elements are on the periodic table?", a:"118", opts:["108","112","118","124"], correct:"C", diff:"medium", type:"mcq" },
        { q:"Who invented the telephone?", a:"Alexander Graham Bell", opts:["Thomas Edison","Nikola Tesla","Alexander Graham Bell","Guglielmo Marconi"], correct:"C", diff:"hard", type:"mcq" },
        { q:"What is the world's most spoken language by native speakers?", a:"Mandarin Chinese", opts:["English","Spanish","Hindi","Mandarin Chinese"], correct:"D", diff:"hard", type:"mcq" },
        { q:"The blue whale is the largest animal on Earth.", a:"True", correct:"True", diff:"easy", type:"tf" },
        { q:"Diamonds are made of carbon.", a:"True", correct:"True", diff:"medium", type:"tf" },
        { q:"The Great Wall of China can be seen from space with the naked eye.", a:"False", correct:"False", diff:"medium", type:"tf", expl:"This is a common myth. The wall is too narrow to be visible from space without optical aids." },
        { q:"In what year did the Titanic sink?", a:"1912", opts:["1908","1910","1912","1914"], correct:"C", diff:"hard", type:"mcq" },
    ],
};

/* ══════════════════════════════════════════════════════════
   ALPINE COMPONENT
   ══════════════════════════════════════════════════════════ */
function quizGenerator() {
    return {
        form: {
            topic:      '',
            type:       'mixed',
            difficulty: 'medium',
            count:      5,
            sourceText: '',
        },

        questionTypes: [
            { value: 'mcq',   label: 'Multiple Choice', icon: '🔤' },
            { value: 'tf',    label: 'True / False',    icon: '✅' },
            { value: 'short', label: 'Short Answer',    icon: '✍️'  },
            { value: 'mixed', label: 'Mixed',           icon: '🎲'  },
        ],

        quiz:          null,
        isGenerating:  false,
        formError:     '',
        showAnswerKey: false,

        /* Interactive quiz state */
        userAnswers: {},
        graded:      false,
        score:       0,

        copySuccess: false,

        /* ── Computed ── */
        get sourceWords() {
            return this.form.sourceText.trim().split(/\s+/).filter(Boolean).length;
        },
        get hasAnswered() {
            if (!this.quiz) return false;
            return Object.keys(this.userAnswers).length > 0;
        },
        get scorePercent() {
            if (!this.quiz || !this.quiz.questions.length) return 0;
            return Math.round((this.score / this.quiz.questions.length) * 100);
        },
        get scoreLabel() {
            var p = this.scorePercent;
            if (p === 100) return '🏆 Perfect score!';
            if (p >= 80)  return '🎉 Excellent work!';
            if (p >= 60)  return '👍 Good job!';
            if (p >= 40)  return '📚 Keep studying!';
            return '💪 Keep practicing!';
        },

        /* ── Init ── */
        init() { /* nothing to load from localStorage for this tool */ },

        /* ── Generate ── */
        generate() {
            var topic = this.form.topic.trim();
            if (!topic) { this.formError = 'Please enter a topic or subject.'; return; }
            this.formError = '';
            this.isGenerating = true;
            this.quiz = null;
            this.userAnswers = {};
            this.graded = false;
            this.showAnswerKey = false;
            var self = this;
            setTimeout(function() {
                try {
                    var q = self.form.sourceText.trim().length >= 60
                        ? self._generateFromText()
                        : self._generateFromBank();
                    self.quiz = q;
                } catch(e) {
                    self.formError = 'Generation failed. Try adjusting your settings.';
                }
                self.isGenerating = false;
            }, 700);
        },

        /* ── Generation: Source Text ── */
        _generateFromText() {
            var text  = this.form.sourceText.trim();
            var count = this.form.count;
            var type  = this.form.type;
            var diff  = this.form.difficulty;
            var topic = this.form.topic.trim();

            var sentences = this._extractSentences(text);
            if (sentences.length < 2) {
                /* Fall back to bank + source-style short answers */
                return this._generateFromBank();
            }

            /* Score and select sentences */
            var scored = sentences
                .map(function(s) { return { text: s, score: _scoreSentence(s, diff) }; })
                .sort(function(a,b) { return b.score - a.score; });

            /* Build word pool for distractors */
            var wordPool = this._buildWordPool(text);

            var questions = [];
            var usedSentences = {};
            var qId = 1;

            for (var si = 0; si < scored.length && questions.length < count; si++) {
                var s = scored[si].text;
                if (usedSentences[s]) continue;
                usedSentences[s] = true;

                var chosenType = this._pickType(type, qId);
                var q = this._sentenceToQuestion(s, chosenType, wordPool, qId++, text);
                if (q) questions.push(q);
            }

            /* Pad if not enough sentences */
            if (questions.length < count) {
                var bankQs = this._generateFromBank();
                var needed = count - questions.length;
                var extra = bankQs.questions.slice(0, needed).map(function(q, i) {
                    q.id = qId + i;
                    return q;
                });
                questions = questions.concat(extra);
            }

            return {
                title:     'Quiz: ' + topic,
                topic:     topic,
                difficulty: diff,
                type:      type,
                typeLabel: this._typeLabel(type),
                fromText:  true,
                questions: questions.slice(0, count),
            };
        },

        _extractSentences(text) {
            return text
                .replace(/([A-Z]{2,})\./g, '$1|')   /* protect acronyms */
                .split(/[.!?]+/)
                .map(function(s) { return s.replace(/\|/g, '.').trim(); })
                .filter(function(s) { return s.length > 25 && s.split(' ').length >= 5; });
        },

        _buildWordPool(text) {
            var words = text.split(/\s+/);
            var pool = { properNouns: [], numbers: [], nouns: [] };
            words.forEach(function(w) {
                var clean = w.replace(/[^a-zA-Z0-9\-]/g, '');
                if (!clean) return;
                if (/^\d{4}$/.test(clean) && parseInt(clean) >= 1000) pool.numbers.push(clean);
                else if (/^\d+$/.test(clean) && clean.length <= 6) pool.numbers.push(clean);
                else if (/^[A-Z][a-z]{2,}$/.test(clean)) pool.properNouns.push(clean);
            });
            pool.properNouns = [...new Set(pool.properNouns)];
            pool.numbers     = [...new Set(pool.numbers)];
            return pool;
        },

        _sentenceToQuestion(sentence, type, wordPool, id, fullText) {
            if (type === 'tf')    return this._makeTF(sentence, id);
            if (type === 'short') return this._makeShort(sentence, id);
            return this._makeMCQ(sentence, wordPool, id, fullText) || this._makeShort(sentence, id);
        },

        _makeTF(sentence, id) {
            var isFalse = (id % 3 === 0); /* every 3rd is false */
            var stmt = sentence;
            var correct = 'True';
            var expl = '';

            if (isFalse) {
                var falsified = _falsifySentence(sentence);
                if (falsified !== sentence) {
                    stmt = falsified;
                    correct = 'False';
                    expl = 'The original statement has been altered.';
                } else {
                    isFalse = false; /* couldn't falsify, keep as true */
                }
            }

            return {
                id: id, type: 'tf', typeLabel: 'T/F',
                text: 'True or False: ' + stmt,
                correct: correct, answer: correct, explanation: expl,
            };
        },

        _makeShort(sentence, id) {
            var q = sentence;
            /* Try to convert statement to question */
            var m;
            m = sentence.match(/^(.+?)\s+(is|was|are|were)\s+(.+)$/i);
            if (m) {
                var subj = m[1].replace(/^(the|a|an)\s+/i,'');
                q = 'What ' + m[2].toLowerCase() + ' ' + subj + '?';
                return { id:id, type:'short', typeLabel:'Short', text:q, answer:m[3].replace(/[,.]$/,''), correct:'', explanation:'' };
            }
            m = sentence.match(/^(.+?)\s+(discovered|invented|created|founded|wrote|built)\s+(.+)$/i);
            if (m) {
                q = 'Who ' + m[2].toLowerCase() + ' ' + m[3].replace(/[,.]$/,'') + '?';
                return { id:id, type:'short', typeLabel:'Short', text:q, answer:m[1], correct:'', explanation:'' };
            }
            return { id:id, type:'short', typeLabel:'Short',
                text: 'In your own words, describe: "' + sentence + '"',
                answer: sentence, correct:'', explanation:'' };
        },

        _makeMCQ(sentence, wordPool, id, fullText) {
            var extracted = _extractKeyTerm(sentence);
            if (!extracted) return null;

            var answer  = extracted.answer;
            var qText   = extracted.question;

            /* Build distractor pool */
            var distractors = [];
            var allWords = (wordPool.numbers || []).concat(wordPool.properNouns || []);
            allWords.forEach(function(w) {
                if (w.toLowerCase() !== answer.toLowerCase() && w.length > 1) distractors.push(w);
            });
            _shuffle(distractors);
            /* If pool is small, add generic alternatives */
            var genericAlt = ['None of the above', 'All of the above', 'Cannot be determined', 'Not applicable', 'Various factors'];
            if (distractors.length < 3) {
                for (var i = 0; i < genericAlt.length && distractors.length < 3; i++) {
                    if (genericAlt[i] !== answer) distractors.push(genericAlt[i]);
                }
            }
            distractors = distractors.slice(0, 3);

            var options = [answer].concat(distractors);
            _shuffle(options);

            var labels = ['A','B','C','D'];
            var correctLabel = labels[options.indexOf(answer)];

            return {
                id: id, type: 'mcq', typeLabel: 'MCQ',
                text: qText,
                answer: answer,
                options: options.map(function(o, i) { return { label: labels[i], text: o }; }),
                correct: correctLabel,
                explanation: '',
            };
        },

        /* ── Generation: Built-in Bank ── */
        _generateFromBank() {
            var topic = this.form.topic.toLowerCase();
            var diff  = this.form.difficulty;
            var type  = this.form.type;
            var count = this.form.count;

            /* Detect category */
            var cat = _detectCategory(topic);
            var bank = QB[cat] || QB.general;

            /* Filter by difficulty */
            var pool = bank.filter(function(q) {
                if (diff === 'easy')   return q.diff === 'easy';
                if (diff === 'hard')   return q.diff === 'hard';
                return true; /* medium: all */
            });

            /* Filter by question type */
            if (type !== 'mixed') {
                var filtered = pool.filter(function(q) { return q.type === type; });
                if (filtered.length >= Math.min(3, count)) pool = filtered;
            }

            /* If not enough, add from other category (general) */
            if (pool.length < count) {
                var extra = QB.general.filter(function(q) {
                    return pool.indexOf(q) === -1;
                });
                pool = pool.concat(extra);
            }

            _shuffle(pool);
            var selected = pool.slice(0, count);

            var qId = 1;
            var self = this;
            var questions = selected.map(function(item, idx) {
                var t = type === 'mixed' ? item.type : type;
                /* If requested type differs from bank type, adapt if possible */
                if (type === 'short' && item.type === 'mcq') {
                    return {
                        id: qId++, type:'short', typeLabel:'Short',
                        text: item.q, answer: item.a, correct:'', explanation: item.expl || '',
                    };
                }
                if (type === 'tf' && item.type === 'mcq') {
                    return {
                        id: qId++, type:'tf', typeLabel:'T/F',
                        text: 'True or False: The answer to "' + item.q + '" is ' + item.a + '.',
                        answer: item.a, correct:'True', explanation: item.expl || '',
                    };
                }
                if (item.type === 'mcq') {
                    var opts = item.opts.map(function(o, i) { return { label:['A','B','C','D'][i], text:o }; });
                    return {
                        id: qId++, type:'mcq', typeLabel:'MCQ',
                        text: item.q, answer: item.a, options: opts,
                        correct: item.correct, explanation: item.expl || '',
                    };
                }
                /* T/F */
                return {
                    id: qId++, type:'tf', typeLabel:'T/F',
                    text: 'True or False: ' + item.q,
                    answer: item.a, correct: item.correct, explanation: item.expl || '',
                };
            });

            return {
                title:     'Quiz: ' + this.form.topic.trim(),
                topic:     this.form.topic.trim(),
                difficulty: diff,
                type:      type,
                typeLabel: this._typeLabel(type),
                fromText:  false,
                questions: questions,
            };
        },

        _pickType(type, idx) {
            if (type !== 'mixed') return type;
            var cycle = ['mcq','tf','mcq','short','mcq','tf'];
            return cycle[(idx - 1) % cycle.length];
        },

        _typeLabel(t) {
            return { mcq:'Multiple Choice', tf:'True/False', short:'Short Answer', mixed:'Mixed' }[t] || t;
        },

        /* ── Interactive Quiz ── */
        selectAnswer(qi, val) {
            this.userAnswers = Object.assign({}, this.userAnswers, { [qi]: val });
        },

        gradeQuiz() {
            if (!this.quiz) return;
            var self = this;
            var correct = 0;
            this.quiz.questions.forEach(function(q, qi) {
                if (self.isCorrect(q, qi)) correct++;
            });
            this.score  = correct;
            this.graded = true;
            this.showAnswerKey = true;
        },

        isCorrect(q, qi) {
            var ans = this.userAnswers[qi];
            if (!ans) return false;
            if (q.type === 'short') return true; /* self-marked */
            return ans.toLowerCase() === (q.correct || '').toLowerCase();
        },

        retakeQuiz() {
            this.userAnswers = {};
            this.graded      = false;
            this.score       = 0;
            this.showAnswerKey = false;
        },

        resetAll() {
            this.quiz = null;
            this.userAnswers = {};
            this.graded = false;
            this.score  = 0;
            this.showAnswerKey = false;
            this.formError = '';
        },

        clearForm() {
            this.form.topic      = '';
            this.form.sourceText = '';
            this.formError = '';
        },

        /* ── Styling helpers ── */
        optClass(q, qi, opt) {
            var selected = this.userAnswers[qi] === opt.label;
            var isCorrect = opt.label === q.correct;

            if (this.graded) {
                if (isCorrect)  return 'border-emerald-400 bg-emerald-50 text-emerald-800';
                if (selected && !isCorrect) return 'border-red-400 bg-red-50 text-red-700';
                return 'border-gray-200 bg-white text-gray-500';
            }
            if (this.showAnswerKey && isCorrect) return 'border-emerald-400 bg-emerald-50 text-emerald-800';
            if (selected) return 'border-brand-400 bg-brand-50 text-brand-800';
            return 'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50';
        },

        optLabelClass(q, qi, opt) {
            var selected = this.userAnswers[qi] === opt.label;
            var isCorrect = opt.label === q.correct;

            if (this.graded) {
                if (isCorrect)  return 'bg-emerald-100 text-emerald-700';
                if (selected && !isCorrect) return 'bg-red-100 text-red-700';
                return 'bg-gray-100 text-gray-500';
            }
            if (this.showAnswerKey && isCorrect) return 'bg-emerald-100 text-emerald-700';
            if (selected) return 'bg-brand-100 text-brand-700';
            return 'bg-gray-100 text-gray-600';
        },

        tfBtnClass(q, qi, val) {
            var selected = this.userAnswers[qi] === val;
            var isCorrect = val === q.correct;

            if (this.graded) {
                if (isCorrect)  return 'border-emerald-400 bg-emerald-50 text-emerald-700';
                if (selected)   return 'border-red-400 bg-red-50 text-red-700';
                return 'border-gray-200 bg-white text-gray-500';
            }
            if (this.showAnswerKey && isCorrect) return 'border-emerald-400 bg-emerald-50 text-emerald-700';
            if (selected) return 'border-brand-400 bg-brand-50 text-brand-800';
            var base = 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50 ';
            return base + (val === 'True' ? 'hover:border-emerald-300' : 'hover:border-red-300');
        },

        /* ── Export ── */
        copyQuiz() {
            if (!this.quiz) return;
            var text = this._quizToText();
            var self = this;
            navigator.clipboard.writeText(text).then(function() {
                self.copySuccess = true;
                setTimeout(function() { self.copySuccess = false; }, 2500);
            }).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
                self.copySuccess = true;
                setTimeout(function() { self.copySuccess = false; }, 2500);
            });
        },

        downloadTxt() {
            if (!this.quiz) return;
            var text = this._quizToText(true);
            var blob = new Blob([text], { type: 'text/plain;charset=utf-8;' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = (this.quiz.topic || 'quiz').toLowerCase().replace(/\s+/g,'-') + '-quiz.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(a.href);
        },

        _quizToText(includeAnswerKey) {
            if (!this.quiz) return '';
            var q = this.quiz;
            var lines = [
                q.title.toUpperCase(),
                'Difficulty: ' + q.difficulty + ' | Type: ' + q.typeLabel + ' | Questions: ' + q.questions.length,
                '='.repeat(60), ''
            ];
            q.questions.forEach(function(qu, i) {
                lines.push((i+1) + '. ' + qu.text);
                if (qu.type === 'mcq' && qu.options) {
                    qu.options.forEach(function(o) { lines.push('   ' + o.label + ') ' + o.text); });
                } else if (qu.type === 'tf') {
                    lines.push('   A) True   B) False');
                } else {
                    lines.push('   Answer: ___________________________');
                }
                lines.push('');
            });
            if (includeAnswerKey !== false) {
                lines.push('', '─'.repeat(60), 'ANSWER KEY', '─'.repeat(60));
                q.questions.forEach(function(qu, i) {
                    var ans = qu.type === 'mcq' ? (qu.correct + ') ' + (qu.options ? (qu.options.find(function(o){return o.label===qu.correct;})||{}).text : qu.answer) : '') : qu.answer || qu.correct;
                    lines.push((i+1) + '. ' + ans);
                    if (qu.explanation) lines.push('   (' + qu.explanation + ')');
                });
            }
            return lines.join('\n');
        },
    };
}

/* ══ Pure helper functions (outside Alpine, no `this`) ══ */
function _scoreSentence(sentence, diff) {
    var score = 0;
    var words = sentence.split(' ').length;
    if (diff === 'easy' && words < 15) score += 10;
    if (diff === 'hard' && words > 12) score += 10;
    if (diff === 'medium') score += 5;
    if (/\b\d{4}\b/.test(sentence)) score += 8;
    if (/[A-Z][a-z]{2,}(\s+[A-Z][a-z]+)+/.test(sentence)) score += 6;
    if (/\b\d+\b/.test(sentence)) score += 3;
    /* Penalise sentences starting with pronouns */
    if (/^(He|She|It|They|This|That|These|Those|His|Her)\b/i.test(sentence)) score -= 6;
    return score;
}

function _extractKeyTerm(sentence) {
    var m;
    /* "X is/was Y" */
    m = sentence.match(/^(.{4,60}?)\s+(is|was|are|were)\s+(.{2,50})$/i);
    if (m) return { question: 'What ' + m[2].toLowerCase() + ' ' + m[1].trim() + '?', answer: m[3].replace(/[,.]$/,'').trim() };

    /* "X discovered/invented Y" */
    m = sentence.match(/^(.{3,50}?)\s+(discovered|invented|created|founded|built|wrote|developed)\s+(.{2,50})$/i);
    if (m) return { question: 'Who ' + m[2].toLowerCase() + ' ' + m[3].replace(/[,.]$/,'').trim() + '?', answer: m[1].trim() };

    /* "X happened in YEAR" */
    m = sentence.match(/^(.+)\s+(?:in|on|during)\s+(\d{4})\.?$/i);
    if (m) return { question: 'In what year did ' + m[1].trim().toLowerCase() + '?', answer: m[2] };

    /* Fill-in-the-blank: replace last 1-3 words */
    var words = sentence.replace(/[.,;!?]$/,'').split(' ');
    if (words.length >= 6) {
        var tailLen = words.length > 8 ? 2 : 1;
        var answer  = words.slice(-tailLen).join(' ');
        var stem    = words.slice(0, -tailLen).join(' ');
        if (answer.length > 2 && /[a-zA-Z]/.test(answer)) {
            return { question: stem + ' ___?', answer: answer };
        }
    }
    return null;
}

function _falsifySentence(sentence) {
    /* Try to swap a year */
    var m = sentence.match(/\b(\d{4})\b/);
    if (m) {
        var y = parseInt(m[1]);
        var fakeY = y + (Math.random() > 0.5 ? 50 : -50);
        return sentence.replace(m[1], fakeY.toString());
    }
    /* Swap "is" → "is not" */
    if (/\b(is|was)\b/.test(sentence)) {
        return sentence.replace(/\b(is|was)\b/, '$1 not');
    }
    /* Swap a capitalized word */
    var cap = sentence.match(/\b([A-Z][a-z]{3,})\b/g);
    if (cap && cap.length >= 2) {
        var a = cap[0], b = cap[1];
        return sentence.replace(a, b).replace(b, a);
    }
    return sentence; /* can't falsify */
}

function _detectCategory(topic) {
    var t = topic.toLowerCase();
    if (/sci|bio|chem|phys|natur|cell|organism|ecosystem|atom|molecule|evolution/.test(t)) return 'science';
    if (/hist|war|ancient|civil|empire|revolution|dynasty|colonial|medieval/.test(t)) return 'history';
    if (/geo|country|capital|continent|ocean|mountain|river|island|nation|map/.test(t)) return 'geography';
    if (/math|algebra|calcul|geometr|statistic|number|equation|formula|arithmet/.test(t)) return 'math';
    if (/tech|comput|program|software|internet|network|digital|code|algorithm|cyber/.test(t)) return 'technology';
    if (/lit|book|author|novel|poem|poet|story|fiction|shakespeare|grammar|english/.test(t)) return 'literature';
    if (/health|med|body|biology|disease|anatomy|physiol|nutrition|vitamin|organ/.test(t)) return 'health';
    return 'general';
}

function _shuffle(arr) {
    for (var i = arr.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
    }
    return arr;
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\quiz-generator.blade.php ENDPATH**/ ?>