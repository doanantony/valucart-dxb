<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bundles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('customer_id')->nullable(false);
            $table->string('name', 64)->nullable()->default(null);    
            $table->string('description',512)->nullable()->default(null);    
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
        Schema::dropIfExists('customer_bundles');
    }
}
