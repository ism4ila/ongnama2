<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    // Afficher la liste des projets
    public function index()
    {
        $projects = Project::latest()->paginate(9); // Paginer par 9 projets par page
        return view('frontend.projects.index', compact('projects')); // Vue: resources/views/frontend/projects/index.blade.php
    }

    // Afficher un projet spécifique (avec son slug)
    public function show(Project $project) // Utilisation du Route Model Binding
    {
        // Laravel va automatiquement chercher le projet par son 'slug' si configuré ou par 'id'
        // Pour utiliser le slug, assurez-vous que votre modèle Project a la méthode getRouteKeyName()
        // ou que la route est définie pour utiliser le slug.
        // Pour l'instant, supposons que le binding fonctionne par ID ou que nous l'ajusterons.
        return view('frontend.projects.show', compact('project')); // Vue: resources/views/frontend/projects/show.blade.php
    }
}