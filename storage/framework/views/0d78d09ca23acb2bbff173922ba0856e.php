<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Tab buttons ── */
.tool-tab {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .55rem 1.1rem;
    border-radius: 1rem;
    border: 1.5px solid;
    font-size: .8rem; font-weight: 600;
    cursor: pointer;
    transition: all .13s;
    white-space: nowrap;
}

/* ── Converter results table ── */
.conv-row {
    display: grid;
    grid-template-columns: 3.5rem 1fr auto auto;
    align-items: center;
    gap: .75rem;
    padding: .65rem 1rem;
    border-radius: .875rem;
    border: 1.5px solid transparent;
    transition: background .15s, border-color .15s;
}
.conv-row.is-active {
    background: #eef2ff;
    border-color: #a5b4fc;
}
.conv-row:not(.is-active):hover {
    background: #f8fafc;
}
.conv-row.is-dec { border-left: 3px solid #4f46e5; }
.conv-row.is-bin { border-left: 3px solid #0891b2; }

/* ── Transfer time bar ── */
.speed-bar-wrap { width: 100%; background: #f1f5f9; border-radius: 9999px; height: 6px; overflow: hidden; }
.speed-bar-fill { height: 100%; border-radius: 9999px; background: #4f46e5; transition: width .4s ease; }

/* ── Drop zone ── */
.drop-zone {
    border: 2.5px dashed #cbd5e1;
    border-radius: 1.25rem;
    padding: 3rem 2rem;
    text-align: center;
    transition: border-color .2s, background .2s;
    cursor: pointer;
}
.drop-zone.drag-over {
    border-color: #4f46e5;
    background: #eef2ff;
}

/* ── File inspector card ── */
.file-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 1.25rem;
    overflow: hidden;
    transition: border-color .15s;
}
.file-card:hover { border-color: #c7d2fe; }

/* ── Storage result big number ── */
.big-result {
    font-size: 3rem; font-weight: 900;
    line-height: 1;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ── Copy flash ── */
@keyframes copiedFlash {
    0%,100% { background: #f0fdf4; color:#166534; }
    50%      { background: #dcfce7; color:#14532d; }
}
.copied-flash { animation: copiedFlash .6s ease 2; }

/* ── Stat card strip ── */
.stat-strip {
    display: grid; gap: 1px;
    background: #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
}
.stat-cell {
    background: white;
    padding: 1rem 1.25rem;
    display: flex; flex-direction: column; gap: .2rem;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="fileSizeCalc()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        
        <div class="flex gap-2 flex-wrap">
            <template x-for="t in TABS" :key="t.id">
                <button type="button"
                        @click="tab = t.id"
                        class="tool-tab"
                        :class="tab === t.id
                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                            : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:bg-gray-50'">
                    <span x-text="t.icon"></span>
                    <span x-text="t.label"></span>
                </button>
            </template>
        </div>

        
        <div x-show="tab === 'convert'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            
            <div class="card p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Convert File Size</h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="form-label">Value</label>
                        <input type="number"
                               x-model.number="cvValue"
                               @input="cvError = ''"
                               min="0"
                               step="any"
                               placeholder="Enter a number…"
                               class="form-input text-lg font-semibold"
                               :class="cvError ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : ''">
                        <p x-show="cvError" class="form-error" x-text="cvError"></p>
                    </div>
                    <div class="sm:w-44">
                        <label class="form-label">Unit</label>
                        <select x-model="cvUnit" class="form-input">
                            <optgroup label="Decimal (SI)">
                                <option value="B">B — Bytes</option>
                                <option value="KB">KB — Kilobytes</option>
                                <option value="MB">MB — Megabytes</option>
                                <option value="GB">GB — Gigabytes</option>
                                <option value="TB">TB — Terabytes</option>
                                <option value="PB">PB — Petabytes</option>
                            </optgroup>
                            <optgroup label="Binary (IEC)">
                                <option value="KiB">KiB — Kibibytes</option>
                                <option value="MiB">MiB — Mebibytes</option>
                                <option value="GiB">GiB — Gibibytes</option>
                                <option value="TiB">TiB — Tebibytes</option>
                                <option value="PiB">PiB — Pebibytes</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="sm:w-32 flex items-end">
                        <button type="button" @click="cvValue = ''; cvError = ''"
                                class="btn btn-secondary w-full">Clear</button>
                    </div>
                </div>

                
                <div class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                    <span class="shrink-0 text-amber-500 mt-0.5">ℹ</span>
                    <span><strong>Decimal (SI):</strong> 1 KB = 1,000 bytes — used by storage manufacturers &amp; network speeds. &nbsp;
                    <strong>Binary (IEC):</strong> 1 KiB = 1,024 bytes — used by operating systems &amp; RAM specs.</span>
                </div>
            </div>

            
            <div x-show="cvBytes !== null && cvValue !== ''"
                 x-transition
                 class="card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex-wrap gap-2">
                    <span class="text-sm font-semibold text-gray-800">Conversion Results</span>
                    <div class="flex gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-medium">
                            <span class="w-2 h-2 rounded-sm bg-indigo-500 inline-block"></span> Decimal (SI)
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-700 font-medium">
                            <span class="w-2 h-2 rounded-sm bg-cyan-500 inline-block"></span> Binary (IEC)
                        </span>
                    </div>
                </div>

                <div class="p-4 space-y-1">
                    <template x-for="r in cvResults" :key="r.abbr">
                        <div class="conv-row"
                             :class="{
                                 'is-active': r.isInput,
                                 'is-dec':    r.type === 'decimal' || r.type === 'both',
                                 'is-bin':    r.type === 'binary',
                             }">
                            
                            <span class="text-xs font-bold px-2 py-1 rounded-lg text-center leading-none"
                                  :class="r.type === 'binary' ? 'bg-cyan-100 text-cyan-800' : 'bg-indigo-100 text-indigo-800'"
                                  x-text="r.abbr"></span>

                            
                            <span class="text-sm text-gray-600" x-text="r.name"></span>

                            
                            <span class="text-sm font-bold text-gray-900 tabular-nums text-right"
                                  x-text="formatVal(r.value)"></span>

                            
                            <button type="button"
                                    @click="copyVal(r.value, r.abbr)"
                                    class="text-gray-300 hover:text-indigo-500 transition-colors shrink-0 p-0.5"
                                    title="Copy value">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs text-gray-500">Exact byte count:</span>
                    <span class="font-black text-gray-900 tabular-nums text-sm"
                          x-text="cvBytes !== null ? cvBytes.toLocaleString('en-US') + ' bytes' : ''"></span>
                </div>
            </div>

            
            <div x-show="cvValue === '' || cvValue === null"
                 class="card p-10 text-center text-gray-400">
                <p class="text-4xl mb-2">💾</p>
                <p class="text-sm">Enter a value above to see all conversions</p>
            </div>

        </div>

        
        <div x-show="tab === 'transfer'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            
            <div class="card p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Download / Upload Time Calculator</h2>

                <div class="grid sm:grid-cols-2 gap-5">
                    
                    <div>
                        <label class="form-label">File Size</label>
                        <div class="flex gap-2">
                            <input type="number"
                                   x-model.number="trSize"
                                   min="0" step="any"
                                   class="form-input flex-1 font-semibold"
                                   placeholder="e.g. 4.7">
                            <select x-model="trSizeUnit" class="form-input w-24">
                                <option value="B">B</option>
                                <option value="KB">KB</option>
                                <option value="MB">MB</option>
                                <option value="GB" selected>GB</option>
                                <option value="TB">TB</option>
                            </select>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Connection Speed</label>
                        <div class="flex gap-2">
                            <input type="number"
                                   x-model.number="trSpeed"
                                   min="0" step="any"
                                   class="form-input flex-1 font-semibold"
                                   placeholder="e.g. 100">
                            <select x-model="trSpeedUnit" class="form-input w-24">
                                <option value="bps">bps</option>
                                <option value="Kbps">Kbps</option>
                                <option value="Mbps" selected>Mbps</option>
                                <option value="Gbps">Gbps</option>
                                <option value="KB/s">KB/s</option>
                                <option value="MB/s">MB/s</option>
                                <option value="GB/s">GB/s</option>
                            </select>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">
                            Protocol Overhead
                            <span class="font-normal text-gray-400">(TCP/HTTP losses)</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="range"
                                   x-model.number="trOverhead"
                                   min="0" max="50" step="1"
                                   class="flex-1 accent-indigo-600">
                            <span class="w-12 text-center font-bold text-indigo-600 text-sm"
                                  x-text="trOverhead + '%'"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 mt-0.5">
                            <span>0% (theoretical)</span><span>50%</span>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Quick Speed Presets</label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="p in SPEED_PRESETS" :key="p.label">
                                <button type="button"
                                        @click="trSpeed = p.speed; trSpeedUnit = p.unit"
                                        class="px-2.5 py-1 rounded-lg border text-xs font-medium transition-all"
                                        :class="trSpeed === p.speed && trSpeedUnit === p.unit
                                            ? 'border-brand-400 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                    <span x-text="p.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="trSize > 0 && trSpeed > 0" x-transition>

                
                <div class="stat-strip sm:grid-cols-2 mb-4">
                    <div class="stat-cell">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Theoretical Time</span>
                        <span class="text-2xl font-black text-indigo-600"
                              x-text="trTimeSec !== null ? formatTime(trTimeSec) : '—'"></span>
                        <span class="text-xs text-gray-400">At full rated speed with no overhead</span>
                    </div>
                    <div class="stat-cell">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Realistic Time (<span x-text="trOverhead"></span>% overhead)
                        </span>
                        <span class="text-2xl font-black text-gray-800"
                              x-text="trTimeWithOverhead !== null ? formatTime(trTimeWithOverhead) : '—'"></span>
                        <span class="text-xs text-gray-400">Accounts for TCP/HTTP protocol losses</span>
                    </div>
                </div>

                
                <div class="card overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <span class="text-sm font-semibold text-gray-800">Speed Comparison</span>
                        <p class="text-xs text-gray-400 mt-0.5">Time to transfer
                            <strong class="text-gray-600" x-text="trSize + ' ' + trSizeUnit"></strong>
                            at common connection speeds
                        </p>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <template x-for="c in trComparisons" :key="c.label">
                            <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-32 shrink-0">
                                    <p class="text-xs font-semibold text-gray-800" x-text="c.label"></p>
                                    <p class="text-xs text-gray-400" x-text="c.speed"></p>
                                </div>
                                <div class="flex-1">
                                    <div class="speed-bar-wrap">
                                        <div class="speed-bar-fill"
                                             :style="'width:' + Math.min(100, trTimeSec ? (c.seconds / trTimeSec * 50).toFixed(1) : 0) + '%;background:' + c.color">
                                        </div>
                                    </div>
                                </div>
                                <div class="w-28 text-right">
                                    <span class="text-sm font-bold text-gray-800" x-text="formatTime(c.seconds)"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        
        <div x-show="tab === 'inspect'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            
            <div class="card p-5">
                <div class="drop-zone"
                     :class="inspDragging ? 'drag-over' : ''"
                     @dragenter.prevent="inspDragging = true"
                     @dragleave.prevent="inspDragging = false"
                     @dragover.prevent
                     @drop.prevent="handleDrop($event)"
                     @click="$refs.fileInput.click()">
                    <input type="file"
                           x-ref="fileInput"
                           multiple
                           class="hidden"
                           @change="handleFileInput($event)">
                    <div class="text-4xl mb-3" x-text="inspDragging ? '📂' : '📁'"></div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">
                        <span x-show="!inspDragging">Drop files here or click to browse</span>
                        <span x-show="inspDragging" class="text-indigo-600">Release to inspect!</span>
                    </p>
                    <p class="text-xs text-gray-400">Any file type — up to 20 files at once. Files are never uploaded.</p>
                </div>

                <div x-show="inspFiles.length > 0"
                     class="flex items-center justify-between mt-3 px-1">
                    <span class="text-sm text-gray-500">
                        <strong class="text-gray-800" x-text="inspFiles.length"></strong>
                        file<span x-show="inspFiles.length !== 1">s</span> inspected
                    </span>
                    <button type="button" @click="clearFiles()" class="btn btn-secondary btn-sm">
                        🗑 Clear All
                    </button>
                </div>
            </div>

            
            <div x-show="inspFiles.length === 0"
                 class="card p-12 text-center text-gray-400">
                <p class="text-5xl mb-3">🔍</p>
                <p class="text-sm font-medium">Drop any file above to see detailed size information</p>
                <p class="text-xs mt-1 opacity-70">Your files are processed locally and never leave your device</p>
            </div>

            
            <div x-show="inspFiles.length > 0" class="space-y-3">
                <template x-for="(f, i) in inspFiles" :key="i">
                    <div class="file-card">
                        
                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-b border-gray-100">
                            <span class="text-2xl shrink-0" x-text="getFileIcon(f.type)"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="f.name"></p>
                                <p class="text-xs text-gray-400" x-text="f.type || 'unknown type'"></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-indigo-600" x-text="formatBytes(f.bytes)"></p>
                                <p class="text-xs text-gray-400" x-text="f.bytes.toLocaleString('en-US') + ' bytes'"></p>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y divide-gray-100">
                            <template x-for="u in INSPECT_UNITS" :key="u.key">
                                <div class="px-4 py-3">
                                    <p class="text-xs text-gray-400 mb-0.5" x-text="u.label"></p>
                                    <p class="text-sm font-bold text-gray-800 tabular-nums"
                                       x-text="formatVal(f.bytes / u.bytes)"></p>
                                </div>
                            </template>
                        </div>

                        
                        <div class="flex flex-wrap gap-x-5 gap-y-1 px-4 py-2.5 border-t border-gray-100 text-xs text-gray-500">
                            <span>📅 Modified: <strong class="text-gray-700" x-text="f.modified"></strong></span>
                            <span x-show="f.dimensions">📐 Dimensions: <strong class="text-gray-700" x-text="f.dimensions"></strong></span>
                            <span>⚖️ Raw: <strong class="text-gray-700" x-text="f.bytes.toLocaleString('en-US') + ' B'"></strong></span>
                        </div>
                    </div>
                </template>
            </div>

        </div>

        
        <div x-show="tab === 'storage'"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            
            <div class="card p-6 space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Storage Planner</h2>
                    <div class="flex gap-2">
                        <button type="button"
                                @click="stMode = 'fit'"
                                class="flex-1 py-2.5 px-4 rounded-xl border-2 text-sm font-semibold transition-all"
                                :class="stMode === 'fit'
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            📦 How many files fit?
                        </button>
                        <button type="button"
                                @click="stMode = 'total'"
                                class="flex-1 py-2.5 px-4 rounded-xl border-2 text-sm font-semibold transition-all"
                                :class="stMode === 'total'
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            📊 How much storage needed?
                        </button>
                    </div>
                </div>

                
                <div x-show="stMode === 'fit'" class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Storage Capacity</label>
                        <div class="flex gap-2">
                            <input type="number" x-model.number="stCapacity" min="0" step="any"
                                   class="form-input flex-1 font-semibold" placeholder="e.g. 500">
                            <select x-model="stCapUnit" class="form-input w-24">
                                <option value="MB">MB</option>
                                <option value="GB" selected>GB</option>
                                <option value="TB">TB</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">File Size (each)</label>
                        <div class="flex gap-2">
                            <input type="number" x-model.number="stFileSize" min="0" step="any"
                                   class="form-input flex-1 font-semibold" placeholder="e.g. 5">
                            <select x-model="stFileUnit" class="form-input w-24">
                                <option value="KB">KB</option>
                                <option value="MB" selected>MB</option>
                                <option value="GB">GB</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="sm:col-span-2">
                        <label class="form-label">Storage Presets</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="p in STORAGE_PRESETS" :key="p.label">
                                <button type="button"
                                        @click="stCapacity = p.size; stCapUnit = p.unit"
                                        class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-all"
                                        :class="stCapacity === p.size && stCapUnit === p.unit
                                            ? 'border-brand-400 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                    <span x-text="p.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                
                <div x-show="stMode === 'total'" class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Number of Files</label>
                        <input type="number" x-model.number="stNumFiles" min="0" step="1"
                               class="form-input font-semibold" placeholder="e.g. 1000">
                    </div>
                    <div>
                        <label class="form-label">File Size (each)</label>
                        <div class="flex gap-2">
                            <input type="number" x-model.number="stFileSize" min="0" step="any"
                                   class="form-input flex-1 font-semibold" placeholder="e.g. 5">
                            <select x-model="stFileUnit" class="form-input w-24">
                                <option value="KB">KB</option>
                                <option value="MB" selected>MB</option>
                                <option value="GB">GB</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="sm:col-span-2">
                        <label class="form-label">File Type Presets</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="p in FILE_PRESETS" :key="p.label">
                                <button type="button"
                                        @click="stFileSize = p.size; stFileUnit = p.unit"
                                        class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-all"
                                        :class="stFileSize === p.size && stFileUnit === p.unit
                                            ? 'border-brand-400 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                    <span x-text="p.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="stResult !== null" x-transition class="card overflow-hidden">

                
                <div class="p-8 text-center border-b border-gray-100">
                    <template x-if="stMode === 'fit'">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Files that fit in
                                <strong x-text="stCapacity + ' ' + stCapUnit"></strong>
                            </p>
                            <p class="big-result" x-text="stResult ? stResult.count.toLocaleString('en-US') : '0'"></p>
                            <p class="text-xs text-gray-400 mt-1">
                                files of <span x-text="stFileSize + ' ' + stFileUnit"></span> each
                            </p>
                        </div>
                    </template>
                    <template x-if="stMode === 'total'">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total storage for
                                <strong x-text="stNumFiles.toLocaleString('en-US') + ' files'"></strong>
                            </p>
                            <p class="big-result"
                               x-text="stResult ? formatBytes(stResult.totalBytes) : '0 B'"></p>
                            <p class="text-xs text-gray-400 mt-1">
                                at <span x-text="stFileSize + ' ' + stFileUnit"></span> per file
                            </p>
                        </div>
                    </template>
                </div>

                
                <div class="p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                        How does that compare?
                    </p>
                    <div class="space-y-2">
                        <template x-for="ref in stComparisons" :key="ref.label">
                            <div class="flex items-center gap-3">
                                <span class="text-lg shrink-0" x-text="ref.icon"></span>
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-600" x-text="ref.label"></span>
                                        <span class="font-semibold text-gray-800" x-text="ref.pctLabel"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-full rounded-full bg-indigo-400 transition-all duration-500"
                                             :style="'width:' + ref.pct + '%'"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="pt-2">
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
/* ═══════════════════════════════════════════════════════════
   FILE SIZE CALCULATOR  —  Alpine.js component
═══════════════════════════════════════════════════════════ */
function fileSizeCalc() {
    return {
        tab: 'convert',

        /* ── Shared unit table ── */
        ALL_UNITS: [
            { abbr:'B',   name:'Byte',      type:'both',    bytes:1 },
            { abbr:'KB',  name:'Kilobyte',  type:'decimal', bytes:1e3 },
            { abbr:'KiB', name:'Kibibyte',  type:'binary',  bytes:1024 },
            { abbr:'MB',  name:'Megabyte',  type:'decimal', bytes:1e6 },
            { abbr:'MiB', name:'Mebibyte',  type:'binary',  bytes:1048576 },
            { abbr:'GB',  name:'Gigabyte',  type:'decimal', bytes:1e9 },
            { abbr:'GiB', name:'Gibibyte',  type:'binary',  bytes:1073741824 },
            { abbr:'TB',  name:'Terabyte',  type:'decimal', bytes:1e12 },
            { abbr:'TiB', name:'Tebibyte',  type:'binary',  bytes:1099511627776 },
            { abbr:'PB',  name:'Petabyte',  type:'decimal', bytes:1e15 },
            { abbr:'PiB', name:'Pebibyte',  type:'binary',  bytes:1125899906842624 },
        ],

        INSPECT_UNITS: [
            { key:'B',  label:'Bytes',      bytes:1 },
            { key:'KB', label:'Kilobytes',  bytes:1e3 },
            { key:'MB', label:'Megabytes',  bytes:1e6 },
            { key:'GB', label:'Gigabytes',  bytes:1e9 },
        ],

        TABS: [
            { id:'convert',  icon:'🔄', label:'Unit Converter'  },
            { id:'transfer', icon:'⏱️', label:'Transfer Time'   },
            { id:'inspect',  icon:'🔍', label:'File Inspector'  },
            { id:'storage',  icon:'🗄️', label:'Storage Planner' },
        ],

        /* ── Converter ── */
        cvValue: '',
        cvUnit:  'MB',
        cvError: '',
        _copied: null,

        /* ── Transfer time ── */
        trSize:      1,
        trSizeUnit:  'GB',
        trSpeed:     100,
        trSpeedUnit: 'Mbps',
        trOverhead:  10,

        SPEED_UNITS: [
            { abbr:'bps',  bps:1       },
            { abbr:'Kbps', bps:1e3     },
            { abbr:'Mbps', bps:1e6     },
            { abbr:'Gbps', bps:1e9     },
            { abbr:'KB/s', bps:8e3     },
            { abbr:'MB/s', bps:8e6     },
            { abbr:'GB/s', bps:8e9     },
        ],

        SPEED_PRESETS: [
            { label:'Dial-up (56K)',  speed:56,  unit:'Kbps' },
            { label:'3G (1 Mbps)',    speed:1,   unit:'Mbps' },
            { label:'4G (20 Mbps)',   speed:20,  unit:'Mbps' },
            { label:'Cable (50)',     speed:50,  unit:'Mbps' },
            { label:'Fiber (100)',    speed:100, unit:'Mbps' },
            { label:'Fiber (500)',    speed:500, unit:'Mbps' },
            { label:'Gigabit',        speed:1,   unit:'Gbps' },
        ],

        /* ── File inspector ── */
        inspFiles:    [],
        inspDragging: false,

        /* ── Storage planner ── */
        stMode:     'fit',
        stCapacity: 500,
        stCapUnit:  'GB',
        stFileSize: 5,
        stFileUnit: 'MB',
        stNumFiles: 1000,

        STORAGE_PRESETS: [
            { label:'128 GB (Phone)',   size:128,  unit:'GB' },
            { label:'256 GB (Phone)',   size:256,  unit:'GB' },
            { label:'512 GB (SSD)',     size:512,  unit:'GB' },
            { label:'1 TB (HDD)',       size:1,    unit:'TB' },
            { label:'2 TB (HDD)',       size:2,    unit:'TB' },
            { label:'4 TB (HDD)',       size:4,    unit:'TB' },
        ],

        FILE_PRESETS: [
            { label:'📷 JPEG photo (3 MB)',       size:3,    unit:'MB' },
            { label:'📷 RAW photo (25 MB)',        size:25,   unit:'MB' },
            { label:'🎵 MP3 song (5 MB)',          size:5,    unit:'MB' },
            { label:'📄 PDF doc (500 KB)',         size:500,  unit:'KB' },
            { label:'🎬 4K video/min (400 MB)',    size:400,  unit:'MB' },
            { label:'🎬 1080p video/min (130 MB)', size:130,  unit:'MB' },
        ],

        /* ══════════════════════════════════
           COMPUTED
        ══════════════════════════════════ */

        get cvBytes() {
            var v = parseFloat(this.cvValue);
            if (this.cvValue === '' || isNaN(v) || v < 0) return null;
            var u = this._findUnit(this.cvUnit);
            return u ? v * u.bytes : null;
        },

        get cvResults() {
            var bytes = this.cvBytes;
            if (bytes === null) return [];
            var self = this;
            return this.ALL_UNITS.map(function(u) {
                return {
                    abbr:    u.abbr,
                    name:    u.name,
                    type:    u.type,
                    value:   bytes / u.bytes,
                    isInput: u.abbr === self.cvUnit,
                };
            });
        },

        get trFileBits() {
            var u = this._findUnit(this.trSizeUnit);
            if (!u || !this.trSize) return 0;
            return this.trSize * u.bytes * 8;
        },

        get trSpeedBps() {
            var u = this.SPEED_UNITS.find(function(u) { return u.abbr === this.trSpeedUnit; }.bind(this));
            return (u && this.trSpeed) ? this.trSpeed * u.bps : 0;
        },

        get trTimeSec() {
            if (!this.trSpeedBps || !this.trFileBits) return null;
            return this.trFileBits / this.trSpeedBps;
        },

        get trTimeWithOverhead() {
            if (this.trTimeSec === null) return null;
            return this.trTimeSec * (1 + this.trOverhead / 100);
        },

        get trComparisons() {
            var bits = this.trFileBits;
            return [
                { label:'Dial-up',        speed:'56 Kbps',  bps:56e3,  color:'#f87171' },
                { label:'3G (slow)',       speed:'1 Mbps',   bps:1e6,   color:'#fb923c' },
                { label:'4G / LTE',        speed:'20 Mbps',  bps:20e6,  color:'#fbbf24' },
                { label:'Cable 50 Mbps',   speed:'50 Mbps',  bps:50e6,  color:'#34d399' },
                { label:'Fiber 100 Mbps',  speed:'100 Mbps', bps:100e6, color:'#22d3ee' },
                { label:'Fiber 500 Mbps',  speed:'500 Mbps', bps:500e6, color:'#818cf8' },
                { label:'Gigabit 1 Gbps',  speed:'1 Gbps',   bps:1e9,   color:'#a78bfa' },
                { label:'10 Gigabit',      speed:'10 Gbps',  bps:10e9,  color:'#c084fc' },
            ].map(function(c) {
                return Object.assign({}, c, { seconds: bits / c.bps });
            });
        },

        get stResult() {
            var capUnit  = this._findUnit(this.stCapUnit)  || { bytes: 1 };
            var fileUnit = this._findUnit(this.stFileUnit) || { bytes: 1 };
            var capBytes  = this.stCapacity * capUnit.bytes;
            var fileBytes = this.stFileSize * fileUnit.bytes;

            if (this.stMode === 'fit') {
                if (!fileBytes || !this.stCapacity || !this.stFileSize) return null;
                return { count: Math.floor(capBytes / fileBytes), totalBytes: capBytes };
            } else {
                if (!this.stNumFiles || !this.stFileSize) return null;
                var total = this.stNumFiles * fileBytes;
                return { count: this.stNumFiles, totalBytes: total };
            }
        },

        get stComparisons() {
            if (!this.stResult) return [];
            var bytes = this.stResult.totalBytes;
            var refs  = [
                { icon:'💾', label:'Floppy disk (1.44 MB)',    bytes:1474560       },
                { icon:'💿', label:'CD-ROM (700 MB)',           bytes:734003200     },
                { icon:'📀', label:'DVD (4.7 GB)',              bytes:5046586573    },
                { icon:'📱', label:'Phone storage (128 GB)',    bytes:137438953472  },
                { icon:'💻', label:'Laptop SSD (512 GB)',       bytes:549755813888  },
                { icon:'🖥️', label:'Desktop HDD (2 TB)',        bytes:2199023255552 },
            ];
            return refs.map(function(r) {
                var pct = Math.min(100, (bytes / r.bytes) * 100);
                var pctLabel = pct < 0.01 ? '< 0.01%'
                             : pct > 9999 ? '> 100×'
                             : pct.toFixed(pct < 1 ? 2 : 1) + '%';
                return Object.assign({}, r, { pct: Math.min(100, pct), pctLabel: pctLabel });
            });
        },

        /* ══════════════════════════════════
           INIT
        ══════════════════════════════════ */

        init() {
            /* Default: show a sample conversion */
            this.cvValue = '1';
        },

        /* ══════════════════════════════════
           FILE HANDLING
        ══════════════════════════════════ */

        handleDrop(e) {
            this.inspDragging = false;
            this._processFiles(e.dataTransfer.files);
        },

        handleFileInput(e) {
            this._processFiles(e.target.files);
            e.target.value = ''; /* allow same file re-selection */
        },

        _processFiles(fileList) {
            var self = this;
            Array.from(fileList).slice(0, 20).forEach(function(file) {
                var item = {
                    name:       file.name,
                    type:       file.type || 'application/octet-stream',
                    bytes:      file.size,
                    modified:   new Date(file.lastModified).toLocaleString('en-US', {
                                    year:'numeric', month:'short', day:'numeric',
                                    hour:'2-digit', minute:'2-digit'
                                }),
                    dimensions: null,
                };

                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var img = new Image();
                        img.onload = function() {
                            item.dimensions = img.width + ' × ' + img.height + ' px';
                            /* Trigger Alpine reactivity by replacing the array */
                            self.inspFiles = self.inspFiles.slice();
                        };
                        img.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }

                self.inspFiles.push(item);
            });
        },

        clearFiles() {
            this.inspFiles = [];
        },

        /* ══════════════════════════════════
           COPY
        ══════════════════════════════════ */

        copyVal(value, unit) {
            var text = this.formatVal(value) + ' ' + unit;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                var el = document.createElement('textarea');
                el.value = text; el.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(el); el.select();
                document.execCommand('copy'); document.body.removeChild(el);
            }
        },

        /* ══════════════════════════════════
           FORMATTING HELPERS
        ══════════════════════════════════ */

        formatVal(n) {
            if (n === 0) return '0';
            if (!isFinite(n)) return '∞';
            var abs = Math.abs(n);
            if (abs > 0 && abs < 1e-9)  return n.toExponential(3);
            if (abs < 0.00001) return n.toPrecision(4);
            if (abs < 1)       return n.toFixed(6).replace(/0+$/, '').replace(/\.$/, '');
            if (abs >= 1e15)   return n.toExponential(3);
            if (Number.isInteger(n)) return n.toLocaleString('en-US');
            var dec = abs < 10 ? 6 : abs < 1000 ? 4 : abs < 1e6 ? 2 : 1;
            return n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: dec });
        },

        formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            var tiers = [
                { t: 1e15, s: 'PB' }, { t: 1e12, s: 'TB' },
                { t: 1e9,  s: 'GB' }, { t: 1e6,  s: 'MB' },
                { t: 1e3,  s: 'KB' }, { t: 1,    s: 'B'  },
            ];
            for (var i = 0; i < tiers.length; i++) {
                if (bytes >= tiers[i].t) {
                    var val = bytes / tiers[i].t;
                    return (val >= 100 ? Math.round(val) : parseFloat(val.toFixed(2))) + ' ' + tiers[i].s;
                }
            }
            return bytes + ' B';
        },

        formatTime(seconds) {
            if (!seconds || seconds <= 0) return '—';
            if (seconds < 1)      return '< 1 sec';
            if (seconds < 60)     return Math.round(seconds) + ' sec';
            if (seconds < 3600) {
                var m = Math.floor(seconds / 60);
                var s = Math.round(seconds % 60);
                return m + ' min' + (s > 0 ? ' ' + s + ' sec' : '');
            }
            if (seconds < 86400) {
                var h = Math.floor(seconds / 3600);
                var m2 = Math.round((seconds % 3600) / 60);
                return h + ' hr' + (m2 > 0 ? ' ' + m2 + ' min' : '');
            }
            return (seconds / 86400).toFixed(1) + ' days';
        },

        getFileIcon(type) {
            if (!type || type === 'application/octet-stream') return '📄';
            if (type.startsWith('image/'))   return '🖼️';
            if (type.startsWith('video/'))   return '🎬';
            if (type.startsWith('audio/'))   return '🎵';
            if (type.includes('pdf'))        return '📕';
            if (type.includes('zip') || type.includes('rar') || type.includes('gzip') || type.includes('7z')) return '🗜️';
            if (type.includes('word') || type.includes('document')) return '📝';
            if (type.includes('excel') || type.includes('spreadsheet')) return '📊';
            if (type.includes('powerpoint') || type.includes('presentation')) return '📊';
            if (type.includes('text/'))      return '📃';
            if (type.includes('html') || type.includes('xml')) return '🌐';
            if (type.includes('javascript') || type.includes('json') || type.includes('css')) return '💻';
            if (type.includes('font'))       return '🔤';
            return '📄';
        },

        /* ── Private ── */
        _findUnit(abbr) {
            return this.ALL_UNITS.find(function(u) { return u.abbr === abbr; });
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\file-size-calculator.blade.php ENDPATH**/ ?>