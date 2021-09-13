<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsDatatypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            ALTER TABLE `products`
                MODIFY COLUMN `packaging_quantity_unit_id` TINYINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `packaging_type` SMALLINT UNSIGNED DEFAULT NULL,
                MODIFY COLUMN `customer_bundle_discount` FLOAT(6, 2) UNSIGNED DEFAULT 0,
                MODIFY COLUMN `minimum_inventory` SMALLINT UNSIGNED DEFAULT 0
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
