<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

class DateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_日付取得(){
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get('/working_status');
        $response->assertStatus(200);

        $expectedDate = Carbon::now()->isoFormat('YYYY年MM月DD日 (ddd)');
        $response->assertSee($expectedDate);
    }
}
