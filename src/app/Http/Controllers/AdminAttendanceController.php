<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\Application;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;



class AdminAttendanceController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        $today = Carbon::parse($request->input('day', today()));
        $yesterday = $today->copy()->subDay();
        $tomorrow = $today->copy()->addDay();

        $todayAttendances = Attendance::with('user')->where('work_date',$today->toDateString())
        ->whereNotNull('start_time')->get();

        return view('admin.attendance_list',compact('today', 'yesterday', 'tomorrow','todayAttendances'));
    }

    public function showAdminDetail($id){
        $user = Auth::user();
        $attendance = Attendance::with(['user','breakTimes'])->findOrFail($id);

        return view('admin.attendance_detail',compact('attendance'));
    }

    public function updateDetail(AttendanceRequest $request,$id){
        $start = $request->input('start_time');
        $end = $request->input('end_time');
        $memo = $request->input('memo');
        $date = $request->input('working_date') ?? now()->toDateString();

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $start);
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $end);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'memo' => $request->input('memo'),
        ]);

        return redirect()->route('admin.attendance_detail',['id' => $attendance->id]);
    }

    public function staffList(){
        $users = User::where('is_admin',false)->get();

        return view('admin.staff_list',compact('users'));
    }

    public function staffAttendanceList(Request $request,$id){
        $user = User::findOrFail($id);

        $currentMonth = Carbon::parse($request->input('month',date('Y-m')));
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $datesInMonth = \Carbon\CarbonPeriod::create($currentMonth->copy()->startOfMonth(),$currentMonth->copy()->endOfMonth());

        $attendances = Attendance::where('user_id', $id)
        ->whereBetween('work_date', [$currentMonth->copy()->startOfMonth(), $currentMonth->copy()->endOfMonth()])
        ->with('breakTimes')
        ->get()
        ->keyBy('work_date');

        return view('admin.staff_attendance_list',compact('user','attendances','currentMonth', 'prevMonth', 'nextMonth', 'datesInMonth'));
    }

    public function exportCsv(Request $request, $id){
        $user = User::findOrFail($id);
        $monthInput = $request->input('month', Carbon::now()->format('Y-m')); // 例: "2025-07"
        $currentMonth = Carbon::parse($monthInput);


        $attendances = Attendance::where('user_id',$id)->whereBetween('work_date',[$currentMonth->copy()->startOfMonth(),$currentMonth->copy()->endOfMonth()])->with('breakTimes')->get();

        $csvHeader = [
            '日付','出勤','退勤','休憩','合計'];
        $temps = [];
        array_push($temps, $csvHeader);

        foreach($attendances as $attendance){
            $break = $attendance->breakTimes->first();

            $temp = [
                $attendance->work_date ?? '',
                $attendance->start_time ?? '',
                $attendance->end_time ?? '',
                optional($break)->start_break ?? '',
                optional($break)->end_break ?? '',
                $attendance->total_time_formatted ?? '',
            ];
            array_push($temps,$temp);
            }
        $stream = fopen('php://temp','r+b');
        foreach($temps as $temp){
            fputcsv($stream,$temp);
        }
        rewind($stream);
        $csv = str_replace(PHP_EOL,"\r\n",stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
        $now = new Carbon();
        $filename = "勤怠データ_{$user->name}_{$now->format('Ymd')}.csv";
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        );
        return response($csv, 200, $headers);
    }
}
