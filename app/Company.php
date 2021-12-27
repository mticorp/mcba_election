<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name','company_logo'
    ];

    protected $table = 'company';

    public function elections()
    {
        return $this->hasMany('App\Election','id');
    }
}
