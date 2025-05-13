<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Assure-toi que le chemin vers ton modèle Category est correct
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => [
                    'en' => 'Electronics',
                    'fr' => 'Électronique',
                    'ar' => 'إلكترونيات',
                ],
                'description' => [
                    'en' => 'Gadgets, devices, and all things electronic.',
                    'fr' => 'Gadgets, appareils et tout ce qui est électronique.',
                    'ar' => 'أدوات وأجهزة وكل الأشياء الإلكترونية.',
                ],
            ],
            [
                'name' => [
                    'en' => 'Books',
                    'fr' => 'Livres',
                    'ar' => 'كتب',
                ],
                'description' => [
                    'en' => 'Novels, comics, educational books and more.',
                    'fr' => 'Romans, bandes dessinées, livres éducatifs et plus encore.',
                    'ar' => 'روايات، قصص مصورة، كتب تعليمية والمزيد.',
                ],
            ],
            [
                'name' => [
                    'en' => 'Clothing',
                    'fr' => 'Vêtements',
                    'ar' => 'ملابس',
                ],
                'description' => [
                    'en' => 'Apparel for all seasons and styles.',
                    'fr' => 'Vêtements pour toutes les saisons et tous les styles.',
                    'ar' => 'ملابس لجميع الفصول والأنماط.',
                ],
            ],
            [
                'name' => [
                    'en' => 'Home & Garden',
                    'fr' => 'Maison et Jardin',
                    'ar' => 'المنزل والحديقة',
                ],
                'description' => [
                    'en' => 'Products for your home and outdoor spaces.',
                    'fr' => 'Produits pour votre maison et vos espaces extérieurs.',
                    'ar' => 'منتجات لمنزلك والمساحات الخارجية.',
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $slugs = [];
            foreach ($categoryData['name'] as $locale => $name) {
                $slugs[$locale] = Str::slug($name);
            }

            Category::create([
                'name' => $categoryData['name'],
                'slug' => $slugs, // Le modèle s'attend à un tableau de slugs par locale
                'description' => $categoryData['description'] ?? null, // Description peut être null
            ]);
        }
    }
}