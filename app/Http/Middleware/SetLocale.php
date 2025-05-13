<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config; // Pour accéder à la configuration

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Récupérer les langues supportées depuis la configuration pour la cohérence
        $supportedLocales = Config::get('app.available_locales', ['en', 'fr', 'ar']);
        $defaultLocale = Config::get('app.fallback_locale', 'en'); // Langue par défaut de l'application

        $localeToSet = null;

        // Priorité 1: Paramètre 'lang' dans l'URL (?lang=xx)
        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales)) {
            $localeToSet = $request->query('lang');
        }
        // Priorité 2: Locale stockée en session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            $localeToSet = Session::get('locale');
        }
        // Priorité 3: Détection de la langue du navigateur (optionnel, décommentez si besoin)
        // elseif ($request->server('HTTP_ACCEPT_LANGUAGE')) {
        //     $browserLocalePreference = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        //     if (in_array($browserLocalePreference, $supportedLocales)) {
        //         $localeToSet = $browserLocalePreference;
        //     }
        // }

        // Si aucune locale n'a été déterminée par les étapes précédentes, utiliser la locale par défaut
        if (is_null($localeToSet)) {
            $localeToSet = $defaultLocale;
        }

        // Définir la locale pour la requête actuelle
        App::setLocale($localeToSet);

        // Mettre à jour la session avec la locale active pour les requêtes suivantes
        // Cela garantit que si la langue a été définie par ?lang ou par défaut, elle est mémorisée.
        Session::put('locale', $localeToSet);

        // Gérer la direction du texte pour la vue (optionnel, mais peut être utile pour le body tag)
        // Cette variable sera disponible dans toutes les vues si vous le souhaitez.
        // Vous l'utilisez déjà bien dans frontend.blade.php avec $siteSettingsGlobal->getDirection()
        // view()->share('current_locale_direction', ($localeToSet == 'ar' ? 'rtl' : 'ltr'));

        return $next($request);
    }
}