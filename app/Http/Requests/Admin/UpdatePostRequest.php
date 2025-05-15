<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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

        // For update, we still require title in at least one language
        // We'll check for any non-empty title in any locale
        $titleRules = [];
        foreach ($locales as $localeCode => $properties) {
            $rules["title.$localeCode"] = ['nullable', 'string', 'max:255'];
            $rules["body.$localeCode"] = ['nullable', 'string'];
            $rules["excerpt.$localeCode"] = ['nullable', 'string'];
            $titleRules[] = "title.$localeCode";
        }

        // At least one title is required across all locales
        $rules["title"] = ['required', function ($attribute, $value, $fail) use ($titleRules) {
            $hasTitle = false;
            foreach ($titleRules as $titleField) {
                if (!empty(request($titleField))) {
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