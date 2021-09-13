<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendorcategories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 64)->nullable(false);    
            $table->string("icon", 512)->nullable()->default(null);
            $table->unsignedSmallInteger('status')->nullable(false)->default('1');
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
        Schema::dropIfExists('vendor_categories');
    }
}
