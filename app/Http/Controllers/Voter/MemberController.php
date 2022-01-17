<?php

namespace App\Http\Controllers\Voter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Election;
use App\ElectionVoter;
use App\MRegister;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Image;
use App\Voter;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function register()
    {
        return view('member.register');
    }

    public function check(Request $request)
    {        
        // $validator = Validator::make($request->all(), [
        //     'refer_code' => 'required',
        //     'nrc_no' => 'required',
        //     'phone_no' => 'required',
        // ], [
        //     'refer_code.required' => 'Reference Code is required.',
        //     'nrc_no.required' => 'NRC is required.',
        //     'phone_no.required' => 'Phone Number is required.',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()->all()]);
        // }

        $refer_code = $request->refer_code;
        $nrc_no = $request->nrc_no;
        $phone_no = $request->phone_no;

        $member = MRegister::where('refer_code', $refer_code)->where('phone_number',$phone_no)->orWhere('nrc',$nrc_no)->first();

                
        if ($member) {
            if ($member->check_flag == 0) {
                $encrypted = Crypt::encryptString($member->id);
                return response()->json(['success' => 'Data Added successfully.', 'member_id' => $encrypted]);
            } else {                
                return response()->json(['errors' => ['Your are already Registered.']]);
            }
        } else {            
            return response()->json(['errors' => ['Invalid Information']]);
        }
    }

    public function refill($member_id)
    {
        $decrypted_member_id = Crypt::decryptString($member_id);
        $member = MRegister::where('id', $decrypted_member_id)->first();

        return view('member.refill-Form', compact('member'));
    }

    public function confirm(Request $request)
    {
        $image_name = $request->old_image;
        $image = $request->file('image');

        $error =  Validator::make($request->all(), [
            'refer_code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
        ], [
            'refer_code.required' => 'Reference Code ဖြည့်စွက်ရန်.',
            'name.required' => 'အမည် ဖြည့်စွက်ရန်.',
            'phone_number.required' => 'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း ဖြည့်စွက်ရန်.',              
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $member = MRegister::find($request->hidden_id);

        if($member->check_flag == 1)
        {
            return response()->json(['success' => 'You are already registered!']);
        }else{
            if ($image != '') {
                $oldpath = public_path() . $request->old_image;
    
                if ($request->old_image) {
                    if ($request->old_image == '/images/user.png') {
                    } else if (file_exists($oldpath)) {
                        unlink($oldpath);
                    }
                }
    
                $filename = rand() . '.jpg';
    
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(400, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('/upload/member/' . $filename));
                $image_name = '/upload/member/' . $filename;
            }
    
            $form_data = array(
                "refer_code" => $request->refer_code,
                "name"     => $request->name,
                "email"     => $request->email,
                "nrc"       => $request->nrc_no,
                "complete_training_no"   => $request->complete_training_no,
                "valuation_training_no"   => $request->valuation_training_no,
                "AHTN_training_no" => $request->AHTN_training_no,
                "graduation" => $request->graduation,
                "phone_number"  => $request->phone_number,
                "address" => $request->address,
                "profile" => $image_name,
                "check_flag" => 1,
                "officeName" => $request->officeName,
                "office_startDate" => $request->office_startDate,
                "officeAddress" => $request->officeAddress,
                "officePhone" => $request->officePhone,
                "officeFax" => $request->officeFax,
                "officeEmail" => $request->officeEmail,
                "yellowCard" => $request->yellowCard,
                "pinkCard" => $request->pinkCard,
            );
    
            MRegister::whereId($request->hidden_id)->update($form_data);
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // generate a pin based on 2 * 7 digits + a random character
            $pin = $characters[rand(0, strlen($characters) - 1)] . mt_rand(10, 99) . $characters[rand(0, strlen($characters) - 1)];
    
            // shuffle the result
            $pin_code = str_shuffle($pin);
            $voter = new Voter;
            $voter->voter_id = $pin_code;
            $voter->vote_count = 1;
            $voter->name = $request->name;
            $voter->email = $request->email;
            $voter->phone_no = $request->phone_number;
            $voter->save();
    
            $voter_data = Voter::where("voter_id", $pin_code)->first();
            DB::table('logs')->insert([
                'voter_id' => $voter_data->id,
            ]);
            $elections = Election::all();
            if (count($elections) > 0) {
                foreach ($elections as $election) {
                    $election_voter = new ElectionVoter();
                    $election_voter->election_id = $election->id;
                    $election_voter->voter_id = $voter_data->id;
                    $election_voter->save();
                }
            }
    
            return response()->json(['success' => 'Data is successfully updated']);
        }        
    }

    public function completeMessage()
    {
        return view('member.compelete-message');
    }
}
