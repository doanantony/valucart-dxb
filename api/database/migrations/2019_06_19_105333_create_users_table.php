<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('company_id')->nullable(false);
            $table->unsignedSmallInteger('user_id')->nullable(false);
            $table->unsignedSmallInteger('user_type_id')->nullable(false);
            $table->string('username',124)->nullable(false);
            $table->string('passwd',124)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
