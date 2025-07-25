<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\User;

class AttendanceFactory extends Factory
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
            'work_date' => $this->faker->date(),
            'status' => 'off_duty',
            'status' => 'on_break',
            'status' => 'working',
            'status' => 'finished',
        ];
    }
}
