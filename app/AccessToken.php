<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $hidden = [
        'access_token'
    ];

    protected $table = 'accesstoken';
}
