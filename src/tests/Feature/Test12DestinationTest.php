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

class Test12DestinationTest extends TestCase
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

    // 送付先住所変更画面で送付先を変更後、購入画面に変更後の住所が表示される
    public function testUpdateDestinationIsChangePurchaseScreen()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);

        $newDestination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->post('/purchase/address/' . $item->item_id);
        $response->assertStatus(302);
        $this->assertDatabaseHas('destinations', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->get('/purchase/' . $item->item_id);

        $response->assertStatus(200);
        $response->assertSee($newDestination['postal_number']);
        $response->assertSee($newDestination['address']);
        $response->assertSee($newDestination['building']);
    }

    // 購入した商品に送付先情報が結びついている
    public function testHandlePurchaseAndDestinationAddress()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);

        $newDestination = Destination::create([
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->post('/purchase/address/' . $item->item_id);
        $response->assertStatus(302);
        $this->assertDatabaseHas('destinations', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'postal_number' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101',
        ]);

        $response = $this->get('/purchase/' . $item->item_id);
        $response->assertStatus(200);

        $response->assertSee($newDestination['postal_number']);
        $response->assertSee($newDestination['address']);
        $response->assertSee($newDestination['building']);

        $response = $this->post('/purchase/order/' . $item->item_id, [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $newDestination->destination_id,
            'postal_number' => $newDestination->postal_number,
            'address' => $newDestination->address,
            'building' => $newDestination->building,
            'status' => 'completed',
        ]);

        $response->assertRedirect('/mypage?page=buy');
        $response->assertSessionHas('message', '購入が完了しました。');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'convenience',
            'destination_id' => $newDestination->destination_id,
            'status' => 'completed',
        ]);
    }
}
