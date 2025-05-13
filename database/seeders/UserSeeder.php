<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur administrateur/auteur
        User::create([
            'name' => 'ISMAILA', // Prénom uniquement
            'last_name' => 'HAMADOU', // Ajout du nom de famille
            'email' => 'ismailahamadou5@gmail.com',
            'is_admin' => true,
            'password' => '12345678',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Soumayya', // Prénom uniquement
            'last_name' => 'LAtifa', // Ajout du nom de famille
            'email' => 'soumy@choco.com',
            'is_admin' => false,
            'password' => '12345678',
            'email_verified_at' => now(),
        ]);

        // Créer quelques utilisateurs normaux


       
    }
}