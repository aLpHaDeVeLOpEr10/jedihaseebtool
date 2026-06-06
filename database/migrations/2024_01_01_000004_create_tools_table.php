<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('icon')->default('🔧');
            $table->string('color')->default('#6366f1');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->string('tool_type')->default('generic'); // calculator, converter, generator, text, file, productivity, game
            $table->string('blade_path')->nullable(); // path to custom blade file
            $table->json('input_schema')->nullable(); // JSON schema for form fields
            $table->json('output_schema')->nullable(); // JSON schema for output display
            $table->string('engine_class')->nullable(); // Service class to handle logic
            $table->string('engine_method')->nullable(); // Method on service class
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('has_custom_blade')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('use_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured', 'sort_order']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
