<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $maxCount = 10;
        $count = 0;

        while ($count < $maxCount) {
            $favorite = Favorite::factory()->make();

            if ($favorite->item_id && $favorite->user_id) {
                $exists = Favorite::where('item_id', $favorite->item_id)
                    ->where('user_id', $favorite->user_id)
                    ->exists();

                if (!$exists) {
                    $favorite->save();
                    $count++;
                }
            }
        }
    }
}
