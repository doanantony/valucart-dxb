<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopularCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popular_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',64)->nullable(false)->unique();
            $table->string('description',1512)->nullable(true);
            $table->string('image',256)->nullable()->default(null);
            $table->unsignedSmallInteger('status')->nullable(false)->default('1');

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
        Schema::dropIfExists('popular_customers');
    }
}
