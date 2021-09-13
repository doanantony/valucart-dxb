<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon', 128)->nullable(false);
            $table->string('user_identifier', 256)->nullable(false);
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
        Schema::dropIfExists('coupon_users');
    }
}
