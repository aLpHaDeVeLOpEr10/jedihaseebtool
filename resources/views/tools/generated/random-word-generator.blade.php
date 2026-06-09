@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50" x-data="randomWordGen()" x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-5">

        {{-- ══════════════════════════
             CONFIGURATION CARD
             ══════════════════════════ --}}
        <div class="card p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">

                {{-- Word Count --}}
                <div>
                    <label class="form-label">
                        Number of Words
                        <span class="ml-1 font-bold text-brand-600" x-text="count"></span>
                    </label>
                    <input type="range" min="1" max="100" step="1"
                           x-model.number="count"
                           class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200">
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>1</span>
                        <div class="flex gap-2">
                            <template x-for="n in [10,20,30,50]" :key="n">
                                <button type="button" @click="count = n"
                                        class="px-2 py-0.5 rounded-lg transition-colors font-medium"
                                        :class="count===n ? 'bg-brand-100 text-brand-700' : 'text-gray-400 hover:text-brand-600'"
                                        x-text="n">
                                </button>
                            </template>
                        </div>
                        <span>100</span>
                    </div>
                </div>

                {{-- Starting Letter --}}
                <div>
                    <label class="form-label">
                        Starting Letter
                        <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <input type="text"
                           x-model="startLetter"
                           @input="startLetter = startLetter.replace(/[^a-zA-Z]/g,'').slice(0,1).toUpperCase()"
                           placeholder="Any letter (A–Z) or leave blank"
                           maxlength="1"
                           class="form-input uppercase tracking-widest font-semibold text-center text-lg"
                           :class="letterError ? 'border-red-400 focus:border-red-400' : ''">
                    <p x-show="letterError" x-transition class="form-error" x-text="letterError"></p>
                    <p class="form-help">Only A–Z accepted. Leave blank for all letters.</p>
                </div>
            </div>

            {{-- Part of Speech --}}
            <div class="mb-4">
                <label class="form-label">Part of Speech</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="pos in partsOfSpeech" :key="pos.value">
                        <button type="button" @click="partOfSpeech = pos.value"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                :class="partOfSpeech === pos.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span x-text="pos.icon"></span>
                            <span x-text="pos.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Word Length --}}
            <div class="mb-4">
                <label class="form-label">Word Length</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="wl in wordLengths" :key="wl.value">
                        <button type="button" @click="wordLength = wl.value"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                :class="wordLength === wl.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span x-text="wl.icon"></span>
                            <span x-text="wl.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Difficulty --}}
            <div class="mb-5">
                <label class="form-label">Difficulty / Complexity</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="d in difficulties" :key="d.value">
                        <button type="button" @click="difficulty = d.value"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                :class="difficulty === d.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span x-text="d.icon"></span>
                            <span x-text="d.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Sort Order --}}
            <div class="mb-5">
                <label class="form-label">Sort Order</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="s in sortOptions" :key="s.value">
                        <button type="button" @click="sortOrder = s.value"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                :class="sortOrder === s.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span x-text="s.icon"></span>
                            <span x-text="s.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Display Style --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl mb-5">
                <div>
                    <p class="text-sm font-medium text-gray-700">Show POS badge on each word</p>
                    <p class="text-xs text-gray-400">Displays noun / verb / adjective / adverb label</p>
                </div>
                <button type="button" @click="showBadge = !showBadge"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                        :class="showBadge ? 'bg-brand-600' : 'bg-gray-200'">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                          :class="showBadge ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
            </div>

            {{-- Error --}}
            <div x-show="poolError" x-transition
                 class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-amber-700" x-text="poolError"></p>
            </div>

            {{-- Generate Button --}}
            <button type="button" @click="generate()"
                    class="btn btn-primary w-full btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Generate Words
            </button>
        </div>

        {{-- ══════════════════════════
             EMPTY STATE
             ══════════════════════════ --}}
        <div x-show="!hasGenerated" class="card p-12 text-center text-gray-400">
            <div class="text-5xl mb-3">📖</div>
            <p class="font-semibold text-gray-500 text-lg">Your words will appear here</p>
            <p class="text-sm mt-1">Configure your options above and click Generate Words</p>
        </div>

        {{-- ══════════════════════════
             RESULTS CARD
             ══════════════════════════ --}}
        <div x-show="hasGenerated && results.length > 0" x-transition class="card overflow-hidden">

            {{-- Results header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900">Generated Words</h3>
                    <p class="text-sm text-gray-400 mt-0.5" x-text="resultSummary"></p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="generate()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Regenerate
                    </button>
                    <button type="button" @click="copyAll()"
                            class="btn btn-secondary btn-sm"
                            :class="copiedAll ? 'text-emerald-600' : ''">
                        <svg x-show="!copiedAll" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <svg x-show="copiedAll" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedAll ? 'Copied!' : 'Copy All'"></span>
                    </button>
                    <button type="button" @click="copyList()"
                            class="btn btn-secondary btn-sm"
                            :class="copiedList ? 'text-emerald-600' : ''">
                        <svg x-show="!copiedList" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        <svg x-show="copiedList" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedList ? 'Copied!' : 'Copy as List'"></span>
                    </button>
                    <button type="button" @click="downloadTxt()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download .txt
                    </button>
                </div>
            </div>

            {{-- Word stats bar --}}
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                <span><span class="font-semibold text-gray-700" x-text="results.length"></span> words</span>
                <span><span class="font-semibold text-gray-700" x-text="avgLength"></span> avg. length</span>
                <span><span class="font-semibold text-gray-700" x-text="shortestWord"></span> shortest</span>
                <span><span class="font-semibold text-gray-700" x-text="longestWord"></span> longest</span>
                <span x-show="startLetter">Starts with: <span class="font-semibold text-brand-600" x-text="startLetter"></span></span>
            </div>

            {{-- Word Grid --}}
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <template x-for="(item, idx) in results" :key="item.id">
                    <div class="group relative flex flex-col items-start gap-1 p-3 rounded-xl border-2 border-gray-100 bg-white hover:border-brand-200 hover:bg-brand-50 transition-all cursor-default">

                        {{-- Word --}}
                        <span class="font-bold text-gray-900 text-base leading-tight break-all" x-text="item.word"></span>

                        {{-- POS badge --}}
                        <span x-show="showBadge"
                              class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium"
                              :class="posBadgeClass(item.pos)"
                              x-text="item.pos"></span>

                        {{-- Length indicator --}}
                        <span class="text-xs text-gray-300" x-text="item.word.length + ' letters'"></span>

                        {{-- Copy btn (absolute top-right) --}}
                        <button type="button"
                                @click="copyWord(item)"
                                class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                :class="copiedId === item.id ? 'opacity-100 text-emerald-500 bg-emerald-50' : 'text-gray-400 hover:text-brand-600 hover:bg-brand-100'"
                                title="Copy word">
                            <svg x-show="copiedId !== item.id" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <svg x-show="copiedId === item.id" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Footer note --}}
            <div class="px-6 py-3 border-t border-gray-100 text-xs text-gray-400 flex items-center justify-between">
                <span>Hover over any word to copy it</span>
                <button type="button" @click="hasGenerated = false; results = []" class="text-gray-400 hover:text-red-500 transition-colors">
                    Clear results
                </button>
            </div>
        </div>

        {{-- No-pool warning --}}
        <div x-show="hasGenerated && results.length === 0 && poolError" x-transition
             class="card p-10 text-center">
            <div class="text-4xl mb-3">🔍</div>
            <p class="font-semibold text-gray-700">No words found</p>
            <p class="text-sm text-gray-400 mt-1" x-text="poolError"></p>
            <button type="button" @click="relax(); generate()" class="btn btn-secondary mt-4">
                Relax Filters &amp; Try Again
            </button>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
