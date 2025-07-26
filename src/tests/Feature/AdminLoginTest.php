<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminLogin extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_管理者ログイン(){
        $admin = User::where('is_admin',true)->first();

        $response = $this->post('/admin/login',[
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/attendance/list');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_管理者メアド未入力(){
        $response = $this->post('/admin/login',[
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals('メールアドレスを入力してください',$errors->first('email'));
    }

    public function test_管理者パスワード未入力(){
        $response = $this->post('/admin/login',[
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals('パスワードを入力してください',$errors->first('password'));
    }

    public function test_管理者登録内容不一致(){
        $response = $this->post('/admin/login',[
            'email' => 'unknown@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません',$errors->first('email'));
    }

}
