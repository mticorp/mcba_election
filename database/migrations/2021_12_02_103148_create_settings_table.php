<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->longText('reminder_text')->nullable();
            $table->longText('member_sms_text')->nullable();
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
