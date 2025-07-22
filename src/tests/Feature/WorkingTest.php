<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkingTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_出勤ボタン機能(){
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off_duty',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('出勤');

        $this->post('/working_status',[
            'action' => 'working',
        ]);

        $response = $this->get('/working_status');
        $response->assertSee('勤務中');
    }

    public function test_出勤1日1回(){
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'finished',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertDontSeeText('出勤');
    }

    public function test_出勤時間確認(){
        $user = User::factory()->create();

        $now = now();
        Carbon::setTestNow($now);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off_duty',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('出勤');

        $this->post('/working_status',[
            'action' => 'working',
        ]);

        $response = $this->get('/attendance-list');
        $response->assertSee($now->format('H:i'));

        $response->assertSee($now->isoFormat('MM/DD(ddd)'));
    }
}
