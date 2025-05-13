<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administration.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Vous pouvez passer des données à la vue ici si nécessaire
        // Par exemple, des statistiques, des notifications, etc.
        return view('admin.dashboard');
    }
}