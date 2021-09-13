<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("
            INSERT INTO `states` (`name`, `published`, `created_at`, `updated_at`, `deleted_at`) VALUES
            ('Abu Dhabi', '0', NOW(), NOW(), NULL),
            ('Ajman', '0', NOW(), NOW(), NULL),
            ('Al Ain', '0', NOW(), NOW(), NULL),
            ('Dubai', '1', NOW(), NOW(), NULL),
            ('Fujairah', '0', NOW(), NOW(), NULL),
            ('Ras al-Khaimah', '0', NOW(), NOW(), NULL),
            ('Sharjah', '1', NOW(), NOW(), NULL),
            ('Umm Al Quwain', '0', NOW(), NOW(), NULL)
        ");
    }
}
