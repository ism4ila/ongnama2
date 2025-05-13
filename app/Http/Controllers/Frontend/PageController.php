<?php
// app/Http/Controllers/Frontend/PageController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($locale, Page $page) // Laravel va essayer de résoudre Page par son slug traduit grâce au middleware
    {
        // La résolution du modèle par slug traduit doit être configurée.
        // Si le route model binding ne fonctionne pas directement avec le slug traduit dans votre version/config,
        // vous devrez peut-être le faire manuellement :
        // $page = Page::where("slug->".app()->getLocale(), $slug)
        //             ->orWhere("slug->".config('app.fallback_locale'), $slug)
        //             ->where('is_published', true)
        //             ->firstOrFail();


        if (!$page->is_published) {
            abort(404);
        }
        // La variable $page est déjà injectée si le route model binding fonctionne
        return view('frontend.page.show', compact('page'));
    }
}