@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase-flex">
        <!-- 購入フォーム -->
        <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->item_id]) }}">
            @csrf

            <!-- 商品情報、購入情報選択エリア -->
            <div class="purchase-info">
                <!-- 商品情報 -->
                <div class="item-info">
                    <img class="item-img" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
                    <div class="item">
                        <p class="item-name">{{ $item->item_name }}</p>
                        <p class="item-price">&yen; <span class="price">{{ number_format($item->price) }}</span></p>
                    </div>
                </div>

                <!-- 支払方法選択 -->
                <div class="payment">
                    <h3 class="payment-ttl">支払い方法</h3>
                    <div class="select">
                        <select class="payment-select" name="payment_method" id="payment">
                            <option value="コンビニ">コンビニ支払い</option>
                            <option value="クレジット">クレジット支払い</option>
                        </select>
                        <svg class="toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z" />
                        </svg>
                    </div>
                </div>

                <!-- 配送先表示 -->
                <div class="address">
                    <div class="address-flex">
                        <h3 class="address-ttl">配送先</h3>
                        <a href="{{ route('profile.change', ['item_id' => $item->item_id]) }}" class="address-change">変更する</a>
                    </div>
                    <p class="profile-address">〒{{ $profile->postal_number }}</p>
                    <p class="profile-address">{{ $profile->address }}</p>
                    <p class="profile-address">{{ $profile->building }}</p>
                </div>

            </div>

            <!-- 商品情報、購入情報選択エリアで選択した内容を表示するエリア -->
            <div class="purchase-view">
                <div class="price-view">
                    <p class="price-ttl">商品代金</p>
                    <p class="item-price">&yen; <span class="view-price span">{{ number_format($item->price) }}</span></p>
                </div>
                <div class="payment-method"></div>
            </div>

            <!-- 購入ボタン -->
            <div class="submit-button">
                <button type="submit" class="purchase-btn">購入する</button>
            </div>
        </form>
        @if (count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/purchase.js') }}" defer></script>
@endsection