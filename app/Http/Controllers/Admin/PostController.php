<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User; // Si vous voulez lister les auteurs
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StorePostRequest; // À créer
use App\Http\Requests\Admin\UpdatePostRequest; // À créer

class PostController extends Controller
{
    protected $availableLocales;

    // Dans app/Http/Controllers/Admin/PostController.php
    public function __construct()
    {
        $availableLocalesConfig = config('app.available_locales', ['en', 'fr', 'ar']);
        $fallbackLocale = config('app.fallback_locale');
        $formattedLocales = [];

        foreach ($availableLocalesConfig as $localeItem) {
            $currentLocaleCode = is_array($localeItem) ? $localeItem['code'] : $localeItem;
            $nativeName = is_array($localeItem) && isset($localeItem['native']) ? $localeItem['native'] : $this->getLanguageName($currentLocaleCode);

            $formattedLocales[$currentLocaleCode] = [
                'native' => $nativeName,
                'is_fallback' => ($currentLocaleCode === $fallbackLocale) // Ajout de cette clé
            ];
        }
        $this->availableLocales = $formattedLocales;
    }

    // La fonction getLanguageName reste la même
    private function getLanguageName($code)
    {
        $names = [
            'en' => 'English',
            'fr' => 'Français',
            'ar' => 'العربية',
        ];
        return $names[$code] ?? ucfirst($code);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(15); // Charger les relations pour l'affichage
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locales = $this->availableLocales;
        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name]; // Pour un select, retourne 'id' => 'nom traduit'
        });
        // Optionnel: lister les utilisateurs administrateurs comme auteurs potentiels
        // $authors = User::where('is_admin', true)->get()->pluck('name', 'id');
        $statuses = ['published' => __('Publié'), 'draft' => __('Brouillon'), 'pending' => __('En attente')];

        return view('admin.posts.create', compact('locales', 'categories', 'statuses' /*, 'authors'*/));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
        $post = new Post();

        // Assigner l'auteur (utilisateur actuellement connecté)
        $post->user_id = auth()->id();
        $post->category_id = $validatedData['category_id'];
        $post->status = $validatedData['status'];

        if ($validatedData['status'] === 'published' && empty($validatedData['published_at'])) {
            $post->published_at = now();
        } elseif (!empty($validatedData['published_at'])) {
            $post->published_at = $validatedData['published_at'];
        } else {
            $post->published_at = null;
        }


        // Gestion de l'image mise en avant
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts/featured_images', 'public');
            $post->featured_image = $path;
        }

        // Gestion des champs traduisibles
        foreach ($this->availableLocales as $localeCode => $properties) {
            if (isset($validatedData['title'][$localeCode]) && !empty($validatedData['title'][$localeCode])) {
                $post->setTranslation('title', $localeCode, $validatedData['title'][$localeCode]);
                // Générer le slug à partir du titre pour cette locale
                $post->setTranslation('slug', $localeCode, Str::slug($validatedData['title'][$localeCode]));
            }
            if (isset($validatedData['body'][$localeCode])) {
                $post->setTranslation('body', $localeCode, $validatedData['body'][$localeCode]);
            }
            if (isset($validatedData['excerpt'][$localeCode])) {
                $post->setTranslation('excerpt', $localeCode, $validatedData['excerpt'][$localeCode]);
            }
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', __('Article créé avec succès.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Généralement, pour l'admin, on redirige vers edit ou index
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $locales = $this->availableLocales;
        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name];
        });
        $statuses = ['published' => __('Publié'), 'draft' => __('Brouillon'), 'pending' => __('En attente')];
        // $authors = User::where('is_admin', true)->get()->pluck('name', 'id');

        return view('admin.posts.edit', compact('post', 'locales', 'categories', 'statuses' /*, 'authors'*/));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $post->category_id = $validatedData['category_id'];
        $post->status = $validatedData['status'];

        if ($validatedData['status'] === 'published' && empty($validatedData['published_at']) && empty($post->published_at)) {
            $post->published_at = now();
        } elseif (!empty($validatedData['published_at'])) {
            $post->published_at = $validatedData['published_at'];
        } elseif ($validatedData['status'] !== 'published') {
            $post->published_at = null;
        }
        // Ne pas écraser published_at si l'article est déjà publié et qu'aucune nouvelle date n'est fournie


        // Gestion de la mise à jour de l'image mise en avant
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $path = $request->file('featured_image')->store('posts/featured_images', 'public');
            $post->featured_image = $path;
        } elseif ($request->boolean('remove_featured_image')) {
            // Supprimer l'image si la case est cochée
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
                $post->featured_image = null;
            }
        }


        // Gestion des champs traduisibles
        foreach ($this->availableLocales as $localeCode => $properties) {
            if (isset($validatedData['title'][$localeCode]) && !empty($validatedData['title'][$localeCode])) {
                $post->setTranslation('title', $localeCode, $validatedData['title'][$localeCode]);
                // Mettre à jour le slug si le nom a changé pour cette locale
                $post->setTranslation('slug', $localeCode, Str::slug($validatedData['title'][$localeCode]));
            }
            // Permettre de vider un champ traduit
            if (array_key_exists('body', $validatedData) && array_key_exists($localeCode, $validatedData['body'])) {
                $post->setTranslation('body', $localeCode, $validatedData['body'][$localeCode]);
            }
            if (array_key_exists('excerpt', $validatedData) && array_key_exists($localeCode, $validatedData['excerpt'])) {
                $post->setTranslation('excerpt', $localeCode, $validatedData['excerpt'][$localeCode]);
            }
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', __('Article mis à jour avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Supprimer l'image associée si elle existe
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', __('Article supprimé avec succès.'));
    }
}
