<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Pour générer les slugs
use App\Http\Requests\Admin\StoreCategoryRequest; // Nous allons créer ce fichier
use App\Http\Requests\Admin\UpdateCategoryRequest; // Nous allons créer ce fichier

class CategoryController extends Controller
{
    protected $availableLocales;

    public function __construct()
    {
        // Récupérer les locales disponibles depuis la configuration
        $this->availableLocales = config('app.available_locales', ['en', 'fr', 'ar']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10); // Récupère les catégories paginées
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locales = $this->availableLocales;
        return view('admin.categories.create', compact('locales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request) // Utilisation du FormRequest
    {
        $validatedData = $request->validated();
        $category = new Category();

        foreach ($this->availableLocales as $locale) {
            if (isset($validatedData['name'][$locale]) && !empty($validatedData['name'][$locale])) {
                $category->setTranslation('name', $locale, $validatedData['name'][$locale]);
                // Générer le slug à partir du nom pour cette locale
                $category->setTranslation('slug', $locale, Str::slug($validatedData['name'][$locale]));
            }
            if (isset($validatedData['description'][$locale])) {
                $category->setTranslation('description', $locale, $validatedData['description'][$locale]);
            }
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', __('Catégorie créée avec succès.'));
    }

    /**
     * Display the specified resource.
     * (Optionnel pour un CRUD admin, souvent on redirige vers edit ou index)
     */
    public function show(Category $category)
    {
        // Pour l'instant, nous n'utilisons pas la vue show, redirigeons vers edit
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $locales = $this->availableLocales;
        return view('admin.categories.edit', compact('category', 'locales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category) // Utilisation du FormRequest
    {
        $validatedData = $request->validated();

        foreach ($this->availableLocales as $locale) {
            if (isset($validatedData['name'][$locale]) && !empty($validatedData['name'][$locale])) {
                $category->setTranslation('name', $locale, $validatedData['name'][$locale]);
                // Mettre à jour le slug si le nom a changé pour cette locale
                 // Vous pourriez vouloir une logique plus complexe ici pour l'unicité des slugs
                $category->setTranslation('slug', $locale, Str::slug($validatedData['name'][$locale]));
            }
            if (array_key_exists('description', $validatedData) && array_key_exists($locale, $validatedData['description'])) {
                $category->setTranslation('description', $locale, $validatedData['description'][$locale]);
            } elseif (array_key_exists('description', $validatedData) && is_null($validatedData['description'][$locale])) {
                $category->setTranslation('description', $locale, null);
            }
        }
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', __('Catégorie mise à jour avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Ajouter une vérification si la catégorie est utilisée (par des articles par exemple)
        // if ($category->posts()->count() > 0) {
        //     return back()->with('error', __('Cette catégorie ne peut pas être supprimée car elle est associée à des articles.'));
        // }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __('Catégorie supprimée avec succès.'));
    }
}