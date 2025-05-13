<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id(); // Unique record
            $table->json('site_name')->nullable(); // Translatable
            $table->json('footer_description')->nullable();
            $table->json('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('contact_map_iframe_url')->nullable();

            $table->string('social_facebook_url')->nullable();
            $table->string('social_twitter_url')->nullable();
            $table->string('social_instagram_url')->nullable();
            $table->string('social_linkedin_url')->nullable();
            $table->string('social_youtube_url')->nullable();

            $table->json('footer_copyright_text')->nullable();
            $table->string('favicon')->nullable(); // Chemin relatif à public/storage/
            $table->string('logo_path')->nullable(); // Chemin relatif à public/storage/
            $table->string('footer_logo_path')->nullable(); // Chemin relatif à public/storage/

            // --- COLONNES DE COULEUR AJOUTÉES ICI ---
            $table->string('primary_color')->nullable()->default('#26A69A');
            $table->string('secondary_color')->nullable()->default('#00796B');
            $table->string('accent_color')->nullable()->default('#80CBC4');
            // -----------------------------------------

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};