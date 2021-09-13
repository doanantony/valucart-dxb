<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('customer_id')->nullable(false);
            $table->string('name', 64)->nullable()->default(null);
            $table->unsignedSmallInteger('area_id')->nullable(false);
            $table->string('street', 128)->nullable()->default(null);
            $table->string('building', 128)->nullable()->default(null);
            $table->string('floor', 128)->nullable()->default(null);
            $table->string('apartment', 128)->nullable()->default(null);
            $table->string('landmark', 128)->nullable()->default(null);
            $table->string('notes', 512)->nullable()->default(null);
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('deleted_at')->nullable()->default(null);
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
