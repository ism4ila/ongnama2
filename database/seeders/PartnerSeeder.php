<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::query()->delete(); // Nettoyer avant de semer

        Partner::create([
            'name' => [
                'en' => 'International Charity Foundation',
                'fr' => 'Fondation Charité Internationale',
                'ar' => 'مؤسسة الخير الدولية'
            ],
            'description' => [
                'en' => 'A global partner supporting humanitarian actions.',
                'fr' => 'Un partenaire mondial soutenant les actions humanitaires.',
                'ar' => 'شريك عالمي يدعم الأعمال الإنسانية.'
            ],
            'logo_url' => '/images/partners/icf_logo.png', // Chemin exemple
            'website_url' => 'https://www.icf-global.org',
            'display_order' => 1,
        ]);

        Partner::create([
            'name' => [
                'en' => 'Local Development Group',
                'fr' => 'Groupe Développement Local',
                'ar' => 'مجموعة التنمية المحلية'
            ],
            'description' => [
                'en' => 'Supporting community projects in Cameroon.',
                'fr' => 'Soutien aux projets communautaires au Cameroun.',
                'ar' => 'دعم المشاريع المجتمعية في الكاميرون.'
            ],
            'logo_url' => '/images/partners/ldg_logo.png', // Chemin exemple
            'website_url' => null, // Peut être null
            'display_order' => 2,
        ]);
    }
}