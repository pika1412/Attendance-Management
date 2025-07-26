<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_自分の勤怠情報全て(){
        $user = User::find(2);
        $this->actingAs($user);

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);

        $attendances = Attendance::where('user_id',$user->id)->get();

        foreach($attendances as $attendance){
            $dateStr = Carbon::parse($attendance->work_date)->isoFormat('MM/DD(ddd)');
            $response->assertSee($dateStr);
        }
    }

    public function test_現在の月表示(){
        $user = User::find(2);
        $this->actingAs($user);

        $month = now()->format('Y-m');
        $currentMonth = Carbon::parse($month)->startOfMonth();

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);
        $response->assertSee($currentMonth->format('Y/m'));
    }

    public function test_前月の情報表示(){
        $user = User::find(2);
        $this->actingAs($user);

        $month = now()->format('Y-m');
        $currentMonth = Carbon::parse($month)->startOfMonth();
        $prevMonth = $currentMonth->copy()->subMonth();

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);

        $url = route('attendance.index',['month' => $prevMonth->format('Y-m')]);
        $response = $this->get($url);
        $response->assertSee($prevMonth->format('Y/m'));
    }

    public function test_翌月の情報表示(){
        $user = User::find(2);
        $this->actingAs($user);

        $month = now()->format('Y-m');
        $currentMonth = Carbon::parse($month)->startOfMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);

        $url = route('attendance.index',['month' => $nextMonth->format('Y-m')]);
        $response = $this->get($url);
        $response->assertSee($nextMonth->format('Y/m'));
    }

    public function test_詳細情報表示(){
        $user = User::find(2);
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' =>now(),
        ]);

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);

        $url = route('attendance.detail', ['id' => $attendance->id]);
        $response = $this->get($url);
        $response->assertStatus(200);
    }
}
