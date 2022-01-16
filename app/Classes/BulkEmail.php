<?php
namespace App\Classes;

use App\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class BulkEmail{
    public static function sendEmail($emails,$voter,$type = null,$url = null){
       
        $setting = Setting::first();

        if($voter)
        {
            $url != null ? $url : $url = route('vote.link', ['voter_id' => $voter->voter_id]);

            if($type == 'reminder')
            {
                $content = ($setting->reminder_text == null) ? Lang::get('message.reminder') :
                str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name, "(" . $voter->vote_count . ")"], $setting->reminder_text);
            } else if ($type == 'member_announce') {
                $content = ($setting->member_annouce == null) ? Lang::get('message.annouce') :
                    str_replace(['[:MemberName]', '[:ShareCount]'], [$voter->name], $setting->member_annouce);
            } else if ($type == 'voter_announce') {
                $content = ($setting->voter_annouce == null) ? Lang::get('message.annouce') :
                    str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name], $setting->voter_annouce);
            } else if($type == 'member'){
                $content = ($setting->member_sms_text == null) ? Lang::get('message.member') .$url. Lang::get('message.contact'):
                str_replace('[:MemberName]', $voter->name, $setting->member_sms_text) . $url;
            }else{
                $content = ($setting->sms_text == null) ? Lang::get('message.text') :
                str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name, "(" . $voter->vote_count . ")"], $setting->sms_text);
            }

            $time = Carbon::now();
            $datetime = $time->toDateTimeString();
            $DT = explode(' ', $datetime);
            $image = public_path() . '/images/mti_logo.png';
        }else{
            return response()->json(['errors' => $type == "member" ? 'Member' : 'Voter'.' Not Found!']);
        }        

        if(count($emails) > 1)
        {
            foreach($emails as $email)
            {
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
                        $message->from(env('MAIL_FROM_ADDRES'));
                        $message->subject('MCBA E-Voting');
                        $message->to($email);
                    }
                );
            }
        }else{
            Mail::send(
                'vid_email',
                array(
                    'link' => $url,
                    'image' => $image,
                    'content' => $content,
                    'date' => $DT[0],
                    'time' => $DT[1],
                ),
                function ($message) use ($emails) {
                    $message->from(env('MAIL_FROM_ADDRES'));
                    $message->subject('MCBA E-Voting');
                    $message->to($emails[0]);
                }
            );
        }        

        if (Mail::failures()) {
            return response()->json(['errors' => 'Email Send Failed.']);
        } else {
            return response()->json(['success' => 'Message Send Successfully.']);
        }
    }
}
