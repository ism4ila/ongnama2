<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request; // Utilisez Illuminate\Http\Request pour les méthodes sans FormRequest spécifique au début
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = config('app.available_locales', ['en', 'fr', 'ar']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locales = $this->availableLocales;
        $project = new Project(); // Pour le formulaire partiel
        $projectStatuses = ['planned' => __('Planifié'), 'ongoing' => __('En cours'), 'completed' => __('Terminé'), 'cancelled' => __('Annulé')];
        return view('admin.projects.create', compact('project', 'locales', 'projectStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validatedData = $request->validated();
        $project = new Project();

        // Gestion de l'image mise en avant
        if ($request->hasFile('featured_image_url')) {
            $path = $request->file('featured_image_url')->store('projects/featured_images', 'public');
            $project->featured_image_url = $path;
        }

        $project->status = $validatedData['status'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'] ?? null;
        $project->location_latitude = $validatedData['location_latitude'] ?? null;
        $project->location_longitude = $validatedData['location_longitude'] ?? null;

        foreach ($this->availableLocales as $locale => $properties) {
            if (isset($validatedData['title'][$locale]) && !empty($validatedData['title'][$locale])) {
                $project->setTranslation('title', $locale, $validatedData['title'][$locale]);
            }
            if (isset($validatedData['description'][$locale])) {
                $project->setTranslation('description', $locale, $validatedData['description'][$locale]);
            }
        }

        $project->save();

        return redirect()->route('admin.projects.index')->with('success', __('Projet créé avec succès.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Optionnel, souvent on redirige vers edit ou index
        return redirect()->route('admin.projects.edit', $project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $locales = $this->availableLocales;
        $projectStatuses = ['planned' => __('Planifié'), 'ongoing' => __('En cours'), 'completed' => __('Terminé'), 'cancelled' => __('Annulé')];
        return view('admin.projects.edit', compact('project', 'locales', 'projectStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validatedData = $request->validated();

        // Gestion de l'image mise en avant
        if ($request->hasFile('featured_image_url')) {
            // Supprimer l'ancienne image si elle existe
            if ($project->featured_image_url) {
                Storage::disk('public')->delete($project->featured_image_url);
            }
            $path = $request->file('featured_image_url')->store('projects/featured_images', 'public');
            $project->featured_image_url = $path;
        } elseif ($request->input('remove_featured_image_url') == 1 && $project->featured_image_url) {
            Storage::disk('public')->delete($project->featured_image_url);
            $project->featured_image_url = null;
        }


        $project->status = $validatedData['status'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'] ?? null;
        $project->location_latitude = $validatedData['location_latitude'] ?? null;
        $project->location_longitude = $validatedData['location_longitude'] ?? null;

        foreach ($this->availableLocales as $locale => $properties) {
            if (isset($validatedData['title'][$locale]) && !empty($validatedData['title'][$locale])) {
                $project->setTranslation('title', $locale, $validatedData['title'][$locale]);
            }
            if (array_key_exists('description', $validatedData) && array_key_exists($locale, $validatedData['description'])) {
                $project->setTranslation('description', $locale, $validatedData['description'][$locale]);
            } elseif (array_key_exists('description', $validatedData) && is_null($validatedData['description'][$locale] ?? null)) {
                 $project->setTranslation('description', $locale, null);
            }
        }

        $project->save();

        return redirect()->route('admin.projects.index')->with('success', __('Projet mis à jour avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Supprimer l'image associée si elle existe
        if ($project->featured_image_url) {
            Storage::disk('public')->delete($project->featured_image_url);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', __('Projet supprimé avec succès.'));
    }
}