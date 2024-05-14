<?php

namespace Database\Factories;

use App\Models\Rest;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Rest::class;

    public function definition()
    {
        return [
            'rest_start_at' => $this->faker->time(),
            'rest_end_at' => $this->faker->time(),
            'rest_time' => '01:00:00',
        ];
    }
}
