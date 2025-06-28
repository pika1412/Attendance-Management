<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}"/>
    @yield('css')
</head>
<body>
    <header class="header">
        <nav class="nav-list">
            <a class="header-logo" href="/"><img src="{{ asset('img/CoachTech_White 1.png')}}" alt="logo">
            </a>
        <ul class="nav-list-group">
            <li>
                <a href="/attendance_list" class="attendance-list">勤怠一覧</a>
            </li>
            <li>
                <a href="/staff_list" class="staff-list">スタッフ一覧</a>
            </li>
            <li><a href="/application_list" class="application-list">申請一覧</a></li>
            <li>
                <div class="logout-button">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit">ログアウト</button>
                </div>
                </form>
            </li>
        </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>