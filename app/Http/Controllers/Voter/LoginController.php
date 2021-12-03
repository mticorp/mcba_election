<?php

namespace App\Http\Controllers\Voter;

use App\Classes\BulkSMS;
use App\Election;
use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;
use App\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Online Voter
    public function Link($voter_id)
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $data = DB::table('voter')->where('voter_id', $voter_id)->first();
        if ($data == null) {
            return view('error.invalid',compact('logo','favicon'));
        } else {
            Session::forget('voter_table_id');
            Session::put('voter_table_id', $data->id);
            return view('voter.get-otp',compact('logo','favicon'));
        }
    }

    public function sendOtp(Request $request)
    {
        $voter_table_id = $request->session()->get('voter_table_id');
        $voter = Voter::find($voter_table_id);

        if ($voter->phone_no == "") {
            return redirect()->back()->with('status', 'Phone Number is not exist in this voter!');
        } else {
            $otp = rand(100000, 999999);
            
            $message = "$otp is your OTP, Welcome to MTI's eVoting System ";
            $bulksms = new BulkSMS();
            $msgResponse = $bulksms->sendSMS($voter->phone_no, $message);

            if (isset($msgResponse->getData()->success)) {
                Session::forget('OTP');
                Session::put('OTP', $otp);

                return redirect()->route('voter.verifyView');
            } else {
                return redirect()->back()->with('status', 'Invalid Phone Number');
            }
        }
    }

    public function verifyView()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('voter.verifyOTP',compact('logo','favicon'));
    }

    public function verifyOtp(Request $request)
    {
        $voter_table_id = $request->session()->get('voter_table_id');
        $enteredOtp = $request->otp;

        if ($enteredOtp == "") {
            return redirect()->back()->with('status','OTP code is required!');
        } else {
            $OTP = $request->session()->get('OTP');
            if ($OTP === (int) $enteredOtp) {
                // Updating user's status "isVerified" as 1.
                $voter = Voter::find($voter_table_id);
                $voter->isVerified = 1;
                $voter->save();
                //Removing Session variable
                Session::forget('OTP');

                return redirect()->route('voter.select.election');
            } else {
               return redirect()->back()->with('status', 'OTP does not match.');
            }
        }
    }

    public function selectElection()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $voter_table_id = request()->session()->get('voter_table_id');
        $voter = Voter::find($voter_table_id);        

        if($voter)
        {
            $all_elections = Election::all();
            return view('voter.select-Election', compact('all_elections','logo','favicon'));
        }else{
            Session::forget('voter_table_id');
            abort(403,'Unauthorized Request!');
        }
    }

    public function index()
    {
        
        $all_elections = Election::all();
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('voter.index', compact('all_elections','logo','favicon'));
    }

    public function VoteIndex($election_id)
    {
        $logo = Logo::first();
        $favicon = Favicon::first();
        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            if ($election->status == 1) {
                return view('voter.manual-vote', compact('election','logo','favicon'));
            } else {
                return view('error.election_not_start','logo','favicon')->with('msg', 'Election is not Started Yet!');
            }
        } else {
            abort(404);
        }
    }

    public function VoteLogin(Request $request)
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $voter_id = $request->enter_voter_id;
        $voter = Voter::where('voter_id',$voter_id)->first();
        if($voter)
        {
            Session::put('voter_table_id',$voter->id);
            return redirect()->route('vote.candidatelist', ['election_id' => $request->election_id]);
        }else{
            
            return view('error.invalid',compact('logo','favicon'));
        }
    }
}
