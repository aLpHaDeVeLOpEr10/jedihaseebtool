<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — <?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2.5 font-bold text-gray-900 text-xl hover:text-brand-600 transition-colors">
                <div class="w-10 h-10 rounded-xl hero-gradient flex items-center justify-center">
                    <span class="text-white font-bold">J</span>
                </div>
                <?php echo e(\App\Models\Setting::get('site_name', 'JEDISEBITOOL')); ?>

            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-6 mb-1">Admin Login</h1>
            <p class="text-gray-500 text-sm">Sign in to manage your tools and settings</p>
        </div>

        <div class="card p-8">
            <?php if($errors->any()): ?>
            <div class="alert alert-error mb-5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                           class="form-input" required autofocus
                           placeholder="admin@jedisebitool.com">
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-input" required
                           placeholder="••••••••">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="rounded text-brand-600">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    Sign In to Dashboard
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            <a href="<?php echo e(route('home')); ?>" class="text-brand-600 hover:underline">← Back to website</a>
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\jedisebitool\resources\views/auth/login.blade.php ENDPATH**/ ?>