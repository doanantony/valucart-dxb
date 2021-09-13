<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_location', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('vendor_id')->nullable(false);
            $table->string('name', 64)->nullable(false);    
            $table->string("short_name", 2024)->nullable()->default(null);
            $table->string('latitude', 2024)->nullable(false);    
            $table->string('longitude', 2024)->nullable(false);  
            $table->string('range', 512)->nullable(false);      
            $table->unsignedSmallInteger('published')->nullable(false)->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_location');
    }
}
