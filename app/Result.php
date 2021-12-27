<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = "result";

    protected $fillable = [
        'candidate_id','vote_count','created_at','updated_at','election_id'
    ];
}
