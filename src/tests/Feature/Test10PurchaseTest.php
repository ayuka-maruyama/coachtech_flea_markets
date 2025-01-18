<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test10PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE profiles AUTO_INCREMENT = 1;');


        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ProfileSeeder::class);
    }

    // 購入するボタンを押すと商品購入がされる
    public function testHandlePurchaseCompletesSuccessfully()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);

        $destination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->post('/purchase/order/' . $item->item_id, [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $destination->destination_id,
            'postal_number' => $destination->postal_number,
            'address' => $destination->address,
            'building' => $destination->building,
            'status' => 'completed',
        ]);

        $response->assertRedirect('/mypage?page=buy');
        $response->assertSessionHas('message', '購入が完了しました。');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $destination->destination_id,
            'status' => 'completed',
        ]);
    }

    // 購入するボタンを押すと商品購入がされ、商品一覧画面にSoldが表示される
    public function testPurchasedItemDisplaysSold()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);

        $destination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->post('/purchase/order/' . $item->item_id, [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $destination->destination_id,
            'postal_number' => $destination->postal_number,
            'address' => $destination->address,
            'building' => $destination->building,
            'status' => 'completed',
        ]);

        $response->assertRedirect('/mypage?page=buy');

        $productListResponse = $this->get('/');
        $productListResponse->assertStatus(200);

        $productListResponse->assertSeeText('Sold');
    }

    // 購入するボタンを押した後、購入した商品に表示される
    public function testPurchasedItemShowMypage()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);

        $destination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->post('/purchase/order/' . $item->item_id, [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $destination->destination_id,
            'postal_number' => $destination->postal_number,
            'address' => $destination->address,
            'building' => $destination->building,
            'status' => 'completed',
        ]);

        $response->assertRedirect('/mypage?page=buy');

        $mypageResponse = $this->get('/mypage?page=buy');

        $mypageResponse->assertStatus(200);
        $mypageResponse->assertSee($item->item_name);
        $mypageResponse->assertSee('Sold');
    }
}
