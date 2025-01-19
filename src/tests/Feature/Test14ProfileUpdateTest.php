<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Test14ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ProfileSeeder::class);
    }

    // プロフィール編集画面を開いたときに、各項目の初期値が表示される
    public function testShowProfile()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $profile = Profile::where('user_id', $user->user_id)->first();
        $response->assertSee($profile->profile_image);
        $response->assertSee($profile->profile_name);
        $response->assertSee($profile->postal_number);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);
    }
}
