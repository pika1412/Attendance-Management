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

class ApplicationSecondTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_承認待ちに全表示(){
        $user = User::find(2);
        $this->actingAs($user);

        $workDate = '2025-07-01';

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => $workDate,
            'memo' => 'テストメモ',
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

        $application = Application::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'applied_at' => Carbon::now(),
        ]);

        $response = $this->get('/stamp_correction_request/list?page=pending');
        $response->assertStatus(200);
        $response->assertSee('承認待ち');
        $response->assertSee($attendance->memo);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($application->applied_at)->format('Y/m/d'));
    }

    public function test_承認済み(){
        $user = User::find(3);
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

        $application = Application::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'approved',
            'applied_at' => Carbon::now(),
        ]);

        $response = $this->get('/stamp_correction_request/list?page=approved');
        $response->assertStatus(200);

        $response->assertSee('承認済み');
        $response->assertSee($attendance->memo);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($application->applied_at)->format('Y/m/d'));
    }

    public function test_詳細画面へ遷移(){
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

        $response = $this->get('/stamp_correction_request/list');
        $response->assertStatus(200);

        $url = route('attendance.detail', ['id' => $attendance->id]);
        $response = $this->get($url);
        $response->assertStatus(200);
    }

}
