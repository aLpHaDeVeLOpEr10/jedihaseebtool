
<?php $__env->startSection('title', 'About Us - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">About <?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?></h1>
        <p class="text-xl text-gray-500">Your all-in-one platform for free, fast, and reliable online tools.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-12">
        <div class="card p-6 text-center">
            <div class="text-4xl mb-4">🆓</div>
            <h3 class="font-bold text-gray-900 mb-2">100% Free</h3>
            <p class="text-gray-500 text-sm">Every tool on our platform is completely free. No subscriptions, no hidden fees.</p>
        </div>
        <div class="card p-6 text-center">
            <div class="text-4xl mb-4">⚡</div>
            <h3 class="font-bold text-gray-900 mb-2">Lightning Fast</h3>
            <p class="text-gray-500 text-sm">Tools load instantly and process results in milliseconds. No waiting around.</p>
        </div>
        <div class="card p-6 text-center">
            <div class="text-4xl mb-4">🔒</div>
            <h3 class="font-bold text-gray-900 mb-2">Privacy First</h3>
            <p class="text-gray-500 text-sm">Most tools run in your browser. Your data stays with you — we don't collect it.</p>
        </div>
    </div>

    <div class="card p-8 tool-prose">
        <h2>Our Mission</h2>
        <p><?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?> was built with a simple mission: to give everyone access to powerful, professional-grade tools without the cost or complexity of specialized software.</p>
        <p>Whether you're a student calculating BMI for a health project, a developer formatting JSON, a freelancer generating invoices, or just someone who needs to convert units quickly — we have you covered.</p>
        <h2>Our Tools</h2>
        <p>We offer hundreds of tools across dozens of categories including calculators, converters, generators, text tools, developer utilities, productivity tools, and much more. New tools are added regularly based on user requests and needs.</p>
        <h2>Contact Us</h2>
        <p>Have a suggestion for a new tool? Found a bug? We'd love to hear from you. <a href="<?php echo e(route('contact')); ?>">Get in touch →</a></p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\about.blade.php ENDPATH**/ ?>