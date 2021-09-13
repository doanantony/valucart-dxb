<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsMetaTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_meta_tags', function (Blueprint $table) {
            

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->mediumInteger('product_id')->unique();
            $table->string('description',256)->nullable();
            $table->string('page_title',128)->nullable();
            $table->string('keywords',256)->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->primary('product_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_meta_tags');
    }
}
