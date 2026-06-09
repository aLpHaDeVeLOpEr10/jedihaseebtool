
<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('content'); ?>
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500"><?php echo e($unreadCount); ?> unread message<?php echo e($unreadCount !== 1 ? 's' : ''); ?></p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">From</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 <?php echo e(!$contact->is_read ? 'font-medium' : ''); ?>">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <?php if(!$contact->is_read): ?>
                            <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0"></span>
                            <?php endif; ?>
                            <div>
                                <p class="text-gray-900"><?php echo e($contact->name); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($contact->email); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($contact->subject ?: 'No subject'); ?></td>
                    <td class="px-4 py-3 text-gray-400 text-xs"><?php echo e($contact->created_at->diffForHumans()); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.contacts.show', $contact)); ?>" class="btn btn-primary btn-sm">View</a>
                            <form action="<?php echo e(route('admin.contacts.destroy', $contact)); ?>" method="POST"
                                  onsubmit="return confirm('Delete this message?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center py-12 text-gray-400">No messages yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100"><?php echo e($contacts->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\admin\contacts\index.blade.php ENDPATH**/ ?>