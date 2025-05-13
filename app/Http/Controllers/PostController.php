<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\User; // Si tu veux lister les auteurs par exemple
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Pour la gestion des fichiers

class PostController extends Controller
{
    protected $supportedLocales = ['en', 'fr', 'ar'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('category', 'user')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name]; // Nom dans la langue actuelle
        });
        // Tu pourrais aussi vouloir récupérer les traductions pour les afficher
        // $categories = Category::all();
        return view('admin.posts.create', [
            'categories' => $categories,
            'statuses' => ['draft' => 'Draft', 'published' => 'Published', 'pending' => 'Pending'], // Tu peux traduire ces clés
            'supportedLocales' => $this->supportedLocales
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,pending',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];

        foreach ($this->supportedLocales as $locale) {
            $rules["title.{$locale}"] = 'required|string|max:255';
            // Le slug sera généré, mais si tu as un champ slug dans le formulaire, valide-le :
            $rules["slug.{$locale}"] = [
                'nullable', // Permet de le laisser vide pour génération automatique
                'string',
                'max:255',
                Rule::unique('posts', "slug->{$locale}") // Unique par langue
            ];
            $rules["body.{$locale}"] = 'required|string';
            $rules["excerpt.{$locale}"] = 'nullable|string|max:500';
        }

        $validatedData = $request->validate($rules);

        $postData = [
            'user_id' => Auth::id(),
            'category_id' => $validatedData['category_id'],
            'status' => $validatedData['status'],
            'published_at' => $validatedData['published_at'] ?? null,
        ];

        // Gestion de l'image à la une
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts/featured_images', 'public');
            $postData['featured_image'] = $path;
        }

        // Préparation des données traduisibles
        foreach ($this->supportedLocales as $locale) {
            $postData['title'][$locale] = $validatedData['title'][$locale];
            // Générer le slug à partir du titre si le champ slug est vide pour cette langue
            $postData['slug'][$locale] = !empty($validatedData['slug'][$locale]) 
                                        ? Str::slug($validatedData['slug'][$locale]) 
                                        : Str::slug($validatedData['title'][$locale]);
            $postData['body'][$locale] = $validatedData['body'][$locale];
            $postData['excerpt'][$locale] = $validatedData['excerpt'][$locale] ?? null;
        }
        
        Post::create($postData);

        return redirect()->route('admin.posts.index')
                         ->with('success', __('Post created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name];
        });
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $categories,
            'statuses' => ['draft' => 'Draft', 'published' => 'Published', 'pending' => 'Pending'],
            'supportedLocales' => $this->supportedLocales
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,pending',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];

        foreach ($this->supportedLocales as $locale) {
            $rules["title.{$locale}"] = 'required|string|max:255';
            $rules["slug.{$locale}"] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', "slug->{$locale}")->ignore($post->id)
            ];
            $rules["body.{$locale}"] = 'required|string';
            $rules["excerpt.{$locale}"] = 'nullable|string|max:500';
        }

        $validatedData = $request->validate($rules);

        $updateData = [
            'category_id' => $validatedData['category_id'],
            'status' => $validatedData['status'],
            'published_at' => $validatedData['published_at'] ?? null,
        ];
        // Note: L'user_id n'est généralement pas modifié lors d'une mise à jour,
        // ou alors il faudrait une logique spécifique pour le permettre.

        // Gestion de la nouvelle image à la une
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $path = $request->file('featured_image')->store('posts/featured_images', 'public');
            $updateData['featured_image'] = $path;
        } elseif ($request->input('remove_featured_image')) {
             if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $updateData['featured_image'] = null;
        }


        foreach ($this->supportedLocales as $locale) {
            $updateData['title'][$locale] = $validatedData['title'][$locale];
            $updateData['slug'][$locale] = !empty($validatedData['slug'][$locale]) 
                                        ? Str::slug($validatedData['slug'][$locale]) 
                                        : Str::slug($validatedData['title'][$locale]);
            $updateData['body'][$locale] = $validatedData['body'][$locale];
            $updateData['excerpt'][$locale] = $validatedData['excerpt'][$locale] ?? null;
        }

        $post->update($updateData);

        return redirect()->route('admin.posts.index')
                         ->with('success', __('Post updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')
                         ->with('success', __('Post deleted successfully.'));
    }
}