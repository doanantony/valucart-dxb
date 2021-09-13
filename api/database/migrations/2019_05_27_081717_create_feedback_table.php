<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('customer_id')->nullable(false);
            $table->string('order_id',64)->nullable(false);
            $table->string('feedback',2024);
            $table->dateTimeTz('created_at')->nullable(true)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(true)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
