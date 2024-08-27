<?php

namespace Database\Factories;

use App\Models\RecordType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecordType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'account_type' => $this->faker->randomElement(['personal', 'corporate']),
            'is_system' => false,
            'type_order'=>$this->faker->randomNumber()
        ];
    }
}
