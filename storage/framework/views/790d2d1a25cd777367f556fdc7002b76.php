
<?php $__env->startSection('title', $page->seo_title ?: $page->title); ?>
<?php $__env->startSection('description', $page->seo_description); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8"><?php echo e($page->title); ?></h1>
    <div class="card p-8 tool-prose">
        <?php echo $page->content; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\page.blade.php ENDPATH**/ ?>