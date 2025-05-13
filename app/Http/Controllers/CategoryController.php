<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Nécessaire pour la validation avancée du slug

class CategoryController extends Controller
{
    protected $supportedLocales = ['en', 'fr', 'ar']; 

    /**
     * Affiche la liste des catégories.
     */
    public function index()
    {
        // La pagination et l'affichage se feront dans la langue actuelle de l'application (config('app.locale'))
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une catégorie.
     */
    public function create()
    {
        return view('admin.categories.create', ['supportedLocales' => $this->supportedLocales]);
    }

    /**
     * Enregistre une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $rules = [];
        foreach ($this->supportedLocales as $locale) {
            $rules["name.{$locale}"] = 'required|string|max:255';
            $rules["slug.{$locale}"] = [
                'required',
                'string',
                'max:255',
                // Assure l'unicité du slug pour cette langue spécifique
                Rule::unique('categories', "slug->{$locale}") 
            ];
            $rules["description.{$locale}"] = 'nullable|string';
        }
        
        $validatedData = $request->validate($rules);

        $categoryData = [];
        foreach ($this->supportedLocales as $locale) {
            $categoryData['name'][$locale] = $validatedData['name'][$locale];
            $categoryData['slug'][$locale] = Str::slug($validatedData['slug'][$locale] ?: $validatedData['name'][$locale]); // Génère le slug s'il n'est pas fourni explicitement ou s'il est vide
            if (isset($validatedData['description'][$locale])) {
                $categoryData['description'][$locale] = $validatedData['description'][$locale];
            }
        }
        
        // Si le champ slug n'est pas explicitement fourni pour chaque langue dans le formulaire,
        // il est préférable de le générer à partir du nom.
        // Le code ci-dessus suppose que vous pourriez avoir des champs slug distincts par langue dans le formulaire.
        // Sinon, vous pouvez générer les slugs à partir des noms :
        // foreach ($this->supportedLocales as $locale) {
        //     $categoryData['slug'][$locale] = Str::slug($validatedData['name'][$locale]);
        // }

        Category::create($categoryData);

        return redirect()->route('admin.categories.index')
                         ->with('success', __('Category created successfully.')); // Utilise la traduction
    }

    /**
     * Affiche les détails d'une catégorie.
     */
    public function show(Category $category)
    {
        // La vue show affichera la traduction selon la locale actuelle
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Affiche le formulaire de modification d'une catégorie.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'), ['supportedLocales' => $this->supportedLocales]);
    }

    /**
     * Met à jour une catégorie existante.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [];
        foreach ($this->supportedLocales as $locale) {
            $rules["name.{$locale}"] = 'required|string|max:255';
            $rules["slug.{$locale}"] = [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', "slug->{$locale}")->ignore($category->id)
            ];
            $rules["description.{$locale}"] = 'nullable|string';
        }
        
        $validatedData = $request->validate($rules);

        $updateData = [];
        foreach ($this->supportedLocales as $locale) {
            $updateData['name'][$locale] = $validatedData['name'][$locale];
            $updateData['slug'][$locale] = Str::slug($validatedData['slug'][$locale] ?: $validatedData['name'][$locale]);
            if (isset($validatedData['description'][$locale])) {
                $updateData['description'][$locale] = $validatedData['description'][$locale];
            } else {
                // Si la description n'est pas fournie pour une langue, la vider
                 $updateData['description'][$locale] = null;
            }
        }
        
        $category->update($updateData);

        return redirect()->route('admin.categories.index')
                         ->with('success', __('Category updated successfully.')); // Utilise la traduction
    }

    /**
     * Supprime une catégorie.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
                         ->with('success', __('Category deleted successfully.')); // Utilise la traduction
    }
}