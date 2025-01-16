<?php

namespace Tests\Feature;

use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test06ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusers,itemsテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
    }

    // 商品名で部分一致検索ができる（検索商品名：コーヒー）
    public function testItemNameSearch()
    {
        $keyword = 'コーヒー';

        $response = $this->get('/?q=' . urlencode($keyword));

        $response->assertStatus(200);

        $response->assertSee($keyword);

        $response->assertDontSee('UnrelatedItem');
    }

    // 検索状態がマイリストでも保持される（検索商品名：ねぎ）
    public function testItemNameSearchMyList()
    {
        $keyword = 'ねぎ';

        $response = $this->get('/?q=' . urlencode($keyword));

        $response->assertStatus(200);

        $response->assertSee($keyword);

        $response = $this->get('/?tab=mylist&q=' . urlencode($keyword));

        $response->assertStatus(200);

        $response->assertSee($keyword);
    }
}
