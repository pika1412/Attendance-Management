<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\BreakTime;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class AdminApplicationController extends Controller
{
    public function application(Request $request){
        $user = Auth::user();
        $page = $request->query('page','pending');

        if($page == 'pending'){
            $applications = Application::where('status','pending')->with('attendance.user')->get();
        }elseif($page == 'approved'){
            $applications = Application::where('status','approved')->with(['attendance.user'])->get();
        }else{
            $applications = collect();
        }

        return view('admin.stamp_list',compact('page','applications'));
    }


    public function index(){

    }
}
