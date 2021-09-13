<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_table', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('user_id')->nullable(false);
            $table->unsignedSmallInteger('user_type_id')->nullable(false);
            $table->string('unique_id',1064)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_table');
    }
}
