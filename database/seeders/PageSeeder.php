<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    Page::truncate();
	
    $aboutContent = File::get(base_path() . "/database/assets/about.html");
    $termsContent = File::get(base_path() . "/database/assets/terms.html");
    $aboutUsContent = File::get(base_path() . "/database/assets/about_us.html");

    $pages = [
      ['name' => 'about', 'content' => $aboutContent, 'is_system' => true],
      ['name' => 'terms', 'content' => $termsContent, 'is_system' => true],
      ['name' => 'about_us', 'content' => $aboutUsContent, 'is_system' => true]
    ];
    DB::table('pages')->insert($pages);
  }
}
