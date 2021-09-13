<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminorders', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('admin_type_id')->nullable(false);
            $table->string('admin_name',124)->nullable(false);
            $table->string('order_reference',64)->nullable(false)->unique();
            $table->string('status',124)->nullable(false);
            $table->float('discount')->nullable(false);
            $table->float('subtotal_price')->nullable(false);
            $table->float('total_price')->nullable(false);
            $table->dateTimeTz('delivery_date')->nullable(true)->default(null);
            $table->tinyInteger('time_slot_id')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adminorders');
    }
}
