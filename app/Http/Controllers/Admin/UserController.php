<?php

namespace App\Http\Controllers\Admin;

use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use DB;
use Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = User::latest()->get(['users.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
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
        return view('admin.user.index',compact('logo','favicon'));
    }
    public function create()
    {
        return view('admin.user.create');
    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $data = User::findOrFail($id);
            return response()->json(['data' => $data]);
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
        $rules = array(
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|confirmed|min:4|max:14',
            'password_confirmation' => 'required|min:4|max:14',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');

        $filename = rand() . '.jpg';

        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize(400, 200, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image_resize->save(public_path('/upload/user/' . $filename));
        $new_name = '/upload/user/' . $filename;

        $form_data = array(
            "name" => request('name'),
            "email" => request('email'),
            "password" => Hash::make($request->password),
            "type" => request('type'),
            "photo" => $new_name,
        );

        User::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function update(Request $request)
    {
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if ($image != '') {
            $rules = array(
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|confirmed|min:4|max:14',
                'password_confirmation' => 'required|min:4|max:14',
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $oldpath = public_path() . $request->hidden_image;

            if (file_exists($oldpath)) {
                unlink($oldpath);
            }

            $filename = rand() . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/user' . $filename));
            $image_name = '/upload/user' . $filename;
        } else {
            $rules = array(
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|confirmed|min:4|max:14',
                'password_confirmation' => 'required|min:4|max:14',
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "type" => $request->type,
            "photo" => $image_name,
        );
        User::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $path = public_path(). $data->photo;

        if($data->photo)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $data->delete();

        $nonedata = DB::table('users')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('users')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            $last_id = User::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `users` AUTO_INCREMENT = $last_id");
        }
    }
}
