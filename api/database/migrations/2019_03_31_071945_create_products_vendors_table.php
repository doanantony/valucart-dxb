<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_vendors', function (Blueprint $table) {
            

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->mediumInteger('product_id')->nullable(false);
            $table->tinyInteger('vendor_id')->nullable(false);
            $table->float('price',6,2)->nullable(false);
            $table->smallInteger('inventory')->nullable(false);
            
            $table->primary(['product_id', 'vendor_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_vendors');
    }
}
