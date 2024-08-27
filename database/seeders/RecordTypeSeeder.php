<?php

namespace Database\Seeders;

use App\Models\RecordType;
use Illuminate\Database\Seeder;

class RecordTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic_types = ['First Name', 'Middle Name', 'Last Name', 'Nickname', 'Company'];
        foreach ($basic_types as $index => $basic_type) {
            RecordType::factory()->create(['name' => $basic_type, 'is_system' => true, 'account_type' => 'personal', 'type_order' => $index, 'required' => ($index == 0 || $index == 2)]);
        }

        $employees_types = ['First Name', 'Middle Name', 'Last Name', 'Nickname'];
        foreach ($employees_types as $index => $employees_type) {
            RecordType::factory()->create([
                'name' => $employees_type,
                'is_system' => true,
                'account_type' => 'employee',
                'type_order' => $index,
                'required' => ($index == 0 || $index == 2)
            ]);
        }

        $corporate_types = ['Business Name'];
        foreach ($corporate_types as $index => $corporate_type) {
            RecordType::factory()->create([
                'name' => $corporate_type,
                'is_system' => true,
                'account_type' => 'corporate',
                'type_order' => $index, 'required' => ($index == 0)
            ]);
        }
    }
}
