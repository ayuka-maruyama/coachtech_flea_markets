<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        // 最大 10 件作成
        $maxCount = 10;
        $count = 0;

        while ($count < $maxCount) {
            // ファクトリーで 1 件生成
            $favorite = Favorite::factory()->make();

            // null データをスキップ
            if ($favorite->item_id && $favorite->user_id) {
                // 重複チェック
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
