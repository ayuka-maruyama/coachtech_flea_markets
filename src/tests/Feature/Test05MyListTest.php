<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemSeeder;
use Database\Seeders\FavoriteSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test05MyListTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusersテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE favorites AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(FavoriteSeeder::class);
    }

    // ログインしていいねした商品だけが表示される
    public function testUsersFavoriteItem()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // ログインユーザーのお気に入り商品を取得
        $favoriteItems = Item::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->get();

        // データベースに存在するお気に入り商品数を確認
        $this->assertGreaterThan(0, $favoriteItems->count());

        // レスポンスの内容を確認（表示された商品件数をカウント）
        $responseContent = $response->getContent();

        // レスポンス内で表示された商品の数をカウント（item-nameで商品名が表示されている場合）
        $displayedFavoritesCount = substr_count($responseContent, 'card-top');

        // データベースのお気に入り件数と、表示されるお気に入り件数が一致するか確認
        $this->assertEquals(
            $favoriteItems->count(),
            $displayedFavoritesCount,
        );
    }

    // 購入済み商品に「Sold」が表示される
    public function testMyListItemSoldLabel()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    // 自分が出品した商品は表示されない
    public function testUserDoesNotSeeOwnItems()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        // ログインユーザーのお気に入り商品を取得（自分の出品した商品を除く）
        $favoriteItems = Favorite::where('user_id', $user->user_id)
            ->get()
            ->filter(function ($favorite) use ($user) {
                return $favorite->item->user_id != $user->user_id;
            });

        // 各お気に入りが正しく表示されているかを確認
        foreach ($favoriteItems as $favorite) {
            $response->assertSee($favorite->item->item_name);
        }
    }

    // 未認証の場合は何も表示されない
    public function testUnauthorizedNotSee()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        // 未認証状態では、空要素が準備されていないか確認
        $response->assertDontSee('<div class="item-card"></div>');
    }
}
