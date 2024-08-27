<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolePermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RolePermission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => $this->faker->randomElement(Role::pluck('id')->toArray()),
            'permission_id' => $this->faker->randomElement(Permission::pluck('id')->toArray())
        ];
    }
}
