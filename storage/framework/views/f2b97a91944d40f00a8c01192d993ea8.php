<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('renders_own_faqs', '1'); ?>
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
                ['label' => $tool->name]
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
                ['label' => $tool->name]
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
                
                <div class="card p-6"
                     x-data="toolRunner('decision-maker-wheel')"
                     x-init="init()">

                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Use the Tool</h2>

                    <form @submit.prevent="submit()" class="space-y-4">
                        <?php $__currentLoopData = $tool->inputs->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal53ec273553903683f1630ee765a946f9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53ec273553903683f1630ee765a946f9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tool-input','data' => ['input' => $input]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tool-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['input' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($input)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53ec273553903683f1630ee765a946f9)): ?>
<?php $attributes = $__attributesOriginal53ec273553903683f1630ee765a946f9; ?>
<?php unset($__attributesOriginal53ec273553903683f1630ee765a946f9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53ec273553903683f1630ee765a946f9)): ?>
<?php $component = $__componentOriginal53ec273553903683f1630ee765a946f9; ?>
<?php unset($__componentOriginal53ec273553903683f1630ee765a946f9); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($tool->inputs->isEmpty()): ?>
                            <div class="alert alert-info">
                                This tool's input form is being configured. Check back soon.
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary w-full btn-lg"
                                :disabled="loading">
                            <span x-show="!loading">⚡ Run Tool</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <span class="spinner"></span> Processing...
                            </span>
                        </button>
                    </form>

                    
                    <div x-show="result" x-cloak class="mt-6 result-animate">
                        <?php if (isset($component)) { $__componentOriginal12dca058461a9582f45e3df3907772fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal12dca058461a9582f45e3df3907772fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tool-result','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tool-result'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal12dca058461a9582f45e3df3907772fd)): ?>
<?php $attributes = $__attributesOriginal12dca058461a9582f45e3df3907772fd; ?>
<?php unset($__attributesOriginal12dca058461a9582f45e3df3907772fd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal12dca058461a9582f45e3df3907772fd)): ?>
<?php $component = $__componentOriginal12dca058461a9582f45e3df3907772fd; ?>
<?php unset($__componentOriginal12dca058461a9582f45e3df3907772fd); ?>
<?php endif; ?>
                    </div>

                    
                    <div x-show="error" x-cloak class="mt-4">
                        <div class="alert alert-error" x-text="error"></div>
                    </div>
                </div>

                
                <?php if($tool->long_description): ?>
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
                    <div class="tool-prose">
                        <?php echo nl2br(e($tool->long_description)); ?>

                    </div>
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
                            <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors"><?php echo e($related->name); ?></span>
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
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\decision-maker-wheel.blade.php ENDPATH**/ ?>