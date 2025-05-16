<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomePageSettingsRequest extends FormRequest
{
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = config('app.available_locales', ['en', 'fr', 'ar']);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'hero_button_link' => 'nullable|string|max:255', // Peut être une URL ou un slug de page
            'hero_background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096', // 4MB max
            'remove_hero_background_image' => 'nullable|boolean',
        ];

        $translatableFields = [
            'hero_title' => 'string|max:255',
            'hero_subtitle' => 'string|max:500',
            'hero_button_text' => 'string|max:100',
            'newsletter_title' => 'string|max:255',
            'newsletter_text' => 'string|max:500',
            'latest_projects_title' => 'string|max:255',
            'latest_posts_title' => 'string|max:255',
            'upcoming_events_title' => 'string|max:255',
            'partners_title' => 'string|max:255',
        ];
        
        $defaultLocale = config('app.fallback_locale', 'en');

        foreach ($this->availableLocales as $localeConfig) {
            $locale = is_array($localeConfig) ? $localeConfig['code'] : $localeConfig;
            foreach ($translatableFields as $field => $validation) {
                // Pour les titres principaux, on peut les rendre obligatoires dans la langue par défaut
                if ($locale == $defaultLocale && in_array($field, ['hero_title'])) {
                     $rules[$field . '.' . $locale] = 'required|' . $validation;
                } else {
                     $rules[$field . '.' . $locale] = 'nullable|' . $validation;
                }
            }
        }
        return $rules;
    }
     public function attributes(): array
    {
        $attributes = [ /* ... Vos attributs personnalisés ... */ ];
        // ...
        return $attributes;
    }
}