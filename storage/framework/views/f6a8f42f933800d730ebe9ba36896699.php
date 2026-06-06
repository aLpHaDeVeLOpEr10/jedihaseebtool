<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Tools</p>
            <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?php echo e(number_format($stats['total_tools'])); ?></p>
        <p class="text-xs text-emerald-600 mt-1"><?php echo e($stats['active_tools']); ?> active</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Categories</p>
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_categories']); ?></p>
        <p class="text-xs text-gray-400 mt-1">tool categories</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Views</p>
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?php echo e(number_format($stats['total_views'])); ?></p>
        <p class="text-xs text-gray-400 mt-1">page views</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Unread Messages</p>
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['unread_contacts']); ?></p>
        <p class="text-xs text-gray-400 mt-1"><a href="<?php echo e(route('admin.contacts.index')); ?>" class="text-brand-600 hover:underline">view messages</a></p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 card overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Recent Tools</h2>
            <a href="<?php echo e(route('admin.tools.index')); ?>" class="text-sm text-brand-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $recentTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                     style="background: <?php echo e($tool->color); ?>22">
                    <?php echo e($tool->icon); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm truncate"><?php echo e($tool->name); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($tool->category->name); ?> · <?php echo e($tool->created_at->diffForHumans()); ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge <?php echo e($tool->status === 'active' ? 'badge-success' : 'badge-gray'); ?> text-xs">
                        <?php echo e($tool->status); ?>

                    </span>
                    <a href="<?php echo e(route('admin.tools.edit', $tool)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="space-y-6">
        
        <div class="card p-5">
            <h2 class="font-semibold text-gray-900 mb-4 text-sm">🔥 Most Viewed</h2>
            <div class="space-y-3">
                <?php $__currentLoopData = $topTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3">
                    <span class="text-lg"><?php echo e($tool->icon); ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($tool->name); ?></p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                <div class="bg-brand-500 h-1.5 rounded-full"
                                     style="width: <?php echo e($topTools->max('view_count') > 0 ? ($tool->view_count / $topTools->max('view_count')) * 100 : 0); ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-400"><?php echo e(number_format($tool->view_count)); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card p-5">
            <h2 class="font-semibold text-gray-900 mb-4 text-sm">Tools by Type</h2>
            <div class="space-y-2">
                <?php $__currentLoopData = $toolsByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 capitalize"><?php echo e($typeRow->tool_type); ?></span>
                    <span class="font-medium text-gray-900"><?php echo e($typeRow->count); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card p-5">
            <h2 class="font-semibold text-gray-900 mb-4 text-sm">Quick Actions</h2>
            <div class="space-y-2">
                <a href="<?php echo e(route('admin.tools.create')); ?>" class="btn btn-primary w-full btn-sm">+ Add New Tool</a>
                <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-secondary w-full btn-sm">+ Add Category</a>
                <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary w-full btn-sm">⚙ Settings</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>