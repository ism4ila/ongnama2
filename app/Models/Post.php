<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // Importer le trait
use Illuminate\Support\Str; // Pour générer les slugs

class Post extends Model
{
    use HasFactory, HasTranslations; // Utiliser les traits

    // Champs traduisibles
    public $translatable = ['title', 'slug', 'body', 'excerpt'];

    /**
     * Les attributs qui sont assignables en masse.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'excerpt',
        'featured_image',
        'status',
        'published_at',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     */
    protected $casts = [
        'published_at' => 'datetime',
        // Les champs JSON sont automatiquement gérés par HasTranslations
        // mais si tu les avais définis ici, tu pourrais les enlever.
        // 'title' => 'array', 
        // 'slug' => 'array',
        // 'body' => 'array',
        // 'excerpt' => 'array',
    ];

    /**
     * Relation avec l'auteur (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la catégorie (Category).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mutateur pour générer automatiquement les slugs traduits si non fournis.
     * Tu peux aussi gérer cela dans le contrôleur si tu préfères plus de contrôle.
     */
    // public function setTitleAttribute($value)
    // {
    //     $this->attributes['title'] = $value; // $value est un tableau de traductions
    //     if (is_array($value)) {
    //         $slugs = [];
    //         foreach ($value as $locale => $title) {
    //             if (!empty($title)) { // Génère le slug seulement si le titre pour cette langue existe
    //                 $slugs[$locale] = Str::slug($title);
    //             }
    //         }
    //         if (!empty($slugs)) {
    //             $this->setTranslations('slug', $slugs);
    //         }
    //     }
    // }

    /**
     * Définir la clé de route pour le modèle (optionnel, pour utiliser le slug dans les URLs).
     * Pour les slugs traduits, cela devient plus complexe si tu veux une résolution automatique
     * par slug dans la langue actuelle. Le Route Model Binding par ID est plus simple ici.
     * Si tu veux utiliser le slug, tu devras gérer la recherche par slug traduit dans le contrôleur.
     */
    // public function getRouteKeyName()
    // {
    //    return 'slug'; // Ceci chercherait dans la colonne 'slug' JSON.
    //                   // Mieux vaut gérer cela manuellement ou utiliser un package dédié aux slugs traduits.
    // }
}