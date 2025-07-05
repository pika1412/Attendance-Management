<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    public function showWorkingStatus(){
        $user = Auth::user();
        $today =  today();
        $attendance = Attendance::where('user_id',Auth::id())
        ->where('work_date', $today)
        ->first();

        $status = $attendance?->status ?? 'off_duty';

        return view('staff.working_status', compact('status','attendance'
        ));
    }

    public function show($id) {
        $attendance = Attendance::findOrFail($id);
        return view('staff.attendance_detail', compact('attendance'));
    }

    public function create(){
        $status = 'off_duty';
        $attendance = 'null';

        return view('staff.working_status', compact(
        'status','attendance'));
    }

    public function update(Request $request){
        $action = $request->input('action');
        $user = Auth::user();
        if(!$user){
            return redirect()->route('login');
        }
        $today = today();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'work_date' => $today],
            ['status' => 'off_duty']
        );

        switch($action){
            case 'working':
                if(!$attendance->start_time){
                $attendance->update   (['start_time' =>  now(),'status' => 'working',]);
                }
            break;

            case 'finished':
                $attendance->update
                (['end_time' => now(),
                'status' => 'finished',
                ]);
            break;

            case 'start_break':
                $attendance ->update(['status' => 'on_break']);

                BreakTime::create([
                    'user_id' => $user->id,
                    'attendance_id' =>$attendance->id,
                    'start_break' => now(),]);
                break;

            case 'end_break':
                $attendance->update(['status' => 'working']);
                $break = BreakTime::where('attendance_id',$attendance->id)
                ->whereNull('end_break')
                ->latest()
                ->first();
            if($break){
                $break->update(['end_break' => now()]);
            }
            break;
        }
        return redirect()->route('working_status');
    }

    public function index(Request $request){
        $user = Auth::user();

        $month = $request->input('month', now()->format('Y-m'));
        $currentMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::where('user_id' , $user->id)
        ->whereBetween('work_date',[$currentMonth,$endOfMonth])->get()
        ->keyBy('work_date');

        $datesInMonth = collect(CarbonPeriod::create(
            Carbon::parse($month)->startOfMonth(),
            Carbon::parse($month)->endOfMonth()
        ));

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        return view('staff.attendance-list', [
            'attendances' => $attendances,
            'datesInMonth'=> $datesInMonth,
            'currentMonth' => $currentMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
        ]);
    }

    public function showDetail($id){
        $attendance = Attendance::with('user','breakTimes')->findOrFail($id);

        return view('staff.attendance_detail',compact('attendance'));
    }

}
