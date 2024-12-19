<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {

        // ランダムな3件のユーザーを取得
        $users = User::take(4)->get();

        // 各ユーザーに対してプロファイルを生成
        $users->each(function ($user) {
            Profile::factory()->create([
                'user_id' => $user->user_id,
            ]);
        });
    }
}
