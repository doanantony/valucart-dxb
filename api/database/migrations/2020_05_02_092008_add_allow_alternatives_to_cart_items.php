<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowAlternativesToCartItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("cart_items", function (Blueprint $table) {
            $table->tinyInteger("allow_alternatives")->nullable(false)->default(1)->after("product_list");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("cart_items", function (Blueprint $table) {
            $table->dropColumn(["allow_alternatives"]);
        });
    }
}
