<?php

namespace App\Http\Controllers\Admin;

use App\Classes\BulkEmail;
use App\Classes\BulkSMS;
use App\Election;
use App\ElectionVoter;
use App\Exports\ExportMemberList;
use App\Http\Controllers\Controller;
use App\MRegister;
use App\Setting;
use App\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
// use Stichoza\GoogleTranslate\GoogleTranslate;
use Maatwebsite\Excel\Facades\Excel;

class MRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->url = route('vote.member.register');
    }

    public function index()
    {
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $DT_data = MRegister::orderBy('check_flag', 'DESC')->get(['m_registers.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
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

        return view('admin.mregister.index');
    }

    public function create()
    {
        return view('admin.mregister.create');
    }

    public function store(Request $request)
    {
        $error = Validator::make($request->all(), [
            'refer_code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'nrc' => 'required|max:255',
        ], [
            'refer_code.required' => 'Reference Code ဖြည့်စွက်ရန်.',
            'name.required' => 'အမည် ဖြည့်စွက်ရန်.',
            'phone_number.required' => 'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း ဖြည့်စွက်ရန်.',
            'nrc.required' => 'နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $filename = rand() . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(400, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('/upload/member/' . $filename));
            $new_name = '/upload/member/' . $filename;
        } else {
            $new_name = '/images/user.png';
        }

        $form_data = array(
            "refer_code" => $request->refer_code,
            "name" => $request->name,
            "email" => $request->email,
            "nrc" => $request->nrc,
            "complete_training_no" => $request->complete_training_no,
            "valuation_training_no" => $request->valuation_training_no,
            "AHTN_training_no" => $request->AHTN_training_no,
            "graduation" => $request->graduation,
            "phone_number" => $request->phone_number,
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
        $member = MRegister::find($candidate_id);
        if ($member != null) {
            return view('admin.mregister.detail', compact('member'));
        } else {
            return abort(404);
        }
    }

    public function edit($member_id)
    {
        $member = MRegister::find($member_id);
        if ($member != null) {
            return view('admin.mregister.edit', compact('member'));
        } else {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $image_name = $request->old_image;
        $image = $request->file('image');

        $error = Validator::make($request->all(), [
            'refer_code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'nrc' => 'required|max:255',
        ], [
            'refer_code.required' => 'Reference Code ဖြည့်စွက်ရန်.',
            'name.required' => 'အမည် ဖြည့်စွက်ရန်.',
            'phone_number.required' => 'ဖုန်းနံပါတ်၊ ဖက်စ်နံပါတ်၊ မိုလ်ဘိုင်းဖုန်း ဖြည့်စွက်ရန်.',
            'nrc.required' => 'နိုင်ငံသားစီစစ်ရေး / အမျိုးသားမှတ်ပုံတင်အမှတ် ဖြည့်စွက်ရန်.',
        ]);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

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
            "name" => $request->name,
            "email" => $request->email,
            "nrc" => $request->nrc,
            "complete_training_no" => $request->complete_training_no,
            "valuation_training_no" => $request->valuation_training_no,
            "AHTN_training_no" => $request->AHTN_training_no,
            "graduation" => $request->graduation,
            "phone_number" => $request->phone_number,
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
        $data = DB::table('m_registers')->where('id', $member_id)->first();

        $path = public_path() . $data->profile;

        if ($data->profile) {
            if ($data->profile == '/images/user.png') {
            } else if (file_exists($path)) {
                unlink($path);
            }
        }

        DB::table('m_registers')->where('id', $member_id)->delete();

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
        return view('admin.mregister.import-excel');
    }

    public function Import(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'data' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            foreach (json_decode($request->data) as $row) {
                if (!isset($row->{'Profile'})) {
                    $profile = '/images/user.png';
                } else {
                    $profile = isset($row->{'Profile'}) ? $row->{'Profile'} : '';
                }

                MRegister::create([
                    'profile' => $profile,
                    'name' => isset($row->{'Name'}) ? $row->{'Name'} : '',
                    'nrc' => isset($row->{'NRC No'}) ? $row->{'NRC No'} : '',
                    'refer_code' => isset($row->{'Customs Reference Code'}) ? $row->{'Customs Reference Code'} : '',
                    'complete_training_no' => isset($row->{'Complete Training No'}) ? $row->{'Complete Training No'} : '',
                    'valuation_training_no' => isset($row->{'Valuation Training No'}) ? $row->{'Valuation Training No'} : '',
                    'AHTN_training_no' => isset($row->{'AHTN Training No'}) ? $row->{'AHTN Training No'} : '',
                    'graduation' => isset($row->{'Graduation'}) ? $row->{'Graduation'} : '',
                    'address' => isset($row->{'Address'}) ? $row->{'Address'} : '',
                    'phone_number' => isset($row->{'Phone Number'}) ? $row->{'Phone Number'} : '',
                    'email' => isset($row->{'Email'}) ? $row->{'Email'} : '',
                    'officeName' => isset($row->{'Office Name'}) ? $row->{'Office Name'} : '',
                    'office_startDate' => isset($row->{'Office Start Date'}) ? $row->{'Office Start Date'} : '',
                    'officeAddress' => isset($row->{'Office Address'}) ? $row->{'Office Address'} : '',
                    'officePhone' => isset($row->{'Office Phone Number'}) ? $row->{'Office Phone Number'} : '',
                    'officeFax' => isset($row->{'Office Fax'}) ? $row->{'Office Fax'} : '',
                    'officeEmail' => isset($row->{'Office Email'}) ? $row->{'Office Email'} : '',
                    'yellowCard' => isset($row->{'Yellow Card'}) ? $row->{'Yellow Card'} : '',
                    'pinkCard' => isset($row->{'Pink Card'}) ? $row->{'Pink Card'} : '',
                ]);
            }

            return response()->json(['success' => "Successfully Imported!"]);
        } else {
            return abort(404);
        }
    }

    public function export()
    {

        return Excel::download(new ExportMemberList(), 'Member_List.xlsx');
    }

    public function sendMessage(Request $request)
    {
        $setting = Setting::first();
        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_message' || $request->type == 'all_message') {
                $type = 'member';
            } else {
                $type = 'member_announce';
            }
            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $member_id) {
                    $member = DB::table('m_registers')->where('id', $member_id)->first();

                    $phone = $member->phone_number;
                    $email = $member->email;

                    if ($phone) {
                        $phones = explode(',', $phone);
                        if ($type == 'member') {
                            $result = BulkSMS::sendSMS($phones, $member, $type, $this->url, $setting);
                        } else {
                            $result = BulkSMS::sendSMS($phones, $member, $type, ' ', $setting);
                        }
                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $member->name . ' SMS Send Fail',
                            ]);
                        }
                    } else {
                        array_push($errors, [
                            $member->name . ' Phone is Empty',
                        ]);
                    }

                    if ($email) {
                        $emails = explode(',', $email);

                        if ($type == 'member') {
                            $result = BulkEmail::sendEmail($emails, $member, $type, $this->url);
                        } else {
                            $result = BulkEmail::sendEmail($emails, $member, $type, ' ');
                        }

                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $member->name . ' Mail Send Fail',
                            ]);
                        }
                    } else {
                        array_push($errors, [
                            $member->name . ' Mail is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'Mail and SMS Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function smsMessageOnly(Request $request)
    {

        $setting = Setting::first();

        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_message' || $request->type == 'all_message') {
                $type = 'member';
            } else {
                $type = 'member_announce';
            }
            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $member_id) {
                    $member = DB::table('m_registers')->where('id', $member_id)->first();

                    $phone = $member->phone_number;

                    if ($phone) {
                        $phones = explode(',', $phone);

                        if ($type == 'member') {
                            $result = BulkSMS::sendSMS($phones, $member, $type, $this->url, $setting);
                        } else {
                            $result = BulkSMS::sendSMS($phones, $member, $type, ' ', $setting);
                        }
                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $member->name . ' SMS Send Fail',
                            ]);
                        }
                    } else {
                        array_push($errors, [
                            $member->name . ' Phone is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'SMS Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function emailMessageOnly(Request $request)
    {
        $errors = [];
        if ($request->check_val) {
            if ($request->type == 'select_message' || $request->type == 'all_message') {
                $type = 'member';
            } else {
                $type = 'member_announce';
            }
            $collection = collect($request->check_val);
            foreach ($collection->chunk(100) as $data) {
                foreach ($data as $member_id) {
                    $member = DB::table('m_registers')->where('id', $member_id)->first();
                    $email = $member->email;

                    if ($email) {
                        $emails = explode(',', $email);

                        if ($type == 'member') {
                            $result = BulkEmail::sendEmail($emails, $member, $type, $this->url);
                        } else {
                            $result = BulkEmail::sendEmail($emails, $member, $type, ' ');
                        }

                        if (isset($result->getData()->errors)) {
                            array_push($errors, [
                                $member->name . ' Mail Send Fail',
                            ]);
                        }
                    } else {
                        array_push($errors, [
                            $member->name . ' Mail is Empty',
                        ]);
                    }
                }
            }

            if ($errors) {
                return response()->json(['errors' => $errors]);
            } else {
                return response()->json(['success' => 'Email Send Successfully.']);
            }
        } else {
            array_push($errors, [
                'No Data Avaliable!',
            ]);

            return response()->json(['errors' => $errors]);
        }
    }

    public function generateVID(Request $request)
    {
        $request->validate([
            'check_val' => 'required|array|min:1',
        ]);

        $collection = collect($request->check_val);
        $already = [];
        foreach ($collection->chunk(100) as $data) {
            foreach ($data as $val) {
                // MRegister::whereId($val)->update(['check_flag' => 1]);
                $member = MRegister::find($val);
                if ($member && $member->check_flag == 0) {
                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                    $pin = $characters[rand(0, strlen($characters) - 1)] . mt_rand(10, 99) . $characters[rand(0, strlen($characters) - 1)];

                    // shuffle the result
                    $pin_code = str_shuffle($pin);
                    $voter = new Voter();
                    $voter->voter_id = $pin_code;
                    $voter->vote_count = 1;
                    $voter->name = $member->name;
                    $voter->email = $member->email;
                    $voter->phone_no = $member->phone_number;
                    $voter->save();

                    $member->update(['check_flag' => 1]);

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
                } else {
                    $already['response'] = 'Already Generated Data are not going to generate again!';
                }
            }
        }

        if (count($already) > 0) {
            return response()->json(['success' => 'Successfully Generated (Already Generated Data are not going to generate again!)']);
        } else {
            return response()->json(['success' => 'Successfully Generated']);
        }
    }

    public function DownloadTemplateExcel()
    {
        $file_path = public_path() . '/upload/Member_List_Download_Template.xlsx';
        if (file_exists($file_path)) {
            return response()->download($file_path);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }
}
