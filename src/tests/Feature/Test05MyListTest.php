<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\FavoriteSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test05MyListTest extends TestCase
{
    use RefreshDatabase;

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

        $favoriteItems = Item::whereHas('favorite', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->get();

        $this->assertGreaterThan(0, $favoriteItems->count());

        $responseContent = $response->getContent();

        $displayedFavoritesCount = substr_count($responseContent, 'card-top');

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

        $favoriteItems = Favorite::where('user_id', $user->user_id)
            ->get()
            ->filter(function ($favorite) use ($user) {
                return $favorite->item->user_id != $user->user_id;
            });

        foreach ($favoriteItems as $favorite) {
            $response->assertSee($favorite->item->item_name);
        }
    }

    // 未認証の場合は何も表示されない
    public function testUnauthorizedNotSee()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('<div class="item-card"></div>');
    }
}
