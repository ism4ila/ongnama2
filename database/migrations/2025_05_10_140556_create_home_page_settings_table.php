<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id(); // Doit être unique, donc un seul enregistrement sera géré par le modèle
            $table->json('hero_title')->nullable();
            $table->json('hero_subtitle')->nullable();
            $table->json('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_background_image')->nullable();

            $table->json('newsletter_title')->nullable();
            $table->json('newsletter_text')->nullable();
            
            $table->json('latest_projects_title')->nullable();
            $table->json('latest_posts_title')->nullable();
            $table->json('upcoming_events_title')->nullable();
            $table->json('partners_title')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};