<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;


class AdminAttendanceController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        $currentMonth = \Carbon\Carbon::parse($request->input('month', date('Y-m')));
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $today = \Carbon\Carbon::today();
        $todayAttendances = Attendance::with('user')->where('work_date',$today)
        ->whereNotNull('start_time')->get();

        return view('admin.attendance_list',compact('currentMonth', 'prevMonth', 'nextMonth', 'today','todayAttendances'));
    }

    public function showAdminDetail($id){
        $user = Auth::user();
        $attendance = Attendance::with('user','breakTimes')->findOrFail($id);

        return view('admin.attendance_detail',compact('attendance'));
    }

    public function updateDetail(AttendanceRequest $request,$id){
        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $memo = $request->input('memo');
        $date = $request->input('working_date') ?? now()->toDateString();

        $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $start);
        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $end);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'memo' => $request->input('memo'),
        ]);

        return redirect()->route('',['id' => $attendance->id]);
    }
}
