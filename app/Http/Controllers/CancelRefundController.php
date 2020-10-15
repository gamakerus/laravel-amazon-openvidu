<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use App\ReasonModel;
use App\CancelrefundModel;
use App\TransactionModel;
use App\NotificationModel;
use App\JobModel;
use App\ProfileModel;
use App\UsersModel;

use DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Refund;

use Mail;
use App\Mail\JobMail;
use Twilio\Rest\Client;
class CancelRefundController extends Controller
{
    public function __construct()
    {
        
        $this->twilio_account_sid = env("TWILIO_ACCOUNT_SID");
        $this->twilio_auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->twclient = new Client($this->twilio_account_sid, $this->twilio_auth_token);

        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    public function index($canref = null,$chosenfilter = null)
    {
        if(Session::get('remember_token')){
            $reasonlist = ReasonModel::orderBy('name')->get();
            $pending = CancelrefundModel::where('status',1)->count();
            $solved = CancelrefundModel::where('status',2)->count();
            if($canref == null){
                $canref = DB::table('cancelrefund_models')
                ->leftJoin('job_models', 'job_models.id', '=', 'cancelrefund_models.jobid')
                ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
                ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
                ->select('cancelrefund_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')
                ->whereIn('cancelrefund_models.status',["1","2"])->get();
            }
            return view('pages/cancelrefund',['pagename'=>'cancelrefund','canref'=>$canref,'pending'=>$pending,'solved'=>$solved,'reasonlist'=>$reasonlist,'chosenfilter'=>$chosenfilter]);
        }
            return Redirect::to("/admin");
    }
    public function addreason(Request $request){
        $reason = new ReasonModel();
        $reason->name = $request->value;
        if($reason->save()){
            $reasonlist = ReasonModel::orderBy('name')->get();
            echo json_encode($reasonlist);
        }
        else{
            $reasonlist = ReasonModel::orderBy('name')->get();
            echo json_encode($reasonlist);
        }
    }
    public function updatereason(Request $request){
        $result = ReasonModel::where('id',$request->id)->update(['name'=>$request->desc]);
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    
    public function deletereason(Request $request){
        $result = ReasonModel::where('id',$request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function chosencanref(Request $request){
        $chosenjob = DB::table('cancelrefund_models')
        ->leftJoin('job_models', 'job_models.id', '=', 'cancelrefund_models.jobid')
        ->select('job_models.cancelflag','job_models.canceltext')
        ->where('cancelrefund_models.id',$request->id)
        ->first();
        if($chosenjob->cancelflag == 0){
            $reason = $chosenjob->canceltext;
        }
        else{
            $reasontext = ReasonModel::where('id',$chosenjob->cancelflag)->select('name')->first();
            $reason = $reasontext['name'];
        }
        echo json_encode($reason);
    }
    public function filterviewforcanref(Request $request){
        session()->put('canreffilter', $request->filtervalue);
        $canref = DB::table('cancelrefund_models')
                ->leftJoin('job_models', 'job_models.id', '=', 'cancelrefund_models.jobid')
                ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
                ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
                ->select('cancelrefund_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')->whereIn('cancelrefund_models.status',["1","2"]);
        if($request->filtervalue == 1){
            $canref = $canref->where('cancelrefund_models.status',2);
        }
        elseif($request->filtervalue == 2){
            $canref = $canref->where('cancelrefund_models.status',1);
        }
        $canref = $canref->get();
        return $this->index($canref,$request->filtervalue);
    }
    public function deletecanref(Request $request){
        $result = CancelrefundModel::where('id',$request->id)->delete();

        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function expertCSV(){
        $canrefArray = [];
        $canref = DB::table('cancelrefund_models')
                ->leftJoin('job_models', 'job_models.id', '=', 'cancelrefund_models.jobid')
                ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
                ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
                ->select('cancelrefund_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')->whereIn('cancelrefund_models.status',["1","2"]);
        if(Session::get('canreffilter') == 1){
            $canref = $canref->where('cancelrefund_models.status',2);
        }
        elseif(Session::get('canreffilter') == 2){
            $canref = $canref->where('cancelrefund_models.status',1);
        }
        $canref = $canref->get();


        for($i = 0; $i < count($canref); $i++){
            $canrefArray[$i]['rdate'] = date("D, M j Y",strtotime($canref[$i]->created_at));
            $canrefArray[$i]['refid'] = $canref[$i]->refid;
            $canrefArray[$i]['client'] = $canref[$i]->cfname." ".$canref[$i]->clname;
            $canrefArray[$i]['provider'] = $canref[$i]->pfname." ".$canref[$i]->plname;
            $canrefArray[$i]['jobid'] = $canref[$i]->jobid;
            $canrefArray[$i]['amount'] = $canref[$i]->amount;
            $canrefArray[$i]['status'] = $canref[$i]->status == 1?"Solved":"Pending";
        }
        $delimiter = ",";
        $filename = "refund_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Request Date', 'Refund ID', 'Patient Name', 'Provider Name', 'Job ID', 'Amount', 'Status');
        fputcsv($f, $fields, $delimiter);
        foreach ($canrefArray as $line) {
            $lineData = array($line['rdate'], $line['refid'], $line['client'], $line['provider'], $line['jobid'], $line['amount'], $line['status']);
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
        $canref = DB::table('cancelrefund_models')
                ->leftJoin('job_models', 'job_models.id', '=', 'cancelrefund_models.jobid')
                ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
                ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
                ->select('cancelrefund_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')->whereIn('cancelrefund_models.status',["1","2"]);
        if(Session::get('canreffilter') == 1){
            $canref = $canref->where('cancelrefund_models.status',2);
        }
        elseif(Session::get('canreffilter') == 2){
            $canref = $canref->where('cancelrefund_models.status',1);
        }
        $canref = $canref->get();
        $data = [
            'heading' => "Refund List",
            'canref' => $canref,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/canrefpdf', $data);  
          return $pdf->download('refund'.date('Y-m-d').'.pdf');
    }
    public function refundaction(Request $request){
        $jobid = CancelrefundModel::where('id',$request->canrefid)->first();
        $chargeid = TransactionModel::where('jobid',$jobid['jobid'])->select('chargeid')->first();
        $refund = Refund::create([
            'charge' => $chargeid['chargeid'],
          ]);
        if($refund['status'] == "succeeded"){
            CancelrefundModel::where('id',$request->canrefid)->update(['refundid'=>$refund['id'],'status'=>2]);
            $detail = JobModel::where('id',$jobid['jobid'])->first();
            $clientphone = ProfileModel::where('userid',$detail->client)->select('phone')->first();
            $clientemail = UsersModel::where('id',$detail->client)->select('email')->first();
            $not = new NotificationModel();
            $not->notid = mt_rand(100000000, 999999999);
            $not->user = 0;
            $not->title = "Your payment was refunded";
            $not->description = "Job ".$detail->jobid." was refunded";
            $not->specific = $detail->client;
            $not->save();

            $message = "Job ".$detail->jobid." was refunded";
           
            $this->twclient->messages->create($clientphone['phone'], 
                    ['from' => $this->twilio_number, 'body' => $message] );
            $data = array(
                'subject' => "Your payment was refunded",
                'user' =>"Support",
                'message' => $message
            );
            Mail::to($clientemail['email'])->send(new JobMail($data));

            return Redirect::to('/admin/cancelrefund')->with('info','Refund is done successfully');
        }
        else{
            return Redirect::to('/admin/cancelrefund')->with('info','Refund is failed please try it again');
        }

    }
}
