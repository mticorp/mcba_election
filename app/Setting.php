<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'fav_name',
        'fav_icon',
        'logo_name',
        'logo_image',
        'sms_text',
        'sms_token',
        'sms_sender',
        'email_address',
        'email_password',
        'reminder_text',
        'member_sms_text',
        'otp_enable',
        'result_enable',
        'otp_valid_time',
        'otp_valid_time_type',
        'member_annouce',
        'voter_annouce',
    ];

    protected $table = 'settings';
}
