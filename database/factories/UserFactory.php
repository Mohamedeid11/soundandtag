<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $account_type = $this->faker->randomElement(['personal', 'corporate']);
        $default = $account_type === 'personal' ? 'default-user.png' : 'default-corporate.png';
        $image = "uploads/profile/".$this->faker->name.time().".png";
        Storage::disk('public')->copy("defaults/".$default, $image);
        return [
            'account_type' => $account_type,
            'name' => $this->faker->name,
            "username" => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'country_id'=>Country::inRandomOrder()->first()->id,
            'image' =>$image,
            'featured' => $this->faker->randomElement([true, false]),
            'plan_id'=> Plan::inRandomOrder()->first()->id,
            ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
