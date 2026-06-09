<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14">
                    <?php echo e($tool->icon); ?>

                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo e($tool->name); ?></h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
            </div>
            <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
                ['label' => 'Home', 'url' => url('/')],
                ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
                ['label' => $tool->name],
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['label' => 'Home', 'url' => url('/')],
                ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
                ['label' => $tool->name],
            ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="grid gap-8 lg:grid-cols-3">

            
            <div class="lg:col-span-2 space-y-6">

                
                <div class="card p-6" x-data="mp4ToMp3()">

                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Convert MP4 to MP3</h2>

                    
                    <div x-show="step === 'idle'">
                        <label
                            for="mp4-upload"
                            class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed rounded-2xl cursor-pointer transition-colors duration-200"
                            :class="dragging
                                ? 'border-brand-400 bg-brand-50'
                                : 'border-gray-200 bg-gray-50 hover:border-brand-300 hover:bg-brand-50/60'"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="onDrop($event)">
                            <div class="flex flex-col items-center gap-2 text-center px-6 pointer-events-none">
                                <span class="text-4xl">🎬</span>
                                <p class="text-sm font-medium text-gray-700">
                                    Drop your MP4 file here, or
                                    <span class="text-brand-600">click to browse</span>
                                </p>
                                <p class="text-xs text-gray-400">MP4 files only &middot; Max 500 MB</p>
                            </div>
                            <input id="mp4-upload" type="file" accept=".mp4,video/mp4" class="hidden"
                                   @change="onFileSelect($event)">
                        </label>
                    </div>

                    
                    <div x-show="step === 'ready'" x-cloak class="space-y-5">

                        
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <span class="text-3xl flex-shrink-0">🎬</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
                                <p class="text-xs text-gray-500 mt-0.5" x-text="fileSize"></p>
                            </div>
                            <button @click="resetTool()" type="button"
                                    class="btn btn-sm btn-secondary flex-shrink-0">
                                Change
                            </button>
                        </div>

                        
                        <div>
                            <label class="form-label">Output Quality</label>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="opt in qualityOptions" :key="opt.kbps">
                                    <button
                                        type="button"
                                        @click="quality = opt.kbps"
                                        class="flex flex-col items-center py-2.5 px-3 rounded-xl border text-sm transition-all duration-150"
                                        :class="quality === opt.kbps
                                            ? 'border-brand-500 bg-brand-50 text-brand-700 font-semibold shadow-sm'
                                            : 'border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
                                        <span x-text="opt.label"></span>
                                        <span class="text-xs mt-0.5 opacity-60" x-text="opt.hint"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        
                        <button @click="startConvert()" type="button"
                                class="btn btn-primary w-full btn-lg">
                            ⚡ Convert to MP3
                        </button>
                    </div>

                    
                    <div x-show="step === 'converting'" x-cloak class="py-4 space-y-4">
                        <div class="flex items-center justify-center gap-3 text-brand-600">
                            <span class="spinner"></span>
                            <span class="text-sm font-medium" x-text="progressLabel"></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-brand-600 h-2.5 rounded-full transition-all duration-300"
                                 :style="'width:' + progress + '%'"></div>
                        </div>
                        <p class="text-center text-xs text-gray-400" x-text="progress + '% complete'"></p>
                        <p class="text-center text-xs text-gray-400">
                            Conversion runs entirely in your browser — large files may take a moment.
                        </p>
                    </div>

                    
                    <div x-show="step === 'done'" x-cloak class="result-animate">
                        <div class="flex flex-col items-center gap-4 p-6 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                            <span class="text-5xl">🎵</span>
                            <div>
                                <p class="font-semibold text-gray-800" x-text="outputName"></p>
                                <p class="text-sm text-gray-500 mt-1"
                                   x-text="outputSize + ' &middot; MP3 &middot; ' + quality + ' kbps'"></p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <a :href="downloadUrl" :download="outputName"
                                   class="btn btn-success btn-lg gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download MP3
                                </a>
                                <button @click="resetTool()" type="button"
                                        class="btn btn-secondary btn-lg">
                                    Convert Another
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div x-show="errorMsg" x-cloak class="mt-4">
                        <div class="alert alert-error flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="errorMsg"></span>
                        </div>
                    </div>

                </div>

                
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">How It Works</h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <?php $__currentLoopData = [
                            ['📁', '1. Upload',   'Select or drag-and-drop your MP4 video file.'],
                            ['⚙️', '2. Convert',  'Audio is decoded and re-encoded as MP3 right in your browser — no server needed.'],
                            ['⬇️', '3. Download', 'Click Download and save your MP3 file instantly.'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $title, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col items-center text-center gap-2 p-4 bg-gray-50 rounded-xl">
                            <span class="text-3xl"><?php echo e($icon); ?></span>
                            <span class="text-sm font-semibold text-gray-800"><?php echo e($title); ?></span>
                            <span class="text-xs text-gray-500 leading-relaxed"><?php echo e($desc); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <?php if($tool->long_description): ?>
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
                    <div class="tool-prose"><?php echo nl2br(e($tool->long_description)); ?></div>
                </div>
                <?php endif; ?>

                
                <?php if($tool->faqs->count() > 0): ?>
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <?php if (isset($component)) { $__componentOriginal3d56a80c35333d0f1afd23147c30df36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d56a80c35333d0f1afd23147c30df36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.faq-list','data' => ['faqs' => $tool->faqs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('faq-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['faqs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tool->faqs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d56a80c35333d0f1afd23147c30df36)): ?>
<?php $attributes = $__attributesOriginal3d56a80c35333d0f1afd23147c30df36; ?>
<?php unset($__attributesOriginal3d56a80c35333d0f1afd23147c30df36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d56a80c35333d0f1afd23147c30df36)): ?>
<?php $component = $__componentOriginal3d56a80c35333d0f1afd23147c30df36; ?>
<?php unset($__componentOriginal3d56a80c35333d0f1afd23147c30df36); ?>
<?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

            
            <div class="space-y-6">

                
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>🔒</span> 100% Private
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Everything runs in your browser. Your file is never uploaded to any server.
                    </p>
                </div>

                
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
                    <ul class="space-y-2 text-xs text-gray-500 leading-relaxed">
                        <li class="flex items-start gap-2">
                            <span class="text-brand-500 mt-0.5">•</span>
                            Choose <strong>128 kbps</strong> for speech or podcasts.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-500 mt-0.5">•</span>
                            Choose <strong>192 kbps</strong> or higher for music.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-500 mt-0.5">•</span>
                            Files must contain an audio track to convert.
                        </li>
                    </ul>
                </div>

                
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
                    <a href="<?php echo e(route('categories.show', $tool->category)); ?>"
                       class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
                        <span class="text-xl"><?php echo e($tool->category->icon); ?></span>
                        <span class="font-medium text-brand-700"><?php echo e($tool->category->name); ?></span>
                    </a>
                </div>

                
                <?php if($relatedTools->count() > 0): ?>
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.show', $related)); ?>"
                           class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                            <span class="text-lg"><?php echo e($related->icon); ?></span>
                            <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">
                                <?php echo e($related->name); ?>

                            </span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.min.js"></script>
<script>
function mp4ToMp3() {
    return {
        // state machine: 'idle' | 'ready' | 'converting' | 'done'
        step: 'idle',
        dragging: false,

        // file
        _file: null,
        fileName: '',
        fileSize: '',

        // options
        quality: 128,
        qualityOptions: [
            { kbps: 128, label: '128 kbps', hint: 'Standard' },
            { kbps: 192, label: '192 kbps', hint: 'High'     },
            { kbps: 320, label: '320 kbps', hint: 'Best'     },
        ],

        // progress
        progress: 0,
        progressLabel: 'Reading file…',

        // output
        outputName: '',
        outputSize: '',
        downloadUrl: null,

        // error
        errorMsg: '',

        // ── file selection ────────────────────────────────────────────────
        onFileSelect(event) {
            const f = event.target.files[0];
            if (f) this._setFile(f);
        },

        onDrop(event) {
            this.dragging = false;
            const f = event.dataTransfer.files[0];
            if (f) this._setFile(f);
        },

        _setFile(f) {
            this.errorMsg = '';

            const lowerName = f.name.toLowerCase();
            const validMime = ['video/mp4', 'video/mpeg', 'video/x-m4v'];
            if (!validMime.includes(f.type) && !lowerName.endsWith('.mp4')) {
                this.errorMsg = 'Please select a valid MP4 (.mp4) file.';
                return;
            }
            if (f.size > 500 * 1024 * 1024) {
                this.errorMsg = 'File is too large. Maximum allowed size is 500 MB.';
                return;
            }

            this._file  = f;
            this.fileName = f.name;
            this.fileSize = this._fmtSize(f.size);
            this.step = 'ready';
        },

        // ── conversion ───────────────────────────────────────────────────
        async startConvert() {
            if (!this._file) return;

            this.errorMsg = '';
            this.step     = 'converting';
            this.progress = 0;
            this.progressLabel = 'Reading file…';

            try {
                // 1. Read file
                const arrayBuffer = await this._readFile(this._file);
                this.progress = 15;
                this.progressLabel = 'Decoding audio…';

                // 2. Decode via Web Audio API (browser handles AAC/MP4 natively)
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) throw new Error('Your browser does not support the Web Audio API.');

                const audioCtx = new AudioCtx();
                let audioBuffer;
                try {
                    audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
                } catch {
                    throw new Error('Could not decode audio. Make sure the MP4 file contains an audio track.');
                }
                await audioCtx.close();

                this.progress = 35;
                this.progressLabel = 'Encoding to MP3…';

                // 3. Extract PCM
                const channels  = Math.min(audioBuffer.numberOfChannels, 2);
                const sampleRate = audioBuffer.sampleRate;
                const left  = audioBuffer.getChannelData(0);
                const right = channels > 1 ? audioBuffer.getChannelData(1) : left;

                // 4. lamejs encode
                const mp3Blobs = await this._encodeMp3(left, right, channels, sampleRate);

                this.progress = 98;
                this.progressLabel = 'Finalising…';

                // 5. Build blob & URL
                const blob = new Blob(mp3Blobs, { type: 'audio/mpeg' });
                if (this.downloadUrl) URL.revokeObjectURL(this.downloadUrl);
                this.downloadUrl = URL.createObjectURL(blob);
                this.outputName  = this._file.name.replace(/\.[^.]+$/, '') + '.mp3';
                this.outputSize  = this._fmtSize(blob.size);

                this.progress = 100;
                this.step = 'done';

            } catch (err) {
                this.step     = 'ready';   // go back so user can retry
                this.errorMsg = err.message || 'Conversion failed. Please try a different file.';
                console.error('[mp4-to-mp3]', err);
            }
        },

        // lamejs MP3 encoding with async yields so the progress bar repaints
        async _encodeMp3(left, right, channels, sampleRate) {
            const encoder   = new lamejs.Mp3Encoder(channels, sampleRate, this.quality);
            const blockSize = 1152;
            const total     = left.length;
            const chunks    = [];

            for (let i = 0; i < total; i += blockSize) {
                const end  = Math.min(i + blockSize, total);
                const lBuf = this._f32ToI16(left.subarray(i, end));
                const rBuf = channels > 1 ? this._f32ToI16(right.subarray(i, end)) : lBuf;

                const out = channels > 1
                    ? encoder.encodeBuffer(lBuf, rBuf)
                    : encoder.encodeBuffer(lBuf);

                if (out.length > 0) chunks.push(new Uint8Array(out));

                // yield every ~500 blocks so the UI can repaint
                if (i % (blockSize * 500) === 0) {
                    this.progress = 35 + Math.round((i / total) * 60);
                    this.progressLabel = 'Encoding to MP3… ' + this.progress + '%';
                    await new Promise(r => setTimeout(r, 0));
                }
            }

            const tail = encoder.flush();
            if (tail.length > 0) chunks.push(new Uint8Array(tail));

            return chunks;
        },

        // ── helpers ───────────────────────────────────────────────────────
        _readFile(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload  = e => resolve(e.target.result);
                reader.onerror = () => reject(new Error('Failed to read the file.'));
                reader.readAsArrayBuffer(file);
            });
        },

        _f32ToI16(float32) {
            const int16 = new Int16Array(float32.length);
            for (let i = 0; i < float32.length; i++) {
                const s = Math.max(-1, Math.min(1, float32[i]));
                int16[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
            }
            return int16;
        },

        _fmtSize(bytes) {
            if (!bytes) return '';
            if (bytes < 1024)        return bytes + ' B';
            if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        },

        // ── reset ─────────────────────────────────────────────────────────
        resetTool() {
            if (this.downloadUrl) URL.revokeObjectURL(this.downloadUrl);
            this._file      = null;
            this.fileName   = '';
            this.fileSize   = '';
            this.step       = 'idle';
            this.progress   = 0;
            this.errorMsg   = '';
            this.downloadUrl = null;
            this.outputName  = '';
            this.outputSize  = '';
            // reset the hidden input so the same file can be re-selected
            const inp = document.getElementById('mp4-upload');
            if (inp) inp.value = '';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/tools/generated/mp4-to-mp3.blade.php ENDPATH**/ ?>