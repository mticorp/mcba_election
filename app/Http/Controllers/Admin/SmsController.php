<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.sms.index');
    }

    public function update(Request $request)
    {
        Setting::first()->update(['sms_text' => $request->sms_text . " "]);
        return redirect()->back();
    }

    public function reminderIndex()
    {
        return view('admin.setting.reminder.index');
    }

    public function reminderUpdate(Request $request)
    {
        Setting::first()->update(['reminder_text' => $request->reminder_text + " "]);
        return redirect()->back();
    }

    public function memberIndex()
    {
        return view('admin.setting.member-sms.index');
    }

    public function memberUpdate(Request $request)
    {
        Setting::first()->update(['member_sms_text' => $request->sms_text . " "]);
        return redirect()->back();
    }
}
