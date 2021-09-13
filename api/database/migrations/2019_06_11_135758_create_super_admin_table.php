<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_admin', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('user_type_id')->nullable(false)->default('1');
            $table->string('company_name',124)->nullable(false);
            $table->string('first_name',124)->nullable(false);
            $table->string('last_name',124)->nullable(false);
            $table->string('display_name',124)->nullable(false);
            $table->string('username',124)->nullable(false);
            $table->string('password',124)->nullable(false);
            $table->string('profile_pic', 512)->nullable(false);
            $table->unsignedSmallInteger('status')->nullable(false)->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_admin');
    }
}
