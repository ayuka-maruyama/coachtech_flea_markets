<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;

class CategoryItemFactory extends Factory
{
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();
        $item = Item::inRandomOrder()->first();

        return [
            'category_id' => $category->category_id,
            'item_id' => $item->item_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
