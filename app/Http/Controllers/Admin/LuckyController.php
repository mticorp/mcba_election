<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use App\Exports\ExportLuckyList;
use App\Favicon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logo;
use App\Lucky;
use Excel;
use DB;

class LuckyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index($election_id)
    {

        
        $logo = Logo::first();
        $favicon = Favicon::first();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = Lucky::latest()->where('election_id',$election_id)->get(['lucky.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->make(true);
        }
        $election_modal = new Election;
        $election = $election_modal->electionWithId($election_id);
        if($election)
        {
            if($election->lucky_flag == 1)
            {
                $elections = $election_modal->electionWithoutCurrent($election_id);
                return view('admin.lucky.index',compact('election','elections','logo','favicon'));
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function export($election_id)
    {
        return Excel::download(new ExportLuckyList($election_id), 'luckyList.xlsx');
    }
}
