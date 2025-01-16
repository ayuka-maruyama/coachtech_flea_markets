<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('favorites')->insert([
            'item_id' => 3,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('favorites')->insert([
            'item_id' => 4,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('favorites')->insert([
            'item_id' => 1,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('favorites')->insert([
            'item_id' => 2,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
