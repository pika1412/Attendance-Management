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

    public function showAdminApproval($attendance_correct_request){
        $user = Auth::user();
        $attendance = Attendance::with(['user','breakTimes'])->findOrFail($attendance_correct_request);
        $application = Application::where('attendance_id',$attendance->id)->first();
        $status = $application?->status??null;

        return view('admin.application_approval',compact('attendance','status'));
    }

    public function approval(Request $request,$attendance_correct_request){
        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $memo = $request->input('memo');
        $date = $request->input('working_date') ?? now()->toDateString();

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $start);
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $end);

        $attendance = Attendance::findOrFail($attendance_correct_request);
        $attendance->update([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'memo' => $request->input('memo'),
        ]);

        $application = Application::where('attendance_id',$attendance->id)->first();

        if($application){
            $application->status = 'approved';
            $application->save();
        }
        $status = $application?->status??null;

        return view('admin.application_approval',compact('attendance','start','end','memo','date','startDateTime','application','status'));
    }
}
