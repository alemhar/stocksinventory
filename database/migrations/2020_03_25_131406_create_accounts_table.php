<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_type');// ADD Default on current tables
            $table->string('sub_account_type');// ADD Default on current tables
            $table->integer('main_code')->unsigned(); // ADD Default on current tables
            $table->string('main_account');// ADD Default on current tables
            $table->integer('account_code')->unsigned();
            $table->string('account_name');
            $table->integer('counterpart_code')->unsigned()->default(0);
            $table->string('counterpart_name')->default('NA');
            $table->string('type')->default('NA'); // GOODS, SERVICE, CAPITAL GOODS
            $table->integer('user_id')->unsigned();
            $table->integer('cd_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('cd_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('cr_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('cr_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('sales_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('sales_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('purchase_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('purchase_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('payment_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('payment_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('collection_list_order')->unsigned()->default(99);// ADD Default on current tables
            $table->integer('collection_list')->unsigned()->default(3);// ADD Default on current tables
            $table->integer('filter')->unsigned()->default(99);
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
        Schema::dropIfExists('accounts');
    }
}
