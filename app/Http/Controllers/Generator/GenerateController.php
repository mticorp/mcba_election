<?php

namespace App\Http\Controllers\Generator;

use App\Classes\BulkEmail;
use App\Classes\BulkSMS;
use App\Company;
use App\Election;
use App\ElectionVoter;
use App\Exports\ExportVoterList;
use App\Http\Controllers\Controller;
use App\Imports\VoterImport;
use App\Setting;
use App\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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
            $electionId = $_GET['election_id'];
            $done = $_GET['done'];

            $election = Election::find($electionId);
            if (!$election) {
                // return response()->json(['error' => 'Election Not Found'],404);
            }
            if (($done == 0 || $done == 1) && $done != '') {
                $DT_data = ElectionVoter::with(['voter', 'log'])->where('election_id', $electionId)->where('done', $done)->get();
            } else {
                $DT_data = ElectionVoter::with(['voter', 'log'])->where('election_id', $electionId)->get();
            }
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) use ($election) {
                    $button = '<button type="button" data-id="' . $DT_data->id . '" data-voter_id="' . $DT_data->voter->voter_id . '" class="btn" id="btn_print" data-name="' . $election->name . '"><i class="fa fa-print"></i> Print</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $company = DB::table('company')->latest('created_at')->first();

        $electionLidt = Election::all();
        return view('generator.generatedVid-list', compact('company', 'electionLidt'));
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

        $company = DB::table('company')->latest('created_at')->first();
        //$election =  DB::table('election')->latest('created_at')->first();
        $election = Election::where('status', '1')->first();
        return view('generator.vidgenerate', compact('company', 'election'));
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
                        'extension' => 'required|in:csv,xlsx,xls',
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

        $setting = Setting::first();

        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_reminder' || $request->type == 'all_reminder') {
                $type = 'reminder';
            } else if ($request->type == 'select_annouce' || $request->type == 'all_annouce') {
                $type = 'voter_announce';
            } else {
                $type = null;
            }
            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $voter_id) {
                    $voter = DB::table('voter')
                        ->select('voter.*', 'election_voters.election_id as election_id')
                        ->where('voter.voter_id', $voter_id)
                        ->orWhere('voter.id', $voter_id)
                        ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                        ->first();

                    $email = $voter->email;
                    $phone = $voter->phone_no;

                    if ($phone) {
                        $phones = explode(',', $phone);
                        $result = BulkSMS::sendSMS($phones, $voter, $type, '', $setting);
                        if (isset($result->getData()->success)) {
                            if ($type == 'reminder') {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['reminder_sms_flag' => 2]);
                            } else {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 2]);
                            }
                        } else {
                            array_push($errors, [
                                $voter->name . ' SMS Send Fail',
                            ]);
                            if ($type == 'reminder') {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['reminder_sms_flag' => 1]);
                            } else {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 1]);
                            }
                        }
                    } else {
                        array_push($errors, [
                            $voter->name . ' Phone is Empty',
                        ]);
                    }

                    if ($email) {
                        $emails = explode(',', $email);

                        $result = BulkEmail::sendEmail($emails, $voter, $type);

                        if (isset($result->getData()->success)) {
                            DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 2]);
                        } else {
                            array_push($errors, [
                                $voter->name . ' Mail Send Fail',
                            ]);
                            DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 1]);
                        }
                    } else {
                        array_push($errors, [
                            $voter->name . ' Mail is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'Mail and SMS Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function smsMessageOnly(Request $request)
    {

        $setting = Setting::first();

        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_reminder' || $request->type == 'all_reminder') {
                $type = 'reminder';
            } else if ($request->type == 'select_annouce' || $request->type == 'all_annouce') {
                $type = 'voter_announce';
            } else {
                $type = null;
            }

            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $voter_id) {
                    $voter = DB::table('voter')
                        ->select('voter.*', 'election_voters.election_id as election_id')
                        ->where('voter.voter_id', $voter_id)
                        ->orWhere('voter.id', $voter_id)
                        ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                        ->first();

                    $phone = $voter->phone_no;

                    if ($phone) {
                        $phones = explode(',', $phone);
                        $result = BulkSMS::sendSMS($phones, $voter, $type, '', $setting);
                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $voter->name . ' SMS Send Fail',
                            ]);
                            if ($type == 'reminder') {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['reminder_sms_flag' => 1]);
                            } else {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 1]);
                            }
                        } else {
                            if ($type == 'reminder') {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['reminder_sms_flag' => 2]);
                            } else {
                                DB::table('logs')->where('voter_id', $voter->id)->update(['sms_flag' => 2]);
                            }
                        }
                    } else {
                        array_push($errors, [
                            $voter->name . ' Phone is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'SMS Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function emailMessageOnly(Request $request)
    {
        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_reminder' || $request->type == 'all_reminder') {
                $type = 'reminder';
            } else if ($request->type == 'select_annouce' || $request->type == 'all_annouce') {
                $type = 'voter_announce';
            } else {
                $type = null;
            }

            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $voter_id) {
                    $voter = DB::table('voter')
                        ->select('voter.*', 'election_voters.election_id as election_id')
                        ->where('voter.voter_id', $voter_id)
                        ->orWhere('voter.id', $voter_id)
                        ->join('election_voters', 'election_voters.voter_id', '=', 'voter.id')
                        ->first();

                    $email = $voter->email;

                    if ($email) {
                        $emails = explode(',', $email);

                        $result = BulkEmail::sendEmail($emails, $voter, $type);

                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $voter->name . ' Mail Send Fail',
                            ]);
                            DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 1]);
                        } else {
                            DB::table('logs')->where('voter_id', $voter->id)->update(['email_flag' => 2]);
                        }
                    } else {
                        array_push($errors, [
                            $voter->name . ' Mail is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'Mail Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }
}
