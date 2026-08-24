<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['input']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['input']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-1">
    <label for="<?php echo e($input->field_name); ?>"
           class="block text-sm font-medium text-gray-700">
        <?php echo e($input->field_label); ?>

        <?php if($input->required): ?>
            <span class="text-red-500 ml-0.5">*</span>
        <?php endif; ?>
    </label>

    <?php if($input->field_type === 'textarea'): ?>
        <textarea
            id="<?php echo e($input->field_name); ?>"
            name="<?php echo e($input->field_name); ?>"
            x-model="inputs['<?php echo e($input->field_name); ?>']"
            placeholder="<?php echo e($input->placeholder); ?>"
            rows="4"
            <?php echo e($input->required ? 'required' : ''); ?>

            class="form-input resize-y"
        ><?php echo e($input->default_value); ?></textarea>

    <?php elseif($input->field_type === 'select'): ?>
        <select
            id="<?php echo e($input->field_name); ?>"
            name="<?php echo e($input->field_name); ?>"
            x-model="inputs['<?php echo e($input->field_name); ?>']"
            <?php echo e($input->required ? 'required' : ''); ?>

            class="form-input">
            <option value="">-- Select --</option>
            <?php $__currentLoopData = $input->options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($option['value'] ?? $option); ?>"
                    <?php echo e(($input->default_value === ($option['value'] ?? $option)) ? 'selected' : ''); ?>>
                    <?php echo e($option['label'] ?? $option); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

    <?php elseif($input->field_type === 'file'): ?>
        <input
            type="file"
            id="<?php echo e($input->field_name); ?>"
            name="<?php echo e($input->field_name); ?>"
            @change="inputs['<?php echo e($input->field_name); ?>'] = $event.target.files[0]"
            <?php echo e($input->required ? 'required' : ''); ?>

            class="form-input file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />

    <?php elseif($input->field_type === 'number'): ?>
        <input
            type="number"
            id="<?php echo e($input->field_name); ?>"
            name="<?php echo e($input->field_name); ?>"
            x-model="inputs['<?php echo e($input->field_name); ?>']"
            placeholder="<?php echo e($input->placeholder); ?>"
            value="<?php echo e($input->default_value); ?>"
            <?php echo e($input->required ? 'required' : ''); ?>

            class="form-input" />

    <?php elseif($input->field_type === 'checkbox'): ?>
        <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="checkbox"
                id="<?php echo e($input->field_name); ?>"
                name="<?php echo e($input->field_name); ?>"
                x-model="inputs['<?php echo e($input->field_name); ?>']"
                <?php echo e($input->default_value ? 'checked' : ''); ?>

                class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
            <span class="text-sm text-gray-600"><?php echo e($input->placeholder ?: $input->field_label); ?></span>
        </label>

    <?php else: ?>
        <input
            type="text"
            id="<?php echo e($input->field_name); ?>"
            name="<?php echo e($input->field_name); ?>"
            x-model="inputs['<?php echo e($input->field_name); ?>']"
            placeholder="<?php echo e($input->placeholder); ?>"
            value="<?php echo e($input->default_value); ?>"
            <?php echo e($input->required ? 'required' : ''); ?>

            class="form-input" />
    <?php endif; ?>

    <?php if($input->help_text): ?>
        <p class="text-xs text-gray-500 mt-1"><?php echo e($input->help_text); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\components\tool-input.blade.php ENDPATH**/ ?>