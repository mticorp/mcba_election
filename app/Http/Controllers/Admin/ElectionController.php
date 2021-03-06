<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\Election;
use App\ElectionVoter;
use App\Company;
use App\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Question;
use App\Setting;
use Carbon\Carbon;

class ElectionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $elections = Election::all();
        
        return view('admin.election.index', compact('elections'));
    }

    public function create()
    {
        $company = Company::all();        
        
        return view('admin.election.create', compact('company'));
    }

    public function changeStatus(Request $request)
    {
        $time = Carbon::now();

        $election = Election::find($request->election_id);
        if ($request->status == 1) {
            $ques_count = Question::where('election_id', $request->election_id)->count();
            $candidate_count = Candidate::where('election_id', $request->election_id)->count();
            if ($election->candidate_flag == 0 && $election->ques_flag == 1) {
                if ($ques_count > 0) {
                    $election->start_time = Carbon::parse($time)->format("Y-m-d H:i:s");
                } else {
                    return response()->json(['ques_notFound' => true]);
                }
            } elseif ($election->candidate_flag == 1 && $election->ques_flag == 0) {
                if ($candidate_count > 0) {
                    $election->start_time = Carbon::parse($time)->format("Y-m-d H:i:s");
                } else {
                    return response()->json(['cadidate_notFound' => true]);
                }
            } elseif ($election->candidate_flag == 1 && $election->ques_flag == 1) {
                if ($candidate_count == 0) {
                    if ($ques_count == 0) {
                        return response()->json(['ques_notFound' => true, 'cadidate_notFound' => true]);
                    } else {
                        return response()->json(['cadidate_notFound' => true]);
                    }
                } else {
                    if ($ques_count == 0) {
                        return response()->json(['ques_notFound' => true]);
                    } else {
                        $election->start_time = Carbon::parse($time)->format("Y-m-d H:i:s");
                    }
                }
            }
        } else {
            $election->end_time = Carbon::parse($time)->format("Y-m-d H:i:s");
        }

        $election->status = $request->status;
        // $election->save();
        if ($election->save()) {
            return response()->json(['success' => 'Status change successfully.', 'election' => $election]);
        } else {
            return response()->json(['errors' => 'Status change Failed.', 'election' => $election]);
        }
    }

    public function forcechangeStatus(Request $request)
    {
        $time = Carbon::now();

        $election = Election::find($request->election_id);
        $election->start_time = Carbon::parse($time)->format("Y-m-d H:i:s");
        $election->status = 1;
        if ($request->flag == "candidate") {
            $election->candidate_flag = 0;
        } else {
            $election->ques_flag = 0;
        }

        // $election->save();

        if ($election->save()) {
            return response()->json(['success' => 'Status change successfully.', 'election' => $election]);
        } else {
            return response()->json(['errors' => 'Status change Failed.', 'election' => $election]);
        }        
    }

    public function luckyFlag(Request $request)
    {
        $election = Election::find($request->election_id);
        if ($election) {
            $election->lucky_flag = $request->lucky_flag;
            $election->save();
            // dd($election);
            return response()->json(['success' => 'Status Change successfully.']);
        } else {
            return response()->json(['errors' => 'Election Not Found!']);
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:255',
            'durationfrom' => 'required',
            'durationto' => 'required',
            //for candidate flag
            'position' => 'required_if:candidate_flag,==,1|nullable',
            'no_of_position_mm' => 'required_if:candidate_flag,==,1|nullable',
            'no_of_position_en' => 'required_if:candidate_flag,==,1|nullable|numeric',
            'description' => 'required_if:candidate_flag,==,1|nullable|string|max:255',
            'candidate_title' => 'required_if:candidate_flag,==,1|nullable|string|max:255',

            // for question flag
            'ques_title' => 'required_if:ques_flag,==,1|nullable',
            'ques_description' => 'required_if:ques_flag,==,1|nullable',
            'company' => 'required',
        );

        $message = array(
            'name.required' => 'Election Name is required !',
            'durationfrom.required' => 'Duration From is required !',
            'durationto.required' => 'Duration To is required !',

            'position.required_if' => 'Election Position is required if Candidate flag is ON !',
            'no_of_position_mm.required_if' => 'No of Position (MM) is required if Candidate flag is ON !',
            'no_of_position_en.required_if' => 'No of Position (EN) is required if Candidate flag is ON !',
            'description.required_if' => 'Description is required if Candidate flag is ON !',
            'candidate_title.required_if' => 'Candidate Title is required if Candidate flag is ON !',
            'ques_title.required_if' => 'Question Title is required if Question flag is ON !',
            'ques_description.required_if' => 'Question Description is required if Question flag is ON !',

        );

        $error = Validator::make($request->all(), $rules, $message);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'        => $request->name,
            'election_title_description' => $request->election_title_description,
            'description'       => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'no_of_position_mm' => $request->no_of_position_mm,
            'no_of_position_en' => $request->no_of_position_en,
            'candidate_title' => $request->candidate_title,
            'position' => $request->position,
            'company_id' => $request->company,
            'lucky_flag' => $request->lucky_flag,
            'ques_flag' => $request->ques_flag,
            'candidate_flag' => $request->candidate_flag,
            'duration_from' => $request->durationfrom,
            'duration_to' => $request->durationto,
            'ques_title' => $request->ques_title,
            'ques_description' => $request->ques_description,
        );

        $data = Election::create($form_data);
        $check_voter = DB::table('voter')->count();
        if ($check_voter > 0) {
            $voter = Voter::all();
            foreach ($voter as $row) {
                $election_voter = new ElectionVoter();
                $election_voter->voter_id = $row->id;
                $election_voter->election_id = $data->id;
                $election_voter->save();
            }
        }

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function edit($election_id)
    {                
        $election = Election::find($election_id);
        $company = Company::all();
        return view('admin.election.edit', compact('election', 'company'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = array(
            'name' => 'required|string|max:255',
            'durationfrom' => 'required',
            'durationto' => 'required',
            //for candidate flag
            'position' => 'required_if:candidate_flag,==,1|nullable',
            'no_of_position_mm' => 'required_if:candidate_flag,==,1|nullable',
            'no_of_position_en' => 'required_if:candidate_flag,==,1|nullable|numeric',
            'description' => 'required_if:candidate_flag,==,1|nullable|string|max:255',
            'candidate_title' => 'required_if:candidate_flag,==,1|nullable|string|max:255',

            // for question flag
            'ques_title' => 'required_if:ques_flag,==,1|nullable',
            'ques_description' => 'required_if:ques_flag,==,1|nullable',
            'company' => 'required',
        );

        $message = array(
            'name.required' => 'Election Name is required !',
            'durationfrom.required' => 'Duration From is required !',
            'durationto.required' => 'Duration To is required !',

            'position.required_if' => 'Election Position is required if Candidate flag is ON !',
            'no_of_position_mm.required_if' => 'No of Position (MM) is required if Candidate flag is ON !',
            'no_of_position_en.required_if' => 'No of Position (EN) is required if Candidate flag is ON !',
            'description.required_if' => 'Description is required if Candidate flag is ON !',
            'candidate_title.required_if' => 'Candidate Title is required if Candidate flag is ON !',
            'ques_title.required_if' => 'Question Title is required if Question flag is ON !',
            'ques_description.required_if' => 'Question Description is required if Question flag is ON !',
        );

        $error = Validator::make($request->all(), $rules, $message);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'        => $request->name,
            'election_title_description' => $request->election_title_description,
            'description'       => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'no_of_position_mm' => $request->no_of_position_mm,
            'no_of_position_en' => $request->no_of_position_en,
            'candidate_title' => $request->candidate_title,
            'position' => $request->position,
            'company_id' => $request->company,
            'lucky_flag' => $request->lucky_flag,
            'ques_flag' => $request->ques_flag,
            'candidate_flag' => $request->candidate_flag,
            'duration_from' => $request->durationfrom,
            'duration_to' => $request->durationto,
            'ques_title' => $request->ques_title,
            'ques_description' => $request->ques_description,
        );

        Election::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($election_id)
    {
        Election::destroy($election_id);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('voter')->truncate();

        DB::table('logs')->truncate();

        $nonedata = DB::table('election')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::table('election')->truncate();
        } else {
            $last_id = Election::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `election` AUTO_INCREMENT = $last_id");
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json(['success' => 'Successfully Deleted!']);
    }
}
