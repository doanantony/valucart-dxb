<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("auth_codes", function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedSmallInteger("customer_id")->nullable(false);
            $table->string("delivery_mode", 256)->nullable()->default(null);
            $table->string("code", 128)->nullable(false);
            $table->string("purpose", 128)->nullable(false);
            $table->dateTimeTz("created_at")->nullable(false)->useCurrent();
            $table->dateTimeTz("updated_at")->nullable(false)->useCurrent();
            $table->dateTimeTz("expires_at")->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("auth_codes");
    }
}
