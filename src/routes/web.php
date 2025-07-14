<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminApplicationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\MiddlewareController;

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
Route::get('/register',[AuthController::class,'showRegisterForm']);
Route::post('/register',[AuthController::class,'register']);
//thanks
Route::get('/thanks',function () {
    return view('auth.thanks');
});
//ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//メール認証
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
$request->fulfill();
return redirect('/login');})->middleware(['auth', 'signed'])->name('verification.verify');

//認証後
Route::middleware(['auth','verified'])->group(function () {
Route::get('/attendance',[AttendanceController::class,'create'])->name('attendance.create');
//勤怠ステータス表示更新
Route::get('/working_status',[AttendanceController::class,'showWorkingStatus'])->name('working_status');//画面表示
Route::post('/working_status',[AttendanceController::class,'update'])->name('working_status');//ステータス変更
Route::get('/attendance-list',[AttendanceController::class,'index'])->name('attendance.index');//勤怠一覧
Route::get('/attendance/list/{id}',[AttendanceController::class,'show'])->name('attendance.show');//詳細ボタン
Route::get('/attendance/detail/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.detail');//詳細画面
Route::patch('attendance/{id}/approval',[AttendanceController::class,'updateDetail'])->name('attendance.updateDetail');//修正ボタン
Route::get('/stamp/{id}',[ApplicationController::class,'index'])->name('staff.stamp');//承認待ち画面
Route::get('/application',[ApplicationController::class,'application'])->name('application');//申請一覧
});

//管理者認証
Route::get('/admin/login',[AdminAuthController::class,'showLoginForm'])->name('admin.login');
Route::post('/admin/login',[AdminAuthController::class,'login'])->name('admin.login.submit');
Route::post('admin/logout',[AdminAuthController::class,'logout'])->name('admin.logout');

//認証後
Route::middleware(['auth','is_admin'])->prefix('admin')->name('admin.')->group(function(){
Route::get('/attendance/list',[AdminAttendanceController::class,'index'])->name('attendance_list');//勤怠一覧
Route::get('/attendance/{id}',[AdminAttendanceController::class,'showAdminDetail'])->name('attendance_detail');//詳細画面
Route::patch('attendance/{id}/approval',[AdminAttendanceController::class,'updateDetail'])->name('attendance.updateDetail');//修正ボタン
Route::get('/staff/list',[AdminAttendanceController::class,'staffList'])->name('staff_list');//スタッフ一覧
Route::get('attendance/staff/{id}',[AdminAttendanceController::class,'staffAttendanceList'])->name('staff_attendance_list');//スタッフ別勤怠一覧
Route::get('/stamp/correction_request/list',[AdminApplicationController::class,'application'])->name('stamp_list');//申請一覧
Route::get('/stamp_correction_request/approve/{attendance_correct_request}',[AdminApplicationController::class,'showAdminApproval'])->name('application_approval');//修正承認画面
Route::patch('/stamp_correction_request/approve/{attendance_correct_request}', [AdminApplicationController::class, 'approval'])->name('approval');
});//承認ボタンリダイレクト

