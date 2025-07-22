<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;


class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_名前バリデーション()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors(['name']);
        $errors = session('errors')->get('name');
        $this->assertContains('お名前を入力してください',$errors);
    }

    public function test_アドレスバリデーション()
    {
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertContains('メールアドレスを入力してください',$errors);
    }

    public function test_パスワード8文字以下バリデーション(){
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'dami12',
            'password_confirmation' => 'dami12',
        ]);
        $response->assertSessionHasErrors(['password']);
        $errors = Session('errors')->get('password');
        $this->assertContains('パスワードは８文字以上で入力してください',$errors);
    }

    public function test_パスワード確認バリデーション(){
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' =>'password1',
        ]);
        $response->assertSessionHasErrors(['password']);
        $errors = Session('errors')->get('password');
        $this->assertContains('パスワードと一致しません',$errors);
    }

    public function test_パスワード一致バリデーション(){
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' =>'password',
        ]);
        $response->assertSessionHasErrors(['password']);
        $errors = Session('errors')->get('password');
        $this->assertContains('パスワードを入力してください',$errors);
    }

    public function test_会員登録(){
        $response = $this->post('/register',[
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' =>'password',
        ]);

        $response->assertRedirect('/thanks');

        $this->assertDatabaseHas('users',[
            'name' => "テスト太郎",
            'email' => "test@example.com",
        ]);
    }
}