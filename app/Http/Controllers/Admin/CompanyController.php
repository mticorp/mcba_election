<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\Favicon;
use App\Logo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
    public function index()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Company::latest()->get(['company.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-info btn-xs btn-flat"><i class="fas fa-edit"></i> Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.company.index',compact('logo','favicon'));
    }

    public function store(Request $request)
    {
        $rules = array(
            'company_name'    =>  'required|string|max:255',
            'image'         =>  'required|mimes:png,jpg,jpeg',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');
        $upload_path = public_path() . '/upload/company/';
        $file = $image->getClientOriginalExtension();
        $name = rand().'.'. $file;
        $image->move($upload_path, $name);

        $new_name = '/upload/company/' . $name;

        $form_data = array(
            'company_name'        => $request->company_name,
            'company_logo'       => $new_name,
        );

        Company::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function edit($id)
    {
        // dd($id);
        if (request()->ajax()) {
            $data = Company::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if ($image != '') {
            $rules = array(
                'company_name'    =>  'required|string|max:255',
                'image'         =>  'required|image|mimes:png,jpg,jpeg',
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $oldpath = public_path() . $request->hidden_image;

            if (file_exists($oldpath)) {
                unlink($oldpath);
            }
            $upload_path = public_path() . '/upload/company/';
            $file = $image->getClientOriginalExtension();
            $name = rand().'.'. $file;
            $image->move($upload_path, $name);
            $image_name = '/upload/company/' . $name;
        } else {
            $rules = array(
                'company_name'    =>  'required|string|max:255',
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'company_name'       =>   $request->company_name,
            'company_logo'            =>   $image_name,
        );
        Company::whereId($request->hidden_id)->update($form_data);

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
        $data = Company::findOrFail($id);
        // dd($data);
        $path = public_path(). $data->company_logo;

        if($data->company_logo)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->delete();

        $nonedata = DB::table('company')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement("SET FOREIGN_KEY_CHECKS = 0");
            DB::table('company')->truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS = 1");
        } else {
            $last_id = Company::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `company` AUTO_INCREMENT = $last_id");
        }
    }
}
