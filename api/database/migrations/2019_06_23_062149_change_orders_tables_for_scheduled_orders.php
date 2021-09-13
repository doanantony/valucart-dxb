<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrdersTablesForScheduledOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('schedule_start_date')->nullable()->default(null)->after('delivery_date');
            $table->unsignedTinyInteger('schedule_interval_id')->nullable()->default(null)->after('schedule_start_date');
            $table->date('schedule_next_date')->nullable()->default(null)->after('schedule_interval_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'schedule_start_date',
                'schedule_interval_id',
                'schedule_next_date'
            ]);
        });

    }
}
