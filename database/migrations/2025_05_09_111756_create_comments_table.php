<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Si les commentaires peuvent être anonymes ou liés à un utilisateur
            $table->string('name'); // Nom de l'auteur du commentaire (si anonyme ou si l'utilisateur n'est pas connecté)
            $table->string('email')->nullable(); // Email (optionnel, pour anonyme)
            $table->text('body');
            $table->boolean('is_approved')->default(false); // Pour la modération
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Pour les réponses aux commentaires
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};