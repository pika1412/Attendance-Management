<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_メール未入力()
    {
        $response = $this->post('/login', [
            'email' => "",
            'password' => "password",
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    public function test_パスワード未入力(){
        $response = $this->post('/login',[
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください',$errors->first('password'));
    }

    public function test_登録内容不一致(){
        $response = $this->post('/login',[
            'email' => 'unknown@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません。',$errors->first('email'));
    }
}
