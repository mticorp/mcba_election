<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
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
        return view('admin.setting.logo_setting.index');
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
            'logo_name' => 'required',
            'image' =>  'required|mimes:png,jpg,jpeg',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $upload_path = public_path() . '/upload/setting/logo/';
            $file = $image->getClientOriginalExtension();
            $name = rand() . '.' . $file;
            $image->move($upload_path, $name);

            $new_name = '/upload/setting/logo/' . $name;
        } else {
            $new_name = null;
        }

        $form_data = array(
            'logo_name' => $request->logo_name,
            'logo_image'       => $new_name,
        );

        Setting::first()->update($form_data);

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
        if (request()->ajax()) {
            $data = Setting::findOrFail($id);
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
            $name = rand() . '.' . $file;
            $image->move($upload_path, $name);
            $image_name = '/upload/setting/logo/' . $name;
        }


        $form_data = array(
            'logo_name' => $request->logo_name,
            'logo_image' =>   $image_name,
        );
        Setting::whereId($request->hidden_id)->update($form_data);

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
        $data = Setting::findOrFail($id);

        $path = public_path() . $data->logo_image;

        if ($data->logo_image) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->update(['logo_name' => null, 'logo_image' => null]);
    }
}
