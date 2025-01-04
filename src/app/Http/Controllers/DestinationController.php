<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\DestinationRequest;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function open($item_id)
    {
        // profile-change.blade.phpに初期登録したProfileデータを渡す
        if (!$user = Auth::user()) {
            return redirect()->route('login.open');
        }

        $item = Item::findOrFail($item_id);
        $destination = $user->profile;

        return view('destination', compact('user', 'item', 'destination'));
    }

    public function update(DestinationRequest $request, $item_id)
    {
        // 購入画面で送付先変更が選択され、更新するボタンが押されたときに、Destinationsテーブルに保存する
        // 複数回更新されることを想定して、destinationsテーブルにuser_id,order_idが同じものがあれば更新、なければ新規作成するようにする
        // 保存するカラム　user_id,item_id,postal_number,address,building

        $user = Auth::user();

        $destination = Destination::where('user_id', $user->user_id)
            ->where('item_id', $item_id)
            ->first();

        if ($destination) {
            $destination->update([
                'postal_number' => $request->postal_number,
                'address' => $request->address,
                'building' => $request->building,
            ]);
        } else {
            Destination::create([
                'user_id' => $user->user_id,
                'item_id' => $item_id,
                'postal_number' => $request->postal_number,
                'address' => $request->address,
                'building' => $request->building,
            ]);
        }

        return redirect()->route('purchase', ['item_id' => $item_id])->with('message', '送付先を変更しました');
    }
}
