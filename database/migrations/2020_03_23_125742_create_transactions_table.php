<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payee_id')->unsigned();
            $table->integer('account_code')->unsigned();
            $table->bigInteger('transaction_no')->unsigned();
            $table->string('account_name');
            $table->string('reference_no');
            $table->string('transaction_no');
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('credit_amount', 14, 2);
            $table->decimal('debit_amount', 14, 2);
            $table->decimal('amount_ex_tax', 14, 2);
            $table->decimal('vat', 14, 2);
            $table->boolean('canceled')->default(0);
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('transactions');
    }
}
