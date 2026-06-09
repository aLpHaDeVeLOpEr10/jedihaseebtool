@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Range sliders ── */
.gg-range {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 6px;
    border-radius: 3px;
    outline: none;
    cursor: pointer;
    border: none;
}
.gg-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid rgba(0,0,0,0.2);
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
    cursor: grab;
    margin-top: -6px;
}
.gg-range:active::-webkit-slider-thumb { cursor: grabbing; }
.gg-range::-webkit-slider-runnable-track { height: 6px; border-radius: 3px; }
.gg-range::-moz-range-thumb {
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid rgba(0,0,0,0.2);
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
    cursor: grab;
}
.gg-range::-moz-range-track { height: 6px; border-radius: 3px; }

/* ── Angle dial ── */
.angle-dial {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
    position: relative;
    cursor: pointer;
    flex-shrink: 0;
}
.angle-dial-inner {
    position: absolute;
    width: 2px;
    background: #4f46e5;
    bottom: 50%;
    left: 50%;
    transform-origin: bottom center;
    border-radius: 2px;
    height: 28px;
    margin-left: -1px;
}
.angle-dial-dot {
    position: absolute;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #4f46e5;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
}

/* ── Stop row drag-over highlight ── */
.stop-row { transition: box-shadow 0.15s; }
.stop-row:hover { box-shadow: 0 0 0 2px #c7d2fe; }

/* ── Preview checkerboard (for transparent stops) ── */
.gg-checker {
    background-image: linear-gradient(45deg,#ccc 25%,transparent 25%),
                      linear-gradient(-45deg,#ccc 25%,transparent 25%),
                      linear-gradient(45deg,transparent 75%,#ccc 75%),
                      linear-gradient(-45deg,transparent 75%,#ccc 75%);
    background-size: 12px 12px;
    background-position: 0 0, 0 6px, 6px -6px, -6px 0px;
}

/* ── Preset swatch hover ── */
.preset-swatch { transition: transform 0.15s, box-shadow 0.15s; }
.preset-swatch:hover { transform: scale(1.06); box-shadow: 0 4px 12px rgba(0,0,0,0.18); }

@media print { nav,header,footer,.no-print { display:none !important; } }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="gradientGen()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-1">{{ $tool->short_description }}</p>
                </div>
                <div class="flex gap-2 flex-wrap no-print">
                    <button type="button" @click="randomGradient()"
                            class="btn btn-secondary">
                        🎲 Random
                    </button>
                    <button type="button" @click="resetDefaults()"
                            class="btn btn-secondary">
                        ↺ Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- ══════════════════════════
                 LEFT — Configuration (2 cols)
                 ══════════════════════════ --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Type selector --}}
                <div class="card p-5">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Gradient Type</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="t in types" :key="t.value">
                            <button type="button"
                                    @click="type = t.value"
                                    class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 text-xs font-semibold transition-all"
                                    :class="type === t.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                                <span class="text-lg" x-text="t.icon"></span>
                                <span x-text="t.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- ── Linear settings ── --}}
                <div class="card p-5" x-show="type === 'linear'" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Direction & Angle</h3>

                    {{-- Direction presets --}}
                    <div class="grid grid-cols-4 gap-1.5 mb-4">
                        <template x-for="d in directions" :key="d.angle">
                            <button type="button" @click="angle = d.angle"
                                    class="flex flex-col items-center py-2 rounded-xl border-2 text-xs transition-all"
                                    :class="angle === d.angle
                                        ? 'border-brand-400 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                <span class="text-base leading-none" x-text="d.arrow"></span>
                                <span class="text-[10px] mt-0.5" x-text="d.label"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Angle row: dial + slider + input --}}
                    <div class="flex items-center gap-4">
                        {{-- Angle dial --}}
                        <div class="angle-dial gg-checker"
                             x-ref="dial"
                             @mousedown.prevent="startDial($event)"
                             @touchstart.prevent="startDialTouch($event)"
                             @touchmove.prevent="moveDialTouch($event)"
                             @touchend="stopDial()"
                             title="Drag to set angle">
                            <div class="absolute inset-0.5 rounded-full overflow-hidden">
                                <div class="w-full h-full" :style="'background:' + gradientValue"></div>
                            </div>
                            <div class="angle-dial-dot"></div>
                            <div class="angle-dial-inner"
                                 :style="'transform: rotate(' + angle + 'deg)'"></div>
                        </div>

                        <div class="flex-1">
                            <input type="range" x-model.number="angle" min="0" max="360" step="1"
                                   class="gg-range mb-2"
                                   :style="'background:linear-gradient(to right, #4f46e5 0%, #4f46e5 '+angle/360*100+'%, #e5e7eb '+angle/360*100+'%, #e5e7eb 100%)'">
                            <div class="flex items-center gap-2">
                                <input type="number" x-model.number="angle" min="0" max="360"
                                       class="form-input w-20 text-center font-mono text-sm">
                                <span class="text-sm text-gray-500 font-semibold">deg</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Radial settings ── --}}
                <div class="card p-5" x-show="type === 'radial'" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Radial Options</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Shape</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="radialShape='ellipse'"
                                        class="py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                        :class="radialShape==='ellipse' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                    Ellipse
                                </button>
                                <button type="button" @click="radialShape='circle'"
                                        class="py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                        :class="radialShape==='circle' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                    Circle
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Size</label>
                            <select x-model="radialSize" class="form-input">
                                <option value="farthest-corner">Farthest Corner</option>
                                <option value="closest-corner">Closest Corner</option>
                                <option value="farthest-side">Farthest Side</option>
                                <option value="closest-side">Closest Side</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Position</label>
                            <select x-model="radialPosition" class="form-input">
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="top right">Top Right</option>
                                <option value="right">Right</option>
                                <option value="bottom right">Bottom Right</option>
                                <option value="bottom">Bottom</option>
                                <option value="bottom left">Bottom Left</option>
                                <option value="left">Left</option>
                                <option value="top left">Top Left</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── Conic settings ── --}}
                <div class="card p-5" x-show="type === 'conic'" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Conic Options</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Start Angle</label>
                            <div class="flex items-center gap-2">
                                <input type="range" x-model.number="conicAngle" min="0" max="360" step="1"
                                       class="gg-range flex-1"
                                       :style="'background:linear-gradient(to right,#4f46e5 0%,#4f46e5 '+conicAngle/360*100+'%,#e5e7eb '+conicAngle/360*100+'%,#e5e7eb 100%)'">
                                <input type="number" x-model.number="conicAngle" min="0" max="360"
                                       class="form-input w-20 text-center font-mono text-sm">
                                <span class="text-sm text-gray-500">°</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Center Position</label>
                            <select x-model="conicPosition" class="form-input">
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="top right">Top Right</option>
                                <option value="right">Right</option>
                                <option value="bottom right">Bottom Right</option>
                                <option value="bottom">Bottom</option>
                                <option value="bottom left">Bottom Left</option>
                                <option value="left">Left</option>
                                <option value="top left">Top Left</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── Color Stops ── --}}
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Color Stops
                            <span class="ml-1 badge badge-gray" x-text="stops.length"></span>
                        </h3>
                        <button type="button" @click="addStop()"
                                :disabled="stops.length >= 8"
                                class="btn btn-secondary btn-sm disabled:opacity-40">
                            + Add Stop
                        </button>
                    </div>

                    {{-- Stop list --}}
                    <div class="space-y-2.5">
                        <template x-for="(stop, idx) in sortedStops" :key="stop.id">
                            <div class="stop-row flex items-center gap-2 p-3 bg-gray-50 rounded-2xl">

                                {{-- Colour swatch + native picker --}}
                                <div class="relative flex-shrink-0" title="Click to pick colour">
                                    <input type="color"
                                           :value="stop.color"
                                           @input="stop.color = $event.target.value"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           tabindex="-1">
                                    <div class="w-9 h-9 rounded-xl border-2 border-white shadow cursor-pointer"
                                         :style="'background:'+stop.color"></div>
                                </div>

                                <div class="flex-1 min-w-0 space-y-1.5">
                                    {{-- Hex input --}}
                                    <input type="text"
                                           x-model="stop.color"
                                           @blur="validateStopColor(stop)"
                                           maxlength="7"
                                           spellcheck="false"
                                           placeholder="#4f46e5"
                                           class="form-input font-mono uppercase text-xs w-full py-1.5 px-2.5">

                                    {{-- Position slider --}}
                                    <div class="flex items-center gap-1.5">
                                        <input type="range" x-model.number="stop.position" min="0" max="100" step="1"
                                               class="gg-range flex-1 h-[6px]"
                                               :style="'background:linear-gradient(to right,'+stop.color+' 0%,'+stop.color+' '+stop.position+'%,#e5e7eb '+stop.position+'%,#e5e7eb 100%)'">
                                        <div class="flex items-center gap-0.5 flex-shrink-0">
                                            <input type="number" x-model.number="stop.position" min="0" max="100"
                                                   class="form-input w-12 text-center font-mono text-xs py-1 px-1">
                                            <span class="text-xs text-gray-400">%</span>
                                        </div>
                                    </div>

                                    {{-- Opacity slider --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] text-gray-400 flex-shrink-0 w-12">Opacity</span>
                                        <input type="range" x-model.number="stop.opacity" min="0" max="100" step="1"
                                               class="gg-range flex-1 h-[6px]"
                                               :style="'background:linear-gradient(to right,transparent 0%,'+stop.color+' 100%)'">
                                        <div class="flex items-center gap-0.5 flex-shrink-0">
                                            <input type="number" x-model.number="stop.opacity" min="0" max="100"
                                                   class="form-input w-12 text-center font-mono text-xs py-1 px-1">
                                            <span class="text-xs text-gray-400">%</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delete --}}
                                <button type="button"
                                        @click="removeStop(stop.id)"
                                        :disabled="stops.length <= 2"
                                        class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all disabled:opacity-25 disabled:cursor-not-allowed"
                                        title="Remove stop">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Distribute evenly --}}
                    <button type="button" @click="distributeEvenly()"
                            x-show="stops.length > 2"
                            class="btn btn-secondary btn-sm w-full mt-3 text-xs">
                        ↔ Distribute Stops Evenly
                    </button>
                </div>

            </div>{{-- /left --}}

            {{-- ══════════════════════════
                 RIGHT — Preview + Output (3 cols)
                 ══════════════════════════ --}}
            <div class="lg:col-span-3 space-y-4">

                {{-- Live Preview --}}
                <div class="card overflow-hidden">
                    <div class="gg-checker">
                        <div class="w-full transition-all duration-300"
                             style="height: 220px"
                             :style="'background:' + gradientValue">
                        </div>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between border-t border-gray-100 bg-white">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Live Preview</span>
                            <span class="badge badge-gray capitalize" x-text="type"></span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="togglePreviewSize()"
                                    class="btn btn-secondary btn-sm text-xs">
                                <span x-text="bigPreview ? '⊟ Compact' : '⊞ Expand'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CSS Output --}}
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Generated CSS</h3>
                        <div class="flex gap-2">
                            <button type="button" @click="copy(cssOneliner, 'value')"
                                    class="btn btn-secondary btn-sm">
                                <svg x-show="copiedKey !== 'value'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <svg x-show="copiedKey === 'value'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='value' ? 'Copied!' : 'Copy Value'"></span>
                            </button>
                            <button type="button" @click="copy(cssBlock, 'block')"
                                    class="btn btn-primary btn-sm">
                                <svg x-show="copiedKey !== 'block'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <svg x-show="copiedKey === 'block'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='block' ? 'Copied!' : 'Copy CSS'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Code block --}}
                    <div class="code-block text-xs leading-relaxed break-all select-all cursor-text"
                         @click="copy(cssBlock,'block')">
                        <span class="text-purple-400">background</span><span class="text-gray-400">: </span><span class="text-emerald-400" x-text="gradientValue"></span><span class="text-gray-400">;</span>
                    </div>

                    {{-- Full CSS block --}}
                    <div class="mt-3 text-xs bg-gray-50 rounded-xl p-3 font-mono text-gray-500 leading-relaxed break-all">
                        <span class="text-blue-500">.element</span> <span class="text-gray-400">{</span><br>
                        &nbsp;&nbsp;<span class="text-purple-500">background</span><span class="text-gray-400">:</span>
                        <span class="text-emerald-600" x-text="' ' + gradientValue"></span><span class="text-gray-400">;</span><br>
                        <span class="text-gray-400">}</span>
                    </div>
                </div>

                {{-- Preset Gallery --}}
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Presets</h3>
                        <span class="text-xs text-gray-400">Click to apply</span>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                        <template x-for="p in presets" :key="p.name">
                            <button type="button"
                                    @click="applyPreset(p)"
                                    class="preset-swatch rounded-2xl overflow-hidden aspect-video border-2 transition-all focus:outline-none focus:ring-2 focus:ring-brand-400"
                                    :class="activePreset === p.name ? 'border-brand-500 ring-2 ring-brand-300' : 'border-transparent'"
                                    :title="p.name">
                                <div class="w-full h-full flex items-end"
                                     :style="'background:' + presetCSS(p)">
                                    <span class="w-full text-center text-[9px] font-semibold py-0.5 bg-black/30 text-white"
                                          x-text="p.name"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card p-4 bg-gradient-to-br from-brand-50 to-indigo-50 border-brand-100">
                    <h4 class="text-xs font-semibold text-brand-700 mb-2">💡 Tips</h4>
                    <ul class="space-y-1 text-xs text-brand-600">
                        <li>• Drag the angle dial or arrow buttons to set direction instantly</li>
                        <li>• Click any colour swatch to open the OS colour picker</li>
                        <li>• Opacity slider on each stop enables transparency effects</li>
                        <li>• "Distribute Evenly" spaces stops at equal intervals</li>
                        <li>• Click the CSS code block to copy the full rule</li>
                    </ul>
                </div>

            </div>{{-- /right --}}

        </div>{{-- /grid --}}

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-8">
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
function gradientGen() {
    return {
        /* ── Gradient type ── */
        type: 'linear',
        types: [
            { value: 'linear', label: 'Linear',  icon: '↗' },
            { value: 'radial', label: 'Radial',  icon: '◎' },
            { value: 'conic',  label: 'Conic',   icon: '🌀' },
        ],

        /* ── Linear ── */
        angle: 135,
        directions: [
            { angle: 0,   arrow: '↑', label: 'Top'    },
            { angle: 45,  arrow: '↗', label: '45°'    },
            { angle: 90,  arrow: '→', label: 'Right'  },
            { angle: 135, arrow: '↘', label: '135°'   },
            { angle: 180, arrow: '↓', label: 'Bottom' },
            { angle: 225, arrow: '↙', label: '225°'   },
            { angle: 270, arrow: '←', label: 'Left'   },
            { angle: 315, arrow: '↖', label: '315°'   },
        ],

        /* ── Radial ── */
        radialShape:    'ellipse',
        radialSize:     'farthest-corner',
        radialPosition: 'center',

        /* ── Conic ── */
        conicAngle:    0,
        conicPosition: 'center',

        /* ── Stops ── */
        stops: [
            { id: 1, color: '#4f46e5', opacity: 100, position: 0   },
            { id: 2, color: '#7c3aed', opacity: 100, position: 50  },
            { id: 3, color: '#ec4899', opacity: 100, position: 100 },
        ],
        _nextId: 4,

        /* ── UI ── */
        bigPreview:  false,
        copiedKey:   '',
        _cpTimer:    null,
        activePreset: '',
        _dialDragging: false,

        /* ── Presets ── */
        presets: [
            { name:'Indigo Dream', type:'linear', angle:135, stops:[{color:'#4f46e5',op:100,pos:0},{color:'#7c3aed',op:100,pos:100}] },
            { name:'Sunset',       type:'linear', angle:45,  stops:[{color:'#FF416C',op:100,pos:0},{color:'#FF4B2B',op:100,pos:100}] },
            { name:'Ocean',        type:'linear', angle:135, stops:[{color:'#0575E6',op:100,pos:0},{color:'#021B79',op:100,pos:100}] },
            { name:'Emerald',      type:'linear', angle:135, stops:[{color:'#11998e',op:100,pos:0},{color:'#38ef7d',op:100,pos:100}] },
            { name:'Mango',        type:'linear', angle:90,  stops:[{color:'#FFE259',op:100,pos:0},{color:'#FFA751',op:100,pos:100}] },
            { name:'Aurora',       type:'linear', angle:135, stops:[{color:'#a18cd1',op:100,pos:0},{color:'#fbc2eb',op:100,pos:100}] },
            { name:'Neon',         type:'linear', angle:225, stops:[{color:'#12c2e9',op:100,pos:0},{color:'#c471ed',op:100,pos:50},{color:'#f64f59',op:100,pos:100}] },
            { name:'Rose',         type:'linear', angle:135, stops:[{color:'#f43f5e',op:100,pos:0},{color:'#fb7185',op:100,pos:50},{color:'#fda4af',op:100,pos:100}] },
            { name:'Forest',       type:'linear', angle:135, stops:[{color:'#134e5e',op:100,pos:0},{color:'#71b280',op:100,pos:100}] },
            { name:'Royal',        type:'linear', angle:135, stops:[{color:'#141E30',op:100,pos:0},{color:'#243B55',op:100,pos:100}] },
            { name:'Radial Glow',  type:'radial', radialShape:'circle', radialSize:'farthest-corner', radialPosition:'center', stops:[{color:'#6366f1',op:100,pos:0},{color:'#0f172a',op:100,pos:100}] },
            { name:'Conic Spin',   type:'conic',  conicAngle:0, conicPosition:'center', stops:[{color:'#f43f5e',op:100,pos:0},{color:'#f59e0b',op:100,pos:33},{color:'#10b981',op:100,pos:66},{color:'#3b82f6',op:100,pos:100}] },
            { name:'Peach',        type:'linear', angle:90,  stops:[{color:'#ffecd2',op:100,pos:0},{color:'#fcb69f',op:100,pos:100}] },
            { name:'Midnight',     type:'linear', angle:135, stops:[{color:'#232526',op:100,pos:0},{color:'#414345',op:100,pos:100}] },
            { name:'Cotton Candy', type:'linear', angle:135, stops:[{color:'#f9a8d4',op:100,pos:0},{color:'#a5f3fc',op:100,pos:100}] },
            { name:'Fire',         type:'linear', angle:0,   stops:[{color:'#f97316',op:100,pos:0},{color:'#ef4444',op:100,pos:50},{color:'#dc2626',op:100,pos:100}] },
        ],

        /* ── Init ── */
        init() { /* nothing to restore from localStorage for this tool */ },

        /* ════════ Computed getters ════════ */

        get sortedStops() {
            return [...this.stops].sort(function(a,b){ return a.position - b.position; });
        },

        /* Build the colour string for one stop (handles opacity) */
        _stopColor(stop) {
            if (stop.opacity >= 100) return stop.color;
            var hex = stop.color.replace('#','');
            var r = parseInt(hex.slice(0,2),16), g = parseInt(hex.slice(2,4),16), b = parseInt(hex.slice(4,6),16);
            var a = (Math.max(0,Math.min(100,stop.opacity))/100).toFixed(2).replace(/\.?0+$/,'');
            return 'rgba('+r+','+g+','+b+','+a+')';
        },

        get stopsCss() {
            var self = this;
            return this.sortedStops.map(function(s){
                return self._stopColor(s) + ' ' + s.position + '%';
            }).join(', ');
        },

        get gradientValue() {
            if (this.type === 'linear') {
                return 'linear-gradient('+this.angle+'deg, '+this.stopsCss+')';
            }
            if (this.type === 'radial') {
                return 'radial-gradient('+this.radialShape+' '+this.radialSize+' at '+this.radialPosition+', '+this.stopsCss+')';
            }
            return 'conic-gradient(from '+this.conicAngle+'deg at '+this.conicPosition+', '+this.stopsCss+')';
        },

        get cssOneliner() { return this.gradientValue; },

        get cssBlock() {
            return 'background: '+this.gradientValue+';';
        },

        /* ════════ Stop management ════════ */

        addStop() {
            if (this.stops.length >= 8) return;
            /* Insert a stop midway between the last two */
            var sorted = this.sortedStops;
            var lastTwo = sorted.slice(-2);
            var midPos = Math.round((lastTwo[0].position + lastTwo[1].position) / 2);
            /* Pick a colour midway (simple interpolation) */
            this.stops.push({
                id: this._nextId++,
                color: '#' + this._midColor(lastTwo[0].color, lastTwo[1].color),
                opacity: 100,
                position: midPos,
            });
            this.activePreset = '';
        },

        removeStop(id) {
            if (this.stops.length <= 2) return;
            this.stops = this.stops.filter(function(s){ return s.id !== id; });
            this.activePreset = '';
        },

        distributeEvenly() {
            var sorted = this.sortedStops;
            var n = sorted.length;
            sorted.forEach(function(s, i) {
                s.position = Math.round((i / (n-1)) * 100);
            });
            this.activePreset = '';
        },

        validateStopColor(stop) {
            var raw = stop.color.trim().replace(/^#/,'');
            if (raw.length === 3) raw = raw[0]+raw[0]+raw[1]+raw[1]+raw[2]+raw[2];
            if (/^[0-9A-Fa-f]{6}$/.test(raw)) {
                stop.color = '#' + raw.toUpperCase();
            } else {
                stop.color = stop.color; /* revert to whatever is stored */
            }
        },

        /* Simple colour midpoint */
        _midColor(hex1, hex2) {
            var parse = function(h) { h=h.replace('#',''); return [parseInt(h.slice(0,2),16),parseInt(h.slice(2,4),16),parseInt(h.slice(4,6),16)]; };
            var pad = function(n) { return ('0'+Math.round(n).toString(16)).slice(-2); };
            var c1=parse(hex1), c2=parse(hex2);
            return pad((c1[0]+c2[0])/2)+pad((c1[1]+c2[1])/2)+pad((c1[2]+c2[2])/2);
        },

        /* ════════ Presets ════════ */

        applyPreset(p) {
            this.activePreset = p.name;
            this.type = p.type;
            if (p.angle   !== undefined) this.angle       = p.angle;
            if (p.radialShape !== undefined) { this.radialShape=p.radialShape; this.radialSize=p.radialSize; this.radialPosition=p.radialPosition; }
            if (p.conicAngle !== undefined) { this.conicAngle=p.conicAngle; this.conicPosition=p.conicPosition; }
            this._nextId = 100;
            this.stops = p.stops.map(function(s,i){
                return { id: 100+i, color: s.color, opacity: s.op, position: s.pos };
            });
        },

        presetCSS(p) {
            var stops = p.stops.map(function(s){ return s.color+' '+s.pos+'%'; }).join(', ');
            if (p.type==='radial') return 'radial-gradient('+(p.radialShape||'ellipse')+' '+(p.radialSize||'farthest-corner')+' at '+(p.radialPosition||'center')+', '+stops+')';
            if (p.type==='conic') return 'conic-gradient(from '+(p.conicAngle||0)+'deg at '+(p.conicPosition||'center')+', '+stops+')';
            return 'linear-gradient('+(p.angle||135)+'deg, '+stops+')';
        },

        /* ════════ Random gradient ════════ */

        randomGradient() {
            var palette = ['#FF6B6B','#FF8E53','#FFA07A','#FFD700','#98FB98','#4f46e5','#7c3aed',
                           '#ec4899','#06b6d4','#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6',
                           '#14b8a6','#f43f5e','#84cc16','#0ea5e9','#FF61D2','#4FACFE','#00F2FE',
                           '#43E97B','#FF9A9E','#A1C4FD','#C2E9FB','#667EEA','#764BA2','#F093FB'];
            var shuffle = function(a){ for(var i=a.length-1;i>0;i--){var j=Math.floor(Math.random()*(i+1));var t=a[i];a[i]=a[j];a[j]=t;}return a; };
            var types = ['linear','linear','linear','radial','conic'];
            this.type = types[Math.floor(Math.random()*types.length)];
            this.angle = Math.round(Math.random()*360);
            this.conicAngle = Math.round(Math.random()*360);

            var count = Math.floor(Math.random()*3)+2;
            var colors = shuffle([...palette]).slice(0,count);
            this._nextId += 50;
            var base = this._nextId;
            this.stops = colors.map(function(c,i){
                return { id: base+i, color: c, opacity: 100, position: Math.round((i/(count-1))*100) };
            });
            this._nextId += count;
            this.activePreset = '';
        },

        /* ════════ Reset defaults ════════ */

        resetDefaults() {
            this.type = 'linear';
            this.angle = 135;
            this.radialShape = 'ellipse';
            this.radialSize = 'farthest-corner';
            this.radialPosition = 'center';
            this.conicAngle = 0;
            this.conicPosition = 'center';
            this.stops = [
                { id: 1, color: '#4f46e5', opacity: 100, position: 0   },
                { id: 2, color: '#7c3aed', opacity: 100, position: 50  },
                { id: 3, color: '#ec4899', opacity: 100, position: 100 },
            ];
            this._nextId = 4;
            this.activePreset = '';
        },

        /* ════════ Preview size toggle ════════ */

        togglePreviewSize() {
            this.bigPreview = !this.bigPreview;
            /* Change the preview height via a data attribute and Alpine */
            var el = document.querySelector('[data-preview-box]');
            if (el) el.style.height = this.bigPreview ? '400px' : '220px';
        },

        /* ════════ Angle dial interaction ════════ */

        startDial(e) {
            this._dialDragging = true;
            this._updateDial(e.clientX, e.clientY);
            var self = this;
            self._dMv = function(ev){ if(self._dialDragging) self._updateDial(ev.clientX,ev.clientY); };
            self._dUp = function(){ self._dialDragging=false; document.removeEventListener('mousemove',self._dMv); document.removeEventListener('mouseup',self._dUp); };
            document.addEventListener('mousemove', self._dMv);
            document.addEventListener('mouseup',   self._dUp);
        },
        startDialTouch(e) { this._dialDragging=true; this._updateDial(e.touches[0].clientX, e.touches[0].clientY); },
        moveDialTouch(e)  { if(this._dialDragging) this._updateDial(e.touches[0].clientX, e.touches[0].clientY); },
        stopDial()        { this._dialDragging=false; },

        _updateDial(cx, cy) {
            var rect = this.$refs.dial.getBoundingClientRect();
            var cx2 = rect.left + rect.width/2;
            var cy2 = rect.top  + rect.height/2;
            var rad = Math.atan2(cx - cx2, cy2 - cy);
            var deg = Math.round(rad * (180/Math.PI));
            this.angle = ((deg % 360) + 360) % 360;
        },

        /* ════════ Clipboard ════════ */

        copy(text, key) {
            var self = this;
            clearTimeout(this._cpTimer);
            navigator.clipboard.writeText(text).then(function(){
                self._setCopied(key);
            }).catch(function(){
                var ta=document.createElement('textarea');
                ta.value=text; ta.style.cssText='position:fixed;opacity:0;pointer-events:none';
                document.body.appendChild(ta); ta.select();
                try{document.execCommand('copy');}catch(e){}
                document.body.removeChild(ta);
                self._setCopied(key);
            });
        },
        _setCopied(k){
            this.copiedKey=k;
            var self=this;
            this._cpTimer=setTimeout(function(){self.copiedKey='';},2000);
        },
    };
}
</script>
@endpush
