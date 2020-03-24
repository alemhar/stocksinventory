<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_code')->unsigned();
            $table->string('account_name');
            $table->string('entry_name');
            $table->string('entry_description');
            $table->integer('branch_code')->unsigned();
            $table->string('tax_type');
            $table->decimal('amount', 14, 2);
            $table->decimal('amount_ex_tax', 14, 2);
            $table->decimal('vat', 14, 2);
            $table->date('transaction_date');
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
        Schema::dropIfExists('transaction_entries');
    }
}
