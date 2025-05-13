<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => ['en' => 'NAMA Org', 'fr' => 'Organisation NAMA', 'ar' => 'منظمة نما'],
                // 'logo_path' => 'images/logos/nama_logo.png',
                // 'footer_logo_path' => 'images/logos/nama_logo_footer.png',
                // 'favicon' => 'images/logos/favicon.ico',
                'footer_description' => [
                    'en' => 'NAMA is dedicated to community development and sustainable impact.',
                    'fr' => 'NAMA se consacre au développement communautaire et à l\'impact durable.',
                    'ar' => 'نما مكرسة لتنمية المجتمع والتأثير المستدام.',
                ],
                'footer_copyright_text' => ['en' => 'All rights reserved.', 'fr' => 'Tous droits réservés.', 'ar' => 'كل الحقوق محفوظة.'],
                'contact_email' => 'info@nama-org.com',
                'contact_phone' => '+237 123 456 789',
                'contact_address' => ['en' => '123 Hope Street, Yaoundé, Cameroon', 'fr' => '123 Rue de l\'Espoir, Yaoundé, Cameroun', 'ar' => '123 شارع الأمل، ياوندي، الكاميرون'],
                'social_facebook_url' => 'https://facebook.com/namaorg',
                'social_twitter_url' => 'https://twitter.com/namaorg',
                'social_instagram_url' => 'https://instagram.com/namaorg',
                'social_linkedin_url' => null,
                'social_youtube_url' => null,
                'primary_color' => '#26A69A',   // Vert Turquoise
                'secondary_color' => '#00796B', // Vert Foncé
                'accent_color' => '#80CBC4',    // Vert Clair Accent
                // 'contact_map_iframe_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d....',
                'default_direction' => 'ltr', // ou 'rtl' si la langue par défaut l'exige
            ]
        );
    }
}