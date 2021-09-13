<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 512)->nullable()->default(null);
            $table->string('landscape', 256)->nullable()->default(null);
            $table->string('portrait', 256)->nullable()->default(null);
            $table->string('href', 1028)->nullable()->default(null);
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('published_at')->nullable()->default(null);
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('deleted_at')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_banners');
    }
}
