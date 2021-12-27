<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuckyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lucky', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->string('phone');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('voter_id');
            $table->timestamps();

            $table->foreign('election_id')
            ->references('id')->on('election')
            ->onDelete('cascade');

            $table->foreign('voter_id')
            ->references('id')->on('voter')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lucky');
    }
}
