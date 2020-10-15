<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization, X-Token, Token');
header('Access-Control-Expose-Headers: X-Token, Token');
header("Content-Type: application/json");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', 'Api\AuthController@login');

//Auth api
Route::post('/signupge', 'Api\AuthController@signupGE');
Route::post('/signupgpwd', 'Api\AuthController@signupGPwd');
Route::post('/signupgp', 'Api\AuthController@signupGP');
Route::post('/signupgce', 'Api\AuthController@signupGCe');
Route::post('/signupgcone', 'Api\AuthController@signupGConE');
Route::post('/signupgconp', 'Api\AuthController@signupGConP');
Route::post('/signupgprofile', 'Api\AuthController@signupGProfile');
Route::post('/signupgprofiledone', 'Api\AuthController@signupGProfileDone');
Route::post('/gGsignup', 'Api\AuthController@gGsignup');

Route::post('/signupc', 'Api\AuthController@signupC');
Route::post('/signupcc', 'Api\AuthController@signupCCon');

Route::post('/signuprec', 'Api\AuthController@signupREC');
Route::post('/signuprpc', 'Api\AuthController@signupRPC');
Route::post('/getfile', 'Api\AuthController@getfile');
Route::post('/profilepay', 'Api\AuthController@profilepay');
Route::post('/getdescription', 'Api\AuthController@getdescription');

Route::post('/createCandidate', 'Api\AuthController@createCandidate');
Route::post('/createOrder', 'Api\AuthController@createOrder');


//End Auth api

//Profile api
Route::post('/profileview', 'Api\ProfileController@Viewinfo');
Route::post('/profileupdate', 'Api\ProfileController@Updateinfo');
Route::post('/avatarupdate', 'Api\ProfileController@Avatarupdate');
Route::post('/coverimgupdate', 'Api\ProfileController@Coverimgupdate');
Route::post('/bioupdate', 'Api\ProfileController@Bioupdate');
Route::post('/emailupdate', 'Api\ProfileController@Emailupdate');
Route::post('/emailverified', 'Api\ProfileController@Emailverified');
Route::post('/emailresend', 'Api\ProfileController@Emailresend');
Route::post('/phoneupdate', 'Api\ProfileController@Phoneupdate');
Route::post('/phoneverified', 'Api\ProfileController@Phoneverified');
Route::post('/passwordupdate', 'Api\ProfileController@Passwordupdate');
Route::post('/priceupdate', 'Api\ProfileController@Priceupdate');
Route::post('/qualificationupdate', 'Api\ProfileController@qualificationUpdate');
Route::post('/chosenProfile', 'Api\ProfileController@chosenProfile');
Route::post('/verifytoken', 'Api\ProfileController@verifytoken');
Route::post('/viewschedule', 'Api\ProfileController@viewschedule');
Route::post('/setschedule', 'Api\ProfileController@setschedule');
Route::post('/setpayaccount', 'Api\ProfileController@setpayaccount');
Route::post('/paydetail', 'Api\ProfileController@paydetail');

//End Profile api

//Search Provider api
Route::post('/getcntPro', 'Api\SearchController@getcntPro');
Route::post('/searchcaregiver', 'Api\SearchController@searchcaregiver');
Route::post('/scheduledetails', 'Api\SearchController@scheduledetails');

//End Search Provider api

//Job api
Route::post('/chosenlist', 'Api\JobController@chosenlist');
Route::post('/setcost', 'Api\JobController@setCost');
//End Job api

//Interview api
Route::post('/interviewlist', 'Api\InterviewController@interviewlist');
Route::post('/confirminterview', 'Api\InterviewController@confirminterview');
Route::post('/acceptinterview', 'Api\InterviewController@acceptinterview');
Route::post('/interviewforclient', 'Api\InterviewController@interviewforclient');
Route::post('/interviewforprovider', 'Api\InterviewController@interviewforprovider');
Route::post('/createintroom', 'Api\InterviewController@createintroom');
Route::post('/cancelintbyclient', 'Api\InterviewController@cancelintbyclient');
Route::post('/cancelintbyprovider', 'Api\InterviewController@cancelintbyprovider');
Route::post('/payinterview', 'Api\InterviewController@payinterview');
Route::post('/checkinterview', 'Api\InterviewController@checkinterview');
Route::get('/canintreason', 'Api\InterviewController@canintreason');
Route::post('/gotoint', 'Api\InterviewController@gotoint');
Route::post('/leaveint', 'Api\InterviewController@leaveint');

//End Interview

//Notification api
Route::post('/checkunread', 'Api\NotificationController@checkunread');
Route::post('/viewnotification', 'Api\NotificationController@viewnotification');
Route::post('/readnotification', 'Api\NotificationController@readnotification');



//Job Api
Route::post('/createjob', 'Api\JobController@createjob');
Route::post('/paystep', 'Api\JobController@paystep');


Route::post('/incomingservice', 'Api\JobController@incomingservice');
Route::post('/ongoingservice', 'Api\JobController@ongoingservice');
Route::post('/finishedservice', 'Api\JobController@finishedservice');
Route::post('/canceledservice', 'Api\JobController@canceledservice');

Route::post('/incomingjob', 'Api\JobController@incomingjob');
Route::post('/ongoingjob', 'Api\JobController@ongoingjob');
Route::post('/previousjob', 'Api\JobController@previousjob');

Route::get('/cancelreason', 'Api\JobController@cancelreason');
Route::post('/canceljob', 'Api\JobController@canceljob');
Route::post('/rejectjob', 'Api\JobController@rejectjob');
Route::post('/acceptjob', 'Api\JobController@acceptjob');
Route::post('/jobdetail', 'Api\JobController@jobdetail');

Route::post('/requestrefund', 'Api\JobController@requestrefund');
Route::post('/viewreason', 'Api\JobController@viewreason');
Route::post('/review', 'Api\JobController@review');

//Transaction Api
Route::post('/translist', 'Api\JobController@translist');
Route::post('/transhistory', 'Api\JobController@transhistory');

//contact form
Route::post('/contact', 'Api\NotificationController@contact');

