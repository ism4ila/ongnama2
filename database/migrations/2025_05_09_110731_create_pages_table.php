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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable title
            $table->json('slug');  // Translatable slug
            $table->json('body');  // Translatable body
            $table->boolean('is_published')->default(false);

            // Champs méta traduisibles (corrigés en JSON)
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            // Nouveaux champs pour la navigation
            $table->boolean('show_in_navbar')->default(false);
            $table->integer('navbar_order')->nullable()->default(0);

            // Nouveaux champs pour les liens de pied de page (optionnel, alternative à un modèle FooterLink)
            $table->boolean('show_in_footer_useful_links')->default(false);
            $table->integer('footer_useful_links_order')->nullable()->default(0);
            $table->boolean('show_in_footer_navigation')->default(false); // Si vous voulez une section nav distincte en footer
            $table->integer('footer_navigation_order')->nullable()->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
