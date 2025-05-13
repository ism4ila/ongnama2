<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event; // Assurez-vous que le modèle Event est correct
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Pour la génération de slugs
use Illuminate\Support\Facades\Storage; // Si vous gérez des uploads d'images

class EventController extends Controller
{
    protected $supportedLocales;

    public function __construct()
    {
        // Langues supportées pour les formulaires traduisibles
        $this->supportedLocales = ['fr', 'en', 'ar']; // Ou récupérez depuis config('app.supported_locales')
    }

    /**
     * Affiche la liste des événements.
     */
    public function index()
    {
        $events = Event::latest()->paginate(10); // Récupère les événements, 10 par page
        return view('admin.events.index', compact('events'));
    }

    /**
     * Affiche le formulaire de création d'un événement.
     */
    public function create()
    {
        $locales = $this->supportedLocales;
        return view('admin.events.create', compact('locales'));
    }

    /**
     * Enregistre un nouvel événement en base de données.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateEvent($request);

        $slugs = [];
        foreach ($this->supportedLocales as $locale) {
            if (!empty($validatedData['title'][$locale])) {
                $slugs[$locale] = Str::slug($validatedData['title'][$locale], '-', $locale);
            } else if ($locale === config('app.fallback_locale') && !empty($validatedData['title'][config('app.fallback_locale')])) {
                 // Générer le slug même si le titre pour une autre langue est vide, basé sur le fallback
                $slugs[$locale] = Str::slug($validatedData['title'][config('app.fallback_locale')], '-', $locale);
            }
        }
        $validatedData['slug'] = $slugs;
        
        // Gestion de l'upload d'image (exemple simple si featured_image_url stocke un chemin)
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('events/featured', 'public');
            $validatedData['featured_image_url'] = $path; // Stocke le chemin de l'image
        } elseif ($request->filled('featured_image_url_text')) {
             $validatedData['featured_image_url'] = $request->input('featured_image_url_text');
        }


        Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Événement créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un événement.
     */
    public function edit(Event $event) // Route Model Binding
    {
        $locales = $this->supportedLocales;
        return view('admin.events.edit', compact('event', 'locales'));
    }

    /**
     * Met à jour un événement en base de données.
     */
    public function update(Request $request, Event $event)
    {
        $validatedData = $this->validateEvent($request, $event->id);
        
        $slugs = [];
        foreach ($this->supportedLocales as $locale) {
            if (!empty($validatedData['title'][$locale])) {
                $slugs[$locale] = Str::slug($validatedData['title'][$locale], '-', $locale);
            } else if ($locale === config('app.fallback_locale') && !empty($validatedData['title'][config('app.fallback_locale')])) {
                $slugs[$locale] = Str::slug($validatedData['title'][config('app.fallback_locale')], '-', $locale);
            } else {
                // Conserver l'ancien slug si le titre est vidé pour cette langue mais pas pour le fallback
                 $slugs[$locale] = $event->getTranslation('slug', $locale, false);
            }
        }
        $validatedData['slug'] = $slugs;

        // Gestion de l'upload d'image
        if ($request->hasFile('featured_image_file')) {
            // Optionnel: supprimer l'ancienne image si elle existe
            if ($event->featured_image_url && strpos($event->featured_image_url, 'http') !== 0) { // Ne pas supprimer les URL externes
                Storage::disk('public')->delete($event->featured_image_url);
            }
            $path = $request->file('featured_image_file')->store('events/featured', 'public');
            $validatedData['featured_image_url'] = $path;
        } elseif ($request->filled('featured_image_url_text')) {
             $validatedData['featured_image_url'] = $request->input('featured_image_url_text');
        } else if ($request->boolean('remove_featured_image')) {
            if ($event->featured_image_url && strpos($event->featured_image_url, 'http') !== 0) {
                Storage::disk('public')->delete($event->featured_image_url);
            }
            $validatedData['featured_image_url'] = null;
        }


        $event->update($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Supprime un événement de la base de données.
     */
    public function destroy(Event $event)
    {
        // Optionnel: supprimer l'image associée si elle est stockée localement
        if ($event->featured_image_url && strpos($event->featured_image_url, 'http') !== 0) {
            Storage::disk('public')->delete($event->featured_image_url);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * Règles de validation communes pour store et update.
     */
    protected function validateEvent(Request $request, $eventId = null)
    {
        $rules = [
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Pour l'upload
            'featured_image_url_text' => 'nullable|url', // Pour une URL directe
            // 'status' => 'required|in:draft,published,cancelled', // Si vous ajoutez un champ statut
        ];

        // Validation pour les champs traduisibles
        foreach ($this->supportedLocales as $locale) {
            $rules["title.$locale"] = ($locale == config('app.fallback_locale') ? 'required|string|max:255' : 'nullable|string|max:255');
            // Le slug sera généré, donc pas besoin de le valider ici directement
            // $rules["slug.$locale"] = 'nullable|string|max:255'; // Si vous voulez permettre la saisie manuelle des slugs
            $rules["description.$locale"] = 'nullable|string';
            $rules["location_text.$locale"] = 'nullable|string|max:255';
        }

        return $request->validate($rules);
    }
}