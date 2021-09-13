<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerEmailVerficationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_email_verfication_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('customer_id')->nullable(false);
            $table->string('code', 16)->nullable(false);
            $table->dateTimeTz('created_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('updated_at')->nullable(false)->useCurrent();
            $table->dateTimeTz('expires_at')->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_email_verfication_codes');
    }
}
