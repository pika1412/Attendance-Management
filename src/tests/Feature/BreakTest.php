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

        $now = now();
        Carbon::setTestNow($now);

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

        $now = now();
        Carbon::setTestNow($now);

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
}
