<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // SEO Meta Tags
            $table->string('meta_title', 70)->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('focus_keyword')->nullable()->after('meta_keywords');
            
            // Canonical URL
            $table->string('canonical_url')->nullable()->after('focus_keyword');
            
            // Open Graph (Sosyal Medya) Optimizasyonu
            $table->string('og_title')->nullable()->after('canonical_url');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            
            // Twitter Card
            $table->string('twitter_title')->nullable()->after('og_image');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->string('twitter_image')->nullable()->after('twitter_description');
            
            // SEO Kontrol Alanları
            $table->boolean('index_page')->default(true)->after('is_active');
            $table->boolean('follow_links')->default(true)->after('index_page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'focus_keyword',
                'canonical_url',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'index_page',
                'follow_links',
            ]);
        });
    }
};
