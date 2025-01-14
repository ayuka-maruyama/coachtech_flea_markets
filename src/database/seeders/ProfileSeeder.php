<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::take(4)->get();

        $users->each(function ($user) {
            Profile::factory()->create([
                'user_id' => $user->user_id,
            ]);
        });
    }
}
