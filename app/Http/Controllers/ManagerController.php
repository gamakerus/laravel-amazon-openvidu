<?php

namespace App\Http\Controllers;
date_default_timezone_set('America/Chicago');
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use Illuminate\Support\Str;
use App\ManagersModel;
use App\SquestionModel;
use Mail;
use App\Mail\InviteMail;
class ManagerController extends Controller
{
    public function index()
    {
        if(Session::get('remember_token')){
            $managerlist = DB::table('managers')->get();
            $questionlist = DB::table('squestion_list_models')->where('status',1)->get();
            return view('pages/manager',['pagename'=>'manager','managerlist'=>$managerlist,'questionlist'=>$questionlist]);
        }
            return Redirect::to("/admin");
    }
    public function chosenmanager(Request $request){
        request()->validate([
            'id' => 'required',
        ]);

        $data = $request->all();
        $chosenmanager = DB::table('managers')->where('id',$data['id'])->get();
        echo json_encode($chosenmanager);
    }
    public function managereditForm(Request $request){
        request()->validate([
            'emanager_roles' => 'required',
            'emanager_status' => 'required',
            'chosen_managerid' => 'required'
        ]);

        $data = $request->all();
        $result = ManagersModel::where('id', $data['chosen_managerid'])
                ->update(['roles' => $data['emanager_roles'],
                        'status'=>$data['emanager_status'],
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
        if($result){
            return Redirect::to('/admin/manager');
        }
    }
    public function deletemanager(Request $request){
        $result = ManagersModel::find($request->id)->delete();
        if($result)
            return response()->json([
                'success' => 'success'
            ]);
        else
            return response()->json([
                'failed' => 'failed'
            ]);
    }
    public function addsqanswer(Request $request){
        request()->validate([
            'userid' => 'required',
            'questionid' => 'required',
            'answer' => 'required',
        ]);

        $data = $request->all();

        $result = SquestionModel::where('userid',$request->userid)->where('questionid',$request->questionid)->first();
        if($result){
            $updateresult = SquestionModel::where('userid',$request->userid)->where('questionid',$request->questionid)
                ->update(['answer' => $request->answer,
                        'updated_at'=>date("Y-m-d H:i:s")
                        ]);
            if($updateresult)
                return response()->json([
                    'success' => 'success'
                ]);
            else
                return response()->json([
                    'failed' => 'failed'
                ]);
            
        }
        else{
            $newresult = new SquestionModel();

            $newresult->userid = $request->userid;
            $newresult->questionid = $request->questionid;
            $newresult->answer = $request->answer;
            $newresult->created_at = date("Y-m-d H:i:s");
            $newresult->updated_at = date("Y-m-d H:i:s");
            if($newresult->save())
                return response()->json([
                    'success' => 'success'
                ]);
            else
                return response()->json([
                    'failed' => 'failed'
                ]);
        }

    }
    public function manageraddForm(Request $request){
        request()->validate([
            'manager_roles' => 'required',
            'manager_status' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:managers',
        ]);

        $data = $request->all();

        $newManager = new ManagersModel();
        $newManager->fname = $data['fname'];
        $newManager->lname = $data['lname'];
        $newManager->email = $data['email'];
        $newManager->roles = $data['manager_roles'];
        $newManager->status = $data['manager_status'];
        $newManager->remember_token = Str::random(60);
        if($newManager->save()){
            $data['token'] = $newManager->remember_token;
            Mail::to($data['email'])->send(new InviteMail($data));
            return Redirect::to('/admin/manager')->with('info','Your invitation is done successfully. Invitation email is sent soon.');;
        }
    }
}
