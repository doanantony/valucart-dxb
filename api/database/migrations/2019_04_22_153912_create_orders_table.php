<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('customer_id')->nullable(false);
            $table->string('order_id',64)->nullable(false)->unique();
            $table->enum('payment_type',['cod','card'])->nullable(true)->default(null);
            $table->dateTimeTz('delivery_date')->nullable(true)->default(null);
            $table->dateTimeTz('created_at')->nullable(true)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(true)->useCurrent();
            $table->longText('snapshots')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
