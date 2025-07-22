<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Requests\LoginRequest;



class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view('auth.admin_login');
    }

    public function login(LoginRequest $request){
        $credential = $request->only(['email','password']);
        $user = User::where('email',$credential['email'])->first();

        if($user && $user->is_admin){
            if(Auth::attempt($credential)){

                return redirect()->route('admin.attendance_list');
            }
        }
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
