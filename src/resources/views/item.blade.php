@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-content">
    <div class="tab">
        <a href="{{ route('home') }}" class="suggest-item @if(Request::path() == '/' && !Request::query('tab')) span @endif">おすすめ</a>
        <a href="/?tab=mylist{{ request('q') ? '&q=' . request('q') : '' }}"
            class="my-list @if(Request::query('tab') == 'mylist') span @endif">
            マイリスト
        </a>
    </div>
    <div class="item-card">
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
    </div>
</div>
@endsection

@section('js')
@endsection