<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionEntryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_entry_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_entry_id')->unsigned();
            //$table->bigInteger('transaction_no')->unsigned();
            $table->string('transaction_type');
            $table->integer('account_code')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 14, 2);
            $table->decimal('sub_total', 14, 2);
            $table->string('tax_type');
            $table->decimal('tax_excluded', 14, 2);
            $table->decimal('vat', 14, 2);
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
        Schema::dropIfExists('transaction_entry_items');
    }
}
