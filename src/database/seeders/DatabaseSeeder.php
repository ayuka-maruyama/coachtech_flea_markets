<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            ItemSeeder::class,
            CommentSeeder::class,
            CategorySeeder::class,
            CategoryItemSeeder::class,
            FavoriteSeeder::class,
        ]);
    }
}
