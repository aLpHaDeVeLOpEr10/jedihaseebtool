
<?php $__env->startSection('title', 'Manage Tools'); ?>

<?php $__env->startSection('header_actions'); ?>
<a href="<?php echo e(route('admin.tools.create')); ?>" class="btn btn-primary btn-sm">+ New Tool</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card p-4 mb-6">
    <form action="<?php echo e(route('admin.tools.index')); ?>" method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Tool name..." class="form-input">
        </div>
        <div class="w-44">
            <label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Type</label>
            <select name="type" class="form-input">
                <option value="">All Types</option>
                <?php $__currentLoopData = $toolTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" <?php echo e(request('type') === $type ? 'selected' : ''); ?>><?php echo e(ucfirst($type)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?php echo e(route('admin.tools.index')); ?>" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>


<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tool</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors <?php echo e($tool->trashed() ? 'opacity-50' : ''); ?>">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-base flex-shrink-0"
                                 style="background: <?php echo e($tool->color); ?>22">
                                <?php echo e($tool->icon); ?>

                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($tool->name); ?></p>
                                <p class="text-xs text-gray-400 font-mono"><?php echo e($tool->slug); ?></p>
                            </div>
                            <?php if($tool->is_featured): ?>
                            <span class="text-yellow-400 text-xs">★</span>
                            <?php endif; ?>
                            <?php if($tool->has_custom_blade): ?>
                            <span class="badge badge-primary text-xs">custom</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($tool->category->name ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3">
                        <span class="badge badge-gray capitalize"><?php echo e($tool->tool_type); ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge <?php echo e(match($tool->status) { 'active' => 'badge-success', 'inactive' => 'badge-gray', 'draft' => 'badge-warning', default => 'badge-gray' }); ?>">
                            <?php echo e($tool->status); ?>

                        </span>
                        <?php if($tool->trashed()): ?>
                        <span class="badge badge-danger ml-1">deleted</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600">
                        <?php echo e(number_format($tool->view_count)); ?>

                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <?php if($tool->trashed()): ?>
                            <form action="<?php echo e(route('admin.tools.restore', $tool->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-sm">Restore</button>
                            </form>
                            <?php else: ?>
                            <a href="<?php echo e(route('tools.show', $tool->slug)); ?>" target="_blank"
                               class="btn btn-secondary btn-sm">View</a>
                            <a href="<?php echo e(route('admin.tools.edit', $tool)); ?>" class="btn btn-primary btn-sm">Edit</a>
                            <form action="<?php echo e(route('admin.tools.toggle-status', $tool)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <?php echo e($tool->status === 'active' ? 'Disable' : 'Enable'); ?>

                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.tools.destroy', $tool)); ?>" method="POST"
                                  onsubmit="return confirm('Delete this tool?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        No tools found. <a href="<?php echo e(route('admin.tools.create')); ?>" class="text-brand-600 hover:underline">Create your first tool →</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        <?php echo e($tools->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\admin\tools\index.blade.php ENDPATH**/ ?>