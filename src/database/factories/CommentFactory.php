<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        $phrases = [
            "とてもおすすめです。",
            "おすすめできません。",
            "詳しい商品情報を教えてほしい",
            "発送までが早かったので助かりました",
        ];

        return [
            'user_id' => User::whereBetween('user_id', [1, 4])->inRandomOrder()->value('user_id'),
            'item_id' => Item::inRandomOrder()->value('item_id'),
            'comment' => fake()->randomElement($phrases),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
