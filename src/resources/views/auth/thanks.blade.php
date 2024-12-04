@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/email-verify.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/thanks.css') }}">
@endsection

@section('content')
<div class="thanks-area">
    <h1 class="thanks-ttl">ご登録ありがとうございます！</h1>
    <p class="thanks-msg">
        会員登録を受け付けました。</br>
        現在は仮登録の状態です。</br>
        ご入力いただいたメールアドレス宛に、確認のメールをお送りしています。</br>
        メールに記載のURLをクリックし会員登録を完了させてください。</br>
        もし、メールが届いていない場合は、迷惑メールフォルダをご確認ください。</br>
    </p>
</div>
@endsection