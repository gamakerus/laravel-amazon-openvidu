<?php

namespace App\Http\Controllers\Api;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\UsersModel;
use App\PtrelationModel;
use App\AvailabledayWeekModel;
use App\ProfileModel;
use App\AvailabletimeDayModel;
use App\ServiceTimeModel;
use App\paramModel;
use App\sscheduleModel;
use App\RequestServiceModel;

class SearchController extends Controller
{
    public function getcntPro(Request $request){
        $radiusvalue = paramModel::where("name","radius")->first();
        if(isset($radiusvalue->value))
            $radiuskilo = $radiusvalue->value*1.6;
        else
            $radiuskilo = 160;
        $result = DB::table("profile_models")->select("id",DB::raw("6371 * acos(cos(radians(" . $request->lat . ")) * cos(radians(profile_models.lat)) * cos(radians(profile_models.long) - radians(" . $request->lng . ")) + sin(radians(" .$request->lat. ")) * sin(radians(profile_models.lat))) AS distance"))->where('roles',2)
        ->get();
        $relation = PtrelationModel::where('status',1)->orderBy('id')->get();
        $stime = ServiceTimeModel::where('status',1)->orderBy('id')->get();
        $daytoweek = AvailabledayWeekModel::where('status',1)->orderBy('id')->get();
        $timetoday = AvailabletimeDayModel::where('status',1)->orderBy('id')->get();
        $cnt = 0;
        //return array('status'=>'success','relation'=>$relation,'stime'=>$stime,'daytoweek'=>$daytoweek,'timetoday'=>$timetoday);
        for($i = 0;$i < count($result);$i++){
            if($result[$i]->distance <= $radiuskilo)
                $cnt++;
        }
        if($cnt > 0)
            return array('status'=>'success','relation'=>$relation,'stime'=>$stime,'daytoweek'=>$daytoweek,'timetoday'=>$timetoday);
        else
            return array('status'=>'failed');
    }
    public function searchcaregiver(Request $request){
        $data = $request->all();
        $perPage = 10;
        $relation = PtrelationModel::where('status',1)->orderBy('id')->get();
        $radiusvalue = paramModel::where("name","radius")->first();
        if(isset($radiusvalue->value)){
            $radiuskilo = $radiusvalue->value*1.6;
            // var_dump($radiusvalue->value);exit;
        }
        else
            $radiuskilo = 160;
        $token = UsersModel::where("remember_token",$request->token)->first();
        $dulpicate = ProfileModel::where("userid",$token->id)->select("address","lat","long")->first();
        if($dulpicate['address'] == null || $dulpicate['lat'] == null || $dulpicate['long'] == null){
            ProfileModel::where("userid",$token->id)->select("address","lat","long")->update(['address'=>$request->data['address'],'long'=>$request->lng,'lat'=>$request->lat]);
        }

        $location = ProfileModel::where("userid",$token->id)->select("lat","long")->first();

        $providers = DB::table('profile_models','sschedule_models')
        ->leftJoin('users_models', 'users_models.id', '=', 'profile_models.userid')
        ->leftJoin('license_models', function ($join) {
            $join->on('profile_models.license', 'like', DB::raw("concat('%,',license_models.id,',%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%',license_models.id,']%')"))
                  ->orOn('profile_models.license', 'like', DB::raw("concat('%[',license_models.id,'%')"));
        })
        ->leftJoin('sschedule_models', function ($join) {
            $join->on('profile_models.userid', '=', 'sschedule_models.userid')
                 ->where('sschedule_models.week', '=', 0);
        });
        
        if($location['long'] != null && $location['lat'] != null)
            $providers = $providers->select('profile_models.userid as id',DB::raw("FLOOR(6371 * acos(cos(radians(".$location['lat'].")) * cos(radians(profile_models.lat)) * cos(radians(profile_models.long) - radians(".$location['long'].")) + sin(radians(".$location['lat'].")) * sin(radians(profile_models.lat)))/1.6) AS distance"),'profile_models.avatar','profile_models.address','profile_models.fname', 'profile_models.lname','profile_models.rate','profile_models.review','profile_models.price','profile_models.bio',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'),'sschedule_models.start','sschedule_models.end');
        else
            $providers = $providers->select('profile_models.userid as id',DB::raw("FLOOR(6371 * acos(cos(radians(".$request->lat.")) * cos(radians(profile_models.lat)) * cos(radians(profile_models.long) - radians(".$request->lng.")) + sin(radians(".$request->lat.")) * sin(radians(profile_models.lat)))/1.6) AS distance"),'profile_models.avatar','profile_models.address','profile_models.fname', 'profile_models.lname','profile_models.rate','profile_models.review','profile_models.price','profile_models.bio',DB::raw('group_concat(license_models.name SEPARATOR "<||>") as licensename'),DB::raw('group_concat(license_models.image SEPARATOR "<||>") as licenseimg'),'sschedule_models.start','sschedule_models.end');   
        
        
        $providers = $providers->where('profile_models.roles',2)->where('users_models.allowed',1)->where('users_models.status',1)->whereNotNull('profile_models.price')->whereNotNull('profile_models.account_id');    
        if($request->data['service'] != 0 && $request->data['service'] != null){
            $providers = $providers->where('profile_models.service',$request->data['service']);

        }
        if($request->filter != 0){
            $providers = $providers->where('profile_models.hiretype',$request->filter);
        }
        if(isset($request->data['selected_lang']) && count($request->data['selected_lang']) > 0){
            session()->put('searchlang', $request->data['selected_lang']);
            $providers = $providers->where(function($query) {
                $langlists = Session::get('searchlang');
                for($i = 0;$i < count($langlists);$i++){
                    if($i == 0)
                        $query->where('profile_models.language', 'like', "%,".$langlists[$i].",%")->orWhere('profile_models.language', 'like', "%[".$langlists[$i]."%")->orWhere('profile_models.language', 'like', "%".$langlists[$i]."]%");
                    else
                        $query = $query->orWhere('profile_models.language', 'like', "%,".$langlists[$i].",%")->orWhere('profile_models.language', 'like', "%[".$langlists[$i]."%")->orWhere('profile_models.language', 'like', "%".$langlists[$i]."]%");
                }
            });
        } 
        if(isset($request->data['activities']) && count($request->data['activities']) > 0){
            $dulpicate = RequestServiceModel::where('type',$request->data['service'])->where('client',$token->id)->where('service',json_encode($request->data['activities']))->where('othertext',$request->data['other'])->first();
            if(!$dulpicate){
                $requestService = new RequestServiceModel();
                $requestService->client = $token->id;
                $requestService->type = $request->data['service'];
                if(count($request->data['activities']) == 1 && $request->data['activities'][0] == 0){
                    $requestService->service = 0;
                    $requestService->othertext = $request->data['other'];
                }
                else
                    $requestService->service = json_encode($request->data['activities']);
                $requestService->save();
            }
            
            if(count($request->data['activities']) == 1 && $request->data['activities'][0] == 0){
                return array('status'=>"other",'otherdesc'=>"We have received your request and our team will contact you soon.");
            }
            session()->put('searchactivities', $request->data['activities']);
            $providers = $providers->where(function($query) {
                $activitylists = Session::get('searchactivities');
                for($i = 0;$i < count($activitylists);$i++){
                    if($i == 0)
                        $query->where('profile_models.serviceactivity', 'like', "%,".$activitylists[$i].",%")->orWhere('profile_models.serviceactivity', 'like', "%[".$activitylists[$i]."%")->orWhere('profile_models.serviceactivity', 'like', "%".$activitylists[$i]."]%");
                    else
                        $query = $query->orWhere('profile_models.serviceactivity', 'like', "%,".$activitylists[$i].",%")->orWhere('profile_models.serviceactivity', 'like', "%[".$activitylists[$i]."%")->orWhere('profile_models.serviceactivity', 'like', "%".$activitylists[$i]."]%");
                }
            });
        } 
        if(isset($request->data['license']) && count($request->data['license']) > 0){
            session()->put('searchlicense', $request->data['license']);
            $providers = $providers->where(function($query) {
                $licenselists = Session::get('searchlicense');
                for($i = 0;$i < count($licenselists);$i++){
                    if($i == 0)
                        $query->where('profile_models.license', 'like', "%,".$licenselists[$i].",%")->orWhere('profile_models.license', 'like', "%[".$licenselists[$i]."%")->orWhere('profile_models.license', 'like', "%".$licenselists[$i]."]%");
                    else
                        $query = $query->orWhere('profile_models.license', 'like', "%,".$licenselists[$i].",%")->orWhere('profile_models.license', 'like', "%[".$licenselists[$i]."%")->orWhere('profile_models.license', 'like', "%".$licenselists[$i]."]%");
                }
            });
        } 
        if(isset($request->data['expertise']) && count($request->data['expertise']) > 0){
            session()->put('searchexspec', $request->data['expertise']);
            $providers = $providers->where(function($query) {
                $exspeclists = Session::get('searchexspec');
                for($i = 0;$i < count($exspeclists);$i++){
                    if($i == 0)
                        $query->where('profile_models.exspec', 'like', "%,".$exspeclists[$i].",%")->orWhere('profile_models.exspec', 'like', "%[".$exspeclists[$i]."%")->orWhere('profile_models.exspec', 'like', "%".$exspeclists[$i]."]%");
                    else
                        $query = $query->orWhere('profile_models.exspec', 'like', "%,".$exspeclists[$i].",%")->orWhere('profile_models.exspec', 'like', "%[".$exspeclists[$i]."%")->orWhere('profile_models.exspec', 'like', "%".$exspeclists[$i]."]%");
                }
            });
        } 
        $providers = $providers->havingRaw("distance <= ".$radiuskilo);

        
        $providers = $providers->groupBy('profile_models.id');
        if($request->sort == 0)
            $providers = $providers->orderBy('profile_models.price');
        elseif($request->sort == 1)
            $providers = $providers->orderBy('profile_models.price',"desc");
        elseif($request->sort == 2)
            $providers = $providers->orderBy('profile_models.review',"desc");
        elseif($request->sort == 3)
            $providers = $providers->orderBy('profile_models.review');
        elseif($request->sort == 4)
            $providers = $providers->orderBy('distance');

        $totalpage = round(count($providers->get())/$perPage);
        if($request->data['page'] == null || $request->data['page'] == 0)
            $providers = $providers->offset(0)->limit($perPage)->get();
        else{
            $providers = $providers->offset($perPage*($request->data['page']-1))->limit($perPage)->get();
        }
        if($providers)
            return array('status'=>"success",'result'=>$providers,'totalpage'=>$totalpage,'relation'=>$relation);
        else{
            return array('status'=>"failed");
        }
    }
    public function scheduledetails(Request $request){
        $details = sscheduleModel::where('userid',$request->id)->where('checked',1)->select('week','start','end')->get();
        $live_in = ProfileModel::where('userid',$request->id)->select('live_in')->first();
        return array('status'=>'success','details'=>$details,'live_in'=>$live_in['live_in']);
    }
}
