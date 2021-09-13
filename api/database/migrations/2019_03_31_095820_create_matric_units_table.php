<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatricUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matric_units', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 64)->nullable(false)->unique();
            $table->string('symbol', 8)->nullable(false)->unique();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matric_units');
    }
}
