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
            $table->bigIncrements('id'); // bigint(20)
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('mailing');
            $table->string('name');
            
            $table->string('phone');
            $table->string('contactname');
            $table->string('contactno');
            $table->string('contactemail');
            $table->string('website');
            
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
        Schema::dropIfExists('companies');
    }
}
