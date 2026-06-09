@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50"
     x-data="nameGenerator()"
     x-init="init()">

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

            {{-- ── Name Type ── --}}
            <div class="mb-5">
                <label class="form-label">Name Category</label>
                <div class="grid grid-cols-3 sm:grid-cols-7 gap-2">
                    <template x-for="t in nameTypes" :key="t.value">
                        <button type="button" @click="setType(t.value)"
                                class="flex flex-col items-center gap-1 py-2.5 px-2 rounded-xl border-2 text-xs font-semibold transition-all"
                                :class="nameType === t.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                            <span class="text-lg leading-none" x-text="t.icon"></span>
                            <span class="leading-tight text-center" x-text="t.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="divider"></div>

            {{-- ── Person / Baby options ── --}}
            <div x-show="nameType === 'person' || nameType === 'baby'" x-transition>

                {{-- Gender --}}
                <div class="mb-4">
                    <label class="form-label">Gender</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="g in genders" :key="g.value">
                            <button type="button" @click="gender = g.value"
                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="gender === g.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="g.icon"></span>
                                <span x-text="g.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Origin --}}
                <div class="mb-4">
                    <label class="form-label">Origin / Language</label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <template x-for="o in origins" :key="o.value">
                            <button type="button" @click="origin = o.value"
                                    class="flex flex-col items-center py-2 px-1 rounded-xl border-2 text-xs font-medium transition-all"
                                    :class="origin === o.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                                <span class="text-base" x-text="o.flag"></span>
                                <span class="mt-0.5" x-text="o.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Name format --}}
                <div class="mb-4">
                    <label class="form-label">Name Format</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="f in nameFormats" :key="f.value">
                            <button type="button" @click="nameFormat = f.value"
                                    class="px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="nameFormat === f.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="f.label">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Fantasy options ── --}}
            <div x-show="nameType === 'fantasy'" x-transition>
                <div class="mb-4">
                    <label class="form-label">Race / Species</label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        <template x-for="r in fantasyRaces" :key="r.value">
                            <button type="button" @click="fantasyRace = r.value"
                                    class="flex items-center gap-2 py-2.5 px-3 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="fantasyRace === r.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="r.icon"></span>
                                <span x-text="r.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Gender</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="g in genders" :key="g.value">
                            <button type="button" @click="gender = g.value"
                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="gender === g.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="g.icon"></span><span x-text="g.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Username options ── --}}
            <div x-show="nameType === 'username'" x-transition>
                <div class="mb-4">
                    <label class="form-label">Style</label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <template x-for="s in usernameStyles" :key="s.value">
                            <button type="button" @click="usernameStyle = s.value"
                                    class="flex flex-col items-center py-2.5 px-2 rounded-xl border-2 text-xs font-semibold transition-all"
                                    :class="usernameStyle === s.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                                <span class="text-lg" x-text="s.icon"></span>
                                <span x-text="s.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Include numbers</p>
                        <p class="text-xs text-gray-400">e.g. ShadowWolf99</p>
                    </div>
                    <button type="button" @click="includeNumbers = !includeNumbers"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                            :class="includeNumbers ? 'bg-brand-600' : 'bg-gray-200'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                              :class="includeNumbers ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>

            {{-- ── Business options ── --}}
            <div x-show="nameType === 'business'" x-transition>
                <div class="mb-4">
                    <label class="form-label">Industry</label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        <template x-for="ind in businessIndustries" :key="ind.value">
                            <button type="button" @click="businessIndustry = ind.value"
                                    class="flex items-center gap-2 py-2.5 px-3 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="businessIndustry === ind.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="ind.icon"></span><span x-text="ind.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Style</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="s in businessStyles" :key="s.value">
                            <button type="button" @click="businessStyle = s.value"
                                    class="px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="businessStyle === s.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="s.label">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Pet options ── --}}
            <div x-show="nameType === 'pet'" x-transition>
                <div class="mb-4">
                    <label class="form-label">Pet Type</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="p in petTypes" :key="p.value">
                            <button type="button" @click="petType = p.value"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="petType === p.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="p.icon"></span><span x-text="p.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Style / Vibe</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="s in petStyles" :key="s.value">
                            <button type="button" @click="petStyle = s.value"
                                    class="px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="petStyle === s.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="s.label">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Place options ── --}}
            <div x-show="nameType === 'place'" x-transition>
                <div class="mb-4">
                    <label class="form-label">Setting</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <template x-for="s in placeSettings" :key="s.value">
                            <button type="button" @click="placeSetting = s.value"
                                    class="flex items-center gap-2 py-2.5 px-3 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="placeSetting === s.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="s.icon"></span><span x-text="s.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Place Type</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="t in placeTypes" :key="t.value">
                            <button type="button" @click="placeType = t.value"
                                    class="px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="placeType === t.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="t.label">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- ── Common options ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                {{-- Starting Letter --}}
                <div>
                    <label class="form-label">
                        Starting Letter
                        <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <div class="relative">
                        <input type="text"
                               x-model="startLetter"
                               @input="startLetter = startLetter.replace(/[^a-zA-Z]/g,'').slice(0,1).toUpperCase()"
                               placeholder="A–Z or leave blank"
                               maxlength="1"
                               class="form-input uppercase tracking-widest font-semibold text-center text-lg"
                               :class="letterError ? 'border-red-400 focus:border-red-400 focus:ring-red-300' : ''">
                    </div>
                    <p x-show="letterError" x-transition class="form-error" x-text="letterError"></p>
                    <p class="form-help">Only letters A–Z are accepted</p>
                </div>

                {{-- Count --}}
                <div>
                    <label class="form-label">Number of Names</label>
                    <div class="grid grid-cols-4 gap-1.5">
                        <template x-for="n in [5, 10, 15, 20]" :key="n">
                            <button type="button" @click="count = n"
                                    class="py-2 rounded-xl border-2 text-sm font-bold transition-all"
                                    :class="count === n
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    x-text="n">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Generate button --}}
            <button type="button" @click="generate()"
                    class="btn btn-primary w-full btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Generate Names
            </button>
        </div>

        {{-- ══════════════════════════
             EMPTY STATE
             ══════════════════════════ --}}
        <div x-show="!hasGenerated" class="card p-12 text-center text-gray-400">
            <div class="text-5xl mb-3">✨</div>
            <p class="font-semibold text-gray-500 text-lg">Your names will appear here</p>
            <p class="text-sm mt-1">Configure your options above and click Generate Names</p>
        </div>

        {{-- ══════════════════════════
             RESULTS CARD
             ══════════════════════════ --}}
        <div x-show="hasGenerated" x-transition class="card overflow-hidden">

            {{-- Results header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900">Generated Names</h3>
                    <p class="text-sm text-gray-400 mt-0.5" x-text="resultsSummary"></p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button type="button" @click="generate()"
                            class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Generate Again
                    </button>
                    <button type="button" @click="copyAll()"
                            class="btn btn-secondary btn-sm">
                        <svg x-show="copiedAll" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="!copiedAll" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span x-text="copiedAll ? 'Copied!' : 'Copy All'"></span>
                    </button>
                </div>
            </div>

            {{-- Name list --}}
            <div class="divide-y divide-gray-50">
                <template x-for="(item, idx) in results" :key="item.id">
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors group">

                        {{-- Number badge --}}
                        <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-gray-100 text-gray-400 text-xs font-bold flex items-center justify-center group-hover:bg-brand-100 group-hover:text-brand-600 transition-colors"
                              x-text="idx + 1"></span>

                        {{-- Name & meaning --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-base" x-text="item.name"></p>
                            <p x-show="item.tag" class="text-xs text-gray-400 mt-0.5" x-text="item.tag"></p>
                        </div>

                        {{-- Favourite toggle --}}
                        <button type="button" @click="toggleFav(item.id)"
                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                :class="item.fav ? 'opacity-100 text-amber-400' : 'text-gray-300 hover:text-amber-400'"
                                title="Favourite">
                            <svg class="w-4 h-4" :fill="item.fav ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </button>

                        {{-- Copy button --}}
                        <button type="button" @click="copyName(item)"
                                class="flex-shrink-0 btn btn-secondary btn-sm opacity-0 group-hover:opacity-100 transition-opacity min-w-[70px]"
                                :class="copiedId === item.id ? 'opacity-100 text-emerald-600' : ''">
                            <svg x-show="copiedId !== item.id" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <svg x-show="copiedId === item.id" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="copiedId === item.id ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Favourites strip (if any) --}}
            <div x-show="favourites.length > 0" class="px-5 py-4 border-t border-gray-100 bg-amber-50">
                <p class="text-xs font-semibold text-amber-700 mb-2">⭐ Favourites</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="f in favourites" :key="f.id">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-xl border border-amber-200 text-sm font-medium text-gray-800 shadow-sm">
                            <span x-text="f.name"></span>
                            <button type="button" @click="copyName(f)" class="text-gray-400 hover:text-brand-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </div>{{-- /results --}}

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
   NAME DATA BANKS
   ════════════════════════════════════════════════════════════════ */
var ND = {
  first: {
    male: {
      english: ['James','John','Robert','Michael','William','David','Richard','Joseph','Thomas','Charles','Christopher','Daniel','Matthew','Anthony','Mark','Donald','Steven','Paul','Andrew','Joshua','Kenneth','Kevin','Brian','George','Timothy','Ronald','Edward','Jason','Jeffrey','Ryan','Jacob','Gary','Nicholas','Eric','Jonathan','Stephen','Scott','Brandon','Benjamin','Samuel','Alexander','Patrick','Jack','Nathan','Tyler','Aaron','Henry','Adam','Noah','Ethan','Liam','Mason','Logan','Lucas','Oliver','Elijah','Carter','Owen','Connor','Luke','Caleb','Hudson','Sebastian','Gavin','Jayden','Hunter','Eli','Isaiah','Cole','Brayden','Wyatt','Austin','Colton','Cooper','Grayson','Declan','Landon','Dylan'],
      spanish: ['Alejandro','Carlos','Diego','Eduardo','Fernando','Gabriel','Hernando','Ignacio','Jorge','Juan','Luis','Manuel','Miguel','Pablo','Rafael','Ricardo','Roberto','Rodrigo','Santiago','Sergio','Víctor','Adrián','Alfonso','Álvaro','Andrés','Antonio','Arturo','Bernardo','Enrique','Ernesto','Felipe','Francisco','Guillermo','Gustavo','Javier','José','Nicolás','Óscar','Pedro','Agustín','Alejandro','Aurelio','Bautista','Benito','César','Cristóbal','Emilio','Eugenio','Horacio','Isidoro','Leonardo','Leandro','Ramón','Raúl','Tomás','Xavier'],
      french:  ['Antoine','Baptiste','Charles','Clément','Émile','François','Guillaume','Hugo','Jean','Jules','Louis','Luc','Martin','Mathieu','Nicolas','Philippe','Pierre','René','Sébastien','Théodore','Victor','Alexandre','Armand','Aurélien','Benoît','Bruno','Christophe','Damien','Éric','Étienne','Gauthier','Gilbert','Grégoire','Henri','Julien','Laurent','Léo','Maxime','Olivier','Pascal','Raphaël','Simon','Stéphane','Thierry','Thomas','Xavier','Yann'],
      arabic:  ['Ahmad','Ali','Hassan','Ibrahim','Karim','Khalid','Mohammed','Omar','Samir','Tariq','Yasir','Yousef','Abdullah','Bilal','Faisal','Hamza','Ismail','Jabir','Majid','Nasser','Rami','Salim','Tarek','Waleed','Zaid','Adnan','Amer','Amir','Badr','Daud','Farid','Ghazi','Hadi','Imran','Jamil','Marwan','Nabil','Qasim','Rashid','Sami','Sufyan','Tariq','Umar','Usama','Wissam','Yazid','Zakariya'],
      japanese:['Akira','Daichi','Haruto','Hayato','Hiroshi','Kenta','Kenji','Makoto','Naoki','Riku','Ren','Ryota','Satoshi','Shota','Sora','Takashi','Taro','Yuki','Yusei','Yuto','Daiki','Eiichi','Fumio','Genji','Ichiro','Jiro','Kazuki','Keita','Koji','Masato','Masashi','Noboru','Osamu','Ryo','Ryusei','Shoji','Sosuke','Takuma','Tatsuki','Tomohiro','Yosuke','Yuichi','Yuma'],
      norse:   ['Bjorn','Erik','Gunnar','Harald','Ivar','Leif','Magnus','Ragnar','Sigurd','Thor','Ulf','Eirik','Asger','Gudmund','Halfdan','Ingvar','Knud','Olaf','Roald','Sven','Tryggve','Vidar','Birger','Bo','Brage','Dag','Einar','Finn','Frode','Geir','Hakon','Helge','Hermund','Isak','Jorgen','Kjell','Lars','Nils','Per','Rolf','Sigbjorn','Stein','Trond','Vegard','Wulf'],
      celtic:  ['Aedan','Bran','Caelan','Connor','Declan','Donovan','Eamon','Fergus','Finn','Gawain','Niall','Oisin','Patrick','Riordan','Ronan','Seamus','Cormac','Ailill','Bairre','Ciaran','Colm','Comhghall','Criostoir','Darragh','Donal','Eoghan','Fearghal','Fionn','Gearoid','Iarlaith','Lochlainn','Muiredach','Naoise','Peadar','Tiernan'],
      italian: ['Alessandro','Andrea','Antonio','Claudio','Davide','Emanuele','Fabio','Francesco','Giorgio','Giovanni','Giuseppe','Lorenzo','Luca','Marco','Mario','Matteo','Nicola','Paolo','Pietro','Roberto','Salvatore','Simone','Stefano','Vincenzo','Alberto','Alfredo','Angelo','Bruno','Carlo','Emilio','Enrico','Enzo','Felice','Giacomo','Giulio','Leo','Massimo','Maurizio','Nino','Piero','Riccardo','Silvio','Tommaso','Umberto','Valerio'],
      german:  ['Dieter','Friedrich','Hans','Heinrich','Heinz','Helmut','Johann','Karl','Klaus','Konrad','Ludwig','Manfred','Otto','Stefan','Walter','Wolfgang','Gerhard','Günter','Jürgen','Markus','Thomas','Uwe','Andreas','Armin','Bernd','Detlef','Ernst','Felix','Frank','Georg','Günther','Harald','Horst','Michael','Peter','Ralf','Rolf','Rüdiger','Stephan','Werner'],
      latin:   ['Antonius','Augustus','Brutus','Caesar','Cassius','Cicero','Claudius','Cornelius','Flavius','Horatio','Julius','Lucius','Marcus','Maximus','Publius','Quintus','Remus','Romulus','Rufus','Scipio','Septimus','Sextus','Silvius','Tiberius','Titus','Valerius','Vibius'],
    },
    female: {
      english: ['Mary','Patricia','Jennifer','Linda','Barbara','Elizabeth','Susan','Jessica','Sarah','Karen','Lisa','Nancy','Betty','Margaret','Sandra','Ashley','Dorothy','Kimberly','Emily','Donna','Michelle','Carol','Amanda','Melissa','Deborah','Stephanie','Rebecca','Sharon','Laura','Cynthia','Amy','Angela','Anna','Brenda','Pamela','Emma','Nicole','Helen','Samantha','Katherine','Christine','Rachel','Carolyn','Janet','Catherine','Maria','Heather','Diane','Julie','Victoria','Kelly','Christina','Joan','Evelyn','Lauren','Judith','Olivia','Martha','Cheryl','Megan','Andrea','Alice','Grace','Hannah','Sophia','Abigail','Isabella','Mia','Charlotte','Ava','Madison','Harper','Amelia','Aria','Scarlett','Lily','Luna','Penelope','Chloe','Layla','Nora','Hazel','Zoey','Aurora','Stella','Leah','Violet','Ellie','Camila','Natalie','Naomi','Josephine','Maya','Eleanor','Addison','Skyler','Lucy','Paisley'],
      spanish: ['Alejandra','Camila','Daniela','Elena','Fernanda','Gabriela','Isabella','Jimena','Lorena','Luisa','Mariana','Natalia','Paola','Rosa','Sofía','Valentina','Valeria','Vanessa','Verónica','Ximena','Andrea','Beatriz','Carolina','Catalina','Diana','Esperanza','Gloria','Julia','Laura','Lucía','Marisol','Mercedes','Paloma','Patricia','Raquel','Sandra','Teresa','Adriana','Alicia','Amelia','Ángela','Belén','Carmen','Cristina','Dolores','Elisa','Emilia','Eva','Florencia','Inés','Irene','Isabel','Josefa','Magdalena','Marta','Remedios','Soledad'],
      french:  ['Amélie','Camille','Chloé','Claire','Élise','Emma','Évelyne','Florence','Isabelle','Julie','Laura','Léa','Lucie','Marie','Margot','Natalie','Noémie','Pauline','Sophie','Valentine','Vivienne','Adèle','Adrienne','Agnès','Anaïs','Aurélie','Axelle','Béatrice','Cécile','Charlotte','Coralie','Delphine','Élise','Estelle','Gaëlle','Hélène','Inès','Jade','Justine','Laetitia','Laure','Louise','Manon','Marion','Mélanie','Nathalie','Océane','Roxane','Sabine','Valérie','Yasmine'],
      arabic:  ['Aisha','Amira','Fatima','Hana','Jasmine','Laila','Mariam','Nadia','Rania','Salma','Sara','Yasmin','Zainab','Dina','Farah','Huda','Lina','Mona','Nour','Reem','Samira','Sana','Zahra','Alia','Asma','Basma','Bushra','Dalia','Ghada','Hadeel','Iman','Jana','Jumana','Khadija','Lana','Lubna','May','Maysoon','Nada','Noor','Ola','Rahaf','Rawan','Ruba','Shahd','Tamara','Wafa'],
      japanese:['Aiko','Akemi','Chiyo','Emi','Hana','Haruka','Hikari','Hinata','Ichika','Kaede','Keiko','Kiko','Kohana','Mei','Mika','Miyu','Nana','Rin','Sakura','Saya','Yui','Yuki','Yuna','Hina','Ai','Akane','Akari','Amane','Asuka','Ayane','Ayumi','Chika','Erika','Fuuka','Haru','Hiyori','Hotaru','Izumi','Kana','Kanako','Kasumi','Kiyomi','Kokona','Kurumi','Mao','Marin','Misaki','Miwa','Momoka','Natsuki','Rei','Riko','Risa','Saki','Sumire','Tomoe','Tsugumi','Yua','Yuina'],
      norse:   ['Astrid','Brita','Freya','Gudrid','Helga','Hilda','Ingrid','Kristin','Ragnhild','Signe','Sigrid','Siri','Solveig','Thora','Turid','Valdis','Vigdis','Runa','Brynja','Frida','Alfhild','Aslaug','Birta','Bodil','Dagny','Elin','Embla','Gjertrud','Gro','Gudrun','Gunhild','Gyrid','Hjordis','Jorunn','Kari','Marit','Ragnborg','Randi','Sigrun','Torhild','Torunn','Ulfhild','Unn'],
      celtic:  ['Aine','Aoife','Brigid','Caoimhe','Deirdre','Eileen','Fiona','Grainne','Isolt','Maeve','Moira','Niamh','Orla','Roisin','Saoirse','Siobhan','Sorcha','Tara','Una','Aisling','Blathnaid','Caitlin','Clodagh','Dearbhla','Eimear','Etain','Gormflaith','Lasairfhiona','Liadan','Meadhbh','Muireann','Muirgheal','Nessa','Nialldha','Sadbh','Saibh','Tailte'],
      italian: ['Alessandra','Beatrice','Chiara','Claudia','Elena','Elisa','Francesca','Giulia','Laura','Lucia','Luisa','Maria','Martina','Monica','Paola','Sara','Silvia','Simona','Sofia','Valentina','Valeria','Ada','Alba','Angela','Anna','Barbara','Benedetta','Carla','Carolina','Cecilia','Cristina','Daniela','Diana','Eleonora','Federica','Filomena','Giada','Gianna','Gloria','Grazia','Irene','Katia','Laura','Letizia','Lisa','Loretta','Marta','Michela','Miranda','Natalia'],
      german:  ['Anna','Bettina','Birgit','Christine','Elisabeth','Eva','Gisela','Hannah','Heike','Helga','Ilse','Ingrid','Katharina','Kerstin','Klara','Lena','Lisa','Maria','Monika','Petra','Sabine','Sandra','Ursula','Andrea','Angelika','Antje','Astrid','Barbara','Beate','Britta','Dagmar','Elke','Frauke','Friederike','Gudrun','Hanna','Hildegard','Inge','Iris','Jana','Julia','Juliane','Katja','Laura','Lea','Luise','Martina','Nicole','Renate','Silke','Sonja','Stefanie','Susanne'],
      latin:   ['Aelia','Agrippina','Aurelia','Caelia','Cassia','Claudia','Cornelia','Domitia','Drusilla','Flavia','Fulvia','Horatia','Julia','Livia','Lucretia','Marcia','Octavia','Pompeia','Portia','Priscilla','Servia','Silvia','Tullia','Valeria','Verginia','Vibia'],
    },
    neutral: {
      english: ['Alex','Jordan','Taylor','Morgan','Casey','Riley','Avery','Quinn','Peyton','Logan','Parker','Reese','Addison','Bailey','Cameron','Dylan','Finley','Hayden','Hunter','Jesse','Kennedy','Lee','Leslie','Paige','Remy','Sage','Scout','Skyler','Spencer','Wren','Sawyer','River','Emerson','Aubrey','Ember','Kai','Phoenix','Blake','Drew','Jamie','Robin','Sam','Shannon','Kerry','Marley','Elliot','Jaden','Alexis','Rowan','Harley','Kendall','Devon','Dana','Sydney','Ashley','Terry','Ryan','Pat'],
    },
  },

  last: {
    english: ['Smith','Johnson','Williams','Brown','Jones','Garcia','Miller','Davis','Wilson','Anderson','Thomas','Taylor','Moore','Jackson','Martin','Lee','Thompson','White','Harris','Clark','Lewis','Robinson','Walker','Hall','Young','Allen','King','Wright','Scott','Green','Baker','Adams','Nelson','Carter','Mitchell','Roberts','Turner','Phillips','Campbell','Parker','Evans','Edwards','Collins','Stewart','Morris','Rogers','Reed','Cook','Morgan','Bell','Murphy','Bailey','Rivera','Cooper','Richardson','Cox','Howard','Ward','Torres','Peterson','Gray','Ramirez','Watson','Brooks','Kelly','Sanders','Price','Bennett','Wood','Barnes','Ross','Henderson','Coleman','Jenkins','Perry','Powell','Long','Patterson','Hughes','Flores','Washington','Butler','Simmons','Foster','Gonzalez','Bryant','Alexander','Russell','Griffin','Diaz','Hayes','Myers','Ford','Hamilton','Graham','Sullivan','Wallace','Woods','Cole','West','Jordan'],
    spanish: ['Rodriguez','Martinez','Hernandez','Lopez','Gonzalez','Perez','Sanchez','Ramirez','Torres','Flores','Rivera','Gomez','Diaz','Cruz','Reyes','Morales','Jimenez','Ruiz','Alvarez','Romero','Vargas','Castillo','Ramos','Ortega','Castro','Nunez','Navarro','Vega','Dominguez','Serrano','Ramos','Gutiérrez','Fernández','Blanco'],
    french:  ['Martin','Bernard','Thomas','Petit','Robert','Richard','Durand','Dubois','Moreau','Laurent','Simon','Michel','Lefebvre','Leroy','Roux','David','Bertrand','Morel','Fournier','Girard','Bonnet','Lambert','Fontaine','Rousseau','Vincent','Blanc','Garnier','Chevalier','Faure','Legrand'],
    arabic:  ['Al-Ahmad','Al-Hassan','Al-Hussein','Al-Mansour','Al-Rashid','Al-Saeed','Al-Sayed','Al-Zahrawi','Mahmoud','Mustafa','Qureshi','Saleh','Shukri','Sulayman','Tamimi','Zuberi'],
    japanese:['Sato','Suzuki','Tanaka','Watanabe','Ito','Yamamoto','Nakamura','Hayashi','Kobayashi','Kato','Yoshida','Yamada','Sasaki','Matsumoto','Inoue','Kimura','Shimizu','Fujiwara','Ogawa','Matsuda','Abe','Nakajima','Hashimoto','Ishikawa','Yamashita','Mori','Koizumi','Fujita','Takeda','Okamoto','Nakayama','Ueno','Furukawa','Aoki','Ishii','Baba'],
    norse:   ['Hansen','Andersen','Erikson','Johansson','Nilsson','Lindqvist','Bergstrom','Magnusson','Thorvaldsen','Sigurdsson','Olafsson','Bjornsson','Leifsson','Gunnarsson','Haraldsson','Svensson','Lindgren','Petersson','Carlsson','Eriksson','Gustafsson','Larsson','Persson','Jonsson'],
    celtic:  ['Murphy','Kelly','O\'Sullivan','Walsh','O\'Brien','Byrne','Ryan','O\'Connor','O\'Neill','O\'Reilly','Doyle','McCarthy','Gallagher','Doherty','Kennedy','Lynch','Quinn','Murray','Brennan','Carroll','Healy','Burke','Collins','O\'Callaghan','Clarke'],
    italian: ['Rossi','Russo','Ferrari','Esposito','Bianchi','Romano','Colombo','Ricci','Marino','Greco','Bruno','Gallo','Conti','De Luca','Costa','Giordano','Mancini','Rizzo','Lombardi','Moretti','Barbieri','Fontana','Santoro','Mariani','Rinaldi','Caruso','Ferrara','Galli','Martini','Leone'],
    german:  ['Müller','Schmidt','Schneider','Fischer','Weber','Meyer','Wagner','Becker','Schulz','Hoffmann','Schäfer','Koch','Bauer','Richter','Klein','Wolf','Schröder','Neumann','Schwarz','Zimmermann','Braun','Krüger','Hofmann','Hartmann','Lange','Schmitt','Werner','Schmitz','Krause','Meier'],
    latin:   ['Antonius','Brutus','Caesar','Cassius','Cicero','Claudius','Cornelius','Flavius','Julius','Maximus','Pompeius','Publius','Scipio','Severus','Titus','Valerius'],
  },

  middle: {
    english: ['James','Lee','Marie','Grace','Rose','Ann','Ray','Lynn','Mae','Jay','Claire','Faith','Hope','Joy','Dean','Paul','Earl','Wayne','Eugene','Dale','Gene','Alan','Scott','Bruce','Kent','Blaine','Drew','Cole','Reid','Blake'],
    neutral: ['River','Sky','Blue','Snow','Rain','Star','Moon','Sun','Dawn','Fern','Sage','Bay','Glen','Wren','Vale','Reed','Ash','Cove','Laine','Blair'],
  },
};

/* Fantasy name syllables */
var FN = {
  elf: {
    m: { pre:['Ael','Aer','Cal','Cel','Ely','Fin','Gal','Ith','Kael','Lar','Mel','Nil','Sal','Syl','Thal','Vel','Zan','Zeph','Aer','Fae','Ith','Rael','Nael','Vael'],
         suf:['ael','aen','aran','el','iel','ien','ilan','ion','ira','ith','nar','niel','orn','rath','riel','rian','thiel','wen','din','mir','val','dor','las'] },
    f: { pre:['Aela','Aeri','Cali','Celi','Elya','Fini','Gali','Ithi','Laer','Meli','Nili','Sali','Syli','Thali','Veli','Zani','Zephi','Faen','Raeli','Naeli'],
         suf:['a','ela','ia','iel','ielle','ina','ira','ith','na','nia','niel','ra','ria','riel','wen','ana','ane','ara','aria','liel'] },
  },
  dwarf: {
    m: { pre:['Bor','Dur','Gar','Gim','Gror','Kar','Mor','Nor','Rag','Tar','Thain','Tor','Vor','Bali','Dori','Fili','Kili','Oin','Gloin'],
         suf:['ak','din','dor','drin','fur','gin','grim','gur','ik','in','kar','kur','lin','nor','rin','rum','thor','tur','ur','urin','bur','ban'] },
    f: { pre:['Disa','Hlin','Kira','Mira','Nora','Sila','Tira','Vira','Bera','Dara'],
         suf:['a','in','en','ra','ira','ana','na','ita','ala'] },
  },
  orc: {
    m: { pre:['Grak','Grom','Gruk','Karg','Krak','Mog','Mor','Rak','Rok','Skag','Thrak','Urg','Vrak','Zag','Drak','Hruk','Kruk','Og','Ug'],
         suf:['akh','ash','gash','gul','gut','kash','nak','nuk','osh','uk','ush','zog','bul','dak','mag','nag','og','rag','rug'] },
    f: { pre:['Gash','Grisha','Kasha','Masha','Raka','Shaka','Zara','Dasha','Hura'],
         suf:['a','sha','ra','ka','na','ta','ga'] },
  },
  wizard: {
    m: { pre:['Alb','Arch','Azel','Beld','Dra','Eldr','Gal','Jar','Khal','Lore','Mori','Ney','Ral','Sarc','Sev','Thal','Ulz','Xan','Zar','Mor','Bal','Cal','Falc'],
         suf:['ador','amuth','anis','avar','axus','azam','edor','emir','eron','ifax','indar','inus','iric','itus','omar','oran','orion','us','yx','zor','an','ar','eus'] },
    f: { pre:['Alara','Arcana','Azela','Belda','Eldra','Galara','Kalara','Lyra','Morana','Neyra','Rala','Sarcea','Sevia','Thala','Xara','Zara'],
         suf:['a','ia','ara','ina','ira','ana','ella','ora','ula','yra'] },
  },
  dragon: {
    m: { pre:['Aela','Ash','Drak','Kir','Mal','Neth','Rha','Scal','Shar','Tar','Thar','Valg','Vrak','Xeth','Bael','Chal','Dral','Frak','Grax','Kal'],
         suf:['akor','alath','athos','avex','axxan','azath','drax','drex','goroth','grath','igon','inax','ith','ixar','oth','othrax','rax','thex','thos','zar','ax','ix'] },
    f: { pre:['Aelara','Ashari','Malara','Nethra','Rhava','Shara','Tarra','Thara','Valgara','Xetha'],
         suf:['a','ara','ath','ira','ora','ra','tha','va','xa'] },
  },
  fairy: {
    m: { pre:['Bel','Bri','Chi','Dew','Fae','Fern','Glin','Ivy','Lich','Mint','Misty','Moon','Pix','Shin','Star','Tin','Twig','Wisp','Glow','Glimmer'],
         suf:['brook','drop','dust','fen','glow','light','mist','shine','spark','thorn','whisp','wind','wing','wood'] },
    f: { pre:['Bell','Bri','Clo','Dew','Fae','Fern','Flos','Glo','Ivy','Lace','Lily','Lu','Mint','Pix','Ros','Sweet'],
         suf:['a','ella','era','etta','ia','ina','la','lea','lette','li','lia','nie','na','ni','nia'] },
  },
  vampire: {
    m: { pre:['Alek','Bas','Cas','Dak','Dram','Eras','Laz','Luci','Mal','Mor','Nos','Sev','Sel','Ulr','Vlad','Zol'],
         suf:['ian','ius','ar','or','us','ath','ov','ev','in','an','ius','as'] },
    f: { pre:['Ara','Cas','Eli','Eva','Isa','Lil','Lis','Mor','Noc','Sera','Syl','Vam','Vic','Viol'],
         suf:['a','ina','ra','ia','ella','eva','ira','ora','na','tia'] },
  },
};

/* Username word pools */
var UW = {
  cool:   { adj:['Shadow','Dark','Swift','Iron','Ghost','Steel','Void','Neon','Rogue','Storm','Blade','Ice','Frost','Lunar','Viper'], nou:['Wolf','Fox','Eagle','Tiger','Dragon','Phoenix','Cobra','Panther','Falcon','Hawk','Raven','Lynx','Bear','Coyote','Viper'] },
  gaming: { adj:['Deadly','Epic','Hyper','Ultra','Xtreme','Power','Turbo','Mega','Super','Pro','Elite','Alpha','Omega','Prime','Apex'], nou:['Warrior','Sniper','Hunter','Knight','Slayer','Raider','Ranger','Assassin','Gunner','Brawler','Striker','Champion','Titan','Legend','Master'] },
  funny:  { adj:['Fluffy','Grumpy','Sneaky','Lazy','Clumsy','Silly','Cheeky','Quirky','Wacky','Bouncy','Dizzy','Fuzzy','Goofy','Loopy','Zany'], nou:['Pickle','Nugget','Waffle','Muffin','Noodle','Taco','Biscuit','Pancake','Potato','Dumpling','Cupcake','Pretzel','Nacho','Burrito','Donut'] },
  professional: { adj:['Smart','Pro','Expert','Senior','Chief','Lead','Prime','Core','Key','Top'], nou:['Dev','Tech','Sys','Admin','Ops','Analyst','Architect','Engineer','Manager','Advisor','Consultant','Director','Strategist','Specialist','Coordinator'] },
  minimal: { adj:['', 'The','A','Just','Simply',''], nou:['Ace','Axe','Bay','Cay','Dex','Fay','Fox','Gus','Hex','Jay','Kay','Lex','Max','Neo','Rex','Sky','Tex','Vex','Zax','Blu','Cru','Dru'] },
};

/* Business name parts */
var BN = {
  tech:    { pre:['Sync','Byte','Nano','Pixel','Cloud','Data','Tech','Flux','Apex','Core','Grid','Nova','Orb','Pulse','Qubit','Stack','Nexus','Vex','Zeta'], suf:['Labs','Hub','Works','Systems','Solutions','Pro','AI','Tech','Digital','Net','Soft','Ware','Base','Flow','Bit','Gate','Mind','Forge','Link','Spark'] },
  food:    { pre:['Fresh','Crispy','Golden','Hearty','Savory','Spicy','Sweet','Zesty','Tender','Rustic','Urban','Local','Farm','Artisan','Wild'], suf:['Kitchen','Bites','Eats','Table','Bowl','Plate','Grill','Café','Pantry','Harvest','Bistro','Diner','Nook','Oven','Corner'] },
  fashion: { pre:['Vogue','Luxe','Chic','Mod','Edge','Avant','Haute','Trend','Style','Sleek','Bold','Pure','Raw','Free','Nova'], suf:['Studio','Label','Wear','Thread','Fabric','Couture','Tailor','House','Brand','Collection','Edit','Line','Mode','Atelier','Closet'] },
  health:  { pre:['Vita','Zen','Heal','Well','Care','Life','Pure','Bloom','Thrive','Balance','Whole','Nurture','Renew','Revive','Bio'], suf:['Health','Wellness','Med','Care','Clinic','Pharma','Therapy','Center','Institute','Plus','Life','Active','Fit','Cure','Prime'] },
  finance: { pre:['Capital','Wealth','Prime','Asset','Summit','Peak','Crown','Apex','Solid','Steady','Gain','Trust','Value','Merit','Gold'], suf:['Finance','Capital','Invest','Group','Partners','Solutions','Advisors','Fund','Holdings','Ventures','Trust','Wealth','Equity','Consulting','Services'] },
  creative:{ pre:['Bright','Bold','Fresh','Spark','Vivid','Prism','Echo','Artistry','Canvas','Muse','Craft','Flair','Verve','Zest','Pixel'], suf:['Studio','Creative','Design','Media','Works','Agency','Art','Collective','Labs','Factory','House','Workshop','Co','Group','Hub'] },
  retail:  { pre:['Prime','Shop','Buy','Sale','Deal','Best','Smart','Easy','Quick','Direct','Value','Top','Pick','Select','Choice'], suf:['Store','Market','Shop','Mart','Express','Place','Point','Hub','Zone','Centre','World','Galaxy','Universe','Plus','Direct'] },
};

/* Pet names */
var PN = {
  dog:  { cute:['Buddy','Biscuit','Coco','Daisy','Fluffy','Luna','Mochi','Noodle','Pepper','Rosie','Snuggles','Teddy','Waffles','Ziggy','Boo','Cupcake','Dumpling','Giggles','Honey','Jellybean'],
          cool:['Ace','Ajax','Blaze','Duke','Ghost','Hunter','Maverick','Ranger','Rex','Shadow','Thor','Titan','Viper','Wolf','Zeus','Apollo','Diesel','Gunner','Jax','Maximus'],
          funny:['Bark Twain','Chewbarka','Droolius Caesar','Furrdinand','Hairy Paw-ter','Jabba the Mutt','Paw McCartney','Sir Barks-a-Lot','Woofgang Puck','Bark Obama'],
          classic:['Bella','Charlie','Cooper','Daisy','Jack','Lady','Lucy','Max','Molly','Riley','Rocky','Sadie','Sam','Sophie','Winston','Bailey','Cleo','Duke','Jake','Maggie'] },
  cat:  { cute:['Biscuit','Button','Cleo','Dusty','Ginger','Mittens','Mochi','Muffin','Peanut','Pixie','Pudding','Snowball','Stardust','Tinker','Whiskers','Angel','Bliss','Cookie','Dottie','Fudge'],
          cool:['Ash','Cobalt','Eclipse','Havoc','Jade','Jinx','Myst','Nova','Onyx','Phantom','Rebel','Salem','Shadow','Slate','Storm','Chaos','Dusk','Ember','Frost','Smoke'],
          funny:['Chairman Meow','Clawdius','Feline Dion','Fluffinator','Grumblekins','Meowiarty','Purrlock Holmes','Sir Fluffington','Snorkel','Whisker Biscuit'],
          classic:['Bella','Felix','Kitty','Leo','Lily','Luna','Max','Mia','Midnight','Oliver','Oscar','Princess','Ruby','Shadow','Simba','Smoky','Stella','Tiger','Tom','Willow'] },
  other:{ cute:['Almond','Angel','Biscuit','Cinnamon','Clover','Clover','Cotton','Dandelion','Honey','Marble','Misty','Pebble','Pecan','Peony','Poppy','Raisin','Sesame','Sundew','Vanilla','Velvet'],
          cool:['Ajax','Blaze','Eclipse','Flash','Force','Ghost','Havoc','Nero','Nova','Orion','Phantom','Rogue','Solar','Storm','Thor','Volt','Zenith','Zephyr','Comet','Cosmos'],
          funny:['Biscuit Bandit','Captain Fuzzy','Fluffy McFluff','Lord Chonkers','Princess Chomps','Sir Nibbles','The Destroyer','Tummy Rubs','Agent Whiskers','Gigglepaws'],
          classic:['Bambi','Blossom','Butter','Charlie','Chester','Cloud','Ginger','Goldie','Lucky','Marble','Midnight','Peanut','Pepper','Rusty','Sandy','Snowy','Sparky','Sunny','Trixie','Ziggy'] },
};

/* Place name syllables */
var PLN = {
  fantasy: {
    pre: ['Aer','Bel','Cair','Dun','Edh','Fell','Gal','Hav','Ith','Kar','Lor','Mal','Nar','Or','Par','Quel','Ria','Sel','Thal','Ul','Val','Wen','Xan','Yor','Zan'],
    suf: ['adon','ador','agrim','alorn','amor','and','ania','anor','aria','arith','aton','aven','avon','azar','edel','edhin','eldor','elion','elorn','emia','enath','enmor','enor','ethi','iath','idor','ilia','ilin','ilion','imor','inim','inor','inth','ion','isil','ithil','itor','ivalor','ivar','iver','odun','omir','onath','onor','orëa','oriel','oris','orith','orn','otar','othil','otion','otis','ovion','ulath','ulor','umor','unath','urath','uron'],
    title:['of the ','the ','of ','Reaches','Woods','Vale','Peaks','Keep','Hold','Halls','Caverns','Fields','Shores','Pass','Isle','Heights','Glen','Hollow','Wastes','Wilds'],
  },
  scifi: {
    pre: ['Ach','Bel','Cel','Dal','Eth','Fal','Kal','Kol','Lyr','Mal','Nar','Nyx','Or','Pax','Quel','Rh','Sel','Sol','Thal','Ul','Vel','Xen','Yor','Zal'],
    suf: ['-4','Alpha','Beta','Delta','Gamma','Omega','Prime','II','III','IV','V','Station','Outpost','Colony','Base','Hub','Nexus','IX','VII','X'],
    title:['System','Sector','Quadrant','Station','Colony','Base','Outpost','Hub','Nexus','Prime'],
  },
  medieval: {
    pre: ['Ash','Black','Bram','Brock','Caw','Crow','Dark','Dun','Elm','Fen','Frost','Grim','Haven','Hawk','Heath','Hedge','Helm','High','Hollow','Hull','Iron','Kings','Moor','North'],
    suf: ['borough','bridge','brook','burn','burgh','by','castle','cliff','cross','dale','field','ford','gate','ham','haven','hill','hold','holm','holt','house','keep','land','lea','mead','mere','mill','moor','more','mount','mouth','port','ridge','shaw','shire','side','stead','stone','thorpe','ton','vale','wall','ward','wick','will','wood','worth'],
  },
  modern: {
    pre: ['Bay','Beach','Cedar','Clear','Crystal','Deer','East','Elm','Fair','Fall','Forest','Glen','Grand','Green','Grove','High','Hill','Lake','Maple','Mill','New','North','Oak','Park','Pine'],
    suf: [' Bay',' Beach',' City',' Creek',' Falls',' Heights',' Hills',' Lake',' Park',' Place',' Point',' Ridge',' Springs',' Town',' Valley',' Village',' View',' Glen',' Cove',' Harbor'],
  },
};

/* ════════════════════════════════════════════════════════════════
   ALPINE.JS COMPONENT
   ════════════════════════════════════════════════════════════════ */
function nameGenerator() {
    return {
        /* ── Config ── */
        nameType:        'person',
        gender:          'any',
        origin:          'english',
        nameFormat:      'full',
        startLetter:     '',
        count:           10,
        fantasyRace:     'elf',
        usernameStyle:   'cool',
        includeNumbers:  false,
        businessIndustry:'tech',
        businessStyle:   'professional',
        petType:         'dog',
        petStyle:        'cute',
        placeSetting:    'fantasy',
        placeType:       'town',

        /* ── UI ── */
        hasGenerated: false,
        results:      [],
        copiedId:     null,
        copiedAll:    false,
        letterError:  '',
        _idCounter:   1,
        _cpTimer:     null,

        /* ── Static option arrays ── */
        nameTypes: [
            { value:'person',   label:'Person',   icon:'👤' },
            { value:'baby',     label:'Baby',     icon:'👶' },
            { value:'fantasy',  label:'Fantasy',  icon:'🧝' },
            { value:'username', label:'Username', icon:'🎮' },
            { value:'business', label:'Business', icon:'🏢' },
            { value:'pet',      label:'Pet',      icon:'🐾' },
            { value:'place',    label:'Place',    icon:'🗺️' },
        ],
        genders: [
            { value:'male',   label:'Male',   icon:'♂' },
            { value:'female', label:'Female', icon:'♀' },
            { value:'any',    label:'Any',    icon:'⚧' },
        ],
        origins: [
            { value:'english',  label:'English',  flag:'🇬🇧' },
            { value:'spanish',  label:'Spanish',  flag:'🇪🇸' },
            { value:'french',   label:'French',   flag:'🇫🇷' },
            { value:'arabic',   label:'Arabic',   flag:'🇸🇦' },
            { value:'japanese', label:'Japanese', flag:'🇯🇵' },
            { value:'norse',    label:'Norse',    flag:'🌊' },
            { value:'celtic',   label:'Celtic',   flag:'☘️'  },
            { value:'italian',  label:'Italian',  flag:'🇮🇹' },
            { value:'german',   label:'German',   flag:'🇩🇪' },
            { value:'latin',    label:'Latin',    flag:'🏛️'  },
        ],
        nameFormats: [
            { value:'first',       label:'First Only'   },
            { value:'full',        label:'Full Name'    },
            { value:'full-middle', label:'First + Middle + Last' },
        ],
        fantasyRaces: [
            { value:'elf',     label:'Elf',     icon:'🧝' },
            { value:'dwarf',   label:'Dwarf',   icon:'⛏️' },
            { value:'orc',     label:'Orc',     icon:'👹' },
            { value:'wizard',  label:'Wizard',  icon:'🧙' },
            { value:'dragon',  label:'Dragon',  icon:'🐉' },
            { value:'fairy',   label:'Fairy',   icon:'🧚' },
            { value:'vampire', label:'Vampire', icon:'🧛' },
        ],
        usernameStyles: [
            { value:'cool',         label:'Cool',      icon:'😎' },
            { value:'gaming',       label:'Gaming',    icon:'🎮' },
            { value:'funny',        label:'Funny',     icon:'😂' },
            { value:'professional', label:'Professional', icon:'💼' },
            { value:'minimal',      label:'Minimal',   icon:'✨' },
        ],
        businessIndustries: [
            { value:'tech',     label:'Tech',     icon:'💻' },
            { value:'food',     label:'Food',     icon:'🍽️' },
            { value:'fashion',  label:'Fashion',  icon:'👗' },
            { value:'health',   label:'Health',   icon:'🏥' },
            { value:'finance',  label:'Finance',  icon:'💰' },
            { value:'creative', label:'Creative', icon:'🎨' },
            { value:'retail',   label:'Retail',   icon:'🛍️' },
        ],
        businessStyles: [
            { value:'professional', label:'Professional' },
            { value:'creative',     label:'Creative'     },
            { value:'catchy',       label:'Catchy'       },
            { value:'compound',     label:'Compound'     },
        ],
        petTypes: [
            { value:'dog',   label:'Dog',   icon:'🐕' },
            { value:'cat',   label:'Cat',   icon:'🐈' },
            { value:'other', label:'Other', icon:'🐇' },
        ],
        petStyles: [
            { value:'cute',    label:'Cute & Sweet' },
            { value:'cool',    label:'Cool & Strong'},
            { value:'funny',   label:'Funny'        },
            { value:'classic', label:'Classic'      },
        ],
        placeSettings: [
            { value:'fantasy',  label:'Fantasy',  icon:'🏰' },
            { value:'scifi',    label:'Sci-Fi',   icon:'🚀' },
            { value:'medieval', label:'Medieval', icon:'⚔️'  },
            { value:'modern',   label:'Modern',   icon:'🏙️' },
        ],
        placeTypes: [
            { value:'town',   label:'Town'   },
            { value:'city',   label:'City'   },
            { value:'region', label:'Region' },
            { value:'world',  label:'World'  },
        ],

        /* ── Init ── */
        init() {},

        /* ── Type change ── */
        setType(t) {
            this.nameType = t;
            this.hasGenerated = false;
            this.results = [];
            this.letterError = '';
        },

        /* ════════ GENERATE ════════ */
        generate() {
            this.letterError = '';
            if (this.startLetter && !/^[A-Z]$/i.test(this.startLetter)) {
                this.letterError = 'Only a single letter A–Z is accepted.';
                return;
            }
            this.hasGenerated = true;
            this.results = [];
            this.copiedId = null;

            var names = [];
            switch(this.nameType) {
                case 'person':
                case 'baby':    names = this._genPerson();   break;
                case 'fantasy': names = this._genFantasy();  break;
                case 'username':names = this._genUsername(); break;
                case 'business':names = this._genBusiness(); break;
                case 'pet':     names = this._genPet();      break;
                case 'place':   names = this._genPlace();    break;
            }
            var self = this;
            this.results = names.slice(0, this.count).map(function(n) {
                return { id: self._idCounter++, name: n.name, tag: n.tag || '', fav: false };
            });
        },

        /* ── Person / Baby ── */
        _genPerson() {
            var ori = this.origin;
            var gen = this.gender;
            var fmt = this.nameFormat;
            var sl  = this.startLetter.toUpperCase();
            var pool = this._buildFirstPool(gen, ori);
            var lastPool = ND.last[ori] || ND.last.english;
            var midPool  = ND.middle.english.concat(ND.middle.neutral);

            var names = [];
            var attempts = 0;
            while (names.length < this.count + 5 && attempts < 300) {
                attempts++;
                var first = this._pick(pool);
                if (sl && first[0].toUpperCase() !== sl) continue;

                var full;
                if (fmt === 'first') {
                    full = first;
                } else if (fmt === 'full') {
                    full = first + ' ' + this._pick(lastPool);
                } else {
                    full = first + ' ' + this._pick(midPool) + ' ' + this._pick(lastPool);
                }
                if (!names.some(function(n){return n.name===full;})) {
                    names.push({ name: full, tag: this._originTag(ori) });
                }
            }
            return names;
        },

        _buildFirstPool(gen, ori) {
            var pool = [];
            var addPool = function(g) {
                var p = (ND.first[g] && ND.first[g][ori]) || ND.first[g].english;
                pool = pool.concat(p);
            };
            if (gen === 'male')   addPool('male');
            else if (gen === 'female') addPool('female');
            else { addPool('male'); addPool('female'); if (ND.first.neutral[ori]) pool = pool.concat(ND.first.neutral[ori]); else pool = pool.concat(ND.first.neutral.english); }
            return pool;
        },

        _originTag(ori) {
            var map = { english:'English origin', spanish:'Spanish origin', french:'French origin', arabic:'Arabic origin', japanese:'Japanese origin', norse:'Norse/Scandinavian origin', celtic:'Celtic/Irish origin', italian:'Italian origin', german:'German origin', latin:'Latin origin' };
            return map[ori] || '';
        },

        /* ── Fantasy ── */
        _genFantasy() {
            var race = this.fantasyRace;
            var gen  = this.gender;
            var sl   = this.startLetter.toUpperCase();
            var pool = FN[race] || FN.elf;
            var genKey = gen === 'female' ? 'f' : (gen === 'male' ? 'm' : (Math.random()>.5 ? 'm' : 'f'));

            var names = [];
            var attempts = 0;
            while (names.length < this.count + 5 && attempts < 300) {
                attempts++;
                var gk = (gen === 'any') ? (Math.random()>.5?'m':'f') : genKey;
                var side = pool[gk] || pool.m;
                var pre = this._pick(side.pre);
                var suf = this._pick(side.suf);
                var name = pre + suf;
                name = name.charAt(0).toUpperCase() + name.slice(1);
                if (sl && name[0] !== sl) continue;
                if (!names.some(function(n){return n.name===name;})) {
                    names.push({ name: name, tag: this._raceTag(race, gk) });
                }
            }
            return names;
        },

        _raceTag(race, gen) {
            var map = { elf:'Elven name', dwarf:'Dwarven name', orc:'Orcish name', wizard:'Wizard/Mage name', dragon:'Dragon name', fairy:'Fairy/Pixie name', vampire:'Vampire name' };
            return (map[race] || '') + (gen ? ' · ' + gen : '');
        },

        /* ── Username ── */
        _genUsername() {
            var style = this.usernameStyle;
            var sl    = this.startLetter.toUpperCase();
            var pool  = UW[style] || UW.cool;
            var nums  = [2,3,7,9,13,21,42,66,77,99,100,123,420,666,777,999];

            var names = [];
            var attempts = 0;
            while (names.length < this.count + 5 && attempts < 300) {
                attempts++;
                var adj = this._pick(pool.adj);
                var nou = this._pick(pool.nou);
                var username = (adj + nou).replace(/\s+/g,'');
                if (this.includeNumbers) {
                    username += this._pick(nums);
                }
                username = username.charAt(0).toUpperCase() + username.slice(1);
                if (sl && username[0] !== sl) continue;
                if (!names.some(function(n){return n.name===username;})) {
                    names.push({ name: username, tag: style.charAt(0).toUpperCase()+style.slice(1)+' style' });
                }
            }
            return names;
        },

        /* ── Business ── */
        _genBusiness() {
            var ind   = this.businessIndustry;
            var style = this.businessStyle;
            var sl    = this.startLetter.toUpperCase();
            var pool  = BN[ind] || BN.tech;

            var names = [];
            var attempts = 0;
            while (names.length < this.count + 5 && attempts < 300) {
                attempts++;
                var pre = this._pick(pool.pre);
                var suf = this._pick(pool.suf);
                var bname;

                if (style === 'compound') {
                    bname = pre + suf.toLowerCase();
                } else if (style === 'creative') {
                    var extra = ['ify','io','ly','ish','er','ify','ize','oo','oo','bit'];
                    bname = pre + this._pick(extra);
                } else if (style === 'catchy') {
                    bname = pre + suf + '!'.repeat(0); /* no exclamation */
                } else {
                    bname = pre + ' ' + suf;
                }

                bname = bname.trim();
                if (sl && bname[0].toUpperCase() !== sl) continue;
                if (!names.some(function(n){return n.name===bname;})) {
                    names.push({ name: bname, tag: ind.charAt(0).toUpperCase()+ind.slice(1)+' industry' });
                }
            }
            return names;
        },

        /* ── Pet ── */
        _genPet() {
            var pt = this.petType;
            var ps = this.petStyle;
            var sl = this.startLetter.toUpperCase();
            var pool = (PN[pt] && PN[pt][ps]) || PN.dog.cute;

            var names = [];
            var shuffled = this._shuffle([...pool]);
            shuffled.forEach(function(n) {
                if (sl && n[0].toUpperCase() !== sl) return;
                names.push({ name: n, tag: pt.charAt(0).toUpperCase()+pt.slice(1)+' name · '+ps });
            });
            /* If not enough matching the letter, add rest */
            if (!sl) names = shuffled.map(function(n){ return { name:n, tag:pt.charAt(0).toUpperCase()+pt.slice(1)+' name · '+ps }; });
            return names;
        },

        /* ── Place ── */
        _genPlace() {
            var setting = this.placeSetting;
            var type    = this.placeType;
            var sl      = this.startLetter.toUpperCase();
            var pool    = PLN[setting] || PLN.fantasy;

            var names = [];
            var attempts = 0;
            while (names.length < this.count + 5 && attempts < 300) {
                attempts++;
                var pname;

                if (setting === 'fantasy') {
                    var pre = this._pick(pool.pre);
                    var suf = this._pick(pool.suf);
                    pname = pre + suf;
                    pname = pname.charAt(0).toUpperCase() + pname.slice(1);
                    if (type === 'region' || type === 'world') {
                        var title = this._pick(['The ','Old ','New ','Great ','Dark ','Bright ','Lost ','Hidden ']);
                        pname = title + pname;
                    }
                } else if (setting === 'scifi') {
                    var pre2 = this._pick(pool.pre);
                    var suf2 = this._pick(pool.suf);
                    pname = pre2 + suf2;
                    pname = pname.charAt(0).toUpperCase() + pname.slice(1);
                    if (type === 'world' || type === 'region') {
                        pname += ' ' + this._pick(['System','Sector','Cluster','Nebula','Expanse','Reach','Zone']);
                    }
                } else if (setting === 'medieval') {
                    var pre3 = this._pick(pool.pre);
                    var suf3 = this._pick(pool.suf);
                    pname = pre3 + suf3;
                    pname = pname.charAt(0).toUpperCase() + pname.slice(1);
                } else {
                    var pre4 = this._pick(pool.pre);
                    var suf4 = this._pick(pool.suf);
                    pname = pre4 + suf4;
                    pname = pname.charAt(0).toUpperCase() + pname.slice(1);
                }

                if (sl && pname[0].toUpperCase() !== sl) continue;
                if (!names.some(function(n){return n.name===pname;})) {
                    names.push({ name: pname, tag: setting.charAt(0).toUpperCase()+setting.slice(1)+' '+type });
                }
            }
            return names;
        },

        /* ════════ Helpers ════════ */

        _pick(arr) { return arr[Math.floor(Math.random() * arr.length)]; },

        _shuffle(arr) {
            for (var i = arr.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
            }
            return arr;
        },

        /* ════════ Computed ════════ */

        get resultsSummary() {
            if (!this.results.length) return '';
            var parts = [this.results.length + ' names'];
            parts.push(this.nameTypes.find(function(t){return t.value===this.nameType;},this).label);
            if (this.nameType==='person'||this.nameType==='baby') {
                parts.push(this.origins.find(function(o){return o.value===this.origin;},this).label);
                parts.push(this.genders.find(function(g){return g.value===this.gender;},this).label);
            }
            return parts.join(' · ');
        },

        get favourites() {
            return this.results.filter(function(r){ return r.fav; });
        },

        /* ════════ Actions ════════ */

        toggleFav(id) {
            var item = this.results.find(function(r){ return r.id === id; });
            if (item) item.fav = !item.fav;
        },

        copyName(item) {
            var self = this;
            clearTimeout(this._cpTimer);
            this._clipboard(item.name, function() {
                self.copiedId = item.id;
                self._cpTimer = setTimeout(function(){ self.copiedId = null; }, 2000);
            });
        },

        copyAll() {
            var self = this;
            var text = this.results.map(function(r,i){ return (i+1)+'. '+r.name; }).join('\n');
            clearTimeout(this._cpTimer);
            this._clipboard(text, function() {
                self.copiedAll = true;
                self._cpTimer = setTimeout(function(){ self.copiedAll = false; }, 2000);
            });
        },

        _clipboard(text, cb) {
            navigator.clipboard.writeText(text).then(cb).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                cb();
            });
        },
    };
}
</script>
@endpush
