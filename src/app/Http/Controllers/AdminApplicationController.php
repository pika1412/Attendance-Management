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
use App\Http\Requests\AttendanceRequest;

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

    public function showAdminApproval($applicationId){
        $application = Application::with('attendance.user', 'attendance.breakTimes')->findOrFail($applicationId);
        $attendance = $application->attendance;
        $status = $application->status;
        return view('admin.application_approval', compact('attendance','status','application'));
    }

    public function approval(AttendanceRequest $request,$applicationId){
        $application = Application::with('attendance')->findOrFail($applicationId);

        if (!$application->attendance) {
            return redirect()->route('admin.stamp_list')->with('error', '承認対象の勤怠が見つかりませんでした。');
        }

        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $memo = $request->input('memo');
        $date = $request->input('working_date') ?? now()->toDateString();

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $start);
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $end);

        $attendance = $application->attendance;
        $attendance->update([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'memo' => $request->input('memo'),
        ]);
        $attendance->refresh();

        $application->status = 'approved';
        $application->save();

        $status = $application?->status??null;

        return redirect()->route('admin.application_approval',['applicationId' => $application->id])->with('success', '申請を承認しました。');
    }
}
