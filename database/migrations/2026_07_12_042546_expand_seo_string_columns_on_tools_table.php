<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            // seo_keywords was varchar(255) but validation allows 500 chars → use text
            $table->text('seo_keywords')->nullable()->change();

            // canonical_url and og_image were varchar(255) but validation allows 500 chars
            $table->string('canonical_url', 500)->nullable()->change();
            $table->string('og_image', 500)->nullable()->change();

            // seo_title: varchar(255) is fine for max:200, but extend slightly for safety
            $table->string('seo_title', 255)->nullable()->change(); // already correct, no-op
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('seo_keywords', 255)->nullable()->change();
            $table->string('canonical_url', 255)->nullable()->change();
            $table->string('og_image', 255)->nullable()->change();
        });
    }
};
