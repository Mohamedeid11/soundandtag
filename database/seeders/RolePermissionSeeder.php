<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where(['name'=>'admin'])->where(['is_system'=>true])->first();
        $admin_role->permissions()->attach(Permission::pluck('id')->toArray());
    }
}
