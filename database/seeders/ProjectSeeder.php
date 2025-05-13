<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project; // Assurez-vous que le chemin est correct
use Carbon\Carbon;
// Pas besoin de Str ici car nous ne générons plus de slugs pour Project selon le modèle fourni

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::query()->delete(); // Nettoyer avant de semer

        $projectsData = [
            [
                'title' => [
                    'en' => 'Mosque Construction in Village Alpha',
                    'fr' => 'Construction Mosquée au Village Alpha',
                    'ar' => 'بناء مسجد في قرية ألفا'
                ],
                'description' => [
                    'en' => 'This project focuses on building a new mosque to serve the spiritual and community needs of Village Alpha residents. It will include a prayer hall, ablution areas, and a small community center space.',
                    'fr' => 'Ce projet se concentre sur la construction d\'une nouvelle mosquée pour répondre aux besoins spirituels et communautaires des habitants du Village Alpha. Il comprendra une salle de prière, des zones d\'ablution et un petit espace de centre communautaire.',
                    'ar' => 'يركز هذا المشروع على بناء مسجد جديد لتلبية الاحتياجات الروحية والمجتمعية لسكان قرية ألفا. وسيشمل قاعة صلاة وأماكن للوضوء ومساحة صغيرة لمركز مجتمعي.'
                ],
                'status' => 'ongoing', // planned, ongoing, completed, cancelled
                'start_date' => Carbon::parse('2024-02-01'),
                'end_date' => null,
                'location_latitude' => 3.8480,  // Exemple : Yaoundé
                'location_longitude' => 11.5021, // Exemple : Yaoundé
                'featured_image_url' => 'https://placehold.co/800x600/26A69A/FFFFFF?text=Mosque+Alpha',
            ],
            [
                'title' => [
                    'en' => 'Water Well Drilling in Beta Region',
                    'fr' => 'Forage Puits d\'Eau en Région Bêta',
                    'ar' => 'حفر بئر ماء في منطقة بيتا'
                ],
                'description' => [
                    'en' => 'A critical initiative to drill and install a new water well, providing sustainable access to clean and safe drinking water for the population of Beta Region.',
                    'fr' => 'Une initiative cruciale pour forer et installer un nouveau puits d\'eau, fournissant un accès durable à une eau potable propre et sûre pour la population de la Région Bêta.',
                    'ar' => 'مبادرة حاسمة لحفر وتركيب بئر مياه جديد، مما يوفر وصولاً مستدامًا لمياه الشرب النظيفة والآمنة لسكان منطقة بيتا.'
                ],
                'status' => 'completed',
                'start_date' => Carbon::parse('2023-05-10'),
                'end_date' => Carbon::parse('2023-12-15'),
                'location_latitude' => 4.0521,  // Exemple : Douala
                'location_longitude' => 9.7040,   // Exemple : Douala
                'featured_image_url' => 'https://placehold.co/800x600/00796B/FFFFFF?text=Well+Beta',
            ],
            [
                'title' => [
                    'en' => 'Educational Support for Gamma Children',
                    'fr' => 'Soutien Éducatif pour les Enfants de Gamma',
                    'ar' => 'دعم تعليمي لأطفال غاما'
                ],
                'description' => [
                    'en' => 'This program aims to distribute essential school supplies, offer tutoring, and improve learning facilities for children in the Gamma district.',
                    'fr' => 'Ce programme vise à distribuer des fournitures scolaires essentielles, offrir du tutorat et améliorer les installations d\'apprentissage pour les enfants du district de Gamma.',
                    'ar' => 'يهدف هذا البرنامج إلى توزيع اللوازم المدرسية الأساسية وتقديم دروس خصوصية وتحسين مرافق التعلم للأطفال في منطقة غاما.'
                ],
                'status' => 'planned', // planned
                'start_date' => Carbon::parse('2025-09-01'),
                'end_date' => Carbon::parse('2026-06-30'),
                'location_latitude' => 6.3570,  // Exemple : Garoua
                'location_longitude' => 13.4030, // Exemple : Garoua
                'featured_image_url' => 'https://placehold.co/800x600/80CBC4/000000?text=Education+Gamma',
            ],
             [
                'title' => [
                    'en' => 'Healthcare Outreach in Delta Province',
                    'fr' => 'Sensibilisation Sanitaire en Province Delta',
                    'ar' => 'التوعية الصحية في مقاطعة دلتا'
                ],
                'description' => [
                    'en' => 'Mobile clinics and health education campaigns to improve healthcare access and awareness in remote villages of Delta Province.',
                    'fr' => 'Cliniques mobiles et campagnes d\'éducation sanitaire pour améliorer l\'accès aux soins de santé et la sensibilisation dans les villages reculés de la Province Delta.',
                    'ar' => 'عيادات متنقلة وحملات تثقيف صحي لتحسين الوصول إلى الرعاية الصحية والوعي في القرى النائية بمقاطعة دلتا.'
                ],
                'status' => 'ongoing',
                'start_date' => Carbon::parse('2024-03-01'),
                'end_date' => null,
                'location_latitude' => 5.9500,  // Exemple : Bamenda
                'location_longitude' => 10.1500, // Exemple : Bamenda
                'featured_image_url' => 'https://placehold.co/800x600/f06292/FFFFFF?text=Healthcare+Delta',
            ],
        ];

        foreach ($projectsData as $data) {
            Project::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'location_latitude' => $data['location_latitude'] ?? null,
                'location_longitude' => $data['location_longitude'] ?? null,
                'featured_image_url' => $data['featured_image_url'] ?? null,
            ]);
        }

        $this->command->info(count($projectsData) . ' projects seeded.');
    }
}