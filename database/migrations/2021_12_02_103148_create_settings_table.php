<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fav_name')->nullable();
            $table->string('fav_icon')->nullable();
            $table->string('logo_name')->nullable();
            $table->string('logo_image')->nullable();
            $table->longText('sms_text')->nullable();
            $table->string('sms_token')->nullable();
            $table->string('sms_sender')->nullable();
            $table->string('email_address')->nullable();
            $table->string('email_password')->nullable();
            $table->longText('reminder_text')->nullable();
            $table->longText('member_sms_text')->nullable();
            $table->longText('voter_annouce')->nullable();
            $table->longText('member_annouce')->nullable();
            $table->tinyInteger('otp_enable')->default(0);
            $table->tinyInteger('result_enable')->default(0);
            $table->string('otp_valid_time')->nullable();
            $table->string('otp_valid_time_type')->nullable();
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
        Schema::dropIfExists('settings');
    }
}