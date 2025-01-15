<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // テスト開始前にusersテーブルのシーディングデータを反映させる
    public function setUp(): void
    {
        parent::setup();

        $this->seed(UserSeeder::class);
    }

    // ログアウトができる
    public function testLogout()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
    }
}
