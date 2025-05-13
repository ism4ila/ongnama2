<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = [
        'title',
        'slug',
        'body',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_published',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'show_in_navbar', // Ajouté
        'navbar_order',   // Ajouté
        'show_in_footer_useful_links', // Ajouté
        'footer_useful_links_order',   // Ajouté
        'show_in_footer_navigation',   // Ajouté
        'footer_navigation_order',     // Ajouté
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_navbar' => 'boolean',
        'navbar_order' => 'integer',
        'show_in_footer_useful_links' => 'boolean',
        'footer_useful_links_order' => 'integer',
        'show_in_footer_navigation' => 'boolean',
        'footer_navigation_order' => 'integer',
    ];
}