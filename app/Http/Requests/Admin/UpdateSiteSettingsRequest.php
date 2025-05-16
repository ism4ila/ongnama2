<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = config('app.available_locales', ['en', 'fr', 'ar']);
    }

    public function authorize(): bool
    {
        return true; // Autoriser l'administrateur
    }

    public function rules(): array
    {
        $rules = [
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_map_iframe_url' => 'nullable|url|max:1000',
            'social_facebook_url' => 'nullable|url|max:255',
            'social_twitter_url' => 'nullable|url|max:255',
            'social_instagram_url' => 'nullable|url|max:255',
            'social_linkedin_url' => 'nullable|url|max:255',
            'social_youtube_url' => 'nullable|url|max:255',
            'favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg,gif|max:1024', // 1MB max
            'remove_favicon' => 'nullable|boolean',
            'logo_path' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif|max:2048', // 2MB max
            'remove_logo_path' => 'nullable|boolean',
            'footer_logo_path' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif|max:2048', // 2MB max
            'remove_footer_logo_path' => 'nullable|boolean',
            'primary_color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'], // Validation format couleur hex
            'secondary_color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'accent_color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'default_direction' => 'nullable|in:ltr,rtl',
        ];

        $defaultLocale = config('app.fallback_locale', 'en');

        foreach ($this->availableLocales as $localeConfig) {
            $locale = is_array($localeConfig) ? $localeConfig['code'] : $localeConfig;
            // Site Name est requis au moins dans la langue par défaut
            if ($locale == $defaultLocale) {
                $rules['site_name.' . $locale] = 'required|string|max:255';
            } else {
                $rules['site_name.' . $locale] = 'nullable|string|max:255';
            }
            $rules['footer_description.' . $locale] = 'nullable|string|max:2000';
            $rules['contact_address.' . $locale] = 'nullable|string|max:500';
            $rules['footer_copyright_text.' . $locale] = 'nullable|string|max:255';
        }
        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [ /* ... Vos attributs personnalisés ... */ ];
        foreach ($this->availableLocales as $localeConfig) {
            $locale = is_array($localeConfig) ? $localeConfig['code'] : $localeConfig;
            $nativeName = is_array($localeConfig) ? $localeConfig['native'] : strtoupper($locale);
            $attributes['site_name.' . $locale] = __('Nom du site (:locale)', ['locale' => $nativeName]);
            // ...
        }
        return $attributes;
    }
}