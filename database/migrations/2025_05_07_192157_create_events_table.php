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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Champ traduisible
            $table->json('slug')->unique();
            $table->json('description'); // Champ traduisible
            $table->dateTime('start_datetime'); // Nom et type standardisés
            $table->dateTime('end_datetime')->nullable(); // Nom et type standardisés
            $table->json('location_text')->nullable(); // Champ traduisible pour le lieu
            $table->string('featured_image_url')->nullable(); // Nom standardisé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};