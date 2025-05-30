<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;

class EventController extends Controller
{
    protected array $availableLocales;

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
        $events = Event::latest('start_datetime')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create', $this->getFormData());
    }

    public function store(StoreEventRequest $request)
    {
        $validatedData = $request->validated();
        $event = new Event();

        $this->setEventData($event, $validatedData);
        $this->handleTranslations($event, $validatedData);
        $this->handleImageUpload($request, $event);

        $event->save();

        return redirect()->route('admin.events.index')->with('success', __('Événement créé avec succès.'));
    }

    public function show(Event $event)
    {
        return redirect()->route('admin.events.edit', $event);
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', $this->getFormData($event));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validatedData = $request->validated();

        $this->setEventData($event, $validatedData);
        $this->handleTranslations($event, $validatedData);
        $this->handleImageUpload($request, $event, true);

        $event->save();

        return redirect()->route('admin.events.index')->with('success', __('Événement mis à jour avec succès.'));
    }

    public function destroy(Event $event)
    {
        if ($event->featured_image_url) {
            $imagePath = public_path($event->featured_image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', __('Événement supprimé avec succès.'));
    }

    private function getFormData(Event $event = null): array
    {
        $data = [
            'locales' => $this->availableLocales,
        ];

        if ($event) {
            $data['event'] = $event;
        }

        return $data;
    }

    private function setEventData(Event $event, array $validatedData): void
    {
        $event->start_datetime = $validatedData['start_datetime'];
        $event->end_datetime = $validatedData['end_datetime'] ?? null;
    }

    private function handleTranslations(Event $event, array $validatedData): void
    {
        foreach ($this->availableLocales as $localeCode => $properties) {
            // Title and Slug
            if (isset($validatedData['title'][$localeCode]) && !empty($validatedData['title'][$localeCode])) {
                $title = $validatedData['title'][$localeCode];
                $event->setTranslation('title', $localeCode, $title);
                $event->setTranslation('slug', $localeCode, Str::slug($title));
            } elseif (isset($validatedData['title'][$localeCode]) && empty($validatedData['title'][$localeCode]) && !$properties['is_fallback']) {
                $event->setTranslation('title', $localeCode, null);
                $event->setTranslation('slug', $localeCode, null);
            }

            // Description
            if (array_key_exists('description', $validatedData) && array_key_exists($localeCode, $validatedData['description'])) {
                $event->setTranslation('description', $localeCode, $validatedData['description'][$localeCode]);
            }

            // Location Text
            if (array_key_exists('location_text', $validatedData) && array_key_exists($localeCode, $validatedData['location_text'])) {
                $event->setTranslation('location_text', $localeCode, $validatedData['location_text'][$localeCode]);
            }
        }
    }

    private function handleImageUpload(Request $request, Event $event, bool $isUpdate = false): void
    {
        if ($isUpdate && $request->boolean('remove_featured_image')) {
            if ($event->featured_image_url) {
                $imagePath = public_path($event->featured_image_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $event->featured_image_url = null;
            }
            return;
        }

        if ($request->hasFile('featured_image')) {
            // Create directory if it doesn't exist
            $uploadPath = public_path('images/events');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Remove old image if updating
            if ($isUpdate && $event->featured_image_url) {
                $oldImagePath = public_path($event->featured_image_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('featured_image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            
            $event->featured_image_url = 'images/events/' . $filename;
        }
    }
}