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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Auteur
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Catégorie

            $table->json('title'); // Champ traduisible
            $table->json('slug');  // Champ traduisible
            $table->json('body');  // Champ traduisible
            $table->json('excerpt')->nullable(); // Champ traduisible

            $table->string('featured_image')->nullable();
            $table->enum('status', ['published', 'draft', 'pending'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};