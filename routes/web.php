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
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;

// --- Contrôleurs Admin ---
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController; // Déjà présent, bien
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
// use App\Http\Controllers\Admin\MediaItemController as AdminMediaItemController; // Si vous l'implémentez


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROUTE POUR LE CHANGEMENT DE LANGUE ---
Route::get('language/{locale}', function ($locale) {
    $supportedLocales = Config::get('app.available_locales', ['en', 'fr', 'ar']);
    if (in_array($locale, $supportedLocales)) {
        App::setLocale($locale);
        Session::put('locale', $locale);
    }
    return Redirect::back();
})->name('language.switch');


// --- ROUTES PUBLIQUES (FRONTEND) ---
Route::group(['middleware' => ['web', 'set_locale']], function () {
    Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.home');
    Route::get('/about', [FrontendAboutPageController::class, 'index'])->name('frontend.about');
    Route::get('/blog', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
    Route::get('/blog/{post:slug}', [FrontendPostController::class, 'show'])->name('frontend.posts.show');
    // Route::post('/blog/{post}/comments', [FrontendPostController::class, 'storeComment'])->name('frontend.posts.comments.store'); // Assurez-vous que cette méthode existe dans FrontendPostController
    Route::get('/projects', [FrontendProjectController::class, 'index'])->name('frontend.projects.index');
    Route::get('/projects/{project:slug}', [FrontendProjectController::class, 'show'])->name('frontend.projects.show'); // Assurez-vous que le modèle Project a un champ slug traduisible et est géré
    Route::get('/events', [FrontendEventController::class, 'index'])->name('frontend.events.index');
    Route::get('/events/{event:slug}', [FrontendEventController::class, 'show'])->name('frontend.events.show'); // Idem pour Event et slug
    Route::get('/contact', [FrontendContactController::class, 'index'])->name('frontend.contact.index');
    Route::post('/contact', [FrontendContactController::class, 'store'])->name('frontend.contact.store');

    Auth::routes(['verify' => true, 'register' => true]); // Ou false pour register si non désiré publiquement

    // Route /home par défaut de Laravel
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); // Assurez-vous que ce contrôleur existe ou commentez/modifiez

    Route::get('/{page:slug}', [FrontendPageController::class, 'show'])->name('frontend.page.show');
});


// --------------------------------------------------
// --------------- ROUTES ADMINISTRATION ------------
// --------------------------------------------------
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'auth.admin'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUDs
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('posts', AdminPostController::class);
        Route::resource('projects', AdminProjectController::class);
        Route::resource('events', AdminEventController::class);
        Route::resource('pages', AdminPageController::class);
        Route::resource('team-members', AdminTeamMemberController::class)->parameters(['team-members' => 'teamMember']); // Alias pour route model binding si besoin
        Route::resource('partners', AdminPartnerController::class);
        Route::resource('users', AdminUserController::class);
        
        // Commentaires (peut nécessiter des routes personnalisées si pas un CRUD complet)
        // Route::resource('comments', AdminCommentController::class)->only(['index', 'edit', 'update', 'destroy']);
        // Si vous avez des actions spécifiques comme 'approve'
        // Route::post('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');


        // Paramètres
        Route::get('settings/site', [AdminSettingController::class, 'editSiteSettings'])->name('settings.site.edit');
        Route::put('settings/site', [AdminSettingController::class, 'updateSiteSettings'])->name('settings.site.update'); // Utiliser PUT ou PATCH pour la mise à jour
        Route::get('settings/homepage', [AdminSettingController::class, 'editHomePageSettings'])->name('settings.homepage.edit');
        Route::put('settings/homepage', [AdminSettingController::class, 'updateHomePageSettings'])->name('settings.homepage.update'); // Utiliser PUT ou PATCH

        // Médiathèque (si implémentée comme ressource)
        // Route::resource('media-items', AdminMediaItemController::class);
    });
});