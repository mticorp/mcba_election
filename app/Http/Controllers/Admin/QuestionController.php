<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use DB;
use Validator;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($election_id)
    {
        
       
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Question::latest()->where('questions.election_id',$election_id)->join('election','election.id','questions.election_id')->get(['questions.*','election.status', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) {
                    if($DT_data->status == 1)
                    {
                        $button = '<button type="button" name="edit" id="' . $DT_data->id . '" class="edit btn btn-info btn-xs btn-flat" disabled><i class="fas fa-edit"></i> Edit</button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="' . $DT_data->id . '" class="delete btn btn-danger btn-xs btn-flat" disabled><i class="fa fa-trash"></i> Delete</button>';
                    }else{
                        $button = '<button type="button" name="edit" id="' . $DT_data->id . '" class="edit btn btn-info btn-xs btn-flat"><i class="fas fa-edit"></i> Edit</button>';
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
            if($election->ques_flag == 1)
            {
                $elections = $election_modal->electionWithoutCurrent($election_id);
                return view('admin.question.index',compact('election','elections'));
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $rules = array(
            'no_mm' => 'required',
            'ques' => 'required|string',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->file('image') != '')
        {
            $image = $request->file('image');
            $upload_path = public_path() . '/upload/question/';
            $file = $image->getClientOriginalExtension();
            $name = rand() . '.' . $file;
            $image->move($upload_path, $name);

            $new_name = '/upload/question/' . $name;
        }else{
            $new_name = null;
        }

        $form_data = array(
            'no'        => $request->no_mm,
            'ques'       => $request->ques,
            'election_id'=> $request->election_id,
            'image' => $new_name,
        );

        Question::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function edit($question_id)
    {
        // dd($id);
        if (request()->ajax()) {
            $data = Question::findOrFail($question_id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $rules = array(
            'no_mm' => 'required',
            'ques' => 'required|string',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }                    

        if ($request->file('image') != '') {            
            $image = $request->file('image');
            
            $oldpath = public_path() . $request->hidden_image;

            if (file_exists($oldpath)) {
                unlink($oldpath);
            }
            
            $upload_path = public_path() . '/upload/question/';
            $file = $image->getClientOriginalExtension();
            $name = rand() . '.' . $file;
            $image->move($upload_path, $name);
            $new_name = '/upload/question/' . $name;
        } else {
            if($request->hidden_image != 'null')
            {
                $new_name = $request->hidden_image;
            }else{
                $new_name = null;
            }
        }

        $form_data = array(
            'no'       =>   $request->no_mm,
            'ques'            =>   $request->ques,
            'election_id'=> $request->election_id,
            'image' => $new_name,
        );
        Question::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Question::findOrFail($id);
        // dd($data);

        $data->delete();

        $nonedata = DB::table('questions')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('questions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            $last_id = Question::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `questions` AUTO_INCREMENT = $last_id");
        }
    }
}
