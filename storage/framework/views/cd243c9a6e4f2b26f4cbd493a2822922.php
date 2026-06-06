<footer class="bg-gray-900 text-gray-300 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="md:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg hero-gradient flex items-center justify-center">
                        <span class="text-white font-bold text-sm">J</span>
                    </div>
                    <span class="font-bold text-white"><?php echo e(\App\Models\Setting::get('site_name', config('app.name'))); ?></span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    <?php echo e(\App\Models\Setting::get('site_description', 'Your all-in-one platform for free online tools.')); ?>

                </p>
            </div>

            
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo e(route('tools.index')); ?>" class="hover:text-white transition-colors">All Tools</a></li>
                    <li><a href="<?php echo e(route('categories.index')); ?>" class="hover:text-white transition-colors">Categories</a></li>
                    <li><a href="<?php echo e(route('search')); ?>" class="hover:text-white transition-colors">Search</a></li>
                    <li><a href="<?php echo e(route('about')); ?>" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-white transition-colors">Contact</a></li>
                </ul>
            </div>

            
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Popular Categories</h3>
                <ul class="space-y-2 text-sm">
                    <?php $__currentLoopData = \App\Models\Category::active()->ordered()->limit(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route('categories.show', $cat)); ?>" class="hover:text-white transition-colors"><?php echo e($cat->icon); ?> <?php echo e($cat->name); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo e(route('privacy')); ?>" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="<?php echo e(route('terms')); ?>" class="hover:text-white transition-colors">Terms of Use</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                <?php echo e(\App\Models\Setting::get('footer_text', '© ' . date('Y') . ' ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL') . '. All rights reserved.')); ?>

            </p>
            <p class="text-xs text-gray-600">Built with ❤️ for everyone</p>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/partials/footer.blade.php ENDPATH**/ ?>