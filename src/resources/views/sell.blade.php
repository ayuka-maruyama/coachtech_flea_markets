@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-area">
    <h1 class="ttl">商品の出品</h1>

    <form action="" method="post" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像表示部分 -->
        <div class="image-container">
            <h2 class="image-ttl">商品画像</h2>
            <div class="image-upload-container">
                <label for="image-input" id="select-button" class="select-button">
                    画像を選択する
                </label>
                <input type="file" id="image-input" class="image-input" accept="image/*" style="display: none;" />
                <div class="image-preview-container" id="image-preview-container" style="display: none;">
                    <img id="image-preview" class="image-preview" src="#" alt="選択された画像" />
                </div>
            </div>
        </div>

        <!-- 商品詳細情報部分 -->
        <div class="item-detail-container">
            <h2 class="detail-ttl">商品の詳細</h2>

            <!-- カテゴリー部分 -->
            <div class="category-container">
                <div class="category-select">
                    <h3 class="category-ttl">カテゴリー</h3>
                    <div class="categories">
                        @foreach($categories as $category)
                        <button type="button" class="category" data-category-id="{{ $category->category_id }}">{{ $category->category_name }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="condition-select">
                    <h3 class="condition-ttl">商品の状態</h3>
                    <select name="condition" id="condition" class="condition">
                        <option value="" selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="item-detail-container">
            <h2 class="detail-ttl">商品名と説明</h2>

            <!-- カテゴリー部分 -->
            <div class="item-container">
                <div class="item-name">
                    <h3 class="item-ttl">商品名</h3>
                    <input type="text" name="item_name" id="item_name" class="item_name">
                </div>
                <div class="item-description">
                    <h3 class="item-ttl">商品の説明</h3>
                    <textarea name="description" id="description" class="description"></textarea>
                </div>
                <div class="item-price">
                    <h3 class="item-ttl">販売価格</h3>
                    <div class="input-wrapper">
                        <input type="text" id="price" name="price" class="price">
                    </div>
                </div>
            </div>

            <button type="submit" class="submit">登録する</button>
        </div>

    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/sell.js') }}"></script>
@endsection