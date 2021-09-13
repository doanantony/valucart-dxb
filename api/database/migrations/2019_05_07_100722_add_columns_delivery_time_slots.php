<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDeliveryTimeSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_time_slots', function (Blueprint $table) {

            $table->string('time_slots', 64)->nullable()->default('null')->change();
            $table->time('from')->nullable()->default(null)->after('time_slots');
            $table->time('to')->nullable()->default(null)->after('from');

            $table->dropUnique('delivery_time_slots_time_slots_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_time_slots', function (Blueprint $table) {
            $table->dropColumn(['from', 'to']);
            $table->unique('time_slots');
        });
    }
}
