<a href="<?php echo e(route('tools.show', $tool)); ?>" class="tool-card group">
    <div class="flex items-start gap-3">
        <div class="tool-icon flex-shrink-0 text-xl"
             style="background: <?php echo e($tool->color); ?>22; color: <?php echo e($tool->color); ?>">
            <?php echo e($tool->icon); ?>

        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 text-sm leading-tight group-hover:text-brand-600 transition-colors">
                <?php echo e($tool->name); ?>

            </h3>
            <?php if($tool->short_description): ?>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($tool->short_description); ?></p>
            <?php endif; ?>
        </div>
        <?php if($tool->is_featured): ?>
        <span class="text-yellow-400 text-xs flex-shrink-0">★</span>
        <?php endif; ?>
    </div>
    <div class="flex items-center justify-between mt-2">
        <span class="badge badge-gray text-xs"><?php echo e($tool->category->name ?? ''); ?></span>
        <span class="text-xs text-gray-400"><?php echo e(number_format($tool->view_count)); ?> uses</span>
    </div>
</a>
<?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\components\tool-card.blade.php ENDPATH**/ ?>