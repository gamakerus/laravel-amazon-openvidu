<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use App\SquestionListModel;
use App\ServiceModel;
use App\PtrelationModel;
use App\LanguageModel;
use App\ExspecModel;
use App\LicenseModel;
use App\ServiceTimeModel;
use App\AvailabledayWeekModel;
use App\AvailabletimeDayModel;
use App\paramModel;
use App\DescriptionModel;
use App\ProfileModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use DB;
class SettingController extends Controller
{
    public function squestion()
    {
        
        if(Session::get('remember_token')){
            $questionlist = DB::table('squestion_list_models')->get();
            return view('pages/squestion',['pagename'=>'squestion','questionlist'=>$questionlist]);
        }
            return Redirect::to("/admin");
    }
    public function services()
    {
        
        if(Session::get('remember_token')){
            $servicelist = DB::table('service_models')->orderBy('type')->orderBy('name')->get();
            return view('pages/services',['pagename'=>'services','servicelist'=>$servicelist]);
        }
            return Redirect::to("/admin");
    }
    public function ptrelation()
    {
        
        if(Session::get('remember_token')){
            $ptrelationlist = DB::table('ptrelation_models')->get();
            return view('pages/ptrelation',['pagename'=>'ptrelation','ptrelationlist'=>$ptrelationlist]);
        }
            return Redirect::to("/admin");
    }
    public function language()
    {
        
        if(Session::get('remember_token')){
            $languagelist = DB::table('language_models')->get();
            return view('pages/language',['pagename'=>'language','languagelist'=>$languagelist]);
        }
            return Redirect::to("/admin");
    }
    public function exspec()
    {
        
        if(Session::get('remember_token')){
            $exspeclist = DB::table('exspec_models')->get();
            return view('pages/exspec',['pagename'=>'exspec','exspeclist'=>$exspeclist]);
        }
            return Redirect::to("/admin");
    }
    public function licenses()
    {
        
        if(Session::get('remember_token')){
            $licenselist = DB::table('license_models')->get();
            return view('pages/licenses',['pagename'=>'licenses','licenselist'=>$licenselist]);
        }
            return Redirect::to("/admin");
    }
    public function schedule()
    {
        
        if(Session::get('remember_token')){
            $sdurationlist = DB::table('service_time_models')->get();
            $daytoweeklist = DB::table('availableday_week_models')->get();
            $timetodaylist = DB::table('availabletime_day_models')->get();
            return view('pages/schedule',['pagename'=>'schedule','sdurationlist'=>$sdurationlist,'daytoweeklist'=>$daytoweeklist,'timetodaylist'=>$timetodaylist]);
        }
            return Redirect::to("/admin");
    }
    public function osettings()
    {
        
        if(Session::get('remember_token')){
            $radiusvalue = paramModel::where("name","radius")->first();
            $flexdesc = DescriptionModel::where("type",1)->where("subtype",1)->first();
            if(!isset($flexdesc['name'])){
                $flexdesc['name'] = "";
            }
            $directdesc = DescriptionModel::where("type",1)->where("subtype",2)->first();
            if(!isset($directdesc['name'])){
                $directdesc['name'] = "";
            }
            if(isset($radiusvalue['value']))
                $radiusvalue = $radiusvalue['value'];
            else
                $radiusvalue = 100;
            return view('pages/osettings',['pagename'=>'osettings','radiusvalue'=>$radiusvalue,'flexdesc'=>$flexdesc['name'],'directdesc'=>$directdesc['name']]);
            
        }
            return Redirect::to("/admin");
    }

