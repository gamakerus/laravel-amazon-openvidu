<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UsersModel;
use App\VcodeModel;
use App\ProfileModel;
use App\AvailabletimeDayModel;
use App\AvailabledayWeekModel;
use App\ServiceTimeModel;
use App\LicenseModel;
use App\ServiceModel;
use App\ExspecModel;
use App\JobModel;
use App\ReasonModel;
use App\NotificationModel;
use App\TransactionModel;
use App\CancelrefundModel;
use App\ReviewModel;

use Mail;
use App\Mail\JobMail;
use DB;
use Google_Client;
use Twilio\Rest\Client;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Account;
use Stripe\AccountLink;

use Stripe\Token;
class JobController extends Controller
{
    public function __construct()
    {
        $this->twilio_account_sid = env("TWILIO_ACCOUNT_SID");
        $this->twilio_auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->twclient = new Client($this->twilio_account_sid, $this->twilio_auth_token);

        $client_ID = env("GOOGLE_CLIENT_ID");
        $this->social = new Google_Client(['client_id' => $client_ID]);
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    public function chosenlist(Request $request){
        $result = ProfileModel::where('userid',$request->id)->first();
        if($result){
            $serviceactivity = json_decode($result->serviceactivity);
            $exspec = json_decode($result->exspec);
            $license = json_decode($result->license);
            $activitieslist = ServiceModel::whereIn('id',$serviceactivity)->select('id','name')->get();
            $exspeclist = ExspecModel::whereIn('id',$exspec)->select('id','name')->get();
            $licenselist = LicenseModel::whereIn('id',$license)->select('id','name')->get();
            $stime = ServiceTimeModel::where('status',1)->orderBy('id')->get();
            $daytoweek = AvailabledayWeekModel::where('status',1)->orderBy('id')->get();
            $timetoday = AvailabletimeDayModel::where('status',1)->orderBy('id')->get();
            return array('exspec'=>$exspeclist,'license'=>$licenselist,'service'=>$activitieslist,'stime'=>$stime,'daytoweek'=>$daytoweek,'timetoday'=>$timetoday);
        }
    }
    public function setCost(Request $request){
        request()->validate([
            'data' => 'required'
            ]);
        $data = $request->all();
        $price = ProfileModel::where('userid',$data['data']['userId'])->select("price")->first();
        $from_date = strtotime($data['data']['startdate']);
        $to_date = strtotime($data['data']['enddate']);
        $day_diff = $to_date - $from_date;
        $days = floor($day_diff/(60*60*24))+1;
        $realdays = $days;
        if($data['data']['exclude']){
            $excludeday = 0;
            for($i = 0; $i < count($data['data']['daysSelected']);$i++){
                if(strtotime($data['data']['daysSelected'][$i]) >= $from_date && strtotime($data['data']['daysSelected'][$i]) <= $to_date){
                    $excludeday++;
                }
            }
            $realdays = $days - $excludeday;
        }
        if($data['data']['selectedHour']['live_in']){
            $totalhour = $realdays*24;
        }
        else{
            $totalhour = $realdays*($data['data']['selectedHour']['end']-$data['data']['selectedHour']['start']+1);
        }
        return array('status'=>"success",'price'=>$totalhour*$price['price'],'hour'=>$totalhour,'priceperhour'=>$price['price']);
    }
    public function createjob(Request $request){
        request()->validate([
            'data' => 'required'
            ]);
        $data = $request->all();
        
        $price = ProfileModel::where('userid',$data['data']['userId'])->first();
        $from_date = strtotime($data['data']['startdate']);
        $to_date = strtotime($data['data']['enddate']);
        $day_diff = $to_date - $from_date;
        $days = floor($day_diff/(60*60*24))+1;
        $realdays = $days;
        if($data['data']['exclude']){
            $excludeday = 0;
            for($i = 0; $i < count($data['data']['daysSelected']);$i++){
                if(strtotime($data['data']['daysSelected'][$i]) >= $from_date && strtotime($data['data']['daysSelected'][$i]) <= $to_date){
                    $excludeday++;
                }
            }
            $realdays = $days - $excludeday;
        }
        if($data['data']['selectedHour']['live_in']){
            $totalhour = $realdays*24;
        }
        else{
            $totalhour = $realdays*($data['data']['selectedHour']['end']-$data['data']['selectedHour']['start']+1);
        }
        $client = UsersModel::where('remember_token',$request->token)->first();
        $job = new JobModel();
        $job->jobid = mt_rand(100000000, 999999999);
        $job->provider = $data['data']['userId'];
        $job->client = $client['id'];
        $job->start = date("Y-m-d",strtotime($data['data']['startdate']));
        $job->end = date("Y-m-d",strtotime($data['data']['enddate']));
        $job->excludeday = json_encode($data['data']['daysSelected']);
        if($data['data']['selectedHour']['live_in']){
            $job->starttime = 24;
            $job->endtime = 24;
        }
        else{
            $job->starttime = $data['data']['selectedHour']['start'];
            $job->endtime = $data['data']['selectedHour']['end'];
        }
        $job->amount = $totalhour*$price['price'];
        $job->status = 0;
        $job->paid = 0;
        $job->service = $data['search']['service'];
        $job->serviceactivity = json_encode($data['search']['activities']);
        $job->license = json_encode($data['search']['license']);
        $job->exspec = json_encode($data['search']['expertise']);
        if($job->save()){
            $clientName = ProfileModel::where('userid',$client['id'])->select('fname','lname')->first();
            $providerphone = ProfileModel::where('userid',$data['data']['userId'])->select('phone')->first();
            $provideremail = UsersModel::where('id',$data['data']['userId'])->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "You are hired";
            $not->description = "You are hired by ".$clientName->fname." ".$clientName->lname;
            $not->specific = $data['data']['userId'];
            $not->save();

            $message = "You are hired by ".$clientName->fname." ".$clientName->lname;
            $this->twclient->messages->create($providerphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "You are hired on Flexhealth",
                'user' =>$clientName->fname." ".$clientName->lname,
                'message' => $message
            );
            Mail::to($provideremail['email'])->send(new JobMail($data));

            return array('status'=>"success",'jobid'=>$job->id);
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function paystep(Request $request){
        $job = JobModel::where('id',$request->jobid)->first();
        $client = ProfileModel::where('userid',$job['client'])->where('roles',1)->first();
        $provider = ProfileModel::where('userid',$job['provider'])->where('roles',2)->first();
        $customer = Customer::create(array(
            'email' => $client['email'],
            'source'  => $request->stripe_token
        ));
        $charge = Charge::create ([
                'customer' => $customer->id,
                "amount" => $job['amount'] * 100,
                "currency" => "usd",
                "description" => $client['fname']." ".$client['lname']." hired ".$provider['fname']." ".$provider['lname']." on Flexhealth"
        ]);
        // $stripe = Account::createExternalAccount(
        //     $account['id'],
        //     ['external_account' => $request->stripe_token,
        //     ]
        // );
        // $accupdate = Account::update(
        //     $account['id'],
        //     [
        //       'tos_acceptance' => [
        //         'date' => time(),
        //         'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
        //       ],
        //     ]
        //   );
        if($charge){
            JobModel::where('id',$request->jobid)->update(['paid' => 1]);
            $transaction = new TransactionModel();
            $transaction->type = 1;
            $transaction->tranid = mt_rand(100000000, 999999999);
            $transaction->jobid = $request->jobid;
            $transaction->amount = $job['amount'];
            $transaction->chargeid = $charge['id'];
            $transaction->customerid = $charge['customer'];
            $transaction->cardid = $charge['payment_method'];
            $transaction->cardnum = $charge['payment_method_details']['card']['last4'];
            $transaction->save();
            return array('status'=>"success");
        }
        else{
            return array('status'=>"failed");
        }

    }
    
    public function cancelreason(){
        $reasonlist = ReasonModel::select('id','name')->get();
        if($reasonlist){
            return array('status'=>'success','reason'=>$reasonlist);
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function acceptjob(Request $request){
        
        $acceptflag = JobModel::where('id',$request->jobid)->update(['status' => 1]);
        if($acceptflag){
            $detail = JobModel::where('id',$request->jobid)->first();
            $providerName = ProfileModel::where('userid',$detail->provider)->select('fname','lname')->first();
            $clientphone = ProfileModel::where('userid',$detail->client)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->client)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your Service is accepted";
            $not->description = "Service is accepted by ".$providerName->fname." ".$providerName->lname;
            $not->specific = $detail->client;
            $not->save();

            $message = "Service is accepted by ".$providerName->fname." ".$providerName->lname;
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "Service is accepted",
                'user' =>$providerName->fname." ".$providerName->lname,
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new JobMail($data));

            return array('status'=>'success');
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function rejectjob(Request $request){
        
        $cancelflag = JobModel::where('id',$request->data['jobid'])->update(['status' => 3,'cancelflag' => $request->data['reason'],'canceltext' => $request->data['other_desc']]);
        if($cancelflag){
            $detail = JobModel::where('id',$request->data['jobid'])->first();
            $canref = new CancelrefundModel();
            $canref->type = 1;
            $canref->refid = mt_rand(100000000, 999999999);
            $canref->jobid = $request->data['jobid'];
            $canref->amount = $detail['amount'];
            $canref->status = 0;
            $canref->save();
            if($request->data['reason'] != 0){
                $reason = ReasonModel::where('id',$request->data['reason'])->select('id','name')->first();
                $reasontmp = $reason->name;
            }
            else{
                $reasontmp = $request->data['other_desc'];
            }
            $providerName = ProfileModel::where('userid',$detail->client)->select('fname','lname')->first();
            $clientphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your Service is canceled";
            $not->description = "Service is canceled by ".$providerName->fname." ".$providerName->lname." - ".$reasontmp;
            $not->specific = $detail->provider;
            $not->save();

            $message = "Service is canceled by ".$providerName->fname." ".$providerName->lname." - ".$reasontmp;
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "Service is canceled",
                'user' =>$providerName->fname." ".$providerName->lname,
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new JobMail($data));

            return array('status'=>'success');
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function canceljob(Request $request){
        
        $cancelflag = JobModel::where('id',$request->data['jobid'])->update(['status' => 3,'cancelflag' => $request->data['reason'],'canceltext' => $request->data['other_desc']]);
        if($cancelflag){
            $detail = JobModel::where('id',$request->data['jobid'])->first();
            $canref = new CancelrefundModel();
            $canref->type = 1;
            $canref->refid = mt_rand(100000000, 999999999);
            $canref->jobid = $request->data['jobid'];
            $canref->amount = $detail['amount'];
            $canref->status = 0;
            $canref->save();
            if($request->data['reason'] != 0){
                $reason = ReasonModel::where('id',$request->data['reason'])->select('id','name')->first();
                $reasontmp = $reason->name;
            }
            else{
                $reasontmp = $request->data['other_desc'];
            }
            $clientName = ProfileModel::where('userid',$detail->client)->select('fname','lname')->first();
            $providerphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $provideremail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your Service is canceled";
            $not->description = "Service is canceled by ".$clientName->fname." ".$clientName->lname." - ".$reasontmp;
            $not->specific = $detail->provider;
            $not->save();

            $message = "Service is canceled by ".$clientName->fname." ".$clientName->lname." - ".$reasontmp;
            $this->twclient->messages->create($providerphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "Service is canceled",
                'user' =>$clientName->fname." ".$clientName->lname,
                'message' => $message
            );
            Mail::to($provideremail['email'])->send(new JobMail($data));

            return array('status'=>'success');
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function incomingservice(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
            ->select('profile_models.*','job_models.id as jobid','job_models.start','job_models.end','job_models.status',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
            ->where('job_models.client',$token['id'])->where('job_models.paid',1)->where('job_models.status',0)->groupBy('job_models.id')->groupBy('profile_models.userid')->orderBy('job_models.created_at','desc')
            ->get();
        
        return array('status'=>'success','incominglist'=>$list);
    }
    public function ongoingservice(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
            ->select('profile_models.*','job_models.id as jobid','job_models.start','job_models.end','job_models.status',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
            ->where('job_models.client',$token['id'])->where('job_models.paid',1)->where('job_models.status',1)->groupBy('job_models.id')->groupBy('profile_models.userid')->orderBy('job_models.created_at','desc')
            ->get();
        return array('status'=>'success','ongoinglist'=>$list);
    }
    public function finishedservice(Request $request){
        $perPage = 10;
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
        ->select('profile_models.*','job_models.id as jobid','job_models.start','job_models.end','job_models.status','job_models.reviewflag',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
        ->where('job_models.client',$token['id'])->where('job_models.paid',1)->where('job_models.status',2)->groupBy('job_models.id')->groupBy('profile_models.userid')->orderBy('job_models.created_at','desc');
        $totalpage = round(count($list->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $list = $list->offset(0)->limit($perPage)->get();
        else{
            $list = $list->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        return array('status'=>'success','finishedlist'=>$list,'totalpage'=>$totalpage);
    }
    public function canceledservice(Request $request){
        $perPage = 10;
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
            ->leftJoin('cancelrefund_models', 'cancelrefund_models.jobid', '=', 'job_models.id')
            ->select('profile_models.*','job_models.id as jobid','job_models.start','job_models.end','job_models.status','cancelrefund_models.status as refstatus',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
            ->where('job_models.client',$token['id'])->where('job_models.paid',1)->where('job_models.status',3)->groupBy('job_models.id')->groupBy('profile_models.userid')->orderBy('job_models.created_at','desc');
        $totalpage = round(count($list->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $list = $list->offset(0)->limit($perPage)->get();
        else{
            $list = $list->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        return array('status'=>'success','canceledlist'=>$list,'totalpage'=>$totalpage);
    }
    public function incomingjob(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $price = ProfileModel::where('userid',$token['id'])->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.client')
            ->select('profile_models.fname','profile_models.lname','profile_models.price','profile_models.avatar','job_models.id as jobid','job_models.amount','job_models.service','job_models.status','job_models.start','job_models.end')
            ->where('job_models.provider',$token['id'])->where('job_models.paid',1)->where('job_models.status',0)->groupBy('job_models.id')->orderBy('job_models.created_at','desc')
            ->get();
        $job = [];
        for($i = 0;$i < count($list);$i ++){
            $job[$i]['jobid'] = $list[$i]->jobid;
            $job[$i]['fname'] = $list[$i]->fname;
            $job[$i]['lname'] = $list[$i]->lname;
            $job[$i]['avatar'] = $list[$i]->avatar;
            $job[$i]['service'] = $list[$i]->service;
            $job[$i]['start'] = $list[$i]->start;
            $job[$i]['end'] = $list[$i]->end;
            $job[$i]['status'] = $list[$i]->status;
            $job[$i]['totalhours'] = $list[$i]->amount/$price['price'];
        }
        return array('status'=>'success','incomingjob'=>$job);
    }
    public function ongoingjob(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $price = ProfileModel::where('userid',$token['id'])->first();
        $list = DB::table('job_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.client')
            ->select('profile_models.fname','profile_models.lname','profile_models.price','profile_models.avatar','job_models.id as jobid','job_models.amount','job_models.service','job_models.status','job_models.start','job_models.end')
            ->where('job_models.provider',$token['id'])->where('job_models.paid',1)->where('job_models.status',1)->groupBy('job_models.id')->orderBy('job_models.created_at','desc')
            ->get();
        $job = [];
        for($i = 0;$i < count($list);$i ++){
            $job[$i]['jobid'] = $list[$i]->jobid;
            $job[$i]['fname'] = $list[$i]->fname;
            $job[$i]['lname'] = $list[$i]->lname;
            $job[$i]['avatar'] = $list[$i]->avatar;
            $job[$i]['service'] = $list[$i]->service;
            $job[$i]['start'] = $list[$i]->start;
            $job[$i]['end'] = $list[$i]->end;
            $job[$i]['status'] = $list[$i]->status;
            $job[$i]['totalhours'] = $list[$i]->amount/$price['price'];
        }
        return array('status'=>'success','ongoingjob'=>$job);
    }
    public function previousjob(Request $request){
        $perPage = 10;
        $token = UsersModel::where('remember_token',$request->token)->first();
        $price = ProfileModel::where('userid',$token['id'])->first();
        $list = DB::table('job_models');
        $list = $list->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.client');
        $list = $list->select('profile_models.fname','profile_models.lname','profile_models.price','profile_models.avatar','profile_models.rate','profile_models.review','job_models.id as jobid','job_models.amount','job_models.service','job_models.status','job_models.reviewflag','job_models.start','job_models.end');
        $list = $list->where('job_models.provider',$token['id'])->where('job_models.paid',1)->whereIn('job_models.status',["2","3"])->groupBy('job_models.id')->orderBy('job_models.created_at','desc');
        $totalpage = round(count($list->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $list = $list->offset(0)->limit($perPage)->get();
        else{
            $list = $list->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        $job = [];
        for($i = 0;$i < count($list);$i ++){
            $job[$i]['jobid'] = $list[$i]->jobid;
            $job[$i]['fname'] = $list[$i]->fname;
            $job[$i]['lname'] = $list[$i]->lname;
            $job[$i]['avatar'] = $list[$i]->avatar;
            $job[$i]['service'] = $list[$i]->service;
            $job[$i]['start'] = $list[$i]->start;
            $job[$i]['end'] = $list[$i]->end;
            $job[$i]['status'] = $list[$i]->status;
            $job[$i]['reviewflag'] = $list[$i]->reviewflag;
            $job[$i]['rate'] = $list[$i]->rate;
            $job[$i]['review'] = $list[$i]->review;
            $job[$i]['totalhours'] = $list[$i]->amount/$price['price'];
        }
        return array('status'=>'success','previousjob'=>$job,'totalpage'=>$totalpage);
    }
    public function jobdetail(Request $request){
        
        $job = DB::table('job_models');
        if($request->usertype == 1)
            $job = $job->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider');
        else
            $job = $job->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.client');
        $job = $job->leftJoin('license_models', function ($join) {
            $join->on('job_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
            ->orOn('job_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('job_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
        })
        ->select('profile_models.fname','profile_models.lname','profile_models.avatar','job_models.*',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
        ->where('job_models.id',$request->jobid)->groupBy('job_models.id')
        ->first();
        if($job){
            $jobdetail['fname'] = $job->fname;
            $jobdetail['lname'] = $job->lname;
            $jobdetail['service'] = $job->service;
            $jobdetail['exspec'] = json_decode($job->exspec);
            $jobdetail['serviceactivity'] = json_decode($job->serviceactivity);
            $jobdetail['licenseimg'] = $job->licenseimg;
            $jobdetail['licensename'] = $job->licensename;
            $jobdetail['avatar'] = $job->avatar;
            $jobdetail['startdate'] = $job->start;
            $jobdetail['enddate'] = $job->end;
            $jobdetail['starttime'] = $job->starttime;
            $jobdetail['endtime'] = $job->endtime;
            $jobdetail['excludedays'] = json_decode($job->excludeday);
            return array('status'=>'success','jobdetail'=>$jobdetail);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function requestrefund(Request $request){
        $result = CancelrefundModel::where('jobid',$request->jobid)->update(['status'=>1]);
        if($result){
            $refid = CancelrefundModel::where('jobid',$request->jobid)->first();
            return array('status'=>'success','refid'=>$refid['refid']);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function viewreason(Request $request){
        $result = JobModel::where('id',$request->jobid)->select('cancelflag','canceltext')->first();
        if($result['cancelflag'] == 0)
            $reason = $result['canceltext'];
        else{
            $reasonlist = ReasonModel::where('id',$result['cancelflag'])->select('id','name')->first();
            $reason = $reasonlist['name'];
        }
        if($reason){
            return array('status'=>'success','reason'=>$reason);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function review(Request $request){
        $job = JobModel::where('id',$request->jobid)->select('provider','client')->first();
        $review = new ReviewModel();
        $review->jobid = $request->jobid;
        if($request->usertype == 1){
            $review->sender = $job['client'];
            $review->receiver = $job['provider'];

        }
        else{
            $review->sender = $job['provider'];
            $review->receiver = $job['client'];
        }
        $review->rate = $request->rating;   
        $review->comment = $request->review;   
        if($review->save()){
            JobModel::where('id',$request->jobid)->update(['reviewflag'=>1]);
            $reviewCnt = ReviewModel::where('receiver',$review->receiver)->count();
            $totalrate = ReviewModel::where('receiver',$review->receiver)->sum('rate');
            $result = ProfileModel::where('userid',$review->receiver)->update(['rate'=>round($totalrate/$reviewCnt),'review'=>$reviewCnt]);
            if($result){
                return array('status'=>'success');
            }
            else{
                return array('status'=>'failed');
            }
        }
        else{
            return array('status'=>'failed');
        }        
    }
    public function translist(Request $request){
        $perPage = 10;
        $token = UsersModel::where('remember_token',$request->token)->select('id')->first();
        $translist = DB::table('transaction_models')
            ->leftJoin('job_models', 'job_models.id', '=', 'transaction_models.jobid')
            ->leftJoin('service_models', function ($join) {
                $join->on('job_models.serviceactivity', 'like', DB::raw("concat('%,',service_models.id,',%')"))
                ->orOn('job_models.serviceactivity', 'like', DB::raw("concat('%',service_models.id,']%')"))
                  ->orOn('job_models.serviceactivity', 'like', DB::raw("concat('%[',service_models.id,'%')"));
            })
            ->select('transaction_models.*','job_models.service','job_models.serviceactivity','job_models.status',DB::raw('group_concat(service_models.name SEPARATOR "<||>") as servicename'))
            ->where('job_models.client',$token['id'])->where('transaction_models.type',1)->where('job_models.paid',1)->groupBy('transaction_models.id')->orderBy('transaction_models.created_at','desc');
        $totalpage = round(count($translist->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $translist = $translist->offset(0)->limit($perPage)->get();
        else{
            $translist = $translist->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        return array('status'=>'success','translist'=>$translist,'totalpage'=>$totalpage);
    }
    public function transhistory(Request $request){
        $perPage = 10;
        $token = UsersModel::where('remember_token',$request->token)->select('id')->first();
        $translist = DB::table('transaction_models')
            ->leftJoin('job_models', 'job_models.id', '=', 'transaction_models.jobid')
            ->leftJoin('service_models', function ($join) {
                $join->on('job_models.serviceactivity', 'like', DB::raw("concat('%,',service_models.id,',%')"))
                ->orOn('job_models.serviceactivity', 'like', DB::raw("concat('%',service_models.id,']%')"))
                  ->orOn('job_models.serviceactivity', 'like', DB::raw("concat('%[',service_models.id,'%')"));
            })
            ->select('transaction_models.*','job_models.service','job_models.serviceactivity','job_models.status',DB::raw('group_concat(service_models.name SEPARATOR "<||>") as servicename'))
            ->where('job_models.provider',$token['id'])->where('transaction_models.type',1)->where('job_models.paid',1)->groupBy('transaction_models.id')->orderBy('transaction_models.created_at','desc');
        $totalpage = round(count($translist->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $translist = $translist->offset(0)->limit($perPage)->get();
        else{
            $translist = $translist->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        return array('status'=>'success','translist'=>$translist,'totalpage'=>$totalpage);
    }
    
}
