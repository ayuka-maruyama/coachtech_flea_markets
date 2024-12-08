<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // すでに関連するuser_idを除外してランダムな未使用のユーザーIDを取得
        $unusedUserId = User::whereNotIn('id', Profile::pluck('user_id')->toArray()) // 既存のuser_idを除外
            ->whereNotNull('email_verified_at') // メール認証済みユーザーのみ対象
            ->inRandomOrder()
            ->first();

        // ユーザーが見つからない場合、エラー処理または新しいユーザーを作成する処理を加える
        if (!$unusedUserId) {
            // 新しいユーザーを作成してそのIDを使用する
            $unusedUserId = User::factory()->create();
        }

        return [
            'profile_name' => fake()->name(),
            'postal_number' => preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', fake()->postcode()),
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress(),
            'profile_image' => 'public/image/profile_' . $this->faker->numberBetween(1, 2) . '.png', // 画像パスを設定
            'user_id' => $unusedUserId->id, // ランダムに取得した未使用ユーザーIDを設定
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
