<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // L'ordre est important ici :
        $this->call([
            UserSeeder::class,
            CategorySeeder::class, // Exemple
            PostSeeder::class,
            ProjectSeeder::class,
            EventSeeder::class,
            PartnerSeeder::class,
            TeamMemberSeeder::class,
            // CommentSeeder::class,

            // Nouveaux Seeders
            HomePageSettingSeeder::class,
            SiteSettingSeeder::class,
            PageSeeder::class, // Mettez à jour ou ajoutez PageSeeder
        ]);
    }
}
