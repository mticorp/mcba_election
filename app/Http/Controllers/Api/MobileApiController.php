<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Candidate;
use App\Classes\BulkSMS;
use App\Election;
use App\ElectionVoter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\MRegister;
use App\Question;
use App\Result;
use App\Setting;
use App\Voter;
use App\Voting;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileApiController extends Controller
{
    public function userlogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $token = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;

            return response()->json(['success' => 'Successfully Login', 'token' => $token]);
        } else {

            return response()->json(['Unauthorised' => 'Unauthorised'], 404);
        }

    }
    public function userlogout(Request $request)
    {
        $token = $request->user()->token()->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function Login(Request $request)
    {
        $voter_id = $request->voter_id;
        $voter = Voter::where('voter_id', $voter_id)->first();
        if ($voter) {
            return response()->json(['success' => 'Successfully Login', 'data' => $voter]);
        } else {
            return response()->json(["error" => "Invalid VoterID"], 404);
        }
    }

    public function sendOTP(Request $request)
    {

        $setting = Setting::first();

        $voter_id = $request->voter_id;
        $voter = Voter::where('voter_id', $voter_id)->first();
        if ($voter) {
            if ($voter->phone_no == null && $voter->phone_no == "") {
                return response()->json(['error' => 'Invalid Mobile Number!'], 400);
            } else {
                $otp = rand(100000, 999999);
                $message = "$otp is your OTP, Welcome to MTI's eVoting System ";
                $bulksms = new BulkSMS();
                $msgResponse = $bulksms->sendSMS($voter->phone_no, $message, '', '', $setting);

                if ($msgResponse->getData()->success) {
                    return response()->json(['success' => 'Successfully Send!', 'data' => ['otp' => $otp, 'voter' => $voter]], 200);
                } else {
                    return response()->json(['error' => 'Process Failed'], 401);
                }
            }
        } else {
            return response()->json(['error' => 'Invalid Voter ID'], 400);
        }
    }

    public function verify(Request $request)
    {
        $voter = Voter::where('voter_id', $request->voter_id)->first();

        if ($voter) {
            $voter->isVerified = 1;
            if ($voter->save()) {
                return response()->json(['success' => 'Successfully Verified!'], 200);
            } else {
                return response()->json(['error' => "Process Failed!"], 400);
            }
        } else {
            return response()->json(['error' => "Voter Not Found!"], 400);
        }
    }

    public function electionlist($voter_id)
    {
        $voter = DB::table('voter')->where('voter_id', $voter_id)->first();

        if ($voter) {
            $elections = DB::table('election')
                ->select('election.*', 'company.company_logo', 'company.company_name')
                ->leftJoin('company', 'company.id', '=', 'election.company_id')
                ->get();
            if (count($elections) > 0) {
                foreach ($elections as $election) {
                    $election->voter_count = $voter->vote_count;
                    $ques_count = Question::where('election_id', $election->id)->count();
                    $election_voter = ElectionVoter::where('election_id', $election->id)->where('voter_id', $voter->id)->first();

                    if ($election_voter) {
                        if ($election_voter->done == 0 && $election->candidate_flag == 1 && $election->ques_flag == 1) {
                            $voter_voting_record = Voting::where('election_id', $election->id)->where('voter_id', $voter->id)->count();

                            if ($ques_count > 0) {
                                $election->ques_flag = 1;
                            } else {
                                $election->ques_flag = 0;
                            }

                            if ($voter_voting_record == 0) {
                                //not voted for candidate & ques
                                $election->isVotedCandidate = false;
                                $election->isVotedQues = false;
                                $election->isVoted = 0;
                            } else {
                                //voted for candidate & not voted for ques
                                $election->isVotedCandidate = true;
                                $election->isVotedQues = false;
                                $election->isVoted = 0;
                            }
                        } elseif ($election_voter->done == 0 && $election->candidate_flag == 1 && $election->ques_flag == 0) {
                            $election->isVotedCandidate = false;
                            $election->isVoted = 0;
                        } elseif ($election_voter->done == 0 && $election->candidate_flag == 0 && $election->ques_flag == 1) {
                            if ($ques_count > 0) {
                                $election->ques_flag = 1;
                            } else {
                                $election->ques_flag = 0;
                            }

                            $election->isVotedQues = false;
                            $election->isVoted = 0;
                        } else {
                            $election->isVoted = 1;
                            $election->isVotedCandidate = true;
                            $election->isVotedQues = true;
                        }
                    } else {
                        return response()->json(['error' => "Voter Not Found!"], 403);
                    }
                }
                return response()->json(['success' => 'Get Data Successfully!', 'data' => $elections], 200);
            } else {
                return response()->json(['message' => "Election Not Found!", 'data' => []], 200);
            }
        } else {
            return response()->json(['error' => "Voter Not Found!"], 403);
        }
    }

    public function candidatelist($election_id)
    {
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            if ($election->status == 1) {
                if ($election->candidate_flag == 1) {
                    $candidatelist = Candidate::where('election_id', $election_id)->get();
                    if ($candidatelist) {
                        return response()->json(['message' => 'Get Candidate Data Successfully!', 'data' => $candidatelist], 200);
                    }
                } else {
                    return response()->json(['message' => "Candidate Feature isn't Include in this Election!", 'candidate_flag' => 0], 403);
                }
            } else {
                return response()->json(['error' => "Election isn't Start Yet!"], 403);
            }
        } else {
            return response()->json(['error' => "Request Election Not Found on Server!"], 403);
        }
    }

    public function VoteStore(Request $request)
    {
        // dd($request->all());
        $candidates = $request->candidates;
        $voter_id = $request->voter_id;
        $voter_vote_count = $request->voter_vote_count;
        $election_id = $request->election_id;
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $voter = DB::table('voter')->where('voter_id', $voter_id)->first();
            if ($voter) {
                $electionVoter_modal = new ElectionVoter;

                $exist = $electionVoter_modal->checkAllDone($voter_id, $election_id);
                if ($exist > 0) {
                    return response()->json(["error" => "Already Voted"], 403);
                } else {
                    $electionVoter = $electionVoter_modal->voterInfo($voter->id, $election_id);

                    if ($electionVoter->done == 0) {
                        $electionVoter_modal->statusUpdate($voter->id, $election_id, 3);
                        foreach ($candidates as $candidate) {
                            Candidate::where('id', '=', $candidate)->increment('vote_count', $voter_vote_count);
                            Result::where([
                                ['candidate_id', '=', $candidate],
                                ['election_id', '=', $election_id],
                            ])->increment('vote_count', $voter_vote_count);
                            $voting = new Voting;
                            $voting->candidate_id = $candidate;
                            $voting->voter_id = $voter->id;
                            $voting->election_id = $election_id;
                            $voting->vote_count = $voter_vote_count;
                            $voting->save();
                        }
                    } else {
                        return response()->json(['message' => 'Already Voted'], 403);
                    }

                    if ($election->candidate_flag == 1 && $election->ques_flag == 1) {
                        $electionVoter->done = 0;
                        $electionVoter->save();
                        return response()->json(["success" => "Successfully Voted", "data" => ['voter' => $voter, 'election_id' => $election_id]], 200);
                    } elseif ($election->candidate_flag == 1 && $election->ques_flag == 0) {
                        $electionVoter->done = 1;
                        $electionVoter->save();
                        return response()->json(["success" => "Successfully Voted", "data" => ['voter' => $voter, 'election_id' => $election_id]], 200);
                    }
                }
            } else {
                return response()->json([
                    "error" => "Voter  Not Found",
                ], 404);
            }
        } else {
            return response()->json([
                "error" => "Election Not Found",
            ], 404);
        }
    }

    public function questionlist($election_id, $voter_id)
    {
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            if ($election->ques_flag == 1) {
                $already_ans = DB::table('answers')->where('voter_id', $voter_id)->where('election_id', $election_id)->join('questions', 'questions.id', '=', 'answers.ques_id')->count() != 0 ? true : false;
                if ($already_ans) {
                    return response()->json(["error" => "You are Already Answered!"], 403);
                } else {
                    $ques = Question::where('election_id', $election_id)->get();
                    return response()->json([
                        "data" => $ques,
                        "message" => "success",
                    ], 200);
                }
            } else {
                return response()->json(["message" => "Question Feature isn't include in this Election!", 'question_flag' => 0], 403);
            }
        } else {
            return response()->json(["error" => "Election Not Found"], 404);
        }
    }

    public function storeanswerandquestion(Request $request)
    {
        $election_ID = $request->election_id;
        $electionWithId = Election::where('id', $election_ID)->first();
        $voter = Voter::where('voter_id', $request->voter_id)->first();

        $election_voter = ElectionVoter::where('election_id', $election_ID)->where('voter_id', $voter->id)->first();

        if (count($request->answers) > 0) {
            foreach ($request->answers as $answer) {
                $ans = new Answer();
                $ans->voter_id = $voter->id;
                $ans->ques_id = $answer["ques_id"];
                $ans->ans_flag = $answer["ans"];
                $ans->save();
            }
        }
        $election_voter->done = 1;
        $election_voter->save();
        return response()->json([
            'success' => 'Successfully Answered!',
            'voter' => $voter,
        ], 200);
    }

    public function VoterCandidateList($election_id, $voter_id)
    {
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        $voter = Voter::where('voter_id', $voter_id)->first();

        if ($election) {
            $electionvoter = ElectionVoter::select('voter.*', 'election_voters.done as done')
                ->join('voter', 'voter.id', 'election_voters.voter_id')
                ->where('election_voters.voter_id', $voter->id)
                ->where('election_voters.election_id', $election_id)
                ->first();

            if ($electionvoter) {
                $candidates = DB::table('candidate')->select('candidate.mname as candidate_name')
                    ->join('voting', 'candidate.id', '=', 'voting.candidate_id')
                    ->join('voter', 'voter.id', '=', 'voting.voter_id')
                    ->where('voter.id', '=', $voter->id)
                    ->where('voting.election_id', $election_id)
                    ->get();

                if (count($candidates) > 0) {
                    return response()->json(['success' => 'success', 'data' => ['candidateList' => $candidates, 'voter' => $voter]], 200);
                } else {
                    return response()->json(['error' => 'No Data Found!'], 404);
                }
            }
        }
    }

    public function GenerateluckyCode($election_id, $voter_id)
    {
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);

        if ($election) {
            if ($election->lucky_flag == 1) {
                $voterintid = Voter::where('voter_id', $voter_id)->first()->id;
                $voter = ElectionVoter::select('voter.*', 'election_voters.done as done')
                    ->join('voter', 'voter.id', 'election_voters.voter_id')
                    ->where('voter.id', $voterintid)
                    ->where('election_voters.election_id', $election_id)
                    ->first();

                if ($voter) {
                    $code = mt_rand(100000, 999999);
                    $exist = DB::table('lucky')->where('code', $code)->where('election_id', $election_id)->first();
                    if ($exist) {
                        do {
                            $new_code = mt_rand(100000, 999999);
                        } while ($new_code == $exist->code);

                        if ($voter->done == 1 && $voter->lucky_flag == 0) {
                            ElectionVoter::where('voter_id', $$voterintid)->where('election_id', $election_id)->update(['lucky_flag' => 1]);
                            DB::table('lucky')->insert([
                                'code' => $new_code,
                                'name' => $voter->name,
                                'phone' => $voter->phone_no,
                                'election_id' => $election_id,
                                'voter_id' => $voter->id,
                            ]);

                            return response()->json(['success' => 'Successfully Generated!', 'data' => $new_code], 200);
                        } elseif ($voter->done == 1 && $voter->lucky_flag == 1) {
                            $old_code = DB::table('lucky')->where('election_id', $election_id)->where('voter_id', $voter_id)->first();
                            if ($old_code) {
                                return response()->json(['success' => 'Successfully Generated!', 'data' => $old_code->code], 200);
                            } else {
                                return response()->json(['error' => 'Data not found!'], 404);
                            }
                        } else {
                            return response()->json(['error' => 'Voter Not Done'], 404);
                        }
                    } else {
                        if ($voter->done == 1 && $voter->lucky_flag == 0) {
                            ElectionVoter::where('voter_id', $voterintid)->where('election_id', $election_id)->update(['lucky_flag' => 1]);
                            DB::table('lucky')->insert([
                                'code' => $code,
                                'name' => $voter->name,
                                'phone' => $voter->phone_no,
                                'election_id' => $election_id,
                                'voter_id' => $voterintid,
                            ]);
                            return response()->json([
                                'success' => 'success',
                                'data' => $code,
                            ], 200);
                        } elseif ($voter->done == 1 && $voter->lucky_flag == 1) {
                            $exist_code = DB::table('lucky')->where('election_id', $election_id)->where('voter_id', $voter_id)->first()->code;

                            if ($exist_code) {
                                return response()->json(['success' => 'Successfully Generated!', 'data' => $exist_code], 200);
                            } else {
                                return response()->json(['error' => 'Data not found!'], 404);
                            }
                        } else {
                            return response()->json(['error' => 'Process Failed!'], 403);
                        }
                    }
                } else {
                    return response()->json(['error' => 'Voter not found!'], 404);
                }
            } else {
                return response()->json(['error' => 'Election Not Found!'], 404);
            }
        }
    }

    public function votingresult($election_id)
    {
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            $ans_modal = new Answer;

            $ans = $ans_modal->AnswerSummaryWithQuestionName($election_id);

            $data = Result::orderBy('result.vote_count', 'desc')->orderBy('candidate.candidate_no', 'asc')
                ->where('candidate.election_id', $election_id)
                ->leftJoin('candidate', 'candidate.id', '=', 'result.candidate_id')
                ->get(['candidate.mname as candidate_name', 'candidate.photo_url as candidate_photo_url', 'result.vote_count', 'candidate.candidate_no as candidate_no']);

            return response()->json(['message' => 'success', 'data' => ["votingResult" => $data,
                "questionResult" => $ans]], 200);
        } else {
            return response()->json(['error' => 'Election Not Found!'], 400);
        }
    }

    public function membercheckform(Request $request)
    {
        $refer_code = $request->refer_code;
        $name = $request->name;
        $agent_name = $request->agent_name;
        $nrc_no = $request->nrc_no;
        $phone_no = $request->phone_no;
        $member = MRegister::where('refer_code', $refer_code)->first();
        if ($member) {
            if ($member->name == $name) {
                if ($member->nrc == $nrc_no) {
                    if ($member->phone_number == $phone_no) {
                        if ($member->check_flag == 0) {
                            return response()->json([
                                'success' => 'success',
                                'data' => $member,
                            ], 200);
                        } else {
                            return response()->json([
                                "error" => "Your are already Registered.",
                            ], 403);
                        }
                    } else {
                        return response()->json([
                            "error" => "Invalid Phone Number",
                        ], 400);
                    }
                } else {
                    return response()->json([
                        "error" => "Invalid NRC",
                    ], 400);
                }
            } else {
                return response()->json([
                    "error" => "Invalid Name",
                ], 400);
            }
        } else {
            return response()->json([
                "error" => "Invalid Reference Code",
            ], 400);
        }
    }

    public function confirmembercheckform(Request $request)
    {
        $image_name = $request->old_image;
        $image = $request->file('image');
        $form_data = array(
            "refer_code" => $request->refer_code,
            "name" => $request->name,
            "email" => $request->email,
            "nrc" => $request->nrc_no,
            "complete_training_no" => $request->complete_training_no,
            "valuation_training_no" => $request->valuation_training_no,
            "AHTN_training_no" => $request->AHTN_training_no,
            "graduation" => $request->graduation,
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "profile" => $image_name,
            "check_flag" => 1,
            "officeName" => $request->officeName,
            "office_startDate" => $request->office_startDate,
            "officeAddress" => $request->officeAddress,
            "officePhone" => $request->officePhone,
            "officeFax" => $request->officeFax,
            "officeEmail" => $request->officeEmail,
            "yellowCard" => $request->yellowCard,
            "pinkCard" => $request->pinkCard,
        );

        MRegister::whereId($request->hidden_id)->update($form_data);
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = $characters[rand(0, strlen($characters) - 1)] . mt_rand(10, 99) . $characters[rand(0, strlen($characters) - 1)];
        $pin_code = str_shuffle($pin);
        $voter = new Voter;
        $voter->voter_id = $pin_code;
        $voter->vote_count = 1;
        $voter->name = $request->name;
        $voter->email = $request->email;
        $voter->phone_no = $request->phone_number;
        $voter->save();
        $voter_data = Voter::where("voter_id", $pin_code)->first();
        $elections = Election::all();
        if (count($elections) > 0) {
            foreach ($elections as $election) {
                $election_voter = new ElectionVoter();
                $election_voter->election_id = $election->id;
                $election_voter->voter_id = $voter_data->id;
                $election_voter->save();
            }
        } else {
            return response()->json([
                'error' => 'Data Not Found',
            ], 404);
        }
        return response()->json([
            'success' => 'success',
        ], 200);
    }
}
