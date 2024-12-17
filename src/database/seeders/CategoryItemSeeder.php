<?php

namespace Database\Seeders;

use App\Models\CategoryItem;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::all()->each(
            function ($item) {
                // まず必ず1つのカテゴリーを選んで紐づけ
                $firstCategory = Category::inRandomOrder()->first();

                // 中間テーブルに追加
                CategoryItem::create([
                    'category_id' => $firstCategory->category_id,
                    'item_id' => $item->item_id,
                ]);
            }
        );
        CategoryItem::factory()->count(5)->create();
    }
}
