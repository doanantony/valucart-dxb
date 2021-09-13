<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals_banners', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('vendor_id')->nullable(false);
            $table->string('type', 64)->nullable(false);    
            $table->string("href", 2024)->nullable()->default(null);
            $table->string('icon', 2024)->nullable(false);    
            $table->string('rating', 64)->nullable(false);  
            $table->string('label1', 64)->nullable(false);    
            $table->string('label2', 64)->nullable(false);    
            $table->string('image', 2024)->nullable(false);  
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
        Schema::dropIfExists('deals_banners');
    }
}
