<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();
        
        // Filtrage par statut si demandé
        if ($request->has('status') && in_array($request->status, ['planned', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }
        
        // Tri par date de début (plus récents d'abord)
        $projects = $query->orderBy('start_date', 'desc')
                         ->paginate(9);
        
        // Ajouter les paramètres de requête à la pagination
        $projects->appends($request->query());
        
        return view('frontend.projects.index', compact('projects'));
    }

    public function show($locale, $project)
    {
        // Trouver le projet par ID
        $project = Project::findOrFail($project);
        
        // Récupérer d'autres projets pour la sidebar (3 projets récents excluant le projet actuel)
        $otherProjects = Project::where('id', '!=', $project->id)
                               ->orderBy('start_date', 'desc')
                               ->take(3)
                               ->get();
        
        return view('frontend.projects.show', compact('project', 'otherProjects'));
    }
}