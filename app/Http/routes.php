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
    Route::get('/timeline','SalaryController@timeline');
    Route::post('/salary/base','SalaryController@base');
    Route::get('/salary/download','SalaryController@download');
    Route::post('/salary/upload', 'SalaryController@upload');
    Route::resource('/salary/category','SalaryCategoryController');
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
    //薪资模块
    Route::get('/salary','SalaryController@index');
    Route::post('/salary/details','SalaryController@detail');
    //账号绑定
    Route::get('/binding','AccountController@showBindingForm');
    Route::post('/binding','AccountController@binding');
    Route::get('/sms/{phone}','AccountController@sms');
    Route::get('/rebinding','AccountController@showRebindingForm');
    Route::post('/rebinding','AccountController@rebinding');
    //我的
    Route::get('/my','OwnController@showMyForm');
    Route::get('/info','OwnController@showMyForm');
});
//Route::group(['middleware' => 'web'], function () {
//    Route::auth();
//    Route::get('/home', 'HomeController@index');
//});
