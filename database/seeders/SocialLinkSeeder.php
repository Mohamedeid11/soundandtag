<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $social_links = [
            [
                'name'=>'Facebook',
                'link'=>'https://www.fb.com',
                'icon'=>'fab fa-facebook',
                'active' => true
            ],
            [
                'name'=>'Twitter',
                'link'=>'https://www.twitter.com',
                'icon'=>'fab fa-twitter',
                'active' => true
            ],
            [
                'name'=>'Instagram',
                'link'=>'https://www.instagram.com',
                'icon'=>'fab fa-instagram',
                'active' => true
            ],

        ];
        DB::table('social_links')->insert($social_links);
    }
}
