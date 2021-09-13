<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactEmailToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('customers', function (Blueprint $table) {
            $table->string('contact_email', 256)->nullable()->default(null)->after('email');
            $table->string('contact_phone_number', 32)->nullable(true)->default(null)->after('phone_number');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([ 'contact_email', 'contact_phone_number' ]);
        });
    }
}
