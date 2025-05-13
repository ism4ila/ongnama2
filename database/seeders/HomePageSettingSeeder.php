<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageSetting;

class HomePageSettingSeeder extends Seeder
{
    public function run(): void
    {
        HomePageSetting::updateOrCreate(
            ['id' => 1], // Condition pour trouver l'enregistrement (ou le créer s'il n'existe pas)
            [
                'hero_title' => [
                    'en' => 'Welcome to NAMA Organization',
                    'fr' => 'Bienvenue à l\'Organisation NAMA',
                    'ar' => 'مرحباً بكم في منظمة نما',
                ],
                'hero_subtitle' => [
                    'en' => 'Working together for a brighter future, empowering communities and driving change.',
                    'fr' => 'Travailler ensemble pour un avenir meilleur, autonomiser les communautés et conduire le changement.',
                    'ar' => 'نعمل معًا من أجل مستقبل أكثر إشراقًا، وتمكين المجتمعات، وإحداث التغيير.',
                ],
                'hero_button_text' => [
                    'en' => 'Discover Our Projects',
                    'fr' => 'Découvrir Nos Projets',
                    'ar' => 'اكتشف مشاريعنا',
                ],
                'hero_button_link' => 'frontend.projects.index', // Nom de la route
                // 'hero_background_image' => 'images/hero/home_hero_bg.jpg', // Chemin relatif à public/storage/

                'latest_projects_title' => ['en' => 'Our Latest Projects', 'fr' => 'Nos Derniers Projets', 'ar' => 'أحدث مشاريعنا'],
                'latest_posts_title' => ['en' => 'Recent News & Updates', 'fr' => 'Actualités et Mises à Jour Récentes', 'ar' => 'آخر الأخبار والتحديثات'],
                'upcoming_events_title' => ['en' => 'Upcoming Events', 'fr' => 'Événements à Venir', 'ar' => 'الأحداث القادمة'],
                'partners_title' => ['en' => 'Our Valued Partners', 'fr' => 'Nos Précieux Partenaires', 'ar' => 'شركاؤنا الكرام'],
                'newsletter_title' => ['en' => 'Stay Informed', 'fr' => 'Restez Informé', 'ar' => 'ابق على اطلاع'],
                'newsletter_text' => [
                    'en' => 'Subscribe to our newsletter to receive the latest news and updates directly in your inbox.',
                    'fr' => 'Abonnez-vous à notre newsletter pour recevoir les dernières nouvelles et mises à jour directement dans votre boîte de réception.',
                    'ar' => 'اشترك في النشرة الإخبارية لدينا لتلقي آخر الأخبار والتحديثات مباشرة في صندوق الوارد الخاص بك.',
                ],
            ]
        );
    }
}