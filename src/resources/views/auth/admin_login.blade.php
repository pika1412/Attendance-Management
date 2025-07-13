@extends('layouts.admin_default')
@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css')}}"/>
@endsection
@section('content')

<div class="login__content">
    <div class="login__form">
        <div class="login__title">
            <h2>ログイン</h2>
        </div><!---login__title-->
<!--ログインフォーム-->
        <form action="{{ route('admin.login.submit') }}" method="post" class="form">
        @csrf
<!--メールアドレス-->
            <div class="form-group">
                <label>メールアドレス</label>
                <input type="email" name="email" value="{{ old ('email') }}">
                @error('email')
                    <div class="error">{{$message}}</div>
                @enderror
            </div>
<!--パスワード-->
            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="password">
                @error('password')
                    <div class="error">{{$message}}</div>
                @enderror
            </div><!--form-group-ttl-->
        <div class="login__button">
        <button class="login__button-submit" type="submit">管理者ログインする</button>
        </div><!--login-button-->
    </form>
    </div><!--login__form-->
</div><!--login__content-->
@endsection