<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use App\Models\Page; // Pour la navigation dynamique

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rendre les paramètres du site disponibles globalement pour toutes les vues frontend
        // ou spécifiquement pour le layout principal et les partiels concernés.
        View::composer(['frontend.frontend', 'frontend.*'], function ($view) {
            // Le 'frontend.*' rendra $siteSettingsGlobal disponible à toutes les vues dans le dossier frontend.
            // Si vous voulez être plus restrictif, listez les vues/partials exacts.
            $view->with('siteSettingsGlobal', SiteSetting::instance());
        });

        // Rendre les liens de navigation principaux disponibles
        View::composer(['frontend.frontend', 'frontend.partials.navbar'], function ($view) {
            // Assurez-vous que Page::query() est utilisé pour construire la requête
            // et que les champs 'show_in_navbar' et 'navbar_order' existent et sont corrects.
            $navLinks = Page::query()
                            ->where('is_published', true)
                            ->where('show_in_navbar', true)
                            ->orderBy('navbar_order', 'asc')
                            ->get()
                            ->map(function ($page) {
                                // Vérifier si la traduction du slug existe pour la locale actuelle, sinon fallback
                                $page->current_locale_slug = $page->getTranslation('slug', app()->getLocale(), $page->slug);
                                return $page;
                            });
            $view->with('globalNavLinks', $navLinks);
        });
    }
}