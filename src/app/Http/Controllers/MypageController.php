<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function showMypageForm(Request $request)
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.form.show');
        }

        if (!$user->profile) {
            return redirect()->route('profile.open', ['page' => 'sell'])->with('message', 'プロフィールを設定してください');
        }

        $profile = $user->profile;

        $page = $request->query('page', 'sell');

        $items = collect();

        if (
            $page === 'buy'
        ) {
            $items = Order::where('user_id', $user->user_id)
                ->with('item')
                ->get()
                ->pluck('item');
        } elseif ($page === 'sell') {
            $items = Item::where('user_id', $user->user_id)->get();
        }

        return view('mypage', compact('user', 'profile', 'items', 'page'));
    }
}
