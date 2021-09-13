<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBundlesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bundles_products', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('bundle_id')->nullable(false);
            $table->unsignedMediumInteger('product_id')->nullable(false);
            $table->unsignedInteger('quantity')->nullable(false)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_bundles_products');
    }
}
