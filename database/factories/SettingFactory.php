<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\SettingPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->userName,
            'value' => $this->faker->text(200),
            'validation' => $this->faker->randomElement(['required', 'nullable']),
            'input_type' => $this->faker->randomElement(['text', 'boolean', 'file']),
            'display_name'=>$this->faker->name,

        ];
    }
}
