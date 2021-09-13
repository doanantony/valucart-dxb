<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->nullable(true)->default(null);
            $table->enum('status',['created','checked_out','paid']);
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
}
