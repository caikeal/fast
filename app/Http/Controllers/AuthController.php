<?php

namespace App\Http\Controllers;

use App\SmsRecord;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout','reset','showResetForm']]);
    }

    public function showLoginForm(){
        return view('home.login');
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 高频访问1分钟限制
     * 处理登录
     */
    public function login(LoginRequest $request){
        $throttles=$this->isUsingThrottlesLoginsTrait();
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $account=$request->input('account');
        $password=$request->input('password');
        //手机号和身份证登录区分
        if(mb_strlen(trim($account))==11 && is_numeric($account)) {
            $credentials = ["phone" => $account, "password" => $password];
        }else{
            $userInfo = User::where("id_card", "=", $account)->first();
            if ($userInfo) {
                if ($userInfo['is_first']==1) {
                    return redirect()->back()
                        ->withInput($request->only("account"))
                        ->withErrors([
                            "account" => "请使用手机号登录！",
                        ]);
                } else {
                    $credentials = ["id_card" => $account, "password" => $password];
                }
            } else {
                $credentials = ["phone" => $account, "password" => $password];
            }
        }

        if (Auth::attempt($credentials)) {
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }
            if(Auth::user()->is_first==1) {
                return redirect('index');
            }else{
                return redirect('binding');
            }
        }

        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return redirect()->back()
            ->withInput($request->only("account"))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 退出登录
     */
    public function logout(){
        Auth::logout();
        return redirect('login');
    }

    public function showResetForm(){
        return view('home.reset');
    }

    public function reset(Requests\ResetRequest $request){
        $is_exist=SmsRecord::where("phone","=",$request->input('phone'))->where("code","=",$request->input('valid'))->count();
        if(!$is_exist){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "valid" => "验证码错误",
                ]);
        }
        $exist_user = User::where("phone","=",$request->input('phone'))->count();
        if(!$exist_user){
            return redirect()->back()
                ->withInput($request->only("phone"))
                ->withErrors([
                    "phone" => "不存在该用户",
                ]);
        }
        $password=bcrypt($request->input('password'));
        if(\Auth::check()){
            $userId=\Auth::user()->id;
            User::where("id","=",$userId)->update(["password"=>$password]);
            \Auth::logout();
            return redirect('login');
        }else{
            User::where("phone","=",$request->input("phone"))->update(["password"=>$password]);
            return redirect('login');
        }
    }

    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'account';
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return '账号密码错误！';
    }
}
