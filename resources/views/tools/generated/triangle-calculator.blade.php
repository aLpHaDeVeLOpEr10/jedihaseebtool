@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ── Triangle Calculator  prefix: tc- ── */
.tc-big {
    font-size:1.9rem; font-weight:900; line-height:1;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
    word-break:break-all;
}
.tc-big.green  { background:linear-gradient(135deg,#059669,#10b981);  -webkit-background-clip:text; background-clip:text; }
.tc-big.amber  { background:linear-gradient(135deg,#d97706,#f59e0b);  -webkit-background-clip:text; background-clip:text; }
.tc-big.rose   { background:linear-gradient(135deg,#e11d48,#f43f5e);  -webkit-background-clip:text; background-clip:text; }
.tc-big.cyan   { background:linear-gradient(135deg,#0891b2,#06b6d4);  -webkit-background-clip:text; background-clip:text; }
.tc-big.slate  { background:linear-gradient(135deg,#475569,#64748b);  -webkit-background-clip:text; background-clip:text; }
.tc-big.teal   { background:linear-gradient(135deg,#0d9488,#14b8a6);  -webkit-background-clip:text; background-clip:text; }
.tc-big.orange { background:linear-gradient(135deg,#ea580c,#f97316);  -webkit-background-clip:text; background-clip:text; }

.tc-stat {
    background:#fff; border:1.5px solid #e2e8f0; border-radius:1rem;
    padding:1rem .85rem; display:flex; flex-direction:column; align-items:center;
    gap:.35rem; text-align:center; transition:border-color .15s, box-shadow .15s, transform .15s;
}
.tc-stat:hover { border-color:#a5b4fc; box-shadow:0 4px 14px rgba(79,70,229,.08); transform:translateY(-1px); }
.tc-lbl  { font-size:.65rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.tc-sub  { font-size:.68rem; color:#94a3b8; }
.tc-val  { font-size:.82rem; font-weight:600; color:#374151; }
.tc-val-sm { font-size:.72rem; color:#6b7280; font-family:'Courier New',monospace; }

/* Mode tabs */
.tc-tab { padding:.4rem .85rem; font-size:.8rem; font-weight:600; border-radius:.6rem;
          border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b;
          cursor:pointer; transition:all .15s; }
.tc-tab:hover { border-color:#a5b4fc; color:#4f46e5; background:#f5f3ff; }
.tc-tab.active { background:#4f46e5; color:#fff; border-color:#4f46e5; }

/* SVG card */
.tc-svg-wrap {
    background:linear-gradient(135deg,#f5f3ff 0%,#ede9fe 100%);
    border-radius:1rem; padding:.5rem; overflow:hidden;
}

/* Section divider */
.tc-div {
    display:flex; align-items:center; gap:.6rem;
    color:#94a3b8; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em;
}
.tc-div::before,.tc-div::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* Entrance */
@keyframes tcIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.tc-in { animation:tcIn .28s ease-out; }

/* Shimmer */
@keyframes tcShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.tc-shim { height:4rem; border-radius:1rem;
    background:linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%);
    background-size:1200px 100%; animation:tcShim 1.4s infinite; }

/* Angle unit toggle */
.tc-unit-btn { padding:.3rem .75rem; font-size:.75rem; font-weight:600; border-radius:.5rem;
               border:1.5px solid #e2e8f0; cursor:pointer; transition:all .15s; }
.tc-unit-btn.active { background:#4f46e5; color:#fff; border-color:#4f46e5; }
.tc-unit-btn:not(.active) { background:#f8fafc; color:#64748b; }
.tc-unit-btn:not(.active):hover { border-color:#a5b4fc; color:#4f46e5; }
</style>

<div class="min-h-screen bg-gray-50" x-data="triCalc()" x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Solve any triangle from <strong>sides, angles, or a mix</strong> — SSS, SAS, ASA, AAS, SSA, and right triangles. Get all sides, angles, area, perimeter, heights, inradius, circumradius, and more.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-primary">6 Input Modes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- ══════════════════════════════
                 LEFT — Input Card
                 ══════════════════════════════ --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Calculation Mode</p>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="m in modes" :key="m.key">
                                <button type="button"
                                        class="tc-tab"
                                        :class="{active: mode === m.key}"
                                        @click="setMode(m.key)"
                                        x-text="m.label"></button>
                            </template>
                        </div>
                        <p class="text-xs text-gray-400 mt-2" x-text="modes.find(m=>m.key===mode)?.hint"></p>
                    </div>

                    <div class="p-5 space-y-4">

                        {{-- Angle unit (hidden for SSS + RIGHT-LL/LH) --}}
                        <div x-show="hasAngleInput">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-gray-600">Angle unit:</span>
                                <button type="button" class="tc-unit-btn" :class="{active: angleUnit==='DEG'}" @click="angleUnit='DEG'">DEG</button>
                                <button type="button" class="tc-unit-btn" :class="{active: angleUnit==='RAD'}" @click="angleUnit='RAD'">RAD</button>
                            </div>
                        </div>

                        {{-- Right-triangle sub-type --}}
                        <div x-show="mode==='RIGHT'">
                            <label class="form-label">Known values</label>
                            <select x-model="rightType" class="form-input text-sm" @change="inp={a:'',b:'',c:'',A:'',B:'',C:''}; error=''; result=null; phase='idle';">
                                <template x-for="rt in rightTypes" :key="rt.key">
                                    <option :value="rt.key" x-text="rt.label"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Dynamic fields --}}
                        <template x-for="f in ['a','b','c','A','B','C']" :key="f">
                            <div x-show="showField(f)">
                                <label class="form-label" x-text="fieldLabel(f)"></label>
                                <input type="number" step="any"
                                       x-model="inp[f]"
                                       @keydown.enter="calculate()"
                                       class="form-input"
                                       :placeholder="isAngle(f) ? (angleUnit==='DEG' ? 'e.g. 45 (degrees)' : 'e.g. 0.785 (radians)') : 'e.g. 5'">
                                <p class="form-help" x-text="fieldHint(f)"></p>
                            </div>
                        </template>

                        {{-- Error --}}
                        <div x-show="error" x-transition
                             class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="error"></span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Solve Triangle
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>

                    </div>
                </div>

                {{-- Reference Card --}}
                <div class="card p-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Notation Guide</p>
                    <div class="space-y-1.5">
                        @foreach([
                            ['Side a', 'opposite vertex A (between B and C)'],
                            ['Side b', 'opposite vertex B (between A and C)'],
                            ['Side c', 'opposite vertex C (between A and B)'],
                            ['Angle A', 'at vertex A'],
                            ['Angle B', 'at vertex B'],
                            ['Angle C', 'at vertex C'],
                        ] as [$k,$v])
                        <div class="flex justify-between text-xs">
                            <span class="font-medium text-gray-700">{{ $k }}</span>
                            <span class="text-gray-400">{{ $v }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between text-xs pt-1 border-t border-gray-100">
                            <span class="font-medium text-gray-700">SSA warning</span>
                            <span class="text-amber-600">may yield 2 solutions</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════
                 RIGHT — Results Panel
                 ══════════════════════════════ --}}
            <div class="lg:col-span-3 space-y-4" id="tc-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="grid grid-cols-3 gap-3">
                    <template x-for="i in 6" :key="i"><div class="tc-shim"></div></template>
                </div>

                {{-- Results --}}
                <div x-show="phase==='done' && result" class="space-y-4 tc-in">

                    {{-- SVG + type badges --}}
                    <div class="card p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div class="flex flex-wrap gap-2">
                                <span class="badge badge-primary" x-text="result ? result.shapeType : ''"></span>
                                <span class="badge"
                                      :class="{
                                          'badge-warning': result && result.angleType==='Obtuse',
                                          'badge-success': result && result.angleType==='Acute',
                                          'badge-gray'   : result && result.angleType==='Right'
                                      }"
                                      x-text="result ? result.angleType + ' Triangle' : ''"></span>
                            </div>
                            <span class="text-xs text-gray-400" x-show="result2">⚠ 2 solutions (SSA)</span>
                        </div>

                        <div class="tc-svg-wrap">
                            <svg x-show="result && result.svgData"
                                 :viewBox="result ? '0 0 ' + result.svgData.svgW + ' ' + result.svgData.svgH : '0 0 320 210'"
                                 class="w-full" style="max-height:220px;">

                                {{-- Triangle fill --}}
                                <polygon
                                    :points="result ? result.svgData.points : ''"
                                    fill="rgba(99,102,241,0.12)"
                                    stroke="#4f46e5"
                                    stroke-width="2"
                                    stroke-linejoin="round"/>

                                {{-- Right angle marker --}}
                                <path
                                    :d="result && result.svgData.rightAnglePath ? result.svgData.rightAnglePath : 'M0,0'"
                                    fill="none" stroke="#4f46e5" stroke-width="1.5"
                                    :opacity="result && result.svgData.rightAnglePath ? 1 : 0"/>

                                {{-- Vertex labels A B C --}}
                                <text :x="result ? result.svgData.lA.x : 0"
                                      :y="result ? result.svgData.lA.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="13" font-weight="700" fill="#1e1b4b">A</text>
                                <text :x="result ? result.svgData.lB.x : 0"
                                      :y="result ? result.svgData.lB.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="13" font-weight="700" fill="#1e1b4b">B</text>
                                <text :x="result ? result.svgData.lC.x : 0"
                                      :y="result ? result.svgData.lC.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="13" font-weight="700" fill="#1e1b4b">C</text>

                                {{-- Side labels a b c --}}
                                <text :x="result ? result.svgData.la.x : 0"
                                      :y="result ? result.svgData.la.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="11" font-style="italic" fill="#6d28d9">a</text>
                                <text :x="result ? result.svgData.lb.x : 0"
                                      :y="result ? result.svgData.lb.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="11" font-style="italic" fill="#6d28d9">b</text>
                                <text :x="result ? result.svgData.lc.x : 0"
                                      :y="result ? result.svgData.lc.y : 0"
                                      text-anchor="middle" dominant-baseline="central"
                                      font-size="11" font-style="italic" fill="#6d28d9">c</text>
                            </svg>
                        </div>
                    </div>

                    {{-- Sides --}}
                    <div>
                        <div class="tc-div mb-3">Sides</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="tc-stat">
                                <div class="tc-lbl">Side a</div>
                                <div class="tc-big" x-text="result ? fmt(result.a) : '—'"></div>
                                <div class="tc-sub">opposite A</div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">Side b</div>
                                <div class="tc-big green" x-text="result ? fmt(result.b) : '—'"></div>
                                <div class="tc-sub">opposite B</div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">Side c</div>
                                <div class="tc-big teal" x-text="result ? fmt(result.c) : '—'"></div>
                                <div class="tc-sub">opposite C</div>
                            </div>
                        </div>
                    </div>

                    {{-- Angles --}}
                    <div>
                        <div class="tc-div mb-3">Angles</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="tc-stat">
                                <div class="tc-lbl">Angle A</div>
                                <div class="tc-big amber" x-text="result ? fmtDeg(result.A_deg) : '—'"></div>
                                <div class="tc-val-sm" x-text="result ? fmtRad(result.A_deg) : ''"></div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">Angle B</div>
                                <div class="tc-big rose" x-text="result ? fmtDeg(result.B_deg) : '—'"></div>
                                <div class="tc-val-sm" x-text="result ? fmtRad(result.B_deg) : ''"></div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">Angle C</div>
                                <div class="tc-big slate" x-text="result ? fmtDeg(result.C_deg) : '—'"></div>
                                <div class="tc-val-sm" x-text="result ? fmtRad(result.C_deg) : ''"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Area & Perimeter --}}
                    <div>
                        <div class="tc-div mb-3">Area &amp; Perimeter</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="tc-stat sm:col-span-2">
                                <div class="tc-lbl">Area</div>
                                <div class="tc-big" x-text="result ? fmt(result.area) : '—'"></div>
                                <div class="tc-sub">square units</div>
                            </div>
                            <div class="tc-stat sm:col-span-2">
                                <div class="tc-lbl">Perimeter</div>
                                <div class="tc-big green" x-text="result ? fmt(result.perimeter) : '—'"></div>
                                <div class="tc-sub">a + b + c</div>
                            </div>
                        </div>
                    </div>

                    {{-- Heights --}}
                    <div>
                        <div class="tc-div mb-3">Altitudes (Heights)</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="tc-stat">
                                <div class="tc-lbl">hₐ</div>
                                <div class="tc-big cyan" x-text="result ? fmt(result.ha) : '—'"></div>
                                <div class="tc-sub">height to a</div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">h_b</div>
                                <div class="tc-big orange" x-text="result ? fmt(result.hb) : '—'"></div>
                                <div class="tc-sub">height to b</div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">h_c</div>
                                <div class="tc-big slate" x-text="result ? fmt(result.hc) : '—'"></div>
                                <div class="tc-sub">height to c</div>
                            </div>
                        </div>
                    </div>

                    {{-- Radii & Medians --}}
                    <div>
                        <div class="tc-div mb-3">Radii &amp; Medians</div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="tc-stat">
                                <div class="tc-lbl">Inradius r</div>
                                <div class="tc-big teal" x-text="result ? fmt(result.r) : '—'"></div>
                                <div class="tc-sub">inscribed circle</div>
                            </div>
                            <div class="tc-stat">
                                <div class="tc-lbl">Circumradius R</div>
                                <div class="tc-big violet" x-text="result ? fmt(result.R) : '—'"></div>
                                <div class="tc-sub">circumscribed circle</div>
                            </div>
                            <div class="tc-stat sm:col-span-1 col-span-2">
                                <div class="tc-lbl">Medians</div>
                                <div class="tc-val">mₐ = <span x-text="result ? fmt(result.ma) : '—'"></span></div>
                                <div class="tc-val">m_b = <span x-text="result ? fmt(result.mb) : '—'"></span></div>
                                <div class="tc-val">m_c = <span x-text="result ? fmt(result.mc) : '—'"></span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Second solution (SSA ambiguous) --}}
                    <div x-show="result2" class="card overflow-hidden border-2 border-amber-200">
                        <div class="bg-amber-50 px-4 py-2.5 flex items-center gap-2 border-b border-amber-200">
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-semibold text-amber-800">Second Solution (SSA Ambiguous Case)</span>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm">
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Side c</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? fmt(result2.c) : '—'"></div>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Angle B</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? fmtDeg(result2.B_deg) : '—'"></div>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Angle C</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? fmtDeg(result2.C_deg) : '—'"></div>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Area</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? fmt(result2.area) : '—'"></div>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Perimeter</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? fmt(result2.perimeter) : '—'"></div>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2 text-center">
                                    <div class="text-xs text-amber-600 font-semibold">Type</div>
                                    <div class="font-bold text-amber-900" x-text="result2 ? result2.shapeType : '—'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Export --}}
                    <div class="card p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-sm font-medium text-gray-600">Export:</span>
                            <button type="button" @click="copySummary()"
                                    class="btn btn-secondary btn-sm"
                                    :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                    x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                            <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">Download .txt</button>
                            <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                        </div>
                    </div>

                </div>{{-- /results --}}

                {{-- Idle info cards --}}
                <div x-show="phase==='idle'" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['🔺','SSS','3 sides → all angles'],
                        ['📐','SAS','2 sides + included angle'],
                        ['📏','ASA','2 angles + included side'],
                        ['📌','AAS','2 angles + any side'],
                        ['⚠️','SSA','ambiguous case'],
                        ['📐','Right','90° triangle solver'],
                    ] as [$icon,$label,$desc])
                    <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                        <p class="text-2xl mb-1.5">{{ $icon }}</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>

            </div>{{-- /right --}}
        </div>{{-- /grid --}}

        {{-- Related tools --}}
        @if($relatedTools->count())
        <div class="mt-6" x-show="phase==='idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Calculators</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}" class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-xl">{{ $related->icon }}</span>
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
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
function triCalc() {
    return {
        mode: 'SSS',
        angleUnit: 'DEG',
        rightType: 'LL',
        inp: { a:'', b:'', c:'', A:'', B:'', C:'' },
        phase: 'idle',
        error: '',
        result: null,
        result2: null,
        summaryCopyFlash: false,

        modes: [
            {key:'SSS',   label:'SSS',   hint:'3 sides known'},
            {key:'SAS',   label:'SAS',   hint:'2 sides + included angle C'},
            {key:'ASA',   label:'ASA',   hint:'2 angles (A,B) + included side c'},
            {key:'AAS',   label:'AAS',   hint:'2 angles (A,B) + non-included side a'},
            {key:'SSA',   label:'SSA',   hint:'sides a,b + angle A (may give 2 solutions)'},
            {key:'RIGHT', label:'Right', hint:'right triangle — angle C = 90°'},
        ],

        rightTypes: [
            {key:'LL',  label:'Both legs (a, b)',        f1:'a', f2:'b'},
            {key:'LHa', label:'Leg a + hypotenuse c',    f1:'a', f2:'c'},
            {key:'LHb', label:'Leg b + hypotenuse c',    f1:'b', f2:'c'},
            {key:'LAa', label:'Leg a + angle A',         f1:'a', f2:'A'},
            {key:'LBb', label:'Leg b + angle B',         f1:'b', f2:'B'},
            {key:'HAa', label:'Hypotenuse c + angle A',  f1:'c', f2:'A'},
            {key:'HBb', label:'Hypotenuse c + angle B',  f1:'c', f2:'B'},
        ],

        get hasAngleInput() {
            if (this.mode === 'SSS') return false;
            if (this.mode === 'RIGHT') {
                return ['LAa','LBb','HAa','HBb'].includes(this.rightType);
            }
            return true;
        },

        init() {},

        setMode(m) {
            this.mode = m;
            this.inp  = { a:'', b:'', c:'', A:'', B:'', C:'' };
            this.error = ''; this.result = null; this.result2 = null; this.phase = 'idle';
        },

        showField(f) {
            const m = this.mode, rt = this.rightType;
            const map = {
                SSS:   ['a','b','c'],
                SAS:   ['a','b','C'],
                ASA:   ['A','B','c'],
                AAS:   ['A','B','a'],
                SSA:   ['a','b','A'],
            };
            if (m === 'RIGHT') {
                const rmap = {
                    LL:['a','b'], LHa:['a','c'], LHb:['b','c'],
                    LAa:['a','A'], LBb:['b','B'], HAa:['c','A'], HBb:['c','B'],
                };
                return (rmap[rt] || []).includes(f);
            }
            return (map[m] || []).includes(f);
        },

        isAngle(f) { return ['A','B','C'].includes(f); },

        fieldLabel(f) {
            const m = this.mode, rt = this.rightType;
            const base = {a:'Side a', b:'Side b', c:'Side c', A:'Angle A', B:'Angle B', C:'Angle C'};
            if (m === 'SAS' && f === 'C') return 'Included Angle C';
            if (m === 'RIGHT') {
                if (f === 'c') return 'Hypotenuse c';
                if (f === 'a') return 'Leg a';
                if (f === 'b') return 'Leg b';
            }
            const unit = this.isAngle(f) ? (' (' + this.angleUnit + ')') : '';
            return base[f] + unit;
        },

        fieldHint(f) {
            const hints = {
                a:'Length of side a (BC)', b:'Length of side b (AC)', c:'Length of side c (AB)',
                A:'Angle at vertex A', B:'Angle at vertex B', C:'Angle at vertex C (included between a and b in SAS)',
            };
            if (this.mode === 'RIGHT') {
                if (f==='c') return 'The longest side, opposite the 90° angle';
                if (f==='a' || f==='b') return 'A leg adjacent to the right angle';
            }
            return hints[f] || '';
        },

        _p(k) {
            const v = this.inp[k];
            if (v === '' || v === null || v === undefined) return null;
            const n = parseFloat(v);
            return isNaN(n) ? null : n;
        },

        _toDeg(v) { return this.angleUnit === 'DEG' ? v : v * 180 / Math.PI; },

        calculate() {
            this.error = ''; this.result = null; this.result2 = null;
            try {
                const res = this._solve();
                const self = this;
                self.phase = 'loading';
                setTimeout(function() {
                    self.result  = res.r1;
                    self.result2 = res.r2 || null;
                    self.phase   = 'done';
                    if (window.innerWidth < 1024) {
                        const el = document.getElementById('tc-results');
                        if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                    }
                }, 150);
            } catch(e) {
                this.error = String(e);
                this.phase = 'idle';
            }
        },

        _solve() {
            const p = this._p.bind(this);
            const td = this._toDeg.bind(this);
            let a, b, c, A_deg, B_deg, C_deg, r1, r2;

            switch (this.mode) {

                case 'SSS': {
                    a = p('a'); b = p('b'); c = p('c');
                    if (a===null||b===null||c===null) throw 'Please enter all three sides.';
                    if (a<=0||b<=0||c<=0) throw 'All sides must be positive numbers.';
                    if (a+b<=c||b+c<=a||a+c<=b) throw 'Triangle inequality fails: the sum of any two sides must exceed the third side.';
                    A_deg = Math.acos((b*b+c*c-a*a)/(2*b*c)) * 180/Math.PI;
                    B_deg = Math.acos((a*a+c*c-b*b)/(2*a*c)) * 180/Math.PI;
                    C_deg = 180 - A_deg - B_deg;
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    break;
                }

                case 'SAS': {
                    a = p('a'); b = p('b'); const Cv = p('C');
                    if (a===null||b===null||Cv===null) throw 'Please enter sides a, b and included angle C.';
                    if (a<=0||b<=0) throw 'Sides must be positive.';
                    C_deg = td(Cv);
                    if (C_deg<=0||C_deg>=180) throw 'Angle C must be strictly between 0° and 180°.';
                    const Cr = C_deg * Math.PI/180;
                    c = Math.sqrt(a*a + b*b - 2*a*b*Math.cos(Cr));
                    A_deg = Math.acos((b*b+c*c-a*a)/(2*b*c)) * 180/Math.PI;
                    B_deg = 180 - A_deg - C_deg;
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    break;
                }

                case 'ASA': {
                    const Av = p('A'), Bv = p('B'); c = p('c');
                    if (Av===null||Bv===null||c===null) throw 'Please enter angles A, B and side c.';
                    if (c<=0) throw 'Side c must be positive.';
                    A_deg = td(Av); B_deg = td(Bv);
                    if (A_deg<=0||B_deg<=0) throw 'Angles must be positive.';
                    C_deg = 180 - A_deg - B_deg;
                    if (C_deg<=0) throw 'Angles A + B ≥ 180°. No triangle possible.';
                    const Ar=A_deg*Math.PI/180, Br=B_deg*Math.PI/180, Cr2=C_deg*Math.PI/180;
                    a = c*Math.sin(Ar)/Math.sin(Cr2);
                    b = c*Math.sin(Br)/Math.sin(Cr2);
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    break;
                }

                case 'AAS': {
                    const Av = p('A'), Bv = p('B'); a = p('a');
                    if (Av===null||Bv===null||a===null) throw 'Please enter angles A, B and side a.';
                    if (a<=0) throw 'Side a must be positive.';
                    A_deg = td(Av); B_deg = td(Bv);
                    if (A_deg<=0||B_deg<=0) throw 'Angles must be positive.';
                    C_deg = 180 - A_deg - B_deg;
                    if (C_deg<=0) throw 'Angles A + B ≥ 180°. No triangle possible.';
                    const Ar=A_deg*Math.PI/180, Br=B_deg*Math.PI/180, Cr3=C_deg*Math.PI/180;
                    b = a*Math.sin(Br)/Math.sin(Ar);
                    c = a*Math.sin(Cr3)/Math.sin(Ar);
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    break;
                }

                case 'SSA': {
                    a = p('a'); b = p('b'); const Av = p('A');
                    if (a===null||b===null||Av===null) throw 'Please enter sides a, b and angle A.';
                    if (a<=0||b<=0) throw 'Sides must be positive.';
                    A_deg = td(Av);
                    if (A_deg<=0||A_deg>=180) throw 'Angle A must be between 0° and 180°.';
                    const Ar = A_deg*Math.PI/180;
                    const sinBv = b*Math.sin(Ar)/a;
                    if (sinBv > 1+1e-9) throw 'No triangle exists: sin(B) = ' + sinBv.toFixed(4) + ' > 1 is impossible with these values.';
                    B_deg = Math.asin(Math.min(1, sinBv)) * 180/Math.PI;
                    C_deg = 180 - A_deg - B_deg;
                    if (C_deg<=0) throw 'No valid triangle — C ≤ 0°. Try different values.';
                    c = a*Math.sin(C_deg*Math.PI/180)/Math.sin(Ar);
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    const B2 = 180 - B_deg, C2 = 180 - A_deg - B2;
                    if (A_deg < 90 && C2 > 0 && B2 > 0 && Math.abs(B2 - B_deg) > 0.001) {
                        const c2 = a*Math.sin(C2*Math.PI/180)/Math.sin(Ar);
                        r2 = this._computeAll(a, b, c2, A_deg, B2, C2);
                    }
                    break;
                }

                case 'RIGHT': {
                    C_deg = 90;
                    const rt = this.rightType;
                    if (rt==='LL') {
                        a=p('a'); b=p('b');
                        if (a===null||b===null) throw 'Enter both legs a and b.';
                        if (a<=0||b<=0) throw 'Legs must be positive.';
                        c=Math.sqrt(a*a+b*b);
                        A_deg=Math.atan2(a,b)*180/Math.PI; B_deg=90-A_deg;
                    } else if (rt==='LHa') {
                        a=p('a'); c=p('c');
                        if (a===null||c===null) throw 'Enter leg a and hypotenuse c.';
                        if (a<=0||c<=0) throw 'Values must be positive.';
                        if (a>=c) throw 'Leg a must be less than hypotenuse c.';
                        b=Math.sqrt(c*c-a*a); A_deg=Math.asin(a/c)*180/Math.PI; B_deg=90-A_deg;
                    } else if (rt==='LHb') {
                        b=p('b'); c=p('c');
                        if (b===null||c===null) throw 'Enter leg b and hypotenuse c.';
                        if (b<=0||c<=0) throw 'Values must be positive.';
                        if (b>=c) throw 'Leg b must be less than hypotenuse c.';
                        a=Math.sqrt(c*c-b*b); B_deg=Math.asin(b/c)*180/Math.PI; A_deg=90-B_deg;
                    } else if (rt==='LAa') {
                        a=p('a'); const Av=p('A');
                        if (a===null||Av===null) throw 'Enter leg a and angle A.';
                        if (a<=0) throw 'Leg must be positive.';
                        A_deg=td(Av);
                        if (A_deg<=0||A_deg>=90) throw 'Angle A must be between 0° and 90°.';
                        B_deg=90-A_deg;
                        b=a/Math.tan(A_deg*Math.PI/180); c=a/Math.sin(A_deg*Math.PI/180);
                    } else if (rt==='LBb') {
                        b=p('b'); const Bv=p('B');
                        if (b===null||Bv===null) throw 'Enter leg b and angle B.';
                        if (b<=0) throw 'Leg must be positive.';
                        B_deg=td(Bv);
                        if (B_deg<=0||B_deg>=90) throw 'Angle B must be between 0° and 90°.';
                        A_deg=90-B_deg;
                        a=b/Math.tan(B_deg*Math.PI/180); c=b/Math.sin(B_deg*Math.PI/180);
                    } else if (rt==='HAa') {
                        c=p('c'); const Av=p('A');
                        if (c===null||Av===null) throw 'Enter hypotenuse c and angle A.';
                        if (c<=0) throw 'Hypotenuse must be positive.';
                        A_deg=td(Av);
                        if (A_deg<=0||A_deg>=90) throw 'Angle A must be between 0° and 90°.';
                        B_deg=90-A_deg;
                        a=c*Math.sin(A_deg*Math.PI/180); b=c*Math.cos(A_deg*Math.PI/180);
                    } else if (rt==='HBb') {
                        c=p('c'); const Bv=p('B');
                        if (c===null||Bv===null) throw 'Enter hypotenuse c and angle B.';
                        if (c<=0) throw 'Hypotenuse must be positive.';
                        B_deg=td(Bv);
                        if (B_deg<=0||B_deg>=90) throw 'Angle B must be between 0° and 90°.';
                        A_deg=90-B_deg;
                        b=c*Math.sin(B_deg*Math.PI/180); a=c*Math.cos(B_deg*Math.PI/180);
                    }
                    r1 = this._computeAll(a, b, c, A_deg, B_deg, C_deg);
                    break;
                }

                default: throw 'Unknown mode.';
            }

            return { r1: r1, r2: r2 || null };
        },

        _computeAll(a, b, c, A_deg, B_deg, C_deg) {
            const A_rad=A_deg*Math.PI/180, B_rad=B_deg*Math.PI/180, C_rad=C_deg*Math.PI/180;
            const s = (a+b+c)/2;
            const area = Math.sqrt(Math.max(0, s*(s-a)*(s-b)*(s-c)));
            const ha = area>0 ? 2*area/a : 0;
            const hb = area>0 ? 2*area/b : 0;
            const hc = area>0 ? 2*area/c : 0;
            const r  = s>0 ? area/s : 0;
            const R  = area>0 ? (a*b*c)/(4*area) : 0;
            const ma = 0.5*Math.sqrt(2*b*b+2*c*c-a*a);
            const mb = 0.5*Math.sqrt(2*a*a+2*c*c-b*b);
            const mc = 0.5*Math.sqrt(2*a*a+2*b*b-c*c);
            const tol=(x,y)=>Math.abs(x-y)<(Math.max(Math.abs(x),Math.abs(y))||1)*1e-5;
            const isEq = tol(a,b)&&tol(b,c);
            const isIso= tol(a,b)||tol(b,c)||tol(a,c);
            const isRt = Math.abs(A_deg-90)<0.05||Math.abs(B_deg-90)<0.05||Math.abs(C_deg-90)<0.05;
            const shapeType = isEq?'Equilateral':isIso?'Isosceles':'Scalene';
            const angleType = isRt?'Right':(A_deg>90||B_deg>90||C_deg>90?'Obtuse':'Acute');
            const svgData = this._computeSVG(a,b,c,A_rad,B_rad,C_rad,isRt,A_deg,B_deg,C_deg);
            return {a,b,c,A_deg,B_deg,C_deg,A_rad,B_rad,C_rad,
                    perimeter:a+b+c,s,area,ha,hb,hc,r,R,ma,mb,mc,
                    shapeType,angleType,hasRight:isRt,svgData};
        },

        _computeSVG(a,b,c,A_rad,B_rad,C_rad,isRt,A_deg,B_deg,C_deg) {
            const Cx=0,Cy=0,Bx=a,By=0;
            const Ax=b*Math.cos(C_rad), Ay=b*Math.sin(C_rad);
            const xs=[Cx,Bx,Ax], ys=[Cy,By,Ay];
            const minX=Math.min(...xs),maxX=Math.max(...xs);
            const minY=Math.min(...ys),maxY=Math.max(...ys);
            const pad=44, svgW=320, svgH=210;
            const scale=Math.min((svgW-2*pad)/(maxX-minX||1),(svgH-2*pad)/(maxY-minY||1));
            const tx=(x)=>pad+(x-minX)*scale;
            const ty=(y)=>svgH-pad-(y-minY)*scale;
            const pA={x:tx(Ax),y:ty(Ay)};
            const pB={x:tx(Bx),y:ty(By)};
            const pC={x:tx(Cx),y:ty(Cy)};
            const cx2=(pA.x+pB.x+pC.x)/3, cy2=(pA.y+pB.y+pC.y)/3;
            const off=16;
            const vlbl=(p)=>{const dx=p.x-cx2,dy=p.y-cy2,len=Math.sqrt(dx*dx+dy*dy)||1;return{x:p.x+dx/len*off,y:p.y+dy/len*off};};
            const slbl=(p1,p2)=>{const mx=(p1.x+p2.x)/2,my=(p1.y+p2.y)/2,dx=mx-cx2,dy=my-cy2,len=Math.sqrt(dx*dx+dy*dy)||1;return{x:mx+dx/len*10,y:my+dy/len*10};};
            let rightAnglePath=null;
            if (isRt) {
                let rv,ad1,ad2;
                if (Math.abs(C_deg-90)<0.05){rv=pC;ad1=pA;ad2=pB;}
                else if (Math.abs(A_deg-90)<0.05){rv=pA;ad1=pB;ad2=pC;}
                else{rv=pB;ad1=pA;ad2=pC;}
                const nr=(v)=>{const l=Math.sqrt(v.x*v.x+v.y*v.y)||1;return{x:v.x/l,y:v.y/l};};
                const d1=nr({x:ad1.x-rv.x,y:ad1.y-rv.y});
                const d2=nr({x:ad2.x-rv.x,y:ad2.y-rv.y});
                const rs=10;
                const p1={x:rv.x+d1.x*rs,y:rv.y+d1.y*rs};
                const p2={x:rv.x+d2.x*rs,y:rv.y+d2.y*rs};
                const p3={x:p1.x+d2.x*rs,y:p1.y+d2.y*rs};
                rightAnglePath=`M ${p1.x.toFixed(1)},${p1.y.toFixed(1)} L ${p3.x.toFixed(1)},${p3.y.toFixed(1)} L ${p2.x.toFixed(1)},${p2.y.toFixed(1)}`;
            }
            return {
                points:`${pA.x.toFixed(1)},${pA.y.toFixed(1)} ${pB.x.toFixed(1)},${pB.y.toFixed(1)} ${pC.x.toFixed(1)},${pC.y.toFixed(1)}`,
                pA,pB,pC,svgW,svgH,
                lA:vlbl(pA),lB:vlbl(pB),lC:vlbl(pC),
                la:slbl(pB,pC),lb:slbl(pA,pC),lc:slbl(pA,pB),
                rightAnglePath,
            };
        },

        fmt(v) {
            if (v===null||v===undefined||isNaN(v)||!isFinite(v)) return '—';
            let s=parseFloat(v.toPrecision(7)).toString();
            if (s.includes('e')) return v.toFixed(4);
            const dp=s.includes('.')?s.split('.')[1].length:0;
            return dp>4 ? v.toFixed(4) : s;
        },

        fmtDeg(deg) {
            if (deg===null||isNaN(deg)) return '—';
            return parseFloat(deg.toFixed(4)).toString() + '°';
        },

        fmtRad(deg) {
            if (deg===null||isNaN(deg)) return '';
            return parseFloat((deg*Math.PI/180).toFixed(6)).toString() + ' rad';
        },

        loadSample() {
            this.setMode('SSS');
            this.inp = {a:'5',b:'7',c:'8',A:'',B:'',C:''};
        },

        clearAll() {
            this.inp={a:'',b:'',c:'',A:'',B:'',C:''};
            this.error=''; this.result=null; this.result2=null; this.phase='idle';
        },

        _buildSummary() {
            if (!this.result) return '';
            const r=this.result;
            return [
                'Triangle Calculator Results','============================',
                'Type: '+r.shapeType+' '+r.angleType+' Triangle','',
                '-- Sides --',
                'a = '+this.fmt(r.a),'b = '+this.fmt(r.b),'c = '+this.fmt(r.c),'',
                '-- Angles --',
                'A = '+this.fmtDeg(r.A_deg)+' / '+this.fmtRad(r.A_deg),
                'B = '+this.fmtDeg(r.B_deg)+' / '+this.fmtRad(r.B_deg),
                'C = '+this.fmtDeg(r.C_deg)+' / '+this.fmtRad(r.C_deg),'',
                '-- Properties --',
                'Area        = '+this.fmt(r.area),
                'Perimeter   = '+this.fmt(r.perimeter),
                'Height ha   = '+this.fmt(r.ha),
                'Height hb   = '+this.fmt(r.hb),
                'Height hc   = '+this.fmt(r.hc),
                'Inradius r  = '+this.fmt(r.r),
                'Circumradius R = '+this.fmt(r.R),
                'Median ma   = '+this.fmt(r.ma),
                'Median mb   = '+this.fmt(r.mb),
                'Median mc   = '+this.fmt(r.mc),
            ].join('\n');
        },

        async copySummary() {
            const text=this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                const ta=document.createElement('textarea');
                ta.value=text; ta.style.cssText='position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            const self=this; this.summaryCopyFlash=true;
            setTimeout(function(){self.summaryCopyFlash=false;},1800);
        },

        downloadSummary() {
            const text=this._buildSummary(); if (!text) return;
            const blob=new Blob([text],{type:'text/plain;charset=utf-8'});
            const url=URL.createObjectURL(blob);
            const a=document.createElement('a');
            a.href=url; a.download='triangle-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
