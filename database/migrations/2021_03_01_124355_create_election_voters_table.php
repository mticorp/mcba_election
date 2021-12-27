<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectionVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_voters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('voter_id');
            $table->tinyInteger('done')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('lucky_flag')->default(0);
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
        Schema::dropIfExists('election_voters');
    }
}
