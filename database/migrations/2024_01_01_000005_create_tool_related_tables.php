<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->string('section_key'); // hero, about, how_to_use, tips, etc.
            $table->string('title')->nullable();
            $table->longText('content');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['tool_id', 'section_key']);
        });

        Schema::create('tool_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['tool_id', 'sort_order']);
        });

        Schema::create('tool_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->string('field_name');
            $table->string('field_label');
            $table->string('field_type'); // text, number, textarea, select, checkbox, radio, date, time, file, color, range
            $table->string('placeholder')->nullable();
            $table->text('default_value')->nullable();
            $table->boolean('required')->default(false);
            $table->json('options')->nullable(); // For select/radio/checkbox
            $table->json('validation')->nullable(); // min, max, pattern etc.
            $table->text('help_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tool_id', 'sort_order']);
        });

        Schema::create('tool_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tool_type');
            $table->longText('blade_content');
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_templates');
        Schema::dropIfExists('tool_inputs');
        Schema::dropIfExists('tool_faqs');
        Schema::dropIfExists('tool_contents');
    }
};
