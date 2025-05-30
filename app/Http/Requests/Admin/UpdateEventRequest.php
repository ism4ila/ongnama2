<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'remove_featured_image' => 'nullable|boolean',
        ];

        foreach ($this->availableLocales as $locale) {
            $rules['title.' . $locale] = 'nullable|string|max:255';
            $rules['description.' . $locale] = 'nullable|string';
            $rules['location_text.' . $locale] = 'nullable|string|max:500';
        }

        // Au moins un titre est requis
        $rules['title'] = ['required', function ($attribute, $value, $fail) {
            $hasTitle = false;
            foreach ($this->availableLocales as $locale) {
                if (!empty($this->input('title.' . $locale))) {
                    $hasTitle = true;
                    break;
                }
            }
            if (!$hasTitle) {
                $fail(__('Au moins un titre dans une langue est requis.'));
            }
        }];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'start_datetime.required' => __('La date de début est obligatoire.'),
            'start_datetime.date' => __('La date de début doit être une date valide.'),
            'end_datetime.date' => __('La date de fin doit être une date valide.'),
            'end_datetime.after' => __('La date de fin doit être postérieure à la date de début.'),
            'featured_image.image' => __('Le fichier doit être une image.'),
            'featured_image.max' => __('L\'image ne doit pas dépasser 5MB.'),
        ];
    }
}