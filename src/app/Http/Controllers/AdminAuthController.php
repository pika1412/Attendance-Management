<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;



class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view('auth.admin_login');
    }

    public function login(Request $request){
        $credential = $request->only(['email','password']);

        if(Auth::guard('admin')->attempt($credential)){

            return redirect()->route('admin.attendance_list');
        }else{
            return back()->withErrors([
                'email' => 'ログインできませんでした。',
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
