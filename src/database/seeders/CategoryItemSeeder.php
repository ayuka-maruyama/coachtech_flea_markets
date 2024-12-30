<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        // Item::all()->each(
        //     function ($item) {
        //         // まず必ず1つのカテゴリーを選んで紐づけ
        //         $firstCategory = Category::inRandomOrder()->first();

        //         // 中間テーブルに追加
        //         category_item::create([
        //             'category_id' => $firstCategory->category_id,
        //             'item_id' => $item->item_id,
        //         ]);
        //     }
        // );
        // category_item::factory()->count(5)->create();
        DB::table('category_item')->insert([
            'category_id' => 1,
            'item_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 5,
            'item_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 2,
            'item_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 10,
            'item_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 1,
            'item_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 5,
            'item_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 2,
            'item_id' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 2,
            'item_id' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 4,
            'item_id' => 7,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 4,
            'item_id' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 5,
            'item_id' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 10,
            'item_id' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 10,
            'item_id' => 9,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 4,
            'item_id' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('category_item')->insert([
            'category_id' => 6,
            'item_id' => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
