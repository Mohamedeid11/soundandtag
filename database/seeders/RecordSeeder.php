<?php

namespace Database\Seeders;

use App\Models\Record;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dispatcher = Record::getEventDispatcher();
        Record::unsetEventDispatcher();
        Record::factory()->count(4)->create();
        Record::setEventDispatcher($dispatcher);
    }
}
