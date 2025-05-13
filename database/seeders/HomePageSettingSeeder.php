<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageSetting;

class HomePageSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Ceci utilise la méthode instance() pour créer les paramètres par défaut s'ils n'existent pas
        HomePageSetting::instance(); 
    }
}