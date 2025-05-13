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
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('model'); // Crée model_id, model_type et un index automatiquement
            $table->string('collection_name')->default('default'); // Pour grouper les médias (ex: 'images_principales', 'documents')
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('public'); // Disque de stockage (voir filesystems.php)
            $table->string('path'); // Chemin relatif au disque
            $table->unsignedBigInteger('size');
            $table->json('alt_text')->nullable(); // Texte alternatif multilingue
            $table->json('caption')->nullable(); // Légende multilingue
            $table->integer('order_column')->nullable();
            $table->timestamps();
            
            // Supprimez cette ligne, car morphs() crée déjà l'index
            // $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};