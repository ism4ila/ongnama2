<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category; // Utile si tu veux afficher les catégories ou filtrer par catégorie
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Affiche la liste paginée des articles publiés.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Post::with('user', 'category') // Charger les relations pour éviter N+1 queries
                       ->where('status', 'published') // Ne prend que les articles publiés
                       ->whereNotNull('published_at') // S'assure qu'une date de publication existe
                       ->where('published_at', '<=', now()) // Ne prend que les articles dont la date est passée ou présente
                       ->latest('published_at'); // Trie par date de publication, du plus récent au plus ancien

        // Optionnel : Filtrage par catégorie si un slug de catégorie est passé dans l'URL
        if ($request->has('category')) {
            $categorySlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                // Recherche du slug dans la colonne JSON pour la langue actuelle
                $q->where("slug->".app()->getLocale(), $categorySlug)
                  // Fallback si la traduction pour la locale actuelle n'existe pas, cherche dans la locale de secours
                  ->orWhere("slug->".config('app.fallback_locale'), $categorySlug);
            });
        }
        
        $posts = $query->paginate(9); // 9 articles par page

        // Récupérer toutes les catégories pour un éventuel filtre dans la vue
        $categories = Category::orderBy('name->'.app()->getLocale())->get();


        return view('frontend.posts.index', compact('posts', 'categories'));
    }

    /**
     * Affiche un article unique par son slug (traduit).
     *
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $slug)
    {
        // Recherche l'article par son slug dans la langue actuelle
        // ou dans la langue de secours si non trouvé.
        $post = Post::where("slug->".app()->getLocale(), $slug)
                    ->orWhere("slug->".config('app.fallback_locale'), $slug)
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->with('user', 'category') // Charger les relations
                    ->firstOrFail(); // Renvoie 404 si non trouvé

        // Optionnel : Récupérer des articles récents pour une sidebar "Articles récents"
        $recentPosts = Post::where('id', '!=', $post->id) // Exclure l'article actuel
                            ->where('status', 'published')
                            ->whereNotNull('published_at')
                            ->where('published_at', '<=', now())
                            ->latest('published_at')
                            ->take(5) // Prend les 5 plus récents
                            ->get();
                            
        // Optionnel : Récupérer les catégories pour une sidebar "Catégories"
        $categories = Category::orderBy('name->'.app()->getLocale())->get();

        return view('frontend.posts.show', compact('post', 'recentPosts', 'categories'));
    }
}