<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Stripe\Transfer;
class PaycycleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paycycle:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nowDate = date("Y-m-d");
        $list = DB::table('job_models')
        ->leftJoin('profile_models', 'profile_models.userid', '=', 'job_models.provider')
        ->select('profile_models.account_id','profile_models.hiretype','job_models.start','job_models.end','job_models.amount')
        ->where('job_models.status',1)
        ->get();
        $tmp = [];
        for($i = 0;$i < count($list); $i++){
            $start = date_create($list[$i]->start);
            $end = date_create($list[$i]->end);
        
            $interval = date_diff($start, $end);
            $tmp[$i]['duration'] = $interval->format("%a")+1;
            $tmp[$i]['start'] = $list[$i]->start;
            $tmp[$i]['end'] = $list[$i]->end;
            $tmp[$i]['account_id'] = $list[$i]->account_id;
            if($list[$i]->hiretype == 1){
                $tmp[$i]['amount'] = floor($list[$i]->amount * 0.75);
            }
            else{
                $tmp[$i]['amount'] = floor($list[$i]->amount * 0.8);
            }
        }
        for($i = 0;$i < count($tmp); $i++){
            if($tmp[$i]['duration'] <= 14){
                if(strtotime($nowDate) == strtotime($tmp[$i]['end'])){
                    $transfer = Transfer::create([
                        'amount' => $tmp[$i]['amount'],
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
            }
            elseif($tmp[$i]['duration'] > 14 && $tmp[$i]['duration'] <= 28){
                $start = date_create($tmp[$i]['start']);
                $end = date_create($nowDate);
            
                $interval = date_diff($start, $end);
                if($interval->format("%a") == 13){
                    $transfer = Transfer::create([
                        'amount' => floor($tmp[$i]['amount']*0.4),
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
                if(strtotime($nowDate) == strtotime($tmp[$i]['end'])){
                    $transfer = Transfer::create([
                        'amount' => floor($tmp[$i]['amount']*0.6),
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
            }
            elseif($tmp[$i]['duration'] > 28){
                $start = date_create($tmp[$i]['start']);
                $end = date_create($nowDate);
            
                $interval = date_diff($start, $end);
                if($interval->format("%a") == 13){
                    $transfer = Transfer::create([
                        'amount' => floor($tmp[$i]['amount']*0.4),
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
                if($interval->format("%a") == 27){
                    $transfer = Transfer::create([
                        'amount' => floor($tmp[$i]['amount']*0.4),
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
                if(strtotime($nowDate) == strtotime($tmp[$i]['end'])){
                    $transfer = Transfer::create([
                        'amount' => floor($tmp[$i]['amount']*0.2),
                        'currency' => 'usd',
                        'destination' => $tmp[$i]['account_id']
                    ]);
                }
            }
        }
    }
}
