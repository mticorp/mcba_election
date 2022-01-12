<?php

namespace App\Http\Controllers\Voter;

use App\Candidate;
use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Result;
use App\Voting;
use App\Question;
use App\Answer;
use App\ElectionVoter;
use App\Setting;
use App\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VoteController extends Controller
{
    public function result($election_id)
    {        
        Setting::first()->result_enable ? '' : abort(403);
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Result::orderBy('result.vote_count', 'desc')
                ->where('candidate.election_id', $election_id)
                ->leftJoin('candidate', 'candidate.id', '=', 'result.candidate_id')
                ->get(['candidate.*', 'result.vote_count as result_vote_count', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            // $DT_data = Candidate::orderBy('candidate.vote_count','desc')->where('candidate.election_id',$election_id)->get(['candidate.*','candidate.vote_count as result_vote_count', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->make(true);
        }
        if ($election) {
            $voter_table_id = Session::get('voter_table_id');
            $ans = new Answer();
            $ans_result = $ans->AnswerSummaryWithQuestionName($election_id);
            return view('voter.result-page', compact('election', 'ans_result', 'voter_table_id'));
        } else {
            return abort(404);
        }
    }

    public function candidateList($election_id)
    {              
        $voter_table_id = Session::get('voter_table_id');
        // dd($voter_table_id);
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            $exist = ElectionVoter::where('election_voters.voter_id', $voter_table_id)
                ->where('election_voters.election_id', $election->id)
                ->where('election_voters.done', '!=', 0)
                ->count() ? true : false;
            $voter = Voter::find($voter_table_id);
            if ($exist) {
                return view('error.already-voted', compact('voter'))->with('msg', "သင်သည် မဲပေးပြီးသွားပါပြီ။");
            }
            if ($election->status == 0) {
                return view('error.election_not_start', compact('voter'))->with('msg', 'မဲပေးပွဲ မစသေးပါ။');
            }
            if ($election->candidate_flag == 0) {
                if ($election->ques_flag == 1) {
                    return redirect()->route('vote.faq', ['election_id' => $election_id]);
                } else {
                    abort(403, "Please, There's no Voting Method, Contact Administator!");
                }
            }

            if ($voter) {
                if ($election->candidate_flag == 1 && $election->ques_flag == 1) {
                    $voter_record = Voting::where('election_id', $election->id)->where('voter_id', $voter_table_id)->count();

                    if ($voter_record > 0) {
                        return redirect()->route('vote.faq', $election->id);
                    } else {
                        $voter_vote_count = $voter->vote_count;
                        $position = $election->no_of_position_en;
                        $candidates = Candidate::where('election_id', '=', $election->id)->get();

                        return view('voter.candidatelist', compact('candidates', 'voter_table_id', 'position', 'voter_vote_count', 'election'));
                    }
                } elseif ($election->candidate_flag == 1 && $election->ques_flag == 0) {
                    $voter_vote_count = $voter->vote_count;
                    $position = $election->no_of_position_en;
                    $candidates = Candidate::where('election_id', '=', $election->id)->get();

                    return view('voter.candidatelist', compact('candidates', 'voter_table_id', 'position', 'voter_vote_count', 'election'));
                }
            } else {
                Session::forget('voter_table_id');
                return abort(403, 'Unauthorized Request!');
            }
        } else {
            return abort(404);
        }
    }

    public function CandidateDetail($election_id, $id)
    {
        
       
        $candidate = Candidate::where('election_id', $election_id)->where('id', $id)->first();
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            return view('voter.candidate-detail', compact('candidate', 'election'));
        } else {
            return abort(404);
        }
    }

    public function already()
    {               
        $voter_table_id = Session::get('voter_table_id');
        $voter = Voter::find($voter_table_id);
        return view('error.already-voted', compact('voter'))->with('msg', "သင်သည် မဲပေးပြီးသွားပါပြီ။");
    }

    public function unauthorized()
    {               
        return view('error.not-found')->with('msg', "ခွင့်ပြုချက် မရှိပါ။");
    }

    public function changeStatus(Request $request)
    {
        $voter_table_id = (int) $request->voter_table_id;
        $election_id = $request->election_id;
        $voter_vote_count = $request->voter_vote_count;
        $voter = DB::table('voter')
            ->join('election_voters', 'election_voters.voter_id', 'voter.id')
            ->select('election_voters.done')
            ->where('election_voters.election_id', $election_id)
            ->where('voter.id', '=', $voter_table_id)
            ->first();

        if ($voter->done == 0 && isset($voter_table_id)) {
            if (isset($_POST['candidate'])) {
                $candidates = $_POST['candidate'];
                foreach ($candidates as $candidate) {
                    $voting = new Voting();
                    $voting->candidate_id = $candidate;
                    $voting->voter_id = $voter_table_id;
                    $voting->election_id = $election_id;
                    $voting->vote_count = $voter_vote_count;
                    $voting->save();
                }
            }
            $election_voter = ElectionVoter::where('voter_id', $voter_table_id)->where('election_id', $election_id)->first();
            $election_voter->done = 2;
            $election_voter->update();
            return response()->json(['election_id' => $election_id]);
        } else {
            return response()->json(['errors' => 'Already Voted']);
        }
    }

    public function voterchangeStatus(Request $request)
    {
        $voter_id = (int) $request->voter_table_id;
        $election_id = $request->election_id;
        $data = new ElectionVoter();

        if ($data->statusUpdate($voter_id, $election_id, 1)) {
            return response()->json(['success' => 'success']);
        } else {
            return response()->json(['errors' => 'errors']);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $voter_id = (int) $request->voter_table_id;
        $voter_vote_count = $request->voter_vote_count;
        $election_id = $request->election_id;

        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            $data = new ElectionVoter();
            $voter = $data->voterInfo($voter_id, $election_id);

            if ($voter) {
                $exist = $data->checkAllDone($voter_id, $election_id);
                if ($exist > 0) {
                    return response()->json(['errors' => "already Voted"]);
                } else {
                    $data->statusUpdate($voter_id, $election_id, 2);
                    if ($voter->done == 0 && $election->candidate_flag == 1 && $election->ques_flag == 1) {
                        $voter_record = Voting::where('election_id', $election->id)->where('voter_id', $voter_id)->count();

                        if ($voter_record > 0) {
                            return response()->json(['success' => "Successfully!", 'election_id' => $election_id, 'ques_flag' => true]);
                        } else {
                            if (isset($_POST['candidate'])) {
                                $candidates = $_POST['candidate'];

                                foreach ($candidates as $candidate_id) {
                                    Candidate::where('id', '=', $candidate_id)->increment('vote_count', $voter_vote_count);

                                    Result::where([
                                        ['candidate_id', '=', $candidate_id],
                                        ['election_id', '=', $election_id]
                                    ])->increment('vote_count', $voter_vote_count);

                                    $voting = new Voting;
                                    $voting->candidate_id = $candidate_id;
                                    $voting->voter_id = $voter_id;
                                    $voting->election_id = $election_id;
                                    $voting->vote_count = $voter_vote_count;
                                    $voting->save();
                                }
                            }

                            return response()->json(['success' => "Successfully!", 'election_id' => $election_id, 'ques_flag' => true]);
                        }
                    } elseif ($voter->done == 0 && $election->candidate_flag == 1 && $election->ques_flag == 0) {
                        if (isset($_POST['candidate'])) {
                            $candidates = $_POST['candidate'];

                            foreach ($candidates as $candidate_id) {
                                Candidate::where('id', '=', $candidate_id)->increment('vote_count', $voter_vote_count);

                                Result::where([
                                    ['candidate_id', '=', $candidate_id],
                                    ['election_id', '=', $election_id]
                                ])->increment('vote_count', $voter_vote_count);

                                $voting = new Voting;
                                $voting->candidate_id = $candidate_id;
                                $voting->voter_id = $voter_id;
                                $voting->election_id = $election_id;
                                $voting->vote_count = $voter_vote_count;
                                $voting->save();
                            }
                        }

                        $voter->done = 1;
                        $voter->save();


                        return response()->json(['success' => "Successfully!", 'election_id' => $election_id, 'ques_flag' => false]);
                    } elseif ($voter->done != 0) {
                        //voted
                        return response()->json(['errors' => "Already Voted!"]);
                    }
                }
            } else {
                Session::forget('voter_table_id');
                return response()->json(['voter_notFound' => 'Voter does not exist!']);
            }
        } else {
            return response()->json(['election_notFound' => 'Election does not exist!']);
        }
    }

    public function faq($election_id)
    {
        
       
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            $voter_table_id = Session::get('voter_table_id');
            if ($election->ques_flag == 1) {
                $already_ans = DB::table('answers')->where('voter_id', $voter_table_id)->where('election_id', $election_id)->join('questions', 'questions.id', '=', 'answers.ques_id')->count() != 0 ? true : false;
                if ($already_ans) {
                    return view('error.alreadyanswer', compact('election'))->with('msg', "သင်သည် မေးခွန်းများကို ဖြေဆိုပြီးသား ဖြစ်သည်။");
                } else {
                    $ques = Question::where('election_id', $election_id)->get();
                    return view('voter.FAQ', compact('election', 'ques', 'voter_table_id'));
                }
            } else {
                return redirect()->route('vote.complete', ['election_id' => $election_id]);
            }
        } else {
            abort(404);
        }
    }


    public function faqStore(Request $request)
    {
        // dd($request->all());
        $data = new ElectionVoter();
       
        $voter = $data->voterInfo($request->voter_table_id, $request->election_id);

        if ($voter) {
            if ($data->statusUpdate($request->voter_table_id, $request->election_id, 3)) {
                foreach ($request->dtData as $data) {
                    $ans = new Answer();
                    $ans->voter_id = $request->voter_table_id;
                    $ans->ques_id = $data['ques_id'];
                    $ans->ans_flag = $data['checked_val'];
                    $ans->save();
                }
                ElectionVoter::where('voter_id', $request->voter_table_id)->where('election_id', $request->election_id)->update(['done' => 1]);
                return response()->json(['success' => 'Data Added successfully.', 'election_id' => $request->election_id]);
            } else {
                return response()->json(['errors' => 'errors']);
            }
        } else {
            return response()->json(['errors' => 'Voter Not Found!']);
        }
    }


    public function complete($election_id)
    {
       
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $voter_table_id = Session::get('voter_table_id');

            $data = new ElectionVoter();
            $voter = $data->voterInfo($voter_table_id, $election_id);

            if ($voter) {
                if ($voter->done != 0) {
                    $candidates = DB::table('candidate')->select('*')
                        ->join('voting', 'candidate.id', '=', 'voting.candidate_id')
                        ->join('voter', 'voter.id', '=', 'voting.voter_id')
                        ->where('voter.id', '=', $voter_table_id)
                        ->where('voting.election_id', $election_id)
                        ->get();

                    $answers = Answer::select('questions.ques', DB::raw("(CASE answers.ans_flag WHEN 1 THEN '1' ELSE '0' END) as ans"))
                        ->join('questions', 'questions.id', 'answers.ques_id')
                        ->where('answers.voter_id', $voter_table_id)
                        ->where('questions.election_id', $election_id)
                        ->get();
                   
                    return view('voter.completed', compact('voter', 'candidates', 'election', 'answers'));
                } else {
                    abort(404);
                }
            } else {
                return abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function storeCode(Request $request)
    {
        $code = mt_rand(100000, 999999);

        $election_id = $request->election_id;

        $voter_id = $request->voter_id;

        $voter = ElectionVoter::select('voter.*', 'election_voters.done as done', 'election_voters.lucky_flag as lucky_flag')
            ->join('voter', 'voter.id', 'election_voters.voter_id')
            ->where('election_voters.voter_id', $voter_id)
            ->where('election_voters.election_id', $election_id)
            ->first();

        if ($voter) {
            $exist = DB::table('lucky')->where('code', $code)->where('election_id', $election_id)->first();
            if ($exist) {
                do {
                    $new_code = mt_rand(100000, 999999);
                } while ($new_code == $exist->code);

                if ($voter->done == 1 && $voter->lucky_flag == 0) {
                    ElectionVoter::where('voter_id', $voter_id)->where('election_id', $election_id)->update(['lucky_flag' => 1]);
                    DB::table('lucky')->insert([
                        'code' => $new_code,
                        'name' => $voter->name,
                        'phone' => $voter->phone_no,
                        'election_id' => $election_id,
                        'voter_id' => $voter->id,
                    ]);

                    return response()->json(['success' => 'Status Change successfully.', 'code' => $new_code]);
                } else if ($voter->done == 1 && $voter->lucky_flag == 1) {
                    $old_code = DB::table('lucky')->where('election_id', $election_id)->where('voter_id', $voter_id)->first();
                    if ($old_code) {
                        return response()->json(['success' => 'Status Change successfully.', 'code' => $old_code->code]);
                    } else {
                        return response()->json(['errors' => 'Data not found!']);
                    }
                } else {
                    return response()->json(['errors' => 'Voter Not Done']);
                }
            } else {
                if ($voter->done == 1 && $voter->lucky_flag == 0) {
                    ElectionVoter::where('voter_id', $voter_id)->where('election_id', $election_id)->update(['lucky_flag' => 1]);
                    DB::table('lucky')->insert([
                        'code' => $code,
                        'name' => $voter->name,
                        'phone' => $voter->phone_no,
                        'election_id' => $election_id,
                        'voter_id' => $voter_id,
                    ]);
                    return response()->json(['success' => 'Status Change successfully.', 'code' => $code]);
                } else if ($voter->done == 1 && $voter->lucky_flag == 1) {
                    $exist_code = DB::table('lucky')->where('election_id', $election_id)->where('voter_id', $voter_id)->first()->code;
                    if ($exist_code) {
                        return response()->json(['success' => 'Status Change successfully.', 'code' => $exist_code]);
                    } else {
                        return response()->json(['errors' => 'Data not found!']);
                    }
                } else {
                    return response()->json(['errors' => 'Voter Not Done or rejected']);
                }
            }
        } else {
            return response()->json(['errors' => 'Voter not Found']);
        }
    }
}
