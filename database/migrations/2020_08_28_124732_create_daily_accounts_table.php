<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_type'); // ** New
            $table->string('sub_account_type'); // ** New
            $table->integer('main_code')->unsigned(); // ** New
            $table->string('main_account'); // ** New
            $table->integer('account_code')->unsigned();
            $table->string('account_name');
            $table->date('transaction_date');
            //$table->decimal('amount', 14, 2);
            $table->decimal('credit_amount', 14, 2)->default(0);
            $table->decimal('debit_amount', 14, 2)->default(0);
            $table->string('type'); // GOODS, SERVICE, CAPITAL GOODS
            $table->integer('user_id')->unsigned();
            $table->string('status', 20)->default('UNCONFIRMED');
            $table->string('transaction_type'); // ** New
            $table->string('entity_type'); // ** New
            $table->string('taxed', 5); // ** New
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
        Schema::dropIfExists('daily_accounts');
    }
}
