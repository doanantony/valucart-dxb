<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('price')->nullable(false)->after('payment_type');
            $table->renameColumn('order_id', 'order_reference');
            $table->unsignedInteger('cart_id')->nullable(false);
            $table->unsignedInteger('address_id')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('order_reference', 'order_id');
            $table->dropColumn('address_id');
            $table->dropColumn('cart_id');
            $table->dropColumn('price');
        });
    }
}
