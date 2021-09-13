<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',64)->nullable(false)->unique();
            $table->string('title',64)->nullable(true);
            $table->string('description',512)->nullable(true);
            $table->string('color_code',64)->nullable(false);
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
        Schema::dropIfExists('offers');
    }
}
