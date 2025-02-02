<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserHit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserHitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserHit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'day' => date('Y-m-d'),
            'count' => $this->faker->numberBetween(0, 1000)
        ];
    }
}
