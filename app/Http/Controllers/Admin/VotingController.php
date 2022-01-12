<?php

namespace App\Http\Controllers\Admin;

use App\Answer;
use App\Candidate;
use App\Election;
use App\Exports\QuestionVotingResultExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use App\Voting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VotingController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth', 'admin']);
    }

    public function votingRecord($election_id)
    {


        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $election->candidate_flag == 0 && $election->ques_flag == 0 ?? abort(403);
            $voting_records = null;
            $candidates = null;
            $vote_count = null;
            $ques = null;
            $ans_records = null;
            $ans_summary = null;

            $elections = $election_modal->electionWithoutCurrent($election_id);

            if ($election->candidate_flag == 1) {
                $voting = new Voting;

                $voting_records = $voting->votingDetail($election_id);
                $candidates = Candidate::where('election_id', '=', $election_id)->orderby('id', 'asc')->get();
                $vote_count = DB::table('candidate')->select('vote_count')->where('election_id', $election_id)->get();
            }

            if ($election->ques_flag == 1) {
                $ques = Question::where('election_id', $election_id)->orderby('id', 'asc')->get();
                $ans = new Answer();
                $ans_records = $ans->answerDetail($election_id);
                $ans_summary = $ans->AnswerSummary($election_id);
            }

            return view('admin.voting.votingRecord', compact('election', 'elections', 'voting_records', 'candidates', 'vote_count', 'ques', 'ans_records', 'ans_summary'));
        } else {
            return abort(404);
        }

        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            if ($election->ques_flag == 1) {
                $elections = $election_modal->electionWithoutCurrent($election_id);

                $ques = Question::where('election_id', $election_id)->orderby('id', 'asc')->get();
                $ans = new Answer();
                $ans_records = $ans->answerDetail($election_id);
                // dd($ans_records);
                $ans_summary = $ans->AnswerSummary($election_id);
                // dd($ans_summary);
                return view('admin.voting.answer', compact('election', 'elections', 'ques', 'ans_records', 'ans_summary'));
            } else {
                abort(403);
            }
        } else {
            return abort(404);
        }
    }

    public function rejectVotingRecord($election_id)
    {
        $election_modal = new Election;


        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $voting = new Voting;
            $reject_voting_records = $voting->rejectvoting($election_id);
            // dd($reject_voting_records);
            $candidates = Candidate::where('election_id', '=', $election_id)->orderby('id', 'asc')->get();
            $voting_reject_summary = $voting->votingrejectSummary($election_id);
            // dd($voting_reject_summary);
            $elections = $election_modal->electionWithoutCurrent($election_id);

            return view('admin.voting.rejectVotingRecord', compact('election', 'elections', 'reject_voting_records', 'voting_reject_summary', 'candidates'));
        } else {
            return abort(404);
        }
    }

    public function notVotedRecord($election_id)
    {


        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $voting = new Voting;
            $no_voting_records = $voting->novoting($election_id);

            $elections = $election_modal->electionWithoutCurrent($election_id);

            return view('admin.voting.notVotedRecord', compact('election', 'elections', 'no_voting_records'));
        } else {
            return abort(404);
        }
    }

    public function votingResult($election_id)
    {
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $elections = $election_modal->electionWithoutCurrent($election_id);
            $voting_count = null;
            $voting_reject_count = null;
            $not_voted_count = null;
            $voting_results = null;
            $data = null;
            $candidates = null;
            $questions = null;
            $ques_data = null;

            if ($election->candidate_flag == 1) {
                $voting_count = DB::table('voter')
                    ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                    ->where('election_voters.election_id', $election_id)
                    ->where('election_voters.done', '=', 1)
                    ->count();
                $voting_reject_count = DB::table('voter')
                    ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                    ->where('election_voters.election_id', $election_id)
                    ->where('election_voters.done', '=', 2)
                    ->count();
                $not_voted_count = DB::table('voter')
                    ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                    ->where('election_voters.election_id', $election_id)
                    ->where('election_voters.done', '=', 0)
                    ->count();

                $candidates = Candidate::where('election_id', '=', $election_id)->orderby('id', 'asc')->get();

                $data = DB::table('candidate')
                    ->select('candidate.candidate_no as candidate_no', 'candidate.mname as mname', 'candidate.id as candidate_id', 'candidate.photo_url as photo', 'candidate.vote_count as vote_count')
                    ->orderBy('vote_count', 'DESC')
                    ->where('candidate.election_id', '=', $election_id)
                    ->where('candidate.vote_count', '!=', 0)
                    ->get();
            }

            if ($election->ques_flag == 1) {
                $questions = Question::with(['answers'])->get();
                foreach ($questions as $question) {
                    $yes_count = 0;
                    $no_count = 0;
                    foreach ($question->answers as $answer) {
                        $totalVoteCount =  $answer->voter_vote_count;
                        if ($answer->ans_flag == 1) {
                            $yes_count += $totalVoteCount;
                        } else {
                            $no_count += $totalVoteCount;
                        }
                    }
                    $question->yes_count = $yes_count;
                    $question->no_count = $no_count;
                }
            }
            return view('admin.voting.votingResult', compact('election', 'elections', 'voting_results', 'data', 'ques_data', 'candidates', 'voting_count', 'voting_reject_count', 'not_voted_count', 'questions'));
        } else {
            return abort(404);
        }
    }

    public function get_candidateList()
    {

        $count = $_GET['count'];
        $election_id = $_GET['election_id'];
        $result = DB::table('candidate')->orderBy('vote_count', 'DESC')->where('election_id', $election_id)->take($count)->get();
        echo json_encode($result);
    }

    public function get_questionList($election_id)
    {
        return Excel::download(new QuestionVotingResultExport($election_id), "ques_voting_result.xlsx");
    }

    public function get_result()
    {
        if (request()->ajax()) {
            $election_id = $_GET['election_id'];
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Candidate::orderBy('candidate_no', 'asc')->orderBy('vote_count','desc')->where('election_id', '=', $election_id)->get(['candidate.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) {
                    $button = '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="detail" id="' . $DT_data->id . '" class="detail btn btn-info btn-xs btn-flat"><i class="fas fa-eye"></i> Detail</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return abort(404);
        }
    }

    public function get_ques_result()
    {
        if (request()->ajax()) {
            $election_id = $_GET['election_id'];

            $questions = Question::with(['answers'])->where('election_id', $election_id)->get();
            foreach ($questions as $question) {
                $yes_count = 0;
                $no_count = 0;
                foreach ($question->answers as $answer) {
                    $totalVoteCount =  $answer->voter_vote_count;
                    if ($answer->ans_flag == 1) {
                        $yes_count += $totalVoteCount;
                    } else {
                        $no_count += $totalVoteCount;
                    }
                }
                $question->yes_count = $yes_count;
                $question->no_count = $no_count;
            }
            return datatables()->of($questions)
                ->addIndexColumn()
                ->addColumn('action', function ($DT_data) {
                    $button = '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="detail" id="' . $DT_data->id . '" class="detail btn btn-info btn-xs btn-flat"><i class="fas fa-eye"></i> Detail</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return abort(404);
        }
    }

    public function votingresultshow($candidate_id, $election_id)
    {


        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            $elections = $election_modal->electionWithoutCurrent($election_id);

            // $voting_detail = DB::table('voting')
            // ->join('candidate', 'candidate.id', '=', 'voting.candidate_id')
            // ->select('candidate.mname as mname', 'candidate.photo_url as photo_url', 'candidate.election_id as election_id')
            // ->groupBy('candidate.mname', 'candidate.photo_url', 'candidate.election_id')
            // ->where('candidate_id', '=', $candidate_id)
            // ->first();

            $voting_detail = DB::table('candidate')
                ->where('id', $candidate_id)
                ->select('candidate.mname as mname', 'candidate.photo_url as photo_url', 'candidate.election_id as election_id')
                ->first();
            // dd($voting_detail);

            $voter_id = DB::table('voting')
                ->select('voter.voter_id', 'voting.created_at', 'voter.vote_count')
                ->join('voter', 'voter.id', '=', 'voting.voter_id')
                ->where('candidate_id', '=', $candidate_id)
                ->groupBy('voter.voter_id', 'voting.created_at', 'voter.vote_count')
                ->get();

            return view('admin.voting.candidate-votingResult-detail', compact('voting_detail', 'voter_id', 'election', 'elections'));
        } else {
            return abort(404);
        }
    }

    public function questionvotingresultshow($question_id, $election_id)
    {


        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            $elections = $election_modal->electionWithoutCurrent($election_id);

            $voting_detail = Question::find($question_id);

            $voter_id = DB::table('answers')
                ->select('voter.voter_id', 'answers.created_at', 'answers.ans_flag')
                ->join('voter', 'voter.id', '=', 'answers.voter_id')
                ->where('answers.ques_id', '=', $question_id)
                ->groupBy('voter.voter_id', 'answers.created_at', 'answers.ans_flag')
                ->get();

            return view('admin.voting.question-votingResult-detail', compact('voting_detail', 'voter_id', 'election', 'elections'));
        } else {
            return abort(404);
        }
    }
}
