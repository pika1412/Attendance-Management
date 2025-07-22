<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CheckStatusTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_勤務外(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off_duty',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);

        $response->assertSee('勤務外');
    }

    public function test_出勤中(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);

        $response->assertSee('出勤中');
    }

    public function test_休憩中(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'on_break',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);

        $response->assertSee('休憩中');
    }

    public function test_退勤済み(){
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'finished',
            'work_date' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);

        $response->assertSee('退勤済');
    }

}
