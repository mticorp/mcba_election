<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('ans_flag')->default(0);
            $table->smallInteger('no_vote_count')->default(0);
            $table->smallInteger('yes_vote_count')->default(0);
            $table->unsignedBigInteger('ques_id');
            $table->unsignedBigInteger('voter_id');
            $table->timestamps();

            $table->foreign('ques_id')
            ->references('id')->on('questions')
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
        Schema::dropIfExists('answers');
    }
}
