<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;

class PermissiontCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionCategory::factory()->create(
            ['name' => 'countries', 'display_name' => 'Countries',]
        );
        PermissionCategory::factory()->create(
            ['name' => 'users', 'display_name' => 'Users',]
        );
        PermissionCategory::factory()->create(
            ['name' => 'record_types', 'display_name' => 'Record Types',]
        );
        PermissionCategory::factory()->create(
            ['name' => 'records', 'display_name' => 'Records',]
        );
        PermissionCategory::factory()->create(
            ['name' => 'settings', 'display_name' => 'Settings',]
        );
        PermissionCategory::factory()->create(
            ['name' => 'contact_messages', 'display_name' => 'Contact Messages',]);
        PermissionCategory::factory()->create(
            ['name' => 'pages', 'display_name' => 'Pages',]);
        PermissionCategory::factory()->create(
            ['name' => 'social_links', 'display_name' => 'Social Links',]);
        PermissionCategory::factory()->create(
            ['name' => 'faqs', 'display_name' => 'Frequently asked questions',]);
        PermissionCategory::factory()->create(
            ['name' => 'guides', 'display_name' => 'Guides',]);
        PermissionCategory::factory()->create(
            ['name' => 'subscriptions', 'display_name' => 'Subscriptions',]);
        PermissionCategory::factory()->create(
            ['name' => 'newsletter_emails', 'display_name' => 'Newsletter Emails',]);
        PermissionCategory::factory()->create(
            ['name' => 'plans', 'display_name' => 'Plans',]);
    }
}
