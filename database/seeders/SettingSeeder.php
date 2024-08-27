<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $must_exist = config('settings');
        foreach ($must_exist as $item){
            Setting::firstOrCreate($item[0], $item[1]);
        }
    }
}
