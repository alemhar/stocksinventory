<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('transaction_id')->unsigned();
            $table->bigInteger('transaction_no')->unsigned();
            $table->string('transaction_type');

            $table->integer('account_code')->unsigned();
            $table->string('account_name');

            $table->date('transaction_date');
            $table->decimal('credit_amount', 14, 2);
            $table->decimal('debit_amount', 14, 2);
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
        Schema::dropIfExists('ledgers');
    }
}
