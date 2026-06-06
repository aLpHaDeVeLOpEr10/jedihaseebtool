<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — <?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?> Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

    
    <aside class="admin-sidebar transition-transform lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg hero-gradient flex items-center justify-center">
                <span class="text-white font-bold text-sm">J</span>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm"><?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?></p>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
        </div>

        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Main</p>

            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="<?php echo e(request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'nav-item-inactive'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Dashboard
            </a>

            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4">Content</p>

            <a href="<?php echo e(route('admin.tools.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.tools*') ? 'nav-item-active' : 'nav-item-inactive'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Tools
                <span class="ml-auto badge badge-primary text-xs"><?php echo e(\App\Models\Tool::count()); ?></span>
            </a>

            <a href="<?php echo e(route('admin.categories.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.categories*') ? 'nav-item-active' : 'nav-item-inactive'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Categories
            </a>

            <a href="<?php echo e(route('admin.contacts.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.contacts*') ? 'nav-item-active' : 'nav-item-inactive'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contacts
                <?php $unread = \App\Models\Contact::where('is_read', false)->count(); ?>
                <?php if($unread > 0): ?>
                <span class="ml-auto badge badge-danger text-xs"><?php echo e($unread); ?></span>
                <?php endif; ?>
            </a>

            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4">System</p>

            <a href="<?php echo e(route('admin.settings.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.settings*') ? 'nav-item-active' : 'nav-item-inactive'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Settings
            </a>

            <a href="<?php echo e(url('/')); ?>" target="_blank"
               class="nav-item-inactive">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Site
            </a>
        </nav>

        
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-semibold text-sm">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e(auth()->user()->email); ?></p>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-20 lg:hidden" x-cloak></div>

    
    <div class="admin-content">
        
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-4 px-6 h-16">
                
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                
                <div class="flex-1">
                    <h1 class="text-lg font-semibold text-gray-900"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                </div>

                
                <div class="flex items-center gap-3">
                    <?php echo $__env->yieldContent('header_actions'); ?>
                    <a href="<?php echo e(route('admin.tools.create')); ?>" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Tool
                    </a>
                </div>
            </div>
        </header>

        
        <div class="px-6 pt-4">
            <?php if(session('success')): ?>
            <div class="alert-success alert mb-4 flex items-center gap-2" data-auto-dismiss="4000">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert-error alert mb-4" data-auto-dismiss="5000"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="alert-error alert mb-4">
                <ul class="list-disc list-inside space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        
        <main class="p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/layouts/admin.blade.php ENDPATH**/ ?>