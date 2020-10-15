<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/caregiver', 'HomeController@index');
Route::get('/nursing', 'HomeController@index');
Route::get('/therapy', 'HomeController@index');
Route::get('/dashboard', 'HomeController@index');
Route::get('/search/caregivers', 'HomeController@index');
Route::get('/search/caregivers/{page}', 'HomeController@index');
Route::get('/password_reset/{name}', 'HomeController@index');
Route::get('/notification', 'HomeController@index');
Route::get('/notification/{page}', 'HomeController@index');
Route::get('/interview-rooms/{name}', 'HomeController@index');

Route::get('/client/interviews', 'HomeController@index');
Route::get('/client/edit/{name}', 'HomeController@index');
Route::get('/client/incoming', 'HomeController@index');
Route::get('/client/ongoing', 'HomeController@index');
Route::get('/client/previous', 'HomeController@index');
Route::get('/client/previous/{page}', 'HomeController@index');
Route::get('/client/transactions', 'HomeController@index');
Route::get('/client/transactions/{page}', 'HomeController@index');
Route::get('/client/refunds', 'HomeController@index');
Route::get('/client/refunds/{page}', 'HomeController@index');

Route::get('/provider/profile', 'HomeController@index');
Route::get('/profile/{name}', 'HomeController@index');
Route::get('/provider/edit/{name}', 'HomeController@index');
Route::get('/provider/incoming', 'HomeController@index');
Route::get('/provider/ongoing', 'HomeController@index');
Route::get('/provider/previous', 'HomeController@index');
Route::get('/provider/previous/{page}', 'HomeController@index');
Route::get('/provider/interviews', 'HomeController@index');
Route::get('/provider/schedules', 'HomeController@index');
Route::get('/provider/services', 'HomeController@index');
Route::get('/provider/transactions', 'HomeController@index');
Route::get('/provider/transactions/{page}', 'HomeController@index');


Route::get('/admin', 'AuthController@index');
Route::get('/admin/register', 'AuthController@register');
Route::get('/admin/resetPassword/{name}', 'AuthController@ResetPwd');

Route::post('/admin/loginForm', 'AuthController@loginForm');
Route::post('/admin/registerForm', 'AuthController@registerForm');
Route::post('/admin/resetPwdForm', 'AuthController@resetPwdForm');

Route::post('/admin/squestionForm', 'AuthController@squestionForm');
Route::get('/admin/logout', 'AuthController@logout')->name('logout');

Route::get('/admin/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/admin/usermanagement', 'UserManageController@index')->name('usermanagement');
Route::get('/admin/providermanagement', 'ProviderManageController@index')->name('providermanagement');
Route::get('/admin/jobmanagement', 'JobManageController@index')->name('jobmanagement');
Route::get('/admin/transaction', 'TransactionController@index')->name('transaction');
Route::get('/admin/cancelrefund', 'CancelRefundController@index')->name('cancelrefund');
Route::get('/admin/notification', 'NotificationController@index')->name('notification');
Route::get('/admin/message', 'MessageController@index')->name('message');
Route::get('/admin/email', 'EmailController@index')->name('email');
Route::get('/admin/manager', 'ManagerController@index')->name('manager');
Route::get('/admin/squestion', 'SettingController@squestion')->name('squestion');
Route::get('/admin/services', 'SettingController@services')->name('services');
Route::get('/admin/ptrelation', 'SettingController@ptrelation')->name('ptrelation');
Route::get('/admin/language', 'SettingController@language')->name('language');
Route::get('/admin/exspec', 'SettingController@exspec')->name('exspec');
Route::get('/admin/licenses', 'SettingController@licenses')->name('licenses');
Route::get('/admin/schedule', 'SettingController@schedule')->name('schedule');
Route::get('/admin/osettings', 'SettingController@osettings')->name('osettings');


//security question manage
Route::post('/admin/squestionaddForm', 'SettingController@squestionaddForm');
Route::post('/admin/chosenquestion', 'SettingController@chosenquestion');
Route::post('/admin/squestioneditForm', 'SettingController@squestioneditForm');
Route::delete('/admin/deletequestion', 'SettingController@deletequestion');

