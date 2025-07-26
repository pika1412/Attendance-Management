<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Application;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_当日全ユーザ勤怠()
    {
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $today = now()->toDateString();

        $users = User::factory()->count(2)->create();
        foreach ($users as $user) {
        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => $today,
        ]);
    }
        $response = $this->get('/admin/attendance/list');
        $response->assertStatus(200);

        foreach ($users as $user) {
            $dateStr = Carbon::parse($today)->isoFormat('YYYY/MM/DD');
            $response->assertSee($dateStr);
        }
    }

    public function test_現在の日付()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $response = $this->get('/admin/attendance/list');
        $response->assertStatus(200);

        $today = now()->isoFormat('YYYY/MM/DD');
        $response->assertSee($today);
    }

    public function test_前日の日付()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $staffUser = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $staffUser->id,
            'work_date' => $yesterday,
        ]);

        $response = $this->get('/admin/attendance/list?day=' . $yesterday);
        $response->assertStatus(200);

        $dateStr = Carbon::parse($yesterday)->isoFormat('YYYY/MM/DD');
        $response->assertSee($dateStr);
    }

    public function test_翌日の日付()
    {
        $admin = User::find(1);
        $this->actingAs($admin);

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $staffUser = User::factory()->create();
        Attendance::factory()->create([
            'user_id' => $staffUser->id,
            'work_date' => $tomorrow,
        ]);

        $response = $this->get('/admin/attendance/list?day=' . $tomorrow);
        $response->assertStatus(200);

        $dateStr = Carbon::parse($tomorrow)->isoFormat('YYYY/MM/DD');
        $response->assertSee($dateStr);
    }
}
