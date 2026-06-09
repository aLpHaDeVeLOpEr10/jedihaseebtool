

<?php $__env->startSection('title', \App\Models\Setting::get('site_name', 'JEDISEBITOOL') . ' - Free Online Tools'); ?>
<?php $__env->startSection('description', \App\Models\Setting::get('seo_default_description', 'Free online tools for calculations, conversions, generators and more.')); ?>

<?php $__env->startSection('structured_data'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "<?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>",
    "url": "<?php echo e(url('/')); ?>",
    "description": "<?php echo e(\App\Models\Setting::get('seo_default_description', '')); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo e(url('/search')); ?>?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<section class="hero-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 md:py-28 text-center">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-sm font-medium mb-6">
            ⚡ <?php echo e($totalTools); ?>+ Free Tools Available
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
            <?php echo e(\App\Models\Setting::get('home_hero_title', 'Your All-in-One')); ?><br>
            <span class="text-yellow-300">Online Toolbox</span>
        </h1>
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-10">
            <?php echo e(\App\Models\Setting::get('home_hero_subtitle', 'Calculators, converters, generators, and productivity tools — all free, no signup required.')); ?>

        </p>

        
        <form action="<?php echo e(route('search')); ?>" method="GET" class="max-w-xl mx-auto">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" placeholder="Search tools... e.g. BMI calculator, QR code"
                    class="w-full pl-12 pr-32 py-4 rounded-2xl text-gray-900 bg-white shadow-xl text-sm focus:outline-none focus:ring-2 focus:ring-white/50">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-primary">
                    Search
                </button>
            </div>
        </form>

        
        <div class="flex flex-wrap justify-center gap-2 mt-8">
            <?php $__currentLoopData = $categories->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('categories.show', $cat)); ?>"
               class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-sm font-medium transition-all hover:scale-105">
                <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl font-bold text-brand-600"><?php echo e(number_format($totalTools)); ?>+</div>
                <div class="text-sm text-gray-500 mt-0.5">Free Tools</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-brand-600"><?php echo e(number_format($totalCategories)); ?></div>
                <div class="text-sm text-gray-500 mt-0.5">Categories</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-brand-600">100%</div>
                <div class="text-sm text-gray-500 mt-0.5">Free Forever</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-brand-600">0</div>
                <div class="text-sm text-gray-500 mt-0.5">Signup Required</div>
            </div>
        </div>
    </div>
</section>


<?php if($featuredTools->count() > 0): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="section-title">⭐ Featured Tools</h2>
                <p class="section-subtitle">Hand-picked tools our users love most</p>
            </div>
            <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-outline hidden sm:flex">View All →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php $__currentLoopData = $featuredTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('components.tool-card', ['tool' => $tool], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-6 text-center sm:hidden">
            <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-outline">View All Tools →</a>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle"><?php echo e($totalCategories); ?> categories of tools for every need</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('categories.show', $category)); ?>"
               class="card-hover p-5 text-center group flex flex-col items-center gap-3">
                <div class="text-3xl group-hover:scale-110 transition-transform duration-200">
                    <?php echo e($category->icon); ?>

                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm leading-tight"><?php echo e($category->name); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5"><?php echo e($category->active_tools_count ?? 0); ?> tools</p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<?php if($recentTools->count() > 0): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="section-title">🆕 Recently Added</h2>
                <p class="section-subtitle">The newest tools on our platform</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $recentTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('components.tool-card', ['tool' => $tool], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <div class="text-5xl mb-4">🚀</div>
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to get started?</h2>
        <p class="text-gray-500 text-lg mb-8">Browse our complete library of free online tools — no account needed.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('tools.index')); ?>" class="btn btn-primary btn-lg">
                Browse All Tools
            </a>
            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline btn-lg">
                View Categories
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\public\home.blade.php ENDPATH**/ ?>