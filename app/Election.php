<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Election extends Model
{
    protected $table = 'election';
    protected $fillable = [
        'name',
        'description',
        'smsdescription',
        'reminderdescription',
        'election_title_description',
        'no_of_position_mm',
        'no_of_position_en',
        'candidate_id',
        'candidate_title',
        'ques_title',
        'ques_description',
        'position',
        'company_id',
        'start_time',
        'end_time',
        'lucky_flag',
        'ques_flag',
        'candidate_flag',
        'duration_from',
        'duration_to',
    ];

    public function electionWithId($election_id)
    {
        $election = DB::table('election')
                    ->select('election.*','company.company_logo','company.company_name')
                    ->where('election.id',$election_id)
                    ->leftJoin('company','company.id','=','election.company_id')
                    ->first();
        return $election;
    }

    public function electionWithoutCurrent($election_id)
    {
        $elections = DB::table('election')
                        ->where('election.id','!=',$election_id)
                        ->get();
        return $elections;
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }
}
