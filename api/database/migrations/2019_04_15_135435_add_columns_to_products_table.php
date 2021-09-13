<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('is_offer', ['0', '1'])->nullable(false)->default('0')->after('is_featured');
            $table->unsignedSmallInteger('packaging_type')->nullable(true)->default(null)->after('packaging_quantity_unit_id');
            $table->unsignedSmallInteger('type')->nullable(true)->default(null)->after('name');
            $table->unsignedTinyInteger('bulk_quantity')->nullable(false)->default(0)->after('is_bulk');
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
            $table->dropColumn(['is_offer', 'packaging_type', 'type', 'bulk_quantity']);
        });
    }
}
