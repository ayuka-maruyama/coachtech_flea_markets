@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-content">

    <div class="user-area">
        @if($profile->profile_image)
        <img class="profile-img" src="{{ asset($profile->profile_image) }}" alt="Profile Image">
        @else
        <img class="profile-img" src="{{ asset('image/default.jpg') }}" alt="Default Profile Image">
        @endif
        <h3 class="user-name">{{ $user->name }}</h3>
        <form action="{{ route('profile.open') }}" method="get">
            <button type="submit" class="btn">プロフィールを編集</button>
        </form>
    </div>

    <div class="page">
        <a href="/mypage?page=sell" class="sell-item @if($page === 'sell') span @endif">出品した商品</a>
        <a href="/mypage?page=buy" class="buy-item @if($page === 'buy') span @endif">購入した商品</a>
    </div>

    <div class="item-card">
        @if($items->isEmpty())
        <p>該当する商品がありません。</p>
        @else
        @foreach($items as $item)
        <div class="card">
            <form action="{{ url('/item/' . $item->item_id) }}" method="post">
                @csrf
                <button type="submit">
                    <div class="card-top">
                        <img class="card-img" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
                    </div>
                    <div class="item-description">
                        <p class="item-name">{{ $item->item_name }}</p>
                        <p class="stock-status @if($item->stock_status == 1) sold @endif">
                            @if($item->stock_status == 1)
                            Sold
                            @endif
                        </p>
                    </div>
                </button>
            </form>
        </div>
        @endforeach
        @endif
    </div>

</div>
@endsection
