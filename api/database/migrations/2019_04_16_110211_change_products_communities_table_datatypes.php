<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsCommunitiesTableDatatypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_communities', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable(false)->change();
            $table->unsignedSmallInteger('community_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_communities', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable(false)->change();
            $table->unsignedSmallInteger('community_id')->nullable(false)->change();
        });
    }
}
