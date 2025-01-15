<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusersテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
    }

    // 全商品を取得して表示
    public function testAllItem()
    {
        $itemCount = Item::count();

        $response = $this->get('/');

        $response->assertStatus(200);

        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->item_image);
            $response->assertSee($item->item_name);
        }

        $this->assertEquals($itemCount, count($items));
    }

    // 購入済み商品に「Sold」が表示される
    public function testItemSoldLabel()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    // ログインしてアクセスした時に自分が出品した商品が表示されない
    public function testUserDoesNotSeeOwnItems()
    {
        // ダミーユーザーでログイン
        $user = User::first();
        $this->actingAs($user);

        // 商品リストページにアクセス
        $response = $this->get('/'); // ルートが正しいか確認

        // ステータスコードを確認
        $response->assertStatus(200);

        // ログインユーザーの商品名はページに含まれていないことを確認
        Item::where('user_id', $user->id)->pluck('item_name')->each(function ($itemName) use ($response) {
            $response->assertDontSee($itemName);
        });
    }
}
