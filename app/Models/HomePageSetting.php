<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomePageSetting extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = [
        'hero_title', 
        'hero_subtitle', 
        'hero_button_text',
        'newsletter_title',
        'newsletter_text',
        'latest_projects_title',
        'latest_posts_title',
        'upcoming_events_title',
        'partners_title',
    ];

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
        'hero_button_link',
        'hero_background_image', // Chemin relatif à public/storage/
        'newsletter_title',
        'newsletter_text',
        'latest_projects_title',
        'latest_posts_title',
        'upcoming_events_title',
        'partners_title',
    ];

    // Données par défaut enrichies
    protected static $defaultSettings = [
        'hero_title' => [
            'fr' => 'Bienvenue à l\'Organisation NAMA', 
            'en' => 'Welcome to NAMA Organization', 
            'ar' => 'مرحباً بكم في منظمة نما للتنمية'
        ],
        'hero_subtitle' => [
            'fr' => 'Ensemble, cultivons l\'espoir et bâtissons un avenir durable pour tous.', 
            'en' => 'Together, let\'s cultivate hope and build a sustainable future for all.', 
            'ar' => 'معًا، نزرع الأمل ونبني مستقبلًا مستدامًا للجميع.'
        ],
        'hero_button_text' => [
            'fr' => 'Découvrez Nos Actions', 
            'en' => 'Discover Our Actions', 
            'ar' => 'اكتشفوا أعمالنا'
        ],
        'hero_button_link' => '/projects', // Ou un nom de route: 'frontend.projects.index'
        'hero_background_image' => 'seeders/images/hero_background.jpg', // Exemple de chemin

        'newsletter_title' => [
            'fr' => 'Restez Connecté à Notre Mission', 
            'en' => 'Stay Connected to Our Mission', 
            'ar' => 'ابقوا على تواصل مع رسالتنا'
        ],
        'newsletter_text' => [
            'fr' => 'Abonnez-vous à notre bulletin d\'information pour recevoir les dernières actualités sur nos projets et événements.', 
            'en' => 'Subscribe to our newsletter to receive the latest updates on our projects and events.', 
            'ar' => 'اشتركوا في نشرتنا الإخبارية لتلقي آخر التحديثات حول مشاريعنا وفعالياتنا.'
        ],
        'latest_projects_title' => [
            'fr' => 'Nos Projets Récents', 
            'en' => 'Our Recent Projects', 
            'ar' => 'أحدث مشاريعنا التنموية'
        ],
        'latest_posts_title' => [
            'fr' => 'Actualités de NAMA', 
            'en' => 'NAMA News & Updates', 
            'ar' => 'أخبار و مستجدات نما'
        ],
        'upcoming_events_title' => [
            'fr' => 'Nos Événements à Venir', 
            'en' => 'Our Upcoming Events', 
            'ar' => 'فعالياتنا القادمة'
        ],
        'partners_title' => [
            'fr' => 'Nos Précieux Partenaires', 
            'en' => 'Our Valued Partners', 
            'ar' => 'شركاؤنا الكرام'
        ],
    ];
    
    public static function instance()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create(self::$defaultSettings);
        } else {
            // Optionnel: Mettre à jour avec les clés manquantes si le modèle $defaultSettings a été étendu
            // $settings->fill(array_diff_key(self::$defaultSettings, $settings->getAttributes()));
            // $settings->save();
        }
        return $settings;
    }
}