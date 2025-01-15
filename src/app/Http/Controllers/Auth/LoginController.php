<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            if (!$user->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            if (!$profile) {
                return redirect()->route('profile.open');
            }

            return redirect()->route('home', ['tab' => 'mylist']);
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
