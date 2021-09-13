<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderScheduleIntervalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_schedule_interval', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 64)->nullable(false);
            $table->string('interval', 64)->nullable(false);
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
        });

        DB::table('order_schedule_interval')->insert([
            [ 'name' => 'every monday', 'interval' => 'monday' ],
            [ 'name' => 'every tuesday', 'interval' => 'tuesday' ],
            [ 'name' => 'every wednesday', 'interval' => 'wednesday' ],
            [ 'name' => 'every thursday', 'interval' => 'thursday' ],
            [ 'name' => 'every friday', 'interval' => 'friday' ],
            [ 'name' => 'every saturday', 'interval' => 'saturday' ],
            [ 'name' => 'every sunday', 'interval' => 'sunday'],
            [ 'name' => 'Weekly (Every 7 days)', 'interval' => '1 week' ],
            [ 'name' => 'Bi weekly (Every 14 days)', 'interval' => '2 weeks' ],
            [ 'name' => 'Monthly (Every 30 days)', 'interval' => '1 month' ],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_schedule_interval');
    }
}
