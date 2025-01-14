<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    public function definition(): array
    {
        // ランダムなユーザー
        $user = User::whereBetween('user_id', [1, 4])->inRandomOrder()->first();

        // ユーザーが出品していない商品をランダムに選択
        $item = Item::where('user_id', '!=', $user->user_id)
            ->whereDoesntHave('favorites', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->inRandomOrder()
            ->first();

        if (!$item || !$user) {
            return [];
        }

        return [
            'item_id' => $item->item_id,
            'user_id' => $user->user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
