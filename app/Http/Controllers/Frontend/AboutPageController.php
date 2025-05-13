<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index()
    {
        // Pour l'instant, juste une vue simple. On pourrait ajouter des données spécifiques si besoin.
        return view('frontend.about'); // Vue: resources/views/frontend/about.blade.php
    }
}