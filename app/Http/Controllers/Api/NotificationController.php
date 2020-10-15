<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\UsersModel;
use App\NotificationModel;
use App\ProfileModel;
use Mail;
use App\Mail\ContactEmail;

class NotificationController extends Controller
{
    public function checkunread(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->select('id','roles')->first();
        $unreadcnt1 = NotificationModel::where('user',$result['roles'])->whereNull('checked')->count();
        $unreadcnt2 = NotificationModel::where('specific',$result['id'])->whereNull('checked')->count();
        $unreadcnt3 = NotificationModel::where('user',$result['roles'])->where('checked','not like',"%\"".$result['id']."\"%")->count();
        $unreadcnt4 = NotificationModel::where('specific',$result['id'])->where('checked','not like',"%\"".$result['id']."\"%")->count();
        return array('status'=>'success','unread'=>$unreadcnt1+$unreadcnt2+$unreadcnt3+$unreadcnt4);
        
    }
    public function viewnotification(Request $request){
        $perPage = 10;
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $result = UsersModel::where('remember_token',$data['token'])->select('id','roles')->first();
        $notarray = NotificationModel::where('user',$result['roles'])->orWhere('specific',$result['id'])
        ->orderBy('created_at','desc')
        ->select('id','title','description','filename','checked','created_at');
        $totalpage = round(count($notarray->get())/$perPage);
        if($request->page == null || $request->page == 0)
            $notarray = $notarray->offset(0)->limit($perPage)->get();
        else{
            $notarray = $notarray->offset($perPage*($request->page-1))->limit($perPage)->get();
        }
        for($i = 0;$i < count($notarray);$i++){
            if($notarray[$i]->checked == null){
                $notarray[$i]->checked = 0;
            }
            else if(strpos($notarray[$i]->checked, '"'.$result['id'].'"') >= 0){
                $notarray[$i]->checked = 1;
            }
            else
                $notarray[$i]->checked = 0;
        }
        return array('status'=>'success','notification'=>$notarray,'totalpage'=>$totalpage);
    }
    public function readnotification(Request $request){
        request()->validate([
            'token' => 'required'
            ]);
        $data = $request->all();
        $user = UsersModel::where('remember_token',$data['token'])->select('id')->first();
        $chosennot = NotificationModel::where('id',$request->id)->select("checked")->first();
        if($chosennot->checked == null || $chosennot->checked == ""){
            $result = NotificationModel::where('id',$request->id)->update(['checked'=>'"'.$user['id'].'",']);
            if($result){
                return array('status'=>'success');
            }
            else{
                return array('status'=>'failed');
            }
        }
        else if(strpos($chosennot->checked, '"'.$user['id'].'"') >= 0){
            $result = NotificationModel::where('id',$request->id)->update(['checked'=>$chosennot->checked.'"'.$user['id'].'",']);
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
    public function contact(Request $request){
        $data = $request->all();
        Mail::to("flexinghealth@gmail.com")->send(new ContactEmail($data['data']));
        Mail::to("consolbryant@gmail.com")->send(new ContactEmail($data['data']));
        Mail::to("support@flexhealth.me")->send(new ContactEmail($data['data']));
        return array('status'=>'success');
    }
}
