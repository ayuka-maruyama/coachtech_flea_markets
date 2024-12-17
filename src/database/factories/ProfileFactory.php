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
        return [
            'profile_name' => fake()->name(),
            'postal_number' => preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', fake()->postcode()),
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->secondaryAddress(),
            'profile_image' => $this->faker->imageUrl(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
