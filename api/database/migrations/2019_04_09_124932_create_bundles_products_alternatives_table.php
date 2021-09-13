<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundlesProductsAlternativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles_products_alternatives', function (Blueprint $table) {
            $table->mediumInteger('bundles_products_id')->nullable(false);
            $table->mediumInteger('product_id')->nullable(false);
            $table->tinyInteger('quantity')->nullable(false)->default(1);
            $table->timestamps();

            $table->primary(['bundles_products_id','product_id'], 'bundle_product_alternative');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles_products_alternatives');
    }
}
