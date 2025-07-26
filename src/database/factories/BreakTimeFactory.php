<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BreakTime;
use Carbon\Carbon;

class BreakTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_break' => Carbon::today()->setTime(12, 0, 0),
            'end_break' => Carbon::today()->setTime(13, 0, 0),
        ];
    }
}
