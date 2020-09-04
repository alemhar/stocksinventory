<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('tin');
            $table->string('mailing');
            $table->string('phone');
            $table->string('contactname');
            $table->string('contactno');
            $table->string('contactemail');
            $table->string('website');
            $table->decimal('payable', 14, 2)->default(0);
            $table->decimal('receivable', 14, 2)->default(0);
            $table->string('entity_type')->default('PRIVATE');
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
        Schema::dropIfExists('payees');
    }
}
