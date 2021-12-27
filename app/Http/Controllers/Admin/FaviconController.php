<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FaviconController extends Controller
{
    public function index()
    {       
        return view('admin.setting.favicon_setting.index');
    }

    public function store(Request $request)
    {
        $rules = array(
            'fav_name'    =>  'required|string|max:255',
            'image'         =>  'required|mimes:png,jpg,jpeg',
        );

        $error = Validator::make($request->all(), $rules);        

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');
        $upload_path = public_path() . '/upload/setting/favicon/';
        $file = $image->getClientOriginalExtension();
        $name = rand() . '.' . $file;
        $image->move($upload_path, $name);

        $new_name = '/upload/setting/favicon/' . $name;

        $form_data = array(
            'fav_name'        => $request->fav_name,
            'fav_icon'       => $new_name,
        );

        Setting::first()->update($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Setting::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {
        $image_name = $request->hidden_image;

        $image = $request->file('image');
        if ($image != '') {
            if ($request->hidden_image) {
                unlink(public_path() . $request->hidden_image);
            }
            $rules = array(
                'fav_name'    =>  'required|string|max:255',
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
            $name = rand() . '.' . $file;
            $image->move($upload_path, $name);
            $image_name = '/upload/setting/favicon/' . $name;
        } else {
            $rules = array(
                'fav_name'    =>  'required|string|max:255',
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'fav_name'       =>   $request->fav_name,
            'fav_icon'            =>   $image_name,
        );
        Setting::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }
    
    public function destroy($id)
    {
        $data = Setting::findOrFail($id);

        $path = public_path() . $data->fav_icon;

        if ($data->fav_icon) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->update(['fav_name' => null, 'fav_icon' => null]);
    }
}
