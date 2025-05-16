<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\HomePageSetting;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Http\Requests\Admin\UpdateHomePageSettingsRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    protected $availableLocales;

    public function __construct()
    {
        // Récupérer les locales disponibles depuis la configuration
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

    private function getLanguageName($code)
    {
        $names = [
            'en' => 'English',
            'fr' => 'Français',
            'ar' => 'العربية',
        ];
        return $names[$code] ?? ucfirst($code);
    }

    /**
     * Affiche le formulaire d'édition des paramètres du site.
     */
    public function editSiteSettings()
    {
        $siteSettings = SiteSetting::instance();
        $locales = $this->availableLocales;
        return view('admin.settings.site_settings', compact('siteSettings', 'locales'));
    }

    /**
     * Met à jour les paramètres du site.
     */
    public function updateSiteSettings(UpdateSiteSettingsRequest $request)
    {
        $settings = SiteSetting::instance();
        $validatedData = $request->validated();

        // Champs traduisibles
        foreach ($this->availableLocales as $localeCode => $properties) {
            if (isset($validatedData['site_name'][$localeCode])) {
                $settings->setTranslation('site_name', $localeCode, $validatedData['site_name'][$localeCode]);
            }
            if (isset($validatedData['footer_description'][$localeCode])) {
                $settings->setTranslation('footer_description', $localeCode, $validatedData['footer_description'][$localeCode]);
            }
            if (isset($validatedData['contact_address'][$localeCode])) {
                $settings->setTranslation('contact_address', $localeCode, $validatedData['contact_address'][$localeCode]);
            }
            if (isset($validatedData['footer_copyright_text'][$localeCode])) {
                // Remplacer {year} dynamiquement
                $copyrightText = str_replace('{year}', date('Y'), $validatedData['footer_copyright_text'][$localeCode]);
                $settings->setTranslation('footer_copyright_text', $localeCode, $copyrightText);
            }
        }

        // Champs non traduisibles directs
        $directFields = [
            'contact_phone', 'contact_email', 'contact_map_iframe_url',
            'social_facebook_url', 'social_twitter_url', 'social_instagram_url',
            'social_linkedin_url', 'social_youtube_url',
            'primary_color', 'secondary_color', 'accent_color', 'default_direction'
        ];

        foreach ($directFields as $field) {
            if (isset($validatedData[$field])) {
                $settings->$field = $validatedData[$field];
            }
        }

        // Gestion des fichiers (favicon, logo_path, footer_logo_path)
        $fileFields = ['favicon', 'logo_path', 'footer_logo_path'];
        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                // Supprimer l'ancien fichier s'il existe
                if ($settings->$fileField) {
                    Storage::disk('public')->delete($settings->$fileField);
                }
                $path = $request->file($fileField)->store('settings', 'public');
                $settings->$fileField = $path;
            } elseif ($request->boolean('remove_' . $fileField)) {
                 if ($settings->$fileField) {
                    Storage::disk('public')->delete($settings->$fileField);
                    $settings->$fileField = null;
                }
            }
        }

        $settings->save();

        return redirect()->route('admin.settings.site.edit')->with('success', __('Paramètres du site mis à jour avec succès.'));
    }

    /**
     * Affiche le formulaire d'édition des paramètres de la page d'accueil.
     */
    public function editHomePageSettings()
    {
        $homePageSettings = HomePageSetting::instance();
        $locales = $this->availableLocales;
        return view('admin.settings.home_page_settings', compact('homePageSettings', 'locales'));
    }

    /**
     * Met à jour les paramètres de la page d'accueil.
     */
    public function updateHomePageSettings(UpdateHomePageSettingsRequest $request)
    {
        $settings = HomePageSetting::instance();
        $validatedData = $request->validated();

        // Champs traduisibles
        $translatableFields = [
            'hero_title', 'hero_subtitle', 'hero_button_text',
            'newsletter_title', 'newsletter_text',
            'latest_projects_title', 'latest_posts_title',
            'upcoming_events_title', 'partners_title'
        ];

        foreach ($this->availableLocales as $localeCode => $properties) {
            foreach ($translatableFields as $field) {
                if (isset($validatedData[$field][$localeCode])) {
                    $settings->setTranslation($field, $localeCode, $validatedData[$field][$localeCode]);
                }
            }
        }

        // Champs non traduisibles directs
        if (isset($validatedData['hero_button_link'])) {
            $settings->hero_button_link = $validatedData['hero_button_link'];
        }

        // Gestion de l'image de fond du héros
        if ($request->hasFile('hero_background_image')) {
            if ($settings->hero_background_image) {
                Storage::disk('public')->delete($settings->hero_background_image);
            }
            $path = $request->file('hero_background_image')->store('settings/homepage', 'public');
            $settings->hero_background_image = $path;
        } elseif ($request->boolean('remove_hero_background_image')) {
            if ($settings->hero_background_image) {
                Storage::disk('public')->delete($settings->hero_background_image);
                $settings->hero_background_image = null;
            }
        }
        $settings->save();

        return redirect()->route('admin.settings.homepage.edit')->with('success', __('Paramètres de la page d\'accueil mis à jour avec succès.'));
    }
}