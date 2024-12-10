<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/destyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    <title>COACHTECHフリマ</title>
</head>

<body>
    <header class="header">
        <div class="log">
            <a href="{{ url('/') }}"> <!-- controller作成、web.php記述後、urlの部分をroute('home')に修正する -->
                <img class="log-img" src="{{ asset('image/logo.svg') }}" alt="logo">
            </a>
        </div>

        @if(!in_array(Request::path(), ['register', 'login', 'thanks', 'email/verify'])) <div class="search-bar">
            <form action="/search" method="get">
                <input class="input-area" type="text" name="q" placeholder="なにをお探しですか？" value="{{ request('q') }}">
            </form>
        </div>

        <nav class="nav">
            <ul>
                @if(Auth::check())
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <li class="logout">
                        <button class="nav-btn" type="submit">ログアウト</button>
                    </li>
                </form>
                @else
                <li class="login">
                    <a href="{{ route('login.open') }}" class="nav-btn">ログイン</a>
                </li>
                @endif
                <li class="my-page">
                    <a href="/mypage" class="nav-btn">マイページ</a>
                </li>
                <li class="exhibit">
                    <a href="/sell" class="nav-btn">出品</a>
                </li>
            </ul>
        </nav>
        @endif
    </header>

    <main class="main">
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')

</body>

</html>