<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Pour les règles d'unicité

class UpdateCategoryRequest extends FormRequest
{
    protected $availableLocales;

    public function __construct()
    {
        $this->availableLocales = config('app.available_locales', ['en', 'fr', 'ar']);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Pour l'instant, on autorise
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [];
        $defaultLocale = config('app.fallback_locale', 'en');
        $categoryId = $this->route('category')->id; // Récupère l'ID de la catégorie en cours d'édition

        foreach ($this->availableLocales as $locale) {
            if ($locale == $defaultLocale) {
                $rules['name.' . $locale] = 'required|string|max:255';
            } else {
                $rules['name.' . $locale] = 'nullable|string|max:255';
            }
            $rules['description.' . $locale] = 'nullable|string';

            // Exemple de validation d'unicité pour le slug (plus pertinent à l'update si le slug était modifiable via formulaire)
            // Comme le slug est généré à partir du nom dans le contrôleur,
            // une validation d'unicité sur le nom (ou une combinaison nom+autre chose) pourrait être plus directe.
            // La validation d'unicité sur un champ JSON est plus complexe.
            // $rules['slug.' . $locale] = [
            //     'nullable',
            //     'string',
            //     'max:255',
            //     Rule::unique('categories', "slug->{$locale}")->ignore($categoryId),
            // ];
            // Pour l'instant, nous laissons le contrôleur gérer la génération du slug.
            // La validation d'unicité des slugs peut être un point d'amélioration future si nécessaire.
        }
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $messages = [];
        foreach ($this->availableLocales as $locale) {
            $messages['name.' . $locale . '.required'] = __('Le nom de la catégorie en :locale est obligatoire.', ['locale' => strtoupper($locale)]);
            $messages['name.' . $locale . '.string'] = __('Le nom de la catégorie en :locale doit être une chaîne de caractères.', ['locale' => strtoupper($locale)]);
            $messages['name.' . $locale . '.max'] = __('Le nom de la catégorie en :locale ne doit pas dépasser 255 caractères.', ['locale' => strtoupper($locale)]);
            $messages['description.' . $locale . '.string'] = __('La description en :locale doit être une chaîne de caractères.', ['locale' => strtoupper($locale)]);
        }
        return $messages;
    }
}