<?php

namespace App\Exports;

use App\Lucky;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ExportLuckyList implements FromCollection
{
    public function  __construct($election_id)
    {
        $this->election_id = $election_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('lucky')->select('code','name','phone')->where('election_id',$this->election_id)->get();
    }
}
