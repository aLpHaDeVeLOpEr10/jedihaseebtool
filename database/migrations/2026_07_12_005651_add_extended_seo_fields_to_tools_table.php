<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            // Open Graph fields (og_image and canonical_url already exist in the table)
            $table->string('og_title', 200)->nullable()->after('og_image');
            $table->text('og_description')->nullable()->after('og_title');

            // Twitter Card fields
            $table->string('twitter_title', 200)->nullable()->after('og_description');
            $table->text('twitter_description')->nullable()->after('twitter_title');

            // Robots meta — e.g. 'index,follow' or 'noindex,nofollow'
            $table->string('robots', 100)->nullable()->after('twitter_description');

            // JSON-LD structured data / schema markup
            $table->text('schema_markup')->nullable()->after('robots');
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn(['og_title', 'og_description', 'twitter_title', 'twitter_description', 'robots', 'schema_markup']);
        });
    }
};
