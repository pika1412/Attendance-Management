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

class AdminUserInformationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_氏名アドレス確認(){
        $admin = User::where('is_admin',true)->first();

        $users = User::factory()->count(3)->create();

        $this->actingAs($admin);
        $response = $this->get('/admin/staff/list');
        $response->assertStatus(200);

        foreach($users as $user){
        $response->assertSee($user->name);
        $response->assertSee($user->email);
        }
    }

        public function test_勤怠情報表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' =>$user->id,
            'work_date' =>now(),
        ]);

        $response = $this->get('/admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);

        $formattedDate = Carbon::parse($attendance->work_date)->isoFormat('MM/DD(ddd)');

        $response->assertSee($formattedDate);
    }

    public function test_前月の情報表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $month = now()->format('Y-m');
        $currentMonth = Carbon::parse($month)->startOfMonth();
        $prevMonth = $currentMonth->copy()->subMonth();

        $response = $this->get('admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);

        $url = route('admin.staff_attendance_list',['id' => $user->id,'month' => $prevMonth->format('Y-m')]);
        $response = $this->get($url);

        $response->assertSee($prevMonth->Format('Y/m'));
    }

    public function test_翌月の情報表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $month = now()->format('Y-m');
        $currentMonth = Carbon::parse($month)->startOfMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $response = $this->get('admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);

        $url = route('admin.staff_attendance_list',['id' => $user->id,'month' => $nextMonth->format('Y-m')]);
        $response = $this->get($url);

        $response->assertSee($nextMonth->Format('Y/m'));
    }

    public function test_詳細画面へ遷移(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' =>now(),
        ]);

        $response = $this->get('/admin/attendance/' . $attendance->id);
        $response->assertStatus(200);

        $url = route('admin.attendance_detail', ['id' => $attendance->id]);
        $response = $this->get($url);
        $response->assertStatus(200);
    }
}