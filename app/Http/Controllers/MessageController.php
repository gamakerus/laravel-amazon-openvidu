<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use Session;
use DB;
use PDF;
use App\UsersModel;
use App\ProfileModel;
use App\MessageModel;
class MessageController extends Controller
{
    public function __construct()
    {
        $this->twilio_account_sid = env("TWILIO_ACCOUNT_SID");
        $this->twilio_auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->twclient = new Client($this->twilio_account_sid, $this->twilio_auth_token);
    }

    public function index($messagelist = null,$chosenfilter = null)
    {
        if(Session::get('remember_token')){
            $users = ProfileModel::orderBy("fname")->select("userid","fname","lname")->get();
            if($messagelist == null){
                $messagelist = DB::table('message_models')
                ->leftJoin('profile_models', 'message_models.specific', '=', 'profile_models.userid')
                ->select('message_models.*','profile_models.fname','profile_models.lname')
                ->get();
            }
            return view('pages/message',['pagename'=>'message','users'=>$users,'messagelist'=>$messagelist,'chosenfilter'=>$chosenfilter]);
        }
            return Redirect::to("/admin");
    }
    public function pushmessage(Request $request){
        $data = $request->all();
        $message = new MessageModel();

        $message->description = $request->desc;
        
        if($request->userlist != "1" && $request->userlist != "2"){
            $realUser = explode("_",$request->userlist);
            $message->specific = $realUser[0];
            $message->user = 0;
            $phone = ProfileModel::where('userid',$realUser[0])->select('phone')->first();
            $text = $request->desc;
            $this->twclient->messages->create($phone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $text] );
        }  
        else{
            $phone = ProfileModel::where('roles',$request->userlist)->select('phone')->get();
            $text = $request->desc;
            for($i = 0;$i < count($phone); $i++){
                $this->twclient->messages->create($phone[$i]['phone'], 
                    ['from' => $this->twilio_number, 'body' => $text] );
            }
            $message->user = $request->userlist;
        }
        $message->msgid = mt_rand(100000000, 999999999);
        if($message->save()){
            return Redirect::to("/admin/message")->with('info','Sent Successfully');;
        }
        else{
            return Redirect::to("/admin/message")->with('info','Sent failed');;
        }
    }
    public function filterviewformessage(Request $request){
        session()->put('msgfilter', $request->filtervalue);
        $messagelist = DB::table('message_models')
        ->leftJoin('profile_models', 'message_models.specific', '=', 'profile_models.userid')
        ->select('message_models.*','profile_models.fname','profile_models.lname');
        if($request->filtervalue == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $messagelist = $messagelist->where('user',1);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        elseif($request->filtervalue == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $messagelist = $messagelist->where('user',2);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        $messagelist = $messagelist->get();
        return $this->index($messagelist,$request->filtervalue);
    }
    public function deletemessage(Request $request){
        $result = MessageModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function chosenmessage(Request $request){
        $messagelist = DB::table('message_models')
        ->select('message_models.*')
        ->where('id',$request->id)
        ->first();
        echo json_encode($messagelist);
    }
    public function expertCSV(){
        $msgArray = [];
        $messagelist = DB::table('message_models')
        ->leftJoin('profile_models', 'message_models.specific', '=', 'profile_models.userid')
        ->select('message_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('msgfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $messagelist = $messagelist->where('user',1);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('msgfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $messagelist = $messagelist->where('user',2);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        $messagelist = $messagelist->get();


        for($i = 0; $i < count($messagelist); $i++){
            $msgArray[$i]['sentdate'] = date("D, M j Y",strtotime($messagelist[$i]->created_at));
            if($messagelist[$i]->user == 1)
                $msgArray[$i]['sento'] = "All Users";
            else if($messagelist[$i]->user == 2)
                $msgArray[$i]['sento'] = "All Providers";
            else
                $msgArray[$i]['sento'] = $messagelist[$i]->fname." ".$messagelist[$i]->lname;
            $msgArray[$i]['msgid'] = $messagelist[$i]->msgid;
            $msgArray[$i]['Message'] = $messagelist[$i]->description;
        }
        $delimiter = ",";
        $filename = "message_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Sent Date', 'Sent To', 'message ID', 'Message');
        fputcsv($f, $fields, $delimiter);
        foreach ($msgArray as $line) {
            $lineData = array($line['sentdate'], $line['sento'], $line['msgid'], $line['Message']);
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
        $messagelist = DB::table('message_models')
        ->leftJoin('profile_models', 'message_models.specific', '=', 'profile_models.userid')
        ->select('message_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('msgfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $messagelist = $messagelist->where('user',1);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('msgfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $messagelist = $messagelist->where('user',2);
            $messagelist = $messagelist->whereIn('specific', $users, 'or');
        }
        $messagelist = $messagelist->get();
        $data = [
            'heading' => "Message List",
            'message' => $messagelist,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/messagepdf', $data);  
          return $pdf->download('message'.date('Y-m-d').'.pdf');
    }
}
