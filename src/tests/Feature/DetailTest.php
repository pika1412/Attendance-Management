<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DetailTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_名前がユーザの氏名(){
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $name = $attendance->user->name;

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);
        $response->assertSee($name);
    }

    public function test_日付が選択した日(){
        $user = User::find(2);
        $this->actingAs($user);

        $selectedDate = Carbon::create(2025,7,1);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => $selectedDate,
        ]);

        $work_date = $selectedDate->format('n月j日');

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);
        $response->assertSee($work_date);
    }

    public function test_出勤退勤が打刻と一致(){
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::where(
            'user_id',$user->id)->first();

        $start_time = Carbon::parse($attendance->start_time)->format('H:i');
        $end_time = Carbon::parse($attendance->end_time)->format('H:i');

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertSee($start_time);
        $response->assertSee($end_time);
    }

    public function test_休憩が打刻と一致(){
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::where(
            'user_id',$user->id)->first();

        $break = BreakTime::where('attendance_id',$attendance->id)
            ->whereNotNull('end_break')
            ->latest()
            ->first();

        $response = $this->get('/attendance/detail/' . $attendance->id);

        if($break){
            $start_break = Carbon::parse($break->start_break)->format('H:i');
            $end_break = Carbon::parse($break->end_break)->format('H:i');
            }

        $response->assertSee($start_break);
        $response->assertSee($end_break);
    }
}
