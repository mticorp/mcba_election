<?php

namespace App\Http\Controllers\Generator;

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
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


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

        $election = Election::first();
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

        return view('generator.generatedVid-list', compact('election'));
    }

    public function store()
    {
        $vid = $_POST['vid'];
        $vote_count = $_POST['vote_count'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $ph_no = $_POST['ph_no'];

        $check_voter = Voter::where("voter_id", $vid)->first();
        $check_email = Voter::where("email", $email)->first();
        $check_phone = Voter::where("phone_no", $ph_no)->first();
        if ($check_email) {
            if ($check_email->email == "") {
                if (!$check_voter) {
                    $voter = new Voter;
                    $voter->voter_id = $vid;
                    $voter->vote_count = $vote_count;
                    $voter->name = $name;
                    $voter->email = $email;
                    $voter->phone_no = $ph_no;
                    $voter->save();

                    $voter_data = Voter::where("voter_id", $vid)->first();
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

                    return response()->json(['success' => 'Voter ID Generated Successfully.', 'vid' => $vid]);
                } else {
                    return response()->json(['errors' => 'Voter ID is Already Exist!']);
                }
            } else {
                return response()->json(['errors' => 'Email is Already Exist!']);
            }
        } else if ($check_phone) {
            return response()->json(['errors' => 'Phone Number is Already Exist!']);
        } else {
            if (!$check_voter) {
                $voter = new Voter;
                $voter->voter_id = $vid;
                $voter->vote_count = $vote_count;
                $voter->name = $name;
                $voter->email = $email;
                $voter->phone_no = $ph_no;
                $voter->save();

                $voter_data = Voter::where("voter_id", $vid)->first();
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

                return response()->json(['success' => 'Voter ID Generated Successfully.', 'vid' => $vid]);
            } else {
                return response()->json(['errors' => 'Voter ID is Already Exist!']);
            }
        }
    }

    public function generateVidBlade()
    {
        $election_smsdescription = Election::first();
        return view('generator.vidgenerate', compact('election_smsdescription'));
    }

    public function excelGenerate(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make(
                [
                    'file'      => $request->file,
                    'extension' => strtolower($request->file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            if ($request->hasFile('file')) {
                $data = Excel::import(new VoterImport(), $request->file('file'));
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
        $election = Election::first();
        $voters = DB::table('voter')->where('voter_id', $vid)->orWhere('id', $vid)->first();
        $url = route('vote.link', ['voter_id' => $voters->voter_id]);
        $email = $voters->email;
        $phone  = $voters->phone_no;
        //$content = "မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Button ကို နှိပ်ပါ။။";
        $content = ($election->smsdescription == Null) ?
            " မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Link ကို နှိပ်ပါ။ " :
            "$election->smsdescription";
        // $content = "မင်္ဂလာပါ ".$voters->name." ! အောက်ဖော်ပြပါ Button အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။";

        if ($phone) {

            $phone_content = ($election->smsdescription == Null) ?
                " မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Link ကို နှိပ်ပါ။ "  . $url :
                "$election->smsdescription" . $url;


            //$phone_content = "မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Link ကို နှိပ်ပါ။ " . $url;
            // $phone_content = "မင်္ဂလာပါ ".$voters->name." ! အောက်ဖော်ပြပါ Link အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။ " . $url;



            // // dd($message);
            $token = "lKwrR0do7Ncd8ebzire137tt";

            // Prepare data for POST request
            $data = [
                "to"        =>      $phone,
                "message"   =>      $phone_content,
                "sender"    =>      "LOYAR"
            ];

            $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);

            $result = curl_exec($ch);
            $info = json_decode($result);
            if ($info->status == "success") {
                DB::table('logs')->where('voter_id', $voters->id)->update(['sms_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $voters->id)->update(['sms_flag' => 1]);
                return response()->json(['errors' => 'SMS Send Failed.']);
            }
        }

        if ($email) {
            $time = Carbon::now();
            $datetime = $time->toDateTimeString();
            $DT = explode(' ', $datetime);
            $image = public_path() . '/images/mti_logo.png';

            Mail::send(
                'vid_email',
                array(
                    'link' => $url,
                    'image' => $image,
                    'content' => $content,
                    'date' => $DT[0],
                    'time' => $DT[1],
                ),
                function ($message) use ($email) {
                    $message->from('helper.mti.evoting.mm@gmail.com');
                    $message->subject('E-Voting');
                    $message->to($email);
                }
            );

            if (Mail::failures()) {
                DB::table('logs')->where('voter_id', $voters->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => 'Email Send Failed.']);
            } else {
                DB::table('logs')->where('voter_id', $voters->id)->update(['email_flag' => 2]);
            }
        }

        return response()->json(['success' => 'Message Send Successfully.']);
    }

    public function smsMessageOnly(Request $request)
    {
        $vid = $request->vid;
        $election = Election::first();
        $voters = DB::table('voter')->where('voter_id', $vid)->orWhere('id', $vid)->first();
        // dd($voters);
        $url = route('vote.link', ['voter_id' => $voters->voter_id]);

        $phone  = $voters->phone_no;

        if ($phone) {
            // $phone_content = "မင်္ဂလာပါ ".$voters->name." ! အောက်ဖော်ပြပါ Link အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။ " . $url;
            //$phone_content = "မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Link ကို နှိပ်ပါ။ " . $url;

            $phone_content =
                ($election->smsdescription == Null) ?
                " မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Link ကို နှိပ်ပါ။ "  . $url :
                "$election->smsdescription" . $url;
            $token = "lKwrR0do7Ncd8ebzire137tt";

            // Prepare data for POST request
            $data = [
                "to"        =>      $phone,
                "message"   =>      $phone_content,
                "sender"    =>      "LOYAR"
            ];

            $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);

            $result = curl_exec($ch);
            $info = json_decode($result);
            if ($info->status == "success") {
                DB::table('logs')->where('voter_id', $voters->id)->update(['sms_flag' => 2]);
                return response()->json(['success' => 'SMS Send Successfully.']);
            } else {
                DB::table('logs')->where('voter_id', $voters->id)->update(['sms_flag' => 1]);
                return response()->json(['errors' => 'SMS Send Failed.']);
            }
        } else {
            return response()->json(['errors' => 'Phone Number Does not Exist!']);
        }
    }

    public function emailMessageOnly(Request $request)
    {
        $vid = $request->vid;
        $election =Election::first();
        $voters = DB::table('voter')->where('voter_id', $vid)->orWhere('id', $vid)->first();
        // dd($voters);
        $url = route('vote.link', ['voter_id' => $voters->voter_id]);
        $email = $voters->email;

        if ($email) {
            // $content = "မင်္ဂလာပါ ".$voters->name." ! အောက်ဖော်ပြပါ Button အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။";       
           // $content = "မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Button ကို နှိပ်ပါ။";
            $content =  ($election->smsdescription == Null) ?
            "မင်္ဂလာပါ (share holder) (share) MCB Bank  ဒါရိုက်တာအဖွဲ့၏ ၂၀၂၀-၂၀၂၁ ဘဏ္ဍာနှစ် အစီရင်ခံချက်နှင့် ဆွေးနွေးဆုံးဖြတ်ချက်များအပေါ် သဘောထားဆန္ဒပြုရန် အောက်ပါ Button ကို နှိပ်ပါ။" :
            "$election->smsdescription";


            $time = Carbon::now();
            $datetime = $time->toDateTimeString();
            $DT = explode(' ', $datetime);
            $image = public_path() . '/images/mti_logo.png';

            Mail::send(
                'vid_email',
                array(
                    'link' => $url,
                    'image' => $image,
                    'content' => $content,
                    'date' => $DT[0],
                    'time' => $DT[1],
                ),
                function ($message) use ($email) {
                    $message->from('helper.mti.evoting.mm@gmail.com');
                    $message->subject("E-Voting");
                    $message->to($email);
                }
            );

            if (Mail::failures()) {
                DB::table('logs')->where('voter_id', $voters->id)->update(['email_flag' => 1]);
                return response()->json(['errors' => 'Email Send Failed.']);
            } else {
                DB::table('logs')->where('voter_id', $voters->id)->update(['email_flag' => 2]);
                return response()->json(['success' => 'Email Send Successfully.']);
            }
        } else {
            return response()->json(['errors' => 'Email Does not Exist!.']);
        }
    }

    public function reminder(Request $request)
    {
        $vid = $request->vid;
        $election = Election::first();
        $non_vote_voter = Voter::where('id', $vid)->first();
        $url = route('vote.link', ['voter_id' => $non_vote_voter->voter_id]);

        $bulksms = new BulkSMS();

        //$content = "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Click here Button ကိုနှိပ်ပါ။";
        $content =  ($election->reminderdescription == Null) ?
            "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Click here Button ကိုနှိပ်ပါ။" :
            "$election->reminderdescription";

        if ($non_vote_voter->phone_no) {

            //$phone_content = "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Link ကိုနှိပ်ပါ။ " . $url;
            $phone_content = ($election->reminderdescription == Null) ?
                "*လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Link ကိုနှိပ်ပါ။ " . $url :
                "$election->reminderdescription" . $url;


            $data = $bulksms->sendSMS($non_vote_voter->phone_no, $phone_content);

            if ($data->getData()->success) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 2]);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 1]);
                return response()->json(['errors' => $data->getData()->errors]);
            }
        }

        if ($non_vote_voter->email) {
            $time = Carbon::now();
            $datetime = $time->toDateTimeString();
            $DT = explode(' ', $datetime);
            $image = public_path() . '/images/mti_logo.png';

            Mail::send(
                'vid_email',
                array(
                    'link' => $url,
                    'image' => $image,
                    'content' => $content,
                    'date' => $DT[0],
                    'time' => $DT[1],
                ),
                function ($message) use ($non_vote_voter) {
                    $message->from('helper.mti.evoting.mm@gmail.com');
                    $message->subject("E-Voting");
                    $message->to($non_vote_voter->email);
                }
            );

            if (Mail::failures()) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_email_flag' => 1]);
                return response()->json(['errors' => 'Email Send Failed.']);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_email_flag' => 2]);
            }
        }

        return response()->json(['success' => 'Message Send Successfully.']);
    }

    public function emailReminderOnly(Request $request)
    {
        $vid = $request->vid;
        $election= Election::first();
        $non_vote_voter = Voter::where('id', $vid)->first();
        $url = route('vote.link', ['voter_id' => $non_vote_voter->voter_id]);

        if ($non_vote_voter->email) {
            //$content = "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Click here Button ကိုနှိပ်ပါ။";
            $content =  ($election->reminderdescription == Null) ?
            "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Click here Button ကိုနှိပ်ပါ။" :
            "$election->reminderdescription";

            $time = Carbon::now();
            $datetime = $time->toDateTimeString();
            $DT = explode(' ', $datetime);
            $image = public_path() . '/images/mti_logo.png';

            Mail::send(
                'vid_email',
                array(
                    'link' => $url,
                    'image' => $image,
                    'content' => $content,
                    'date' => $DT[0],
                    'time' => $DT[1],
                ),
                function ($message) use ($non_vote_voter) {
                    $message->from('helper.mti.evoting.mm@gmail.com');
                    $message->subject("E-Voting");
                    $message->to($non_vote_voter->email);
                }
            );

            // check for failures
            if (Mail::failures()) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_email_flag' => 1]);
                return response()->json(['errors' => 'Email Send Failed.']);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_email_flag' => 2]);
            }

            return response()->json(['success' => 'Email Send Successfully.']);
        } else {
            return response()->json(['errors' => 'Email Does not Exist!']);
        }
    }

    public function smsReminderOnly(Request $request)
    {
        $vid = $request->vid;
        $non_vote_voter = Voter::where('id', $vid)->first();
        $election = Election::first();
        if ($non_vote_voter->phone_no) {
            $url = route('vote.link', ['voter_id' => $non_vote_voter->voter_id]);
            $bulksms = new BulkSMS();

            //$phone_content = "လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Link ကိုနှိပ်ပါ။ " . $url;
            $phone_content = ($election->reminderdescription == Null) ?
                "*လူကြီးမင်းသည် အွန်လိုင်းစနစ်ဖြင့် မဲပေးရန် ကျန်ရှိနေပါသည်။ မဲပေးရန်အတွက် Link ကိုနှိပ်ပါ။ " . $url :
                "$election->reminderdescription" . $url;



            $data = $bulksms->sendSMS($non_vote_voter->phone_no, $phone_content);
            if ($data->getData()->success) {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 2]);
                return response()->json(['success' => 'SMS Send Successfully.']);
            } else {
                DB::table('logs')->where('voter_id', $non_vote_voter->id)->update(['reminder_sms_flag' => 1]);
                return response()->json(['errors' => $data['errors']]);
            }
        } else {
            return response()->json(['errors' => 'Phone Number Does not Exist!']);
        }
    }
}
