<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;


class MypageController extends Controller
{
    public function open(Request $request)
    {
        // ログイン状況を確認して、未ログインならログイン画面へ遷移
        if (!$user = Auth::user()) {
            return redirect()->route('login.open');
        }

        if (!$user->profile) {
            return redirect()->route('profile.open')->with('message', 'プロフィールを設定してください');
        }

        // ログイン済ユーザーの登録済みプロフィールを取得
        $profile = $user->profile;

        // クエリパラメータ `tab` の値を取得（デフォルトは 'sell'）
        $tab = $request->query('tab', 'sell');

        // データの取得
        if ($tab === 'buy') {
            // 購入した商品を取得
            $items = Order::where('user_id', $user->user_id)
                ->with('item') // 必要に応じて関連する商品の情報を取得
                ->get()
                ->pluck('item'); // Order に関連する Item を取得
        } else {
            // 出品した商品を取得
            $items = Item::where('user_id', $user->user_id)->get();
        }

        return view('mypage', compact('user', 'profile', 'items', 'tab'));
    }
}
