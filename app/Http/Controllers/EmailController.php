<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use App\UsersModel;
use App\ProfileModel;
use App\EmaillistModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Mail;
use App\Mail\PushEmail;
class EmailController extends Controller
{
    public function index($emaillist = null,$chosenfilter = null)
    {
        if(Session::get('remember_token')){
            $users = ProfileModel::orderBy("fname")->select("userid","fname","lname")->get();
            if($emaillist == null){
                $emaillist = DB::table('emaillist_models')
                ->leftJoin('profile_models', 'emaillist_models.specific', '=', 'profile_models.userid')
                ->select('emaillist_models.*','profile_models.fname','profile_models.lname')
                ->get();
            }
            return view('pages/email',['pagename'=>'email','users'=>$users,'emaillist'=>$emaillist,'chosenfilter'=>$chosenfilter]);
        }
            return Redirect::to("/admin");
    }
    public function pushemail(Request $request){
        $data = $request->all();
        $email = new EmaillistModel();

        if(isset($data['file']) && $data['file']){
            $cover = $request->file('file');
            //$extension = $cover->getClientOriginalExtension();
            $filename = $cover->getClientOriginalName();
            $email->filename = $filename;
            Storage::disk('s3')->put("/email/".$filename , File::get($cover));
        }
        $email->subject = $request->subject;
        $email->description = $request->writearea;
        if($request->userlist != "1" && $request->userlist != "2"){
            $realUser = explode("_",$request->userlist);
            $email->specific = $realUser[0];
            $email->user = 0;
            $toemail = UsersModel::where("id",$realUser[0])->select("email")->first();
        }  
        else{
            $toemail = UsersModel::where("roles",$request->userlist)->select("email")->get();
            $email->user = $request->userlist;
        }
        $email->emailid = mt_rand(100000000, 999999999);
        if($email->save()){
            $file = $request->file('file');
            if($file != null){
                if ($file->getError() == 1) {
                    $max_size = $file->getMaxFileSize() / 1024 / 1024;  // Get size in Mb
                    $error = 'The file size must be less than ' . $max_size . 'Mb.';
                    return Redirect::to("/admin/email")->with('info', $error);
                }
                $data = [
                    'file' => $file,
                    'subject' => $email->subject,
                    'description' => $email->description
                ];
            }
            else{
                $data = [
                    'subject' => $email->subject,
                    'description' => $email->description
                ];
            }
            Mail::to($toemail)->send(new PushEmail($data));
            return Redirect::to("/admin/email")->with('info','Sent Successfully');;
        }
        else{
            return Redirect::to("/admin/email")->with('info','Sent failed');;
        }
    }
    public function deleteemail(Request $request){
        $img = EmaillistModel::where('id',$request->id)->first();
        if($img->filename)
            Storage::disk('s3')->delete("/email/".$img['filename']);
        $result = EmaillistModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function chosenemail(Request $request){
        $emaillist = DB::table('emaillist_models')
        ->select('emaillist_models.*')
        ->where('id',$request->id)
        ->first();
        echo json_encode($emaillist);
    }
    public function filterviewforemail(Request $request){
        session()->put('emailfilter', $request->filtervalue);
        $emaillist = DB::table('emaillist_models')
        ->leftJoin('profile_models', 'emaillist_models.specific', '=', 'profile_models.userid')
        ->select('emaillist_models.*','profile_models.fname','profile_models.lname');
        if($request->filtervalue == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $emaillist = $emaillist->where('user',1);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        elseif($request->filtervalue == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $emaillist = $emaillist->where('user',2);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        $emaillist = $emaillist->get();
        return $this->index($emaillist,$request->filtervalue);
    }
    public function expertCSV(){
        $emailArray = [];
        $emaillist = DB::table('emaillist_models')
        ->leftJoin('profile_models', 'emaillist_models.specific', '=', 'profile_models.userid')
        ->select('emaillist_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('emailfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $emaillist = $emaillist->where('user',1);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('emailfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $emaillist = $emaillist->where('user',2);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        $emaillist = $emaillist->get();


        for($i = 0; $i < count($emaillist); $i++){
            $emailArray[$i]['sentdate'] = date("D, M j Y",strtotime($emaillist[$i]->created_at));
            if($emaillist[$i]->user == 1)
                $emailArray[$i]['sento'] = "All Users";
            else if($emaillist[$i]->user == 2)
                $emailArray[$i]['sento'] = "All Providers";
            else
                $emailArray[$i]['sento'] = $emaillist[$i]->fname." ".$emaillist[$i]->lname;
            $emailArray[$i]['emailid'] = $emaillist[$i]->emailid;
            $emailArray[$i]['subject'] = $emaillist[$i]->subject;
        }
        $delimiter = ",";
        $filename = "email_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Sent Date', 'Sent To', 'Email ID', 'Subject');
        fputcsv($f, $fields, $delimiter);
        foreach ($emailArray as $line) {
            $lineData = array($line['sentdate'], $line['sento'], $line['emailid'], $line['subject']);
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
        $emaillist = DB::table('emaillist_models')
        ->leftJoin('profile_models', 'emaillist_models.specific', '=', 'profile_models.userid')
        ->select('emaillist_models.*','profile_models.fname','profile_models.lname');
        if(Session::get('emailfilter') == 1){
            $users = UsersModel::where('roles',1)->select('id')->get();
            $emaillist = $emaillist->where('user',1);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        elseif(Session::get('emailfilter') == 2){
            $users = UsersModel::where('roles',2)->select('id')->get();
            $emaillist = $emaillist->where('user',2);
            $emaillist = $emaillist->whereIn('specific', $users, 'or');
        }
        $emaillist = $emaillist->get();
        $data = [
            'heading' => "Email List",
            'email' => $emaillist,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/emailpdf', $data);  
          return $pdf->download('email'.date('Y-m-d').'.pdf');
    }
}
