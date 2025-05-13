<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'role',
        'image',
        'bio',
        'order',
        'is_active',
    ];

    public $translatable = [
        'name',
        'role',
        'bio',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}