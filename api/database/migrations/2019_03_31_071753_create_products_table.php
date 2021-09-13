<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->mediumIncrements('id');
            $table->string('sku',64)->nullable(false)->unique();
            $table->tinyInteger('subcategory_id')->nullable(false);
            $table->tinyInteger('brand_id')->nullable(false);
            $table->string('name',128)->nullable(false);
            $table->string('description',512)->nullable(false);
            $table->smallInteger('packaging_quantity')->nullable();
            $table->tinyInteger('packaging_quantity_unit_id')->nullable();
            $table->float('maximum_selling_price',6,2)->nullable(false);
            $table->float('valucart_price',6,2)->nullable(false);
            $table->enum('published',['0','1'])->nullable(false)->default('0');
            $table->enum('is_admin_bundlable',['0','1'])->nullable(false)->default('0');
            $table->float('admin_bundle_discount',5,2)->nullable(false);
            $table->enum('is_customer_bundlable',['0','1'])->nullable(false)->default('0');
            $table->float('customer_bundle_discount',5,2)->nullable(false);
            $table->tinyInteger('minimum_inventory')->nullable(false);
            $table->timestampsTz();
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
        Schema::dropIfExists('products');
    }
}
