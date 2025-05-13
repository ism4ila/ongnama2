<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // Assurez-vous que cette ligne est présente

class Project extends Model
{
    use HasFactory;
    use HasTranslations; // Assurez-vous que cette ligne est présente

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',                 // Ajouté
        'description',           // Ajouté
        'status',
        'start_date',
        'end_date',
        'location_latitude',
        'location_longitude',
        'featured_image_url'
    ];

    /**
     * Les attributs qui doivent être traduisibles.
     *
     * @var array<int, string>
     */
    public $translatable = ['title', 'description'];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'location_latitude' => 'decimal:7', // Conservez les casts existants
        'location_longitude' => 'decimal:7',
        // Ne pas caster les champs traduisibles ici, HasTranslations s'en charge
    ];

    // Ajoutez ici d'autres relations ou méthodes si nécessaire
}