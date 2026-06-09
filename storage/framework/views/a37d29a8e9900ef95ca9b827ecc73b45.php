

<?php $__env->startSection('title', $category->seo_title); ?>
<?php $__env->startSection('description', $category->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-brand-600">Home</a>
            <span>›</span>
            <a href="<?php echo e(route('categories.index')); ?>" class="hover:text-brand-600">Categories</a>
            <span>›</span>
            <span class="text-gray-900"><?php echo e($category->name); ?></span>
        </nav>
        <div class="flex items-center gap-4">
            <span class="text-5xl"><?php echo e($category->icon); ?></span>
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?php echo e($category->name); ?></h1>
                <?php if($category->description): ?>
                <p class="text-gray-500 mt-2"><?php echo e($category->description); ?></p>
                <?php endif; ?>
                <p class="text-sm text-gray-400 mt-1"><?php echo e($tools->total()); ?> tools available</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">
            Showing <strong><?php echo e($tools->firstItem()); ?>–<?php echo e($tools->lastItem()); ?></strong> of <strong><?php echo e($tools->total()); ?></strong>
        </p>
        <select onchange="window.location = this.value" class="form-input py-1.5 text-sm w-auto">
            <option value="<?php echo e(route('categories.show', [$category, 'sort' => 'default'])); ?>" <?php echo e(!request('sort') ? 'selected' : ''); ?>>Default</option>
            <option value="<?php echo e(route('categories.show', [$category, 'sort' => 'popular'])); ?>" <?php echo e(request('sort') === 'popular' ? 'selected' : ''); ?>>Most Popular</option>
            <option value="<?php echo e(route('categories.show', [$category, 'sort' => 'newest'])); ?>" <?php echo e(request('sort') === 'newest' ? 'selected' : ''); ?>>Newest</option>
            <option value="<?php echo e(route('categories.show', [$category, 'sort' => 'name'])); ?>" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>A–Z</option>
        </select>
    </div>

    <?php if($tools->count() > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('components.tool-card', ['tool' => $tool], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($tools->links()); ?></div>
    <?php else: ?>
    <div class="card p-16 text-center">
        <div class="text-5xl mb-4">🔍</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No tools in this category yet</h3>
        <p class="text-gray-500">Check back soon — we're adding new tools regularly.</p>
        <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-primary mt-4">Browse All Tools</a>
    </div>
    <?php endif; ?>

    <?php if($relatedCategories->count() > 0): ?>
    <div class="mt-12">
        <h2 class="text-xl font-bold text-gray-900 mb-5">Other Categories</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            <?php $__currentLoopData = $relatedCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('categories.show', $cat)); ?>"
               class="card-hover p-4 text-center flex flex-col items-center gap-2">
                <span class="text-2xl"><?php echo e($cat->icon); ?></span>
                <span class="text-xs font-medium text-gray-700"><?php echo e($cat->name); ?></span>
                <span class="text-xs text-gray-400"><?php echo e($cat->tools_count); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/public/categories/show.blade.php ENDPATH**/ ?>