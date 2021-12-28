<?php

namespace App\Imports;

use App\MRegister;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
// use Stichoza\GoogleTranslate\GoogleTranslate;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class ImportMember implements ToModel,WithHeadingRow,WithValidation,WithChunkReading {
    use Importable;

    /**
    * param array $row
    *
    * return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row['profile'] == '')
        {
            $profile = '/images/user.png';
        }else{
            $profile = $row['profile'];
        }
        
        // $en = new GoogleTranslate('en');
        // $nrc_no = $en->translate($row['nrc_no']);        
        // $nrc_no = str_replace(' ', '', $nrc_no);
        // $state_district = explode('/',$nrc_no);
        // $state_no = $state_district[0];
        // $district = explode('(',$state_district[1]);        
        // $register_no = explode(')',$nrc_no)[1];

        // $myanmar_nrc = $state_no . "/" . Str::lower($district[0]) . "(N)" . $register_no; 
        
        return new MRegister([
            'profile' =>   $profile,
            'name'     => $row['name'],
            'nrc'       => $row['nrc_no'],
            'refer_code' => $row['customs_reference_code'],
            'complete_training_no'   => $row['complete_training_no'],
            'valuation_training_no'   => $row['valuation_training_no'],
            'AHTN_training_no' => $row['ahtn_training_no'],
            'graduation'=> $row['graduation'],
            'address' => $row['address'],
            'phone_number'  => $row['phone_number'],
            'email'     => $row['email'],
            'officeName' => $row['office_name'],
            'office_startDate' => $row['office_start_date'],
            'officeAddress' => $row['office_address'],
            'officePhone' => $row['office_phone_number'],
            'officeFax' => $row['office_fax'],
            'officeEmail' => $row['office_email'],
            'yellowCard' => $row['yellow_card'],
            'pinkCard' => $row['pink_card'],
        ]);
    }

    public function rules(): array
    {
        return [
            'profile' => 'nullable',
            'name' => 'required|string',
            'nrc_no' => 'required|string',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