/* ════════════════════════════════════════════════════════════════
   WORD BANK  (keyed: W[pos][difficulty] = string[])
   pos:        noun | verb | adj | adv
   difficulty: c (common) | i (intermediate) | a (advanced)
   ════════════════════════════════════════════════════════════════ */
var W = {
  noun: {
    c: ['cat','dog','car','book','house','tree','bird','fish','rock','star','moon','sun','sky','rain','wind','fire','water','earth','hand','eye','face','foot','head','arm','leg','door','wall','floor','road','town','city','farm','park','lake','hill','sea','ship','boat','train','plane','bus','ball','game','song','word','name','time','year','day','week','month','hour','man','woman','boy','girl','baby','king','queen','friend','child','mother','father','sister','brother','cup','bag','box','hat','coat','shoe','phone','desk','chair','bed','lamp','map','key','lock','ring','coin','bread','meat','milk','egg','rice','leaf','seed','wing','tail','bone','skin','tooth','hair','neck','knee','back','side','top','end','note','line','sign','step','door','gate','path','room','wall','roof','yard','field','pond','bush','sand','clay','gold','iron','wood','stone','glass','cloth','wool','silk','rope','wire','pipe','drum','horn','bell','flag','mask','cane','stick','knife','fork','spoon','bowl','dish','pan','pot','jar','tin','bottle','bag','purse','belt','sock','boot','glove','scarf','vest','gown','cape'],
    i: ['garden','forest','valley','mountain','ocean','island','desert','bridge','castle','temple','museum','library','theater','harbor','canyon','glacier','meadow','volcano','tiger','eagle','dolphin','jaguar','gorilla','peacock','scorpion','lobster','octopus','jellyfish','diamond','emerald','crystal','marble','granite','copper','silver','compass','lantern','mirror','telescope','chimney','furnace','blanket','pillow','cabinet','ladder','hammer','scissors','notebook','calendar','envelope','merchant','soldier','captain','lawyer','surgeon','craftsman','journalist','inventor','sculptor','musician','athlete','pilgrim','warrior','philosopher','shepherd','harvest','festival','journey','adventure','mystery','disaster','triumph','courage','wisdom','freedom','justice','shadow','thunder','lightning','horizon','twilight','sunrise','sunset','province','peninsula','continent','glacier','hemisphere','atmosphere','carnivore','herbivore','predator','survivor','pioneer','wanderer','colonist','navigator','astronaut','historian','detective','guardian','outpost','fortress','monument','cathedral','parliament','territory','boundary','climate','season','monsoon','avalanche','earthquake','hurricane'],
    a: ['phenomenon','metropolis','archipelago','infrastructure','conscience','sovereignty','catastrophe','renaissance','civilization','archaeology','mythology','topography','meteorology','cartography','genealogy','anthropology','psychology','philosophy','astronomy','typography','bureaucracy','democracy','aristocracy','plutocracy','monastery','parliament','legislature','constitution','jurisdiction','institution','corporation','collaboration','deterioration','manifestation','reconciliation','hallucination','contradiction','disintegration','transformation','amplification','differentiation','exaggeration','contemplation','circumnavigation','sequestration','vindication','reparation','proclamation','elaboration','sedimentation','condensation','fermentation','stratification','fossilization','metamorphosis','photosynthesis','equilibrium','paradox','enigma','conundrum','dichotomy','hierarchy','labyrinth','paradigm','periphery','prototype','skepticism','trajectory','ubiquity','vernacular','xenophobia','aftermath','belligerence','chauvinism','duplicity','euphemism','fanaticism','hubris','idiosyncrasy','juxtaposition','kleptocracy','loquacity','magnanimity','narcissism','obfuscation','pedantry','quagmire','reparation','soliloquy','tautology','unilateralism','verbosity','whimsy'],
  },
  verb: {
    c: ['run','eat','go','see','make','think','come','know','take','give','get','put','say','tell','ask','find','use','want','need','feel','try','keep','hold','bring','work','show','turn','move','play','live','look','help','stand','start','stop','wait','read','write','speak','hear','walk','sit','sleep','wake','open','close','push','pull','jump','fall','grow','build','break','drive','cook','clean','wash','buy','sell','pay','count','learn','teach','carry','throw','catch','pick','cut','lift','drop','fill','send','call','meet','win','lose','fight','rest','smile','laugh','cry','love','hate','hope','sing','dance','swim','fly','climb','crawl','reach','touch','taste','smell','bite','chew','swallow','breathe','blink','wave','nod','kick','hit','grab','shake','hug','kiss','point','knock','tap','press','slide','roll','spin','dig','plant','water','feed','hunt','fish','build','paint','draw','write'],
    i: ['explore','discover','arrange','examine','develop','consider','analyze','describe','explain','observe','calculate','estimate','represent','compare','transform','construct','demonstrate','evaluate','integrate','navigate','negotiate','illustrate','investigate','communicate','collaborate','coordinate','accumulate','accomplish','elaborate','formulate','generate','hypothesize','implement','interpret','introduce','measure','modify','organize','participate','predict','preserve','produce','publish','recognize','recommend','reflect','regulate','research','select','simulate','substitute','summarize','synthesize','translate','validate','visualize','achieve','advocate','appreciate','challenge','contribute','criticize','dedicate','determine','differentiate','emphasize','encourage','establish','facilitate','identify','indicate','influence','initiate','maintain','manipulate','motivate','overcome','persuade','prioritize','promote','pursue','strengthen','support','sustain','utilize','assemble','broadcast','calculate','capture','catalog','certify','clarify','classify','configure','confirm','convert','customize','deploy','detect','diagnose','distribute','document','duplicate','emerge','encode','evaluate','filter','format','harvest'],
    a: ['circumnavigate','substantiate','conceptualize','deliberate','extrapolate','interpolate','rationalize','systematize','categorize','institutionalize','legitimize','marginalize','monopolize','neutralize','optimize','polarize','standardize','theorize','universalize','actualize','ameliorate','articulate','assimilate','authenticate','calibrate','compensate','concentrate','consolidate','corroborate','deconstruct','delineate','disseminate','enumerate','epitomize','eradicate','exacerbate','exonerate','galvanize','harmonize','illuminate','immobilize','incorporate','indoctrinate','insinuate','invigorate','juxtapose','legislate','liberate','mitigate','mobilize','modulate','objectify','orchestrate','perpetuate','persevere','postulate','precipitate','propagate','recapitulate','rehabilitate','reinvigorate','repudiate','scrutinize','sequester','transcend','vindicate','aggrandize','bifurcate','capitulate','conglomerate','decimate','emancipate','extrapolate','fabricate','gerrymandering','homogenize','incapacitate','impersonate','liquidate','misappropriate','obfuscate','proliferate','repatriate','subjugate','undermine','vilify'],
  },
  adj: {
    c: ['big','small','tall','short','long','wide','thin','fat','hot','cold','warm','cool','wet','dry','hard','soft','fast','slow','loud','quiet','dark','light','bright','clean','dirty','old','new','young','first','last','good','bad','best','worst','high','low','near','far','open','full','empty','heavy','rich','poor','free','busy','happy','sad','angry','calm','brave','shy','wise','silly','nice','mean','kind','cruel','safe','wild','tame','raw','fresh','ripe','thick','sharp','round','flat','square','red','blue','green','yellow','white','black','gray','pink','purple','orange','brown','clear','cute','cool','bold','fair','pure','rare','vast','deep','thin','pale','dull','foul','rough','plain','weak','sick','busy','lazy','easy','odd','bare','bare','fake','real','true','false'],
    i: ['ancient','modern','elegant','massive','vivid','serene','agile','fierce','gentle','vibrant','brilliant','radiant','majestic','tranquil','dynamic','fragile','sturdy','graceful','luminous','mysterious','peculiar','whimsical','nostalgic','enchanting','magnificent','turbulent','resilient','versatile','extraordinary','sophisticated','impressive','remarkable','spectacular','breathtaking','astonishing','formidable','unpredictable','relentless','compassionate','thoughtful','articulate','knowledgeable','resourceful','adventurous','determined','ambitious','passionate','tenacious','meticulous','conscientious','innovative','creative','collaborative','strategic','analytical','systematic','comprehensive','thorough','detailed','accurate','reliable','trustworthy','dependable','consistent','adaptable','flexible','spontaneous','impulsive','cautious','deliberate','rational','logical','intuitive','empathetic','sensitive','diplomatic','assertive','balanced','concise','concrete','constructive','courageous','dedicated','diligent','discreet','earnest','eloquent','energetic','enthusiastic','forthright','generous','genuine','gracious','humble','impartial','industrious','insightful','inspiring','judicious','loyal','mature','methodical'],
    a: ['ephemeral','labyrinthine','melancholic','ubiquitous','perspicacious','ethereal','enigmatic','esoteric','exquisite','grandiose','illustrious','ineffable','irreverent','languid','loquacious','magnanimous','nefarious','ostentatious','paradoxical','pedantic','pernicious','quixotic','recondite','sagacious','sanguine','sycophantic','tenebrous','transcendent','trepidatious','vacuous','verbose','voracious','zealous','anachronistic','arcane','bellicose','byzantine','capricious','clandestine','contentious','crepuscular','cynical','deleterious','disingenuous','ebullient','egregious','elusive','equivocal','erudite','existential','expedient','fastidious','felicitous','furtive','gratuitous','hedonistic','hyperbolic','implacable','incongruous','incorrigible','indigenous','inimitable','inscrutable','meretricious','nebulous','nonchalant','obsequious','obstinate','ominous','opprobrious','perfidious','perspicacious','phlegmatic','pleonastic','pretentious','pugnacious','pusillanimous','recalcitrant','sanctimonious','sedulous','solipsistic','sophomoric','specious','spurious','stoic','superfluous','truculent','unctuous','veracious','vituperative'],
  },
  adv: {
    c: ['fast','well','hard','soon','just','very','too','now','then','here','there','back','far','away','still','yet','never','always','often','sometimes','maybe','perhaps','also','only','even','almost','quite','really','truly','finally','first','next','once','twice','already','again','together','alone','early','late','more','less','most','least','much','many','little','quickly','slowly','quietly','loudly','safely','badly','easily','nearly','simply','clearly','deeply','softly','lately','mainly','mostly','partly','fully','truly','freely','tightly','firmly','calmly','gently','briskly','openly','boldly','wisely','kindly','fairly','surely','daily','weekly','monthly','yearly','rarely','broadly','closely','mostly','lightly','briefly','highly','widely','poorly'],
    i: ['silently','rapidly','carefully','precisely','gently','calmly','eagerly','firmly','gracefully','honestly','intently','joyfully','lazily','nervously','patiently','roughly','sharply','smoothly','steadily','suddenly','thoroughly','utterly','vaguely','warmly','briskly','correctly','curiously','diligently','directly','distinctly','effectively','efficiently','equally','exactly','explicitly','fluently','frequently','gradually','heavily','immediately','independently','initially','intensely','largely','logically','naturally','necessarily','notably','obviously','originally','partially','perfectly','primarily','probably','promptly','properly','publicly','purely','regularly','relatively','repeatedly','seemingly','sincerely','skillfully','slightly','spontaneously','strictly','strongly','successfully','swiftly','technically','ultimately','uniquely','vividly','abundantly','accordingly','adequately','appropriately','approximately','authentically','automatically','concisely','considerably','consistently','continually','cooperatively','deliberately','dramatically','essentially','eventually','exceptionally','exclusively','explicitly','extensively','formally','fundamentally','genuinely','globally','graciously'],
    a: ['meticulously','paradoxically','incongruously','surreptitiously','perfidiously','magnanimously','egregiously','eloquently','fastidiously','imperiously','inexorably','insidiously','irrevocably','judiciously','laboriously','lamentably','malevolently','nonchalantly','obstinately','ominously','ostentatiously','painstakingly','perniciously','perspicaciously','pragmatically','prolifically','providentially','querulously','rapturously','relentlessly','sagaciously','solemnly','stoically','strenuously','submissively','superficially','tenaciously','tirelessly','treacherously','tumultuously','ubiquitously','unequivocally','unrelentingly','unwittingly','vehemently','vigorously','voraciously','whimsically','zealously','absurdly','acutely','adroitly','ambiguously','amiably','anomalously','astutely','audaciously','boisterously','brazenly','brusquely','capriciously','categorically','ceremoniously','circumspectly','clandestinely','cogently','coherently','compulsively','confidently','contentiously','cumulatively','deferentially','delicately','diplomatically','disproportionately','dogmatically','emphatically','enigmatically','equivocally','euphemistically','exuberantly','fervently','flamboyantly','furtively','imperatively'],
  },
};

