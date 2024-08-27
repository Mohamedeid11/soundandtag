<?php

namespace Database\Seeders;

use App\Models\Guide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guides')->truncate();
        $json = File::get(base_path()."/database/assets/guides.json");
        $data = new Collection(json_decode($json, true));
        $guides = [];
        foreach ($data as $guide){
            $guides[] = [
                'name' => $guide['name'],
                'explanation' => $guide['explanation'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
        }
        DB::table('guides')->insert($guides);
    }
}
