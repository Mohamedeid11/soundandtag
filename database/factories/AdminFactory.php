<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>  $this->faker->name ,
            'username'=>  $this->faker->userName ,
            'email'=>  $this->faker->email ,
            'password'=>  Hash::make('sownd-admn-2022') ,
            'remember_token' => Str::random(10),
            'is_system' => false,
            'role_id' =>  Role::inRandomOrder()->first()->id ,
            'image' => (new Admin)->image
        ];
    }
}
