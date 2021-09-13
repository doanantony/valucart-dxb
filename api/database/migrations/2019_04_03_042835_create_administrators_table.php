<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrators', function (Blueprint $table) {
            
            $table->tinyIncrements('id')->nullable(false);
            $table->smallInteger('staff_id')->nullable(true)->default(null);
            $table->string('login_id', 128)->nullable(false);
            $table->string('secret', 256)->nullable(false);
            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrators');
    }
}
