
<?php $__env->startSection('title', 'Create Category'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
<form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Category Details</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Name *</label>
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
                <label class="form-label">Slug</label>
                <input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="form-input font-mono" placeholder="auto-generated">
            </div>
        </div>
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input"><?php echo e(old('description')); ?></textarea>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Icon (emoji)</label>
                <input type="text" name="icon" value="<?php echo e(old('icon', '🔧')); ?>" class="form-input text-2xl text-center" maxlength="5">
            </div>
            <div>
                <label class="form-label">Color</label>
                <input type="color" name="color" value="<?php echo e(old('color', '#6366f1')); ?>" class="form-input h-10">
            </div>
            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo e(old('sort_order', 0)); ?>" class="form-input">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded text-brand-600" checked <?php echo e(old('is_active') === '0' ? '' : 'checked'); ?>>
            <label for="is_active" class="text-sm text-gray-700">Active (visible on website)</label>
        </div>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">SEO</h2>
        <div>
            <label class="form-label">SEO Title</label>
            <input type="text" name="seo_title" value="<?php echo e(old('seo_title')); ?>" class="form-input" placeholder="Auto-generated if blank">
        </div>
        <div>
            <label class="form-label">Meta Description</label>
            <textarea name="seo_description" rows="2" class="form-input"><?php echo e(old('seo_description')); ?></textarea>
        </div>
        <div>
            <label class="form-label">Keywords</label>
            <input type="text" name="seo_keywords" value="<?php echo e(old('seo_keywords')); ?>" class="form-input" placeholder="keyword1, keyword2">
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary">Create Category</button>
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/admin/categories/create.blade.php ENDPATH**/ ?>