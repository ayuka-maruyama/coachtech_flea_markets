@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase-complete.css') }}">
@endsection

@section('content')
<div class="complete-area">
    <h1 class="ttl">購入いただきありがとうございます！</h1>
    <p class="message">
        ご指定いただいた支払方法にて、商品代金の支払いを承りました。</br>
        商品がお手元に届くまで、今しばらくお待ちください。
    </p>
    <p class="link-area">
        <a href="{{ route('home') }}" class="link">商品一覧ページへ戻る</a>
    </p>
</div>
@endsection