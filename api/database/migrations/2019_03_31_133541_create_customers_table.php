<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('first_name', 32)->nullable(false);
            $table->string('last_name', 32)->nullable(false);
            $table->enum('gender', ['female', 'male'])->nullable()->default(null);
            $table->string('email', 256)->nullable()->default(null);
            $table->enum('email_verified', ['0', '1'])->nullable(false)->default('0');
            $table->string('phone_number', 32)->nullable(true)->default(null);
            $table->enum('phone_number_verified', ['0', '1'])->nullable(false)->default('0');
            $table->tinyInteger('country_id')->nullable(true)->default(null);
            $table->string('secret', 256)->nullable(true)->default(null);
            $table->string('oauth_provider', 32)->nullable(true)->default(null);
            $table->string('oauth_privider_user_id', 128)->nullable(true)->default(null);
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
