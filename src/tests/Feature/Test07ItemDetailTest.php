<?php

namespace Tests\Feature;

use App\Models\CategoryItem;
use App\Models\Item;
use App\Models\Comment;
use Database\Seeders\CategoryItemSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\FavoriteSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test07ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusers,itemsテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE favorites AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE comments AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE profiles AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(CategoryItemSeeder::class);
        $this->seed(FavoriteSeeder::class);
        $this->seed(CommentSeeder::class);
        $this->seed(ProfileSeeder::class);
    }

    // 全ての情報が商品詳細画面に表示される
    public function testItemDetailPageDisplaysAllInformation()
    {
        $item = Item::find(1);
        $comments = Comment::where('item_id', 1)->get();
        $categories = CategoryItem::where('item_id', 1)->get();

        $response = $this->get('/item/' . $item->item_id);

        $response->assertStatus(200);

        $response->assertSee($item->item_name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->favorite->count());
        $response->assertSee($comments->count());
        $response->assertSee($item->description);
        $response->assertSee($categories->pluck('category_name')->join(', '));
        $response->assertSee($item->condition);

        foreach ($comments as $comment) {
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->comment);
        }
    }

    public function testCategoriesShow()
    {
        $item = Item::find(1);

        $categories = $item->categories;

        $response = $this->get('/item/' . $item->item_id);

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->category_name);
        }
    }
}
