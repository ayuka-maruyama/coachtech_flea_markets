<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function checkout($order_id)
    {
        $order = Order::findOrFail($order_id);

        Stripe::setApiKey(config('services.stripe.secret'));

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
            $order->update([
                'status' => 'completed',
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

        return redirect()->route('mypage.form.show', ['tab' => 'buy'])->with('message', '購入が完了しました。');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', '支払いがキャンセルされました。');
    }
}
