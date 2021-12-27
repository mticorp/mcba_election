<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->unsignedBigInteger('voter_id');           
            $table->tinyInteger('sms_flag')->default(0);
            $table->tinyInteger('email_flag')->default(0);
            $table->tinyInteger('reminder_sms_flag')->default(0);
            $table->tinyInteger('reminder_email_flag')->default(0);               
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
        Schema::dropIfExists('logs');
    }
}
