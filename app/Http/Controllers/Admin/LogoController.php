<?php

namespace App\Http\Controllers\Admin;

use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;
use App\LogoAndFavicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.setting.logo_setting.index',compact('logo','favicon'));
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
            'image'         =>  'required|mimes:png,jpg,jpeg',
        );

        $error = Validator::make($request->all(), $rules);

        $logo = Logo::first();
        if($logo)
        {
            return response()->json(['errors' => 'Logo Exist']);
        }

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');
        $upload_path = public_path() . '/upload/setting/logo/';
        $file = $image->getClientOriginalExtension();
        $name = rand().'.'. $file;
        $image->move($upload_path, $name);

        $new_name = '/upload/setting/logo/' . $name;

        $form_data = array(
            'logo'       => $new_name,
        );

        Logo::create($form_data);

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
         //dd($id);
        if (request()->ajax()) {
            $data = Logo::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if ($image != '') {
            if ($request->hidden_image) {
                unlink(public_path() . $request->hidden_image);
            }
            $rules = array(
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
            $upload_path = public_path() . '/upload/setting/logo/';
            $file = $image->getClientOriginalExtension();
            $name = rand().'.'. $file;
            $image->move($upload_path, $name);
            $image_name = '/upload/setting/logo/' . $name;
        } 
    

        $form_data = array(
            'logo'            =>   $image_name,
        );
        Logo::whereId($request->hidden_id)->update($form_data);

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
        $data = Logo::findOrFail($id);
        
        $path = public_path(). $data->logo;

        if($data->logo)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->delete();

        $nonedata = DB::table('logo')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement("SET FOREIGN_KEY_CHECKS = 0");
            DB::table('company')->truncate();
            DB::statement("SET FOREIGN_KEY_CHECKS = 1");
        } else {
            $last_id = Logo::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `logo` AUTO_INCREMENT = $last_id");
        }
    }
}
