<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::query()->delete(); // Nettoyer avant de semer

        Project::create([
            'title' => [
                'en' => 'Mosque Construction in Village X',
                'fr' => 'Construction Mosquée au Village X',
                'ar' => 'بناء مسجد في قرية X'
            ],
            'description' => [
                'en' => 'Detailed description of the mosque construction project in Village X.',
                'fr' => 'Description détaillée du projet de construction de mosquée au Village X.',
                'ar' => 'وصف تفصيلي لمشروع بناء المسجد في قرية X.'
            ],
            'status' => 'ongoing',
            'start_date' => Carbon::parse('2024-01-15'),
            'end_date' => null, // Peut être null si en cours
            'location_latitude' => 4.0483, // Exemple Yaoundé
            'location_longitude' => 11.5021, // Exemple Yaoundé
            'featured_image_url' => '/images/projects/mosque_x.jpg', // Chemin exemple
        ]);

        Project::create([
            'title' => [
                'en' => 'Water Well Drilling in Region Y',
                'fr' => 'Forage Puits d\'Eau en Région Y',
                'ar' => 'حفر بئر ماء في منطقة Y'
            ],
            'description' => [
                'en' => 'Drilling a well to provide clean drinking water for the community in Region Y.',
                'fr' => 'Forage d\'un puits pour fournir de l\'eau potable à la communauté de la Région Y.',
                'ar' => 'حفر بئر لتوفير مياه الشرب النظيفة للمجتمع في منطقة Y.'
            ],
            'status' => 'completed',
            'start_date' => Carbon::parse('2023-06-01'),
            'end_date' => Carbon::parse('2023-11-30'),
            'location_latitude' => 10.2333, // Exemple Nord Cameroun
            'location_longitude' => 13.2333, // Exemple Nord Cameroun
            'featured_image_url' => '/images/projects/well_y.jpg', // Chemin exemple
        ]);
    }
}