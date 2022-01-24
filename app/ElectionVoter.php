<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectionVoter extends Model
{
    protected $fillable = [
        'election_id','voter_id','done','status'
    ];

    public function getVoterAutoId($voter_id)
    {
        $result = Voter::select('id')->where('voter_id',$voter_id)->first();
        return $result->id;
    }

    public function statusUpdate($voter_id,$election_id,$status)
    {
        ElectionVoter::where('voter_id',$voter_id)->where('election_id',$election_id)->update(['status' => $status]);
        return true;
    }

    public function checkDone($voter_id,$election_id)
    {
        $result = ElectionVoter::where('voter_id',$voter_id)->where('election_id',$election_id)->where('done',1)->count();
        return $result;
    }

    public function checkAllDone($voter_id,$election_id)
    {
        $result = ElectionVoter::where('voter_id',$voter_id)->where('election_id',$election_id)->where('done',1)->orWhere('done',2)->count();
        return $result;
    }

    public function voterInfo($voter_id,$election_id)
    {
        $result = ElectionVoter::select('election_voters.*','voter.isVerified as isVerified','voter.voter_id as VId')
        ->join('voter','voter.id','election_voters.voter_id')
        ->where('election_voters.voter_id',$voter_id)
        ->where('election_voters.election_id',$election_id)        
        ->first();
        return $result;
    }

    public function election()
    {
        return $this->belongsTo(Election::class,'voter_id','id');
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class,'voter_id','id');
    }

    public function log()
    {
        return $this->belongsTo(Log::class,'voter_id','voter_id');
    }
}
