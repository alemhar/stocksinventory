<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company');
            $table->string('address');
            $table->string('address2');
            $table->string('city');
            $table->string('tin');
            $table->string('tin1', 5);
            $table->string('tin2', 5);
            $table->string('tin3', 5);
            $table->string('branch_code', 5);
            $table->string('rdo_code', 10);
            $table->string('line_of_business');
            $table->string('zip_code', 10);
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

        DB::update("ALTER TABLE companies AUTO_INCREMENT = 100;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
