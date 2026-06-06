<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <title><?php echo $__env->yieldContent('title', config('app.name', 'JEDISEBITOOL')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', \App\Models\Setting::get('seo_default_description', 'Free online tools for everyone.')); ?>">
    <?php echo $__env->yieldContent('seo_keywords_meta'); ?>

    
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical', url()->current()); ?>">

    
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', config('app.name')); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', ''); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo e(\App\Models\Setting::get('site_name', config('app.name'))); ?>">
    <?php echo $__env->yieldContent('og_image_meta'); ?>

    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title', config('app.name')); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('description', ''); ?>">

    
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <?php echo $__env->yieldContent('head'); ?>

    
    <?php echo $__env->yieldContent('structured_data'); ?>

    
    <?php if(\App\Models\Setting::get('google_analytics')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(\App\Models\Setting::get('google_analytics')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo e(\App\Models\Setting::get('google_analytics')); ?>');
    </script>
    <?php endif; ?>
</head>
<body class="bg-white text-gray-900 font-sans antialiased">

    
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(session('success')): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
        <div class="alert-success alert flex items-center gap-2" data-auto-dismiss="4000">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    </div>
    <?php endif; ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/layouts/public.blade.php ENDPATH**/ ?>