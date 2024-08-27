<?php

namespace Database\Seeders;

use App\Models\Admin;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        $role = Role::where(['is_system' => true])->where(['name' => 'admin'])->first();

        $users = ['islam' , 'mohamed' , 'nahla' , 'admin', 'harsha'];
        foreach ($users as $key => $user)
        {
            Admin::factory()->create(
                [
                    'name' => ucfirst($user),
                    'username' => $user,
                    'email' => $user . '@mail.com',
                    'is_system' => true,
                    'role_id' => $role->id,
                ]
            );

        }
    }
}
