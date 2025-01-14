<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Destination;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id)
    {
        if (!$user = Auth::user()) {
            return redirect()->route('login.form.show');
        }

        if (!$user->profile) {
            return redirect()->route('profile.open')->with('message', 'プロフィールを設定してください');
        }

        $item = Item::findOrFail($item_id);

        $destination = Destination::where('user_id', $user->user_id)
            ->where('item_id', $item_id)
            ->first();

        if (!$destination) {
            $destination = $user->profile;
        }

        return view('purchase', compact('user', 'item', 'destination'));
    }

    public function handlePurchase(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();

        $validatedData = $request->validated();

        if (!$request->destination_id) {
            Destination::create([
                'user_id' => $user->user_id,
                'item_id' => $item_id,
                'postal_number' => $user->profile->postal_number,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ]);
        }

        $destinationId = Destination::where('user_id', $user->user_id)
            ->where('item_id', $item_id)
            ->value('destination_id');

        if ($validatedData['payment_method'] === 'card') {
            try {
                $order = Order::create([
                    'user_id' => $user->user_id,
                    'item_id' => $item_id,
                    'payment_method' => $validatedData['payment_method'],
                    'destination_id' => $destinationId,
                    'status' => 'pending',
                ]);
            } catch (\Exception $e) {
                Log::error('購入処理エラー', ['exception' => $e]);
                return redirect()->route('home')->with('error', '購入処理中にエラーが発生しました。');
            }

            return redirect()->action([StripeController::class, 'checkout'], ['order_id' => $order->order_id]);
        } else {
            try {
                $order = Order::create([
                    'user_id' => $user->user_id,
                    'item_id' => $item_id,
                    'payment_method' => $validatedData['payment_method'],
                    'destination_id' => $destinationId,
                    'status' => 'completed',
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

            return redirect()->route('mypage.form.show', ['page' => 'buy'])->with('message', '購入が完了しました。');
        }
    }
}
