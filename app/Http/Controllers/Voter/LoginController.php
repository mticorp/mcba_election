<?php

namespace App\Http\Controllers\Voter;

use App\Classes\BulkSMS;
use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Voter;
use Carbon\Carbon;
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

            $setting = Setting::first();
            if($setting && $setting->otp_enable == 0)
            {
                return redirect()->route('voter.select.election');
            }else{
                $phones = explode(',',$data->phone_no);
                return view('voter.get-otp',compact('phones'));
            }            
        }
    }

    public function sendOtp(Request $request)
    {        
        $voter_table_id = $request->session()->get('voter_table_id');
        $voter = Voter::find($voter_table_id);

        if ($voter->phone_no == "") {
            return redirect()->back()->with('status', 'Phone Number is not exist in this voter!');
        } else {            
            
            $phones = explode(',',$voter->phone_no);
            $otp = rand(100000, 999999);            

            $setting = Setting::first();

            if($setting->otp_valid_time_type == 's')
            {
                $expire_time = Carbon::now()->addSeconds($setting->otp_valid_time);
            }elseif($setting->otp_valid_time_type == 'm')
            {
                $expire_time = Carbon::now()->addMinutes($setting->otp_valid_time);
            }else{
                $expire_time = Carbon::now()->addHours($setting->otp_valid_time);
            }
            
            $message = "$otp is your OTP, Welcome to MTI's eVoting System ";
            $msgResponse = BulkSMS::sendSMS($phones,$voter,'otp',$message);

            if (isset($msgResponse->getData()->success)) {
                Session::forget(['OTP','OTP_expire_at']);
                session(['OTP' => $otp,'OTP_expire_at' => $expire_time]);

                return redirect()->route('voter.verifyView');
            } else {
                return redirect()->back()->with('status', 'Invalid Phone Number');
            }
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
            $expire_time = $request->session()->get('OTP_expire_at');
            if(Carbon::now() > $expire_time)
            {
                Session::forget('OTP');
                Session::forget('OTP_expire_at');
                return redirect()->back()->with('status', 'OTP code is expired!');
            }
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
            $all_elections = Election::where('status','1')->get();
            return view('voter.select-Election', compact('all_elections'));
        } else {
            Session::forget('voter_table_id');
            abort(403, 'Unauthorized Request!');
        }
    }

    public function index()
    {

        $all_elections = Election::where('status','1')->get();
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
                return view('error.election_not_start')->with('msg', 'မဲပေးပွဲ မစသေးပါ။');
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
