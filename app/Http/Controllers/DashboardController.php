<?php

namespace App\Http\Controllers; // Namespace correct

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller // Nom de classe correct
{
    public function __construct()
    {
        $this->middleware('auth'); // Applique le middleware auth à toutes les méthodes
    }

    public function index()
    {
        $users = User::count();
        $widget = ['users' => $users];
        return view('home', compact('widget')); // Retourne la vue 'home.blade.php'
    }
}