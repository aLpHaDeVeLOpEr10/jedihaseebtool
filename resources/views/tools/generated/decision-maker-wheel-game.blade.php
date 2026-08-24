@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')

<style>
/* ── Decision Wheel Styles (prefix: dw-) ── */
.dw-hero{background:linear-gradient(135deg,#eef2ff 0%,#e0e7ff 50%,#c7d2fe 100%);border-bottom:1px solid #c7d2fe;padding:2rem 0}
.dw-hero-icon{width:56px;height:56px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.dw-badge{display:inline-flex;align-items:center;gap:.375rem;background:#e0e7ff;border:1px solid #c7d2fe;color:#3730a3;font-size:.75rem;font-weight:600;padding:.25rem .75rem;border-radius:999px}

/* ── Two-column tool layout ── */
.dw-layout{display:flex;flex-direction:column;gap:1.25rem}
@media(min-width:768px){.dw-layout{flex-direction:row;align-items:flex-start;gap:1.5rem}}
.dw-choices-col{order:2}
@media(min-width:768px){.dw-choices-col{order:1;width:256px;flex-shrink:0}}
.dw-wheel-col{order:1;display:flex;flex-direction:column;align-items:center;gap:1rem}
@media(min-width:768px){.dw-wheel-col{order:2;flex:1;min-width:0}}

/* ── Canvas ── */
.dw-canvas-wrap{width:100%;max-width:380px;margin:0 auto}
.dw-canvas-wrap canvas{width:100%;aspect-ratio:1;display:block}

/* ── Spin button ── */
.dw-spin-btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;font-size:1.0625rem;font-weight:700;padding:.8rem 2.5rem;border-radius:12px;border:none;cursor:pointer;transition:all .15s;box-shadow:0 4px 14px rgba(79,70,229,.3);letter-spacing:.01em}
.dw-spin-btn:hover:not(:disabled){background:linear-gradient(135deg,#4338ca,#6d28d9);transform:translateY(-1px);box-shadow:0 6px 22px rgba(79,70,229,.45)}
.dw-spin-btn:active:not(:disabled){transform:translateY(0)}
.dw-spin-btn:disabled{opacity:.55;cursor:not-allowed;transform:none;box-shadow:none}
.dw-spin-btn.ready{animation:dw-glow 2.2s ease-in-out infinite}
@keyframes dw-glow{0%,100%{box-shadow:0 4px 14px rgba(79,70,229,.3)}50%{box-shadow:0 4px 28px rgba(79,70,229,.55)}}
.dw-spinner{width:18px;height:18px;border:2.5px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:dw-spin .7s linear infinite;flex-shrink:0}
@keyframes dw-spin{to{transform:rotate(360deg)}}

/* ── Choices list ── */
.dw-choice-item{display:flex;align-items:center;gap:.5rem;padding:.4rem .5rem;border-radius:8px;background:#f9fafb;border:1px solid #f3f4f6;transition:background .1s;margin-bottom:.375rem}
.dw-choice-item:hover{background:#f3f4f6}
.dw-choice-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;box-shadow:0 0 0 1.5px rgba(0,0,0,.08)}
.dw-choice-text{flex:1;font-size:.85rem;color:#1f2937;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0}
.dw-edit-input{flex:1;padding:.2rem .4rem;border:1.5px solid #4f46e5;border-radius:5px;font-size:.85rem;color:#1f2937;background:#fff;min-width:0}
.dw-edit-input:focus{outline:none;box-shadow:0 0 0 2px rgba(79,70,229,.15)}
.dw-icon-btn{width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;flex-shrink:0;transition:all .1s;background:transparent;color:#9ca3af;padding:0}
.dw-icon-btn:hover{background:#e5e7eb;color:#374151}
.dw-icon-btn.del:hover{background:#fee2e2;color:#dc2626}
.dw-icon-btn.ok:hover{background:#dcfce7;color:#16a34a}

/* ── Add row ── */
.dw-add-row{display:flex;gap:.5rem;margin-top:.625rem}
.dw-add-input{flex:1;padding:.5rem .75rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.875rem;color:#1f2937;background:#fff;transition:border-color .15s;min-width:0}
.dw-add-input:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1)}
.dw-add-btn{display:inline-flex;align-items:center;gap:.25rem;background:#4f46e5;color:#fff;font-size:.875rem;font-weight:600;padding:.5rem .875rem;border-radius:8px;border:none;cursor:pointer;flex-shrink:0;transition:background .15s}
.dw-add-btn:hover:not(:disabled){background:#4338ca}
.dw-add-btn:disabled{opacity:.45;cursor:not-allowed}

/* ── Count badge ── */
.dw-count{display:inline-flex;align-items:center;justify-content:center;background:#e0e7ff;color:#4338ca;font-size:.7rem;font-weight:700;padding:.1rem .45rem;border-radius:999px;min-width:20px}

/* ── Result ── */
.dw-result{padding:1.125rem 1.25rem;border-radius:12px;border:2.5px solid;text-align:center;animation:dw-pop .45s cubic-bezier(.34,1.56,.64,1);width:100%;max-width:380px}
@keyframes dw-pop{from{transform:scale(.75);opacity:0}to{transform:scale(1);opacity:1}}
.dw-result-label{font-size:.6875rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#6b7280;margin-bottom:.25rem}
.dw-result-text{font-size:1.5rem;font-weight:800;color:#111827;word-break:break-word;line-height:1.25}
.dw-spin-again{display:inline-flex;align-items:center;gap:.375rem;background:#fff;border:1.5px solid #e5e7eb;color:#4f46e5;font-size:.875rem;font-weight:600;padding:.45rem 1rem;border-radius:8px;cursor:pointer;transition:all .12s;margin-top:.75rem}
.dw-spin-again:hover{background:#eef2ff;border-color:#a5b4fc}

/* ── Empty state text ── */
.dw-min-warn{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.5rem .75rem;font-size:.8rem;color:#92400e;text-align:center;width:100%;max-width:380px}

/* ── Action row below choices ── */
.dw-action-row{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.5rem}
.dw-sm-btn{display:inline-flex;align-items:center;gap:.25rem;padding:.35rem .75rem;border-radius:7px;border:1.5px solid #e5e7eb;font-size:.8rem;font-weight:500;color:#6b7280;cursor:pointer;background:#fff;transition:all .12s}
.dw-sm-btn:hover{border-color:#c7d2fe;color:#4338ca;background:#eef2ff}
.dw-sm-btn.red:hover{border-color:#fca5a5;color:#dc2626;background:#fef2f2}

/* ── Sidebar presets ── */
.dw-preset-chip{display:inline-flex;align-items:center;padding:.3rem .7rem;border-radius:999px;border:1.5px solid #e5e7eb;font-size:.8rem;font-weight:500;color:#4b5563;cursor:pointer;background:#fff;transition:all .12s;white-space:nowrap;margin:.2rem .2rem .2rem 0}
.dw-preset-chip:hover{border-color:#a5b4fc;color:#4338ca;background:#eef2ff}

/* ── History ── */
.dw-hist-item{display:flex;align-items:center;gap:.5rem;padding:.375rem .5rem;border-radius:6px;background:#f9fafb;border:1px solid #f3f4f6;margin-bottom:.3rem}
.dw-hist-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.dw-hist-text{font-size:.8125rem;color:#374151;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;min-width:0}
</style>

<!-- Hero -->
<div class="dw-hero">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
    <div class="flex items-start gap-4">
      <div class="dw-hero-icon">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
        </svg>
      </div>
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <span class="dw-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
            Instant &middot; No Sign-up
          </span>
        </div>
        <p class="text-gray-600 text-sm max-w-2xl">{{ $tool->description }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Main layout — Alpine scope wraps the full 3-column grid so sidebar can read history/choices -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="grid gap-8 lg:grid-cols-3" x-data="dwTool()">

    <!-- ════════════ MAIN COLUMN ════════════ -->
    <div class="lg:col-span-2 space-y-5">

      <!-- ── Main Tool Card ── -->
      <div class="card p-5 sm:p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Spin the Wheel</h2>

        <div class="dw-layout">

          <!-- ─ Choices Column ─ -->
          <div class="dw-choices-col">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-gray-700">Options</span>
              <span class="dw-count" x-text="choices.length + ' / 20'"></span>
            </div>

            <!-- Error message -->
            <p x-show="error" class="text-xs text-red-600 mb-2" x-text="error"></p>

            <!-- Choice list -->
            <div style="max-height:320px;overflow-y:auto;overflow-x:hidden" id="dw-choice-list">
              <template x-for="(choice, idx) in choices" :key="idx">
                <div class="dw-choice-item">
                  <!-- color dot -->
                  <span class="dw-choice-dot" :style="'background:' + COLORS[idx % COLORS.length]"></span>

                  <!-- display mode -->
                  <span
                    class="dw-choice-text"
                    x-show="editingIdx !== idx"
                    x-text="choice"
                    @dblclick="startEdit(idx)"
                    :title="choice"
                  ></span>

                  <!-- edit mode -->
                  <input
                    :id="'dw-edit-' + idx"
                    class="dw-edit-input"
                    x-show="editingIdx === idx"
                    x-model="editText"
                    @keydown.enter="saveEdit(idx)"
                    @keydown.escape="cancelEdit()"
                    @blur="saveEdit(idx)"
                    maxlength="60"
                  >

                  <!-- action buttons -->
                  <template x-if="editingIdx === idx">
                    <button class="dw-icon-btn ok" @click="saveEdit(idx)" title="Save">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </button>
                  </template>
                  <template x-if="editingIdx !== idx">
                    <button class="dw-icon-btn" @click.stop="startEdit(idx)" :disabled="isSpinning" title="Edit">
                      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                  </template>
                  <button class="dw-icon-btn del" @click="removeChoice(idx)" :disabled="isSpinning" title="Remove">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </template>

              <!-- Empty hint -->
              <p x-show="choices.length === 0" class="text-xs text-gray-400 italic text-center py-4">No options yet &mdash; add some below!</p>
            </div>

            <!-- Add input -->
            <div class="dw-add-row">
              <input
                type="text"
                x-model="newChoice"
                @keydown.enter="addChoice()"
                placeholder="Type an option&hellip;"
                maxlength="60"
                class="dw-add-input"
                :disabled="choices.length >= 20 || isSpinning"
              >
              <button
                class="dw-add-btn"
                @click="addChoice()"
                :disabled="!newChoice.trim() || choices.length >= 20 || isSpinning"
              >
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add
              </button>
            </div>

            <!-- Secondary actions -->
            <div class="dw-action-row">
              <button class="dw-sm-btn" @click="shuffleChoices()" :disabled="choices.length < 2 || isSpinning" title="Randomise order">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/></svg>
                Shuffle
              </button>
              <button class="dw-sm-btn red" @click="clearAll()" :disabled="choices.length === 0 || isSpinning">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                Clear All
              </button>
            </div>

          </div><!-- /choices col -->

          <!-- ─ Wheel Column ─ -->
          <div class="dw-wheel-col">

            <!-- Canvas -->
            <div class="dw-canvas-wrap">
              <canvas x-ref="wheelCanvas" width="400" height="400"></canvas>
            </div>

            <!-- Min 2 warning -->
            <div class="dw-min-warn" x-show="validChoices.length < 2 && !isSpinning">
              Add at least <strong>2 options</strong> to spin the wheel
            </div>

            <!-- Spin button -->
            <button
              class="dw-spin-btn"
              :class="{ ready: canSpin && !winner }"
              :disabled="!canSpin"
              @click="spin()"
            >
              <span x-show="!isSpinning" class="flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Spin the Wheel!
              </span>
              <span x-show="isSpinning" class="flex items-center gap-2">
                <span class="dw-spinner"></span>
                Spinning&hellip;
              </span>
            </button>

            <!-- Result -->
            <div
              class="dw-result"
              x-show="winner"
              x-transition
              :style="'border-color:' + winnerColor + ';background:' + winnerColor + '14'"
            >
              <div class="text-2xl mb-1">&#x1F389;</div>
              <div class="dw-result-label">The wheel has decided!</div>
              <div class="dw-result-text" x-text="winner"></div>
              <button class="dw-spin-again" @click="resetSpin()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 4v6h6M23 20v-6h-6"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15"/></svg>
                Spin Again
              </button>
            </div>

          </div><!-- /wheel col -->

        </div><!-- /dw-layout -->
      </div>

      <!-- ── How to Use ── -->
      <div class="card p-5">
        <h2 class="text-base font-semibold text-gray-900 mb-4">How to Use</h2>
        <ol class="space-y-2.5">
          <li class="flex gap-3 text-sm text-gray-600">
            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
            <span><strong class="text-gray-800">Add options</strong> &mdash; type each choice and press Enter or click Add. Use a preset from the sidebar to start quickly.</span>
          </li>
          <li class="flex gap-3 text-sm text-gray-600">
            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
            <span><strong class="text-gray-800">Customise</strong> &mdash; double-click any option to edit it inline. Drag to reorder or hit Shuffle.</span>
          </li>
          <li class="flex gap-3 text-sm text-gray-600">
            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
            <span><strong class="text-gray-800">Spin!</strong> &mdash; click "Spin the Wheel" and watch it slow down to reveal your decision.</span>
          </li>
          <li class="flex gap-3 text-sm text-gray-600">
            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
            <span><strong class="text-gray-800">Accept the result</strong> &mdash; the winning option is highlighted on the wheel and displayed below it. Spin again any time!</span>
          </li>
        </ol>
      </div>

    </div><!-- /main column -->

    <!-- ════════════ SIDEBAR ════════════ -->
    <div class="space-y-5">

      <!-- Quick Presets -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Quick Presets</h3>
        <p class="text-xs text-gray-500 mb-3">Click to load a set of options instantly:</p>
        <div class="flex flex-wrap">
          <template x-for="preset in presets" :key="preset.label">
            <button class="dw-preset-chip" @click="loadPreset(preset.options)" :disabled="isSpinning">
              <span x-text="preset.emoji + ' ' + preset.label"></span>
            </button>
          </template>
        </div>
      </div>

      <!-- Spin History -->
      <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-semibold text-gray-900">Spin History</h3>
          <button
            x-show="history.length > 0"
            class="text-xs text-gray-400 hover:text-red-500 transition-colors"
            @click="history = []"
          >Clear</button>
        </div>

        <div x-show="history.length === 0" class="text-xs text-gray-400 italic text-center py-3">
          Results will appear here after each spin
        </div>

        <template x-for="(item, i) in history" :key="i">
          <div class="dw-hist-item">
            <span class="dw-hist-dot" :style="'background:' + item.color"></span>
            <span class="dw-hist-text" x-text="item.text"></span>
            <span class="text-xs text-gray-400 flex-shrink-0" x-show="i === 0">Latest</span>
          </div>
        </template>
      </div>

      <!-- Tips -->
      <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Tips &amp; Ideas</h3>
        <div class="space-y-2 text-sm text-gray-600">
          <div class="flex gap-2 items-start"><span class="text-indigo-500 font-bold">&#x1F4DD;</span><span>Use <strong class="text-gray-800">double-click</strong> on any option to edit it instantly.</span></div>
          <div class="flex gap-2 items-start"><span class="text-indigo-500 font-bold">&#x1F500;</span><span>Hit <strong class="text-gray-800">Shuffle</strong> to randomise the order before spinning.</span></div>
          <div class="flex gap-2 items-start"><span class="text-indigo-500 font-bold">&#x1F3AF;</span><span>Add <strong class="text-gray-800">duplicate options</strong> to increase their probability of being picked.</span></div>
          <div class="flex gap-2 items-start"><span class="text-indigo-500 font-bold">&#x1F4CB;</span><span>Up to <strong class="text-gray-800">20 options</strong> supported. Resize your browser to make the wheel larger.</span></div>
        </div>
      </div>

    </div><!-- /sidebar -->

  </div><!-- /grid -->
</div>

@endsection

@push('scripts')
<script>
function dwTool() {
  var COLORS = [
    '#ef4444','#f97316','#f59e0b','#22c55e',
    '#14b8a6','#3b82f6','#8b5cf6','#ec4899',
    '#06b6d4','#84cc16','#e11d48','#7c3aed'
  ];

  return {
    COLORS: COLORS,

    // ── Choices ──
    choices: ['Go for a walk', 'Watch a movie', 'Read a book', 'Cook something', 'Call a friend', 'Play a game'],
    newChoice: '',
    editingIdx: -1,
    editText: '',
    error: '',

    // ── Spin state ──
    isSpinning: false,
    currentAngle: 0,
    startAngle: 0,
    targetAngle: 0,
    spinStart: null,
    spinDuration: 5000,
    _winnerIdx: -1,

    // ── Result ──
    winner: null,
    winnerColor: '',
    winnerIdx: -1,
    history: [],

    // ── Presets ──
    presets: [
      { label: 'Yes / No',       emoji: '&#x2753;', options: ['Yes', 'No'] },
      { label: 'Yes / No / Maybe', emoji: '&#x1F914;', options: ['Yes', 'No', 'Maybe'] },
      { label: 'Weekdays',       emoji: '&#x1F4C5;', options: ['Monday','Tuesday','Wednesday','Thursday','Friday'] },
      { label: 'Food',           emoji: '&#x1F35D;', options: ['Pizza','Pasta','Sushi','Tacos','Burgers','Salad'] },
      { label: 'Activities',     emoji: '&#x26BD;',  options: ['Watch TV','Go outside','Cook','Read','Exercise','Nap'] },
      { label: 'Numbers 1-6',    emoji: '&#x1F3B2;', options: ['1','2','3','4','5','6'] },
      { label: 'Seasons',        emoji: '&#x1F342;', options: ['Spring','Summer','Autumn','Winter'] },
      { label: 'Rock / Paper / Scissors', emoji: '&#x270A;', options: ['Rock','Paper','Scissors'] },
    ],

    // ── Computed ──
    get validChoices() {
      return this.choices.filter(function(c) { return c.trim().length > 0; });
    },
    get canSpin() {
      return this.validChoices.length >= 2 && !this.isSpinning;
    },

    // ── Init (called automatically by Alpine) ──
    init: function() {
      var vm = this;
      vm.$nextTick(function() {
        vm.drawWheel();
        vm.$watch('choices', function() {
          if (!vm.isSpinning) vm.drawWheel();
        });
      });
    },

    // ── Choices management ──
    addChoice: function() {
      var text = this.newChoice.trim();
      if (!text) return;
      if (this.choices.length >= 20) { this.error = 'Maximum 20 options allowed.'; return; }
      this.choices.push(text);
      this.newChoice = '';
      this.error = '';
    },
    removeChoice: function(i) {
      if (this.isSpinning) return;
      this.choices.splice(i, 1);
      if (this.editingIdx === i)  this.editingIdx = -1;
      else if (this.editingIdx > i) this.editingIdx--;
      if (this.winnerIdx >= this.choices.length) { this.winner = null; this.winnerIdx = -1; }
    },
    startEdit: function(i) {
      if (this.isSpinning) return;
      this.editingIdx = i;
      this.editText = this.choices[i];
      var vm = this;
      vm.$nextTick(function() {
        var el = document.getElementById('dw-edit-' + i);
        if (el) { el.focus(); el.select(); }
      });
    },
    saveEdit: function(i) {
      var text = this.editText.trim();
      if (text) {
        this.choices[i] = text;
        this.choices = this.choices.slice(); // trigger reactivity
      }
      this.editingIdx = -1;
    },
    cancelEdit: function() {
      this.editingIdx = -1;
    },
    clearAll: function() {
      if (this.isSpinning) return;
      this.choices = [];
      this.winner = null;
      this.winnerIdx = -1;
      this.newChoice = '';
      this.editingIdx = -1;
      this.currentAngle = 0;
      this.error = '';
    },
    shuffleChoices: function() {
      if (this.isSpinning || this.choices.length < 2) return;
      var arr = this.choices.slice();
      for (var i = arr.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
      }
      this.choices = arr;
      this.winner = null;
      this.winnerIdx = -1;
    },
    loadPreset: function(options) {
      if (this.isSpinning) return;
      this.choices = options.slice();
      this.winner = null;
      this.winnerIdx = -1;
      this.currentAngle = 0;
      this.error = '';
    },
    resetSpin: function() {
      this.winner = null;
      this.winnerIdx = -1;
      this.drawWheel();
    },

    // ── Spin ──
    spin: function() {
      if (!this.canSpin) return;
      this.winner = null;
      this.winnerIdx = -1;
      this.error = '';

      var choices = this.validChoices;
      var N = choices.length;
      var sliceAngle = (2 * Math.PI) / N;

      // Randomly pick a winner
      var winIdx = Math.floor(Math.random() * N);

      // Compute final angle so pointer (at top) lands near center of winner slice
      // Pointer at top means pointer is at canvas angle -π/2
      // After rotating wheel by finalAngle, slice i is at canvas angles:
      //   [i*slice - π/2 + finalAngle,  (i+1)*slice - π/2 + finalAngle]
      // Pointer (at -π/2) lands on slice i when:
      //   -finalAngle (mod 2π) ∈ [i*slice, (i+1)*slice]
      // Target: -finalAngle ≡ (winIdx + 0.5)*slice (mid-slice) ± small jitter
      var jitter = (Math.random() * 0.5 - 0.25) * sliceAngle;
      var desired = ((winIdx + 0.5) * sliceAngle + jitter + 2 * Math.PI * 100) % (2 * Math.PI);
      // finalAngle ≡ -desired (mod 2π)  =>  finalAngle ≡ 2π - desired (mod 2π)
      var targetMod = (2 * Math.PI - desired + 2 * Math.PI) % (2 * Math.PI);

      // Ensure at least 7-10 full rotations forward from current angle
      var minExtra = (7 + Math.floor(Math.random() * 4)) * 2 * Math.PI;
      var minTarget = this.currentAngle + minExtra;
      var n = Math.ceil((minTarget - targetMod) / (2 * Math.PI));
      var finalAngle = n * 2 * Math.PI + targetMod;

      this.startAngle    = this.currentAngle;
      this.targetAngle   = finalAngle;
      this.spinDuration  = 4200 + Math.random() * 1800;
      this.spinStart     = null;
      this._winnerIdx    = winIdx;
      this.isSpinning    = true;

      var vm = this;
      requestAnimationFrame(function(ts) { vm.animate(ts); });
    },

    animate: function(ts) {
      if (this.spinStart === null) this.spinStart = ts;
      var elapsed = ts - this.spinStart;
      var t = Math.min(elapsed / this.spinDuration, 1);
      var eased = 1 - Math.pow(1 - t, 4);   // quartic ease-out

      this.currentAngle = this.startAngle + eased * (this.targetAngle - this.startAngle);
      this.drawWheel();

      if (t < 1) {
        var vm = this;
        requestAnimationFrame(function(ts) { vm.animate(ts); });
      } else {
        this.currentAngle  = this.targetAngle;
        this.isSpinning    = false;
        var choices = this.validChoices;
        this.winner        = choices[this._winnerIdx] || choices[0];
        this.winnerColor   = this.COLORS[this._winnerIdx % this.COLORS.length];
        this.winnerIdx     = this._winnerIdx;
        this.history.unshift({ text: this.winner, color: this.winnerColor });
        if (this.history.length > 10) this.history.pop();
        this.drawWheel();
      }
    },

    // ── Drawing ──
    drawWheel: function() {
      var canvas = this.$refs.wheelCanvas;
      if (!canvas) return;
      var ctx    = canvas.getContext('2d');
      var SIZE   = canvas.width;   // 400
      var cx     = SIZE / 2;
      var cy     = SIZE / 2;
      var PTR_H  = 26;             // pointer triangle height
      var radius = cx - PTR_H - 12;

      ctx.clearRect(0, 0, SIZE, SIZE);

      var choices = this.validChoices;
      var N       = choices.length;

      if (N < 2) {
        this.drawEmpty(ctx, SIZE, cx, cy, radius);
        return;
      }

      var sliceAngle = (2 * Math.PI) / N;

      // ── Outer drop shadow ──
      ctx.save();
      ctx.beginPath();
      ctx.arc(cx, cy, radius + 3, 0, 2 * Math.PI);
      ctx.shadowColor  = 'rgba(0,0,0,0.2)';
      ctx.shadowBlur   = 20;
      ctx.shadowOffsetY = 4;
      ctx.fillStyle    = '#e5e7eb';
      ctx.fill();
      ctx.restore();

      // ── Slices (rotated) ──
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(this.currentAngle);

      for (var i = 0; i < N; i++) {
        var sa = i * sliceAngle - Math.PI / 2;
        var ea = sa + sliceAngle;
        var color = this.COLORS[i % this.COLORS.length];

        // fill
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.arc(0, 0, radius, sa, ea);
        ctx.closePath();
        ctx.fillStyle = color;
        ctx.fill();

        // separator line
        ctx.strokeStyle = 'rgba(255,255,255,0.6)';
        ctx.lineWidth   = 1.5;
        ctx.stroke();

        // label
        ctx.save();
        ctx.rotate(sa + sliceAngle / 2);
        ctx.textAlign    = 'right';
        ctx.textBaseline = 'middle';
        var fs = N <= 5 ? 13 : N <= 8 ? 12 : N <= 12 ? 10 : 9;
        ctx.font = '600 ' + fs + 'px Inter, system-ui, sans-serif';
        ctx.fillStyle   = 'rgba(255,255,255,0.96)';
        ctx.shadowColor = 'rgba(0,0,0,0.55)';
        ctx.shadowBlur  = 4;
        var mc = N <= 5 ? 18 : N <= 8 ? 14 : N <= 12 ? 11 : 8;
        var lbl = choices[i].length > mc ? choices[i].substring(0, mc - 1) + '…' : choices[i];
        ctx.fillText(lbl, radius * 0.86, 0);
        ctx.restore();
      }

      // ── Centre hub ──
      ctx.beginPath();
      ctx.arc(0, 0, radius * 0.115, 0, 2 * Math.PI);
      ctx.shadowColor = 'rgba(0,0,0,0.3)';
      ctx.shadowBlur  = 10;
      ctx.fillStyle   = '#ffffff';
      ctx.fill();
      ctx.shadowBlur  = 0;
      ctx.strokeStyle = 'rgba(0,0,0,0.12)';
      ctx.lineWidth   = 1.5;
      ctx.stroke();

      ctx.restore();  // ← removes rotation

      // ── Outer rim ──
      ctx.beginPath();
      ctx.arc(cx, cy, radius, 0, 2 * Math.PI);
      ctx.strokeStyle = 'rgba(255,255,255,0.75)';
      ctx.lineWidth   = 3;
      ctx.stroke();

      // ── Winner slice highlight (after spin) ──
      if (!this.isSpinning && this.winnerIdx >= 0 && N > 0) {
        var wsa = this.winnerIdx * sliceAngle - Math.PI / 2 + this.currentAngle;
        var wea = wsa + sliceAngle;
        ctx.save();
        ctx.translate(cx, cy);
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.arc(0, 0, radius, wsa, wea);
        ctx.closePath();
        ctx.shadowColor  = 'rgba(255,255,200,0.7)';
        ctx.shadowBlur   = 16;
        ctx.strokeStyle  = 'rgba(255,255,255,0.95)';
        ctx.lineWidth    = 3.5;
        ctx.stroke();
        ctx.restore();
      }

      // ── Pointer triangle (fixed, at top) ──
      var tipY  = cy - radius - 1;
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(cx,      tipY + 1);    // pointed tip (pointing into wheel)
      ctx.lineTo(cx - 11, tipY - PTR_H + 4);
      ctx.lineTo(cx + 11, tipY - PTR_H + 4);
      ctx.closePath();
      ctx.fillStyle   = '#4f46e5';
      ctx.shadowColor = 'rgba(79,70,229,0.5)';
      ctx.shadowBlur  = 10;
      ctx.fill();
      ctx.shadowBlur  = 0;
      ctx.strokeStyle = '#ffffff';
      ctx.lineWidth   = 2;
      ctx.stroke();
      ctx.restore();
    },

    drawEmpty: function(ctx, SIZE, cx, cy, radius) {
      // Dashed placeholder circle
      ctx.save();
      ctx.beginPath();
      ctx.arc(cx, cy, radius, 0, 2 * Math.PI);
      ctx.setLineDash([8, 6]);
      ctx.strokeStyle = '#d1d5db';
      ctx.lineWidth   = 2;
      ctx.stroke();
      ctx.setLineDash([]);

      ctx.fillStyle  = '#9ca3af';
      ctx.font       = '500 14px Inter, system-ui, sans-serif';
      ctx.textAlign  = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText('Add options to see the wheel', cx, cy - 10);
      ctx.fillStyle = '#c4b5fd';
      ctx.font      = '600 28px Arial, sans-serif';
      ctx.fillText('❓', cx, cy + 26);
      ctx.restore();

      // Pointer even in empty state
      var tipY = cy - radius - 1;
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(cx, tipY + 1);
      ctx.lineTo(cx - 11, tipY - 22);
      ctx.lineTo(cx + 11, tipY - 22);
      ctx.closePath();
      ctx.fillStyle   = '#c7d2fe';
      ctx.shadowColor = 'rgba(199,210,254,0.4)';
      ctx.shadowBlur  = 8;
      ctx.fill();
      ctx.strokeStyle = '#fff';
      ctx.lineWidth   = 2;
      ctx.stroke();
      ctx.restore();
    },

  }; // end return
}
</script>
@endpush