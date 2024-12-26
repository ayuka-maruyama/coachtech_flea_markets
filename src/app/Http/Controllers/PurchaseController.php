<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function open($item_id)
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.open');
        }

        $item = Item::findOrFail($item_id);
        $profile = $user->profile;


        return view('purchase', compact('user', 'item', 'profile'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $validatedData = $request->validated();

        try {
            // 注文の作成
            $order = Order::create([
                'user_id' => $user->user_id,
                'item_id' => $item_id,
                'payment_method' => $validatedData['payment_method'],
                'postal_number' => $profile->postal_number,
                'address' => $profile->address,
                'building' => $profile->building,
            ]);

            $item = Item::find($item_id);
            if ($item) {
                $item->update([
                    'stock_status' => 1,
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', '購入処理中にエラーが発生しました。');
        }

        return redirect()->route('home')->with('message', '購入が完了しました。');
    }
}
