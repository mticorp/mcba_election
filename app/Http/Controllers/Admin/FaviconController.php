<?php

namespace App\Http\Controllers\Admin;

use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FaviconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Favicon::latest()->get(['favicon.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
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
        return view('admin.setting.favicon_setting.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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
        $upload_path = public_path() . '/upload/setting/favicon/';
        $file = $image->getClientOriginalExtension();
        $name = rand().'.'. $file;
        $image->move($upload_path, $name);

        $new_name = '/upload/setting/favicon/' . $name;

        $form_data = array(
            'favicon_name'        => $request->company_name,
            'favicon'       => $new_name,
        );

        Favicon::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        if (request()->ajax()) {
            $data = Favicon::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if ($image != '') {
            $rules = array(
                'company_name'    =>  'required|string|max:255',
                'image'         =>  'required|mimes:png,jpg,jpeg',
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $oldpath = public_path() . $request->hidden_image;

            if (file_exists($oldpath)) {
                unlink($oldpath);
            }
            $upload_path = public_path() . '/upload/setting/favicon/';
            $file = $image->getClientOriginalExtension();
            $name = rand().'.'. $file;
            $image->move($upload_path, $name);
            $image_name = '/upload/setting/favicon/' . $name;
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
            'favicon_name'       =>   $request->company_name,
            'favicon'            =>   $image_name,
        );
        Favicon::whereId($request->hidden_id)->update($form_data);

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
        $data = Favicon::findOrFail($id);
        // dd($data);
        $path = public_path(). $data->company_logo;

        if($data->company_logo)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->delete();

        $nonedata = DB::table('favicon')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement("SET FOREIGN_KEY_CHECKS = 0");
            DB::table('favicon')->truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS = 1");
        } else {
            $last_id = Favicon::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `company` AUTO_INCREMENT = $last_id");
        }
    }
}
