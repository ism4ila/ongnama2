<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Partner extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'logo_path',
        'website_url',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    // Si un partenaire peut avoir un logo via MediaItems (au lieu de logo_path simple)
    // public function logo()
    // {
    //     return $this->morphOne(MediaItem::class, 'model')->where('collection_name', 'logo');
    // }
}