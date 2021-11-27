<?php

namespace App\Imports;

use App\Election;
use App\ElectionVoter;
use App\Voter;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VoterImport implements ToModel,WithHeadingRow,WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        // dd($row);
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // generate a pin based on 2 * 7 digits + a random character
        $pin = $characters[rand(0, strlen($characters) - 1)] . mt_rand(10, 99) . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $pin_code = str_shuffle($pin);

        $current = Carbon::now();
        // dd($row);
        $elections = Election::all();

        $voter = Voter::create([
            'voter_id' => $pin_code,
            'name'  => $row['name'],
            'email'  => $row['email'],
            'phone_no' => $row['phone_number'],
            'vote_count'  => $row['vote_count'],
            'created_at' => $current,
            'updated_at' => $current,
        ]);

        if(count($elections) > 0)
        {
            foreach($elections as $election)
            {
                $election_voter = new ElectionVoter();
                $election_voter->election_id = $election->id;
                $election_voter->voter_id = $voter->id;
                $election_voter->save();
            }
        }

        DB::table('logs')->insert([
            'voter_id' => $voter->id,                        
        ]);

        return $voter;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'nullable',                   
            'vote_count' => 'required|numeric',
        ];
    }
}
