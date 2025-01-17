<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Test09CommentTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusers,itemsテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->artisan('migrate:fresh');

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE comments AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE profiles AUTO_INCREMENT = 1;');

        $this->seed(UserSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(CommentSeeder::class);
        $this->seed(ProfileSeeder::class);
    }

    // ログイン済の場合、コメントの送信ができコメント件数が増加する
    public function testSentCommentAndCommentIncrease()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(1);
        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);
        $initialCommentCount = $item->comment()->count();
        $response->assertSee('<p class="count comment-count">' . $initialCommentCount . '</p>', false);

        $comment = 'あいうえお';

        $response = $this->post('/item/' . $item->item_id . '/comment', [
            'comment' => $comment,
            'item_id' => $item->item_id,
            'user_id' => $user->user_id,
        ]);

        $response = $this->get('/item/' . $item->item_id);
        $response->assertStatus(200);

        $updatedCommentCount = $item->comment()->count();

        $response->assertSee('<p class="count comment-count">' . $updatedCommentCount . '</p>', false);

        $this->assertEquals($initialCommentCount + 1, $updatedCommentCount);
        $this->assertDatabaseHas('comments', [
            'comment' => $comment,
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
        ]);
    }

    // 未ログインユーザーはコメントを送信できない
    public function testUnauthorizedNotSentComment()
    {
        $item = Item::find(1);

        $comment = 'あいうえお';

        $response = $this->post('/item/' . $item->item_id . '/comment', [
            'comment' => $comment,
            'item_id' => $item->item_id,
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'comment' => $comment,
            'item_id' => $item->item_id,
        ]);
    }

    // コメントが入力されていない場合にバリデーションメッセージが表示される
    public function testSentCommentNotEntered()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(1);

        $response = $this->post('/item/' . $item->item_id . '/comment', [
            'comment' => '',
            'item_id' => $item->item_id,
            'user_id' => $user->user_id,
        ]);

        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);

        $this->assertDatabaseMissing('comments', [
            'comment' => '',
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
        ]);
    }

    // コメントが256文字以上入力されている場合にバリデーションメッセージが表示される
    public function testSentCommentOver256Characters()
    {
        $user = User::first();
        $this->actingAs($user);

        $item = Item::find(1);

        $comment = '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890';

        $response = $this->post('/item/' . $item->item_id . '/comment', [
            'comment' => $comment,
            'item_id' => $item->item_id,
            'user_id' => $user->user_id,
        ]);

        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以内で入力してください',
        ]);

        $this->assertDatabaseMissing('comments', [
            'comment' => $comment,
            'user_id' => $user->user_id,
            'item_id' => $item->item_id,
        ]);
    }
}
