<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/staff.css')}}"/>
    @yield('css')
</head>
<body>
    <header class="header">
        <nav class="nav-list">
            <a class="header-logo" href="{{ route('working_status') }}"><img src="{{ asset('img/CoachTech_White 1.png')}}" alt="logo">
            </a>
        <ul class="nav-list-group">
            <li>
                <a href="/working_status" class="attendance">勤怠</a>
            </li>
            <li>
                <a href="{{ route('attendance.index') }}" class="">勤怠一覧</a>
            </li>
            <li><a href="/attendance_detail" class="">申請</a></li>
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