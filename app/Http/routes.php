<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['namespace'=>'Admin','middleware' => ['web'],'prefix'=>'admin'], function () {
    Route::get('/','HomeController@index');
    Route::get('/login','AuthController@showLoginForm');
    Route::post('/login','AuthController@login');
    Route::get('/logout','AuthController@logout');
    Route::get('/index','HomeController@index');
    //模版模块
    Route::get('/base/{id}', 'SalaryBaseController@show');
    //薪资模块
    Route::get('/timeline','SalaryController@timeline');
    Route::post('/salary/base','SalaryController@base');
    Route::get('/salary/download','SalaryController@download');
    Route::post('/salary/upload', 'SalaryController@upload');
    Route::resource('/salary/category','SalaryCategoryController');
    //社保模块
    Route::get('/insurance','SalaryController@insurance');
    Route::post('/insurance/upload', 'InsuranceController@upload');
    //企业用户管理模块
    Route::get('/super','ManagerController@super');
    Route::put('/super/reset_password/{id}','ManagerController@reset');
    Route::resource('/manager','ManagerController');
    Route::resource('/account','AccountController');
    //前台用户管理模块
    Route::resource('/user','UserController');
    //人员管理模块
    Route::resource('/employ','EmployController');
    //企业任务模块
    Route::resource('/task','SalaryTaskController');
    Route::resource('/task_application','TaskApplicationController');
    Route::resource('/company','CompanyController');
    //理赔模块
    Route::resource('/compensation','CompensationController');
    Route::post('/compensation/upload', 'CompensationController@upload');
    //历史查询模块
    Route::get('/history/download', 'HistoryController@download');
    Route::post('/history/reupload', 'HistoryController@reupload');
    Route::resource('/history', 'HistoryController');
    //消息模块
    Route::get('/notify', 'NewsController@notify');
    Route::resource('/news', 'NewsController');
    //下属上传数据统计模块
    Route::resource('/underling', 'UnderlingController');
    //答疑解惑模块
    Route::resource('/answer', 'AnswerController');
    Route::get('/answer-info', 'AnswerController@info');
    //数据统计模块
    Route::get('/data-before-times', 'StatisticsController@visitLastTimes');
    Route::get('/user-before-times', 'StatisticsController@userLastTimes');
    Route::get('/data-now-times', 'StatisticsController@nowVisitTimes');
    Route::get('/user-now-times', 'StatisticsController@nowUserTimes');
    //角色分配模块
    Route::get('/affiliation', 'RoleController@getAffiliation');
    Route::get('/role-list', 'RoleController@showRoles');
    Route::post('/role-create', 'RoleController@addRoles');
    Route::post('/role-update', 'RoleController@updateRoles');
    Route::get('/permission-list', 'RoleController@allPermission');
    Route::get('/permission-own', 'RoleController@getPermission');
    Route::post('/permission-update', 'RoleController@updatePermission');
    Route::get('/manager-level-list', 'RoleController@initManagerList');
    Route::get('/manager-role-list', 'RoleController@getRole');
    Route::get('/manager-relation', 'RoleController@getManagerList');
    Route::post('/manager-role-save', 'RoleController@saveManagerRole');
    //系统消息模块
    Route::get('/system/info', 'InfoController@index');
    Route::post('/system/info/create', 'InfoController@sendSystemInfo');
    Route::post('/system/info/delete/{id}', 'InfoController@delete');
    Route::post('/system/info/close/{id}', 'InfoController@close');
    Route::post('/system/info/open/{id}', 'InfoController@open');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('/','HomeController@index');
    //登陆模块
    Route::get('/login','AuthController@showLoginForm');
    Route::post('/login','AuthController@login');
    Route::get('/logout','AuthController@logout');
    Route::get('/reset','AuthController@showResetForm');
    Route::post('/reset','AuthController@reset');
    //首页
    Route::get('/index','HomeController@index');
    Route::get('system/info', 'InfoController@index');
    //薪资模块
    Route::get('/salary','SalaryController@index');
    Route::post('/salary/details','SalaryController@detail');
    //社保模块
    Route::get('/insurance','SalaryController@insurance');
    Route::get('/insurance/progress', 'InsuranceController@index');
    Route::get('/insurance/specific/{id}', 'InsuranceController@specific');
    Route::post('/insurance/details', 'InsuranceController@detail');
    //账号绑定
    Route::get('/binding','AccountController@showBindingForm');
    Route::post('/binding','AccountController@binding');
    Route::get('/sms/{phone}','AccountController@sms');
    Route::get('/rebinding','AccountController@showRebindingForm');
    Route::post('/rebinding','AccountController@rebinding');
    //我的
    Route::get('/my','OwnController@showMyForm');
    Route::get('/info','OwnController@showMyForm');
    //理赔查询
    Route::get('/compensation/index','CompensationController@index');
    Route::get('/compensation/days','CompensationController@getWorkDay');
    Route::get('/compensation/details','CompensationController@specific');
    Route::post('/compensation/details','CompensationController@detail');
    //联系我们模块
    Route::get('/contactus', 'ContactController@index');
    //答疑解惑模块
    Route::resource('/question', 'QuestionController');
    Route::get('question-my', 'QuestionController@myQuestion');
    Route::get('question-new', 'QuestionController@newQuestion');
    Route::get('question-search', 'QuestionController@searchQuestion');
});
