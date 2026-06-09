
<?php $__env->startSection('title', 'Privacy Policy - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
    <div class="card p-8 tool-prose">
        <p><strong>Last updated:</strong> <?php echo e(date('F j, Y')); ?></p>
        <h2>Information We Collect</h2>
        <p><?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?> collects minimal information. Most tools process data entirely in your browser and never send it to our servers. When you use our contact form, we collect your name, email, and message to respond to your inquiry.</p>
        <h2>How We Use Information</h2>
        <p>Any information collected is used solely to provide and improve our services, respond to contact inquiries, and analyze aggregate usage patterns (never individual users).</p>
        <h2>Cookies</h2>
        <p>We use only essential cookies required for the website to function properly. We do not use tracking or advertising cookies.</p>
        <h2>Third-Party Services</h2>
        <p>We may use Google Analytics to understand aggregate website traffic. We use QR code generation APIs for the QR Code tool, which may process the text you enter.</p>
        <h2>Data Security</h2>
        <p>We implement appropriate technical measures to protect any data processed through our services.</p>
        <h2>Contact</h2>
        <p>For privacy questions, contact us at <a href="mailto:<?php echo e(\App\Models\Setting::get('contact_email', 'privacy@jedisebitool.com')); ?>"><?php echo e(\App\Models\Setting::get('contact_email', 'privacy@jedisebitool.com')); ?></a>.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\privacy.blade.php ENDPATH**/ ?>