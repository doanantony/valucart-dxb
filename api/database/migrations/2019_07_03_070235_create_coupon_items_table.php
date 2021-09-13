<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon', 128)->nullable(false);
            $table->string('item_type', 128)->nullable(false);
            $table->string('item_id', 128)->nullable(false);
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
        Schema::dropIfExists('coupon_items');
    }
}
