<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = [
        'site_name',
        'footer_description', 
        'contact_address', 
        'footer_copyright_text'
        // Ajoutez d'autres champs traduisibles si nécessaire (ex: slogan)
    ];

    protected $fillable = [
        'site_name',
        'footer_description',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_map_iframe_url',
        'social_facebook_url',
        'social_twitter_url',
        'social_instagram_url',
        'social_linkedin_url',
        'social_youtube_url',
        'footer_copyright_text',
        'favicon', // Chemin relatif à public/storage/
        'logo_path', // Chemin relatif à public/storage/
        'footer_logo_path', // Chemin relatif à public/storage/
        'primary_color',    // Exemple: #26A69A
        'secondary_color',  // Exemple: #00796B
        'accent_color',     // Exemple: #80CBC4
    ];

    // Données par défaut enrichies
    protected static $defaultSettings = [
        'site_name' => ['en' => 'NAMA Development', 'fr' => 'NAMA Développement', 'ar' => 'نما للتنمية'],
        'footer_description' => [
            'en' => 'NAMA is a non-profit organization dedicated to fostering sustainable development and empowering communities through innovative projects in education, health, and economic growth.', 
            'fr' => 'NAMA est une organisation à but non lucratif dédiée à la promotion du développement durable et à l\'autonomisation des communautés grâce à des projets innovants dans l\'éducation, la santé et la croissance économique.', 
            'ar' => 'نما هي منظمة غير ربحية مكرسة لتعزيز التنمية المستدامة وتمكين المجتمعات من خلال مشاريع مبتكرة في التعليم والصحة والنمو الاقتصادي.'
        ],
        'contact_address' => [
            'en' => '123 Development Ave, Hope City, Nationland', 
            'fr' => '123 Avenue du Développement, Cité de l\'Espoir, Nationland', 
            'ar' => '١٢٣ شارع التنمية، مدينة الأمل، دولة الأحلام'
        ],
        'contact_phone' => '+1 (555) 123-4567',
        'contact_email' => 'info@nama-dev.org',
        'contact_map_iframe_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3980.758368010596!2d11.51918561521961!3d3.866393997238855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x108bcfabc8a77891%3A0x8b052c81262555f5!2sYaound%C3%A9%2C%20Cameroon!5e0!3m2!1sen!2scm!4v1620750000000!5m2!1sen!2scm', // Exemple de lien Google Maps (Yaoundé)
        'social_facebook_url' => 'https://facebook.com/namadevorg',
        'social_twitter_url' => 'https://twitter.com/namadevorg',
        'social_instagram_url' => 'https://instagram.com/namadevorg',
        'social_linkedin_url' => 'https://linkedin.com/company/namadevorg',
        'social_youtube_url' => 'https://youtube.com/namadevorg',
        'footer_copyright_text' => [
            'en' => '© {year} NAMA Development. All Rights Reserved. Building a better tomorrow, today.', 
            'fr' => '© {year} NAMA Développement. Tous Droits Réservés. Construire un avenir meilleur, dès aujourd\'hui.', 
            'ar' => '© {year} نما للتنمية. جميع الحقوق محفوظة. نبني غدًا أفضل، اليوم.'
        ], // {year} sera remplacé dynamiquement
        'favicon' => 'seeders/images/favicon.png',
        'logo_path' => 'seeders/images/logo_nama_light.png', // Pour navbar avec fond clair
        'footer_logo_path' => 'seeders/images/logo_nama_dark.png', // Pour footer potentiellement sur fond sombre
        'primary_color' => '#26A69A',   // Vert turquoise
        'secondary_color' => '#00796B', // Vert plus foncé
        'accent_color' => '#80CBC4',    // Vert clair accent
    ];
    
    public static function instance()
    {
        $settings = self::first();
        if (!$settings) {
            $settingsData = self::$defaultSettings;
            // Remplacer {year} dans le copyright
            foreach ($settingsData['footer_copyright_text'] as $lang => $text) {
                $settingsData['footer_copyright_text'][$lang] = str_replace('{year}', date('Y'), $text);
            }
            $settings = self::create($settingsData);
        } else {
            // Mettre à jour le copyright pour l'année en cours si nécessaire
            $updated = false;
            foreach ($settings->getTranslations('footer_copyright_text') as $lang => $text) {
                if (strpos($text, '{year}') !== false) {
                    $settings->setTranslation('footer_copyright_text', $lang, str_replace('{year}', date('Y'), $text));
                    $updated = true;
                }
            }
            if($updated) $settings->save();
        }
        return $settings;
    }

    public function getDirection(?string $locale = null): string
    {
        $currentLocale = $locale ?? app()->getLocale();
        if (in_array($currentLocale, ['ar', 'he', 'fa', 'ur'])) {
            return 'rtl';
        }
        return 'ltr';
    }
}