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
        'reminder_text',
        'member_sms_text'
    ];

    protected $table = 'settings';
}
