<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\Profile;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function handleLogin(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $profile = Profile::where('user_id', $user->user_id)->first();

            // email_verified_at の確認
            if (!$user->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            // profileの確認
            if (!$profile) {
                return redirect()->route('profile.open');
            }

            // 上記条件がすべて不一致の場合
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form.show');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form.show');
    }
}
