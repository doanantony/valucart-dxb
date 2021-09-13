<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaDataToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('meta_page_title', 128)->nullable()->after('is_offer');
            $table->string('meta_description', 256)->nullable()->after('meta_page_title');
            $table->string('meta_keywords', 256)->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumns(['meta_page_title', 'meta_description', 'meta_keywords']);
        });
    }
}
