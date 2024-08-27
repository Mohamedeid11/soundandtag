<?php

namespace Database\Seeders;

use App\Models\Faq;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(base_path()."/database/assets/faq.json");
        $data = new Collection(json_decode($json, true));
        $faqs = [];
        foreach ($data as $faq){
            $faqs[] = [
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
        }
        DB::table('faqs')->insert($faqs);

    }
}
