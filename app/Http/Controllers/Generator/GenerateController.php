<?php

namespace App\Http\Controllers\Generator;

use App\Classes\BulkEmail;
use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Classes\BulkSMS;
use App\ElectionVoter;
use App\Exports\ExportVoterList;
use App\Imports\VoterImport;
use App\Setting;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

use function PHPSTORM_META\map;

class GenerateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);        
    }

    public function index()
    {
        $elections = Election::all();

        $company = Company::all();
        return view('generator.index', compact('elections', 'company'));
    }

    public function vidList()
    {
        if (request()->ajax()) {
            $DT_data = Voter::latest()->join('logs', 'logs.voter_id', 'voter.id')->get(['voter.*', 'logs.sms_flag', 'logs.email_flag', 'logs.reminder_sms_flag', 'logs.reminder_email_flag']);
            // dd($DT_data);
            $check = ElectionVoter::select('election_voters.done', 'voter.id', 'election.name as election_name', 'election.position as election_position')
                ->join('voter', 'voter.id', '=', 'election_voters.voter_id')
                ->join('election', 'election_voters.election_id', '=', 'election.id')
                ->get();
            // dd($check);
            $array = [];
            foreach ($DT_data as $item) {
                foreach ($check as $v) {
                    if ($v->id == $item->id) {
                        array_push($array, $v);
                        $item->election_voter = $array;
                    }
                }
                $array = [];
            }
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) {
                    $button = '<button type="button" data-id="' . $DT_data->id . '" data-voter_id="' . $DT_data->voter_id . '" class="btn" id="btn_print"><i class="fa fa-print"></i> Print</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('generator.generatedVid-list');
    }

    public function store(Request $request)
    {        
        $voter = new Voter;
        $voter->voter_id = $request->vid;
        $voter->vote_count = $request->vote_count;
        $voter->name = $request->name;
        $voter->email = $request->email;
        $voter->phone_no = $request->ph_no;
        $voter->save();

        $voter_data = Voter::where("voter_id", $request->vid)->first();
        $elections = Election::all();
        if (count($elections) > 0) {
            foreach ($elections as $election) {
                $election_voter = new ElectionVoter();
                $election_voter->election_id = $election->id;
                $election_voter->voter_id = $voter_data->id;
                $election_voter->save();
            }
        }

        DB::table('logs')->insert([
            'voter_id' => $voter->id,
        ]);

        return response()->json(['success' => 'Voter ID Generated Successfully.', 'vid' => $request->vid]);        
    }

    public function generateVidBlade()
    {
        return view('generator.vidgenerate');
    }

    public function excelGenerate(Request $request)
    {
        if ($request->ajax()) {
            

            if ($request->hasFile('file')) {
                $validator = Validator::make(
                    [                   
                        'extension' => strtolower($request->file->getClientOriginalExtension()),
                    ],
                    [                    
                        'extension'      => 'required|in:csv,xlsx,xls',
                    ]
                );
                
                if ($validator->fails()) {                
                    return response()->json(['errors' => $validator->errors()->all()]);
                }
                
                try {
                    $import = new VoterImport();
                    $import->import($request->file('file'));
                } catch (ValidationException $e) {
                    
                     $failures = $e->failures();
                     return response()->json($failures[0]->errors());
                }
                
                return response()->json(['success' => 'Data Added Successfully']);
            } else {
                return response()->json(['errors' => 'Excel File is Required!']);
            }
        } else {
            return abort(404);
        }
    }

    public function export()
    {
        return Excel::download(new ExportVoterList(), 'Voter.xlsx');
    }

    public function excelDownload()
    {
        $file_path = public_path() . '/upload/generate_template.xlsx';
        // dd($file_path);
        if (file_exists($file_path)) {
            return response()->download($file_path);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function sendMessage(Request $request)
    {
        $vid = $request->vid;        
        $voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();
        
        $email = $voter->email;
        $phone  = $voter->phone_no;

        if ($phone) {
            $phones = explode(',', $phone);
            
            $result = BulkSMS::sendSMS($phones, $voter);
            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        }        

        if ($email) {
            $emails = explode(',', $email);

            $result = BulkEmail::sendEmail($emails,$voter);

            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        }

        return response()->json(['success' => 'Message Send Successfully.']);
    }

    public function smsMessageOnly(Request $request)
    {        
        $vid = $request->vid;
        $voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();

        $phone  = $voter->phone_no;
        if ($phone) {            
            $phones = explode(',', $phone);
                            
            $result = BulkSMS::sendSMS($phones, $voter);

            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 2]);
                return response()->json(['success' => 'SMS Send Successfully.']);
            } else {                
                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        } else {
            return response()->json(['errors' => 'Phone Number Does not Exist!']);
        }
    }

    public function emailMessageOnly(Request $request)
    {
        $vid = $request->vid;        
        $voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();
        
        $email = $voter->email;

        if ($email) {
            $emails = explode(',', $email);

            $result = BulkEmail::sendEmail($emails,$voter);

            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        } else {
            return response()->json(['errors' => 'Email Does not Exist!.']);
        }
    }

    public function reminder(Request $request)
    {
        $vid = $request->vid;
        $non_vote_voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();        
        
        if ($non_vote_voter->phone_no) {

            $phones = explode(',', $non_vote_voter->phone_no);            

            $result = BulkSMS::sendSMS($phones, $non_vote_voter,'reminder');
            
            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        }

        if ($non_vote_voter->email) {

            $emails = explode(',', $non_vote_voter->email);

            $result = BulkEmail::sendEmail($emails,$non_vote_voter,'reminder');

            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['email_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        }

        return response()->json(['success' => 'Message Send Successfully.']);
    }

    public function emailReminderOnly(Request $request)
    {
        $vid = $request->vid;
        $non_vote_voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();
        
        if ($non_vote_voter->email) {
            $emails = explode(',', $non_vote_voter->email);

            $result = BulkEmail::sendEmail($emails,$non_vote_voter,'reminder');

            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['email_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }

            return response()->json(['success' => 'Email Send Successfully.']);
        } else {
            return response()->json(['errors' => 'Email Does not Exist!']);
        }
    }

    public function smsReminderOnly(Request $request)
    {
        $vid = $request->vid;
        $non_vote_voter = DB::table('voter')
            ->select('voter.*', 'election_voters.election_id as election_id')
            ->where('voter.voter_id', $vid)
            ->orWhere('voter.id', $vid)
            ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
            ->first();
        
        if ($non_vote_voter->phone_no) {
            $phones = explode(',', $non_vote_voter->phone_no);            

            $result = BulkSMS::sendSMS($phones, $non_vote_voter);
            
            if (isset($result->getData()->success)) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 1]);
                return response()->json(['errors' => $result->getData()->errors]);
            }
        } else {
            return response()->json(['errors' => 'Phone Number Does not Exist!']);
        }
    }
}
