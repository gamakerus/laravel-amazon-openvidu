<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\UsersModel;
use App\VcodeModel;
use App\ProfileModel;
use App\sscheduleModel;
use App\ischeduleModel;
use App\LanguageModel;
use App\ExspecModel;
use App\LicenseModel;
use App\ServiceModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Mail;
use App\Mail\JobMail;
use App\Mail\InviteUsers;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Twilio\Rest\Client;

class ProviderManageController extends Controller
{
    public function __construct()
    {
        $this->twilio_account_sid = env("TWILIO_ACCOUNT_SID");
        $this->twilio_auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->twclient = new Client($this->twilio_account_sid, $this->twilio_auth_token);

    }
    public function index($providers = null,$type = null)
    {
        
        if(Session::get('remember_token')){
            $providersCnt = DB::table('profile_models')
                ->where('profile_models.roles',2)
                ->count();
            if($providers == null){
                $providers = DB::table('users_models')
                ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
                ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
                ->where('users_models.roles',2)
                ->get();
                session()->put('csvArray', $providers);
            }
            $flexhealth_providers = DB::table('profile_models')
            ->where('profile_models.roles',2)->where('profile_models.hiretype',1)
            ->count();
            $directhire_providers = DB::table('profile_models')
            ->where('profile_models.roles',2)->where('profile_models.hiretype',2)
            ->count();
            $verified_providers = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',2)->where('users_models.status',1)
            ->count();
            $caregivers = ProfileModel::where('roles',2)->where('service',1)->count();
            $nurses = ProfileModel::where('roles',2)->where('service',2)->count();
            $therapists = ProfileModel::where('roles',2)->where('service',3)->count();
            $language = LanguageModel::where('status',1)->orderBy('name')->get();
            $exspec = ExspecModel::where('status',1)->orderBy('name')->get();
            $license = LicenseModel::where('status',1)->orderBy('name')->get();
            $service = ServiceModel::where('status',1)->where('type',1)->orderBy('name')->get();
            return view('pages/providermanagement',['pagename'=>'provider','providers'=>$providers,'providersCnt'=>$providersCnt,'flexhealth_providers'=>$flexhealth_providers,'directhire_providers'=>$directhire_providers,'verified_providers'=>$verified_providers,'caregivers'=>$caregivers,'nurses'=>$nurses,'therapists'=>$therapists,'language'=>$language,'exspec'=>$exspec,'license'=>$license,'service'=>$service,'servicetype'=>$type]);
        }
            return Redirect::to("/admin");
    }
    public function deleteprovider(Request $request){
        $result1 = UsersModel::where('id',$request->id)->where('roles',2)->delete();
        $result2 = VcodeModel::where('userid',$request->id)->delete();
        $avatar = ProfileModel::where('userid',$request->id)->where('roles',2)->first();
        if(isset($avatar['avatar']) && $avatar['avatar'])
            Storage::disk('s3')->delete($avatar['avatar']);
        Storage::disk('s3')->delete("/licensefile/".$request->id);
        $result3 = ProfileModel::where('userid',$request->id)->where('roles',2)->delete();
        $result4 = sscheduleModel::where('userid',$request->id)->delete();
        $result5 = ischeduleModel::where('userid',$request->id)->delete();

        if($result1)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function banprovider(Request $request){
        $result = UsersModel::where('id',$request->id)->update(['allowed' => 0]);
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function unbanprovider(Request $request){
        $result = UsersModel::where('id',$request->id)->update(['allowed' => 1]);
        if($result){
            $email = UsersModel::where('id',$request->id)->select('email')->first();
            $phone = ProfileModel::where('userid',$request->id)->select('phone')->first();
            $message = "You are approved by system so you can use your account now";
            $this->twclient->messages->create($phone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "You are approved on Flexhealth",
                'user' =>"Support",
                'message' => $message
            );
            Mail::to($email['email'])->send(new JobMail($data));
            return response()->json([
                'success' => 'success'
            ]);
        }
            
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function chosenprovider(Request $request){
        $chosenprovider = DB::table('users_models')
        ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
        ->select('users_models.id as user_id','users_models.email','users_models.remember_token','users_models.roles','profile_models.*')
        ->where('users_models.roles',2)
        ->where('users_models.id',$request->id)
        ->first();
        $languageid = json_decode($chosenprovider->language);
        $language = LanguageModel::whereIn('id',$languageid)->select('name')->get();
        $exspecid = json_decode($chosenprovider->exspec);
        $exspec = ExspecModel::whereIn('id',$exspecid)->select('name')->get();
        $serviceactivityid = json_decode($chosenprovider->serviceactivity);
        $serviceactivity = ServiceModel::whereIn('id',$serviceactivityid)->select('name')->get();
        $licenseid = json_decode($chosenprovider->license);
        $license = DB::table('license_models')
        ->leftJoin('p_license_file_models', 'p_license_file_models.license', '=', 'license_models.id')
        ->select('license_models.id','license_models.name','p_license_file_models.file')
        ->where('p_license_file_models.provider',$request->id)->whereIn('license_models.id',$licenseid)
        ->get();
        $sschedule = sscheduleModel::where('userid',$chosenprovider->user_id)->where('checked',1)->get();
        $ischedule = ischeduleModel::where('userid',$chosenprovider->user_id)->where('checked',1)->get();
        echo json_encode(array($chosenprovider,$language,$exspec,$serviceactivity,$license,$sschedule,$ischedule));
    }
    public function getchosenactivities(Request $request){
        $service = ServiceModel::where('status',1)->where('type',$request->value)->orderBy('name')->get();
        echo json_encode($service);
    }
    public function provideraddForm(Request $request){
        request()->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users_models',
        ]);
        
        $data = $request->all();
        $newprovider = new UsersModel();
        $newprovider->email = $data['email'];
        $newprovider->email_verified_at = date("Y-m-d H:i:s");
        $newprovider->roles = 2;
        $newprovider->status = 1;
        $newprovider->allowed = 1;
        $newprovider->remember_token = Str::random(60);
        if($newprovider->save()){
            $newproviderPro = new ProfileModel();
            $newproviderPro->fname = $data['fname'];
            $newproviderPro->lname = $data['lname'];
            if($data['phone'] != null && $data['phone'] != ""){
                $newproviderPro->countryISO = strtoupper($data['countryISO']);
                $newproviderPro->phone = "+".$data['dialCode']." ".$data['phone'];
            }
            $newproviderPro->gender = $data['gender'];
            if($data['dob'] != null && $data['dob'] != "")
                $newproviderPro->dob = date("Y-m-d",strtotime($data['dob']));
            $newproviderPro->roles = 2;
            $newproviderPro->service = $data['service'];
            $newproviderPro->hiretype = $data['hiretype'];
            $newproviderPro->serviceactivity = $data['service_act_tmp'];
            $newproviderPro->exspec = $data['exspec_tmp'];
            $newproviderPro->language = $data['language_tmp'];
            $newproviderPro->license = $data['license_tmp'];
            
            $newproviderPro->userid = $newprovider->id;
            $newproviderPro->save();
            $newVcode = new VcodeModel();
            $newVcode->userid = $newprovider->id;
            $newVcode->code = mt_rand(100000, 999999);
            $newVcode->codeP = mt_rand(100000, 999999);
            $newVcode->save();
            $data['token'] = $newprovider->remember_token;
            Mail::to($data['email'])->send(new InviteUsers($data));
            return Redirect::to('/admin/providermanagement');
        }
    }
    public function chosenserviceProviders(Request $request){
        $providers = DB::table('users_models')
        ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
        ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
        ->where('users_models.roles',2)
        ->where('profile_models.service',$request->chosen_type)
        ->get();
        session()->put('csvArray', $providers);
        return $this->index($providers,$request->chosen_type);
    }
    public function expertCSV(){
        // var_dump(count(Session::get('csvArray')));exit;
        // return Excel::download(new ProfileModel, 'disney.csv');
        $providersArray = [];
        $tmpproviders = Session::get('csvArray');
        for($i = 0; $i < count($tmpproviders); $i++){
            $providersArray[$i]['id'] = $tmpproviders[$i]->id;
            $providersArray[$i]['joineddate'] = date("D, M j Y",strtotime($tmpproviders[$i]->created_at));
            $providersArray[$i]['name'] = $tmpproviders[$i]->fname." ".$tmpproviders[$i]->lname;
            $providersArray[$i]['email'] = $tmpproviders[$i]->email;
            $providersArray[$i]['phone'] = $tmpproviders[$i]->phone;
            $providersArray[$i]['status'] = $tmpproviders[$i]->status;
            $providersArray[$i]['allowed'] = $tmpproviders[$i]->allowed;
        }
        $delimiter = ",";
        $filename = "providers_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('ID', 'Joined Date', 'Name', 'Email', 'Phone', 'Verified', 'Status');
        fputcsv($f, $fields, $delimiter);
        // var_dump($providersArray);exit;
        //output each row of the data, format line as csv and write to file pointer
        foreach ($providersArray as $line) {
            $status = ($line['status'] == '1')?'Active':'Inactive';
            $allowed = ($line['allowed'] == '1')?'Approved':'Not approved';
            $lineData = array($line['id'], $line['joineddate'], $line['name'], $line['email'], $line['phone'], $status, $allowed);
            fputcsv($f, $lineData, $delimiter);
        }
        
        //move back to beginning of file
        fseek($f, 0);
        
        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        //output all remaining data on a file pointer
        fpassthru($f);
        // return Redirect::to('/admin/providermanagement');
    }
    public function downloadPDF() {
        $data = [
            'heading' => "Provider List",
            'users' => Session::get('csvArray'),
                 
              ];
          
          $pdf = PDF::loadView('/pdf/userpdf', $data);  
          return $pdf->download('providerlist'.date('Y-m-d').'.pdf');
    }
    public function viewjobdetail(Request $request){
        $jobdetail = DB::table('job_models')
        ->select('job_models.*')->where('provider',$request->id)->latest('job_models.created_at')->limit(3)->get();
        echo json_encode($jobdetail);
    }
    public function viewalljob(Request $request){
        $name = ProfileModel::where('userid',$request->id)->select('fname','lname')->first();
        $jobdetail = DB::table('job_models')
        ->select('job_models.*')->where('provider',$request->id)->latest('job_models.created_at')->get();
        return view('pages/viewalljob',['pagename'=>'user','jobdetail'=>$jobdetail,'name'=>$name->fname.' '.$name->lname]);
    }
}
