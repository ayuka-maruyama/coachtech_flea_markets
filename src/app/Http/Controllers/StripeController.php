<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;

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
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        return redirect()->route('purchase.complete');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', '支払いがキャンセルされました。');
    }
}
