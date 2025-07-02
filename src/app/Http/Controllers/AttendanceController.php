<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Breaks;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    public function show(){
        $user = Auth::user();
        $today =  today();
        $attendance = Attendance::where('user_id',Auth::id())->where('work_date',$today)->first();
        $status = $attendance?->status ?? 'off_duty';

        return view('staff.working_status', compact('status','attendance'
        ));
    }

    public function create(){
        $status = 'off_duty';
        $attendance = 'null';

        return view('staff.working_status', compact(
        'status','attendance'));
    }

    public function index(){
        return view('admin.attendance_list');
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
                $attendance->update   (['start_time' =>  now(),'status' => 'working',]);
            break;

            case 'finished':
                $attendance->update
                (['end_time' => now(),
                'status' => 'finished',
                ]);
            break;

            case 'start_break':
                $attendance ->update(['status' => 'on_break']);

                Breaks::create([
                    'user_id' => $user->id,
                    'attendance_id' =>$attendance->id,
                    'start_break' => now(),]);
                break;

            case 'end_break':
                $attendance->update(['status' => 'working']);
                $break = Breaks::where('attendance_id',$attendance->id)
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
}
