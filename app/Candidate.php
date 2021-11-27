<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //
    protected $table = 'candidate';

    protected $fillable = [
        'candidate_no',
		'election_id',
		'mname',
        'nrc_no',
        'dob',
        'gender',
        'position',   
        'phone_no',
		'email',     
        'education',
        'address',
        'company',
        'company_start_date',
        'no_of_employee',
        'company_phone',
        'company_email',
        'company_address',
        'experience',                
        'photo_url',        	
		'created_at',
        'updated_at',
        'biography'
    ];

    public function socials()
    {
        return $this->hasMany('App\Social');
    }

    public function interdegrees()
    {
        return $this->hasMany('App\Interdegree');
    }

    // public function degrees()
    // {
    //     return $this->hasMany('App\Degree');
    // }

    // public function scopeList(){
    //     $result =Candidate::join('election', 'election.id',   '=', 'candidate.election_id')
    //                    ->where('election.status', '=', '1')
    //                    ->select( DB::Raw("'' as No"),
    //                             'election.name',
    //                              'mname',
    //                             //  'dob',
    //                             //   'gender',
    //                             //  'father_name',
    //                             //   'gender',
    //                             //   'nrc',
    //                             //   'member_no',
    //                               'sm_no',
    //                               'photo',
    //                               //'expired_date',
    //                               'degree_name',
    //                               'degree_year',
    //                               'now_job',
    //                               'last_job',
    //                               //'address',
    //                               'phone_no',
    //                               'email',
    //                               'inter_degree_name',
    //                               'inter_degree_date',
    //                               'inter_degree_association',
    //                               'social_association',
    //                               'social_duty',
    //                               'social_duration',
    //                               //'motto',
    //                               //'remark',
    //                               'candidate.created_at',
    //                               'candidate.updated_at')
    //                    ->get();
    //     return $result;
    // }
}
