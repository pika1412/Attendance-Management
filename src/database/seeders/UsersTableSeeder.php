<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        [
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ],
        [
            'name' => '西 玲奈',
            'email'=> 'reina.n@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        [
            'name' => '山田 太郎',
            'email'=> 'taro.y@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        [
            'name' => '増田 一世','email'=> 'issei.m@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        [
            'name' => '山本 敬吉','email'=> 'keikichi.y@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        [
            'name' => '秋田 朋美','email'=> 'tomomi.a@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        [
            'name' => '中西 教夫','email'=> 'norio.n@coachtech.com',
            'password' =>Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ],
        ]);
    }
}
