<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

use App\UsersModel;
use App\ProfileModel;
use App\JobModel;
use App\NotificationModel;
use Mail;
use App\Mail\JobMail;
use Twilio\Rest\Client;

class JobfinishedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobfinished:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
        $this->twilio_account_sid = env("TWILIO_ACCOUNT_SID");
        $this->twilio_auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->twclient = new Client($this->twilio_account_sid, $this->twilio_auth_token);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $lastDate = date("Y-m-d H:i:s");
        $users = JobModel::where('status',1)->where('end','<',$lastDate)->select('client','provider')->get();
        $message = "Your Job is expired so you can leave some reviews and also start new service.";
        $data = array(
            'subject' => "Your Job is expired",
            'user' =>"Support",
            'message' => $message
        );
        $client = [];
        $provider = [];
        for($i = 0;$i < count($users); $i ++){
            $client[$i] = $users[$i]->client;
            $provider[$i] = $users[$i]->provider;
        }
        $pPhone = ProfileModel::whereIn('userid',$provider)->select('phone')->get();
        $pEmail = UsersModel::whereIn('id',$provider)->select('email')->get();
        $cPhone = ProfileModel::whereIn('userid',$client)->select('phone')->get();
        $cEmail = UsersModel::whereIn('id',$client)->select('email')->get();

        for($i = 0;$i < count($pPhone); $i ++){
            $this->twclient->messages->create($pPhone[$i]['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
        }
        for($i = 0;$i < count($pEmail); $i ++){
            Mail::to($pEmail[$i]['email'])->send(new JobMail($data));
        }
        for($i = 0;$i < count($cPhone); $i ++){
            $this->twclient->messages->create($cPhone[$i]['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
        }
        for($i = 0;$i < count($cEmail); $i ++){
            Mail::to($cEmail[$i]['email'])->send(new JobMail($data));
        }   
        for($i = 0;$i < count($client); $i ++){
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your Job is expired";
            $not->description = "Your Job is expired so you can leave some reviews and also start new service.";
            $not->specific = $client[$i];
            $not->save();
        }
        for($i = 0;$i < count($provider); $i ++){
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your Job is expired";
            $not->description = "Your Job is expired so you can leave some reviews and also start new service.";
            $not->specific = $provider[$i];
            $not->save();
        }  
        JobModel::where('status',1)->where('end','<',$lastDate)->update(['status'=>2]);

    }
}
