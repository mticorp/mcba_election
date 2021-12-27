<?php

namespace App\Exports;

use App\Setting;
use App\Voter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use CyberWings\MyanmarPhoneNumber;

class ExportVoterList implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection()
    {
        // return Voter::query()->select(['phone_no',DB::raw("")]);  
        $setting = Setting::first();
        $voters = Voter::select('phone_no', 'voter_id', 'name','vote_count')->get();
        $phone_number = new MyanmarPhoneNumber();
        foreach ($voters as $voter) {
            $url = route('vote.link', ['voter_id' => $voter->voter_id]);
            $phones = explode(',', $voter->phone_no);
            $generated_phone = "";
            $last_key = count($phones) - 1;
            foreach ($phones as $key => $phone) {
                $comman = $last_key != $key ? ',' : '';
                $generated_phone .= $phone_number->add_prefix($phone) . $comman;
            }
            $voter->phone_no = $generated_phone;                        
            if ($setting->sms_text) {                
                $phone_content = str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name,"(".$voter->vote_count.")"], $setting->sms_text) . $url;
            } else {
                $phone_content = "မင်္ဂလာပါ " . $voter->name . "! အောက်ဖော်ပြပါ Link အားနှိပ်၍ ဝင်ရောက်မဲပေးနိုင်ပါပြီ။ " . $url;
            }
            $voter->voter_id = $phone_content;
            unset($voter->name);
            unset($voter->vote_count);
        }        
        return $voters;
    }

    public function headings(): array
    {
        return [
            'Phone No',
            'Message',
        ];
    }
}
