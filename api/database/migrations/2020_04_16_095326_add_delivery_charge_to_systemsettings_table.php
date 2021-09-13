<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryChargeToSystemsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('minimum_order',32)->nullable(true)->after('id');
            $table->string('mm_minimum_order',32)->nullable(true)->after('minimum_order');
            $table->string('freedelivery_minimum_order',32)->nullable(true)->after('mm_minimum_order');
            $table->string('mm_freedelivery_minimum_order',32)->nullable(true)->after('freedelivery_minimum_order');
            $table->string('delivery_charge',32)->nullable(true)->after('mm_freedelivery_minimum_order');
            $table->string('vat',32)->nullable(true)->after('delivery_charge');
            $table->string('max_delivery_time_deliveries',32)->nullable(true)->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_settings', function (Blueprint $table) {
            //
        });
    }
}
