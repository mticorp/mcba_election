<?php

namespace App\Imports;

use App\Election;
use App\ElectionVoter;
use App\Voter;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;

class VoterImport implements ToModel,WithHeadingRow,WithValidation
{
    use Importable;
    private $currentRowNumber = 1;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {                        
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // generate a pin based on 2 * 7 digits + a random character
        $pin = $characters[rand(0, strlen($characters) - 1)] . mt_rand(10, 99) . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $pin_code = str_shuffle($pin);

        $current = Carbon::now();        
        $elections = Election::all();

        if($row['phone_number']){
            $phone_no = str_replace('-','',$row['phone_number']);
            $phone_no = str_replace(' ','',$phone_no);
            $split_phone_no = explode(',',$phone_no);
            $phone_pattern = "/09\d{7}/";
            
            foreach($split_phone_no as $key => $phone)
            {
                if(!preg_match($phone_pattern,$phone) || strlen($phone) > 11)
                {
                    unset($split_phone_no[$key]);
                }
            }

            if(count($split_phone_no) < 1)
            {
                $error = ['errors' => 'Invalid Phone at line - '.$this->currentRowNumber];
                $failures[] = new Failure($this->currentRowNumber, 'phone_no', $error, $row);

                throw new ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
            $phone_no = implode(',',$split_phone_no);
        }else{
            $error = ['errors' => 'Phone Number is required at line - '.$this->currentRowNumber];
            $failures[] = new Failure($this->currentRowNumber, 'phone_no', $error, $row);

            throw new ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }
        
        if($row['email']){
            $email = str_replace(' ','',$row['email']);
            $split_email = explode(',',$email);
            $email_pattern = "/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/";        
            foreach($split_email as $key => $email)
            {
                if(!preg_match($email_pattern,$email))
                {
                    unset($split_email[$key]);
                }
            }

            if(count($split_email) < 1)
            {
                $error = ['errors' => 'Invalid Email at line - '.$this->currentRowNumber];
                $failures[] = new Failure($this->currentRowNumber, 'email', $error, $row);

                throw new ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }

            $email = implode(',',$split_email);
        }else{
            $email = null;
        }
        
        $voter = Voter::create([
            'voter_id' => $pin_code,
            'name'  => $row['name'],
            'email'  => $email,
            'phone_no' => $phone_no,
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

        $this->currentRowNumber = $this->currentRowNumber + 1;
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
