<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]);

            Auth::login($user);
            //このユーザーでログイン状態にする

            $user->sendEmailVerificationNotification();

            return redirect('/thanks');
    }

    public function ShowLoginForm(){
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            $user = Auth::user();

        if(!$user->hasVerifiedEmail()){
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'メールアドレスの認証が完了していません。',]);
        }
        return redirect()->intended('/working_status')->with('message', 'ログイン成功');
        }
        return redirect()->back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
