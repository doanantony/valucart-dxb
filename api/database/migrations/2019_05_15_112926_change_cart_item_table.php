<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('cart_id', 32)->nullable(false)->change();
            $table->string('item_type', 128)->nullable(false)->after('cart_id');
            $table->string('product_list', 2048)->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedInteger('cart_id')->nullable(false)->change();
            $table->dropColumn(['item_type', 'product_list']);
        });
    }
}
