@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-area area">

    <h1 class="ttl">会員登録</h1>

    <div class="register-form">

        <form action="{{ route('register.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label class="label" for="name">ユーザー名</label>
                <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}">
                <div class="error">
                    @error('name')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="label" for="email">メールアドレス</label>
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}">
                <div class="error">
                    @error('email')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="label" for="password">パスワード</label>
                <input class="form-input" id="password" type="password" name="password" value="{{ old('password') }}">
                <div class="error">
                    @error('password')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="label" for="password_confirmation">確認用パスワード</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation">
                <div class="error">
                    @error('password_confirmation')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">登録する</button>
            </div>

        </form>

        <p class="link login-link">
            <a href="{{ route('login.open') }}">ログインはこちら</a>
        </p>

    </div>
</div>
@endsection