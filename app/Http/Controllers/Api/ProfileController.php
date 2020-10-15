<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\UsersModel;
use App\VcodeModel;
use App\ProfileModel;
use App\sscheduleModel;
use App\ischeduleModel;
use DB;
use Mail;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Session;
use Google_Client;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Hash;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class ProfileController extends Controller
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

    public function Viewinfo(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = ProfileModel::where('userid',$result['id'])->first();
        if($duplicate_value){
            $userdata['fname'] = $duplicate_value['fname'];
            $userdata['lname'] = $duplicate_value['lname'];
            $userdata['address'] = $duplicate_value['address'];
            $userdata['long'] = $duplicate_value['long'];
            $userdata['lat'] = $duplicate_value['lat'];
            $userdata['dob'] = date("m/d/Y",strtotime($duplicate_value['dob']));
            $userdata['email'] = $result['email'];
            $userdata['phone'] = $duplicate_value['phone'];
            $userdata['countryISO'] = $duplicate_value['countryISO'];
            if($duplicate_value['gender'] == 1)
                $userdata['gender'] = true;
            else
                $userdata['gender'] = false;
            $userdata['bio'] = $duplicate_value['bio'];
            $userdata['price'] = $duplicate_value['price'];
            $userdata['service'] = $duplicate_value['service'];
            $userdata['language'] = json_decode($duplicate_value['language']);
            $userdata['exspec'] = json_decode($duplicate_value['exspec']);
            $userdata['license'] = json_decode($duplicate_value['license']);
            return array('status'=>'success','userdata'=>$userdata);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function Updateinfo(Request $request){
        request()->validate([
            'token' => 'required',
            'userdata' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = ProfileModel::where('userid',$result['id'])->update(['fname' => $data['userdata']['fname'],'lname' => $data['userdata']['lname'],'address' => $data['userdata']['address'],'long' => $data['userdata']['long'],'lat' => $data['userdata']['lat'],'gender' => $data['userdata']['gender'],'dob' => date("Y-m-d",strtotime($data['userdata']['dob']))]);
        if($duplicate_value){
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function Avatarupdate(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        if($data['avatar'] != null){
            $result = UsersModel::where('remember_token',$data['token'])->first();
            $oldfile = ProfileModel::where('userid',$result['id'])->first();
            Storage::disk('s3')->delete($oldfile['avatar']);
            $avatarname = Str::random(20).date("Y-m-d");
            $real_img = file_get_contents($data['avatar']);
            Storage::disk('s3')->put($avatarname.".png", $real_img);
            $duplicate_value = ProfileModel::where('userid',$result['id'])->update(['avatar' => $avatarname.".png"]);
            if($duplicate_value){
                $avatar = ProfileModel::where('userid',$result['id'])->select('avatar')->first();
                return array('status'=>'success','avatar'=>$avatar['avatar']);
            }
            else{
                return array('status'=>'failed');
            }
        }
    }
    public function Coverimgupdate(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        if($data['coverimg'] != null){
            $result = UsersModel::where('remember_token',$data['token'])->first();
            $oldfile = ProfileModel::where('userid',$result['id'])->first();
            Storage::disk('s3')->delete("/coverimg/".$oldfile['coverimg']);
            $coverimgname = Str::random(20).date("Y-m-d");
            $real_img = file_get_contents($data['coverimg']);
            Storage::disk('s3')->put("/coverimg/".$coverimgname.".png", $real_img);
            $duplicate_value = ProfileModel::where('userid',$result['id'])->update(['coverimg' => $coverimgname.".png"]);
            if($duplicate_value){
                $coverimg = ProfileModel::where('userid',$result['id'])->select('coverimg')->first();
                return array('status'=>'success','coverimg'=>$coverimg['coverimg']);
            }
            else{
                return array('status'=>'failed');
            }
        }
    }
    public function Bioupdate(Request $request){
        request()->validate([
            'token' => 'required',
            'bio' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = ProfileModel::where('userid',$result['id'])->update(['bio' => $data['bio']]);
        if($duplicate_value){
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function Emailupdate(Request $request){
        request()->validate([
            'token' => 'required',
            'email' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('email',$data['email'])->first();
        if($result){
            return array('status'=>'existed');
        }
        else{
            UsersModel::where('remember_token',$data['token'])->update(['emailtmp' => $data['email']]);
            $data['code'] = mt_rand(100000, 999999);
            $userid = UsersModel::where('remember_token',$data['token'])->first();
            VcodeModel::where('userid',$userid['id'])->update(['code' => $data['code']]);
            
            Mail::to($data['email'])->send(new SendMail($data));
            return array('status'=>'success');
        }
    }
    public function Emailverified(Request $request){
        request()->validate([
            'token' => 'required',
            'code' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->where('code',$data['code'])->first();
        if($duplicate_value){
            UsersModel::where('remember_token',$data['token'])->update(['email' => $result['emailtmp']]);
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function Emailresend(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $data['code'] = mt_rand(100000, 999999);

        $userid = UsersModel::where('remember_token',$data['token'])->first();
        VcodeModel::where('userid',$userid['id'])->update(['code' => $data['code']]);
        Mail::to($userid['emailtmp'])->send(new SendMail($data));
        return array('status'=>'success');
    }
    public function Phoneupdate(Request $request){
        request()->validate([
            'token' => 'required',
            'phone' => 'required'
            ]);
        $data = $request->all();
        
        $verificationCode = mt_rand(100000, 999999);
        $userid = UsersModel::where('remember_token',$data['token'])->first();
        $result = VcodeModel::where('userid',$userid['id'])->update(['codeP' => $verificationCode]);
        $message = "Verification Code: ".$verificationCode." From Flexhealth";
        $this->twclient->messages->create($data['phone'], 
                ['from' => $this->twilio_number, 'body' => $message] );
        if($result)
            return array('status'=>'success');
        else
            return array('status'=>'failed');
        
    }
    public function Phoneverified(Request $request){
        request()->validate([
            'token' => 'required',
            'phone' => 'required',
            'countryiso' => 'required',
            'code' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->where('codeP',$data['code'])->first();
        if($duplicate_value){
            ProfileModel::where('userid',$result['id'])->update(['phone' => $data['phone'],'countryISO' => $data['countryiso']]);
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function Passwordupdate(Request $request){
        request()->validate([
            'token' => 'required',
            'newPass' => 'required'
            ]);
        $data = $request->all();
        if($data['resetFlag']){
            $result = UsersModel::where('remember_token',$data['token'])->update(['password' => Hash::make($data['newPass'])]);
            if($result){
                UsersModel::where('remember_token',$data['token'])->update(['remember_token' => Str::random(60)]);
                return array('status'=>'success');
            }
            else
                return array('status'=>'failed');
        }
        else{
            $result = UsersModel::where('remember_token',$data['token'])->first();
            if($result->password != "" || $result->password != null){
                if(Hash::check($data['curPass'], $result->password)){
                    UsersModel::where('remember_token',$data['token'])->update(['password' => Hash::make($data['newPass'])]);
                    return array('status'=>'success');
                }
                else{
                    return array('status'=>'failed');
                }
            }
            else{
                UsersModel::where('remember_token',$data['token'])->update(['password' => Hash::make($data['newPass'])]);
                return array('status'=>'success');
            }
        }
    }
    public function qualificationUpdate(Request $request){
        request()->validate([
            'token' => 'required',
            'userdata' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $profile = ProfileModel::where('userid',$result['id'])->first();
        $profile->language = json_encode($data['userdata']['language']);
        $profile->exspec = json_encode($data['userdata']['expertise']);
        $profile->license = json_encode($data['userdata']['license']);
        if($profile->save()){
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function Priceupdate(Request $request){
        request()->validate([
            'token' => 'required',
            'price' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $profile = ProfileModel::where('userid',$result['id'])->first();
        $profile->price = $data['price'];
        if($profile->save()){
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function chosenProfile(Request $request){
        request()->validate([
            'id' => 'required'
            ]);
        $data = $request->all();
        $duplicate_value = ProfileModel::where('userid',$data['id'])->first();
        if($duplicate_value){
            $providers = DB::table('profile_models')
            ->leftJoin('users_models', 'users_models.id', '=', 'profile_models.userid')
            ->leftJoin('license_models', function ($join) {
                $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                      ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                      ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
            })
            ->select('users_models.email','profile_models.*',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'))
            ->where('profile_models.userid',$data['id'])->groupBy('profile_models.userid')
            ->first();
            $ischedule = ischeduleModel::where("userid",$data['id'])->where("checked",1)->select('week','start','end')->get();
            $sschedule = sscheduleModel::where("userid",$data['id'])->where("checked",1)->select('week','start','end')->get();
            $userdata['fname'] = $providers->fname;
            $userdata['lname'] = $providers->lname;
            $userdata['address'] = $providers->address;
            $userdata['dob'] = date("m/d/Y",strtotime($providers->dob));
            $userdata['email'] = $providers->email;
            $userdata['phone'] = $providers->phone;
            $userdata['countryISO'] = $providers->countryISO;
            $userdata['gender'] = $providers->gender;
            $userdata['bio'] = $providers->bio;
            $userdata['price'] = $providers->price;
            $userdata['service'] = $providers->service;
            $userdata['language'] = json_decode($providers->language);
            $userdata['exspec'] = json_decode($providers->exspec);
            $userdata['license'] = json_decode($providers->license);
            $userdata['serviceactivity'] = json_decode($providers->serviceactivity);
            $userdata['rate'] = $providers->rate;
            $userdata['review'] = $providers->review;
            $userdata['live_in'] = $providers->live_in;
            $userdata['licenseimg'] = $providers->licenseimg;
            $userdata['licensename'] = $providers->licensename;
            $userdata['ischedule'] = $ischedule;
            $userdata['sschedule'] = $sschedule;
            $userdata['avatar'] = $providers->avatar;
            return array('status'=>'success','userdata'=>$userdata);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function verifytoken(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        if($result)
            return array('status'=>'success');
        else
            return array('status'=>'failed');
    }
    public function viewschedule(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $sschedule = sscheduleModel::where('userid',$result['id'])->where('checked',1)->get();
        $ischedule = ischeduleModel::where('userid',$result['id'])->where('checked',1)->get();
        $live_in = ProfileModel::where('userid',$result['id'])->select('live_in')->first();
        return array('status'=>'success','sschedule'=>$sschedule,'ischedule'=>$ischedule,'live_in'=>$live_in['live_in']);
    }
    public function setschedule(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        for($i = 0;$i < count($data['data']['sschedule']); $i++){
            $flag = sscheduleModel::where("userid",$result['id'])->where("week",$data['data']['sschedule'][$i]['name'])->first();
            if($flag){
                if($data['data']['sschedule'][$i]['checked'])
                    $checked = 1;
                else
                    $checked = 0;
                sscheduleModel::where('userid',$result['id'])->where('week',$data['data']['sschedule'][$i]['name'])->update(['checked' => $checked,'start' => $data['data']['sschedule'][0]['start'],'end' => $data['data']['sschedule'][0]['end']]);
            }
            else{
                $sschedule = new sscheduleModel();
                $sschedule->userid = $result['id'];
                $sschedule->week = $data['data']['sschedule'][$i]['name'];
                if($data['data']['sschedule'][$i]['checked'])
                    $sschedule->checked = 1;
                else
                    $sschedule->checked = 0;
                $sschedule->start = $data['data']['sschedule'][0]['start'];
                $sschedule->end = $data['data']['sschedule'][0]['end'];
                $sschedule->save();
            }
        }
        if($data['data']['live_in']){
            $live_flag = 1;
        }
        else{
            $live_flag = 0;
        }
        ProfileModel::where('userid',$result['id'])->update(['live_in' => $live_flag]);
        for($i = 0;$i < count($data['data']['ischedule']); $i++){
            $flag = ischeduleModel::where("userid",$result['id'])->where("week",$data['data']['ischedule'][$i]['name'])->first();
            if($flag){
                if($data['data']['ischedule'][$i]['checked'])
                    $checked = 1;
                else
                    $checked = 0;
                    ischeduleModel::where('userid',$result['id'])->where('week',$data['data']['ischedule'][$i]['name'])->update(['checked' => $checked,'start' => $data['data']['ischedule'][$i]['start'],'end' => $data['data']['ischedule'][$i]['end']]);
            }
            else{
                $ischedule = new ischeduleModel();
                $ischedule->userid = $result['id'];
                $ischedule->week = $data['data']['ischedule'][$i]['name'];
                if($data['data']['ischedule'][$i]['checked'])
                    $ischedule->checked = 1;
                else
                    $ischedule->checked = 0;
                $ischedule->start = $data['data']['ischedule'][$i]['start'];
                $ischedule->end = $data['data']['ischedule'][$i]['end'];
                $ischedule->save();
            }
            
        }

        return array('status'=>'success');
    }
    public function paydetail(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->select('id')->first();
        $accttmp = ProfileModel::where('userid',$token['id'])->select('account_id')->first();
        if($accttmp['account_id'] != null && $accttmp['account_id'] != ""){
            $detail['existed'] = 1;
            $paymentinfo = Account::retrieve(
                $accttmp['account_id']
            );
        }
        else{
            $detail['existed'] = 0;
        }
        if(isset($paymentinfo['external_accounts']['data'][0]['last4']))
            $detail['last4'] = $paymentinfo['external_accounts']['data'][0]['last4'];
        if(isset($paymentinfo['external_accounts']['data'][0]['object']) && $paymentinfo['external_accounts']['data'][0]['object'] == "bank_account"){
            $detail['type'] = 1;
        }
        else{
            $detail['type'] = 0;
        }
        if(isset($paymentinfo['capabilities']['card_payments']) && $paymentinfo['capabilities']['card_payments'] == "active" && $paymentinfo['capabilities']['transfers'] == "active"){
            $detail['status'] = 1;
        }
        else{
            $detail['status'] = 0;
        }
        return array('status'=>'success','result'=>$detail);
    }
    public function setpayaccount(Request $request){
        $token = UsersModel::where('remember_token',$request->token)->select('id')->first();
        
        $accttmp = ProfileModel::where('userid',$token['id'])->select('account_id')->first();
        if($accttmp['account_id'] != null && $accttmp['account_id'] != ""){
            $account = Account::retrieve(
                $accttmp['account_id']
            );
        }
        else{
            $account = Account::create([
                'country' => 'US',
                'type' => 'express',
                'requested_capabilities' => ['card_payments', 'transfers'],
            ]);
            ProfileModel::where('userid',$token['id'])->update(['account_id'=>$account['id']]);
        }
        $accountlink = AccountLink::create([
            'account' => $account['id'],
            'refresh_url' => 'https://flexhealth.me/provider/transactions',
            'return_url' => 'https://flexhealth.me/provider/transactions',
            'type' => 'account_onboarding',
        ]);
        if($accountlink['url']){
            return array('status'=>'success','url'=>$accountlink['url']);
        }
        else{
            return array('status'=>'failed');
        }
        
    }
}
