<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Twilio\Rest\Client;
use App\UsersModel;
use App\VcodeModel;
use App\ProfileModel;
use App\sscheduleModel;
use App\ischeduleModel;
use App\TransactionModel;
use App\PLicenseFileModel;
use App\DescriptionModel;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use App\Mail\SendMail;
use DB;
use Google_Client;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


class AuthController extends Controller
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
    
    public function login(Request $request){
        if(!$request->socialFlag){
            request()->validate([
                'email' => 'required|email',
                'password' => 'required',
                ]);
            $data = $request->all();
            //$result = UsersModel::where('email',$data['email'])->first();
            $result = DB::table('users_models')
                ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
                ->select('users_models.*', 'profile_models.fname', 'profile_models.lname','profile_models.avatar','profile_models.coverimg','profile_models.phone', 'profile_models.address','profile_models.long', 'profile_models.lat', 'profile_models.bio')
                ->where('email',$data['email'])
                ->first();
            if(!$result)
                return array('status'=>'failed');
            $userdata['email'] = $result->email;
            $userdata['phone'] = $result->phone;
            $userdata['address'] = $result->address;
            $userdata['fname'] = $result->fname;
            $userdata['lname'] = $result->lname;
            $userdata['avatar'] = $result->avatar;
            $userdata['coverimg'] = $result->coverimg;
            $userdata['usertype'] = $result->roles;
            $userdata['lat'] = $result->lat;
            $userdata['long'] = $result->long;
            $userdata['bio'] = $result->bio;
            $userdata['payflag'] = $result->payflag;
            if(Hash::check($data['password'], $result->password)){
                if($result->status == 1 && $result->allowed == 1 && $result->submit == 1 && $result->payflag == 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    $newToken = Str::random(60);
                    //var_dump($token['remember_token']);exit;
                    UsersModel::where('remember_token',$token['remember_token'])->update(['remember_token' => $newToken]);
                    return array('status'=>'success','token'=>$newToken,'userdata'=>$userdata);
                }
                else if($result->status == 0 && $result->allowed == 1)
                    return array('status'=>'pending','userdata'=>$userdata);

                else if($result->submit != 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    return array('status'=>'not_completed','token'=>$token['remember_token'],'userdata'=>$userdata);
                }
                else if($result->payflag != 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    return array('status'=>'not_paid','token'=>$token['remember_token'],'userdata'=>$userdata);
                }
                else if($result->status == 1 && $result->allowed == 0)
                    return array('status'=>'not_approved');
                
                else
                    return array('status'=>'illegal');
            }
            else{
                return array('status'=>'failed');
            }
        }
        else{
            request()->validate([
                'email' => 'required|email',
                'password' => 'required',
                ]);
            $data = $request->all();
            //$result = UsersModel::where('email',$data['email'])->first();
            $result = DB::table('users_models')
                ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
                ->select('users_models.*', 'profile_models.fname', 'profile_models.lname','profile_models.avatar','profile_models.coverimg','profile_models.phone', 'profile_models.address','profile_models.long', 'profile_models.lat', 'profile_models.bio')
                ->where('email',$data['email'])
                ->first();
            if(!$result)
                return array('status'=>'failed');
            $userdata['email'] = $result->email;
            $userdata['phone'] = $result->phone;
            $userdata['address'] = $result->address;
            $userdata['fname'] = $result->fname;
            $userdata['lname'] = $result->lname;
            $userdata['avatar'] = $result->avatar;
            $userdata['coverimg'] = $result->coverimg;
            $userdata['usertype'] = $result->roles;
            $userdata['lat'] = $result->lat;
            $userdata['long'] = $result->long;
            $userdata['bio'] = $result->bio;
            $userdata['payflag'] = $result->payflag;
            $payload = $this->social->verifyIdToken($data['password']);
            //var_dump($payload);exit;
            if($payload && $payload['email'] == $data['email']){
                if($result->status == 1 && $result->allowed == 1 && $result->submit == 1 && $result->payflag == 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    $newToken = Str::random(60);
                    //var_dump($token['remember_token']);exit;
                    UsersModel::where('remember_token',$token['remember_token'])->update(['remember_token' => $newToken]);
                    return array('status'=>'success','token'=>$newToken,'userdata'=>$userdata);
                }
                else if($result->status == 0 && $result->allowed == 1)
                    return array('status'=>'pending','userdata'=>$userdata);

                else if($result->submit != 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    return array('status'=>'not_completed','token'=>$token['remember_token'],'userdata'=>$userdata);
                }
                else if($result->payflag != 1){
                    $token = UsersModel::where('email',$data['email'])->first();
                    return array('status'=>'not_paid','token'=>$token['remember_token'],'userdata'=>$userdata);
                }
                else if($result->status == 1 && $result->allowed == 0)
                    return array('status'=>'not_approved');
                
                else
                    return array('status'=>'illegal');
            }
            else{
                return array('status'=>'failed');
            }
        }
        
    }
    public function signupGE(Request $request){
        request()->validate([
            'email' => 'required|email|unique:users_models'
            ]);
        $data = $request->all();
        $signup = new UsersModel();
        $signup->email = $data['email'];
        $signup->remember_token = Str::random(60);
        $signup->roles = 2;
        $signup->status = 0;
        $signup->allowed = 0;

        $verification_code = mt_rand(100000, 999999);
        $duplicate_value = UsersModel::where('email',$data['email'])->where('roles',2)->first();
        if(!$duplicate_value){
            if($signup->save()){
                $data['code'] = $verification_code;
                $vcode = new VcodeModel();
                $vcode->userid = $signup->id;
                $vcode->code = $verification_code;
                $vcode->save();
                
                Mail::to($data['email'])->send(new SendMail($data));
                return array('status'=>'success','token'=>$signup->remember_token);
            }
        }
        else{
            return array('status'=>'failed');
        }

    }

    public function gGsignup(Request $request){
        request()->validate([
            'email' => 'required|email|unique:users_models',
            'fname' => 'required',
            'lname' => 'required',
            'avatar' => 'required'
            ]);
        $data = $request->all();
        $signup = new UsersModel();
        $signup->email = $data['email'];
        $signup->remember_token = Str::random(60);
        $signup->roles = 2;
        $signup->status = 0;
        $signup->allowed = 0;
        $verification_code = "";
        $duplicate_value = UsersModel::where('email',$data['email'])->where('roles',2)->first();
        if(!$duplicate_value){
            if($signup->save()){
                $data['code'] = $verification_code;
                $vcode = new VcodeModel();
                $vcode->userid = $signup->id;
                $vcode->code = $verification_code;
                $vcode->save();
                $profile = new ProfileModel();
                $profile->roles = 2;
                $profile->userid = $signup->id;
                $profile->fname = $data['fname'];
                $profile->lname = $data['lname'];
                if($data['avatar'] != null){
                    $avatarname = Str::random(20).date("Y-m-d");
                    $real_img = file_get_contents($data['avatar']);
                    Storage::disk('s3')->put($avatarname.".png", $real_img);
                    $profile->avatar = $avatarname.".png";
                }
                $profile->save();
                return array('status'=>'success','token'=>$signup->remember_token);
            }
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function signupGCe(Request $request){
        request()->validate([
            'token' => 'required',
            'email' => 'required|email|unique:users_models'
            ]);
        $data = $request->all();
        $duplicate_value = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $singup = UsersModel::find($duplicate_value['id']);
        $singup->email = $data['email'];
        $vcode = VcodeModel::where('userid',$duplicate_value['id'])->first();
        $vcode->code = mt_rand(100000, 999999);
        if($singup->save() && $vcode->save()){
            $data['code'] = $vcode->code;
            Mail::to($data['email'])->send(new SendMail($data));
            return array('status'=>'success','token'=>$data['token']);
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function signupGConE(Request $request){
        request()->validate([
            'token' => 'required',
            'code' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->where('code',$data['code'])->first();
        if($duplicate_value){
            UsersModel::where('remember_token',$data['token'])->where('roles',2)->update(['email_verified_at' => date("Y-m-d H:i:s")]);
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function signupGPwd(Request $request){
        request()->validate([
            'token' => 'required',
            'password' => 'required|min:8',
            ]);
        $data = $request->all();
        $duplicate_value = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $duplicate_value->password = Hash::make($data['password']);
        if($duplicate_value->save()){
            return array('status'=>'success','token'=>$data['token']);
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function signupGP(Request $request){
        request()->validate([
            'token' => 'required',
            'phone' => 'required',
            'countryISO' => 'required',
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $duplicate_value = ProfileModel::where('userid',$result['id'])->where('roles',2)->first();
        if($duplicate_value){
            $profile = ProfileModel::find($duplicate_value['id']);
        }
        else{
            $profile = new ProfileModel();
        }
        $profile->roles = 2;
        $profile->userid = $result['id'];
        $profile->phone = $data['phone'];
        $profile->countryISO = $data['countryISO'];
        if($profile->save()){
            $verification_code = mt_rand(100000, 999999);
            $vcode = VcodeModel::where('userid',$result['id'])->first();
            $vcode->codeP = $verification_code;
            $vcode->save();
            $message = "Verification Code: ".$verification_code." From Flexhealth";
            
            $this->twclient->messages->create($data['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            return array('status'=>'success','token'=>$data['token']);
        }
        else{
            return array('status'=>'failed');
        }

    }
    public function signupGConP(Request $request){
        request()->validate([
            'token' => 'required',
            'code' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->where('codeP',$data['code'])->first();
        if($duplicate_value){
            UsersModel::where('remember_token',$data['token'])->where('roles',2)->update(['status' => 1]);
            $email = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
            $result = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*', 'profile_models.fname', 'profile_models.lname','profile_models.avatar','profile_models.coverimg','profile_models.phone', 'profile_models.address')
            ->where('email',$email['email'])
            ->first();
            $userdata['email'] = $result->email;
            $userdata['phone'] = $result->phone;
            $userdata['address'] = $result->address;
            $userdata['fname'] = $result->fname;
            $userdata['lname'] = $result->lname;
            $userdata['avatar'] = $result->avatar;
            $userdata['coverimg'] = $result->coverimg;
            $userdata['usertype'] = $result->roles;
            return array('status'=>'success','token'=>$data['token'],'userdata'=>$userdata);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function signupGProfile(Request $request){
        request()->validate([
            'servicetype' => 'required'
            ]);
        $data = $request->all();
        $languagelist = DB::table('language_models')->where('status',1)->orderBy('name')->select('id','name')->get();
        $exspeclist = DB::table('exspec_models')->where('status',1)->orderBy('name')->select('id','name')->get();
        $licenselist = DB::table('license_models')->where('status',1)->orderBy('name')->select('id','name')->get();
        $servicelist = DB::table('service_models')->where('type',$data['servicetype'])->where('status',1)->orderBy('name')->select('id','name')->get();
        return json_encode(array('language'=>$languagelist,'exspec'=>$exspeclist,'license'=>$licenselist,'service'=>$servicelist));
    }
    public function signupGProfileDone(Request $request){
        request()->validate([
            'token' => 'required',
            'userdata' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $profile = ProfileModel::where('userid',$result['id'])->first();
        //var_dump($data['userdata']);exit;
        $profile->fname = $data['userdata']['firstname'];
        $profile->lname = $data['userdata']['lastname'];
        $profile->gender = $data['userdata']['gender'];
        $profile->dob = date("Y-m-d",strtotime($data['userdata']['birthday']));

        if($profile->avatar != null && $profile->avatar != ""){
            Storage::disk('s3')->delete($profile->avatar);
        }

        if($data['userdata']['avatar'] != null){
            $avatarname = Str::random(20).date("Y-m-d");
            $blobtoimg = explode(',', $data['userdata']['avatar']);
            $real_img = base64_decode($blobtoimg[1]);
            Storage::disk('s3')->put($avatarname.".png", $real_img);
            $profile->avatar = $avatarname.".png";
        }
        $profile->address = $data['userdata']['address'];
        $profile->long = $data['userdata']['longitude'];
        $profile->lat = $data['userdata']['latitude'];
        $profile->language = json_encode($data['userdata']['language']);
        $profile->exspec = json_encode($data['userdata']['expertise']);
        $profile->license = json_encode($data['userdata']['license']);
        $profile->service = $data['userdata']['service'];
        $profile->hiretype = $data['userdata']['hiremethod'];
        $profile->serviceactivity = json_encode($data['userdata']['activities']);
        $profile->live_in = $data['userdata']['live_in'];
        if($profile->save()){
            for($i = 0;$i < count($data['userdata']['service_time']); $i++){
                $sschedule = new sscheduleModel();
                $sschedule->userid = $result['id'];
                $sschedule->week = $data['userdata']['service_time'][$i]['name'];
                if($data['userdata']['service_time'][$i]['checked'])
                    $sschedule->checked = 1;
                else
                    $sschedule->checked = 0;
                $sschedule->start = $data['userdata']['service_time'][0]['start'];
                $sschedule->end = $data['userdata']['service_time'][0]['end'];
                $sschedule->save();
            }
            for($i = 0;$i < count($data['userdata']['interview_time']); $i++){
                $ischedule = new ischeduleModel();
                $ischedule->userid = $result['id'];
                $ischedule->week = $data['userdata']['interview_time'][$i]['name'];
                if($data['userdata']['interview_time'][$i]['checked'])
                    $ischedule->checked = 1;
                else
                    $ischedule->checked = 0;
                $ischedule->start = $data['userdata']['interview_time'][$i]['start'];
                $ischedule->end = $data['userdata']['interview_time'][$i]['end'];
                $ischedule->save();
            }
            UsersModel::where('remember_token',$data['token'])->where('roles',2)->update(['allowed' => 0,'submit' => 1]);
            $email = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
            $result = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*', 'profile_models.fname', 'profile_models.lname','profile_models.avatar','profile_models.coverimg','profile_models.phone', 'profile_models.address')
            ->where('email',$email['email'])
            ->first();
            $userdata['email'] = $result->email;
            $userdata['phone'] = $result->phone;
            $userdata['address'] = $result->address;
            $userdata['fname'] = $result->fname;
            $userdata['lname'] = $result->lname;
            $userdata['avatar'] = $result->avatar;
            $userdata['coverimg'] = $result->coverimg;
            $userdata['usertype'] = $result->roles;
            $userdata['payflag'] = $result->payflag;
            return array('status'=>'success','token'=>$data['token'],'userdata'=>$userdata);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function signupC(Request $request){
        if(!$request->socialFlag){
            request()->validate([
                'email' => 'required|email|unique:users_models',
                'password' => 'required|min:8',
                'dob' => 'required',
                'phone' => 'required',
                'countryISO' => 'required',
                'paymethod' => 'required'
                ]);
            $data = $request->all();
            $signup = new UsersModel();
            $signup->email = $data['email'];
            $signup->password = Hash::make($data['password']);
            $signup->remember_token = Str::random(60);
            $signup->roles = 1;
            $signup->status = 0;
            $signup->allowed = 0;
            $signup->submit = 1;
            $signup->payflag = 1;
            $verification_code = mt_rand(100000, 999999);
            $verification_codeforP = mt_rand(100000, 999999);
            $duplicate_value = UsersModel::where('email',$data['email'])->where('roles',1)->first();
            if(!$duplicate_value){
                if($signup->save()){
                    $data['code'] = $verification_code;
                    $vcode = new VcodeModel();
                    $vcode->userid = $signup->id;
                    $vcode->code = $verification_code;
                    $vcode->codeP = $verification_codeforP;
                    $vcode->save();
                    $profile = new ProfileModel();
                    $profile->roles = 1;
                    $profile->userid = $signup->id;
                    $profile->dob = date("Y-m-d",strtotime($data['dob']));
                    if($data['avatar'] != null){
                        $avatarname = Str::random(20).date("Y-m-d");
                        $blobtoimg = explode(',', $data['avatar']);
                        $real_img = base64_decode($blobtoimg[1]);
                        Storage::disk('s3')->put($avatarname.".png", $real_img);
                        $profile->avatar = $avatarname.".png";
                    }
                    $profile->phone = $data['phone'];
                    $profile->countryISO = $data['countryISO'];
                    $message = "Verification Code: ".$verification_codeforP." From Flexhealth";
                    $this->twclient->messages->create($data['phone'], 
                            ['from' => $this->twilio_number, 'body' => $message] );
                    $profile->paymethod = $data['paymethod'];
                    $profile->save();
                    Mail::to($data['email'])->send(new SendMail($data));
                    return array('status'=>'success','token'=>$signup->remember_token);
                }
            }
            else{
                return array('status'=>'failed');
            }
        }
        else{
            request()->validate([
                'email' => 'required|email|unique:users_models',
                'fname' => 'required',
                'lname' => 'required',
                'avatar' => 'required',
                'dob' => 'required',
                'phone' => 'required',
                'countryISO' => 'required',
                'paymethod' => 'required'
                ]);
            $data = $request->all();
            $duplicate_value = UsersModel::where('email',$data['email'])->where('roles',1)->first();
            if(!$duplicate_value){
                $signup = new UsersModel();
                $signup->email = $data['email'];
                $signup->remember_token = Str::random(60);
                $signup->roles = 1;
                $signup->status = 0;
                $signup->allowed = 0;
                $signup->submit = 1;
                $signup->payflag = 1;
                $signup->email_verified_at = date("Y-m-d H:i:s");
                if($signup->save()){
                    $verification_code = "";
                    $verification_codeforP = mt_rand(100000, 999999);
                    $vcode = new VcodeModel();
                    $vcode->userid = $signup->id;
                    $vcode->code = $verification_code;
                    $vcode->codeP = $verification_codeforP;
                    $vcode->save();
                    $profile = new ProfileModel();
                    $profile->roles = 1;
                    $profile->userid = $signup->id;
                    $profile->fname = $data['fname'];
                    $profile->lname = $data['lname'];
                    $profile->dob = date("Y-m-d",strtotime($data['dob']));
                    if($data['avatar'] != null){
                        $avatarname = Str::random(20).date("Y-m-d");
                        $real_img = file_get_contents($data['avatar']);
                        Storage::disk('s3')->put($avatarname.".png", $real_img);
                        $profile->avatar = $avatarname.".png";
                    }
                    $profile->phone = $data['phone'];
                    $profile->countryISO = $data['countryISO'];
                    $message = "Verification Code: ".$verification_codeforP." From Flexhealth";
                    $this->twclient->messages->create($data['phone'], 
                            ['from' => $this->twilio_number, 'body' => $message] );
                    $profile->paymethod = $data['paymethod'];
                    $profile->save();
                    return array('status'=>'success','token'=>$signup->remember_token);
                }
            }
            else{
                return array('status'=>'failed');
            }
        }
        
    }
    public function signupCCon(Request $request){
        request()->validate([
            'token' => 'required',
            'codeP' => 'required'
            ]);
        $data = $request->all();
        if($data['codeE'] == null)
            $data['codeE'] = "";
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',1)->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->where('code',$data['codeE'])->where('codeP',$data['codeP'])->first();
        if($duplicate_value){
            UsersModel::where('remember_token',$data['token'])->where('roles',1)->update(['email_verified_at' => date("Y-m-d H:i:s"),'status' => 1,'allowed' => 1]);
            $email = UsersModel::where('remember_token',$data['token'])->where('roles',1)->first();
            $result1 = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*', 'profile_models.fname', 'profile_models.lname','profile_models.avatar','profile_models.coverimg','profile_models.phone', 'profile_models.address')
            ->where('email',$email['email'])
            ->first();

            $userdata['email'] = $result1->email;
            $userdata['phone'] = $result1->phone;
            $userdata['address'] = $result1->address;
            $userdata['fname'] = $result1->fname;
            $userdata['lname'] = $result1->lname;
            $userdata['avatar'] = $result1->avatar;
            $userdata['coverimg'] = $result1->coverimg;
            $userdata['usertype'] = $result1->roles;
            return array('status'=>'success','token'=>$data['token'],'userdata'=>$userdata);
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function signupREC(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $duplicate_value = VcodeModel::where('userid',$result['id'])->first();
        $vcode = VcodeModel::find($duplicate_value['id']);
        $vcode->code = mt_rand(100000, 999999);
        if($vcode->save()){
            $data['code'] = $vcode->code;
            $get_email_field = UsersModel::where('remember_token',$data['token'])->first();
            Mail::to($get_email_field['email'])->send(new SendMail($data));
            return array('status'=>'success');
        }
        else{
            return array('status'=>'failed');
        }
    }
    public function signupRPC(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->first();
        $phone = ProfileModel::where('userid',$result['id'])->first();
        $verification_code = mt_rand(100000, 999999);
        $vcode = VcodeModel::where('userid',$result['id'])->first();
        $vcode->codeP = $verification_code;
        $vcode->save();
        $message = "Verification Code: ".$verification_code." From Flexhealth";
        $this->twclient->messages->create($phone['phone'], 
                ['from' => $this->twilio_number, 'body' => $message] );
        //if($vcode->save()){
        return array('status'=>'success');
        //}
        // else{
        //     return array('status'=>'failed');
        // }
    }
    public function getfile(Request $request){
        
        $result = UsersModel::where('remember_token',$request->token)->select('id')->first();
        $profile = ProfileModel::where('userid',$result->id)->first();
        $tmp = Storage::disk('s3')->delete("/licensefile/".$result->id);
        PLicenseFileModel::where('provider',$result->id)->delete();
        foreach($request->file() as $key=>$fileArray){
            $filename = $key;
            $cover = $fileArray;
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('s3')->put("/licensefile/".$result->id."/".$filename.'.'.$extension , File::get($cover));
            $lfile = new PLicenseFileModel();
            $lfile->provider = $result->id;
            $lfile->license = $key;
            $lfile->file = $filename.'.'.$extension;
            $lfile->save();
        }
    }
    public function profilepay(Request $request){
        $user = UsersModel::where('remember_token',$request->token)->first();
        $customer = Customer::create(array(
            'email' => $user['email'],
            'source'  => $request->stripe_token
        ));
        $stripe = Charge::create ([
                'customer' => $customer->id,
                "amount" => 30 * 100,
                "currency" => "usd",
                "description" => "Profile Payment"
        ]);
        if($stripe){
            $transaction = new TransactionModel();
            $transaction->type = 3;
            $transaction->tranid = mt_rand(100000000, 999999999);
            $transaction->amount = 30;
            $transaction->chargeid = $stripe['id'];
            $transaction->customerid = $stripe['customer'];
            $transaction->cardid = $stripe['payment_method'];
            $transaction->save();
            UsersModel::where('remember_token',$request->token)->update(['payflag' => 1]);
            return array('status'=>"success");
        }
        else{
            return array('status'=>"failed");
        }

    }
    public function createCandidate(Request $request){
        request()->validate([
            'token' => 'required',
            'data' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $profile = ProfileModel::where('userid',$result['id'])->first();
        $jsonArr = array('firstName'=>$data['data']['firstname'],
        'lastName'=>$data['data']['lastname'],
        'email'=>$data['data']['email'],
        );
        $ch=curl_init();
        // user credencial
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,"https://api.accuratebackground.com/v3/candidate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($jsonArr));
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $resultArr = json_decode($response);
        curl_close($ch);
        $profile->candidateid = $resultArr->id;
        if($profile->save()){
            return array('status'=>"success",'ctoken'=>$resultArr->id);
        }
        else{
            return array('status'=>"falied");
        }
    }
    public function createOrder(Request $request){
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->where('roles',2)->first();
        $profile = ProfileModel::where('userid',$result['id'])->first();
        $jsonArr = array('candidateId'=>$profile['candidateid'],
        'packageType'=>"PKG_STANDARD",
        'workflow'=>"INTERACTIVE",
        'jobLocation'=>array(
            'country'=>'US',
            'region'=>$data['data']['region'],
            'city'=>$data['data']['city'],
        ),
        'additionalProductTypes[0].productType'=>"TERB"
        );
        $ch=curl_init();
        // user credencial
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,"https://api.accuratebackground.com/v3/order");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($jsonArr));
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $resultArr = json_decode($response);
        curl_close($ch);
        $profile->orderid = $resultArr->id;
        if($profile->save()){
            return array('status'=>"success");
        }
        else{
            return array('status'=>"falied");
        }
    }
    public function getdescription(Request $request){
        if($request->type == "hire"){
            $result = DescriptionModel::where('type',1)->select('id','subtype','name')->get();
            if($result){
                return array('status'=>"success",'desc'=>$result);
            }
            else{
                return array('status'=>"failed");
            }
        }
    }
}
