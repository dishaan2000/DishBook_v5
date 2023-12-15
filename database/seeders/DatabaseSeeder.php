<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Lasse',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Hans',
            'email' => 'test1@example.com',
            'password' => 'password',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Christian',
            'email' => 'test2@example.com',
            'password' => 'password',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Kristian',
            'email' => 'test3@example.com',
            'password' => 'password',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Lone',
            'email' => 'test4@example.com',
            'password' => 'password',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '1',
            'following_id' => '2',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '1',
            'following_id' => '3',
        ]);
        
        \App\Models\Follow::factory()->create([
            'follower_id' => '1',
            'following_id' => '4',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '2',
            'following_id' => '1',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '3',
            'following_id' => '1',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '4',
            'following_id' => '1',
        ]);

        \App\Models\Follow::factory()->create([
            'follower_id' => '5',
            'following_id' => '1',
        ]);

        \App\Models\Post::factory()->create([
            'user_id' => '1',
            'content' => 'Hej vennerne, er så glad for at kunne se alles postes (self undtaget Lone 😠)',
        ]);

        \App\Models\Post::factory()->create([
            'user_id' => '5',
            'content' => 'Hvorfor kan Lasse ikke lide mig😢(Kan han ikke se mine post?)',
        ]);

        \App\Models\like::factory()->create([
            'user_id' => '2',
            'post_id' => '1',
        ]);

        \App\Models\like::factory()->create([
            'user_id' => '3',
            'post_id' => '1',
        ]);

        \App\Models\like::factory()->create([
            'user_id' => '4',
            'post_id' => '1',
        ]);

        \App\Models\like::factory()->create([
            'user_id' => '3',
            'post_id' => '2',
        ]);

        \App\Models\Comment::factory()->create([
            'user_id' => '3',
            'post_id' => '1',
            'content' => 'Jeg hater også på Lone😠'
        ]);

        \App\Models\Comment::factory()->create([
            'user_id' => '3',
            'post_id' => '2',
            'content' => 'Jeg er din største FAN <3!!'
        ]);


    }
}
