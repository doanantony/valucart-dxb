<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('function', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('module_id')->nullable(false);
            $table->unsignedSmallInteger('message_id')->nullable(true);
            $table->string('function_name',124)->nullable(false);
            $table->string('function_path',124)->nullable(false);
            $table->string('function_menu',124)->nullable(false);
            $table->string('function_title',124)->nullable(false);
            $table->string('function_head',124)->nullable(false);
            $table->string('function_small',124)->nullable(false);
            $table->unsignedSmallInteger('parent')->nullable(false);
            $table->string('function_class',124)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('function');
    }
}
