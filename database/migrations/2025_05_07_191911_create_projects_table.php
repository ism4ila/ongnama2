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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Champ traduisible
            $table->json('description'); // Champ traduisible
            $table->string('status')->default('planned'); // planned, ongoing, completed, cancelled
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('location_latitude', 10, 7)->nullable(); // Précision pour latitude
            $table->decimal('location_longitude', 10, 7)->nullable(); // Précision pour longitude
            $table->string('featured_image_url')->nullable(); // Nom standardisé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};