<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCutOffTimeToDeliveryTimeSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("delivery_time_slots", function (Blueprint $table) {
            $table->string("cut_off", 64)->nullable(true)->default(null)->after("to");
            $table->timestampTz("unpublished_at")->nullable(true)->default(null)->after("cut_off");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("delivery_time_slots", function (Blueprint $table) {
            $table->dropColumn(["cut_off", "unpublished_at"]);
        });
    }
}
