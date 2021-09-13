<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managementteam', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('user_type_id')->nullable(false)->default('2');
            $table->string('first_name',124)->nullable(false);
            $table->string('last_name',124)->nullable(false);
            $table->string('email',124)->nullable(false);
            $table->string('phone_no',124)->nullable(false);
            $table->string('status',124)->nullable(false);
            $table->string('user_act_id', 124)->nullable(true);
            $table->string('created_at',124)->nullable(false);
            $table->string('updated_at',124)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managementteam');
    }
}
