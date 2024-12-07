<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function open()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // email_verified_at の確認
            if ($user->email_verified_at) {
                return redirect('/mypage');
            } else {
                return redirect('/email/verify');
            }
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
