<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundlesImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('bundles_images', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('product_id')->nullable(false);
            $table->string('path', 512)->nullable(false);
            $table->unsignedTinyInteger('is_thumb')->nullable()->default(null);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundles_images');
    }
}
