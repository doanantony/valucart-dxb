<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('valucart_order_id')->nullable(false);
            $table->string('network_reference', 256)->nullable(false);
            $table->float('amount', 8, 2)->nullable(false)->default(0);
            $table->string('currency', 16)->nullable(false)->default('AED');
            $table->string('status', 16)->nullable(false)->default('started');
            $table->string('network_payment_url', 512)->nullable(true)->default(null);
            $table->dateTimeTz('network_created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
