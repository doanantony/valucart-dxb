<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->nullable()->default(null)->after('payment_type');
            $table->unsignedTinyInteger('payment_status')->nullable()->default(null)->after('status');
            $table->unsignedTinyInteger('cancelation_status')->nullable()->default(null)->after('payment_status');
            $table->unsignedSmallInteger('canceled_by_admin_id')->nullable()->default(null)->after('cancelation_status');
            $table->string('cancelation_reason', 512)->nullable()->default(null)->after('canceled_by_admin_id');
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
                'status',
                'payment_status',
                'cancelation_status',
                'canceled_by_admin_id',
                'cancelation_reason',
            ]);
        });
    }
}
