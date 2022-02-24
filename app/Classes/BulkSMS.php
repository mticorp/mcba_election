<?php

namespace App\Classes;

use Illuminate\Support\Facades\Lang;

class BulkSMS
{
    public static function sendSMS($mobileNumber, $voter, $type = null, $url = null, $setting)
    {

        if ($setting) {
            if ($setting->sms_token != null && $setting->sms_token != '') {
                $token = $setting->sms_token;
            } else {
                $token = "KpgyfcKjibHEHccbCUX9uhrD";
            }

            if ($setting->sms_sender != null && $setting->sms_sender != '') {
                $sms_sender = $setting->sms_sender;
            } else {
                $sms_sender = "MCBA";
            }
        }

        if ($voter) {
            $url != null ? $url : $url = route('vote.link', ['voter_id' => $voter->voter_id]);

            if ($type == 'reminder') {
                $message = ($setting->reminder_text == null) ? Lang::get('message.reminder') . $url :
                str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name, "(" . $voter->vote_count . ")"], $setting->reminder_text) . $url;
            } else if ($type == 'member_announce') {
                $message = ($setting->member_annouce == null) ? Lang::get('message.annouce') :
                str_replace(['[:MemberName]', '[:ShareCount]'], [$voter->name], $setting->member_annouce);
            } else if ($type == 'voter_announce') {
                $message = ($setting->voter_annouce == null) ? Lang::get('message.annouce') :
                str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name], $setting->voter_annouce);
            } else if ($type == 'member') {
                $message = ($setting->member_sms_text == null) ? Lang::get('message.member') . $url . " \n" . Lang::get('message.contact') :
                str_replace('[:MemberName]', $voter->name, $setting->member_sms_text) . $url;
            } else if ($type == 'otp') {
                $message = $url;
            } else {
                $message = ($setting->sms_text == null) ? Lang::get('message.text') . $url :
                str_replace(['[:VoterName]', '[:ShareCount]'], [$voter->name, "(" . $voter->vote_count . ")"], $setting->sms_text) . $url;
            }
        } else {
            return response()->json(['errors' => $type == "member" ? 'Member' : 'Voter' . ' Not Found!']);
        }

        $isError = 0;
        $errorMessage = true;
        if (count($mobileNumber) > 1) {
            $response = "";
            foreach ($mobileNumber as $phone) {
                $phone = str_replace("-", "", $phone);
                $phone = str_replace(" ", "", $phone);
                // Prepare data for POST request
                $data = [
                    "to" => $phone,
                    "message" => $message,
                    "sender" => $sms_sender,
                ];

                $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                ]);

                $result = curl_exec($ch);
                $info = json_decode($result);
                if ($info->status == "success") {
                    if (curl_errno($ch)) {
                        $isError = true;
                        $errorMessage = curl_error($ch);
                    }
                    curl_close($ch);
                    if ($isError) {
                        return response()->json(['errors' => $errorMessage]);
                    } else {
                        $response .= $phone . " ";
                    }
                }
            }
            return response()->json(['success' => $response . "Successfully Send!"]);
        } else {
            $phone = str_replace("-", "", $mobileNumber[0]);
            $phone = str_replace(" ", "", $phone);
            // Prepare data for POST request
            $data = [
                "to" => $phone,
                "message" => $message,
                "sender" => $sms_sender,
            ];

            $ch = curl_init("http://159.138.135.30/smsserver/sendsms-token");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ]);

            $result = curl_exec($ch);
            $info = json_decode($result);
            // dd($info);
            if ($info->status == "success") {
                if (curl_errno($ch)) {
                    $isError = true;
                    $errorMessage = curl_error($ch);
                }
                curl_close($ch);
                if ($isError) {
                    return response()->json(['errors' => $errorMessage]);
                } else {
                    return response()->json(['success' => 'Successfully Send!']);
                }
            } else {
                return response()->json(['errors' => 'SMS Send Failed.']);
            }
        }
    }
}
