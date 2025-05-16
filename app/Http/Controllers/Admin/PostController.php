<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
// use App\Models\User; // Inclus mais non utilisé directement pour $authors dans create/edit
use Illuminate\Http\Request; // Nécessaire pour le type hinting de handleFeaturedImageUpload
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;

class PostController extends Controller
{
    protected array $availableLocales;

    public function __construct()
    {
        // La configuration des locales reste la même, elle est claire et remplit son rôle.
        // On pourrait envisager un service si cette logique est partagée par de nombreux contrôleurs.
        $availableLocalesConfig = config('app.available_locales', ['en', 'fr', 'ar']);
        $fallbackLocale = config('app.fallback_locale');
        $formattedLocales = [];

        foreach ($availableLocalesConfig as $localeItem) {
            $currentLocaleCode = is_array($localeItem) ? $localeItem['code'] : $localeItem;
            $nativeName = is_array($localeItem) && isset($localeItem['native']) ? $localeItem['native'] : $this->getLanguageName($currentLocaleCode);

            $formattedLocales[$currentLocaleCode] = [
                'native' => $nativeName,
                'is_fallback' => ($currentLocaleCode === $fallbackLocale)
            ];
        }
        $this->availableLocales = $formattedLocales;
    }

    private function getLanguageName(string $code): string
    {
        $names = [
            'en' => 'English',
            'fr' => 'Français',
            'ar' => 'العربية',
        ];
        return $names[$code] ?? ucfirst($code);
    }

    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create', $this->getFormData());
    }

    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();
        $post = new Post();

        $post->user_id = auth()->id();
        $post->category_id = $validatedData['category_id'];
        $post->status = $validatedData['status'];
        $this->setPublishedAt($post, $validatedData);

        $this->handleTranslations($post, $validatedData);
        $this->handleFeaturedImageUpload($request, $post);

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', __('Article créé avec succès.'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', $this->getFormData($post));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $post->category_id = $validatedData['category_id'];
        $post->status = $validatedData['status'];
        $this->setPublishedAt($post, $validatedData, true); // true pour indiquer une mise à jour

        $this->handleTranslations($post, $validatedData);
        $this->handleFeaturedImageUpload($request, $post, true); // true pour indiquer une mise à jour

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', __('Article mis à jour avec succès.'));
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', __('Article supprimé avec succès.'));
    }

    /**
     * Prépare les données communes pour les formulaires de création et d'édition.
     */
    private function getFormData(Post $post = null): array
    {
        $categories = Category::all()->mapWithKeys(function ($category) {
            // Assumant que le modèle Category utilise aussi HasTranslations pour 'name'
            return [$category->id => $category->name];
        });
        $statuses = ['published' => __('Publié'), 'draft' => __('Brouillon'), 'pending' => __('En attente')];

        $data = [
            'locales' => $this->availableLocales,
            'categories' => $categories,
            'statuses' => $statuses,
        ];

        if ($post) {
            $data['post'] = $post;
        }

        return $data;
    }

    /**
     * Gère la logique de la date de publication.
     */
    private function setPublishedAt(Post $post, array $validatedData, bool $isUpdate = false): void
    {
        $newPublishedAt = $validatedData['published_at'] ?? null; // Peut être null si non fourni ou vide

        if ($validatedData['status'] === 'published') {
            if (!empty($newPublishedAt)) {
                $post->published_at = $newPublishedAt;
            } elseif (!$isUpdate || ($isUpdate && empty($post->published_at))) {
                // Publier maintenant si c'est une création ou si c'est une MàJ et qu'il n'y avait pas de date avant
                $post->published_at = now();
            }
            // Si $isUpdate est true et $post->published_at existe déjà et $newPublishedAt est vide, on ne touche à rien.
        } else {
            $post->published_at = null; // Statut non publié, donc pas de date de publication.
        }
    }

    /**
     * Gère l'enregistrement des champs traduisibles.
     */
    private function handleTranslations(Post $post, array $validatedData): void
    {
        foreach ($this->availableLocales as $localeCode => $properties) {
            // Titre et Slug (le slug est généré à partir du titre)
            if (isset($validatedData['title'][$localeCode]) && !empty($validatedData['title'][$localeCode])) {
                $title = $validatedData['title'][$localeCode];
                $post->setTranslation('title', $localeCode, $title);
                // Toujours mettre à jour le slug si le titre pour cette langue est fourni
                $post->setTranslation('slug', $localeCode, Str::slug($title));
            } elseif (isset($validatedData['title'][$localeCode]) && empty($validatedData['title'][$localeCode]) && !$properties['is_fallback']) {
                // Permet de vider un titre non fallback si une chaîne vide est explicitement passée
                 $post->setTranslation('title', $localeCode, null);
                 $post->setTranslation('slug', $localeCode, null);
            }


            // Corps de l'article
            if (array_key_exists('body', $validatedData) && array_key_exists($localeCode, $validatedData['body'])) {
                $post->setTranslation('body', $localeCode, $validatedData['body'][$localeCode]);
            }

            // Extrait
            if (array_key_exists('excerpt', $validatedData) && array_key_exists($localeCode, $validatedData['excerpt'])) {
                $post->setTranslation('excerpt', $localeCode, $validatedData['excerpt'][$localeCode]);
            }
        }
    }

    /**
     * Gère le téléversement et la suppression de l'image mise en avant.
     */
    private function handleFeaturedImageUpload(Request $request, Post $post, bool $isUpdate = false): void
    {
        // Si la case "supprimer l'image" est cochée (uniquement en mode MàJ)
        if ($isUpdate && $request->boolean('remove_featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
                $post->featured_image = null;
            }
            return; // On ne traite pas un nouveau fichier si la suppression est demandée.
        }

        // S'il y a un nouveau fichier image
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image si elle existe (en mode MàJ)
            if ($isUpdate && $post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $path = $request->file('featured_image')->store('posts/featured_images', 'public');
            $post->featured_image = $path;
        }
        // Si aucun nouveau fichier n'est uploadé et que la case "supprimer" n'est pas cochée, on ne touche pas à l'image existante.
    }

    // La méthode `show` peut être omise si vous n'avez pas de vue "show" distincte dans l'admin.
    // Si vous la gardez, la redirection vers edit est une pratique courante.
    // public function show(Post $post)
    // {
    //     return redirect()->route('admin.posts.edit', $post);
    // }
}