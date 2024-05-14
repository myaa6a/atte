<?php

namespace Database\Factories;

use App\Models\Stamp;
use Illuminate\Database\Eloquent\Factories\Factory;

class StampFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Stamp::class;

    public function definition()
    {

        $dummy_date = $this->faker->dateTimeThisMonth;

        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 105),
            'rest_id' => $this->faker->numberBetween(1,5),
            'stamp_date'=> $this->faker->dateTimeBetween($startDate = '-1 week', $endDate = '+1 week'),
            'work_start_at' => $dummy_date->format('H:i:s'),
            'work_end_at' => $dummy_date->modify('+9hours')->format('H:i:s'),
            'work_time' => '08:00:00',
        ];
    }
}