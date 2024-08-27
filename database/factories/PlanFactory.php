<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_type' => $this->faker->randomElement(['personal', 'corporate']),
            'period' => $this->faker->randomElement(['annually', 'biennially', 'triennially', 'quadrennially']),
            'price' => $this->faker->numberBetween(0, 1000),
            'items' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
