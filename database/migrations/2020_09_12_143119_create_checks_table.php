<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('check_no');
            $table->string('check_bank');
            $table->date('check_date');
            $table->date('check_amount');
            $table->string('reference_no');
            $table->bigInteger('transaction_no')->unsigned();
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->string('status', 20)->default('UNCONFIRMED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checks');
    }
}
