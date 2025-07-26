<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Application;
use App\Models\BreakTime;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApplicationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_出勤が退勤より後()
    {
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);

        $start_time = '10:00';
        $end_time = '9:00';

        $response = $this->patch('/attendance/' . $attendance->id .'/approval',[
            'start_time' =>  $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('start_time');
        $errors = session('errors')->get('start_time');
        $this->assertContains('出勤時間もしくは退勤時間が不適切な値です',$errors);
    }

    public function test_休憩開始が退勤より後()
    {
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);

        $start_break = '10:00';
        $end_break = '11:00';
        $start_time = '08:00';
        $end_time = '9:00';

        $response = $this->patch('/attendance/' . $attendance->id .'/approval',[
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('start_break');
        $errors = session('errors')->get('start_break');
        $this->assertContains('休憩時間が勤務時間外です',$errors);
    }

    public function test_休憩終了が退勤より後()
    {
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);

        $start_break = '10:00';
        $end_break = '11:00';
        $start_time = '08:00';
        $end_time = '10:30';

        $response = $this->patch('/attendance/' . $attendance->id .'/approval',[
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('end_break');
        $errors = session('errors')->get('end_break');
        $this->assertContains('休憩時間が勤務時間外です',$errors);
    }

    public function test_備考未入力(){
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $start_time = '09:00';
        $end_time = '18:00';
        $start_break = '12:00';
        $end_break = '13:00';

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);

        $response = $this->patch('/attendance/' . $attendance->id .'/approval',[
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'memo' => '',
        ]);

        $response->assertSessionHasErrors('memo');
        $errors = session('errors')->get('memo');
        $this->assertContains('備考を記入してください',$errors);
    }

    public function test_修正申請処理(){
        $user = User::find(2);
        $this->actingAs($user);

        $workDate = '2025-07-01';

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => $workDate,
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'start_break' => Carbon::parse($attendance->work_date . ' 12:00'),
            'end_break' => Carbon::parse($attendance->work_date . ' 13:00'),
        ]);

        $start_time = '09:00';
        $end_time = '18:00';
        $start_break = '12:00';
        $end_break = '13:00';

        $response = $this->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);

        $response = $this->patch('/attendance/' . $attendance->id .'/approval',[
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'memo' => 'test',
        ]);
        $attendance->refresh();

        $admin = User::find(1);
        $this->actingAs($admin);

        $response = $this->get('/admin/stamp/correction_request/list');
        $response->assertStatus(200);

        $response->assertSee('承認待ち');
        $response->assertSee($attendance->memo);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($attendance->work_date)->format('Y/m/d'));

        $attendanceCorrectRequest = Application::where('attendance_id', $attendance->id)->first();
        $this->assertNotNull($attendanceCorrectRequest);

        $response = $this->get('/admin/stamp_correction_request/approve/' . $attendanceCorrectRequest->id);
        $response->assertStatus(200);

        $response->assertSee($attendance->memo);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('12:00');
        $response->assertSee('13:00');
        $response->assertSee($user->name);
        $response->assertSee('2025年');
        $response->assertSee('7月');
        $response->assertSee('1日');
    }
}
