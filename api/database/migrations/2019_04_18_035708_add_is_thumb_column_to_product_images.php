<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsThumbColumnToProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_thumb')->nullable()->default(null)->after('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_images', function (Blueprint $table) {
            $table->dropColumn('is_thumb');
        });
    }
}
