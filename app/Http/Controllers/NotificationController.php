<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use App\UsersModel;
use App\ProfileModel;
use App\NotificationModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
date_default_timezone_set('America/Chicago');
class NotificationController extends Controller
{
    public function index($notificationlist = null,$chosenfilter = null)
    {
        
        if(Session::get('remember_token')){
            $users = ProfileModel::orderBy("fname")->select("userid","fname","lname")->get();
            if($notificationlist == null){
                $notificationlist = DB::table('notification_models')
                ->leftJoin('profile_models', 'notification_models.specific', '=', 'profile_models.userid')
                ->select('notification_models.*','profile_models.fname','profile_models.lname')
                ->get();
            }
            return view('pages/notification',['pagename'=>'notification','users'=>$users,'notificationlist'=>$notificationlist,'chosenfilter'=>$chosenfilter]);
        }
            return Redirect::to("/admin");
    }
    public function pushnotification(Request $request){
        $data = $request->all();
        $notification = new NotificationModel();

        if(isset($data['file']) && $data['file']){
            $cover = $request->file('file');
            //$extension = $cover->getClientOriginalExtension();
            $filename = $cover->getClientOriginalName();
            $notification->filename = $filename;
            Storage::disk('s3')->put("/notification/".$filename , File::get($cover));
        }
        $notification->title = $request->title;
        $notification->description = $request->writearea;
        if($request->userlist != "1" && $request->userlist != "2"){
            $realUser = explode("_",$request->userlist);
            $notification->specific = $realUser[0];
            $notification->user = 0;
        }  
        else
            $notification->user = $request->userlist;
        $notification->notid = mt_rand(100000000, 999999999);
        if($notification->save()){
            return Redirect::to("/admin/notification")->with('info','Sent Successfully');;
        }
        else{
            return Redirect::to("/admin/notification")->with('info','Sent failed');;
        }
    }
    public function deletenotification(Request $request){
        $img = NotificationModel::where('id',$request->id)->first();
        if($img->filename)
            Storage::disk('s3')->delete("/notification/".$img['filename']);
        $result = NotificationModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function filterviewfornotification(Request $request){
        session()->put('notfilter', $request->filtervalue);
        $notificationlist = DB::table('notification_models')
        ->leftJoin('profile_models', 'notification_models.specific', '=', 'profile_models.userid')
        ->select('notification_models.*','profile_models.fname','profile_models.lname');
        if($request->filtervalue == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $notificationlist = $notificationlist->where('user',1);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        elseif($request->filtervalue == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $notificationlist = $notificationlist->where('user',2);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        $notificationlist = $notificationlist->get();
        return $this->index($notificationlist,$request->filtervalue);
    }
    public function chosennotification(Request $request){
        $notificationlist = DB::table('notification_models')
        ->select('notification_models.*')
        ->where('id',$request->id)
        ->first();
        echo json_encode($notificationlist);
    }
    public function expertCSV(){
        $notArray = [];
        $notificationlist = DB::table('notification_models')
        ->leftJoin('profile_models', 'notification_models.specific', '=', 'profile_models.userid')
        ->select('notification_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('notfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $notificationlist = $notificationlist->where('user',1);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('notfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $notificationlist = $notificationlist->where('user',2);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        $notificationlist = $notificationlist->get();


        for($i = 0; $i < count($notificationlist); $i++){
            $notArray[$i]['sentdate'] = date("D, M j Y",strtotime($notificationlist[$i]->created_at));
            if($notificationlist[$i]->user == 1)
                $notArray[$i]['sento'] = "All Users";
            else if($notificationlist[$i]->user == 2)
                $notArray[$i]['sento'] = "All Providers";
            else
                $notArray[$i]['sento'] = $notificationlist[$i]->fname." ".$notificationlist[$i]->lname;
            $notArray[$i]['notid'] = $notificationlist[$i]->notid;
            $notArray[$i]['title'] = $notificationlist[$i]->title;
        }
        $delimiter = ",";
        $filename = "notification_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Sent Date', 'Sent To', 'Notification ID', 'Title');
        fputcsv($f, $fields, $delimiter);
        foreach ($notArray as $line) {
            $lineData = array($line['sentdate'], $line['sento'], $line['notid'], $line['title']);
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
    public function downloadPDF() {
        $notificationlist = DB::table('notification_models')
        ->leftJoin('profile_models', 'notification_models.specific', '=', 'profile_models.userid')
        ->select('notification_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('notfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $notificationlist = $notificationlist->where('user',1);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('notfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $notificationlist = $notificationlist->where('user',2);
            $notificationlist = $notificationlist->whereIn('specific', $users, 'or');
        }
        $notificationlist = $notificationlist->get();
        $data = [
            'heading' => "Notification List",
            'notification' => $notificationlist,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/notificationpdf', $data);  
          return $pdf->download('notification'.date('Y-m-d').'.pdf');
    }
}
