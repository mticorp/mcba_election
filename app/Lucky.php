<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lucky extends Model
{
    protected $table = 'lucky';

    protected $fillable = [
        'code','name','phone','election_id','voter_id'
    ];
}
