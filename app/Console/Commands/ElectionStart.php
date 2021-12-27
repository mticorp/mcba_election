<?php

namespace App\Console\Commands;

use App\Election;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ElectionStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:start {election_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command used to start Election';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $election_id = $this->argument('election_id');
        $election = Election::find($election_id);
        $time = Carbon::now();

        if(Carbon::parse($election->duration_from)->format('Y-m-d h:i') == Carbon::now()->format('Y-m-d h:i'))
        {
            $election->status = 1;
            $election->start_time = Carbon::parse($time)->format("Y-m-d h:i:s");
            if($election->update())
            {
                $noti_text = "Starting";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "notification":{
                    "title": "'.$election->name.'",
                      "body": "'.$noti_text.'",
                    },
                    "badge": 1,
                    "to": "/topics/status"
                  }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: key=AAAAyVw-fEQ:APA91bHI41bZLzLDf77zoetoChlCd5u6q_iy1is-j5SqHZ96yrCibVflU8HGd48dYECPPBVlbh6lQyU5OL5nIFv8zg0E-ufH3L2dRKttH_frUHVjT5EWD1z_rT2rRVG_rZUZOHL0M9Sz ',
                    'Content-Type: application/json'
                ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Log::info("$election->name Election Started Successfully");
            }else{
                Log::error("$election->name Election Start Failed!");;
            }
        }
    }
}
