<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\CancelrefundModel;
use App\InterviewListModel;
use App\NotificationModel;
use App\RequestServiceModel;

class DashboardController extends Controller
{
    public function index()
    {
        
        if(Session::get('remember_token')){
            $users = DB::table('users_models')
            ->select("*")
            ->where('users_models.roles',1)
            ->count();
            $approved_users = DB::table('users_models')
            ->select("*")
            ->where('users_models.roles',1)
            ->where('users_models.allowed',1)
            ->count();
            $providers = DB::table('users_models')
            ->select("*")
            ->where('users_models.roles',2)
            ->count();
            $flexhealth_providers = DB::table('users_models')
            ->leftJoin('profile_models', 'users_models.id', '=', 'profile_models.userid')
            ->select('users_models.*','profile_models.hiretype')
            ->where('users_models.roles',2)
            ->where('profile_models.hiretype',1)
            ->count();
            $transCnt = DB::table('transaction_models')
            ->where('transaction_models.type',1)
            ->count();
            $transAmount = DB::table('transaction_models')
            ->select(DB::raw('SUM(amount) as total'))
            ->where('type',1)
            ->get();
            $refundpending = CancelrefundModel::where('status',1)->count();
            $refundsolved = CancelrefundModel::where('status',2)->count();
            $interview = InterviewListModel::where('status',1)->count();

            $requests = DB::table('request_service_models')
            ->leftJoin('profile_models', 'request_service_models.client', '=', 'profile_models.userid')
            ->select('request_service_models.*','profile_models.fname','profile_models.lname')
            ->where('request_service_models.service',"0")->orderBy('created_at','desc')
            ->get();
            return view('pages/dashboard',['pagename'=>'dashboard','users'=>$users,'approved_users'=>$approved_users,'providers'=>$providers,'flexhealth_providers'=>$flexhealth_providers,'transCnt'=>$transCnt,'transAmount'=>$transAmount[0]->total,'refundpending'=>$refundpending,'refundsolved'=>$refundsolved,'interview'=>$interview,'requests'=>$requests]);
        }
            return Redirect::to("/admin");
    }
    public function replyrequest(Request $request){
        $not = new NotificationModel();
        $not->notid = mt_rand(100000000, 999999999);
        $not->user = 0;
        $not->title = "Reply from admin to your service requestion";
        $not->description = $request->desc;
        $not->specific = $request->userid;
        if($not->save()){
            RequestServiceModel::where('id',$request->requestid)->update(['flag'=>1]);
            return Redirect::to("/admin/dashboard");
        }
        else{
            return Redirect::to("/admin/dashboard");
        }
    }
}
