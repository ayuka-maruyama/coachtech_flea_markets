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
        Profile::factory()->count(2)->create();
    }
}
