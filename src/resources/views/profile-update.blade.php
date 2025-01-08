@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/profile.css') }}">
@endsection

@section('content')
<div class="profile-area">
    <h1 class="ttl">プロフィール設定</h1>
    <div class="profile-form">
        <form action="{{ route('update.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH') <!-- PATCH メソッド指定 -->

            <div class="img-area">
                <div id="preview-area" class="preview-area">
                    <!-- プロフィール画像が存在する場合はそれを表示、ない場合はデフォルト画像 -->
                    <img id="image-preview"
                        class="image-preview"
                        src="{{ $user->profile && $user->profile->profile_image ? asset($user->profile->profile_image) : asset('image/default.jpg') }}"
                        alt="プロフィール画像">
                </div>

                <label for="image" class="file-upload">
                    画像を選択
                </label>
                <input type="file" name="profile_image" id="image" style="display: none;">
                <div class="error">
                    @error('profile_image')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="label" for="profile_name">ユーザー名</label>
                <input class="form-input" id="profile_name" type="text" name="profile_name" value="{{ old('profile_name', $user->profile->profile_name) }}">
                <div class="error">
                    @error('profile_name')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="postal_number">郵便番号</label>
                <input class="form-input" id="postal_number" type="text" name="postal_number" value="{{ old('postal_number', $user->profile->postal_number) }}">
                <div class="error">
                    @error('postal_number')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="address">住所</label>
                <input class="form-input" id="address" type="text" name="address" value="{{ old('address', $user->profile->address) }}">
                <div class="error">
                    @error('address')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="building">建物</label>
                <input class="form-input" id="building" type="text" name="building" value="{{ old('building', $user->profile->building) }}">
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
<script src="{{ asset('js/profile.js') }}" defer></script>
@endsection