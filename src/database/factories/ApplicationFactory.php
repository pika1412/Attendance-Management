<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\User;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'attendance_id' => Attendance::factory(),
            'status' => 'pending',
            'status' => 'approved',
            'applied_at' => Carbon::now(),
        ];
    }
}
