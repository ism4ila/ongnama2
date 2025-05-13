<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str; // Pour générer des slugs à partir des titres

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Event::query()->delete(); // Optionnel

        $eventsData = [
            [
                'title' => [
                    'fr' => 'Gala Annuel de Charité NAMA',
                    'en' => 'NAMA Annual Charity Gala',
                    'ar' => 'حفل العشاء الخيري السنوي لمنظمة نما'
                ],
                // Génération des slugs à partir des titres
                'slug' => [
                    'fr' => Str::slug('Gala Annuel de Charité NAMA', '-'),
                    'en' => Str::slug('NAMA Annual Charity Gala', '-'),
                    'ar' => Str::slug('حفل العشاء الخيري السنوي لمنظمة نما', '-', 'ar') // Préciser la langue pour les caractères non latins
                ],
                'description' => [ /* ... vos descriptions ... */ ],
                'start_datetime' => Carbon::now()->addMonths(2)->setHour(19)->setMinute(0)->setSecond(0),
                'end_datetime' => Carbon::now()->addMonths(2)->setHour(23)->setMinute(0)->setSecond(0),
                'location_text' => [ /* ... vos lieux ... */ ],
                'featured_image_url' => 'seeders/images/events/gala_nama.jpg',
            ],
            [
                'title' => [
                    'fr' => 'Atelier de Formation : Entrepreneuriat Jeune',
                    'en' => 'Youth Entrepreneurship Training Workshop',
                    'ar' => 'ورشة عمل تدريبية: ريادة الأعمال للشباب'
                ],
                'slug' => [
                    'fr' => Str::slug('Atelier de Formation Entrepreneuriat Jeune', '-'),
                    'en' => Str::slug('Youth Entrepreneurship Training Workshop', '-'),
                    'ar' => Str::slug('ورشة عمل تدريبية ريادة الأعمال للشباب', '-', 'ar')
                ],
                'description' => [ /* ... vos descriptions ... */ ],
                'start_datetime' => Carbon::now()->addWeeks(3)->setHour(9)->setMinute(30)->setSecond(0),
                'end_datetime' => Carbon::now()->addWeeks(3)->addDays(2)->setHour(17)->setMinute(0)->setSecond(0),
                'location_text' => [ /* ... vos lieux ... */ ],
                'featured_image_url' => 'seeders/images/events/workshop_entrepreneuriat.jpg',
            ],
            // ... Ajoutez les slugs pour les autres événements de la même manière ...
            [
                'title' => [
                    'fr' => 'Journée de Sensibilisation à la Santé Communautaire',
                    'en' => 'Community Health Awareness Day',
                    'ar' => 'يوم التوعية الصحية المجتمعية'
                ],
                'slug' => [
                    'fr' => Str::slug('Journée de Sensibilisation à la Santé Communautaire', '-'),
                    'en' => Str::slug('Community Health Awareness Day', '-'),
                    'ar' => Str::slug('يوم التوعية الصحية المجتمعية', '-', 'ar')
                ],
                'description' => [/*...*/],
                'start_datetime' => Carbon::now()->subWeeks(2)->setHour(10)->setMinute(0)->setSecond(0),
                'end_datetime' => Carbon::now()->subWeeks(2)->setHour(16)->setMinute(0)->setSecond(0),
                'location_text' => [/*...*/],
                'featured_image_url' => 'seeders/images/events/sante_communautaire.jpg',
            ],
            [
                'title' => [
                    'fr' => 'Webinaire : L\'Impact du Changement Climatique en Afrique Centrale',
                    'en' => 'Webinar: The Impact of Climate Change in Central Africa',
                    'ar' => 'ندوة عبر الإنترنت: تأثير تغير المناخ في وسط أفريقيا'
                ],
                'slug' => [
                    'fr' => Str::slug('Webinaire Impact Changement Climatique Afrique Centrale', '-'),
                    'en' => Str::slug('Webinar Impact Climate Change Central Africa', '-'),
                    'ar' => Str::slug('ندوة تأثير تغير المناخ وسط أفريقيا', '-', 'ar')
                ],
                'description' => [/*...*/],
                'start_datetime' => Carbon::now()->addDays(10)->setHour(14)->setMinute(0)->setSecond(0),
                'end_datetime' => Carbon::now()->addDays(10)->setHour(15)->setMinute(30)->setSecond(0),
                'location_text' => [/*...*/],
                'featured_image_url' => 'seeders/images/events/webinar_climat.jpg',
            ],
            [
                'title' => [
                    'fr' => 'Campagne de Reboisement "Un Arbre, Une Vie"',
                    'en' => '"One Tree, One Life" Reforestation Campaign',
                    'ar' => 'حملة إعادة التحريج "شجرة واحدة، حياة واحدة"'
                ],
                'slug' => [
                    'fr' => Str::slug('Campagne Reboisement Un Arbre Une Vie', '-'),
                    'en' => Str::slug('One Tree One Life Reforestation Campaign', '-'),
                    'ar' => Str::slug('حملة إعادة التحريج شجرة واحدة حياة واحدة', '-', 'ar')
                ],
                'description' => [/*...*/],
                'start_datetime' => Carbon::now()->subMonths(1)->setHour(8)->setMinute(0)->setSecond(0),
                'end_datetime' => null,
                'location_text' => [/*...*/],
                'featured_image_url' => 'seeders/images/events/reboisement.jpg',
            ]
        ];

        foreach ($eventsData as $data) {
            // Remplir la description si elle est vide pour l'exemple
            if (empty($data['description'])) {
                $data['description'] = [
                    'fr' => 'Description par défaut pour ' . $data['title']['fr'],
                    'en' => 'Default description for ' . $data['title']['en'],
                    'ar' => 'وصف افتراضي لـ ' . $data['title']['ar'],
                ];
            }
            if (empty($data['location_text'])) {
                 $data['location_text'] = [
                    'fr' => 'Lieu à définir',
                    'en' => 'Location to be defined',
                    'ar' => 'الموقع قيد التحديد',
                ];
            }
            Event::create($data);
        }
    }
}