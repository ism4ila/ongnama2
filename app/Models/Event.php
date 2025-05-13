<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = [
        'title', 
        'slug', // <-- AJOUTÉ ICI
        'description', 
        'location_text'
    ];

    protected $fillable = [
        'title',
        'slug', // <-- AJOUTÉ ICI
        'description',
        'start_datetime',
        'end_datetime',
        'location_text',
        'featured_image_url',
        // 'status', // Décommentez si vous ajoutez le statut
        // 'user_id', // Décommentez si vous ajoutez user_id
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    // ... (les scopes upcoming() et past() restent les mêmes) ...
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', Carbon::now())->orderBy('start_datetime', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('start_datetime', '<', Carbon::now())->orderBy('start_datetime', 'desc');
    }
    
    public function getImageUrlAttribute(): ?string
    {
        if ($this->featured_image_url) {
            if (strpos($this->featured_image_url, 'http') !== 0) {
                return \Illuminate\Support\Facades\Storage::url($this->featured_image_url);
            }
            return $this->featured_image_url;
        }
        return null;
    }

    /**
     * Spécifie la clé de route pour le modèle.
     * Utile pour le route model binding avec des slugs traduits,
     * mais nécessite une configuration supplémentaire ou une gestion manuelle dans le contrôleur.
     * Pour l'instant, nous gérons la récupération par slug manuellement dans le contrôleur.
     *
     * @return string
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug'; // Si vous voulez utiliser le route model binding avec slug
    // }
}