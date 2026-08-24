<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <title><?php echo $__env->yieldContent('title', config('app.name', 'JEDISEBITOOL')); ?></title>
    <?php
        // Meta description: child section > tool model > global default
        $_seoDesc = $__env->hasSection('description')
            ? $__env->yieldContent('description')
            : ($__env->hasSection('meta_description')
                ? $__env->yieldContent('meta_description')
                : (isset($tool) && $tool->getRawOriginal('seo_description')
                    ? $tool->seo_description  // accessor adds short_description fallback
                    : \App\Models\Setting::get('seo_default_description', 'Free online tools for everyone.')));

        // Open Graph
        $_ogTitle = $__env->hasSection('og_title')
            ? $__env->yieldContent('og_title')
            : (isset($tool) && $tool->getRawOriginal('og_title')
                ? $tool->getRawOriginal('og_title')
                : $__env->yieldContent('title', config('app.name')));

        $_ogDesc = $__env->hasSection('og_description')
            ? $__env->yieldContent('og_description')
            : (isset($tool) && $tool->getRawOriginal('og_description')
                ? $tool->getRawOriginal('og_description')
                : $_seoDesc);

        $_ogImage = $__env->hasSection('og_image')
            ? $__env->yieldContent('og_image')
            : (isset($tool) && $tool->getRawOriginal('og_image') ? $tool->getRawOriginal('og_image') : '');

        // Twitter Card
        $_twTitle = isset($tool) && $tool->getRawOriginal('twitter_title')
            ? $tool->getRawOriginal('twitter_title')
            : $_ogTitle;

        $_twDesc = isset($tool) && $tool->getRawOriginal('twitter_description')
            ? $tool->getRawOriginal('twitter_description')
            : $_seoDesc;

        // Canonical URL
        $_canonical = $__env->hasSection('canonical')
            ? $__env->yieldContent('canonical')
            : (isset($tool) && $tool->getRawOriginal('canonical_url')
                ? $tool->getRawOriginal('canonical_url')
                : url()->current());

        // Robots
        $_robots = isset($tool) ? $tool->robots_meta : 'index, follow';

        // SEO keywords
        $_keywords = isset($tool) && $tool->getRawOriginal('seo_keywords')
            ? $tool->getRawOriginal('seo_keywords')
            : '';
    ?>
    <meta name="description" content="<?php echo e($_seoDesc); ?>">
    <?php if($_keywords): ?>
    <meta name="keywords" content="<?php echo e($_keywords); ?>">
    <?php endif; ?>
    <meta name="robots" content="<?php echo e($_robots); ?>">

    
    <link rel="canonical" href="<?php echo e($_canonical); ?>">

    
    <meta property="og:title" content="<?php echo e($_ogTitle); ?>">
    <meta property="og:description" content="<?php echo e($_ogDesc); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo e(\App\Models\Setting::get('site_name', config('app.name'))); ?>">
    <?php if($_ogImage): ?>
    <meta property="og:image" content="<?php echo e($_ogImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php endif; ?>
    <?php echo $__env->yieldContent('og_image_meta'); ?>

    
    <meta name="twitter:card" content="<?php echo e($_ogImage ? 'summary_large_image' : 'summary'); ?>">
    <meta name="twitter:title" content="<?php echo e($_twTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($_twDesc); ?>">
    <?php if($_ogImage): ?>
    <meta name="twitter:image" content="<?php echo e($_ogImage); ?>">
    <?php endif; ?>

    
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <?php echo $__env->yieldContent('head'); ?>

    
    <?php if($__env->hasSection('structured_data')): ?>
        <?php echo $__env->yieldContent('structured_data'); ?>
    <?php elseif(isset($tool) && $tool->getRawOriginal('schema_markup')): ?>
        <script type="application/ld+json"><?php echo $tool->getRawOriginal('schema_markup'); ?></script>
    <?php endif; ?>

    
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

        
        <?php if (! ($__env->hasSection('renders_own_content_sections'))): ?>
            <?php if(isset($tool) && $tool->contents->where('is_visible', true)->isNotEmpty()): ?>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-8 space-y-6">
                <?php $__currentLoopData = $tool->contents->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-6">
                    <?php if($section->title): ?>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e($section->title); ?></h2>
                    <?php endif; ?>
                    <div class="tool-prose"><?php echo nl2br(e($section->content)); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if (! ($__env->hasSection('renders_own_faqs'))): ?>
            <?php if(isset($tool) && $tool->faqs->where('is_visible', true)->isNotEmpty()): ?>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-10">
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
                    <div class="space-y-3" x-data="{ open: null }">
                        <?php $__currentLoopData = $tool->faqs->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <button @click="open = open === <?php echo e($fi); ?> ? null : <?php echo e($fi); ?>"
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <span class="font-medium text-gray-800 text-sm"><?php echo e($faq->question); ?></span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                                     :class="open === <?php echo e($fi); ?> ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open === <?php echo e($fi); ?>" x-cloak
                                 class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">
                                <?php echo e($faq->answer); ?>

                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/layouts/public.blade.php ENDPATH**/ ?>