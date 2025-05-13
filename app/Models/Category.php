<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    
    public $translatable = ['name', 'slug', 'description'];
    
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    
    // Relation : Une catégorie peut avoir plusieurs articles
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}