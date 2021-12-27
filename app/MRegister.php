<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MRegister extends Model
{
    protected $fillable = [
        'profile',
        'name',
        'nrc',
        'refer_code',
        'complete_training_no',
        'valuation_training_no',
        'AHTN_training_no',
        'graduation',
        'address',
        'phone_number',
        'email',
        'officeName',
        'office_startDate',
        'officeAddress',
        'officePhone',
        'officeFax',
        'officeEmail',
        'yellowCard',
        'pinkCard',
        'check_flag'
    ];

    protected $table = 'm_registers';
}
