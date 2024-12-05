@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-area">
    <h1 class="ttl">ログイン</h1>
    <div class="login-form">
        <form action="{{ route('login.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="label" for="email">ユーザー名／メールアドレス</label>
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
                <button type="submit" class="submit-btn">ログインする</button>
            </div>
        </form>
        <p class="register-link">
            <a href="{{ route('register.open') }}">会員登録はこちら</a>
        </p>
    </div>
</div>
@endsection