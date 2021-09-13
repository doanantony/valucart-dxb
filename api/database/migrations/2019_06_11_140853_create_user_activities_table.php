<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('user_id');
            $table->unsignedSmallInteger('user_type_id')->nullable(false);
            $table->unsignedSmallInteger('object_id')->nullable(true);
            $table->string('date_time',512);
            $table->string('log',1512);
            $table->string('edited_id',1512);
            $table->string('ip_adress',1512);
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
        Schema::dropIfExists('user_activities');
    }
}
