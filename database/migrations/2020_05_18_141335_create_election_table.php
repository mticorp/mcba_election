<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('position')->nullable();
            $table->string('name');
            $table->string('description')->nullable();            
            $table->longText('election_title_description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('lucky_flag')->default(0);
            $table->tinyInteger('ques_flag')->default(0);
            $table->string('ques_title')->nullable();
            $table->string('ques_description')->nullable();
            $table->tinyInteger('candidate_flag')->default(0);
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->dateTime('duration_from')->nullable();
            $table->dateTime('duration_to')->nullable();
            $table->string('no_of_position_mm',30)->nullable();
            $table->string('no_of_position_en',30)->nullable();
            $table->string('candidate_title')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            $table->foreign('company_id')
            ->references('id')->on('company')
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
        Schema::dropIfExists('election');
    }
}
