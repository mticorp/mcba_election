<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Election;
use App\Favicon;
use App\Imports\ImportMember;
use App\Logo;
use App\MRegister;
use DB;
use Image;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Crypt;

class MRegisterController extends Controller
{
    public function __construct()
    {        
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = MRegister::latest()->get(['m_registers.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return datatables()->of($DT_data)
                ->addColumn('action', function ($DT_data) {
                    $button = '<button type="button" name="detail" id="' . $DT_data->id . '" class="detail btn btn-dark btn-xs btn-flat"><i class="fa fa-eye"></i> Detail</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="edit" id="' . $DT_data->id . '" class="edit btn btn-info btn-xs btn-flat"><i class="fas fa-edit"></i> Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $DT_data->id . '" class="delete btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mregister.index',compact('logo','favicon'));
    }

    public function create()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.mregister.create',compact('logo','favicon'));
    }

    public function store(Request $request)
    {
        $error =  Validator::make($request->all(), [
            'refer_code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'nrc' => 'required|max:255',
            'email' => 'email|nullable',
            'officeEmail' => 'email|nullable',
        ],[
            'refer_code.required'=>'Reference Code ဖြည့်စွက်ရန်.',
            'name.required'=>'အမည် ဖြည့်စွက်ရန်.',
            'phone_number.required'=>'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း ဖြည့်စွက်ရန်.',
            'nrc.required'=>'နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
            'email.email' => "Email is Invaild Format",
            'officeEmail.email' => "Email is Invaild Format",
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->hasfile('image'))
        {
            $image = $request->file('image');
            $filename = rand() . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/member/' . $filename));
            $new_name = '/upload/member/' . $filename;
        }else{
            $new_name = '/images/user.png';
        }
        $form_data = array(
            "refer_code" => $request->refer_code,
            "name"     => $request->name,
            "email"     => $request->email,
            "nrc"       => $request->nrc,
            "complete_training_no"   => $request->complete_training_no,
            "valuation_training_no"   => $request->valuation_training_no,
            "AHTN_training_no" => $request->AHTN_training_no,
            "graduation"=> $request->graduation,
            "phone_number"  => $request->phone_number,
            "address" => $request->address,
            "election_id" => $request->election_id,
            "profile" => $new_name,
            "officeName" => $request->officeName,
            "office_startDate" => $request->office_startDate,
            "officeAddress" => $request->officeAddress,
            "officePhone" => $request->officePhone,
            "officeFax" => $request->officeFax,
            "officeEmail" => $request->officeEmail,
            "yellowCard" => $request->yellowCard,
            "pinkCard" => $request->pinkCard,
        );

        MRegister::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    public function detail($candidate_id)
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $member = MRegister::find($candidate_id);
        if ($member != null) {
            return view('admin.mregister.detail', compact('member','logo','favicon'));
        } else {
            return abort(404);
        }
    }

    public function edit($member_id)
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        $member = MRegister::find($member_id);
        if ($member != null) {
            return view('admin.mregister.edit', compact('member','logo','favicon'));
        } else {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $image_name = $request->old_image;
        $image = $request->file('image');

        $error =  Validator::make($request->all(), [
            'refer_code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'nrc' => 'required|max:255',
            'email' => 'email|nullable',
            'officeEmail' => 'email|nullable',
        ],[
            'refer_code.required'=>'Reference Code ဖြည့်စွက်ရန်.',
            'name.required'=>'အမည် ဖြည့်စွက်ရန်.',
            'phone_number.required'=>'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း ဖြည့်စွက်ရန်.',
            'nrc.required'=>'နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
            'email.email' => "Email is Invaild Format",
            'officeEmail.email' => "Email is Invaild Format",
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if ($image != '') {

            $oldpath = public_path() . $request->old_image;

            if($request->old_image)
            {
                if($request->old_image == '/images/user.png')
                {

                }else if (file_exists($oldpath)) {
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
            "nrc"       => $request->nrc,
            "complete_training_no"   => $request->complete_training_no,
            "valuation_training_no"   => $request->valuation_training_no,
            "AHTN_training_no" => $request->AHTN_training_no,
            "graduation"=> $request->graduation,
            "phone_number"  => $request->phone_number,
            "address" => $request->address,
            "profile" => $image_name,
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

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($member_id)
    {
        $data = DB::table('m_registers')->where('id',$member_id)->first();

        $path = public_path(). $data->profile;

        if($data->profile)
        {
            if($data->profile == '/images/user.png')
            {

            }else if (file_exists($path)) {
                unlink($path);
            }
        }

        DB::table('m_registers')->where('id',$member_id)->delete();

        $nonedata = DB::table('m_registers')->count() == 0 ? true : false;

        if ($nonedata) {
            DB::table('m_registers')->truncate();
        } else {
            $last_id = MRegister::latest()->first()->id + 1;
            DB::statement("ALTER TABLE `m_registers` AUTO_INCREMENT = $last_id");
        }

        return redirect()->back();
    }

    public function excelImport()
    {
        
        $logo = Logo::first();
        $favicon = Favicon::first();
        return view('admin.mregister.import-excel',compact('logo','favicon'));
    }

    public function Import(Request $request)
    {
        // dd($request->all());
        if($request->ajax())
        {
            $validator = Validator::make([
                    'file'      => $request->file,
                    'extension' => strtolower($request->file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            if ($request->hasFile('file')) {
                // dd('hi');
                Excel::import(new ImportMember(), $request->file('file'));
                return response()->json(['success' => 'Data Added Successfully']);
            }else{
                return response()->json(['errors' => 'Excel File is Required!']);
            }
        }else{
            return abort(404);
        }
    }

    public function export()
    {
        $file_path = public_path() . '/upload/Member_List_template.xlsx';
        // dd($file_path);
        if (file_exists($file_path))
        {
            return response()->download($file_path);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function sendMessage(Request $request)
    {
        $errors = [];
        if($request->check_val)
        {
            foreach($request->check_val as $member_id)
            {
                $member = DB::table('m_registers')->where('id', $member_id)->first();
                $url = route('vote.member.register');

                $phone  = $member->phone_number;
                $email = $member->email;

                if($phone)
                {
                    $phone_content = "(စမ်းသပ်ခြင်း)မင်္ဂလာ! အောက်ဖော်ပြပါ Link အားနှိပ်၍ မန်ဘာဒေတာအား စစ်ဆေးနိုင်ပါပြီ ".$url." 
                    တစ်စုံတစ်ရာအခက်အခဲရှိပါက 09767629043 - Aye Aye Aung ( Technical Project Manager ) သို့ဆက်သွယ်မေးမြန်းနိုင်ပါသည်။";

                    $token = "KpgyfcKjibHEHccbCUX9uhrD";

                    // Prepare data for POST request
                    $data = [
                        "to"        =>      $phone,
                        "message"   =>      $phone_content,
                        "sender"    =>      "MCBA"
                    ];

                    $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ]);

                    $result = curl_exec($ch);
                    $info = json_decode($result);
                    if($info->status == "success")
                    {

                    }else{
                        array_push($errors,[
                            $member->name.' SMS Send Fail'
                        ]);
                    }
                }else{
                    array_push($errors,[
                        $member->name.' Phone is Empty'
                    ]);
                }

                if($email)
                {
                    $content = "(စမ်းသပ်ခြင်း)မင်္ဂလာ! အောက်ဖော်ပြပါ Button အားနှိပ်၍ မန်ဘာဒေတာအား စစ်ဆေးနိုင်ပါပြီ                    
                    တစ်စုံတစ်ရာအခက်အခဲရှိပါက 09767629043 - Aye Aye Aung ( Technical Project Manager ) သို့ဆက်သွယ်မေးမြန်းနိုင်ပါသည်";

                    $time = Carbon::now();
                    $datetime = $time->toDateTimeString();
                    $DT = explode(' ', $datetime);
                    $image = public_path().'/images/mti_logo.png';

                    Mail::send(
                        'vid_email',
                        array(
                            'link' => $url,
                            'image' => $image,
                            'content' => $content,
                            'date' => $DT[0],
                            'time' => $DT[1],
                        ),
                        function ($message) use ($email) {
                            $message->from('evoting.mti@gmail.com');
                            $message->subject('MTI - Election Voting System');
                            $message->to($email);
                        }
                    );

                    if (Mail::failures()) {
                        array_push($errors,[
                            $member->name.' Mail Send Fail'
                        ]);
                    }
                }else{
                    array_push($errors,[
                        $member->name.' Mail is Empty',
                    ]);
                }
            }

            if($errors)
            {
                return response()->json(['errors' => $errors]);
            }else{
                return response()->json(['success' => 'Mail and SMS Send Successfully.']);
            }
        }else{
            array_push($errors,[
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function smsMessageOnly(Request $request)
    {
        $errors = [];
        if($request->check_val)
        {
            foreach($request->check_val as $member_id)
            {
                $member = DB::table('m_registers')->where('id', $member_id)->first();
                $url = route('vote.member.register');

                $phone  = $member->phone_number;

                if($phone)
                {
                    // $phone_content = "မင်္ဂလာပါ! အတွက် အောက်ဖော်ပြပါ Link အားနှိပ်၍ ဝင်ရောက် Register လုပ်နိုင်ပါပြီ။ " . $url;
                    $phone_content = "(စမ်းသပ်ခြင်း)မင်္ဂလာ! အောက်ဖော်ပြပါ Link အားနှိပ်၍ မန်ဘာဒေတာအား စစ်ဆေးနိုင်ပါပြီ ".$url." 
                    တစ်စုံတစ်ရာအခက်အခဲရှိပါက 09767629043 - Aye Aye Aung ( Technical Project Manager ) သို့ဆက်သွယ်မေးမြန်းနိုင်ပါသည်။";

                    $token = "KpgyfcKjibHEHccbCUX9uhrD";

                    // Prepare data for POST request
                    $data = [
                        "to"        =>      $phone,
                        "message"   =>      $phone_content,
                        "sender"    =>      "MCBA"
                    ];

                    $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ]);

                    $result = curl_exec($ch);
                    $info = json_decode($result);
                    if($info->status == "success")
                    {

                    }else{
                        array_push($errors,[
                            $member->name.' SMS Send Fail'
                        ]);
                    }
                }else{
                    array_push($errors,[
                        $member->name.' Phone is Empty'
                    ]);
                }
            }

            if($errors)
            {
                return response()->json(['errors' => $errors]);
            }else{
                return response()->json(['success' => 'SMS Send Successfully.']);
            }
        }else{
            array_push($errors,[
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function emailMessageOnly(Request $request)
    {
        $errors = [];
        if($request->check_val)
        {
            foreach($request->check_val as $member_id)
            {
                $member = DB::table('m_registers')->where('id', $member_id)->first();
                $url = route('vote.member.register');
                $email = $member->email;

                if($email)
                {
                    // $content = "မင်္ဂလာပါ! အောက်ဖော်ပြပါ Button အားနှိပ်၍ ဝင်ရောက် Register လုပ်နိုင်ပါပြီ။";
                    $content = "(စမ်းသပ်ခြင်း)မင်္ဂလာ! အောက်ဖော်ပြပါ Button အားနှိပ်၍ မန်ဘာဒေတာအား စစ်ဆေးနိုင်ပါပြီ                    
                    တစ်စုံတစ်ရာအခက်အခဲရှိပါက 09767629043 - Aye Aye Aung ( Technical Project Manager ) သို့ဆက်သွယ်မေးမြန်းနိုင်ပါသည်";

                    $time = Carbon::now();
                    $datetime = $time->toDateTimeString();
                    $DT = explode(' ', $datetime);
                    $image = public_path().'/images/mti_logo.png';

                    Mail::send(
                        'vid_email',
                        array(
                            'link' => $url,
                            'image' => $image,
                            'content' => $content,
                            'date' => $DT[0],
                            'time' => $DT[1],
                        ),
                        function ($message) use ($email) {
                            $message->from('helper.mti.evoting.mm@gmail.com');
                            $message->subject('MTI - Election Voting System');
                            $message->to($email);
                        }
                    );

                    if (Mail::failures()) {
                        array_push($errors,[
                            $member->name.' Mail Send Fail'
                        ]);
                    }
                }else{
                    array_push($errors,[
                        $member->name.' Mail is Empty',
                    ]);
                }
            }

            if($errors)
            {
                return response()->json(['errors' => $errors]);
            }else{
                return response()->json(['success' => 'Email Send Successfully.']);
            }
        }else{
            array_push($errors,[
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }
}
