<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusersテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->seed(UserSeeder::class);
    }

    // emailが入力されていない場合、バリデーションメッセージが表示される
    public function testEmailIsRequired()
    {
        $response = $this->post('/login', [
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');
        $this->assertEquals('メールアドレスを入力してください', $errors[0]);
    }

    // passwordが入力されていない場合、バリデーションメッセージが表示される
    public function testPasswordIsRequired()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードを入力してください', $errors[0]);
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function testEmailOrPasswordMismatch()
    {
        $response = $this->post('/login', [
            'email' => 'test1@example.com',
            'password' => 'password1',
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');
        $this->assertEquals('ログイン情報が登録されていません', $errors[0]);
    }

    // 全ての項目が入力され、会員情報が登録されたら、ログイン画面へ遷移される
    public function testUserLoginToRedirect()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/mypage/profile');

        $this->assertAuthenticated();
    }
}
