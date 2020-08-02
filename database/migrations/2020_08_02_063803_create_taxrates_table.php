<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxrates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tax_type')->default('WE');
            $table->integer('tax_rate')->unsigned();
            $table->string('atc');
            $table->string('atc_code');
            $table->string('description');
            $table->string('condition');
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
        Schema::dropIfExists('taxrates');
    }
}
