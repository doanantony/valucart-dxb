<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundlesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles_products', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->smallInteger('bundle_id')->nullable(false);
            $table->mediumInteger('product_id')->nullable(false);
            $table->tinyInteger('quantity')->nullable(false)->default(1);
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles_products');
    }
}
