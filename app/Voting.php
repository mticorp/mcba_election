<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{

    protected $table = 'voting';
    protected $fillable = [
        'voter_id', 'election_id','candidate_id'
    ];

    public function scopeList(){
        $result =Voting::join('election', 'election.id',   '=', 'voting.election_id')
                        ->join('candidate','candidate.id','=','voting.candidate_id')
                        ->join('voter','voter.id','=','voting.voter_id')
                        ->where([
                            ['election.status', '=', '1'],
                            ['voter.done','=','1'],
                        ])
                        ->select('voting.id','election.name as election_name','candidate.mname as candidate_name','voter.voter_id','voting.created_at')
                        ->orderby('candidate.id', 'asc')
                        ->get();
                        // dd($result);
        return $result;

    }

    public function scopeReject(){
        $result =Voting::join('election', 'election.id',   '=', 'voting.election_id')
                        ->join('candidate','candidate.id','=','voting.candidate_id')
                        ->join('voter','voter.id','=','voting.voter_id')
                        ->where([
                            ['election.status', '=', '1'],
                            ['voter.done','=','2'],
                        ])
                        ->select('voting.id','election.name as election_name','candidate.mname as candidate_name','voter.voter_id','voting.created_at')
                        ->get();
        return $result;
    }

    public function scopeNotVoted(){
        $result =Voter::join('election','election.id','=','voter.election_id')
        ->where([
            ['election.status', '=', '1'],
            ['voter.done','=','0'],
        ])
        ->select('voter.id','election.name as election_name','voter.voter_id','voter.created_at')
        ->get();
        return $result;
    }

    public function votingDetail($election_id){
        $array =[];
        $voters = Voter::select('voter.*')->where('election_voters.done', '=', 1)
                        ->orWhere('election_voters.done',0)
                        ->where('election_voters.election_id','=',$election_id)                     
                        ->join('election_voters','voter.id','=','election_voters.voter_id')
                        ->get();
        // dd($voters);
        foreach($voters as $voter){
            if ($voter->done == 0) {
                $count = Voting::where('voter_id', $voter->id)->count();
                if ($count > 0) {
                    $result = $this->getVotingResult($voter->id, $election_id);
                    $array[$voter->voter_id] = $result;
                }
            } else {
                $result = $this->getVotingResult($voter->id, $election_id);
                $array[$voter->voter_id] = $result;
            }
        }
        return $array;
    }

    public function rejectvoting($election_id)
    {
        $array =[];
        $voters = Voter::select('voter.*')
                        ->where('election_voters.done', '=', 2)
                        ->where('election_voters.election_id','=',$election_id)
                        ->join('election_voters','voter.id','=','election_voters.voter_id')
                        ->get();        

        foreach($voters as $voter){
            $result = $this->getVotingResult($voter->id, $election_id);
            $array[$voter->voter_id] = $result;
        }
        // dd($array);
        return $array;
    }

    public function novoting($election_id)
    {
        $array =[];
        $voters = Voter::select('voter.*')->where('election_voters.done', '=', 0)
                        ->where('election_voters.election_id','=',$election_id)
                        ->join('election_voters','voter.id','=','election_voters.voter_id')
                        ->get();

        foreach($voters as $voter){
            $count = Voting::where('voter_id', $voter->id)->count();
            if ($count > 0) {
            } else {
                $result = $this->getVotingResult($voter->id, $election_id);
                $array[$voter->voter_id] = $result;
            }        
        }
        return $array;
    }

    public function votingSummary($election_id){
        $result = DB::table('candidate')
                 ->leftjoin('voting','voting.candidate_id','=','candidate.id')
                 ->leftjoin('voter','voter.id','=','voting.voter_id')
                 ->where('candidate.election_id','=',$election_id)
                 ->where('voter.done','=',1)
                 ->select('candidate.id as candidate_id',DB::raw('count(voting.candidate_id)as voting_count'))
                 ->groupBy('candidate.id')
                 ->orderBy('candidate.id', 'asc')
                 ->get();
        return $result;
    }

    public function novotingSummary($election_id){
        $result = DB::table('candidate')
                 ->leftjoin('voting','voting.candidate_id','=','candidate.id')
                 ->leftjoin('voter','voter.id','=','voting.voter_id')
                 ->leftjoin('election_voters','voter.id','=','election_voters.voter_id')
                 ->where('candidate.election_id','=',$election_id)
                 ->where('election_voters.done','=',0)
                 ->select('candidate.id as candidate_id',DB::raw('count(voting.candidate_id)as voting_count'))
                 ->groupBy('candidate.id')
                 ->orderBy('candidate.id', 'asc')
                 ->get();
        return $result;
    }

    public function votingrejectSummary($election_id){
        // dd($election_id);
        $result = DB::table('candidate')                                             
                ->select('candidate.id as candidate_id',DB::raw("(sum(voting.vote_count)) as voting_count")) //DB::raw("(count(voting.candidate_id))as voting_count")
                ->leftjoin('voting','voting.candidate_id','=','candidate.id')
                ->leftjoin(DB::raw("(select * from election_voters where election_id = '".$election_id."')as election_voters"),'election_voters.voter_id','=','voting.voter_id')
                ->where('voting.election_id',$election_id)
                ->where('election_voters.done',2)
                ->groupBy('candidate.id')
                ->orderBy('candidate.id', 'asc')
                ->get();
                //  dd($result);
        return $result;
    }
    public function getVotingResult($voter_id,$election_id){
        // dd($voter_id);
        $result =DB::table('candidate')
                ->leftjoin(DB::raw("(select * from voting where voter_id ='".$voter_id."')as voting"),'candidate.id','=','voting.candidate_id')
                ->where('candidate.election_id','=',$election_id)
                ->select('candidate.id','mname',DB::raw("(case when voting.voter_id is null then 0 else voting.vote_count end)as vote"))
                ->orderBy('candidate.id', 'asc')
                ->get();
        // dd($result);
        return $result;
    }
}
