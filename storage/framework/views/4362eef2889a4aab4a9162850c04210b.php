
<?php $__env->startSection('title', 'Message from ' . $contact->name); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="card p-6 mb-4">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h2 class="font-semibold text-gray-900 text-lg"><?php echo e($contact->subject ?: 'No subject'); ?></h2>
                <p class="text-sm text-gray-500 mt-1">From: <strong><?php echo e($contact->name); ?></strong> &lt;<?php echo e($contact->email); ?>&gt;</p>
                <p class="text-xs text-gray-400 mt-0.5"><?php echo e($contact->created_at->format('D, M j, Y \a\t g:ia')); ?></p>
            </div>
            <div class="flex gap-2">
                <a href="mailto:<?php echo e($contact->email); ?>" class="btn btn-primary btn-sm">Reply via Email</a>
                <form action="<?php echo e(route('admin.contacts.destroy', $contact)); ?>" method="POST"
                      onsubmit="return confirm('Delete?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-5 text-gray-700 leading-relaxed whitespace-pre-wrap"><?php echo e($contact->message); ?></div>
    </div>
    <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-secondary">← Back to Messages</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\admin\contacts\show.blade.php ENDPATH**/ ?>