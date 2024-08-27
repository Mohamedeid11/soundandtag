<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'transaction_id'=>$this->faker->text(),
            'value'=>$this->faker->numberBetween(0, 100000),
            'payment_type' => $this->faker->randomElement(['credit', 'wire']),
            'confirmed' => $this->faker->randomElement([true, false]),
        ];
    }
}