//admin users manage
Route::post('/admin/chosenmanager', 'ManagerController@chosenmanager');
Route::post('/admin/manageraddForm', 'ManagerController@manageraddForm');
Route::post('/admin/managereditForm', 'ManagerController@managereditForm');
Route::delete('/admin/deletemanager', 'ManagerController@deletemanager');
Route::post('/admin/addsqanswer', 'ManagerController@addsqanswer');
Route::post('/admin/replyrequest', 'DashboardController@replyrequest');


//users manage
Route::delete('/admin/deleteuser', 'UserManageController@deleteuser');
Route::post('/admin/banuser', 'UserManageController@banuser');
Route::post('/admin/unbanuser', 'UserManageController@unbanuser');
Route::post('/admin/chosenuser', 'UserManageController@chosenuser');
Route::post('/admin/clientaddForm', 'UserManageController@clientaddForm');
Route::post('/admin/viewrequestservice', 'UserManageController@viewrequestservice');
Route::post('/admin/viewallrequest', 'UserManageController@viewallrequest');
Route::post('/admin/resetpwd', 'UserManageController@resetpwd');
Route::get('/admin/expertCSVforusers', 'UserManageController@expertCSV');
Route::get('/admin/downloadPDFforusers', 'UserManageController@downloadPDFforusers');


//providers manage
Route::delete('/admin/deleteprovider', 'ProviderManageController@deleteprovider');
Route::post('/admin/unbanprovider', 'ProviderManageController@unbanprovider');
Route::post('/admin/banprovider', 'ProviderManageController@banprovider');
Route::post('/admin/chosenprovider', 'ProviderManageController@chosenprovider');
Route::post('/admin/getchosenactivities', 'ProviderManageController@getchosenactivities');
Route::post('/admin/provideraddForm', 'ProviderManageController@provideraddForm');
Route::post('/admin/chosenserviceProviders', 'ProviderManageController@chosenserviceProviders');
Route::get('/admin/expertCSV', 'ProviderManageController@expertCSV');
Route::get('/admin/downloadPDF', 'ProviderManageController@downloadPDF');
Route::post('/admin/viewjobdetail', 'ProviderManageController@viewjobdetail');
Route::post('/admin/viewalljob', 'ProviderManageController@viewalljob');

//Cancellation manage
Route::post('/admin/addreason', 'CancelRefundController@addreason');
Route::post('/admin/updatereason', 'CancelRefundController@updatereason');
Route::delete('/admin/deletereason', 'CancelRefundController@deletereason');

//End Cancellation manage

//service manage
Route::post('/admin/serviceaddForm', 'SettingController@serviceaddForm');
Route::post('/admin/chosenservice', 'SettingController@chosenservice');
Route::post('/admin/serviceeditForm', 'SettingController@serviceeditForm');
Route::delete('/admin/deleteservice', 'SettingController@deleteservice');


//patient relation
Route::post('/admin/ptrelationaddForm', 'SettingController@ptrelationaddForm');
Route::post('/admin/chosenptrelation', 'SettingController@chosenptrelation');
Route::post('/admin/ptrelationeditForm', 'SettingController@ptrelationeditForm');
Route::delete('/admin/deleteptrelation', 'SettingController@deleteptrelation');


//language
Route::post('/admin/languageaddForm', 'SettingController@languageaddForm');
Route::post('/admin/chosenlanguage', 'SettingController@chosenlanguage');
Route::post('/admin/languageeditForm', 'SettingController@languageeditForm');
Route::delete('/admin/deletelanguage', 'SettingController@deletelanguage');


//Expertise & Specialization
Route::post('/admin/exspecaddForm', 'SettingController@exspecaddForm');
Route::post('/admin/chosenexspec', 'SettingController@chosenexspec');
Route::post('/admin/exspeceditForm', 'SettingController@exspeceditForm');
Route::delete('/admin/deleteexspec', 'SettingController@deleteexspec');

//License
Route::post('/admin/licenseaddForm', 'SettingController@licenseaddForm');
Route::post('/admin/chosenlicense', 'SettingController@chosenlicense');
Route::post('/admin/licenseeditForm', 'SettingController@licenseeditForm');
Route::delete('/admin/deletelicense', 'SettingController@deletelicense');

