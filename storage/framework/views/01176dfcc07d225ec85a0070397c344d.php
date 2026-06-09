
<?php $__env->startSection('title', 'Terms of Use - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms of Use</h1>
    <div class="card p-8 tool-prose">
        <p><strong>Last updated:</strong> <?php echo e(date('F j, Y')); ?></p>
        <h2>Acceptance of Terms</h2>
        <p>By using <?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>, you agree to these terms. If you don't agree, please don't use our services.</p>
        <h2>Use of Tools</h2>
        <p>Our tools are provided for lawful purposes only. You may not use them to violate laws, infringe rights, or cause harm. Tool results are for informational purposes only and should not replace professional advice.</p>
        <h2>Disclaimer of Warranties</h2>
        <p>Our tools are provided "as is" without warranties of any kind. While we strive for accuracy, we cannot guarantee that results are error-free. Always verify important calculations independently.</p>
        <h2>Limitation of Liability</h2>
        <p><?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?> shall not be liable for any damages arising from the use of our tools or reliance on tool results.</p>
        <h2>Changes to Terms</h2>
        <p>We may update these terms at any time. Continued use after changes constitutes acceptance of the updated terms.</p>
        <h2>Contact</h2>
        <p>Questions about these terms? <a href="<?php echo e(route('contact')); ?>">Contact us →</a></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\terms.blade.php ENDPATH**/ ?>