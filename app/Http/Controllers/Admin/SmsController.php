<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;
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

        $elections = Election::all();
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.setting.sms&reminder_setting.index', compact('elections','logo','favicon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createpage($id)
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $election = Election::where('id', $id)->first();
        return view('admin.setting.sms&reminder_setting.create_page', compact('election','logo','favicon'));
    }

    public function remindercreatepage($id)
    {
        $election = Election::where('id', $id)->first();
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.setting.sms&reminder_setting.reminder_create_page', compact('election','logo','favicon'));
    }


    public function descriptioncreatepage($id)
    {
        $election = Election::where('id', $id)->first();
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.setting.sms&reminder_setting.description_create_page', compact('election','logo','favicon'));
    }


    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        //
    }

    public function update(Request $request, $id)
    {
        DB::table('election')
        ->where("election.id", '=',  $id)
        ->update(['election.smsdescription'=> $request->sms]);
        return redirect()->route('admin.sms.index');
    }


    public function reminderupdate(Request $request, $id)
    {
        DB::table('election')
        ->where("election.id", '=',  $id)
        ->update(['election.reminderdescription'=> $request->reminder]);
        return redirect()->route('admin.sms.index');
    }


    public function descriptionupdate(Request $request, $id)
    {
        DB::table('election')
        ->where("election.id", '=',  $id)
        ->update(['election.election_title_description'=> $request->description]);
        return redirect()->route('admin.sms.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
