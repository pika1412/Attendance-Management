<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BreakTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_休憩ボタン(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        $this->post('/working_status',[
            'action' => 'start_break',
        ]);
    }

    public function test_休憩何回でも可能(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        for($i = 0; $i < 3; $i++){
            $this->post('/working_status',[
                'action' => 'start_break',
            ]);
            $this->post('/working_status',[
                'action' => 'end_break',
            ]);
        }
        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');
    }

    public function test_休憩戻ボタン(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        $this->post('/working_status',[
            'action' => 'start_break',
        ]);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');

        $this->post('/working_status',[
            'action' => 'end_break',
        ]);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_休憩戻一日に何回も可能(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        for($i = 0; $i < 3; $i++){
            $this->post('/working_status',[
                'action' => 'start_break',
            ]);
            $this->post('/working_status',[
                'action' => 'end_break',
            ]);
        }

        $this->post('/working_status', [
            'action' => 'start_break',
        ]);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');
    }

    public function test_休憩時刻を一覧画面で確認可能(){
        $user = User::factory()->create();

        $now = now();
        Carbon::setTestNow($now);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now()->toDateString(),
            'start_time' => $now,
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        $this->post('/working_status',[
            'action' => 'start_break',
        ]);

        $response = $this->get('/working_status');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');

        $this->post('/working_status',[
            'action' => 'end_break',
        ]);

        $response = $this->get('/attendance-list');
        $response->assertStatus(200);

        $response->assertSee($now->format('H:i'));
        $response->assertSee($now->isoFormat('MM/DD(ddd)'));
    }
}
