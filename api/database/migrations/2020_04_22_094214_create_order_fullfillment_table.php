<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFullfillmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_fullfillment', function (Blueprint $table) {
             $table->mediumIncrements('id');
             $table->string('order_reference',64)->nullable(false)->unique();
             $table->string('status',124)->nullable(false);
             $table->longText('snapshots')->nullable(true);
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
        Schema::dropIfExists('order_fullfillment');
    }
}
