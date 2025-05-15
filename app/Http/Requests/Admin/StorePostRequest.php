<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change to true to allow the request to proceed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locales = config('app.available_locales', ['en', 'fr', 'ar']);
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', Rule::in(['published', 'draft', 'pending'])],
            'published_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'remove_featured_image' => ['nullable', 'boolean'],
        ];

        // At least title in one language is required (usually primary language)
        $primaryLocale = array_key_first($locales);
        $rules["title.$primaryLocale"] = ['required', 'string', 'max:255'];
        $rules["body.$primaryLocale"] = ['required', 'string'];

        // Validate other locales if provided
        foreach ($locales as $localeCode => $properties) {
            if ($localeCode !== $primaryLocale) {
                $rules["title.$localeCode"] = ['nullable', 'string', 'max:255'];
                $rules["body.$localeCode"] = ['nullable', 'string'];
            }
            $rules["excerpt.$localeCode"] = ['nullable', 'string'];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $locales = config('app.available_locales', ['en', 'fr', 'ar']);
        $attributes = [
            'category_id' => __('catégorie'),
            'status' => __('statut'),
            'published_at' => __('date de publication'),
            'featured_image' => __('image mise en avant'),
        ];

        foreach ($locales as $localeCode => $properties) {
            $attributes["title.$localeCode"] = __('titre (:locale)', ['locale' => strtoupper($localeCode)]);
            $attributes["body.$localeCode"] = __('corps de l\'article (:locale)', ['locale' => strtoupper($localeCode)]);
            $attributes["excerpt.$localeCode"] = __('extrait (:locale)', ['locale' => strtoupper($localeCode)]);
        }

        return $attributes;
    }
}