<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::factory()->count(5)->sequence(
            ['name' => 'Board Members'],
            ['name' => 'Executive Members'],
            ['name' => 'Managers'],
            ['name' => 'Staff'],
            ['name' => 'Other'],
    )->create();

    }
}
