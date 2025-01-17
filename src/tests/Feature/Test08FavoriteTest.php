<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\FavoriteSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test08FavoriteTest extends TestCase
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

    // いいね登録、いいね数の増加を確認
    public function testUsersFavoriteItem()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(8);
        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $initialFavoriteCount = $item->favorite()->count();

        $response->assertSee('<p class="count favorite-count">' . $initialFavoriteCount . '</p>', false);

        $this->post('/favorite/toggle/' . $item->item_id);

        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $updatedFavoriteCount = $item->favorite()->count();

        $response->assertSee('<p class="count favorite-count">' . $updatedFavoriteCount . '</p>', false);

        $this->assertEquals($initialFavoriteCount + 1, $updatedFavoriteCount);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
        ]);
    }

    // いいねをしたらアイコンの色が変わる
    public function testUsersFavoriteItemChange()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(8);
        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $response->assertSee('class="favorite-btn "', false);
        $response->assertDontSee('class="favorite-btn favorite"', false);

        $this->post('/favorite/toggle/' . $item->item_id);

        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $response->assertSee('class="favorite-btn favorite"', false);
    }

    // いいね解除、いいね数の減少を確認
    public function testUsersFavoriteReleaseAndDecreaseIndication()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(3);
        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $response->assertSee('class="favorite-btn favorite"', false);
        $response->assertDontSee('class="favorite-btn "', false);
        $initialFavoriteCount = $item->favorite()->count();
        $response->assertSee('<p class="count favorite-count">' . $initialFavoriteCount . '</p>', false);

        $this->post('/favorite/toggle/' . $item->item_id);

        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $updatedFavoriteCount = $item->favorite()->count();

        $response->assertSee('class="favorite-btn "', false);
        $response->assertSee('<p class="count favorite-count">' . $updatedFavoriteCount . '</p>', false);

        $this->assertEquals($initialFavoriteCount - 1, $updatedFavoriteCount);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
        ]);
    }
}
