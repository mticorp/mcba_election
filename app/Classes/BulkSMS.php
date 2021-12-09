<?php
namespace App\Classes;
class BulkSMS{
    public function sendSMS($mobileNumber,$message){
        // dd($mobileNumber);
        $isError = 0;
        $errorMessage = true;
        //Your message to send, Adding URL encoding.

        //Preparing post parameters
        $token = "KpgyfcKjibHEHccbCUX9uhrD";

        // Prepare data for POST request
        $data = [
            "to"        =>      $mobileNumber,
            "message"   =>      $message,
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
        // dd($info);
        if($info->status == "success")
        {
            if (curl_errno($ch)) {
                $isError = true;
                $errorMessage = curl_error($ch);
            }
            curl_close($ch);
            if($isError){
                // dd($errorMessage);
                return response()->json(['errors' => $errorMessage]);
            }else{
                return response()->json(['success' => 'Successfully Send!']);
            }
        }else{
            return response()->json(['errors' => 'SMS Send Failed.']);
        }
    }
}
?>
