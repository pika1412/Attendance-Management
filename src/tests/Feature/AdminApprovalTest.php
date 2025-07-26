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

class AdminApprovalTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }


    public function test_承認待ち情報の表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $users = User::factory()->count(3)->create();

        foreach($users as $user){
            Application::factory()->create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        $response = $this->get('/admin/stamp/correction_request/list');
        $response->assertStatus(200);

        $response = $this->get('/admin/stamp/correction_request/list?page=pending');
        $response->assertStatus(200);

        foreach($users as $user){
        $response->assertSee($user->name);
        }
    }

    public function test_承認済み情報の表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $users = User::factory()->count(3)->create();

        foreach($users as $user){
            Application::factory()->create([
                'user_id' => $user->id,
                'status' => 'approved',
            ]);
        }

        $response = $this->get('/admin/stamp/correction_request/list');
        $response->assertStatus(200);

        $response = $this->get('/admin/stamp/correction_request/list?page=approved');
        $response->assertStatus(200);

        foreach($users as $user){
        $response->assertSee($user->name);
        }
    }

    public function test_詳細内容の表示(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
        'user_id' => $user->id,
        'memo' => 'テストメモ',
        ]);

        $app = Application::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'attendance_id' => $attendance->id,
        ]);

        $break = BreakTime::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
        ]);

        $url = route('admin.application_approval',['applicationId' => $app->id]);
        $response = $this->get($url);
        $response->assertStatus(200);

        $response->assertSee($app->user->name);
        $response->assertSee($app->attendance->memo);
        $response->assertSee(
        Carbon::parse($attendance->work_date)->format('Y年'));
        $response->assertSee(
        Carbon::parse($attendance->work_date)->format('n月j日'));
        $response->assertSee($attendance?->end_time ? Carbon::parse($attendance->start_time)->format('H:i') :null);
        $response->assertSee($attendance?->end_time ? Carbon::parse($attendance->end_time)->format('H:i') :null);
        $response->assertSee($break->start_break ? Carbon::parse($break->start_break)->format('H:i') : '');
        $response->assertSee($break->end_break ? Carbon::parse($break->end_break)->format('H:i') : '');
    }

    public function test_承認処理(){
        $admin = User::where('is_admin',true)->first();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
        'user_id' => $user->id,
        'memo' => 'テストメモ',
        'work_date' =>now(),
        ]);

        $app = Application::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'attendance_id' => $attendance->id,
        ]);

        $break = BreakTime::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
        ]);


        $start_time = '09:00';
        $end_time = '18:00';
        $start_break = '12:00';
        $end_break = '13:00';

        $url = route('admin.application_approval',['applicationId' => $app->id]);

        $response = $this->patch($url,[
            'user_id' => $user->id,
            'work_date' =>now(),
            'start_break' =>  $start_break,
            'end_break' => $end_break,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'memo' => 'test',
        ]);
        $response->assertStatus(302);

        $response = $this->followingRedirects()->patch($url, [
        'user_id' => $user->id,
        'work_date' => now(),
        'start_break' =>  $start_break,
        'end_break' => $end_break,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'memo' => 'test',
        ]);
        $attendance->refresh();
        $response->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee($attendance->memo);
        $response->assertSee(
        Carbon::parse($attendance->work_date)->format('Y年'));
        $response->assertSee(
        Carbon::parse($attendance->work_date)->format('n月j日'));
        $response->assertSee($attendance?->end_time ? Carbon::parse($attendance->start_time)->format('H:i') :null);
        $response->assertSee($attendance?->end_time ? Carbon::parse($attendance->end_time)->format('H:i') :null);
        $response->assertSee($break->start_break ? Carbon::parse($break->start_break)->format('H:i') : '');
        $response->assertSee($break->end_break ? Carbon::parse($break->end_break)->format('H:i') : '');

    }
}