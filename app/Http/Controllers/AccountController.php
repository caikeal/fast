<?php

namespace App\Http\Controllers;

use App\SmsLib\SendTemplateSMS;
use App\SmsRecord;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function __construct(SendTemplateSMS $smsPhone)
    {
        $this->middleware('auth',['except' => ['sms']]);
        $this->smsPhone=$smsPhone;
    }

    public function showBindingForm()
    {
        $isFirst = \Auth::user()->is_first;
        if ($isFirst) {
            return redirect('index');
        }
        return view('home.binding');
    }

    public function sms($phone)
    {
        $is_phone=is_numeric($phone) && preg_match('/^13\d{9}$|^14\d{9}$|^15\d{9}$|^17\d{9}$|^18\d{9}$/',$phone);
        if(!$is_phone){
            $data=array(
                "status"=>0,
                "message"=>"手机号格式错误！",
            );
            return $data;
        }

        $now=Carbon::now()->subMinutes(2)->toDateTimeString();
        $is_exist=SmsRecord::where("phone","=",$phone)->where("created_at",">=",$now)->orderBy("created_at","desc")->first();
        if($is_exist){
            $data=array(
                "status"=>2,
                "message"=>"短信已发送！",
                "sms"=>$is_exist->code,
            );
            return $data;
        }

        $verification=rand(1,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $result=$this->smsPhone->sendTemplateSMS($phone,array($verification),"68199");
        if($result){
            $smsRecord=new SmsRecord();
            $smsRecord->phone=$phone;
            $smsRecord->code=$verification;
            if($smsRecord->save()) {
                $data = array(
                    "status" => 1,
                    "sms" => $verification,
                );
            }else{
                $data = array(
                    "status" => 0,
                    "message" => "发送失败请重新请求！",
                );
            }
            return $data;
        }else{
            $data=array(
                "status"=>0,
                "message"=>"发送失败请重新请求！",
            );
            return $data;
        }
    }

    public function binding(Requests\BindingRequest $request){
        $userId=\Auth::user()->id;
        $is_exist=SmsRecord::where("phone","=",$request->input('phone'))->where("code","=",$request->input('valid'))->count();
        if(!$is_exist){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "valid" => "验证码错误",
                ]);
        }

       $exist_user = User::where("phone","=",$request->input('phone'))->count();
        if($exist_user){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "phone" => "该用户已经存在",
                ]);
        }

        $password=bcrypt($request->input('password'));
        User::where("id","=",$userId)->update(["phone"=>$request->input('phone'),"is_first"=>1,"password"=>$password]);
        return redirect('index');
    }

    public function showRebindingForm(){
        return view('home.rebinding');
    }

    public function rebinding(Requests\RebindingRequest $request){
        $userId=\Auth::user()->id;
        $phone=\Auth::user()->phone;
        $is_exist1=SmsRecord::where("phone","=",$phone)->where("code","=",$request->input('valid'))->count();
        $is_exist2=SmsRecord::where("phone","=",$request->input('newPhone'))->where("code","=",$request->input('newValid'))->count();

        if(!$is_exist1||!$is_exist2){
            $datum=array();
            if(!$is_exist1){
                $datum['valid']="验证码错误";
            }
            if(!$is_exist2){
                $datum['newValid']="验证码错误";
            }
            return redirect()->back()
                ->withInput($request->only("newPhone"))
                ->withErrors($datum);
        }
        User::where("id","=",$userId)->update(["phone"=>$request->input('newPhone')]);
        return redirect('my');
    }
}
