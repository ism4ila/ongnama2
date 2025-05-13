<?php

namespace App\Http\Controllers; // Namespace standard

use Illuminate\Http\Request;
use App\Models\Project; // Pour les projets phares
use App\Models\Post;    // Pour les actualités récentes

class HomeController extends Controller
{
    // /**
    //  * Show the public application homepage.
    //  *
    //  * @return \Illuminate\Contracts\Support\Renderable
    //  */
    // public function index()
    // {
    //     // Exemple : Récupérer 3 projets phares et 3 actualités récentes
    //     $featuredProjects = Project::where('is_featured', true)->latest()->take(3)->get();
    //     $recentPosts = Post::whereNotNull('published_at')->where('published_at', '<=', now())->latest()->take(3)->get();

    //     // Le nom de la vue 'frontend.home' que nous avions défini,
    //     // ou vous pouvez le nommer 'welcome' ou 'public-home' selon votre structure de vues.
    //     // Pour rester cohérent avec les propositions précédentes :
    //     return view('frontend.home', compact('featuredProjects', 'recentPosts'));
    // }
}