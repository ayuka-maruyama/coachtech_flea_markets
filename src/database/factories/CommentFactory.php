<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::whereBetween('user_id', [1, 4])->inRandomOrder()->value('user_id'),
            'item_id' => Item::inRandomOrder()->value('item_id'),
            'comment' => fake()->realText(20),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
