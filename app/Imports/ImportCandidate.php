<?php

namespace App\Imports;

use App\Candidate;
use App\Result;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use DB;

class ImportCandidate implements ToModel,WithHeadingRow,WithValidation
{
    use Importable;

    public function  __construct($election_id)
    {
        $this->election_id = $election_id;
    }

    public function model(array $row)
    {
        if($row['photo_url'] == '')
        {
            $profile = '/images/user.png';
        }else{
            $profile = $row['photo_url'];
        }
        $candidate = Candidate::create([
            "photo_url" => $profile,
            "candidate_no" => $row['candidate_no'],
            "mname"     => $row['name'],
            "nrc_no"     => $row['nrc_no'],
            "dob"     => $row['date_of_birth'],
            "position"     => $row['position'],
            "education"     => $row['education'],
            "phone_no"     => $row['phone_number'],
            "gender" => $row['gender'],
            "email"     => $row['email'],
            "address"     => $row['address'],
            "company"     => $row['company'],
            "company_start_date"     => $row['company_established_year'],
            "no_of_employee"     => $row['no_of_employee'],
            "company_email"     => $row['company_email'],
            "company_phone"     => $row['company_phone_number'],
            "company_address"     => $row['company_address'],
            "experience"     => $row['experience'],
            "biography" => $row['biography'],
            "election_id" => $this->election_id,
        ]);

        Result::create([
            'candidate_id' => $candidate->id,
            'election_id' => $this->election_id,
        ]);

        return $candidate;
    }

    public function rules(): array
    {
        return [
            'photo_url' => 'nullable',
            'candidate_no' => 'required|string',
            'name' => 'required|string',            
        ];
    }
}
