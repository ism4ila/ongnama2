<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- Contrôleurs Frontend ---
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\AboutPageController as FrontendAboutPageController;
use App\Http\Controllers\Frontend\ProjectController as FrontendProjectController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
// use App\Http\Controllers\Frontend\PartnerController as FrontendPartnerController; // Décommentez si utilisé
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;

// --- Contrôleurs Backend/Admin ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
// Importez les autres contrôleurs Admin au besoin (Post, Project, Event, Page, SiteSetting etc.)
// use App\Http\Controllers\Admin\EventController as AdminEventController; // Exemple
// use App\Http\Controllers\Admin\PageController as AdminPageController; // Exemple

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- AUTHENTIFICATION ---
// Cette ligne est cruciale ! Elle enregistre les routes de connexion, inscription, etc.
Auth::routes(['verify' => true]); // 'verify' => true active la vérification d'email si vous l'utilisez

// --- ROUTES PUBLIQUES (FRONTEND) ---
Route::group(['middleware' => ['web', 'set_locale']], function () {
    Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.home');

    Route::get('/news', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
    Route::get('/news/{post:slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');

    Route::get('/projects', [FrontendProjectController::class, 'index'])->name('frontend.projects.index');
    Route::get('/projects/{project:slug}', [FrontendProjectController::class, 'show'])->name('frontend.projects.show');
    
    Route::get('/events', [FrontendEventController::class, 'index'])->name('frontend.events.index');
    // Assurez-vous que votre modèle Event peut résoudre 'slug' ou ajustez le paramètre
    // Par exemple, si vous utilisez un champ 'slug' unique :
    Route::get('/events/{event:slug}', [FrontendEventController::class, 'show'])->name('frontend.events.show');
    // Ou si vous aviez une méthode spécifique comme 'showByNameSlug':
    // Route::get('/events/{event_slug}', [FrontendEventController::class, 'showByNameSlug'])->name('frontend.events.show');


    Route::get('/contact', [FrontendContactController::class, 'index'])->name('frontend.contact.index');
    Route::post('/contact', [FrontendContactController::class, 'store'])->name('frontend.contact.store');

    // Exemple pour une page "À propos" via PageController (si 'about' est un slug dans votre DB)
    // Route::get('/about', [FrontendPageController::class, 'show'])->defaults('slug', 'about')->name('frontend.about');
    // Ou si vous gardez un contrôleur dédié :
    // Route::get('/about', [FrontendAboutPageController::class, 'index'])->name('frontend.about');


    // Route pour les PAGES DYNAMIQUES (IMPORTANT: doit être déclarée APRÈS les autres routes plus spécifiques)
    Route::get('/{slug}', [FrontendPageController::class, 'show'])
        ->where('slug', '^[a-zA-Z0-9_\-\/]+$')
        ->name('frontend.page.show');
});


// --- ROUTES ADMINISTRATION (BACKEND) ---
// Le middleware 'auth' assure que seuls les utilisateurs connectés peuvent accéder à ces routes.
Route::middleware(['auth', 'web'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // CRUD pour les Catégories (que nous avons implémenté)
    Route::resource('categories', AdminCategoryController::class);

    // Ajoutez ici d'autres Route::resource pour les autres sections admin au fur et à mesure
    // Exemple:
    // Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    // Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    // Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
});

// Route /home par défaut de Laravel (peut être supprimée ou redirigée si non utilisée, car nous avons admin.dashboard)
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');