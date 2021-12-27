<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('voter_id');
            $table->unsignedBigInteger('election_id');
            $table->bigInteger('vote_count')->default(0);
            $table->timestamps();

            $table->foreign('candidate_id')
            ->references('id')->on('candidate')
            ->onDelete('cascade');

            $table->foreign('voter_id')
            ->references('id')->on('voter')
            ->onDelete('cascade');

            $table->foreign('election_id')
            ->references('id')->on('election')
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
        Schema::dropIfExists('voting');
    }
}
