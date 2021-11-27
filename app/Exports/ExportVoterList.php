<?php

namespace App\Exports;

use App\Voter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportVoterList implements FromCollection, WithHeadings
{
    use Exportable;
   
    public function collection()
    {
        // return Voter::query()->select(['phone_no',DB::raw("")]);        
        $voters = Voter::select('phone_no','voter_id','name')->get();
        foreach($voters as $voter)
        {
            $url = route('vote.link', ['voter_id' => $voter->voter_id]);
            $phone_content = "မင်္ဂလာပါ ".$voter->name." ! အောက်ဖော်ပြပါ Link အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။ " . $url;
            $voter->voter_id = $phone_content;        
            unset($voter->name);
        }       
        return $voters;
    }

    public function headings():array
    {
        return [
            'Phone No',
            'Message',            
        ];
    }
}
