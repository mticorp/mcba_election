<?php

namespace App;

use App\Candidate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CandidateExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('candidate')
                    ->join('degree','degree.candidate_id','=','candidate.id')
                    ->join('interdegree','interdegree.candidate_id','=','candidate.id')
                    ->join('social','social.candidate_id','=','candidate.id')
                    ->select('candidate.candidate_no',
                    'candidate.mname',
                    'candidate.age',
                    'degree.degree_name',
                    'degree.degree_year',
                    'candidate.now_job',
                    'candidate.email',
                    'candidate.phone_no',
                    'candidate.des')
                    ->get();
            
    }

    public function headings(): array
    {   
        return [
            'ကိုယ်စားလှယ်လောင်းအမှတ်',
            'အမည် (မြန်မာစာဖြင့်)',
            'အသက်',            
            'ရရှိသည့်ဘွဲ့ များနှင့်ခုနှစ်များ',            
            'လက်ရှိအလုပ်အကိုင်',
            'အီးမေးလ်',
            'ဆက်သွယ်ရန်ဖုန်း','မှတ်ချက်'];
    }
}