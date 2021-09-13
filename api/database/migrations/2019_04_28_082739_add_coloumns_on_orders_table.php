<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnsOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('orders', function (Blueprint $table) {
            $table->float('sub_total_price')->nullable(false)->after('cancelation_reason');
            $table->float('first_order_discount')->nullable(false)->after('sub_total_price');
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
            $table->dropColoumn('sub_total_price');
            $table->dropColoumn('first_order_discount');
        });
    }
}
