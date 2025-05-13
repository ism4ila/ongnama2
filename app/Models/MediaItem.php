<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MediaItem extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['alt_text', 'caption'];

    protected $fillable = [
        'model_type',
        'model_id',
        'collection_name',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'size',
        'alt_text',
        'caption',
        'order_column',
    ];

    protected $casts = [
        'size' => 'integer',
        'order_column' => 'integer',
    ];

    // Relation polymorphique : Un média appartient à un modèle (Project, Post, etc.)
    public function model()
    {
        return $this->morphTo();
    }
}