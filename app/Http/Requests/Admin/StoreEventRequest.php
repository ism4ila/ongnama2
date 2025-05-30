<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = array_keys(config('app.available_locales', ['en', 'fr', 'ar']));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        $defaultLocale = config('app.fallback_locale', 'en');

        foreach ($this->availableLocales as $locale) {
            if ($locale == $defaultLocale) {
                $rules['title.' . $locale] = 'required|string|max:255';
                $rules['description.' . $locale] = 'required|string';
            } else {
                $rules['title.' . $locale] = 'nullable|string|max:255';
                $rules['description.' . $locale] = 'nullable|string';
            }
            $rules['location_text.' . $locale] = 'nullable|string|max:500';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'start_datetime.required' => __('La date de début est obligatoire.'),
            'start_datetime.date' => __('La date de début doit être une date valide.'),
            'end_datetime.date' => __('La date de fin doit être une date valide.'),
            'end_datetime.after' => __('La date de fin doit être postérieure à la date de début.'),
            'featured_image.image' => __('Le fichier doit être une image.'),
            'featured_image.max' => __('L\'image ne doit pas dépasser 5MB.'),
        ];

        foreach ($this->availableLocales as $locale) {
            $messages['title.' . $locale . '.required'] = __('Le titre en :locale est obligatoire.', ['locale' => strtoupper($locale)]);
            $messages['title.' . $locale . '.max'] = __('Le titre en :locale ne doit pas dépasser 255 caractères.', ['locale' => strtoupper($locale)]);
            $messages['description.' . $locale . '.required'] = __('La description en :locale est obligatoire.', ['locale' => strtoupper($locale)]);
        }

        return $messages;
    }
}