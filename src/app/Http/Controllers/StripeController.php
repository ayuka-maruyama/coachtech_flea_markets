<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;
use App\Models\Item;

class StripeController extends Controller
{
    public function checkout($order_id)
    {
        $order = Order::findOrFail($order_id);

        // Stripe APIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe Checkoutセッションの作成
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $order->item->item_name,
                    ],
                    'unit_amount' => $order->item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['order_id' => $order->order_id]),
            'cancel_url' => route('stripe.cancel'),
            'payment_intent_data' => [
                'description' => env('APP_NAME') . ' - Order #' . $order->order_id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        try {
            // 注文確定と在庫ステータスの更新
            $order->update([
                'status' => 'completed', // 注文確定ステータス
            ]);

            $item = Item::find($order->item_id);
            if ($item) {
                $item->update([
                    'stock_status' => 1,
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', '注文の確定処理中にエラーが発生しました。');
        }

        return redirect()->route('mypage.open', ['tab' => 'buy'])->with('message', '購入が完了しました。');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', '支払いがキャンセルされました。');
    }
}
