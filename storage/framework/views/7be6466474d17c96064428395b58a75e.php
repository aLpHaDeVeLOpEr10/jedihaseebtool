<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['faqs']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['faqs']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-2" x-data="{ open: null }">
    <?php $__currentLoopData = $faqs->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="border border-gray-100 rounded-xl overflow-hidden">
        <button
            type="button"
            @click="open = open === <?php echo e($loop->index); ?> ? null : <?php echo e($loop->index); ?>"
            class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-gray-50 transition-colors">
            <span class="text-sm font-medium text-gray-800"><?php echo e($faq->question); ?></span>
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                 :class="{ 'rotate-180': open === <?php echo e($loop->index); ?> }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div
            x-show="open === <?php echo e($loop->index); ?>"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
            <?php echo nl2br(e($faq->answer)); ?>

        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\components\faq-list.blade.php ENDPATH**/ ?>