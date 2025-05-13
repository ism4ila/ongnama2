<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::upcoming()
                               // ->where('status', 'published')
                               ->paginate(6, ['*'], 'upcoming_page');

        $pastEvents = Event::past()
                           // ->where('status', 'published')
                           ->paginate(6, ['*'], 'past_page');

        return view('frontend.events.index', compact('upcomingEvents', 'pastEvents'));
    }

    /**
     * Affiche les détails d'un événement spécifique par son slug.
     *
     * @param string $event_slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show($event_slug) // Modifié pour accepter un seul paramètre event_slug
    {
        $event = Event::where("slug->".app()->getLocale(), $event_slug)
                      ->orWhere("slug->".config('app.fallback_locale'), $event_slug)
                      // ->where('status', 'published')
                      ->firstOrFail();

        // Redirection SEO si le slug de l'URL n'est pas celui de la langue actuelle
        $currentLocaleSlug = $event->getTranslation('slug', app()->getLocale());
        if ($event_slug !== $currentLocaleSlug && $currentLocaleSlug) {
            return redirect()->route('frontend.events.show', [
                'locale' => app()->getLocale(),
                'event_slug' => $currentLocaleSlug
            ], 301);
        }

        $otherEvents = Event::upcoming()
                            ->where('id', '!=', $event->id)
                            // ->where('status', 'published')
                            ->take(4)
                            ->get();

        return view('frontend.events.show', compact('event', 'otherEvents'));
    }
}