<?php

namespace App\Exports;

use App\Candidate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;

class ExportCandidate implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Candidate::query()->select(

            "photo_url",
            "candidate_no",
            "mname",
            "nrc_no",
            "dob",
            "position",
            "education",
            "phone_no",
            "gender",
            "email",
            "address",
            "company",
            "company_start_date",
            "no_of_employee",
            "company_email",
            "company_phone",
            "company_address",
            "experience",
            "biography",
        );
    }

    public function headings(): array
    {
        return [
            'Photo Url',
            'Candidate No',
            'Name',
            'NRC No',
            'Date Of Birth',
            'Position',
            'Education',
            'Phone Number',
            'Gender',
            'Email',
            'Address',
            'Company',
            'Company Established Year',
            'No of Employee',
            'Company Email',
            'Company Phone Number',
            'Company Address',
            'Experience',
            'Biography',
        ];
    }
}
