<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\RecordType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Record::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'user_id' => $user->id,
            'record_type_id' => RecordType::where(['account_type'=>$user->account_type])->whereNull('user_id')->inRandomOrder()->first()->id,
            'file_path' =>$this->faker->filePath(),
            'text_representation' =>$this->faker->text(),
        ];
    }
}
