<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDatabaseDatatypesForProductsUpload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::statement('
            ALTER TABLE `categories`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `department_id` TINYINT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `subcategories`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `category_id` SMALLINT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `brands`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `name` VARCHAR(128) NOT NULL UNIQUE
        ');

        DB::statement('
            ALTER TABLE `communities`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `country_id` TINYINT UNSIGNED DEFAULT NULL
        ');

        DB::statement('
            ALTER TABLE `matric_units`
                MODIFY COLUMN `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT
        ');

        DB::statement('
            ALTER TABLE `vendors`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `name` VARCHAR(128) NOT NULL UNIQUE
        ');

        DB::statement('
            ALTER TABLE `product_packaging_types`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
        ');

        DB::statement('
            ALTER TABLE `product_types`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
        ');

        DB::statement('
            ALTER TABLE `products`
                MODIFY COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `category_id` SMALLINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `subcategory_id` SMALLINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `brand_id` SMALLINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `packaging_quantity_unit_id` TINYINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `packaging_type` SMALLINT UNSIGNED DEFAULT NULL
        ');

        DB::statement('
            ALTER TABLE `products_communities`
                MODIFY COLUMN `product_id` SMALLINT UNSIGNED NOT NULL,
                MODIFY COLUMN `community_id` SMALLINT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `products_images`
                MODIFY COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                MODIFY COLUMN `product_id` INT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `products_meta_tags`
                MODIFY COLUMN `product_id` INT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `products_vendors`
                MODIFY COLUMN `product_id` INT UNSIGNED NOT NULL,
                MODIFY COLUMN `vendor_id` SMALLINT UNSIGNED NOT NULL,
                MODIFY COLUMN `inventory` SMALLINT UNSIGNED NOT NULL
        ');

        DB::statement('
            ALTER TABLE `states`
                MODIFY COLUMN `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
        ');

        DB::statement('
            ALTER TABLE `vendors_states`
                MODIFY COLUMN `vendor_id` SMALLINT UNSIGNED NOT NULL,
                MODIFY COLUMN `state_id` SMALLINT UNSIGNED NOT NULL
        ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement('SELECT 1');
    }
}
