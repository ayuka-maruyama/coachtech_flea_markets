<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\Destination;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function open($item_id)
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.open');
        }

        $item = Item::findOrFail($item_id);

        // destinationテーブルに該当があればdestination
        // 該当なしなら、profile
        $destination = Destination::where('user_id', $user->user_id)
            ->where('item_id', $item_id)
            ->first();

        if (!$destination) {
            $destination = $user->profile;
        }

        return view('purchase', compact('user', 'item', 'destination'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();

        $validatedData = $request->validated();

        if ($validatedData['payment_method'] === 'card') {
            // クレジットカード払いの場合、仮注文データを作成
            try {
                $order = Order::create([
                    'user_id' => $user->user_id,
                    'item_id' => $item_id,
                    'payment_method' => $validatedData['payment_method'],
                    'destination' => $request->destination_id,
                    'status' => 'pending', // 仮注文のステータス
                ]);
            } catch (\Exception $e) {
                Log::error('購入処理エラー', ['exception' => $e]);
                return redirect()->route('home')->with('error', '購入処理中にエラーが発生しました。');
            }

            return redirect()->action([StripeController::class, 'checkout'], ['order_id' => $order->order_id]);
        } else {
            // コンビニ払いの場合、即時注文確定
            try {
                $order = Order::create([
                    'user_id' => $user->user_id,
                    'item_id' => $item_id,
                    'payment_method' => $validatedData['payment_method'],
                    'destination' => $request->destination_id,
                    'status' => 'completed', // 注文確定ステータス
                ]);

                $item = Item::find($item_id);
                if ($item) {
                    $item->update([
                        'stock_status' => 1,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('購入処理エラー', ['exception' => $e]);
                return redirect()->route('home')->with('error', '購入処理中にエラーが発生しました。');
            }

            return redirect()->route('mypage.open', ['tab' => 'buy'])->with('message', '購入が完了しました。');
        }
    }
}
