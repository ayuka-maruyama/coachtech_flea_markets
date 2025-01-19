<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Test13MyPageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ProfileSeeder::class);

        $this->setupTestData();
    }

    // テスト用に購入済み商品データ、出品商品データを作成
    private function setupTestData(): void
    {
        $user = User::first();

        $item = Item::find(3);
        $destination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        Order::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $destination->destination_id,
            'status' => 'completed',
        ]);
    }

    // プロフィールページにアクセスし、必要事項が正しく表示される
    public function testProfilePageShowsCorrectInformation()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200);

        $profile = Profile::where('user_id', $user->user_id)->first();
        $response->assertSee($profile->profile_image);
        $response->assertSee($user->name);

        $userItems = Item::where('user_id', $user->user_id)->get();
        foreach ($userItems as $item) {
            $response->assertSee($item->item_name);
        }

        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        $purchasedItem = Item::find(3);
        $response->assertSee($purchasedItem->item_name);
    }
}
