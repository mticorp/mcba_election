<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;

class SecurityController extends Controller
{
    public function index()
    {
        return view('admin.setting.security.index');
    }

    public function update(Request $request)
    {        
        Setting::first()->update([
            'otp_enable' =>  $request->otp_enable == 'on' ? 1 : 0,
            'result_enable' => $request->result_enable == 'on' ? 1 : 0,
            'otp_valid_time' => $request->otp_valid_time,
            'otp_valid_time_type' => $request->time_type,
        ]);
        return response()->json(['success' => 'Successfully Status Changed!']);
    }
}
