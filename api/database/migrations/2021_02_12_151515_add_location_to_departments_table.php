<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string("latitude", 512)->nullable(true)->default(null)->after("name");
            $table->string("longitude", 512)->nullable(true)->default(null)->after("name");
            $table->tinyInteger('user_type_id')->nullable()->default(null)->after('id');
            $table->string('email', 256)->nullable()->default(null)->after("name");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            //
        });
    }
}
