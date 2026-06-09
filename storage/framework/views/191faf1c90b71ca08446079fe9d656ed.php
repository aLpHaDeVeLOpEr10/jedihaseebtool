
<?php $__env->startSection('title', 'Search: ' . $q . ' - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
        <form action="<?php echo e(route('search')); ?>" method="GET" class="mt-4 max-w-xl">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="<?php echo e($q); ?>" placeholder="Search tools..."
                       class="form-input pl-10 pr-4">
            </div>
        </form>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <?php if($q): ?>
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <p class="text-gray-500 text-sm">
            <strong><?php echo e($tools->total()); ?></strong> results for "<strong><?php echo e($q); ?></strong>"
        </p>
        <?php if($category): ?>
        <span class="badge badge-primary">Category: <?php echo e($category); ?> <a href="<?php echo e(route('search', ['q' => $q])); ?>" class="ml-1 hover:text-red-400">×</a></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="flex gap-8">
        <aside class="w-48 flex-shrink-0 hidden lg:block">
            <div class="card p-4">
                <h3 class="font-semibold text-sm text-gray-700 mb-3">Filter by Category</h3>
                <div class="space-y-1">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('search', ['q' => $q, 'category' => $cat->slug])); ?>"
                       class="block text-sm px-2 py-1.5 rounded-lg transition-colors <?php echo e($category === $cat->slug ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50'); ?>">
                        <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <?php if($tools->count() > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.tool-card', ['tool' => $tool], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-6"><?php echo e($tools->links()); ?></div>
            <?php else: ?>
            <div class="card p-16 text-center">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No results found</h3>
                <p class="text-gray-500 mb-4">Try different keywords or browse by category.</p>
                <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-primary">Browse All Tools</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\search.blade.php ENDPATH**/ ?>