<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPhoneNumberVerificationCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_phone_number_verification_codes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('customer_id')->nullable(false);
            $table->smallInteger('code')->nullable(false);
            $table->timestampTz('created_at')->nullable(false)->useCurrent();
            $table->timestampTz('expires_at')->nullable(true)->default(null);
            $table->timestampTz('used_at')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_phone_number_verification_codes');
    }
}
