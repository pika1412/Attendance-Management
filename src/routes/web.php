<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//認証
Route::get('/register',[RegisterController::class,'showRegisterForm']);
Route::post('/register',[RegisterController::class,'register']);

//thanks
Route::get('/thanks',function () {
    return view('auth.thanks');
});

//メール認証
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/working_status');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/working_status', function () {
    return 'メール認証完了しました！';
});

//勤怠登録
Route::get('/attendance',[AttendanceController::class,'create'])->name('attendance.create');
Route::post('/attendance',[AttendanceController::class,'store'])->name('attendant.store');

//管理者画面
Route::get('/attendance-list',[AttendanceController::class,'index']);