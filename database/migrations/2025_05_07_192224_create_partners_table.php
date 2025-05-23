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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Champ traduisible
            $table->json('description')->nullable(); // Champ traduisible
            $table->string('logo_url'); // Nom standardisé
            $table->string('website_url')->nullable();
            $table->integer('display_order')->default(0); // Ajout de l'ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};