<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\Election;
use App\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCandidate;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index($election_id)
    {        
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Candidate::orderBy('candidate_no', 'asc')->where('candidate.election_id',$election_id)->join('election','election.id','candidate.election_id')->get(['candidate.*','election.status', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) {
                    $button = '<button type="button" name="detail" id="' . $DT_data->id . '" class="detail btn btn-dark btn-xs btn-flat"><i class="fa fa-eye"></i> Detail</button>';
                    $button .= '&nbsp;&nbsp;';
                    if($DT_data->status == 1)
                    {
                        $button .= '<button type="button" name="edit" id="' . $DT_data->id . '" class="edit btn btn-info btn-xs btn-flat" disabled><i class="fas fa-edit"></i> Edit</button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="' . $DT_data->id . '" class="delete btn btn-danger btn-xs btn-flat" disabled><i class="fa fa-trash"></i> Delete</button>';
                    }else{
                        $button .= '<button type="button" name="edit" id="' . $DT_data->id . '" class="edit btn btn-info btn-xs btn-flat"><i class="fas fa-edit"></i> Edit</button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="' . $DT_data->id . '" class="delete btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Delete</button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            $elections = $election_modal->electionWithoutCurrent($election_id);
            $candidates = Candidate::where('election_id', '=', $election_id)->orderby('id', 'asc')->get();
            // dd($candidates);
            return view('admin.candidate.index',compact('elections','election','candidates'));
        }else{
            return abort(404);
        }
    }

    public function excelImport($election_id)
    {
        
       
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            $elections = $election_modal->electionWithoutCurrent($election_id);
            return view('admin.candidate.import-excel',compact('election','elections'));
        }else{
            return abort(404);
        }
    }

    public function Import(Request $request)
    {
        if($request->ajax())
        {
            $election_id = $request->election_id;
            if ($request->hasFile('file')) {
                $validator = Validator::make([
                    'file'      => $request->file,
                    'extension' => strtolower($request->file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()->all()]);
                }
                try{
                    Excel::import(new ImportCandidate($election_id), $request->file('file'));
                }
                catch (\Maatwebsite\Excel\Validators\ValidationException $failures)
                {
                    return response()->json(['validator' => $failures]);
                }

                return response()->json(['success' => 'Data Added Successfully']);
            }else{
                return response()->json(['errors' => 'Excel File is Required!']);
            }
        }else{
            return abort(404);
        }
    }

    public function export()
    {
        $file_path = public_path() . '/upload/Candidate_List_template.xlsx';
        // dd($file_path);
        if (file_exists($file_path))
        {
            return response()->download($file_path);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function create($election_id)
    {       
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            if($election->status == 0)
            {
                $elections = $election_modal->electionWithoutCurrent($election_id);
                return view('admin.candidate.create',compact('election','elections'));
            }else{
                abort(403,'Election is already Started!');
            }
        }else{
            return abort(404);
        }

    }

    public function detail($election_id,$candidate_id)
    {
        
       
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            $candidate = Candidate::find($candidate_id);
            if ($candidate != null) {
                $elections = $election_modal->electionWithoutCurrent($election_id);
                return view('admin.candidate.detail', compact('candidate','election','elections'));
            } else {
                return abort(404);
            }
        }else{
            return abort(404);
        }
    }

    public function edit($election_id,$candidate_id)
    {
        
       
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            if($election->status == 0)
            {
                $candidate = Candidate::find($candidate_id);
                if ($candidate != null) {
                    $elections = $election_modal->electionWithoutCurrent($election_id);
                    return view('admin.candidate.edit', compact('candidate','election','elections'));
                } else {
                    return abort(404);
                }
            }else{
                abort(403,'Election is already Started!');
            }
        }else{
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $error = Validator::make($request->all(), [
            'candidate_no' => 'required|max:255',
            'mname' => 'required|string|max:255',
            // 'nrc_no' => 'required|string|max:255',
            // 'dob' => 'required|date',
            // // 'position' => 'required',
            // 'phone_no' => 'required',
            // 'company' => 'required',
        ],[
            'candidate_no.required'=>'ကိုယ်စားလှယ်လောင်းအမှတ် ဖြည့်စွက်ရန်.',
            'mname.required'=>'အမည် ဖြည့်စွက်ရန်.',
            // 'company.required'=>'လုပ်ငန်းအမည် ဖြည့်စွက်ရန်.',
            // 'nrc_no.required'=>'နိုင်ငံသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
            // 'dob.required'=>'မွေးသက္ကရာဇ် ဖြည့်စွက်ရန်.',
            // 'phone_no.required'=>'ဆက်သွယ်ရန်ဖုန်း ဖြည့်စွက်ရန်.',
            // 'position.required'=>'ရာထူး ဖြည့်စွက်ရန်.',
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->hasfile('image'))
        {
            $image = $request->file('image');
            $filename = rand() . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/candidate/' . $filename));
            $new_name = '/upload/candidate/' . $filename;
        }else{
            $new_name = '/images/user.png';
        }

        $form_data = array(
            "candidate_no" => $request->candidate_no,
            "mname"     => $request->mname,
            "nrc_no"     => $request->nrc_no,
            "dob"     => $request->dob,
            "position"     => $request->position,
            "education"     => $request->education,
            "phone_no"     => $request->phone_no,
            "gender" => $request->gender,
            "email"     => $request->email,
            "address"     => $request->address,
            "company"     => $request->company,
            "company_start_date"     => $request->company_start_date,
            "no_of_employee"     => $request->no_of_employee,
            "company_email"     => $request->company_email,
            "company_phone"     => $request->company_phone,
            "company_address"     => $request->company_address,
            "experience"     => $request->experience,
            "biography" => $request->biography,
            "election_id" => $request->election_id,
            "photo_url" => $new_name,
        );

        $candidate = Candidate::create($form_data);

        $result_data = array(
           "candidate_id" => $candidate->id,
           "election_id" => $request->election_id,
        );

        Result::create($result_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function update(Request $request)
    {
        $error = Validator::make($request->all(), [
            'candidate_no' => 'required|max:255',
            'mname' => 'required|string|max:255',
            // 'nrc_no' => 'required|string|max:255',
            // 'dob' => 'required|date',
            // // 'position' => 'required',
            // 'phone_no' => 'required',
            // 'company' => 'required',
        ],[
            'candidate_no.required'=>'ကိုယ်စားလှယ်လောင်းအမှတ် ဖြည့်စွက်ရန်.',
            'mname.required'=>'အမည် ဖြည့်စွက်ရန်.',
            // 'company.required'=>'လုပ်ငန်းအမည် ဖြည့်စွက်ရန်.',
            // 'nrc_no.required'=>'နိုင်ငံသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
            // 'dob.required'=>'မွေးသက္ကရာဇ် ဖြည့်စွက်ရန်.',
            // 'phone_no.required'=>'ဆက်သွယ်ရန်ဖုန်း ဖြည့်စွက်ရန်.',
            // 'position.required'=>'ရာထူး ဖြည့်စွက်ရန်.',
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            if($request->old_image || $request->old_image != '')
            {
                if($request->old_image != '/images/user.png')
                {
                    $oldpath = public_path() . $request->old_image;
                    if (file_exists($oldpath)) {
                        unlink($oldpath);
                    }
                }
            }

            $filename = rand() . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/candidate/' . $filename));
            $image_name = '/upload/candidate/' . $filename;
        }else{
            $image_name = $request->old_image;
        }

        $form_data = array(
            "candidate_no" => $request->candidate_no,
            "mname"     => $request->mname,
            "nrc_no"     => $request->nrc_no,
            "dob"     => $request->dob,
            "position"     => $request->position,
            "education"     => $request->education,
            "phone_no"     => $request->phone_no,
            "gender" => $request->gender,
            "email"     => $request->email,
            "address"     => $request->address,
            "company"     => $request->company,
            "company_start_date"     => $request->company_start_date,
            "no_of_employee"     => $request->no_of_employee,
            "company_email"     => $request->company_email,
            "company_phone"     => $request->company_phone,
            "company_address"     => $request->company_address,
            "experience"     => $request->experience,
            "biography" => $request->biography,
            "election_id" => $request->election_id,
            "photo_url" => $image_name,
        );

        Candidate::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($election_id,$candidate_id)
    {
        $data = Candidate::where('id',$candidate_id)->where('election_id',$election_id)->first();
        $path = public_path(). $data->photo_url;

        if($data->photo_url || $data->photo_url != null)
        {
            if($data->photo_url != '/images/user.png')
            {
                if(file_exists($path))
                {
                    unlink($path);
                }
            }
        }
        $data->delete();

        $nonedata = DB::table('candidate')->count() == 0 ? true : false;
        $result_nonedata = DB::table('result')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('candidate')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            $last_id = Candidate::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `candidate` AUTO_INCREMENT = $last_id");
        }

        if ($result_nonedata) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('result')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            $last_id = Result::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `result` AUTO_INCREMENT = $last_id");
        }

        return response()->json(['success' => 'Successfully Deleted!']);        
    }
}
