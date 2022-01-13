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
        );
    }

    public function headings(): array
    {
        return [
            'Profile',
            'Name',
            'NRC No',
            'Customs Reference Code',
            'Complete Training No',
            'Valuation Training No',
            'AHTN Training No',
            'Graduation',
            'Address',
            'Phone Number',
            'Email',
            'Office Name',
            'Office Start Date',
            'Office Address',
            'Office Phone Number',
            'Office Fax',
            'Office Email',
            'Yellow Card',
            'Pink Card',
        ];
    }
}
