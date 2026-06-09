
<?php $__env->startSection('title', 'Edit: ' . $tool->name); ?>

<?php $__env->startSection('header_actions'); ?>
<div class="flex gap-2">
    <a href="<?php echo e(route('tools.show', $tool->slug)); ?>" target="_blank" class="btn btn-secondary btn-sm">👁 View</a>
    <form action="<?php echo e(route('admin.tools.toggle-featured', $tool)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-secondary btn-sm">
            <?php echo e($tool->is_featured ? '★ Unfeature' : '☆ Feature'); ?>

        </button>
    </form>
    <form action="<?php echo e(route('admin.tools.toggle-status', $tool)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn <?php echo e($tool->status === 'active' ? 'btn-secondary' : 'btn-success'); ?> btn-sm">
            <?php echo e($tool->status === 'active' ? 'Deactivate' : 'Activate'); ?>

        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ activeTab: 'basic' }">
    
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        <?php $__currentLoopData = [['basic', 'Basic Info'], ['seo', 'SEO'], ['inputs', 'Inputs & FAQs'], ['blade', 'Blade Template']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$tab, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button type="button" @click="activeTab = '<?php echo e($tab); ?>'"
                :class="activeTab === '<?php echo e($tab); ?>' ? 'border-brand-500 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors">
            <?php echo e($label); ?>

        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form action="<?php echo e(route('admin.tools.update', $tool)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        
        <div x-show="activeTab === 'basic'" class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="font-semibold text-gray-900 mb-5">Basic Information</h2>
                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Tool Name *</label>
                                <input type="text" name="name" value="<?php echo e(old('name', $tool->name)); ?>"
                                       class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Slug *</label>
                                <input type="text" name="slug" value="<?php echo e(old('slug', $tool->slug)); ?>"
                                       class="form-input font-mono">
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-input" required>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $tool->category_id) == $cat->id ? 'selected' : ''); ?>>
                                        <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Tool Type *</label>
                                <select name="tool_type" class="form-input" required>
                                    <?php $__currentLoopData = $toolTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type); ?>" <?php echo e(old('tool_type', $tool->tool_type) === $type ? 'selected' : ''); ?>>
                                        <?php echo e(ucfirst($type)); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description"
                                   value="<?php echo e(old('short_description', $tool->short_description)); ?>"
                                   class="form-input" maxlength="500">
                        </div>
                        <div>
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" rows="6" class="form-input"><?php echo e(old('long_description', $tool->long_description)); ?></textarea>
                        </div>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Icon (emoji)</label>
                                <input type="text" name="icon" value="<?php echo e(old('icon', $tool->icon)); ?>"
                                       class="form-input text-xl text-center" maxlength="5">
                            </div>
                            <div>
                                <label class="form-label">Brand Color</label>
                                <input type="color" name="color" value="<?php echo e(old('color', $tool->color)); ?>"
                                       class="form-input h-10">
                            </div>
                            <div>
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="<?php echo e(old('sort_order', $tool->sort_order)); ?>"
                                       class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card p-6">
                    <h2 class="font-semibold text-gray-900 mb-5">Engine Configuration</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Engine Class</label>
                            <input type="text" name="engine_class"
                                   value="<?php echo e(old('engine_class', $tool->engine_class)); ?>"
                                   class="form-input font-mono text-sm"
                                   placeholder="App\Services\CalculatorEngine">
                        </div>
                        <div>
                            <label class="form-label">Engine Method</label>
                            <input type="text" name="engine_method"
                                   value="<?php echo e(old('engine_method', $tool->engine_method)); ?>"
                                   class="form-input font-mono text-sm"
                                   placeholder="percentage">
                        </div>
                    </div>
                    <p class="form-help mt-2">Leave blank to use automatic dispatch based on tool type and slug.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-5 sticky top-20">
                    <h2 class="font-semibold text-gray-900 mb-4">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input">
                                <option value="active" <?php echo e(old('status', $tool->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="draft" <?php echo e(old('status', $tool->status) === 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="inactive" <?php echo e(old('status', $tool->status) === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" id="is_featured_edit"
                                   class="rounded text-brand-600" <?php echo e($tool->is_featured ? 'checked' : ''); ?>>
                            <label for="is_featured_edit" class="text-sm text-gray-700">Featured Tool</label>
                        </div>
                    </div>
                    <div class="mt-5 space-y-2">
                        <button type="submit" class="btn btn-primary w-full">Save Changes</button>
                        <a href="<?php echo e(route('admin.tools.index')); ?>" class="btn btn-secondary w-full">Back to List</a>
                        <form action="<?php echo e(route('admin.tools.destroy', $tool)); ?>" method="POST"
                              onsubmit="return confirm('Permanently delete this tool?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-full btn-sm">Delete Tool</button>
                        </form>
                    </div>
                </div>

                <div class="card p-5">
                    <h2 class="font-semibold text-gray-900 mb-3 text-sm">Tool Stats</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Views</span>
                            <span class="font-medium"><?php echo e(number_format($tool->view_count)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Uses</span>
                            <span class="font-medium"><?php echo e(number_format($tool->use_count)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Created</span>
                            <span class="font-medium"><?php echo e($tool->created_at->format('M d, Y')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Has Blade</span>
                            <span class="font-medium"><?php echo e($bladeExists ? '✅ Yes' : '❌ No'); ?></span>
                        </div>
                    </div>
                    <?php if(!$bladeExists): ?>
                    <form action="<?php echo e(route('admin.tools.generate-blade', $tool)); ?>" method="POST" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-secondary btn-sm w-full">Generate Blade File</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'seo'" x-cloak class="card p-6 max-w-2xl">
            <h2 class="font-semibold text-gray-900 mb-5">SEO Settings</h2>
            <div class="space-y-4">
                <div>
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" value="<?php echo e(old('seo_title', $tool->getOriginal('seo_title'))); ?>"
                           class="form-input" placeholder="Leave blank to auto-generate">
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <textarea name="seo_description" rows="3" class="form-input"><?php echo e(old('seo_description', $tool->getOriginal('seo_description'))); ?></textarea>
                </div>
                <div>
                    <label class="form-label">SEO Keywords</label>
                    <input type="text" name="seo_keywords" value="<?php echo e(old('seo_keywords', $tool->seo_keywords)); ?>"
                           class="form-input" placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-primary">Save SEO Settings</button>
            </div>
        </div>

        
        <div x-show="activeTab === 'inputs'" x-cloak class="space-y-6"
             x-data="editInputBuilder(<?php echo e($tool->inputs->toJson()); ?>, <?php echo e($tool->faqs->toJson()); ?>)">

            <input type="hidden" name="inputs" :value="JSON.stringify(fields)">
            <input type="hidden" name="faqs" :value="JSON.stringify(faqs)">

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">Input Fields</h2>
                    <button type="button" @click="addField()" class="btn btn-secondary btn-sm">+ Add Field</button>
                </div>
                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="`Field ${index + 1}: ` + (field.field_label || 'Untitled')"></span>
                                <button type="button" @click="removeField(index)" class="text-red-400 hover:text-red-600 text-sm">Remove</button>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Field Name</label>
                                    <input type="text" x-model="field.field_name" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Label</label>
                                    <input type="text" x-model="field.field_label" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Type</label>
                                    <select x-model="field.field_type" class="form-input text-sm mt-1">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="date">Date</option>
                                        <option value="color">Color</option>
                                        <option value="range">Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Placeholder</label>
                                    <input type="text" x-model="field.placeholder" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Default</label>
                                    <input type="text" x-model="field.default_value" class="form-input text-sm mt-1">
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" x-model="field.required" class="rounded text-brand-600">
                                        Required
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addField()" class="w-full border-2 border-dashed border-gray-200 rounded-xl p-3 text-sm text-gray-400 hover:border-brand-300 hover:text-brand-500 transition-colors">
                        + Add Input Field
                    </button>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">FAQs</h2>
                    <button type="button" @click="addFaq()" class="btn btn-secondary btn-sm">+ Add FAQ</button>
                </div>
                <div class="space-y-4">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="`FAQ ${index + 1}`"></span>
                                <button type="button" @click="removeFaq(index)" class="text-red-400 text-sm">Remove</button>
                            </div>
                            <div>
                                <input type="text" x-model="faq.question" placeholder="Question..." class="form-input text-sm mb-2">
                                <textarea x-model="faq.answer" rows="2" placeholder="Answer..." class="form-input text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Inputs & FAQs</button>
        </div>

        
        <div x-show="activeTab === 'blade'" x-cloak class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-900">Blade Template</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Path: <code class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">resources/views/tools/generated/<?php echo e($tool->slug); ?>.blade.php</code>
                    </p>
                </div>
                <?php if(!$bladeExists): ?>
                <form action="<?php echo e(route('admin.tools.generate-blade', $tool)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-secondary">Generate Template</button>
                </form>
                <?php endif; ?>
            </div>

            <?php if($bladeExists): ?>
            <div class="alert alert-success mb-4">
                ✅ Custom Blade file exists. You can edit it below.
            </div>
            <div>
                <label class="form-label">Blade Content</label>
                <textarea name="blade_content" rows="30"
                          class="form-input font-mono text-xs leading-relaxed"
                          placeholder="Blade template content..."><?php echo e($bladeContent); ?></textarea>
                <p class="form-help">This is the generated Blade template for this tool. Customize it as needed.</p>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Blade Template</button>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                No custom Blade file has been generated yet. Click "Generate Template" to create one.
                <br><br>
                The generic renderer will be used until a custom template is created.
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function editInputBuilder(existingFields, existingFaqs) {
    return {
        fields: existingFields.map(f => ({
            field_name: f.field_name || '',
            field_label: f.field_label || '',
            field_type: f.field_type || 'text',
            placeholder: f.placeholder || '',
            default_value: f.default_value || '',
            required: !!f.required,
            help_text: f.help_text || ''
        })),
        faqs: existingFaqs.map(f => ({
            question: f.question || '',
            answer: f.answer || ''
        })),
        addField() {
            this.fields.push({ field_name: '', field_label: '', field_type: 'text', placeholder: '', default_value: '', required: false });
        },
        removeField(i) { this.fields.splice(i, 1); },
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
        removeFaq(i) { this.faqs.splice(i, 1); }
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/admin/tools/edit.blade.php ENDPATH**/ ?>