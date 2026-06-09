<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Flip card ── */
.fc-scene { perspective: 1200px; }
.fc-inner { transform-style: preserve-3d; transition: transform 0.42s cubic-bezier(0.4,0,0.2,1); width:100%; height:100%; position:relative; }
.fc-inner.flipped { transform: rotateY(180deg); }
.fc-face { backface-visibility: hidden; -webkit-backface-visibility: hidden; position:absolute; inset:0; border-radius:1rem; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2rem; text-align:center; }
.fc-back { transform: rotateY(180deg); }

/* ── Print layout ── */
@media print {
    nav, .no-print, header { display:none !important; }
    .print-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; padding:16px; }
    .print-card { border:2px solid #e5e7eb; border-radius:8px; padding:16px; page-break-inside:avoid; }
    .print-card-q { font-size:12px; font-weight:600; color:#6b7280; margin-bottom:6px; }
    .print-card-front { font-size:15px; font-weight:600; color:#111827; margin-bottom:10px; border-bottom:1px solid #e5e7eb; padding-bottom:10px; }
    .print-card-a { font-size:12px; font-weight:600; color:#6b7280; margin-bottom:6px; }
    .print-card-back { font-size:14px; color:#374151; }
    .print-tag { font-size:11px; color:#4f46e5; margin-top:8px; }
    body { background:white !important; }
}
/* line-clamp polyfill for older browsers */
.line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="flashcardCreator()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap no-print">
                    <button type="button" @click="exportJSON()"
                            x-show="cards.length > 0"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        JSON
                    </button>
                    <button type="button" @click="exportCSV()"
                            x-show="cards.length > 0"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        CSV
                    </button>
                    <button type="button" @click="copyAll()"
                            x-show="cards.length > 0"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copySuccess ? 'Copied!' : 'Copy All'"></span>
                    </button>
                    <button type="button" @click="printCards()"
                            x-show="cards.length > 0"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    <button type="button" @click="clearDeck()"
                            x-show="cards.length > 0"
                            class="btn btn-sm border border-red-200 text-red-600 hover:bg-red-50">
                        Clear Deck
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

        
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mb-5 no-print" x-show="cards.length > 0" x-transition>
            <div class="card p-3 text-center">
                <p class="text-2xl font-bold text-gray-900" x-text="cards.length"></p>
                <p class="text-xs text-gray-400 mt-0.5">Total Cards</p>
            </div>
            <div class="card p-3 text-center">
                <p class="text-2xl font-bold text-brand-600" x-text="allTags.length"></p>
                <p class="text-xs text-gray-400 mt-0.5">Categories</p>
            </div>
            <div class="card p-3 text-center">
                <p class="text-2xl font-bold text-emerald-600" x-text="knownCount"></p>
                <p class="text-xs text-gray-400 mt-0.5">Known</p>
            </div>
            <div class="card p-3 text-center hidden sm:block">
                <p class="text-2xl font-bold text-amber-500" x-text="unknownCount"></p>
                <p class="text-xs text-gray-400 mt-0.5">Reviewing</p>
            </div>
            <div class="card p-3 text-center hidden sm:block">
                <p class="text-2xl font-bold text-gray-400" x-text="cards.length - knownCount - unknownCount"></p>
                <p class="text-xs text-gray-400 mt-0.5">Unseen</p>
            </div>
        </div>

        
        <div class="flex bg-gray-100 rounded-2xl p-1 gap-0.5 mb-6 no-print w-fit">
            <button type="button" @click="activeTab = 'create'"
                    class="px-5 py-2 rounded-xl text-sm font-medium transition-all duration-150"
                    :class="activeTab === 'create' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                ✏️ Create
            </button>
            <button type="button"
                    @click="cards.length ? (activeTab = 'study', resetStudyOnTabSwitch()) : null"
                    class="px-5 py-2 rounded-xl text-sm font-medium transition-all duration-150"
                    :class="activeTab === 'study' ? 'bg-brand-600 text-white shadow-sm' : cards.length ? 'text-gray-500 hover:text-gray-700' : 'text-gray-300 cursor-not-allowed'">
                🎓 Study
                <span x-show="cards.length > 0" class="ml-1 text-xs opacity-70" x-text="'(' + cards.length + ')'"></span>
            </button>
        </div>

        
        
        
        <div x-show="activeTab === 'create'">

            
            <div class="mb-5">
                <input type="text" x-model="deckTitle" @change="save()"
                       placeholder="Deck Title (e.g. Biology Chapter 3)"
                       class="w-full text-2xl font-bold text-gray-900 bg-transparent focus:outline-none border-b-2 border-dashed border-gray-200 focus:border-brand-300 pb-1.5 transition-colors placeholder-gray-300">
                <p class="text-xs text-gray-400 mt-1.5"
                   x-text="cards.length + ' ' + (cards.length === 1 ? 'card' : 'cards') + ' · saved locally'"></p>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

                
                <div class="lg:col-span-2" x-ref="formCard">
                    <div class="card">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700"
                                x-text="editingId ? '✏️ Edit Card' : '➕ Add Flashcard'"></h3>
                            <button type="button" x-show="editingId" @click="resetForm()"
                                    class="text-xs text-gray-400 hover:text-gray-600 font-medium transition-colors no-print">
                                Cancel edit
                            </button>
                        </div>

                        <form @submit.prevent="editingId ? updateCard() : addCard()" class="p-5 space-y-4">

                            
                            <div x-show="formError" x-transition class="alert alert-error text-xs" x-text="formError"></div>

                            
                            <div>
                                <label class="form-label">
                                    Front — Question / Term *
                                </label>
                                <textarea x-model="form.front" rows="4" required
                                          placeholder="Enter the question, term, or concept..."
                                          class="form-input resize-none"></textarea>
                            </div>

                            
                            <div>
                                <label class="form-label">
                                    Back — Answer / Definition *
                                </label>
                                <textarea x-model="form.back" rows="4" required
                                          placeholder="Enter the answer or definition..."
                                          class="form-input resize-none"></textarea>
                            </div>

                            
                            <div>
                                <label class="form-label">
                                    Category / Tag
                                    <span class="font-normal text-gray-400">(optional)</span>
                                </label>
                                <input type="text" x-model="form.tag"
                                       placeholder="e.g. Chapter 1, Biology, Vocab"
                                       class="form-input">
                                
                                <div x-show="allTags.length > 0" class="flex flex-wrap gap-1.5 mt-2">
                                    <template x-for="tag in allTags" :key="tag">
                                        <button type="button" @click="form.tag = tag"
                                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors"
                                                :class="form.tag === tag ? 'bg-brand-100 text-brand-700 ring-1 ring-brand-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                                x-text="tag"></button>
                                    </template>
                                </div>
                            </div>

                            
                            <div class="flex gap-2 pt-1">
                                <button type="submit" class="btn btn-primary flex-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span x-text="editingId ? 'Update Card' : 'Add Card'"></span>
                                </button>
                                <button type="button" @click="resetForm()" x-show="editingId"
                                        class="btn btn-secondary">Cancel</button>
                            </div>

                        </form>
                    </div>

                    
                    <div x-show="form.front.trim() || form.back.trim()" x-transition class="mt-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Preview</p>
                        <div class="card p-4 space-y-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-1">FRONT</p>
                                <p class="text-sm text-gray-800 font-medium"
                                   x-text="form.front || '—'"></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-1">BACK</p>
                                <p class="text-sm text-gray-600"
                                   x-text="form.back || '—'"></p>
                            </div>
                            <div x-show="form.tag">
                                <span class="badge badge-primary text-xs" x-text="form.tag"></span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-3">

                    
                    <div x-show="cards.length === 0"
                         class="card p-12 text-center h-full flex flex-col items-center justify-center">
                        <div class="text-5xl mb-4">🃏</div>
                        <p class="font-semibold text-gray-600 text-lg">No cards yet</p>
                        <p class="text-sm text-gray-400 mt-2">Add your first flashcard using the form.</p>
                    </div>

                    
                    <div x-show="cards.length > 0">

                        
                        <div class="flex flex-col sm:flex-row gap-2 mb-4 no-print">
                            <div class="relative flex-1">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                </svg>
                                <input type="text" x-model="searchQuery"
                                       placeholder="Search cards…"
                                       class="form-input pl-9 text-sm">
                            </div>
                            <select x-model="filterTag" class="form-input text-sm w-full sm:w-44"
                                    x-show="allTags.length > 0">
                                <option value="all">All Categories</option>
                                <template x-for="tag in allTags" :key="tag">
                                    <option :value="tag" x-text="tag"></option>
                                </template>
                            </select>
                        </div>

                        
                        <p class="text-xs text-gray-400 mb-3 no-print"
                           x-show="filteredCards.length !== cards.length"
                           x-text="filteredCards.length + ' of ' + cards.length + ' cards shown'"></p>

                        
                        <div x-show="filteredCards.length === 0 && cards.length > 0"
                             class="card p-8 text-center text-gray-400">
                            <p class="font-medium">No cards match your search.</p>
                            <button type="button" @click="searchQuery=''; filterTag='all'"
                                    class="mt-2 text-xs text-brand-600 hover:underline">Clear filters</button>
                        </div>

                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 print-grid">
                            <template x-for="card in filteredCards" :key="card.id">
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col print-card">

                                    
                                    <div class="px-4 pt-4 pb-0 flex items-start justify-between gap-2">
                                        <span x-show="card.tag"
                                              class="badge badge-primary text-xs flex-shrink-0"
                                              x-text="card.tag"></span>
                                        <span x-show="!card.tag" class="flex-1"></span>

                                        
                                        <div class="flex gap-1 flex-shrink-0 no-print"
                                             x-show="_deleteTarget !== card.id">
                                            <button type="button" @click="startEdit(card)"
                                                    title="Edit card"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-brand-600 hover:bg-brand-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            </button>
                                            <button type="button" @click="requestDelete(card.id)"
                                                    title="Delete card"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    
                                    <div class="px-4 pt-3 pb-0">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 print-card-q">Q</p>
                                        <p class="text-sm font-medium text-gray-900 line-clamp-3 print-card-front"
                                           x-text="card.front"></p>
                                    </div>

                                    <hr class="border-gray-100 mx-4 my-3">

                                    
                                    <div class="px-4 pb-4">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 print-card-a">A</p>
                                        <p class="text-sm text-gray-500 line-clamp-3 print-card-back"
                                           x-text="card.back"></p>
                                        <span x-show="card.tag" class="print-tag" style="display:none" x-text="'#' + card.tag"></span>
                                    </div>

                                    
                                    <div class="px-4 pb-3 no-print" x-show="knownSet[card.id] !== undefined">
                                        <span class="badge text-xs"
                                              :class="knownSet[card.id] ? 'badge-success' : 'badge-warning'"
                                              x-text="knownSet[card.id] ? '✓ Known' : '↻ Reviewing'"></span>
                                    </div>

                                    
                                    <div x-show="_deleteTarget === card.id"
                                         class="mx-3 mb-3 bg-red-50 rounded-xl p-3 flex items-center gap-2 no-print">
                                        <span class="text-xs text-red-700 flex-1">Delete this card?</span>
                                        <button type="button" @click="confirmDelete()"
                                                class="btn btn-sm bg-red-500 text-white text-xs">Yes</button>
                                        <button type="button" @click="cancelDelete()"
                                                class="btn btn-secondary btn-sm text-xs">No</button>
                                    </div>

                                </div>
                            </template>
                        </div>

                        
                        <div class="mt-5 text-center no-print" x-show="cards.length >= 1">
                            <button type="button"
                                    @click="activeTab = 'study'; resetStudyOnTabSwitch()"
                                    class="btn btn-primary">
                                🎓 Start Studying
                                <span class="ml-1 opacity-80 text-xs" x-text="'(' + cards.length + ' cards)'"></span>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        
        
        
        <div x-show="activeTab === 'study'" class="max-w-2xl mx-auto">

            
            <div x-show="cards.length === 0" class="card p-12 text-center">
                <div class="text-5xl mb-4">📚</div>
                <p class="font-semibold text-gray-600 text-lg">No cards to study yet</p>
                <p class="text-sm text-gray-400 mt-2">Go to the Create tab and add some flashcards first.</p>
                <button type="button" @click="activeTab = 'create'"
                        class="btn btn-primary mt-5">Create Cards</button>
            </div>

            <div x-show="cards.length > 0">

                
                <div x-show="studyComplete" x-transition class="card p-10 text-center">
                    <div class="text-5xl mb-4">🎉</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Deck Complete!</h2>
                    <p class="text-gray-500 mb-6">You've reviewed all <span x-text="cards.length"></span> cards.</p>
                    <div class="flex justify-center gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-emerald-600" x-text="knownCount"></p>
                            <p class="text-xs text-gray-400 mt-0.5">Known</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-amber-500" x-text="unknownCount"></p>
                            <p class="text-xs text-gray-400 mt-0.5">Reviewing</p>
                        </div>
                    </div>
                    <div class="flex justify-center gap-2 flex-wrap">
                        <button type="button" @click="restartStudy()" class="btn btn-primary">
                            🔄 Study Again
                        </button>
                        <button type="button" @click="studyUnknownOnly()" x-show="unknownCount > 0"
                                class="btn btn-secondary">
                            📌 Review Missed (<span x-text="unknownCount"></span>)
                        </button>
                    </div>
                </div>

                
                <div x-show="!studyComplete">

                    
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                            <span x-text="'Card ' + (studyIdx + 1) + ' of ' + studyOrder.length"></span>
                            <span class="flex items-center gap-3">
                                <span class="text-emerald-600 font-medium" x-text="'✓ ' + knownCount + ' known'"></span>
                                <span class="text-amber-500 font-medium" x-text="'↻ ' + unknownCount + ' reviewing'"></span>
                            </span>
                        </div>
                        <div class="bg-gray-100 rounded-full h-2 overflow-hidden">
                            
                            <div class="h-full flex">
                                <div class="bg-emerald-400 transition-all duration-300"
                                     :style="`width:${cards.length ? (knownCount / cards.length * 100) : 0}%`"></div>
                                <div class="bg-amber-400 transition-all duration-300"
                                     :style="`width:${cards.length ? (unknownCount / cards.length * 100) : 0}%`"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex justify-center mb-3 h-6">
                        <span x-show="studyCard && studyCard.tag"
                              class="badge badge-primary text-xs"
                              x-text="studyCard ? studyCard.tag : ''"></span>
                    </div>

                    
                    <div class="fc-scene cursor-pointer mb-3"
                         style="height:260px"
                         @click="flipCard()"
                         role="button"
                         :aria-label="isFlipped ? 'Card showing answer — click to flip back' : 'Card showing question — click to reveal answer'">
                        <div class="fc-inner" :class="isFlipped ? 'flipped' : ''">

                            
                            <div class="fc-face bg-white border border-gray-100 shadow-sm">
                                <div class="w-full">
                                    <p class="text-xs font-bold text-brand-400 uppercase tracking-widest text-center mb-4">Question</p>
                                    <p class="text-gray-900 font-semibold text-lg leading-relaxed text-center"
                                       x-text="studyCard ? studyCard.front : ''"></p>
                                </div>
                                <p class="absolute bottom-4 text-xs text-gray-300 font-medium">Tap to reveal answer</p>
                            </div>

                            
                            <div class="fc-back fc-face"
                                 style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                                <div class="w-full">
                                    <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest text-center mb-4">Answer</p>
                                    <p class="text-white font-semibold text-lg leading-relaxed text-center"
                                       x-text="studyCard ? studyCard.back : ''"></p>
                                </div>
                                <p class="absolute bottom-4 text-xs text-indigo-200 font-medium">Tap to flip back</p>
                            </div>

                        </div>
                    </div>

                    
                    <div class="flex items-center justify-center gap-3 mb-4 no-print">
                        <button type="button" @click="prevCard()"
                                :disabled="studyIdx === 0"
                                class="btn btn-secondary btn-sm disabled:opacity-40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Prev
                        </button>
                        <button type="button" @click="flipCard()"
                                class="btn btn-secondary px-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Flip
                        </button>
                        <button type="button" @click="nextCard()"
                                :disabled="studyIdx >= studyOrder.length - 1"
                                class="btn btn-secondary btn-sm disabled:opacity-40">
                            Next
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    
                    <div class="flex gap-3 mb-5 no-print">
                        <button type="button" @click="markKnown()"
                                class="flex-1 btn border-2 transition-all"
                                :class="studyCard && knownSet[studyCard.id] === true ? 'border-emerald-400 bg-emerald-50 text-emerald-700' : 'border-gray-200 bg-white text-gray-700 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Got it!
                        </button>
                        <button type="button" @click="markUnknown()"
                                class="flex-1 btn border-2 transition-all"
                                :class="studyCard && knownSet[studyCard.id] === false ? 'border-amber-400 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Still learning
                        </button>
                    </div>

                    
                    <div class="flex items-center justify-between flex-wrap gap-3 no-print">
                        <div class="flex gap-2">
                            <button type="button" @click="toggleShuffle()"
                                    class="btn btn-sm border transition-all"
                                    :class="isShuffled ? 'border-brand-300 bg-brand-50 text-brand-700' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'">
                                🔀 <span x-text="isShuffled ? 'Shuffled' : 'Shuffle'"></span>
                            </button>
                            <button type="button" @click="restartStudy()"
                                    class="btn btn-sm border border-gray-200 bg-white text-gray-600 hover:bg-gray-50">
                                🔄 Restart
                            </button>
                        </div>
                        <p class="text-xs text-gray-400">
                            ← → to navigate · Space to flip
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <p class="text-xs text-center text-gray-400 mt-8 no-print">
            🔒 Cards are saved locally in your browser. Nothing is sent to any server.
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
function flashcardCreator() {
    return {
        /* ── State ── */
        deckTitle:  'My Flashcard Deck',
        cards:      [],
        _nextId:    1,

        /* ── Form ── */
        form:       { front: '', back: '', tag: '' },
        editingId:  null,
        formError:  '',

        /* ── UI ── */
        activeTab:   'create',
        searchQuery: '',
        filterTag:   'all',

        /* ── Study ── */
        studyIdx:    0,
        isFlipped:   false,
        isShuffled:  false,
        studyOrder:  [],       /* array of card indices into this.cards */
        studyPool:   null,     /* null = all cards; array of ids = filtered pool */
        knownSet:    {},       /* { cardId: true | false } */

        /* ── Delete confirm ── */
        _deleteTarget: null,

        /* ── Export ── */
        copySuccess: false,

        /* ══ Computed ══ */

        get filteredCards() {
            var self = this;
            var list = this.cards;
            if (this.filterTag !== 'all') {
                list = list.filter(function(c) { return c.tag === self.filterTag; });
            }
            if (this.searchQuery.trim()) {
                var q = this.searchQuery.toLowerCase();
                list = list.filter(function(c) {
                    return c.front.toLowerCase().includes(q) ||
                           c.back.toLowerCase().includes(q) ||
                           (c.tag && c.tag.toLowerCase().includes(q));
                });
            }
            return list;
        },

        get allTags() {
            var tags = {};
            this.cards.forEach(function(c) { if (c.tag) tags[c.tag] = true; });
            return Object.keys(tags).sort();
        },

        get studyCard() {
            if (!this.studyOrder.length) return null;
            var cardIdx = this.studyOrder[Math.min(this.studyIdx, this.studyOrder.length - 1)];
            return this.cards[cardIdx] || null;
        },

        get knownCount() {
            var self = this;
            return Object.values(this.knownSet).filter(function(v) { return v === true; }).length;
        },

        get unknownCount() {
            return Object.values(this.knownSet).filter(function(v) { return v === false; }).length;
        },

        get studyComplete() {
            if (!this.cards.length || !this.studyOrder.length) return false;
            var self = this;
            return this.studyOrder.every(function(idx) {
                var card = self.cards[idx];
                return card && self.knownSet[card.id] !== undefined;
            });
        },

        /* ══ Lifecycle ══ */
        init() {
            this._load();
            this._buildStudyOrder();
            var self = this;
            document.addEventListener('keydown', function(e) { self._onKey(e); });
        },

        _onKey(e) {
            if (this.activeTab !== 'study' || !this.cards.length || this.studyComplete) return;
            if (e.key === ' ' || e.key === 'Spacebar') { e.preventDefault(); this.flipCard(); }
            if (e.key === 'ArrowRight') this.nextCard();
            if (e.key === 'ArrowLeft')  this.prevCard();
        },

        /* ══ Form operations ══ */
        addCard() {
            if (!this.form.front.trim() || !this.form.back.trim()) {
                this.formError = 'Both the question (front) and answer (back) are required.';
                return;
            }
            this.formError = '';
            this.cards.push({
                id:        this._nextId++,
                front:     this.form.front.trim(),
                back:      this.form.back.trim(),
                tag:       this.form.tag.trim(),
                createdAt: Date.now(),
            });
            this.cards = this.cards.slice(); /* trigger Alpine reactivity */
            this._buildStudyOrder();
            this.resetForm();
            this._save();
        },

        startEdit(card) {
            this.editingId    = card.id;
            this.form.front   = card.front;
            this.form.back    = card.back;
            this.form.tag     = card.tag || '';
            this.formError    = '';
            var self = this;
            this.$nextTick(function() {
                var el = self.$refs.formCard;
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },

        updateCard() {
            if (!this.form.front.trim() || !this.form.back.trim()) {
                this.formError = 'Both the question and answer are required.';
                return;
            }
            this.formError = '';
            var id   = this.editingId;
            var idx  = this.cards.findIndex(function(c) { return c.id === id; });
            if (idx !== -1) {
                this.cards[idx] = Object.assign({}, this.cards[idx], {
                    front: this.form.front.trim(),
                    back:  this.form.back.trim(),
                    tag:   this.form.tag.trim(),
                });
                this.cards = this.cards.slice();
            }
            this._buildStudyOrder();
            this.resetForm();
            this._save();
        },

        resetForm() {
            this.form.front = '';
            this.form.back  = '';
            this.form.tag   = '';
            this.editingId  = null;
            this.formError  = '';
        },

        /* ══ Delete ══ */
        requestDelete(id) { this._deleteTarget = id; },
        cancelDelete()    { this._deleteTarget = null; },
        confirmDelete() {
            var id = this._deleteTarget;
            this._deleteTarget = null;
            this.cards = this.cards.filter(function(c) { return c.id !== id; });
            var newKnown = {};
            var self = this;
            Object.keys(this.knownSet).forEach(function(k) { if (parseInt(k) !== id) newKnown[k] = self.knownSet[k]; });
            this.knownSet = newKnown;
            if (this.editingId === id) this.resetForm();
            this._buildStudyOrder();
            this._save();
        },

        /* ══ Study mode ══ */
        resetStudyOnTabSwitch() {
            /* Only reset card position + flip; preserve known/unknown */
            this.studyPool = null;
            this._buildStudyOrder();
            this.studyIdx  = 0;
            this.isFlipped = false;
        },

        _buildStudyOrder() {
            var len = this.cards.length;
            if (this.studyPool) {
                /* filtered pool (review missed only) */
                var self = this;
                var order = [];
                this.cards.forEach(function(c, i) { if (self.studyPool.indexOf(c.id) !== -1) order.push(i); });
                this.studyOrder = order;
            } else {
                this.studyOrder = Array.from({ length: len }, function(_, i) { return i; });
            }
            if (this.isShuffled) this._shuffle();
        },

        _shuffle() {
            var a = this.studyOrder;
            for (var i = a.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = a[i]; a[i] = a[j]; a[j] = t;
            }
        },

        flipCard() { this.isFlipped = !this.isFlipped; },

        nextCard() {
            if (this.studyIdx < this.studyOrder.length - 1) {
                this.studyIdx++;
                this.isFlipped = false;
            }
        },

        prevCard() {
            if (this.studyIdx > 0) {
                this.studyIdx--;
                this.isFlipped = false;
            }
        },

        markKnown() {
            if (!this.studyCard) return;
            var id = this.studyCard.id;
            this.knownSet = Object.assign({}, this.knownSet, { [id]: true });
            if (this.studyIdx < this.studyOrder.length - 1) {
                this.studyIdx++;
                this.isFlipped = false;
            }
        },

        markUnknown() {
            if (!this.studyCard) return;
            var id = this.studyCard.id;
            this.knownSet = Object.assign({}, this.knownSet, { [id]: false });
            if (this.studyIdx < this.studyOrder.length - 1) {
                this.studyIdx++;
                this.isFlipped = false;
            }
        },

        toggleShuffle() {
            this.isShuffled = !this.isShuffled;
            this._buildStudyOrder();
            this.studyIdx  = 0;
            this.isFlipped = false;
        },

        restartStudy() {
            this.knownSet  = {};
            this.studyPool = null;
            this._buildStudyOrder();
            this.studyIdx  = 0;
            this.isFlipped = false;
        },

        studyUnknownOnly() {
            var self = this;
            this.studyPool = this.cards
                .filter(function(c) { return self.knownSet[c.id] === false; })
                .map(function(c) { return c.id; });
            /* Reset known status for those cards */
            var newKnown = {};
            Object.keys(this.knownSet).forEach(function(k) {
                if (self.knownSet[k] !== false) newKnown[k] = self.knownSet[k];
            });
            this.knownSet = newKnown;
            this._buildStudyOrder();
            this.studyIdx  = 0;
            this.isFlipped = false;
        },

        /* ══ Persistence ══ */
        _save() {
            localStorage.setItem('flashcard_creator_data', JSON.stringify({
                deckTitle: this.deckTitle,
                cards:     this.cards,
                _nextId:   this._nextId,
            }));
        },

        save() { this._save(); },

        _load() {
            try {
                var raw = localStorage.getItem('flashcard_creator_data');
                if (raw) {
                    var d = JSON.parse(raw);
                    this.deckTitle = d.deckTitle || 'My Flashcard Deck';
                    this.cards     = d.cards     || [];
                    this._nextId   = d._nextId   || 1;
                }
            } catch(e) { /* ignore corrupt data */ }
        },

        /* ══ Export ══ */
        exportJSON() {
            var data = {
                deckTitle: this.deckTitle,
                cardCount: this.cards.length,
                cards: this.cards.map(function(c) {
                    return { front: c.front, back: c.back, tag: c.tag };
                }),
            };
            var blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            this._dl(blob, this._slug(this.deckTitle) + '_flashcards.json');
        },

        exportCSV() {
            var rows = [['Front (Question)', 'Back (Answer)', 'Tag / Category']];
            this.cards.forEach(function(c) { rows.push([c.front, c.back, c.tag || '']); });
            var csv = rows.map(function(r) {
                return r.map(function(v) { return '"' + String(v).replace(/"/g, '""') + '"'; }).join(',');
            }).join('\r\n');
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            this._dl(blob, this._slug(this.deckTitle) + '_flashcards.csv');
        },

        copyAll() {
            var text = this.cards.map(function(c, i) {
                return 'Card ' + (i + 1) + ':\nQ: ' + c.front + '\nA: ' + c.back + (c.tag ? '\nTag: ' + c.tag : '');
            }).join('\n\n');
            var self = this;
            navigator.clipboard.writeText(text).then(function() {
                self.copySuccess = true;
                setTimeout(function() { self.copySuccess = false; }, 2500);
            }).catch(function() {
                /* Fallback for browsers without clipboard API */
                var ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                self.copySuccess = true;
                setTimeout(function() { self.copySuccess = false; }, 2500);
            });
        },

        printCards() { window.print(); },

        clearDeck() {
            if (!confirm('Clear all ' + this.cards.length + ' cards and start fresh? This cannot be undone.')) return;
            this.cards     = [];
            this.deckTitle = 'My Flashcard Deck';
            this.knownSet  = {};
            this._nextId   = 1;
            this.studyOrder = [];
            this.studyIdx   = 0;
            this.isFlipped  = false;
            this.resetForm();
            this._save();
        },

        /* ══ Helpers ══ */
        _dl(blob, filename) {
            var a    = document.createElement('a');
            a.href   = URL.createObjectURL(blob);
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);
        },

        _slug(str) {
            return String(str).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'deck';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\flashcard-creator.blade.php ENDPATH**/ ?>