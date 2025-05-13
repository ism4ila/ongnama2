<?php

namespace App\Http\Middleware; // Vérifiez ce namespace

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session; // Pour stocker la langue en session

class SetLocale // Vérifiez le nom de la classe
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'fr', 'ar']; // Langues supportées

        // 1. Langue depuis le paramètre d'URL ?lang=fr
        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales)) {
            $locale = $request->query('lang');
            App::setLocale($locale);
            Session::put('locale', $locale); // Stocke en session pour les requêtes suivantes
        } 
        // 2. Langue depuis la session (si pas de paramètre d'URL, mais déjà visité avec ?lang=)
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            App::setLocale(Session::get('locale'));
        }
        // 3. Langue du navigateur (optionnel, si vous voulez une détection automatique)
        // elseif ($request->server('HTTP_ACCEPT_LANGUAGE')) {
        //     $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        //     if (in_array($browserLocale, $supportedLocales)) {
        //         App::setLocale($browserLocale);
        //         // Optionnel: Stocker aussi en session la langue détectée du navigateur
        //         // Session::put('locale', $browserLocale);
        //     }
        // }
        // 4. Sinon, la locale par défaut de config/app.php ('fallback_locale' ou 'locale') sera utilisée.
        // App::setLocale() définit la langue pour la requête actuelle.
        // Session::put('locale', App::getLocale()) s'assure que si une locale est définie (même par défaut), elle est mémorisée.
        
        // Si aucune locale n'a été explicitement définie par les étapes précédentes, 
        // et que vous voulez vous assurer que la session est toujours initialisée avec la locale actuelle (même celle par défaut de l'app)
        if (!Session::has('locale') || !in_array(Session::get('locale'), $supportedLocales)) {
            Session::put('locale', App::getLocale());
        }


        // Assurez-vous que le paramètre 'locale' est disponible pour les routes
        // Cela est utile pour la génération d'URL avec le paramètre de langue si vous l'utilisez comme segment d'URL.
        // Pour le moment, votre SetLocale se base sur ?lang= ou la session, pas sur un segment d'URL.
        // Si vous passez à des URL comme /fr/accueil, la gestion de la locale dans les routes change.
        // $request->route()->forgetParameter('locale'); // Décommenter si vous aviez un paramètre de route 'locale'

        return $next($request);
    }
}