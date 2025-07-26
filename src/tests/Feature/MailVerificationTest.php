<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

class MailVerificationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }


    public function test_認証メール送信()
    {
        Notification::fake();
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' =>'password',
        ]);
        $response->assertRedirect('/thanks');
        $user = User::where('email','test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);

    }

    public function test_認証メールサイト遷移(){
        $user = User::factory()->create();

        $response = $this->get('/thanks');
        $response->assertStatus(200);

        $response->assertSee('認証はこちらから');

        $response->assertSee('http://localhost:8025');
    }

}
