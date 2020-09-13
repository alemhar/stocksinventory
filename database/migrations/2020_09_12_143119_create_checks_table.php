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
            $table->string('check_bank_branch');
            $table->date('check_date');
            $table->decimal('check_amount', 14, 2);
            $table->string('reference_no');
            $table->bigInteger('transaction_no')->unsigned();
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->string('deposit_reference_no');
            $table->string('deposit_date');
            $table->decimal('deposit_amount', 14, 2);
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
