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
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('credit_amount', 14, 2)->default(0);
            $table->decimal('debit_amount', 14, 2)->default(0);
            $table->decimal('total_payment', 14, 2)->default(0);
            $table->decimal('amount_ex_tax', 14, 2)->default(0);
            $table->decimal('vat', 14, 2)->default(0);
            $table->string('wtax_code');
            $table->decimal('wtax', 14, 2)->default(0);
            $table->boolean('canceled')->default(0);
            $table->integer('user_id')->unsigned();
            //$table->tinyInteger('filter');
            $table->string('status', 20)->default('UNCONFIRMED');
            $table->integer('branch_id')->unsigned()->default(0);
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
