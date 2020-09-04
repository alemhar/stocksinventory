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
            $table->string('entity_type');
            $table->integer('branch_id')->unsigned();
            $table->string('account_type'); // ** New
            $table->string('sub_account_type'); // ** New
            $table->integer('main_code')->unsigned(); // ** New
            $table->string('main_account'); // ** New
            $table->integer('account_code')->unsigned();
            $table->string('account_name');
            $table->integer('counterpart_code')->unsigned()->default(0);
            $table->string('counterpart_name')->default('NA');
            $table->string('reference_no');
            $table->bigInteger('transaction_no')->unsigned();
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('credit_amount', 14, 2)->default(0);
            $table->decimal('debit_amount', 14, 2)->default(0);
            $table->decimal('amount_ex_tax', 14, 2)->default(0);
            $table->decimal('vat', 14, 2)->default(0);
            $table->string('wtax_code');
            $table->decimal('wtax', 14, 2)->default(0);
            $table->string('type'); // GOODS, SERVICE, CAPITAL GOODS
            $table->decimal('total_payment', 14, 2)->default(0);
            $table->decimal('total_collection', 14, 2)->default(0);
            $table->decimal('total_deduction', 14, 2)->default(0);
            $table->date('depreciation_date');
            $table->integer('depreciated_id')->unsigned()->default(0);
            $table->boolean('canceled')->default(0);
            $table->integer('user_id')->unsigned();
            $table->integer('useful_life')->default(0);
            $table->decimal('salvage_value', 14, 2)->default(0);
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
