<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10); // Récupère les catégories, les plus récentes en premier
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name->'.app()->getLocale(), // Assurez-vous que le nom est unique pour la locale actuelle
            'slug' => 'nullable|string|max:255|unique:categories,slug->'.app()->getLocale(), // Le slug doit aussi être unique
        ]);

        // Création du slug à partir du nom si non fourni
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        } else {
            $validatedData['slug'] = Str::slug($validatedData['slug']); // Assurez-vous que le slug fourni est "slugifié"
        }
        
        // Le modèle Category utilise HasTranslations, donc l'assignation directe
        // pour la locale courante fonctionnera pour les champs traduisibles.
        // Pour sauvegarder explicitement une traduction :
        // $category = new Category();
        // $category->setTranslation('name', app()->getLocale(), $validatedData['name']);
        // $category->setTranslation('slug', app()->getLocale(), $validatedData['slug']);
        // $category->save();

        Category::create($validatedData); // Cela devrait fonctionner grâce à $fillable et HasTranslations

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     * Note: La méthode show() est souvent optionnelle pour les CRUD admin simples.
     * Si vous ne l'utilisez pas, vous pouvez la supprimer ainsi que sa route.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category')); // Vous devrez créer cette vue si vous voulez la garder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $locale = app()->getLocale();
        $validatedData = $request->validate([
            // Assurez-vous que la validation d'unicité ignore l'enregistrement actuel
            'name' => 'required|string|max:255|unique:categories,name->'.$locale.','.$category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug->'.$locale.','.$category->id,
        ]);

        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        } else {
            $validatedData['slug'] = Str::slug($validatedData['slug']);
        }

        // $category->setTranslation('name', $locale, $validatedData['name']);
        // $category->setTranslation('slug', $locale, $validatedData['slug']);
        // $category->save();
        $category->update($validatedData); // Met à jour pour la locale courante

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}