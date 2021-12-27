<?php

namespace App;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class VotingRecordExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    
        return Voting::List();
    }

    public function headings(): array
    {   
        return [
            'NO',
            'Electioin Name',
            'Candidate Name',
            'voter ID',
            'Voting Date/Time'

        ];

    }
}