<?php

namespace App\Http\Controllers\Voter;

use App\Classes\BulkSMS;
use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Voter;
use CyberWings\MyanmarPhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // Online Voter
    public function Link($voter_id)
    {
        $data = DB::table('voter')->where('voter_id', $voter_id)->first();
        if ($data == null) {
            return view('error.invalid');
        } else {
            Session::forget('voter_table_id');
            Session::put('voter_table_id', $data->id);
            $phones = explode(',',$data->phone_no);
            return view('voter.get-otp',compact('phones'));
        }
    }

    public function sendOtp(Request $request)
    {
        $voter_table_id = $request->session()->get('voter_table_id');
        $phone_number = new MyanmarPhoneNumber();
        $voter = Voter::find($voter_table_id);

        if($request->phones)
        {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:7',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('status', $validator->errors()->first());
            }
            
            $generated_phone = $phone_number->add_prefix($request->phone);            
        }else{            
            $generated_phone = $phone_number->add_prefix($voter->phone_no);
        }        

        if (starts_with($generated_phone, '959')) {
            $generated_phone = substr($generated_phone, 3);
        } else if (starts_with($generated_phone, '09')) {
            $generated_phone = substr($generated_phone, 2);
        }

        $voter = Voter::where('id', $voter_table_id)
            ->where('phone_no', 'like', "%" . $generated_phone . "%")
            ->first();
        
        if (!$voter) {
            return redirect()->back()->with('status', 'Voter not found')->withInput();
        }

        $otp = rand(100000, 999999);
        $message = "$otp is your OTP, Welcome to MTI's eVoting System";
        $bulksms = new BulkSMS();            
        $phones = ['09' . $generated_phone];        
        $msgResponse = $bulksms->sendSMS($phones, $message);
        if (isset($msgResponse->getData()->success)) {
            Session::forget('OTP');
            Session::put('OTP', $otp);

            return redirect()->route('voter.verifyView');
        } else {
            return redirect()->back()->with('status', 'Failed to send OTP. Please try again!')->withInput();
        }
    }

    public function verifyView()
    {
        return view('voter.verifyOTP');
    }

    public function verifyOtp(Request $request)
    {
        $voter_table_id = $request->session()->get('voter_table_id');
        $enteredOtp = $request->otp;

        if ($enteredOtp == "") {
            return redirect()->back()->with('status', 'OTP code is required!');
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

        $voter_table_id = request()->session()->get('voter_table_id');
        $voter = Voter::find($voter_table_id);

        if ($voter) {
            $all_elections = Election::all();
            return view('voter.select-Election', compact('all_elections'));
        } else {
            Session::forget('voter_table_id');
            abort(403, 'Unauthorized Request!');
        }
    }

    public function index()
    {

        $all_elections = Election::all();

        return view('voter.index', compact('all_elections'));
    }

    public function VoteIndex($election_id)
    {

        $election_modal = new Election();
        $election = $election_modal->electionWithId($election_id);
        if ($election) {
            if ($election->status == 1) {
                return view('voter.manual-vote', compact('election'));
            } else {
                return view('error.election_not_start')->with('msg', 'Election is not Started Yet!');
            }
        } else {
            abort(404);
        }
    }

    public function VoteLogin(Request $request)
    {


        $voter_id = $request->enter_voter_id;
        $voter = Voter::where('voter_id', $voter_id)->first();
        if ($voter) {
            Session::put('voter_table_id', $voter->id);
            return redirect()->route('vote.candidatelist', ['election_id' => $request->election_id]);
        } else {

            return view('error.invalid');
        }
    }
}
