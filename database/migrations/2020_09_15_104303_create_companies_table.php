<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;

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
