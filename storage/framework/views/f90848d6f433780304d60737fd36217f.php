<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items' => []]));

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

foreach (array_filter((['items' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<nav class="flex items-center gap-1.5 text-sm text-gray-500" aria-label="Breadcrumb">
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!$loop->last): ?>
            <?php if(isset($item['url'])): ?>
                <a href="<?php echo e($item['url']); ?>" class="hover:text-brand-600 transition-colors"><?php echo e($item['label']); ?></a>
            <?php else: ?>
                <span><?php echo e($item['label']); ?></span>
            <?php endif; ?>
            <span class="text-gray-300">/</span>
        <?php else: ?>
            <span class="text-gray-700 font-medium truncate max-w-[200px]"><?php echo e($item['label']); ?></span>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>