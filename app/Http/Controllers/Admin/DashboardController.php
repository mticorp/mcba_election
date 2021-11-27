<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Election;
use App\Candidate;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index($election_id)
    {        
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        // dd($election);
        if($election)
        {
            $elections = $election_modal->electionWithoutCurrent($election_id);

            $candidates = Candidate::where('election_id', '=', $election_id)->orderby('id', 'asc')->take(8)->get();
            $total_count = DB::table('voter')->count();
            $total_answer_count = DB::table('answers')->count();

            $voting_count = DB::table('voter')
                        ->join('election_voters','election_voters.voter_id','=','voter.id')
                        ->where('election_voters.election_id',$election_id)
                        
                        ->where('election_voters.done','=',1)
                        ->count();
            $voting_reject_count =DB::table('voter')
                        ->join('election_voters','election_voters.voter_id','=','voter.id')
                        ->where('election_voters.election_id',$election_id)
                               
                        ->where('election_voters.done','=',2)
                        ->count();
            $not_voted_count = DB::table('voter')
                        ->join('election_voters','election_voters.voter_id','=','voter.id')
                        ->where('election_voters.election_id',$election_id)
                               
                        ->where('election_voters.done','=',0)
                        ->count();
            
            $answer_count = DB::table('voter')
                        ->join('election_voters','election_voters.voter_id','=','voter.id')
                        ->where('election_voters.election_id',$election_id)
                        
                        ->where('election_voters.done','=',1)
                        ->count();

            if($total_answer_count !=0)
            {
                $percent_answer_count = ($answer_count / $total_answer_count) * 100;
            }else{
                $percent_answer_count = 0;
            }
            
            if($total_count != 0)
            {
                $percent_voting_count = ($voting_count / $total_count) * 100;
                $percent_voting_count = number_format((float)$percent_voting_count, 0, '.', '');

                $pecrent_voting_reject_count = ($voting_reject_count / $total_count) * 100;
                $pecrent_voting_reject_count = number_format((float)$pecrent_voting_reject_count, 0, '.', '');

                $percent_not_voted_count = ($not_voted_count / $total_count) * 100;
                $percent_not_voted_count = number_format((float)$percent_not_voted_count, 0, '.', '');
            }else{
                $percent_voting_count = 0;
                $pecrent_voting_reject_count = 0;
                $percent_not_voted_count = 0;
            }

            $ques_count = DB::table('questions')->where('election_id',$election_id)->count();

            $ques = DB::table('questions')->where('election_id',$election_id)->take(2)->get();
            return view('admin.dashboard',compact('election','elections','percent_voting_count','pecrent_voting_reject_count','percent_not_voted_count','candidates','ques','ques_count','percent_answer_count'));
        }
        else{
            return abort(404);
        }
    }

    public function election()
    {
        return view('admin.election.index');
    }
}
