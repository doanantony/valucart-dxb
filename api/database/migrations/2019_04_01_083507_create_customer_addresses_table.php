<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            
            $table->mediumIncrements('id');
            $table->string('label', 32)->nullable()->default(null);
            $table->smallInteger('area_id')->nullable(false);
            $table->string('street', 128)->nullable(false);
            $table->string('building', 64)->nullable(false);
            $table->string('floor', 32)->nullable(true)->default(null);
            $table->string('apartment', 32)->nullable(true)->default(null);
            $table->string('nearest_landmark', 128)->nullable(true)->default(null);
            $table->string('phone_number', 32)->nullable(true)->default(null);
            $table->string('landline', 32)->nullable(true)->default(null);
            $table->string('shiping_notes', 256)->nullable(true)->default();
            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
