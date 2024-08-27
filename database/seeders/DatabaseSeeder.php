<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            CountrySeeder::class,
            RoleSeeder::class,
            PlanSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            RecordTypeSeeder::class,
            RecordSeeder::class,
            PaymentSeeder::class,
            PermissiontCategorySeeder::class,
            PermissiontSeeder::class,
            RolePermissionSeeder::class,
            PageSeeder::class,
            SocialLinkSeeder::class,
            FaqSeeder::class,
            GuideSeeder::class,
            SubscriptionSeeder::class,
            CategorySeeder::class
        ]);
    }
}
