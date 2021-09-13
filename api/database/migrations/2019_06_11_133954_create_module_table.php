<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('module_name',124);
            $table->string('module_control',124);
            $table->string('module_menu',124);
            $table->string('module_class',124);
            $table->unsignedSmallInteger('module_priority')->nullable(true);
            $table->unsignedSmallInteger('object_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
