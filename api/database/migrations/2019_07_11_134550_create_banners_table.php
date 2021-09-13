<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("banners", function (Blueprint $table) {
            $table->smallIncrements("id");
            $table->string("position", 128)->nullable(false);
            $table->string("name", 128)->nullable()->default(null);
            $table->string("landscape", 512)->nullable()->default(null);
            $table->string("portrait", 512)->nullable()->default(null);
            $table->string("href", 512)->nullable()->default(null);
            $table->string("resource_type", 128)->nullable()->default(null);
            $table->string("resource_identifier", 128)->nullable()->default(null);
            $table->unsignedTinyInteger("order")->nullable()->default(null);
            $table->dateTimeTz("published_at")->nullable()->default(null);
            $table->dateTimeTz("created_at")->nullable(false)->useCurrent();
            $table->dateTimeTz("updated_at")->nullable(false)->useCurrent();
            $table->dateTimeTz("deleted_at")->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("banners");
    }
}
