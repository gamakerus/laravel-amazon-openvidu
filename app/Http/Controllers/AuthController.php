<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session;


use App\ManagersModel;
use App\SquestionModel;
use DB;
date_default_timezone_set('America/Chicago');
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
class AuthController extends Controller
{
    public function index()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }
    public function loginForm(Request $request){
        request()->validate([
            'fname' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            ]);
        $data = $request->all();
        $result = ManagersModel::where('email',$data['email'])->where('fname',$data['fname'])->first();
        if(isset($result['password']) && Hash::check($data['password'], $result['password'])){
            if($result['status'] == 0)
                return Redirect::to("/admin")->with('info','Your credential is under review now. Please try it later');
            else{
                
                $newToken = Str::random(60);
                ManagersModel::where('email',$data['email'])->where('fname',$data['fname'])->update(['remember_token' => $newToken]);
                session()->put('remember_token', $newToken);
                session()->put('roles', $result['roles']);
                $questionlist = DB::table('squestion_list_models')->join('squestion', 'squestion_list_models.id', '=', 'squestion.questionid')
                ->select('squestion_list_models.id','squestion_list_models.question')->where('userid',$result['id'])->get();
                $randomValue = rand(1,count($questionlist));
                if(isset($questionlist[$randomValue-1]))
                    return view('auth/squestion',['question'=>$questionlist[$randomValue-1]]);
                else
                    return Redirect::to("/admin")->with('info','Please wait for setting your security question.');
            }
        }
        else{
            return Redirect::to("/admin")->with('info','Credential is invalid. Please try it again.');
        }
    }
    public function registerForm(Request $request){
        request()->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:managers',
            'password' => 'required|min:8',
        ]);

        $data = $request->all();

        $check = $this->create($data);
        return Redirect::to("/admin")->with('info','Welcome to register successfully. Your credential is under review.');
    }
    public function squestionForm(Request $request){
        request()->validate([
            'answer' => 'required',
            'questionid' => 'required',
            'remember_token' => 'required',
            ]);
        $data = $request->all();
        $id = ManagersModel::where('remember_token',$data['remember_token'])->first();
        $result = SquestionModel::where('userid',$id['id'])->where('answer',$data['answer'])->where('questionid',$data['questionid'])->first();
        if($result){
            return Redirect::to("/admin/dashboard");
        }
        return Redirect('/admin');
    }
    public function create(array $data)
    {
      return User::create([
        'fname' => $data['fname'],
        'lname' => $data['lname'],
        'email' => $data['email'],
        'roles' => 0,
        'status' => 0,
        'remember_token' => Str::random(60),
        'password' => Hash::make($data['password'])
      ]);
    }
    public function logout() {
        Session::flush();
        return Redirect('/admin');
    }
    public function ResetPwd($name){
        return view('auth/resetPwd',['token'=>$name]);
    }
    public function resetPwdForm(Request $request){
        request()->validate([
            'token' => 'required',
            'password' => 'required',
            ]);
        $data = $request->all();
        $result = ManagersModel::where('remember_token',$data['token'])->update(['password' => Hash::make($data['password'])]);
        if($result)
            return Redirect('/admin');
    }
}
