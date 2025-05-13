<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assure-toi d'avoir des utilisateurs et des catégories avant de lancer ce seeder
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Please seed Users and Categories before seeding Posts.');
            return;
        }

        $posts = [
            [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => [
                    'en' => 'My First Laravel Post',
                    'fr' => 'Mon Premier Article Laravel',
                    'ar' => 'مقالتي الأولى في لارافيل',
                ],
                'body' => [
                    'en' => 'This is the body of my first post in English. <strong>Laravel is awesome!</strong>',
                    'fr' => 'Ceci est le corps de mon premier article en français. <strong>Laravel est génial !</strong>',
                    'ar' => 'هذا هو محتوى مقالتي الأولى باللغة العربية. <strong>لارافيل رائع!</strong>',
                ],
                'excerpt' => [
                    'en' => 'A short excerpt of the first post.',
                    'fr' => 'Un court extrait du premier article.',
                    'ar' => 'مقتطف قصير من المقالة الأولى.',
                ],
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => [
                    'en' => 'Understanding Eloquent Relationships',
                    'fr' => 'Comprendre les Relations Eloquent',
                    'ar' => 'فهم علاقات Eloquent',
                ],
                'body' => [
                    'en' => 'Detailed explanation of Eloquent relationships: one-to-one, one-to-many, many-to-many, etc.',
                    'fr' => 'Explication détaillée des relations Eloquent : un-à-un, un-à-plusieurs, plusieurs-à-plusieurs, etc.',
                    'ar' => 'شرح مفصل لعلاقات Eloquent: واحد لواحد، واحد لكثير، كثير لكثير، إلخ.',
                ],
                'excerpt' => [
                    'en' => 'Learn how to define and use Eloquent relationships in your Laravel project.',
                    'fr' => 'Apprenez à définir et utiliser les relations Eloquent dans votre projet Laravel.',
                    'ar' => 'تعلم كيفية تعريف واستخدام علاقات Eloquent في مشروع Laravel الخاص بك.',
                ],
                'status' => 'draft',
                'published_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => [
                    'en' => 'Tips for Multilingual Applications',
                    'fr' => 'Conseils pour les Applications Multilingues',
                    'ar' => 'نصائح للتطبيقات متعددة اللغات',
                ],
                'body' => [
                    'en' => 'Best practices for building applications that support multiple languages.',
                    'fr' => 'Meilleures pratiques pour construire des applications qui supportent plusieurs langues.',
                    'ar' => 'أفضل الممارسات لبناء تطبيقات تدعم لغات متعددة.',
                ],
                'excerpt' => [
                    'en' => 'Discover tips and tricks for localization and internationalization.',
                    'fr' => 'Découvrez des trucs et astuces pour la localisation et l\'internationalisation.',
                    'ar' => 'اكتشف النصائح والحيل للترجمة المحلية والدولية.',
                ],
                'status' => 'published',
                'published_at' => Carbon::now(),
            ]
        ];

        foreach ($posts as $postData) {
            $slugs = [];
            foreach ($postData['title'] as $locale => $title) {
                $slugs[$locale] = Str::slug($title);
            }

            Post::create([
                'user_id' => $postData['user_id'],
                'category_id' => $postData['category_id'],
                'title' => $postData['title'],
                'slug' => $slugs,
                'body' => $postData['body'],
                'excerpt' => $postData['excerpt'] ?? null,
                'status' => $postData['status'],
                'published_at' => $postData['published_at'] ?? null,
                // 'featured_image' => 'path/to/default/image.jpg', // Optionnel
            ]);
        }
    }
}