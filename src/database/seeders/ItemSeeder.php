<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            'item_name' => '腕時計',
            'brand' => 'Armani',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
            'item_image' => 'image/Armani+Mens+Clock.jpg',
            'stock_status' => 0,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'HDD',
            'brand' => 'FujitsuFujitsu',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => '目立った傷や汚れなし',
            'item_image' => 'image/HDD+Hard+Disk.jpg',
            'stock_status' => 0,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => '玉ねぎ3束',
            'brand' => '北海道産',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition' => 'やや傷や汚れあり',
            'item_image' => 'image/iLoveIMG+d.jpg',
            'stock_status' => 0,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => '革靴',
            'brand' => 'no brand',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'condition' => '状態が悪い',
            'item_image' => 'image/Leather+Shoes+Product+Photo.jpg',
            'stock_status' => 0,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'ノートPC',
            'brand' => 'Living',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'condition' => '良好',
            'item_image' => 'image/Living+Room+Laptop.jpg',
            'stock_status' => 0,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'マイク',
            'brand' => 'Elecom',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'condition' => '目立った傷や汚れなし',
            'item_image' => 'image/Music+Mic+4632231.jpg',
            'stock_status' => 0,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'ショルダーバッグ',
            'brand' => 'COACH',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 'やや傷や汚れあり',
            'item_image' => 'image/Purse+fashion+pocket.jpg',
            'stock_status' => 0,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'タンブラー',
            'brand' => 'no brand',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'condition' => '状態が悪い',
            'item_image' => 'image/Tumbler+souvenir.jpg',
            'stock_status' => 0,
            'user_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'コーヒーミル',
            'brand' => 'no brand',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'condition' => '良好',
            'item_image' => 'image/Waitress+with+Coffee+Grinder.jpg',
            'stock_status' => 0,
            'user_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('items')->insert([
            'item_name' => 'メイクセット',
            'brand' => 'A-make',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'condition' => '目立った傷や汚れなし',
            'item_image' => 'image/MakeUpSet.jpg',
            'stock_status' => 0,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
