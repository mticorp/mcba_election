<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('candidate_no');
            $table->string('mname');
            $table->string('company')->nullable();
            $table->longText('address')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('position')->nullable();
            $table->string('nos')->nullable();
            $table->string('age')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('vote_count')->default(0);
            $table->longText('biography')->nullable();
            $table->string('nrc_no')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->longText('education')->nullable();
            $table->string('company_start_date')->nullable();
            $table->string('no_of_employee')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_address')->nullable();
            $table->longText('experience')->nullable();
            $table->unsignedBigInteger('election_id');
            $table->timestamps();

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
        Schema::dropIfExists('candidate');
    }
}
