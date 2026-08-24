
<?php $__env->startSection('title', 'All Categories - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-900">All Categories</h1>
        <p class="text-gray-500 mt-2"><?php echo e($categories->count()); ?> categories of free online tools</p>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('categories.show', $category)); ?>" class="card-hover p-6 flex items-start gap-5">
            <div class="text-4xl flex-shrink-0"><?php echo e($category->icon); ?></div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg"><?php echo e($category->name); ?></h2>
                <?php if($category->description): ?>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2"><?php echo e($category->description); ?></p>
                <?php endif; ?>
                <span class="badge badge-primary mt-2"><?php echo e($category->active_tools_count); ?> tools</span>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\categories\index.blade.php ENDPATH**/ ?>