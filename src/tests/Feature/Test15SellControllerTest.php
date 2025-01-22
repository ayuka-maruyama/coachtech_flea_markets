<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test15SellControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
    }

    // 商品出品画面で必要な情報が保存できる
    public function testItCanStoreAnItem()
    {
        $user = User::first();
        $this->actingAs($user);

        $categories = Category::whereIn('category_id', [3, 4])->get();

        $image = UploadedFile::fake()->image('item.jpg');

        $requestData = [
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '1000',
            'description' => 'これはテスト商品です。',
            'condition' => '良好',
            'category_id' => '[3, 4]',
            'item_image' => $image,
            'user_id' => $user->user_id,
        ];

        $response = $this->actingAs($user)->post(route('sell.store'), $requestData);

        $response->assertRedirect(route('mypage.form.show', ['page' => 'sell']));
        $response->assertSessionHas('success', '商品が正常に登録されました。');

        $this->assertDatabaseHas('items', [
            'item_name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '1000',
            'description' => 'これはテスト商品です。',
            'condition' => '良好',
            'user_id' => $user->user_id,
        ]);

        $item = \App\Models\Item::where('item_name', 'テスト商品')->first();
        $this->assertNotNull($item);
        $this->assertEqualsCanonicalizing($categories->pluck('category_id')->toArray(), $item->categories->pluck('category_id')->toArray());

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->item_id,
                'category_id' => $category->category_id,
            ]);
        }

        $this->assertFileExists(public_path($item->item_image));
    }
}
