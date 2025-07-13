<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds =  DB::table('users')->where('is_admin',false)->pluck('id');

        $now = now();
        $year = $now->year;
        $month = $now->month;

        for($day = 1; $day <= 31; $day++){
            $date = \Carbon\Carbon::create($year,$month,$day);

            if($date->month !==$month) break;
            if($date->isSunday()) continue;

            foreach($userIds as $userId){
                $attendanceId = DB::table('attendances')->insertGetId([
                'user_id' => $userId,
                'work_date' => $date->toDateString(),
                'start_time' => $date->copy()->setTime(9, 0)->toDateTimeString(),
                'end_time'   => $date->copy()->setTime(18, 0)->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('breaks')->insert([
                'user_id' => $userId,
                'attendance_id' => $attendanceId,
                'start_break' => $date->copy()->setTime(12, 0),
                'end_break' => $date->copy()->setTime(13, 0),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        }
    }
}
