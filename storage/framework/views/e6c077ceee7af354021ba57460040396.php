<?php $__env->startSection('title', 'All Tools - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('description', 'Browse our complete library of free online tools — calculators, converters, generators and more.'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-900">All Tools</h1>
        <p class="text-gray-500 mt-2"><?php echo e($tools->total()); ?> tools available — all free, no account needed</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        
        <aside class="lg:w-64 flex-shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="lg:hidden w-full btn btn-outline mb-4 flex items-center justify-between">
                <span>Filters</span>
                <svg class="w-4 h-4" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="hidden lg:block space-y-6" :class="open ? '!block' : ''">
                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Category</h3>
                    <div class="space-y-1.5">
                        <a href="<?php echo e(route('tools.index', request()->except('category'))); ?>"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors <?php echo e(!request('category') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50'); ?>">
                            <span>All Categories</span>
                            <span class="text-xs text-gray-400"><?php echo e($tools->total()); ?></span>
                        </a>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.index', array_merge(request()->all(), ['category' => $cat->slug]))); ?>"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors <?php echo e(request('category') === $cat->slug ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50'); ?>">
                            <span class="flex items-center gap-2">
                                <span><?php echo e($cat->icon); ?></span>
                                <span><?php echo e($cat->name); ?></span>
                            </span>
                            <span class="text-xs text-gray-400"><?php echo e($cat->active_tools_count); ?></span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Tool Type</h3>
                    <div class="space-y-1.5">
                        <a href="<?php echo e(route('tools.index', request()->except('type'))); ?>"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors <?php echo e(!request('type') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50'); ?>">
                            All Types
                        </a>
                        <?php $__currentLoopData = $toolTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.index', array_merge(request()->all(), ['type' => $type]))); ?>"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors capitalize <?php echo e(request('type') === $type ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50'); ?>">
                            <?php echo e(ucfirst($type)); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </aside>

        
        <div class="flex-1">
            
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    Showing <strong><?php echo e($tools->firstItem()); ?>–<?php echo e($tools->lastItem()); ?></strong> of <strong><?php echo e($tools->total()); ?></strong> tools
                </p>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Sort:</span>
                    <select onchange="window.location = this.value" class="form-input py-1.5 text-sm w-auto">
                        <option value="<?php echo e(route('tools.index', array_merge(request()->all(), ['sort' => 'default']))); ?>" <?php echo e(request('sort', 'default') === 'default' ? 'selected' : ''); ?>>Default</option>
                        <option value="<?php echo e(route('tools.index', array_merge(request()->all(), ['sort' => 'popular']))); ?>" <?php echo e(request('sort') === 'popular' ? 'selected' : ''); ?>>Most Popular</option>
                        <option value="<?php echo e(route('tools.index', array_merge(request()->all(), ['sort' => 'newest']))); ?>" <?php echo e(request('sort') === 'newest' ? 'selected' : ''); ?>>Newest</option>
                        <option value="<?php echo e(route('tools.index', array_merge(request()->all(), ['sort' => 'name']))); ?>" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>A–Z</option>
                    </select>
                </div>
            </div>

            
            <?php if($tools->count() > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.tool-card', ['tool' => $tool], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="mt-8">
                <?php echo e($tools->links()); ?>

            </div>
            <?php else: ?>
            <div class="card p-16 text-center">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No tools found</h3>
                <p class="text-gray-500">Try adjusting your filters or browse all categories.</p>
                <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-primary mt-4">Clear Filters</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/public/tools/index.blade.php ENDPATH**/ ?>