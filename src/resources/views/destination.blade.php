@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/destination.css') }}">
@endsection

@section('content')
<div class="change-area">
    <h1 class="ttl">住所の変更</h1>
    <div class="change-form">
        <form action="{{ route('destination.store', ['item_id' => $item->item_id]) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="label" for="postal_number">郵便番号</label>
                <input class="form-input" id="postal_number" type="text" name="postal_number" value="{{ old('postal_number', $destination->postal_number) }}">
                <div class="error">
                    @error('postal_number')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="address">住所</label>
                <input class="form-input" id="address" type="text" name="address" value="{{ old('address', $destination->address) }}">
                <div class="error">
                    @error('address')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="building">建物</label>
                <input class="form-input" id="building" type="text" name="building" value="{{ old('building', $destination->building) }}">
                <div class="error">
                    @error('building')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/profile-change.js') }}" defer></script>
@endsection
