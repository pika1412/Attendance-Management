<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FinishedTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_退勤(){
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('退勤');

        $this->post('/working_status',[
            'action' => 'finished',
        ]);

        $response = $this->get('/working_status');
        $response->assertSee('退勤済');
    }

    public function test_退勤時刻確認(){
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

        $response = $this->get('/working_status');
        $response->assertSee('出勤中');

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('退勤');

        $this->post('/working_status',[
            'action' => 'finished',
        ]);
        $response = $this->get('/working_status');
        $response->assertSee('退勤済');

        $response = $this->get('/attendance-list');
        $response->assertSee($now->format('H:i'));

        $response->assertSee($now->isoFormat('MM/DD(ddd)'));
    }
}
