<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSnapshotToAdminordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */



    public function up()
    {
        Schema::table('adminorders', function (Blueprint $table) {
            $table->unsignedSmallInteger('snapshots')->nullable(true)->after('time_slot_id');
           
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('_adminorders', function (Blueprint $table) {
            //
        });
    }
}
