<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Test01RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function testNameIsRequired()
    {
        $response = $this->post('/register', [
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);

        $errors = session('errors')->get('name');
        $this->assertEquals('お名前を入力してください', $errors[0]);
    }

    // emailが入力されていない場合、バリデーションメッセージが表示される
    public function testEmailIsRequired()
    {
        $response = $this->post('/register', [
            'name' => 'テスト登録',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');
        $this->assertEquals('メールアドレスを入力してください', $errors[0]);
    }

    // passwordが入力されていない場合、バリデーションメッセージが表示される
    public function testPasswordIsRequired()
    {
        $response = $this->post('/register', [
            'name' => 'テスト登録',
            'email' => 'newuser@example.com',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードを入力してください', $errors[0]);
    }

    // passwordが7文字以下の場合、バリデーションメッセージが表示される
    public function testPasswordMustBeAtLeast8Characters()
    {
        $response = $this->post('/register', [
            'name' => 'テスト登録',
            'email' => 'newuser@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors[0]);
    }

    // passwordとpassword_confirmationが不一致の場合、バリデーションメッセージが表示される
    public function testPasswordAndConfirmationPasswordMismatch()
    {
        $response = $this->post('/register', [
            'name' => 'テスト登録',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password1',
        ]);

        $response->assertSessionHasErrors(['password']);

        $errors = session('errors')->get('password');
        $this->assertEquals('パスワードと一致しません', $errors[0]);
    }

    // 全ての項目が入力され、会員情報が登録されたら、ログイン画面へ遷移される
    public function testUserRegistrationRedirectToLogin()
    {
        Mail::fake();

        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テスト登録',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/thanks');

        $this->assertDatabaseHas('users', [
            'name' => 'テスト登録',
            'email' => 'newuser@example.com',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