/* ════════════════════════════════════════════════════════════════
   ALPINE.JS COMPONENT
   ════════════════════════════════════════════════════════════════ */
function randomWordGen() {
    return {
        /* ── Config ── */
        count:        20,
        startLetter:  '',
        partOfSpeech: 'any',
        wordLength:   'any',
        difficulty:   'all',
        sortOrder:    'random',
        showBadge:    true,

        /* ── UI state ── */
        hasGenerated: false,
        results:      [],
        copiedId:     null,
        copiedAll:    false,
        copiedList:   false,
        letterError:  '',
        poolError:    '',
        _idCtr:       1,
        _cpTimer:     null,

        /* ── Static option arrays ── */
        partsOfSpeech: [
            { value:'any', icon:'📚', label:'Any'        },
            { value:'noun',icon:'🏷️', label:'Noun'       },
            { value:'verb',icon:'⚡', label:'Verb'       },
            { value:'adj', icon:'🎨', label:'Adjective'  },
            { value:'adv', icon:'💨', label:'Adverb'     },
        ],
        wordLengths: [
            { value:'any',    icon:'📏', label:'Any Length'      },
            { value:'short',  icon:'📌', label:'Short (≤ 4)'     },
            { value:'medium', icon:'📐', label:'Medium (5 – 7)'  },
            { value:'long',   icon:'📜', label:'Long (8+)'       },
        ],
        difficulties: [
            { value:'all',          icon:'🌐', label:'All Levels'   },
            { value:'common',       icon:'🟢', label:'Common'       },
            { value:'intermediate', icon:'🟡', label:'Intermediate' },
            { value:'advanced',     icon:'🔴', label:'Advanced'     },
        ],
        sortOptions: [
            { value:'random',    icon:'🔀', label:'Random'         },
            { value:'az',        icon:'🔤', label:'A → Z'          },
            { value:'za',        icon:'🔤', label:'Z → A'          },
            { value:'shortest',  icon:'↗️', label:'Shortest first'  },
            { value:'longest',   icon:'↘️', label:'Longest first'   },
        ],

        init() {},

        /* ════════ GENERATE ════════ */
        generate() {
            this.letterError = '';
            this.poolError   = '';
            if (this.startLetter && !/^[A-Z]$/i.test(this.startLetter)) {
                this.letterError = 'Only a single letter A–Z is accepted.';
                return;
            }
            this.hasGenerated = true;
            this.results = [];
            this.copiedId = null;

            /* Build word pool */
            var pool = this._buildPool();

            if (pool.length === 0) {
                this.poolError = 'No words match your current filters. Try relaxing the length, difficulty, or starting-letter constraints.';
                return;
            }

            /* Sample */
            var need  = Math.min(this.count, pool.length);
            var picked = this._sample(pool, need);

            /* Sort */
            picked = this._applySort(picked);

            var self = this;
            this.results = picked.map(function(w) {
                return { id: self._idCtr++, word: w.word, pos: w.pos };
            });

            if (pool.length < this.count) {
                this.poolError = 'Only ' + pool.length + ' words match your filters — showing all of them.';
            }
        },

        _buildPool() {
            var posKeys  = this.partOfSpeech === 'any' ? ['noun','verb','adj','adv'] : [this.partOfSpeech];
            var diffKeys = this.difficulty === 'all' ? ['c','i','a']
                         : this.difficulty === 'common' ? ['c']
                         : this.difficulty === 'intermediate' ? ['i'] : ['a'];
            var sl = this.startLetter.toUpperCase();

            var pool = [];
            var seen = {};
            var self = this;

            posKeys.forEach(function(pos) {
                diffKeys.forEach(function(dk) {
                    var arr = W[pos] && W[pos][dk] ? W[pos][dk] : [];
                    arr.forEach(function(word) {
                        /* Length filter */
                        var len = word.length;
                        if (self.wordLength === 'short'  && len > 4)  return;
                        if (self.wordLength === 'medium' && (len < 5 || len > 7)) return;
                        if (self.wordLength === 'long'   && len < 8)  return;
                        /* Starting letter filter */
                        if (sl && word[0].toUpperCase() !== sl) return;
                        /* Dedup */
                        if (seen[word]) return;
                        seen[word] = true;
                        pool.push({ word: word, pos: self._posLabel(pos) });
                    });
                });
            });
            return pool;
        },

        _sample(arr, n) {
            var copy = arr.slice();
            var out  = [];
            while (out.length < n && copy.length) {
                var idx = Math.floor(Math.random() * copy.length);
                out.push(copy.splice(idx, 1)[0]);
            }
            return out;
        },

        _applySort(arr) {
            if (this.sortOrder === 'az')       return arr.sort(function(a,b){ return a.word.localeCompare(b.word); });
            if (this.sortOrder === 'za')       return arr.sort(function(a,b){ return b.word.localeCompare(a.word); });
            if (this.sortOrder === 'shortest') return arr.sort(function(a,b){ return a.word.length - b.word.length; });
            if (this.sortOrder === 'longest')  return arr.sort(function(a,b){ return b.word.length - a.word.length; });
            return arr; /* random — already random from _sample */
        },

        _posLabel(pos) {
            return { noun:'noun', verb:'verb', adj:'adjective', adv:'adverb' }[pos] || pos;
        },

        /* Relax all filters except POS */
        relax() {
            this.wordLength  = 'any';
            this.difficulty  = 'all';
            this.startLetter = '';
        },

        /* ════════ Computed getters ════════ */

        get resultSummary() {
            if (!this.results.length) return '';
            var pos  = this.partsOfSpeech.find(function(p){ return p.value === this.partOfSpeech; }, this);
            var diff = this.difficulties.find(function(d){ return d.value === this.difficulty; }, this);
            var wl   = this.wordLengths.find(function(w){ return w.value === this.wordLength; }, this);
            return this.results.length + ' words · ' + pos.label + ' · ' + diff.label + ' · ' + wl.label;
        },

        get avgLength() {
            if (!this.results.length) return '—';
            var tot = this.results.reduce(function(s,r){ return s + r.word.length; }, 0);
            return (tot / this.results.length).toFixed(1);
        },

        get shortestWord() {
            if (!this.results.length) return '—';
            return this.results.reduce(function(a,b){ return a.word.length <= b.word.length ? a : b; }).word;
        },

        get longestWord() {
            if (!this.results.length) return '—';
            return this.results.reduce(function(a,b){ return a.word.length >= b.word.length ? a : b; }).word;
        },

        /* ════════ POS badge colour ════════ */

        posBadgeClass(pos) {
            if (pos === 'noun')      return 'bg-blue-100 text-blue-700';
            if (pos === 'verb')      return 'bg-emerald-100 text-emerald-700';
            if (pos === 'adjective') return 'bg-purple-100 text-purple-700';
            if (pos === 'adverb')    return 'bg-amber-100 text-amber-700';
            return 'bg-gray-100 text-gray-600';
        },

        /* ════════ Copy / Export ════════ */

        copyWord(item) {
            var self = this;
            clearTimeout(this._cpTimer);
            this._clip(item.word, function() {
                self.copiedId = item.id;
                self._cpTimer = setTimeout(function(){ self.copiedId = null; }, 2000);
            });
        },

        copyAll() {
            var self = this;
            var text = this.results.map(function(r){ return r.word; }).join(', ');
            clearTimeout(this._cpTimer);
            this._clip(text, function() {
                self.copiedAll = true;
                self._cpTimer = setTimeout(function(){ self.copiedAll = false; }, 2000);
            });
        },

        copyList() {
            var self = this;
            var text = this.results.map(function(r,i){ return (i+1) + '. ' + r.word; }).join('\n');
            clearTimeout(this._cpTimer);
            this._clip(text, function() {
                self.copiedList = true;
                self._cpTimer = setTimeout(function(){ self.copiedList = false; }, 2000);
            });
        },

        downloadTxt() {
            var pos  = this.partOfSpeech === 'any' ? 'mixed' : this.partOfSpeech;
            var lines = ['Random Words — ' + this.results.length + ' ' + pos + ' words', ''];
            this.results.forEach(function(r,i){ lines.push((i+1) + '. ' + r.word + '  (' + r.pos + ')'); });
            lines.push('');
            lines.push('Generated by Random Word Generator');
            var blob = new Blob([lines.join('\n')], { type: 'text/plain' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'random-words.txt';
            a.click();
            URL.revokeObjectURL(a.href);
        },

        _clip(text, cb) {
            navigator.clipboard.writeText(text).then(cb).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
                document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                cb();
            });
        },
    };
}
</script>
@endpush
