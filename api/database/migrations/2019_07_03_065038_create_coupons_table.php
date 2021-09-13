<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('coupon', 128)->nullable(false);
            $table->float('minimum_order_value', 5, 2)->nullable()->default(null);
            $table->string('discount', 128)->nullable(false)->default(0);
            $table->unsignedSmallInteger('usage_limit')->nullable()->default(null);
            $table->string('for_payment_method', 128)->nullable()->default(null);
            $table->unsignedTinyInteger('for_first_order')->nullable()->default(null);
            $table->dateTimeTz('starts_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('expires_at')->nullable()->default(null);
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('deleted_at')->nullable()->default(null);

            $table->primary('coupon');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
