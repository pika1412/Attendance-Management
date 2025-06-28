<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
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

            $user->sendEmailVerificationNotification();

            Auth::login($user);
            //このユーザーでログイン状態にする
            return redirect('/thanks');
    }
}
