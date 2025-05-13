<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

// --- Contrôleurs Frontend ---
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\AboutPageController as FrontendAboutPageController;
use App\Http\Controllers\Frontend\ProjectController as FrontendProjectController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController; // Pour les "Actualités" / "News"
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
// use App\Http\Controllers\Frontend\PartnerController as FrontendPartnerController; // Décommentez si vous avez une page dédiée
use App\Http\Controllers\Frontend\PageController as FrontendPageController; // Pour les pages dynamiques
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;

// --- Contrôleurs Admin ---
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController; // Pour la connexion admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
// Décommentez et ajoutez les use statements pour d'autres contrôleurs Admin au fur et à mesure
// use App\Http\Controllers\Admin\PostController as AdminPostController;
// use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
// use App\Http\Controllers\Admin\EventController as AdminEventController;
// use App\Http\Controllers\Admin\PageController as AdminPageController;
// use App\Http\Controllers\Admin\SettingController as AdminSettingController; // Pour SiteSettings, HomePageSettings etc.

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROUTE POUR LE CHANGEMENT DE LANGUE ---
// Doit être accessible sans le middleware SetLocale pour pouvoir changer la langue
Route::get('language/{locale}', function ($locale) {
    $supportedLocales = Config::get('app.available_locales', ['en', 'fr', 'ar']);
    if (in_array($locale, $supportedLocales)) {
        App::setLocale($locale);
        Session::put('locale', $locale);
    }
    return Redirect::back();
})->name('language.switch');


// --- ROUTES PUBLIQUES (FRONTEND) ---
// Toutes ces routes bénéficient du middleware 'set_locale' (via le groupe 'web' dans Kernel.php)
// et du groupe 'web' (session, cookies, etc.).
Route::group(['middleware' => ['web', 'set_locale']], function () {

    Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.home');

    // "À Propos" - Peut être gérée par PageController si 'about' est un slug dans la table Pages
    // Ou gardez un contrôleur dédié si la logique est complexe :
    Route::get('/about', [FrontendAboutPageController::class, 'index'])->name('frontend.about');

    // Actualités (Posts) - Votre fichier actuel utilise /news, mais vos vues utilisent frontend.posts.*
    // J'utilise /blog pour la cohérence avec les noms de route frontend.posts.*. Adaptez si /news est préféré.
    Route::get('/blog', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
    Route::get('/blog/{post:slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');
    // Route pour les commentaires (si implémentée)
    // Route::post('/blog/{post}/comments', [FrontendPostController::class, 'storeComment'])->name('frontend.posts.comments.store');


    // Projets
    Route::get('/projects', [FrontendProjectController::class, 'index'])->name('frontend.projects.index');
    Route::get('/projects/{project:slug}', [FrontendProjectController::class, 'show'])->name('frontend.projects.show');

    // Événements
    Route::get('/events', [FrontendEventController::class, 'index'])->name('frontend.events.index');
    // Correction: s'assurer que le paramètre est {event:slug} pour le route model binding
    Route::get('/events/{event:slug}', [FrontendEventController::class, 'show'])->name('frontend.events.show');

    // Partenaires (si une page dédiée existe)
    // Route::get('/partners', [FrontendPartnerController::class, 'index'])->name('frontend.partners.index');

    // Contact
    Route::get('/contact', [FrontendContactController::class, 'index'])->name('frontend.contact.index');
    Route::post('/contact', [FrontendContactController::class, 'store'])->name('frontend.contact.store');


    // --- ROUTES D'AUTHENTIFICATION STANDARD LARAVEL (Pour utilisateurs frontend, si nécessaire) ---
    // Mettez 'register' => false si vous ne voulez pas de page d'inscription publique
    Auth::routes(['verify' => true, 'register' => true]);

    // Route /home par défaut de Laravel (si Auth::routes() est utilisé)
    // Assurez-vous qu'un App\Http\Controllers\HomeController existe ou changez cette route.
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    // --- ROUTE POUR LES PAGES DYNAMIQUES (IMPORTANT: À LA FIN DES ROUTES FRONTEND) ---
    // Elle essaiera de trouver une page correspondant au slug.
    // Le {page:slug} utilise le route model binding sur le champ 'slug' du modèle Page.
    Route::get('/{page:slug}', [FrontendPageController::class, 'show'])
        // ->where('page', '^[a-zA-Z0-9_\-\/]+$') // Moins nécessaire avec le route model binding par slug explicite
        ->name('frontend.page.show');
});


// --------------------------------------------------
// --------------- ROUTES ADMINISTRATION ------------
// --------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {

    // Routes de Connexion pour l'Administration (accessibles aux invités)
    // Le middleware 'guest' est appliqué via le constructeur de AdminLoginController
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Routes Administrateur Protégées
    // Nécessite que l'utilisateur soit authentifié ET soit un administrateur (via le middleware 'auth.admin')
    Route::middleware(['auth', 'auth.admin'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD pour les Catégories
        Route::resource('categories', AdminCategoryController::class);

        // Ajoutez ici les routes pour les autres ressources Admin
        // Exemple :
        // Route::resource('posts', AdminPostController::class);
        // Route::resource('projects', AdminProjectController::class);
        // Route::resource('events', AdminEventController::class);
        // Route::resource('pages', AdminPageController::class);
        // Route::resource('settings/site', AdminSettingController::class); // Exemple pour les paramètres
    });
});