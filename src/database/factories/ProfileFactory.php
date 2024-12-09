<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        $unusedUserId = User::whereNotIn('id', Profile::pluck('user_id')->toArray())
            ->whereNotNull('email_verified_at')
            ->inRandomOrder()
            ->first();

        // 利用可能なユーザーがいない場合、新しいユーザーを作成
        if (!$unusedUserId) {
            $unusedUserId = User::factory()->create();
        }

        return [
            'profile_name' => fake()->name(),
            'postal_number' => preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', fake()->postcode()),
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress(),
            'profile_image' => null,
            'user_id' => $unusedUserId->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
