<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use App\TransactionModel;
date_default_timezone_set('America/Chicago');
class TransactionController extends Controller
{
    public function index()
    {
        
        if(Session::get('remember_token')){
            $transCnt = DB::table('transaction_models')
                ->where('type',1)
                ->count();
            
            $amount = DB::table('transaction_models')
            ->select(DB::raw('SUM(amount) as total'))
            ->where('type',1)
            ->get();
            if($amount[0]->total == null){
                $amount = 0;
            }
            else{
                $amount = $amount[0]->total;
            }
            $trans = DB::table('transaction_models')
            ->leftJoin('job_models', 'job_models.id', '=', 'transaction_models.jobid')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('transaction_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')
            ->where('transaction_models.type',1)
            ->get();
            return view('pages/transaction',['pagename'=>'transaction','trans'=>$trans,'number'=>$transCnt,'totalamonut'=>$amount]);
        }
            return Redirect::to("/admin");
    }
    public function expertCSV(){
        $transArray = [];
        $trans = DB::table('transaction_models')
            ->leftJoin('job_models', 'job_models.id', '=', 'transaction_models.jobid')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('transaction_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')
            ->where('transaction_models.type',1)
            ->get();
        for($i = 0; $i < count($trans); $i++){
            $transArray[$i]['paymentdate'] = date("D, M j Y",strtotime($trans[$i]->created_at));
            $transArray[$i]['tranid'] = $trans[$i]->tranid;
            $transArray[$i]['cname'] = $trans[$i]->cfname." ".$trans[$i]->clname;
            $transArray[$i]['gname'] = $trans[$i]->pfname." ".$trans[$i]->plname;
            $transArray[$i]['jobid'] = $trans[$i]->jobid;
            $transArray[$i]['amount'] = $trans[$i]->amount;
        }
        $delimiter = ",";
        $filename = "trans_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Payment Date', 'Trans ID', 'Patient Name', 'Provider Name', 'Job ID', 'Amount');
        fputcsv($f, $fields, $delimiter);
        // var_dump($transArray);exit;
        //output each row of the data, format line as csv and write to file pointer
        foreach ($transArray as $line) {
            $lineData = array($line['paymentdate'], $line['tranid'], $line['cname'], $line['gname'], $line['jobid'], $line['amount']);
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
        $trans = DB::table('transaction_models')
            ->leftJoin('job_models', 'job_models.id', '=', 'transaction_models.jobid')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('transaction_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname','job_models.jobid')
            ->where('transaction_models.type',1)
            ->get();
        $data = [
            'heading' => "Transaction List",
            'trans' => $trans,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/transpdf', $data);  
          return $pdf->download('translist'.date('Y-m-d').'.pdf');
    }
    public function deletetransaction(Request $request){
        $result = TransactionModel::where('id',$request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
}
