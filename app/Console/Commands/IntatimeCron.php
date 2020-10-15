<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

use App\UsersModel;
use App\ProfileModel;
use App\JobModel;
use App\NotificationModel;
use App\InterviewListModel;

use Mail;
use App\Mail\JobMail;
use Twilio\Rest\Client;

class IntatimeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intatime:cron';

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
        $week = date("w")-1;
        $hr = date("H");
        InterviewListModel::where('week',$week)->where('settime',$hr)->update(['checkflag'=>1]);
        InterviewListModel::where('week',"<>",$week)->where('settime',"<>",$hr)->update(['checkflag'=>0]);
        $users = InterviewListModel::where('week',$week)->where('settime',$hr)->select('client','provider')->get();
        $message = "Now you need to go to interview.";
        $data = array(
            'subject' => "Interview Time",
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
            $not->title = "Interview Time";
            $not->description = "Now you need to go to interview";
            $not->specific = $client[$i];
            $not->save();
        }
        for($i = 0;$i < count($provider); $i ++){
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Time";
            $not->description = "Now you need to go to interview";
            $not->specific = $provider[$i];
            $not->save();
        }
    }
}
