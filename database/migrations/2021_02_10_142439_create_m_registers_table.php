<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('profile')->nullable();
            $table->string('name')->nullable();
            $table->string('nrc')->nullable();
            $table->string('refer_code')->nullable();
            $table->string('complete_training_no')->nullable();
            $table->string('valuation_training_no')->nullable();
            $table->string('AHTN_training_no')->nullable();
            $table->string('graduation')->nullable();
            $table->longText('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('officeName')->nullable();
            $table->string('office_startDate')->nullable();
            $table->string('officeAddress')->nullable();
            $table->string('officePhone')->nullable();
            $table->string('officeFax')->nullable();
            $table->string('officeEmail')->nullable();
            $table->string('yellowCard')->nullable();
            $table->string('pinkCard')->nullable();
            $table->integer('check_flag')->default(0);
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
        Schema::dropIfExists('m_registers');
    }
}
