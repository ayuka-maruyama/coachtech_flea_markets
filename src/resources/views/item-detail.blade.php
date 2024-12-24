@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item-detail.css') }}">
@endsection

@section('content')
<div class="detail-flex">
    <div class="item-img">
        <img class="card-img" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
    </div>

    <div class="item-detail">
        <h1 class="item-name">{{ $item->item_name }}</h1>
        <p class="item-brand">{{ $item->brand }}</p>
        <p class="item-price">&yen;<span class="price">{{ number_format($item->price) }}</span>（税込）</p>

        <div class="image-flex">
            <div class="favorite-icon">
                <button class="favorite-btn {{ $isFavorited ? 'favorite' : '' }}" id="favorite-btn" data-item-id="{{ $item->item_id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="btn-image">
                        <path fill="#98999a" d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z" />
                    </svg>
                </button>
                <p class="count favorite-count">{{ $favoriteCount }}</p>
            </div>

            <div class="comment-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="btn-image">
                    <path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z" />
                </svg>
                <p class="count comment-count">{{ $commentCount }}</p>
            </div>

        </div>

        <form action="{{ route('purchase', ['item_id' => $item->item_id]) }}" method="get">

            <div class="submit-button">
                @if ($isPurchased)
                <button type="button" class="purchase-btn disabled" disabled>購入済み</button>
                @else
                <button type="submit" class="purchase-btn">購入する</button>
                @endif
            </div>
        </form>

        <div class="description">
            <h2 class="description-ttl">商品説明</h2>
            <p class="detail">{{ $item->description }}</p>
        </div>

        <div class="condition-area">
            <h2 class="info-ttl">商品の情報</h2>
            <div class="category">
                <h3 class="category-ttl">カテゴリー</h3>
                @foreach ($item->categories as $category)
                <p class="select-category">{{ $category->category_name }}</p>
                @endforeach
            </div>
            <div class="condition">
                <h3 class="condition-ttl">商品の状態</h3>
                <p class="item-condition">{{ $item->condition }}</p>
            </div>
        </div>

        <div class="comment-view">
            <h2 class="comment-ttl">コメント（{{ $commentCount }}）</h2>
            @foreach ($comments as $comment)
            <div class="comment-area">
                <div class="comment-flex">
                    @if ($comment->user->profile && $comment->user->profile->profile_image)
                    <img class="profile-img" src="{{ asset('storage/profile_images/' . $comment->user->profile->profile_image) }}" alt="Profile Image">
                    @else
                    <div class="profile-img default-profile-image"></div>
                    @endif
                    <p class="name">{{ $comment->user->name }}</p>
                </div>
                <p class="comment">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>

        <div class="comment-sent">
            <h3 class="sent-ttl">商品へのコメント</h3>
            <form action="{{ route('comment', ['item_id' => $item->item_id]) }}" method="post">
                @csrf
                <textarea class="comment-textarea" name="comment" id="comment" rows="5"></textarea>

                <div class="error">
                    @error('comment')
                    <p>{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="submit">コメントを送信する</button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/favorite.js') }}" defer></script>
@endsection