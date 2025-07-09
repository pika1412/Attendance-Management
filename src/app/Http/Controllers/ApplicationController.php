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

class ApplicationController extends Controller
{
    public function index($id){
        $attendance = Attendance::where('id',$id)->where('user_id',auth()->id())->with('breakTimes','user')->firstOrFail();

        return view('staff.stamp',compact('attendance'));
    }

    public function application(Request $request){
        $user = auth()->user();
        $page = $request->query('page','pending');

        if($page == 'pending'){
            $applications = Application::where('user_id',$user->id)->where('status','pending')->with('attendance','user')->get();
        }elseif($page == 'approved'){
            $applications = Application::where('user_id',$user->id)->where('status','approved')->with(['attendance', 'user'])->get();
        }else{
            $applications = collect();
        }

        return view('staff.stamp-list',compact('applications','user','page'));
    }
}
