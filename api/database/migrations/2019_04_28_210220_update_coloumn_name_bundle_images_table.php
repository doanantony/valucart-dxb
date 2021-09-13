<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColoumnNameBundleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundles_images', function (Blueprint $table) {
            $table->renameColumn('product_id', 'bundle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bundles_images', function (Blueprint $table) {
            $table->renameColumn('bundle_id', 'product_id');
        });
    }
}