    //Security Question
    public function squestionaddForm(Request $request){
        request()->validate([
            'question' => 'required',
            'question_status' => 'required'
        ]);

        $data = $request->all();

        $squestion = new SquestionListModel();

        $squestion->question = $data['question'];
        $squestion->status = $data['question_status'];
        $squestion->created_at = date("Y-m-d H:i:s");
        $squestion->updated_at = date("Y-m-d H:i:s");
        if($squestion->save()){
            return Redirect::to('/admin/squestion');
        }
    }
    public function chosenquestion(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenquestion = DB::table('squestion_list_models')->where('id',$data['id'])->get();
        echo json_encode($chosenquestion);
    }
    public function squestioneditForm(Request $request){
        request()->validate([
            'equestion' => 'required',
            'equestion_status' => 'required',
            'chosen_questionid' => 'required'
        ]);

        $data = $request->all();
        $result = SquestionListModel::where('id', $data['chosen_questionid'])
                ->update(['question' => $data['equestion'],
                        'status'=>$data['equestion_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/squestion');
        }
    }
    public function deletequestion(Request $request){
        $result = SquestionListModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Security Question



    //Service
    public function serviceaddForm(Request $request){
        request()->validate([
            'service_type' => 'required',
            'service' => 'required',
            'service_status' => 'required'
        ]);

        $data = $request->all();

        $services = new ServiceModel();

        $services->type = $data['service_type'];
        $services->name = $data['service'];
        $services->status = $data['service_status'];
        $services->created_at = date("Y-m-d H:i:s");
        $services->updated_at = date("Y-m-d H:i:s");
        if($services->save()){
            return Redirect::to('/admin/services');
        }
    }
    public function chosenservice(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenservice = DB::table('service_models')->where('id',$data['id'])->get();
        echo json_encode($chosenservice);
    }
    public function serviceeditForm(Request $request){
        request()->validate([
            'eservice' => 'required',
            'eservice_type' => 'required',
            'eservice_status' => 'required',
            'chosen_serviceid' => 'required'
        ]);

        $data = $request->all();
        $result = ServiceModel::where('id', $data['chosen_serviceid'])
                ->update(['name' => $data['eservice'],
                        'type'=>$data['eservice_type'],
                        'status'=>$data['eservice_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/services');
        }
    }
    public function deleteservice(Request $request){
        $result = ServiceModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Service

    //Patient Relation
    public function ptrelationaddForm(Request $request){
        request()->validate([
            'ptrelation' => 'required',
            'ptrelation_status' => 'required'
        ]);

        $data = $request->all();

        $ptrelation = new PtrelationModel();

        $ptrelation->name = $data['ptrelation'];
        $ptrelation->status = $data['ptrelation_status'];
        $ptrelation->created_at = date("Y-m-d H:i:s");
        $ptrelation->updated_at = date("Y-m-d H:i:s");
        if($ptrelation->save()){
            return Redirect::to('/admin/ptrelation');
        }
    }
    public function chosenptrelation(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenptrelation = DB::table('ptrelation_models')->where('id',$data['id'])->get();
        echo json_encode($chosenptrelation);
    }
    public function ptrelationeditForm(Request $request){
        request()->validate([
            'eptrelation' => 'required',
            'eptrelation_status' => 'required',
            'chosen_ptrelationid' => 'required'
        ]);

        $data = $request->all();
        $result = PtrelationModel::where('id', $data['chosen_ptrelationid'])
                ->update(['name' => $data['eptrelation'],
                        'status'=>$data['eptrelation_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/ptrelation');
        }
    }
    public function deleteptrelation(Request $request){
        $result = PtrelationModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Patient Relation



    //Language
    public function languageaddForm(Request $request){
        request()->validate([
            'language' => 'required',
            'language_status' => 'required'
        ]);

        $data = $request->all();

        $language = new LanguageModel();

        $language->name = $data['language'];
        $language->status = $data['language_status'];
        $language->created_at = date("Y-m-d H:i:s");
        $language->updated_at = date("Y-m-d H:i:s");
        if($language->save()){
            return Redirect::to('/admin/language');
        }
    }
    public function chosenlanguage(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenlanguage = DB::table('language_models')->where('id',$data['id'])->get();
        echo json_encode($chosenlanguage);
    }
    public function languageeditForm(Request $request){
        request()->validate([
            'elanguage' => 'required',
            'elanguage_status' => 'required',
            'chosen_languageid' => 'required'
        ]);

        $data = $request->all();
        $result = LanguageModel::where('id', $data['chosen_languageid'])
                ->update(['name' => $data['elanguage'],
                        'status'=>$data['elanguage_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/language');
        }
    }
    public function deletelanguage(Request $request){
        $result = LanguageModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Language

    //Expertise & Specialization
    public function exspecaddForm(Request $request){
        request()->validate([
            'exspec' => 'required',
            'exspec_status' => 'required'
        ]);

        $data = $request->all();

        $exspec = new ExspecModel();

        $exspec->name = $data['exspec'];
        $exspec->status = $data['exspec_status'];
        $exspec->created_at = date("Y-m-d H:i:s");
        $exspec->updated_at = date("Y-m-d H:i:s");
        if($exspec->save()){
            return Redirect::to('/admin/exspec');
        }
    }
    public function chosenexspec(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenexspec = DB::table('exspec_models')->where('id',$data['id'])->get();
        echo json_encode($chosenexspec);
    }
    public function exspeceditForm(Request $request){
        request()->validate([
            'eexspec' => 'required',
            'eexspec_status' => 'required',
            'chosen_exspecid' => 'required'
        ]);

        $data = $request->all();
        $result = ExspecModel::where('id', $data['chosen_exspecid'])
                ->update(['name' => $data['eexspec'],
                        'status'=>$data['eexspec_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/exspec');
        }
    }
    public function deleteexspec(Request $request){
        $result = ExspecModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Expertise & Specialization


    //License
    public function licenseaddForm(Request $request){
        request()->validate([
            'license' => 'required',
            'license_status' => 'required'
        ]);

        $data = $request->all();

        $license = new LicenseModel();

        $license->name = $data['license'];
        $license->status = $data['license_status'];
        $license->created_at = date("Y-m-d H:i:s");
        $license->updated_at = date("Y-m-d H:i:s");
        if($data['file']){
            $imagename = $license->name.date("Y-m-d");
            $cover = $request->file('file');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('s3')->put("/licenseimg/".$imagename.'.'.$extension , File::get($cover));
            $license->image = $imagename.'.'.$extension ;
        }
        if($license->save()){
            return Redirect::to('/admin/licenses');
        }
    }
    public function chosenlicense(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenlicense = DB::table('license_models')->where('id',$data['id'])->get();
        echo json_encode($chosenlicense);
    }
    public function licenseeditForm(Request $request){
        request()->validate([
            'elicense' => 'required',
            'elicense_status' => 'required',
            'chosen_licenseid' => 'required'
        ]);

        $data = $request->all();
        $img = LicenseModel::where('id',$data['chosen_licenseid'])->first();
        if($img->image)
            Storage::disk('s3')->delete("/licenseimg/".$img['image']);
        if($data['file']){
            $imagename = $data['elicense'].date("Y-m-d");
            $cover = $request->file('file');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('s3')->put("/licenseimg/".$imagename.'.'.$extension , File::get($cover));
        }
        $result = LicenseModel::where('id', $data['chosen_licenseid'])
                ->update(['name' => $data['elicense'],
                        'status'=>$data['elicense_status'],
                        'image'=>$imagename.'.'.$extension,
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/licenses');
        }
    }
    public function deletelicense(Request $request){
        $img = LicenseModel::where('id',$request->id)->first();
        if($img->image)
            Storage::disk('s3')->delete("/licenseimg/".$img['image']);
        $result = LicenseModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End License


    //Service Duration
    public function sdurationaddForm(Request $request){
        request()->validate([
            'sduration' => 'required',
            'sduration_status' => 'required'
        ]);

        $data = $request->all();

        $sduration = new ServiceTimeModel();

        $sduration->name = $data['sduration'];
        $sduration->status = $data['sduration_status'];
        $sduration->created_at = date("Y-m-d H:i:s");
        $sduration->updated_at = date("Y-m-d H:i:s");
        if($sduration->save()){
            return Redirect::to('/admin/schedule');
        }
    }
    public function chosensduration(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosensduration = DB::table('service_time_models')->where('id',$data['id'])->get();
        echo json_encode($chosensduration);
    }
    public function sdurationeditForm(Request $request){
        request()->validate([
            'esduration' => 'required',
            'esduration_status' => 'required',
            'chosen_sdurationid' => 'required'
        ]);

        $data = $request->all();
        $result = ServiceTimeModel::where('id', $data['chosen_sdurationid'])
                ->update(['name' => $data['esduration'],
                        'status'=>$data['esduration_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/schedule');
        }
    }
    public function deletesduration(Request $request){
        $result = ServiceTimeModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Service Duration


    //Day to week
    public function daytoweekaddForm(Request $request){
        request()->validate([
            'daytoweek' => 'required',
            'daytoweek_status' => 'required'
        ]);

        $data = $request->all();

        $daytoweek = new AvailabledayWeekModel();

        $daytoweek->name = $data['daytoweek'];
        $daytoweek->status = $data['daytoweek_status'];
        $daytoweek->created_at = date("Y-m-d H:i:s");
        $daytoweek->updated_at = date("Y-m-d H:i:s");
        if($daytoweek->save()){
            return Redirect::to('/admin/schedule');
        }
    }
    public function chosendaytoweek(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosendaytoweek = DB::table('availableday_week_models')->where('id',$data['id'])->get();
        echo json_encode($chosendaytoweek);
    }
    public function daytoweekeditForm(Request $request){
        request()->validate([
            'edaytoweek' => 'required',
            'edaytoweek_status' => 'required',
            'chosen_daytoweekid' => 'required'
        ]);

        $data = $request->all();
        $result = AvailabledayWeekModel::where('id', $data['chosen_daytoweekid'])
                ->update(['name' => $data['edaytoweek'],
                        'status'=>$data['edaytoweek_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/schedule');
        }
    }
    public function deletedaytoweek(Request $request){
        $result = AvailabledayWeekModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Day to week

    //Time to day
    public function timetodayaddForm(Request $request){
        request()->validate([
            'timetoday' => 'required',
            'timetoday_status' => 'required'
        ]);

        $data = $request->all();

        $timetoday = new AvailabletimeDayModel();

        $timetoday->name = $data['timetoday'];
        $timetoday->status = $data['timetoday_status'];
        $timetoday->created_at = date("Y-m-d H:i:s");
        $timetoday->updated_at = date("Y-m-d H:i:s");
        if($timetoday->save()){
            return Redirect::to('/admin/schedule');
        }
    }
    public function chosentimetoday(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosentimetoday = DB::table('availabletime_day_models')->where('id',$data['id'])->get();
        echo json_encode($chosentimetoday);
    }
    public function timetodayeditForm(Request $request){
        request()->validate([
            'etimetoday' => 'required',
            'etimetoday_status' => 'required',
            'chosen_timetodayid' => 'required'
        ]);

        $data = $request->all();
        $result = AvailabletimeDayModel::where('id', $data['chosen_timetodayid'])
                ->update(['name' => $data['etimetoday'],
                        'status'=>$data['etimetoday_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/schedule');
        }
    }
    public function deletetimetoday(Request $request){
        $result = AvailabletimeDayModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    //End Time to day

    //Other Settings
    public function setRadius(Request $request){
        $getflag = paramModel::where("name","radius")->first();
        if($getflag){
            $result = paramModel::where("name","radius")->update(['value' => $request->value]);
            if($result)
                return response()->json([
                    'success' => 'success'
                ]);
            else
                return response()->json([
                    'failed' => 'failed'
                ]);
        }
        else{
            $value = new paramModel();
            $value->name = "radius";
            $value->value = $request->value;
            if($value->save())
                return response()->json([
                    'success' => 'success'
                ]);
            else
                return response()->json([
                    'failed' => 'failed'
                ]);
        }
    }
    public function updateflexdesc(Request $request){
        $result = DescriptionModel::where('type',1)->where('subtype',1)->first();
        if($result){
            DescriptionModel::where('type',1)->where('subtype',1)->update(['name'=>$request->desc]);
            return Redirect::to('/admin/osettings');
        }
        else{
            $newDesc = new DescriptionModel();
            $newDesc->type = 1;
            $newDesc->subtype = 1;
            $newDesc->name = $request->desc;
            $newDesc->save();
            return Redirect::to('/admin/osettings');
        }
    }
    public function updatedirectdesc(Request $request){
        $result = DescriptionModel::where('type',1)->where('subtype',2)->first();
        if($result){
            DescriptionModel::where('type',1)->where('subtype',2)->update(['name'=>$request->desc]);
            return Redirect::to('/admin/osettings');
        }
        else{
            $newDesc = new DescriptionModel();
            $newDesc->type = 1;
            $newDesc->subtype = 2;
            $newDesc->name = $request->desc;
            $newDesc->save();
            return Redirect::to('/admin/osettings');
        }
    }
    public function updatecoverimg(Request $request){
        if($request->coverimg != null){
            $coverimgname = "ads".Str::random(20).date("Y-m-d");
            $real_img = file_get_contents($request->coverimg);
            Storage::disk('s3')->put("/coverimg/".$coverimgname.".png", $real_img);
            $duplicate_value = ProfileModel::where('roles',1)->update(['coverimg' => $coverimgname.".png"]);
            if($duplicate_value){
                return response()->json(['success'=>'success']);
            }
            else{
                return response()->json(['success'=>'failed']);
            }
        }
    }
}
