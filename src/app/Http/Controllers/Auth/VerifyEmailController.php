<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    // メール確認通知の表示ルート
    public function confirmation()
    {
        return view('auth.verify-email');
    }

    // メール確認処理ルート
    public function __invoke(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('profile');
        }

        if ($request->hasValidSignature()) {
            $user->markEmailAsVerified();
            Auth::login($user);
            return redirect()->route('profile');
        }
        abort(403, 'このリンクは無効です');
    }

    // 再送信ルート
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back();
    }
}
