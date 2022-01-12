<?php

namespace App\Exports;

use App\MRegister;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;
use CyberWings\MyanmarPhoneNumber;

class ExportMemberList implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return MRegister::query()->select(
            'profile',
            'name',
            'nrc',
            'refer_code',
            'complete_training_no',
            'valuation_training_no',
            'AHTN_training_no',
            'graduation',
            'address',
            'phone_number',
            'email',
            'officeName',
            'office_startDate',
            'officeAddress',
            'officePhone',
            'officeFax',
            'officeEmail',
            'yellowCard',
            'pinkCard',
            'check_flag',
        );
    }

    public function headings(): array
    {
        return [
            'profile',
            'Name',
            'NRC',
            'refer_code',
            'complete_training_no',
            'valuation_training_no',
            'AHTN_training_no',
            'graduation',
            'address',
            'phone_number',
            'email',
            'officeName',
            'office_startDate',
            'officeAddress',
            'officePhone',
            'officeFax',
            'officeEmail',
            'yellowCard',
            'pinkCard',
            'check_flag',
        ];
    }
}
