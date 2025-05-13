<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
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
        // Mettez à true si vous gérez les autorisations via une autre méthode (ex: middleware, Policies)
        // ou ajoutez votre logique d'autorisation ici.
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
        $defaultLocale = config('app.fallback_locale', 'en'); // Langue par défaut pour les champs obligatoires

        foreach ($this->availableLocales as $locale) {
            // Le nom est obligatoire au moins dans la langue par défaut
            if ($locale == $defaultLocale) {
                $rules['name.' . $locale] = 'required|string|max:255';
            } else {
                $rules['name.' . $locale] = 'nullable|string|max:255';
            }
            // La description est toujours optionnelle
            $rules['description.' . $locale] = 'nullable|string';

            // Validation d'unicité pour les slugs (exemple)
            // Note: La génération du slug se fait dans le contrôleur.
            // Si vous voulez valider l'unicité des slugs ici, ce serait plus complexe
            // car les slugs ne sont pas directement soumis par le formulaire.
            // Pour l'instant, on se concentre sur la validation des noms et descriptions.
            // Une validation d'unicité plus poussée pour les slugs traduits se ferait mieux
            // avec une règle personnalisée ou directement dans le contrôleur avant de sauvegarder,
            // en vérifiant chaque slug généré.
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

    /**
     * Prepare the data for validation.
     *
     * Ici, nous ne modifions pas les données avant validation pour les slugs car ils sont générés dans le contrôleur.
     * Si vous aviez un champ slug dans le formulaire, vous pourriez le générer ici.
     */
    // protected function prepareForValidation()
    // {
    //     // Exemple si vous aviez des champs slug dans le formulaire:
    //     // $data = [];
    //     // foreach ($this->availableLocales as $locale) {
    //     //     if ($this->input("name.{$locale}")) {
    //     //         $data["slug"][$locale] = Str::slug($this->input("name.{$locale}"));
    //     //     }
    //     // }
    //     // $this->merge($data);
    // }
}