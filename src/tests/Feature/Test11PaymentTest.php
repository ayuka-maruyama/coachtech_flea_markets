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
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class Test11PaymentTest extends TestCase
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

    public function testPaymentMethodPersistenceWithMock()
    {
        Http::fake([
            'https://api.stripe.com/*' => Http::response(['status' => 'success'], 200),
        ]);

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

        // フォーム送信（支払い方法をコンビニからカードに変更）
        $response = $this->post(route('purchase.store', ['item_id' => $item->item_id]), [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'card',
            'postal_number' => $newDestination->postal_number,
            'address' => $newDestination->address,
            'building' => $newDestination->building,
            'destination_id' => 1,
            'status' => 'pending',
            '_token' => csrf_token(),
        ]);
        $response->assertRedirect();

        // データベースに支払方法がカードで保存されていることを確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
            'payment_method' => 'card',
        ]);
    }
}
