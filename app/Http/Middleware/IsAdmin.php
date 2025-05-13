<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_admin) { // ou Auth::user()->isAdmin()
            return $next($request);
        }

        // Rediriger vers la page de connexion admin ou une page d'erreur
        // Ou simplement retourner une erreur 403 si l'utilisateur est connecté mais pas admin
        if (Auth::check()) {
             // Option 1: Rediriger vers la page d'accueil du site principal avec un message d'erreur
             // return redirect('/')->with('error', 'Accès non autorisé.');
             // Option 2: Afficher une erreur 403
             abort(403, 'ACCÈS NON AUTORISÉ.');
        }
        
        // Si l'utilisateur n'est pas connecté du tout, rediriger vers la page de connexion admin
        return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
    }
}