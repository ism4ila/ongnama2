<?php

namespace App\Http\Controllers\Admin\Auth; // <-- Namespace important

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Si vous utilisez RouteServiceProvider pour la redirection, décommentez la ligne suivante
// use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /**
     * Où rediriger les administrateurs après la connexion.
     *
     * @var string
     */
    // Si vous définissez une constante ADMIN_HOME dans RouteServiceProvider :
    // protected $redirectTo = RouteServiceProvider::ADMIN_HOME;
    // Sinon, une redirection simple :
    protected $redirectTo = '/admin/dashboard'; // C'est la redirection que nous utilisons pour l'instant

    /**
     * Afficher le formulaire de connexion de l'administrateur.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // S'assure que la vue existe bien à cet emplacement
        return view('admin.auth.login');
    }

    /**
     * Gérer une requête de connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Optionnel : Gestion du Throttling (limitation des tentatives de connexion)
        // if (method_exists($this, 'hasTooManyLoginAttempts') &&
        //     $this->hasTooManyLoginAttempts($request)) {
        //     $this->fireLockoutEvent($request);
        //     return $this->sendLockoutResponse($request);
        // }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // Optionnel : Incrémenter les tentatives de connexion si Throttling activé
        // $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Valider les informations de connexion de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'password.required' => 'Le mot de passe est requis.',
        ]);
    }

    /**
     * Tenter de connecter l'utilisateur à l'application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // C'est ici que nous nous assurons que l'utilisateur est un administrateur
        return Auth::attempt(
            $this->credentials($request) + ['is_admin' => true], // Ajout crucial pour vérifier 'is_admin'
            $request->filled('remember') // Gère la case "Se souvenir de moi"
        );
    }

    /**
     * Obtenir les informations d'identification nécessaires pour la connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Envoyer la réponse après que l'utilisateur a été authentifié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate(); // Régénère l'ID de session pour la sécurité

        // Optionnel : Effacer les tentatives de connexion si Throttling activé
        // $this->clearLoginAttempts($request);

        // Redirige vers la destination prévue (ou $redirectTo si aucune destination n'était prévue)
        return redirect()->intended($this->redirectTo);
    }

    /**
     * Obtenir la réponse de connexion échouée pour l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Lance une exception de validation avec le message d'erreur standard de Laravel pour 'auth.failed'
        // Ce message peut être personnalisé dans vos fichiers de langue (ex: lang/fr/auth.php)
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Le message d'erreur s'appliquera au champ 'email'
        ]);
    }

    /**
     * Déconnecter l'administrateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Déconnecte l'utilisateur

        $request->session()->invalidate(); // Invalide la session actuelle
        $request->session()->regenerateToken(); // Régénère le token CSRF

        // Redirige vers la page de connexion de l'admin après la déconnexion
        return redirect()->route('admin.login');
    }

    /**
     * Constructeur pour appliquer le middleware guest.
     * Le middleware 'guest' est utilisé pour les pages que seuls les utilisateurs non connectés peuvent voir (comme login, register).
     * Si un utilisateur connecté essaie d'y accéder, il est redirigé (généralement vers sa page d'accueil).
     */
    public function __construct()
    {
        // Applique le middleware 'guest' à toutes les méthodes de ce contrôleur,
        // SAUF pour la méthode 'logout'.
        $this->middleware('guest')->except('logout');
    }
}