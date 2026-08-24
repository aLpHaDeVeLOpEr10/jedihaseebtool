
<?php $__env->startSection('title', 'Contact Us - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Contact Us</h1>
        <p class="text-gray-500">Have a question, suggestion, or found a bug? We'd love to hear from you.</p>
    </div>

    <?php if(session('success')): ?>
    <div class="alert-success alert mb-6"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card p-8">
        <form action="<?php echo e(route('contact.submit')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-input" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-input" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div>
                <label class="form-label">Subject</label>
                <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" class="form-input">
            </div>
            <div>
                <label class="form-label">Message *</label>
                <textarea name="message" rows="6" class="form-input" required><?php echo e(old('message')); ?></textarea>
                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn btn-primary w-full btn-lg">Send Message</button>
        </form>
    </div>

    <div class="mt-8 grid sm:grid-cols-2 gap-4">
        <div class="card p-5 text-center">
            <div class="text-2xl mb-2">📧</div>
            <p class="font-semibold text-gray-800 text-sm">Email</p>
            <a href="mailto:<?php echo e(\App\Models\Setting::get('contact_email', 'hello@jedisebitool.com')); ?>" class="text-brand-600 text-sm hover:underline">
                <?php echo e(\App\Models\Setting::get('contact_email', 'hello@jedisebitool.com')); ?>

            </a>
        </div>
        <div class="card p-5 text-center">
            <div class="text-2xl mb-2">⏱️</div>
            <p class="font-semibold text-gray-800 text-sm">Response Time</p>
            <p class="text-gray-500 text-sm">Usually within 24-48 hours</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\contact.blade.php ENDPATH**/ ?>