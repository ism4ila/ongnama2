<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Post;
use App\Models\Event;
use App\Models\Partner;
use App\Models\HomePageSetting; // <-- Ajouté

// SiteSetting sera injecté par ViewComposer, donc pas besoin ici si ViewComposer est utilisé.
// use App\Models\SiteSetting; 

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil publique.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Récupérer les derniers projets (par exemple, les 3 plus récents publiés)
        $latestProjects = Project::where('status', 'published') // Assurez-vous d'avoir une colonne status ou is_published
            ->latest('start_date') // ou 'created_at' selon votre logique de tri
            ->take(3)
            ->get();

        // Récupérer les derniers articles publiés (par exemple, les 3 plus récents)
        $latestPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        // Récupérer les prochains événements (par exemple, les 3 plus proches)
        $upcomingEvents = Event::where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->take(3)
            ->get();

        // Récupérer les partenaires (par exemple, tous, triés par ordre d'affichage)
        $partners = Partner::orderBy('display_order', 'asc')->get();
        
        // Récupérer les paramètres spécifiques à la page d'accueil
        $homePageSettings = HomePageSetting::instance();
        
        // Les SiteSettings (logo, infos footer, etc.) sont généralement rendus disponibles globalement
        // via un ViewComposer (voir étape 11). Si vous n'utilisez pas de ViewComposer pour cela,
        // vous devriez les charger ici :
        // $siteSettings = SiteSetting::instance();

        return view('frontend.home', compact(
            'latestProjects',
            'latestPosts',
            'upcomingEvents',
            'partners',
            'homePageSettings' // Passer les paramètres de la page d'accueil à la vue
            // 'siteSettings' // Décommentez si vous ne passez pas par un ViewComposer
        ));
    }
}