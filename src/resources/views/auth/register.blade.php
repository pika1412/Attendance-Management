@extends('layouts.default')
@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css')}}">
@endsection
@section('content')

<div class="register-form__content">
    <div class="register-form">
            <h1 class="register-form__header">会員登録</h1>
<!--フォーム-->
        <form action="/register" method="post" class="form">
        @csrf
            <div class="form-group">
<!--ユーザー名-->
                <div class="form-group-ttl">
                <label>名前
                    <input type="text" name="name" value="{{ old('name') }}"></label>
                    @error('name')
                    <div class="error">{{$message}}
                    </div>
                    @enderror
                </div><!--form-group-ttl-->
<!--メールアドレス-->
                <div class="form-group-ttl">
                    <label>メールアドレス
                    <input type="text" name="email" value="{{ old('email') }}"></label>
                    @error('email')
                    <div class="error">{{$message}}</div>
                    @enderror
                </div><!--form-group-ttl-->
<!--パスワード-->
                <div class="form-group-ttl">
                    <label>パスワード
                    <input type="password" name="password"></label>
                    @error('password')
                    <div class="error">{{$message}}</div>@enderror
                </div><!--form-group-ttl-->
<!--確認用パスワード-->
                <div class="form-group-ttl">
                    <label>確認用パスワード
                    <input type="password" name="password_confirmation"></label>
                </div><!--form-group-ttl-->
<!--登録ボタン-->
        <div class="register__button">
            <button class="register__button-submit" type="submit">登録する</button>
        </div><!--register__button-->
        </form>
        </div><!--form-group-->
        <div class="login">
        <a href="/login">ログインはこちら</a>
        </div>
</div><!--register-form__content-->

@endsection