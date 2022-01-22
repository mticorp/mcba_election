<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'voter_id','sms_flag','email_flag','reminder_sms_flag','reminder_email_flag',
    ];
}
