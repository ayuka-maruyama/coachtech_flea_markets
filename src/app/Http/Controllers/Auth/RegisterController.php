<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    // 登録フォームを表示するメソッド
    public function showRegisterFrom()
    {
        return view('auth.register');
    }

    // ユーザー登録を処理するメソッド
    public function handleRegister(RegisterRequest $request)
    {
        // ユーザーの作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ユーザー作成後にメール認証の通知を表示
        event(new Registered($user)); // 登録イベントの発火

        // メール認証の通知画面にリダイレクト
        return redirect()->route('register.thanks');
    }
}