//Service Duration
Route::post('/admin/sdurationaddForm', 'SettingController@sdurationaddForm');
Route::post('/admin/chosensduration', 'SettingController@chosensduration');
Route::post('/admin/sdurationeditForm', 'SettingController@sdurationeditForm');
Route::delete('/admin/deletesduration', 'SettingController@deletesduration');

//Day to week
Route::post('/admin/daytoweekaddForm', 'SettingController@daytoweekaddForm');
Route::post('/admin/chosendaytoweek', 'SettingController@chosendaytoweek');
Route::post('/admin/daytoweekeditForm', 'SettingController@daytoweekeditForm');
Route::delete('/admin/deletedaytoweek', 'SettingController@deletedaytoweek');

//Time to day
Route::post('/admin/timetodayaddForm', 'SettingController@timetodayaddForm');
Route::post('/admin/chosentimetoday', 'SettingController@chosentimetoday');
Route::post('/admin/timetodayeditForm', 'SettingController@timetodayeditForm');
Route::delete('/admin/deletetimetoday', 'SettingController@deletetimetoday');

//Other Settings
Route::post('/admin/setRadius', 'SettingController@setRadius');
Route::post('/admin/updateflexdesc', 'SettingController@updateflexdesc');
Route::post('/admin/updatedirectdesc', 'SettingController@updatedirectdesc');
Route::post('/admin/updatecoverimg', 'SettingController@updatecoverimg');

//Notification
Route::post('/admin/pushnotification', 'NotificationController@pushnotification');
Route::delete('/admin/deletenotification', 'NotificationController@deletenotification');
Route::post('/admin/filterviewfornotification', 'NotificationController@filterviewfornotification');
Route::post('/admin/chosennotification', 'NotificationController@chosennotification');
Route::get('/admin/expertCSVfornot', 'NotificationController@expertCSV');
Route::get('/admin/downloadPDFfornot', 'NotificationController@downloadPDF');



//Message
Route::post('/admin/pushmessage', 'MessageController@pushmessage');
Route::post('/admin/filterviewformessage', 'MessageController@filterviewformessage');
Route::delete('/admin/deletemessage', 'MessageController@deletemessage');
Route::post('/admin/chosenmessage', 'MessageController@chosenmessage');
Route::get('/admin/expertCSVformsg', 'MessageController@expertCSV');
Route::get('/admin/downloadPDFformsg', 'MessageController@downloadPDF');

//Email
Route::post('/admin/pushemail', 'EmailController@pushemail');
Route::delete('/admin/deleteemail', 'EmailController@deleteemail');
Route::post('/admin/chosenemail', 'EmailController@chosenemail');
Route::post('/admin/filterviewforemail', 'EmailController@filterviewforemail');
Route::get('/admin/expertCSVforemail', 'EmailController@expertCSV');
Route::get('/admin/downloadPDFforemail', 'EmailController@downloadPDF');


//Job
Route::get('/admin/expertCSVforjob', 'JobManageController@expertCSV');
Route::get('/admin/downloadPDFforjob', 'JobManageController@downloadPDF');
Route::post('/admin/chosenjob', 'JobManageController@chosenjob');
Route::delete('/admin/deletejob', 'JobManageController@deletejob');

//Transaction
Route::get('/admin/expertCSVfortrans', 'TransactionController@expertCSV');
Route::get('/admin/downloadPDFfortrans', 'TransactionController@downloadPDF');
Route::delete('/admin/deletetransaction', 'TransactionController@deletetransaction');


//CanRef 
Route::get('/admin/expertCSVforcanref', 'CancelRefundController@expertCSV');
Route::get('/admin/downloadPDFforcanref', 'CancelRefundController@downloadPDF');
Route::post('/admin/chosencanref', 'CancelRefundController@chosencanref');
Route::delete('/admin/deletecanref', 'CancelRefundController@deletecanref');
Route::post('/admin/filterviewforcanref', 'CancelRefundController@filterviewforcanref');
Route::post('/admin/refundaction', 'CancelRefundController@refundaction');

