<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favicon extends Model

{
    
    protected $table = 'favicon';
    protected $fillable = [
        'favicon_name',
        'favicon',

    ];
}
