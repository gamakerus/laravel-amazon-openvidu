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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mail;
use PDF;
use App\Mail\InviteUsers;
use Illuminate\Support\Facades\Hash;
class UserManageController extends Controller
{
    public function index()
    {
        
        if(Session::get('remember_token')){
            $users = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',1)
            ->get();
            $approved_users = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',1)->where('users_models.allowed',1)
            ->count();
            $verified_users = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',1)->where('users_models.status',1)
            ->count();
            return view('pages/usermanagement',['pagename'=>'user','users'=>$users,'approved_users'=>$approved_users,'verified_users'=>$verified_users]);
        }
            return Redirect::to("/admin");
    }
    public function deleteuser(Request $request){
        $result1 = UsersModel::where('id',$request->id)->where('roles',1)->delete();
        $result2 = VcodeModel::where('userid',$request->id)->delete();
        $avatar = ProfileModel::where('userid',$request->id)->where('roles',1)->first();
        if(isset($avatar['avatar']))
            Storage::disk('s3')->delete($avatar['avatar']);
        $result3 = ProfileModel::where('userid',$request->id)->where('roles',1)->delete();
        

        if($result1)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function banuser(Request $request){
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
    public function unbanuser(Request $request){
        $result = UsersModel::where('id',$request->id)->update(['allowed' => 1]);
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function chosenuser(Request $request){
        $chosenuser = DB::table('users_models')
        ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
        ->select('users_models.id as userid','users_models.email','users_models.remember_token','users_models.roles','profile_models.*')
        ->where('users_models.roles',1)
        ->where('users_models.id',$request->id)
        ->get();
        echo json_encode($chosenuser);
    }
    public function clientaddForm(Request $request){
        request()->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users_models',
            'paymethod' => 'required',
        ]);
        
        $data = $request->all();
        $newclient = new UsersModel();
        $newclient->email = $data['email'];
        $newclient->email_verified_at = date("Y-m-d H:i:s");
        $newclient->roles = 1;
        $newclient->status = 1;
        $newclient->allowed = 1;
        $newclient->remember_token = Str::random(60);
        if($newclient->save()){
            $newclientPro = new ProfileModel();
            $newclientPro->fname = $data['fname'];
            $newclientPro->lname = $data['lname'];
            $newclientPro->paymethod = $data['paymethod'];
            if($data['phone'] != null && $data['phone'] != ""){
                $newclientPro->countryISO = strtoupper($data['countryISO']);
                $newclientPro->phone = "+".$data['dialCode']." ".$data['phone'];
            }
            $newclientPro->gender = $data['gender'];
            if($data['dob'] != null && $data['dob'] != "")
                $newclientPro->dob = date("Y-m-d",strtotime($data['dob']));
            $newclientPro->roles = 1;
            $newclientPro->userid = $newclient->id;
            $newclientPro->save();
            $newVcode = new VcodeModel();
            $newVcode->userid = $newclient->id;
            $newVcode->code = mt_rand(100000, 999999);
            $newVcode->codeP = mt_rand(100000, 999999);
            $newVcode->save();
            $data['token'] = $newclient->remember_token;
            Mail::to($data['email'])->send(new InviteUsers($data));
            return Redirect::to('/admin/usermanagement');
        }
    }
    public function resetpwd(Request $request){
        request()->validate([
            'id' => 'required',
            'pwd' => 'required'
        ]);
        $data = $request->all();
        $result = UsersModel::where('id',$request->id)->update(['password' => Hash::make($request->pwd)]);
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function viewrequestservice(Request $request){
        $requestservice = DB::table('request_service_models')
        ->leftJoin('service_models', function ($join) {
            $join->on('request_service_models.service', 'like', DB::raw("concat('%,',service_models.id,',%')"))
            ->orOn('request_service_models.service', 'like', DB::raw("concat('%',service_models.id,']%')"))
            ->orOn('request_service_models.service', 'like', DB::raw("concat('%[',service_models.id,'%')"));
        })->select('request_service_models.*',DB::raw('group_concat(service_models.name SEPARATOR "<||>") as sname'))->where('client',$request->id)->groupBy('request_service_models.id')->latest('request_service_models.created_at')->limit(3)->get();
        echo json_encode($requestservice);
    }
    public function viewallrequest(Request $request){
        $name = ProfileModel::where('userid',$request->id)->select('fname','lname')->first();
        $requestservice = DB::table('request_service_models')
        ->leftJoin('service_models', function ($join) {
            $join->on('request_service_models.service', 'like', DB::raw("concat('%,',service_models.id,',%')"))
            ->orOn('request_service_models.service', 'like', DB::raw("concat('%',service_models.id,']%')"))
            ->orOn('request_service_models.service', 'like', DB::raw("concat('%[',service_models.id,'%')"));
        })->select('request_service_models.*',DB::raw('group_concat(service_models.name SEPARATOR "<||>") as sname'))->where('client',$request->id)->groupBy('request_service_models.id')->latest('request_service_models.created_at')->get();
        return view('pages/viewallrequest',['pagename'=>'user','requestservice'=>$requestservice,'name'=>$name->fname.' '.$name->lname]);
    }
    public function expertCSV(){
        // var_dump(count(Session::get('csvArray')));exit;
        // return Excel::download(new ProfileModel, 'disney.csv');
        $usersArray = [];
        $users = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',1)
            ->get();
        for($i = 0; $i < count($users); $i++){
            $usersArray[$i]['id'] = $users[$i]->id;
            $usersArray[$i]['joineddate'] = date("D, M j Y",strtotime($users[$i]->created_at));
            $usersArray[$i]['name'] = $users[$i]->fname." ".$users[$i]->lname;
            $usersArray[$i]['email'] = $users[$i]->email;
            $usersArray[$i]['phone'] = $users[$i]->phone;
            $usersArray[$i]['status'] = $users[$i]->status;
            $usersArray[$i]['allowed'] = $users[$i]->allowed;
        }
        $delimiter = ",";
        $filename = "users_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('ID', 'Joined Date', 'Name', 'Email', 'Phone', 'Verified', 'Status');
        fputcsv($f, $fields, $delimiter);
        // var_dump($usersArray);exit;
        //output each row of the data, format line as csv and write to file pointer
        foreach ($usersArray as $line) {
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
        
    }
    public function downloadPDFforusers() {
        $users = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.id as profileid','profile_models.fname', 'profile_models.lname','profile_models.phone')
            ->where('users_models.roles',1)
            ->get();
        $data = [
            'heading' => "User List",
            'users' => $users,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/userpdf', $data);  
          return $pdf->download('userlist'.date('Y-m-d').'.pdf');
    }
}
