<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ischeduleModel;
use App\InterviewListModel;
use App\UsersModel;
use App\NotificationModel;
use App\ProfileModel;
use App\IntcancelreasonModel;
use App\TransactionModel;
use Mail;
use App\Mail\InterviewRequestionMail;
use DB;
use Google_Client;
use Twilio\Rest\Client;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
function Makeweek($value){
    if($value == 0)
        return "Monday";
    else if($value == 1)
        return "Tuesday";
    else if($value == 2)
        return "Wednesday";
    else if($value == 3)
        return "Thursday";
    else if($value == 4)
        return "Friday";
    else if($value == 5)
        return "Saturday";
    else if($value == 6)
        return "Sunday";
}
function MakeTime($value){
    $value = $value + 1;
    if($value == 12)
        return "12 PM";
    if($value == 24)
        return "12 AM";
    if($value > 12)
        return ($value - 12)." PM";
    return $value." AM";
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
class InterviewController extends Controller
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
    public function interviewlist(Request $request){
        $result = ischeduleModel::where('userid',$request->id)->where('checked',1)->get();
        if($result){
            return array('interview'=>$result);
        }
    }
    public function confirminterview(Request $request){
        $detail = ischeduleModel::where('id',$request->id)->first();
        $token = UsersModel::where('remember_token',$request->token)->first();
       
        $interview = new InterviewListModel();
        $interview->provider = $detail->userid;
        $interview->week = $detail->week;
        $interview->start = $detail->start;
        $interview->end = $detail->end;
        $interview->client = $token['id'];
        $interview->status = 0;
        $interview->settime = $request->settime;
        if($interview->save()){
            $clientName = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $providerphone = ProfileModel::where('userid',$detail->userid)->select('phone')->first();
            $provideremail = UsersModel::where('id',$detail->userid)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Requestion";
            $not->description = "Interview requestion from ".$clientName->fname." ".$clientName->lname;
            $not->specific = $detail->userid;
            $not->save();

            $message = "Interview Requestion On ".MakeTime($interview->settime)." - ".MakeTime($interview->settime+1).", ".Makeweek($detail->week)." From ".$clientName->fname." ".$clientName->lname;
            $this->twclient->messages->create($providerphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$clientName->fname." ".$clientName->lname,
                'message' => $message
            );
            Mail::to($provideremail['email'])->send(new InterviewRequestionMail($data));
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function checkinterview(Request $request){
        $detail = ischeduleModel::where('id',$request->id)->first();
        $token = UsersModel::where('remember_token',$request->token)->first();
        $result = InterviewListModel::where('provider',$detail->userid)->where('client',$token['id'])->where('status',0)->first();
        if($result)
            return array('status'=>'exist');
        else
            return array('status'=>'success');
    }
    public function acceptinterview(Request $request){
        
        $detail = InterviewListModel::where('id',$request->id)->first();
        $token = UsersModel::where('remember_token',$request->token)->first();
        $session = generateRandomString(10);
        $result = InterviewListModel::where('id',$request->id)->update(['status'=>1,'room'=>$session]);
        if($result){
            $providername = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $clientphone = ProfileModel::where('userid',$detail->client)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->client)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview is accepted";
            $not->description = "Interview is accepted by ".$providername->fname." ".$providername->lname;
            $not->specific = $detail->client;
            $not->save();

            $message = "Interview is accepted by ".$providername->fname." ".$providername->lname;
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$providername->fname." ".$providername->lname,
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new InterviewRequestionMail($data));

            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function interviewforclient(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('interview_list_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'interview_list_models.provider')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
            ->select('profile_models.*','interview_list_models.id as intid','interview_list_models.week','interview_list_models.settime','interview_list_models.start','interview_list_models.end','interview_list_models.status','interview_list_models.checkflag',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
            ->where('interview_list_models.client',$token['id'])->where('interview_list_models.cchk', '!=' ,2)
            ->where('interview_list_models.status',"<>",2)->groupBy('profile_models.userid')->groupBy('interview_list_models.week')
            ->get();
        return array('status'=>'success','interviewlist'=>$list);
    }
    public function interviewforprovider(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->first();
        $list = DB::table('interview_list_models')
            ->leftJoin('profile_models', 'profile_models.userid', '=', 'interview_list_models.client')
            ->select('profile_models.*','interview_list_models.id as intid','interview_list_models.week','interview_list_models.settime','interview_list_models.start','interview_list_models.end','interview_list_models.status','interview_list_models.checkflag')
            ->where('interview_list_models.provider',$token['id'])->where('interview_list_models.pchk', '!=' ,2)
            ->where('interview_list_models.status',"<>",2)->groupBy('profile_models.userid')->groupBy('interview_list_models.week')
            ->get();
        return array('status'=>'success','interviewlist'=>$list);
    }
    public function createintroom(Request $request){
        $result = InterviewListModel::where('id',$request->id)->first();
        if($result){
            return array('status'=>'success','session'=>$result['room']);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function canintreason(Request $request){
        $reasonlist = IntcancelreasonModel::where('type',2)->select('id','name')->get();
        if($reasonlist){
            return array('status'=>'success','reason'=>$reasonlist);
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function cancelintbyclient(Request $request){
       
        InterviewListModel::where('id',$request->data['jobid'])->update(['status'=>2]);
        $token = UsersModel::where('remember_token',$request->token)->first();
        $detail = InterviewListModel::where('id',$request->data['jobid'])->first();
        $reason = IntcancelreasonModel::where('type',1)->where('id',$request->data['reason'])->select('id','name')->first();
        if($reason){
            $clientName = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $providerphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $provideremail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Cancellation";
            $not->description = "Interview is canceled by ".$clientName->fname." ".$clientName->lname." - ".$reason['name'];
            $not->specific = $detail->provider;
            $not->save();

            $message = "Interview is canceled by ".$clientName->fname." ".$clientName->lname." - ".$reason['name'];
            $this->twclient->messages->create($providerphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$clientName->fname." ".$clientName->lname,
                'message' => $message
            );
            Mail::to($provideremail['email'])->send(new InterviewRequestionMail($data));
            return array('status'=>'success');
        }
        else{
            $clientName = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $providerphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $provideremail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Cancellation";
            $not->description = "Interview is canceled by ".$clientName->fname." ".$clientName->lname." - ".$request->data['other_desc'];
            $not->specific = $detail->provider;
            $not->save();

            $message = "Interview is canceled by ".$clientName->fname." ".$clientName->lname." - ".$request->data['other_desc'];
            $this->twclient->messages->create($providerphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$clientName->fname." ".$clientName->lname,
                'message' => $message
            );
            Mail::to($provideremail['email'])->send(new InterviewRequestionMail($data));
            return array('status'=>'success');
        }
    }
    public function cancelintbyprovider(Request $request){
        
        InterviewListModel::where('id',$request->data['jobid'])->update(['status'=>2]);
        $token = UsersModel::where('remember_token',$request->token)->first();
        $detail = InterviewListModel::where('id',$request->data['jobid'])->first();
        $reason = IntcancelreasonModel::where('type',2)->where('id',$request->data['reason'])->select('id','name')->first();
        if($reason){
            $providerName = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $clientphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Rejection";
            $not->description = "Interview is rejected by ".$providerName->fname." ".$providerName->lname." - ".$reason->name;
            $not->specific = $detail->provider;
            $not->save();

            $message = "Interview is rejected by ".$providerName->fname." ".$providerName->lname." - ".$reason->name;
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$providerName->fname." ".$providerName->lname,
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new InterviewRequestionMail($data));
            return array('status'=>'success');
        }
        else{
            $providerName = ProfileModel::where('userid',$token['id'])->select('fname','lname')->first();
            $clientphone = ProfileModel::where('userid',$detail->provider)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->provider)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Interview Rejection";
            $not->description = "Interview is rejected by ".$providerName->fname." ".$providerName->lname." - ".$request->data['other_desc'];
            $not->specific = $detail->provider;
            $not->save();

            $message = "Interview is rejected by ".$providerName->fname." ".$providerName->lname." - ".$request->data['other_desc'];
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'client' =>$providerName->fname." ".$providerName->lname,
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new InterviewRequestionMail($data));
            return array('status'=>'success');
        }
    }
    public function payinterview(Request $request){
        $user = UsersModel::where('remember_token',$request->token)->first();
        $customer = Customer::create(array(
            'email' => $user['email'],
            'source'  => $request->stripe_token
        ));
        $stripe = Charge::create ([
                'customer' => $customer->id,
                "amount" => 30 * 100,
                "currency" => "usd",
                "description" => "Interview Payment"
        ]);
        if($stripe){
            $transaction = new TransactionModel();
            $transaction->type = 2;
            $transaction->tranid = mt_rand(100000000, 999999999);
            $transaction->amount = 30;
            $transaction->chargeid = $stripe['id'];
            $transaction->customerid = $stripe['customer'];
            $transaction->cardid = $stripe['payment_method'];
            $transaction->save();

            return array('status'=>"success");
        }
        else{
            return array('status'=>"failed");
        }
    }
    public function gotoint(Request $request){
        $user = UsersModel::where('remember_token',$request->token)->first();
        if($user['roles'] == 1){
            $result = InterviewListModel::where('id',$request->intid)->update(['cchk'=>1]);
            if($result){
                return array('status'=>"success");
            }
            else{
                return array('status'=>"failed");
            }
        }
        else{
            $result = InterviewListModel::where('id',$request->intid)->update(['pchk'=>1]);
            if($result){
                return array('status'=>"success");
            }
            else{
                return array('status'=>"failed");
            }
        }
    }
    public function leaveint(Request $request){
        $user = UsersModel::where('remember_token',$request->token)->first();
        if($user['roles'] == 1){
            $result = InterviewListModel::where('id',$request->intid)->update(['cchk'=>2]);
            if($result){
                return array('status'=>"success");
            }
            else{
                return array('status'=>"failed");
            }
        }
        else{
            $result = InterviewListModel::where('id',$request->intid)->update(['pchk'=>2]);
            if($result){
                return array('status'=>"success");
            }
            else{
                return array('status'=>"failed");
            }
        }
    }
}
