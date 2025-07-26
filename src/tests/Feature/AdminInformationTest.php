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

class AdminInformationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_詳細画面に表示()
    {
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now(),
        ]);

        $response = $this->get("/admin/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee(Carbon::parse($attendance->work_date)->format('Y年'));
        $response->assertSee(Carbon::parse($attendance->work_date)->format('n月j日'));
        $response->assertSee($user->name);
    }

    public function test_出勤が退勤より後()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
        ]);
        $response = $this->get("/admin/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $application = Application::factory()->create();
        $applicationId = $application->id;
        $start_time = '10:00';
        $end_time = '9:00';

        $response = $this->patch('/admin/stamp_correction_request/approve/' . $applicationId, [
            'start_time' =>  $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('start_time');
        $errors = session('errors')->get('start_time');
        $this->assertContains('出勤時間もしくは退勤時間が不適切な値です',$errors);
    }

    public function test_休憩開始が退勤より後()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
        ]);
        $response = $this->get("/admin/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $application = Application::factory()->create();
        $applicationId = $application->id;
        $start_break = '10:00';
        $end_break = '11:00';
        $start_time = '08:00';
        $end_time = '9:00';

        $response = $this->patch('/admin/stamp_correction_request/approve/' . $applicationId, [
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' =>  $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('start_break');
        $errors = session('errors')->get('start_break');
        $this->assertContains('休憩時間が勤務時間外です',$errors);
    }

    public function test_休憩終了が退勤より後()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
        ]);
        $response = $this->get("/admin/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $application = Application::factory()->create();
        $applicationId = $application->id;
        $start_break = '10:00';
        $end_break = '11:00';
        $start_time = '08:00';
        $end_time = '10:30';

        $response = $this->patch('/admin/stamp_correction_request/approve/' . $applicationId, [
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' =>  $start_time,
            'end_time' => $end_time,
        ]);

        $response->assertSessionHasErrors('end_break');
        $errors = session('errors')->get('end_break');
        $this->assertContains('休憩時間が勤務時間外です',$errors);
    }

    public function test_備考未入力()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
        ]);
        $response = $this->get("/admin/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $application = Application::factory()->create();
        $applicationId = $application->id;
        $start_break = '12:00';
        $end_break = '13:00';
        $start_time = '09:00';
        $end_time = '18:00';

        $response = $this->patch('/admin/stamp_correction_request/approve/' . $applicationId, [
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' =>  $start_time,
            'end_time' => $end_time,
            'memo' => '',
        ]);

        $response->assertSessionHasErrors('memo');
        $errors = session('errors')->get('memo');
        $this->assertContains('備考を記入してください',$errors);
    }
}