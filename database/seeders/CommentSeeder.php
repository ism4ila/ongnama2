<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Factory as FakerFactory;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();
        // $faker_fr = FakerFactory::create('fr_FR');
        // $faker_ar = FakerFactory::create('ar_SA');

        Comment::truncate(); // Optionnel

        $posts = Post::where('is_published', true)->get(); // Ou le critère de publication que vous utilisez pour Post
        $users = User::all();

        if ($posts->isEmpty()) {
            $this->command->info('No posts found to seed comments for. Please ensure PostSeeder runs first and creates published posts.');
            return;
        }

        foreach ($posts as $post) {
            // Créer entre 2 et 7 commentaires principaux pour chaque article
            for ($i = 0; $i < $faker->numberBetween(2, 7); $i++) {
                $isRegisteredUserComment = $users->isNotEmpty() && $faker->boolean(60); // 60% de chance qu'un utilisateur enregistré commente
                $commenter = $isRegisteredUserComment ? $users->random() : null;

                $mainComment = Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $commenter ? $commenter->id : null,
                    'name' => $commenter ? $commenter->name : $faker->name,
                    'email' => $commenter ? $commenter->email : $faker->unique()->safeEmail,
                    'body' => $faker->paragraphs($faker->numberBetween(1, 4), true),
                    'is_approved' => $faker->boolean(85), // 85% de chance d'être approuvé
                    'created_at' => $faker->dateTimeBetween($post->published_at ?? $post->created_at, 'now'),
                    'updated_at' => $faker->dateTimeBetween($post->published_at ?? $post->created_at, 'now'),
                ]);

                // Créer entre 0 et 3 réponses pour certains commentaires principaux
                if ($mainComment->is_approved && $faker->boolean(50)) { // 50% de chance qu'un commentaire approuvé ait des réponses
                    for ($j = 0; $j < $faker->numberBetween(0, 3); $j++) {
                        $isRegisteredUserReply = $users->isNotEmpty() && $faker->boolean(70);
                        $replyCommenter = $isRegisteredUserReply ? $users->random() : null;
                        
                        Comment::create([
                            'post_id' => $post->id,
                            'user_id' => $replyCommenter ? $replyCommenter->id : null,
                            'name' => $replyCommenter ? $replyCommenter->name : $faker->name,
                            'email' => $replyCommenter ? $replyCommenter->email : $faker->unique()->safeEmail,
                            'body' => $faker->sentences($faker->numberBetween(1,3), true),
                            'is_approved' => $faker->boolean(95), 
                            'parent_id' => $mainComment->id,
                            'created_at' => $faker->dateTimeBetween($mainComment->created_at, 'now'),
                            'updated_at' => $faker->dateTimeBetween($mainComment->created_at, 'now'),
                        ]);
                    }
                }
            }
        }
    }
}