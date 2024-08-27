<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'account_type' => 'personal',
                'period' => 'annually',
                'price' => 20,
                'items' => 0,
                'is_system' => true,
            ],
            [
                'account_type' => 'personal',
                'period' => 'triennially',
                'price' => 45,
                'items' => 0,
                'is_system' => false,
            ],
            [
                'account_type' => 'corporate',
                'period' => 'annually',
                'price' => 50,
                'items' =>15,
                'is_system' => true,
            ],
            [
                'account_type' => 'corporate',
                'period' => 'annually',
                'price' => 200,
                'items' =>30,
                'is_system' => false,
            ],
            [
                'account_type' => 'corporate',
                'period' => 'annually',
                'price' => 230,
                'items' =>50,
                'is_system' => false,
            ],
            [
                'account_type' => 'corporate',
                'period' => 'annually',
                'price' => 250,
                'items' =>100,
                'is_system' => false,
            ],
        ];
        DB::table('plans')->insert($plans);
    }
}
