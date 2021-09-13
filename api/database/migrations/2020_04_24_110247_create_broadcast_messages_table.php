<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("broadcast_messages", function (Blueprint $table) {
            $table->tinyIncrements("id");
            $table->string("type", 32)->nullable(false);
            $table->string("message", 2048)->nullable(false);
            $table->timestampTz("publish_at")->nullable(false)->useCurrent();
            $table->timestampTz("expires_at")->nullable(true)->default(null);
            $table->timestampTz("unpublished_at")->nullable(true)->default(null);
            $table->timestampsTz(0);
            $table->softDeletesTz("deleted_at", 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broadcast_messages');
    }
}
