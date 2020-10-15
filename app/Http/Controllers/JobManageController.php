<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use App\ExspecModel;
use App\LicenseModel;
use App\ServiceModel;
use App\JobModel;
use App\TransactionModel;
date_default_timezone_set('America/Chicago');
class JobManageController extends Controller
{
    public function index()
    {
        if(Session::get('remember_token')){
            $jobsCnt = DB::table('job_models')
                ->whereNotNull('job_models.status')
                ->where('job_models.status',"<>",0)
                ->count();
            
            $ongoingjobs = DB::table('job_models')
            ->where('job_models.status',1)
            ->count();
            $finishedjobs = DB::table('job_models')
            ->where('job_models.status',2)
            ->count();
            $canceledjobs = DB::table('job_models')
            ->where('job_models.status',3)
            ->count();
            $jobslist = DB::table('job_models')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('job_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname')
            ->whereNotNull('job_models.status')
            ->where('job_models.status',"<>",0)
            ->get();
            return view('pages/jobmanagement',['pagename'=>'job','jobslist'=>$jobslist,'jobsCnt'=>$jobsCnt,'ongoingjobs'=>$ongoingjobs,'finishedjobs'=>$finishedjobs,'canceledjobs'=>$canceledjobs]);
        }
            return Redirect::to("/admin");
    }
    public function expertCSV(){
        $jobsArray = [];
        $jobs = DB::table('job_models')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('job_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname')
            ->whereNotNull('job_models.status')
            ->where('job_models.status',"<>",0)
            ->get();
        for($i = 0; $i < count($jobs); $i++){
            $jobsArray[$i]['startingdate'] = date("D, M j Y",strtotime($jobs[$i]->created_at));
            $jobsArray[$i]['jobid'] = $jobs[$i]->jobid;
            $jobsArray[$i]['cname'] = $jobs[$i]->cfname." ".$jobs[$i]->clname;
            $jobsArray[$i]['gname'] = $jobs[$i]->pfname." ".$jobs[$i]->plname;
            if($jobs[$i]->service == 1)
                $jobsArray[$i]['service'] = "Caregiver";
            else if($jobs[$i]->service == 2)
                $jobsArray[$i]['service'] = "Nursing";
            else
                $jobsArray[$i]['service'] = "Therapy";
            if($jobs[$i]->status == 1)
                $jobsArray[$i]['status'] = "Ongoing";
            else if($jobs[$i]->status == 2)
                $jobsArray[$i]['status'] = "Finished";
            else
                $jobsArray[$i]['status'] = "Canceled";
        }
        $delimiter = ",";
        $filename = "jobs_" . date('Y-m-d') . ".csv";
        
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        //set column headers
        $fields = array('Starting Date', 'JOB ID', 'Patient Name', 'Provider Name', 'Service', 'Status');
        fputcsv($f, $fields, $delimiter);
        // var_dump($jobsArray);exit;
        //output each row of the data, format line as csv and write to file pointer
        foreach ($jobsArray as $line) {
            $lineData = array($line['startingdate'], $line['jobid'], $line['cname'], $line['gname'], $line['service'], $line['status']);
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
        $jobs = DB::table('job_models')
            ->leftJoin('profile_models as client', 'job_models.client', '=', 'client.userid')
            ->leftJoin('profile_models as provider', 'job_models.provider', '=', 'provider.userid')
            ->select('job_models.*','provider.fname as pfname', 'provider.lname as plname','client.fname as cfname', 'client.lname as clname')
            ->whereNotNull('job_models.status')
            ->where('job_models.status',"<>",0)
            ->get();
        $data = [
            'heading' => "Job List",
            'jobs' => $jobs,
                 
              ];
          
          $pdf = PDF::loadView('/pdf/jobpdf', $data);  
          return $pdf->download('joblist'.date('Y-m-d').'.pdf');
    }
    public function chosenjob(Request $request){
        $chosenjob = DB::table('job_models')
        ->select('job_models.*')
        ->where('job_models.id',$request->id)
        ->first();
        $exspecid = json_decode($chosenjob->exspec);
        $exspec = ExspecModel::whereIn('id',$exspecid)->select('name')->get();
        $serviceactivityid = json_decode($chosenjob->serviceactivity);
        $serviceactivity = ServiceModel::whereIn('id',$serviceactivityid)->select('name')->get();
        $licenseid = json_decode($chosenjob->license);
        $license = LicenseModel::whereIn('id',$licenseid)->select('name')->get();

        $excludedays = json_decode($chosenjob->excludeday);
        echo json_encode(array($chosenjob,$exspec,$serviceactivity,$license,$excludedays));
    }
    public function deletejob(Request $request){
        $result1 = JobModel::where('id',$request->id)->delete();
        $result2 = TransactionModel::where('jobid',$request->id)->delete();

        if($result1)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
}
