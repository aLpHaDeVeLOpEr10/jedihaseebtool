<?php $__env->startSection('title', 'Manage Categories'); ?>

<?php $__env->startSection('header_actions'); ?>
<a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary btn-sm">+ New Category</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <form action="<?php echo e(route('admin.categories.index')); ?>" method="GET" class="flex gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                   placeholder="Search categories..." class="form-input flex-1">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tools</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl"><?php echo e($cat->icon); ?></span>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($cat->name); ?></p>
                                <?php if($cat->description): ?>
                                <p class="text-xs text-gray-400 truncate max-w-xs"><?php echo e($cat->description); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500"><?php echo e($cat->slug); ?></td>
                    <td class="px-4 py-3 text-center">
                        <a href="<?php echo e(route('admin.tools.index', ['category' => $cat->id])); ?>"
                           class="badge badge-primary hover:bg-brand-200 transition-colors">
                            <?php echo e($cat->tools_count); ?>

                        </a>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="badge <?php echo e($cat->is_active ? 'badge-success' : 'badge-gray'); ?>">
                            <?php echo e($cat->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-gray-500"><?php echo e($cat->sort_order); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('categories.show', $cat)); ?>" target="_blank"
                               class="btn btn-secondary btn-sm">View</a>
                            <a href="<?php echo e(route('admin.categories.edit', $cat)); ?>"
                               class="btn btn-primary btn-sm">Edit</a>
                            <form action="<?php echo e(route('admin.categories.toggle-status', $cat)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <?php echo e($cat->is_active ? 'Hide' : 'Show'); ?>

                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.categories.destroy', $cat)); ?>" method="POST"
                                  onsubmit="return confirm('Delete this category? Tools must be removed first.')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        No categories yet. <a href="<?php echo e(route('admin.categories.create')); ?>" class="text-brand-600">Create one →</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        <?php echo e($categories->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>